<?php
class SymfonyUtils {

	public static function listPluginModules() {
		$modules = array ();
		$dirs = vbFileUtils :: listDirectories(SF_ROOT_DIR . DIRECTORY_SEPARATOR . 'plugins');

		foreach ($dirs as $dir) {
			$pluginDirs = vbFileUtils :: listDirectories($dir, false);
			foreach ($pluginDirs as $pluginDir) {
				if ($pluginDir == 'modules') {
					$modulesDirs = vbFileUtils :: listDirectories($dir . DIRECTORY_SEPARATOR . $pluginDir, false);
					foreach ($modulesDirs as $modulesDir) {
						array_push($modules, $modulesDir);
					}
				}
			}
		}

		return $modules;
	}
	
	public static function listPluginModulesFormatted ($separator = ',') {
		$modules = '';
		$moduleDirs = self::listPluginModules();
		foreach ($moduleDirs as $moduleDir) {
			$modules .= $moduleDir . $separator;
		}
		
		$modules = substr ($modules, 0, strlen ($modules) -1);
		return $modules;
	}
}
?>