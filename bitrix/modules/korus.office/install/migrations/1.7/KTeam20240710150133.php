<?php

declare(strict_types=1);

namespace Sprint\Migration;

class KTeam20240710150133 extends Version
{
    protected $description = "add default widgets";
    protected $moduleVersion = "4.6.2";

    /**
     * @return void
     * @throws Exceptions\RestartException
     * @throws Exceptions\MigrationException
     */
    public function up(): void
    {
        $this->getExchangeManager()
            ->IblockElementsImport()
            ->setExchangeResource('iblock_elements.xml')
            ->setLimit(20)
            ->execute(function ($item) {
                $this->getHelperManager()
                    ?->Iblock()
                    ->saveElement(
                        $item['iblock_id'],
                        $item['fields'],
                        $item['properties']
                    );
            });
    }

    /**
     * @return void
     * @throws Exceptions\RestartException
     * @throws Exceptions\MigrationException
     */
    public function down(): void
    {
        $this->getExchangeManager()
            ->IblockElementsImport()
            ->setExchangeResource('iblock_elements.xml')
            ->setLimit(10)
            ->execute(function ($item) {
                $this->getHelperManager()
                    ?->Iblock()
                    ->deleteElementByCode(
                        $item['iblock_id'],
                        $item['fields']['CODE']
                    );
            });
    }
}
