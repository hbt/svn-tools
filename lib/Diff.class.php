<?php
class Diff
{
    public $oriStartLine, $oriEndLine, $startLine, $endLine, $isDifferent = false;

    /*
     * write comparison between two files into file
     * diff file1 file2 > file1_file2.diff
     */
    public static function writeDiffFile($url, $rev1, $rev2, $clearCache = false)
    {
        $file1 = svnLog :: getFilename($url, $rev1);
        $file2 = svnLog :: getFilename($url, $rev2);
        $file3 = svnLog :: getURLDir($url) . $rev1 . '_' . $rev2 . '.diff';

        $cmd = 'diff  -bBa ' . $file1 . ' ' . $file2 . ' > ' . $file3;
        if (!file_exists($file3) || $clearCache)
            $res = shell_exec($cmd);

        return file_get_contents($file3);
    }

    /*
     * returns differences like
     * 11c11,15
     * 11c11,23
     * etc.
     */
    public static function getShellDiff($file1, $file2)
    {
        $cmd = 'diff  -bBa ' . $file1 . ' ' . $file2;
        $res = shell_exec($cmd);

        $resLines = explode("\n", $res);

        $diffs = array ();

        foreach ($resLines as $resLine)
        {
            $resLineChars = str_split($resLine);
            if (!in_array($resLineChars[0], array (
                    '<',
                    '>',
                    '|',
                    '-'
                )) && !empty ($resLine))
                $diffs[] = $resLine;
        }

        return $diffs;
    }

    public function __construct($startLine, $endLine)
    {
        $this->oriStartLine = $startLine;
        $this->oriEndLine = $endLine;

        $this->startLine = $startLine;
        $this->endLine = $endLine;

    }

    public function handleDifference($diffLine)
    {
        // c = change
        // d = delete
        // a = added

        if (stripos($diffLine, 'c') !== false)
        {
            // change
            $this->handleDiffChange($diffLine);
        }
        elseif (stripos($diffLine, 'a') !== false)
        {
            // added
            $this->handleDiffAdd($diffLine);
        }
        elseif (stripos($diffLine, 'd') !== false)
        {
            // added
            $this->handleDiffDelete($diffLine);
        }
    }

    /**
     * TODO: make sure when there is an ADD, the range is always on the right. If the range is on the left, the algorithm will fail
     */
    public function handleDiffAdd($diffLine)
    {
        // diffLine example: 11a12,17
        list ($left1, $left2, $mleft2, $right1, $right2, $mright2) = $this->getLeftRightLineNumbers($diffLine, 'a');

        // start line
        if ($left2 <= $this->oriStartLine)
        {
            $this->startLine = $this->startLine + $mright2 - $right1 +1;
            $this->endLine = $this->endLine + $mright2 - $right1 +1;
        }

        if ($left2 >= $this->oriStartLine && $left2 < $this->oriEndLine)
        {
            $this->endLine = $this->endLine + $mright2 - $right1 +1;
            $this->isDifferent = true;
        }

    }

    public function handleDiffDelete($diffLine)
    {
        // diffline example: 13,17d12
        list ($left1, $left2, $mleft2, $right1, $right2, $mright2) = $this->getLeftRightLineNumbers($diffLine, 'd');

        // start line
        if ($left2 <= $this->oriStartLine)
        {
            $this->startLine = $this->startLine - ($mleft2 - $left1) -1;
            $this->endLine = $this->endLine - ($mleft2 - $left1) -1;
        }

        if ($left2 >= $this->oriStartLine && $left2 < $this->oriEndLine)
        {
            $this->endLine = $this->endLine - ($mleft2 - $left1) -1;
            $this->isDifferent = true;
        }

    }

    private function getLeftRightLineNumbers($diffLine, $separator)
    {
        $explodedLine = explode($separator, $diffLine);

        // get both sides
        $left = $explodedLine[0]; // 12,16
        $right = $explodedLine[1]; // 12,14

        // make sure we have a range
        $leftExploded = explode(',', $left);
        if (count($leftExploded) == 1) // in case we have a line like 12c12,14
        {
            $left1 = null;
            $left2 = $left;
        }
        else
        {
            $left1 = $leftExploded[0];
            $left2 = $leftExploded[1];
        }

        // same thing as left
        $rightExploded = explode(',', $right);
        if (count($rightExploded) == 1) // in case we have a line like 12,14c12
        {
            $right1 = null;
            $right2 = $right;
        }
        else
        {
            $right1 = $rightExploded[0];
            $right2 = $rightExploded[1];
        }

        $mright2 = $right2;
        $mleft2 = $left2;

        if ($left1 === null)
            $mleft2 = 0;
        if ($right1 === null)
            $mright2 = 0;

        return array (
            $left1,
            $left2,
            $mleft2,
            $right1,
            $right2,
            $mright2
        );

    }

    public function handleDiffChange($diffLine)
    {
        // diffLine example: 12,16c12,14

        list ($left1, $left2, $mleft2, $right1, $right2, $mright2) = $this->getLeftRightLineNumbers($diffLine, 'c');

        $margin = ($mleft2 - $left1) - ($mright2 - $right1);

        // calculate the new start line
        if ($left2 <= $this->oriStartLine)
        {
            $this->startLine = $this->startLine - $margin;
            $this->endLine = $this->endLine - $margin;
        }

        // calculate the end line
        if ($left2 >= $this->oriStartLine && $left2 <= $this->oriEndLine)
        {
            $this->endLine = $this->endLine - $margin;
            $this->isDifferent = true;

        }

    }
}
?>