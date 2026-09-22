<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// GetMessage("IDEA_STATUS_NEW"); GetMessage("IDEA_STATUS_PROCESSING"); GetMessage("IDEA_STATUS_COMPLETED");

pretty_print($arResult);
if(!empty($arResult["OK_MESSAGE"]))
{
    ?>
    <div class="blog-notes blog-note-box">
        <div class="blog-note-text">
            <ul>
                <?
                foreach($arResult["OK_MESSAGE"] as $v)
                {
                    ?>
                    <li><?=$v?></li>
                    <?
                }
                ?>
            </ul>
        </div>
    </div>
    <?
}
if(!empty($arResult["MESSAGE"]))
{
    ?>
    <div class="blog-textinfo blog-note-box">
        <div class="blog-textinfo-text">
            <ul>
                <?
                foreach($arResult["MESSAGE"] as $v)
                {
                    ?>
                    <li><?=$v?></li>
                    <?
                }
                ?>
            </ul>
        </div>
    </div>
    <?
}
if(!empty($arResult["ERROR_MESSAGE"]))
{
    ?>
    <div class="blog-errors blog-note-box blog-note-error">
        <div class="blog-error-text">
            <ul>
                <?
                foreach($arResult["ERROR_MESSAGE"] as $v)
                {
                    ?>
                    <li><?=$v?></li>
                    <?
                }
                ?>
            </ul>
        </div>
    </div>
    <?
}
?>
<?php

