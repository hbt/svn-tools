<?php
class deployResetTask extends sfBaseTask {
	protected function configure() {
		$this->namespace = 'test';
		$this->name = 'reset';
		$this->briefDescription = 'Resets the testing database using a dump';
		$this->detailedDescription =<<<EOF
The [deploy:deploy-reset|INFO] resets a database using an old dump
Call it with:

  [php symfony deploy:deploy-reset|INFO]
EOF;
		// add arguments here, like the following:
		$this->addArgument('environment_name', sfCommandArgument :: OPTIONAL, 'symfony environment name', 'test');
		$this->addArgument('connection_name', sfCommandArgument :: OPTIONAL, 'databases.yml connection name', 'propel');
		$this->addArgument('application', sfCommandArgument :: OPTIONAL, 'symfony application name', 'frontend');

		$this->addOption('filename', null, sfCommandOption :: PARAMETER_OPTIONAL, 'SQL dump filename', 'dumps.sql');
		$this->addOption('full_path', null, sfCommandOption :: PARAMETER_OPTIONAL, 'SQL dump filename');
	}

	protected function execute($arguments = array (), $options = array ()) {
		define('DS', DIRECTORY_SEPARATOR);

		// load configuration
		$configuration = ProjectConfiguration :: getApplicationConfiguration($arguments['application'], $arguments['environment_name'], true);

		// initialize database manager
		$db = new sfDatabaseManager($configuration);
		$db->initialize($configuration);

		$config = $db->getDatabase('propel')->getConfiguration();
		$username = $config['propel']['datasources']['propel']['connection']['username'];
		$password = $config['propel']['datasources']['propel']['connection']['password'];
		$host = $config['propel']['datasources']['propel']['connection']['hostspec'];
		$database = $config['propel']['datasources']['propel']['connection']['database'];

		// build path
		$fullPath = sfConfig :: get('sf_root_dir') . DS . 'tmp' . DS . 'sql_dumps' . DS . $options['filename'];
		if ($options['full_path'] != '') {
			$fullPath = $options['full_path'];
		}

		// exec
		$cmd = 'mysql --user ' . $username . ' --password=' . $password . ' -h ' . $host . ' ' . $database . ' < ' . $fullPath;
		shell_exec($cmd);

		echo "Database reset " . $cmd . "\n\n";

	}
}