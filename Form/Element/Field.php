<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Base Class for editable fields in form
 */
abstract class Madjoki_Form_Element_Field extends Madjoki_Form_Element_Base
{
	/**
	 *
	 */
	protected $id;
	
	/**
	 *
	 */
	protected $field_name;
	
	/**
	 *
	 */
	protected $data_field;
	
	/**
	 *
	 */
	protected $text;
	
	/**
	 *
	 */
	protected $sub_text;
	
	/**
	 *
	 */
	protected $value;
	
	/**
	 *
	 */
	protected $js;
	
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $field_name, $text, $data_field = null, $id = null)
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
		
		parent::__construct($form);
		
		$this->setupValue();
	}
	
	/**
	 *
	 */
	protected function setupValue()
	{
		// Use value from post
		if ($this->form->is_post && isset($_POST[$this->field_name]))
			$this->value = $_POST[$this->field_name];
		elseif (!$this->form->is_post)
			$this->value = $this->form->getValue($this->data_field);
		else
			$this->value = 0;		
	}
	
	/**
	 *
	 */
	public function getRendering()
	{
		return array('div' => true, 'dl' => true);
	}
	
	/**
	 *
	 */
	public function setSubtext($text)
	{
		$this->sub_text = $text;
	}

	/**
	 *
	 */
	public function Validate()
	{
		return true;
	}

	/**
	 *
	 */
	public function getFieldName()
	{
		return $this->field_name;
	}
	
	/**
	 *
	 */
	public function getDataField()
	{
		return $this->data_field;
	}

	/**
	 *
	 */
	public function setValue($value)
	{
		$this->value = $value;
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
	 *
	 */
	public function addJS($action, $js)
	{
		return $this->js[$action] = $js;
	}	
	
	/**
	 *
	 */
	public function render()
	{
		echo '
		<dt id="dt_', $this->id, '">', $this->render_name(), '</dt>
		<dd id="dd_', $this->id, '">', $this->render_field(), '</dd>';
	}
	
	/**
	 *
	 */
	protected function render_name()
	{
		echo $this->text;
		
		if (!empty($this->sub_text))
			echo '<br /><span class="smalltext">', $this->sub_text, '</span>';
	}
	
	/**
	 *
	 */
	protected abstract function render_field();
}

?>