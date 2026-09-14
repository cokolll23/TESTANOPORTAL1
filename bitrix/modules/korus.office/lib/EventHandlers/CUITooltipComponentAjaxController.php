<?php

namespace Korus\Office\EventHandlers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Korus\Office\Entity\OfficeFieldSettingsTable;

class CUITooltipComponentAjaxController
{
    /**
     * Фильтрует поля карточки тултипа
     *
     * @param Event $event
     * @return EventResult
     */
    public static function onAfterAction(Event $event): EventResult
    {
        $eventResult = $event->getParameter('result');
        $cardFields = $eventResult['user']['cardFields'];

        try {
            $fieldSettingsQuery = OfficeFieldSettingsTable::getList([
                'select' => ['VIEW', 'FIELD_CODE']
            ])->fetchAll();

            if(!empty($fieldSettingsQuery)) {
                $fieldSettingsQuery = array_merge($fieldSettingsQuery, OfficeFieldSettingsTable::getPrimaryFieldSettings());
                $fieldViewSettings = array_column($fieldSettingsQuery, 'VIEW', 'FIELD_CODE');

                $cardFieldsToShow = [];

                foreach ($cardFields as $fieldCode => $field) {
                    if ((int)$fieldViewSettings[$fieldCode] === OfficeFieldSettingsTable::VIEW['SHOW']) {
                        $cardFieldsToShow[$fieldCode] = $field;
                    }
                }

                $eventResult['user']['cardFields'] = $cardFieldsToShow;
                $event->setParameter('result', $eventResult);
            }

            return new EventResult(EventResult::SUCCESS);
        } catch (ObjectPropertyException|SystemException $e) {
            return new EventResult(EventResult::ERROR);
        }
    }
}