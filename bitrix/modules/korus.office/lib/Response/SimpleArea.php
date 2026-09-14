<?php

namespace Korus\Office\Response;


use Bitrix\Main\Engine\Response\ContentArea\ContentAreaInterface;

class SimpleArea implements ContentAreaInterface
{
    private string $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function getHtml()
    {
        return $this->html;
    }
}