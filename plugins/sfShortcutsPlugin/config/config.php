<?php
require_once(sfConfig::get('sf_plugins_dir').'/sfShortcutsPlugin/lib/sfShortcuts.php');

$customShortcuts = sfConfig::get('sf_lib_dir').'/myShortcuts.php';

if( file_exists($customShortcuts) )
{
  require_once($customShortcuts);
}
