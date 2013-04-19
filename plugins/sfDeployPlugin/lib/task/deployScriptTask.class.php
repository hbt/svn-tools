<?php
class deployScriptTask extends sfBaseTask {
	protected function configure() {
		$this->namespace = 'deploy';
		$this->name = 'script';
		$this->briefDescription = 'Deploys a script ';
		$this->detailedDescription =<<<EOF
The [deploy:script|INFO] deploys a script only
Call it with:

  [php symfony deploy:script|INFO]
EOF;

		// add arguments here, like the following:
		$this->addArgument('scriptName', sfCommandArgument :: REQUIRED, 'The script name');
		$this->addArgument('scriptType', sfCommandArgument :: OPTIONAL, 'The script type (php or sql)');
		$this->addArgument('patchEnvironmentName', sfCommandArgument :: OPTIONAL, 'The patch environment name');
		$this->addArgument('patchVersionNumber', sfCommandArgument :: OPTIONAL, 'The patch version number');

		// add options here, like the following:
		$this->addOption('applicationName', null, sfCommandOption :: PARAMETER_OPTIONAL, 'The environment', 'frontend');
		$this->addOption('environmentName', null, sfCommandOption :: PARAMETER_OPTIONAL, 'The environment', 'dev');
	}

	protected function execute($arguments = array (), $options = array ()) {
		define('DS', DIRECTORY_SEPARATOR);

		// load configuration
		$configuration = ProjectConfiguration :: getApplicationConfiguration($options['applicationName'], $options['environmentName'], true);

    if ($arguments['scriptType'] == '') {
        $arguments['scriptType'] = 'php';
    }

		//    // set default values if arguments are empty
		if ($arguments['patchVersionNumber'] == '') {
			$arguments['patchVersionNumber'] = sfConfig :: get('app_patch_version');
		}
		//
		if ($arguments['patchEnvironmentName'] == '') {
			$arguments['patchEnvironmentName'] = sfConfig :: get('app_patch_env');
		}

		//	// initialize database manager
		$db = new sfDatabaseManager($configuration);
		$db->initialize($configuration);

		$config = $db->getDatabase('propel')->getConfiguration();
		$username = $config['propel']['datasources']['propel']['connection']['username'];
		$password = $config['propel']['datasources']['propel']['connection']['password'];
		$host = $config['propel']['datasources']['propel']['connection']['hostspec'];
		$database = $config['propel']['datasources']['propel']['connection']['database'];

		// build filename
		$filename = sfConfig :: get('sf_root_dir') . DIRECTORY_SEPARATOR . 'batch' . DIRECTORY_SEPARATOR . 'patches' . DIRECTORY_SEPARATOR . $arguments['patchVersionNumber'] . DIRECTORY_SEPARATOR . $arguments['patchEnvironmentName'] . DIRECTORY_SEPARATOR . $arguments['scriptName'] . '.' . $arguments['patchEnvironmentName'] . '.' . $arguments['scriptType'];
		if (!file_exists($filename)) {
			throw new Exception("File does not exist " . $filename);
		}

		if ($arguments['scriptType'] == 'sql') {
			$cmd = 'mysql --user=' . $username . ' --password=' . $password . ' -h ' . $host . ' ' . $database . ' < ' . $filename;
			shell_exec($cmd);
		} else {
			require_once ($filename);
		}

		echo "Executed " . $filename;

	}
}