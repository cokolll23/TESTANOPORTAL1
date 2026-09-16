<?php

use Bitrix\Main\GroupTable;
use Korus\Personalarea\Doctrine\Entity\Vacation;
use Korus\Personalarea\Helpers\PlannerHelper;
use Korus\Personalarea\Helpers\VacationHelper;
use Korus\Personalarea\Workflow\Subscribers\BankVacationPlanSubscribers;
use \Korus\Personalarea\Workflow\Subscribers;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $APPLICATION, $USER;
$APPLICATION->SetTitle(GetMessage("BITRIX_PLANNER_PLANIROVANIE_OTPUSKO"));

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule('intranet') || !CModule::IncludeModule('im')) {
    die(GetMessage("BITRIX_PLANNER_NE_USTANOVLENY_TREBU"));
}

CModule::IncludeModule('korus.familiarization');
$em = \Korus\Personalarea\Helpers\DoctrineHelper::getEntityManager();
$user = new \Korus\Personalarea\User\User($USER, $em);
$isHead = \Korus\Personalarea\Helpers\BitrixHelper::isHead($user);

$vacHolidays = VacationHelper::getVacationHolidays();

$IBLOCK_ID = intval($arParams['IBLOCK_ID']);

$arResult = [];
$arResult['ERROR'] = '';
$arResult['PERIOD'] = [];
$arResult['MARKER'] = [];
$arResult['SUMMARY'] = [];
$arResult['USER_ID'] = $USER->GetId();
$arResult['UNPLAN_USERS'] = [];
$arResult['REJECTED_USERS'] = [];

$hrGroup = GroupTable::getList(['filter' => ['STRING_ID' => 'PERSONNEL_DEPARTMENT']])->fetch()['ID'];

$arParams['COUNT_DAYS'] = $arParams['COUNT_DAYS'] == 'Y';
$arParams['COUNT_DAYS_AUTO'] = $arParams['COUNT_DAYS_AUTO'] == 'Y';
$arResult['DRP'] = $hrGroup && in_array($hrGroup, $USER->GetUserGroupArray());
$arResult['isHead'] = $isHead;
$arResult['HR'] = $USER->IsAdmin()
    || ($arParams['HR_GROUP_ID'] && in_array($arParams['HR_GROUP_ID'],
            $USER->GetUserGroupArray()))
    || $arResult['DRP']
    || $isHead;

$arResult['TYPES'] = [
    'VACATION' => GetMessage("BITRIX_PLANNER_OTPUSK"),
];
$rs = CIBlockPropertyEnum::GetList($arOrder = ["SORT" => "ASC", "VALUE" => "ASC"],
    $arFilter = ['IBLOCK_ID' => $IBLOCK_ID, 'PROPERTY_ID' => 'ABSENCE_TYPE']);
while ($f = $rs->Fetch()) {
    $arResult['TYPES'][$f['XML_ID']] = $f['VALUE'];
    $arResult['ABSENCE_TYPES'][$f['XML_ID']] = $f['ID'];
    $arResult['ABSENCE_TYPES'][$f['ID']] = $f['XML_ID'];

    if (in_array($f['XML_ID'], ['VACATION', 'LEAVEUNPAYED'])) {
        $arResult['VAC_TYPES'][] = [
            'id' => $f['ID'],
            'name' => $f['VALUE'],
        ];
    }
}

