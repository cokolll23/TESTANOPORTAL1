<?php

namespace Korus\Office\Formatter\Gratitude;

use Bitrix\Main\ORM\Fields;
use Korus\Office\Formatter\AbstractFormatter;

class Post extends AbstractFormatter
{
    protected static function getFields() : array
    {
        return [
            new Fields\IntegerField('ID'),
            new Fields\IntegerField('AUTHOR_ID'),
            new Fields\IntegerField('UF_GRATITUDE'),
            (new Fields\BooleanField('MICRO'))
                ->configureValues('N', 'Y'),
            new Fields\IntegerField('BADGE_ID')
        ];
    }
}
