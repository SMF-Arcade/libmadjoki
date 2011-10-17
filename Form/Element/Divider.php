<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_Divider extends Madjoki_Form_Element_Base
{	
	function getRendering()
	{
		return array('div' => true, 'dl' => false);
	}
	
	function render()
	{
		echo '
		<hr class="hrcolor" />';
	}
}

?>