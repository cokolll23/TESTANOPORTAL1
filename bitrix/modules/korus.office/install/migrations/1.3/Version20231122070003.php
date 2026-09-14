<?php

namespace Sprint\Migration;


class Version20231122070003 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        $helper = $this->getHelperManager()->Iblock();

        $helper->deleteIblockType('badges');
    }

    public function down()
    {
        //your code ...
    }
}
