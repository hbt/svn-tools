<?php


/*
 * make a search between diffs in revisions (svn log of a file)
 * Usage:
 * php batch/search.php http://svn.ssipm.com/projects/uwo/ctms/lib/model/EmailTemplate.php query
 *
 * Example:
 * php batch/search.php http://svn.ssipm.com/projects/uwo/ctms/lib/model/EmailTemplate.php __toString()
 *
 * options:
 * cc = clearcache (recreate the diff files)
 * -b = brief
 */

require_once (dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

// load configuration
$configuration = ProjectConfiguration :: getApplicationConfiguration('frontend', 'dev', true);

// make sure all arguments are there
$url = '';
$query = '';
$showContent = true;

if (isset ($argv[1]))
    $url = $argv[1];

if (isset ($argv[2]))
    $query = $argv[2];

if (empty ($url) || $query === null)
{
    echo "Usage: php batch/search.php #url #query [cc] \n\n cc = clearCache";
    exit (1);
}

// clear cache or not
$clearCache = false;

foreach ($argv as $argument)
{
    if ($argument === 'cc')
        $clearCache = true;
    elseif ($argument === '-b')
    {
        $showContent = false;
    }
}

// retrieve the log
$data = svnLog :: retrieveLog($url, $clearCache);

// export all revisions
svnLog :: exportRevisions($url, $data, $clearCache);

$previous = $data[0];
$results = array();
for ($i = 1; $i < count($data); $i++)
{
    $entry = $data[$i];

    $file1 = svnLog :: getFilename($url, $previous['revision']);
    $file2 = svnLog :: getFilename($url, $entry['revision']);

    // produce a diff file and read it
    $content = Diff :: writeDiffFile($url, $previous['revision'], $entry['revision']);

    if (stripos($content, $query) !== false)
    {
        $results[] = array($previous, $entry, $content);
    }

    $previous = $entry;
}

// display and format results
foreach ($results as $result)
{
    $entry1 = $result[0];
    $entry2 = $result[1];
    $content = $result[2];

    $rev1 = $entry1['revision'];
    $rev2 = $entry2['revision'];

    echo "Needle found in diff between revisions $rev1 and $rev2 \n\n";
    echo "---> $rev1 : " . $entry1['full_message'];
    echo "\n ---> $rev2 : " . $entry2['full_message'];
    if ($showContent)
    {
        echo "Haystack: $content \n\n\n\n";
    }

    echo "===============================\n\n";
}

exit (0);
?>