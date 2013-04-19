<?php
require_once (dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

// load configuration
$configuration = ProjectConfiguration :: getApplicationConfiguration('frontend', 'dev', true);

//$db = new sfDatabaseManager($configuration);
//$db->initialize($configuration);

$url = 'http://svn.ssipm.com/projects/uwo/ctms/lib/model/EmailTemplate.php';

$data = svnLog::retrieveLog($url);

// download all files (once)
foreach ($data as $entry)
{
    $revision = $entry['revision'];
    svnLog::getFile($revision, $url);
}


?>