<?php

// include(dirname(__FILE__).'/../../../plugins/sfShortcutsTestingPlugin/lib/bootstrap/functional.php');

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

require_once dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);
include ($configuration->getSymfonyLibDir() . '/vendor/lime/lime.php');

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));

sfConfig::set('sf_csrf_secret', false);
sfConfig::set('app_csrf_secret', false);