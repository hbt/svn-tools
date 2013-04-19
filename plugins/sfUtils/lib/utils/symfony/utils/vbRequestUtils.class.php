<?php
class vbRequestUtils {
	public static function get_param($parameterName, $namespace = null, $defaultValue = null, $request = null) {
		if ($request == null) {
			$request = sfContext :: getInstance()->getRequest();
		}

		if ($namespace == null) {
			return $request->getParameter($parameterName, $defaultValue);
		} else {
			$params = $request->getParameter($namespace);
			if (is_array($params) && count($params) != 0) {
				return $params[$parameterName];
			} else {
				return $defaultValue;
			}
		}
	}
}
?>