<?php

namespace Korus\Personalarea\Onec;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Config\Option;
use DateTime;
use CFile;
use CIBlockElement;
use CIBlockPropertyEnum;
use CIBlockSection;
use CIBlockXMLFile;
use CUser;
use CUserCMLImport as BitrixCMLImport;
use Korus\Personalarea\Doctrine\Entity\Absence;
use Korus\Personalarea\Doctrine\Entity\BUser;
use Korus\Personalarea\Helpers\DoctrineHelper;

IncludeModuleLangFile(__FILE__);

/**
 * @codeCoverageIgnore
 */
class Cml2 extends BitrixCMLImport
{
    public static $absenceMapping = [
        'LEAVESICK' => [
            'Болеет' => '',
           // 'Болезнь без оплаты' => '',
        ],
        'VACATION' => [
            'В ежегодном отпуске' => '',
        ],
        'ASSIGNMENT' => [
            'В командировке' => '',
        ],
        'LEAVEUNPAYED' => [
            'В отпуске без сохранения зарплаты' => '',
        ],
        'PREGNANT' => [
            'В отпуске по беременности и родам' => '',
        ],
        'CHILD' => [
            'В отпуске по уходу за ребенком' => '',
        ],
        'OTHER' => [
            'В учебном оплачиваемом отпуске' => 'Учебный отпуск',
            'Дополнительные выходные дни не оплачиваемые' => 'Отгул',
            'Дополнительные выходные дни (оплачиваемые)' => 'Дополнительный выходной день (льготы,гос.обязанности)',
            'Дополнительные отпуск' => 'Дополнительный отпуск',
            'Отсутствие с сохранением оплаты' => 'Донор, воен сборы',
            'Отсутствует по невыясненной причине' => 'Неявка по невыясненной причине',
            'Прогулы' => 'Прогул',
        ],
    ];
    protected $absenceComment = '';
    private $em;

    function ImportMetaData($xml_root_id = false)
    {
        global $DB;

        if (!$this->arParams['SKIP_STRUCTURE_CHECK'] && !$this->CheckStructure()) {
            return false;
        }

        //todo - надо проверить
//        $DB->Query('UPDATE b_iblock_section SET ACTIVE = "N" WHERE DEPTH_LEVEL > 1 AND IBLOCK_ID = ' . Option::get('intranet',
//                'iblock_structure', 0));

        if (null == $this->__ibxml) {
            $this->__ibxml = new CIBlockXMLFile();
        }

        $XML_DEPARTMENTS_PARENT = false;
        $XML_PROPERTIES_PARENT = false;

        if ($xml_root_id <= 0) {
            $rs = $DB->Query(sprintf('SELECT MIN(PARENT_ID) MIN_ID FROM b_xml_tree WHERE NAME="%s"',
                GetMessage('IBLOCK_XML2_USER_TAG_CLASSIFIER')));
            $ar = $rs->Fetch();
            $xml_root_id = $ar["MIN_ID"];
        }

        $rs = $DB->Query(sprintf('SELECT ID, ATTRIBUTES FROM b_xml_tree WHERE PARENT_ID = %d AND NAME="%s"',
            intval($xml_root_id), GetMessage('IBLOCK_XML2_USER_TAG_CLASSIFIER')));
        if ($ar = $rs->Fetch()) {
            if (strlen($ar["ATTRIBUTES"]) > 0) {
                $attrs = unserialize($ar["ATTRIBUTES"]);
                if (is_array($attrs)) {
                    if (array_key_exists(GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY'), $attrs)) {
                        $this->bUpdateOnly = ($attrs[GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY')] == 'true') || (intval($attrs[GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY')]) ? true : false);
                    }
                }
            }

            $rs = $DB->Query(sprintf("select * from b_xml_tree where PARENT_ID = %d order by ID", $ar["ID"]));
            while ($ar = $rs->Fetch()) {
                switch ($ar['NAME']) {
                    case GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENTS'):
                        $XML_DEPARTMENTS_PARENT = $ar['ID'];
                        break;
                    case GetMessage('IBLOCK_XML2_USER_TAG_PROPERTIES'):
                        $XML_PROPERTIES_PARENT = $ar['ID'];
                        break;
                    default:
                        break;
                }
            }

            if ($XML_DEPARTMENTS_PARENT) {
                $this->arDepartments = $this->__ibxml->GetAllChildrenarray($XML_DEPARTMENTS_PARENT);
                if (!$this->LoadDepartments($this->arDepartments,
                    $this->GetStructureRoot())) {
                    return false;
                }

                $this->next_step['_TEMPORARY']['DEPARTMENTS'] = $this->arSectionCache;
            }

            if ($XML_PROPERTIES_PARENT) {
                $this->arProperties = $this->__ibxml->GetAllChildrenarray($XML_PROPERTIES_PARENT);

                foreach ($this->arProperties as $arPropertyData) {
                    $this->GetPropertyByXML_ID($arPropertyData[GetMessage('IBLOCK_XML2_USER_TAG_ID')], [
                        'XML_ID' => $arPropertyData[GetMessage('IBLOCK_XML2_USER_TAG_ID')],
                        'NAME' => $arPropertyData[GetMessage('IBLOCK_XML2_USER_TAG_NAME')],
                    ]);
                }
            }
        }

