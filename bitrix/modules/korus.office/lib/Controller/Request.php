<?php

namespace Korus\Office\Controller;

use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Error;
use Korus\Main\Controller\BaseController;

class Request extends BaseController
{
    public function configureActions()
    {
        $result = parent::configureActions();

        $result['markCommentViewed']['+prefilters'] = [
            new ActionFilter\HttpMethod(
                [ActionFilter\HttpMethod::METHOD_POST]
            )
        ];

        return $result;
    }

    public function markCommentViewedAction(int $requestId) : bool
    {
        $currentState = \CUserOptions::GetOption('korus.office', 'viewed_comments', []);
        $currentState[] = $requestId;

        $result = \CUserOptions::SetOption('korus.office', 'viewed_comments', $currentState);
        if ($result) {
            return true;
        }

        $this->addError(new Error('Неизвестная ошибка'));
        return false;
    }
}
