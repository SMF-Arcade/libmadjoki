<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
abstract class Madjoki_Form_Element_Base
{
	/**
	 * @var Madjoki_Form_Base
	 */
	protected $form;
	
	/**
	 *
	 */
	public abstract function getRendering();
	
	/**
	 *
	 */
	public abstract function render();
	
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form)
	{
		$this->form = $form;
		$form->addElement($this);
	}
}

?>