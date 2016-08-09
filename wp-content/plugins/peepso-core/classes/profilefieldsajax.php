<?php

class PeepSoProfilefieldsAjax implements PeepSoAjaxCallback
{
	private static $_instance = NULL;

	private $user_id;

	private function __construct()
	{

	}

	// @todo docblock
	public static function get_instance()
	{
		if (self::$_instance === NULL)
			self::$_instance = new self();
		return (self::$_instance);
	}

	// @todo docblock
	public function save(PeepSoAjaxResponse $resp)
	{
		$input = new PeepSoInput();


		// @todo this code is repeated
		$view_uid 	= $input->post_int('view_user_id',0);
		$uid 		= $input->post_int('user_id',0);
		$cur_uid	=	PeepSo::get_user_id();

		if( (!$view_uid || !$uid || !$cur_uid) || ($cur_uid != $uid) || ($view_uid !=$uid && !current_user_can('edit_users')) ) {
			$resp->error('Insufficient permissions');
			$resp->success(FALSE);
		}
		// eof @todo this code is repeated

		$id			= $input->post_int('id');
		$value		= $input->post('value');
		
		$field = PeepSoField::get_field_by_id($id, $view_uid);

		if( !($field instanceof PeepSoField)) {
			$resp->success( FALSE );
			$resp->error('Invalid field ID');
			return;
		}

		// wp field returns INT, peepso field returns BOOL
		$success = $field->save($value);

		if( TRUE === $success || is_int($success) ) {

			$user = new PeepSoUser($view_uid);
			$user->profile_fields->load_fields();
			$user->profile_fields->get_fields();
			$stats = $user->profile_fields->profile_fields_stats;

			$resp->set('profile_completeness', $stats['completeness']);
			$resp->set('profile_completeness_message', $stats['completeness_message']);

			$resp->set('missing_required',	$stats['missing_required']);
			$resp->set('missing_required_message',	$stats['missing_required_message']);

			$resp->success( TRUE );
			$resp->set('display_value', $field->render( FALSE ));
		} else {
			$resp->success( FALSE );
			$resp->error($field->validation_errors);
		}
	}

	public function save_acc(PeepSoAjaxResponse $resp)
	{
		$input = new PeepSoInput();


		// @todo this code is repeated
		$view_uid 	= $input->post_int('view_user_id',0);
		$uid 		= $input->post_int('user_id',0);
		$cur_uid	=	PeepSo::get_user_id();

		if( (!$view_uid || !$uid || !$cur_uid) || ($cur_uid != $uid) || ($view_uid !=$uid && !current_user_can('edit_users')) ) {
			$resp->error('Insufficient permissions');
			$resp->success(FALSE);
		}
		// eof @todo this code is repeated


		$id			= $input->post_int('id');
		$acc		= $input->post_int('acc');

		$field = PeepSoField::get_field_by_id($id, $view_uid);

		if( !($field instanceof PeepSoField)) {
			$resp->success( FALSE );
			$resp->error('Invalid field ID');
			return;
		}

		if( TRUE === $field->save_acc($acc) ) {
			$resp->success( TRUE );
		} else {
			$resp->success( FALSE );
			$resp->error(__('Couldn\'t save privacy', 'peepso'));
		}
	}
}

// EOF