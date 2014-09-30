<div class="my_meta_control clearfix">
	<div class="third">
		<?php $mb->the_field('title'); ?>
		<label><?php _e('Title', 'cdcrm'); ?></label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p> 
		<p><span><?php _e('Example: Chairmain of the Board of Directors, Board Member, etc.', 'cdcrm'); ?></span></p>

	</div>

	<div class="third">
		<?php $mb->the_field('prefix'); ?>
		<label><?php _e('Prefix', 'cdcrm'); ?></label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p> 
		<p><span><?php _e('Example: Jr., III, Esq., etc.', 'cdcrm'); ?></span></p>
	</div>

	<div class="third">
		<?php $mb->the_field('suffix'); ?>
		<label><?php _e('Suffix', 'cdcrm'); ?></label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p> 
		<p><span><?php _e('Example: Jr., III, Esq., etc.', 'cdcrm'); ?></span></p>
	</div>

	<div class="clearfix">
	</div>

	<label><?php _e('Address', 'cdcrm'); ?></label>
	<p>
		<?php $metabox->the_field('address'); ?>
		<textarea name="<?php $metabox->the_name(); ?>" rows="3"><?php $metabox->the_value(); ?></textarea>
	</p>

	<?php $options = get_option('cdcrm_options'); ?>

	<div class="third">
		<?php $mb->the_field('city'); ?>
		<label><?php _e('City', 'cdcrm'); ?></label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p> 
	</div>

	<div class="third">
		<?php $mb->the_field('state'); ?>
		<label><?php _e('State', 'cdcrm'); ?></label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p>
	</div>

	<div class="third">
		<?php $mb->the_field('zip'); ?>
		<label><?php _e('Zip', 'cdcrm'); ?></label>
		<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p>
	</div>

	<fieldset class="half left">
		<legend><?php _e('Phone Number(s)', 'cdcrm'); ?></legend>

		<a href="#" class="dodelete-phone button"><?php _e('Remove All Phone Numbers', 'cdcrm'); ?></a>
 
		<?php while($mb->have_fields_and_multi('phone')): ?>
		<?php $mb->the_group_open(); ?>
			<?php $mb->the_field('phonenumber'); ?>
			<label><?php _e('Phone Number', 'cdcrm'); ?></label>
			<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p>

			<?php $mb->the_field('phonetype'); ?>
			<label><?php _e('Phone Number Type', 'cdcrm'); ?></label>
			<?php $selected = ' selected="selected"'; ?>
			<select name="<?php $mb->the_name(); ?>">
				<option value=""></option>
				<?php $mb->the_field('phonetype'); ?>
				<?php $phonetypes = $options['person_phone_type'];
			 	$typesarray = explode( ",", $phonetypes);
			 	foreach ($typesarray as $type) { ?>
			 		<option value="<?php echo $type; ?>" <?php if ($mb->get_the_value() == $type) echo $selected; ?>><?php echo $type; ?></option>
			 	<?php } ?>
			</select>

		<a href="#" class="dodelete button"><?php _e('Remove This Phone Number', 'cdcrm'); ?></a>
		<hr />

		<?php $mb->the_group_close(); ?>
		<?php endwhile; ?>
		<p><a href="#" class="docopy-phone button"><?php _e('Add Another Phone Number', 'cdcrm'); ?></a></p>
	</fieldset>

	<fieldset class="half">
		<legend><?php _e('Email Address(es)', 'cdcrm'); ?></legend>
		<a href="#" class="dodelete-email button"><?php _e('Remove All Email Addresses', 'cdcrm'); ?></a>
 
		<?php while($mb->have_fields_and_multi('email')): ?>
		<?php $mb->the_group_open(); ?>
			<?php $mb->the_field('emailaddress'); ?>
			<label><?php _e('Email Address', 'cdcrm'); ?></label>
			<p><input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></p>
			
			<?php $mb->the_field('emailtype'); ?>
			<label><?php _e('Email Address Type', 'cdcrm'); ?></label>
			<?php $selected = ' selected="selected"'; ?>
			<select name="<?php $mb->the_name(); ?>">
				<option value=""></option>
				<?php $mb->the_field('emailtype'); ?>
				<?php $emailtypes = $options['person_email_type'];
			 	$typesarray = explode( ",", $emailtypes);
			 	foreach ($typesarray as $type) { ?>
			 		<option value="<?php echo $type; ?>" <?php if ($mb->get_the_value() == $type) echo $selected; ?>><?php echo $type; ?></option>
			 	<?php } ?>
			</select>

		<a href="#" class="dodelete button"><?php _e('Remove This Email Address', 'cdcrm'); ?></a>
		<hr />

		<?php $mb->the_group_close(); ?>
		<?php endwhile; ?>
		<p><a href="#" class="docopy-email button"><?php _e('Add Another Email Address', 'cdcrm'); ?></a></p>
	</fieldset>

</div>