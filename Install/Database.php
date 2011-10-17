<?php
/**
 *
 *
 * @package LibMadjoki
 * @subpackage Install
 */

/**
 *
 */
abstract class Madjoki_Install_Database
{
	/**
	 *
	 */
	protected $tables;
	
	/**
	 *
	 */
	protected $columnRename;
	
	/**
	 *
	 */
	protected $prefix = '{db_prefix}';

	/**
	 *
	 */
	public function doTables()
	{
		global $smcFunc;

		$log = array();
		$existingTables = $smcFunc['db_list_tables']();

		foreach ($this->tables as $table)
		{
			$table_name = $table['name'];

			$tableExists = in_array($smcFunc['db_quote']($this->prefix . $table_name, array()), $existingTables);

			// Create table
			if (!$tableExists && empty($table['smf']))
				$smcFunc['db_create_table']($this->prefix . $table_name, $table['columns'], $table['indexes']);
			// Update table
			else
			{
				$currentTable = $smcFunc['db_table_structure']($this->prefix . $table_name);

				// Renames in this table?
				if (!empty($table['rename']))
				{
					foreach ($currentTable['columns'] as $column)
					{
						if (isset($table['rename'][$column['name']]))
						{
							$old_name = $column['name'];
							$column['name'] = $table['rename'][$column['name']];

							$smcFunc['db_change_column']($this->prefix . $table_name, $old_name, $column);
						}
					}
				}

				// Global renames? (should be avoided)
				if (!empty($this->columnRename))
				{
					foreach ($currentTable['columns'] as $column)
					{
						if (isset($this->columnRename[$column['name']]))
						{
							$old_name = $column['name'];
							$column['name'] = $this->columnRename[$column['name']];
							$smcFunc['db_change_column']($this->prefix . $table_name, $old_name, $column);
						}
					}
				}

				// Check that all columns are in
				foreach ($table['columns'] as $id => $col)
				{
					$exists = false;

					// TODO: Check that definition is correct
					foreach ($currentTable['columns'] as $col2)
					{
						if ($col['name'] === $col2['name'])
						{
							$exists = true;
							break;
						}
					}

					// Add missing columns
					if (!$exists)
						$smcFunc['db_add_column']('{db_prefix}' . $table_name, $col);
				}

				// Remove any unnecassary columns
				foreach ($currentTable['columns'] as $col)
				{
					$exists = false;

					foreach ($table['columns'] as $col2)
					{
						if ($col['name'] === $col2['name'])
						{
							$exists = true;
							break;
						}
					}

					if (!$exists && isset($table['upgrade']['columns'][$col['name']]))
					{
						if ($table['upgrade']['columns'][$col['name']] == 'drop')
							$smcFunc['db_remove_column']('{db_prefix}' . $table_name, $col['name']);
					}
				}

				// Check that all indexes are in and correct
				foreach ($table['indexes'] as $id => $index)
				{
					$exists = false;

					foreach ($currentTable['indexes'] as $index2)
					{
						// Primary is special case
						if ($index['type'] == 'primary' && $index2['type'] == 'primary')
						{
							$exists = true;

							if ($index['columns'] !== $index2['columns'])
							{
								$smcFunc['db_remove_index']('{db_prefix}' . $table_name, 'primary');
								$smcFunc['db_add_index']('{db_prefix}' . $table_name, $index);
							}

							break;
						}
						// Make sure index is correct
						elseif (isset($index['name']) && isset($index2['name']) && $index['name'] == $index2['name'])
						{
							$exists = true;

							// Need to be changed?
							if ($index['type'] != $index2['type'] || $index['columns'] !== $index2['columns'])
							{
								$smcFunc['db_remove_index']('{db_prefix}' . $table_name, $index['name']);
								$smcFunc['db_add_index']('{db_prefix}' . $table_name, $index);
							}

							break;
						}
					}

					if (!$exists)
						$smcFunc['db_add_index']('{db_prefix}' . $table_name, $index);
				}

				// Remove unnecassary indexes
				foreach ($currentTable['indexes'] as $index)
				{
					$exists = false;

					foreach ($table['indexes'] as $index2)
					{
						// Primary is special case
						if ($index['type'] == 'primary' && $index2['type'] == 'primary')
							$exists = true;
						// Make sure index is correct
						elseif (isset($index['name']) && isset($index2['name']) && $index['name'] == $index2['name'])
							$exists = true;
					}

					if (!$exists)
					{
						if (isset($table['upgrade']['indexes']))
						{
							foreach ($table['upgrade']['indexes'] as $index2)
							{
								if ($index['type'] == 'primary' && $index2['type'] == 'primary' && $index['columns'] === $index2['columns'])
									$smcFunc['db_remove_index']('{db_prefix}' . $table_name, 'primary');
								elseif (isset($index['name']) && isset($index2['name']) && $index['name'] == $index2['name'] && $index['type'] == $index2['type'] && $index['columns'] === $index2['columns'])
									$smcFunc['db_remove_index']('{db_prefix}' . $table_name, $index['name']);
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * Returns names for tables without prefix
	 */
	public function getTables()
	{
		return array_keys($this->tables);
	}
}

?>