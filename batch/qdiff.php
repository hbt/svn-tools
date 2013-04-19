<?php
require_once (dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

// load configuration
$configuration = ProjectConfiguration :: getApplicationConfiguration('frontend', 'dev', true);

//$db = new sfDatabaseManager($configuration);
//$db->initialize($configuration);


$cmd = 'diff  ' . $argv[1] . ' ' . $argv[2];
$res =  shell_exec($cmd);

$resLines = explode("\n", $res);

$diffs = array();

foreach ($resLines as $resLine)
{
    $resLineChars = str_split($resLine);
    if (!in_array($resLineChars[0], array('<', '>', '|', '-')))
        $diffs[] = $resLine;
}

v($diffs);

$diff = new Diff(5287, 364, 400); // expected 829, 843
foreach ($diffs as $d)
{
    $diff->handleDifference($d);
}

echo $diff->startLine;
echo "\n\n";
echo $diff->endLine;
echo "\n\n";
echo $diff->isDifferent;

// create a directory for processing

// retrieve log
function getLog($url)
{
    // save log
}

function getRevisions()
{
    // read log

    // create array with revision numbers
}

// export revisions into directory (svn export )
function exportRevisions()
{

}

// compare revision
function compareRevision($rv1, $rv2, $beginLine, $endLine)
{

}

 ?>