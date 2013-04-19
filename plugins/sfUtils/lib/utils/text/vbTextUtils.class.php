<?php

class vbTextUtils
{
	public static function generateUniqueId () {
		$id = microtime() * time();
		$id = str_ireplace('.', '', $id);
    	$id = str_ireplace('-', '', $id);
    	$id = str_ireplace(' ', '', $id);
		
		return $id;
	}

    static public function stripText($text)
    {
        $text = self::convertAccentuatedCaracter($text);
        $text = strtolower($text);

        // strip all non word chars
        $text = preg_replace('/\W/', ' ', $text);

        // replace all white space sections with a dash
        $text = preg_replace('/\ +/', '-', $text);

        // trim dashes
        $text = preg_replace('/\-$/', ' ', $text);
        $text = preg_replace('/^\-/', ' ', $text);

        
        return trim($text);
    }

    static public function removeAccents($text)
    {
        $text = htmlentities($text, ENT_COMPAT, "UTF-8");
        $text = preg_replace(
        '/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/',
        '$1',$text);
        return html_entity_decode($text);
    }
    
    public static function clearAlias ($alias) {
    	$alias = self::removeAccents ($alias);
    	
    	$alias = preg_replace('/\W/', ' ', $alias);
    	$alias =  preg_replace('/\-$/', ' ', $alias);
      $alias =  preg_replace('/^\-/', ' ', $alias);
    	$alias = str_ireplace('.', '', $alias);
    	$alias = str_ireplace('-', '', $alias);
    	$alias = str_ireplace(' ', '', $alias);
    	
    	return $alias;
    }
    
    public static function isAliasValid (&$alias) {
    	$alias = self::clearAlias ($alias);
    	if (strpos ($alias, '.')) {
    		return false;
    	} else if (strpos ($alias, ' ')) {
    		return false;
    	} else {
    		return true;
    	}
    }
}