if ($_POST['action'] == 'add') {
    $t0 = MakeTimeStamp($_POST['day_to']);
    $t1 = MakeTimeStamp($_POST['day_from']);

    $period = 1 + ceil(($t0 - $t1) / 86400);
    if (!$arResult['TYPES'][$type = $_POST['event_type']]) {
        $type = 'VACATION';
    }
    $name = $arResult['TYPES'][$type];

    if ($t0 >= $t1 && (!$arParams['COUNT_DAYS'] || $type != 'VACATION' || $arResult['USERS'][$arResult['USER_ID']]['DAYS_LEFT'] + $arParams['EXTRA_DAYS'] >= $period)) {
        $el = new CIBlockElement;
        if ($ID = $el->Add([
                'IBLOCK_ID' => $IBLOCK_ID,
                'NAME' => $name,
                'CODE' => $type,
                'ACTIVE' => 'N',
                'ACTIVE_FROM' => $_POST['day_from'],
                'ACTIVE_TO' => $_POST['day_to'],
                'PREVIEW_TEXT' => $_POST['PREVIEW_TEXT'],
                'PROPERTY_VALUES' => [
                    'USER' => $arResult['USER_ID'],
                    'ABSENCE_TYPE' => $arResult['ABSENCE_TYPES'][$type],
                    'BP_STATUS' => Vacation::$bpStatuses['DRAFT'],
                ],
            ]
        )) {
            $comment = $_POST['PREVIEW_TEXT'] ? ' [' . $_POST['PREVIEW_TEXT'] . ']' : '';
            $ar = CIntranetUtils::GetUserDepartments($arResult['USER_ID']);
            if ($id = CIntranetUtils::GetDepartmentManagerID($ar[0])) {
                PlannerHelper::imNotify($USER->GetId(), $id, GetMessage("BITRIX_PLANNER_DOBAVLENO") . $name,
                    GetMessage("BITRIX_PLANNER_DOBAVLNO") . $name . ' [' . $arResult['USERS'][$arResult['USER_ID']]['NAME'] . ' ' . $arResult['USERS'][$arResult['USER_ID']]['LAST_NAME'] . '] ' . $_POST['day_from'] . ' - ' . $_POST['day_to'] . $comment);
            }
        } else {
            $arResult['ERROR'] = $el->LAST_ERROR;
        }
    } else {
        $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_VY_NE_MOJETE_VZATQ_O");
    }
} elseif ($_POST['action'] == 'delete' || $_POST['action'] == 'edit') {
    $rs = CIBlockElement::GetList($by = ['ACTIVE_FROM' => 'ASC'],
        $arFilter = ['IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'N', 'ID' => $_POST['id']], false, false,
        ['*', 'PROPERTY_USER', 'PROPERTY_ABSENCE_TYPE', 'PROPERTY_BP_STATUS']);
    if ($f = $rs->Fetch()) {
        $uid = intval($f['PROPERTY_USER_VALUE']);
        if ($uid == $arResult['USER_ID'] || $arResult['ADMIN']) {
            if ($_POST['action'] == 'delete') {
                CIBlockElement::Delete($f['ID']);

                $fmlAbs = $em->getRepository(Korus\Personalarea\Doctrine\Entity\Absence::class)->findOneBy(['vacation_id' => $f['ID']]);
                if ($fmlAbs) {
                    if (!count($fmlAbs->getBps())) {
                        $em->remove($fmlAbs);
                        $em->flush();
                    }
                }

                PlannerHelper::imNotify($USER->GetId(), $uid, GetMessage("BITRIX_PLANNER_ZAPISQ_UDALENA"),
                    GetMessage("BITRIX_PLANNER_ZAPISQ_UDALENA1") . $arResult['TYPES'][$f['CODE']] . ' [' . $f['ACTIVE_FROM'] . ' - ' . $f['ACTIVE_TO'] . ']');
            } else // edit
            {
                if (!$arResult['TYPES'][$type = $_POST['event_type']]) {
                    $type = 'VACATION';
                }
                $name = $arResult['TYPES'][$type];

                $period = 1 + ceil((MakeTimeStamp($_POST['day_to']) - MakeTimeStamp($_POST['day_from'])) / 86400) - PlannerHelper::getPeriod($f) / 86400;
                if (!$arParams['COUNT_DAYS'] || $type != 'VACATION' || $arResult['USERS'][$arResult['USER_ID']]['DAYS_LEFT'] + $arParams['EXTRA_DAYS'] >= $period) {
                    $el = new CIBlockElement;
                    if ($el->Update($_POST['id'],
                        [
                            'ACTIVE' => 'N',
                            'NAME' => $name,
                            'CODE' => $type,
                            'ACTIVE_FROM' => $_POST['day_from'],
                            'ACTIVE_TO' => $_POST['day_to'],
                            'PREVIEW_TEXT' => $_POST['PREVIEW_TEXT'],
                            'PROPERTY_VALUES' => [
                                'USER' => $uid,
                                'ABSENCE_TYPE' => $arResult['ABSENCE_TYPES'][$type],
                            ],
                        ]
                    )) {
                        $comment = $_POST['PREVIEW_TEXT'] ? ' [' . $_POST['PREVIEW_TEXT'] . ']' : '';
                        PlannerHelper::imNotify($USER->GetId(), $uid, GetMessage("BITRIX_PLANNER_ZAPISQ_IZMENENA"),
                            GetMessage("BITRIX_PLANNER_ZAPISQ_IZMENENA1") . $arResult['TYPES'][$f['CODE']] . ' ' . $f['ACTIVE_FROM'] . ' - ' . $f['ACTIVE_TO'] . $comment);
                        //LocalRedirect($APPLICATION->GetCurPage() . $arResult['BASE_URL']);
                    } else {
                        $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_NELQZA_IZMENITQ_ZAPI");
                    }
                } else {
                    $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_NELQZA_IZMENITQ_ZAPI");
                }
            }
        } else {
            $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_NELQZA_IZMENITQ_ZAPI1");
        }
    } else {
        $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_ZAPISQ_NE_NAYDENA");
    }
} elseif ($_POST['action'] == 'approve' || $_POST['action'] == 'unapprove') {
    $rs = CIBlockElement::GetList($by = ['ACTIVE_FROM' => 'ASC'],
        $arFilter = ['IBLOCK_ID' => $IBLOCK_ID, 'ID' => $_POST['id']], false, false,
        ['*', 'PROPERTY_USER', 'PROPERTY_ABSENCE_TYPE']);
    if ($f = $rs->Fetch()) {
        $uid = intval($f['PROPERTY_USER_VALUE']);
        if ($arResult['ADMIN']) {
            $el = new CIBlockElement;
            $el->Update($f['ID'], ['ACTIVE' => $_POST['action'] == 'approve' ? 'Y' : 'N']);
            VacationHelper::setVacationBPStatus($f['ID'], Vacation::$bpStatuses['APPROVED']);

            if ($_POST['action'] == 'approve') {
                PlannerHelper::imNotify($USER->GetId(), $uid, GetMessage("BITRIX_PLANNER_PODTVERJDENO") . $name,
                    GetMessage("BITRIX_PLANNER_PODTVERJDNO") . $name . ' [' . $f['ACTIVE_FROM'] . ' - ' . $f['ACTIVE_TO'] . ']');
            }
            LocalRedirect($APPLICATION->GetCurPage() . $arResult['BASE_URL']);
        } else {
            $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_NET_PRAV_NA_OPERACIU");
        }
    } else {
        $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_ZAPISQ_NE_NAYDENA");
    }
} elseif ($_POST['action'] == 'unactive') {
    $el = new CIBlockElement;
    if (!$el->Update($_POST['id'],
        [
            'ACTIVE' => 'N'
        ]
    )) {
        $APPLICATION->RestartBuffer();
        echo json_encode(['error' => $el->LAST_ERROR]);
        die;
    }
}


$arResult['MONTH'] = intval($_POST['month']);
$arResult['YEAR'] = intval($_POST['year'] ?? $_REQUEST['year']);
//if (!$arResult['MONTH'] || $arResult['MONTH'] > 12)
//	$arResult['MONTH'] = intval(date('m'));
if (!$arResult['YEAR']) {
    $arResult['YEAR'] = date('Y');
}

$arResult['PREV_YEAR'] = $arResult['YEAR'] - 1;
$arResult['NEXT_YEAR'] = $arResult['YEAR'] + 1;

$arResult['RECURSIVE'] = ($_REQUEST['subdep'] && $_REQUEST['subdep'] != 'false') ? true : false;
if ($_REQUEST['dateFrom']
    && $_REQUEST['dateTo']) {
    $arResult['start'] = new DateTime($_REQUEST['dateFrom']);
    $arResult['end'] = new DateTime($_REQUEST['dateTo']);
    $diff = $arResult['start']->diff($arResult['end']);
    $arResult['LAST_DAY'] = $diff->format('%a') + 1;
} else {
    $arResult['start'] = new DateTime('01.01.' . $arResult['YEAR']);
    $arResult['end'] = new DateTime('31.12.' . $arResult['YEAR']);
    $diff = $arResult['start']->diff($arResult['end']);
    $arResult['LAST_DAY'] = $diff->format('%a') + 1;
}

//$arResult['LAST_DAY'] = date('t',mktime(1,1,1,12 ,31,$arResult['YEAR']));

$arResult['ADMIN'] = false;

$arResult['IBLOCK_ID'] = COption::GetOptionInt('intranet', 'iblock_structure');
$arResult['DEPARTMENT_LIST'] = [];
$f = CUser::GetList($by = 'ID', $order = 'ASC', ['ID' => $USER->GetId()],
    ['SELECT' => ['UF_DEPARTMENT']])->Fetch();

if ($_REQUEST['department']) {
    $arDepReq = [];
    foreach ($_REQUEST['department'] as $dep) {
        $arDepReq[] = intval($dep);
    }
}

$arResult['DEPARTMENT_ID'] = ($arDepReq ? $arDepReq : $f['UF_DEPARTMENT']);

$arParams['EXTRA_DAYS'] = intval($arParams['EXTRA_DAYS']);

$arFilter = ['IBLOCK_ID' => $arResult['IBLOCK_ID'], 'ACTIVE' => 'Y'];
if ($arResult['HR']) {
    $arResult['ADMIN'] = true;
} elseif (count($ar = CIntranetUtils::GetSubordinateDepartments($USER->GetId(), true))) {
    if (!$arResult['DEPARTMENT_ID']) {
        $arResult['DEPARTMENT_ID'] = $ar[0];
    }
    if (in_array($arResult['DEPARTMENT_ID'], $ar)) {
        $arResult['ADMIN'] = true;
    }
    if ($arParams['SHOW_ALL'] != 'Y') {
        $arFilter['ID'] = $ar;
    }
} else {
    if (!$f || !is_array($f['UF_DEPARTMENT']) || count($f['UF_DEPARTMENT']) == 0) {
        return;
    } // �� �������� ��� ������������� ��� ���������
    if (!$arResult['DEPARTMENT_ID']) {
        $arResult['DEPARTMENT_ID'] = $f['UF_DEPARTMENT'][0];
    }
    if ($arParams['SHOW_ALL'] != 'Y') {
        $arFilter['ID'] = $f['UF_DEPARTMENT'];
    }
}

if (!$arResult['DEPARTMENT_ID']) {
    echo '<div style="color:red; font-size: 18px;">У вас не указано подразделение</div>';
    return;
}

$arResult['ALLOW_DAYS_ADD'] = $arResult['HR'] || $arResult['ADMIN'] && $arParams['MANAGER_ADD_DAYS'] != 'N';

$arResult['HOLIDAY_LIST'] = [];
for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++) {
    $tmp = dirname(__FILE__) . '/xmlcache/calendar_' . $y . '.xml';

    if (!file_exists($tmp) || (filesize($tmp) == 0 && filemtime($tmp) < time() - 3600)) {
        $ob = new CHTTP();
        $ob->http_timeout = 5;
        if (!$ob->Download('http://xmlcalendar.ru/data/ru/' . $y . '/calendar.xml', $tmp)) {
            file_put_contents($tmp, '');
        }
    }

    if (file_exists($tmp) && filesize($tmp) > 0) {
        $H = [];
        $D = [];
        require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/xml.php');

        $xml = new CDataXML();
        if ($xml->Load($tmp)) {
            $node = $xml->SelectNodes('/calendar/holidays/');
            foreach ($node->children as $holiday) {
                $hid = intval($holiday->getAttribute('id'));
                $H[$hid] = $holiday->getAttribute('title');
            }

            $node = $xml->SelectNodes('/calendar/days/');
            foreach ($node->children as $day) {
                $ar = explode('.', $day->getAttribute('d'));
                $m = intval($ar[0]);
                $d = intval($ar[1]);

                $hid = intval($day->getAttribute('h'));
                if ($day->getAttribute('t') == 1) {
                    $type = $hid ? 'HOLIDAY' : 'WEEKEND';
                } // �������� ��� ������� ��������
                else {
                    $type = 'WORKDAY';
                } // ����������� ������� ����

                if ($y == $arResult['YEAR'] && $m == $arResult['MONTH']) {
                    $arResult['HOLIDAY_LIST']['DISPLAY'][$d] = [
                        'TYPE' => $type,
                        'TITLE' => $hid ? $H[$hid] : '',
                    ];
                }

                if ($type == 'HOLIDAY') {
                    $arResult['HOLIDAY_LIST']['TIMESTAMP'][] = mktime(0, 0, 0, $m, $d, $y);
                }

                if ($type == 'WORKDAY') {
                    $arResult['WORKDAY_LIST']['TIMESTAMP'][] = mktime(0, 0, 0, $m, $d, $y);
                }
//					$arResult['HOLIDAY_LIST']['TIMESTAMP'][] = mktime(12, 0, 0, $m, $d, $y);
            }
        }
    }
}

