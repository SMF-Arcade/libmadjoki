<?php

/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_File extends Madjoki_Form_Element_Field
{
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $field_name, $text, $data_field = null, $id = null)
	{
		$form->enctype = 'multipart/form-data';
	
		parent::__construct($form, $field_name, $text, $data_field, $id);
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
	protected function render_field()
	{
		echo '
		<input type="file" id="', $this->id, '" name="', $this->field_name, '" size="30" tabindex="', $context['tabindex']++, '" />';
	}
}

?>