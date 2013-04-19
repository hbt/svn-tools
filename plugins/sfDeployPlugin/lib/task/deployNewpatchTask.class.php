<?php

class deployNewpatchTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'deploy';
    $this->name             = 'new-patch';
    $this->briefDescription = 'Create a new patch';
    $this->detailedDescription = <<<EOF
The [deploy:new-patch|INFO] creates a new patch
Call it with:

  [php symfony deploy:new-patch|INFO]
EOF;
    // add arguments here, like the following:
    $this->addArgument('version', sfCommandArgument::REQUIRED, 'The version number');
    // add options here, like the following:
  }

  protected function execute($arguments = array(), $options = array())
  {
	define ('DS', DIRECTORY_SEPARATOR);
	$patch_path = 'batch' . DS . 'patches' . DS .  $arguments['version'];

	// batch folder exists ?
	if (!file_exists('batch')) {
		mkdir ('batch');
	}

	// batch/patches folder exists?
	if (!file_exists('batch' . DS . 'patches')) {
		mkdir ('batch' . DS . 'patches');
	}

	// batch/patches/patchNumberxxx folder exists?
	if (!file_exists($patch_path)) {
		mkdir ($patch_path);
		mkdir ($patch_path . DS . 'dev');
		mkdir ($patch_path . DS . 'prod');
		mkdir ($patch_path . DS . 'common');
		file_put_contents($patch_path . DS . 'deploy_me.php', '<?php
$patch_files = array(
	\'filename.dev.php\' => \'comments on file\',
);

 ?>');
	} else {
		throw new Exception ("A patch with the same version number already exists");
	}

  }
}