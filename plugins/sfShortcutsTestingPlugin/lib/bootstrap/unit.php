<?php

// FILE MOVED BUT HAS NOT BEEN TESTED

function load($app = 'frontend', $env = 'dev', $debug = true) {
    DEFINE('SF_ROOT_DIR', realpath(dirname(__FILE__) . '/../../../'));
    DEFINE('SF_APP', $app);
    require_once dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php';

    $configuration = ProjectConfiguration :: getApplicationConfiguration($app, $env, $debug);
    sfContext :: createInstance($configuration);
    include ($configuration->getSymfonyLibDir() . '/vendor/lime/lime.php');
}

function loadBasic () {
    require_once ('basic_unit.php');
}

//////////////
//$_test_dir = realpath(dirname(__FILE__).'/..');
//
//require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');
//$configuration = new ProjectConfiguration(realpath($_test_dir.'/..'));
//include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');