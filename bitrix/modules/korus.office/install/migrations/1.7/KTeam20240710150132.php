<?php

declare(strict_types=1);

namespace Sprint\Migration;

use Bitrix\Main\EventManager;
use Korus\Office\EventHandlers\MainServiceWidget;

class KTeam20240710150132 extends Version
{
    protected $description = "add MainServiceWidget events";

    protected $moduleVersion = "4.6.2";

    public function up(): void
    {
        $eventManager = EventManager::getInstance();

        foreach (static::getEvents() as $event) {
            $eventManager->registerEventHandler(...$event);
        }
    }

    public function down(): void
    {
        $eventManager = EventManager::getInstance();

        foreach (static::getEvents() as $event) {
            $eventManager->unRegisterEventHandler(...$event);
        }
    }

    protected static function getEvents(): array
    {
        return [
            [
                'iblock',
                'OnBeforeIBlockElementDelete',
                'korus.office',
                MainServiceWidget::class,
                'checkDeleteDefaultWidget',
            ],
            [
                'iblock',
                'OnBeforeIBlockElementUpdate',
                'korus.office',
                MainServiceWidget::class,
                'checkEditCodeDefaultWidget',
            ],
        ];
    }

}
