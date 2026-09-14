<?php

declare(strict_types=1);

namespace Korus\Office\EventHandlers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Korus\Office\Service\MainWidgetService;

class MainServiceWidget
{
    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function checkDeleteDefaultWidget($id): bool
    {
        $id = (int) $id;

        if ($id > 0) {
            $mainWidgetService = new MainWidgetService();
            if ($mainWidgetService->isWidgetDefault($id)) {
                global $APPLICATION;
                $APPLICATION->throwException(Loc::getMessage('CANT_DELETE_DEFAULT_WIDGET'));
                return false;
            }
        }

        return true;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function checkEditCodeDefaultWidget(array $arFields): bool
    {
        if (!isset($arFields['CODE'])) {
            return true;
        }

        $mainWidgetService = new MainWidgetService();
        $isChange = in_array(
            $arFields['CODE'],
            $mainWidgetService->getDefaultWidgetsCode(),
            true
        );

        if (!$isChange && $mainWidgetService->isWidgetDefault((int) $arFields['ID'])) {
            global $APPLICATION;
            $APPLICATION->throwException(Loc::getMessage('CANT_EDIT_DEFAULT_WIDGET'));
            return false;
        }

        return true;
    }

}