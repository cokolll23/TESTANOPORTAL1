<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\Extension;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\UserTable;

$APPLICATION->SetPageProperty('BodyClass', ($bodyClass ? $bodyClass . ' ' : '') . 'no-all-paddings no-background');
\Bitrix\Main\UI\Extension::load(array("ui.buttons", "ui.alerts", "ui.tooltip", "ui.hint", "utils.glide"));
\CJSCore::Init("loader");

Page\Asset::getInstance()->addJs($templateFolder . '/js/utils.js');
Page\Asset::getInstance()->addJs($templateFolder . '/js/stresslevel.js');
Page\Asset::getInstance()->addJs($templateFolder . '/js/grats.js');
Page\Asset::getInstance()->addJs($templateFolder . '/js/profilepost.js');
Page\Asset::getInstance()->addJs($templateFolder . '/js/tags.js');
Page\Asset::getInstance()->addJs($templateFolder . '/js/tags-users-popup.js');
Page\Asset::getInstance()->addJs($templateFolder . '/js/form-entity.js');
Page\Asset::getInstance()->addCss('/bitrix/components/bitrix/socialnetwork.blog.blog/templates/.default/style.css');

\Bitrix\Main\UI\Extension::load("ui.icons.b24");
pretty_print($arResult);

if (!$arResult["Permissions"]['view']) {
    ?>
    <div class="ui-alert ui-alert-danger">
    <span class="ui-alert-message"><?= Loc::getMessage('INTRANET_USER_PROFILE_VIEW_ACCESS_DENIED') ?></span>
    </div><?
    return;
}

