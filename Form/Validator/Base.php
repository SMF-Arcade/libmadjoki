<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Base Class for Validator
 */
abstract class Madjoki_Form_Validator_Base
{
	/**
	 *
	 */
	abstract function checkString(&$string);
	
	/**
	 *
	 */
	abstract function unCheckString(&$string);
}

?>