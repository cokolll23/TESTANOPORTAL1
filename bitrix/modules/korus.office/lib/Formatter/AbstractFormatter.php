<?php

namespace Korus\Office\Formatter;

use Bitrix\Main\ORM\Fields;

abstract class AbstractFormatter
{
    public static function format(array $data) : array
    {
        /** @var Fields\ScalarField $field */
        foreach (static::getFields() as $field) {
            $name = $field->getName();
            if (isset($data[$name])) {
                $data[$name] = $field->cast($data[$name]);
            }
        }

        return $data;
    }

    abstract protected static function getFields() : array;
}