$this->SetViewTarget('inside_pagetitle');
$APPLICATION->includeComponent(
        'bitrix:intranet.binding.menu',
        '',
        array(
                'SECTION_CODE' => 'user_detail',
                'MENU_CODE' => 'top_menu',
                'CONTEXT' => [
                        'USER_ID' => $arResult['User']['ID']
                ]
        )
);
$this->EndViewTarget();
if (isset($arResult["Urls"]["CommonSecurity"]) && $arResult["User"]["STATUS"] !== "email") {
    $this->SetViewTarget('inside_pagetitle');
    ?>
    <? if ($arResult["Permissions"]['edit']): ?>
        <span onclick="BX.SidePanel.Instance.open('<?= $arResult["Urls"]["CommonSecurity"] . "?page=auth" ?>', {width: 1100});"
              class="ui-btn ui-btn-light-border ui-btn-themes">
			<?= Loc::getMessage("INTRANET_USER_PROFILE_PASSWORDS") ?>
		</span>
    <? endif ?>

    <?
    if ($arResult["OTP_IS_ENABLED"] == "Y" && ($arResult["IsOwnProfile"] || $USER->CanDoOperation('security_edit_user_otp'))):?>
        <span onclick="BX.SidePanel.Instance.open('<?= $arResult["Urls"]["CommonSecurity"] . "?page=security" ?>', {width: 1100});"
              class="ui-btn ui-btn-light-border ui-btn-themes">
			<?= Loc::getMessage("INTRANET_USER_PROFILE_SECURITY") ?>
		</span>
    <? endif ?>
    <? $this->EndViewTarget();
}
?>
<? if ($arResult["IsOwnProfile"]
        && file_exists($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/bitrix/socialnetwork.admin.set")
        && ($arResult["User"]["SHOW_SONET_ADMIN"])) {
    $APPLICATION->IncludeComponent(
            "bitrix:socialnetwork.admin.set",
            "",
            array(
                    "PROCESS_ONLY" => "Y"
            ),
            $component,
            array("HIDE_ICONS" => "Y")
    );
}
?>
<div class="intranet-user-profile" id="intranet-user-profile-wrap">
    <div class="intranet-user-profile-column-left">
        <!-- фото ["User"]["STATUS"]-->
        <div class="intranet-user-profile-column-block">
            <div class="intranet-user-profile-rank">
                <? if (
                        isset($arResult["User"]["STATUS"]) && !empty($arResult["User"]["STATUS"])
                        && (
                                !(
                                        $arResult["User"]["STATUS"] === "employee"
                                        && ($arResult["IsOwnProfile"] || !$arResult["Permissions"]['edit'])
                                )
                                || $arResult["User"]["SHOW_SONET_ADMIN"]
                        )
                ): ?>
                    <div class="intranet-user-profile-rank-item intranet-user-profile-rank-<?= $arResult["User"]["STATUS"] ?> <? if ($arResult["Permissions"]['edit']): ?>intranet-user-profile-rank-item-pointer<? endif ?>"
                         data-role="user-profile-actions-button">
                        <span><?= ToUpper(Loc::getMessage("INTRANET_USER_PROFILE_" . $arResult["User"]["STATUS"])) ?></span>
                        <? if ($arResult["Permissions"]['edit']): ?>
                            <span class="intranet-user-profile-rank-item-config"></span>
                        <? endif ?>
                    </div>
                <? endif ?>
            </div>

            <div class="intranet-user-profile-status-info">
                <div class="intranet-user-profile-status intranet-user-profile-status-<?= $arResult["User"]["ONLINE_STATUS"]["STATUS"] ?>">
                    <?= ToUpper($arResult["User"]["ONLINE_STATUS"]["STATUS_TEXT"]) ?>
                </div>
                <div class="intranet-user-profile-last-time">
                    <? if ($arResult["User"]["ONLINE_STATUS"]['STATUS'] == 'idle'): ?>
                        <? echo($arResult["User"]["ONLINE_STATUS"]['LAST_SEEN_TEXT'] ? Loc::getMessage('INTRANET_USER_PROFILE_LAST_SEEN_IDLE_' . ($arResult["User"]["PERSONAL_GENDER"] == 'F' ? 'F' : 'M'),
                                array('#LAST_SEEN#' => $arResult["User"]["ONLINE_STATUS"]['LAST_SEEN_TEXT'])) : ''); ?>
                    <? else: ?>
                        <? echo($arResult["User"]["ONLINE_STATUS"]['LAST_SEEN_TEXT'] ? Loc::getMessage('INTRANET_USER_PROFILE_LAST_SEEN_' . ($arResult["User"]["PERSONAL_GENDER"] == 'F' ? 'F' : 'M'),
                                array('#LAST_SEEN#' => $arResult["User"]["ONLINE_STATUS"]['LAST_SEEN_TEXT'])) : ''); ?>
                    <? endif; ?>
                </div>
            </div>

            <div class="intranet-user-profile-userpic ui-icon ui-icon-common-user
				<? if ($arResult["IsOwnProfile"] || $arResult["Permissions"]['edit']): ?>
					intranet-user-profile-userpic-edit
				<? endif ?>"
            >
                <i
                        id="intranet-user-profile-photo"
                        <? if (isset($arResult["User"]["PHOTO"]) && !empty($arResult["User"]["PHOTO"])): ?>
                            style="background-image: url('<?= \CHTTP::urnEncode($arResult["User"]["PHOTO"]) ?>'); background-size: cover"
                        <? endif ?>
                ></i>
                <? if ($arResult["IsOwnProfile"] || $arResult["Permissions"]['edit']): ?>
                    <div class="intranet-user-profile-userpic-load">
                        <div class="intranet-user-profile-userpic-create"
                             id="intranet-user-profile-photo-camera"><?= Loc::getMessage("INTRANET_USER_PROFILE_AVATAR_CAMERA") ?></div>
                        <div class="intranet-user-profile-userpic-upload"
                             id="intranet-user-profile-photo-file"><?= Loc::getMessage("INTRANET_USER_PROFILE_AVATAR_LOAD") ?></div>
                    </div>
                    <div class="intranet-user-profile-userpic-remove" id="intranet-user-profile-photo-remove"></div>
                <? endif ?>
            </div>

            <?
            if (!$arResult["IsOwnProfile"] && $arResult["User"]["STATUS"] !== "email") {
                ?>
                <div class="intranet-user-profile-actions">
                    <?
                    if ($arResult["User"]["ACTIVE"] == "N"):?>
                        <a
                                class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round"
                                href="javascript:void(0)"
                                onclick="if (top.BXIM) { top.BXIM.openHistory(<?= $arResult["User"]['ID'] ?>); }"
                        >
                            <?= Loc::getMessage("INTRANET_USER_PROFILE_CHAT_HISTORY") ?>
                        </a>
                    <? else: ?>
                        <a
                                class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round"
                                href="javascript:void(0)"
                                onclick="if (top.BXIM) { top.BXIM.openMessenger(<?= $arResult["User"]['ID'] ?>); }"
                        >
                            <?= Loc::getMessage("INTRANET_USER_PROFILE_CHAT") ?>
                        </a>
                        <a
                                class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round"
                                href="javascript:void(0)"
                                onclick="if (top.BXIM) { top.BXIM.callTo(<?= $arResult["User"]['ID'] ?>); }"
                        >
                            <?= Loc::getMessage("INTRANET_USER_PROFILE_VIDEOCALL") ?>
                        </a>
                    <? endif ?>
                </div>
                <?
            }

            $APPLICATION->IncludeComponent(
                    "bitrix:intranet.absence.user",
                    "profile",
                    array(
                            "ID" => $arResult["User"]['ID'],
                    ),
                    false,
                    array("HIDE_ICONS" => "Y")
            );
            ?>
        </div>
        <?
        if (
                $arResult["StressLevel"]['AVAILABLE'] == 'Y'
                && !in_array($arResult["User"]["STATUS"], ['email', 'extranet'])
                && !$arResult["isExtranetSite"]
        ) {
            ?>
            <!-- стресс-->

            <?
        }
        ?>

        <?php
        $userField = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_COMPETENCE']
        )->Fetch();
        ?>
        <!-- COMPETENCES-->
        <?php if ($arResult['COMPETENCES']): ?>
            <div class="intranet-user-profile-column-block">
                <h2 class="intranet-user-profile-column-block-title">
                    <span class="intranet-user-profile-column-block-title-text">
                        <?= Loc::getMessage('INTRANET_USER_PROFILE_COMPETENCE_TITLE') ?>
                    </span>
                </h2>
                <div class="user-profile-competences">
                    <?php foreach ($arResult['COMPETENCES'] as $comp): ?>
                        <div class="user-profile-competences__item">
                            <a target="_top" href="/company?apply_filter=Y&UF_COMPETENCE=<?php echo $comp['ID'] ?>">
                                <?php echo $comp['TITLE'] ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php
        if (0 && $arResult["IsOwnProfile"] && $arResult["User"]["STATUS"] !== "email") {
            ?>
            <div class="intranet-user-profile-column-block">
                <div class="intranet-user-profile-apps">
                    <div class="intranet-user-profile-apps-item">
                        <?/*
						.intranet-user-profile-apps-icon-active
						.intranet-user-profile-apps-icon-complete
						.intranet-user-profile-apps-icon-download
					*/
                        ?>
                        <div class="intranet-user-profile-apps-title"><?= Loc::getMessage("INTRANET_USER_PROFILE_MOBILE_APP") ?></div>
                        <span data-role="profile-android-app"
                              class="intranet-user-profile-apps-icon  intranet-user-profile-apps-icon-android
						<?
                              if ($arResult["User"]["APP_ANDROID_INSTALLED"]):?>
							intranet-user-profile-apps-icon-active
						<? else: ?>
							intranet-user-profile-apps-icon-download
						<? endif ?>"
                        ></span>
                        <span data-role="profile-ios-app"
                              class="intranet-user-profile-apps-icon intranet-user-profile-apps-icon-appstore
						<?
                              if ($arResult["User"]["APP_IOS_INSTALLED"]):?>
							intranet-user-profile-apps-icon-active
						<? else: ?>
							intranet-user-profile-apps-icon-download
						<? endif ?>"
                        ></span>
                    </div>
                    <div class="intranet-user-profile-apps-item">
                        <div class="intranet-user-profile-apps-title"><?= Loc::getMessage("INTRANET_USER_PROFILE_DESKTOP_APP") ?></div>
                        <a href="https://dl.bitrix24.com/b24/bitrix24_desktop.exe" target="_blank"
                           class="intranet-user-profile-apps-icon intranet-user-profile-apps-icon-windows
						<?
                           if ($arResult["User"]["APP_WINDOWS_INSTALLED"]):?>
							intranet-user-profile-apps-icon-active
						<? else: ?>
							intranet-user-profile-apps-icon-download
						<? endif ?>"
                        ></a>
                        <a href="https://dl.bitrix24.com/b24/bitrix24_desktop.dmg" target="_blank"
                           class="intranet-user-profile-apps-icon intranet-user-profile-apps-icon-iphone
						<?
                           if ($arResult["User"]["APP_MAC_INSTALLED"]):?>
							intranet-user-profile-apps-icon-active
						<? else: ?>
							intranet-user-profile-apps-icon-download
						<? endif ?>"
                        ></a>
                    </div>
                </div>
            </div>
            <?
        }

        if (0 && !empty($arResult["DISK_INFO"])) {
            ?>
            <div class="intranet-user-profile-column-block">
                <div class="intranet-user-profile-apps">5
                    <div class="intranet-user-profile-desktop-block">
                        <?= Loc::getMessage("INTRANET_USER_PROFILE_DISK_INSTALLED") ?>
                        <?
                        if (
                                isset($arResult["DISK_INFO"]["INSTALLATION_DATE"])
                                && !empty(($arResult["DISK_INFO"]["INSTALLATION_DATE"]))
                        ) {
                            echo $arResult["DISK_INFO"]["INSTALLATION_DATE"];
                        }

                        if (
                                isset($arResult["DISK_INFO"]["SPACE"])
                                && !empty(($arResult["DISK_INFO"]["SPACE"]))
                        ) {
                            echo ", " . Loc::getMessage("INTRANET_USER_PROFILE_DISK_SPACE",
                                            array("#VALUE#" => $arResult["DISK_INFO"]["SPACE"]));
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?
        }

        if (
                !empty($arResult["Gratitudes"])
                && !in_array($arResult["User"]["STATUS"], ['email', 'extranet'])
                && !$arResult["isExtranetSite"]
        ) {
            ?>
            <!--благодарности-->
            <div class="intranet-user-profile-column-block">
            <div class="intranet-user-profile-column-block-title">
                <span class="intranet-user-profile-column-block-title-text"><?= Loc::getMessage('INTRANET_USER_PROFILE_BLOG_GRAT_TITLE') ?></span><?
                if (
                        !empty($arResult["Gratitudes"]['URL_ADD'])
                        && $USER->getId() != $arResult["User"]["ID"]
                ) {
                    ?>
                    <div  onclick="BX.SidePanel.Instance.open('<?= $arResult['Gratitudes']['URL_ADD'] ?>', {
                            cacheable: false,
                            data: {
                            entityType: 'gratPost',
                            entityId: '<?= intval($arResult["User"]["ID"]) ?>'
                            },
                            width: 1000
                            }); return event.preventDefault();" class="intranet-user-profile-column-block-title-like"
                          data-role="intranet-user-profile-column-block-title-like"><?= Loc::getMessage('INTRANET_USER_PROFILE_BLOG_GRAT_ADD') ?></div><?
                }
                ?></div>

            <div id="intranet-user-profile-thanks" class="intranet-user-profile-thanks"
                 data-bx-grat-url="<?= htmlspecialcharsbx($arResult['Gratitudes']['URL_LIST']) ?>"><?
                foreach ($arResult['Gratitudes']['BADGES'] as $badge) {
                    ?>
                <div
                        class="intranet-user-profile-thanks-item intranet-user-profile-thanks-item-<?= htmlspecialcharsbx($badge['CODE']) ?>"
                        title="<?= htmlspecialcharsbx($badge['NAME']) ?>"
                        data-bx-grat-code="<?= htmlspecialcharsbx($badge['CODE']) ?>"
                        data-bx-grat-enum="<?= intval($badge['ID']) ?>"></div><?
                }
                ?></div>

            <div class="intranet-user-profile-thanks-users">
            <div class="intranet-user-profile-thanks-users-wrapper"
                 id="intranet-user-profile-thanks-users-wrapper"></div>
            <div class="intranet-user-profile-thanks-users-loader" id="intranet-user-profile-thanks-users-loader"></div>
            <div class="intranet-user-profile-load-users-link" style="display: none;"
                 id="intranet-user-profile-load-users-link"><?= Loc::getMessage('INTRANET_USER_PROFILE_MORE',
                        array('#NUM#' => $arParams['GRAT_POST_LIST_PAGE_SIZE'])) ?></div>
            </div><?

            if (
                    !empty($arResult['Gratitudes']['URL_ADD'])
                    && $USER->getId() != $arResult['User']['ID']
            ) {
                ?>
                <div class="intranet-user-profile-thanks-info">
                <a href="javascript:void(0);"
                   onclick="BX.SidePanel.Instance.open('<?= $arResult['Gratitudes']['URL_ADD'] ?>', {
                           cacheable: false,
                           data: {
                           entityType: 'gratPost',
                           entityId: '<?= intval($arResult["User"]["ID"]) ?>'
                           },
                           width: 1000
                           }); return event.preventDefault();"
                   class="ui-btn ui-btn-xs ui-btn-light-border ui-btn-round"><?= Loc::getMessage('INTRANET_USER_PROFILE_BLOG_GRAT_ADD') ?></a>
                </div><?
            }
            ?></div><?
        }

        // Мои интересы
        if (
                $arResult["CurrentUser"]["STATUS"] != 'extranet'
                && !empty($arResult["Tags"])
                && (
                        !empty($arResult["Tags"]["COUNT"])
                        || $arResult["Permissions"]['edit']
                        || $USER->getId() == $arResult["User"]["ID"]
                )
                && !in_array($arResult["User"]["STATUS"], ['extranet', 'email', 'invited'])
        ) {
            ?>
        <div id="intranet-user-profile-tags-container"
             class="intranet-user-profile-container<?= (empty($arResult["Tags"]["COUNT"]) ? ' intranet-user-profile-container-empty' : '') ?>">
            <div class="intranet-user-profile-container-header">
                <div class="intranet-user-profile-container-title"><?= Loc::getMessage('INTRANET_USER_PROFILE_TAGS_TITLE') ?></div><?

                if (
                        $arResult["Permissions"]['edit']
                        || $USER->getId() == $arResult["User"]["ID"]
                ) {
                    ?>
                    <div id="intranet-user-profile-add-tags"
                         class="intranet-user-profile-container-edit"><?= Loc::getMessage('INTRANET_USER_PROFILE_TAGS_MODIFY') ?></div><?
                }

                ?></div>
            <div class="intranet-user-profile-container-body intranet-user-profile-tags-wrapper"><?

                if (
                        $arResult["Permissions"]['edit']
                        || $USER->getId() == $arResult["User"]["ID"]
                ) {
                    ?>
                    <div id="intranet-user-profile-tags-input" class="intranet-user-profile-tags-area"></div><?
                }

                ?>
                <div class="intranet-user-profile-tags" id="intranet-user-profile-tags"></div>

                <div id="intranet-user-profile-interests-stub"
                     class="intranet-user-profile-about intranet-user-profile-empty-stub intranet-user-profile-about-interests">
                    <div class="intranet-user-profile-post-edit-stub-default"><?= Loc::getMessage('INTRANET_USER_PROFILE_INTERESTS_STUB_TEXT') ?></div>
                    <a id="intranet-user-profile-interests-stub-button"
                       class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round ui-btn-caps"><?= Loc::getMessage('INTRANET_USER_PROFILE_INTERESTS_STUB_BUTTON_2') ?></a>
                </div>

                <div class="intranet-user-profile-thanks-users-loader" id="intranet-user-profile-tags-loader"></div>
            </div>
            </div><?
        }
        ?>
        <style>
            :root {
                --primary-red: #ea213a;
                --primary-red-hover: #d1182f;
                --primary-red-active: #b51225;
                --primary-red-light: #fef0f2;
                --text-main: #181c24;
                --text-secondary: #717684;
                --border-color: #f0f2f5;
                --card-bg: #ffffff;
                --bg-body: #f4f5f8;
                --radius-lg: 24px;
                --radius-md: 16px;
                --radius-sm: 10px;
                --shadow-card: 0 16px 40px rgba(0, 0, 0, 0.06), 0 4px 12px rgba(0, 0, 0, 0.03);
            }

            .container {
                display: flex;
                gap: 32px;
                max-width: 700px;
                width: 100%;
                align-items: flex-start;
                justify-content: center;
                margin: 0 auto;
            }

            .card {
                background: var(--card-bg);
                border-radius: var(--radius-lg);
                padding: 32px;
                box-shadow: var(--shadow-card);
            }

            /* Главная карточка */
            .main-card {
                flex: 1;
                max-width: 90%;
                position: relative;
            }

            .header-banner {
                position: relative;
                margin-bottom: 24px;
            }

            .header-banner h1 {
                font-size: 26px;
                font-weight: 700;
                letter-spacing: -0.02em;
                margin-bottom: 6px;
            }

            .header-banner p {
                color: var(--text-secondary);
                font-size: 14px;
            }

            .star-illustration {
                position: absolute;
                right: 0;
                top: -12px;
                width: 72px;
                height: 72px;
                filter: drop-shadow(0 8px 16px rgba(234, 33, 58, 0.3));
            }

            .section-box {
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                /*padding: 20px;*/
                margin-bottom: 24px;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .section-title {
                font-size: 15px;
                font-weight: 700;
            }

            .remain-badge {
                font-size: 13px;
                color: var(--text-secondary);
            }

            .remain-badge b {
                color: var(--primary-red);
                font-weight: 700;
            }

            .stars-action-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 18px;
            }

            .stars-list {
                display: flex;
                gap: 8px;
            }

            .star-icon {
                width: 32px;
                height: 32px;
            }

            .btn-send {
                background: var(--primary-red);
                color: #fff;
                border: none;
                border-radius: var(--radius-sm);
                padding: 10px 18px;
                font-size: 14px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                transition: background 0.15s ease, transform 0.1s ease;
            }

            .btn-send:hover {
                background: var(--primary-red-hover);
            }

            .btn-send:active {
                transform: scale(0.98);
                background: var(--primary-red-active);
            }

            .rules-bar {
                background: #fafbfd;
                border-radius: var(--radius-sm);
                /*padding: 12px 14px;*/
                display: flex;
                align-items: center;
                font-size: 12px;
                color: #4b5162;
                gap: 16px;
            }

            .rule-item {
                display: flex;
                align-items: center;
                gap: 8px;
                flex: 1;
                line-height: 1.35;
            }

            .rule-divider {
                width: 1px;
                height: 24px;
                background: #e5e8ee;
            }

            .received-section {
                margin-bottom: 24px;
            }

            .received-counter {
                color: var(--primary-red);
                font-size: 14px;
                font-weight: 700;
            }

            .stars-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 12px 16px;
                margin-top: 12px;
            }

            .star-small {
                width: 22px;
                height: 22px;
            }

            .comments-header {
                font-size: 16px;
                font-weight: 700;
                margin-bottom: 16px;
            }

            .comment-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 14px 0;
                border-bottom: 1px solid #f2f3f7;
            }

            .comment-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .avatar {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                background: #e9ecf2;
                flex-shrink: 0;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: 600;
                color: #656d80;
            }

            .comment-content {
                flex: 1;
            }

            .comment-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 4px;
            }

            .user-name-row {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .user-name {
                font-size: 14px;
                font-weight: 700;
            }

            .comment-meta {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .comment-date {
                font-size: 12px;
                color: var(--text-secondary);
            }

            .dots-menu {
                cursor: pointer;
                color: #9aa0ae;
                font-size: 16px;
                line-height: 1;
                padding: 0 4px;
            }

            .comment-text {
                font-size: 13px;
                color: #434958;
                line-height: 1.4;
            }

            /* Карточка модалки / подарка */
            .modal-card {
                width: 440px;
                border-radius: var(--radius-lg);
                padding: 28px;
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-title {
                font-size: 18px;
                font-weight: 700;
            }

            .close-btn {
                background: none;
                border: none;
                cursor: pointer;
                color: #8c93a3;
                padding: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                transition: background 0.15s;
            }

            .close-btn:hover {
                background: #f0f2f5;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 8px;
                color: #181c24;
            }

            .req {
                color: var(--primary-red);
            }

            .search-input-wrapper {
                position: relative;
            }

            .search-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #99a0af;
                pointer-events: none;
            }

            .input-field {
                width: 100%;
                padding: 12px 14px 12px 40px;
                border: 1px solid #dfe2e8;
                border-radius: var(--radius-sm);
                font-size: 13px;
                outline: none;
                transition: border-color 0.15s, box-shadow 0.15s;
            }

            .input-field:focus {
                border-color: #b0b8c6;
            }

            .input-field::placeholder {
                color: #9ea4b2;
            }

            .user-dropdown {
                margin-top: 8px;
                background: #ffffff;
                border: 1px solid #edf0f5;
                border-radius: var(--radius-sm);
                overflow: hidden;
            }

            .user-option {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 14px;
                cursor: pointer;
                transition: background 0.15s;
            }

            .user-option.selected {
                background: var(--primary-red-light);
            }

            .user-option-avatar {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: #e4e7ee;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 13px;
                color: #555e70;
                flex-shrink: 0;
            }

            .user-option-info {
                flex: 1;
            }

            .user-option-name {
                font-size: 13px;
                font-weight: 600;
                color: var(--text-main);
            }

            .user-option-dept {
                font-size: 11px;
                color: var(--text-secondary);
                margin-top: 2px;
            }

            .textarea-wrapper {
                position: relative;
            }

            .textarea-field {
                width: 100%;
                min-height: 110px;
                padding: 12px 14px;
                border: 1px solid #dfe2e8;
                border-radius: var(--radius-sm);
                font-size: 13px;
                outline: none;
                resize: vertical;
                color: var(--text-main);
                transition: border-color 0.15s;
            }

            .textarea-field:focus {
                border-color: #b0b8c6;
            }

            .textarea-field::placeholder {
                color: #9ea4b2;
            }

            .char-count {
                text-align: right;
                font-size: 11px;
                color: #9ea4b2;
                margin-top: 6px;
            }

            .alert-banner {
                background: var(--primary-red-light);
                border-radius: var(--radius-sm);
                padding: 12px 14px;
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 20px;
                color: var(--primary-red);
                font-size: 12px;
                font-weight: 500;
            }

            .modal-actions {
                display: flex;
                gap: 12px;
            }

            .btn-cancel {
                flex: 1;
                background: #f1f3f7;
                color: var(--text-main);
                border: none;
                border-radius: var(--radius-sm);
                padding: 12px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.15s;
            }

            .btn-cancel:hover {
                background: #e5e8ee;
            }

            .btn-submit {
                flex: 2;
                background: var(--primary-red);
                color: #ffffff;
                border: none;
                border-radius: var(--radius-sm);
                padding: 12px;
                font-size: 13px;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                cursor: pointer;
                transition: background 0.15s;
            }

            .btn-submit:hover {
                background: var(--primary-red-hover);
            }

            @media (max-width: 900px) {
                .container {
                    flex-direction: column;
                    align-items: center;
                }

                .main-card, .modal-card {
                    width: 100%;
                    max-width: 580px;
                }
            }

            /* Затемняющий фон */
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(2px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                padding: 20px;
                box-sizing: border-box;

                /* Плавное появление */
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.25s ease, visibility 0.25s ease;
            }

            /* Активное состояние */
            .modal-overlay.is-open {
                opacity: 1;
                visibility: visible;
            }

            /* Само модальное окно */
            .modal-overlay .modal-card {
                background: #ffffff;
                border-radius: 16px;
                width: 100%;
                max-width: 480px;
                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
                transform: translateY(20px) scale(0.96);
                transition: transform 0.25s ease;
            }

            .modal-overlay.is-open .modal-card {
                transform: translateY(0) scale(1);
            }


            .received-section {
                padding: 24px;
                background: #fff;
                border-radius: 16px;
            }

            .received-section .section-title {
                margin: 0 0 16px;
                font-size: 20px;
                font-weight: 600;
                color: #202124;
            }

            .received-stars-row {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .received-section .stars-grid {
                display: flex;
                flex: 1;
                flex-wrap: wrap;
                align-items: center;
                gap: 8px;
                min-width: 0;
            }

            .received-section .star-small {
                display: block;
                flex: 0 0 32px;
                width: 32px;
                height: 32px;
                fill: #e53935;
            }

            .received-section .received-counter {
                flex-shrink: 0;
                font-size: 16px;
                font-weight: 500;
                color: #60646c;
                white-space: nowrap;
            }

        </style>
        <!-- SVG спрайт иконок -->
        <svg style="position: absolute; width: 0; height: 0; overflow: hidden;" aria-hidden="true">
            <!-- Красная 3D-звезда -->
            <g id="icon-star-red">
                <defs>
                    <radialGradient id="starGrad" cx="35%" cy="30%" r="70%">
                        <stop offset="0%" stop-color="#ff7b88"/>
                        <stop offset="45%" stop-color="#ea213a"/>
                        <stop offset="100%" stop-color="#b50c22"/>
                    </radialGradient>
                    <filter id="starShadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#ea213a" flood-opacity="0.25"/>
                    </filter>
                </defs>
                <path filter="url(#starShadow)" fill="url(#starGrad)"
                      d="M16 2.5l4.17 8.45 9.33 1.36-6.75 6.58 1.59 9.29L16 23.79l-8.34 4.39 1.59-9.29-6.75-6.58 9.33-1.36z"/>
            </g>

            <!-- Серая звезда -->
            <g id="icon-star-gray">
                <path fill="#e6e8ee"
                      d="M16 2.5l4.17 8.45 9.33 1.36-6.75 6.58 1.59 9.29L16 23.79l-8.34 4.39 1.59-9.29-6.75-6.58 9.33-1.36z"/>
            </g>

            <!-- Иконка бумажного самолётика -->
            <g id="icon-send">
                <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </g>
        </svg>

        <div class="container">

            <!-- Левая колонка: Основной интерфейс -->
            <main class="card main-card">
                <header class="header-banner">
                    <h1>Марафон звёзд</h1>
                    <p>Подарите 5 звёзд коллегам из других дирекций</p>
                </header>

                <!-- Блок: Мои звёзды для отправки -->

                <?php
                global $USER;

                $starsCount = 0;
                $maxStars = 5; // Всего звезд (как на картинке)

                if ($USER->IsAuthorized()) {
                    $userId = (int)$USER->GetID();

                    // Получаем значение поля UF_MARATHON
                    $userData = \Bitrix\Main\UserTable::getRow([
                            'select' => ['ID', 'UF_MARATHON'],
                            'filter' => ['=ID' => $userId]
                    ]);

                    if (!empty($userData['UF_MARATHON'])) {
                        $starsCount = (int)$userData['UF_MARATHON'];
                    }
                    $user = UserTable::getList([
                            'select' => ['ID', 'UF_EXCLUDE'],
                            'filter' => ['=ID' => $userId]
                    ])->fetch();

                    if ($user && !empty($user['UF_EXCLUDE'])) {
                        // Значение также будет массивом
                        $user = UserTable::getList([
                                'select' => ['ID', 'UF_EXCLUDE'],
                                'filter' => ['=ID' => $userId]
                        ])->fetch();

                        if ($user && !empty($user['UF_EXCLUDE'])) {
                            // Значение также будет массивом
                            $arUsersExclude = $user['UF_EXCLUDE'];
                            // print_r($user['UF_EXCLUDE']);
                        }

                    }
                }

                // Ограничиваем диапазон от 0 до $maxStars
                $starsCount = max(0, min($maxStars, $starsCount));
                ?>

                <!-- Блок: Мои звёзды для отправки -->
                <?php
                global $USER;

                $starsCount = 0;
                $maxStars = 5; // Всего звезд

                if ($USER->IsAuthorized()) {
                    $userId = (int)$USER->GetID();

                    // Получаем значение поля UF_MARATHON
                    $userData = \Bitrix\Main\UserTable::getRow([
                            'select' => ['ID', 'UF_MARATHON'],
                            'filter' => ['=ID' => $userId]
                    ]);

                    if (!empty($userData['UF_MARATHON'])) {
                        $starsCount = (int)$userData['UF_MARATHON'];
                    }
                }

                // Ограничиваем диапазон от 0 до $maxStars
                $starsCount = max(0, min($maxStars, $starsCount));
                ?>
                <!-- Блок: Мои звёзды для отправки -->
                <section class="section-box">
                    <div class="user-stars-block">
                        <div class="user-stars-title">Мои звёзды для отправки</div>
                        <span class="remain-badge">Осталось: <b id="stars-left-text"><?= $starsCount ?> из 5</b></span>

                    </div>

                    <style>
                        .user-stars-block {
                            display: flex;
                            justify-content: space-between;
                            background: #ffffff;
                            border-radius: 8px;
                        }

                        .user-stars-title {
                            font-size: 14px;
                            font-weight: 700;
                            color: #333333;
                            margin-bottom: 12px;
                        }

                        .user-stars-list {
                            display: flex;
                            gap: 8px;
                            align-items: center;
                        }

                        .star-icon {
                            display: block;
                            width: 26px;
                            height: 26px;
                        }

                        .btn-send.disabled {
                            background-color: #6b6868;
                        }
                    </style>

                    <div class="stars-action-row">
                        <div class="user-stars-list">
                            <?php for ($i = 1; $i <= $maxStars; $i++): ?>
                                <svg class="star-icon" viewBox="0 0 32 32">
                                    <use href="<?= ($i <= $starsCount) ? '#icon-star-red' : '#icon-star-gray' ?>"></use>
                                </svg>
                            <?php endfor; ?>
                        </div>

                        <? $blButton = $starsCount == 0 ? 'disabled' : ''; ?>
                        <button <?= $blButton; ?> class="btn-send <?= $blButton; ?>" id="open-gift-btn">
                            <svg width="15" height="15" viewBox="0 0 24 24">
                                <use href="#icon-send"></use>
                            </svg>
                            Подарить звезду
                        </button>
                    </div>

                    <div class="rules-bar">
                        <div class="rule-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#717684"
                                 stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <line x1="9" y1="9" x2="15" y2="9"></line>
                                <line x1="9" y1="13" x2="13" y2="13"></line>
                            </svg>
                            <span>Комментарий обязателен</span>
                        </div>
                        <div class="rule-divider"></div>
                        <div class="rule-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#717684"
                                 stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Нельзя подарить звезду сотруднику своей дирекции</span>
                        </div>
                    </div>
                </section>

                <!-- Блок: Получено -->

                <?php


                global $USER;

                /*
                 * Настройки.
                 */
                $iblockCode = 'stars_marathon';
                $recipientPropertyCode = 'whome_send_user';

                /*
                 * Если отправитель хранится в отдельном свойстве типа
                 * «Привязка к пользователю», укажите его код.
                 *
                 * Пустая строка — использовать CREATED_BY.
                 */
                $senderPropertyCode = '';

                /*
                 * Если комментарий хранится в отдельном свойстве,
                 * укажите его код.
                 *
                 * Пустая строка — использовать PREVIEW_TEXT / DETAIL_TEXT.
                 * Ожидается одиночное строковое свойство или свойство HTML/текст.
                 */
                $commentPropertyCode = '';

                if (!$USER->IsAuthorized()) {
                    echo '<p>Чтобы увидеть полученные звёзды, авторизуйтесь.</p>';
                    return;
                }

                if (!Loader::includeModule('iblock')) {
                    echo '<p>Не удалось подключить модуль инфоблоков.</p>';
                    return;
                }

                $currentUserId = (int)$USER->GetID();


                /*
                 * Находим инфоблок по символьному коду.
                 */
                $iblockResult = CIBlock::GetList(
                        [],
                        [
                                'CODE' => $iblockCode,
                                'ACTIVE' => 'Y',
                        ]
                );

                $iblock = $iblockResult->Fetch();

                if (!$iblock) {
                    echo '<p>Инфоблок stars_marathon не найден.</p>';
                    return;
                }

                /*
                 * Получаем элементы, отправленные текущему пользователю.
                 *
                 * GetNextElement() + GetProperties() позволяет не размножать
                 * элементы в результате из-за множественных свойств.
                 */
                $elementsResult = CIBlockElement::GetList(
                        [
                                'DATE_CREATE' => 'DESC',
                                'ID' => 'DESC',
                        ],
                        [
                                'IBLOCK_ID' => (int)$iblock['ID'],
                                'ACTIVE' => 'Y',
                                '=PROPERTY_' . $recipientPropertyCode => $currentUserId,
                                'CHECK_PERMISSIONS' => 'Y',
                                'MIN_PERMISSION' => 'R',
                        ],
                        false,
                        false,
                        [
                                'ID',
                                'IBLOCK_ID',
                                'CREATED_BY',
                                'DATE_CREATE',
                                'PREVIEW_TEXT',
                                'DETAIL_TEXT',
                        ]
                );

                $comments = [];
                $authorIds = [];

                while ($elementObject = $elementsResult->GetNextElement()) {
                    // false — получаем исходные значения, экранируем при выводе.
                    $element = $elementObject->GetFields(false, false);

                    $properties = [];

                    if ($senderPropertyCode !== '' || $commentPropertyCode !== '') {
                        $properties = $elementObject->GetProperties();
                    }

                    $authorId = (int)$element['CREATED_BY'];

                    if ($senderPropertyCode !== '') {
                        $senderCode = strtoupper($senderPropertyCode);
                        $authorId = (int)($properties[$senderCode]['VALUE'] ?? 0);
                    }

                    if ($commentPropertyCode !== '') {
                        $commentCode = strtoupper($commentPropertyCode);
                        $commentValue = $properties[$commentCode]['VALUE'] ?? '';

                        // Поддержка свойства типа HTML/текст.
                        $text = is_array($commentValue)
                                ? (string)($commentValue['TEXT'] ?? '')
                                : (string)$commentValue;
                    } else {
                        $previewText = trim((string)$element['PREVIEW_TEXT']);

                        $text = $previewText !== ''
                                ? $previewText
                                : (string)$element['DETAIL_TEXT'];
                    }

                    $comments[] = [
                            'ID' => (int)$element['ID'],
                            'AUTHOR_ID' => $authorId,
                            'TEXT' => $text,
                            'DATE_CREATE' => (string)$element['DATE_CREATE'],
                    ];

                    if ($authorId > 0) {
                        $authorIds[$authorId] = $authorId;
                    }
                }

                /*
                 * Получаем всех авторов одним запросом.
                 */
                $authors = [];

                if ($authorIds) {
                    $by = 'id';
                    $order = 'asc';

                    $usersResult = CUser::GetList(
                            $by,
                            $order,
                            [
                                    'ID' => implode('|', array_values($authorIds)),
                            ],
                            [
                                    'FIELDS' => [
                                            'ID',
                                            'NAME',
                                            'LAST_NAME',
                                            'LOGIN',
                                    ],
                            ]
                    );

                    while ($author = $usersResult->Fetch()) {
                        $authors[(int)$author['ID']] = $author;
                    }
                }

                $starsCount = count($comments);

                /*
                 * Склонение: 1 звезда, 2 звезды, 5 звёзд, 21 звезда.
                 */
                $pluralizeStars = static function (int $number): string {
                    $lastTwo = $number % 100;
                    $lastOne = $number % 10;

                    if ($lastTwo >= 11 && $lastTwo <= 14) {
                        return 'звёзд';
                    }

                    if ($lastOne === 1) {
                        return 'звезда';
                    }

                    if ($lastOne >= 2 && $lastOne <= 4) {
                        return 'звезды';
                    }

                    return 'звёзд';
                };

                $escape = static function ($value): string {
                    return htmlspecialchars(
                            (string)$value,
                            ENT_QUOTES | ENT_SUBSTITUTE,
                            'UTF-8'
                    );
                };

                $getInitials = static function (array $author): string {
                    $initials = '';

                    foreach (['NAME', 'LAST_NAME'] as $field) {
                        $part = trim((string)($author[$field] ?? ''));

                        if ($part !== '') {
                            $initials .= mb_substr($part, 0, 1, 'UTF-8');
                        }
                    }

                    if ($initials === '') {
                        $initials = mb_substr(
                                (string)($author['LOGIN'] ?? '?'),
                                0,
                                1,
                                'UTF-8'
                        );
                    }

                    return mb_strtoupper($initials, 'UTF-8');
                };

                $formatCommentDate = static function (string $value): string {
                    $timestamp = MakeTimeStamp($value);

                    if (!$timestamp) {
                        return '';
                    }

                    $months = [
                            1 => 'января',
                            2 => 'февраля',
                            3 => 'марта',
                            4 => 'апреля',
                            5 => 'мая',
                            6 => 'июня',
                            7 => 'июля',
                            8 => 'августа',
                            9 => 'сентября',
                            10 => 'октября',
                            11 => 'ноября',
                            12 => 'декабря',
                    ];

                    return date('d', $timestamp)
                            . ' ' . $months[(int)date('n', $timestamp)]
                            . ' ' . date('Y', $timestamp);
                };

                /*
                 * Одинаковая SVG-звезда для счётчика и комментариев.
                 * Внешние изображения и SVG-спрайты не нужны.
                 */
                $starSvg = '
    <svg
        class="sm-star"
        viewBox="0 0 32 32"
        aria-hidden="true"
        focusable="false"
    >
        <path d="
            M16 2
            L20.3 11.1
            L30.3 12.4
            L23 19.4
            L24.8 29.3
            L16 24.5
            L7.2 29.3
            L9 19.4
            L1.7 12.4
            L11.7 11.1
            Z
        " />
    </svg>
';

                $starsLabel = $starsCount . ' ' . $pluralizeStars($starsCount);
                ?>

                <style>
                    .stars-marathon,
                    .stars-marathon * {
                        box-sizing: border-box;
                    }

                    .stars-marathon {
                        width: 100%;
                        /*
                                        padding: 36px 32px 20px;*/
                        background: #fff;
                        border-radius: 0 0 24px 24px;
                        color: #333;
                        font-family: Arial, sans-serif;
                    }

                    .stars-marathon .sm-received {
                        /*padding: 0 24px;*/
                    }

                    .stars-marathon .sm-title {
                        margin: 0 0 26px;
                        font-size: 20px;
                        font-weight: 600;
                        line-height: 1.3;
                    }

                    .stars-marathon .sm-stars-row {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 24px;
                    }

                    .stars-marathon .sm-stars {
                        display: flex;
                        flex: 1 1 auto;
                        flex-wrap: wrap;
                        align-items: center;
                        gap: 10px;
                        min-width: 0;
                    }

                    .stars-marathon .sm-star {
                        display: block;
                        flex-shrink: 0;
                        width: 30px;
                        height: 30px;
                        fill: #dc3541;
                    }

                    .stars-marathon .sm-stars-count {
                        flex: 0 0 auto;
                        color: #666;
                        font-size: 15px;
                        line-height: 1.4;
                        white-space: nowrap;
                    }

                    .stars-marathon .sm-comments {
                        margin-top: 48px;
                    }

                    .stars-marathon .sm-comments-title {
                        margin: 0 0 14px;
                        font-size: 16px;
                        font-weight: 600;
                        line-height: 1.4;
                    }

                    .stars-marathon .sm-comment {
                        display: flex;
                        align-items: flex-start;
                        gap: 14px;
                        padding: 16px 0 26px;
                    }

                    .stars-marathon .sm-comment + .sm-comment {
                        border-top: 1px solid #f0f0f0;
                    }

                    .stars-marathon .sm-avatar {
                        display: flex;
                        flex: 0 0 42px;
                        align-items: center;
                        justify-content: center;
                        width: 42px;
                        height: 42px;
                        border-radius: 50%;
                        background: #eeeff4;
                        color: #777c87;
                        font-size: 14px;
                        font-weight: 600;
                    }

                    .stars-marathon .sm-comment-content {
                        flex: 1 1 auto;
                        min-width: 0;
                    }

                    .stars-marathon .sm-comment-top {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 16px;
                        min-height: 20px;
                    }

                    .stars-marathon .sm-user-row {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        min-width: 0;
                    }

                    .stars-marathon .sm-user-name {
                        color: #707070;
                        font-size: 14px;
                        font-weight: 600;
                        overflow-wrap: anywhere;
                    }

                    .stars-marathon .sm-user-arrow {
                        flex-shrink: 0;
                        width: 0;
                        height: 0;
                        border-top: 4px solid #aaa;
                        border-right: 3px solid transparent;
                        border-left: 3px solid transparent;
                    }

                    .stars-marathon .sm-user-row .sm-star {
                        width: 14px;
                        height: 14px;
                    }

                    .stars-marathon .sm-comment-meta {
                        display: flex;
                        flex-shrink: 0;
                        align-items: center;
                        gap: 18px;
                        color: #888;
                    }

                    .stars-marathon .sm-comment-date {
                        font-size: 12px;
                        white-space: nowrap;
                    }

                    .stars-marathon .sm-dots {
                        color: #a2a2a2;
                        font-size: 16px;
                        letter-spacing: 1px;
                        line-height: 1;
                    }

                    .stars-marathon .sm-comment-text {
                        margin: 12px 0 0;
                        color: #555;
                        font-size: 13px;
                        line-height: 1.6;
                        overflow-wrap: anywhere;
                    }

                    .stars-marathon .sm-empty {
                        margin: 0;
                        padding: 12px 0 20px;
                        color: #888;
                        font-size: 14px;
                        line-height: 1.5;
                    }

                    @media (max-width: 600px) {
                        .stars-marathon {
                            padding: 24px 18px 12px;
                            border-radius: 16px;
                        }

                        .stars-marathon .sm-received {
                            padding: 0;
                        }

                        .stars-marathon .sm-stars-row {
                            gap: 14px;
                        }

                        .stars-marathon .sm-stars {
                            gap: 6px;
                        }

                        .stars-marathon .sm-stars > .sm-star {
                            width: 25px;
                            height: 25px;
                        }

                        .stars-marathon .sm-comments {
                            margin-top: 32px;
                        }

                        .stars-marathon .sm-comment {
                            gap: 10px;
                            padding-bottom: 20px;
                        }

                        .stars-marathon .sm-comment-top {
                            align-items: flex-start;
                            flex-direction: column;
                            gap: 7px;
                        }

                        .stars-marathon .sm-comment-meta {
                            justify-content: space-between;
                            width: 100%;
                        }

                        .stars-marathon .sm-comment-text {
                            margin-top: 9px;
                        }
                    }
                </style>

                <div class="stars-marathon">
                    <section class="sm-received" aria-label="Полученные звёзды">
                        <h2 class="sm-title">Получено</h2>

                        <div class="sm-stars-row">
                            <div class="sm-stars" aria-hidden="true">
                                <?php for ($i = 0; $i < $starsCount; $i++): ?>
                                    <?= $starSvg ?>
                                <?php endfor; ?>
                            </div>

                            <span class="sm-stars-count">
                <?= $escape($starsLabel) ?>
            </span>
                        </div>
                    </section>

                    <section class="sm-comments" aria-label="Комментарии">
                        <h2 class="sm-comments-title">Комментарии</h2>

                        <div class="sm-comments-list">
                            <?php if (!$comments): ?>
                                <p class="sm-empty">
                                    Пока никто не отправил вам звёзды.
                                </p>
                            <?php else: ?>
                                <?php foreach ($comments as $comment): ?>
                                    <?php
                                    $author = $authors[$comment['AUTHOR_ID']] ?? [];

                                    $authorName = trim(
                                            (string)($author['NAME'] ?? '')
                                            . ' '
                                            . (string)($author['LAST_NAME'] ?? '')
                                    );

                                    if ($authorName === '') {
                                        $authorName = (string)(
                                                $author['LOGIN'] ?? 'Пользователь'
                                        );
                                    }

                                    $initials = $getInitials($author);
                                    $dateLabel = $formatCommentDate(
                                            $comment['DATE_CREATE']
                                    );
                                    ?>

                                    <article
                                            class="sm-comment"
                                            data-element-id="<?= (int)$comment['ID'] ?>"
                                    >
                                        <div class="sm-avatar" aria-hidden="true">
                                            <?= $escape($initials) ?>
                                        </div>

                                        <div class="sm-comment-content">
                                            <div class="sm-comment-top">
                                                <div class="sm-user-row">
                                    <span class="sm-user-name">
                                        <?= $escape($authorName) ?>
                                    </span>

                                                    <span
                                                            class="sm-user-arrow"
                                                            aria-hidden="true"
                                                    ></span>

                                                    <?= $starSvg ?>
                                                </div>

                                                <div class="sm-comment-meta">
                                    <span class="sm-comment-date">
                                        <?= $escape($dateLabel) ?>
                                    </span>

                                                    <span
                                                            class="sm-dots"
                                                            aria-hidden="true"
                                                    >•••</span>
                                                </div>
                                            </div>

                                            <?php if (trim($comment['TEXT']) !== ''): ?>
                                                <p class="sm-comment-text">
                                                    <?= nl2br($escape($comment['TEXT'])) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </main>

        </div>
        <!-- Оверлей модального окна (изначально скрыт) -->
        <div class="modal-overlay" id="gift-modal" aria-hidden="true">
            <aside class="card modal-card" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="modal-header">
                    <h2 class="modal-title" id="modal-title">Подарить звезду</h2>
                    <button class="close-btn" id="btn-close-modal" aria-label="Закрыть">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>

                </div>
                <div style="color: #FF1E2D; font-weight: bold; font-size: 14px; margin-bottom: 10px;" id="error-message"
                     class="selected-employees_information"></div>

                <div class="form-group">
                    <label class="form-label" for="user-search">Кому</label>

                    <style>
                        .selected-employees__row {
                            align-items: flex-start;
                        }

                        .selected-employees__info {
                            flex: 1;
                        }

                        .selected-employees__id {
                            margin-top: 3px;
                            font-size: 12px;
                            color: #888;
                        }

                        .selected-employees__comment {
                            margin-top: 12px;
                        }

                        .selected-employees__comment-label {
                            display: block;
                            margin-bottom: 5px;
                            font-size: 13px;
                            font-weight: 600;
                            color: #333;
                        }

                        .selected-employees__textarea {
                            display: block;
                            box-sizing: border-box;
                            width: 100%;
                            min-height: 80px;
                            padding: 8px 10px;
                            border: 1px solid #c9cfd6;
                            border-radius: 4px;
                            font: inherit;
                            resize: vertical;
                        }

                        .selected-employees__textarea:focus {
                            border-color: #2fc6f6;
                            outline: 2px solid rgba(47, 198, 246, 0.2);
                        }

                        .selected-employees {
                            display: flex;
                            flex-direction: column;
                            gap: 8px;
                            margin-top: 12px;
                        }

                        .selected-employees__row {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 16px;
                            padding: 10px 12px;
                            border: 1px solid #dfe3e6;
                            border-radius: 6px;
                            background: #fff;
                        }

                        .selected-employees__info {
                            min-width: 0;
                            overflow-wrap: anywhere;
                        }

                        .selected-employees__name {
                            font-weight: 600;
                            color: #333;
                        }

                        .selected-employees__department {
                            margin-top: 3px;
                            font-size: 13px;
                            color: #777;
                        }

                        .selected-employees__remove {
                            flex-shrink: 0;
                            padding: 5px 8px;
                            border: 0;
                            border-radius: 4px;
                            background: transparent;
                            color: #c0392b;
                            cursor: pointer;
                        }

                        .selected-employees__remove:hover {
                            background: #fff0ef;
                        }

                        .selected-employees__remove:focus-visible {
                            outline: 2px solid #c0392b;
                            outline-offset: 2px;
                        }

                        .selected-employees__empty {
                            font-size: 13px;
                            color: #888;
                        }
                    </style>
                    <?php
                    global $USER;

                    if ($USER->IsAuthorized()) {
                        $userId = $USER->GetID();

                        // Получаем пользователя
                        $rsUser = CUser::GetByID($userId);
                        $arUser = $rsUser->Fetch();

                        // UF_DEPARTMENT — массив ID подразделений

                        $arDepartments = $arUser['UF_DEPARTMENT'];

                        $blGenDir = $arDepartments == 1 ? 1 : 0;

                        if (!empty($arDepartments) && is_array($arDepartments)) {
                            foreach ($arDepartments as $departmentId) {
                                // ID подразделения
                                $departmentId = (int)$departmentId;

                                // Получаем название подразделения
                                $departmentName = '';

                                // В Битрикс24 оргструктура обычно хранится в инфоблоке.
                                // Если у вас известен ID инфоблока подразделений, укажите его:
                                $iblockId = 3; // замените на реальный ID инфоблока подразделений


                                Loader::includeModule('iblock');

                                $iblockId = IblockTable::getList([
                                        'filter' => ['=CODE' => 'departments'],
                                        'select' => ['ID']
                                ])->fetch()['ID'] ?? null;


                                if (\Bitrix\Main\Loader::includeModule('iblock')) {
                                    $rsSection = \CIBlockSection::GetList(
                                            [],
                                            ['ID' => $departmentId, 'IBLOCK_ID' => $iblockId],
                                            false,
                                            ['ID', 'NAME']
                                    );

                                    if ($arSection = $rsSection->Fetch()) {
                                        //echo $departmentName = $arSection['NAME'];
                                        $departmentID = $arSection['ID'];
                                    }
                                }

                            }
                        }
                    }
                    if (!$USER->IsAuthorized()) {
                        return;
                    }

                    if (
                            !Loader::includeModule('intranet')
                            || !Loader::includeModule('iblock')
                    ) {
                        throw new RuntimeException('Необходимы модули intranet и iblock');
                    }

                    Extension::load('ui.entity-selector');

                    //$userId = (int)$USER->GetID();

                    $arUser = CUser::GetByID($userId)->Fetch();
                    if (!$arUser) {
                        return;
                    }
                    $arDepartments = (array)($arUser['UF_DEPARTMENT'] ?? []);

                    $arDepartments = array_filter(array_map('intval', $arDepartments));
                    if (!$arDepartments) {
                        echo 'Пользователь не привязан к подразделениям';
                        return;
                    }

                    $iblockId = (int)(IblockTable::getList([
                            'filter' => ['=CODE' => 'departments'],
                            'select' => ['ID'],
                    ])->fetch()['ID'] ?? 0);

                    if (!$iblockId) {
                        echo 'Инфоблок подразделений не найден';
                        return;
                    }

                    function getSectionChain(int $sectionId, int $iblockId): array
                    {
                        $res = CIBlockSection::GetNavChain($iblockId, $sectionId, ['ID', 'NAME', 'DEPTH_LEVEL']);
                        $chain = [];
                        while ($row = $res->Fetch()) {
                            $chain[] = [
                                    'ID' => (int)$row['ID'],
                                    'NAME' => (string)$row['NAME'],
                                    'DEPTH_LEVEL' => (int)$row['DEPTH_LEVEL'],
                            ];
                        }
                        return $chain;
                    }

                    function findInChain(array $chain, array $keywords): ?array
                    {
                        foreach ($chain as $node) {
                            foreach ($keywords as $kw) {
                                if (mb_stripos($node['NAME'], $kw) !== false) {
                                    return $node;
                                }
                            }
                        }
                        return null;
                    }

                    /**
                     * Получить ID всех вложенных разделов (на всех уровнях) для указанного раздела
                     * @return int[]
                     */
                    function getNestedSectionIds(int $rootSectionId, int $iblockId): array
                    {
                        $ids = [];

                        $rs = CIBlockSection::GetList(
                                ['LEFT_MARGIN' => 'ASC'],
                                [
                                        'IBLOCK_ID' => $iblockId,
                                        'SECTION_ID' => $rootSectionId,
                                        'ACTIVE' => 'Y',
                                        'GLOBAL_ACTIVE' => 'Y',
                                ],
                                false,
                                ['ID']
                        );
                        while ($row = $rs->Fetch()) {
                            $ids[] = (int)$row['ID'];
                        }

                        // Если структура может быть многоуровневой и GET_LIST с SECTION_ID
                        // возвращает только прямых потомков — обойдём рекурсивно.
                        // Проверим "глубину" через LEFT_MARGIN / RIGHT_MARGIN, чтобы взять всех.
                        $rootRes = CIBlockSection::GetList(
                                [],
                                ['IBLOCK_ID' => $iblockId, 'ID' => $rootSectionId],
                                false,
                                ['ID', 'LEFT_MARGIN', 'RIGHT_MARGIN']
                        )->Fetch();

                        if ($rootRes && $rootRes['LEFT_MARGIN'] !== null && $rootRes['RIGHT_MARGIN'] !== null) {
                            $ids = [];
                            $rs = CIBlockSection::GetList(
                                    ['LEFT_MARGIN' => 'ASC'],
                                    [
                                            'IBLOCK_ID' => $iblockId,
                                            '>LEFT_MARGIN' => (int)$rootRes['LEFT_MARGIN'],
                                            '<RIGHT_MARGIN' => (int)$rootRes['RIGHT_MARGIN'],
                                            'ACTIVE' => 'Y',
                                            'GLOBAL_ACTIVE' => 'Y',
                                    ],
                                    false,
                                    ['ID']
                            );
                            while ($row = $rs->Fetch()) {
                                $ids[] = (int)$row['ID'];
                            }
                        }

                        return $ids;
                    }

                    $directorateKeywords = ['Генеральный директор', 'Дирекция', 'Комитет', 'Советник', 'Заместитель директора', 'Директор дивизиона', 'заместитель генерального директора'];
                    $managementKeywords = ['Управление', 'Группа'];

                    $foundDirectorate = null;
                    $foundManagement = null;
                    $foundGroup = null;

                    foreach ($arDepartments as $departmentId) {
                        $chain = getSectionChain($departmentId, $iblockId);
                        if (!$chain) {
                            continue;
                        }

                        for ($i = count($chain) - 1; $i >= 0; $i--) {
                            $node = $chain[$i];
                            $name = $node['NAME'];

                            if (!$foundGroup && mb_stripos($name, 'Группа') !== false) {
                                $foundGroup = $node;
                            }
                            if (!$foundManagement && findInChain([$node], $managementKeywords)) {
                                $foundManagement = $node;
                            }
                            if (!$foundDirectorate && findInChain([$node], $directorateKeywords)) {
                                $foundDirectorate = $node;
                            }
                        }
                    }

                    // Определяем "главный" раздел: Дирекция > Управление > Группа
                    $rootSection = $foundDirectorate ?: $foundManagement ?: $foundGroup;

                    if (!$rootSection) {
                        echo 'Не удалось определить раздел пользователя';
                        return;
                    }

                    $rootSectionId = (int)$rootSection['ID'];

                    // Получаем ID всех вложенных разделов (включая сам корневой)
                    $nestedIds = getNestedSectionIds($rootSectionId, $iblockId);
                    $allSectionIds = array_merge([$rootSectionId], $nestedIds);

                    // Для удобства — уникализируем
                    $allSectionIds = array_values(array_unique($allSectionIds));

                    // Подключаем модуль инфоблоков
                    \Bitrix\Main\Loader::includeModule('iblock');

                    // 1. Получаем ID инфоблока по его символьному коду 'departments'
                    $iblockCode = 'departments';
                    $resIblock = CIBlock::GetList([], ['=CODE' => $iblockCode, 'CHECK_PERMISSIONS' => 'N']);
                    $arIblock = $resIblock->Fetch();

                    if (!$arIblock) {
                        die("Инфоблок с кодом '{$iblockCode}' не найден.");
                    }

                    $iblockId = $arIblock['ID'];

                    // 2. Получаем все ID разделов этого инфоблока
                    $allSectionIds1 = [];
                    $rsSections = CIBlockSection::GetList(
                            ['SORT' => 'ASC'],
                            ['IBLOCK_ID' => $iblockId],
                            false,
                            ['ID']
                    );

                    while ($arSection = $rsSections->Fetch()) {
                        $allSectionIds1[] = (int)$arSection['ID'];
                    }

                    // 3. Массив ID, которые нужно исключить (ваш определенный массив)
                    //$excludeIds = [5, 10, 15]; // Пример: замените на ваши реальные ID

                    // 4. Вычитаем один массив из другого
                    // array_diff вернет все значения из $allSectionIds1, которых нет в $excludeIds
                    $resultIds = array_diff($allSectionIds1, $allSectionIds);
                    // Вывод результата (для отладки)
                    // Теперь в $resultIds лежит массив с ID разделов, которые остались после исключения.
                    if ($arDepartments[0] == 1) {
                        $allowedDepartmentIds = array_diff($allSectionIds1, [$arDepartments[0]]);
                    } else {
                        $allowedDepartmentIds = $resultIds;
                    }

                    // Если нужен именно массив без корневого:
                    // $nestedOnly = array_values(array_diff($allSectionIds, [$rootSectionId]));


                    // Подразделения, сотрудников которых разрешено выбирать.
                    // Задавайте список на сервере, а не берите его без проверки из запроса.


                    $allowedDepartmentIds = array_values(array_unique(array_filter(
                            array_map('intval', $allowedDepartmentIds),
                            static function ($id) {
                                return $id > 0;
                            }
                    )));

                    $users = [];
                    $departmentIds = [];

                    // При пустом списке подразделений никого не загружаем.
                    if ($allowedDepartmentIds) {
                        $by = 'last_name';
                        $order = 'asc';

                        $result = CUser::GetList(
                                $by,
                                $order,
                                [
                                        'ACTIVE' => 'Y',
                                        'UF_DEPARTMENT' => $allowedDepartmentIds,
                                ],
                                [
                                        'FIELDS' => [
                                                'ID',
                                                'NAME',
                                                'LAST_NAME',
                                                'SECOND_NAME',
                                                'LOGIN',
                                        ],
                                        'SELECT' => [
                                                'UF_DEPARTMENT',
                                        ],
                                ]
                        );

                        while ($user = $result->Fetch()) {
                            $user['UF_DEPARTMENT'] = array_values(array_filter(
                                    array_map('intval', (array)$user['UF_DEPARTMENT']),
                                    static function ($id) {
                                        return $id > 0;
                                    }
                            ));

                            foreach ($user['UF_DEPARTMENT'] as $departmentId) {
                                $departmentIds[$departmentId] = $departmentId;
                            }

                            $users[(int)$user['ID']] = $user;
                        }
                    }

                    // Получаем названия подразделений одним запросом.
                    $departmentNames = [];

                    $structureIblockId = (int)Option::get(
                            'intranet',
                            'iblock_structure',
                            '0'
                    );

                    if ($structureIblockId > 0 && $departmentIds) {
                        $result = CIBlockSection::GetList(
                                ['NAME' => 'ASC'],
                                [
                                        'IBLOCK_ID' => $structureIblockId,
                                        'ID' => array_values($departmentIds),
                                ],
                                false,
                                ['ID', 'NAME']
                        );

                        while ($department = $result->Fetch()) {
                            $departmentNames[(int)$department['ID']] = $department['NAME'];
                        }
                    }

                    // Формируем элементы селектора.
                    $items = [];

                    foreach ($users as $user) {
                        $fullName = trim(implode(' ', array_filter(
                                [
                                        $user['LAST_NAME'],
                                        $user['NAME'],
                                        $user['SECOND_NAME'],
                                ],
                                static function ($value) {
                                    return trim((string)$value) !== '';
                                }
                        )));

                        if ($fullName === '') {
                            $fullName = $user['LOGIN'];
                        }

                        $names = [];

                        foreach ($user['UF_DEPARTMENT'] as $departmentId) {
                            if (isset($departmentNames[$departmentId])) {
                                $names[] = $departmentNames[$departmentId];
                            }
                        }

                        $items[] = [
                                'id' => (int)$user['ID'],

                            // Своя сущность, без стандартного провайдера всех пользователей.
                                'entityId' => 'department-employee',

                                'title' => $fullName,
                                'subtitle' => implode(', ', array_unique($names)),

                                'tabs' => ['employees'],

                                'customData' => [
                                        'departmentIds' => $user['UF_DEPARTMENT'],
                                ],
                        ];
                    }

                    $itemsJson = json_encode(
                            $items,
                            JSON_UNESCAPED_UNICODE
                            | JSON_HEX_TAG
                            | JSON_HEX_AMP
                            | JSON_HEX_APOS
                            | JSON_HEX_QUOT
                            | JSON_THROW_ON_ERROR
                    );
                    ?>
                    <div id="employees-selector"></div>
                    <div
                            id="selected-employees"
                            class="selected-employees"
                            aria-live="polite"
                    >
                    </div>


                    <div class="search-input-wrapper" style="display: none">
                        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" class="input-field" id="user-search"
                               placeholder="Начните вводить имя, фамилию или дирекцию">
                    </div>

                    <div class="user-dropdown" id="user-list" style="display: none">
                        <div class="user-option selected" data-name="Елена Кузнецова" data-dept="Дирекция по продукту"
                             data-initials="ЕК">
                            <div class="user-option-avatar">ЕК</div>
                            <div class="user-option-info">
                                <div class="user-option-name">Елена Кузнецова</div>
                                <div class="user-option-dept">Дирекция по продукту</div>
                            </div>
                        </div>
                        <div class="user-option" data-name="Алексей Воронов" data-dept="Дирекция по технологиям"
                             data-initials="АВ">
                            <div class="user-option-avatar">АВ</div>
                            <div class="user-option-info">
                                <div class="user-option-name">Алексей Воронов</div>
                                <div class="user-option-dept">Дирекция по технологиям</div>
                            </div>
                        </div>
                        <div class="user-option" data-name="Ольга Фёдорова" data-dept="Дирекция по маркетингу"
                             data-initials="ОФ">
                            <div class="user-option-avatar">ОФ</div>
                            <div class="user-option-info">
                                <div class="user-option-name">Ольга Фёдорова</div>
                                <div class="user-option-dept">Дирекция по маркетингу</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="display: none">
                    <label class="form-label" for="comment-text-input">Комментарий <span class="req">*</span></label>
                    <div class="textarea-wrapper">
                <textarea id="comment-text-input" class="textarea-field" maxlength="500"
                          placeholder="Напишите, за что вы благодарите коллегу..."></textarea>
                    </div>
                    <div class="char-count"><span id="current-chars">0</span> / 500</div>
                </div>

                <div class="alert-banner">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Доступны только коллеги из других дирекций</span>
                </div>

                <div class="modal-actions">
                    <button class="btn-cancel" id="btn-cancel">Отмена</button>
                    <button class="btn-submit" id="btn-submit">
                        <svg width="15" height="15" viewBox="0 0 24 24">
                            <use href="#icon-send"></use>
                        </svg>
                        Отправить звезду
                    </button>
                </div>
            </aside>
        </div>
    </div>
    <!--Конец левая колонка-->


    <div class="intranet-user-profile-column-right">
        <div class="intranet-user-profile-container-header_title ">
            <div class="intranet-user-profile-column-block-title" style="display: block">
                <?= $arResult['User']['LAST_NAME'] . ' ' . $arResult['User']['NAME'] . ' ' . $arResult['User']['SECOND_NAME'] ?>
            </div>
            <div>  <? if ($arResult['User']['WORK_POSITION'] != ''): ?>
                    <?= $arResult['User']['WORK_POSITION']; ?>
                <? endif; ?>
            </div>
        </div>
        <? // Контактная информация
        if ($arResult['IsOwnProfile'] || $arResult['Permissions']['edit']) {
            echo '<div class="wrap-profile wrap-edit-fields-js' . (empty($arResult['FormData']['UF_MOB_PHONE_PER_ACT']['VALUE']) ? " phone-not-visible" : "") . '" data-phone-visible="' . (!empty($arResult['FormData']['UF_MOB_PHONE_PER_ACT']['VALUE']) ? 1 : 0) . '">';
        }
        $APPLICATION->IncludeComponent(
                "lab:ui.form",
                "",
                array(
                        "GUID" => $arResult["FormId"],
                        "INITIAL_MODE" => "view",
                        "ENTITY_TYPE_NAME" => "USER",
                        "ENTITY_ID" => $arResult["User"]["ID"],
                        "ENTITY_FIELDS" => $arResult["FormFields"],
                        "ENTITY_CONFIG" => $arResult["FormConfig"],
                        "ENTITY_DATA" => $arResult["FormData"],
                        "ENABLE_SECTION_EDIT" => false,
                        "ENABLE_SECTION_CREATION" => false,
                        "ENABLE_SECTION_DRAG_DROP" => false,
                        "FORCE_DEFAULT_SECTION_NAME" => true,
                        "ENABLE_PERSONAL_CONFIGURATION_UPDATE" => $arResult["EnablePersonalConfigurationUpdate"],
                        "ENABLE_COMMON_CONFIGURATION_UPDATE" => $arResult["EnableCommonConfigurationUpdate"],
                        "ENABLE_SETTINGS_FOR_ALL" => $arResult["EnableSettingsForAll"],
                        "READ_ONLY" => $arResult["Permissions"]['edit'] ? false : true,
                        "ENABLE_USER_FIELD_CREATION" => $arResult["EnableUserFieldCreation"],
                        "ENABLE_USER_FIELD_MANDATORY_CONTROL" => $arResult["EnableUserFieldMandatoryControl"],
                        "USER_FIELD_ENTITY_ID" => $arResult["UserFieldEntityId"],
                        "USER_FIELD_PREFIX" => $arResult["UserFieldPrefix"],
                        "USER_FIELD_CREATE_SIGNATURE" => $arResult["UserFieldCreateSignature"],
                        "SERVICE_URL" => POST_FORM_ACTION_URI . '&' . bitrix_sessid_get(),
                        "COMPONENT_AJAX_DATA" => array(
                                "COMPONENT_NAME" => $this->getComponent()->getName(),
                                "ACTION_NAME" => "save",
                                "SIGNED_PARAMETERS" => $this->getComponent()->getSignedParameters()
                        )
                )
        );

        if ($arResult['IsOwnProfile'] || $arResult['Permissions']['edit']) {
            echo '</div>';
        } ?>

        <!-- Организационная структура-->
        <!--DEPARTMENT_CHAINS-->
        <style>
            /* Скрытие блока */
            .team-grid.hidden {
                display: none;
            }

            /* Стили для кнопки (если ещё нет) */
            .show-team-btn {
                background: none;
                border: none;
                color: #e31e24; /* Цвет как на скриншоте */
                font-size: 12px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 0;
                font-family: inherit;
            }

            .show-team-btn:hover {
                opacity: 0.8;
            }

            /* Треугольник */
            .triangle {
                font-size: 10px;
                transition: transform 0.2s ease;
            }

            /* Общие стили для секции */
            .team-section {
                font-family: 'Arial', sans-serif; /* Или ваш шрифт */
                padding: 20px;
                background-color: #fff;
            }

            /* --- Кнопка "Показать команду" --- */
            .team-header {
                margin-bottom: 20px;
            }

            .triangle {
                font-size: 10px;
                transform: translateY(-1px); /* Небольшая коррекция положения */
            }

            /* --- Сетка сотрудников --- */
            .team-grid {
                display: grid;
                /* Создаем 3 колонки равной ширины */
                grid-template-columns: repeat(3, 1fr);
                gap: 30px; /* Расстояние между колонками */
            }

            /* --- Карточка сотрудника --- */
            .team-card {
                display: flex;
                align-items: center; /* Выравнивание по вертикали */
                gap: 15px; /* Расстояние между фото и текстом */
            }

            /* --- Обертка для аватара (Круглая) --- */
            .avatar-wrapper {
                width: 56px; /* Размер круга */
                height: 56px;
                border-radius: 50%; /* Делает круг */
                overflow: hidden; /* Обрезает всё лишнее */
                position: relative;
                flex-shrink: 0; /* Запрещает сжатие фото */
                background-color: #f0f0f0; /* Фон, пока грузится фото */
            }

            /* --- Стили для картинки (на основе вашего кода) --- */
            .q-img__container.absolute-full {
                width: 100%;
                height: 100%;
                position: relative;
            }

            .q-img__image {
                width: 100%;
                height: 100%;
                object-fit: cover; /* Заполняет круг без искажений */
                object-position: 50% 50%;
                display: block;
            }

            /* --- Текстовая информация --- */
            .team-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .team-name {
                font-size: 16px;
                font-weight: 500;
                color: #000;
                margin-bottom: 4px;
            }

            .team-position {
                font-size: 14px;
                color: #888; /* Серый цвет для должности */
                line-height: 1.2;
            }

            /* --- Адаптивность для мобильных устройств --- */
            @media (max-width: 900px) {
                .team-grid {
                    grid-template-columns: 1fr; /* В одну колонку на узких экранах */
                    gap: 20px;
                }
            }

            /* Стили для самой ссылки (текст) */
            .structure-link {
                display: inline-flex; /* Выстраивает текст и кружок в одну линию */
                align-items: center; /* Выравнивает по центру по вертикали */
                gap: 12px; /* Расстояние между текстом и стрелкой */
                text-decoration: none; /* Убирает подчеркивание */
                color: #000; /* Цвет текста (черный) */
                font-weight: 700; /* Жирность шрифта как на картинке */
                /*font-size: 18px;*/ /* Размер шрифта (подстройте под ваш дизайн) */
                font-family: sans-serif;
            }

            /* Стили для кружка со стрелкой */
            .arrow-circle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 32px; /* Диаметр кружка */
                height: 32px; /* Диаметр кружка */
                border-radius: 50%; /* Делает форму круглой */
                background-color: transparent; /* По умолчанию фон прозрачный */
                color: #4B2E83; /* Фиолетовый цвет стрелки (как на 2 картинке) */
                transition: background-color 0.3s ease; /* Плавная смена цвета */
            }

            /* Эффект при наведении на всю ссылку (или только на кружок) */
            .structure-link:hover .arrow-circle {
                background-color: #e0e0e0; /* Серый фон при наведении */
            }

            /* Чтобы стрелка не сжималась, если текст длинный */
            .arrow-circle svg {
                flex-shrink: 0;
            }

            /* Общий контейнер (опционально, для отступов) */
            .manager-block {
                font-family: Arial, sans-serif; /* Или ваш шрифт */
                padding: 20px;
                background-color: #ffffff;
            }

            /* Заголовок "Руководитель" */
            .manager-label {
                color: #7f7f7f; /* Серый цвет */
                font-size: 14px;
                margin-bottom: 12px;
                font-weight: 400;
            }

            /* Карточка с фото и текстом */
            .manager-card {
                display: flex;
                align-items: center; /* Выравнивание по центру вертикали */
                gap: 16px; /* Отступ между фото и текстом */
            }

            /* Контейнер для фото (делаем круглым) */
            .manager-photo-container {
                width: 64px;
                height: 64px;
                border-radius: 50%; /* Круглая форма */
                overflow: hidden; /* Обрезаем все, что выходит за круг */
                flex-shrink: 0; /* Запрещаем сжимать фото */
                background-color: #f0f0f0; /* Заглушка, пока грузится фото */
            }

            /* Само изображение */
            .manager-photo {
                width: 100%;
                height: 100%;
                object-fit: cover; /* Аналог object-fit: cover из вашего кода */
                object-position: 50% 50%;
                display: block;
            }

            /* Блок с текстом */
            .manager-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            /* Имя (ссылка) */
            .manager-name {
                color: #000000;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none; /* Убираем подчеркивание */
                margin-bottom: 4px;
                line-height: 1.2;
            }

            /* Эффект при наведении на имя (опционально) */
            .manager-name:hover {
                color: #0055bb; /* Цвет ссылки при наведении */
                text-decoration: underline;
            }

            /* Должность */
            .manager-position {
                color: #7f7f7f; /* Серый цвет, как у заголовка */
                font-size: 14px;
                line-height: 1.2;
            }

            .intranet-user-profile-post-container {
                background-color: #ffffff;
                display: flex;
                padding: 0 0 14px 0;
                line-height: 22px;
                font-size: 22px;
                font-weight: 700;
                border-bottom: 1px solid #eef2f4;
                color: black;
            }

            .dept-tree {
                display: flex;
                flex-direction: column;
                gap: 8px;
                font-size: 14px;
                line-height: 1.4;
                font-family: Arial, sans-serif;
            }

            .dept-tree__item {
                display: flex;
                align-items: center;
                /* Каждый уровень сдвигается на 20px */
                padding-left: calc(var(--level) * 20px);
            }

            /* Треугольная стрелка перед промежуточными узлами */
            .dept-tree__arrow {
                display: inline-block;
                color: #c4c4c4;
                font-size: 11px;
                margin-right: 6px;
                user-select: none;
            }

            /* Обычные родительские ссылки */
            .dept-tree__item .dept-tree__link,
            .dept-tree__item .dept-tree__text {
                color: #222222;
                text-decoration: none;
            }

            .dept-tree__item .dept-tree__link:hover {
                text-decoration: underline;
            }

            /* Текущий (конечный) департамент */
            .dept-tree__item--current {
                /* Если у последнего нет стрелки, компенсируем её ширину для точного выравнивания */
                padding-left: calc(var(--level) * 20px + 12px);
            }

            .dept-tree__item--current .dept-tree__link,
            .dept-tree__item--current .dept-tree__text {
                color: #d15656; /* Красно-розовый акцент как на скриншоте */
            }


            .show-team-btn:hover {
                opacity: 0.8;
            }

            /* Стили для треугольника */
            .triangle {
                font-size: 12px;
                transition: transform 0.3s ease;
            }

            /* Стили для сетки (пример, если у вас их нет) */
            .team-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }

            /* Стили для кнопки (чтобы было похоже на картинку) */
            .team-header {
                margin-bottom: 20px;
            }


            .show-team-btn:hover {
                opacity: 0.8;
            }

            /* Стили для стрелочки */
            .triangle {
                font-size: 10px;
                display: inline-block;
                transition: transform 0.3s ease;
            }

            /* Класс, который переворачивает стрелку вверх */
            .triangle.rotated {
                transform: rotate(180deg);
            }

            /* --- Анимация блока --- */

            /* Начальное состояние: скрыт */
            .team-grid {
                max-height: 0;
                overflow: hidden;
                opacity: 0;
                visibility: hidden; /* Скрывает контент от скринридеров, когда блок закрыт */
                transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out, visibility 0.5s;

                /* Ваши стили для сетки (если нужны) */
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            /* Состояние когда открыт */
            .team-grid.active {
                max-height: 1000px; /* Достаточно большое значение, чтобы вместить контент */
                opacity: 1;
                visibility: visible;
                /* Добавляем отступ снизу, если нужно */
                margin-bottom: 20px;
            }
        </style>
        <div class=" department_chains intranet-user-profile-container-header_title ">
            <h2 class="intranet-user-profile-column-block-title">

                <a href="https://corp-portal.welcome.moscow/company/vis_structure.php" class="structure-link">
                    Организационная структура
                    <span class="arrow-circle">
        <!-- SVG стрелка вправо -->
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </svg>
    </span>
                </a>

            </h2>

            <?php if (!empty($arResult['DEPARTMENT_CHAINS'])): ?>
                <div class="dept-chains-wrapper">
                    <?php foreach ($arResult['DEPARTMENT_CHAINS'] as $chain): ?>
                        <div class="dept-tree">
                            <?php
                            $total = count($chain);
                            foreach ($chain as $depth => $dep):
                                $isLast = ($depth === $total - 1);
                                ?>
                                <div class="dept-tree__item <?= $isLast ? 'dept-tree__item--current' : '' ?>"
                                     style="--level: <?= $depth; ?>;">
                                    <?php if (!$isLast): ?>
                                        <span class="dept-tree__arrow">&#9662;</span>
                                    <?php endif; ?>

                                    <?php if (!empty($dep['href'])): ?>
                                        <a href="<?= htmlspecialcharsbx($dep['href']) ?>" target="_top"
                                           class="dept-tree__link">
                                            <?= htmlspecialcharsbx($dep['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="dept-tree__text"><?= htmlspecialcharsbx($dep['name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="manager-block">
                <!-- Заголовок категории -->
                <div class="manager-label">Руководитель</div>

                <!-- Карточка сотрудника -->
                <div class="manager-card">
                    <!-- Фото -->
                    <?
                    if (count($arResult['User']['MANAGERS']) === 1) {
                        $value = reset($arResult['User']['MANAGERS']);
                    }
                    ?>
                    <div class="manager-photo-container">
                        <img
                                class="manager-photo"
                                src='<?= $value["PHOTO"] ?>'
                                alt="<?= $value["FULL_NAME"] ?>"
                        >
                    </div>

                    <!-- Информация -->
                    <div class="manager-info">
                        <a href="https://corp-portal.welcome.moscow/company/personal/user/<?= $value["ID"] ?>/"
                           class="manager-name">
                            <?= $value["FULL_NAME"] ?>
                        </a>
                        <div class="manager-position"><?= $value["PERSONAL_PROFESSION"] ?></div>
                    </div>

                </div>
            </div>
            <section class="team-section">
                <!-- Кнопка "Показать команду" -->
                <div class="team-header">
                    <button class="show-team-btn" id="toggleTeamBtn">
                        Показать команду <span class="triangle">▲</span>
                    </button>
                </div>

                <!-- Сетка с сотрудниками (по умолчанию скрыта) -->
                <div class="team-grid" id="teamGrid">
                    <? foreach ($arResult['DepartmentUsers'] as $team): ?>
                        <div class="team-card">
                            <div class="avatar-wrapper">
                                <div class="q-img__container absolute-full">
                                    <img class="q-img__image" src="<?= $team['PERSONAL_PHOTO'] ?>"
                                         alt="<?= $team['NAME'] . ' ' . $team['LAST_NAME'] ?>" loading="lazy">
                                </div>
                            </div>
                            <div class="team-info">
                                <div class="team-name"><?= $team['NAME'] . ' ' . $team['LAST_NAME'] ?></div>
                                <div class="team-position"><?= $team['PERSONAL_PROFESSION'] ?></div>
                            </div>
                        </div>
                    <? endforeach; ?>

                </div>
            </section>


        </div>
        <!--END DEPARTMENT_CHAINS-->

        <? if (
                !empty($arResult["ProfileBlogPost"])
                && (
                        !empty($arResult["ProfileBlogPost"]["POST_ID"])
                        || $arResult["Permissions"]['edit']
                        || $USER->getId() == $arResult["User"]["ID"]
                )
                && $arResult["User"]["STATUS"] !== "email"
        ) {
            ?>
            <div id="intranet-user-profile-post-container"
                 class="intranet-user-profile-container<?= (empty($arResult["ProfileBlogPost"]["POST_ID"]) ? ' intranet-user-profile-container-empty' : '') ?>">
                <div class="intranet-user-profile-container-header">
                    <div class="intranet-user-profile-container-title"><?= Loc::getMessage("INTRANET_USER_PROFILE_BLOG_POST_TITLE") ?></div><?

                    if (
                            $arResult["Permissions"]['edit']
                            || $USER->getId() == $arResult["User"]["ID"]
                    ) {
                        ?><a id="intranet-user-profile-post-edit-link"
                             class="intranet-user-profile-container-edit"><?= Loc::getMessage('INTRANET_USER_PROFILE_BLOG_POST_MODIFY'); ?></a><?
                    }

                    ?></div>
                <div class="intranet-user-profile-container-body intranet-user-profile-about-wrapper">
                    <div id="intranet-user-profile-about-wrapper"></div>

                    <div id="intranet-user-profile-post-edit-stub"
                         class="intranet-user-profile-about intranet-user-profile-empty-stub intranet-user-profile-about-profile">
                        <div class="intranet-user-profile-post-edit-stub-default"><?= Loc::getMessage('INTRANET_USER_PROFILE_BLOG_POST_STUB_TEXT') ?></div>
                        <a id="intranet-user-profile-post-edit-stub-button"
                           class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round ui-btn-caps"><?= Loc::getMessage('INTRANET_USER_PROFILE_BLOG_POST_STUB_BUTTON') ?></a>
                    </div><?

                    if (
                            (
                                    $arResult["Permissions"]['edit']
                                    || $USER->getId() == $arResult["User"]["ID"]
                            )
                    ) {
                        ?>
                        <div id="intranet-user-profile-about-form-wrapper" style="display: block;">
                        <form action=""
                              id="<?= htmlspecialcharsbx($arResult["ProfileBlogPost"]["formParams"]["FORM_ID"]) ?>"
                              name="<?= htmlspecialcharsbx($arResult["ProfileBlogPost"]["formParams"]["FORM_ID"]) ?>"
                              method="POST" enctype="multipart/form-data" target="_self" class="profile-post-form">
                            <?= bitrix_sessid_post(); ?>
                            <?
                            $APPLICATION->IncludeComponent(
                                    "bitrix:main.post.form",
                                    ".default",
                                    $arResult["ProfileBlogPost"]["formParams"],
                                    false,
                                    [
                                            "HIDE_ICONS" => "Y"
                                    ]
                            ); ?>
                            <input type="hidden" name="cuid" id="upload-cid" value=""/>
                        </form>
                        </div><?
                    }

                    ?>
                    <div class="intranet-user-profile-about-loader" id="intranet-user-profile-about-loader"></div>
                </div>
            </div>
            <?
        }
        ?>

    </div>
</div>
<div class="intranet-user-profile-bottom-controls">
    <? /*if ($arResult["IsOwnProfile"]):?>
	<a class="ui-btn ui-btn-xs ui-btn-light-border ui-btn-themes" onclick="BX.Intranet.Bitrix24.ThemePicker.Singleton.showDialog()">
		<?=Loc::getMessage("INTRANET_USER_PROFILE_THEME")?>
	</a>
	<?endif*/ ?>
</div>

<? if ($arResult["adminRightsRestricted"]) {
    $moveRightsConfirmText = Loc::getMessage("INTRANET_USER_PROFILE_MOVE_ADMIN_RIGHTS_CONFIRM");
    $moveRightsConfirmText .= "<br/><br/><span style='color: #acb1b7'>" . Loc::getMessage("INTRANET_USER_PROFILE_MOVE_ADMIN_RIGHTS_CONFIRM_PROMO",
                    array(
                            "#LINK_START#" => "<a href=\"javascript:void(0)\" 
			onclick=\"B24.licenseInfoPopup.show('adminQuantityRestriction', '" . Loc::getMessage("INTRANET_USER_PROFILE_RIGHTS_RESTR_TITLE") . "', BX.clone(BX('adminRestrContent')))\"	
		>",
                            "#LINK_END#" => "</a>"
                    )) . "</span>";
    $issetTfTariff = \Bitrix\Main\Loader::includeModule("bitrix24") && in_array(\CBitrix24::getLicensePrefix(),
                    array('ru', 'kz', 'by', 'ua'));
    ?>
    <div style="display: none">
        <div id="adminRestrContent">
            <?
            if ($arResult["IS_COMPANY_TARIFF"]):?>
                <div style="padding-bottom: 20px;"><?= Loc::getMessage("INTRANET_USER_PROFILE_RIGHTS_RESTR_COMPANY_TEXT") ?></div>
            <? else: ?>
                <div style='font-size: 20px; padding-bottom: 20px;'><?= Loc::getMessage("INTRANET_USER_PROFILE_RIGHTS_RESTR_TEXT1") ?></div>
                <div style='padding-bottom: 20px;'><?= Loc::getMessage("INTRANET_USER_PROFILE_RIGHTS_RESTR_TEXT2") ?></div>
                <table width='100%'>
                    <tr align='center' style='font-weight: bold'>
                        <td><?= Loc::getMessage("INTRANET_USER_PROFILE_TARIFF_PROJECT") ?></td>
                        <?
                        if ($issetTfTariff):?>
                            <td><?= Loc::getMessage("INTRANET_USER_PROFILE_TARIFF_TF") ?></td>
                        <? endif ?>
                        <td><?= Loc::getMessage("INTRANET_USER_PROFILE_TARIFF_TEAM") ?></td>
                        <td><?= Loc::getMessage("INTRANET_USER_PROFILE_TARIFF_COMPANY") ?></td>
                    </tr>
                    <tr align='center'>
                        <td>1</td>
                        <?
                        if ($issetTfTariff):?>
                            <td>2</td>
                        <? endif ?>
                        <td>5</td>
                        <td><?= Loc::getMessage("INTRANET_USER_PROFILE_UNLIM") ?></td>
                    </tr>
                </table>
                <!--<br>
				<div>
					<a href='javascript:void' onclick='BX.Helper.show("redirect=detail&code=5869717");'><?= Loc::getMessage("INTRANET_USER_PROFILE_RIGHTS_RESTR_MORE") ?></a>
				</div>-->
            <? endif ?>
        </div>
    </div>
    <?
} ?>


<script>
    BX.ready(function () {
        const employeeItems = <?= $itemsJson ?>;
        const selectedContainer = document.getElementById('selected-employees');

        $('body').on('click', '.ui-selector-item-box', function (e) {

            setTimeout(sel, 1000)

        });

        function sel() {
            const _thisId = $('.selected-employees__row').data('user-id');
            console.log(_thisId);
        }

        // Справочник: ID сотрудника → его данные.
        const employeesById = new Map(
            employeeItems.map(function (item) {
                return [String(item.id), item];
            })
        );

        var $arExcludedUsers = []; //Ids

        //console.log(arrAr);
        const selector = new BX.UI.EntitySelector.TagSelector({
            id: 'employees-by-department',
            multiple: false,

            events: {
                onAfterTagAdd: function () {
                    renderSelectedEmployees();
                },
                // Событие срабатывает перед добавлением тега (выбором из списка)
                onBeforeTagAdd: function (event) {
                    $('.selected-employees_information').text('');

                    // Получаем сам виджет (селектор)
                    const selector = event.getTarget();

                    // Получаем данные выбранного элемента (тега)
                    const {tag} = event.getData();

                    // Теперь у вас есть доступ к информации о сотруднике
                    // Например, можно получить ID и имя:
                    const userId = tag.getId();       // ID пользователя
                    const userName = tag.getTitle();  // Имя / ФИО


                    const arrAr = <?= json_encode($arUsersExclude) ?>;

                    if (Array.isArray(arrAr) && arrAr.length > 0) {
                        if (arrAr.some(x => Number(x) === userId)) {
                            alert(userName + ' вы уже отправили звезду, выберите другого');

                            setTimeout(function () {
                                $('#selected-employees').find('button.selected-employees__remove').each(function () {
                                    $(this).click();
                                    $('.selected-employees_information').text(userName + ' вы уже отправили звезду, выберите другого');
                                });
                            }, 100);
                        }
                    }


                    // Здесь можно выполнить свои действия, например, отправить AJAX запрос
                    // или обновить другое поле на странице.
                },

                onAfterTagRemove: function () {
                    renderSelectedEmployees();
                }
            },

            dialogOptions: {
                recentTabOptions: {
                    visible: false
                },

                context: null,

                enableSearch: true,
                dynamicLoad: false,
                dynamicSearch: false,

                entities: [
                    {
                        id: 'department-employee',
                        dynamicLoad: false,
                        dynamicSearch: false,
                        options: {
                            id: 'user',
                            // Массив ID пользователей, которых нужно скрыть
                            excludedIds: [590, 190]
                        }

                    }
                ],

                tabs: [
                    {
                        id: 'employees',
                        title: 'Сотрудники'
                    }
                ],

                items: employeeItems
            }
        });

        /* const dialog = selector.getDialog();

         // Устанавливаем новые опции для сущности user
         dialog.getEntity('user').setOptions({
             excludeUsers: [590]
         });

 // Сбрасываем кэш загруженных элементов и перезагружаем вкладки
         dialog.clearCache();*/

        // ID сотрудника → введённый комментарий.
// Сохраняет текст при перерисовке списка.
        const employeeComments = new Map();

        function renderSelectedEmployees() {
            selectedContainer.textContent = '';

            const tags = selector.getTags();

            if (tags.length === 0) {
                const empty = document.createElement('div');

                empty.className = 'selected-employees__empty';
                empty.textContent = 'Сотрудники не выбраны';

                selectedContainer.appendChild(empty);
                return;
            }

            const fragment = document.createDocumentFragment();


            tags.forEach(function (tag) {
                const userId = String(tag.getId());
                const employee = employeesById.get(userId);

                const name = employee
                    ? employee.title
                    : tag.getTitle();

                const department = employee && employee.subtitle
                    ? employee.subtitle
                    : 'Подразделение не указано';

                // Строка, привязанная к ID сотрудника.
                const row = document.createElement('div');

                row.className = 'selected-employees__row';
                row.id = 'selected-employee-' + userId;
                row.dataset.userId = userId;

                const info = document.createElement('div');
                info.className = 'selected-employees__info';

                const nameElement = document.createElement('div');
                nameElement.className = 'selected-employees__name';
                nameElement.textContent = name;

                const idElement = document.createElement('div');
                idElement.className = 'selected-employees__id';
                idElement.textContent = 'ID пользователя: ' + userId;

                const departmentElement = document.createElement('div');
                departmentElement.className = 'selected-employees__department';
                departmentElement.textContent = department;

                // ID сотрудника для отправки обычной HTML-формы.
                const userIdInput = document.createElement('input');

                userIdInput.type = 'hidden';
                userIdInput.name = 'employees[' + userId + '][id]';
                userIdInput.value = userId;

                // Обязательный комментарий конкретного сотрудника.
                const commentContainer = document.createElement('div');
                commentContainer.className = 'selected-employees__comment';

                const commentLabel = document.createElement('label');

                commentLabel.className = 'selected-employees__comment-label';
                commentLabel.htmlFor = 'employee-comment-' + userId;
                commentLabel.textContent = 'Комментарий *';

                const commentTextarea = document.createElement('textarea');

                commentTextarea.id = 'employee-comment-' + userId;
                commentTextarea.name = 'employees[' + userId + '][comment]';
                commentTextarea.className = 'selected-employees__textarea';
                commentTextarea.rows = 3;
                commentTextarea.required = true;
                commentTextarea.placeholder = 'Введите комментарий';
                commentTextarea.value = employeeComments.get(userId) || '';

                function updateComment() {
                    employeeComments.set(userId, commentTextarea.value);

                    // Не разрешаем комментарий, состоящий только из пробелов.
                    commentTextarea.setCustomValidity(
                        commentTextarea.value.trim() === ''
                            ? 'Заполните комментарий для сотрудника.'
                            : ''
                    );
                }

                commentTextarea.addEventListener('input', updateComment);
                updateComment();

                commentContainer.appendChild(commentLabel);
                commentContainer.appendChild(commentTextarea);

                const removeButton = document.createElement('button');

                removeButton.type = 'button';
                removeButton.className = 'selected-employees__remove';
                removeButton.textContent = 'Удалить';
                removeButton.setAttribute(
                    'aria-label',
                    'Удалить сотрудника: ' + name
                );

                removeButton.addEventListener('click', function () {
                    selector.removeTag({
                        id: tag.getId(),
                        entityId: tag.getEntityId()
                    });
                });

                info.appendChild(nameElement);
                info.appendChild(idElement);
                info.appendChild(departmentElement);
                info.appendChild(userIdInput);
                info.appendChild(commentContainer);

                row.appendChild(info);
                row.appendChild(removeButton);

                fragment.appendChild(row);
            });

            selectedContainer.appendChild(fragment);
        }


        selector.renderTo(
            document.getElementById('employees-selector')
        );

        renderSelectedEmployees();

        // ID выбранных сотрудников для отправки формы.
        window.getSelectedEmployeeIds = function () {
            return selector.getTags().map(function (tag) {
                return Number(tag.getId());
            });
        };


    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Управление модальным окном
        const openBtn = document.getElementById('open-gift-btn');
        const modalOverlay = document.getElementById('gift-modal');
        const closeBtn = document.getElementById('btn-close-modal');
        const cancelBtn = document.getElementById('btn-cancel');

        function openModal() {
            modalOverlay.classList.add('is-open');
            modalOverlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modalOverlay.classList.remove('is-open');
            modalOverlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        openBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);

        modalOverlay?.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalOverlay.classList.contains('is-open')) {
                closeModal();
            }
        });

        // Обработка отправки формы
        const btnSubmit = document.getElementById('btn-submit');

        btnSubmit.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedContainer = document.getElementById('selected-employees');
            if (!selectedContainer) return;

            // 1. Ищем выбранного сотрудника
            const row = selectedContainer.querySelector('.selected-employees__row');
            if (!row) {
                alert('Пожалуйста, выберите сотрудника.');
                return;
            }

            const targetUserId = row.getAttribute('data-user-id');
            if (!targetUserId) {
                alert('Не удалось определить ID сотрудника.');
                return;
            }

            // 2. Ищем поле комментария внутри выбранного сотрудника
            const commentBlock = row.querySelector('.selected-employees__comment');
            const textarea = commentBlock ? commentBlock.querySelector('textarea') : null;
            const commentValue = textarea ? textarea.value.trim() : '';

            if (!commentValue) {
                alert('Заполните обязательный комментарий для сотрудника.');
                if (textarea) textarea.focus();
                return;
            }

            // 3. Блокируем кнопку на время запроса
            btnSubmit.disabled = true;
            const originalBtnText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = 'Отправка...';

            // 4. Формируем POST-параметры
            const formData = new FormData();
            formData.append('sessid', BX.bitrix_sessid()); // Токен безопасности Битрикса
            formData.append('target_user_id', targetUserId);
            formData.append('comment', commentValue);

            // Путь к обработчику (укажите точный путь, если файл лежит в отдельной папке)
            fetch('/mar/ajax_marathon_handler.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Звезда добавлена');

                        // Очистка и закрытие
                        if (textarea) textarea.value = '';
                        closeModal();

                        // При необходимости обновляем страницу для пересчета остатка
                        location.reload();
                    } else {
                        alert(data.message || 'Произошла ошибка при отправке');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Произошла сетевая ошибка при отправке запроса.');
                })
                .finally(() => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalBtnText;
                });
        });
    });
