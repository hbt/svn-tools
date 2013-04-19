<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfErrorLogger.class.php 8080 2008-03-25 16:41:29Z fabien $
 */
class sfErrorLogger
{
  static public function log500(sfEvent $event)
  {
    $exception = $event->getSubject();
    $context = sfContext::getInstance();

    // is database configured?
    try
    {
      Propel::getConnection();

      // log exception in db
      $log = new sfErrorLog();
      $log->setType('sfError404Exception' == get_class($exception) ? 404 : 500);
      $log->setClassName(get_class($exception));
      $log->setMessage(!is_null($exception->getMessage()) ? $exception->getMessage() : 'n/a');
      $log->setModuleName($context->getModuleName());
      $log->setActionName($context->getActionName());
      $log->setExceptionObject($exception);
      $log->setRequest($context->getRequest());
      $log->setUri($context->getRequest()->getUri());
      $log->save();
    }
    catch (PropelException $e)
    {
    }
  }

  static public function log404(sfEvent $event)
  {
    $request = sfContext::getInstance()->getRequest();

    // is database configured?
    try
    {
      Propel::getConnection();

      // log 404 in db
      $log = new sfErrorLog();
      $log->setType(404);
      $log->setClassName(null);
      $log->setMessage('n/a');
      $log->setModuleName($event['module']);
      $log->setActionName($event['action']);
      $log->setExceptionObject(null);
      $log->setRequest($request);
      $log->setUri($request->getUri());
      $log->save();
    }
    catch (PropelException $e)
    {
    }
  }
}
