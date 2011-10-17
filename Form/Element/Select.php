<?php

/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_Select extends Madjoki_Form_Element_Field
{
	/**
	 *
	 */
	protected $options = array();
	
	/**
	 *
	 */
	public function addOption($value, $text)
	{
		$this->options[$value] = $text;
	}

	/**
	 *
	 */
	public function setOptions($array)
	{
		$this->options = $array;
	}
	
	/**
	 *
	 */
	public function Validate()
	{
		return isset($this->options[$this->getValue()]);
	}
	
	/**
	 *
	 */
	protected function render_field()
	{
		global $context;
		
		echo '
		<select name="', $this->field_name, '" tabindex="', $context['tabindex']++, '">';
		
		foreach ($this->options as $value => $text)
			echo '
			<option value="', $value, '"', $this->getValue() == $value ? ' selected="selected"' : '', '>', $text, '</option>';
		
		echo '
		</select>';
	}
}

?>