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
 * @version    SVN: $Id: sfErrorLog.php 7911 2008-03-15 22:05:07Z fabien $
 */
class sfErrorLog extends BasesfErrorLog
{
  public function getExceptionObject()
  {
    return @unserialize(parent::getExceptionObject());
  }

  public function setExceptionObject($exception)
  {
    parent::setExceptionObject(serialize($exception));
  }

  public function getRequest()
  {
    return @unserialize(parent::getRequest()->getContents());
  }

  public function setRequest($request)
  {
    parent::setRequest(serialize($request));
  }
}
