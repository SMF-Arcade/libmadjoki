<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_Submit extends Madjoki_Form_Element_Base
{
	/**
	 *
	 */
	protected $text;
	
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $text = '')
	{
		global $txt;
		
		if (empty($text))
			$text = $txt['save'];
			
		$this->text = $text;
			
		parent::__construct($form);
	}
	
	/**
	 *
	 */
	public function getRendering()
	{
		return array('div' => true, 'dl' => false);
	}
	
	/**
	 *
	 */
	public function render()
	{
		global $context;
		
		echo '
		<div class="righttext">
			<input class="button_submit" type="submit" value="', $this->text, '" tabindex="', $context['tabindex']++, '" />
		</div>';
	}
}

?>