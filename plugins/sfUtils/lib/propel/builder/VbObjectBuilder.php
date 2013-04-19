<?php
require_once '../../propel/builder/SfObjectBuilder.php';

/**
 * person:
    id:
    name:
      type: varchar(255)
      required: true
      formatter: ucfirst
      ff_name: getMyNameFormatted
    bio:
      type: varchar(255)
      required: true
      formatter: ucfirst, booleanYesNo
    imdb_id:
      type: varchar(255)
      required: true
    title_array:
      type: longvarchar
      setter: serializeArray
      setter_name: setSerializedTitle
    created_at:
    updated_at:
    _uniques:
      person_imdb_id_uq: [imdb_id]
 */
class VbObjectBuilder extends SfObjectBuilder
{

    protected function addSave(& $script)
    {
        parent :: addSave($script);

        // add formatters methods
        $this->addMethodsToColumns($script);
        $this->addBooleanGetters($script);

    }

    /**
     * calls the functions based on the attributes assigned to the columns in the schema
     *
     * formatter: static function name from vbFormatterUtils
     * ff_name: function name to appear in BaseXXX file by default = getFormattedColumnName
     */
    protected function addMethodsToColumns(& $script)
    {
        $columns = $this->getTable()->getColumns();
        foreach ($columns as $column)
        {
            $attributes = $column->getAttributes();
            if (isset ($attributes['formatter']))
            {
                $functionName = 'getFormatted' . $column->getPhpName();
                if (isset ($attributes['ff_name']))
                {
                    $functionName = $attributes['ff_name'];
                }

                $this->addFormattedMethod($script, $column, $functionName, $attributes['formatter']);
            }

            if (isset ($attributes['setter']))
            {
                $functionName = 'setFormatted' . $column->getPhpName();
                if (isset ($attributes['setter_name']))
                {
                    $functionName = $attributes['setter_name'];
                }

                $this->addSetterFormattedMethod($script, $column, $functionName, $attributes['setter']);
            }
        }
    }

    /**
     * add is function for boolean colums
     */
    protected function addBooleanGetters(& $script)
    {
        $columns = $this->getTable()->getColumns();
        foreach ($columns as $column)
        {
            if ($column->getPhpType() == 'boolean')
            {
                $script .= '

      public function is' . $column->getPhpName() . '()
      {
    		return $this->get' . $column->getPhpName() . '();
       }';
            }
        }
    }

    protected function addSetterFormattedMethod(& $script, $column, $functionName, $attributeValue)
    {
        $attributeValue = trim($attributeValue);

        // extract values if there is more than one formatter
        if (strpos($attributeValue, ','))
        {
            $attributeValue = str_ireplace(' ', '', $attributeValue);
            $attributeValues = explode(',', $attributeValue);

        }
        else
        {
            $attributeValues = array (
                $attributeValue
            );

        }

        // build string
        $myScript = '';
        $firstIteration = true;
        foreach ($attributeValues as $v)
        {
            if ($firstIteration)
            {
                $variable = '$v';
                $firstIteration = false;
            }
            else
            {
                $variable = '';
            }
            $begin = 'vbFormatterUtils::setter' . ucfirst($v) . '(' . $variable;
            $end = ')';
            $tmpScript = $begin . $myScript . $end;
            $myScript = $tmpScript;
        }

        $script .= '

    public function ' . $functionName . '($v)
    {
          return ' . '$this->set' . $column->getPhpName() . '(' . $myScript . ');
     }';
    }

    /**
     * add formatters for dates, strings, etc. for columns
     */
    protected function addFormattedMethod(& $script, $column, $functionName, $attributeValue)
    {
        $attributeValue = trim($attributeValue);

        // extract values if there is more than one formatter
        if (strpos($attributeValue, ','))
        {
            $attributeValue = str_ireplace(' ', '', $attributeValue);
            $attributeValues = explode(',', $attributeValue);

        }
        else
        {
            $attributeValues = array (
                $attributeValue
            );

        }

        // build string
        $myScript = '';
        $firstIteration = true;
        foreach ($attributeValues as $v)
        {
            if ($firstIteration)
            {
                $variable = '$this->get' . $column->getPhpName() . '()';
                $firstIteration = false;
            }
            else
            {
                $variable = '';
            }
            $begin = 'vbFormatterUtils::format' . ucfirst($v) . '(' . $variable;
            $end = ')';
            $tmpScript = $begin . $myScript . $end;
            $myScript = $tmpScript;
        }

        $script .= '

    public function ' . $functionName . '()
    {
          return ' . $myScript . ';
     }';
    }

}