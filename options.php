<?php
/* Options Page for Chamber Dashboard CRM */

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'cdcrm_delete_plugin_options')
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function cdcrm_delete_plugin_options() {
	delete_option('cdcrm_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'cdcrm_add_defaults')
// ------------------------------------------------------------------------------

// Define default option settings
function cdcrm_add_defaults() {
	$tmp = get_option('cdcrm_options');
    if(!is_array($tmp)) {
		delete_option('cdcrm_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	
						"person_phone_type" => "Work, Home, Cell",
			 			"person_email_type" => "Work, Personal",
			 			"person_business_roles" => "Owner, Manager, Employee, Accounting",
		);
		update_option('cdcrm_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'cdcrm_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function cdcrm_init(){
	register_setting( 'cdcrm_plugin_options', 'cdcrm_options', 'cdcrm_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'cdcrm_add_options_page');
// ------------------------------------------------------------------------------

// Add menu page
function cdcrm_add_options_page() {
	add_submenu_page( '/chamber-dashboard-business-directory/options.php', __('CRM Options', 'cdcrm'), __('CRM Options', 'cdcrm'), 'manage_options', 'cdash-crm', 'cdcrm_render_form' );
}


// ------------------------------------------------------------------------------
// Register Settings
// ------------------------------------------------------------------------------

add_action( 'admin_init', 'cdcrm_settings_init' );

function cdcrm_settings_init() {
	register_setting( 'cdcrm_settings_page', 'cdcrm_options' );
	register_setting( 'cdcrm_import_page', 'cdcrm_options' );

	add_settings_section(
		'cdcrm_settings_page_section', 
		__( 'Chamber Dashboard CRM Settings', 'cdcrm' ), 
		'cdcrm_settings_section_callback', 
		'cdcrm_settings_page'
	);

	add_settings_field( 
		'person_phone_type', 
		__( 'Phone Number Types', 'cdcrm' ), 
		'cdcrm_person_phone_type_render', 
		'cdcrm_settings_page', 
		'cdcrm_settings_page_section',
		array( 
			__( 'When you enter a phone number for a person, you can choose what type of phone number it is.  The default options are "Work, Home, Cell".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want main and/or office and/or cell, you will need to enter them.)', 'cdcrm')  
		) 
	);

	add_settings_field( 
		'person_email_type', 
		__( 'Email Types', 'cdcrm' ), 
		'cdcrm_person_email_type_render', 
		'cdcrm_settings_page', 
		'cdcrm_settings_page_section',
		array( 
			__( 'When you enter an email address for a business, you can choose what type of email address it is.  The default options are "Work, Personal".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want main and/or sales and/or accounting and/or HR, you will need to enter them.)', 'cdcrm')  
		) 
	);

	add_settings_field( 
		'person_business_roles', 
		__( 'Business Roles', 'cdcrm' ), 
		'cdcrm_person_business_roles_render', 
		'cdcrm_settings_page', 
		'cdcrm_settings_page_section',
		array( 
			__( 'You can connect people to businesses, and describe the person\'s role in that business. The default options are "Owner, Manager, Employee, Accounting". To change these options, enter a comma-separated list here. (Note: your entry will over-ride the default, so if you still want owner and/or manager and/or employee, you will need to enter them.', 'cdcrm')  
		) 
	);

	add_settings_field( 
		'person_display', 
		__( 'Display Contacts', 'cdcrm' ), 
		'cdcrm_person_display_render', 
		'cdcrm_settings_page', 
		'cdcrm_settings_page_section',
		array( 
			__( 'When you connect a person to a business, you can choose to display that person\'s contact information in the business directory by checking the box next to "Display."  Select where you want that person\'s information to display.', 'cdcrm')  
		) 
	);

	add_settings_field( 
		'person_display_fields', 
		__( 'Fields to Display', 'cdcrm' ), 
		'cdcrm_person_display_fields_render', 
		'cdcrm_settings_page', 
		'cdcrm_settings_page_section',
		array( 
			__( 'If you want to display contacts, select what contact information to display.', 'cdcrm')  
		) 
	);

	add_settings_field( 
		'person_custom', 
		__( 'Custom Fields', 'cdcrm' ), 
		'cdcrm_person_custom_render', 
		'cdcrm_settings_page', 
		'cdcrm_settings_page_section',
		array( 
			__( 'If you need to store additional information about people, you can create custom fields here.', 'cdcrm')  
		) 
	);

	// import tab
	add_settings_section(
		'cdcrm_import_page_section', 
		__( 'Import', 'cdcrm' ), 
		'cdcrm_import_section_callback', 
		'cdcrm_import_page'
	);

}

function cdcrm_person_phone_type_render( $args ) { 

	$options = get_option( 'cdcrm_options' );
	?>
	<input type='text' name='cdcrm_options[person_phone_type]' value='<?php echo $options['person_phone_type']; ?>'>
	<br /><span class="description"><?php echo $args[0]; ?></span>
	<?php

}

function cdcrm_person_email_type_render( $args ) { 

	$options = get_option( 'cdcrm_options' );
	?>
	<input type='text' name='cdcrm_options[person_email_type]' value='<?php echo $options['person_email_type']; ?>'>
	<br /><span class="description"><?php echo $args[0]; ?></span>
	<?php

}

function cdcrm_person_business_roles_render( $args ) { 

	$options = get_option( 'cdcrm_options' );
	?>
	<input type='text' name='cdcrm_options[person_business_roles]' value='<?php echo $options['person_business_roles']; ?>'>
	<br /><span class="description"><?php echo $args[0]; ?></span>
	<?php

}

function cdcrm_person_display_render( $args ) { 

	$options = get_option( 'cdcrm_options' );
	?>
	<span class="description"><?php echo $args[0]; ?></span><br />
	<?php $choices = array(
		'single' => __( 'Single Business View', 'cdcrm' ),
		'category' => __( 'Category/Membership Level View', 'cdcrm' ),
		'shortcode' => __( 'Shortcode View', 'cdcrm' ),
	);
	foreach( $choices as $value => $description ) {
		$checked = false;
		if( isset( $options['person_display'] ) && in_array( $value, $options['person_display'] ) ) {
			$checked = true;
		} ?>
		<input type='checkbox' name='cdcrm_options[person_display][<?php echo $value; ?>]' id="<?php echo $value; ?>" value='<?php echo $value; ?>' <?php checked( $checked, true, true ); ?>><label for="<?php echo $value; ?>"><?php echo $description; ?></label><br />
	<?php }

}

function cdcrm_person_display_fields_render( $args ) { 

	$options = get_option( 'cdcrm_options' );
	?>
	<span class="description"><?php echo $args[0]; ?></span><br />
	<?php $choices = array(
		'title' => __( 'Title', 'cdcrm' ),
		'prefix' => __( 'Prefix', 'cdcrm' ),
		'suffix' => __( 'Suffix', 'cdcrm' ),
		'role' => __( 'Role in the business', 'cdcrm' ),
		'phone' => __( 'Phone Number(s)', 'cdcrm' ),
		'email' => __( 'Email Address(es)', 'cdcrm' ),
		'address' => __( 'Mailing Address', 'cdcrm' )
	);
	foreach( $choices as $value => $description ) {
		$checked = false;
		if( isset( $options['person_display_fields'] ) && in_array( $value, $options['person_display_fields'] ) ) {
			$checked = true;
		} ?>
		<input type='checkbox' name='cdcrm_options[person_display_fields][<?php echo $value; ?>]' id="<?php echo $value; ?>" value='<?php echo $value; ?>' <?php checked( $checked, true, true ); ?>><label for="<?php echo $value; ?>"><?php echo $description; ?></label><br />
	<?php }

}


function cdcrm_person_custom_render( $args ) { 

	$options = get_option( 'cdcrm_options' );
	?>
	<span class="description"><?php echo $args[0]; ?></span>
	<?php if(!empty($options['person_custom'])) {
			$customfields = $options['person_custom'];
			$i = 1;
			foreach($customfields as $field) { ?>
				<div class="repeating" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
					<p><strong><?php _e('Custom Field Name', 'cdcrm'); ?></strong></p>
						<input type="text" size="30" name="cdcrm_options[person_custom][<?php echo $i; ?>][name]" value="<?php echo $field['name']; ?>" />
					<p><strong><?php _e('Custom Field Type', 'cdcrm'); ?></strong></p>	
						<select name='cdcrm_options[person_custom][<?php echo $i; ?>][type]'>
							<option value=''></option>
							<option value='text' <?php selected('text', $field['type']); ?>><?php _e('Short Text Field', 'cdcrm'); ?></option>
							<option value='textarea' <?php selected('textarea', $field['type']); ?>><?php _e('Multi-line Text Area', 'cdcrm'); ?></option>
						</select>
					<p><a href="#" class="repeat"><?php _e('Add Another', 'cdcrm'); ?></a></p>
				</div>
				<?php $i++;
			}
		} else { ?>
			<div class="repeating" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
				<p><strong><?php _e('Custom Field Name', 'cdcrm'); ?></strong></p>
					<input type="text" size="30" name="cdcrm_options[person_custom][1][name]" value="<?php if(isset($options['person_custom'][1]['name'])) { echo $options['person_custom'][1]['name']; } ?>" />
				<p><strong><?php _e('Custom Field Type'); ?></strong></p>	
					<select name='cdcrm_options[person_custom][1][type]'>
						<option value=''></option>
						<option value='text'><?php _e('Short Text Field', 'cdcrm'); ?></option>
						<option value='textarea'><?php _e('Multi-line Text Area', 'cdcrm'); ?></option>
					</select>
				<p><a href="#" class="repeat"><?php _e('Add Another', 'cdcrm'); ?></a></p>
			</div>
		<?php }

}

function cdcrm_settings_section_callback() {

}

function cdcrm_import_section_callback() {
	do_action( 'cdcrm_import_page' );
}

add_action( 'cdcrm_import_page', 'cdcrm_import_promo', 10 ); 

function cdcrm_import_promo() { ?>
	<p><?php _e( 'Import functionality is coming soon!  Visit <a href="https://chamberdashboard.com" target="_blank">Chamber Dashboard</a> to sign up for our newsletter and be the first to know when the importing plugin is released!', 'cdash-crm' ); ?></p>
<?php }


// Render the Plugin options form
function cdcrm_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Chamber Dashboard CRM Settings', 'cdcrm'); ?></h2>
		<?php settings_errors(); ?>

		<?php  
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'cdcrm_settings_page';  
        ?> 

        <h2 class="nav-tab-wrapper">  
            <a href="?page=cdash-crm&tab=cdcrm_settings_page" class="nav-tab <?php echo $active_tab == 'cdcrm_settings_page' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'cdcrm' ); ?></a>  
            <a href="?page=cdash-crm&tab=cdcrm_import_page" class="nav-tab <?php echo $active_tab == 'cdcrm_import_page' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Import', 'cdcrm' ); ?></a>  
        </h2> 


		<div id="main" style="width: 70%; min-width: 350px; float: left;">
			<!-- Beginning of the Plugin Options Form -->
			
			<?php 
            if( $active_tab == 'cdcrm_settings_page' ) {  ?>
	            <form method="post" action="options.php">
	                <?php settings_fields( 'cdcrm_settings_page' );
					do_settings_sections( 'cdcrm_settings_page' ); 
					submit_button(); ?>
				</form>
				<script type="text/javascript">
					// Add a new repeating section
					var attrs = ['for', 'id', 'name'];
					function resetAttributeNames(section) { 
					    var tags = section.find('input, label, select'), idx = section.index();
					    tags.each(function() {
					      var $this = jQuery(this);
					      jQuery.each(attrs, function(i, attr) {
					        var attr_val = $this.attr(attr);
					        if (attr_val) {
					            $this.attr(attr, attr_val.replace(/\[person_custom\]\[\d+\]\[/, '\[person_custom\]\['+(idx + 1)+'\]\['))					        }
					      })
					    })
					}
					                   
					jQuery('.repeat').click(function(e){
					        e.preventDefault();
					        var lastRepeatingGroup = jQuery('.repeating').last();
					        var cloned = lastRepeatingGroup.clone(true)  
					        cloned.insertAfter(lastRepeatingGroup);
					        cloned.find("input").val("");
					        cloned.find("select").val("");
					        cloned.find("input:radio").attr("checked", false);
					        resetAttributeNames(cloned)
					    });
				</script>
            <?php } else if( $active_tab == 'cdcrm_import_page' ) {
                settings_fields( 'cdcrm_import_page' );
                do_settings_sections( 'cdcrm_import_page' ); 

            }
			
			?>

			
		</div><!-- #main -->
		<?php include( plugin_dir_path( __FILE__ ) . '/includes/aside.php' ); ?>
	</div>

	<?php	
}



// Sanitize and validate input. 
function cdcrm_validate_options($input) {
	// $msg = "<pre>" . print_r($input, true) . "</pre>";
	// wp_die($msg);
	if( isset( $input['person_phone_type'] ) ) {
    	$input['person_phone_type'] = wp_filter_nohtml_kses( $input['person_phone_type'] );
    }
    if( isset( $input['person_email_type'] ) ) {
    	$input['person_email_type'] = wp_filter_nohtml_kses( $input['person_email_type'] );
    }
    if( isset( $input['person_business_roles'] ) ) {
    	$input['person_business_roles'] = wp_filter_nohtml_kses( $input['person_business_roles'] );
    }

	return $input;
}

// Display a Settings link on the main Plugins page
function cdcrm_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$cdcrm_links = '<a href="'.get_admin_url().'options-general.php?page=cdash-crm/options.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $cdcrm_links );
	}

	return $links;
}

?>