<?php
class SimpleHTMLDomUtils
{
    public static function file_get_dom()
    {
        $dom = new SimpleHTMLDom;
        $args = func_get_args();
        $dom->load(call_user_func_array('file_get_contents', $args), true);
        return $dom;
    }

    // get dom form string
    public static function str_get_dom($str, $lowercase = true)
    {
        $dom = new SimpleHTMLDom;
        $dom->load($str, $lowercase);
        return $dom;
    }

    /**
    * retrieve node text and makes the right checks
    * TODO: should integrate it to SimpleHTMLDOM
    */
    public static function getNodeText($node, $position = 0)
    {
        $result = null;
        if ($node != null && count($node->nodes) > 0)
        {
            if (isset ($node->nodes[$position]))
            {
                $result = $node->nodes[$position]->innertext();
            }
        }

        return $result;
    }

    /**
     * retrieve node attribute and makes the right checks
     * TODO: should integrate it to SimpleHTMLDOM
     */
    public static function getNodeAttribute($node, $position = 0, $attributeName)
    {
        $result = null;
        if ($node != null && count($node->nodes) > 0)
        {
            if (isset ($node->nodes[$position]) && $node->nodes[$position]->hasAttribute($attributeName))
            {
                $result = $node->nodes[$position]->getAttribute($attributeName);
            }
        }

        return $result;
    }
}
?>