        return true;
    }

    function LoadDepartments($arDepts = null, $PARENT_ID = null)
    {
        if (null == $arDepts) {
            $arDepts = $this->arDepartments;
        }

        $obSection = new CIBlockSection();

        foreach ($arDepts as $arDeptData) {
            $XML_ID = $arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_ID')];
            if ($SECTION_ID = $this->GetSectionByXML_ID($this->DEPARTMENTS_IBLOCK_ID, $XML_ID)) {
                $dbRes = $obSection->GetByID($SECTION_ID);
                $arCurrentSection = $dbRes->Fetch();
            }

            $arFields = [
                'ACTIVE' => ($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_STATUS')] === GetMessage('IBLOCK_XML2_USER_VALUE_DELETED') ? 'N' : 'Y'),
                'IBLOCK_ID' => $this->DEPARTMENTS_IBLOCK_ID,
                'IBLOCK_SECTION_ID' => intval($PARENT_ID),
                'EXTERNAL_ID' => $XML_ID,
                'NAME' => $arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_NAME')],
                //'SORT' => 100,
            ];

            $bStoreHead = false;
            if (isset($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD')])) {
                if ($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD')]) {
                    if ($arUser = $this->GetUserByXML_ID($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD')])) {
                        $arFields['UF_HEAD'] = $arUser['ID'];
                    } else {
                        $bStoreHead = true;
                    }
                } else {
                    $arFields['UF_HEAD'] = '';
                }
            }

            /* Импортируем заместителя вышестоящего подразделения */
            if (isset($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD_ZAM')])) {
                if ($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD_ZAM')]) {
                    if ($arUser = $this->GetUserByXML_ID($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD_ZAM')])) {
                        $arFields['UF_HEAD_ZAM'] = $arUser['ID'];
                    } else {
                        $bStoreHead = true;
                    }
                } else {
                    $arFields['UF_HEAD_ZAM'] = '';
                }
            }

            /* Импортируем признак структурного подразделения*/
            if (isset($arDeptData[GetMessage('IBLOCK_XML2_IS_STRUCTURE')])) {
                if ($arDeptData[GetMessage('IBLOCK_XML2_IS_STRUCTURE')] && ($arDeptData[GetMessage('IBLOCK_XML2_IS_STRUCTURE')] == 'true')) {
                    $arFields['UF_STRUCTURAL'] = 1;
                } else {
                    $arFields['UF_STRUCTURAL'] = 0;
                }
            }

            if (!$SECTION_ID) {
                $arFields['SORT'] = 100;
                $SECTION_ID = $obSection->Add($arFields);
                $res = ($SECTION_ID > 0);
                $this->arSectionCache[$this->DEPARTMENTS_IBLOCK_ID][$XML_ID] = $SECTION_ID;
            } else {
                $res = $obSection->Update($SECTION_ID, $arFields);
            }

            if (!$res) {
                $GLOBALS['APPLICATION']->ThrowException($obSection->LAST_ERROR);

                return false;
            }

            if ($bStoreHead) {
                if (!$this->next_step['_TEMPORARY']['DEPARTMENT_HEADS']) {
                    $this->next_step['_TEMPORARY']['DEPARTMENT_HEADS'] = [];
                }
                if (!$this->next_step['_TEMPORARY']['DEPARTMENT_HEADS'][$arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD')]]) {
                    $this->next_step['_TEMPORARY']['DEPARTMENT_HEADS'][$arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD')]] = [];
                }

                $this->next_step['_TEMPORARY']['DEPARTMENT_HEADS'][$arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT_HEAD')]][] = $SECTION_ID;
            }

            if (is_array($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENTS')])) {
                if (!$this->LoadDepartments($arDeptData[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENTS')], $SECTION_ID)) {
                    return false;
                }
            }
        }

        // if (!$PARENT_ID)
        // $obSection->ReSort();

        return true;
    }

    function GetUserByXML_ID($XML_ID)
    {
        $dbRes = CUser::GetList($by = "ID", $order = "ASC", array("XML_ID" => $XML_ID),
            ['SELECT' => ['UF_DISABLE_UPDATE']]);
        return $dbRes->Fetch();
    }

    function ImportUsers($xml_root_id = false, $start_time = false, $interval = 0)
    {
        global $DB;

        $this->em = DoctrineHelper::getEntityManager();

        $this->__user = new CUser();

        if (null == $this->__ibxml) {
            $this->__ibxml = new CIBlockXMLFile();
        }

        if ($start_time === false) {
            $start_time = time();
        }

        $counter = [
            "ADD" => 0,
            "UPD" => 0,
            "DEL" => 0,
            "DEA" => 0,
            "ERR" => 0,
        ];

        if (!$this->next_step["XML_ELEMENTS_PARENT"]) {
            if ($xml_root_id <= 0) {
                $rs = $DB->Query("SELECT MIN(PARENT_ID) MIN_ID FROM b_xml_tree WHERE NAME='" . GetMessage('IBLOCK_XML2_USER_TAG_STRUCTURE') . "'");
                $ar = $rs->Fetch();
                $xml_root_id = $ar["MIN_ID"];
            }

            $query = "SELECT ID, ATTRIBUTES FROM b_xml_tree WHERE PARENT_ID = " . intval($xml_root_id) . " AND NAME='" . GetMessage('IBLOCK_XML2_USER_TAG_STRUCTURE') . "'";
            $rs = $DB->Query($query);
            if ($ar = $rs->Fetch()) {
                if (strlen($ar["ATTRIBUTES"]) > 0) {
                    $attrs = unserialize($ar["ATTRIBUTES"]);
                    if (is_array($attrs)) {
                        if (array_key_exists(GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY'), $attrs)) {
                            $this->bUpdateOnly =
                                ($attrs[GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY')] == 'true') ||
                                (intval($attrs[GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY')]) ? true : false);
                            $this->next_step['bUpdateOnly'] = $this->bUpdateOnly;
                        }
                    }
                }

                $rs = $DB->Query("SELECT ID, ATTRIBUTES FROM b_xml_tree WHERE PARENT_ID = " . intval($ar['ID']) . " AND NAME='" . GetMessage('IBLOCK_XML2_USER_TAG_USERS') . "'");
                if ($ar = $rs->Fetch()) {
                    $this->next_step["XML_ELEMENTS_PARENT"] = $ar['ID'];
                }
            }
        }

        if ($this->next_step["XML_ELEMENTS_PARENT"]) {
            $rsParents = $DB->Query("SELECT ID, LEFT_MARGIN, RIGHT_MARGIN FROM b_xml_tree WHERE PARENT_ID = " . intval($this->next_step["XML_ELEMENTS_PARENT"]) . " AND ID > " . intval($this->next_step["XML_LAST_ID"]) . " ORDER BY ID");

            $q = 0;
            while ($arParent = $rsParents->Fetch()) {
                $arXMLElement = $this->__ibxml->GetAllChildrenarray($arParent);

                $ID = $this->LoadUser($arXMLElement, $counter);

                $this->next_step["XML_LAST_ID"] = $arParent["ID"];

                if ($interval > 0 && (time() - $start_time) > $interval) {
                    break;
                }
            }

            $this->em->flush();
        }

        unset($this->__user);

        return $counter;
    }

    function LoadUser($arXMLElement, &$counter)
    {
        $start_time = microtime(true);
        static $USER_COUNTER = null;

        static $property_state_final = 0;

        if (!is_array($property_state_final)) {
            $property_state_final = [];
            $property_state = CIBlockPropertyEnum::GetList(
                [],
                [
                    "IBLOCK_ID" => $this->STATE_HISTORY_IBLOCK_ID,
                    "CODE" => "STATE",
                ]
            );
            while ($property_state_enum = $property_state->GetNext()) {
                $property_state_final[ToLower($property_state_enum["VALUE"])] = $property_state_enum["ID"];
            }
        }

        $obUser = &$this->__user;

        // this counter'll be used for generating users login name
        if (null == $USER_COUNTER) {
            $dbRes = $GLOBALS['DB']->Query('SELECT MAX(ID) M FROM b_user');
            $ar = $dbRes->Fetch();
            $USER_COUNTER = $ar['M'];
        }

        $CURRENT_USER = false;

        // check user existence
        if ($arCurrentUser = $this->GetUserByXML_ID($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ID')])) {
            $CURRENT_USER = $arCurrentUser['ID'];
        }

        $active = null;
        $unactive = null;
        $statuses = $arXMLElement['ИсторияСостояний'];


        if ($arCurrentUser['UF_DISABLE_UPDATE']) {
        } else {
            foreach ($statuses as $status) {
                if ($status['Значение'] == 'Принят') {
                    if (!$active
                        || $ac > $active) {
                        $active = $ac;
                    }
                }

                if ($status['Значение'] == 'Уволен') {
                    if (!$unactive
                        || $ac > $unactive) {
                        $unactive = $ac;
                    }
                }
            }

            if ($unactive > $active) {
                $dateKill = $unactive->format('d.m.Y');
                $active = false;
            } else {
                $dateKill = !empty($arXMLElement[GetMessage('IBLOCK_XML2_USER_DATE_KILL')]) ? ConvertTimeStamp(MakeTimeStamp($arXMLElement[GetMessage('IBLOCK_XML2_USER_DATE_KILL')],
                    'YYYY-MM-DD')) : '';
                $active = true;
            }


            // common user data
            $arFields = [
                'ACTIVE' => !$active ? 'N' : 'Y',
                'UF_1C' => 'Y',
                'XML_ID' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ID')],
                'LID' => $this->arParams['SITE_ID'],
                'LAST_NAME' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_LAST_NAME')],
                'NAME' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_FIRST_NAME')],
                'SECOND_NAME' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_SECOND_NAME')],
                'PERSONAL_BIRTHDAY' => !empty($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_BIRTH_DATE')]) ? ConvertTimeStamp(MakeTimeStamp($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_BIRTH_DATE')],
                    'YYYY-MM-DD')) : '',
                'PERSONAL_GENDER' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_GENDER')] == GetMessage('IBLOCK_XML2_USER_VALUE_FEMALE') ? 'F' : 'M',
                'UF_INN' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_INN')],
                'WORK_POSITION' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_POST')],
                'PERSONAL_PROFESSION' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_POST')],
                'UF_DATE_GET' => !empty($arXMLElement[GetMessage('IBLOCK_XML2_USER_DATE_GET')]) ? ConvertTimeStamp(MakeTimeStamp($arXMLElement[GetMessage('IBLOCK_XML2_USER_DATE_GET')],
                    'YYYY-MM-DD')) : '',
                'UF_DATE_KILL' => $dateKill,
                'UF_VACATION_DAYS' => !empty($arXMLElement[GetMessage('IBLOCK_XML2_USER_VACATION_DAYS')]) ? $arXMLElement[GetMessage('IBLOCK_XML2_USER_VACATION_DAYS')] : 0,
            ];

            if (array_key_exists(GetMessage('IBLOCK_XML2_USER_TAG_PHOTO'), $arXMLElement)) {
                if ($arCurrentUser['PERSONAL_PHOTO'] > 0) {
                    CFile::Delete($arCurrentUser['PERSONAL_PHOTO']);
                }

                if (strlen($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_PHOTO')]) > 0) {
                    $arFields['PERSONAL_PHOTO'] = $this->MakeFilearray($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_PHOTO')]);
                }
            }

            // address fields
            if (is_array($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ADDRESS')])) {
                foreach ($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ADDRESS')] as $key => $arAddressField) {
                    if (GetMessage('IBLOCK_XML2_USER_TAG_FULLADDRESS') == $key) {
                        $arFields['PERSONAL_STREET'] = $arAddressField;
                    } else {
                        $type = $arAddressField[GetMessage('IBLOCK_XML2_USER_TAG_TYPE')];
                        $value = $arAddressField[GetMessage('IBLOCK_XML2_USER_TAG_VALUE')];
                        switch ($type) {
                            case GetMessage('IBLOCK_XML2_USER_VALUE_ZIP'):
                                $arFields['PERSONAL_ZIP'] = $value;
                                break;
                            case GetMessage('IBLOCK_XML2_USER_VALUE_STATE'):
                                $arFields['PERSONAL_STATE'] = $value;
                                break;
                            case GetMessage('IBLOCK_XML2_USER_VALUE_DISTRICT'):
                                $arFields['UF_DISTRICT'] = $value;
                                break;
                            case GetMessage('IBLOCK_XML2_USER_VALUE_CITY1'):
                            case GetMessage('IBLOCK_XML2_USER_VALUE_CITY2'):
                                if ($arFields['PERSONAL_CITY']) {
                                    $arFields['PERSONAL_CITY'] .= ', ';
                                }
                                $arFields['PERSONAL_CITY'] .= $value;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }

            // contact fields
            if (is_array($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_CONTACTS')])) {
                foreach ($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_CONTACTS')] as $arContactsField) {
                    $type = $arContactsField[GetMessage('IBLOCK_XML2_USER_TAG_TYPE')];
                    $value = $arContactsField[GetMessage('IBLOCK_XML2_USER_TAG_VALUE')];
                    switch ($type) {
                        case GetMessage('IBLOCK_XML2_USER_VALUE_PHONE_INNER'):
                            $arFields['UF_PHONE_INNER'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_PHONE_WORK'):
                            $arFields['WORK_PHONE'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_PHONE_MOBILE'):
                            $arFields['PERSONAL_MOBILE'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_PHONE_PERSONAL'):
                            $arFields['PERSONAL_PHONE'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_PAGER'):
                            $arFields['PERSONAL_PAGER'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_FAX'):
                            $arFields['PERSONAL_FAX'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_EMAIL'):
                            $arFields['EMAIL'] = $value; // b_user.EMAIL
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_ICQ'):
                            $arFields['PERSONAL_ICQ'] = $value;
                            break;
                        case GetMessage('IBLOCK_XML2_USER_VALUE_WWW'):
                            $arFields['PERSONAL_WWW'] = $value;
                            break;
                        default:
                            break;
                    }
                }
            }

            //departments data
            $arFields['UF_DEPARTMENT'] = [];
            if (is_array($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENTS')])) {
                foreach ($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENTS')] as $DEPT_XML_ID) {
                    if ($DEPT_ID = $this->GetSectionByXML_ID($this->DEPARTMENTS_IBLOCK_ID, $DEPT_XML_ID)) {
                        $arFields['UF_DEPARTMENT'][] = $DEPT_ID;
                    }
                }
            }

            // state history
            if (is_array($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE_HISTORY')])) {
                $last_state_date = 0;
                $first_state_date = 1767132000; //strtotime('2025-12-31')
                $arStateHistory = [];

                foreach ($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE_HISTORY')] as $arState) {
                    $state = $arState[GetMessage('IBLOCK_XML2_USER_TAG_VALUE')];

                    $date = intval(MakeTimeStamp($arState[GetMessage('IBLOCK_XML2_USER_TAG_DATE')], 'YYYY-MM-DD'));
                    while (is_array($arStateHistory[$date])) {
                        $date++;
                    }

                    if (!$last_state_date || doubleval($last_state_date) < doubleval($date)) {
                        $last_state_date = $date;
                    }
                    if (doubleval($first_state_date) > doubleval($date)) {
                        $first_state_date = $date;
                    }

                    $DEPARTMENT_ID = $this->GetSectionByXML_ID($this->DEPARTMENTS_IBLOCK_ID,
                        $arState[GetMessage('IBLOCK_XML2_USER_TAG_DEPARTMENT')]);

                    $arStateHistory[$date] = [
                        'STATE' => $state,
                        'POST' => $arState[GetMessage('IBLOCK_XML2_USER_TAG_POST')],
                        'DEPARTMENT' => $DEPARTMENT_ID,
                    ];
                }

                ksort($arStateHistory);

                // if person's last state is "Fired" - deactivate him.
                if (GetMessage('IBLOCK_XML2_USER_VALUE_FIRED') == $arStateHistory[$last_state_date]['STATE']) {
                    $arFields['ACTIVE'] = 'N';
                }
                // save data serialized
                //$arFields['UF_1C_STATE_HISTORY'] = serialize($arStateHistory);
            } else {
                $arStateHistory = [];
                $last_state_date = null;
                $first_state_date = null;
            }

            // properties data
            if (is_array($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_PROPERTY_VALUES')])) {
                foreach ($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_PROPERTY_VALUES')] as $arPropertyData) {
                    $PROP_XML_ID = $arPropertyData[GetMessage('IBLOCK_XML2_USER_TAG_ID')];
                    $PROP_VALUE = $arPropertyData[GetMessage('IBLOCK_XML2_USER_TAG_VALUE')];
                    $arFields[$this->CalcPropertyFieldName($PROP_XML_ID)] = $PROP_VALUE;
                }
            }

            if (!$arFields['EMAIL'] && $this->arParams['EMAIL_PROPERTY_XML_ID']) {
                $arFields['EMAIL'] = $arFields[$this->CalcPropertyFieldName($this->arParams['EMAIL_PROPERTY_XML_ID'])];
            }

            $bEmailExists = true;
            if (!$arFields['EMAIL'] && $this->arParams['DEFAULT_EMAIL']) {
                $bEmailExists = false;
                $arFields['EMAIL'] = $this->arParams['DEFAULT_EMAIL'];
            }

            if (!$arFields['EMAIL']) {
                $bEmailExists = false;
                $arFields['EMAIL'] = Option::get('main', 'email_from', "admin@" . $_SERVER['SERVER_NAME']);
            }

            // EMAIL, LOGIN and PASSWORD fields
            if (!$CURRENT_USER) {
                // for a new user
                $USER_COUNTER++;

                $arFields['LOGIN'] = '';
                if ($this->arParams['LDAP_ID_PROPERTY_XML_ID'] && $this->arParams['LDAP_SERVER']) {
                    if ($arFields['LOGIN'] = $arFields[$this->CalcPropertyFieldName($this->arParams['LDAP_ID_PROPERTY_XML_ID'])]) {
                        $arFields['EXTERNAL_AUTH_ID'] = 'LDAP#' . $this->arParams['LDAP_SERVER'];
                    }
                }

                if (!$arFields['LOGIN'] && $this->arParams['LOGIN_PROPERTY_XML_ID']) {
                    $arFields['LOGIN'] = $arFields[$this->CalcPropertyFieldName($this->arParams['LOGIN_PROPERTY_XML_ID'])];
                }
                if (!$arFields['LOGIN'] && $this->arParams['LOGIN_TEMPLATE']) {
                    $arFields['LOGIN'] = str_replace('#', $USER_COUNTER, $this->arParams['LOGIN_TEMPLATE']);
                }
                if (!$arFields['LOGIN']) {
                    $arFields['LOGIN'] = 'user_' . $USER_COUNTER;
                }

                if (!$arFields['EXTERNAL_AUTH_ID']) {
                    if ($this->arParams['PASSWORD_PROPERTY_XML_ID']) {
                        $arFields['PASSWORD'] = $arFields['CONFIRM_PASSWORD'] =
                            $arFields[$this->CalcPropertyFieldName($this->arParams['PASSWORD_PROPERTY_XML_ID'])];
                    }

                    if (!$arFields['PASSWORD']) {
                        $arFields['PASSWORD'] = $arFields['CONFIRM_PASSWORD'] =
                            RandString($this->arParams['PASSWORD_LENGTH'] ? $this->arParams['PASSWORD_LENGTH'] : 7);
                    }
                }

                if (!$bEmailExists && $arFields['EMAIL'] && $this->arParams['UNIQUE_EMAIL'] != 'N') {
                    $arFields['EMAIL'] = preg_replace('/@/', '_' . $USER_COUNTER . '@', $arFields['EMAIL'], 1);
                }

                // set user groups list to default from main module setting
                if (is_array($this->arUserGroups)) {
                    $arFields['GROUP_ID'] = $this->arUserGroups;
                }
            } else {
                // for an existing user
                if ($this->arParams['UPDATE_LOGIN']) {
                    $arFields['LOGIN'] = $arFields[$this->CalcPropertyFieldName($this->arParams['LOGIN_PROPERTY_XML_ID'])];
                    if (strlen($arFields['LOGIN']) <= 0) {
                        unset($arFields['LOGIN']);
                    }
                }


                //Пытаемся обновить логин пользователя

                if ($this->arParams['LDAP_ID_PROPERTY_XML_ID'] && $this->arParams['LDAP_SERVER']) {
                    //Если у нас новый логин отличается от старого, то проверяем на существования пользователя с новым логином. Если пользователь не существует, то спокойно обновляемся. В противном случае пишем в лог неудачу.

                    $ldap_login = $arFields[$this->CalcPropertyFieldName($this->arParams['LDAP_ID_PROPERTY_XML_ID'])];
                    $arFields['EXTERNAL_AUTH_ID'] = 'LDAP#' . $this->arParams['LDAP_SERVER'];

                    if ($arCurrentUser['LOGIN'] != $ldap_login) {
                        $testuser = CUser::GetByLogin($ldap_login)->fetch();

                        if (!($testuser)) {
                            $arFields['LOGIN'] = $arFields[$this->CalcPropertyFieldName($this->arParams['LDAP_ID_PROPERTY_XML_ID'])];
                        } else {
                            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/intranet/cml2-import-user.log',
                                'a');
                            fwrite($fp, "==============================================================\r\n");
                            fwrite($fp, "Попытка создать пользователя с дублирующимся логином\r\n");
                            fwrite($fp, print_r($arFields, true) . "\r\n");
                            fwrite($fp, print_r($arCurrentUser, true) . "\r\n");
                            fwrite($fp, "==============================================================\r\n");
                            fclose($fp);
                        }
                    } else {
                        $arCurrentUser['LOGIN'] = $arFields['LOGIN'] = $arFields[$this->CalcPropertyFieldName($this->arParams['LDAP_ID_PROPERTY_XML_ID'])];
                    }
                }


                if ($this->arParams['UPDATE_PASSWORD']) {
                    $arFields['PASSWORD'] = $arFields['CONFIRM_PASSWORD'] = $arFields[$this->CalcPropertyFieldName($this->arParams['PASSWORD_PROPERTY_XML_ID'])];
                    if (strlen($arFields['PASSWORD']) <= 0) {
                        unset($arFields['PASSWORD']);
                        unset($arFields['CONFIRM_PASSWORD']);
                    }
                }

                if (!$this->arParams['UPDATE_EMAIL'] || strlen($arFields['EMAIL']) <= 0) {
                    unset($arFields['EMAIL']);
                }
            }

            if (!$CURRENT_USER && $arFields['LOGIN']) {
                if ($curUser = CUser::GetByLogin($arFields['LOGIN'])->Fetch()) {
                    if (!$curUser['XML_ID']) {
                        $CURRENT_USER = $curUser['ID'];
                    }
                }
            }

            $bNew = $CURRENT_USER <= 0;

            if (!$bNew) {
                foreach ($arFields as $key => $value) {
                    if (!in_array($key, ['LOGIN', 'ACTIVE', 'EXTERNAL_AUTH_ID', 'XML_ID']) && !in_array($key,
                            $this->arParams['UPDATE_PROPERTIES'])) {
                        unset($arFields[$key]);
                    }
                }

                // update existing user
                if ($res = $obUser->Update($CURRENT_USER, $arFields)) {
                    $counter[$arFields['ACTIVE'] == 'Y' ? 'UPD' : 'DEA']++;
                }
            } else {
                $group_id = $arFields['GROUP_ID'];
                unset($arFields['GROUP_ID']);
                // create new user
                if ($CURRENT_USER = $obUser->Add($arFields)) {
                    $counter['ADD']++;

                    CUser::SetUserGroup($CURRENT_USER, $group_id);

                    if (isset($this->next_step['_TEMPORARY']['DEPARTMENT_HEADS'][$arFields['XML_ID']])) {
                        $obSection = new CIBlockSection();
                        foreach ($this->next_step['_TEMPORARY']['DEPARTMENT_HEADS'][$arFields['XML_ID']] as $dpt) {
                            $obSection->Update($dpt, ['UF_HEAD' => $CURRENT_USER], false, false);
                        }
                    }

                    if ($this->arParams['EMAIL_NOTIFY'] == 'Y' || ($this->arParams['EMAIL_NOTIFY'] == 'E') && $bEmailExists) {
                        $arFields['ID'] = $CURRENT_USER;

                        //$this->__event->Send("USER_INFO", SITE_ID, $arFields);
                        //echo CEvent::Send("USER_INFO", 's1', $arFields);

                        $this->__user->SendUserInfo(
                            $CURRENT_USER,
                            $this->arParams['SITE_ID'],
                            '',
                            $this->arParams['EMAIL_NOTIFY_IMMEDIATELY'] == 'Y'
                        );
                    }
                }

                if (!$res = ($CURRENT_USER > 0)) {
                    $USER_COUNTER--;
                }
            }

            if (!$res) {
                $counter['ERR']++;
                $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/intranet/cml2-import-user.log', 'a');
                fwrite($fp, "==============================================================\r\n");
                fwrite($fp, $obUser->LAST_ERROR . "\r\n");
                fwrite($fp, print_r($arFields, true));
                fwrite($fp, "==============================================================\r\n");
                fclose($fp);
            } elseif (is_array($arStateHistory) && count($arStateHistory) > 0) {
                if (null == $this->__ib) {
                    $this->__ib = new CIBlockElement();
                }

                if (!$bNew) {
                    $dbRes = $this->__ib->GetList(
                        [],
                        [
                            'PROPERTY_USER' => $CURRENT_USER,
                            'IBLOCK_ID' => $this->STATE_HISTORY_IBLOCK_ID,
                        ],
                        false,
                        false,
                        ['ID', 'IBLOCK_ID']
                    );
                    while ($arRes = $dbRes->Fetch()) {
                        $this->__ib->Delete($arRes['ID']);
                    }
                }

                foreach ($arStateHistory as $date => $arState) {
                    $arStateFields = [
                        'IBLOCK_SECTION' => false,
                        'IBLOCK_ID' => $this->STATE_HISTORY_IBLOCK_ID,
                        'DATE_ACTIVE_FROM' => ConvertTimeStamp($date, 'SHORT'),
                        'ACTIVE' => 'Y',
                        'NAME' => $arState['STATE'] . ' - ' . $arFields['LAST_NAME'] . ' ' . $arFields['NAME'],
                        'PREVIEW_TEXT' => $arState['STATE'],
                        'PROPERTY_VALUES' => [
                            'POST' => $arState['POST'],
                            'USER' => $CURRENT_USER,
                            'DEPARTMENT' => $arState['DEPARTMENT'],
                            'STATE' => ["VALUE" => $property_state_final[ToLower($arState['STATE'])]],
                        ],
                    ];

                    if (!$this->__ib->Add($arStateFields, false, false)) {
                        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/intranet/cml2-import-state.log', 'a');
                        fwrite($fp, "==============================================================\r\n");
                        fwrite($fp, $this->__ib->LAST_ERROR . "\r\n");
                        fwrite($fp, print_r($arStateFields, true));
                        fwrite($fp, "==============================================================\r\n");
                        fclose($fp);
                    }
                }
            }
        }

        return $CURRENT_USER;
    }

    function ImportAbsence($xml_root_id = false, $start_time = false, $interval = 0)
    {
        global $DB;

        if (null == $this->__ib) {
            $this->__ib = new CIBlockElement();
        }

        if (null == $this->__ibxml) {
            $this->__ibxml = new CIBlockXMLFile();
        }

        if ($start_time === false) {
            $start_time = time();
        }

        $counter = [
            "ADD" => 0,
            "UPD" => 0,
            "DEL" => 0,
            "DEA" => 0,
            "ERR" => 0,
        ];

        if (!$this->next_step['DONE']) {
            //$this->clearAbsences($interval);

            if ($interval > 0 && (time() - $start_time) > $interval) {
                return -1;
            }
        }

        if (!$this->next_step["XML_ABSENCE_PARENT"]) {
            if ($xml_root_id <= 0) {
                $rs = $DB->Query("SELECT MIN(PARENT_ID) MIN_ID FROM b_xml_tree WHERE NAME='" . GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE') . "'");
                $ar = $rs->Fetch();
                $xml_root_id = $ar["MIN_ID"];
            }

            $rs = $DB->Query("SELECT ID, ATTRIBUTES FROM b_xml_tree WHERE PARENT_ID = " . intval($xml_root_id) . " AND NAME='" . GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE') . "'");
            if ($ar = $rs->Fetch()) {
                if (strlen($ar["ATTRIBUTES"]) > 0) {
                    $attrs = unserialize($ar["ATTRIBUTES"]);
                    if (is_array($attrs)) {
                        if (array_key_exists(GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY'), $attrs)) {
                            $this->bUpdateOnly =
                                ($attrs[GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY')] == 'true') ||
                                (intval($attrs[GetMessage('IBLOCK_XML2_USER_ATTR_UPDATE_ONLY')]) ? true : false);
                            $this->next_step['bUpdateOnly'] = $this->bUpdateOnly;
                        }
                    }
                }

                $rs = $DB->Query("SELECT ID, ATTRIBUTES FROM b_xml_tree WHERE PARENT_ID = " . intval($ar['ID']) . " AND NAME='" . GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE_ELEMENTS') . "'");
                if ($ar = $rs->Fetch()) {
                    $this->next_step["XML_ABSENCE_PARENT"] = $ar['ID'];
                }
            }
        }

        if ($this->next_step["XML_ABSENCE_PARENT"]) {
            $rsParents = $DB->Query("SELECT ID, LEFT_MARGIN, RIGHT_MARGIN FROM b_xml_tree WHERE PARENT_ID = " . intval($this->next_step["XML_ABSENCE_PARENT"]) . " AND ID > " . intval($this->next_step["XML_LAST_ID"]) . " ORDER BY ID");

            while ($arParent = $rsParents->Fetch()) {
                $arXMLElement = $this->__ibxml->GetAllChildrenarray($arParent);

                $ID = $this->LoadAbsence($arXMLElement, $counter);

                $this->next_step["XML_LAST_ID"] = $arParent["ID"];

                if ($interval > 0 && (time() - $start_time) > $interval) {
                    break;
                }
            }
        }

        unset($this->__ib);

        return $counter;
    }

    function LoadAbsence($arXMLElement, &$counter)
    {
        global $DB;

        $el = new CIBlockElement();

        $CURRENT_ENTRY = false;

        $XML_ID = $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ID')];
        $id = $arXMLElement[GetMessage('IBLOCK_XML2_BITRIX_ID')];

        /**
         * Если из 1С нам пришел идентификатор Битрикса, то ищем отпуск сначала по нему, а потом по XML_ID
         */
        $arCurrentEntry = false;
        if ($id) {
            $arCurrentEntry = $this->__ib->GetByID($id)->Fetch();
        }
        /**
         * Ищем по XML_ID в том числе тогда, когда не нашли по Bitrix_ID (вдруг нам что-то не то прислали)
         */
        if ((!$arCurrentEntry) && ($XML_ID)) {
            $arCurrentEntry = $this->GetAbsenceByXML_ID($XML_ID);
        }

        if ($arCurrentEntry) {
            $CURRENT_ENTRY = $arCurrentEntry['ID'];
        }
        /**
         * Если в составе реквизитов пришло <Статус>Удален</Статус>, удаляем элемент
         */
        if (GetMessage('IBLOCK_XML2_USER_VALUE_DELETED') == $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATUS')]) {
            if ($CURRENT_ENTRY) {
                $this->__ib->Delete($CURRENT_ENTRY);
            }

            $counter['DEL']++;

            return $CURRENT_ENTRY;
        }

        $arCurrentUser = $this->GetUserByXML_ID($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_USER')]);

        /**
         * Если пользователь нашли пользователя, на которого оформляется отпуск по XML_ID
         */
        if ($arCurrentUser && $arCurrentUser['ACTIVE'] == 'Y') {
            $activeFrom = ConvertTimeStamp(
                MakeTimeStamp(
                    $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DATE_FROM')],
                    'YYYY-MM-DD'
                )
            );
            $activeTo = ConvertTimeStamp(
                MakeTimeStamp(
                    $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DATE_TO')],
                    'YYYY-MM-DD'
                )
            );

            $defaultType = false;
            $res = false;

            if ($CURRENT_ENTRY) {
                /**
                 * Исходим из того, что измениться у отпуска могут только даты.
                 * В случае, если меняется тип отпуска, либо сотрудник, создается новый отпуск
                 */
                if (!empty($arNewItems)) {
                    $arFields = $arNewItems[0];
                    unset($arNewItems[0]);
                } else {
                    $arFields = [
                        'ACTIVE_FROM' => $activeFrom,
                        'ACTIVE_TO' => $activeTo,
                    ];
                }

                if ($el->Update(
                    $CURRENT_ENTRY, $arFields)) {
                    if ($dev = Option::get(FML_MODULE_NAME, 'update_absence_type', 0)) {
                        /* В боевом режиме не должны дополнительно обновляться никакие свойства отпуска */
                        CIblockElement::SetPropertyValuesEx(
                            $CURRENT_ENTRY,
                            $this->ABSENCE_IBLOCK_ID,
                            [
                                'ABSENCE_TYPE' => $this->__GetAbsenceType(
                                    $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')] .
                                    '|' . $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE_CAUSE')],
                                    $defaultType
                                ),
                            ]
                        );
                    }
                    $counter['UPD']++;
                    $res = true;
                }
            } else {
                $arFields = [
                    'XML_ID' => $XML_ID,
                    'IBLOCK_SECTION' => false,
                    'IBLOCK_ID' => $this->ABSENCE_IBLOCK_ID,
                    'NAME' => $arCurrentUser['LAST_NAME'] . ' ' . $arCurrentUser['NAME'] . ' - ' . $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')],
                    'ACTIVE_FROM' => !empty($arNewItems) ? $arNewItems[0]['ACTIVE_FROM'] : $activeFrom,
                    'ACTIVE_TO' => !empty($arNewItems) ? $arNewItems[0]['ACTIVE_TO'] : $activeTo,
                    'ACTIVE' => 'Y',
                    'PREVIEW_TEXT' => $this->absenceComment ? $this->absenceComment : $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE_CAUSE')],
                    'PREVIEW_TEXT_TYPE' => 'text',
                    'DETAIL_TEXT' => $this->absenceComment ? $this->absenceComment : $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DOCUMENT')],
                    'DETAIL_TEXT_TYPE' => 'text',
                    'PROPERTY_VALUES' => [
                        'USER' => $arCurrentUser['ID'],
                        'STATE' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')],
                        'FINISH_STATE' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_FINISH_STATE')],
                        'ABSENCE_TYPE' => $this->__GetAbsenceType(
                            $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')] .
                            '|' . $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE_CAUSE')],
                            $defaultType
                        ),
                        'USER_ACTIVE' => $arCurrentUser['ACTIVE'],
                    ],
                ];

                if (strpos($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DOCUMENT')], 'Отпуск') !== false) {
                    $arFields['framed'] = true;
                }

                if ($defaultType) {
                    $arFields['defaultType'] = true;
                }

                $CURRENT_ENTRY = $this->__ib->Add($arFields);
                if ($res = ($CURRENT_ENTRY > 0)) {
                    $counter['ADD']++;
                }

                if (!empty($arNewItems)) {
                    unset($arNewItems[0]);
                }
            }

            foreach ($arNewItems as $arNewItem) {
                $arFields = [
                    'IBLOCK_SECTION' => false,
                    'IBLOCK_ID' => $this->ABSENCE_IBLOCK_ID,
                    'NAME' => $arCurrentUser['LAST_NAME'] . ' ' . $arCurrentUser['NAME'] . ' - ' . $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')],
                    'ACTIVE_FROM' => $arNewItem['ACTIVE_FROM'],
                    'ACTIVE_TO' => $arNewItem['ACTIVE_TO'],
                    'ACTIVE' => 'Y',
                    'PREVIEW_TEXT' => $this->absenceComment ? $this->absenceComment : $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE_CAUSE')],
                    'PREVIEW_TEXT_TYPE' => 'text',
                    'DETAIL_TEXT' => $this->absenceComment ? $this->absenceComment : $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DOCUMENT')],
                    'DETAIL_TEXT_TYPE' => 'text',
                    'PROPERTY_VALUES' => [
                        'USER' => $arCurrentUser['ID'],
                        'STATE' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')],
                        'FINISH_STATE' => $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_FINISH_STATE')],
                        'ABSENCE_TYPE' => $this->__GetAbsenceType(
                            $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_STATE')] .
                            '|' . $arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_ABSENCE_CAUSE')],
                            $defaultType
                        ),
                        'USER_ACTIVE' => $arCurrentUser['ACTIVE'],
                    ],
                ];

                if (strpos($arXMLElement[GetMessage('IBLOCK_XML2_USER_TAG_DOCUMENT')], 'Отпуск') !== false) {
                    $arFields['framed'] = true;
                }

                if ($defaultType) {
                    $arFields['defaultType'] = true;
                }

                $this->__ib->Add($arFields);
            }

            if (!$res) {
                $counter['ERR']++;

                return false;
            }

            return $CURRENT_ENTRY;
        }

        /**
         * Если дошли до сюда, значит возникли ошибки
         */
        $counter['ERR']++;

        return false;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    function __GetAbsenceType($type, $defaultType)
    {
        $absenceTypes = $this->getAbsenceTypes();
        $this->absenceComment = '';

        foreach (static::$absenceMapping as $vacationType => $vacationMappings) {
            foreach ($vacationMappings as $vacationCode => $vacationComment) {
                if (strpos(mb_strtoupper($type), mb_strtoupper($vacationCode)) !== false) {
                    $this->absenceComment = $vacationComment;

                    if ($vacationType == 'LEAVESICK'
                        && $defaultType) {
                        return $absenceTypes['OTHER'];
                    } else {
                        return $absenceTypes[$vacationType];
                    }
                }
            }
        }

        return $absenceTypes['OTHER'];
    }

    /**
     * @return array|bool
     */
    protected function getAbsenceTypes()
    {
        if (!is_array($this->arAbsenceTypes)) {
            $this->arAbsenceTypes = [];
            $dbTypeRes = CIBlockPropertyEnum::GetList(
                ["SORT" => "ASC", "VALUE" => "ASC"],
                ['IBLOCK_ID' => $this->arParams['ABSENCE_IBLOCK_ID'], 'PROPERTY_ID' => 'ABSENCE_TYPE']
            );
            while ($arTypeValue = $dbTypeRes->GetNext()) {
                $this->arAbsenceTypes[$arTypeValue['XML_ID']] = $arTypeValue['ID'];
            }
        }

        return $this->arAbsenceTypes;
    }
}
