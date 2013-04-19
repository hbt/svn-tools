<?php
/*
 * Usage:
 * php batch/diff.php #url #startLine #endLine
 *
 * Example:
 * php batch/diff.php http://svn.ssipm.com/projects/uwo/ctms/lib/model/EmailTemplate.php 20 50
 *
 * Options:
 * cc = clearCache (clear the exported files from SVN (download again) + clear the log)
 * xx = show xxdiff commands
 * #revisionNumber = 4th argument optional (revision number to start at)
 *
 */

require_once (dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

// load configuration
$configuration = ProjectConfiguration :: getApplicationConfiguration('frontend', 'dev', true);

// make sure all arguments are there
$url = '';
$startLine = null;
$endLine = null;
$revisionNumber = null;

if (isset ($argv[1]))
    $url = $argv[1];

if (isset ($argv[2]))
    $startLine = $argv[2];

if (isset ($argv[3]))
    $endLine = $argv[3];

if (isset ($argv[4]))
    $revisionNumber = $argv[4];

if (empty($url) || $startLine === null || $endLine === null)
{
    echo "Usage: php batch/diff.php URL startLine endLine. \n\n Options 'xx' for xxdiff commands, 'cc' for clearcache \n\n Example: php batch/diff.php http://svn.ssipm.com/projects/uwo/ctms/lib/model/EmailTemplate.php 223 545\n\n";
    exit(1);
}

// clear cache or not
$clearCache = false;
$showXX = false;

foreach ($argv as $argument)
{
    if ($argument === 'cc')
        $clearCache = true;
    elseif ($argument === 'xx')
        $showXX = true;
}

// retrieve the log
$data = svnLog :: retrieveLog($url, $clearCache);

// export all revisions
svnLog :: exportRevisions($url, $data, $clearCache);

// read revisions
$position = 0;
if ($revisionNumber == null)
{
    // take latest revision number from data
    $revisionNumber = $data[0]['revision'];
}
else
{
    // find the position
    foreach ($data as $key => $entry)
    {
        if ($entry['revision'] == $revisionNumber)
        {
            $position = $key;
            break;
        }
    }
}

// perform diffs
$previous = $data[$position];
$oldStartLine = $startLine;
$oldEndLine = $endLine;

for ($i = $position +1; $i < count($data); $i++)
{
    $entry = $data[$i];

    $file1 = svnLog :: getFilename($url, $previous['revision']);
    $file2 = svnLog :: getFilename($url, $entry['revision']);

    //echo "comparing revisions " . $previous['revision'] . " vs " . $entry['revision'] . " \n\n\n";
    $differences = Diff :: getShellDiff($file1, $file2);

    $diff = new Diff($startLine, $endLine);
    foreach ($differences as $difference)
        $diff->handleDifference($difference);

    $startLine = $diff->startLine;
    $endLine = $diff->endLine;

    if ($diff->isDifferent)
    {
        echo ("DIFFERENCE detected in revision " . $previous['revision'] . " (lines $oldStartLine, $oldEndLine) vs revision " . $entry['revision'] . " lines $startLine, $endLine \n\n");
        if ($showXX)
            echo "xxdiff $file1 $file2 \n\n\n";

    }

    $oldStartLine = $startLine;
    $oldEndLine = $endLine;

//        v($differences);

    $previous = $entry;
}
?>