<?php

use Bitrix\Main\Type\Date;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arMonths = [
        '',
        GetMessage("BITRIX_PLANNER_ANVARQ"),
        GetMessage("BITRIX_PLANNER_FEVRALQ"),
        GetMessage("BITRIX_PLANNER_MART"),
        GetMessage("BITRIX_PLANNER_APRELQ"),
        GetMessage("BITRIX_PLANNER_MAY"),
        GetMessage("BITRIX_PLANNER_IUNQ"),
        GetMessage("BITRIX_PLANNER_IULQ"),
        GetMessage("BITRIX_PLANNER_AVGUST"),
        GetMessage("BITRIX_PLANNER_SENTABRQ"),
        GetMessage("BITRIX_PLANNER_OKTABRQ"),
        GetMessage("BITRIX_PLANNER_NOABRQ"),
        GetMessage("BITRIX_PLANNER_DEKABRQ"),
];
$futureYear = new DateTime('01.01.' . (date('Y') + 1));

$arResult['TYPES'] = array_merge(['OUT_OF_PLAN' => GetMessage("BITRIX_PLANNER_OUT_OF_PLAN")], $arResult['TYPES']);
pretty_print($arResult);
?>

<div id="holidays">
    <script>
        var day_from = 0;
        var day_to = 0;
        var month = '<?=sprintf('%02d', $arResult['MONTH'])?>';
        var year = <?=$arResult['YEAR']?>;
        var future_year = <?=date('Y') + 1?>;
        var last_day = <?=$arResult['end']->getTimestamp()?>;
        var vacationTask = <?=json_encode($arResult['VACATION_TASK'])?>;

        $(document).on('mouseover', '.day-future', function () {
            if (this.id
                && !$(this).hasClass('day-saved')) {
                Mark(this);
            }
        });
        $(document).on('mousedown', '.day-future', function () {
            if (this.id
                && !$(this).hasClass('day-saved')) {
                StartSelect(this);
            }
        });
        $(document).on('mouseup', '.day-future', function () {
            if (this.id
                && !$(this).hasClass('day-saved')) {
                EndSelect(this);
            }
        });

        function GetDay(ob) {
            let id = ob.id.replace(/^day_/, '');
            id = new Date(id * 1000);
            return id;
        }

        function StartSelect(ob) {
            day_from = GetDay(ob);

            if (vacationTask || true) {

                Mark(ob);
            }
            $('.day-future').css('background', '');
        }

        function EndSelect(ob) {
            if (!vacationTask) {
                //return;
            }

            Mark(ob);

            day_to = GetDay(ob);

            if (day_from < new Date()) {
                $(document).ready(function () {
                    var dummyVue = new Vue();
                    dummyVue.$message({
                        'type': 'warning',
                        'message': 'Выбраны даты прошедшего периода. Вы не можете запланировать отпуск на данный период'
                    });
                });

                closeForm();

                return;
            }

            ShowEditForm('<?=GetMessageJS("BITRIX_PLANNER_DOBAVLENIE_ZAPISI")?>');
            document.forms.add_form.action.value = 'add';

            m = day_from.getMonth() + 1;
            if (m < 10)
                m = '0' + m;

            v = day_from.getDate();
            if (v < 10)
                v = '0' + v;
            document.forms.add_form.day_from.value = v + '.' + m + '.' + year;
            m = day_to.getMonth() + 1;
            if (m < 10)
                m = '0' + m;

            v = day_to.getDate();
            if (v < 10)
                v = '0' + v;
            document.forms.add_form.day_to.value = v + '.' + m + '.' + year;

            <?php if (!$arParams['COUNT_DAYS'] || $arResult['USERS'][$arResult['USER_ID']]['DAYS_LEFT'] + $arParams['EXTRA_DAYS'] > 0) { ?>
            SetType('VACATION');
            <?php } ?>

            if (ob.getAttribute('data-vac-type') == 'vacation') {
                SetType('VACATION');
            } else {
                SetType('OUT_OF_PLAN');
            }
        }

        function EditVacation(id, from, to, type, PREVIEW_TEXT, action) {
            if (action) {
                ShowEditForm('<?=GetMessageJS("BITRIX_PLANNER_DOBAVLENIE_ZAPISI")?>');
                document.forms.add_form.action.value = 'add';
            } else {
                ShowEditForm('<?=GetMessageJS("BITRIX_PLANNER_IZMENENIE_ZAPISI")?>');
                document.forms.add_form.action.value = 'edit';
            }
            document.forms.add_form.id.value = id;
            document.forms.add_form.day_from.value = from;
            document.forms.add_form.day_to.value = to;
            document.forms.add_form.PREVIEW_TEXT.value = PREVIEW_TEXT ? PREVIEW_TEXT : '';
            SetType(type);
        }

        function SetType(type) {
            var sel = document.forms.add_form.event_type;
            type = (type.toLowerCase() == 'out_of_plan' ? 'VACATION' : type);
            for (i = 0; i < sel.options.length; i++)
                sel.options[i].selected = sel.options[i].value == type;

            // if (type.toLowerCase() == 'vacation') {
            //     $('[data-vac-value=out_of_plan]').hide();
            //     $('[data-vac-value=vacation]').show();
            // } else {
            //     $('[data-vac-value=out_of_plan]').show();
            //     $('[data-vac-value=vacation]').hide();
            // }
        }

        function closeForm() {
            BX('date_edit_form').style.display = 'none';

            $(document).ready(function () {
                $('[id^="day_"]').css('background', '');
            });

            day_from = 0;
            day_to = 0;
        }

        function ShowEditForm(text) {
            frm = BX('date_edit_form');

            t = (document.documentElement.scrollTop || document.body.scrollTop) + (window.innerHeight - 400) / 2;
            frm.style.top = (t < 0 ? 0 : t) + 'px';
            l = (window.innerWidth - 600) / 2;
            frm.style.left = (l < 0 ? 0 : l) + 'px';
            frm.style.display = '';

            BX('date_edit_title').innerHTML = '<b>' + text + '</b>';

            document.onkeydown = function (e) {
                e = e || window.event;
                if (e.keyCode == 27) {
                    closeForm();
                }
            }
        }

        function Mark(ob) {
            $(document).ready(function () {
                $('[id^="day_"]').css('background', '');
            });
            let endDate = null;
            if (day_from > 0) {

                if (day_to > 0) {
                    endDate = day_to;
                } else {
                    endDate = GetDay(ob);
                }

                let i = new Date();
                i.setTime(day_from.getTime());

                for (i; i <= endDate; i.setDate(i.getDate() + 1)) {
                    $(document).ready(function () {
                        $('#day_' + (i / 1000)).css('background', 'rgb(136, 168, 226)');
                    });
                }
            }
        }

        function ApproveVacation(id) {
            if (confirm('<?=GetMessageJS("BITRIX_PLANNER_PODTVERDITQ_ZAPISQ")?>'))
                document.location = '<?=$arResult['BASE_URL']?>&action=approve&id=' + id;
        }

        function UnApproveVacation(id) {
            if (confirm('<?=GetMessageJS("BITRIX_PLANNER_VERNUTQ_STATUS_NEPOD")?>'))
                document.location = '<?=$arResult['BASE_URL']?>&action=unapprove&id=' + id;
        }

        function AddDaysLeft(ob, id) {
            inp = BX('days_left_' + id);
            inp.value = ob.dataset.days;
            inp.style.display = '';
            inp.focus();
            inp.onkeypress =
                function (event) {
                    if (event.keyCode == 13) {
                        inp = BX('days_left_' + id);
                        document.location = '<?=$arResult['BASE_URL']?>&set_user_id=' + id + '&set_days=' + encodeURIComponent(inp.value);
                    }
                }
        }

        function RefreshList(department) {
            document.location = '<?=$arResult['BASE_URL']?>&department=' + department;
        }

        function showError() {
            $(document).ready(function () {
                var dummyVue = new Vue();
                dummyVue.$message({
                    'type': 'warning',
                    'message': 'Вы не можете оформить отпуск начинающийся ранее, чем через ' + <?=$arResult['vacationStartDelta']?> + ' рабочих дней от текущей даты.'
                });
            });
        }

    </script>

    <div class="hol_table">
        <!-- Start Верхний фильтр по годам -->
        <div class="hol_table_year">
            <?php
            for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++) { ?>
                <?php
                if ($i == $arResult['YEAR']
                        && (!$_REQUEST['dateFrom']
                                || !$_REQUEST['dateTo'])) { ?>
                    <div class="hol_table_year-item"><b><?= $i; ?></b></div>
                    <?php
                } else { ?>
                    <a href="?year=<?= $i; ?>" class="hol_table_year-item"><?= $i; ?></a>
                    <?php
                }
            } ?>
        </div>
        <!-- End Верхний фильтр по годам -->
        <div class="planner_toper">
            <div class="planner_toper-left">
                <div class="planner_filter">
                    <?
                    CJSCore::Init([
                            'jquery',
                            'vue_bundle',
                            'element_ui',
                            'fml_utils',
                            'fml_complex_user_selector',
                            'fml-tree-selector',
                            'axios',
                            'fmlapi',
                            'polifill',
                    ]);

                    $isHead = false;
                    if (count($arResult['DEPARTMENT_LIST']) > 1) {
                        $isHead = true;
                    }
                    ?>
                    <div id="dep_sel">
                    </div>

                    <script>
                        <?if($_REQUEST['dateFrom'] && $dateFrom = new Date($_REQUEST['dateFrom'])):?>
                        window.dateFrom = <?=json_encode($dateFrom->toString())?>;
                        <?endif;?>
                        <?if($_REQUEST['dateTo'] && $dateTo = new Date($_REQUEST['dateTo'])):?>
                        window.dateTo = <?=json_encode($dateTo->toString())?>;
                        <?endif;?>

                        $(document).ready(function () {
                            window.depSelector.departments = <?=json_encode($arResult['dep_list'])?>;
                            window.depSelector.department = <?=json_encode($arResult['DEPARTMENT_ID'])?>;
                            window.depSelector.head = <?=json_encode($arResult['HR'])?>;
                            window.depSelector.hr = <?=json_encode($arResult['HR'])?>;
                            window.depSelector.listType = <?=json_encode($arResult['VAC_TYPES'])?>;
                            window.depSelector.subdep = <?=json_encode($arResult['RECURSIVE'])?>;
                            window.size = <?=intval($arParams['PAGE_SIZE'])?>;
                        });
                    </script>
                </div>
            </div>
            <div class="planner_toper-right">
                <div class="planner_descr-wrap">
                    <table style="border-collapse:collapse">
                  <!--      <tr>
                            <td class="day-color day-thumb"></td>
                            <td class="spmin"><?php /*= GetMessage("BITRIX_PLANNER_OTMECENO_NE_SOHRANE") */?></td>
                        </tr>
                        <tr>
                            <td class="day-color day-saved"></td>
                            <td class="spmin"><?php /*= GetMessage("BITRIX_PLANNER_SOGL") */?></td>
                        </tr>-->
                        <?php
                        foreach ($arResult['TYPES'] as $k => $v) { ?>
                            <tr <?
                                if ($k != 'VACATION' && $k != 'LEAVEUNPAYED' && $k != 'PREGNANT' && $k != 'CHILD') { ?>class="hidden"<?
                            } ?>>
                                <td class="day-color mark-<?= strtolower($k); ?>"></td>
                                <td class="spmin"><?= htmlspecialcharsbx($v); ?>6</td>
                            </tr>
                            <?php
                        } ?>
                       <!-- <tr>
                            <td class="day-color day-transfer day-transfer-other shtrix"></td>
                            <td class="spmin"><?php /*= GetMessage("BITRIX_PLANNER_PERENOS") */?></td>
                        </tr>-->
                        <tr>
                            <td class="day-color mark-assignment"></td>
                            <td class="spmin"><?= GetMessage("BITRIX_PLANNER_ASSIG") ?></td>
                        </tr>
                        <tr>
                            <td class="day-color mark-leavesick"></td>
                            <td class="spmin"><?= GetMessage("BITRIX_PLANNER_SICK") ?></td>
                        </tr>
                        <tr>
                            <td class="day-color mark-other"></td>
                            <td class="spmin"><?= GetMessage("BITRIX_PLANNER_SOHRANENO_NO_NE_POD") ?></td>
                        </tr>
                        <!--<tr>
                            <td class="day-color mark-plan_change"></td>
                            <td class="spmin"><?php /*= GetMessage("BITRIX_PLANNER_PLAN_CHANGE") */?></td>
                        </tr>
                        <tr>
                            <td class="day-color mark-out_of_plan" width=20></td>
                            <td class="spmin"><?php /*= GetMessage("BITRIX_PLANNER_OUT_OF_PLAN") */?></td>
                        </tr>-->
                    </table>
                </div>
            </div>
        </div>

        <div class="ajax">
            <?
            if ($_POST['department']
                    || $_POST['subdep']
                    || $_POST['pregnancy']
                    || $_POST['dateFrom']
                    || $_POST['dateTo']
                    || $_POST['planStatus']
                    || $_POST['offset']) {
                $APPLICATION->RestartBuffer();
                ob_start();
            } ?>
            <table class="depList">
                <tr class="depFirst">
                    <th>&nbsp;</th>
                </tr>
                <tr class="depSecond">
                    <th>Сотрудники</th>
                </tr>
                <?
                $showDep = [];
                foreach ($arResult['USERS'] as $f) {
                    $d = $f['DAYS_LEFT'];
                    $count = isset($_POST['department']) && count($_POST['department']) > 1;
                    if (($count || $_POST['subdep'] == 'true')
                            && !in_array($f['dep'], $showDep)) {
                        $showDep[] = $f['dep']; ?>
                        <tr>
                            <th class="dep<?= (count($showDep) % 2); ?>">
                                <?= $arResult['DEPARTMENT_LIST'][$f['dep']]['DEPTH_NAME']; ?>
                            </th>
                        </tr>
                        <?php
                    } ?>
                    <tr>
                        <th style="text-align:left" class="dep<?= (count($showDep) % 2); ?> <?= (in_array(
                                $f['ID'],
                                $arResult['UNPLAN_USERS']
                        ) ? 'unplan' : '') ?>">
                            <?
                            $name = htmlspecialcharsbx(
                                    $f['LAST_NAME'] . ' ' . $f['NAME'] . ' (' . $f['WORK_POSITION'] . ')'
                            ); ?>
                            <span id="system_person_<?= $f['ID']; ?>"
                                  bx-tooltip-user-id="<?= $f['ID'] ?>"><?= $name ?></span>
                            <?= ($arParams['COUNT_DAYS'] ?
                                    ' (' . GetMessage(
                                            "BITRIX_PLANNER_DNEY"
                                    ) . '<span class="days_num ' . ($d < 0 ? 'planner-red' : '') . '">' . $d . '</span>)' .
                                    ($arResult['ALLOW_DAYS_ADD'] ? ' <input size=3 id="days_left_' . $f['ID'] . '" style="display:none"><span class="edit-btn ' . ($arParams['COUNT_DAYS_AUTO'] && !$f['UF_DAYS_DATE'] ? 'planner-red' : '') . '" data-days="' . $f['DAYS_LEFT_BASE'] . '" onclick="AddDaysLeft(this, ' . $f['ID'] . ')">&#9997;</span>' : '')
                                    : ''); ?>

                            <?
                            if ($arResult['drpTask']
                                    || $arResult['bossTask'][$f['ID']]): ?>
                                <br><a href="javascript:void(0);"
                                       onclick="showSetVacation(<?= $f['ID']; ?>, '<?= $name ?>',
                                               '<?= $arResult['bossTask'][$f['ID']]['id'] ?? $arResult['drpTask'] ?>',
                                       <?= json_encode($arResult['bossTask'][$f['ID']] ? true : false) ?>)">
                                    Задать отпуск
                                </a>
                            <?
                            endif; ?>

                            <?
                            if ($arResult['bossApproveTask'][$f['ID']]): ?>
                                <div class="boss_approve_task">
                                    <a href="javascript:void(0);"
                                       taskid="<?= $arResult['bossApproveTask'][$f['ID']]; ?>" class="approve_vacation">
                                        Согласовать отпуск
                                    </a>
                                    /
                                    <a href="javascript:void(0);"
                                       onclick="showRejectVacation(<?= $arResult['bossApproveTask'][$f['ID']]; ?>)">
                                        Отклонить отпуск
                                    </a>
                                </div>
                            <?
                            endif; ?>

                            <?
                            if (in_array($f['ID'], $arResult['UNPLAN_USERS'])): ?>
                                <div style="color: red">График не запланирован</div>
                            <?
                            endif; ?>
                        </th>
                    </tr>
                    <?php
                } ?>
            </table>
            <!-- Start Главная таблица с отпусками -->
            <table id="main-table">
                <tr>
                    <th style="min-width:300px"></th>
                    <?
                    for ($i = $arResult['start']; $i <= $arResult['end']; $i->modify('+1 day')) {
                        $t = clone($i);
                        $t->modify('last day of this month');
                        if ($t < $arResult['end']) {
                            $colspan = $t->diff($i);
                            $colspan = $colspan->format('%a') + 1;
                        } else {
                            $colspan = $i->diff($arResult['end']);
                            $colspan = $colspan->format('%a') + 1;
                        }
                        $i = $t; ?>
                        <td class="day-title"
                            colspan="<?= $colspan; ?>"><?= FormatDate('f', $i->getTimestamp()); ?></td>
                        <?php
                    } ?>
                </tr>
                <tr>
                    <th style="min-width:300px;background: #eef2f4;">Сотрудники</th>
                    <?php
                    foreach ($arResult['days'] as $day): ?>
                        <td class="day-title <?= $day['class']; ?>"
                            title="<?= $day['title']; ?>"><?= $day['date']; ?></td>
                    <?php
                    endforeach; ?>
                    <?php
                    $showDep = [];
                    foreach ($arResult['USERS'] as $f) {
                    $d = $f['DAYS_LEFT'];
                    $count = isset($_POST['department']) && count($_POST['department']) > 1;

                    if (($count || $_POST['subdep'] == 'true')
                    && !in_array($f['dep'], $showDep)) {
                    $showDep[] = $f['dep']; ?>
                <tr>
                    <th class="dep<?= (count($showDep) % 2); ?>">
                        <?= $arResult['DEPARTMENT_LIST'][$f['dep']]['DEPTH_NAME']; ?></th>
                    <?php
                    for ($i = clone($arResult['start']); $i <= $arResult['end']; $i->modify('+1 day')) { ?>
                        <td></td>
                        <?php
                    } ?>
                </tr>
                <?php
                } ?>
                <?
                $userLineClass = 'any-user';
                if ($f['ID'] === intval($_REQUEST["byUserId"])) {
                    $userLineClass = 'current-user';
                } elseif (($f['ID'] == $arResult['USER_ID']) && empty ($_REQUEST["byUserId"])) {
                    $userLineClass = 'current-user';
                }
                ?>
                <tr class="<?= $userLineClass ?>">
                    <th style="text-align:left;" class="dep<?= (count($showDep) % 2); ?>">
                                <span id="system_person_<?= $f['ID']; ?>" bx-tooltip-user-id="<?= $f['ID'] ?>">
                                    <a href="<?= $arResult['BASE_URL']; ?>&set_user_id=<?= $f['ID']; ?>">
                                        <?= htmlspecialcharsbx(
                                                $f['LAST_NAME'] . ' ' . $f['NAME'] . ' (' . $f['WORK_POSITION'] . ')'
                                        ); ?>
                                    </a>
                                </span>
                        <?= ($arParams['COUNT_DAYS'] ?
                                ' (' . GetMessage(
                                        "BITRIX_PLANNER_DNEY"
                                ) . '<span class="days_num ' . ($d < 0 ? 'planner-red' : '') . '">' . $d . '</span>)' .
                                ($arResult['ALLOW_DAYS_ADD'] ? ' <input size=3 id="days_left_' . $f['ID'] . '" style="display:none"><span class="edit-btn ' . ($arParams['COUNT_DAYS_AUTO'] && !$f['UF_DAYS_DATE'] ? 'planner-red' : '') . '" data-days="' . $f['DAYS_LEFT_BASE'] . '" onclick="AddDaysLeft(this, ' . $f['ID'] . ')">&#9997;</span>' : '')
                                : ''); ?>

                        <?
                        if ($arResult['drpTask']
                                || $arResult['bossTask'][$f['ID']]): ?>
                            <br><a href="javascript:void(0);">Задать отпуск</a>
                        <?
                        endif; ?>

                        <?
                        if ($arResult['bossApproveTask'][$f['ID']]): ?>
                            <div class="boss_approve_task">
                                <a href="javascript:void(0);">
                                    Согласовать отпуск
                                </a>
                                /
                                <a href="javascript:void(0);">
                                    Отклонить отпуск
                                </a>
                            </div>
                        <?
                        endif; ?>

                        <?
                        if (in_array($f['ID'], $arResult['UNPLAN_USERS'])): ?>
                            <div style="color: red">График не запланирован</div>
                        <?
                        endif; ?>
                    </th>
                    <?php
                    for ($i = clone($arResult['start']); $i <= $arResult['end']; $i->modify('+1 day')) {
                        $id = $f['ID'] == $arResult['USER_ID'] ? ' id="day_' . $i->getTimestamp() . '"' : '';
                        $m = $arResult['MARKER'][$f['ID']][$i->format('Ymd')];
                        $class = 'mark-' . (in_array($f['ID'], $arResult['REJECTED_USERS'])
                                && $i >= $futureYear && $m ? 'plan_change' : strtolower($m['CODE']));
                        if ($i->format('Ymd') > date('Ymd')) {
                            $class_past = 'day-future';
                        }
                        $transferDayClass = (!empty($arResult["TRANSFER_USERS_DAYS"][$f['ID'] . $i->format('dmY')]))
                                ? (' day-transfer' . ($m ? '' : ' day-transfer-' . $arResult["TRANSFER_USERS_DAYS"][$f['ID'] . $i->format(
                                                        'dmY'
                                                )])) : '';
                        if ($m['ACTIVE'] == 'N') { ?>
                            <td <?php
                                if ($arResult['ADMIN']): ?>ondblclick="ApproveVacation(<?= $m['ID']; ?>)"<?php
                            endif; ?>
                                class="zero-pad day-saved <?= $class_past; ?>"
                                title="<?= $m['TITLE']; ?>" <?= $id; ?>>
                                <div class="<?= $class . ($m['PARTIAL'] ? ' day-partial ' : '') . $transferDayClass; ?>"
                                     <?php
                                     if ($arResult['USER_ID'] == $f['ID']
                                     && $m['can_change'] !== false): ?>style="cursor:pointer;border-radius:10px"
                                     onclick="EditVacation('<?= $m['ID']; ?>', '<?= $m['ACTIVE_FROM']; ?>', '<?= $m['ACTIVE_TO']; ?>', '<?= $m['CODE']; ?>',
                                             '<?= CUtil::JSEscape(
                                             str_replace(
                                                     '"',
                                                     '&quot;',
                                                     $m['~PREVIEW_TEXT']
                                             )
                                     ); ?>')"
                                     <?php
                                     else: ?>style="border-radius:10px"<?php
                                endif; ?>>&nbsp;
                                </div>
                                <?php
                                if ($m['PREVIEW_TEXT'] && $m['FIRST_DAY']): ?>
                                    <div class="comment"><?= htmlspecialcharsbx($m['~PREVIEW_TEXT']); ?></div>
                                <?php
                                endif; ?>
                            </td>
                            <?php
                        } elseif ($m['ACTIVE'] == 'Y') { ?>
                            <td class="zero-pad <?= $class_past; ?>" <?= ($arResult['ADMIN'] ? 'ondblclick="UnApproveVacation(' . $m['ID'] . ')"' : ''); ?>
                                title="<?= $m['TITLE']; ?>"<?= $id; ?>>
                                <div class="active-day <?= $class . ($m['PARTIAL'] ? ' day-partial ' : '') . $transferDayClass; ?>">
                                    &nbsp;
                                </div><?= ($m['PREVIEW_TEXT'] && $m['FIRST_DAY'] ?
                                        '<div class="comment">' . htmlspecialcharsbx(
                                                $m['~PREVIEW_TEXT'] .
                                                ($m['transfer'] ? ($m['~PREVIEW_TEXT'] ? ', ' : '') .
                                                        $m['transfer'] : '')
                                        ) . '</div>' : ''); ?>
                            </td>
                            <?php
                        } elseif ($id) { ?>
                            <?
                            $vacationType = ($arResult['VACATION_TASK'] && $i->format('Y') == $futureYear->format(
                                    'Y'
                            ) ? 'vacation' : 'out_of_plan') ?>
                            <td <?= $id; ?>
                                    class="<?= $class_past . $transferDayClass; ?>"
                                    data-vac-type="<?= $vacationType ?>"></td>
                            <?php
                        } else { ?>
                            <td class="<?= $class_past . $transferDayClass; ?>"></td>
                            <?php
                        }
                    }
                    }
                    ?>
                </tr>
            </table>
            <!-- End Главная таблица с отпусками -->
            <div id="page_nav">
                <el-pagination background layout="prev, pager, next"
                               v-if="<?= json_encode(intval($arResult['total']) > intval($arParams['PAGE_SIZE'])) ?>"
                               :total="<?= intval($arResult['total']) ?>"
                               :page-size="<?= intval($arParams['PAGE_SIZE']) ?>"
                               :current-page="<?= intval($_POST['page']) ?>"
                               @current-change="loadVacations"></el-pagination>
            </div>
            <?
            foreach ($arResult['VACATION_TASK']['actions'] as $action => $title): ?>

                <div class="task_btn el-button <?= ($action == 'approve' ? 'el-button--success' : 'el-button--danger') ?>"
                     data-taskid="<?= $arResult['VACATION_TASK']['id']; ?>"
                     data-days="<?= $arResult['VACATION_TASK']['days']; ?>"
                     data-action="<?= $action ?>">
                    <?= $title ?>
                </div>
            <?
            endforeach; ?>
            <?php

            // Запланированные отсутствия
            //
            if (count($arResult['PERIOD'])) { ?>
                <div class="planner_period">
                    <table style="border-collapse:collapse">
                        <tr>
                            <td colspan=8 style="background-color:#eef2f4;color:#000;text-align:center">
                                <b><?= GetMessage("BITRIX_PLANNER_ZAPLANIROVANNYE_OTSU"); ?></b>&nbsp;<div
                                        style="float:right"><a
                                            href="<?= $arResult['BASE_URL']; ?>&export=report"><?= GetMessage(
                                                "BITRIX_PLANNER_EKSPORT_V"
                                        ); ?></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        foreach ($arResult['PERIOD'] as $f) {
                            $uid = intval($f['PROPERTY_USER_VALUE']);
                            if ($uid != $arResult['USER_ID'] && !$arResult['ADMIN'] && !$arResult['HR']) {
                                continue;
                            }

                            $arActions = [];
                            $class = 'mark-' . strtolower($f['CODE']);

                            if ($f['ACTIVE'] == 'Y') {
                                if ($uid == $arResult['USER_ID']
                                        && $f['can_change']) {
                                    $arActions[] = '<a href="/lk/planner/transfer/?id=' . $f['ID'] . '" class="link-disapprove">' . GetMessage(
                                                    "BITRIX_PLANNER_CHANGE_VACATION"
                                            ) . '</a>';

                                    if ($f['CODE'] == 'OUT_OF_PLAN') {
                                        $arActions[] = '<a href="javascript:unActiveVac(' . $f['ID'] . ')" class="link-disapprove">' . GetMessage(
                                                        "BITRIX_PLANNER_OUT_OF_PLAN_CANCEL"
                                                ) . '</a>';
                                    }
                                }
                            } elseif (!$f['PROPERTY_BP_VALUE']
                                    || ($arResult['VACATION_TASK']
                                            && $_REQUEST['year'] == (date('Y') + 1))) {
                                if ($uid == $arResult['USER_ID']) {
                                    $arActions[] = '<a href="javascript:EditVacation(' . $f['ID'] . ', \'' . $f['ACTIVE_FROM'] . '\', \'' . $f['ACTIVE_TO'] . '\', \'' . $f['CODE'] . '\', \'' . CUtil::JSEscape(
                                                    str_replace(
                                                            '"',
                                                            '&quot;',
                                                            $f['~PREVIEW_TEXT']
                                                    )
                                            ) . '\')" class="link-edit">' . GetMessage("BITRIX_PLANNER_IZMENITQ") . '</a>';
                                    $arActions[] = '<a href="javascript:DeleteVacation(' . $f['ID'] . ')" class="link-remove">' . GetMessage(
                                                    "BITRIX_PLANNER_UDALITQ"
                                            ) . '</a>';

                                    if ($f['PROPERTY_ABSENCE_TYPE_ENUM_ID'] == $arResult['ABSENCE_TYPES']['LEAVEUNPAYED']
                                            || $f['CODE'] == 'OUT_OF_PLAN') {
                                        if ($f['PROPERTY_ABSENCE_TYPE_ENUM_ID'] == $arResult['ABSENCE_TYPES']['LEAVEUNPAYED']) {
                                            $href = '/lk/planner/leave_absence/?start=' . $f['ACTIVE_FROM'] . '&end=' . $f['ACTIVE_TO'];
                                            $arActions[] = '<a href="' . $href . '" class="link-disapprove">' . GetMessage(
                                                            "BITRIX_PLANNER_MAKE_VACATION"
                                                    ) . '</a>';
                                        } else {
                                            if (!$f['can_change']) {
                                                $arActions[] = '<a href="javascript:void(0)" class="link-disapprove" onclick="showError()">' . GetMessage(
                                                                "BITRIX_PLANNER_MAKE_VACATION"
                                                        ) . '</a>';
                                            } else {
                                                $href = '/lk/planner/off_graf/?start=' . $f['ACTIVE_FROM'] . '&end=' . $f['ACTIVE_TO'] . '&vacationid=' . $f['ID'];
                                                $arActions[] = '<a href="' . $href . '" class="link-disapprove">' . GetMessage(
                                                                "BITRIX_PLANNER_MAKE_VACATION"
                                                        ) . '</a>';
                                            }
                                        }
                                    }
                                }
                            } ?>
                            <?
                            $userLineClass = '';
                            if ($uid === intval($_REQUEST["byUserId"])) {
                                $userLineClass = 'current-user';
                            } elseif ($arResult['ADMIN'] && ($uid == $arResult['USER_ID']) && empty ($_REQUEST["byUserId"])) {
                                $userLineClass = 'current-user';
                            }
                            ?>
                            <tr class="<?= $userLineClass ?>">
                                <td>
                                    <?= htmlspecialcharsbx(
                                            $arResult['USERS'][$uid]['LAST_NAME']
                                    ); ?> <?= htmlspecialcharsbx($arResult['USERS'][$uid]['NAME']); ?>
                                </td>
                                <td class="<?= $class; ?>"><?= htmlspecialcharsbx(
                                            $arResult['TYPES'][$f['CODE']]
                                    ); ?></td>
                                <td>
                                    <?= $f['ACTIVE_FROM']; ?>
                                </td>
                                <td>
                                    <?= $f['ACTIVE_TO']; ?>
                                </td>
                                <td>
                                    <span class="status <?= ($f['users'] ? 'pointer' : '') ?>"">
                                    <?= $f['VACATION_STATUS']; ?>
                                    <?
                                    if ($f['users']): ?>
                                        <span class="task_status">
                                                Ожидает исполнения:<br>
                                            <?= $f['users'] ?>
                                            </span>
                                    <?
                                    endif; ?>
                                    </span>
                                </td>
                                <td><?= $f['HUMAN_TIME']; ?></td>
                                <td><?= $f['PREVIEW_TEXT'] . ($f['transfer'] ? ($m['~PREVIEW_TEXT'] ? ', ' : '') . $f['transfer'] : ''); ?></td>
                                <td><?= implode(' / ', $arActions); ?></td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </div>
                <?php
            }
            if ($arResult['SUMMARY']) {
                $colspan = count($arResult['TYPES']) + 1; ?>
                <table style="border-collapse:collapse" class="itogo">
                    <tr>
                        <td colspan=<?= $colspan; ?> style="background-color:#eef2f4;text-align:center;color: #000;
                        ">
                        <b><?= GetMessage("BITRIX_PLANNER_ITOGO_ZA"); ?><?= $arMonths[$arResult['MONTH']]; ?>
                            <?= ($_REQUEST['dateFrom'] && $_REQUEST['dateTo'] ? 'за выбранный период' : $arResult['YEAR']); ?></b>&nbsp;<div
                                style="float:right"><a
                                    href="<?= $arResult['BASE_URL']; ?>&export=summary"><?= GetMessage(
                                        "BITRIX_PLANNER_EKSPORT_V"
                                ); ?></a>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th><?php
                        foreach ($arResult['TYPES'] as $type_id => $type) { ?>
                            <?
                            /* echo '<pre>';print_r($type_id);echo '</pre>';*/ ?>
                            <th <?
                                if (!in_array($type_id, [
                                    'VACATION',
                                    'LEAVEUNPAYED',
                                    'PREGNANT',
                                    'ASSIGNMENT',
                                    'CHILD',
                                    'LEAVESICK',
                            ])) { ?>class="hidden"<?
                            } ?>><?= htmlspecialcharsbx($type); ?></th>
                            <?php
                        } ?>
                    </tr>
                    <?php
                    foreach ($arResult['USERS'] as $f) {
                    $uid = intval($f['ID']);
                    if (!$arResult['SUMMARY'][$uid]) {
                        continue;
                    } ?>
                    <?
                    $userLineClass = '';
                    if ($uid === intval($_REQUEST["byUserId"])) {
                        $userLineClass = 'current-user';
                    } elseif ($arResult['ADMIN'] && ($uid == $arResult['USER_ID']) && empty ($_REQUEST["byUserId"])) {
                        $userLineClass = 'current-user';
                    }
                    ?>
                    <tr class="<?= $userLineClass ?>">
                        <td>
                            <a href="<?= $arResult['BASE_URL']; ?>&set_user_id=<?= $uid; ?>"><?= htmlspecialcharsbx(
                                        $arResult['USERS'][$uid]['LAST_NAME'] . ' ' . $arResult['USERS'][$uid]['NAME']
                                ); ?></a>
                        </td>
                        <?php
                        foreach ($arResult['TYPES'] as $type_id => $type) { ?>
                            <td <?
                                if (!in_array($type_id, [
                                    'VACATION',
                                    'LEAVEUNPAYED',
                                    'PREGNANT',
                                    'ASSIGNMENT',
                                    'CHILD',
                                    'LEAVESICK',
                            ])) { ?>class="hidden"<?
                            } ?>><?= ($arResult['SUMMARY'][$uid][$arResult['ABSENCE_TYPES'][$type_id]]
                                        ? $arResult['SUMMARY'][$uid][$arResult['ABSENCE_TYPES'][$type_id]] . ' дн.' : ''); ?></td>
                            <?php
                        } ?>
                        <?php
                        } ?>
                </table>
                <?php
            }
            if ($_POST['department']
                    || $_POST['subdep']
                    || $_POST['pregnancy']
                    || $_POST['dateFrom']
                    || $_POST['dateTo']
                    || $_POST['planStatus']
                    || $_POST['offset']) {
                $buffer = ob_get_contents();
                ob_clean();
                echo json_encode(['data' => $buffer]);
                die;
            } ?>
        </div>
    </div>
    <?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/interface/admin_lib.php');
    $uid = $arResult['USER_ID'];
    ?>

</div>
<div style="position:absolute;top:0px;left:0px;display:none;box-shadow:4px 4px 10px #666" id="date_edit_form"
     class="date_edit_form-wr">
    <form method=post name=add_form id="edit_form" class="edit_form">
        <input type=hidden name=action>
        <input type=hidden name=id>
        <div class="edit_form-wrap">
            <table style="border-collapse:collapse;background-color:#FFF" cellpadding=4>
                <tr>
                    <td colspan=2 style="background-color:#e8e8e8;text-align:center" id="date_edit_title"
                        class="date_edit_title"></td>
                </tr>
                <tr class="date_edit_tr first">
                    <td><?= GetMessage("BITRIX_PLANNER_SOTRUDNIK") ?></td>
                    <td><?= htmlspecialcharsbx(
                                $arResult['USERS'][$uid]['LAST_NAME'] . ' ' . $arResult['USERS'][$uid]['NAME']
                        ) ?></td>
                </tr>
                <tr class="date_edit_tr">
                    <td><?= GetMessage("BITRIX_PLANNER_DATA_NACALA") ?></td>
                    <td><input name=day_from size=16 autocomplete="off">
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.calendar", "", [
                                        "SHOW_INPUT" => "N",
                                        "FORM_NAME" => "add_form",
                                        "INPUT_NAME" => "day_from",
                                        "INPUT_NAME_FINISH" => "",
                                        "INPUT_VALUE" => "",
                                        "INPUT_VALUE_FINISH" => "",
                                        "SHOW_TIME" => $arParams['SHOW_TIME'],
                                        "HIDE_TIMEBAR" => "N",
                                ]
                        ); ?>

                    </td>
                </tr>
                <tr class="date_edit_tr">
                    <td><?= GetMessage("BITRIX_PLANNER_DATA_KONCA") ?></td>
                    <td><input name=day_to size=16 autocomplete="off">
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.calendar", "", [
                                        "SHOW_INPUT" => "N",
                                        "FORM_NAME" => "add_form",
                                        "INPUT_NAME" => "day_to",
                                        "INPUT_NAME_FINISH" => "",
                                        "INPUT_VALUE" => "",
                                        "INPUT_VALUE_FINISH" => "",
                                        "SHOW_TIME" => $arParams['SHOW_TIME'],
                                        "HIDE_TIMEBAR" => "N",
                                ]
                        ); ?>
                    </td>
                </tr>
                <tr class="date_edit_tr">
                    <td><?= GetMessage("BITRIX_PLANNER_TIP_ZAPISI") ?></td>
                    <td>
                        <select class="edit_type_select" name=event_type size=<?= count($arResult['TYPES']) ?>>
                            <?php
                            foreach ($arResult['TYPES'] as $k => $v) {
                                if (!in_array($k, ['VACATION', 'LEAVEUNPAYED'])) {
                                    continue;
                                } ?>
                                <option value="<?= htmlspecialcharsbx($k); ?>"
                                        data-vac-value="<?= strtolower($k) ?>"><?= htmlspecialcharsbx($v); ?></option>
                                <?php
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr class="date_edit_tr">
                    <td><?= GetMessage("BITRIX_PLANNER_PRIMECANIE") ?></td>
                    <td><input name=PREVIEW_TEXT style="width:100%;box-sizing: border-box;"></td>
                </tr>
                <tr class="dt_buttons">
                    <td colspan=2 style="font-weight:bold;height:20px;text-align:center">
                        <div class="webform-button-accept planner-ok-button"
                             onclick="sendToSave()"><?= GetMessage("BITRIX_PLANNER_SOHRANITQ") ?></div>
                        <div class="planner-esc-button"
                             onclick="closeForm()"><?= GetMessage("BITRIX_PLANNER_OTMENA") ?></div>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>

<script>
    var bossTasks = <?=json_encode($arResult['bossTask'])?>;

    function showSetVacation(id, name, bp, action) {
        let vacations = [];

        if (bossTasks && bossTasks[id]) {
            vacations = bossTasks[id].vacations;
        }

        window.setVacation.show = true;
        window.setVacation.id = id;
        window.setVacation.bp = bp;
        window.setVacation.action = action;
        window.setVacation.user = name;
        window.setVacation.periods = vacations;
    }

    function showRejectVacation(task) {
        window.rejectVacation.show = true;
        window.rejectVacation.task = task;
    }
</script>
<div id="set_vacation"></div>
<div id="reject_vacation"></div>

<div id="addVacationButton">
    <div class="ui-btn ui-btn-primary addVacation">
        <span class="webform-small-button-icon"></span>
        Подать заявление
    </div>

    <?php
    if ($arResult['DRP']): ?>
        <a class="ui-btn ui-btn-primary"
           href="/lk/planner/plan/">
            Запустить планирование графика
        </a>
    <?php
    endif; ?>
</div>

<div id="vacationChoiceWindow">
    <div style="display: grid">
        <a class="ui-btn ui-btn-primary"
           href="/lk/planner/leave_absence/">
            Отпуск за свой счет
        </a>
        <a class="ui-btn ui-btn-primary"
           href="/lk/planner/off_graf/">
            Отпуск вне графика
        </a>
        <a class="ui-btn ui-btn-primary"
           href="/lk/planner/transfer/">
            Перенос отпуска
        </a>
    </div>
</div>
