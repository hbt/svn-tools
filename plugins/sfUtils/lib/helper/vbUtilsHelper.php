<?php

/**
 * returns basic javascripts variables needed
 */
function include_basic_js () {
	$str = '<script type="text/javascript">';
	$str .= 'var SF_APP = "' . sfContext::getInstance()->getConfiguration()->getApplication() . '";';
	$str .= 'var SF_ENV = "' . sfContext::getInstance()->getConfiguration()->getEnvironment() . '";';
	$str .= '</script>';

	return $str;
}

function vb_url_for ($url) {
	if (sfContext::getInstance()->getConfiguration()->getEnvironment() == 'prod') {
		return '/' . sfContext::getInstance()->getConfiguration()->getApplication() . '.php' . '/' . $url;
	} else {
		return '/' . sfContext::getInstance()->getConfiguration()->getApplication() . '_' . sfContext::getInstance()->getConfiguration()->getEnvironment() . '.php' . '/' . $url;
	}
}

function vb_image_tag ($source, $options = array()) {
	return '<image src="' . $source . '"'. _tag_options ($options) . '/>';

}


?>