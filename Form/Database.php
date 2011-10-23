<?php
/**
 *
 * @package LibMadjoki
 * @subpackage Form
 */

/**
 * Base Class for database forms
 *
 * @todo Add check for duplicate name/id's
 */
abstract class Madjoki_Form_Database extends Madjoki_Form_Base
{
	/**
	 * 
	 */
	protected $id;

	/**
	 * 
	 */
	protected $id_field;
	
	/**
	 *
	 */
	protected $tableName = '';

	/**
	 *
	 */
	protected $where = array();
	
	/**
	 *
	 */
	protected $defaultValues = array();
	
	/**
	 *
	 */
	protected $error = 'not_found';
	
	/**
	 * Defination of database fields
	 *
	 * In following format: db_field_name => array(
	 * 	type => db field type
	 * 	[field_name => string] Form field name if not same as db_field_name
	 * 	[get_value => callback] Callback for getting value (if doesn't directly use same)
	 * )
	 */
	protected $fields = array();
	
	/**
	 *
	 */
	protected $keyFields = array();
	
	/**
	 *
	 */
	protected $extra = array();
	
	/**
	 *
	 */
	final public function __construct($id = null, $is_fatal = true, $is_post = null, $extra = null)
	{
		if ($is_post == null)
			$this->is_post = !empty($_POST['save']);
		else
			$this->is_post = $is_post;
			
		if ($this->is_post)
			checkSession('post', '');
			
		if ($id === null)
		{
			$this->id = 'new';
			$this->fromEmpty();
		}
		else
		{
			$this->id = $id;
			$this->fromDatabase($is_fatal);
		}
		
		if ($extra !== null)
			$this->extra = $extra;
		else
			$this->extra = array();
		
		$this->addFields();
		
		parent::__construct($is_post);
	}
	
	/**
	 *
	 */
	public function getID()
	{
		return $this->id;
	}
	
	/**
	 *
	 */
	public function getValue($data_field)
	{
		return isset($this->data[$data_field]) ? $this->data[$data_field] : (isset($this->defaultValues[$data_field]) ? $this->defaultValues[$data_field] : null);
	}
	
	/**
	 *
	 */
	final protected function fromEmpty()
	{
		$this->data = $this->defaultValues;
	}
	
	/**
	 *
	 */
	final protected function fromDatabase($is_fatal)
	{
		global $smcFunc;
		
		//
		$fields = array();
		
		foreach ($this->fields as $field_name => $field)
			$fields[] = 't.' . (isset($field['db_field']) ? ($field['db_field'] . ' AS ' . $field_name) : $field_name);
		
		$request = $smcFunc['db_query']('', '
			SELECT ' . implode(', ', $fields) . '
			FROM ' . $this->tableName . ' AS t
			WHERE {raw:id_field} = {int:id}' . (!empty($this->where) ? '
				AND ' . implode('
				AND ', $this->where) : ''),
			array(
				'id_field' => $this->id_field,
				'id' => $this->id,
			)
		);
		$this->data = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);
		
		if (!$this->data && $is_fatal)
			fatal_lang_error($this->error);
		elseif (!$this->data)
		{
			$this->id = null;
			return false;
			$this->fromEmpty();
		}
	}
	
	/**
	 *
	 */
	protected function onUpdated($data)
	{
	}

	/**
	 *
	 */
	protected function onNew($id, $data)
	{
	}
	
	/**
	 *
	 */
	final public function Save()
	{
		global $smcFunc;
		
		if (!$this->is_post)
			return false;
		
		if (!$this->Validate())
			return false;
		
		$elm = array();
		$data = array();
		
		foreach ($this->elements as $element)
		{
			if ($element instanceof Madjoki_Form_Element_Field)
				$elm[$element->getDataField()] = $element;
		}
		
		$updates = array();
		$keys = array();
		$values = array();
		
		foreach ($this->fields as $field_name => $field)
		{
			// Callback for getting value
			if (isset($field['get_value']))
				$data[$field_name] = call_user_func(array($this, $field['get_value']));
			elseif (isset($elm[$field_name]))
				$data[$field_name] = $elm[$field_name]->getValue();
			
			if ($this->id === 'new')
			{
				if ($field_name == $this->id_field || !isset($data[$field_name]))
					continue;
				
				$keys[$field_name] = $field['type'];
				
				if (isset($data[$field_name]))
					$values[] = $data[$field_name];
				else
					$values[] = $this->getValue($field_name);
			}
			elseif (isset($data[$field_name]))
				$updates[] = $field_name . ' = {' . $field['type'] . ':' . $field_name .'}';
		}
		
		if ($this->id === 'new')
		{			
			$smcFunc['db_insert']('',
				$this->tableName,
				$keys,
				$values,
				$this->fields
			);
			
			$id = $smcFunc['db_insert_id']($this->tableName, $this->id_field);
	
			//
			$this->onNew($id, $data);
		
			return true;
		}
		else
		{
			// Setup ID
			$data['id_field'] = $this->id_field;
			$data['id'] = $this->id;
			
			$smcFunc['db_query']('', '
				UPDATE ' . $this->tableName . '
				SET
					' . implode(',
					', $updates) . '
				WHERE {raw:id_field} = {int:id}',
				$data
			);
			
			$this->id = $smcFunc['db_insert_id']($this->tableName, $this->id_field);
			
			//
			$this->onUpdated($data);

			return true;
		}
	}
	
	/**
	 *
	 */
	abstract function addFields();
}

?>