<?php
class deploySwitchenvTask extends sfBaseTask {
	protected function configure() {
		$this->namespace = 'deploy';
		$this->name = 'switch-env';
		$this->briefDescription = 'Switches environments';
		$this->detailedDescription =<<<EOF
The [deploy:switch-env|INFO] task does things.
Call it with:

  [php symfony deploy:switch-env|INFO]
EOF;
		// add arguments here, like the following:
		$this->addArgument('environmentName', sfCommandArgument :: REQUIRED, 'The environment name');
		// add options here, like the following:
		//$name, $shortcut, $mode, $help, $default);
		$this->addOption('backupFiles', null, sfCommandOption::PARAMETER_OPTIONAL, 'backup the files of the current environment using .lastEnvironment file extension', 'false');
	}

	protected function execute($arguments = array (), $options = array ()) {

		// search for files
		echo '--- Searching for files ---' . "\n";
		$files = sfFinder :: type('any')->name('*.' . $arguments['environmentName'])->in(getcwd() . DIRECTORY_SEPARATOR);
		$hidden_files = sfFinder :: type('any')->name('.*.' . $arguments['environmentName'])->in(getcwd() . DIRECTORY_SEPARATOR);

		$files = array_merge($files, $hidden_files);

		// if we do not find any files, return
		if (count($files) > 0) {
			echo '--- ' . count($files) . ' file(s) found ---' . "\n";
		} else {
			echo "Search returned no results for this environment: " . $arguments['environmentName'] . "\n";
			return;
		}

		// start process
		echo "--- Processing... ---\n";

		foreach ($files as $file) {
			echo "--- Locating file named " . $file . "\n";
			if (is_file(str_ireplace('.' . $arguments['environmentName'], '', $file))) {

				if ($options['backupFiles'] == 'true') {
					echo "--- Creating backup file named " . str_ireplace('.' . $arguments['environmentName'], '.lastEnvironment', $file) . "\n";
					copy(str_ireplace('.' . $arguments['environmentName'], '', $file), str_ireplace('.' . $arguments['environmentName'], '.lastEnvironment', $file));
				} else {
					echo "--- Removing file named " . str_ireplace('.' . $arguments['environmentName'], '', $file) . "\n";
					unlink(str_ireplace('.' . $arguments['environmentName'], '', $file));
				}
			}

			echo "--- Creating file named " . str_ireplace('.' . $arguments['environmentName'], '', $file) . "\n";

			copy($file, str_ireplace('.' . $arguments['environmentName'], '', $file));
		}
	}
}