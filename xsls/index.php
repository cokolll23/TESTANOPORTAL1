<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("XSLS");

use Lab\Helpers\UsersHelpers as UH;
use Bitrix\Main\UserTable;

?>
<?php
// https://www.aconvert.com/ru/document/xls-to-json/
// https://disk.yandex.ru/i/flfIQx5xrHBUOQ
// скопировать и вставить json
$jsonFile = '[
{"field1":"Запрос: СписокПериоды (Записей в результате: 549)","field2":"","field3":""}
,
{"field1":"СотрудникФизическоеЛицо","field2":"ИД","field3":"Email"}
,
{"field1":"Абдрахманова Эмилия Равилевна","field2":"87aee931-9c27-11ef-afe7-00155d000910","field3":"AbdrakhmanovaER@mos.ru"}
,
{"field1":"Аблонский Роман Алексеевич","field2":"e6954e05-cfc7-11ee-aee0-00155d000910","field3":"ablonskiyra@mos.ru"}
,
{"field1":"Адамчук Лидия Николаевна","field2":"f8736c45-2efa-11f0-b0a3-00155d000912","field3":"adamchukln@mos.ru"}
,
{"field1":"Азарова Анастасия Юрьевна","field2":"e6650b10-53be-11ef-af8a-00155d000912","field3":"AzarovaAY1@mos.ru"}
,
{"field1":"Азарова Лариса Владимировна","field2":"db164982-5e53-11f1-b228-00155d000912","field3":"AzarovaLV@mos.ru"}
,
{"field1":"Айрапетян Екатерина Игоревна","field2":"c4bb5573-5fa4-11eb-aa59-00155d1a381f","field3":"KarapetyanEI@mos.ru"}
,
{"field1":"Аксёнова Алина Алексеевна","field2":"d10e337f-7ccf-11f0-b106-00155d000912","field3":"aksenovaaa2@mos.ru"}
,
{"field1":"Аксентьева Светлана Андреевна","field2":"da065527-8186-11f0-b10c-00155d000912","field3":"aksentevasa@mos.ru"}
,
{"field1":"Александрова Анна Сергеевна","field2":"6bc5493b-6007-11f1-b22a-00155d000910","field3":"AleksandrovaAS8@it.mos.ru"}
,
{"field1":"Алексеев Антон Вячеславович","field2":"4eae540f-7151-11ed-ad13-00155d000910","field3":"AlekseevAV22@mos.ru"}
,
{"field1":"Алексина Ирина Владимировна","field2":"029cf27c-27c1-11ef-af52-00155d000910","field3":"AleksinaIV@mos.ru"}
,
{"field1":"Алешина Алена Игоревна","field2":"ffe2d2c9-ad02-11ed-ad68-00155d000910","field3":"AleshinaAI@mos.ru"}
,
{"field1":"Анашкин Артем Игоревич","field2":"546347f9-9c0f-11ef-afe7-00155d000910","field3":"AnashkinAI3@mos.ru"}
,
{"field1":"Андриянова Мария Александровна","field2":"7f3738be-ec03-11ee-af06-00155d000912","field3":"AndriyanovaMA@mos.ru"}
,
{"field1":"Аникеев Евгений Александрович","field2":"11e887d6-326e-11ed-acc2-00155d000912","field3":"AnikeevEA@mos.ru"}
,
{"field1":"Анисимова Александра Дмитриевна","field2":"3ba86ee8-ba11-11f0-b154-00155d000910","field3":"anisimovaad2@mos.ru"}
,
{"field1":"Анисимова Вера Николаевна","field2":"0dfb5bb8-d797-11ee-aeea-00155d000910","field3":"anisimovavn@mos.ru"}
,
{"field1":"Анисимова Елена Владимировна","field2":"160483e8-4909-11eb-aa3a-00155d1a381f","field3":"AnisimovaEV1@mos.ru"}
,
{"field1":"Аннамухамедов Батыр Арсланович","field2":"626fd319-72b1-11ec-abb7-00155d051a08","field3":"AnnamukhamedovBA1@mos.ru"}
,
{"field1":"Антипова Екатерина Андреевна","field2":"57033d32-6d0f-11f0-b0f2-00155d000912","field3":"antipovaea5@mos.ru"}
,
{"field1":"Антипова Мария Викторовна","field2":"ff3fcb71-5645-11f0-b0d4-00155d000910","field3":"AntipovaMV7@mos.ru"}
,
{"field1":"Антюхова Лилия Рамилевна","field2":"f30ff402-c3ca-11ed-ad85-00155d000912","field3":"AntyukhovaLR2@mos.ru"}
,
{"field1":"Анурова Майя Александровна","field2":"96af2245-2837-11f1-b1e3-00155d000912","field3":"AnurovaMA@mos.ru"}
,
{"field1":"Аншакова Татьяна Геннадьевна","field2":"b63237f6-92e3-11ec-abe0-00155d051a08","field3":"AnshakovaTG@mos.ru"}
,
{"field1":"Аншакова Юлия Николаевна","field2":"66d1e0bd-570e-11f0-b0d5-00155d000912","field3":"anshakovayn1@mos.ru"}
,
{"field1":"Апенянская София Витальевна","field2":"9cda7961-7102-11f0-b0f7-00155d000912","field3":"apenyanskayasv@mos.ru"}
,
{"field1":"Аржанова Анастасия Дмитриевна","field2":"d224554d-2838-11f1-b1e3-00155d000912","field3":"ArzhanovaAD@mos.ru"}
,
{"field1":"Арнаутова Александра Евгеньевна","field2":"94ab771e-ca97-11f0-b169-00155d000910","field3":"arnautovaae@mos.ru"}
,
{"field1":"Артамошкина Наталья Викторовна","field2":"133032c5-66c1-11ef-afa2-00155d000912","field3":"artamoshkinanv@mos.ru"}
,
{"field1":"Асаилова Анастасия Ильинична","field2":"fd897487-ca42-11ee-aed9-00155d000910","field3":"KalenkovaAI@mos.ru"}
,
{"field1":"Асонова Софья Олеговна","field2":"cd6c0680-2904-11f1-b1e4-00155d000910","field3":"AsonovaSO@mos.ru"}
,
{"field1":"Афанасьева Дарья Ивановна","field2":"d2db1e3e-d18f-11ef-b02b-00155d000910","field3":"afanasevadi@mos.ru"}
,
{"field1":"Ахмедзянова Евгения Викторовна","field2":"1864ca57-0e4d-11ed-ac92-00155d000910","field3":"Evgeshaah@gmail.com"}
,
{"field1":"Бабаева Зарина Феликсовна","field2":"82492613-6bef-11ee-ae60-00155d000910","field3":"BabaevaZF@mos.ru"}
,
{"field1":"Базин Сергей Сергеевич","field2":"5a54726c-64b3-11ed-ad03-00155d000910","field3":"BazinSS@mos.ru"}
,
{"field1":"Баландюк Максим Игоревич","field2":"58f41ef5-47f5-11ef-af7b-00155d000910","field3":"balandyukmi@mos.ru"}
,
{"field1":"Балбекова Дарья Эдуардовна","field2":"f89deb06-e5bc-11ee-aefe-00155d000912","field3":"BalbekovaDE@mos.ru"}
,
{"field1":"Баранивская Наталья Викторовна","field2":"b69d4aeb-23d1-11ef-af4d-00155d000912","field3":"BaranivskayaNV@mos.ru"}
,
{"field1":"Баранова Екатерина Владимировна","field2":"f0c76e63-bcb6-11ed-ad7c-00155d000910","field3":"BaranovaEV4@mos.ru"}
,
{"field1":"Барашкова Полина Сергеевна","field2":"afa2981a-23fc-11f0-b093-00155d000910","field3":"BarashkovaPS@mos.ru"}
,
{"field1":"Барлова Александра Олеговна","field2":"507a460c-4201-11f1-b204-00155d000910","field3":"barlovaao@mos.ru"}
,
{"field1":"Бауман Елена Михайловна","field2":"d1459439-af85-11ee-aeb7-00155d000912","field3":"BaumanEM@mos.ru"}
,
{"field1":"Белик Сергей Сергеевич","field2":"503f1a8f-b3e2-11ec-ac0a-00155d051a08","field3":"BelikSS@mos.ru"}
,
{"field1":"Беликова Валерия Валерьевна","field2":"fc85337c-d724-11f0-b17b-00155d000912","field3":"lerika-2000@yandex.ru"}
,
{"field1":"Белкина Екатерина Дмитриевна","field2":"93c8dddb-5b27-11f0-b0da-00155d000910","field3":"belkinaed@mos.ru"}
,
{"field1":"Белов Илья Игоревич","field2":"648f2c38-2849-11f1-b1e3-00155d000912","field3":"belovii@mos.ru"}
,
{"field1":"Белозерова Анастасия Александровна","field2":"daf5bf64-52c3-11f1-b219-00155d000912","field3":"belozerovaaa4@it.mos.ru"}
,
{"field1":"Белявцева Мария Сергеевна","field2":"a6fd3571-4ac2-11ed-ace1-00155d000912","field3":"BelyavtsevaMS@mos.ru"}
,
{"field1":"Белявцева Ольга Андреевна","field2":"b0f4d1d9-607c-11f0-b0e1-00155d000912","field3":"belyavtsevaoa1@mos.ru"}
,
{"field1":"Бережной Сергей Викторович","field2":"c4786dc9-37ad-11ef-af66-00155d000910","field3":"BerezhnoySV3@mos.ru"}
,
{"field1":"Березнева Анастасия Олеговна","field2":"9a11159a-b80b-11ed-ad76-00155d000912","field3":"IvanovaAO6@mos.ru"}
,
{"field1":"Берестовская Ольга Валентиновна","field2":"dc50e517-2187-11ef-af4a-00155d000910","field3":"berestovskayaov@mos.ru"}
,
{"field1":"Бирюков Андрей Андреевич","field2":"2bfde83d-fb45-11eb-ab1e-00155d051a08","field3":"BiryukovAA1@mos.ru"}
,
{"field1":"Бисярина Ольга Андреевна","field2":"877d95e6-ec7a-11ec-ac65-00155d000912","field3":"BisyarinaOA@mos.ru"}
,
{"field1":"Бичарова Анастасия Викторовна","field2":"65414b47-37b8-11f0-b0ad-00155d000910","field3":"BicharovaAV@mos.ru"}
,
{"field1":"Богодухова Снежана Григорьевна","field2":"17424852-283b-11f1-b1e3-00155d000912","field3":"BogodukhovaSG@mos.ru"}
,
{"field1":"Бодров Дмитрий Алексеевич","field2":"5175dca5-6347-11ee-ae55-00155d000910","field3":"dima1997_70@mail.ru"}
,
{"field1":"Болденкова Мария Вадимовна","field2":"81d82cfc-4527-11eb-aa35-00155d1a381f","field3":"BoldenkovaMV@mos.ru"}
,
{"field1":"Бондаренко Наталья Андреевна","field2":"7c08d582-5417-11f1-b21b-00155d000912","field3":"bondarenkona7@mos.ru"}
,
{"field1":"Борисов Денис Сергеевич","field2":"d1720e2d-3415-11e9-a98f-00155d1a3433","field3":"BorisovDS1@mos.ru"}
,
{"field1":"Борисов Дмитрий Михайлович","field2":"f26ec3fd-72a8-11f0-b0f9-00155d000912","field3":"borisovdm@mos.ru"}
,
{"field1":"Боровеева Татьяна Сергеевна","field2":"d1720e10-3415-11e9-a98f-00155d1a3433","field3":"BoroveevaTS@mos.ru"}
,
{"field1":"Бородкина Ирина Дмитриевна","field2":"1b337328-cb2e-11ee-aeda-00155d000912","field3":"BorodkinaID@mos.ru"}
,
{"field1":"Бородовская София Максимовна","field2":"bd3590ae-283b-11f1-b1e3-00155d000912","field3":"BorodovskayaSM@mos.ru"}
,
{"field1":"Боярский Андрей Алексеевич","field2":"e8073dad-b7ff-11ed-ad76-00155d000912","field3":"BoyarskiiAA@mos.ru"}
,
{"field1":"Брагина Ирина Ильинична","field2":"9bdddad5-76ea-11ee-ae6e-00155d000912","field3":"BraginaII@mos.ru"}
,
{"field1":"Брезгунов Олег Валерьевич","field2":"6a32ae9d-eea5-11ef-b04a-00155d000910","field3":"brezgunovov@mos.ru"}
,
{"field1":"Брик Ольга Васильевна","field2":"cde31fd6-ce1f-11e9-a994-00155d1a3432","field3":"BrikOV@mos.ru"}
,
{"field1":"Бриккман Юлия Игоревна","field2":"0ac88f7d-4515-11eb-aa35-00155d1a381f","field3":"DimitryukYI@mos.ru"}
,
{"field1":"Брусников Михаил Владимирович","field2":"c0540eef-2982-11f0-b09a-00155d000910","field3":"BrusnikovMV@mos.ru"}
,
{"field1":"Брызгачев Вячеслав Владимирович","field2":"d8cb765d-4ac7-11ed-ace1-00155d000912","field3":"BryzgachevVV@mos.ru"}
,
{"field1":"Булгаков Валентин Николаевич","field2":"d2e96678-438c-11f1-b206-00155d000910","field3":"bulgakovvn1@mos.ru"}
,
{"field1":"Булгакова Анна Игоревна","field2":"e2a5da8c-d2b0-11ed-ad98-00155d000910","field3":"BulgakovaAI@mos.ru"}
,
{"field1":"Булгакова Полина Викторовна","field2":"e1d7e283-be58-11ed-ad7e-00155d000912","field3":"BulgakovaPV@mos.ru"}
,
{"field1":"Булычкина Анна Петровна","field2":"7ce6d857-89ca-11ee-ae86-00155d000912","field3":"BulychkinaAP@mos.ru"}
,
{"field1":"Буркова Алина Андреевна","field2":"f2af5e4a-ad7e-11f0-b144-00155d000912","field3":"burkovaaa@mos.ru"}
,
{"field1":"Бурмистрова Юлия Михайловна","field2":"21dcddc1-2769-11ed-acb3-00155d000912","field3":"BurmistrovaYM@mos.ru"}
,
{"field1":"Буторин Юрий Игоревич","field2":"0b2c756f-ce84-11f0-b170-00155d000912","field3":"butorinyi@mos.ru"}
,
{"field1":"Вавиленкова Ирина Леонидовна","field2":"f431b373-e8b7-11ed-adb4-00155d000912","field3":"VavilenkovaIL1@mos.ru"}
,
{"field1":"Вавилин Павел Александрович","field2":"079a9b79-8b97-11ef-afd2-00155d000912","field3":"VavilinPA@mos.ru"}
,
{"field1":"Валеев Денис Рустамович","field2":"1ec86f36-536e-11ed-acec-00155d000912","field3":"ValeevDR1@mos.ru"}
,
{"field1":"Валимухаметов Юлдаш Рафилевич","field2":"81e1caff-4888-11f1-b20c-00155d000912","field3":"valimukhametovyr@mos.ru"}
,
{"field1":"Валишина Юлия Владимировна","field2":"8e43091d-c3f2-11ee-aed1-00155d000912","field3":"ValishinaYV1@mos.ru"}
,
{"field1":"Ванюшкина Дарья Владимировна","field2":"25bc7ead-584a-11f1-b220-00155d000910","field3":"VanyushkinaDV@it.mos.ru"}
,
{"field1":"Варванин Евгений Николаевич","field2":"1d5c5e96-28b9-11eb-aa10-00155d1a381f","field3":"VarvaninEN@mos.ru"}
,
{"field1":"Василов Владислав Васильевич","field2":"f53eadbe-4781-11f1-b20b-00155d000912","field3":"vasilovvv@mos.ru"}
,
{"field1":"Василова Наталия Владимировна","field2":"81928312-a391-11ed-ad5a-00155d000910","field3":"SinikovaNV@mos.ru"}
,
{"field1":"Васильев Вячеслав Анатольевич","field2":"52a2dedc-7174-11ee-ae67-00155d000910","field3":"VasilevVA18@mos.ru"}
,
{"field1":"Васильева Татьяна Алексеевна","field2":"331cb5d0-87de-11ec-abd2-00155d051a08","field3":"VasilevaTA3@mos.ru"}
,
{"field1":"Ватах Анастасия Александровна","field2":"93b52554-01b3-11ed-ac82-00155d000912","field3":"VatakhAA@mos.ru"}
,
{"field1":"Ватолин Алексей Владиславович","field2":"b95131e1-2987-11f0-b09a-00155d000910","field3":"VatolinAV@mos.ru"}
,
{"field1":"Вежлев Николай Александрович","field2":"2446ddff-8717-11f0-b113-00155d000912","field3":"vezhlevna@mos.ru"}
,
{"field1":"Вельтман Алексей Владиславович","field2":"dbf6c15e-53d9-11ef-af8a-00155d000912","field3":"veltmanav@mos.ru"}
,
{"field1":"Вельтман Кристина Михайловна","field2":"1356a8e3-23d8-11ef-af4d-00155d000912","field3":"VeltmanKM@mos.ru"}
,
{"field1":"Вендеревских Игорь Андреевич","field2":"6cafe9a7-cf0c-11ee-aedf-00155d000910","field3":"venderevskikhia@mos.ru"}
,
{"field1":"Верещагина Елена Игоревна","field2":"6407e54a-2e61-11f1-b1eb-00155d000910","field3":"VereschaginaEI@mos.ru"}
,
{"field1":"Вертунов Арсений Андреевич","field2":"243a43d6-3a9e-11ef-af6a-00155d000912","field3":"vertunovaa@mos.ru"}
,
{"field1":"Вертунова Дарья Андреевна","field2":"f14c2c60-5419-11eb-aa48-00155d1a381f","field3":"VertunovaDD@mos.ru"}
,
{"field1":"Викторова Екатерина Сергеевна","field2":"45013630-0001-11f1-b1af-00155d000912","field3":"viktorovaes2@mos.ru"}
,
{"field1":"Винокурова Анна Алексеевна","field2":"bd49625c-5db6-11f1-b227-00155d000912","field3":"VinokurovaAA4@it.mos.ru"}
,
{"field1":"Витров Тимур Витальевич","field2":"c48005b8-53cb-11ef-af8a-00155d000912","field3":"vitrovtv@mos.ru"}
,
{"field1":"Власова Елена Анатольевна","field2":"87739100-bc48-11ea-a9be-00155d1a381f","field3":"VlasovaEA2@mos.ru"}
,
{"field1":"Власова Татьяна Юрьевна","field2":"d1ace1ee-cbf3-11ee-aedb-00155d000912","field3":"VlasovaTY3@mos.ru"}
,
{"field1":"Волчков Фёдор Олегович","field2":"05f899e8-318a-11f1-b1ef-00155d000912","field3":"volchkovfo@mos.ru"}
,
{"field1":"Воробьев Константин Сергеевич","field2":"fc1b9c55-0492-11f0-b066-00155d000912","field3":"VorobevKS@mos.ru"}
,
{"field1":"Восканянц Наталья Евгеньевна","field2":"56f3a59f-2ce5-11f1-b1e9-00155d000910","field3":"VoskanyantsNE@mos.ru"}
,
{"field1":"Вотякова Елена Васильевна","field2":"80681355-7d32-11ee-ae76-00155d000910","field3":"tokareva1007@gmail.com"}
,
{"field1":"Вронская Кристина Александровна","field2":"5716daca-b37d-11ee-aebc-00155d000912","field3":"VronskayaKA@mos.ru"}
,
{"field1":"Выборнов Андрей Владимирович","field2":"92b2a526-988c-11eb-aaa1-00155d1a381f","field3":"VybornovAV@mos.ru"}
,
{"field1":"Габрусевич Дмитрий Евгеньевич","field2":"72f4b2ef-5c97-11f0-b0dc-00155d000912","field3":"gabrusevichde@mos.ru"}
,
{"field1":"Гаврилова Ирина Александровна","field2":"0b94f924-2103-11f1-b1da-00155d000912","field3":"gavrilovaia2@mos.ru"}
,
{"field1":"Гаврилова Татьяна Владимировна","field2":"b58860ea-d4e4-11ec-ac43-00155d000910","field3":"GavrilovaTV5@mos.ru"}
,
{"field1":"Герасимов Даниил Александрович","field2":"f0b218a6-2c73-11ef-af58-00155d000910","field3":"GerasimovDA2@mos.ru"}
,
{"field1":"Герхенрейдер Римма Михайловна","field2":"67f7ebd4-5725-11f0-b0d5-00155d000912","field3":"GerkhenreyderRM@mos.ru"}
,
{"field1":"Глазкова Ирина Дмитриевна","field2":"c5fd15b2-6441-11ef-af9f-00155d000910","field3":"GlazkovaID@mos.ru"}
,
{"field1":"Глазкова Софья Олеговна","field2":"dd70c9de-f005-11ee-af0b-00155d000912","field3":"GlazkovaSO@mos.ru"}
,
{"field1":"Гоголь Яна Сергеевна","field2":"f1cfb2b6-8c82-11f0-b11a-00155d000912","field3":"gogolys@mos.ru"}
,
{"field1":"Голикова Мария Владимировна","field2":"7da1c38a-8839-11ee-ae84-00155d000910","field3":"GolikovaMV3@mos.ru"}
,
{"field1":"Головченко Вера Валерьевна","field2":"740f99cc-1736-11ee-adf1-00155d000910","field3":"GolovchenkoVV@mos.ru"}
,
{"field1":"Горбунов Владимир Сергеевич","field2":"297e55ea-ce97-11f0-b170-00155d000912","field3":"gorbunovvs1@mos.ru"}
,
{"field1":"Гордиенко Андрей Анатольевич","field2":"e9c63013-7ef9-11ef-afc2-00155d000910","field3":"GordienkoAA4@mos.ru"}
,
{"field1":"Горячев Иван Александрович","field2":"a519a6ea-3d4a-11f0-b0b4-00155d000912","field3":"goryachevia@mos.ru"}
,
{"field1":"Грачев Борис Владимирович","field2":"016f4941-08c3-11ed-ac8b-00155d000910","field3":"GrachevBV@mos.ru"}
,
{"field1":"Григорьева Алина Сергеевна","field2":"da8ca54c-478b-11f1-b20b-00155d000912","field3":"grigorevaas6@mos.ru"}
,
{"field1":"Грошева Анастасия Владимировна","field2":"18e4c60e-2845-11f1-b1e3-00155d000912","field3":"GroshevaAV2@mos.ru"}
,
{"field1":"Грыжина Елена Юрьевна","field2":"69abd16b-ee1b-11eb-ab0d-00155d051a08","field3":"GryzhinaEY@mos.ru"}
,
{"field1":"Губарева Алёна Павловна","field2":"ad15fa76-7995-11ef-afbb-00155d000912","field3":"gubarevaap1@mos.ru"}
,
{"field1":"Гузенин Руслан Олегович","field2":"724bcf07-0f92-11f0-b074-00155d000910","field3":"GuzeninRO@mos.ru"}
,
{"field1":"Гюлер Ксения Николаевна","field2":"fa999387-44f0-11eb-aa35-00155d1a381f","field3":"GyulerKN@mos.ru"}
,
{"field1":"Давыдова Дина Антоновна","field2":"d39b6304-be0a-11f0-b159-00155d000912","field3":"davydovada4@mos.ru"}
,
{"field1":"Давыдова Юлия Михайловна","field2":"048bcdb6-eee3-11f0-b199-00155d000910","field3":"davydovaym2@mos.ru"}
,
{"field1":"Данилова Мария Викторовна","field2":"4f22aa4d-4f5e-11ef-af84-00155d000910","field3":"ryabovamv3@mos.ru"}
,
{"field1":"Дворецкая Нина Викторовна","field2":"9b484d49-27cd-11ef-af52-00155d000910","field3":"DvoretskayaNV1@mos.ru"}
,
{"field1":"Дворников Георгий Витальевич","field2":"78c1f69e-48ff-11eb-aa3a-00155d1a381f","field3":"DvornikovGV1@mos.ru"}
,
{"field1":"Демидик Ольга Анатольевна","field2":"3fc89bc4-851c-11ee-ae80-00155d000912","field3":"DemidikOA@mos.ru"}
,
{"field1":"Демидова Евгения Анатольевна","field2":"30bb9a88-2839-11f1-b1e3-00155d000912","field3":"DemidovaEA3@mos.ru"}
,
{"field1":"Дербенева Алена Игоревна","field2":"8c28a49f-f577-11ee-af12-00155d000910","field3":"DerbenevaAI@mos.ru"}
,
{"field1":"Дергачева Анна Вячеславовна","field2":"f1bf1f5d-c170-11ed-ad82-00155d000910","field3":"dergachevaav@mos.ru"}
,
{"field1":"Добротворская Анастасия Александровна","field2":"bc82d784-4329-11ed-acd7-00155d000912","field3":"DobrotvorskayaAA1@mos.ru"}
,
{"field1":"Долганова Татьяна Валерьевна","field2":"1babb84e-cf4a-11f0-b171-00155d000912","field3":"dolganovatv@mos.ru"}
,
{"field1":"Дочвири Ираклий Александрович","field2":"25c629c2-b146-11ef-b002-00155d000912","field3":"dochviriia@mos.ru"}
,
{"field1":"Дрозд Ольга Андреевна","field2":"5ffb2293-0007-11f1-b1af-00155d000912","field3":"drozdoa@mos.ru"}
,
{"field1":"Егоренкова Анна Дмитриевна","field2":"138993e5-4a50-11ef-af7e-00155d000912","field3":"egorenkovaad@mos.ru"}
,
{"field1":"Егоров Валерий Игоревич","field2":"904dd76b-efcc-11ed-adbd-00155d000912","field3":"EgorovVI8@mos.ru"}
,
{"field1":"Елисеева Галина Владимировна","field2":"12a524b0-0fb2-11ec-ab38-00155d051a08","field3":"EliseevaGV@mos.ru"}
,
{"field1":"Ёлкин Георгий Владимирович","field2":"0dbe8b16-a20a-11ed-ad58-00155d000910","field3":"ElkinGV@mos.ru"}
,
{"field1":"Елфимова Анастасия Ивановна","field2":"f3931266-379e-11f0-b0ad-00155d000910","field3":"ElfimovaAI@mos.ru"}
,
{"field1":"Емелин Владимир Владимирович","field2":"e717d994-5b0e-11f0-b0da-00155d000910","field3":"emelinvv1@mos.ru"}
,
{"field1":"Ергакова Мария Андреевна","field2":"e2cbfff1-2042-11ec-ab4d-00155d051a08","field3":"klukvik5@mail.ru"}
,
{"field1":"Ерёмина Екатерина Владимировна","field2":"d040603f-71e8-11ec-abb6-00155d051a08","field3":"EreminaEV4@mos.ru"}
,
{"field1":"Ермаков Глеб Денисович","field2":"e4efb689-b300-11f0-b14b-00155d000910","field3":"ermakovgd@mos.ru"}
,
{"field1":"Ермолаев Михаил Николаевич","field2":"4014a907-98e5-11ef-afe3-00155d000910","field3":"ErmolaevMN@mos.ru"}
,
{"field1":"Ефимов Александр Игоревич","field2":"350b9d2d-b088-11ef-b001-00155d000910","field3":"EfimovAI3@mos.ru"}
,
{"field1":"Ефимов Сергей Юрьевич","field2":"15c8b8db-16c6-11f1-b1cc-00155d000912","field3":"efimovsy5@mos.ru"}
,
{"field1":"Жабовская Екатерина Андреевна","field2":"c0626ac3-5800-11f1-b220-00155d000910","field3":"ZhabovskayaEA@mos.ru"}
,
{"field1":"Жуков Андрей Сергеевич","field2":"f187d66e-b304-11f0-b14b-00155d000910","field3":"zhukovas11@mos.ru"}
,
{"field1":"Загарина Ирина Николаевна","field2":"16e06857-817d-11f0-b10c-00155d000912","field3":"zagarinain@mos.ru"}
,
{"field1":"Заграничный Дмитрий Павлович","field2":"1935f0ce-47f7-11ef-af7b-00155d000910","field3":"zagranichnyydp@mos.ru"}
,
{"field1":"Зайкова Ксения Викторовна","field2":"1725ba17-d44c-11ed-ad9a-00155d000910","field3":"ZaikovaKV@mos.ru"}
,
{"field1":"Зайцева Маргарита Юрьевна","field2":"a54b37fb-0115-11ee-add3-00155d000912","field3":"margosha7173@mail.ru"}
,
{"field1":"Заренкова Анфиса","field2":"1298e737-31b6-11f1-b1ef-00155d000912","field3":"ZarenkovaA@mos.ru"}
,
{"field1":"Захарчук Алиса Сергеевна","field2":"f10c51d3-cca3-11ee-aedc-00155d000912","field3":"ZakharchukAS@mos.ru"}
,
{"field1":"Зеленов Александр Аркадьевич","field2":"221317a4-6fb5-11ed-ad11-00155d000912","field3":"ZelenovAA@mos.ru"}
,
{"field1":"Зеленов Александр Аркадьевич","field2":"79ef6a35-d405-11f0-b177-00155d000912","field3":"ZelenovAA@mos.ru"}
,
{"field1":"Зимин Сергей Юрьевич","field2":"51f7be49-e14c-11ea-a9ca-00155d1a381f","field3":"ZiminSY@mos.ru"}
,
{"field1":"Злобина Светлана Анатольевна","field2":"7569eb11-4275-11ef-af74-00155d000912","field3":"zlobinasa@mos.ru"}
,
{"field1":"Золотарев Александр Викторович","field2":"0e3f12a7-2847-11f1-b1e3-00155d000912","field3":"zolotarevav4@mos.ru"}
,
{"field1":"Зотова Надежда Валерьевна","field2":"c3bbd52f-4772-11ec-ab7f-00155d051a08","field3":"ZotovaNV@mos.ru"}
,
{"field1":"Зубатова Дарья Витальевна","field2":"ca93b190-2011-11f0-b08e-00155d000912","field3":"ZubatovaDV@mos.ru"}
,
{"field1":"Зуев Дмитрий Сергеевич","field2":"f79f89ff-dfe6-11ec-ac53-00155d000912","field3":"ZuevDS@mos.ru"}
,
{"field1":"Ибраимова Лейла Эскендеровна","field2":"ec0a9640-2da1-11f1-b1ea-00155d000910","field3":"IbraimovaLE@mos.ru"}
,
{"field1":"Иванова Светлана Георгиевна","field2":"d06e6334-bd28-11eb-aad0-00155d1a381f","field3":"IvanovaSG5@mos.ru"}
,
{"field1":"Изоткин Александр Геннадьевич","field2":"46564cc6-1129-11f0-b076-00155d000912","field3":"IzotkinAG@mos.ru"}
,
{"field1":"Изотова Евгения Сергеевна","field2":"974dced0-6827-11ea-a9a2-00155d1a230c","field3":"IzotovaES@mos.ru"}
,
{"field1":"Илдис Алина Александровна","field2":"9d72c33f-0d67-11f1-b1c0-00155d000910","field3":"ildisaa@mos.ru"}
,
{"field1":"Казакова Алина Александровна","field2":"acbc2fa7-5000-11f0-b0cc-00155d000910","field3":"kazakovaaa3@mos.ru"}
,
{"field1":"Казанцев Артем Евгеньевич","field2":"e95f3040-e56f-11ec-ac5c-00155d000912","field3":"KazantsevAE@mos.ru"}
,
{"field1":"Калашников Алексей Юрьевич","field2":"82c59175-d2c1-11ed-ad98-00155d000910","field3":"kalashnikovay3@mos.ru"}
,
{"field1":"Каленкин Роман Николаевич","field2":"ec7a068b-635c-11ee-ae55-00155d000910","field3":"KalenkinRN@mos.ru"}
,
{"field1":"Калинушкин Максим Владимирович","field2":"7454c04a-47fc-11ef-af7b-00155d000910","field3":"kalinushkinmv@mos.ru"}
,
{"field1":"Калистратов Илья Алексеевич","field2":"f2f3e445-d59a-11f0-b179-00155d000912","field3":"KalistratovIA1@mos.ru"}
,
{"field1":"Каллаур Ольга Юрьевна","field2":"1c4c337e-a8d3-11f0-b13e-00155d000910","field3":"olkallaur@mail.ru"}
,
{"field1":"Калугин Иван Анатольевич","field2":"12014990-b07b-11ef-b001-00155d000910","field3":"KaluginIA@mos.ru"}
,
{"field1":"Кандыба Дарья Дмитриевна","field2":"e598df3f-8702-11f0-b113-00155d000912","field3":"kandybadd@mos.ru"}
,
{"field1":"Кардаполова Татьяна Алексеевна","field2":"37f5f189-9846-11f0-b129-00155d000912","field3":"kardapolovata@mos.ru"}
,
{"field1":"Каревская Анна Андреевна","field2":"37fcf7f1-4045-11f0-b0b8-00155d000910","field3":"karevskayaaa@mos.ru"}
,
{"field1":"Кармацкий Илья Анатольевич","field2":"b524be47-fd78-11ef-b05d-00155d000912","field3":"karmatskiyia@mos.ru"}
,
{"field1":"Картузов Илья Евгеньевич","field2":"acaa8afc-c06a-11f0-b15c-00155d000912","field3":"kartuzovie@mos.ru"}
,
{"field1":"Касаткин Алексей Вячеславович","field2":"23fe92dc-e316-11eb-aaff-00155d051a08","field3":"KasatkinAV@mos.ru"}
,
{"field1":"Касаткин Алексей Вячеславович","field2":"612b8ab3-33eb-11f1-b1f2-00155d000912","field3":"KasatkinAV@mos.ru"}
,
{"field1":"Касницкая Виктория Николаевна","field2":"5f3c4cc1-0b00-11f1-b1bd-00155d000912","field3":"kasnitskayavn@mos.ru"}
,
{"field1":"Кастрова Анна Владимировна","field2":"f52656dd-2ce6-11ed-acbb-00155d000910","field3":"kastrovaav@mos.ru"}
,
{"field1":"Каткова Елена Дмитриевна","field2":"c1240b8d-3032-11ee-ae13-00155d000912","field3":"KatkovaED@mos.ru"}
,
{"field1":"Киреева Анна Викторовна","field2":"0bdcc970-7889-11ee-ae70-00155d000912","field3":"KireevaAV4@mos.ru"}
,
{"field1":"Киреева Дарина Сергеевна","field2":"2e73321e-d831-11ed-ad9f-00155d000912","field3":"KireevaDS@mos.ru"}
,
{"field1":"Кислякова Ольга Васильевна","field2":"24f3fe59-9a4c-11ee-ae9c-00155d000912","field3":"KislyakovaOV@mos.ru"}
,
{"field1":"Клишина Виктория Павловна","field2":"9d3ff22d-0919-11ef-af2b-00155d000910","field3":"KlishinaVP@mos.ru"}
,
{"field1":"Клушина Елена Валентиновна","field2":"afd0e5ab-76fb-11ee-ae6e-00155d000912","field3":"KlushinaEV2@mos.ru"}
,
{"field1":"Клюкина Дарья Сергеевна","field2":"36ce3059-2843-11f1-b1e3-00155d000912","field3":"KlyukinaDS@mos.ru"}
,
{"field1":"Князев Андрей Дмитриевич","field2":"7f93f20d-e107-11ee-aef8-00155d000910","field3":"KnyazevAD3@mos.ru"}
,
{"field1":"Князева Елена Геннадьевна","field2":"eba41dd9-219b-11f0-b090-00155d000912","field3":"KnyazevaEG3@mos.ru"}
,
{"field1":"Ковалев Никита Александрович","field2":"fd2367a4-a1fe-11ed-ad58-00155d000910","field3":"KovalevNA@mos.ru"}
,
{"field1":"Коваленко Артём Андреевич","field2":"8880d308-0854-11ef-af2a-00155d000912","field3":"KovalenkoAA2@mos.ru"}
,
{"field1":"Коваленко Ксения Васильевна","field2":"196e3c96-9aec-11ed-ad4f-00155d000910","field3":"KovalenkoKV@mos.ru"}
,
{"field1":"Коваленко Мария Алексеевна","field2":"f29c6826-c3ff-11ee-aed1-00155d000912","field3":"KovalenkoMA@mos.ru"}
,
{"field1":"Ковтуненко Антон Александрович","field2":"07a202ec-714b-11ed-ad13-00155d000910","field3":"KovtunenkoAA@mos.ru"}
,
{"field1":"Козлова Диана Анатольевна","field2":"7d229a3f-15f8-11ec-ab40-00155d051a08","field3":"KozlovaDA2@mos.ru"}
,
{"field1":"Кокорева Ирина Александровна","field2":"516e3782-5fb7-11eb-aa59-00155d1a381f","field3":"KokorevaIA1@mos.ru"}
,
{"field1":"Колбасова Инна Олеговна","field2":"aff6a2e1-6c51-11f0-b0f1-00155d000910","field3":"kolbasovaio@mos.ru"}
,
{"field1":"Колесникова Анна Аркадьевна","field2":"77151f75-e2c4-11ef-b042-00155d000912","field3":"kolesnikovaaa3@mos.ru"}
,
{"field1":"Колесникова Елизавета Константиновна","field2":"48f25a5b-f001-11ee-af0b-00155d000912","field3":"KolesnikovaEK1@mos.ru"}
,
{"field1":"Колокольцев Сергей Дмитриевич","field2":"efc7280b-1287-11ef-af37-00155d000910","field3":"KolokoltsevSD@mos.ru"}
,
{"field1":"Коломарь Елена Алексеевна","field2":"4bb6f055-29c3-11ed-acb6-00155d000912","field3":"KolomarEA@mos.ru"}
,
{"field1":"Колюканова Марина Анатольевна","field2":"2b59f647-e121-11ea-a9ca-00155d1a381f","field3":"KolyukanovaMA@mos.ru"}
,
{"field1":"Комарова Елена Сергеевна","field2":"92c0fb89-3a71-11ee-ae20-00155d000910","field3":"KomarovaES5@mos.ru"}
,
{"field1":"Комиссарова Анастасия Алексеевна","field2":"7458f3cc-eff4-11ee-af0b-00155d000912","field3":"KomissarovaAA5@mos.ru"}
,
{"field1":"Кононова Елизавета Дмитриевна","field2":"43f0e408-2f41-11ed-acbe-00155d000912","field3":"KononovaED@mos.ru"}
,
{"field1":"Коняева Ксения Сергеевна","field2":"7524c1fe-283b-11f1-b1e3-00155d000912","field3":"KonyaevaKS@mos.ru"}
,
{"field1":"Коптилкина Ирина Сергеевна","field2":"964c9246-d15a-11ee-aee2-00155d000910","field3":"KoptilkinaIS@mos.ru"}
,
{"field1":"Копцева Кристина Алексеевна","field2":"dda688e8-283e-11f1-b1e3-00155d000912","field3":"KoptsevaKA@mos.ru"}
,
{"field1":"Кораблина Ирина Анатольевна","field2":"d9b9428f-a15a-11ed-ad57-00155d000912","field3":"KorablinaIA@mos.ru"}
,
{"field1":"Коренькова Анастасия Анатольевна","field2":"f6cff9d1-428e-11ef-af74-00155d000912","field3":"korenkovaaa@mos.ru"}
,
{"field1":"Королев Андрей Александрович","field2":"6cd85ea8-1e82-11f0-b08c-00155d000910","field3":"KorolevAA1@mos.ru"}
,
{"field1":"Королева Ирина Андреевна","field2":"a0baed50-2839-11f1-b1e3-00155d000912","field3":"KorolevaIA13@mos.ru"}
,
{"field1":"Королева Ксения Сергеевна","field2":"6e402800-bba4-11f0-b156-00155d000910","field3":"korolevaks1@mos.ru"}
,
{"field1":"Корхов Александр Вадимович","field2":"b1c9e4b8-faf8-11ee-af19-00155d000912","field3":"korkhovav1@mos.ru"}
,
{"field1":"Косарева Татьяна Константиновна","field2":"2a3d7d74-0ae5-11f0-b06e-00155d000912","field3":"KosarevaTK3@mos.ru"}
,
{"field1":"Косолобенкова Ирина Александровна","field2":"1ebd4e56-ea10-11f0-b193-00155d000910","field3":"KosolobenkovaIA@mos.ru"}
,
{"field1":"Косьянова Карина Максимовна","field2":"e5b6ffc8-b3b5-11ee-aebc-00155d000912","field3":"KosyanovaKM@mos.ru"}
,
{"field1":"Котельникова Марина Борисовна","field2":"26504bd4-c201-11eb-aad6-00155d1a381f","field3":"KotelnikovaMB@mos.ru"}
,
{"field1":"Краморенко Анна Матвеевна","field2":"031c5d46-6999-11ee-ae5d-00155d000912","field3":"KramorenkoAM@mos.ru"}
,
{"field1":"Краснов Филипп Евгеньевич","field2":"b12396be-5dff-11ef-af97-00155d000912","field3":"krasnovfe@mos.ru"}
,
{"field1":"Кривчанская Екатерина Михайловна","field2":"ae226e0d-6374-11ef-af9e-00155d000912","field3":"KrivchanskayaEM@mos.ru"}
,
{"field1":"Кротова Наталья Андреевна","field2":"3fc7ffdd-0bca-11f1-b1be-00155d000912","field3":"krotovana4@mos.ru"}
,
{"field1":"Круглов Денис Максимович","field2":"24dc1697-bbef-11ed-ad7b-00155d000910","field3":"kruglovdm@mos.ru"}
,
{"field1":"Круглов Денис Максимович","field2":"d8f5f0ab-f22b-11f0-b19d-00155d000910","field3":"kruglovdm@mos.ru"}
,
{"field1":"Круглова Наталья Геннадьевна","field2":"ed0d64e9-452f-11eb-aa35-00155d1a381f","field3":"KruglovaNG@mos.ru"}
,
{"field1":"Крюков Андрей Александрович","field2":"1c0b8fea-7154-11ed-ad13-00155d000910","field3":"KryukovAA3@mos.ru"}
,
{"field1":"Крючкова Варвара Александровна","field2":"6bc25b8b-d17b-11ef-b02b-00155d000910","field3":"kryuchkovava3@mos.ru"}
,
{"field1":"Кузина Анастасия Дмитриевна","field2":"a0b22568-797e-11ef-afbb-00155d000912","field3":"KuzinaAD1@mos.ru"}
,
{"field1":"Кузнецова Валерия Сергеевна","field2":"19dadd6c-3039-11ee-ae13-00155d000912","field3":"kuznetsovavs5@mos.ru"}
,
{"field1":"Кузькина Полина Геннадьевна","field2":"fffb5e42-1a39-11ee-adf7-00155d000910","field3":"KuzkinaPG@mos.ru"}
,
{"field1":"Кузьмичева Василиса Владимировна","field2":"1f9ee891-86fd-11f0-b113-00155d000912","field3":"vasilisa88@yahoo.com"}
,
{"field1":"Куклина Екатерина Сергеевна","field2":"d30350df-27bf-11ef-af52-00155d000910","field3":"KuklinaES1@mos.ru"}
,
{"field1":"Кукушкин Данила Владимирович","field2":"cf2adf63-bee5-11ec-ac18-00155d051a08","field3":"KukushkinDV@mos.ru"}
,
{"field1":"Куприянова Елена Геннадьевна","field2":"5cb833f0-2836-11f1-b1e3-00155d000912","field3":"KupriyanovaEG@mos.ru"}
,
{"field1":"Купцов Владимир Сергеевич","field2":"340fed1e-9b43-11ef-afe6-00155d000910","field3":"KuptsovVS1@mos.ru"}
,
{"field1":"Курачева Ольга Александровна","field2":"4a86071b-8a02-11ef-afd0-00155d000912","field3":"KurachevaOA@mos.ru"}
,
{"field1":"Курджиева Марина Филаловна","field2":"17367350-d09e-11ee-aee1-00155d000910","field3":"KurdzhievaMF@mos.ru"}
,
{"field1":"Курмаева Валерия Юрьевна","field2":"f2d0e43e-4c87-11ee-ae38-00155d000910","field3":"KurmaevaVY@mos.ru"}
,
{"field1":"Курнакин Дмитрий Андреевич","field2":"78ae9ca3-ae91-11ed-ad6a-00155d000912","field3":"KurnakinDA@mos.ru"}
,
{"field1":"Куршев Никита Владиславович","field2":"a509107e-12fa-11ed-ac98-00155d000912","field3":"KurshevNV@mos.ru"}
,
{"field1":"Кутузова Ирина Алексеевна","field2":"85ae946a-e77d-11ef-b046-00155d000910","field3":"kutuzovaia1@mos.ru"}
,
{"field1":"Кутырева Наталья Валерьевна","field2":"5ebcf8d2-7701-11ee-ae6e-00155d000912","field3":"KutyrevaNV1@mos.ru"}
,
{"field1":"Кучкильдин Андрей Анварович","field2":"8a505415-a9ac-11ec-abfd-00155d051a08","field3":"KuchkildinAA@mos.ru"}
,
{"field1":"Кушева Мария Юрьевна","field2":"4b8867f8-fbb9-11ee-af1a-00155d000912","field3":"kushevamy@mos.ru"}
,
{"field1":"Кушнеревич Олеся Леонидовна","field2":"34aaf005-00cb-11f1-b1b0-00155d000910","field3":"kushnerevichol@mos.ru"}
,
{"field1":"Лазарев Рафаэль Владимирович","field2":"923e7c66-16a3-11f0-b082-00155d000912","field3":"lazarevrv@mos.ru"}
,
{"field1":"Ланин Герман Александрович","field2":"c89ff32c-73e3-11ea-a9a7-00155d1a230c","field3":"LaninGA@mos.ru"}
,
{"field1":"Ларина Валерия Николаевна","field2":"a0438c9a-d470-11ee-aee6-00155d000912","field3":"LarinaVN@mos.ru"}
,
{"field1":"Лёвкина Евгения Сергеевна","field2":"d24a2204-2902-11f1-b1e4-00155d000910","field3":"LevkinaES@mos.ru"}
,
{"field1":"Лесюк Георгий Андреевич","field2":"e575af0a-e4f1-11ee-aefd-00155d000912","field3":"LesyukGA@mos.ru"}
,
{"field1":"Липс Евгения Геннадьевна","field2":"f5f9ce86-d840-11ed-ad9f-00155d000912","field3":"LipsEG@it.mos.ru"}
,
{"field1":"Лисовская Екатерина Михайловна","field2":"858bdab5-1846-11eb-a9fb-00155d1a381f","field3":"LisovskayaEM@mos.ru"}
,
{"field1":"Литвинова Мария Валерьевна","field2":"c9c5b757-5e50-11f1-b228-00155d000912","field3":"litvinovamv1@mos.ru"}
,
{"field1":"Литвинова Полина Григорьевна","field2":"f6531d5f-2839-11f1-b1e3-00155d000912","field3":"LitvinovaPG@mos.ru"}
,
{"field1":"Лозовская Светлана Францевна","field2":"f4c600bc-f0be-11ee-af0c-00155d000912","field3":"LozovskayaSF@mos.ru"}
,
{"field1":"Лошкарева Ирина Анатольевна","field2":"dc4c0e4d-2e14-11ef-af5a-00155d000910","field3":"loshkarevaia@mos.ru"}
,
{"field1":"Лукашенко Михаил Алексеевич","field2":"43ed845e-ecff-11ef-b048-00155d000912","field3":"lukashenkoma@mos.ru"}
,
{"field1":"Лукина Юлия Анатольевна","field2":"858ee4bf-7740-11ef-afb8-00155d000912","field3":"LukinaYA4@mos.ru"}
,
{"field1":"Лукоянов Алексей Александрович","field2":"c6d9a753-071b-11f1-b1b8-00155d000910","field3":"lukoyanovaa1@mos.ru"}
,
{"field1":"Лукьянова Ирина Павловна","field2":"28f82a1a-787f-11ee-ae70-00155d000912","field3":"LukyanovaIP@mos.ru"}
,
{"field1":"Луньков Александр Валерьевич","field2":"cbd82977-0ecc-11f0-b073-00155d000910","field3":"lunkovav1@mos.ru"}
,
{"field1":"Львов Юрий Владимирович","field2":"19336669-68ad-11ec-abaa-00155d051a08","field3":"lvoff42@gmail.com"}
,
{"field1":"Любавина Елена Владимировна","field2":"7ab02b22-f8cd-11ef-b057-00155d000912","field3":"LyubavinaEV@mos.ru"}
,
{"field1":"Лявинец Евгений Михайлович","field2":"c3992bfb-cb73-11ec-ac28-00155d051a08","field3":"LyavinetsEM@mos.ru"}
,
{"field1":"Мазуров Виталий Александрович","field2":"293f94a4-dd72-11eb-aaf8-00155d051a08","field3":"MazurovVA@mos.ru"}
,
{"field1":"Мазуров Михаил Петрович","field2":"6cbb4ea2-a141-11ed-ad57-00155d000912","field3":"MazurovMP@mos.ru"}
,
{"field1":"Май Екатерина Алексеевна","field2":"a5827475-2846-11f1-b1e3-00155d000912","field3":"may.ekaterina.88@gmail.com"}
,
{"field1":"Макаров Геннадий Валерьевич","field2":"01b71a82-452f-11eb-aa35-00155d1a381f","field3":"MakarovGV@mos.ru"}
,
{"field1":"Макеева Елена Александровна","field2":"196219e4-d3d1-11e9-a994-00155d1a3432","field3":"MakeevaEA@mos.ru"}
,
{"field1":"Маковецкая Софья Вячеславовна","field2":"6b284577-0b25-11ec-ab32-00155d051a08","field3":"MakovetskayaSV@mos.ru"}
,
{"field1":"Максимова Ольга Владимировна","field2":"b9270628-d4e3-11ec-ac43-00155d000910","field3":"MaksimovaOV9@mos.ru"}
,
{"field1":"Маликова Ольга Сергеевна","field2":"69db2b3a-347b-11f0-b0a9-00155d000912","field3":"MalikovaOS@mos.ru"}
,
{"field1":"Малофеев Иван Юрьевич","field2":"a957daac-7aba-11ed-ad1f-00155d000910","field3":"MalofeevIY@mos.ru"}
,
{"field1":"Малышева Дарья Григорьевна","field2":"6a0877b4-4f5e-11ef-af84-00155d000910","field3":"MalyshevaDG@mos.ru"}
,
{"field1":"Манафова Фидан Рауфовна","field2":"1837d110-e205-11ef-b041-00155d000910","field3":"ManafovaFR@mos.ru"}
,
{"field1":"Мансуров Ришат Ахшантуевич","field2":"de195971-856a-11ef-afca-00155d000910","field3":"mansurovra@mos.ru"}
,
{"field1":"Мартынов Лев Александрович","field2":"accb9ef3-3843-11ef-af67-00155d000912","field3":"YudkinLA@mos.ru"}
,
{"field1":"Марусов Дмитрий Александрович","field2":"67aa2016-b171-11ea-a9b7-00155d1a2829","field3":"MarusovDA@mos.ru"}
,
{"field1":"Масалитина Алла Михайловна","field2":"0603082f-89fc-11ef-afd0-00155d000912","field3":"MasalitinaAM@mos.ru"}
,
{"field1":"Маслова Анастасия Ивановна","field2":"317390f3-4aa7-11f1-b20f-00155d000912","field3":"maslovaai2@mos.ru"}
,
{"field1":"Масловская Анна Андреевна","field2":"9185c334-fb63-11ec-ac7a-00155d000912","field3":"MaslovskayaAA1@mos.ru"}
,
{"field1":"Матвеев Борис Николаевич","field2":"50153bc9-379c-11ef-af66-00155d000910","field3":"MatveevBN@mos.ru"}
,
{"field1":"Матвеева Инна Александровна","field2":"7e84820e-2842-11f1-b1e3-00155d000912","field3":"MatveevaIA3@mos.ru"}
,
{"field1":"Машошин Евгений Геннадьевич","field2":"522b0c61-429c-11ef-af74-00155d000912","field3":"mashoshineg@mos.ru"}
,
{"field1":"Медведев Сергей Владиславович","field2":"35e97c60-795c-11ee-ae71-00155d000910","field3":"MedvedevSV6@mos.ru"}
,
{"field1":"Медников Руслан Владимирович","field2":"f1c3529a-a213-11ed-ad58-00155d000910","field3":"MednikovRV@mos.ru"}
,
{"field1":"Межиев Магомед Заурбекович","field2":"8553b16e-2a6e-11ec-ab5a-00155d051a08","field3":"MezhievMZ@mos.ru"}
,
{"field1":"Мелешков Сергей Олегович","field2":"73b515aa-b0ee-11ed-ad6d-00155d000912","field3":"MeleshkovSO@mos.ru"}
,
{"field1":"Мельниченко Наталья Сергеевна","field2":"43d219fc-c900-11f0-b167-00155d000912","field3":"melnichenkons1@mos.ru"}
,
{"field1":"Мечетин Алексей Васильевич","field2":"74cad695-5889-11ef-af90-00155d000912","field3":"MechetinAV@mos.ru"}
,
{"field1":"Мещерякова Наталия Игоревна","field2":"4ee0318a-2838-11f1-b1e3-00155d000912","field3":"mescheryakovani2@mos.ru"}
,
{"field1":"Милянцевич Марина Андреевна","field2":"15e45182-68f6-11ef-afa5-00155d000910","field3":"MilyantsevichMA@mos.ru"}
,
{"field1":"Миронов Андрей Иванович","field2":"2bc06777-7fc5-11ef-afc3-00155d000912","field3":"MironovAI5@mos.ru"}
,
{"field1":"Мифтахова Альбина Ильгизовна","field2":"f9b0aeb3-599d-11f1-b222-00155d000912","field3":"miftakhovaai@it.mos.ru"}
,
{"field1":"Михайлов Александр Сергеевич","field2":"3b2f519d-3908-11ef-af68-00155d000912","field3":"MikhaylovAS4@mos.ru"}
,
{"field1":"Михайлова Анастасия Александровна","field2":"e90fc508-7e07-11ee-ae77-00155d000912","field3":"mikhaylova.anastasia.2004@mail.ru"}
,
{"field1":"Михеев Денис Александрович","field2":"3db60382-aebe-11ee-aeb6-00155d000912","field3":"MikheevDA5@mos.ru"}
,
{"field1":"Михейкина Наталья Анатольевна","field2":"85915079-a361-11ec-abf5-00155d051a08","field3":"MikheykinaNA@mos.ru"}
,
{"field1":"Мкртчян Мария Тиграновна","field2":"d7ac7ccb-4b64-11ec-ab84-00155d051a08","field3":"MkrtchyanMT@mos.ru"}
,
{"field1":"Молочкова Наталья Александровна","field2":"67f91578-b1cf-11ed-ad6e-00155d000912","field3":"MolochkovaNA@mos.ru"}
,
{"field1":"Монастырский Евгений Сергеевич","field2":"113cb516-db42-11ec-ac4d-00155d000912","field3":"MonastyrskiyES@mos.ru"}
,
{"field1":"Морозова Нина Юрьевна","field2":"09be8e0e-c239-11ed-ad83-00155d000912","field3":"morozovany5@mos.ru"}
,
{"field1":"Мостовая Олеся Юрьевна","field2":"843e5df1-2903-11f1-b1e4-00155d000910","field3":"MostovayaOY@mos.ru"}
,
{"field1":"Мохна Юрий Николаевич","field2":"2db1df4f-4f5e-11ef-af84-00155d000910","field3":"MokhnaYN1@mos.ru"}
,
{"field1":"Мохова Елена Дмитриевна","field2":"7845e8f3-c461-11ef-b01a-00155d000912","field3":"MokhovaED@mos.ru"}
,
{"field1":"Мурашевская Татьяна Владимировна","field2":"09abed97-1846-11eb-a9fb-00155d1a381f","field3":"MurashevskayaTV@mos.ru"}
,
{"field1":"Назарова Александра Витальевна","field2":"3533f578-ea8c-11e9-a996-00155d1a3432","field3":"KorolevaAV1@mos.ru"}
,
{"field1":"Настасюк Евгений Владимирович","field2":"ae018724-0411-11ec-ab29-00155d051a08","field3":"NastasyukEV@mos.ru"}
,
{"field1":"Натыкина Елена Васильевна","field2":"1dd4c906-cc98-11ee-aedc-00155d000912","field3":"NatykinaEV@mos.ru"}
,
{"field1":"Невзоров Алексей Александрович","field2":"027cd2ef-46b0-11f0-b0c0-00155d000910","field3":"nevzorovaa2@mos.ru"}
,
{"field1":"Невзорова Екатерина Петровна","field2":"55e67734-69dd-11eb-aa66-00155d1a381f","field3":"nevzorovaep1@mos.ru"}
,
{"field1":"Неганов Дмитрий Андреевич","field2":"f03fe76a-fa8b-11f0-b1a8-00155d000910","field3":"neganovda1@mos.ru"}
,
{"field1":"Нефедов Александр Игоревич","field2":"9f54ad7f-c5f3-11ec-ac21-00155d051a08","field3":"NefedovAI@mos.ru"}
,
{"field1":"Нефедова Анна Викторовна","field2":"25273945-a1a8-11eb-aaad-00155d1a381f","field3":"NefedovaAV1@mos.ru"}
,
{"field1":"Нехлопочина Инна Сергеевна","field2":"341bb5b8-650e-11ef-afa0-00155d000912","field3":"nekhlopochinais@mos.ru"}
,
{"field1":"Никанорова Мария Максимовна","field2":"8028cb97-1963-11ed-aca1-00155d000912","field3":"NikanorovaMM@mos.ru"}
,
{"field1":"Никитина Ксения Владимировна","field2":"4c1f6803-5e93-11f1-b228-00155d000912","field3":"NikitinaKV4@it.mos.ru"}
,
{"field1":"Никитченко Илья Андреевич","field2":"3b28b49b-68f8-11ef-afa5-00155d000910","field3":"NikitchenkoIA@mos.ru"}
,
{"field1":"Нилов Илья Николаевич","field2":"045ad09d-5ffe-11ed-acfc-00155d000910","field3":"ilyanilov1987@gmail.com"}
,
{"field1":"Новицкая Владислава Андреевна","field2":"77d184cd-8632-11ef-afcb-00155d000910","field3":"novitskayava1@mos.ru"}
,
{"field1":"Носачева Анна Андреевна","field2":"a658b016-3a01-11f0-b0b0-00155d000910","field3":"NosachevaAA@mos.ru"}
,
{"field1":"Носкова Екатерина Евгеньевна","field2":"b7ed420b-d6d1-11ee-aee9-00155d000912","field3":"ulybinaee@mos.ru"}
,
{"field1":"Оболенская Татьяна Ивановна","field2":"5eb3bddf-9e10-11ed-ad53-00155d000912","field3":"ObolenskayaTI@mos.ru"}
,
{"field1":"Огай Игорь Александрович","field2":"bb1f6688-87c6-11f0-b114-00155d000912","field3":"ogayia@mos.ru"}
,
{"field1":"Олитто Алиса Андреевна","field2":"7dc9f951-d604-11ee-aee8-00155d000912","field3":"olittoaa@mos.ru"}
,
{"field1":"Ордина Юлия Игоревна","field2":"467963cc-d610-11ee-aee8-00155d000912","field3":"OrdinaYI@mos.ru"}
,
{"field1":"Орешкин Анатолий Александрович","field2":"df6a0332-93a9-11f0-b123-00155d000910","field3":"oreshkinaa5@mos.ru"}
,
{"field1":"Оруджев Руслан Тариелевич","field2":"a51b409c-b670-11ed-ad74-00155d000912","field3":"OrudzhevRT@mos.ru"}
,
{"field1":"Орудин Даниил Евгеньевич","field2":"d84f253d-1610-11f1-b1cb-00155d000912","field3":"OrudinDE@it.mos.ru"}
,
{"field1":"Осокина Екатерина Валерьевна","field2":"e04e7594-60d7-11f1-b22b-00155d000912","field3":"OsokinaEV2@it.mos.ru"}
,
{"field1":"Павлов Егор Андреевич","field2":"651b61c5-29ea-11ee-ae0b-00155d000912","field3":"pavlovea5@mos.ru"}
,
{"field1":"Павлова Елена Владимировна","field2":"a6f412da-0881-11eb-a9e7-00155d1a381f","field3":"PavlovaEV8@mos.ru"}
,
{"field1":"Павлушкина Мария Александровна","field2":"9b84090c-445c-11eb-aa34-00155d1a381f","field3":"PavlushkinaMA1@mos.ru"}
,
{"field1":"Пальмина Мария Александровна","field2":"8c62f6af-0bbb-11ec-ab33-00155d051a08","field3":"PalminaMA2@mos.ru"}
,
{"field1":"Панов Владимир Алексеевич","field2":"45dc113a-9787-11f0-b128-00155d000912","field3":"panoff.2000@yandex.ru"}
,
{"field1":"Пароходов Дмитрий Юрьевич","field2":"336db161-a202-11ed-ad58-00155d000910","field3":"ParokhodovDY@mos.ru"}
,
{"field1":"Парфенова Татьяна Борисовна","field2":"69057e6e-6e86-11ea-a9a4-00155d1a230c","field3":"ParfenovaTB@mos.ru"}
,
{"field1":"Пасенков Максим Владимирович","field2":"1cbe993d-5e6c-11ed-acfa-00155d000912","field3":"PasenkovMV@mos.ru"}
,
{"field1":"Пастушков Дмитрий Викторович","field2":"5327497f-d76f-11ed-ad9e-00155d000912","field3":"pastushkovdv@mos.ru"}
,
{"field1":"Пасугинова Мария Германовна","field2":"adc9c4af-160a-11f1-b1cb-00155d000912","field3":"pasuginovamg@mos.ru"}
,
{"field1":"Первушина Надежда Сергеевна","field2":"5a40701c-5551-11ef-af8c-00155d000912","field3":"PervushinaNS@mos.ru"}
,
{"field1":"Переход Артур Анатольевич","field2":"b7fcb954-283d-11f1-b1e3-00155d000912","field3":"PerekhodAA@mos.ru"}
,
{"field1":"Перова Ирина Владимировна","field2":"3d980d7a-e4f4-11ee-aefd-00155d000912","field3":"PerovaIV@mos.ru"}
,
{"field1":"Петрова Елена Вячеславовна","field2":"cfce59ee-28cd-11eb-aa10-00155d1a381f","field3":"PetrovaEV9@mos.ru"}
,
{"field1":"Петрякова Елена Николаевна","field2":"c74b9f6a-f4d0-11ef-b052-00155d000910","field3":"PetryakovaEN@mos.ru"}
,
{"field1":"Пирожков Илья Александрович","field2":"8d2b40b7-0583-11f1-b1b6-00155d000912","field3":"pirozhkovia@mos.ru"}
,
{"field1":"Питель Анна Алексеевна","field2":"95254cb3-f002-11ed-adbd-00155d000912","field3":"PitelAA@mos.ru"}
,
{"field1":"Платонова Мария Юрьевна","field2":"7c5e94dc-06be-11e9-a98f-00155d1a3433","field3":"KleymenovaMY@mos.ru"}
,
{"field1":"Плетнева Эльмира Ярулловна","field2":"d51922ed-7ce8-11ec-abc4-00155d051a08","field3":"PletnevaEY1@mos.ru"}
,
{"field1":"Плехов Михаил Владимирович","field2":"368efd5a-664e-11ed-ad05-00155d000910","field3":"PlekhovMV@mos.ru"}
,
{"field1":"Плотникова Алёна Сергеевна","field2":"39a4d764-aefe-11ee-aeb6-00155d000912","field3":"PlotnikovaAS1@mos.ru"}
,
{"field1":"Плохих Виктория Максимовна","field2":"019babf9-10fc-11ef-af35-00155d000912","field3":"PlokhikhVM@mos.ru"}
,
{"field1":"Погорелов Сергей Евгеньевич","field2":"954420c9-48c7-11ef-af7c-00155d000912","field3":"pogorelovse1@mos.ru"}
,
{"field1":"Подей Ольга Ивановна","field2":"ba3a959f-9c04-11ea-a9aa-00155d1a230c","field3":"PodejOI@mos.ru"}
,
{"field1":"Подлегаев-Головин Александр Дмитриевич","field2":"35cfcf07-fb01-11ee-af19-00155d000912","field3":"podlegaevgolovinad@mos.ru"}
,
{"field1":"Подосинова Анна Эмануиловна","field2":"cc072493-4e7d-11ea-a99f-00155d1a38ec","field3":"PodosinovaAE@mos.ru"}
,
{"field1":"Пожидаев Роман Олегович","field2":"fded8066-518a-11f0-b0ce-00155d000912","field3":"pozhidaevro@mos.ru"}
,
{"field1":"Поклонова Наталия Сергеевна","field2":"ec245c06-aec4-11ee-aeb6-00155d000912","field3":"PoklonovaNS@mos.ru"}
,
{"field1":"Поликарпова Карина Александровна","field2":"9e2bc0ad-4518-11eb-aa35-00155d1a381f","field3":"PolikarpovaKA@mos.ru"}
,
{"field1":"Полозова Анастасия Михайловна","field2":"0c10b326-3046-11ee-ae13-00155d000912","field3":"PolozovaAM@mos.ru"}
,
{"field1":"Полтавская Наталья Александровна","field2":"087227c0-4459-11eb-aa34-00155d1a381f","field3":"PoltavskayaNA1@mos.ru"}
,
{"field1":"Полякова Юлия Анатольевна","field2":"9b345a0e-d261-11eb-aaeb-00155d1a1df7","field3":"PolyakovaYA@mos.ru"}
,
{"field1":"Пономарёв Кирилл Сергеевич","field2":"b4dd3431-59c7-11f1-b222-00155d000912","field3":"ponomarevks@it.mos.ru"}
,
{"field1":"Попова Анастасия Александровна","field2":"f3646a00-2835-11f1-b1e3-00155d000912","field3":"PopovaAA3@mos.ru"}
,
{"field1":"Поспелов Семён Николаевич","field2":"dcc051f0-6602-11f0-b0e8-00155d000912","field3":"pospelovsn@mos.ru"}
,
{"field1":"Потапова Александра Сергеевна","field2":"93b15bd0-3bf1-11e9-a98f-00155d1a3433","field3":"PotapovaAS@mos.ru"}
,
{"field1":"Потошова Полина Романовна","field2":"2f682152-710e-11f0-b0f7-00155d000912","field3":"potoshovapr1@mos.ru"}
,
{"field1":"Преженцев Егор Матвеевич","field2":"e8af0642-d26b-11eb-aaeb-00155d1a1df7","field3":"PrezhentsevEM@mos.ru"}
,
{"field1":"Пудышев Никита Вадимович","field2":"3f2f9845-e4ac-11f0-b18c-00155d000910","field3":"PudyshevNV@mos.ru"}
,
{"field1":"Пылова Наталья Сергеевна","field2":"84badf29-13f9-11e9-a98f-00155d1a3433","field3":"PylovaNS@mos.ru"}
,
{"field1":"Пышный Игорь Олегович","field2":"1e291a5a-9fff-11ef-afec-00155d000910","field3":"pyshnyyio@mos.ru"}
,
{"field1":"Пышный Игорь Олегович","field2":"f118430d-d421-11f0-b177-00155d000912","field3":"pyshnyyio@mos.ru"}
,
{"field1":"Райдер Екатерина Сергеевна","field2":"de0e52ef-1978-11ee-adf4-00155d000910","field3":"krayder9@gmail.com"}
,
{"field1":"Ракус Мария Сергеевна","field2":"d37c2ad4-aa93-11f0-b140-00155d000912","field3":"rakusms@mos.ru"}
,
{"field1":"Ременникова Дарья Михайловна","field2":"0d377064-542f-11f1-b21b-00155d000912","field3":"remennikovadm@mos.ru"}
,
{"field1":"Репин Виктор Андреевич","field2":"482bbe96-f044-11f0-b19b-00155d000910","field3":"repinva2@mos.ru"}
,
{"field1":"Родкина Анастасия Валентиновна","field2":"7bac23ce-cfd4-11ee-aee0-00155d000910","field3":"RodkinaAV@mos.ru"}
,
{"field1":"Романов Николай Евгеньевич","field2":"9cb2a5cb-c716-11ee-aed5-00155d000910","field3":"romanovne@mos.ru"}
,
{"field1":"Ромашкина Надежда Александровна","field2":"f7373a4a-a0df-11eb-aaac-00155d1a381f","field3":"RomashkinaNA@mos.ru"}
,
{"field1":"Рубцова Анастасия Юрьевна","field2":"396728b6-7698-11ef-afb7-00155d000910","field3":"RubtsovaAY@mos.ru"}
,
{"field1":"Рудакова Дарья Константиновна","field2":"bc615e79-7e13-11ee-ae77-00155d000912","field3":"RudakovaDK@mos.ru"}
,
{"field1":"Рукавишникова Варвара Федоровна","field2":"5806ef7c-adc8-11ed-ad69-00155d000912","field3":"RukavishnikovaVF@mos.ru"}
,
{"field1":"Румянцева Анна Владимировна","field2":"b2f956df-5e7f-11f1-b228-00155d000912","field3":"RumyantsevaAV8@it.mos.ru"}
,
{"field1":"Русак Анна Андреевна","field2":"6d078731-65fd-11f0-b0e8-00155d000912","field3":"rusakaa@mos.ru"}
,
{"field1":"Рыжов Павел Борисович","field2":"1ca7bdc4-f063-11ec-ac6a-00155d000912","field3":"RyzhovPB@mos.ru"}
,
{"field1":"Рябова Наталья Леонидовна","field2":"75f87875-2d24-11f1-b1e9-00155d000910","field3":"RyabovaNL@mos.ru"}
,
{"field1":"Рябова Олеся Алексеевна","field2":"186688b9-2e6b-11f1-b1eb-00155d000910","field3":"ryabovaoa5@mos.ru"}
,
{"field1":"Саакян Жанна Хачатуровна","field2":"e2f3c8a0-4e97-11f1-b214-00155d000910","field3":"saakyanzk@mos.ru"}
,
{"field1":"Савва Екатерина Анатольевна","field2":"eca5cbbb-53d8-11ef-af8a-00155d000912","field3":"larryisrealforever74@gmail.com"}
,
{"field1":"Садыкова Заррина Сергеевна","field2":"1ac68c93-3984-11ed-accb-00155d000910","field3":"zayatut@bk.ru"}
,
{"field1":"Сазонов Игорь Александрович","field2":"2e4afa78-0380-11ee-add7-00155d000912","field3":"SazonovIA1@mos.ru"}
,
{"field1":"Самигуллина Эльвира Фирдауисовна","field2":"a1202dc4-2e7f-11ed-acbd-00155d000910","field3":"SamigullinaEF@mos.ru"}
,
{"field1":"Самойлова Полина Юрьевна","field2":"37cf2c4d-484d-11f1-b20c-00155d000912","field3":"samoylovapy@mos.ru"}
,
{"field1":"Самосудова Екатерина Евгеньевна","field2":"225e7ab8-1b05-11ee-adf8-00155d000912","field3":"samosudovaee@mos.ru"}
,
{"field1":"Самсонова Софья Павловна","field2":"e7fa36ba-5bd2-11f0-b0db-00155d000912","field3":"samsonovasp1@mos.ru"}
,
{"field1":"Сапицкая Кира Сергеевна","field2":"7ffe75ac-cef0-11ee-aedf-00155d000910","field3":"sapitskayaks@mos.ru"}
,
{"field1":"Сапрыкина Яна Андреевна","field2":"7b95158e-dfb1-11ee-aef6-00155d000910","field3":"yana.saprykina.01@inbox.ru"}
,
{"field1":"Саратина Валентина Александровна","field2":"9d39b990-6214-11f0-b0e3-00155d000912","field3":"saratinava@mos.ru"}
,
{"field1":"Сауляк Кирилл Андреевич","field2":"e038d315-e567-11ec-ac5c-00155d000912","field3":"SaulyakKA@mos.ru"}
,
{"field1":"Сафронов Павел Владимирович","field2":"80e1cb96-2ce2-11f1-b1e9-00155d000910","field3":"darthspv@gmail.com"}
,
{"field1":"Седнев Андрей Алексеевич","field2":"37e9ba80-82c2-11ee-ae7d-00155d000912","field3":"SednevAA1@mos.ru"}
,
{"field1":"Седова Александра Геннадьевна","field2":"767848fc-b3de-11ec-ac0a-00155d051a08","field3":"SedovaAG@mos.ru"}
,
{"field1":"Седова Александра Геннадьевна","field2":"5cd95fd7-cec4-11f0-b170-00155d000912","field3":"SedovaAG@mos.ru"}
,
{"field1":"Седова Татьяна Владимировна","field2":"740a6488-b080-11ef-b001-00155d000910","field3":"SedovaTV2@mos.ru"}
,
{"field1":"Селезнева Елена Анатольевна","field2":"62ea6a17-283f-11f1-b1e3-00155d000912","field3":"SeleznevaEA2@mos.ru"}
,
{"field1":"Селезнева Юлия Дмитриевна","field2":"abdd223f-7239-11ee-ae68-00155d000912","field3":"SeleznevaYD@mos.ru"}
,
{"field1":"Селиванникова Анастасия Андреевна","field2":"4e05b4b1-ffdd-11ef-b060-00155d000912","field3":"selivannikovaaa@mos.ru"}
,
{"field1":"Семенихина Анна Александровна","field2":"68361432-2845-11f1-b1e3-00155d000912","field3":"SemenikhinaAA1@mos.ru"}
,
{"field1":"Семенов Сергей Иванович","field2":"48f8e4de-d01a-11f0-b172-00155d000912","field3":"semenovsi4@mos.ru"}
,
{"field1":"Семёнова Анастасия Владиславовна","field2":"bb6449ba-0ed2-11f0-b073-00155d000910","field3":"SemenovaAV36@mos.ru"}
,
{"field1":"Семенова Дарья Александровна","field2":"780d7669-fd7a-11ef-b05d-00155d000912","field3":"SemenovaDA5@mos.ru"}
,
{"field1":"Семёнова Яна Алексеевна","field2":"31106b7b-235b-11f1-b1dd-00155d000912","field3":"semenovaya7@mos.ru"}
,
{"field1":"Сергеева Анна Владимировна","field2":"40c3a5ef-ea12-11f0-b193-00155d000910","field3":"SergeevaAV17@mos.ru"}
,
{"field1":"Сергеева Екатерина Евгеньевна","field2":"2eae260b-862d-11eb-aa8a-00155d1a381f","field3":"PolyakovaEE@mos.ru"}
,
{"field1":"Серегина Наталья Александровна","field2":"b6e65cad-e38f-11ef-b043-00155d000910","field3":"SereginaNA1@mos.ru"}
,
{"field1":"Серпкова Вероника Анатольевна","field2":"7ef91286-fbf0-11ea-a9d8-00155d1a381f","field3":"SerpkovaVA1@mos.ru"}
,
{"field1":"Сибагатуллина Лейсан Дамировна","field2":"5dd6f624-2174-11ef-af4a-00155d000910","field3":"sibagatullinald@mos.ru"}
,
{"field1":"Сидоров Алексей Юрьевич","field2":"8cb63d19-4a6b-11ef-af7e-00155d000912","field3":"sidorovay4@mos.ru"}
,
{"field1":"Сизова Анна Романовна","field2":"3412f8b3-1e88-11f0-b08c-00155d000910","field3":"SizovaAR@mos.ru"}
,
{"field1":"Сироткин Михаил Сергеевич","field2":"20785686-114c-11f1-b1c5-00155d000912","field3":"sirotkinms1@mos.ru"}
,
{"field1":"Скакун Екатерина Александровна","field2":"190f65b7-a683-11eb-aab3-00155d1a381f","field3":"ekaterina-alexandrovna1993@rambler.ru"}
,
{"field1":"Сметанкина Ольга Владимировна","field2":"05b975a3-4e0f-11ed-ace5-00155d000910","field3":"SmetankinaOV@mos.ru"}
,
{"field1":"Соболева Юлия Александровна","field2":"b41b9cde-1601-11f1-b1cb-00155d000912","field3":"sobolevaya3@mos.ru"}
,
{"field1":"Соколов Алексей Владимирович","field2":"1dee8c6e-c5f2-11f0-b163-00155d000910","field3":"sokolovav26@mos.ru"}
,
{"field1":"Соколов Максим Евгеньевич","field2":"27a80cc4-d2b9-11ed-ad98-00155d000910","field3":"SokolovME1@mos.ru"}
,
{"field1":"Соколов Роман Олегович","field2":"9e3f9a7b-dfbf-11ef-b03e-00155d000912","field3":"sokolovro@mos.ru"}
,
{"field1":"Солдатова Ирина Наилевна","field2":"c005fd21-44f2-11eb-aa35-00155d1a381f","field3":"SoldatovaIN@mos.ru"}
,
{"field1":"Солнцева Анастасия Сергеевна","field2":"f658ab9d-37ac-11f0-b0ad-00155d000910","field3":"SolntsevaAS@mos.ru"}
,
{"field1":"Соловьянов Алексей Александрович","field2":"43fd6d2e-b2d8-11ef-b004-00155d000910","field3":"SolovyanovAA@mos.ru"}
,
{"field1":"Солодова Виктория Романовна","field2":"3c39da64-5876-11ef-af90-00155d000912","field3":"SolodovaVR@mos.ru"}
,
{"field1":"Сорокина Анна Викторовна","field2":"6a65204d-d0b4-11ee-aee1-00155d000910","field3":"SorokinaAV9@mos.ru"}
,
{"field1":"Спецакова Виктория Евгеньевна","field2":"18facc28-2182-11ef-af4a-00155d000910","field3":"spetsakovave@mos.ru"}
,
{"field1":"Спивакова Дария Владимировна","field2":"3b522b37-1d8b-11ef-af45-00155d000912","field3":"SmirnovaDV4@mos.ru"}
,
{"field1":"Спиридонова Алёна Юрьевна","field2":"b9a721ee-70d0-11ef-afaf-00155d000912","field3":"SpiridonovaAY@mos.ru"}
,
{"field1":"Старовойтова Оксана Николаевна","field2":"b75d50c9-19c3-11f0-b086-00155d000912","field3":"StarovoytovaON@mos.ru"}
,
{"field1":"Степаненко Дмитрий Романович","field2":"0f659660-2535-11ee-ae05-00155d000912","field3":"StepanenkoDR@mos.ru"}
,
{"field1":"Степанова Анна Алексеевна","field2":"27180a6b-03c5-11f0-b065-00155d000912","field3":"StepanovaAA6@mos.ru"}
,
{"field1":"Степанько Анастасия Вячеславовна","field2":"fea04f60-2843-11f1-b1e3-00155d000912","field3":"StepankoAV@mos.ru"}
,
{"field1":"Страковский Илья Дмитриевич","field2":"4d8b7384-0374-11ee-add7-00155d000912","field3":"StrakovskiyID@mos.ru"}
,
{"field1":"Стрелкова Галина Юрьевна","field2":"7b4313b4-5f30-11ec-ab9d-00155d051a08","field3":"g_strelkova@mail.ru"}
,
{"field1":"Строгонова Алина Александровна","field2":"97e5f352-6c46-11f0-b0f1-00155d000910","field3":"fedorovaaa2@mos.ru"}
,
{"field1":"Ступина Карина Александровна","field2":"ee30f569-5ec1-11ef-af98-00155d000912","field3":"StupinaKA@mos.ru"}
,
{"field1":"Суздалева Екатерина Васильевна","field2":"e7490d98-5964-11eb-aa51-00155d1a381f","field3":"SuzdalevaEV@mos.ru"}
,
{"field1":"Суров Даниил Сергеевич","field2":"f9536d7b-8389-11ee-ae7e-00155d000912","field3":"SurovDS@mos.ru"}
,
{"field1":"Сухорученко Алла Васильевна","field2":"851cf7e6-10f8-11ef-af35-00155d000912","field3":"SukhoruchenkoAV@mos.ru"}
,
{"field1":"Тамаева Радимхан Албуриевна","field2":"b4e96a79-678e-11f0-b0ea-00155d000912","field3":"tamaevara@mos.ru"}
,
{"field1":"Тарасова Дарья Михайловна","field2":"2a3337ea-363b-11ec-ab69-00155d051a08","field3":"tarasovadm@mos.ru"}
,
{"field1":"Тарасова Елизавета Александровна","field2":"04a2c285-0ae2-11f0-b06e-00155d000912","field3":"SolodukhinaEA@mos.ru"}
,
{"field1":"Тарасова Юлия Батаровна","field2":"4a9d637a-f1e8-11f0-b19d-00155d000910","field3":"juotchepkova@mail.ru"}
,
{"field1":"Таршилова Дарья Денисовна","field2":"7a0eded1-eccf-11ee-af07-00155d000910","field3":"TarshilovaDD@mos.ru"}
,
{"field1":"Текунова Наталия Юрьевна","field2":"f61748aa-0ae2-11f0-b06e-00155d000912","field3":"tekunovany@mos.ru"}
,
{"field1":"Тимакова Маргарита Сергеевна","field2":"91eba1a2-9bba-11ed-ad50-00155d000912","field3":"GegechkoriMS@mos.ru"}
,
{"field1":"Тимофеев Алексей Романович","field2":"8bf6ed13-9773-11ef-afe1-00155d000912","field3":"alian.tim.job24@gmail.com"}
,
{"field1":"Титкова Елена Андреевна","field2":"c2959716-6f40-11ef-afad-00155d000912","field3":"TitkovaEA2@mos.ru"}
,
{"field1":"Титова Елена Николаевна","field2":"ac5d5ad3-c58f-11ee-aed3-00155d000912","field3":"TitovaEN5@mos.ru"}
,
{"field1":"Титова Елизавета Сергеевна","field2":"4e76da36-8e14-11f0-b11c-00155d000910","field3":"titovaes1@mos.ru"}
,
{"field1":"Тихонов Андрей Викторович","field2":"4160379d-639c-11f0-b0e5-00155d000912","field3":"tikhonovav7@mos.ru"}
,
{"field1":"Тихонов Тимофей Лукьянович","field2":"b4af3cf2-e791-11ef-b046-00155d000910","field3":"TikhonovTL@mos.ru"}
,
{"field1":"Тишкова Елизавета Валерьевна","field2":"c5558a81-4004-11ee-ae28-00155d000912","field3":"TishkovaEV2@mos.ru"}
,
{"field1":"Ткачев Егор Дмитриевич","field2":"62cceee3-2848-11f1-b1e3-00155d000912","field3":"tkacheved@mos.ru"}
,
{"field1":"Товкач Максим Николаевич","field2":"31d29a21-69e5-11eb-aa66-00155d1a381f","field3":"TovkachMN@mos.ru"}
,
{"field1":"Токовая Анна Владимировна","field2":"bc6dbda5-9d70-11ee-aea0-00155d000910","field3":"anettok1324@gmail.com"}
,
{"field1":"Толканов Константин Александрович","field2":"0ca348e2-e12d-11ea-a9ca-00155d1a381f","field3":"TolkanovKA@mos.ru"}
,
{"field1":"Томилова Александра Анатольевна","field2":"979da903-ca3c-11ee-aed9-00155d000910","field3":"TomilovaAA1@mos.ru"}
,
{"field1":"Трегулов Ильдар Фяритович","field2":"dbd91dce-9690-11ef-afe0-00155d000910","field3":"TregulovIF@mos.ru"}
,
{"field1":"Тришина Людмила Артуровна","field2":"de9ffcaf-0878-11ef-af2a-00155d000912","field3":"TrishinaLA@mos.ru"}
,
{"field1":"Трофимова Екатерина Валерьевна","field2":"2b241366-97f8-11ee-ae99-00155d000912","field3":"trofimovaev@mos.ru"}
,
{"field1":"Труфанов Сергей Андреевич","field2":"62c29c5f-52f2-11ef-af89-00155d000910","field3":"TrufanovSA@mos.ru"}
,
{"field1":"Тычко Лариса Владимировна","field2":"8ac31542-c02b-11ea-a9c0-00155d1a381f","field3":"TychkoLV@mos.ru"}
,
{"field1":"Тюрина Анна Александровна","field2":"f3cb3aa3-3ff3-11ee-ae28-00155d000912","field3":"TyurinaAA6@mos.ru"}
,
{"field1":"Удалов Кирилл Александрович","field2":"ef393165-283f-11f1-b1e3-00155d000912","field3":"UdalovKA@mos.ru"}
,
{"field1":"Удачина Юлия Ивановна","field2":"57107999-c3d4-11ed-ad85-00155d000912","field3":"udachinayi@mos.ru"}
,
{"field1":"Уколова Елена Николаевна","field2":"84badf17-13f9-11e9-a98f-00155d1a3433","field3":"UkolovaEN@mos.ru"}
,
{"field1":"Уралова Ирина Сергеевна","field2":"8263f1cc-03df-11ec-ab29-00155d051a08","field3":"RogovaIS@mos.ru"}
,
{"field1":"Устинов Станислав Николаевич","field2":"eb015f60-5c8f-11f0-b0dc-00155d000912","field3":"ustinovsn1@mos.ru"}
,
{"field1":"Уткин Иван Вячеславович","field2":"1ecaaf18-2064-11ec-ab4d-00155d051a08","field3":"UtkinIV@mos.ru"}
,
{"field1":"Уткина Надежда Евгеньевна","field2":"d5997c86-4443-11eb-aa34-00155d1a381f","field3":"UtkinaNE@mos.ru"}
,
{"field1":"Ухина Наталья Вениаминовна","field2":"24ef102b-607b-11f0-b0e1-00155d000912","field3":"ukhinanv@mos.ru"}
,
{"field1":"Федина Ольга Николаевна","field2":"3a844461-a200-11ed-ad58-00155d000910","field3":"FedinaON@mos.ru"}
,
{"field1":"Федоров Филипп Игоревич","field2":"178e788a-58ed-11ed-acf3-00155d000912","field3":"FedorovFI2@mos.ru"}
,
{"field1":"Федулов Вадим Владимирович","field2":"dfec3a2f-2979-11f0-b09a-00155d000910","field3":"FedulovVV1@mos.ru"}
,
{"field1":"Федулова Мария Вадимовна","field2":"4dc742b4-7f40-11ec-abc7-00155d051a08","field3":"FedulovaMV@mos.ru"}
,
{"field1":"Филатов Андрей Юрьевич","field2":"43687673-b1b7-11ed-ad6e-00155d000912","field3":"FilatovAY4@mos.ru"}
,
{"field1":"Филенко Иван Владимирович","field2":"8da4d5a7-42c7-11f1-b205-00155d000912","field3":"filenkoiv@mos.ru"}
,
{"field1":"Филипкин Владимир Владимирович","field2":"5951e685-21a8-11f0-b090-00155d000912","field3":"FilipkinVV1@mos.ru"}
,
{"field1":"Филиппов Алексей Игоревич","field2":"7c69b30e-f11a-11f0-b19c-00155d000912","field3":"filippovai1@mos.ru"}
,
{"field1":"Фисунова Ольга Дмитриевна","field2":"91a1bf67-b933-11ef-b00c-00155d000912","field3":"FisunovaOD@mos.ru"}
,
{"field1":"Фогель Виктория Сергеевна","field2":"cda73f20-3a98-11ef-af6a-00155d000912","field3":"lukinavs1@mos.ru"}
,
{"field1":"Фон Анна Александровна","field2":"ff748d83-4407-11ef-af76-00155d000912","field3":"fonaa@mos.ru"}
,
{"field1":"Фролова Дарья Владимировна","field2":"3cdf20e7-5073-11ee-ae3d-00155d000912","field3":"OzhoginaDV@mos.ru"}
,
{"field1":"Хабибуллина Алиса Константиновна","field2":"fffdf58f-b498-11f0-b14d-00155d000910","field3":"KhabibullinaAK@mos.ru"}
,
{"field1":"Хайртдинов Тимур Ильгизович","field2":"3d3f480f-7358-11f0-b0fa-00155d000912","field3":"radyrabochaya@mail.ru"}
,
{"field1":"Хертек Долаана Александровна","field2":"7c4588d6-df7e-11ee-aef6-00155d000910","field3":"KhertekDA@mos.ru"}
,
{"field1":"Хорошилова Екатерина Леонидовна","field2":"cb10f96d-58d7-11f1-b221-00155d000912","field3":"KhoroshilovaEL@it.mos.ru"}
,
{"field1":"Хрыпченко Любовь Сергеевна","field2":"39f356dc-8ac9-11ef-afd1-00155d000912","field3":"KhrypchenkoLS@mos.ru"}
,
{"field1":"Цапковская Людмила Витальевна","field2":"94c10f5c-ff36-11eb-ab23-00155d051a08","field3":"TsapkovskayaLV1@mos.ru"}
,
{"field1":"Цапковский Иван Валерьевич","field2":"73c35652-3d4a-11f1-b1fe-00155d000912","field3":"tsapkovskijiv@mos.ru"}
,
{"field1":"Цвеловская Анастасия Константиновна","field2":"b05b8c78-87fb-11ec-abd2-00155d051a08","field3":"tsvelovskayaak@mos.ru"}
,
{"field1":"Цыпленков Данил Олегович","field2":"35660c88-8a3f-11ec-abd5-00155d051a08","field3":"TsyplenkovDO@mos.ru"}
,
{"field1":"Чагдурова Лариса Валерьевна","field2":"4b5684c4-3316-11eb-aa1d-00155d1a381f","field3":"ChagdurovaLV@mos.ru"}
,
{"field1":"Чадин Руслан Михайлович","field2":"5ca3e68b-4530-11eb-aa35-00155d1a381f","field3":"ChadinRM@mos.ru"}
,
{"field1":"Чайка Диана Александровна","field2":"6f093c52-be02-11f0-b159-00155d000912","field3":"chaykada@mos.ru"}
,
{"field1":"Черва Александра Александровна","field2":"8d00bafc-3774-11ef-af66-00155d000910","field3":"ChervaAA@mos.ru"}
,
{"field1":"Черкас Анжелика Анатольевна","field2":"d381a7a0-5915-11f1-b221-00155d000912","field3":"CherkasAA@it.mos.ru"}
,
{"field1":"Чернов Александр Викторович","field2":"32935972-8ef5-11ec-abdb-00155d051a08","field3":"ChernovAV9@mos.ru"}
,
{"field1":"Чернышева Анастасия Дмитриевна","field2":"14c715f4-1352-11ef-af38-00155d000910","field3":"ChernyshevaAD@mos.ru"}
,
{"field1":"Чефонова Яна Игоревна","field2":"d1b2fb48-d0a2-11ee-aee1-00155d000910","field3":"ChefonovaYI@mos.ru"}
,
{"field1":"Чубченко Марина Михайловна","field2":"7bcff80d-49fb-11ed-ace0-00155d000910","field3":"ChubchenkoMM1@mos.ru"}
,
{"field1":"Шатский Максим Сергеевич","field2":"326e087e-ee93-11ef-b04a-00155d000910","field3":"shatskiyms1@mos.ru"}
,
{"field1":"Шаховская Мария Сергеевна","field2":"3373e15b-7f44-11f0-b109-00155d000912","field3":"shakhovskayams@mos.ru"}
,
{"field1":"Шахумов Рамазан Ширинович","field2":"0d5e4a94-4129-11ec-ab77-00155d051a08","field3":"ShokhumovRS@mos.ru"}
,
{"field1":"Шванская Ирина Никитовна","field2":"a94fd772-3a7b-11ee-ae20-00155d000910","field3":"irensky13@gmail.com"}
,
{"field1":"Швырёва Татьяна Андреевна","field2":"ac5ba39a-42cc-11f0-b0bb-00155d000912","field3":"shvyrevata@mos.ru"}
,
{"field1":"Шевкунов Дмитрий Игоревич","field2":"7cb72567-3670-11ec-ab69-00155d051a08","field3":"ShevkunovDI@mos.ru"}
,
{"field1":"Шевченко Анна Игоревна","field2":"f61e580e-dc7a-11ef-b03a-00155d000912","field3":"shevchenkoai2@mos.ru"}
,
{"field1":"Шевченко Евгения Сергеевна","field2":"6799679c-dc02-11ec-ac4e-00155d000912","field3":"ShevchenkoES1@mos.ru"}
,
{"field1":"Шек Дарья Станиславовна","field2":"858eb849-6309-11f1-b22e-00155d000912","field3":"shekds@mos.ru"}
,
{"field1":"Шершнева Ольга Владимировна","field2":"4c004300-5b23-11f1-b224-00155d000912","field3":"shershnevaov3@mos.ru"}
,
{"field1":"Шигаев Ринат Ханяфеевич","field2":"8ff4e4ca-22ea-11e9-a98f-00155d1a3433","field3":"ShigaevRK@mos.ru"}
,
{"field1":"Ширшова Варвара Александровна","field2":"d3dba922-3182-11ec-ab63-00155d051a08","field3":"ShirshovaVA@mos.ru"}
,
{"field1":"Ширяева Анна Александровна","field2":"a6aee82d-be81-11ee-aeca-00155d000912","field3":"ShiryaevaAA4@mos.ru"}
,
{"field1":"Шитова Виктория Александровна","field2":"92d8696d-1ef0-11ee-adfd-00155d000912","field3":"ShitovaVA1@mos.ru"}
,
{"field1":"Шитова Ксения Игоревна","field2":"8a9492cc-5709-11eb-aa4d-00155d1a381f","field3":"ShitovaKI1@mos.ru"}
,
{"field1":"Шкелев Антон Алексеевич","field2":"23102f09-cefd-11ee-aedf-00155d000910","field3":"shkelevaa@mos.ru"}
,
{"field1":"Шмакова Нина Сергеевна","field2":"fef4a26f-2837-11f1-b1e3-00155d000912","field3":"ShmakovaNS1@mos.ru"}
,
{"field1":"Шорникова Екатерина Сергеевна","field2":"f8d4f9b5-4c0b-11ea-a99f-00155d1a38ec","field3":"ShornikovaES@mos.ru"}
,
{"field1":"Эрболатова Амина Уллубиевна","field2":"25c1d10d-b384-11ee-aebc-00155d000912","field3":"ErbolatovaAU@mos.ru"}
,
{"field1":"Юсипов Руслан Тагерович","field2":"c97e820a-424f-11ee-ae2b-00155d000910","field3":"YusipovRT@mos.ru"}
,
{"field1":"Юсупов Егор Евгеньевич","field2":"213045e0-3f5a-11ef-af70-00155d000910","field3":"YusupovEE@mos.ru"}
,
{"field1":"Юшко Кирилл Дмитриевич","field2":"4c5c7abb-8ed8-11f0-b11d-00155d000910","field3":"yushkokd@mos.ru"}
,
{"field1":"Яговкина Анастасия Игоревна","field2":"65686247-f282-11ef-b04f-00155d000910","field3":"YagovkinaAI@mos.ru"}
,
{"field1":"Яковлева Мария Игоревна","field2":"0e1e1976-2831-11f1-b1e3-00155d000912","field3":"YakovlevaMI1@mos.ru"}
,
{"field1":"Яковлева Татьяна Геннадьевна","field2":"97d07e02-e500-11ee-aefd-00155d000912","field3":"YakovlevaTG5@mos.ru"}
,
{"field1":"Янгурская Екатерина Андреевна","field2":"a6ed3442-21cf-11f1-b1db-00155d000910","field3":"yangurskayaea@mos.ru"}
,
{"field1":"Яндрова Мария Анатольевна","field2":"d3596a91-bfe8-11e9-a994-00155d1a3432","field3":"YandrovaMA@mos.ru"}
,
{"field1":"Яновский Никита Валерьевич","field2":"6a9c0799-71c7-11f0-b0f8-00155d000910","field3":"yanovskiynv@mos.ru"}
,
{"field1":"Ящук Евгений Андреевич","field2":"3d347425-0b09-11f1-b1bd-00155d000912","field3":"yaschukea@mos.ru"}

]
';

