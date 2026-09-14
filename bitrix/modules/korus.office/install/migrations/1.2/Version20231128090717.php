<?php

namespace Sprint\Migration;


class Version20231128090717 extends Version
{
    protected $description = "Обновление бейджей. Замена иконок в старых бейджах и добавление новых";
    protected $moduleVersion = "4.3.2";

    /**
     * @return bool|void
     * @throws Exceptions\RestartException
     * @throws Exceptions\HelperException
     * @throws Exceptions\MigrationException
     */
    public function up()
    {
        $this->getExchangeManager()
            ->HlblockElementsImport()
            ->setExchangeResource('hlblock_elements.xml')
            ->setLimit(20)
            ->execute(function ($item) {
                $existElements = $this->getHelperManager()
                    ->Hlblock()->getElements(
                        $item['hlblock_id'],
                        [
                            'select' => ['ID','UF_NAME'],
                            'filter' => ['UF_NAME' => $item['fields']['UF_NAME']],
                            'limit' => 1,
                        ]
                    );

                if (!empty($existElements)) {
                    $existElement = array_shift($existElements);
                    $this->getHelperManager()->Hlblock()->updateElement(
                        $item['hlblock_id'],
                        $existElement['ID'],
                        $item['fields']
                    );
                } else {
                    $this->getHelperManager()
                        ->Hlblock()
                        ->addElement(
                            $item['hlblock_id'],
                            $item['fields']
                        );
                }
            });
    }

    /**
     * @return bool|void
     * @throws Exceptions\RestartException
     * @throws Exceptions\HelperException
     * @throws Exceptions\MigrationException
     */
    public function down()
    {
    }


}
