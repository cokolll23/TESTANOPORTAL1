<?php

namespace Sprint\Migration;


use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Korus\Office\EventHandlers\BusinessBadges;

class Version20231016135552 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        if ($event = $this->getEvent()) {
            $eventManager = EventManager::getInstance();
            $eventManager->registerEventHandler(...$event);
        }
    }

    public function down()
    {
        if ($event = $this->getEvent()) {
            $eventManager = EventManager::getInstance();
            $eventManager->unRegisterEventHandler(...$event);
        }
    }

    private function getEvent(): array
    {
        if (Loader::includeModule('highloadblock') && Loader::includeModule('korus.office')) {

            $hlBusinessBadgesQuery = HighloadBlockTable::getList([
                'filter' => [
                    '=NAME' => 'BusinessBadges'
                ]
            ]);

            if ($hlBusinessBadges = $hlBusinessBadgesQuery->fetch()) {
                $businessBadgesEntity = HighloadBlockTable::compileEntity($hlBusinessBadges);

                return ['', $businessBadgesEntity->getName() . 'OnBeforeDelete', 'korus.office', BusinessBadges::class, 'OnBeforeDelete'];
            }
        }

        return [];
    }
}
