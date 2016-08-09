<?php

class PeepSoFieldSelectSingle extends PeepSoField {

	protected $field_meta_keys_extra = array(
			'select_options',
	);

	public static $admin_label='Select - Single';


	public function __construct($post, $user_id)
	{
		$this->field_meta_keys = array_merge($this->field_meta_keys, $this->field_meta_keys_extra);
		parent::__construct($post, $user_id);

		$this->render_form_methods = array(
			'_render_form_select' => __('dropdown','peepso'),
			'_render_form_checklist' => __('checklist','peepso'),
		);

		$this->default_desc = __('Pick only one.','peepso');
	}

	// Renderers

	protected function _render($echo = false)
	{
		$options = $this->_get_options();
		return ( isset($options[$this->value])  ? $options[$this->value] :  $this->_render_empty_fallback() );
	}

	protected function _render_input_args()
	{
		ob_start();

		echo ' name="'.$this->input_args['name'].'"',
			' id="'.$this->input_args['id'].'"',
			' data-id="'.$this->id.'"',
		' onchange="profile.field_changed(this,event);"';

		return ob_get_clean();
	}

	protected function _render_form_select( )
	{
		$options = $this->_get_options();

		if(!count($options)) {
			return FALSE;
		}

		ob_start();
		?>
		<select<?php echo $this->_render_input_args(); ?>>
			<option value=""><?php _e('Select an option...', 'peepso'); ?></option>
			<?php
			foreach ($options as $k => $v) {

				$selected = '';

				if ($this->value == $k) {
					$selected = 'selected';
				}

				$option = '<option %3$s value="%1$s">%2$s</option>';

				echo sprintf($option, $k, $v, $selected);
			}
			?>
		</select>
		<?php

		$ret = ob_get_clean();
		return $ret;
	}

	protected function _render_form_checklist()
	{
		$options = $this->_get_options();

		if(!count($options)) {
			return FALSE;
		}

		ob_start();

		foreach ($options as $k => $v) {

			$checked = '';

			if ($this->value == $k) {
				$checked = 'checked';
			}

			$option = '<div><label><input name="%4$s" type="radio" %3$s value="%1$s" ' . $this->_render_input_args() . ' /> %2$s</label></div>';

			echo sprintf($option, $k, $v, $checked, 'profile_field_' . $this->id);
		}

		return ob_get_clean();
	}

	// Utils
	protected function _get_options()
	{
		$options = $this->meta->select_options;
		if(!is_array($options)) {
			$options = array();
		}

		return $options;
	}
}
