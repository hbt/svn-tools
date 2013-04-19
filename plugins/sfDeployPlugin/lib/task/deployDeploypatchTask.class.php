<?php
class deployDeploypatchTask extends sfBaseTask
{
    protected function configure()
    {
        $this->namespace = 'deploy';
        $this->name = 'patch';
        $this->briefDescription = 'Deploys a patch';
        $this->detailedDescription =<<<EOF
The [deploy:deploy-patch|INFO] deploys a patch
Call it with:

  [php symfony deploy:deploy-patch|INFO]
EOF;
        // add arguments here, like the following:
        $this->addArgument('patchVersionNumber', sfCommandArgument :: OPTIONAL, 'The patch version number');
        $this->addArgument('patchEnvironmentName', sfCommandArgument :: OPTIONAL, 'The patch environment name');

        // add options here, like the following:
        $this->addOption('applicationName', null, sfCommandOption :: PARAMETER_OPTIONAL, 'The application', 'frontend');
        $this->addOption('environmentName', null, sfCommandOption :: PARAMETER_OPTIONAL, 'The environment', 'dev');
    }

    protected function execute($arguments = array (), $options = array ())
    {
        define('DS', DIRECTORY_SEPARATOR);

        // load configuration
        $configuration = ProjectConfiguration :: getApplicationConfiguration($options['applicationName'], $options['environmentName'], true);

        // set default values if arguments are empty
        if ($arguments['patchVersionNumber'] == '')
        {
            $arguments['patchVersionNumber'] = sfConfig :: get('app_patch_version');
        }

        if ($arguments['patchEnvironmentName'] == '')
        {
            $arguments['patchEnvironmentName'] = sfConfig :: get('app_patch_env');
        }

        // check if patch exists
        if (!file_exists(sfConfig :: get('sf_root_dir') . DS . 'batch' . DS . 'patches' . DS . $arguments['patchVersionNumber']))
        {
            throw new Exception('PATCH ' . $arguments['patchVersionNumber'] . ' COULD NOT BE FOUND');
        }

        // initialize database manager
        $db = new sfDatabaseManager($configuration);
        $db->initialize($configuration);

        $config = $db->getDatabase('propel')->getConfiguration();
        $username = $config['propel']['datasources']['propel']['connection']['username'];
        $password = $config['propel']['datasources']['propel']['connection']['password'];
        $host = $config['propel']['datasources']['propel']['connection']['hostspec'];
        $database = $config['propel']['datasources']['propel']['connection']['database'];

        // include patch file
        include_once (sfConfig :: get('sf_root_dir') . DS . 'batch' . DS . 'patches' . DS . $arguments['patchVersionNumber'] . DS . 'deploy_me.php');
        $errorsCount = 0;
        foreach ($patch_files as $patch_file => $comment)
        {
            $path = sfConfig :: get('sf_root_dir') . DIRECTORY_SEPARATOR . 'batch' . DIRECTORY_SEPARATOR . 'patches' . DIRECTORY_SEPARATOR . $arguments['patchVersionNumber'];
            $commonPath = $path . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . $patch_file;
            $devPath = $path . DIRECTORY_SEPARATOR . 'dev' . DIRECTORY_SEPARATOR . $patch_file;
            $prodPath = $path . DIRECTORY_SEPARATOR . 'prod' . DIRECTORY_SEPARATOR . $patch_file;

            if (!file_exists($devPath) && stripos($patch_file, '.dev.') !== false)
            {
                echo " FILE CANNOT BE FOUND  $devPath \n\n";
                $errorsCount++;
            }
            elseif (!file_exists($commonPath) && stripos($patch_file, '.common.') !== false)
            {
                echo " FILE CANNOT BE FOUND  $commonPath \n\n";
                $errorsCount++;
            }
            elseif (!file_exists($prodPath) && stripos($patch_file, '.prod.') !== false)
            {
               echo " FILE CANNOT BE FOUND  $prodPath \n\n";
               $errorsCount++;
            }

            if (strpos($patch_file, '.php') == null)
            {
                $cmd = 'mysql --user=' . $username . ' --password=' . $password . ' -h ' . $host . ' ' . $database . ' < ';
                if (file_exists($commonPath))
                {
                    $cmd .= $commonPath;
                }
                else
                    if (file_exists($devPath) && $arguments['patchEnvironmentName'] == 'dev')
                    {
                        $cmd .= $devPath;
                    }
                    else
                        if (file_exists($prodPath) && $arguments['patchEnvironmentName'] == 'prod')
                        {
                            $cmd .= $prodPath;
                        }
                        else
                        {
                            echo "COULD NOT LOCATE FILE NAMED " . $commonPath . "\n" . $devPath . "\n" . $prodPath;
                        }

                shell_exec($cmd);
            }
            else
            {
                if (file_exists($commonPath))
                {
                    require_once ($commonPath);
                }
                else
                    if (file_exists($devPath) && $arguments['patchEnvironmentName'] == 'dev')
                    {
                        require_once ($devPath);
                    }
                    else
                        if (file_exists($prodPath) && $arguments['patchEnvironmentName'] == 'prod')
                        {
                            require_once ($prodPath);
                        }
            }

            echo "\n\n\n\n *****EXECUTED SCRIPT '" . $patch_file . "' ==> " . $comment . "\n\n\n\n";
        }

        echo "\n\n\n\n PATCH " . $arguments['patchVersionNumber'] . " FULLY DEPLOYED with $errorsCount errors\n\n\n\n";
    }
}