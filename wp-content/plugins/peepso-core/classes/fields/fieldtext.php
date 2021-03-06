<?php

class PeepSoFieldText extends PeepSoField {

	public static $admin_label='Text';

	public function __construct($post, $user_id)
	{
		parent::__construct($post, $user_id);

		$render_form_methods = array(
			'_render_form_input' => __('input (single line)','peepso'),
			'_render_form_textarea' => __('textarea (multiple lines)','peepso'),
		);

		$this->render_form_methods = array_merge( $this->render_form_methods, $render_form_methods );
		
		$validation_methods = array(
			'lengthmin',
			'lengthmax',
		);

		$this->validation_methods = array_merge( $this->validation_methods, $validation_methods);

		$this->default_desc = __('Tell us about it.','peepso');
	}

	protected function _render_form_textarea()
	{
		$ret = '<textarea'.$this->_render_input_args().'>' . $this->value . '</textarea>';
		return $ret;
	}
}
