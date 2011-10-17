<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_Check extends Madjoki_Form_Element_Field
{	
	protected function render_field()
	{
		global $context;
		
		$value = $this->getValue();
		
		echo '
		<input type="checkbox" id="', $this->id, '" name="', $this->field_name, '" value="1" ', !empty($value) ? 'checked="checked" ' : '', 'tabindex="', $context['tabindex']++, '" class="check" ';
		
		if (!empty($this->js))
			foreach ($this->js as $action => $js)
				echo 'on'. $action . '="' . $js . '" ';
		
		echo '/>';
	}
}

?>