<?php
class svnLog
{
    public static function getSVNDir()
    {
        $dir = sfConfig :: get('sf_root_dir') . DS . 'svn_diffs' . DS;
        if (!file_exists($dir))
            mkdir($dir);

        return $dir;
    }

    public static function getURLDir($url)
    {
        $dir = self :: getSVNDir() . sha1($url) . DS;
        if (!file_exists($dir))
            mkdir($dir);

        return $dir;
    }

    /*
     * export SVN revisions of a URL into a directory (all files)
     * filename.revisionNumber
     */
    public static function exportRevisions($url, $data, $clearCache = false)
    {

        foreach ($data as $entry)
        {
            $revision = $entry['revision'];
            self :: getFile($revision, $url, $clearCache);
        }

    }

    public static function retrieveLog($url, $clearCache = false)
    {
        $filename = self :: getURLDir($url) . 'log.txt';
        if ($clearCache || !file_exists($filename))
            self :: downloadLog($url, $filename);

        // read
        $log = file_get_contents($filename);

        // retrieve data parsed and extracted as an array
        $data = self :: parseLog($log);

        return $data;

    }

    public static function getFilename($url, $revision)
    {
        return self :: getURLDir($url) . basename($url) . '.' . $revision;
    }

    public static function getFile($revision, $url, $clearCache)
    {
        // svn export -r $revision $url $path
        $filename = self :: getFilename($url, $revision);
        if (!file_exists($filename) || $clearCache)
        {
            echo "Exporting $filename \n\n";
            shell_exec('svn export -r ' . $revision . ' ' . $url . ' ' . $filename);
        }
    }

    public static function parseLog($log)
    {
        $data = array ();

        $svnLogEntries = explode('------------------------------------------------------------------------', $log);

        foreach ($svnLogEntries as $svnLogEntry)
        {
            if (strlen($svnLogEntry) > 1)
            {
                $data[] = self :: extractSvnLogEntry($svnLogEntry);
            }

        }

        return $data;
    }

    static function extractSvnLogEntry($logEntry)
    {
        $explodedLogEntry = explode("\n", $logEntry);
        $logEntryInfos = array ();

        // revision, author, date and number of lines in message
        $line1 = explode('|', $explodedLogEntry[1]);
        $logEntryInfos['revision'] = (int) trim(substr($line1[0], 1));
        $logEntryInfos['author'] = trim($line1[1]);

        {
            $date = $line1[2];
            $explodedDate = explode(' ', $date);
            $logEntryInfos['date'] = $explodedDate[1];
            $logEntryInfos['time'] = $explodedDate[2];
            $logEntryInfos['timezone'] = $explodedDate[3];
            $logEntryInfos['formatted_date'] = substr($date, strpos($date, '(') + 1, -2);
        }

        $explodedLine1_3 = explode(' ', $line1[3]);
        $logEntryInfos['lines'] = $explodedLine1_3[1];

        // files and operations
        $files = array ();
        for ($i = 3; $i < count($explodedLogEntry); $i++) // NOTE: could be improved by finding the latest file
        {
            if ($explodedLogEntry[$i] == "")
                break;
            else
            {
                $explodedFile = explode(' ', $explodedLogEntry[$i]);
                $files[$explodedFile[4]] = $explodedFile[3];
            }
        }

        $logEntryInfos['files'] = $files;
        $logEntryInfos['full_message'] = '';
        for ($j = $i +1; $j < count($explodedLogEntry) - 1; $j++)
        {
            $logEntryInfos['full_message'] .= $explodedLogEntry[$j] . "\n";
            $logEntryInfos['messages'][] = $explodedLogEntry[$j];
        }

        return $logEntryInfos;
    }

    public static function downloadLog($url, $filename)
    {
        shell_exec('svn log ' . $url . ' -v > ' . $filename);
    }
}
?>