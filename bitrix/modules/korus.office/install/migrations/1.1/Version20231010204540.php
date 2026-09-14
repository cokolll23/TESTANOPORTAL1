<?php

namespace Sprint\Migration;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Korus\Office\EventHandlers\OnGenericMenu;

class Version20231010204540 extends Version
{
    protected $description = '';

    protected $moduleVersion = '4.6.2';
    protected string $MODULE_ID = 'korus.office';

    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function up(): void
    {
        foreach ($this->getEvents() as $event) {
            RegisterModuleDependences(...$event);
        }
    }

    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function down(): void
    {
        foreach ($this->getEvents() as $event) {
            UnRegisterModuleDependences(...$event);
        }
    }

    private function getEvents(): array
    {
        return [
            // генерация пунктов меню
            [
                'korus.menu',
                'OnGenericMenu',
                $this->MODULE_ID,
                OnGenericMenu::class,
                'getGenericMenu',
                200,
            ],
        ];
    }
}
