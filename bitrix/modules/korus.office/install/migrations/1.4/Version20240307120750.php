<?php

declare(strict_types=1);

namespace Sprint\Migration;

use Bitrix\Main\EventManager;
use Korus\Office\EventHandlers\CUITooltipComponentAjaxController;

class Version20240307120750 extends Version
{
    protected $description = "delete CUITooltipComponentAjaxController::onAfterAction v1 on korus.office";

    protected $moduleVersion = "4.6.2";

    public function up(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'main',
            'CUITooltipComponentAjaxController::onAfterAction',
            'korus.office',
            CUITooltipComponentAjaxController::class,
            'onAfterAction'
        );

        $eventManager->registerEventHandler(
            'main',
            'CUITooltipComponentAjaxController::onAfterAction',
            'korus.office',
            CUITooltipComponentAjaxController::class,
            'onAfterAction'
        );
    }

    public function down()
    {
        //your code ...
    }
}
