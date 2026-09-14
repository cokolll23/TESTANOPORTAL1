<?php

namespace Korus\Office\Controller;

use Bitrix\Main\Engine\Response\Component;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Korus\Main\Controller\BaseController;

class Components extends BaseController
{
    /**
     * Возвращает отрендеренный битриксовый редактор
     *
     * @param string $name
     * @return Component|null
     */
    public function getEditorAction(string $name) : ?Component
    {
        try {
            global $USER_FIELD_MANAGER;

            $postFieldsList = $USER_FIELD_MANAGER->getUserFields("BLOG_POST", 0, LANGUAGE_ID);
            $ufBlogPostFile = $postFieldsList['UF_BLOG_POST_FILE'];

            $settings = [
                "FORM_ID" => $name,
                "SHOW_MORE" => "Y",
                "PARSER" => [
                    "Bold",
                    "Italic",
                    "Underline",
                    "Strike",
                    "ForeColor",
                    "FontList",
                    "FontSizeList",
                    "RemoveFormat",
                    "Quote",
                    "Code",
                    "CreateLink",
                    "Image",
                    "UploadFile",
                    "InputVideo",
                    "Table",
                    "Justify",
                    "InsertOrderedList",
                    "InsertUnorderedList",
                    "Source",
                    "MentionUser",
                    "Spoiler"
                ],
                "BUTTONS" => [
                    "UploadFile",
                    "CreateLink",
                    "InputVideo",
                    "Quote",
                    "MentionUser"
                ],
                "TEXT" => [
                    "NAME" => "profilepost",
                    "VALUE" => "",
                    "HEIGHT" => "80px"
                ],
                "UPLOAD_FILE" => $ufBlogPostFile,
                "UPLOAD_WEBDAV_ELEMENT" => [],
                "UPLOAD_FILE_PARAMS" => [
                    "width" => 400,
                    "height" => 400
                ],
                "FILES" => [
                    "VALUE" => [],
                    "DEL_LINK" => false,
                    "SHOW" => "N"
                ],
                "SMILES" => (Loader::includeModule('blog') ? \CBlogSmile::getSmilesList() : []),
                "LHE" => [
                    "id" => "id" . $name,
                    "documentCSS" => "body {color:#434343;}",
                    "iframeCss" => "html body {padding-left: 14px!important; font-size: 13px!important; line-height: 18px!important;}",
                    "ctrlEnterHandler" => "__logSubmitCommentForm" . $name,
                    "fontFamily" => "'Helvetica Neue', Helvetica, Arial, sans-serif",
                    "fontSize" => "12px",
                    "bInitByJS" => false,
                    "height" => 80
                ],
                "PROPERTIES" => [
                    ['ELEMENT_ID' => 'url_preview_' . $name]
                ],
                "SELECTOR_VERSION" => 2,
                "HIDE_CHECKBOX_ALLOW_EDIT" => 'Y'
            ];

            return new Component('bitrix:main.post.form', '', $settings);
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }

    public function getProfileMenuAction(int $profileId) : ?Component
    {
        try {
            $params = [
                'ID' => $profileId,
                'PAGE_ID' => 'user',
                'NO_BUFFER' => true,
                'USE_MAIN_MENU' => false,
                'PATH_TO_USER' => '/company/personal/user/#user_id#/',
                'PATH_TO_USER_EDIT' => '/company/personal/user/#user_id#/edit/',
                'PATH_TO_USER_FRIENDS' => '/company/personal/user/#user_id#/friends/',
                'PATH_TO_USER_FRIENDS_ADD' => '/company/personal/user/#user_id#/friends/add/',
                'PATH_TO_USER_FRIENDS_DELETE' => '/company/personal/user/#user_id#/friends/delete/',
                'PATH_TO_USER_GROUPS' => '/company/personal/user/#user_id#/groups/',
                'PATH_TO_MESSAGE_FORM' => '/company/personal/messages/form/#user_id#/',
                'PATH_TO_MESSAGES_INPUT' => '/company/personal/messages/input/',
                'PATH_TO_USER_BLOG' => '/company/personal/user/#user_id#/blog/',
                'PATH_TO_USER_PHOTO' => '/company/personal/user/#user_id#/photo/',
                'PATH_TO_USER_FORUM' => '/company/personal/user/#user_id#/forum/',
                'PATH_TO_USER_CALENDAR' => '/company/personal/user/#user_id#/calendar/',
                'PATH_TO_USER_FILES' => '/company/personal/user/#user_id#/files/lib/#path#',
                'PATH_TO_USER_DISK' => '/company/personal/user/#user_id#/disk/path/#PATH#',
                'PATH_TO_USER_TASKS' => '/company/personal/user/#user_id#/tasks/',
                'PATH_TO_USER_CONTENT_SEARCH' => '/company/personal/user/#user_id#/search/',
                'PATH_TO_LOG' => '/company/personal/log/',
            ];

            return new Component('bitrix:socialnetwork.user_menu', '', $params);
        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage(), $e->getCode()));
            return null;
        }
    }
}