$holds = \Bitrix\Main\Config\Option::get('calendar', 'year_holidays', '');
foreach (explode(',', $holds) as $item) {
    if (trim($item)) {
        $item = explode('.', $item);
        $arResult['HOLIDAY_LIST']['TIMESTAMP'][] = mktime(0, 0, 0, $item[1], $item[0], date('Y'));
    }
}

$words = \Bitrix\Main\Config\Option::get('calendar', 'year_workdays', '');
foreach (explode(',', $words) as $item) {
    if (trim($item)) {
        $item = explode('.', $item);
        $arResult['WORKDAY_LIST']['TIMESTAMP'][] = mktime(0, 0, 0, $item[1], $item[0], date('Y'));
    }
}

CModule::IncludeModule('iblock');
$rs = CIBlockSection::GetList($arOrder = ['left_margin' => 'asc'], $arFilter);
while ($f = $rs->Fetch()) {
    $f['DEPTH_NAME'] = str_repeat('. ', ($f['DEPTH_LEVEL'] - 1)) . $f['NAME'];
    $arResult['DEPARTMENT_LIST'][$f['ID']] = $f;
    $arResult['dep_list'][] = [
        'id' => $f['ID'],
        'name' => $f['DEPTH_NAME'],
    ];
}

$set_user_id = intval($_POST['set_user_id']);
$arResult['BASE_URL'] = '?year=' . $arResult['YEAR'] . '&month=' . $arResult['MONTH'] . '&set_user_id=' . $set_user_id . '&department=' . $arResult['DEPARTMENT_ID'] . '&recursive=' . $arResult['RECURSIVE'];
$tmp = [];
//$rs = CIntranetUtils::GetDepartmentEmployees($arResult['DEPARTMENT_ID'], $arResult['RECURSIVE'], $bSkipSelf = false);
//while($f = $rs->Fetch())

