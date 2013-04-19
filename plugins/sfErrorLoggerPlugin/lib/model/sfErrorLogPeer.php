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
 * @version    SVN: $Id: sfErrorLogPeer.php 7911 2008-03-15 22:05:07Z fabien $
 */
class sfErrorLogPeer extends BasesfErrorLogPeer
{
  static public function deleteAllSimilar($error)
  {
    if (is_null($error))
    {
      return;
    }

    $c = new Criteria();

    $c->add(self::TYPE,        $error->getType());
    $c->add(self::CLASS_NAME,  $error->getClassName());
    $c->add(self::MODULE_NAME, $error->getModuleName());
    $c->add(self::ACTION_NAME, $error->getActionName());
    $c->add(self::MESSAGE,     $error->getMessage());
    $c->add(self::URI,         $error->getUri());

    self::doDelete($c);
  }
}
