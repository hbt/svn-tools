<?php
class deployLoadDBTask extends sfBaseTask
{
    protected function configure()
    {
        $this->namespace = 'deploy';
        $this->name = 'load';
        $this->briefDescription = 'Loads a database based on current configuration';
        $this->detailedDescription =<<<EOF
The [deploy:dump|INFO] loads a database based on current configuration
Call it with:

  [php symfony deploy:dump|INFO]
EOF;
        $this->addArgument('filename', sfCommandArgument :: OPTIONAL, 'db dump filename (default is database_name)', 'project');

        // add options here, like the following:
        $this->addOption('applicationName', null, sfCommandOption :: PARAMETER_OPTIONAL, 'The application', 'frontend');
        $this->addOption('environmentName', null, sfCommandOption :: PARAMETER_OPTIONAL, 'The environment', 'all');
    }

    protected function execute($arguments = array (), $options = array ())
    {
        define('DS', DIRECTORY_SEPARATOR);

        // load configuration
        $configuration = ProjectConfiguration :: getApplicationConfiguration($options['applicationName'], 'dev', true);

        $db = new sfDatabaseManager($configuration);
        $db->initialize($configuration);

        $config = $db->getDatabase('propel')->getConfiguration();
        $username = $config['propel']['datasources']['propel']['connection']['username'];
        $password = $config['propel']['datasources']['propel']['connection']['password'];
        $host = $config['propel']['datasources']['propel']['connection']['hostspec'];
        $database = $config['propel']['datasources']['propel']['connection']['database'];

        $filename = $arguments['filename'];
        if ($filename == 'auto')
        {
            $filename = $database . '_' . date('Y-m-d') . '.sql';
        }
        elseif ($filename == 'project')
        {
            $filename = $database . '.sql';
        }

        $cmd = 'mysql --user=' . $username . ' --password=' . $password . ' -h ' . $host . ' ' . $database . ' < ' . $filename;
        shell_exec($cmd);

        echo "Loaded $filename into $database \n\n";
    }
}