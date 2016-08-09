<?php

class PeepSoAdminProfiles
{
	public static function administration()
	{
		self::enqueue_scripts();
		$profile_fields = new PeepSoProfileFields(new PeepSoUser());
		$fields = $profile_fields->load_fields();

		$add_new_message = PeepSoRemoteContent::get('peepso_admin_profiles_new_field','add_new_field.txt');
		$plugin_url = PeepSoRemoteContent::get('peepso_admin_profiles_plugin_url','add_new_field_link.txt');

		wp_localize_script('peepso-admin-profiles', 'peepsoadminprofilesdata', array(
			'popup_template' => PeepSoTemplate::exec_template('admin', 'profiles_no_plugin', array('message' => $add_new_message), TRUE),
			'plugin_url' => $plugin_url,
			'number_invalid' => __('Value should be greater than or equal to 0.', 'peepso'),
			'max_invalid' => __('Maximum value should be greater than or equal to %d (minimum value).', 'peepso'),
			'min_invalid' => __('Minimum value should be less than or equal to %d (maximum value).', 'peepso'),
		));

		do_action('peepso_admin_profiles_list_before');
		PeepSoTemplate::exec_template('admin','profiles_field_list', $fields);
	}

	public static function enqueue_scripts()
	{
		wp_register_script('bootstrap', PeepSo::get_asset('aceadmin/js/bootstrap.min.js'),
			array('jquery'), PeepSo::PLUGIN_VERSION, TRUE);
		wp_register_script('peepso-admin-profiles', PeepSo::get_asset('js/admin-profiles.min.js'),
			array('jquery', 'jquery-ui-sortable', 'underscore', 'peepso'), PeepSo::PLUGIN_VERSION, TRUE);

		wp_enqueue_script('bootstrap');
		wp_enqueue_script('peepso-admin-profiles');
	}
}

if(isset($_GET['reset'])) {
	PeepSoProfileFields::reset();
	PeepSoProfileFields::install(TRUE);
	echo '<hr>Reset complete';die();
}

// EOF
