<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_Header extends Madjoki_Form_Element_Base
{
	/**
	 *
	 */
	protected $text;
	
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $text)
	{
		global $txt;
		
		$this->text = $text;
			
		parent::__construct($form);
	}
	
	/**
	 *
	 */
	public function getRendering()
	{
		return array('div' => false, 'dl' => false);
	}
	
	/**
	 *
	 */
	public function render()
	{
		echo '
		<div class="cat_bar">
			<h3 class="catbg">', $this->text, '</h3>
		</div>';
	}
}

?>