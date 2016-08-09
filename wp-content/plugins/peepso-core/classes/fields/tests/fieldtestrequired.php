<?php

class PeepSoFieldTestRequired extends PeepSoFieldTestAbstract
{

	public function __construct($value)
	{
		parent::__construct($value);

		$this->admin_label = __('Required', 'peepso');
		$this->admin_type = 'checkbox';
	}
	
	public function test()
	{
		$success = TRUE;

		if( !is_array($this->value) && !strlen($this->value) ) {
			$success = FALSE;
		}

		if( is_array($this->value) && !count($this->value) ) {
			$success = FALSE;
		}

		if( FALSE === $success) {
			$this->error = __('This field is required', 'peepso');

			return FALSE;
		}

		return TRUE;
	}

}