<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Landing\Manager;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\UI\Buttons\Button;
use Bitrix\UI\Buttons\Color;
use Bitrix\UI\Toolbar\Facade\Toolbar;
use Korus\Main\Helpers\Layout;
use Korus\MainPage\Service\TagsService;
use Korus\MainPage\DTO\TagsType;
use Korus\News\NewsHelpers;
use Korus\News\Service\LandingNewsCounterService;

Loader::includeModule('korus.statistic');
Loader::includeModule('korus.news');
Loader::includeModule('korus.mainpage');
$tagsColor = [];
$landingUniqViews = 0;

if (Loader::includeModule("landing") && Loader::includeModule("iblock")) {
    CJSCore::Init(array("jquery3"));

    $landingPublicationPath = Manager::getPublicationPath(null, 's1');
    $mainLandingCode =  NewsHelpers::getMainLandingCodeBySiteId();
    $tagService = new TagsService;
    $tags = [];

    $landing = Bitrix\Landing\Landing::getList(array(
        'select'=> array('ID', 'VIEWS', 'CODE'),
        'filter' => array(
            'CODE' => $arResult["VARIABLES"]["CODE"],
            'CHECK_PERMISSIONS' => 'N'
        )
    ))->fetch();
pretty_print($landing);
    if($landing) {
        $arSelect = Array("ID", 'TAGS', 'PROPERTY_HIDE_COMMENTS');
        $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "PROPERTY_SITE_ID" => $landing["ID"]);
        $newsIBElement = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect)->Fetch();
        if (!$newsIBElement) {
            throw new \Exception(GetMessage("ELEMENT_IB_NOT_FOUND"));
        }
        $isCommentsHide = $newsIBElement['PROPERTY_HIDE_COMMENTS_VALUE'] == 'Y';

        if (!empty($newsIBElement['TAGS'])) {
            $tags = array_merge($tags, array_map('trim', explode(',', $newsIBElement['TAGS'])));
        }

        $tags = array_unique($tags);
        try {
            $tagsColor = array_column($tagService->getListColors($tags, TagsType::News), 'theme', 'text');;
        } catch (ObjectPropertyException|SystemException $e) {
        }
        unset($tags);
    }

    try {
        $landingNewsCounterService = new LandingNewsCounterService;

        $landingUniqViews = $landingNewsCounterService->getViewsByCode((string) $landing["CODE"]);
    } catch (Exception $e) {
    }

}
if($landing["ID"] && $newsIBElement['ID']) {
    $APPLICATION->IncludeComponent("korus:add.news.view", '', ['LANDING_ID' => $landing["ID"]], false);
    ?>
    <div class="landing-detail-news">
        <style>
            #uiToolbarStar { display: none; }
        </style>
        <?
        $APPLICATION->IncludeComponent(
            'bitrix:landing.pub',
            '',
            array(
                "LID" => $landing["ID"]
            ),
            null,
            array(
                'HIDE_ICONS' => 'Y'
            )
        );
        ?>
    </div>

    <div class="landing-detail-bottom">
        <?php if (!empty($newsIBElement['TAGS'])): ?>
            <section class="pgk-news-item-tags">
                <?php foreach (explode(',', $newsIBElement['TAGS']) as $tag) : ?>
                    <a href="<?= $arParams['SEF_FOLDER'] ?>?TAG=<?= trim($tag) ?>"
                       class="kt-tag kt-tag__<?=($tagsColor[trim($tag)])?>-20"><span class="kt-tag__content"><?= trim($tag) ?></span></a>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
        <div class="feed-post-informers">
            <div class="feed-post-informers-cont">
                <?php
                $APPLICATION->IncludeComponent(
                    "korus:likes",
                    "",
                    [
                        "ENTITY_TYPE_ID" => "IBLOCK_ELEMENT",
                        "ENTITY_ID" => $newsIBElement["ID"],
                        "OWNER_ID" => $arParams["USER_ID"],
                        "VIEW_COUNT" => $landingUniqViews,
                    ],
                    null,
                    ["HIDE_ICONS" => "Y"]
                );
                ?>
            </div>
        </div>

        <? if (!$isCommentsHide): ?>
            <div class="comments-wrapper">
                <div id="comments" class="comments-title"><?=Getmessage('REVIEWS')?></div>
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:forum.comments",
                    "",
                    Array(
                        "ALLOW_ALIGN" => "Y",
                        "ALLOW_ANCHOR" => "Y",
                        "ALLOW_BIU" => "Y",
                        "ALLOW_CODE" => "Y",
                        "ALLOW_FONT" => "Y",
                        "ALLOW_HTML" => "Y",
                        "ALLOW_IMG" => "Y",
                        "ALLOW_LIST" => "Y",
                        "ALLOW_MENTION" => "Y",
                        "ALLOW_NL2BR" => "Y",
                        "ALLOW_QUOTE" => "Y",
                        "ALLOW_SMILES" => "Y",
                        "ALLOW_TABLE" => "Y",
                        "ALLOW_VIDEO" => "Y",
                        "CACHE_TIME" => "0",
                        "CACHE_TYPE" => "A",
                        "COMPONENT_TEMPLATE" => "",
                        "DATE_TIME_FORMAT" => "d.m.Y H:i:s",
                        "EDITOR_CODE_DEFAULT" => "N",
                        "ENTITY_ID" => $newsIBElement['ID'],
                        "ENTITY_TYPE" => "NW",
                        "ENTITY_XML_ID" => "NEWS_" . $newsIBElement['ID'],
                        "FORUM_ID" => $arParams['FORUM_ID'],
                        "IMAGE_HTML_SIZE" => "0",
                        "IMAGE_SIZE" => "600",
                        "MESSAGES_PER_PAGE" => "3",
                        "NAME_TEMPLATE" => "",
                        "PAGE_NAVIGATION_TEMPLATE" => "",
                        "PREORDER" => "N",
                        "SHOW_MINIMIZED" => "N",
                        "SHOW_RATING" => "Y",
                        "SUBSCRIBE_AUTHOR_ELEMENT" => "N",
                        "URL_TEMPLATES_PROFILE_VIEW" => "/company/personal/user/#AUTHOR_ID#/",
                        "URL_TEMPLATES_READ" => "",
                        "USER_FIELDS" => array(
                            0 => "UF_FORUM_MESSAGE_DOC",
                            1 => "UF_FORUM_MESSAGE_VER",
                            2 => "UF_FORUM_MES_URL_PRV",
                        ),
                        "USE_CAPTCHA" => "Y",
                        'LHE' => [
                            'copilotParams' => [],
                            'isCopilotEnabled' => false,
                        ],
                    )
                );?>
            </div>
        <? endif; ?>
    </div>

    <script>
        function leaveComment() {
            var el = $('.comments-wrapper').find('[id^="NEWS_<?= $newsIBElement['ID'] ?>"]');

            BX.onCustomEvent(el[0], 'onReply', []);
        }

        function isValidUrl(string) {
            try {
                const url = new URL(string);
                return url.protocol === "http:" || url.protocol === "https:";
            } catch (_) {
                return false;
            }
        }

        $(document).ready(function () {
            $('.landing-detail-news a').each(function () {
                if (isValidUrl($(this).attr('href'))) {
                    let aUrl = new URL($(this).attr('href'));

                    if (aUrl.pathname === '<?=$landingPublicationPath?>') {
                        aUrl.pathname = '<?=$arResult['FOLDER']?><?=$mainLandingCode?>/';
                    } else if (aUrl.pathname.indexOf('<?=$landingPublicationPath?>') !== -1) {
                        aUrl.pathname = aUrl.pathname.replace('<?=$landingPublicationPath?>', '<?=$arResult['FOLDER']?>');
                    }

                    $(this).attr('href', aUrl.href);
                }
            });
        })
    </script>
    <?php
}

Toolbar::addButton(Button::create([
    "link" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
    'text' => GetMessage("T_NEWS_DETAIL_BACK"),
    'color' => Color::LIGHT_BORDER,
]));
