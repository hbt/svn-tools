<?php
class deployDumpTask extends sfBaseTask {
	protected function configure() {
		$this->namespace = 'test';
		$this->name = 'dump';
		$this->briefDescription = 'Dumps the current database';
		$this->detailedDescription =<<<EOF
The [deploy:deploy-dump|INFO] dumps a copy of the database using various arguments
Call it with:

  [php symfony deploy:deploy-dump|INFO]
EOF;
		// add arguments here, like the following:
		$this->addArgument('filename', sfCommandArgument :: OPTIONAL, 'The patch version number', 'dumps.sql');

		// add options here, like the following:
		$this->addOption('connection_name', null, sfCommandOption :: PARAMETER_OPTIONAL, 'databases yml connection name', 'propel');
		$this->addOption('application', null, sfCommandOption :: PARAMETER_OPTIONAL, 'symfony application', 'frontend');
		$this->addOption('environment', null, sfCommandOption :: PARAMETER_OPTIONAL, 'symfony environment', 'dev');
		$this->addOption('username', null, sfCommandOption :: PARAMETER_OPTIONAL, 'database username');
		$this->addOption('password', null, sfCommandOption :: PARAMETER_OPTIONAL, 'database password');
		$this->addOption('server', null, sfCommandOption :: PARAMETER_OPTIONAL, 'database server name');
		$this->addOption('database_name', null, sfCommandOption :: PARAMETER_OPTIONAL, 'database name');
	}

	protected function execute($arguments = array (), $options = array ()) {
		define('DS', DIRECTORY_SEPARATOR);

		// load configuration
		$configuration = ProjectConfiguration :: getApplicationConfiguration($options['application'], $options['environment'], true);

		// check if dump directory exists
		if (!file_exists(sfConfig :: get('sf_root_dir') . DS . 'tmp')) {
			mkdir(sfConfig :: get('sf_root_dir') . DS . 'tmp');
		}
		if (!file_exists(sfConfig :: get('sf_root_dir') . DS . 'tmp' . DS . 'sql_dumps')) {
			mkdir(sfConfig :: get('sf_root_dir') . DS . 'tmp' . DS . 'sql_dumps');
		}

		$fullPath = sfConfig :: get('sf_root_dir') . DS . 'tmp' . DS . 'sql_dumps' . DS . $arguments['filename'];

		// initialize database manager
		$db = new sfDatabaseManager($configuration);
		$db->initialize($configuration);

		if ($options['username'] == '') {
			$config = $db->getDatabase($options['connection_name'])->getConfiguration();
			$config = $db->getDatabase('propel')->getConfiguration();
			$options['username'] = $config['propel']['datasources'][$options['connection_name']]['connection']['username'];
			$options['password'] = $config['propel']['datasources'][$options['connection_name']]['connection']['password'];
			$options['server'] = $config['propel']['datasources'][$options['connection_name']]['connection']['hostspec'];
			$options['database_name'] = $config['propel']['datasources'][$options['connection_name']]['connection']['database'];
		}

		// exec command
		$cmd = 'mysqldump --user=' . $options['username'] . ' --password=' . $options['password'] . ' -h ' . $options['server'] . ' ' . $options['database_name'] . ' > ' . $fullPath;
		shell_exec($cmd);

		echo "\n dumped file here " . $fullPath . "\n\n";
	}
}