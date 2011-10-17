<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Validator that accepts numeric values
 */
class Madjoki_Form_Validator_Numeric extends Madjoki_Form_Validator_Base
{
	/**
	 *
	 */
	public $allowEmpty = false;
	
	/**
	 *
	 */
	public function __construct($allowEmpty = false)
	{
		$this->allowEmpty = $allowEmpty;
	}
	
	/**
	 *
	 */
	public function checkString(&$string)
	{
		global $smcFunc, $sourcedir;
		
		$string = trim($string);
		
		if ($this->allowEmpty && empty($string))
			return true;
		
		if (is_numeric($string))
			return true;
		
		return false;
	}
	
	/**
	 *
	 */
	public function unCheckString(&$string)
	{
	}
}

?>