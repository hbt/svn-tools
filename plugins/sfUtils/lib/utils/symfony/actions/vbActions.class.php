<?php
class vbActions extends sfActions {
  /**
  * Dispatches to the action defined by the 'action' parameter of the sfRequest object.
  *
  * This method try to execute the executeXXX() method of the current object where XXX is the
  * defined action name.
  *
  * handle
  *  - ajax get  executeAjaxGetXXX
  *  - ajax post executeAjaxPostXXX
  *  - http get executeGetXXX
  *  - http post executePostXXX
  *
  *
  * @return string A string containing the view name associated with this action
  *
  * @throws sfInitializationException
  *
  * @see sfAction
  */
  //  public function execute()
  //  {
  //    // dispatch ajax calls
  //    $isAjax = $this->getRequest()->isXmlHttpRequest();
  //    if ($isAjax) {
  //       $actionToRun = 'ajax' . ucfirst($this->getActionName());
  //
  //       // PATCH
  //       if (!is_callable(array($this, $actionToRun))) {
  //        $actionToRun = 'execute'.ucfirst($this->getActionName());
  //       } else {
  //       	$this->setTemplate('ajax.' . $this->getActionName());
  //       }
  //    } else {
  //    	// dispatch action
  //	    $actionToRun = 'execute'.ucfirst($this->getActionName());
  //    }
  //
  //    if (!is_callable(array($this, $actionToRun)))
  //    {
  //      // action not found
  //      $error = 'sfAction initialization failed for module "%s", action "%s". You must create a "%s" method.';
  //      $error = sprintf($error, $this->getModuleName(), $this->getActionName(), $actionToRun);
  //      throw new sfInitializationException($error);
  //    }
  //
  //    if (sfConfig::get('sf_logging_enabled'))
  //    {
  //      $this->getContext()->getLogger()->info('{sfAction} call "'.get_class($this).'->'.$actionToRun.'()'.'"');
  //    }
  //
  //    // run action
  //    $ret = $this->$actionToRun();
  //
  //    return $ret;
  //  }

  public function is_post() {
    if ($this->getRequest()->getMethod() == sfRequest :: POST) {
      return true;
    } else {
      return false;
    }
  }

  public function is_get() {
    if ($this->getRequest()->getMethod() == sfRequest :: GET) {
      return true;
    } else {
      return false;
    }
  }

  public function get_param($paramName, $defaultValue = null, $namespace = null) {
    if ($namespace != null) {
      $params = $this->getRequestParameter($namespace);
      if (is_array($params) && count($params) != 0) {
        return $params[$paramName];
      } else {
        return $defaultValue;
      }
    }
    return $this->getRequestParameter($paramName, $defaultValue);
  }

  public function set_param($paramName, $value, $namespace = null) {
    if ($namespace != null) {
      $params = $this->getRequestParameter($namespace);
      if (is_array($params) && count($params) != 0) {
        return $params[$paramName] = $value;
      }
    }
    return $this->getRequest()->setParameter($paramName, $value);
  }

    public function has_param($paramName)
    {
        return $this->getRequest()->hasParameter($paramName);
    }

  public function set_user_attr($attributeName, $attributeValue, $ns = null) {
    $this->getUser()->setAttribute($attributeName, $attributeValue, $ns);
  }

  public function get_user_attr($attributeName, $defaultValue = null, $ns = null) {
    return $this->getUser()->getAttribute($attributeName, $defaultValue, $ns);
  }
}
?>