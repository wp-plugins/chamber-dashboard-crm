<?php

// Add Shortcode to display people
function cdcrm_people_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'category' => '',
			'display' => 'description, image', // options: description, image (featured image), phone, email, address, title, prefix, suffix
			'orderby' => 'rand', // options: title, rand, menu_order
			'format' => 'list', // options: list, grid2, grid3, grid4
		), $atts )
	);

	// We have to have a category
	if( '' == $category ) {
		$output = __( 'You must select a category to display.  See the <a href="https://chamberdashboard.com/professional-services-support/documentation/" target="_blank">documentation</a> for examples.', 'cdcrm' ); 
	}

	// Enqueue stylesheet if the display format is columns instead of list
	wp_enqueue_style( 'cdash-business-directory', plugin_dir_url( 'cdash-business-directory.php' ) . 'chamber-dashboard-business-directory/css/cdash-business-directory.css' );
	if($format !== 'list') {
		wp_enqueue_script( 'cdash-business-directory', plugin_dir_url( 'cdash-business-directory/php' ) . 'chamber-dashboard-business-directory/js/cdash-business-directory.js' );
	}

	// If user wants to display stuff other than the default, turn their display options into an array for parsing later
	if($display !== '') {
  		$displayopts = explode( ", ", $display);
  	}

	$args = array( 
		'post_type' => 'person',
	    'tax_query' => array( 
	      array(
	        'taxonomy' => 'people_category',
	        'field' => 'slug',  
	        'terms' => $category, 
	        'include_children' => false,
	        'operator' => 'IN' 
	      ),
	    ),
	    'posts_per_page' => -1,
	    'order' => 'ASC',  
	    'orderby' => $orderby, 											 
	);
	
	$people = new WP_Query( $args );
	
	// The Loop
	if ( $people->have_posts() ) {
		$output = '<div id="cdcrm-people" class="' . $format . '">';
			while ( $people->have_posts() ) : $people->the_post();
				global $person_metabox;
				$meta = $person_metabox->the_meta();
				$output .= '<div class="cdcrm-person">';
					if( in_array( 'image', $displayopts ) && has_post_thumbnail() ) {
						// display featured image
						$output .= '<div class="cdcrm-person-image">' . get_the_post_thumbnail() . '</div>';
					}
					// display the name
					$name = '';
					if( in_array( 'prefix', $displayopts ) && isset( $meta[ 'prefix'] ) && '' !== $meta['prefix'] ) {
						$name = $meta['prefix'] . '&nbsp';
					}
					$name .= get_the_title();
					if( in_array( 'suffix', $displayopts ) && isset( $meta[ 'suffix'] ) && '' !== $meta['suffix'] ) {
						$name .= ',&nbsp;' . $meta['suffix'];
					}
					$output .= '<h3 class="cdcrm-name">' . $name . '</h3>';
					if( in_array( 'title', $displayopts ) && isset( $meta[ 'title'] ) && '' !== $meta['title'] ) {
						$output .= '<p class="cdcrm-title">' . $meta['title'] . '</p>';
					}
					if( in_array( 'description', $displayopts ) ) {
						// display description
						$output .= '<div class="cdcrm-person-description">' . get_the_content() . '</div>';
					}
					if( in_array( 'address', $displayopts ) ) {
						// display address
						$output .= '<p class="cdcrm-address">';
						if( isset( $meta['address'] ) && '' !== $meta['address'] ) {
							$output .= $meta['address'] . '<br />';
						}
						if( isset( $meta['city'] ) && '' !== $meta['city'] ) {
							$output .= $meta['city'] . ',&nbsp;';
						}
						if( isset( $meta['state'] ) && '' !== $meta['state'] ) {
							$output .= $meta['state'] . '&nbsp;';
						}
						if( isset( $meta['zip'] ) && '' !== $meta['zip'] ) {
							$output .= $meta['zip'];
						}
						$output .= '</p>';
					}
					if( in_array( 'phone', $displayopts ) && isset( $meta['phone'] ) )  {
						// display phone
						$output .= cdash_display_phone_numbers( $meta['phone'] );
					}
					if( in_array( 'email', $displayopts ) && isset( $meta['email'] ) ) {
						// display email
						$output .= cdash_display_email_addresses( $meta['email'] );
					}
				$output .= '</div>';
			endwhile;
		$output .= '</div>';
	} else {
		$output = __( 'No people found.', 'cdcrm' );
	}
	
	// Reset Post Data
	wp_reset_postdata();
	
	
	
	

	return $output;

}
add_shortcode( 'chamber_dashboard_people', 'cdcrm_people_shortcode' );

?>