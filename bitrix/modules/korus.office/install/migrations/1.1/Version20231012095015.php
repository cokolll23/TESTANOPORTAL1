<?php

namespace Sprint\Migration;


use Bitrix\Main\InvalidOperationException;

class Version20231012095015 extends Version
{
    protected $description = "Добавляет набор emoji для статусов в лк";

    protected $moduleVersion = "4.3.2";

    /**
     * @return void
     * @throws InvalidOperationException
     */
    public function up(): void
    {
        $galleryId = \CSmileGallery::getByStringId('korus_postcard');
        if (is_array($galleryId)) {
            $galleryId = $galleryId['ID'];
        }

        if (!$galleryId) {
            throw new InvalidOperationException('Не удалось найти галерею смайлов K-Team.');
        }

        $zipPath = dirname(__FILE__, 3) . '/resource/';

        $emojiSet = \CSmileSet::add([
            'STRING_ID' => 'status_emoji',
            'SORT' => 100,
            'PARENT_ID' => $galleryId,
            'TYPE' => \CSmileSet::TYPE_SET,
            'LANG' => [
                'ru' => 'Эмодзи в статусе',
                'en' => 'Status emoji',
            ]
        ]);

        \CSmile::import([
            'FILE' => $zipPath . '/emoji.zip',
            'SET_ID' => $emojiSet
        ]);
    }

    public function down()
    {
        //your code ...
    }
}
