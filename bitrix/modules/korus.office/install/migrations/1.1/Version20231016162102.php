<?php

namespace Sprint\Migration;


class Version20231016162102 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Hlblock()->deleteField($helper->Hlblock()->getHlblockId('BusinessBadges'),'UF_ICON');
    }

    public function down()
    {
        //your code ...
    }
}
