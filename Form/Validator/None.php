<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Validator that accepts everything
 */
class Madjoki_Form_Validator_None extends Madjoki_Form_Validator_Base
{
	/**
	 *
	 */
	function checkString(&$string)
	{
		return true;
	}
	
	/**
	 *
	 */
	function unCheckString(&$string)
	{
	}
}

?>