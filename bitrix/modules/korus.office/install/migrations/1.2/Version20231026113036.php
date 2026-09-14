<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use Korus\Main\Loader;
use Korus\Office\Entity\OfficeFieldSettingsTable;

class Version20231026113036 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.3.2";

    public function up()
    {
        Loader::includeModule('korus.office');
        $connection = Application::getConnection();
        if(!$connection->isTableExists(OfficeFieldSettingsTable::getTableName())) {
            OfficeFieldSettingsTable::getEntity()->createDbTable();
        }
    }

    public function down()
    {
        Loader::includeModule('korus.office');
        $connection = Application::getConnection();
        $tableName = OfficeFieldSettingsTable::getTableName();
        if($connection->isTableExists($tableName)) {
            $connection->dropTable($tableName);
        }
    }
}
