<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 *
 */
class Madjoki_Form_Element_MemberGroups extends Madjoki_Form_Element_CheckList
{
	/**
	 *
	 */
	public function __construct(Madjoki_Form_Base $form, $field_name, $text, $data_field = null, $id = null, $sep = ',')
	{
		parent::__construct($form, $field_name, $text, $data_field, $id, $sep);
		
		$this->loadMemberGroups();
	}
	
	/**
	 *
	 */
	protected function loadMemberGroups()
	{
		global $context, $smcFunc, $txt;
		
		
		$addDefaults = !$this->form->is_post && empty($this->value);

		// Default membergroups.
		$this->options = array(
			-1 => array(
				'id' => '-1',
				'name' => $txt['guests'],
				'is_post_group' => false,
			),
			0 => array(
				'id' => '0',
				'name' => $txt['regular_members'],
				'is_post_group' => false,
			)
		);
		
		if ($addDefaults)
		{
			$this->value[] = -1;
			$this->value[] = 0;
		}
	
		// Load membergroups.
		$request = $smcFunc['db_query']('', '
			SELECT group_name, id_group, min_posts
			FROM {db_prefix}membergroups
			WHERE id_group > 3 OR id_group = 2
			ORDER BY min_posts, id_group != 2, group_name');
	
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			if ($addDefaults && $row['min_posts'] == -1)
				$this->value[] = $row['id_group'];
	
			$this->options[(int) $row['id_group']] = array(
				'id' => $row['id_group'],
				'name' => trim($row['group_name']),
				'is_post_group' => $row['min_posts'] != -1,
			);
		}
	
		$smcFunc['db_free_result']($request);
	}
	
	/**
	 *
	 */
	public function addOption($value, $text)
	{
		trigger_error('Can\'t add to membergroups list', E_FATAL_ERROR);
	}

	/**
	 *
	 */
	public function setOptions($array)
	{
		trigger_error('Can\'t add to membergroups list', E_FATAL_ERROR);
	}
	
	/**
	 *
	 */
	protected function render_input($value, $text)
	{
		global $context;
		static $i = 1;
		
		echo '
		<input type="checkbox" name="', $this->field_name, '[]" id="', $this->id, '_', $i, '" value="', $value, '"',
			in_array($value, $this->value) ? ' checked="checked"' : '', ' tabindex="', $context['tabindex']++, '" />
		<label for="', $this->id, '_', $i, '">', $text['name'], '</label>
		<br />';
		
		$i++;		
	}
}

?>