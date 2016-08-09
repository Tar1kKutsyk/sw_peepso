<?php

class PeepSoFieldTextUrl extends PeepSoFieldText
{
	public static $admin_label='URL';

	public function __construct($post, $user_id)
	{
		parent::__construct($post, $user_id);

		$this->default_desc = __('What\'s the site\'s address?','peepso');
		// No text area / multiline for URL
		unset($this->render_form_methods['_render_form_textarea']);

		// Add an option to render as <a href>
		$this->render_methods['_render_link'] = __('clickable link','peepso');

		// Remove inherited length validators
		$this->validation_methods = array_diff($this->validation_methods, array('lengthmax','lengthmin'));
		$this->validation_methods[] = 'patternurl';

		$this->default_desc = __('What\'s the site\'s address?','peepso');
	}

	protected function _render_link()
	{
		if(empty($this->value)) {
			return $this->_render_empty_fallback();
		}

		if(substr($this->value,0,4) != 'http' && !stristr($this->value, '://')) {
			$this->value = 'http://'.$this->value;
		}

		$display_value = explode('://',$this->value,2);

		return '<a href="' . $this->value . '" target="_blank">' . $display_value[1] . '</a>';
	}

}