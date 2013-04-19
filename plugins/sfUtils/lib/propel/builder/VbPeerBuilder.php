<?php
require_once '../../propel/builder/SfPeerBuilder.php';
/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage addonsf c
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: SfPeerBuilder.php 2534 2006-10-26 17:13:50Z fabien $
 */
class VbPeerBuilder extends SfPeerBuilder {
	protected function addSelectMethods(& $script) {
		parent :: addSelectMethods($script);
		$this->addRetrieveByProperty($script);
		$this->addRetrieveByProperties($script);
		$this->addPopulateSelectiveColumnsObjects($script);
	}

	protected function addRetrieveByProperty(& $script) {
		$script .= '

				  public static function retrieveByProperty($property, $value, $criterion = Criteria::EQUAL)
				  {
				        $criteria = new Criteria();
				        $criteria->add($property, $value, $criterion);
				        $v = ' . $this->getPeerClassname() . '::doSelect($criteria);

				        return !empty($v) > 0 ? $v[0] : null;
				   }';
	}

	protected function addRetrieveByProperties(& $script) {
		foreach ($this->getTable()->getColumns() as $col) {
			$script .= '
						    public static function retrieveBy' . $col->getPhpName() . '($value, $criterion = Criteria::EQUAL) {
						      return self::retrieveByProperty (' . $this->getPeerClassname() . '::' . strtoupper($col->getName()) . ', $value, $criterion);
						    }';
		}
	}

	protected function addPopulateSelectiveColumnsObjects(& $script) {
		$script .= '

private static $resultSetColumnTypes = array (';
		foreach ($this->getTable()->getColumns() as $col) {
			$affix = CreoleTypes :: getAffix(CreoleTypes :: getCreoleCode($col->getType()));

			$script .= '\'' . $col->getName() . '\' => \'' . $affix . '\', ';

		}

		$script .= ');';

        $script .= '

public static function populateObjectsSelectedColumns (ResultSet $rs, array $columns) {
	$results = array();
                $cls = ' .  'self::getOMClass();
        $cls = Propel::import($cls);
                while($rs->next()) {
                    $obj = new $cls();
                    foreach ($columns as $column) {
                        if (!array_key_exists($column, self::$resultSetColumnTypes)) {
                        	throw new Exception ("Column  " .  $column . " does not exist");
                        }

                        $phpSetMethod = \'set\' . self::translateFieldName($column, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_PHPNAME);
                        $resultSetColumnGet =  \'get\' .self::$resultSetColumnTypes[$column];
                        $obj->$phpSetMethod($rs->$resultSetColumnGet($column));

                }


            $results[] = $obj;

        }
        return $results;
}
                        ';
	}
}