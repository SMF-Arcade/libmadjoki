<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Validator that validates BBC
 */
class Madjoki_Form_Validator_BBC extends Madjoki_Form_Validator_Base
{
	/**
	 * @var array Array of options
	 */
	protected $options;
	
	/**
	 * Construct new text validator.
	 *
	 * Possible options:
	 * 	no_empty: disallow empty values
	 * 
	 * @param array $options Array of options. 
	 */
	public function __construct($options = array())
	{
		$this->options = $options;
	}
	
	/**
	 *
	 */
	function checkString(&$string)
	{
		global $smcFunc, $sourcedir;
		
		require_once($sourcedir . '/Subs-Post.php');
		
		$string = $smcFunc['htmlspecialchars']($string, ENT_QUOTES);
		preparsecode($string);
		
		if (empty($this->options['no_empty']))
			return true;
		else
			return !empty($string) && trim($string) != '';
	}
	
	/**
	 *
	 */
	function unCheckString(&$string)
	{
		global $smcFunc, $sourcedir;
		
		require_once($sourcedir . '/Subs-Post.php');
		$string = un_preparsecode($string);
	}
}

?>