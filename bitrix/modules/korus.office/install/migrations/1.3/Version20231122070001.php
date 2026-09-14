<?php

namespace Sprint\Migration;

use Bitrix\Main\Loader;
use CFile;
use CIBlockElement;
use Sprint\Migration\Helpers\HlblockHelper;

class Version20231122070001 extends Version
{
    protected $description = "transfer old badges";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        $badges = $this->getBadges();

        if (empty($badges)) {
            return;
        }

        $badgesUsers = $this->getBadgesUsers();

        $this->addBadges($badges, $badgesUsers);
    }

    private function getBadges(): array
    {
        $badges = [];
        $ibHelper = $this->getHelperManager()->Iblock();

        $iblockId = $ibHelper->getIblockId('KORUS_BADGES', 'badges');

        if ($iblockId === 0) {
            return $badges;
        }

        $dbRes = $this->getDbResult(
            ['IBLOCK_ID' => $iblockId],
            ['ID', 'NAME', 'PREVIEW_PICTURE', 'PROPERTY_COLOR']
        );

        while ($badge = $dbRes->Fetch()) {
            $badges[$badge['ID']] = ['NAME' => $badge['NAME'], 'COLOR' => $badge['PROPERTY_COLOR_VALUE']];

            if ($imgId = $badge['PREVIEW_PICTURE']) {
                $badges[$badge['ID']]['IMG'] = $this->rewriteImage($imgId);
            }
        }

        return $badges;
    }

    private function getBadgesUsers(): array
    {
        $badgesUsers = [];
        $ibHelper = $this->getHelperManager()->Iblock();

        $iblockId = $ibHelper->getIblockId('KORUS_BADGES_USERS', 'badges1');

        if ($iblockId === 0) {
            return $badgesUsers;
        }

        $dbRes = $this->getDbResult(
            ['IBLOCK_ID' => $iblockId],
            ['ID', 'DATE_CREATE', 'PREVIEW_PICTURE', 'PROPERTY_USER', 'PROPERTY_BADGES']
        );

        while ($badgesUser = $dbRes->Fetch()) {
            $badgesUsers[$badgesUser['PROPERTY_BADGES_VALUE']][$badgesUser['PROPERTY_USER_VALUE']] = [
                'USER' => (int)$badgesUser['PROPERTY_USER_VALUE'],
                'DATE_CREATE' => $badgesUser['DATE_CREATE'],
            ];
        }

        return $badgesUsers;
    }

    private function getDbResult(array $filter, array $select)
    {
        return CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            $select
        );
    }

    private function rewriteImage($imgId)
    {
        $result = null;
        $arImg = CFile::MakeFileArray($imgId);

        if (empty($arImg)) {
            return $result;
        }

        $arImg['MODULE_ID'] = 'highloadblock';
        $arImg['old_file'] = $imgId;

        $newFile = (int)CFile::SaveFile($arImg, 'highloadblock');

        if ($newFile > 0) {
            $result = CFile::MakeFileArray($newFile);
        }

        return $result;
    }

    private function addBadges(array $badges, array $badgesUsers): void
    {
        Loader::includeModule('highloadblock');
        $hlHelper = $this->getHelperManager()->Hlblock();

        foreach ($badges as $key => $badge) {
            $badgeId = $hlHelper->addElement(
                'BusinessBadges',
                [
                    'UF_NAME' => $badge['NAME'],
                    'UF_COLOR' => $badge['COLOR'],
                    'UF_IMG' => $badge['IMG'],
                ]
            );

            if ($badgeId > 0 && $badgesUsers[$key]) {
                $this->addBadgesUsers($badgesUsers[$key], $badgeId, $hlHelper);
            }
        }
    }

    private function addBadgesUsers(array $badgesUsers, int $badgeId, HlblockHelper $hlHelper): void
    {
        foreach ($badgesUsers as $badgesUser) {
            $hlHelper->addElement(
                'UsersBusinessBadges',
                [
                    'UF_USER' => $badgesUser['USER'],
                    'UF_BUSINESS_SIGNS' => $badgeId,
                    'UF_DATE' => $badgesUser['DATE_CREATE'],
                ],
            );
        }
    }

    public function down()
    {
        //your code ...
    }
}
