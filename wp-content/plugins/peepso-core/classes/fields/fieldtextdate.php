<?php

class PeepSoFieldTextDate extends PeepSoFieldText {

	public static $admin_label='Date';

	public function __construct($post, $user_id)
	{
		parent::__construct($post, $user_id);

		$this->render_form_methods['_render_form_input'] = __('date picker', 'peepso');

		// Remove inherited text area / multiline rendering
		unset($this->render_form_methods['_render_form_textarea']);

		// Add an option to render as a relative date
		$this->render_methods['_render'] = __('date (WordPress format)','peepso');
		$this->render_methods['_render_relative'] = __('relative - time passed (ie 1 month, 5 years)','peepso');
		$this->render_methods['_render_relative_age'] = __('relative - age (ie 25 years old)','peepso');

		// Remove inherited length validators
		$this->validation_methods = array_diff($this->validation_methods, array('lengthmax','lengthmin'));

		$this->default_desc = __('When did it happen?','peepso');
	}

	protected function _render()
	{
		if(empty($this->value)) {
			return $this->_render_empty_fallback();
		}

		$ret = date_i18n(get_option('date_format'), strtotime($this->value));

		return $ret;
	}

	protected function _render_relative()
	{
		if(empty($this->value)) {
			return $this->_render_empty_fallback();
		}

		#$render_args = $this->meta->type->render;

		// Grab rounding settings if defined (floor() by default)
		#$round = (isset($render_args->round)) ? $render_args->round : "floor";

		// Run against current date

		$now = date('U', current_time('timestamp', 0));
		$ret = human_time_diff_round_alt(strtotime($this->value), $now);

		return $ret;
	}

	protected function _render_relative_age()
	{
		if(empty($this->value)) {
			return $this->_render_empty_fallback();
		}

		$ret = $this->_render_relative() . ' ' . __('old','peepso');
		return $ret;
	}

	protected function _render_form_input( )
	{
		$val = '';
		
		if(!empty($this->value)) {
			$val = date_i18n(get_option('date_format'), strtotime($this->value));
		}

		$ret = '<input type="text" class="datepicker" value="' . $val . '"' . $this->_render_input_args()
		     . ' data-value="' . $this->value . '" readonly="readonly" onkeydown="return profile.field_keydown(this,event);">';

		return $ret;
	}
}