if ($_REQUEST['user']) {
    $res = \Bitrix\Main\UserTable::getList([
        'filter' => [
            'ID' => $_REQUEST['user'],
        ],
        'select' => [
            'ID',
            'LAST_NAME',
            'NAME',
            'WORK_POSITION',
            'UF_DEPARTMENT',
        ],
    ]);

    while ($userReq = $res->fetch()) {
        $userReq['dep'] = $userReq['UF_DEPARTMENT'][0];
        $users[] = $userReq;
    }

    $arResult['total'] = count($users);
} else {
    [$users, $arResult['total']] = \Korus\Personalarea\Helpers\BitrixHelper::getDepUsers(
        $arResult['DEPARTMENT_ID'],
        $arParams['PAGE_SIZE'],
        intval($_POST['offset']),
        $arResult['RECURSIVE']
    );
}

if ($_POST['planStatus']) {
    $orX = [];
    $userList = array_column($users, 'ID');
    $qb = $em->getRepository(\Korus\Personalarea\Doctrine\Entity\BP::class)->createQueryBuilder('bp');
    $qb->leftJoin('bp.absences', 'abs')
        ->leftJoin('bp.bpTasks', 'bpt')
        ->leftJoin('bpt.bpTasksUsers', 'ust')
        ->andWhere($qb->expr()->eq('bp.name', ':name'))
        ->setParameter('name', \Korus\Personalarea\Workflow\Subscribers\UserVacationPlanSubscribers::$WFName)
        ->setFirstResult(intval($_POST['offset']))
        ->setMaxResults($arParams['PAGE_SIZE']);

    foreach ($_POST['planStatus'] as $planStatus) {
        switch ($planStatus) {
            case 'forming':
                $orX[] = $qb->expr()->andX(
                    $qb->expr()->eq('bp.state', ':formingState'),
                    $qb->expr()->eq('ust.active', 1),
                    $qb->expr()->in('ust.user_id', $userList)
                );

                $qb->setParameter('formingState', $planStatus);
                break;

            case 'reforming':
                $orX[] = $qb->expr()->andX(
                    $qb->expr()->eq('bp.state', ':reformingState'),
                    $qb->expr()->eq('ust.active', 1),
                    $qb->expr()->in('ust.user_id', $userList)
                );

                $qb->setParameter('reformingState', $planStatus);
                break;

            case 'approving':
                $orX[] = $qb->expr()->andX(
                    $qb->expr()->in('bp.state', ':approveState'),
                    $qb->expr()->in('abs.user_id', $userList)
                );

                $qb->setParameter('approveState', [
                    'bossApproval',
                    'additionalApprove'
                ]);
                break;
        }
    }

    $qb->andWhere($qb->expr()->orX(...$orX));

    $newUsers = [];
    foreach ($qb->getQuery()->getResult() as $bp) {
        $newUsers[] = $bp->getParameter('user');
    }

    foreach ($users as $key => $arUser) {
        if (!in_array($arUser['ID'], $newUsers)) {
            unset($users[$key]);
        }
    }
    $arResult['total'] = count($users);
}

