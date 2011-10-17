<?php
/**
 * Autoload
 *
 * @package Madjoki
 */

/**
 * 
 */
class Madjoki_SendFile
{
	/**
	 *
	 */
	public static function Send($filepath, $downloadname = null)
	{
		global $context, $modSettings;
		
		$context['no_last_modified'] = true;
		
		$file_ext = substr($filepath, strrpos($filepath, '.'));
	
		if (!file_exists($filepath))
			return false;
		
		if ($downloadname === null)
			$downloadname = basename($filepath);
	
		$filesize = filesize($filepath);
		
		$do_gzip = !empty($modSettings['enableCompressedOutput']) && $filesize <= 1024;
	
		ob_end_clean();
		if ($do_gzip)
			@ob_start('ob_gzhandler');
		else
		{
			ob_start();
			header('Content-Encoding: none');
		}
	
		header('Pragma: ');
		if (!$context['browser']['is_gecko'])
			header('Content-Transfer-Encoding: binary');
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 525600 * 60) . ' GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filepath)) . ' GMT');
	
		header('Accept-Ranges: bytes');
		header('Connection: close');
		header('Content-Type: application/octet-stream');
	
		if (!$do_gzip)
			header('Content-Length: ' . $filesize);
	
		// Try to buy some time...
		@set_time_limit(0);
	
		// For text files.....
		if (in_array($file_ext, array('txt', 'css', 'htm', 'html', 'php', 'xml')))
		{
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') !== false)
				$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\r\n", $buffer);');
			elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false)
				$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\r", $buffer);');
			else
				$callback = create_function('$buffer', 'return preg_replace(\'~\r~\', "\r\n", $buffer);');
		}
	
		if (!$do_gzip)
		{
			// Forcibly end any output buffering going on.
			if (function_exists('ob_get_level'))
			{
				while (@ob_get_level() > 0)
					@ob_end_clean();
			}
			else
			{
				@ob_end_clean();
				@ob_end_clean();
				@ob_end_clean();
			}
	
			$fp = fopen($filepath, 'rb');
			while (!feof($fp))
			{
				if (isset($callback))
					echo $callback(fread($fp, 8192));
				else
					echo fread($fp, 8192);
				flush();
			}
			fclose($fp);
		}
		// On some of the less-bright hosts, readfile() is disabled.  It's just a faster, more byte safe, version of what's in the if.
		elseif (isset($callback) || @readfile($filepath) == null)
			echo isset($callback) ? $callback(file_get_contents($filepath)) : file_get_contents($filepath);
	
		obExit(false);
	}
}

?>