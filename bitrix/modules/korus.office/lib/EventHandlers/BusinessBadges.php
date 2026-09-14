<?php

namespace Korus\Office\EventHandlers;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\EntityError;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\ORM\EventResult;
use Throwable;

class BusinessBadges
{
    /**
     * Удаляет записи из таблицы "Бизнес-значки пользователей" связанные со значком
     *
     * @param Event $event
     * @return EventResult
     */
    public static function OnBeforeDelete(Event $event): EventResult
    {
        $result = new EventResult();
        try {
            $businessBadgeId = $event->getParameter('id')['ID'];

            Loader::includeModule('highloadblock');

            $hlUsersBusinessBadgesQuery = HighloadBlockTable::getList([
                'filter' => [
                    '=NAME' => 'UsersBusinessBadges'
                ]
            ]);

            if ($hlUsersBusinessBadges = $hlUsersBusinessBadgesQuery->fetch()) {

                /**
                 * @var Entity $usersBusinessBadgesEntity
                 * @var DataManager $usersBusinessBadgesClass
                 */
                $usersBusinessBadgesEntity = HighloadBlockTable::compileEntity($hlUsersBusinessBadges);
                $usersBusinessBadgesClass = $usersBusinessBadgesEntity->getDataClass();

                $usersBusinessBadgesRecords = $usersBusinessBadgesClass::query()
                    ->addFilter('UF_BUSINESS_SIGNS', $businessBadgeId)
                    ->addSelect('ID')
                    ->fetchAll();


                foreach ($usersBusinessBadgesRecords as $usersBusinessBadgesRecord) {
                    $usersBusinessBadgesClass::delete($usersBusinessBadgesRecord['ID']);
                }
            }
        } catch (Throwable $exception) {
            $result->addError((new EntityError($exception->getMessage())));
        }

        return $result;
    }
}