$arResult['USERS'] = [];
foreach ($users as $f) {
    if ($arResult['ADMIN'] && $set_user_id) {
        if ($f['ID'] == $set_user_id) {
            $arResult['USER_ID'] = $set_user_id;

            if ($arResult['ALLOW_DAYS_ADD'] && isset($_POST['set_days'])) {
                $d = intval($_POST['set_days']);
                $rs0 = CUser::GetList($by = 'id', $order = 'asc', $arFilter = ['ID' => $set_user_id],
                    $arParams = ['SELECT' => ['UF_DAYS', 'UF_DAYS_DATE']]);
                $f = $rs0->Fetch();

                if (!array_key_exists('UF_DAYS', $f)) {
                    $obUserField = new CUserTypeEntity;
                    $obUserField->Add([
                            'ENTITY_ID' => 'USER',
                            'FIELD_NAME' => 'UF_DAYS',
                            'USER_TYPE_ID' => 'double',
                            'SORT' => 100,
                            'SHOW_FILTER' => 'N',
                            'SHOW_IN_LIST' => 'N',
                            'EDIT_IN_LIST' => 'N',
                            'EDIT_FORM_LABEL' => ['ru' => GetMessage("BITRIX_PLANNER_CISLO_DNEY_OTPUSKA")],
                            'LIST_COLUMN_LABEL' => ['ru' => GetMessage("BITRIX_PLANNER_CISLO_DNEY_OTPUSKA")],
                            'LIST_FILTER_LABEL' => ['ru' => GetMessage("BITRIX_PLANNER_CISLO_DNEY_OTPUSKA")],
                        ]
                    );
                }

                if (!array_key_exists('UF_DAYS_DATE', $f)) {
                    $obUserField = new CUserTypeEntity;
                    $obUserField->Add([
                            'ENTITY_ID' => 'USER',
                            'FIELD_NAME' => 'UF_DAYS_DATE',
                            'USER_TYPE_ID' => 'date',
                            'SORT' => 200,
                            'SHOW_FILTER' => 'N',
                            'SHOW_IN_LIST' => 'N',
                            'EDIT_IN_LIST' => 'N',
                            'EDIT_FORM_LABEL' => ['ru' => GetMessage("BITRIX_PLANNER_USER_DAY_DATE")],
                            'LIST_COLUMN_LABEL' => ['ru' => GetMessage("BITRIX_PLANNER_USER_DAY_DATE")],
                            'LIST_FILTER_LABEL' => ['ru' => GetMessage("BITRIX_PLANNER_USER_DAY_DATE")],
                        ]
                    );
                }

                $ob = new CUser();
                if ($ob->Update($set_user_id, [
                    'UF_DAYS' => $d,
                    'UF_DAYS_DATE' => ConvertTimeStamp(mktime(0, 0, 0, $arResult['MONTH'], 1, $arResult['YEAR'])),
                ])) {
                    LocalRedirect($APPLICATION->GetCurPage() . $arResult['BASE_URL']);
                } else {
                    $arResult['ERROR'] = GetMessage("BITRIX_PLANNER_NE_UDALOSQ_IZMENITQ");
                }
            }
            $set_user_id = 0;
        }
    }

    $f['DAYS_LEFT_BASE'] = $f['DAYS_LEFT'] = intval($f['UF_DAYS']);
    if ($arParams['COUNT_DAYS_AUTO'] && $f['UF_DAYS_DATE']) {
        $t1 = mktime(0, 0, 0, $arResult['MONTH'], 1, $arResult['YEAR']);
        $day_real = PlannerHelper::countVacationDays($t1, MakeTimeStamp($f['UF_DAYS_DATE']));
        $day_display = $t1 < time() ? PlannerHelper::countVacationDays(time(),
            MakeTimeStamp($f['UF_DAYS_DATE'])) : $day_real;
        $f['DAYS_LEFT'] += $day_display;
        $f['DAYS_LEFT_BASE'] += $day_real;
    }
    $arResult['USERS'][$f['ID']] = $f;

    if ($f['ID'] == $arResult['USER_ID']) {
        $APPLICATION->SetTitle(' [' . $f['NAME'] . ' ' . $f['LAST_NAME'] . ']');
    }
}

