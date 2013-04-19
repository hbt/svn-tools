<?php
define('DS', DIRECTORY_SEPARATOR);

function sfContext()
{
  return sfContext::getInstance();
}

function sfController()
{
  return sfContext()->getController();
}

function sfUser()
{
  return sfContext()->getUser();
}

function sfRequest()
{
  return sfContext()->getRequest();
}

function sfResponse()
{
  return sfContext()->getResponse();
}

function isAuthenticated()
{
  return sfUser()->isAuthenticated();
}

function getParam ($paramName)
{
  return sfContext()->getRequest()->getParameter ($paramName);
}

function __($string, $args = array(), $catalogue = 'messages')
{
  return sfContext::getInstance()->getI18n()->__($string, $args, $catalogue);
}


// code for testing shortcuts
class vbTests {
    static $currentTests;
    static $completedTests;
    static $allTests = array();
    static $allCompletedTests = array();
}


function register_tests($tests) {
  vbTests::$currentTests = $tests;
  vbTests::$allTests = array_merge(vbTests::$currentTests, vbTests::$allTests);

}

function stop_tests() {
    if (count(vbTests::$currentTests) != count(vbTests::$completedTests)) {
      echo "\n\n****************************************\n\n";
      echo "You forgot " . (count(vbTests::$currentTests) - count(vbTests::$completedTests)) . " tests \n\n";
      $diff = array_diff (array_keys(vbTests::$currentTests), array_keys(vbTests::$completedTests));
      foreach ($diff as $key) {
        echo "\nTest " . $key  . " ABOUT  " . vbTests::$currentTests[$key] . "\n";
      }

      echo "\n\n****************************************\n\n";
    }

    vbTests::$allCompletedTests = array_merge(vbTests::$completedTests, vbTests::$allCompletedTests);
}

function check_test($testKey) {
  if (array_key_exists($testKey, vbTests::$currentTests)) {
    vbTests::$completedTests[$testKey] = true;
  } else {
    throw new Exception ('test key does not exist in supplied array');
  }
}

/**
 * var dump shortcut
 */
function v()
{
    foreach (func_get_args() as $arg)
    {
        var_dump($arg);
    }

}

/**
 * useful when the debugbar is needed in sfView::NONE
 */
function debugbar()
{
    $css = file_get_contents(SF_ROOT_DIR . '/web/sf/sf_web_debug/css/main.css');
    $js = file_get_contents(SF_ROOT_DIR . '/web/sf/sf_web_debug/js/main.js');
    echo '<style>' . $css . '</style>';
    echo '<script>' . $js . '</script>';
}