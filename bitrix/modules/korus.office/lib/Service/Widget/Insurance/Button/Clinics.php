<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget\Insurance\Button;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use CFile;
use Korus\Office\Service\Widget\Button;

Loc::loadMessages(__FILE__);

class Clinics implements Button
{
    public static function get(): array
    {
        $clinicsFileId = Option::get('korus.office', 'insurance_clinics', 0);

        if (!empty($clinicsFileId)) {
            return [
                'LABEL' => Loc::getMessage("INSURANCE_CLINICS_LABEL"),
                'ICON' => 'medicine-box',
                'URL' => CFile::GetPath($clinicsFileId),
            ];
        }

        return [];
    }
}
