<?php

class PeepSoConfigSectionAdvanced extends PeepSoConfigSectionAbstract
{
	public static $css_overrides = array(
		'appearance-avatars-square',
	);

	// Builds the groups array
	public function register_config_groups()
	{
		$this->context='full';
		$this->_group_filesystem();

		if( !isset($_GET['filesystem']) ) {
			$this->context = 'left';
			$this->_group_debug();
			$this->_group_opengraph();

			$this->context = 'right';
			$this->_group_mailq();
			$this->_group_uninstall();
		}

		# @todo #257 $this->config_groups[] = $this->_group_opengraph();
	}

	private function _group_filesystem()
	{

		// # Message Filesystem
		$this->set_field(
			'system_filesystem_warning',
			__('This setting is to be changed upon very first PeepSo activation or in case of site migration. If changed in any other case it will result in missing content including user avatars, covers, photos etc. (error 404).', 'peepso'),
			'warning'
		);

		// # Message Filesystem
		$this->set_field(
			'system_filesystem_description',
			__('PeepSo allows users to upload images that are stored on your server. Enter a location where these files are to be stored.<br/>This must be a directory that is writable by your web server and and is accessible via the web. If the directory specified does not exist, it will be created.', 'peepso'),
			'message'
		);

		$this->args('class','col-xs-12');
		$this->args('field_wrapper_class','controls col-sm-10');
		$this->args('field_label_class', 'control-label col-sm-2');
		$this->args('default', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'peepso');

		$this->args('validation', array('required', 'custom'));
		$this->args('validation_options',
			array(
			'error_message' => __('Can not write to directory', 'peepso'),
			'function' => array($this, 'check_wp_filesystem')
			)
		);
		// # Uploads
		$this->set_field(
			'site_peepso_dir',
			__('Uploads Directory', 'peepso'),
			'text'
		);


		$this->set_group(
			'filesystem',
			__('File System', 'peepso')
		);
	}

	private function _group_debug()
	{
		// # Enable Logging
		$this->set_field(
			'system_enable_logging',
			__('Enable Logging', 'peepso'),
			'yesno_switch'
		);

		// # Message Logging Description
		$this->set_field(
			'system_enable_logging_description',
			__('When enabled, various debug information is logged in the peepso_errors database table. This can impact website speed and should ONLY be enabled when someone is debugging PeepSo.','peepso'),
			'message'
		);

		// Purge Content After
		$this->args('validation', array('required','numeric'));
		$this->args('int', TRUE);
		$this->args('default', 0);
		$this->set_field(
				'site_contentpurge_purge_after_days',
				__('Purge Activity Items after how many days', 'peepso'),
				'text'
		);

		// # Purge Content Description
		$this->set_field(
				'system_purge_description',
				__('Number of days to keep Activity Stream items before deleting. <b>0 (zero)</b> to disable. When enabled, old activity posts will be <b>permanently deleted</b> on a regular basis.','peepso'),
				'message'
		);

		$this->set_group(
			'advanced_debugg',
			__('Maintenance & debugging', 'peepso')
		);
	}

	private function _group_mailq()
	{
		// # Disable MailQueue
		$this->set_field(
				'disable_mailqueue',
				__('Disable PeepSo Default Mailqueue', 'peepso'),
				'yesno_switch'
		);

		// # Message Logging Description
		$this->set_field(
				'disable_mailqueue_description',
				__('Only set this to "yes" if you are experiencing issues with the default PeepSo mailqueue (some PeepSo emails not sent). ','peepso')
				.__('You will have to set up a replacement cronjob, please refer to the documentation under the keyword "mailqueue"','peepso')
				,
				'message'
		);

		$this->set_group(
				'mailqueue',
				__('Mailqueue', 'peepso')
		);
	}

	private function _group_uninstall()
	{
		// # Delete Posts and Comments
		$this->args('field_wrapper_class', 'controls col-sm-8 danger');

		$this->set_field(
			'delete_post_data',
			__('Delete Post and Comment data', 'peepso'),
			'yesno_switch'
		);

		// # Delete All Data And Settings
		$this->args('field_wrapper_class', 'controls col-sm-8 danger');

		$this->set_field(
			'delete_on_deactivate',
			__('Delete all data and settings', 'peepso'),
			'yesno_switch'
		);

		// Build Group
		$summary= __('When set to "YES", all <em>PeepSo</em> data will be deleted upon plugin Uninstall (but not Deactivation).<br/>Once deleted, <u>all data is lost</u> and cannot be recovered.', 'peepso');
		$this->args('summary', $summary);

		$this->set_group(
			'peepso_uninstall',
			__('PeepSo Uninstall', 'peepso'),
			__('Control behavior of PeepSo when uninstalling / deactivating', 'peepso')
		);
	}

	private function _group_opengraph()
	{
		$this->set_field(
			'opengraph_enable',
			__('Enable Open Graph', 'peepso'),
			'yesno_switch'
		);

		// Open Graph Title
		$this->set_field(
			'opengraph_title',
			__('Title (og:title)', 'peepso'),
			'text'
		);
		
		// Open Graph Title
		$this->set_field(
			'opengraph_description',
			__('Description (og:description)', 'peepso'),
			'textarea'
		);
		
		// Open Graph Image
		$this->set_field(
			'opengraph_image',
			__('Image (og:image)', 'peepso'),
			'text'
		);

		$this->set_group(
			'opengraph',
			__('Open Graph', 'peepso'),
			__("The Open Graph protocol enables sites shared for example to Facebook carry information that render shared URLs in a great way. Having a photo, title and description. You can learn more about it in our documentation. Just search for 'Open Graph'.", 'peepso')
		);
	}


	/**
	 * Checks if the directory has been created, if not use WP_Filesystem to create the directories.
	 * @param  string $value The peepso upload directory
	 * @return boolean
	 */
	public function check_wp_filesystem($value)
	{
		$form_fields = array('site_peepso_dir');
		$url = wp_nonce_url('admin.php?page=peepso_config&tab=filesystem', 'peepso-config-nonce', 'peepso-config-nonce');

		if (FALSE === ($creds = request_filesystem_credentials($url, '', false, false, $form_fields))) {
			return FALSE;
		}

		// now we have some credentials, try to get the wp_filesystem running
		if (!WP_Filesystem($creds)) {
			// our credentials were no good, ask the user for them again
			request_filesystem_credentials($url, '', true, false, $form_fields);
			return FALSE;
		}

		global $wp_filesystem;

		if (!$wp_filesystem->is_dir($value) || !$wp_filesystem->is_dir($value . DIRECTORY_SEPARATOR . 'users')) {
			return $wp_filesystem->mkdir($value) && $wp_filesystem->mkdir($value . DIRECTORY_SEPARATOR . 'users');
		}

		return $wp_filesystem->is_writable($value);
	}

}