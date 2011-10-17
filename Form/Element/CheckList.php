<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_CheckList extends Madjoki_Form_Element_Field
{
	/**
	 *
	 */
	protected $options = array();
	
	/**
	 *
	 */
	protected $separator = ',';
	
	/**
	 *
	 */
	function __construct(Madjoki_Form_Base $form, $field_name, $text, $data_field = null, $id = null, $sep = ',')
	{
		$this->separator = $sep;
		
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
		elseif (!$this->form->is_post && !empty($value) && !is_array($value))
			$this->value = explode(',', $value);
		elseif (is_array($value))
			$this->value = $value;
		else
			$this->value = array();
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
		if (!empty($this->separator))
			return implode($this->separator, $this->value);
		else
			return $this->value;
	}
	
	/**
	 *
	 */
	public function Validate()
	{
		foreach ($this->value as $value)
			if (!isset($this->options[$value]))
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
			
		echo '
		<i>', $txt['check_all'], '</i> <input type="checkbox" onclick="invertAll(this, this.form, \'', $this->field_name, '[]\');" tabindex="', $context['tabindex']++, '" /><br />';
	}
	
	/**
	 *
	 */
	protected function render_input($value, $text)
	{
		global $context;
		static $i = 1;
		
		echo '
		<input type="checkbox" name="', $this->field_name, '[]" id="', $this->id, '_', $i, '" value="', $value, '"',
			in_array($value, $this->value) ? ' checked="checked"' : '', ' tabindex="', $context['tabindex']++, '" />
		<label for="', $this->id, '_', $i, '">', $text, '</label>
		<br />';
		
		$i++;		
	}
}

?>