$map_from = $arResult['start']->getTimestamp();//mktime(0, 0, 0, $arResult['MONTH'], 1, $arResult['YEAR']);
$map_to = $arResult['end']->getTimestamp();//86400 + mktime(0, 0, 0, $arResult['MONTH'], $arResult['LAST_DAY'], $arResult['YEAR']);
$arFilter = [
    'IBLOCK_ID' => $IBLOCK_ID,
    'PROPERTY_USER' => array_keys($arResult['USERS']),
    '>=DATE_ACTIVE_TO' => $arResult['start']->format('d.m.Y') . ' 00:00:00',
    '<=DATE_ACTIVE_FROM' => $arResult['end']->format('d.m.Y') . ' 23:59:59',
];

$canNotChange = new DateTime(date('d.m.Y'));
$termService = new \Korus\Personalarea\Services\TermService($em);

$arResult['vacationStartDelta'] = \Bitrix\Main\Config\Option::get(FML_MODULE_NAME, 'VACATION_START_RANGE', 10);

$canNotChange = $termService->addWorkDays($canNotChange, $arResult['vacationStartDelta']);
if ($_POST['type']) {
    $arFilter['PROPERTY']['ABSENCE_TYPE'] = $_POST['type'];
} else {
    foreach ($arResult['ABSENCE_TYPES'] as $absType => $val) {
        if ($_POST['pregnancy'] == 'true'
            || !in_array($absType, ['CHILD', 'PREGNANT'])) {
            $arFilter['PROPERTY']['ABSENCE_TYPE'][] = $val;
        }
    }
}
$rs = CIBlockElement::GetList($by = ['ACTIVE_FROM' => 'ASC'], $arFilter, false, false,
    ['*', 'PROPERTY_USER', 'PROPERTY_ABSENCE_TYPE', 'PROPERTY_BP', 'PROPERTY_OUT_OF_PLAN', 'PROPERTY_BP_STATUS']);
