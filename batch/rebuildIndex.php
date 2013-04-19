<?php
require_once (dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

// load configuration
$configuration = ProjectConfiguration :: getApplicationConfiguration('frontend', 'dev', true);

//$db = new sfDatabaseManager($configuration);
//$db->initialize($configuration);

//$url = 'http://svn.ssipm.com/projects/uwo/ctms/lib/model/Visit.php';
//
//$data = svnLog::retrieveLog($url);
//
//// download all files (once)
//foreach ($data as $entry)
//{
//    $revision = $entry['revision'];
//    svnLog::getFile($revision, $url);
//}

//$diff = new Diff(5287, 16, 20); // expected 20,27
//$diff->handleDifference('13,14c13,18');
//$diff->handleDifference('18c22,25');

//$diff = new Diff(5287, 18, 24); // expected 16,21
//$diff->handleDifference('12,16c12,14');
//$diff->handleDifference('21,23c19,20');

//$diff = new Diff(5287, 12, 15); // expected 18,21
//$diff->handleDifference('11a12,17');
//$diff->handleDifference('15a22,23');
//
//$diff = new Diff(5287, 19, 25); // expected 14,17
//$diff->handleDifference('13,17d12');
//$diff->handleDifference('22,24d16');


$diff = new Diff(5287, 44, 66); // expected 42,64
$diff->handleDifference('6c6');
$diff->handleDifference('9c9');
$diff->handleDifference('11,415c11,433');

echo $diff->startLine;
echo "\n\n";
echo $diff->endLine;
echo "\n\n";
echo $diff->isDifferent;


?>