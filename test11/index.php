<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("карта");
use Lab\Helpers\UsersHelpers as UH;
use Bitrix\Main\UserTable;
?>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=418210cd-2ce9-4656-bf3b-4683fa11e261&lang=ru_RU"
            type="text/javascript"></script>
    <script type="text/javascript">
    var iconImageSize = [70, 35];
    ymaps.ready(init);
    function init() {

        var myMap = new ymaps.Map("map", {
            center: [55.7711, 37.5993],
            zoom: 14
        }, {
            searchControlProvider: 'yandex#search'
        });
        myMap.behaviors.disable('scrollZoom');

        var FullCustomBalloonTver = ymaps.templateLayoutFactory.createClass(
            '<div id="tver" class="custom-balloon-wrapper abs">' +
            '<div class="custom-balloon">' +
            '<img width="280px" src="imgs/balun1.svg">' +
            '<div class="balun-content_wrapp" id="ig">' +
            '<div class="balun-content_top" > Ректорский<br> домик</div>' +
            '<div class="balun-content_m" ><b>Тверская, 5А</b></div>' +
            '<div class="balun-content_b" >АНО Проектный офис по развитию туризма и гостеприимства Москвы</div>' +
            '<a href="/wcp/tver.pdf" class="baloon-btn" >схема проезда</a>' +
            '</div>' +
            '</div>' +
            '</div>'
        );

        // Тверская.
        var myPlacemarkTver = new ymaps.Placemark([55.756893, 37.611140], null, {
            iconLayout: FullCustomBalloonTver,
            iconImageHref: "imgs/balun1.svg",
            iconContent: 'Тверская',
            iconImageSize: iconImageSize,
            iconImageOffset: [-100, -35]
        });
        myMap.geoObjects.add(myPlacemarkTver);

        var FullCustomBalloonHolodilnik = ymaps.templateLayoutFactory.createClass(

            '<div id="holod" class="custom-balloon-wrapper">' +
            '<div class="custom-balloon">' +
            '<img width="280px" src="imgs/bdmitrovka.svg">' +
            '<div class="balun-content_wrapp" id="">' +
            '<div class="balun-content_top" > Холодильник</div>' +
            '<div class="balun-content_m" ><b>Большая дмитровка, 11, стр 7</b></div>' +
            '<div class="balun-content_b" ><div>Комитет по туризму</div></b>АНО Проектный офис по развитию туризма и гостеприимства Москвы </div>' +
            '<a  id="details-link" style="cursor: pointer" href="https://corp-portal.welcome.moscow/wcp/imgs/holod.pdf" class="baloon-btn" > схема проезда</a>' +

            '</div>' +
            '</div>' +
            '</div>'

        );

        // Большая дмитровка 11 стр 7
        var myPlacemarkHolodilnik = new ymaps.Placemark([55.761644, 37.612991], null, {
            iconLayout: FullCustomBalloonHolodilnik,
            iconImageHref: "imgs/bdmitrovka.svg",
            iconContent: 'Большая дмитровка 11 стр 7',
            iconImageSize: iconImageSize,
            iconImageOffset: [-120, 350]
        });
        myMap.geoObjects.add(myPlacemarkHolodilnik);




// Belka
        var FullCustomBalloonBelka = ymaps.templateLayoutFactory.createClass(
            '<div id="belka" class="custom-balloon-wrapper">' +
            '<div class="custom-balloon">' +
            '<img width="280px" src="imgs/lesnoy.svg">' +
            '<div class="balun-content_wrapp" >' +
            '<div class="balun-content_top" > Белка></div>' +
            '<div class="balun-content_m" ><b>4-й Лесной переулок, 4</b></div>' +
            '<div class="balun-content_b" >АНО Проектный офис по развитию туризма и гостеприимства Москвы</div>' +
            '<a class="baloon-btn" >схема проезда</a>' +
            '</div>' +
            '</div>' +
            '</div>'
        );

        // Belka.
        var myPlacemarkBelka = new ymaps.Placemark([55.779069, 37.586931], null, {
            iconLayout: FullCustomBalloonBelka,
            iconImageHref: "imgs/lesnoy.svg",
            iconContent: 'Большая дмитровка 11 стр 7',
            iconImageSize: iconImageSize,
            //iconImageOffset: [1200, 350]
        });
        myMap.geoObjects.add(myPlacemarkBelka);


    }
    </script>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>