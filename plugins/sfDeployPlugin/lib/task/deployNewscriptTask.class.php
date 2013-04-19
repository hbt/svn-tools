<?php

class deployNewscriptTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'deploy';
    $this->name             = 'new-script';
    $this->briefDescription = 'Create a new script in a patch';
    $this->detailedDescription = <<<EOF
The [deploy:new-script|INFO] creates a new script in a patch
Call it with:

  [php symfony deploy:new-script|INFO]
EOF;


    // add arguments here, like the following:
    $this->addArgument('patchVersionNumber', sfCommandArgument::REQUIRED, 'The patch version number');
    $this->addArgument('environmentName', sfCommandArgument::REQUIRED, 'The environment name');
    $this->addArgument('filename', sfCommandArgument::REQUIRED, 'The script file name');
    $this->addArgument('type', sfCommandArgument::REQUIRED, 'The script type (php or sql)');

    // add options here, like the following:
    $this->addOption('description', null, sfCommandOption::PARAMETER_OPTIONAL, 'The script description', '');
  }

  protected function execute($arguments = array(), $options = array())
  {
    define ('DS', DIRECTORY_SEPARATOR);

    // check if patch exists
    if (!file_exists ('batch' . DS . 'patches' . DS . $arguments['patchVersionNumber'])) {
    	throw new Exception ("No patch was found with this version number");
    }

    // check if environment exists
    if (!file_exists ('batch' . DS . 'patches' . DS . $arguments['patchVersionNumber'] . DS . $arguments['environmentName'])) {
		throw new Exception ("There is no environment named " . $arguments['environmentName']);
    }

    // create script with description in comments
    // check if a script with the same name already exists
    if (file_exists ('batch' . DS . 'patches' . DS . $arguments['patchVersionNumber'] . DS . $arguments['environmentName'] . DS . $arguments['filename'] . '.' . $arguments['environmentName'] . '.' . $arguments['type'])) {
    	throw new Exception ('A script with the same name already exists');
    }

	// insert description in the top
	$custom_description = '';
	if ($arguments['type'] == 'php') {
		if ($options['description'] != '') {
			$custom_description = '<?php /** ' . $options['description'] . ' **/ ?>';
		}
	}

    file_put_contents('batch' . DS . 'patches' . DS . $arguments['patchVersionNumber'] . DS . $arguments['environmentName'] . DS . $arguments['filename'] . '.' . $arguments['environmentName'] . '.' . $arguments['type'], $custom_description);

  }
}