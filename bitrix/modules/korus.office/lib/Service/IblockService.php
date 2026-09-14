<?php

namespace Korus\Office\Service;

use Bitrix\Main\EO_User;
use KTeam\Main\EO_User as KEO_User;

class IblockService extends Service
{
    /** @var KEO_Section */
    protected $section;

    public function __construct($section, EO_User|KEO_User $user)
    {
        parent::__construct($user);
        $this->section = $section;
    }

    public function getWidgetTitle() : string
    {
        return $this->section->getName();
    }

    public function getWidgetImage() : string
    {
        $imgId = $this->section->getPicture();
        if (empty($imgId)) {
            return '';
        }

        return \CFile::GetPath($imgId);
    }

    public function getWidgetColor() : string
    {
        return (string)($this->section->get('UF_COLOR') ?: $this->section->get('PARENT_SECTION')->get('UF_COLOR'));
    }

    public function getWidgetButtons() : array
    {
        $result = [];

        $link = $this->section->get('UF_LINK');
        if ($link) {
            $result[] = [
                'LABEL' => 'Перейти',
                'ICON' => 'arrow-circle',
                'URL' => $link
            ];
        }

        return $result;
    }

    public function getWidgetDetails() : string
    {
        return $this->section->getDescription();
    }
}
