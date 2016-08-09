<?php

class PeepSoAdminConfigFields implements PeepSoAjaxCallback
{
	private static $_instance = NULL;

	private function __construct(){}

	public static function get_instance()
	{
		if (self::$_instance === NULL)
			self::$_instance = new self();
		return (self::$_instance);
	}

	public function set_prop(PeepSoAjaxResponse $resp)
	{
		if (!PeepSo::is_admin()) {
			$resp->success(FALSE);
			$resp->error(__('Insufficient permissions.', 'peepso'));
			return;
		}

		$input = new PeepSoInput();

		$id = $input->post_int('id');
		$prop = $input->post('prop');
		$value = $input->post('value');

		$post = array(
			'ID'    => $id,
			$prop   => $value,
		);

		wp_update_post($post);

		if('post_title' == $prop ) {
			delete_post_meta($id, 'default_title');
		}

		$resp->success(TRUE);
	}

	public function set_meta(PeepSoAjaxResponse $resp)
	{
		if (!PeepSo::is_admin()) {
			$resp->success(FALSE);
			$resp->error(__('Insufficient permissions.', 'peepso'));
			return;
		}

		$input = new PeepSoInput();

		$id = $input->post_int('id');
		$prop = $input->post('prop');

		$value = $input->post('value');

		if(1 == $input->post_int('json',0)) {
			$value = htmlspecialchars_decode($value);
			$value = json_decode($value, TRUE);
		}

		$key = $input->post('key', NULL);

		$meta_value = get_post_meta($id, $prop, 1);

		if( NULL !== $key) {
			if(!is_array($meta_value)) {
				$meta_value = array();
			}
			$meta_value[$key] = $value;
		} else {
			$meta_value = $value;
		}

		update_post_meta($id, $prop, $meta_value);
		$resp->success(TRUE);
	}

	public function set_order(PeepSoAjaxResponse $resp)
	{
		if (!PeepSo::is_admin()) {
			$resp->success(FALSE);
			$resp->error(__('Insufficient permissions.', 'peepso'));
			return;
		}

		$input = new PeepSoInput();



		if( $fields = json_decode($input->post('fields')) ) {
			$i = 1;
			foreach( $fields as $id ) {
				update_post_meta( $id, 'order', $i);
				$i++;
			}
		}

		$resp->success(TRUE);
	}

	public function set_admin_box_status(PeepSoAjaxResponse $resp)
	{
		if (!PeepSo::is_admin()) {
			$resp->success(FALSE);
			$resp->error(__('Insufficient permissions.', 'peepso'));
			return;
		}

		$input = new PeepSoInput();

		$id 	= $input->post('id');
		$status = $input->post_int('status', 0);

		$id = json_decode($id);

		foreach($id as $field_id) {
			update_user_meta(PeepSo::get_user_id(), 'admin_profile_field_open_' . $field_id, $status);
		}

		$resp->success(TRUE);
	}
}

// EOF
