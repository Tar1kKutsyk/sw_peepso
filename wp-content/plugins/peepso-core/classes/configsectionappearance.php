<?php

class PeepSoConfigSectionAppearance extends PeepSoConfigSectionAbstract
{
	public static $css_overrides = array(
		'appearance-avatars-square',
	);

	// Builds the groups array
	public function register_config_groups()
	{
		$this->context='left';
		$this->_group_profiles();

		$this->context='right';
		$this->_group_registration();
		$this->_group_general();
		$this->_group_members();
	}

	private function _group_profiles()
	{
		// Display Name Style
		$options = array(
			'real_name' => __('Real Names', 'peepso'),
			'username' => __('Usernames', 'peepso'),
		);

		$this->args('options', $options);

		$this->set_field(
			'system_display_name_style',
			__('Display Name Style', 'peepso'),
			'select'
		);

		// Allow User To Override Name Setting
		$this->set_field(
				'system_override_name',
				__('Let Users Override This Setting', 'peepso'),
				'yesno_switch'
		);

		$this->args('default', 1);
		// Allow User To Change Username
		$this->set_field(
				'allow_username_change',
				__('Let Users Change Usernames', 'peepso'),
				'yesno_switch'
		);

		// Allow Profile Deletion
		$this->set_field(
			'site_registration_allowdelete',
			__('Allow Profile Deletion', 'peepso'),
			'yesno_switch'
		);

		// Allow users to hide themselves from all user listings
		$this->set_field(
			'allow_hide_user_from_user_listing',
			__('Allow users to hide themselves from all user listings', 'peepso'),
			'yesno_switch'
		);

		// Always link to PeepSo Profile
		$this->set_field(
			'always_link_to_peepso_profile',
			__('Always link to PeepSo Profile', 'peepso'),
			'yesno_switch'
		);

		/** AVATARS **/
		// # Separator Avatars
		$this->set_field(
			'separator_avatars',
			__('Avatars', 'peepso'),
			'separator'
		);

		// Use Square Avatars
		$this->set_field(
			'appearance-avatars-square',
			__('Use square avatars', 'peepso'),
			'yesno_switch'
		);

		// Use Peepso Avatars
		$this->set_field(
			'avatars_wordpress_only',
			__('Use WordPress Avatars', 'peepso'),
			'yesno_switch'
		);

		// Use Peepso Avatars
		$this->set_field(
			'avatars_wordpress_only_desc',
			__('The users will be unable to change their avatars in their PeepSo profiles. PeepSo will inherit the avatars from your WordPress site', 'peepso'),
			'message'
		);



		// Use Peepso Avatars
		$this->set_field(
			'avatars_peepso_only',
			__('Use PeepSo avatars everywhere', 'peepso'),
			'yesno_switch'
		);

		// Use Gravatar Avatars
		$this->set_field(
			'avatars_gravatar_enable',
			__('Allow Gravatar avatars', 'peepso'),
			'yesno_switch'
		);


		// Build Group
		$this->set_group(
			'profiles',
			__('User Profiles', 'peepso')
		);
	}


	private function _group_registration()
	{
		/** CUSTOM TEXT **/

		// # Separator Callout
		$this->set_field(
			'separator_callout',
			__('Customize text', 'peepso'),
			'separator'
		);

		// # Callout Header
		$this->set_field(
			'site_registration_header',
			__('Callout Header', 'peepso'),
			'text'
		);

		// # Callout Text
		$this->set_field(
			'site_registration_callout',
			__('Callout Text', 'peepso'),
			'text'
		);

		// # Button Text
		$this->set_field(
			'site_registration_buttontext',
			__('Button Text', 'peepso'),
			'text'
		);

		/** LANDING PAGE IMAGE **/
		// # Separator Landing Page
		$this->set_field(
			'separator_landing_page',
			__('Landing Page Image', 'peepso'),
			'separator'
		);

		// # Message Logging Description
		$this->set_field(
			'suggested_message_landing_page',
			// todo: filter for landing page image size
			__('Suggested Landing Page image size is: 1140px x 469px.','peepso'),
			'message'
		);

		// Landing Page Image
		$default = PeepSo::get_option('landing_page_image', PeepSo::get_asset('images/landing/register-bg.jpg'));
		$landing_page = !empty($default) ? $default : PeepSo::get_asset('images/landing/register-bg.jpg');
		$this->args('value', $landing_page);
		$this->set_field(
			'landing_page_image',
			__('Selected Image', 'peepso'),
			'text'
		);

		$default = PeepSo::get_option('landing_page_image_default', PeepSo::get_asset('images/landing/register-bg.jpg'));
		$this->args('value', $default);
		$this->set_field(
			'landing_page_image_default',
			'',
			'text'
		);
		// Build Group
		$this->set_group(
			'registration',
			__('Registration', 'peepso')
		);
	}

	private function _group_general()
	{
		// Primary CSS Template
		$options = array(
			'' => __('Light', 'peepso'),
		);

		$dir =  plugin_dir_path(__FILE__).'/../templates/css';

		$dir = scandir($dir);
		$from_key	= array( 'template-', '.css' );
		$to_key		= array( '' );

		$from_name	= array( '_', '-' );
		$to_name 	= array( ' ',' ' );

		foreach($dir as $file){
			if('template-' == substr($file, 0, 9)) {

				$key=str_replace($from_key, $to_key, $file);
				$name=str_replace($from_name, $to_name, $key);
				$options[$key]=ucwords($name);
			}
		}

		$this->args('options', $options);

		$this->set_field(
			'site_css_template',
			__('Primary CSS Template', 'peepso'),
			'select'
		);


		// Show "Powered By Peepso" Link
		$this->set_field(
			'system_show_peepso_link',
			__('Show "Powered by PeepSo" link', 'peepso'),
			'yesno_switch'
		);

		// Build Group
		$this->set_group(
			'appearance_general',
			__('General', 'peepso')
		);
	}

	private function _group_members()
	{
		// Default Sorting
		$options = array(
			'' => __('Alphabetical', 'peepso'),
			'peepso_last_activity' => __('Recently online', 'peepso'),
			'registered' => __('Latest members', 'peepso'),
		);

		$this->args('options', $options);

		$this->set_field(
			'site_memberspage_default_sorting',
			__('Default Sorting', 'peepso'),
			'select'
		);

		// Build Group
		$this->set_group(
			'appearance_members',
			__('Members page', 'peepso')
		);
	}
}