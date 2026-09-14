<?php

namespace Sprint\Migration;


use Bitrix\Highloadblock\HighloadBlockRightsTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use CGroup;

class Version20231016162011 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        if (Loader::includeModule('highloadblock')) {

            $groupId = CGroup::GetIDByCode('PERSONNEL_DEPARTMENT');
            $taskId = 0;
            $taskQuery = \CTask::GetList(['LETTER' => 'ASC'], ['MODULE_ID' => 'highloadblock', 'NAME' => 'hblock_write']);
            while ($task = $taskQuery->getNext()) {
                $taskId = $task['ID'];
            }

            $hlBadgesQuery = HighloadBlockTable::getList([
                'filter' => [
                    'NAME' => ['BusinessBadges', 'UsersBusinessBadges']
                ]
            ]);

            while ($hlBadges = $hlBadgesQuery->fetch()) {
                HighloadBlockRightsTable::add([
                    'HL_ID' => $hlBadges['ID'],
                    'ACCESS_CODE' => 'G'.$groupId,
                    'TASK_ID' => $taskId
                ]);
            }
        }
    }

    public function down()
    {
        //your code ...
    }
}
