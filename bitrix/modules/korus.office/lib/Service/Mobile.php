<?php

namespace Korus\Office\Service;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;

class Mobile extends Service
{
    public function getOperator() : string
    {
        return 'ПАО "МТС"';
    }

    public function getTariff() : string
    {
        return 'Корпоративный';
    }

    public function getTariffDetails() : string
    {
        return sprintf(
            '%s<br>%s<br>%s',
            '100 минут исходящих вызовов',
            '100 СМС',
            '6 Гб'
        );
    }

    public function getWidgetDetails() : string
    {
        $resultHtml = sprintf('Тариф "%s"<br>', $this->getTariff());
        $resultHtml .= sprintf('Оператор: %s', $this->getOperator());
        $resultHtml .= $this->getTariffDetails();

        return $resultHtml;
    }
}
