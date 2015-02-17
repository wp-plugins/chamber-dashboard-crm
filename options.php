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
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form
function cdcrm_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Chamber Dashboard CRM Settings', 'cdcrm'); ?></h2>


		<div id="main" style="width: 70%; min-width: 350px; float: left;">
			<!-- Beginning of the Plugin Options Form -->
			<form method="post" action="options.php">
				<?php settings_fields('cdcrm_plugin_options'); ?>
				<?php $options = get_option('cdcrm_options'); ?>

				<!-- Table Structure Containing Form Controls -->
				<!-- Each Plugin Option Defined on a New Table Row -->
				<table class="form-table">

					<!-- Phone Number types -->
					<tr>
						<th scope="row"><?php _e('Phone Number Types', 'cdcrm'); ?></th>
						<td>
							<input type="text" size="57" name="cdcrm_options[person_phone_type]" value="<?php if(isset($options['person_phone_type'])) { echo $options['person_phone_type']; } ?>" />
							<br /><span style="color:#666666;margin-left:2px;"><?php _e('When you enter a phone number for a person, you can choose what type of phone number it is.  The default options are "Work, Home, Cell".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want main and/or office and/or cell, you will need to enter them.)', 'cdcrm'); ?></span>
						</td>
					</tr>

					<!-- Email types -->
					<tr>
						<th scope="row"><?php _e('Email Types', 'cdcrm'); ?></th>
						<td>
							<input type="text" size="57" name="cdcrm_options[person_email_type]" value="<?php if (isset($options['person_email_type'])) { echo $options['person_email_type']; } ?>" />
							<br /><span style="color:#666666;margin-left:2px;"><?php _e('When you enter an email address for a business, you can choose what type of email address it is.  The default options are "Work, Personal".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want main and/or sales and/or accounting and/or HR, you will need to enter them.)', 'cdcrm'); ?></span>
						</td>
					</tr>	

					<!-- Business Roles -->
					<tr>
						<th scope="row"><?php _e('Business Roles', 'cdcrm'); ?></th>
						<td>
							<input type="text" size="57" name="cdcrm_options[person_business_roles]" value="<?php if (isset($options['person_business_roles'])) { echo $options['person_business_roles']; } ?>" />
							<br /><span style="color:#666666;margin-left:2px;"><?php _e('You can connect people to businesses, and describe the person\'s role in that business.  The default options are "Owner, Manager, Employee, Accounting".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want owner and/or manager and/or employee, you will need to enter them.', 'cdcrm'); ?></span>
						</td>
					</tr>	
			

					<!-- Custom Fields -->
					<tr>
						<th scope="row"><?php _e('Custom Fields', 'cdcrm'); ?></th>
						<td>
							<span style="color:#666666;margin-left:2px;"><?php _e('If you need to store additional information about people, you can create custom fields here.', 'cdcrm'); ?></span><br />
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
							<?php } ?>
						</td>
					</tr>	


				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'cdcrm') ?>" />
				</p> 
			</form>

			<script type="text/javascript">
			// Add a new repeating section
			var attrs = ['for', 'id', 'name'];
			function resetAttributeNames(section) { 
			    var tags = section.find('input, label'), idx = section.index();
			    tags.each(function() {
			      var $this = jQuery(this);
			      jQuery.each(attrs, function(i, attr) {
			        var attr_val = $this.attr(attr);
			        if (attr_val) {
			            $this.attr(attr, attr_val.replace(/\[person_custom\]\[\d+\]\[/, '\[person_custom\]\['+(idx + 1)+'\]\['))
			        }
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
		</div><!-- #main -->
		<?php include( plugin_dir_path( __FILE__ ) . '/includes/aside.php' ); ?>
	</div>

	<?php	
}



// Sanitize and validate input. Accepts an array, return a sanitized array.
function cdcrm_validate_options($input) {
	 // strip html from textboxes
	// $input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']); // Sanitize textarea input (strip html tags, and escape characters)
	$input['person_phone_type'] =  wp_filter_nohtml_kses($input['person_phone_type']); 
	$input['person_phone_type'] =  wp_filter_nohtml_kses($input['person_phone_type']);
	// $input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
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