Bitrix\Main\UI\Extension::load([
        'main.core',
        'ui.buttons',
        'ui.entity-selector',
        'ui.notification',
]);
?>
<style>
    .idea-moderation {
        margin-top: 16px;
    }

    .idea-moderation__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .idea-moderation__users {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
    }

    .idea-moderation__user {
        display: inline-flex;
        align-items: center;
        max-width: 100%;
        padding: 5px 8px 5px 10px;
        border: 1px solid #d5d9dc;
        border-radius: 16px;
        background: #f5f7f8;
        color: #333;
        font-size: 13px;
        line-height: 18px;
    }

    .idea-moderation__user-name {
        overflow: hidden;
        max-width: 240px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .idea-moderation__user-remove {
        width: 20px;
        height: 20px;
        margin-left: 5px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: transparent;
        color: #828b95;
        font-size: 18px;
        line-height: 18px;
        cursor: pointer;
    }

    .idea-moderation__user-remove:hover {
        background: #e5e8eb;
        color: #333;
    }

    .idea-moderation__status {
        min-height: 18px;
        margin-top: 8px;
        font-size: 13px;
    }

    .idea-moderation__status--success {
        color: #2f8b23;
    }

    .idea-moderation__status--error {
        color: #d0021b;
    }
</style>
<div id="idea-posts-content">
    <?
    if(count($arResult["POST"] ?? [])>0)
    {
        $arStatusList = CIdeaManagment::getInstance()->Idea()->GetStatusList();
        foreach($arResult["POST"] as $CurPost)
        {
            ?><div id="blog-post-<?=$CurPost["ID"]?>"><?
            if($arParams["SHOW_RATING"] == "Y"):?>
                <div class="idea-rating-block">
			<span class="idea-rating-block-left">
				<span class="idea-rating-block-right">
					<span class="idea-rating-block-content idea-rating-block-content-ext-<?=$arParams['RATING_TEMPLATE']?>">
						<span class="idea-rating-block-content-description"><?=GetMessage("IDEA_RATING_TITLE");?>:</span>
						<?$APPLICATION->IncludeComponent(
                                "bitrix:rating.vote", $arParams['RATING_TEMPLATE'],
                                Array(
                                        "VOTE_AVAILABLE" => $CurPost["DISABLE_VOTE"]?"N":"Y",
                                        "ENTITY_TYPE_ID" => "BLOG_POST",
                                        "ENTITY_ID" => $CurPost["ID"],
                                        "OWNER_ID" => $CurPost["arUser"]["ID"],
                                        "USER_VOTE" => $arResult["RATING"][$CurPost["ID"]]["USER_VOTE"],
                                        "USER_HAS_VOTED" => $arResult["RATING"][$CurPost["ID"]]["USER_HAS_VOTED"],
                                        "TOTAL_VOTES" => $arResult["RATING"][$CurPost["ID"]]["TOTAL_VOTES"],
                                        "TOTAL_POSITIVE_VOTES" => $arResult["RATING"][$CurPost["ID"]]["TOTAL_POSITIVE_VOTES"],
                                        "TOTAL_NEGATIVE_VOTES" => $arResult["RATING"][$CurPost["ID"]]["TOTAL_NEGATIVE_VOTES"],
                                        "TOTAL_VALUE" => $arResult["RATING"][$CurPost["ID"]]["TOTAL_VALUE"],
                                        "PATH_TO_USER_PROFILE" => $arParams["AR_RESULT"]["PATH_TO_USER"],
                                ),
                                false,
                                array("HIDE_ICONS" => "Y")
                        );?>
					</span>
				</span>
			</span>
                </div>
            <?endif;
            $status = GetMessage("IDEA_STATUS_".mb_strtoupper($arStatusList[$CurPost["POST_PROPERTIES"]["DATA"]["UF_STATUS"]["VALUE"]]["XML_ID"]));
            if($status == '')
                $status = $arStatusList[$CurPost["POST_PROPERTIES"]["DATA"]["UF_STATUS"]["VALUE"]]["VALUE"];
            ?>
            <div class="blog-qtl<?if(in_array($CurPost["PUBLISH_STATUS"], array(BLOG_PUBLISH_STATUS_READY, BLOG_PUBLISH_STATUS_DRAFT))):?> blog-post-hidden<?endif;?>">
                <div class="blog-qtr">
                    <div class="blog-idea-body">
                        <div class="idea-owner">
                            <div class="bx-idea-condition-description status-color-<?=mb_strtolower($arStatusList[$CurPost["POST_PROPERTIES"]["DATA"]["UF_STATUS"]["VALUE"]]["XML_ID"]);?>">
                                <div <?if($arResult["IDEA_MODERATOR"]):?>class="status-action idea-action-cursor" onclick="JSPublicIdea.ShowStatusDialog(this, '<?=$CurPost["ID"]?>')" id="status-<?=$CurPost["ID"]?>"<?endif;?>><?=htmlspecialcharsbx($status)?></div>
                            </div>
                            <?=GetMessage("IDEA_INTRODUCED_TITLE")?> <img class="idea-user-avatar" src="<?=$arResult["AUTHOR_AVATAR"][$CurPost["arUser"]["ID"]]["src"]?>" align="top">
                            <?if (COption::GetOptionString("blog", "allow_alias", "Y") == "Y" && array_key_exists("ALIAS", $CurPost["BlogUser"]) && $CurPost["BlogUser"]["ALIAS"] <> '')
                                $arTmpUser = array(
                                        "NAME" => "",
                                        "LAST_NAME" => "",
                                        "SECOND_NAME" => "",
                                        "LOGIN" => "",
                                        "NAME_LIST_FORMATTED" => $CurPost["BlogUser"]["~ALIAS"]);
                            elseif ($CurPost["urlToAuthor"] <> '')
                                $arTmpUser = array(
                                        "NAME" => $CurPost["arUser"]["~NAME"],
                                        "LAST_NAME" => $CurPost["arUser"]["~LAST_NAME"],
                                        "SECOND_NAME" => $CurPost["arUser"]["~SECOND_NAME"],
                                        "LOGIN" => $CurPost["arUser"]["~LOGIN"],
                                        "NAME_LIST_FORMATTED" => "",
                                );
                            ?><noindex>
                                <?$APPLICATION->IncludeComponent("bitrix:main.user.link",
                                        '',
                                        array(
                                                "ID" => $CurPost["arUser"]["ID"],
                                                "HTML_ID" => "blog_blog_".$CurPost["arUser"]["ID"],
                                                "NAME" => $arTmpUser["NAME"],
                                                "LAST_NAME" => $arTmpUser["LAST_NAME"],
                                                "SECOND_NAME" => $arTmpUser["SECOND_NAME"],
                                                "LOGIN" => $arTmpUser["LOGIN"],
                                                "NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
                                                "USE_THUMBNAIL_LIST" => "N",
                                                "PROFILE_URL" => $CurPost["urlToAuthor"],
                                            //"PROFILE_URL_LIST" => $CurPost["urlToBlog"],
                                                "PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
                                                "PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
                                                "DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
                                                "SHOW_YEAR" => $arParams["SHOW_YEAR"],
                                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                "NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
                                                "SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
                                                "PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
                                                "PATH_TO_SONET_USER_PROFILE" => $arParams["PATH_TO_USER"],
                                                "INLINE" => "Y",
                                                "SEO_USER" => "Y"
                                        ),
                                        false,
                                        array("HIDE_ICONS" => "Y")
                                );
                                ?>
                            </noindex>
                            <?=$CurPost["DATE_PUBLISH_FORMATED"]?>
                        </div>

                        <?  pretty_print($CurPost);?>



                        <div class="post-title"><h2>
                                <a href="<?=$CurPost["urlToPost"]?>" title="<?=$CurPost["TITLE"]?>"><?=$CurPost["TITLE"]?></a></h2>
                        </div>
                        <!-- Пример уникального контейнера для каждого поста -->
                        <?php
                        $postId = (int)$CurPost['ID'];
                        ?>

                        <div
                                class="idea-moderation"
                                data-role="idea-moderation"
                                data-post-id="<?= $postId ?>"
                        >
                            <div
                                    class="idea-moderation__users"
                                    data-role="selected-users"
                                    aria-live="polite"
                            ></div>
                            <div class="idea-moderation__actions">
                                <button
                                        type="button"
                                        class="ui-btn ui-btn-light-border ui-btn-sm"
                                        data-role="select-moderators"
                                >
                                    Выбрать модераторов
                                </button>

                                <button
                                        type="button"
                                        class="ui-btn ui-btn-primary ui-btn-sm"
                                        data-role="create-tasks"
                                        data-author='<?= $CurPost["AUTHOR_ID"] ?>'
                                        disabled
                                >
                                    Создать задачи
                                </button>
                            </div>



                            <div
                                    class="idea-moderation__status"
                                    data-role="status"
                                    aria-live="polite"
                            ></div>
                        </div>
                        <div class="idea-post-content"><?=$CurPost["TEXT_FORMATED"]?><?


                            if ($CurPost["CUT"] == "Y")
                            {
                                ?><p><a class="blog-postmore-link" href="<?=$CurPost["urlToPost"]?>"><?=GetMessage("BLOG_BLOG_BLOG_MORE")?></a></p><?
                            }
                            if($CurPost["POST_PROPERTIES"]["SHOW"] == "Y" && false)
                            {
                                ?><p><?
                                foreach ($CurPost["POST_PROPERTIES"]["DATA"] as $arPostField)
                                {
                                    if(!empty($arPostField["VALUE"]))
                                    {
                                        ?><b><?=$arPostField["EDIT_FORM_LABEL"]?>:</b>&nbsp;<?$APPLICATION->IncludeComponent(
                                            "bitrix:system.field.view",
                                            $arPostField["USER_TYPE"]["USER_TYPE_ID"],
                                            array("arUserField" => $arPostField), null, array("HIDE_ICONS"=>"Y"));
                                        ?><br /><?
                                    }
                                }
                                ?></p><?
                            }
                            ?></div><?

                        if (!empty($CurPost["POST_PROPERTIES"]["DATA"][CBlogPost::UF_NAME])):
                            $eventHandlerID = false;
                            $eventHandlerID = AddEventHandler("main", "system.field.view.file", Array("CBlogTools", "blogUFfileShow"));
                            $blogPostDoc = $CurPost["POST_PROPERTIES"]["DATA"][CBlogPost::UF_NAME];
                            if (!empty($blogPostDoc["VALUE"])): ?>
                                <div class="blog-post-files">
                                    <?$APPLICATION->IncludeComponent(
                                            "bitrix:system.field.view",
                                            $blogPostDoc["USER_TYPE"]["USER_TYPE_ID"],
                                            array("arUserField" => $blogPostDoc), null, array("HIDE_ICONS"=>"N"));?>
                                </div>
                            <? endif;
                            if ($eventHandlerID !== false && (intval($eventHandlerID) > 0))
                                RemoveEventHandler("main", "system.field.view.file", $eventHandlerID);
                        endif;

                        if(!empty($CurPost["urlToHide"]) || !empty($CurPost["urlToShow"]) ||
                                !empty($CurPost["urlToEdit"]) || !empty($CurPost["urlToDelete"])):?>
                            <div class="idea-post-meta">
                                <div class="idea-post-meta-util">
                                    <?if($CurPost["urlToHide"] <> ''):?>
                                        <a href="<?=$CurPost["urlToHide"]?>" onclick="if(confirm('<?=GetMessageJS("BLOG_MES_HIDE_POST_CONFIRM")?>')){this.href+='&sessid='+BX.bitrix_sessid(); return true;}return false;"><span class="idea-post-link-caption"><?=GetMessage("BLOG_MES_HIDE")?></span></a>
                                    <?elseif($CurPost["urlToShow"] <> ''):?>
                                        <a href="<?=$CurPost["urlToShow"]?>" onclick="if(confirm('<?=GetMessageJS("IDEA_MES_SHOW_POST_CONFIRM")?>')){this.href+='&sessid='+BX.bitrix_sessid(); return true;}return false;"><span class="idea-post-link-caption"><?=GetMessage("IDEA_MES_SHOW")?></span></a>
                                    <?endif;?>
                                    <?if($CurPost["urlToEdit"] <> ''):?>
                                        <a href="<?=$CurPost["urlToEdit"]?>"><span class="idea-post-link-caption"><?=GetMessage("BLOG_MES_EDIT")?></span></a>
                                    <?endif;?>
                                    <?if($CurPost["urlToDelete"] <> ''):?>
                                        <a href="<?=$CurPost["urlToDelete"]?>" onclick="if(confirm('<?=GetMessageJS("BLOG_MES_DELETE_POST_CONFIRM")?>')){this.href+='&sessid='+BX.bitrix_sessid(); return true;}return false;"><span class="idea-post-link-caption"><?=GetMessage("BLOG_MES_DELETE")?></span></a>
                                    <?endif;?>
                                </div>
                                <br clear="both"/>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>
            <div>
                <?if($CurPost["IS_DUPLICATE"] !== false):?>
                    <div class="blog-comments-duplicate">
                        <div class="blog-comment-line-duplicate"></div>
                        <div class="blog-comment-duplicate">
                            <?=GetMessage("IDEA_POST_DUPLICATE", array("#LINK#" => $CurPost["IS_DUPLICATE"]))?>
                        </div>
                    </div>
                <?endif;?>
                <?$cntOfficial = 0;
                if(!empty($CurPost["OFFICIAL_POST_ID"])):
                    $arOfficialComments = array("ID"=>$CurPost["OFFICIAL_POST_ID"]);
                    ?><?$cntOfficial = $APPLICATION->IncludeComponent(
                        "bitrix:idea.comment.list",
                        "official_list",
                        Array(
                                "RATING_TEMPLATE" => $arParams['RATING_TEMPLATE'],
                                "FILTER" => $arOfficialComments,
                                "BLOG_VAR"		=> $arParams["AR_RESULT"]["ALIASES"]["blog"],
                                "USER_VAR"		=> $arParams["AR_RESULT"]["ALIASES"]["user_id"],
                                "PAGE_VAR"		=> $arParams["AR_RESULT"]["ALIASES"]["page"],
                                "POST_VAR"			=> $arParams["AR_RESULT"]["ALIASES"]["post_id"],
                                "PATH_TO_BLOG"	=> $arParams["AR_RESULT"]["PATH_TO_BLOG"],
                                "PATH_TO_POST"	=> $arParams["AR_RESULT"]["PATH_TO_POST"],
                                "PATH_TO_USER"	=> $arParams["AR_RESULT"]["PATH_TO_USER"],
                                "PATH_TO_SMILE"	=> $arParams["AR_RESULT"]["PATH_TO_SMILE"],
                                "BLOG_URL"		=> $arParams["AR_RESULT"]["VARIABLES"]["blog"],
                                "ID"			=> $CurPost["ID"],
                                "CACHE_TYPE"	=> $arParams["AR_RESULT"]["CACHE_TYPE"],
                                "CACHE_TIME"	=> $arParams["AR_RESULT"]["CACHE_TIME"],
                                "COMMENTS_COUNT" => 1000, //unlimited by logic
                                "DATE_TIME_FORMAT"	=> $arParams["AR_RESULT"]["DATE_TIME_FORMAT"],
                                "USE_ASC_PAGING"	=> $arParams["AR_PARAMS"]["USE_ASC_PAGING"],
                                "NOT_USE_COMMENT_TITLE"	=> $arParams["AR_PARAMS"]["NOT_USE_COMMENT_TITLE"],
                                "GROUP_ID" 			=> $arParams["AR_PARAMS"]["GROUP_ID"],
                                "NAME_TEMPLATE" => $arParams["AR_PARAMS"]["NAME_TEMPLATE"],
                                "SHOW_LOGIN" => $arParams["AR_PARAMS"]["SHOW_LOGIN"],
                                "PATH_TO_CONPANY_DEPARTMENT" => $arParams["AR_PARAMS"]["PATH_TO_CONPANY_DEPARTMENT"],
                                "PATH_TO_SONET_USER_PROFILE" => $arParams["AR_PARAMS"]["PATH_TO_SONET_USER_PROFILE"],
                                "PATH_TO_MESSAGES_CHAT" => $arParams["AR_PARAMS"]["PATH_TO_MESSAGES_CHAT"],
                                "PATH_TO_VIDEO_CALL" => $arParams["AR_PARAMS"]["PATH_TO_VIDEO_CALL"],
                                "SHOW_RATING" => $arParams["AR_PARAMS"]["SHOW_RATING"],
                                "SMILES_COUNT" => $arParams["AR_PARAMS"]["SMILES_COUNT"],
                                "IMAGE_MAX_WIDTH" => $arParams["AR_PARAMS"]["IMAGE_MAX_WIDTH"],
                                "IMAGE_MAX_HEIGHT" => $arParams["AR_PARAMS"]["IMAGE_MAX_HEIGHT"],
                                "EDITOR_RESIZABLE" => $arParams["AR_PARAMS"]["COMMENT_EDITOR_RESIZABLE"],
                                "EDITOR_DEFAULT_HEIGHT" => $arParams["AR_PARAMS"]["COMMENT_EDITOR_DEFAULT_HEIGHT"],
                                "EDITOR_CODE_DEFAULT" => $arParams["AR_PARAMS"]["COMMENT_EDITOR_CODE_DEFAULT"],
                                "ALLOW_VIDEO" => $arParams["AR_PARAMS"]["COMMENT_ALLOW_VIDEO"],
                                "ALLOW_POST_CODE" => $arParams["AR_PARAMS"]["ALLOW_POST_CODE"],
                                "SHOW_SPAM" => $arParams["AR_PARAMS"]["SHOW_SPAM"],
                                "NO_URL_IN_COMMENTS" => $arParams["AR_PARAMS"]["NO_URL_IN_COMMENTS"],
                                "NO_URL_IN_COMMENTS_AUTHORITY" => $arParams["AR_PARAMS"]["NO_URL_IN_COMMENTS_AUTHORITY"],
                                "POST_BIND_USER" => $arParams["AR_PARAMS"]["POST_BIND_USER"],
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                );?><?
                    $cntOfficial = intval($cntOfficial);
                endif;?>
                <div class="tag-tl">
                    <div class="tag-tr">
                        <div class="tag-block">
                            <div class="tag-line">
							<span class="main-tag-category"><?
                                if($CurPost["IDEA_CATEGORY"]["NAME"]!==false)
                                {
                                    if($CurPost["IDEA_CATEGORY"]["LINK"]===false)
                                        echo $CurPost["IDEA_CATEGORY"]["NAME"];
                                    else
                                    {
                                        ?><a href="<?=$CurPost["IDEA_CATEGORY"]["LINK"];?>"><?=$CurPost["IDEA_CATEGORY"]["NAME"];?></a><?
                                    }
                                }?></span>
                                <?if(!empty($CurPost["CATEGORY"]))
                                {
                                    $skipFirst = true;
                                    ?><span class="tag-marker"></span><?
                                    foreach($CurPost["CATEGORY"] as $v)
                                    {
                                        if (!$skipFirst) echo ', ';
                                        ?><a href="<?=$v["urlToCategory"]?>" rel="nofollow"><?=$v["NAME"]?></a><?
                                        $skipFirst = false;
                                    }
                                }
                                ?>
                            </div>
                            <span class="post-comment">(<a href="<?=$CurPost["urlToPost"]?>#comments"><?=GetMessage("IDEA_POST_COMMENT_CNT")?>: <?=(intval($CurPost["NUM_COMMENTS"]) - $cntOfficial);?></a>)</span>
                            <br style="clear:both;" />
                        </div>
                    </div>
                </div>
                <div class="tag-tbl"><div class="tag-tbr"><div class="tag-tbb"></div></div></div>
            </div>
            <div class="bottom-space"></div>
            </div>
            <?
        }
        ?><?=$arResult["NAV_STRING"];?>

        <?
    }
    elseif(!empty($arResult["BLOG"]))
    {
        ?><div class="blog-post-current">
        <div class="blog-errors blog-note-box blog-textinfo">
            <div class="blog-error-text"><?=GetMessage("BLOG_BLOG_BLOG_NO_AVAIBLE_MES");?></div>
        </div>
        </div><?
    }
    ?>
</div>
<script>
    (function () {
        'use strict';

        const AJAX_URL = '/local/ajax/idea/create-tasks.php';

        function initIdeaModeration(context) {
            const root = context || document;

            root.querySelectorAll('[data-role="idea-moderation"]').forEach(function (container) {
                if (container.dataset.initialized === 'Y') {
                    return;
                }

                container.dataset.initialized = 'Y';

                const postId = parseInt(container.dataset.postId, 10);
                const selectButton = container.querySelector(
                    '[data-role="select-moderators"]'
                );
                const createButton = container.querySelector(
                    '[data-role="create-tasks"]'
                );
                const usersContainer = container.querySelector(
                    '[data-role="selected-users"]'
                );
                const statusContainer = container.querySelector(
                    '[data-role="status"]'
                );

                if (
                    !postId ||
                    !selectButton ||
                    !createButton ||
                    !usersContainer ||
                    !statusContainer
                ) {
                    return;
                }

                /*
                 * Ключ — строковый ID пользователя.
                 *
                 * Значение:
                 * {
                 *     id: 123,
                 *     title: 'Иван Иванов'
                 * }
                 */
                const selectedUsers = new Map();

                let dialog = null;
                let requestInProgress = false;

                function clearStatus() {
                    statusContainer.textContent = '';
                    statusContainer.classList.remove(
                        'idea-moderation__status--success',
                        'idea-moderation__status--error'
                    );
                }

                function showStatus(message, type) {
                    statusContainer.textContent = message || '';
                    statusContainer.classList.remove(
                        'idea-moderation__status--success',
                        'idea-moderation__status--error'
                    );

                    if (type === 'success') {
                        statusContainer.classList.add(
                            'idea-moderation__status--success'
                        );
                    }

                    if (type === 'error') {
                        statusContainer.classList.add(
                            'idea-moderation__status--error'
                        );
                    }
                }

                function updateCreateButton() {
                    createButton.disabled =
                        requestInProgress || selectedUsers.size === 0;
                }

                function renderSelectedUsers() {
                    usersContainer.replaceChildren();

                    selectedUsers.forEach(function (user) {
                        const userElement = document.createElement('span');
                        userElement.className = 'idea-moderation__user';

                        const nameElement = document.createElement('span');
                        nameElement.className = 'idea-moderation__user-name';
                        nameElement.textContent = user.title;

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className =
                            'idea-moderation__user-remove';
                        removeButton.dataset.userId = String(user.id);
                        removeButton.title = 'Удалить пользователя';
                        removeButton.setAttribute(
                            'aria-label',
                            'Удалить ' + user.title
                        );
                        removeButton.textContent = '×';

                        userElement.append(nameElement, removeButton);
                        usersContainer.append(userElement);
                    });

                    updateCreateButton();
                }

                function addSelectedItem(item) {
                    const userId = item.getId();

                    selectedUsers.set(String(userId), {
                        id: userId,
                        title:
                            item.getTitle() ||
                            'Пользователь #' + String(userId)
                    });

                    clearStatus();
                    renderSelectedUsers();
                }

                function removeSelectedItem(item) {
                    selectedUsers.delete(String(item.getId()));

                    clearStatus();
                    renderSelectedUsers();
                }

                function getDialog() {
                    if (dialog) {
                        return dialog;
                    }

                    dialog = new BX.UI.EntitySelector.Dialog({
                        id: 'idea-moderators-' + postId,
                        context: 'IDEA_MODERATORS_' + postId,
                        targetNode: selectButton,
                        multiple: true,
                        enableSearch: true,
                        dropdownMode: false,
                        entities: [
                            {
                                id: 'user',
                                options: {
                                    inviteEmployeeLink: false
                                }
                            }
                        ],
                        events: {
                            'Item:onSelect': function (event) {
                                const data = event.getData();
                                addSelectedItem(data.item);
                            },

                            'Item:onDeselect': function (event) {
                                const data = event.getData();
                                removeSelectedItem(data.item);
                            }
                        }
                    });

                    return dialog;
                }

                selectButton.addEventListener('click', function () {
                    clearStatus();
                    getDialog().show();
                });

                usersContainer.addEventListener('click', function (event) {
                    const removeButton = event.target.closest(
                        '[data-user-id]'
                    );

                    if (!removeButton) {
                        return;
                    }

                    const userId = removeButton.dataset.userId;
                    const entityDialog = getDialog();

                    const item = entityDialog.getItem({
                        entityId: 'user',
                        id: userId
                    });

                    if (item && item.isSelected()) {
                        /*
                         * Вызовет событие Item:onDeselect,
                         * где пользователь будет удалён из Map.
                         */
                        item.deselect();
                    } else {
                        /*
                         * Резервный вариант, если элемент не найден
                         * в EntitySelector.
                         */
                        selectedUsers.delete(String(userId));
                        renderSelectedUsers();
                    }
                });

                createButton.addEventListener('click', function () {
                    if (requestInProgress || selectedUsers.size === 0) {
                        return;
                    }
                    const author=$(this).data('author');

                    const moderatorIds = Array.from(
                        selectedUsers.values()
                    ).map(function (user) {
                        return user.id;
                    });

                    requestInProgress = true;
                    createButton.classList.add('ui-btn-wait');
                    updateCreateButton();
                    clearStatus();

                    BX.ajax({
                        url: AJAX_URL,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            sessid: BX.bitrix_sessid(),
                            postId: postId,
                            author: author,
                            moderatorIds: moderatorIds
                        },

                        onsuccess: function (response) {
                            requestInProgress = false;
                            createButton.classList.remove('ui-btn-wait');
                            updateCreateButton();

                            if (!response || response.success !== true) {
                                showStatus(
                                    response && response.message
                                        ? response.message
                                        : 'Не удалось создать задачи.',
                                    'error'
                                );
                                return;
                            }

                            const createdCount = response.createdCount || 0;
                            const failedCount = response.failedCount || 0;

                            if (failedCount > 0) {
                                showStatus(
                                    'Создано задач: ' +
                                    createdCount +
                                    '. Не создано: ' +
                                    failedCount +
                                    '.',
                                    'error'
                                );
                            } else {
                                showStatus(
                                    'Задачи успешно созданы: ' +
                                    createdCount +
                                    '.',
                                    'success'
                                );
                            }

                            /*
                             * Выбранные пользователи намеренно не очищаются.
                             * Они остаются возле кнопок.
                             *
                             * Если после создания задач список нужно очистить,
                             * можно вызвать:
                             *
                             * getDialog().deselectAll();
                             */
                        },

                        onfailure: function () {
                            requestInProgress = false;
                            createButton.classList.remove('ui-btn-wait');
                            updateCreateButton();

                            showStatus(
                                'Ошибка соединения при создании задач.',
                                'error'
                            );
                        }
                    });
                });

                renderSelectedUsers();
            });
        }

        BX.ready(function () {
            initIdeaModeration(document);
        });

        /*
         * Метод можно вызвать после динамической загрузки новых постов:
         *
         * BX.IdeaModeration.init(контейнерСНовымиПостами);
         */
        BX.IdeaModeration = {
            init: initIdeaModeration
        };
    })();
</script>