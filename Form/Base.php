<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Base Class for forms
 *
 * @todo Add check for duplicate name/id's
 */
abstract class Madjoki_Form_Base
{
	/**
	 *
	 */
	protected $action_url;
	
	/**
	 *
	 */
	protected $method = 'POST';
	
	/**
	 *
	 *
	 */
	public $formid = 'baseform';
	
	/**
	 * @var Madjoki_Form_Element[]
	 */
	protected $elements = array();

	/**
	 * @var Madjoki_Form_Element[]
	 */
	protected $formFields = array();
	
	/**
	 *
	 */
	protected $data = array();
	
	/**
	 *
	 */
	protected $saveEntities = array();
	
	/**
	 *
	 */
	protected $hidden = array('save' => '1');
	
	/**
	 *
	 */
	public $enctype;
	
	/**
	 *
	 */
	public $is_post = false;

	/**
	 *
	 */
	protected $js = array();
	
	/**
	 *
	 */
	public function __construct($is_post = null)
	{
		if ($is_post == null)
			$this->is_post = !empty($_POST['save']);
		else
			$this->is_post = $is_post;
			
		if ($this->is_post)
			checkSession('post', '');
			
		// CSS is needed
		loadTemplate(false, 'libmadjoki');
	}

	/**
	 *
	 */
	public function addElement(Madjoki_Form_Element_Base $element)
	{
		if ($element instanceof Madjoki_Form_Element_Field)
			$this->addElementField($element);
		else
			$this->elements[] = $element;
	}
	
	/**
	 *
	 */
	public function addElementField(Madjoki_Form_Element_Field $element)
	{
		if (isset($this->formFields[$element->getFieldName()]))
			trigger_error('Duplicate field name!', E_FATAL_ERROR);
			
		$this->elements[] = $element;
		$this->formFields[$element->getFieldName()] = $element;
	}
	
	/**
	 *
	 * @return Madjoki_Form_Element_Field
	 */
	public function getFieldByName($field_name)
	{
		return $this->formFields[$field_name];
	}	
	
	/**
	 *
	 */
	public function addHiddenField($name, $value)
	{
		$this->hidden[$name] = $value;
	}
	
	/**
	 *
	 */
	public function getValue($data_field)
	{			
		return isset($this->data[$data_field]) ? $this->data[$data_field] : null;
	}
	
	/**
	 *
	 */
	public function Validate()
	{
		$is_valid = true;
		
		foreach ($this->elements as $element)
		{
			if ($element instanceof Madjoki_Form_Element_Field)
				$is_valid &= $element->Validate();
		}
		
		return $is_valid;
	}
	
	/**
	 *
	 */
	public function addJS($js)
	{
		$this->js[] = $js;
	}
	
	/**
	 *
	 */
	public function render()
	{
		global $context;
		
		$is_div_open = false;
		$is_dl_open = false;
		
		if (!empty($this->saveEntities))
			$submitJS = 'submitonce(this);smc_saveEntities(\'' . $this->formid . '\', [' . implode('\', \'', $this->saveEntities) . '\']);';
		else
			$submitJS = 'submitonce(this);';
		
		echo '
	<form id="', $this->formid, '" name="', $this->formid, '" action="', $this->action_url,'" method="post" accept-charset="', $context['character_set'], '" onsubmit="'. $submitJS . '" ', !empty($this->enctype) ? ' enctype="' . $this->enctype . '"' : '', ' class="lmform">';
	
		foreach ($this->elements as $element)
		{			
			// Get rendering options
			$rendering = $element->getRendering();
			
			if ($rendering['div'] && !$is_div_open)
			{
				echo '
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">';
			
				$is_div_open = true;
			}

			if ($rendering['dl'] && !$is_dl_open)
			{
				echo '
				<dl>';
			
				$is_dl_open = true;
			}
			
			if (!$rendering['dl'] && $is_dl_open)
			{
				echo '
				</dl>';
				
				$is_dl_open = false;
			}
			
			if (!$rendering['div'] && $is_div_open)
			{
				echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>';
		
				$is_div_open = false;
			}
			
			$element->render();
		}
		
		if ($is_dl_open)
			echo '
				</dl>';
				
		if ($is_div_open)
			echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>';
		
		foreach ($this->hidden as $name => $value)
			echo '
		<input type="hidden" name="', $name, '" value="', $value, '" />';
		
		echo '
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />';
		
		if (!empty($this->js))
		{
			echo '
		<script type="text/javascript"><!-- // --><![CDATA[';
		
			foreach ($this->js as $js)
				echo $js;
		
			echo '
		// ]]></script>';
		}
		
		echo '
	</form>';
	}
}

?>