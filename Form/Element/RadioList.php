<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_RadioList extends Madjoki_Form_Element_Field
{
	/**
	 *
	 */
	protected $options = array();
	
	/**
	 *
	 */
	function __construct(Madjoki_Form_Base $form, $field_name, $text, $data_field = null, $id = null)
	{
		parent::__construct($form, $field_name, $text, $data_field, $id);
	}
	
	/**
	 *
	 */
	protected function setupValue()
	{
		$value = $this->form->getValue($this->data_field);
		// Use value from post
		if ($this->form->is_post && isset($_POST[$this->field_name]))
			$this->value = $_POST[$this->field_name];
		elseif (!$this->form->is_post && !empty($value))
			$this->value = $value;
		else
			$this->value = null;	
	}

	/**
	 *
	 */
	function addOption($value, $text)
	{
		$this->options[$value] = $text;
	}

	/**
	 *
	 */
	function setOptions($array)
	{
		$this->options = $array;
	}
	
	/**
	 *
	 */
	public function getValue()
	{
		return $this->value;
	}
	
	/**
	 *
	 */
	public function Validate()
	{
		if (!isset($this->options[$this->value]))
			return false;
			
		return true;
	}
	
	/**
	 *
	 */
	protected function render_field()
	{
		global $txt, $context;
		
		$value = $this->getValue();
		
		$i = 1;
		
		foreach ($this->options as $value => $text)
			$this->render_input($value, $text);
	}
	
	/**
	 *
	 */
	protected function render_input($value, $text)
	{
		global $context;
		static $i = 1;
		
		echo '
		<input type="radio" name="', $this->field_name, '" id="', $this->id, '_', $i, '" value="', $value, '"',
			$value == $this->value ? ' checked="checked"' : '', ' tabindex="', $context['tabindex']++, '" />
		<label for="', $this->id, '_', $i, '">', $text, '</label>
		<br />';
		
		$i++;		
	}
}

?>