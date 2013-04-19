<?php

class WebBrowserUtils
{
    /**
     * returns http auth headers when a username & password is require
     */
    public static function getAuthHeaders($username, $password)
    {
        $headers = array ();
        $headers['Authorization'] = "Basic " . base64_encode($username . ":" . $password) . "\r\n";
        return $headers;
    }
}

 ?>