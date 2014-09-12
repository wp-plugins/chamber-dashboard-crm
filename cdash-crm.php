<?php
/*
Plugin Name: Chamber Dashboard CRM
Plugin URI: http://chamberdashboard.com
Description: Customer Relationship Management for your Chamber of Commerce
Version: 1.0
Author: Morgan Kay
Author URI: http://wpalchemists.com
*/

/*  Copyright 2014 Morgan Kay and the Fremont Chamber of Commerce (email : info@chamberdashboard.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// ------------------------------------------------------------------------
// REQUIRE MINIMUM VERSION OF WORDPRESS:                                               
// ------------------------------------------------------------------------


function cdcrm_requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.8", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'cdcrm_requires_wordpress_version' );

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'cdcrm_add_defaults');
register_uninstall_hook(__FILE__, 'cdcrm_delete_plugin_options');
add_action('admin_init', 'cdcrm_init' );
add_action('admin_menu', 'cdcrm_add_options_page');
add_filter( 'plugin_action_links', 'cdcrm_plugin_action_links', 10, 2 );

// Require options stuff
require_once( plugin_dir_path( __FILE__ ) . 'options.php' );


// Initialize language so it can be translated
function cdcrm_language_init() {
  load_plugin_textdomain( 'cdcrm', false, 'chamber-dashboard-crm/languages' );
}
add_action('init', 'cdcrm_language_init');

// Set up a function to tell us if the Chamber Dashboard Business Directory plugin is active
function cdcrm_business_directory_installed() {
	if( function_exists( 'cdash_register_cpt_business' ) ) {
		return true;
		echo "business directory is active";
	} else {
		echo "business directory is not active";
	}
}
add_action( 'plugins_loaded', 'cdcrm_business_directory_installed' );

// ------------------------------------------------------------------------
// SET UP CUSTOM POST TYPES AND TAXONOMIES
// ------------------------------------------------------------------------

// Register Custom Taxonomy - People Cateogory
function cdcrm_register_taxonomy_people_category() {

	$labels = array(
		'name'                       => _x( 'People Categories', 'Taxonomy General Name', 'cdcrm' ),
		'singular_name'              => _x( 'People Category', 'Taxonomy Singular Name', 'cdcrm' ),
		'menu_name'                  => __( 'People Category', 'cdcrm' ),
		'all_items'                  => __( 'All People Categories', 'cdcrm' ),
		'parent_item'                => __( 'Parent People Category', 'cdcrm' ),
		'parent_item_colon'          => __( 'Parent People Category:', 'cdcrm' ),
		'new_item_name'              => __( 'New People Category Name', 'cdcrm' ),
		'add_new_item'               => __( 'Add New People Category', 'cdcrm' ),
		'edit_item'                  => __( 'Edit People Category', 'cdcrm' ),
		'update_item'                => __( 'Update People Category', 'cdcrm' ),
		'separate_items_with_commas' => __( 'Separate People Categories with commas', 'cdcrm' ),
		'search_items'               => __( 'Search People Categories', 'cdcrm' ),
		'add_or_remove_items'        => __( 'Add or remove People Category', 'cdcrm' ),
		'choose_from_most_used'      => __( 'Choose from the most used People Categories', 'cdcrm' ),
		'not_found'                  => __( 'Not Found', 'cdcrm' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'people_category', array( 'person' ), $args );

}

add_action( 'init', 'cdcrm_register_taxonomy_people_category', 0 );

// Register Custom Taxonomy - Activity Cateogory
// function cdcrm_register_taxonomy_activity_category() {

// 	$labels = array(
// 		'name'                       => _x( 'Activity Categories', 'Taxonomy General Name', 'cdcrm' ),
// 		'singular_name'              => _x( 'Activity Category', 'Taxonomy Singular Name', 'cdcrm' ),
// 		'menu_name'                  => __( 'Activity Category', 'cdcrm' ),
// 		'all_items'                  => __( 'All Activity Categories', 'cdcrm' ),
// 		'parent_item'                => __( 'Parent Activity Category', 'cdcrm' ),
// 		'parent_item_colon'          => __( 'Parent Activity Category:', 'cdcrm' ),
// 		'new_item_name'              => __( 'New Activity Category Name', 'cdcrm' ),
// 		'add_new_item'               => __( 'Add New Activity Category', 'cdcrm' ),
// 		'edit_item'                  => __( 'Edit Activity Category', 'cdcrm' ),
// 		'update_item'                => __( 'Update Activity Category', 'cdcrm' ),
// 		'separate_items_with_commas' => __( 'Separate Activity Categories with commas', 'cdcrm' ),
// 		'search_items'               => __( 'Search Activity Categories', 'cdcrm' ),
// 		'add_or_remove_items'        => __( 'Add or remove Activity Category', 'cdcrm' ),
// 		'choose_from_most_used'      => __( 'Choose from the most used Activity Categories', 'cdcrm' ),
// 		'not_found'                  => __( 'Not Found', 'cdcrm' ),
// 	);
// 	$args = array(
// 		'labels'                     => $labels,
// 		'hierarchical'               => true,
// 		'public'                     => true,
// 		'show_ui'                    => true,
// 		'show_admin_column'          => false,
// 		'show_in_nav_menus'          => false,
// 		'show_tagcloud'              => false,
// 	);
// 	register_taxonomy( 'activity_category', array( 'activity' ), $args );

// }

// add_action( 'init', 'cdcrm_register_taxonomy_activity_category', 0 );

// Add some default activity categories
// function cdcrm_default_activity_categories() {
// 	wp_insert_term( 'Meeting Attendance', 'activity_category' );
// 	wp_insert_term(	'Event Attendance', 'activity_category' );
// 	wp_insert_term( 'Conversation', 'activity_category'	);
// 	wp_insert_term( 'Donation', 'activity_category' );
// 	wp_insert_term( 'Volunteering', 'activity_category' );
// }

// add_action( 'init', 'cdcrm_default_activity_categories', 10 );

// Register Custom Post Type - Person
function cdcrm_register_cpt_person() {

	$labels = array(
		'name'                => _x( 'People', 'Post Type General Name', 'cdcrm' ),
		'singular_name'       => _x( 'Person', 'Post Type Singular Name', 'cdcrm' ),
		'menu_name'           => __( 'People', 'cdcrm' ),
		'parent_item_colon'   => __( 'Parent Person:', 'cdcrm' ),
		'all_items'           => __( 'All People', 'cdcrm' ),
		'view_item'           => __( 'View Person', 'cdcrm' ),
		'add_new_item'        => __( 'Add New Person', 'cdcrm' ),
		'add_new'             => __( 'Add New', 'cdcrm' ),
		'edit_item'           => __( 'Edit Person', 'cdcrm' ),
		'update_item'         => __( 'Update Person', 'cdcrm' ),
		'search_items'        => __( 'Search People', 'cdcrm' ),
		'not_found'           => __( 'Not found', 'cdcrm' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'cdcrm' ),
	);
	$args = array(
		'label'               => __( 'Person', 'cdcrm' ),
		'description'         => __( 'People', 'cdcrm' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', ),
		'taxonomies'          => array( 'people_category' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-nametag',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'person', $args );

}

add_action( 'init', 'cdcrm_register_cpt_person', 0 );

// Register Custom Post Type - Activity
// function cdcrm_register_cpt_activity() {

// 	$labels = array(
// 		'name'                => _x( 'Activities', 'Post Type General Name', 'cdcrm' ),
// 		'singular_name'       => _x( 'Activity', 'Post Type Singular Name', 'cdcrm' ),
// 		'menu_name'           => __( 'Activities', 'cdcrm' ),
// 		'parent_item_colon'   => __( 'Parent Activity:', 'cdcrm' ),
// 		'all_items'           => __( 'All Activities', 'cdcrm' ),
// 		'view_item'           => __( 'View Activity', 'cdcrm' ),
// 		'add_new_item'        => __( 'Add New Activity', 'cdcrm' ),
// 		'add_new'             => __( 'Add New', 'cdcrm' ),
// 		'edit_item'           => __( 'Edit Activity', 'cdcrm' ),
// 		'update_item'         => __( 'Update Activity', 'cdcrm' ),
// 		'search_items'        => __( 'Search Activities', 'cdcrm' ),
// 		'not_found'           => __( 'Not found', 'cdcrm' ),
// 		'not_found_in_trash'  => __( 'Not found in Trash', 'cdcrm' ),
// 	);
// 	$args = array(
// 		'label'               => __( 'Activity', 'cdcrm' ),
// 		'description'         => __( 'Activities', 'cdcrm' ),
// 		'labels'              => $labels,
// 		'supports'            => array( 'title', 'editor',  ),
// 		'taxonomies'          => array( 'activity_category' ),
// 		'hierarchical'        => false,
// 		'public'              => true,
// 		'show_ui'             => true,
// 		'show_in_menu'        => false,
// 		'show_in_nav_menus'   => false,
// 		'show_in_admin_bar'   => true,
// 		'menu_position'       => 5,
// 		'menu_icon'           => 'dashicons-smiley',
// 		'can_export'          => true,
// 		'has_archive'         => true,
// 		'exclude_from_search' => true,
// 		'publicly_queryable'  => false,
// 		'capability_type'     => 'post',
// 	);
// 	register_post_type( 'activity', $args );

// }

// add_action( 'init', 'cdcrm_register_cpt_activity', 0 );


// ------------------------------------------------------------------------
// SET UP METABOXES
// ------------------------------------------------------------------------

if ( !cdcrm_business_directory_installed() ) {
	// we only need to require wpalchemy files if Chamber Dashboard Business Directory is not active
	include_once 'wpalchemy/MetaBox.php';
}
define( 'CDCRM_PATH', plugin_dir_path(__FILE__) );

// Add a stylesheet to the admin area to make meta boxes look nice
function cdcrm_metabox_stylesheet()
{
    if ( is_admin() && !cdcrm_business_directory_installed() )
    {
        wp_enqueue_style( 'wpalchemy-metabox', plugins_url() . '/chamber-dashboard-crm/wpalchemy/meta.css' );
    }
}
add_action( 'init', 'cdcrm_metabox_stylesheet' );

// Create metabox for people
$person_metabox = new WPAlchemy_MetaBox(array
(
    'id' => 'person_meta',
    'title' => 'Contact Information ',
    'types' => array('person'),
    'template' => CDCRM_PATH . '/wpalchemy/person.php',
    'mode' => WPALCHEMY_MODE_EXTRACT,
    'prefix' => '_cdcrm_'
));

$options = get_option('cdcrm_options');
if(!empty($options['person_custom'])) {
	// Create metabox for custom fields
	$custom_metabox = new WPAlchemy_MetaBox(array
	(
	    'id' => 'custom_meta',
	    'title' => 'Custom Fields',
	    'types' => array('person'),
	    'template' => CDCRM_PATH . '/wpalchemy/personcustom.php',
	    'mode' => WPALCHEMY_MODE_EXTRACT,
	    'prefix' => '_cdcrm_'
	));
}

// ------------------------------------------------------------------------
// Connect People to Businesses and People to Activities
// https://github.com/scribu/wp-posts-to-posts/blob/master/posts-to-posts.php
// ------------------------------------------------------------------------


function cdcrm_p2p_check() {
	if ( !is_plugin_active( 'posts-to-posts/posts-to-posts.php' ) ) {
		require_once dirname( __FILE__ ) . '/wpp2p/autoload.php';
		define( 'P2P_PLUGIN_VERSION', '1.6.3' );
		define( 'P2P_TEXTDOMAIN', 'cdcrm' );
	}
}
add_action( 'admin_init', 'cdcrm_p2p_check' );

function cdcrm_p2p_load() {
	//load_plugin_textdomain( P2P_TEXTDOMAIN, '', basename( dirname( __FILE__ ) ) . '/languages' );
	if ( !function_exists( 'p2p_register_connection_type' ) ) {
		require_once dirname( __FILE__ ) . '/wpp2p/autoload.php';
	}
	P2P_Storage::init();
	P2P_Query_Post::init();
	P2P_Query_User::init();
	P2P_URL_Query::init();
	P2P_Widget::init();
	P2P_Shortcodes::init();
	register_uninstall_hook( __FILE__, array( 'P2P_Storage', 'uninstall' ) );
	if ( is_admin() )
		cdcrm_load_admin();
}

function cdcrm_load_admin() {
	P2P_Autoload::register( 'P2P_', dirname( __FILE__ ) . '/wpp2p/admin' );

	new P2P_Box_Factory;
	new P2P_Column_Factory;
	new P2P_Dropdown_Factory;

	new P2P_Tools_Page;
}

function cdcrm_p2p_init() {
	// Safe hook for calling p2p_register_connection_type()
	do_action( 'p2p_init' );
}

require dirname( __FILE__ ) . '/wpp2p/scb/load.php';
scb_init( 'cdcrm_p2p_load' );
add_action( 'wp_loaded', 'cdcrm_p2p_init' );


// Create the connection between businesses and people if business directory is installed
if ( cdcrm_business_directory_installed() ) {
	function cdcrm_people_and_businesses() {
		// Get the list of roles from the options page
		$options = get_option('cdcrm_options');
		$roleslist = $options['person_business_roles'];
		$rolesarray = explode( ",", $roleslist);

		// create the connection between people and businesses
	    p2p_register_connection_type( array(
	        'name' => 'businesses_to_people',
	        'from' => 'business',
	        'to' => 'person',
	        'reciprocal' => true,
	        'admin_column' => 'any',
	        'fields' => array(
		        'role' => array( 
		            'title' => 'Role',
		            'type' => 'select',
		            'values' => $rolesarray,
		        ),
	        )
	    ) );
	}
	add_action( 'p2p_init', 'cdcrm_people_and_businesses' );
}

// function cdcrm_people_and_activities() {
//     // create the connection between people and activities
//     p2p_register_connection_type( array(
//         'name' => 'people_to_activities',
//         'from' => 'person',
//         'to' => 'activity',
//         'admin_box' => array(
// 		    'context' => 'advanced'
// 		  	),
//         'title' => array(
// 		    'from' => __( 'This Person\'s Activities', 'cdcrm' ),
// 		    'to' => __( 'Person', 'cdcrm' )
// 		  	),
//         'to_labels' => array(
// 			'singular_name' => __( 'Activity', 'cdcrm' ),
// 			'search_items' => __( 'Search activities', 'cdcrm' ),
// 			'not_found' => __( 'No activities found.', 'cdcrm' ),
// 			'create' => __( 'Add Activity', 'cdcrm' ),
// 			),
//     ) );
// }
// add_action( 'p2p_init', 'cdcrm_people_and_activities' );

// When you create an activity on the edit person screen, the activity should be published right away
function cdcrm_published_by_default( $args, $ctype, $post_id ) {
    $args['post_status'] = 'publish';
    return $args;
}
add_filter( 'p2p_new_post_args', 'cdcrm_published_by_default', 10, 3 );

?>