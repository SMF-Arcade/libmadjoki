<?php
/**
 * Autoload
 *
 * @package Madjoki
 */

/**
 * 
 */
class Madjoki_Autoload
{
	/**
	 * Autoload function
	 * 
	 * @param string $class_name Class Name
	 */
	static public function autoload($class_name)
	{
		global $sourcedir;
		
		if (substr($class_name, 0, 7) == 'Madjoki')
		{
			
			$class_file = str_replace('_', '/', $class_name);
	
			if (file_exists($sourcedir . '/' . $class_file . '.php'))
				require_once($sourcedir . '/' . $class_file . '.php');
			else
				return false;
				
			return true;
		}
		
		return false;
	}
	
	/**
	 * Registers autoload function
	 */
	static public function registerAutoload()
	{
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}
}

?>