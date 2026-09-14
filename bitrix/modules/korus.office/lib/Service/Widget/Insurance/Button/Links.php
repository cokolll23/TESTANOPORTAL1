<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget\Insurance\Button;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Korus\Office\Service\Widget\Button;

Loc::loadMessages(__FILE__);

class Links implements Button
{
    public static function get(): array
    {
        $appLinks = Option::get('korus.office', 'insurance_links', []);

        if ($appLinks) {
            $appLinks = unserialize($appLinks);

            foreach ($appLinks as $label => $link) {
                if (!empty($link)) {
                    $appLinksDropdown[] = [
                        'LABEL' => $label,
                        'HREF' => $link,
                        // TODO: add target in settings page
                        'TARGET' => '_blank',
                    ];
                }
            }

            if (!empty($appLinksDropdown)) {
                return [
                    'LABEL' => Loc::getMessage("INSURANCE_LINKS_LABEL"),
                    'ICON' => 'mobile-phone',
                    'OPTIONS' => $appLinksDropdown
                ];
            }
        }

        return [];
    }
}
