<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget\Insurance\Button;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use CFile;
use Korus\Office\Service\Widget\Button;

Loc::loadMessages(__FILE__);

class Service implements Button
{
    public static function get(): array
    {
        $servicesFileId = Option::get('korus.office', 'insurance_services', 0);

        if (!empty($servicesFileId)) {
            return [
                'LABEL' => Loc::getMessage("INSURANCE_SERVICE_LABEL"),
                'ICON' => 'clipboard-bullet-list',
                'URL' => CFile::GetPath($servicesFileId),
            ];
        }

        return [];
    }
}
