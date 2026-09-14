<?php

namespace Korus\Office\Formatter\Gratitude;

use Bitrix\Main\ORM\Fields;
use Korus\Office\Formatter\AbstractFormatter;

class Badge extends AbstractFormatter
{
    protected static function getFields() : array
    {
        return [
            new Fields\IntegerField('ID'),
            new Fields\IntegerField('SORT'),
            new Fields\StringField('CODE'),
            new Fields\StringField('NAME')
        ];
    }
}