</script>
<script>
    BX.message({
        "INTRANET_USER_PROFILE_REINVITE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_REINVITE"))?>",
        "INTRANET_USER_PROFILE_DELETE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_DELETE"))?>",
        "INTRANET_USER_PROFILE_FIRE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_FIRE"))?>",
        "INTRANET_USER_PROFILE_MOVE_TO_INTRANET": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_MOVE_TO_INTRANET"))?>",
        "INTRANET_USER_PROFILE_HIRE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_HIRE"))?>",
        "INTRANET_USER_PROFILE_ADMIN_MODE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_ADMIN_MODE"))?>",
        "INTRANET_USER_PROFILE_QUIT_ADMIN_MODE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_QUIT_ADMIN_MODE"))?>",
        "INTRANET_USER_PROFILE_SET_ADMIN_RIGHTS": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_SET_ADMIN_RIGHTS"))?>",
        "INTRANET_USER_PROFILE_REMOVE_ADMIN_RIGHTS": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_REMOVE_ADMIN_RIGHTS"))?>",
        "INTRANET_USER_PROFILE_SYNCHRONIZE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_SYNCHRONIZE"))?>",
        "INTRANET_USER_PROFILE_FIRE_CONFIRM": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_FIRE_CONFIRM"))?>",
        "INTRANET_USER_PROFILE_DELETE_CONFIRM": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_DELETE_CONFIRM"))?>",
        "INTRANET_USER_PROFILE_HIRE_CONFIRM": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_HIRE_CONFIRM"))?>",
        "INTRANET_USER_PROFILE_YES": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_YES"))?>",
        "INTRANET_USER_PROFILE_NO": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_NO"))?>",
        "INTRANET_USER_PROFILE_MOVE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_MOVE"))?>",
        "INTRANET_USER_PROFILE_CLOSE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_CLOSE"))?>",
        "INTRANET_USER_PROFILE_SAVE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_SAVE"))?>",
        "INTRANET_USER_PROFILE_MOVE_TO_INTRANET_TITLE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_MOVE_TO_INTRANET_TITLE"))?>",
        "INTRANET_USER_PROFILE_REINVITE_SUCCESS": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_REINVITE_SUCCESS"))?>",
        "INTRANET_USER_PROFILE_PHOTO_DELETE_CONFIRM": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_PHOTO_DELETE_CONFIRM"))?>",
        "INTRANET_USER_PROFILE_MOVE_ADMIN_RIGHTS_CONFIRM": "<?=\CUtil::JSEscape($moveRightsConfirmText)?>",
        "INTRANET_USER_PROFILE_FIELD_NAME": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_FIELD_NAME"))?>",
        "INTRANET_USER_PROFILE_FIELD_LAST_NAME": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_FIELD_LAST_NAME"))?>",
        "INTRANET_USER_PROFILE_FIELD_SECOND_NAME": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_FIELD_SECOND_NAME"))?>",
        "INTRANET_USER_PROFILE_TAGS_POPUP_ADD": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_TAGS_POPUP_ADD"))?>",
        "INTRANET_USER_PROFILE_CONFIRM_PASSWORD": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_CONFIRM_PASSWORD"))?>",
        "INTRANET_USER_PROFILE_TAGS_POPUP_TITLE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_TAGS_POPUP_TITLE"))?>",
        "INTRANET_USER_PROFILE_TAGS_POPUP_SEARCH_TITLE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_TAGS_POPUP_SEARCH_TITLE"))?>",
        "INTRANET_USER_PROFILE_TAGS_POPUP_HINT_3": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_TAGS_POPUP_HINT_3"))?>",
        "INTRANET_USER_PROFILE_TAGS_POPUP_HINT_2": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_TAGS_POPUP_HINT_2"))?>",
        "INTRANET_USER_PROFILE_APP_INSTALL": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_APP_INSTALL"))?>",
        "INTRANET_USER_PROFILE_APP_SEND": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_APP_SEND"))?>",
        "INTRANET_USER_PROFILE_APP_INSTALL_TEXT": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_APP_INSTALL_TEXT"))?>",
        "INTRANET_USER_PROFILE_APP_PHONE": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_APP_PHONE"))?>",
        "INTRANET_USER_FIILDS_SETTINGS": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_FIILDS_SETTINGS"))?>",
        "INTRANET_USER_PROFILE_SET_INEGRATOR_RIGHTS": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_SET_INEGRATOR_RIGHTS"))?>",
        "INTRANET_USER_PROFILE_FIRE_INVITED_USER": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_FIRE_INVITED_USER"))?>",
        "INTRANET_USER_PROFILE_SET_INTEGRATOR_RIGHTS_CONFIRM": "<?=\CUtil::JSEscape(
                Loc::getMessage("INTRANET_USER_PROFILE_SET_INTEGRATOR_RIGHTS_CONFIRM", array(
                        "#NAME#" => "<b>" . $arResult["User"]["FULL_NAME"] . "</b>",
                        "#LINK_START#" => "<a href=\"javascript:void(0)\" onclick='top.BX.Helper.show(\"redirect=detail&code=7725333\");'>",
                        "#LINK_END#" => "</a>"
                ))
        )?>",
        "INTRANET_USER_PROFILE_STRESSLEVEL_NORESULT_INDICATOR_TEXT": "<?=\CUtil::JSEscape(Loc::getMessage("INTRANET_USER_PROFILE_STRESSLEVEL_NORESULT_INDICATOR_TEXT"))?>"
    });

    new BX.Intranet.UserProfile.Manager({
        signedParameters: '<?=$this->getComponent()->getSignedParameters()?>',
        componentName: '<?=$this->getComponent()->getName() ?>',
        canEditProfile: '<?=$arResult["Permissions"]['edit'] ? "Y" : "N"?>',
        badgesData: <?=(\CUtil::phpToJSObject(!empty($arResult['Gratitudes']['BADGES']) ? $arResult['Gratitudes']['BADGES'] : array()))?>,
        gratPostListPageSize: <?=intval($arParams['GRAT_POST_LIST_PAGE_SIZE'])?>,
        userId: <?=intval($arResult["User"]["ID"])?>,
        userStatus: <?=\CUtil::PhpToJSObject($arResult["User"]["STATUS"])?>,
        isOwnProfile: '<?=$arResult["IsOwnProfile"] ? "Y" : "N"?>',
        urls: <?=\CUtil::PhpToJSObject($arResult["Urls"])?>,
        isSessionAdmin: "<?=$arResult["User"]["IS_SESSION_ADMIN"] ? "Y" : "N"?>",
        showSonetAdmin: "<?=$arResult["User"]["SHOW_SONET_ADMIN"] ? "Y" : "N"?>",
        isExtranetUser: "<?=$arResult["User"]["IS_EXTRANET"] ? "Y" : "N"?>",
        isCurrentUserIntegrator: "<?=$arResult["IS_CURRENT_USER_INTEGRATOR"] ? "Y" : "N"?>",
        languageId: "<?=LANGUAGE_ID?>",
        siteId: "<?=SITE_ID?>",
        isCloud: "<?=$arResult["isCloud"] ? "Y" : "N"?>",
        isRusCloud: "<?=$arResult["isRusCloud"] ? "Y" : "N"?>",
        adminRightsRestricted: "<?=$arResult["adminRightsRestricted"] ? "Y" : "N"?>",
        delegateAdminRightsRestricted: "<?=$arResult["delegateAdminRightsRestricted"] ? "Y" : "N"?>",
        isFireUserEnabled: "<?=$arResult["isFireUserEnabled"] ? "Y" : "N"?>",
        profilePostData: {
            formId: '<?=(!empty($arResult["ProfileBlogPost"]["formParams"]) ? \CUtil::JSEscape($arResult["ProfileBlogPost"]["formParams"]["FORM_ID"]) : '')?>',
            lheId: '<?=(!empty($arResult["ProfileBlogPost"]["formParams"]) ? \CUtil::JSEscape($arResult["ProfileBlogPost"]["formParams"]["LHE"]["id"]) : '')?>'
        },
        initialFields: <?=CUtil::PhpToJSObject($arResult["User"])?>,
        gridId: '<?='INTRANET_USER_GRID_' . SITE_ID?>',
        isCurrentUserAdmin: '<?=$arResult["IS_CURRENT_USER_ADMIN"] ? "Y" : "N"?>',
        voximplantEnablePhones: <?=CUtil::PhpToJSObject($arResult["User"]["VOXIMPLANT_ENABLE_PHONES"])?>
    });
