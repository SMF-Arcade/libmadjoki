<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_TextArea extends Madjoki_Form_Element_Field
{
	/**
	 *
	 */
	protected $cols = 30;
	
	/**
	 *
	 */
	protected $rows = 5;
	
	/**
	 *
	 */
	protected $validator;
	
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $field_name, $text, Madjoki_Form_Validator_Base $validator, $data_field = null, $id = null)
	{
		$this->field_name = $field_name;
		
		// Todo: make sure field name is valid ID
		if ($id == null)
			$this->id = $field_name;
		else
			$this->id = $id;
		
		if ($data_field == null)
			$this->data_field = $field_name;
		else
			$this->data_field = $data_field;
		
		$this->text = $text;
		
		$this->validator = $validator;
		
		parent::__construct($form, $field_name, $text, $data_field, $id);
	}
	
	/**
	 *
	 */
	public function Validate()
	{
		return $this->validator->checkString($this->value);
	}
	
	/**
	 *
	 */
	public function setSize($cols, $rows)
	{
		$this->cols = $cols;
		$this->rows = $rows;
	}
	
	/**
	 *
	 */
	public function render_field()
	{
		global $context;
		
		$this->validator->unCheckString($this->value);
		
		echo '
		<textarea id="', $this->id, '" name="', $this->field_name, '" cols="', $this->cols, '" rows="', $this->rows, '" tabindex="', $context['tabindex']++, '">', $this->getValue(), '</textarea>';
	}
}

?>