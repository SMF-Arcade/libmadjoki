<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_BBCEditor extends Madjoki_Form_Element_Field
{
	/**
	 *
	 */
	protected $validator;
	
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $field_name, $text, Madjoki_Form_Validator_Base $validator, $data_field = null, $id = null)
	{
		global $sourcedir;
		
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
		
		//
		$this->validator->unCheckString($this->value);
		
		require_once($sourcedir . '/Subs-Editor.php');
		$editorOptions = array(
			'form' => $this->form->formid,
			'id' => $this->id,
			'value' => $this->value,
			'width' => '100%',
		);
		create_control_richedit($editorOptions);
	}
	
	/**
	 *
	 */
	protected function setupValue()
	{
		global $sourcedir;
		
		// Use value from post
		if ($this->form->is_post && isset($_POST[$this->field_name]))
		{
			if (!empty($_REQUEST[$this->id . '_mode']))
			{
				require_once($sourcedir . '/Subs-Editor.php');
		
				$_POST[$this->field_name] = html_to_bbc($_POST[$this->field_name]);
				$_POST[$this->field_name] = un_htmlspecialchars($_POST[$this->field_name]);
				$_REQUEST[$this->field_name] = $_POST[$this->field_name];
			}

			$this->value = $_POST[$this->field_name];
		}
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
	public function render()
	{
		echo '
		<dd id="dd_', $this->id, '" class="full">', $this->render_field(), '</dd>';
	}
	/**
	 *
	 */
	public function render_field()
	{
		global $context, $txt;
		
		echo '
			<div id="bbcBox_', $this->id, '"></div>
			<div id="smileyBox_', $this->id, '"></div>
			', template_control_richedit($this->id, 'smileyBox_' . $this->id, 'bbcBox_' . $this->id), '';
			
		/*
		echo '
		</dd>
		<dd class="full center">
			<span class="smalltext">', $context['browser']['is_firefox'] ? $txt['shortcuts_firefox'] : $txt['shortcuts'], '</span><br />
			', template_control_richedit_buttons($this->id), '';*/
	}
}

?>