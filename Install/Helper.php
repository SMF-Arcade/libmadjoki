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
class Madjoki_Install_Helper
{
	/**
	 * Enables or disabled core features.
	 */
	static public function updateAdminFeatures($item, $enabled)
	{
		global $modSettings;
	
		$admin_features = isset($modSettings['admin_features']) ? explode(',', $modSettings['admin_features']) : array('cd,cp,k,w,rg,ml,pm');
	
		if (!is_array($item))
			$item = array($item);
	
		if ($enabled)
			$admin_features = array_merge($admin_features, $item);
		else
			$admin_features = array_diff($admin_features, $item);
	
		updateSettings(array('admin_features' => implode(',', $admin_features)));
	
		return true;
	}
	
	/**
	 * Add settings based on array
	 *
	 * @param array $addSettings Array of settings to add
	 * @return void
	 * @since 0.1
	 */
	static public function doSettings($settings)
	{
		global $modSettings;
	
		$update = array();
	
		foreach ($settings as $variable => $value)
		{
			list ($value, $overwrite) = $value;
	
			if ($overwrite || !isset($modSettings[$variable]))
				$update[$variable] = $value;
		}
	
		if (!empty($update))
			updateSettings($update);
	}
	
	/**
	 * Add permissions based on array
	 *
	 * @param array $permissions Permissions to add
	 * @return void
	 */
	static public function doPermission($permissions)
	{
		global $smcFunc;
	
		$perm = array();
	
		foreach ($permissions as $permission => $default)
		{
			$result = $smcFunc['db_query']('', '
				SELECT COUNT(*)
				FROM {db_prefix}permissions
				WHERE permission = {string:permission}',
				array(
					'permission' => $permission
				)
			);
	
			list ($num) = $smcFunc['db_fetch_row']($result);
	
			if ($num == 0)
			{
				foreach ($default as $grp)
					$perm[] = array($grp, $permission);
			}
		}
	
		if (empty($perm))
			return;
	
		$smcFunc['db_insert']('insert',
			'{db_prefix}permissions',
			array(
				'id_group' => 'int',
				'permission' => 'string'
			),
			$perm,
			array()
		);
	}
}

?>