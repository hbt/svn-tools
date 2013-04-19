<?php
class vbFormatterUtils
{
    public static function formatUcfirst($value)
    {
        return ucfirst($value);
    }

    public static function formatBooleanYesNo($value)
    {
        return $value == 1 ? 'Yes' : 'No';
    }

    public static function formatUnserialize($value)
    {
        return unserialize($value);
    }

    public static function setterSerialize($value)
    {
        return serialize($value);
    }
}
?>