</script>
<script>
    (function () {
        var likes = document.querySelectorAll('[data-role="intranet-user-profile-column-block-title-like"]');

        for (var i = 0; i < likes.length; i++) {
            likes[i].addEventListener('click', function () {
                this.classList.add('intranet-user-profile-column-block-title-like-animate');

                this.addEventListener('animationend', function () {
                    this.classList.remove('intranet-user-profile-column-block-title-like-animate');
                })
            }, false);
        }
    })();

    BX.ready(function () {
        phonePersonalEdit();
    });
</script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleTeamBtn');
        const teamGrid = document.getElementById('teamGrid');
        const triangle = toggleBtn.querySelector('.triangle');

        toggleBtn.addEventListener('click', function () {
            // Переключаем класс active у сетки
            teamGrid.classList.toggle('active');

            // Переключаем класс rotated у стрелки (поворот на 180 градусов)
            triangle.classList.toggle('rotated');

            // Меняем текст кнопки (опционально, но логично)
            if (teamGrid.classList.contains('active')) {
                toggleBtn.innerHTML = 'Скрыть команду <span class="triangle rotated">▲</span>';
            } else {
                toggleBtn.innerHTML = 'Показать команду <span class="triangle">▲</span>';
            }
        });
    });

    $(document).ready(function () {
        // 1. Находим строку-маркер
        var $targetRow = $('[data-cid="PERSONAL_MOBILE"]');

        // Проверяем, существует ли она
        if ($targetRow.length > 0) {
            // 2. Собираем все элементы, которые идут ПОСЛЕ маркера (или включая его, если нужно)
            // В данном случае мы скрываем всё, что идет после Подразделения
            // .nextAll() выбирает все следующие сиблинги (соседние элементы)
            var $rowsToHide = $targetRow.nextAll();

            // Если вы хотите скрыть и само подразделение тоже, добавьте:
            // $rowsToHide = $rowsToHide.add($targetRow);

            if ($rowsToHide.length > 0) {
                // Добавляем класс для скрытия
                $rowsToHide.addClass('hidden-rows');

                // 3. Создаем кнопку "Показать/Скрыть"
                var $btn = $('<button id="toggle-details" class="toggle-btn">Показать подробности</button>');

                // Вставляем кнопку после последнего видимого элемента (или после маркера)
                // Для красоты вставим её после последнего скрытого элемента
                $rowsToHide.last().after($btn);

                // 4. Логика работы кнопки
                $btn.click(function () {
                    var $hiddenElements = $('.hidden-rows');

                    // Плавное отображение/скрытие
                    $hiddenElements.slideToggle(300);

                    // Меняем текст кнопки
                    if ($(this).text() === "Показать подробности") {
                        $(this).text("Скрыть подробности");
                    } else {
                        $(this).text("Показать подробности");
                    }
                });
            }
        }
    });
</script>