while ($f = $rs->GetNext()) {
    $uid = intval($f['PROPERTY_USER_VALUE']);
    if (!$uid || !$arResult['USERS'][$uid]) {
        continue;
    }

    if (!$f['ACTIVE_FROM'] || !$f['ACTIVE_TO']) {
        continue;
    }

    $f['showFrom'] = new DateTime($f['ACTIVE_FROM']);
    $f['showFrom'] = $termService->awayWorkDays($f['showFrom'], $arResult['vacationStartDelta']);
    $f['showFrom'] = $f['showFrom']->format('d.m.Y');

    $f['showTo'] = new DateTime($f['ACTIVE_TO']);
    $f['showTo'] = $termService->addWorkDays($f['showTo'], $arResult['vacationStartDelta']);
    $f['showTo'] = $f['showTo']->format('d.m.Y');

    $from = MakeTimeStamp($f['ACTIVE_FROM']);
    $to = MakeTimeStamp($f['ACTIVE_TO']);

    $f['CODE'] = $arResult['ABSENCE_TYPES'][$f['PROPERTY_ABSENCE_TYPE_ENUM_ID']];
    if (!$arResult['TYPES'][$f['CODE']]) {
        $f['CODE'] = 'VACATION';
    }

    $from = PlannerHelper::timestampRemoveTime($from);
    $to = PlannerHelper::timestampRemoveTime($to);

    if ($f['CODE'] == 'VACATION') {
        $from = PlannerHelper::timestampRemoveTime($from);
        $to = PlannerHelper::timestampRemoveTime($to);

        $start = new DateTime(date('d.m.Y', $from));
        if ($start > $canNotChange) {
            $f['can_change'] = true;
        }

        if ($bp = getChangeBp($em, $f['ID'])) {
            /**
             * @var \Korus\Personalarea\Doctrine\Entity\BP $bp
             */
            if ($bp->getName() == 'vacationChange') {
                $f['transfer'] = 'оформляется перенос на ' . $bp->getParameter('start');
            }
        }
    }

    $bps = null;
    $bps = getBps($em, $f['ID']);

    if (!empty($bps)) {
        $hasWaiting = false;
        foreach ($bps as $bp) {
            if ($bp->getState() == 'waiting') {
                $hasWaiting = true;
            }

            if ((!in_array($bp->getState(),
                        ['forming', 'end']) && $bp->getName() == Subscribers\UserVacationPlanSubscribers::$WFName)
                || ($bp->getName() == Subscribers\VacationChangeSubscribers::$WFName && $bp->getState() != 'end')) {
                $f['can_change'] = false;
            }

            foreach ($bp->getBpTasks() as $bpTask) {
                foreach ($bpTask->getBpTasksUsers() as $task) {
                    if ($task->getActive()) {
                        $userService = new \Korus\Personalarea\Services\Entity\BUserService($task->getBUser());

                        $f['users'][] = $userService->getFullName();
                    }
                }
            }
        }

        if (is_array($f['users'])) {
        $f['users'] = implode('<br>', $f['users']);
        }

        if ($hasWaiting && !$f['users']) {
            $f['users'] = 'Ожидание оформления в 1с';
        }
    }

    if ($to < $from) {
        continue;
    }

    if ($_POST['action'] == 'delete' && $_POST['id'] == $f['ID']) {
        continue;
    }

    $to_fixed = $to;
    if (date('His',
            $to) == 0) // ���������� ���� ������� ��� ����� �������, ��������� ����� ��� ����������� ����� ������������ �������
    {
        $to_fixed += 86400;
    }

    $from_visible = max([$from, $map_from]);
    $to_visible = min([$to_fixed, $map_to]);
    $period = $to_fixed - $from;
    $visible_period = $to_visible > $from_visible ? $to_visible - $from_visible : 0; // ����� ���������� �� ��������� ���������

    if ($map_to == $to
        || $map_to == $from) // ���������� ���� ������� ��� ����� �������, ��������� ����� ��� ����������� ����� ������������ �������
    {
        $visible_period += 86400;
    }

    $f['PERIOD'] = $period;
    $f['VISIBLE_PERIOD'] = $visible_period;

    if ($arParams['COUNT_DAYS'] && $f['CODE'] == 'VACATION') {
        $DAYS_DATE = $arParams['COUNT_DAYS_AUTO'] ? $arResult['USERS'][$uid]['UF_DAYS_DATE'] : false;
        if (!$DAYS_DATE || $from >= MakeTimeStamp($DAYS_DATE)) {
            $d = floor($period / 86400);
            foreach ($arResult['HOLIDAY_LIST']['TIMESTAMP'] as $t) {
                if ($t > $from && $t < $to) {
                    $d--;
                }
            }

            $arResult['USERS'][$uid]['DAYS_LEFT'] -= $d;
        }
    }

    [$days, $hasHoliday] = VacationHelper::getVacationDays($f['ACTIVE_FROM'], $f['ACTIVE_TO'], $vacHolidays);

    $f['HUMAN_TIME'] = $days . ' дн.';
    $f['PARTIAL'] = $period < 86400 && (date('His', $from) > 0 || date('His', $to) > 0);
    $f['VACATION_STATUS'] = ($f['PROPERTY_BP_STATUS_VALUE']) ?? (($f['ACTIVE'] == 'N') ? Vacation::$bpStatuses['DRAFT'] : Vacation::$bpStatuses['APPROVED']);

    $f['TITLE'] = $arResult['TYPES'][$f['CODE']] . ' ' . $f['ACTIVE_FROM'] . ' - ' . $f['ACTIVE_TO'] . ' (' . $f['HUMAN_TIME'] . ')' . ($f['ACTIVE'] != 'Y' ? ' - ' . GetMessage("BITRIX_PLANNER_NE_PODTVERJDENO") : '') . ($f['PREVIEW_TEXT'] ? ' [' . htmlspecialcharsbx($f['~PREVIEW_TEXT']) . ']' : '');

    if ($f['PROPERTY_OUT_OF_PLAN_VALUE']) {
        $f['CODE'] = 'OUT_OF_PLAN';
    }

    if ($uid == $USER->GetID()
        || $arResult['ADMIN']
        || $arResult['HR']) {
        $arResult['PERIOD'][] = $f;
    }

    if ($f['VISIBLE_PERIOD'] && $f['ACTIVE'] == 'Y') {
        $arResult['SUMMARY'][$f['PROPERTY_USER_VALUE']][$f['PROPERTY_ABSENCE_TYPE_ENUM_ID']] += $days;
    }

    $from0 = $from_visible;
    while ($visible_period > 0) {
        if ($from0 > $map_to) {
            break;
        }
        $d = date('j', $from0);
        $arResult['MARKER'][$uid][date('Ymd', $from0)] = $f;
        if (count($arResult['MARKER'][$uid]) == 1) {
            $arResult['MARKER'][$uid][date('Ymd', $from0)]['FIRST_DAY'] = true;
        }
        $from0 += 86400;
        $visible_period -= 86400;
    }

    if ($to >= $map_to) {
        $arResult['MARKER'][$uid][date('Ymd', $map_to)] = $f;
        if (count($arResult['MARKER'][$uid]) == 1) {
            $arResult['MARKER'][$uid][date('Ymd', $map_to)]['FIRST_DAY'] = true;
        }
    }
}

$arResult['VACATION_TASK'] = VacationHelper::getVacationTask();
if ($arResult['DRP']
    && $_GET['year'] == (date('Y') + 1)) {
    $bp = $em->getRepository(\Korus\Personalarea\Doctrine\Entity\BP::class)->createQueryBuilder('bp');
    $bp->andWhere($bp->expr()->eq('bp.name', ':name'))
        ->andWhere($bp->expr()->eq('bp.state', ':state'))
        ->setParameters([
            'name' => BankVacationPlanSubscribers::$WFName,
            'state' => 'drpEdit'
        ]);

    $bp = $bp->getQuery()->getResult()[0];
    $arResult['drpTask'] = $bp ? $bp->getId() : null;
}

CUtil::InitJSCore(["tooltip"]);

$arResult['days'] = [];

for ($i = clone($arResult['start']); $i <= $arResult['end']; $i->modify('+1 day')) {
    $title = '';

    if ($h = $arResult['HOLIDAY_LIST']['DISPLAY'][$i]) {
        $title = $h['TITLE'] ? $h['TITLE'] . ' (' . GetMessage('BITRIX_PLANNER_' . $h['TYPE']) . ')' : GetMessage('BITRIX_PLANNER_' . $h['TYPE']);
    }

    $class = 'bg';

    $tms = $i->getTimestamp();
    $weekend = ($i->format('N') > 5 || in_array($tms,
                $arResult['HOLIDAY_LIST']['TIMESTAMP'])) && !in_array($tms,
            $arResult['WORKDAY_LIST']['TIMESTAMP']);

    $red = '';

    if ($h) {
        if ($h['TYPE'] == 'HOLIDAY') {
            $class .= $weekend ? '-weekend' : '-holiday';
        } elseif ($h['TYPE'] == 'WEEKEND') {
            $class .= '-weekend';
        } else {
            $class .= '-workday';
        }
    } elseif ($weekend) {
        $class .= '-weekend';
    }

    if ($h && $h['TYPE'] != 'WORKDAY') {
        $class .= ' holiday-red';
    }

    $arResult['days'][] = [
        'class' => $class,
        'title' => htmlspecialcharsbx($title),
        'date' => $i->format('d'),
    ];
}