$arJsonDecoded = json_decode($jsonFile, true);
// Пропускаем заголовки
$usersData = array_slice($arJsonDecoded, 2);

// emails как ключи массив от Мазурова с ИД 1с овскими для Внешнего кода в портале
foreach ($usersData as $userDecoded) {
    $usersDataN[strtolower($userDecoded['field3'])] = $userDecoded;// к нижнему регистру
}

// получаем активных юзеров с пустым Внешним кодом и логином не имеющим "@mos.ru" то есть не из Комитета и из МУФ
$result = UserTable::getList([
    'select' => ['ID', 'XML_ID', 'EMAIL', 'LOGIN'],           // выбираем все поля пользователя
    'filter' => [
        '=ACTIVE' => 'Y',
        'XML_ID' => '',
        '!%LOGIN' => '@mos.ru',     // Логин НЕ содержит символ "@mos.ru"

    ],
    'order' => ['ID' => 'ASC']
]);

$usersNeedsXML_ID = [];
while ($user = $result->fetch()) {
    $usersNeedsXML_ID[strtolower($user['EMAIL'])] = $user;// чтобы совпадали почты
}

// находим совпадения почт в Мазурова файле и в портале(все в нижнем портале)
$matchEmails = array_intersect_key($usersNeedsXML_ID, $usersDataN);

