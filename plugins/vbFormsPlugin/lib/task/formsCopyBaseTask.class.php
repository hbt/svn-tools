<?php
class formsCopyBase extends sfBaseTask {
	protected function configure() {
		$this->namespace = 'forms';
		$this->name = 'copy-base';
		$this->briefDescription = '';
		$this->detailedDescription =<<<EOF
The [forms:copy-base|INFO] makes a copy of the lib/form/base and makes modifications to support this plugin
Better be used at the beginning of a project

Call it with:

  [php symfony forms:copy-base|INFO]
EOF;

	}

	protected function execute($arguments = array (), $options = array ()) {
		$configuration = ProjectConfiguration :: getApplicationConfiguration('frontend', 'dev', true);
		define('DS', DIRECTORY_SEPARATOR);
		$copyDir = 'vbBase';
		$classNamespace = 'vb';
		$libDir = sfConfig :: get('sf_root_dir') . DS . 'lib' . DS;
		$formDir = $libDir . 'form' . DS;
		$vbBaseDir = $formDir . $copyDir . DS;

		// check if directory exists
		if (!file_exists($vbBaseDir)) {
			mkdir($vbBaseDir);
		}

		// list all files and copy the ones that do not already exist
		$baseFiles = vbFileUtils :: listFiles($formDir . 'base', true, 'file');
		foreach ($baseFiles as $baseFile) {
			$fileContent = file_get_contents($baseFile);

			// replace class name
			//			$fileContent = preg_replace('/Base/', $classNamespace . 'Base', $fileContent);
			$fileContent = str_ireplace($this->getClassName($baseFile), $classNamespace . $this->getClassName($baseFile), $fileContent);
			$fileContent = str_ireplace('BaseFormPropel', $classNamespace . 'BaseFormPropel', $fileContent);

			// replace file name to match class name
			$filename = vbFileUtils :: getFileName($baseFile);
			$filename = preg_replace('/Base/', $classNamespace . 'Base', $filename);

			// write file
			file_put_contents($vbBaseDir . $filename, $fileContent);

			echo "\n\n Patched Form " . $baseFile;
		}

	}

	private function getClassName($baseFile) {
		$filename = vbFileUtils :: getFileName($baseFile);

		return substr($filename, 0, stripos($filename, '.class.php'));

	}
}