if ($arResult['ERROR']) {
    $APPLICATION->RestartBuffer();
    echo json_encode(['error' => $arResult['ERROR']]);
    die();
}

if ($_REQUEST['export'] == 'report') {
    $APPLICATION->RestartBuffer();
    Header('Content-Type: application/vnd.ms-excel');
    Header('Content-Disposition: attachment;filename=' . sprintf('absence_report_%d.%02d.xls', $arResult['YEAR'],
            $arResult['MONTH']));
    $this->IncludeComponentTemplate('report');
}

if ($_REQUEST['export'] == 'summary') {
    $APPLICATION->RestartBuffer();
    Header('Content-Type: application/vnd.ms-excel');
    Header('Content-Disposition: attachment;filename=' . sprintf('absence_summary_%d.%02d.xls', $arResult['YEAR'],
            $arResult['MONTH']));
    $this->IncludeComponentTemplate('summary');
}

if ($isHead && $arResult['USERS']) {
    $qb = $em->getRepository(\Korus\Personalarea\Doctrine\Entity\BpTask::class)->createQueryBuilder('bpTask');
    $qb->leftJoin('bpTask.bp', 'bp')
        ->leftJoin('bpTask.bpTasksUsers', 'ust')
        ->andWhere($qb->expr()->eq('ust.active', 1))
        ->andWhere($qb->expr()->eq('ust.user_id', $user->getCurUserId()))
        ->andWhere($qb->expr()->orX(
            $qb->expr()->eq('bp.name', ':bossPlanTask'),
            $qb->expr()->andX(
                $qb->expr()->eq('bp.name', ':bossApproveTask'),
                $qb->expr()->eq('bp.state', ':bossApproveState')
            )
        ))
        ->setParameter('bossPlanTask', \Korus\Personalarea\Workflow\Subscribers\BossUserPlanVacationSubscribers::$WFName)
        ->setParameter('bossApproveTask', \Korus\Personalarea\Workflow\Subscribers\UserVacationPlanSubscribers::$WFName)
        ->setParameter('bossApproveState', 'bossApproval');

    foreach ($qb->getQuery()->getResult() as $bpTask) {
        $bp = $bpTask->getBp();
        if ($bp->getName() == \Korus\Personalarea\Workflow\Subscribers\BossUserPlanVacationSubscribers::$WFName) {
            $arResult['bossTask'][$bp->getParameter('user')] = [
                'id' => $bp->getId(),
                'vacations' => $bpTask->getParameter('vacations')
            ];
        } else {
            $arResult['bossApproveTask'][$bp->getParameter('user')] = $bpTask->getId();
        }
    }

    $qb = $em->getRepository(\Korus\Personalarea\Doctrine\Entity\BP::class)->createQueryBuilder('bp');
    $qb->leftJoin('bp.bpTasks', 'bpTask')
        ->leftJoin('bpTask.bpTasksUsers', 'ust')
        ->andWhere($qb->expr()->eq('ust.active', 1))
        ->andWhere($qb->expr()->in('ust.user_id', array_keys($arResult['USERS'])))
        ->andWhere($qb->expr()->eq('bp.name', ':bossApproveTask'))
        ->andWhere($qb->expr()->in('bp.state', ':bossApproveState'))
        ->setParameter('bossApproveTask', \Korus\Personalarea\Workflow\Subscribers\UserVacationPlanSubscribers::$WFName)
        ->setParameter('bossApproveState', ['forming', 'reforming']);

    foreach ($qb->getQuery()->getResult() as $bp) {
        $user = $bp->getParameter('user');
        if ($bp->getState() == 'reforming') {
            $arResult['REJECTED_USERS'][] = $user;
        }
        $arResult['UNPLAN_USERS'][] = $user;
    }
}

$this->IncludeComponentTemplate();


function getChangeBp($em, $vacId)
{
    $bp = $em->getRepository(\Korus\Personalarea\Doctrine\Entity\BP::class)->createQueryBuilder('bp');
    $bp->leftJoin('bp.absences', 'abs')
        ->andWhere($bp->expr()->eq('abs.vacation_id', $vacId))
        ->andWhere($bp->expr()->neq('bp.state', '\'end\''))
        ->andWhere($bp->expr()->eq('bp.name', '\'vacationChange\''));

    return $bp->getQuery()->getResult()[0];
}

function getBps($em, $vacId)
{
    $bp = $em->getRepository(\Korus\Personalarea\Doctrine\Entity\BP::class)->createQueryBuilder('bp');
    $bp->leftJoin('bp.absences', 'abs')
        ->andWhere($bp->expr()->eq('abs.vacation_id', $vacId))
        ->andWhere($bp->expr()->neq('bp.state', '\'end\''));

    return $bp->getQuery()->getResult();
}
