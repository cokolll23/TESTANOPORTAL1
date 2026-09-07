<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("XSLS");
use Lab\Helpers\UsersHelpers as UH;
use Bitrix\Main\UserTable;
?>
<?php
use Bitrix\Main\Application;
use Bitrix\Main\UI\PageNavigation;

$connection = Application::getConnection();

// Получаем общее количество
$countSql = "SELECT COUNT(*) FROM b_vote";
$totalCount = $connection->query($countSql)->fetch()['COUNT(*)'];

// Пагинация
$nav = new PageNavigation('nav-votes');
$nav->allowAllRecords(true)
    ->setPageSize(4)
    ->initFromUri();
$nav->setRecordCount($totalCount);

// Основной запрос с пагинацией
$sql = "
    SELECT * FROM (
        SELECT * FROM b_vote 
        WHERE DATE_END <= NOW() 
        ORDER BY TIMESTAMP_X DESC
    ) AS expired
    UNION ALL
    SELECT * FROM (
        SELECT * FROM b_vote 
        WHERE DATE_END > NOW() 
        ORDER BY TIMESTAMP_X DESC
    ) AS activexc
    ORDER BY CASE WHEN DATE_END <= NOW() THEN 0 ELSE 1 END, TIMESTAMP_X DESC
    LIMIT " . $nav->getLimit() . " OFFSET " . $nav->getOffset();

$result = $connection->query($sql);

while ($vote = $result->fetch()) {
    echo "ID: {$vote['ID']}, TITLE: {$vote['TITLE']}, DATE_END: {$vote['DATE_END']}<br>";
}

var_dump($nav);

/*$nav->setPageSizes([10, 20, 50]);
$nav->setCurrentPage($this->request->getQuery('PAGEN_1'));
$nav->initFromUri();

// Для вывода используйте компонент system.pagenavigation
$APPLICATION->IncludeComponent(
    "bitrix:system.pagenavigation",
    "modern",
    array(
        "NAV_OBJECT" => $nav,
        "SHOW_ALWAYS" => "Y",
        "SEF_MODE" => "Y" // Попытка включить ЧПУ для кастомной навигации
    ),
    false
);*/
?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>