<?php

namespace Sprint\Migration;


use Bitrix\Main\EventManager;
use Korus\Office\EventHandlers\CUITooltipComponentAjaxController;

class Version20231113092952 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler('main',
            'CUITooltipComponentAjaxController::onAfterAction',
            'korus.office',
            CUITooltipComponentAjaxController::class,
            'onAfterAction');
    }

    public function down()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main',
            'CUITooltipComponentAjaxController::onAfterAction',
            'korus.office',
            CUITooltipComponentAjaxController::class,
            'onAfterAction');
    }
}