$itemsInChunks =20;// чем больше тем меньше чанков
$chunkNumb = 1;// номер ключ чанка в массиве


$arChunksSplit = array_chunk($matchEmails, $itemsInChunks);// если много делим на части


$user = new CUser();
foreach ($arChunksSplit[$chunkNumb] as $arUser) {
    $userId = $arUser['ID'];
    $userEmail = strtolower($arUser['EMAIL']);
    $fields = [
        'XML_ID' => $usersDataN[$userEmail]['field2'],
    ];


    //  если все гут раскомментим ниже код для добавления Внешнего кода

   /* $res=$user->Update($userId, $fields);
    if ($res) {
        $log = date('Y-m-d H:i:s') . ' USERS_XML_ID_UPDAE  ID=' . $userId.' EMAIL='.$arUser['EMAIL'].' XML_ID='.$usersDataN[$userEmail]['field2'];
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/USERS_XML_ID_UPDAE.txt', $log . PHP_EOL, FILE_APPEND);

        echo $res;
        echo "\n id=" . $userId;
    } else {
        echo $user->LAST_ERROR;
    }*/
}

pretty_print($usersDataN, '$usersDataN from json Mazurov');

pretty_print($usersNeedsXML_ID, '$usersNeedsXML_ID юзеры нужен Вн. код');

pretty_print($matchEmails, '$matchEmails'); // совпадение почт из Маз. и из  портала to needs XML_ID

pretty_print($arChunksSplit, '$arChunksSplit'); // общий с чанками

pretty_print($arChunksSplit[$chunkNumb], 'chunk номер'.$chunkNumb); // массив




?>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>