<?php

namespace Korus\Office\Manager;

use Bitrix\Main\Config\Option;
use Bitrix\Im\Department;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Korus\Main\Helpers\Page;
use Korus\Main\Orm\Iblock\Manager;
use Korus\Exchange\Entity\PositionToDepartmentTable;


Loader::includeModule('korus.main');
/**
 * Манагер для работы с подразделениями. ИБ Подразделения (departments)
 *
 * @author ASayants
 */
class Departments
{
    /** @var \Bitrix\Main\Entity\DataManager|string */
    protected $departmentClassName;
    /** @var int ID ИБ Подразделения */
    protected $departmentIbId;
    /** @var \CIBlockSection */
    protected $sectionOb;

    public function __construct()
    {
        Page::initModules(['im']);
        $this->departmentIbId = (int)Option::get('intranet', 'iblock_structure');
        $this->departmentClassName = Manager::getInstance()->getProvider($this->departmentIbId)->getSectionTableClassName();
        $this->sectionOb = new \CIBlockSection();
    }

    /**
     * Получаем цепочку родителей раздела
     * @param int $depId ID раздела
     * @return array
     */
    public function getChainParents(int $depId) : array
    {
        $result = [];

        $parentSectionIterator = $this->departmentClassName::getList([
            'select' => [
                'SECTION_ID' => 'SECTION_SECTION.ID',
                'PREV_SECTION_ID' => 'SECTION_SECTION.IBLOCK_SECTION_ID',
                'PREV_SECTION_NAME' => 'SECTION_SECTION.NAME',
            ],
            'filter' => [
                '=ID' => $depId,
            ],
            'runtime' => [
                'SECTION_SECTION' => [
                    'data_type' => '\Bitrix\Iblock\SectionTable',
                    'reference' => [
                        '=this.IBLOCK_ID' => 'ref.IBLOCK_ID',
                        '>=this.LEFT_MARGIN' => 'ref.LEFT_MARGIN',
                        '<=this.RIGHT_MARGIN' => 'ref.RIGHT_MARGIN',
                    ],
                    'join_type' => 'inner'
                ],
            ],
            'order' => ['SECTION_SECTION.LEFT_MARGIN' => 'ASC']
        ]);

        while ($parentSection = $parentSectionIterator->fetch()) {
            $result[] = [
                'id' => $parentSection['SECTION_ID'],
                'name' => $parentSection['PREV_SECTION_NAME'],
                'href' => '/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=' . $parentSection['SECTION_ID'],
            ];
        }

        return $result;
    }
}
