<?php

class SessionUtils {

  public static function clearSession () {

  	$user = sfContext::getInstance()->getUser();
  	$user->setAuthenticated(false);
  	$user->getAttributeHolder()->clear();
  	$user->clearCredentials ();

  }

	/**
	 * @param time 2 weeks
	 */
  public static function setCookie ($name, $value, $time = 1296000) {
	sfContext :: getInstance()->getResponse()->setCookie($name, $value, time() + $time, '/');
  }

  public static function clearCookie ($name) {
  	  sfContext::getInstance()->getResponse()->setCookie($name, '', time() - 3600, '/');
  }
}
?>