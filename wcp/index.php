<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("");
?>
<?

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss($_SERVER["DOCUMENT_ROOT"] . '/wcp/style.css');
?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!--font-size: clamp(22px,2.5vw,53px);-->
    <style>
        .container1 {
            width: min(100% - 48px, 1447px);
            margin: 0 auto;
        }

        .wcp {
            margin-top: 24px
        }

        .page__workarea-content {
            padding: 0;
        }

        .wcp, .wcp-y, .wcp-12, .wcp-13 {
            background-color: #ffffff;
            font-family: ONY One, Helvetica, Arial, sans-serif;
            font-size: 14px;
        }

        .wcp-y .schedule__card h3, .wcp-y .schedule__card p {
            font-size: clamp(10px, 1.3vw, 22px) !important;
        }


        .wcp-2-wrapper_left-inner-wrapp .top {
            font-family: "ONY Moscow Wide";
        }

        .flex {
            display: flex
        }

        .hidden {
            display: none
        }

        .rel {
            position: relative
        }

        .abs {
            position: absolute
        }

        #wcp-header {
            background-color: #ffffff
        }

        .red-color {
            color: #E30613;
        }

        .yellow-color {
            color: #FFF1DE;
        }

        .blue-bckgr {
            background-color: #8CB6F5;
        }

        .wcp-header_main-img {
            background-image: url("imgs/header/main-img/Group.png ");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            width: 100%;
            height: min-content;

        }

        .wcp-main-img_top-left {
            width: 41%
        }

        .wcp-main-img_btm-left {
            right: 0%;
            bottom: -2%;
            width: 53%;
        }

        .wcp-main-img_btm-left-flex {
            flex-direction: column;
            /*font-size: 95px;*/
            font-size: clamp(42px, 5vw, 94px);
            /*font-size:clamp(2.3333rem, 1.7556rem + 3.25vw, 5.2222rem);*/
            font-family: 'ONY Moscow Wide';
            line-height: 100%;
            font-weight: 400;

        }

        .wcp-main-img_top-left-left {
            align-items: center;
        }

        .wcp-2_wrapper {
            padding-top: 2%
        }

        .wcp-2_wrapper-top {
            height: 180px;
        }

        .wcp-2-wrapper_left {
            width: 43%;
            height: 100%
        }

        .wcp-2-wrapper_left-inner {
            background-color: #8CB6F5;
            width: 98%;
            border-radius: 40px;
            height: 100%;
            padding-left: 1%;
            padding-right: 1%;
        }

        .wcp-2-wrapper_left-inner-wrapp {
            width: 85%;
            align-items: center;
            justify-content: space-between;
            margin: 0 auto;
        }

        .wcp-2-wrapper_left-inner > div div.top {
            color: #fff;
            text-align: center;
            /*font-size: 53px;*/
            font-size: clamp(22px, 2.3vw, 53px);
            line-height: 100%;
        }

        .wcp-2-wrapper_left-inner > div .dwn {

            color: #fff;
            text-align: center;
        }

        .wcp-2-wrapper_right {
            display: flex;
            width: 56%;
            height: 100%;
            justify-content: flex-end;
            font-family: "denistina", "Marck Script", cursive;
        }

        .wcp-2-wrapper_right-inner {
            display: flex;
            justify-content: center;
            background-color: #FFF1DE;
            width: 93%;
            height: 100%;
            border-radius: 40px;
            color: #E30613;
            font-size: clamp(12px, 3vw, 62px)
        }

        .wcp-2-wrapper_right-inner1 {
            width: 85%;
            line-height: 100%;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            align-content: center;
        }


        .wcp-3 .container {
            /*height: 150px;*/
            /*height: clamp(2.5rem, 1.125rem + 6.875vw, 9.375rem);*/
            /*height: clamp(100px,6vw, 150px);*/

            height: clamp(120px, 7.5vw, 150px);
            display: flex;
            gap: 20px; /* Расстояние между карточками */
            /* justify-content: center;*/
        }

        .wcp-3 .card {
            background-color: #8CB6F5; /* Голубой цвет с картинки */
            color: white;
            white-space: nowrap;
            padding-left: clamp(1rem, 2vw, 9rem);
            padding-right: clamp(1rem, 2vw, 9rem);
            padding-top: clamp(1rem, 3.7vw, 4.6rem);
            padding-bottom: clamp(1rem, 2vw, 6rem);

            border-radius: 45px;
            font-weight: 700;
            font-size: clamp(10px, 1.1vw, 27px);

            line-height: 105%;
            display: flex;
            align-items: center; /* Базовая ширина 300px, могут растягиваться */

        }

        .wcp-3 .c1 {
            width: 20%;
        }

        .wcp-3 .c2 {
            width: 30%;
        }

        .wcp-3 .c3 {
            width: 50%;
        }

        .wcp-3 .c3 > div {
            padding-left: 10%;
            padding-right: 10%;


        }


        .wcp-3 .title-wrap {
            width: 100%;
            padding-top: 60px;
            padding-bottom: 30px;
            overflow: hidden;
        }

        .wcp-3 .title {
            margin: 0;
            font-size: clamp(42px, 6vw, 94px);
            line-height: 0.85;
            font-weight: 400;
            text-transform: uppercase;
            color: #8CB6F5; /* #8db8f3;*/
            font-family: 'ONY Moscow Wide';
            /*letter-spacing: 2px;*/


        }


        .wcp-3-3 {
            height: 200px
        }

        .wcp-3-3 .middle {
            right: 31%;
            top: 25%;
            color: #E30613;
            font-size: clamp(15px, 4vw, 65px);
            font-family: "denistina", "Marck Script", cursive;
            text-align: center;
            line-height: 75%;
        }

        .wcp-3-3 .right {
            right: 0px;
            width: 30%;
        }

        .wcp-3-3 .left {
            left: 35%;
        }

        .wcp-3-3 .left img {
            width: clamp(60px, 7vw, 128px);
        }

        .wcp-4 {
            height: 400px;
        }

        .wcp-4 .section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 60px 20px;
        }

        .wcp-4 .container {
            width: 100%;
            max-width: 1040px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: clamp(32px, 6vw, 80px);
        }

        .wcp-4 .sign img {
            width: 20%;

        }

        .wcp-4 .text {
            font-size: clamp(32px, 5vw, 24px);
            line-height: 0.95;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #E30613;
        }


        .wcp-4-21, .wcp-4-2 {
            height: 100%;
        }

        .wcp-4 .wcp-4-2-1 {
            top: 0;
        }

        .wcp-4 .wcp-4-2-2 {
            right: 0%;
        }

        .wcp-4 .wcp-4-2-3 {
            top: 30%;
        }

        .wcp-4 .wcp-4-2-4 {
            right: 0%;
            bottom: 0%;
        }

        .wcp-4 .text {
            display: flex;
            align-items: flex-end;
        }

        .wcp-4-2-2 .wcp-4-2-11 {
            justify-content: flex-end;
        }

        /*wcp-5*/

        .wcp-5 .section {
            margin-bottom: 72px;
        }

        .wcp-5 .section__header {
            display: grid;
            grid-template-columns: 1fr 330px;
            gap: 28px;
            align-items: end;
            margin-bottom: 44px;
        }

        .wcp-5 .section__title {
            margin: 0;
            font-size: clamp(42px, 7vw, 74px);
            line-height: 0.9;
            font-weight: 900;
            letter-spacing: -2px;
            text-transform: uppercase;
            color: #8CB6F5;
        }

        .wcp-5 .section__side {
            padding-bottom: 8px;
        }

        .wcp-5 .section__subtitle {
            margin: 0 0 20px;
            font-size: 18px;
            line-height: 1.15;
            font-weight: 700;
            color: #8CB6F5;
        }

        .wcp-5 .section__note {
            margin: 0;
            font-size: 14px;
            line-height: 1.25;
            color: #E30613;
        }

        .wcp-5 .leaders {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
            align-items: start;
        }

        .wcp-5 .leaders--top {
            grid-template-areas:
        "main-photo main-info . ."
        ". card-1 card-2 card-3";
        }

        .wcp-5 .leaders--bottom {
            grid-template-columns: 230px 230px 1fr 1fr;
            grid-template-areas:
        "main-photo main-info . .";
        }

        .wcp-5 .leader {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .wcp-5 .leader--photo-only {
            grid-area: main-photo;
        }

        .wcp-5 .leader--main-info {
            grid-area: main-info;
        }

        .wcp-5 .leader:nth-child(3) {
            grid-area: card-1;
        }

        .wcp-5 .leader:nth-child(4) {
            grid-area: card-2;
        }

        .wcp-5 .leader:nth-child(5) {
            grid-area: card-3;
        }

        .wcp-5 .leader__photo {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 28px;
            overflow: hidden;
        }

        .wcp-5 .leader__photo img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .wcp-5 .leader__info {
            min-height: 190px;
            padding: 24px 22px;
            border-radius: 0 0 28px 28px;
            background: #83aef0;
        }

        .wcp-5 .leader--main-info .leader__info {
            min-height: 215px;
            border-radius: 28px;
        }

        .wcp-5 .leader__name {
            margin: 0 0 22px;
            font-size: 17px;
            line-height: 1.15;
            font-weight: 800;
        }

        .wcp-5 .leader__position {
            margin: 0;
            font-size: 14px;
            line-height: 1.25;
            font-weight: 600;
        }

        .wcp-5 .leader--with-photo .leader__photo {
            border-radius: 28px 28px 0 0;
        }


        .wcp-y {
            background-color: #fff6eb;
        }

        .wcp-y .section-title {
            color: #8CB6F5;
            font-size: clamp(42px, 7vw, 94px);
            font-family: 'ONY Moscow Wide';
            /*letter-spacing: 2px;*/
            line-height: 0.85;
            font-weight: 400;
        }

        .wcp-3-2 .card {
            display: flex;
        }
    </style>
    <style>
        .page {
            width: min(100%, 1447px);
            margin: 0 auto;
            /*  padding: 60px 42px 70px;*/
        }

        .wcp-11 .schedule {
            margin-bottom: 58px;
            color: #fff;
        }

        .wcp-11 .schedule__header {
            display: flex;
            align-items: flex-end;
            gap: 18px;
            margin-bottom: 28px;
        }

        .wcp-11 .schedule__header .title-red {
            font-family: "ONY Moscow Wide";
            font-weight: 400;
            /*font-size: 93.39px;*/
            font-size: clamp(42px, 5vw, 94px);
            line-height: 100%;
            text-transform: uppercase;
            color: #E30613;
            margin-top: 50px;
            /*width: 70%;*/
            white-space: nowrap;

        }

        .wcp-11 .schedule__header p {
            /*margin: 0 0 4px;*/
            font-weight: 700;
            color: #8CB6F5;
            font-family: 'ONY One';
            font-size: clamp(10px, 1.4vw, 27.43px);
            line-height: 105%;
            margin-bottom: clamp(15px, 1vw, 20px);

        }

        .wcp-11 .schedule__grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px 22px;
        }

        .wcp-11 .card {
            background: #83b2ef;
            border-radius: 22px;
            color: #fff;
        }

        .wcp-11 .schedule__card {
            flex: 1 1 calc(50% - 22px);
            min-height: 76px;
            padding: 20px 32px;
        }

        .wcp-11 .schedule__card h3,
        .wcp-11 .rule-card h3 {
            margin: 0;
            font-size: 15px;
            line-height: 1.2;
            font-weight: 800;
        }

        .wcp-11 .schedule__card p {
            margin: 4px 0 0;
            font-size: 13px;
            line-height: 1.25;
            font-weight: 600;
        }

        .wcp-11 .rules h2 {
            font-family: "ONY Moscow Wide";
            font-weight: 400;
            /*font-size: 93.39px;*/
            font-size: clamp(42px, 6vw, 94px);
            /*font-size: clamp(42px, 7vw, 94px);*/
            line-height: 100%;
            text-transform: uppercase;
            color: #8CB6F5;
            margin-bottom: 40px;
        }

        .wcp-11 .rules__grid {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            color: #fff;
        }

        .wcp-11 .rules__column {
            flex: 1 1 0;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .wcp-11 .rule-card {
            padding: 24px 28px;
        }

        .wcp-11 .rule-card ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }


        .wcp-11 .schedule__card li {
            color: #fff;
            font-family: ONY One;
            font-weight: 700;
            font-size: 22.03px;
            line-height: 100%;

        }


    </style>
    <style>
        .wcp-9 .about {
            padding: 32px 0 48px;
        }

        .wcp-9 .container {
            width: min(100% - 48px, 1447px);
            margin: 0 auto;
        }

        .wcp-9 .title {
            margin: 0;
            font-weight: 900;
            line-height: 0.95;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .wcp-9 .title-blue {
            color: #8CB6F5;
            font-size: clamp(42px, 6vw, 94px);
            font-family: 'ONY Moscow Wide';
            letter-spacing: 2px;
            line-height: 0.85;
            font-weight: 400;

        }

        .wcp-9 .title-red {
            color: #E30613;
            /*font-size: 94px;*/
            font-size: clamp(42px, 5vw, 94px);
            font-family: 'ONY Moscow Wide';

            line-height: 100%;
            font-weight: 400;
        }

        .wcp-9 .mission-card {
            margin-top: 22px;
            margin-bottom: 48px;
            background: #fff1dd;
            border-radius: 28px;
            color: #8CB6F5;
            padding: 28px;
            font-weight: 700;
            font-size: clamp(15px, 2vw, 28px);
            line-height: 131%;
        }

        .wcp-9 .mission-card p {
            max-width: 700px;
            margin: 0;
        }

        .wcp-9 .mission-card p + p {
            margin-top: 28px;
        }

        .wcp-9 .values {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            gap: 56px 80px;
            padding: 36px 40px 52px;
        }

        .wcp-9 .value {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 18px;
            width: calc(50% - 40px);
        }

        .wcp-9 .value-1 {
            /*margin-left: 50px;*/
        }

        .wcp-9 .value-2 {
            margin-top: 120px;
            margin-left: -50px;
        }


        .wcp-9 .value-3 {
            margin-top: -80px;
            margin-left: 130px;
        }

        .wcp-9 .value-4 {
            /*margin-top: 5%;*/
            margin-left: auto;
        }

        .wcp-9 .value-number {
            flex: 0 0 auto;
            color: #E30613;
            font-family: denistina;
            font-size: clamp(52px, 11vw, 229px);
            font-style: italic;
            font-weight: 400;
            /*font-size: 229px;*/
            line-height: 75%;
            text-align: center;
        }

        .wcp-9 .value-text {
            max-width: 270px;
            margin-top: 15%;
        }

        .wcp-9 .value-text h3 {
            margin: 0 0 8px;
            color: #E30613;
            font-size: clamp(17px, 2.2vw, 22px);
            line-height: 1;
            font-weight: 900;
        }

        .wcp-9 .value-text p {
            font-family: "ONY One";
            margin: 0;
            font-size: 18px;
            line-height: 100%;
            font-weight: 400;
            color: #000;
        }

        .wcp-9 .projects-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-top: 20px;
        }

        .wcp-9 .projects-note {
            margin: 0 0 8px;
            color: #E30613;
            font-size: 15px;
            line-height: 1.2;
            font-weight: 700;
        }

        .wcp-9 .projects {
            display: flex;
            margin-top: 28px;
            border-radius: 28px 28px 0 0;
            overflow: hidden;
        }

        .wcp-9 .project-card {
            flex: 1 1 0;
            min-width: 0;
            color: #111111;
            text-decoration: none;
        }

        .wcp-9 .project-card img {
            display: block;
            width: 100%;
            aspect-ratio: 1 / 0.82;
            object-fit: cover;
        }

        .wcp-9 .project-card h3 {
            margin: 18px 14px 0;
            font-size: clamp(14px, 1.7vw, 17px);
            line-height: 1;
            font-weight: 900;
        }
    </style>
    <style>
        .wcp-12 {
            background: linear-gradient(to bottom,
            #FFF6EB 0%,
            #F2F2F0 30%,
            #DFECF9 60%,
            #D2E8FF 100%
            );
        }

        .wcp-12 .benefits {

            padding: 70px 0 50px;

        }

        .wcp-12 .benefits__container {
            width: min(100% - 48px, 1120px);
            margin: 0 auto;
        }

        .wcp-12 .benefits__title {
            font-family: "ONY Moscow Wide";
            font-weight: 400;
            /*font-size: 93.39px;*/
            font-size: clamp(42px, 7vw, 94px);
            line-height: 66%;
            text-transform: uppercase;
            color: #8CB6F5;
            margin-bottom: 40px;
        }

        .wcp-12 .benefits__grid {
            display: flex;
            flex-wrap: wrap;
            gap: 130px 42px;
        }

        .wcp-12 .benefit-card {
            position: relative;
            display: flex;
            flex-direction: column;
            width: calc((100% - 84px) / 3);
            min-height: 158px;
            border-radius: 22px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.05);
            overflow: visible;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .wcp-12 .benefit-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(74, 135, 201, 0.22);
        }

        .wcp-12 .benefit-card__image {
            height: 105px;
            border-radius: 22px 22px 22px 0;
            background: linear-gradient(135deg, #d7e8f6 0%, #95cff7 100%);
            position: relative;
            /* display: flex;
             align-items: center;*/
            padding: 14px 24px;
        }

        .wcp-12 .benefit-card__image img {
            position: absolute;
            display: block;
            /* max-width: 86px;
             max-height: 82px;*/
            object-fit: contain;

        }

        .wcp-12 .benefit-card__bottom {
            min-height: 53px;
            padding: 30px 22px 15px;
            display: flex;
            align-items: center;
            background: #ffffff;
            border-radius: 0 0 22px 22px;
            bottom: -60%;
            width: 100%;
        }

        .wcp-12 .benefit-card__bottom span {
            font-size: 16px;
            line-height: 1.15;
            font-weight: 700;
        }

        .wcp-12 .benefit-card__badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #AAD3FB;

            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            /*box-shadow: 0 0 0 7px #AAD3FB;*/
        }

        .wcp-12 .benefits__note {
            width: fit-content;
            margin: 58px 0 0 auto;
            margin-right: 210px;
            font-size: 15px;
            line-height: 1.15;
            font-weight: 600;
            color: #E30613;
        }

        .wcp-12 .benefit-card__image1 {
            height: 77%;
        }
    </style>
    <style>
        .page {
            width: 100%;
            max-width: 1447px;
            margin: 0 auto;
            /*padding: 80px 38px 80px;
            background-color: #C2E0FFBA;*/
        }

        .wcp-13 {
            background-color: #D2E8FF;
            padding-top: 50px;
            padding-bottom: 100px;
        }

        .wcp-13 .section {
            position: relative;
        }

        .wcp-13 .section-links {
            margin-bottom: 34px;
        }

        .wcp-13 .note {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #E30613;
        }

        .wcp-13 .note-top {
            text-align: right;
            padding-right: 70px;
            margin-bottom: 18px;
        }

        .wcp-13 .note-bottom {
            text-align: right;
            padding-right: 70px;
            margin-top: 28px;
        }

        .wcp-13 .title {
            font-family: "ONY Moscow Wide";
            font-weight: 400;
            font-size: 93.39px;
            line-height: 66%;
            text-transform: uppercase;
            color: #8CB6F5;
            margin-bottom: 40px;
        }

        .wcp-13 .title-blue {
            font-family: "ONY Moscow Wide";
            font-weight: 400;
            /*font-size: 93.39px;*/
            font-size: clamp(42px, 7vw, 94px);
            line-height: 100%;
            text-transform: uppercase;
            color: #8CB6F5;
            margin-bottom: 40px;
        }

        .wcp-13 .title-red {
            margin-bottom: 32px;

            color: #E30613;
            font-family: "ONY Moscow Wide";
            font-weight: 400;

            font-size: clamp(42px, 4.4vw, 94px);

            line-height: 100%;
            text-transform: uppercase;

        }

        .wcp-13 .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 18px 24px;
            margin-bottom: 50px;
        }

        .wcp-13 .card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;

            flex: 1 1 calc(50% - 12px);
            min-height: 78px;
            padding: 18px 24px;

            background: #fff;
            border-radius: 16px;

            color: #111;
            text-decoration: none;
            text-align: center;
            font-size: 18px;
            font-weight: 800;

            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .wcp-13 .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(70, 120, 180, 0.15);
        }

        .wcp-13 .card-handwritten {
            overflow: visible;
        }

        .wcp-13 .hand {
            position: absolute;
            font-family: "denistina", "Marck Script", cursive;
            font-size: 350%;
            font-weight: 400;
            color: #E30613;
            line-height: 1;
            pointer-events: none;
        }

        .wcp-13 .hand-left {
            left: 5px;
            top: -12px;
        }

        .wcp-13 .hand-left1::after {
            content: "";
            position: absolute;
            left: -4px;
            bottom: -22px;
            width: 180px;
            height: 42px;
            border-bottom: 2px solid #c32644;
            border-left: 2px solid #c32644;
            border-radius: 0 0 0 80px;
            transform: rotate(7deg);
        }

        .wcp-13 .hand-right {
            right: 18px;
            bottom: -9px;
        }

        .wcp-13 .hand-right1::before {
            content: "";
            position: absolute;
            right: 75px;
            top: -16px;
            width: 90px;
            height: 36px;
            border-top: 2px solid #c32644;
            border-radius: 80px 80px 0 0;
            transform: rotate(5deg);
        }
    </style>
    <style>

        /* Кастомный голубой цвет как на фото */
        .bg-custom-blue {
            background-color: #8eb4f5 !important;
        }

        /* Радиус скругления для всех элементов */
        .rounded-custom {
            border-radius: 45px !important;
            overflow: hidden;
        }

        .info-card {
            padding: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;

        }


        .info-card h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .info-card p {
            font-size: 1rem;
            line-height: 1.4;
            margin-bottom: 0;
        }

        .person-img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }

        /* Отступы между рядами */
        .main-row {
            margin-bottom: 30px;
        }

        .deputy-card {
            margin-bottom: 20px;
        }


        .wcp-6 {
            margin-top: 70px;

        }

        .wcp-6 .section-header {
            margin-bottom: 50px;

        }

        .wcp-6 .title-blue, .wcp-8 .title-blue {
            color: #8CB6F5;
            font-size: clamp(42px, 5.5vw, 94px);
            font-family: 'ONY Moscow Wide';
            letter-spacing: 2px;
            line-height: 0.85;
            font-weight: 400;
        }

        .wcp-8 .title-blue {
            margin-right: 1%;
        }

        .wcp-6 .section-info, .wcp-8 .section-info {
            display: flex;
            align-items: flex-end;
            padding-bottom: 10px;
            padding-left: 10px;
            color: #8CB6F5;
            font-size: clamp(10px, 1.1vw, 28px);
            font-weight: 700;
            line-height: 1.15;
        }

        .wcp-8 .section-info {
            padding-bottom: 0px;
            padding-left: 0px;
        }

        .wcp-6 .section-header {
            align-items: flex-end;
        }

        .wcp-6 .info-card h2, .wcp-8 .info-card h3 {
            font-family: 'ONY One';
            font-weight: 700;
            font-size: clamp(10px, 1.5vw, 28px);
            line-height: 100%;
            color: #fff;
        }

        .wcp-6 .info-card p, .wcp-8 .info-card p, .wcp-8 .team-card p {
            font-family: 'ONY One';
            font-weight: 400;
            font-size: clamp(10px, 1.1vw, 22px);
            line-height: 100%;
            color: #fff;
        }
    </style>
    <style>
        .wcp-8 .team {
            width: 100%;
            padding: 28px 16px;
        }

        .wcp-11 .schedule__header .title-red.wcp-11 .schedule__header .title-red
        .wcp-8 .section-header {
            align-items: flex-end;
            padding-bottom: 0px !important;
        }

        .wcp-8 .team__grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            max-width: 1447px;
            margin: 0 auto;
        }

        .wcp-8 .team-card {
            display: block;
            width: calc((100% - 30px) / 4);
            aspect-ratio: 1 / 1.2;
            border-radius: 28px;
            overflow: hidden;
            text-decoration: none;
        }

        .wcp-8 .team-card--text {
            padding: 28px 24px;
            background: #8CB6F5;
            color: #ffffff;
        }

        .wcp-8 .team-card--text h3 {
            font-family: 'ONY One';
            font-weight: 700;
            font-size: clamp(10px, 1.7vw, 28px);
            line-height: 100%;
            color: #fff;
            margin-bottom: 20px;
        }


        /*.wcp-8 .team-card--text p {
            font-family: ONY One;
            font-weight: 400;
            font-size: clamp(12.21px,1vw,22.21px);
            line-height: 100%;

        }*/

        .wcp-8 .team-card--photo {
            background: #eeeeee;
        }

        .wcp-8 .team-card--photo img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .bx-newslist, .bx-newslist-container {
            background-color: transparent;
        }

        .wcp-9 .projects1 .star_OWLCAROUSEL_item {
            font-size: 18px;
        }

    </style>
<?php // media reqsts?>
    <style>
        @media (max-width: 1024px) and (max-width: 1470px) {

            .wcp-main-img_btm-left-flex {
                flex-direction: column;
                font-size: 75px !important;
                font-family: 'ONY Moscow Wide';
                line-height: 100%;
                font-weight: 400;
            }

            .wcp-2-wrapper_right-inner {
                font-size: 45px;
            }

            .wcp-2-wrapper_left-inner > div div.top {
                font-size: 35px;
            }

            .wcp-3 .card {
                font-size: 20px;
            }

            .wcp-12 .benefit-card__bottom {

                bottom: -30%;

            }
        }

        @media (min-width: 1400px) {
            .container, .container-sm, .container-md, .container-lg, .container-xl, .container-xxl {
                max-width: 1447px;
            }
        }

        @media (max-width: 1300px) {
            .wcp-12 .benefit-card__bottom {
                top: -10% !important;
            }

            .wcp-3-3 .left img {
                width: 40% !important;
            }

            .wcp-3-3 .middle {
                font-size: 400%;
            }

            .wcp-9 .value-2 {
                margin-top: 0px;
                margin-left: 250px;
            }

            .wcp-9 .value-4 {
                margin-top: 0px;
                margin-left: auto;
            }

            .wcp-9 .value-3 {
                margin-top: -50px;
                margin-left: 80px;
            }

            .wcp-main-img_btm-left-flex {
                font-size: 55px;
            }

        }

        @media (max-width: 1024px) {
            .wcp-2-wrapper_right-inner {
                font-size: 210%;
            }

            .wcp-3-3 .middle {

            }

            .wcp-main-img_btm-left-flex {
                font-size: 45px;
            }
        }

        /* Планшет */
        @media (max-width: 900px) {
            .wcp-8 .team__grid {
                max-width: 720px;
            }

            .wcp-8 .team-card {
                width: calc((100% - 20px) / 3);
            }
        }

        @media (max-width: 900px) {


            .wcp-5 .section__header {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .wcp-5 .section__side {
                padding-bottom: 0;
            }

            .wcp-5 .leaders,
            .wcp-5 .leaders--top,
            .wcp-5 .leaders--bottom {
                grid-template-columns: repeat(2, 1fr);
                grid-template-areas: none;
            }

            .wcp-5 .leader,
            .wcp-5 .leader--photo-only,
            .wcp-5 .leader--main-info,
            .wcp-5 .leader:nth-child(3),
            .wcp-5 .leader:nth-child(4),
            .wcp-5 .leader:nth-child(5) {
                grid-area: auto;
            }

            .wcp-5 .leader--photo-only {
                order: 1;
            }

            .wcp-5 .leader--main-info {
                order: 2;
            }
        }


        @media (max-width: 820px) {
            .page {
                padding: 42px 24px 50px;
            }

            .wcp-11 .schedule__header {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .wcp-11 .schedule__header p {
                max-width: none;
            }

            .wcp-11 .schedule__card {
                flex-basis: 100%;
            }

            .wcp-11 .rules__grid {
                flex-wrap: wrap;
            }

            .wcp-11 .rules__column {
                flex: 1 1 calc(50% - 20px);
            }
        }

        @media (max-width: 768px) {
            .wcp-3 .title {
                font-size: clamp(38px, 14vw, 90px);
                letter-spacing: -0.05em;

            }
        }

        @media (max-width: 768px) {
            .wcp-4 .section {
                padding: 40px 20px;
            }

            .wcp-4 .container {
                flex-direction: column;
                align-items: flex-start;
                gap: 28px;
            }

            .wcp-4 .sign {
                width: 70%;
                max-width: 300px;
                flex: none;
            }

            .wcp-4 .text {
                font-size: clamp(36px, 11vw, 56px);
            }
        }

        @media (max-width: 768px) {
            .page {
                padding: 70px 22px 50px;
            }

            .wcp-13 .note-top,
            .wcp-13 .note-bottom {
                text-align: center;
                padding-right: 0;
            }

            .wcp-13 .title-blue,
            .wcp-13 .title-red {
                text-align: left;
                letter-spacing: -1px;
            }

            .wcp-13 .cards {
                gap: 14px;
            }

            .wcp-13 .card {
                flex-basis: 100%;
                min-height: 66px;
                padding: 16px 18px;
                font-size: 13px;
            }

            .wcp-13 .hand {
                font-size: 25px;
            }

            .wcp-13 .hand-left {
                left: 12px;
                top: -9px;
            }

            .wcp-13 .hand-left::after {
                width: 130px;
                height: 34px;
            }

            .wcp-13 .hand-right {
                right: 15px;
                bottom: 8px;
            }

            .wcp-13 .hand-right::before {
                width: 65px;
                right: 62px;
            }
        }


        /* Небольшие планшеты */
        @media (max-width: 680px) {
            .wcp-8 .team-card {
                width: calc((100% - 10px) / 2);
                border-radius: 24px;
            }

            .wcp-8 .team-card--text {
                padding: 22px 20px;
            }

            .wcp-8 .team-card--text h3 {
                font-size: 16px;
                margin-bottom: 18px;
            }

            .wcp-8 .team-card--text p {
                font-size: 13px;
            }
        }

        /* Адаптация для мобильных устройств */
        @media (max-width: 600px) {
            .wcp-3 .card {
                flex: 1 1 100%; /* На узких экранах занимают всю ширину */
                border-radius: 30px;
                padding: 20px;
                font-size: 16px;
            }
        }

        @media (max-width: 560px) {
            .page {
                padding: 34px 16px 40px;
            }

            .wcp-11 .schedule {
                margin-bottom: 42px;
            }

            .wcp-11 .schedule__grid,
            .wcp-11 .rules__grid,
            .wcp-11 .rules__column {
                gap: 14px;
            }

            .wcp-11 .rules__column {
                flex-basis: 100%;
            }

            .wcp-11 .schedule__card,
            .wcp-11 .rule-card {
                padding: 18px 20px;
                border-radius: 16px;
            }


        }

        @media (max-width: 560px) {


            .wcp-5 .section {
                margin-bottom: 56px;
            }

            .wcp-5 .section__header {
                margin-bottom: 28px;
            }

            .wcp-5 .section__title {
                font-size: 42px;
                letter-spacing: -1px;
            }

            .wcp-5 .section__subtitle {
                font-size: 16px;
            }

            .wcp-5 .section__note {
                font-size: 13px;
            }

            .wcp-5 .leaders,
            .wcp-5 .leaders--top,
            .wcp-5 .leaders--bottom {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .wcp-5 .leader__photo {
                max-height: 360px;
            }

            .wcp-5 .leader__info,
            .wcp-5 .leader--main-info .leader__info {
                min-height: auto;
                padding: 22px 20px;
            }

            .wcp-5 .leader__name {
                font-size: 18px;
                margin-bottom: 18px;
            }

            .wcp-5 .leader__position {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .wcp-3 .title-wrap {
                padding: 12px;
            }

            .wcp-3 .title {
                font-size: clamp(30px, 15vw, 64px);
                letter-spacing: -0.04em;

            }
        }

        @media (max-width: 480px) {
            .page {
                padding: 45px 16px 40px;
            }

            .wcp-13 .title-blue {
                margin-bottom: 22px;
                font-size: 39px;
            }

            .wcp-13 .title-red {
                margin-bottom: 24px;
                font-size: 36px;
            }

            .wcp-13 .note {
                font-size: 12px;
            }

            .wcp-13 .card {
                border-radius: 13px;
            }
        }

        /* Мобильные */
        @media (max-width: 430px) {
            .wcp-8 .team {
                padding: 20px 10px;
            }

            .wcp-8 .team__grid {
                gap: 8px;
            }

            .wcp-8 .team-card {
                width: 100%;
                aspect-ratio: 1 / 1;
                border-radius: 22px;
            }

            .wcp-8 .team-card--text {
                min-height: 210px;
            }
        }

        @media (max-width: 420px) {
            .wcp-4 .section {
                align-items: flex-start;
            }

            .wcp-4 .sign {
                width: 40%;
            }

            .wcp-4 .text {
                font-size: 38px;
            }
        }

    </style>
<?php
global $USER;
$curUser = $USER->GetID();
?>

    <div class="wcp container">
        <div id="about">
            <section id="wcp-header" class="wcp-header">
                <div class="container1">
                    <div class="wcp-header_wrapper">
                        <div class="wcp-header_main-img rel">
                            <div class="wcp-main-img_top-left abs">
                                <div class="wcp-main-img_top-left-inn flex">
                                    <div class="wcp-main-img_top-left-left flex">
                                        <img width="100%" src="imgs/header/moscow/Group.png">
                                    </div>
                                    <div class="wcp-main-img_top-left-middle">
                                        <img width="100%" src="imgs/header/top left  middle/ANO_colors and white 1.png">
                                    </div>
                                    <div class="wcp-main-img_top-left-right">
                                        <img width="100%"
                                             src="imgs/header/top right  middle — копия/Komitet_black 1.png">
                                    </div>
                                </div>
                            </div>
                            <img width="100%" src="imgs/header/main-img/imgM.png">
                            <div class="wcp-main-img_btm-left abs">
                                <div class="wcp-main-img_btm-left-flex flex red-color">
                                    <div class="wcp-main-img_btm-left-1">
                                        ДОБРО
                                    </div>
                                    <div class="wcp-main-img_btm-left-2 ">
                                        ПОЖАЛОВАТЬ
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="wcp-2" class="wcp-2">
                <div class="container1">
                    <div class="wcp-2_wrapper">
                        <div class="wcp-2_wrapper-top flex">
                            <div class="wcp-2-wrapper_left ">
                                <div class="wcp-2-wrapper_left-inner flex"
                                >
                                    <div class="wcp-2-wrapper_left-inner-wrapp flex"
                                         style="    align-items: center;justify-content: space-between;">

                                        <div class="wcp-2-wrapper_left-inner-l">
                                            <?php
                                            $APPLICATION->IncludeComponent(
                                                    "bitrix:main.include",
                                                    "",
                                                    [
                                                            "AREA_FILE_SHOW" => "file",
                                                            "PATH" => "includes/wcp-2-wrapper_left-inner-l.php", // Укажите свой путь
                                                            "EDIT_TEMPLATE" => "" // Опционально: шаблон области
                                                    ]
                                            );
                                            ?>
                                        </div>
                                        <div class="wcp-2-wrapper_left-inner-m">
                                            <?php
                                            $APPLICATION->IncludeComponent(
                                                    "bitrix:main.include",
                                                    "",
                                                    [
                                                            "AREA_FILE_SHOW" => "file",
                                                            "PATH" => "includes/wcp-2-wrapper_left-inner-m.php", // Укажите свой путь
                                                            "EDIT_TEMPLATE" => "" // Опционально: шаблон области
                                                    ]
                                            );
                                            ?>
                                        </div>
                                        <div class="wcp-2-wrapper_left-inner-r">
                                            <?php
                                            $APPLICATION->IncludeComponent(
                                                    "bitrix:main.include",
                                                    "",
                                                    [
                                                            "AREA_FILE_SHOW" => "file",
                                                            "PATH" => "includes/wcp-2-wrapper_left-inner-r.php", // Укажите свой путь
                                                            "EDIT_TEMPLATE" => "" // Опционально: шаблон области
                                                    ]
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wcp-2-wrapper_right hand-cursive">
                                <div class="wcp-2-wrapper_right-inner">
                                    <div class="wcp-2-wrapper_right-inner1">
                                        <div class="top">в команду профессионалов,</div>
                                        <div class="dwn"> влюбленных в Москву</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wcp-2_wrapper-bottom">
                            <div class="wcp-2-wrapper-bottom-top">

                            </div>
                            <div class="wcp-2-wrapper-bottom-bottom">

                            </div>
                            <div class="wcp-2-wrapper-bottom-bottom1">

                            </div>
                        </div>

                    </div>
                </div>
            </section>


            <section id="wcp-3" class="wcp-3">
                <div class="container1">
                    <div class="wcp-3-1">
                        <div class="title-wrap">
                            <h1 class="title">СТРУКТУРА</h1>
                        </div>
                    </div>
                    <div class="wcp-3-2">
                        <div class="container">
                            <div class="card c1">
                                <div>
                                    Правительство<br> Москвы
                                </div>
                            </div>
                            <div style="width: " class="card c2">
                                <div>
                                    Комитет по туризму<br> города Москвы
                                </div>
                            </div>
                            <div class="card c3">
                                <div>
                                    АНО «Проектный офис по развитию туризма <br> и гостеприимства Москвы»
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="wcp-3-3  rel">
                        <div class="left abs">
                            <img src="imgs/Vector-left.png">
                        </div>
                        <div style="color: #E30613" class="middle  hand-cursive abs">одна большая <br>
                            команда
                        </div>
                        <div class="right abs">
                            <img width="65%" src="imgs/Vector-right.png">
                        </div>
                    </div>
                </div>
            </section>

            <div id="leaders">
                <div class="wcp-6">
                    <div class="container1">
                        <div class="section-header flex">
                            <h1 style="text-transform: uppercase;" class="section-title title-blue">
                                Команда<br/>
                                руководителей
                            </h1>

                            <div class="section-info">
                                Комитет по туризму
                            </div>
                        </div>
                        <!-- Первый ряд: Председатель -->
                        <div class="row g-4 main-row">
                            <div class="col-12 col-sm-3 col-md-3">
                                <a class="person horizontal" href="https://www.mos.ru/tourism/structure/person/34365093/">
                                    <div class="rounded-custom">
                                        <img src="imgs/komitet-leaders/kozlov.png" alt="Козлов Евгений Александрович"
                                             class="person-img">
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-sm-3 col-md-3">
                                <a target="_blank" style="text-decoration: none!important;" class="person horizontal"
                                   href="https://www.mos.ru/tourism/structure/person/34365093/">
                                    <div class="bg-custom-blue info-card rounded-custom">
                                        <h2>Козлов Евгений Александрович</h2>
                                        <p>Председатель Комитета по туризму города Москвы</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Второй ряд: Заместители (Фотографии) -->
                        <div class="row g-4">
                            <!-- Суворова -->
                            <div class="col-12 col-md-3">
                                <div class="deputy-card">
                                    <div class="rounded-custom mb-3">

                                    </div>
                                </div>
                            </div>
                            <!-- Суворова -->
                            <div class="col-12 col-md-3">
                                <div class="deputy-card">
                                    <a target="_blank" style="text-decoration: none!important;" class="person"
                                       href="https://www.mos.ru/tourism/structure/person/103953093/">
                                        <div class="rounded-custom mb-3">
                                            <img src="imgs/komitet-leaders/suvorova.png" alt="Суворова Наталья Владимировна"
                                                 class="person-img">
                                        </div>
                                        <div class="bg-custom-blue info-card rounded-custom">
                                            <h2>Суворова Наталья Владимировна</h2>
                                            <p>Заместитель председателя Комитета по туризму города Москвы</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Арутюнова -->
                            <div class="col-12 col-md-3">
                                <div class="deputy-card">
                                    <a target="_blank" style="text-decoration: none!important;" class="person"
                                       href="https://www.mos.ru/tourism/structure/person/104422093/">
                                        <div class="rounded-custom mb-3">
                                            <img src="imgs/komitet-leaders/arutyunova.png" alt="Арутюнова Алина Зурабовна"
                                                 class="person-img">
                                        </div>
                                        <div class="bg-custom-blue info-card rounded-custom">
                                            <h2>Арутюнова Алина Зурабовна</h2>
                                            <p>Заместитель председателя Комитета по туризму города Москвы</p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Нурмуханов -->
                            <div class="col-12 col-md-3">
                                <div class="deputy-card">
                                    <a target="_blank" style="text-decoration: none!important;" class="person"
                                       href="https://www.mos.ru/tourism/structure/person/104922093/">
                                        <div class="rounded-custom mb-3">
                                            <img src="imgs/komitet-leaders/nurmuhametov.png"
                                                 alt="Нурмуханов Булат Абдулнадирович" class="person-img">
                                        </div>
                                        <div class="bg-custom-blue info-card rounded-custom">
                                            <h2>Нурмуханов Булат Абдулнадирович</h2>
                                            <p>Заместитель председателя Комитета по туризму города Москвы</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <section style="margin-bottom: 50px;" class="team wcp-8">

                    <div class="container1">
                        <div style="margin-bottom: 50px;margin-top: 70px" class="section-header flex">
                            <h1 style="text-transform: uppercase;" class="section-title title-blue">
                                Команда<br/>
                                руководителей
                            </h1>

                            <div class="section-info">
                                <p class="section-info1">
                                    АНО Проектный офис<br/>
                                    по развитию туризма<br/>
                                    и гостеприимства Москвы
                                </p>
                            </div>
                        </div>
                        <div style="margin-bottom: 10px;" class="team__grid sed">
                            <a href="/company/personal/user/196/" class="team-card team-card--photo">
                                <img src="imgs/sedova.png" alt="Седова Татьяна Владимировна">
                            </a>
                            <a href="/company/personal/user/196/" class="team-card team-card--text">
                                <h3>Седова Татьяна<br> Владимировна</h3>
                                <p>Генеральный <br> директор</p>
                            </a>
                        </div>
                        <div class="team__grid">

                            <a href="/company/personal/user/195/"" class="team-card team-card--text">
                            <h3>Никанорова Мария Максимовна</h3>
                            <p>Первый заместитель генерального директора по внешним коммуникациям</p>
                            </a>

                            <a href="/company/personal/user/195/" class="team-card team-card--photo">
                                <img src="imgs/nikanorova.png" alt="Никанорова Мария Максимовна">
                            </a>

                            <a href="/company/personal/user/228/" class="team-card team-card--text">
                                <h3>Попова Анастасия Александровна</h3>
                                <p>Первый заместитель генерального директора по развитию делового туризма</p>
                            </a>

                            <a href="/company/personal/user/228/" class="team-card team-card--photo">
                                <img src="imgs/popova.png" alt="Попова Анастасия Александровна">
                            </a>

                            <a href="/company/personal/user/145/" class="team-card team-card--photo">
                                <img src="imgs/makeeva.png" alt="Макеева Елена Александровна">
                            </a>

                            <a href="/company/personal/user/145/" class="team-card team-card--text">
                                <h3>Макеева Елена Александровна</h3>
                                <p>Заместитель генерального директора по финансам</p>
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/160/"
                               class="team-card team-card--photo">
                                <img src="imgs/nefedova.png" alt="Нефедова Анна Викторовна">
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/160/"
                               class="team-card team-card--text">
                                <h3>Нефедова Анна Викторовна</h3>
                                <p>Заместитель генерального директора по региональному взаимодействию</p>
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/79/"
                               class="team-card team-card--text">
                                <h3>Молочкова Наталья Александровна</h3>
                                <p>Заместитель генерального директора по международному сотрудничеству и общегородским
                                    проектам</p>
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/79/"
                               class="team-card team-card--photo">
                                <img src="imgs/molochrjva.png" alt="Молочкова Наталья Александровна">
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/1185/"
                               class="team-card team-card--text">
                                <h3>Калачева Мария Александровна</h3>
                                <p>Заместитель генерального директора по развитию и реализации специальных проектов</p>
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/1185/"
                               class="team-card team-card--photo">
                                <img src="imgs/kalacheva.png" alt="Калачева Мария Александровна">
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/30/"
                               class="team-card team-card--photo">
                                <img src="imgs/miheykina.png" alt="Михейкина Наталья Анатольевна">
                            </a>

                            <a href="https://corp-portal.welcome.moscow/company/personal/user/30/"
                               class="team-card team-card--text">
                                <h3>Михейкина Наталья Анатольевна</h3>
                                <p>Директор дивизиона</p>
                            </a>

                        </div>
                    </div>
                </section>
            </div>

            <section class="about wcp-9">
                <div class="container">

                    <h1 class="title title-blue">Наша миссия</h1>

                    <div class="mission-card">
                        <div style="flex-wrap: wrap;padding-left: 7%" class="mission-card_inner-wrapp flex">


                            Заключается в формировании и продвижении положительного имиджа Москвы<br>
                            как одного из ведущих туристических направлений в мире.

                            <svg style="margin-left: 32%;" width="433" height="30" viewBox="0 0 1333 62" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3811 36.7936C29.2697 35.7843 43.3575 34.7649 54.1988 34.0718C57.6148 33.8524 55.9048 34.2105 58.2617 34.0697C62.8699 33.7948 67.1384 33.353 71.6728 33.1063C73.1754 33.0257 71.3357 33.5213 72.9103 33.4289C81.537 32.9204 89.6449 32.2155 98.3005 31.6451C141.541 28.7941 183.369 26.07 227.143 23.5171C241.346 22.6884 259.006 21.954 273.772 20.9681C275.497 20.8525 274.45 20.6025 274.777 20.5582C279.9 19.8741 278.225 20.5958 281.582 20.4884C294.757 20.0694 310.02 19.2648 322.765 18.4754C328.214 18.1376 322.719 17.8098 328.22 17.8678C328.602 17.8713 327.238 18.2715 328.733 18.2288C332.114 18.1312 338.531 17.7426 341.23 17.6319C346.367 17.4232 347.213 17.3774 352.049 17.1398C358.656 16.8184 366.48 16.6 373.483 16.1915C374.978 16.1033 372.982 15.861 375.364 15.6902C377.723 15.5202 384.109 15.2486 386.293 15.2271C388.463 15.2059 387.553 15.5134 387.939 15.5562C391.118 15.921 398.478 14.8751 399.423 14.8154C416.645 13.7383 431.491 13.5084 447.754 12.8465C459.988 12.348 473.183 11.7036 484.96 11.2869C498.913 10.795 510.645 10.5665 524.333 10.0797C525.768 10.0292 524.074 9.68888 525.825 9.62654C544.229 8.97178 563.003 8.4447 581.289 8.2053C583.124 8.18166 580.77 8.59479 582.603 8.59206C593.99 8.55949 612.096 8.78672 618.664 7.11635C626.185 6.99303 635.834 6.83284 642.658 6.98958C647.09 7.08975 642.804 7.56133 648.226 7.28307C649.447 7.22102 647.852 6.92301 649.682 6.85517C657.314 6.57151 665.888 6.50722 673.61 6.18975C675.177 6.12447 674.743 5.83529 675.186 5.77339C678.911 5.26916 685.082 5.87494 686.418 5.90373C696.466 6.11481 704.287 5.83414 713.809 5.69892C716.026 5.66785 713.4 5.30791 715.532 5.26381C726.462 5.04264 738.125 4.9209 748.962 4.97025C751.367 4.9823 749.16 5.34854 750.309 5.37176C755.368 5.47077 756.475 4.81583 761.276 4.90291C762.286 4.92058 761.72 5.21791 761.905 5.24793C764.857 5.71872 767.471 4.98082 768.228 4.95753C777.892 4.64685 783.984 5.33621 793.053 5.15987C794.144 5.13814 791.665 4.78858 794.759 4.74995C815.732 4.48827 837.483 4.48929 857.857 4.74554C861.09 4.78485 858.783 5.066 858.987 5.09974C862.3 5.62697 864.418 4.90981 865.46 4.88154C873.729 4.67732 880.984 5.27619 886.806 4.42827C921.451 4.74043 956.075 4.43935 990.437 5.4613C990.871 5.47338 990.091 5.81945 990.956 5.84203C1001.22 6.11746 999.375 5.55695 1006.4 4.94332C1008.19 4.78945 1013.44 5.63916 1013.89 5.70197C1015.56 5.93356 1013.82 6.44311 1014.38 6.48986C1018.95 6.85822 1018.36 5.12232 1024.86 5.1737C1025.06 5.17821 1024.21 5.49007 1024.75 5.5261C1026.44 5.63907 1028.1 5.52559 1029.71 5.648C1033.42 5.93089 1034.53 6.78131 1040.5 6.33622C1041.22 6.27781 1040.18 5.28884 1045.87 5.37565C1047.98 5.41116 1058.34 5.45993 1061.1 5.53531C1063.87 5.61255 1061.96 5.91964 1062.16 5.93434C1069.42 6.38557 1066.91 6.54716 1070.73 7.17955C1074.59 7.80698 1073.5 6.33139 1075.05 6.17001C1075.81 6.0894 1077.54 6.13681 1079.2 6.21744C1080.9 6.29865 1082.64 6.4057 1083.33 6.46113C1084.71 6.57167 1082.99 6.78499 1084.22 6.84398C1092.4 7.20349 1087.76 6.07475 1090.43 5.80634C1096.76 5.15124 1101.62 6.11671 1107.39 6.05092C1108.93 6.03234 1107.66 5.82288 1109.04 5.75653C1114.59 5.49026 1117.8 5.71689 1124.11 5.16046C1125.11 5.07139 1130.11 4.21513 1133.44 4.55604C1133.9 4.606 1132.62 4.93271 1134.94 4.89565C1138.39 4.84153 1144.76 4.6212 1148.48 4.36242C1151.25 4.17033 1148.04 3.86603 1151.55 3.8472C1153.11 3.8388 1150.23 4.2316 1153.17 4.19377C1156.06 4.15761 1163.48 3.80643 1166.3 3.66867C1169.11 3.53113 1167.56 3.26166 1167.79 3.22841C1169.15 3.03441 1174.56 2.62523 1175.04 2.60433C1199.14 1.48546 1221.34 1.10639 1244.87 0.548669C1246.69 0.507904 1245.47 0.166928 1245.75 0.157007C1252.07 -0.0722212 1247.1 0.938993 1251.53 1.21412C1250.9 0.493633 1253.46 0.5938 1257.92 0.506784C1260.08 0.466292 1258.9 0.185552 1259.1 0.156459C1262.31 -0.270877 1264.48 0.301 1265.04 0.418243C1267.14 0.850556 1269.49 0.391222 1270.9 0.694187C1272.31 0.996288 1269.05 1.34252 1273.26 1.41955C1274.44 1.18479 1271.34 0.435595 1274.53 0.396329C1286.88 0.157672 1309.6 2.15068 1319.25 5.18453C1327.77 7.78564 1332.41 10.0881 1332.86 15.9824C1332.86 16.3047 1332.84 16.6266 1332.82 16.9486C1332.33 22.3687 1330.29 23.6295 1329.66 24.5736C1328.8 25.4456 1328.38 26.2433 1325.27 27.6258C1318.92 29.8462 1321.74 27.5748 1320.76 27.6027C1319.86 27.5288 1318.91 27.9888 1317.79 28.0175C1316.65 28.0431 1319.02 27.5039 1316.42 27.8334C1313.84 28.1955 1306.68 29.2005 1305.74 29.5186C1303.81 30.222 1307.11 30.6774 1300.08 30.8262C1299.67 30.8331 1300.96 30.4427 1299.43 30.4848C1288.72 30.7655 1277.62 31.1577 1266.82 31.712C1265.35 31.7851 1263.84 31.8501 1262.29 31.9067C1260.65 31.9635 1262.1 31.5223 1261.14 31.5506C1250.99 31.8035 1254.59 32.2341 1249.07 33.1257C1250.29 32.172 1247.19 31.7486 1239.47 31.9474C1234.81 32.0614 1235.63 32.4347 1232.63 32.7805C1231.73 32.885 1228.43 32.6798 1227.85 32.8764C1225.9 33.5199 1228.99 34.1508 1221.03 34.0157C1220.68 34.0085 1221.79 33.6573 1220.58 33.6569C1212.75 33.648 1211.85 34.295 1203.39 34.48C1196.76 34.624 1185.01 34.3671 1177.47 34.3968C1177.62 33.3757 1170.5 33.6977 1167.63 34.4401C1162.74 34.4775 1157.46 34.5959 1152.74 34.5792C1151.11 34.5739 1152.18 34.2608 1151.97 34.2324C1148.82 33.7866 1146.7 34.5104 1145.65 34.5574C1134.22 35.0402 1124.89 34.7521 1114.01 35.6311C1113.62 35.6633 1113.01 36.3902 1110.08 35.9818C1109.89 35.9535 1113.47 35.4965 1108.36 35.6645C1103.22 35.8318 1093.53 35.8844 1086.44 36.6394C1084.71 36.8191 1080.42 37.6601 1076.89 37.4641C1076.42 37.4363 1076.91 37.1976 1076.24 37.1329C1074.07 36.9296 1072.88 37.7362 1070.31 37.3346C1068.84 37.0976 1066.28 37.0063 1063.13 37.198C1060.79 37.344 1058.25 37.6133 1055.24 38.0893C1055 38.1256 1053.41 38.8543 1051.13 39.0797C1047.52 39.4362 1042.48 39.8616 1038.69 40.301C1036.91 40.5065 1038.09 40.7259 1037.78 40.7677C1025.74 42.3949 1017.16 43.1019 1004.32 45.2632C1001.97 45.6812 995.342 47.3999 990.953 48.5936C988.55 49.2971 988.333 48.9421 987.365 49.0576C987.117 49.1134 986.766 49.2404 986.372 49.6018C986.137 49.8521 985.881 50.1397 985.755 50.7536C985.698 51.0585 985.713 51.4273 985.81 51.7576C985.895 52.0588 986.045 52.3462 986.237 52.602C987.673 54.244 989.496 54.6698 992.417 55.2835C992.502 55.2992 992.589 55.3149 992.678 55.3306C997.536 56.1694 1003.55 54.6629 1011.89 56.4991C1028.23 57.5066 1043.38 58.6269 1059.78 58.6393C1060.52 58.6396 1062.86 58.0813 1064.6 58.1246C1067.18 58.1907 1068.58 58.501 1070.42 58.5507C1078.13 58.763 1085.18 58.5813 1092.61 58.6345C1095.43 58.6518 1094.44 58.9481 1097.81 58.9612C1101.17 58.9703 1103.41 58.5736 1107.98 58.7224C1111.4 58.8341 1114.88 59.5889 1119.44 59.5983C1119.54 59.078 1120.97 58.8844 1123.48 58.7139C1124.81 58.6249 1126.41 58.542 1128.31 58.4197C1129.39 58.3496 1128.22 58.1086 1128.73 58.0613C1130.8 57.8628 1132.7 58.0301 1134.66 57.8096C1135.13 57.7564 1135.14 57.4877 1135.53 57.4289C1138.85 56.9216 1144.26 57.2877 1145.24 57.3436C1148.82 57.5722 1150.48 57.1329 1153.26 57.132C1156 57.1292 1151.93 57.5651 1155.13 57.4716C1161.81 57.2745 1170.34 56.5866 1177.08 55.9477C1179.78 55.6902 1177.9 55.5119 1178.28 55.4668C1183.82 54.8393 1189.48 54.5569 1192.46 53.4572C1196.54 54.7833 1195.79 52.8843 1203.71 52.0902C1204.35 52.0236 1205.26 52.7698 1202.9 53.2533C1200.53 53.7361 1196.84 53.9823 1193.56 54.6032C1192.78 54.7556 1188.36 56.1172 1187.74 56.2328C1182.93 57.172 1182.11 55.9283 1181.85 55.8878C1178.87 55.4303 1173.42 56.8172 1171.98 56.9965C1165.33 57.8475 1159.61 58.0545 1154.06 58.5809C1150.39 58.9202 1150.11 59.787 1147.21 59.9068C1137.65 60.2716 1134.27 59.1904 1125.3 60.4573C1124.25 60.6013 1124.34 60.9263 1123.53 61.1043C1122.87 61.2543 1121.56 61.3008 1118.36 61.0207C1117.93 60.984 1112.79 60.3946 1109.8 60.8467C1109.43 60.9041 1109.45 61.1757 1108.96 61.2034C1105.67 61.3822 1103.47 61.1803 1099.87 61.2962C1096.28 61.4115 1091.95 61.5634 1087.86 61.4501C1085.38 61.3833 1082.22 60.8508 1081.04 60.715C1077.7 60.3268 1074.11 60.8639 1072.05 60.3991C1069.98 59.9309 1074.06 59.3391 1068.6 58.9661C1068.38 59.6686 1067.9 60.3848 1067.57 61.0888C1055.57 60.8401 1043.78 60.264 1031.75 59.715C1030.26 59.6466 1031.76 60.1008 1030.17 60.0364C1023.43 59.7518 1017.5 59.1259 1010.53 58.65C1005.05 58.2538 999.11 58.112 993.254 57.1061C992.909 56.9565 992.523 56.8065 992.115 56.6549C991.042 56.2713 989.889 55.9209 988.398 55.3063C988.016 55.1436 987.685 54.9961 987.208 54.741C986.987 54.6213 986.775 54.4949 986.56 54.3513C986.283 54.1661 986.01 53.9577 985.745 53.7C985.232 53.2411 984.24 51.8956 984.633 50.2637C984.785 49.6031 985.1 49.0915 985.405 48.7154C985.653 48.411 985.918 48.1633 986.192 47.9421C989.839 45.5258 992.788 45.145 995.586 44.3784C997.681 43.9003 997.633 44.1429 999.132 43.8877C1004.87 42.9786 1002.1 42.8287 1003.61 42.4176C1010.25 40.738 1011.06 42.2521 1018.96 41.06C1019.38 40.9925 1024.4 39.4401 1025.07 39.3043C1029.52 38.3761 1025.88 39.499 1028.66 39.209C1033.84 38.6793 1037.35 38.4741 1043.35 37.558C1045.1 37.2898 1049.24 36.3073 1052.71 36.3729C1053.16 36.382 1052.94 36.6902 1053.3 36.6705C1059.42 36.314 1059.85 35.5256 1062.91 34.879C1063.14 34.8295 1063.39 34.7811 1063.65 34.7343C1067.36 34.0763 1073.2 34.2376 1074.41 34.3301C1077.53 34.5608 1072.05 34.7031 1071.89 34.7661C1069.95 35.4592 1074.23 35.0214 1077.41 34.8478C1077.92 35.6873 1082.47 35.3335 1080.77 34.6632C1080.57 34.5896 1078.05 34.8414 1077.41 34.8478C1080.18 33.7883 1085.16 33.3525 1090.65 33.8624C1093.23 34.1065 1090.86 34.6529 1098.61 34.1199C1102.17 33.8852 1100.74 33.3442 1103.85 33.0462C1110.96 32.3677 1120.25 32.6258 1127.59 32.3441C1130.25 32.2416 1127.65 31.9791 1129.46 31.8983C1134.29 31.6844 1138.56 31.8369 1143.41 31.6126C1143.85 31.5919 1142.77 31.2671 1144.28 31.2329C1155.72 30.9699 1165.69 31.0536 1177.63 30.9129C1183.4 30.8444 1193.72 30.5369 1199.69 30.503C1210.06 30.4448 1222.76 30.7707 1231.62 30.4571C1237.36 30.2537 1236.1 29.9318 1238.57 29.6358C1243.3 29.0686 1251.77 29.5976 1252.88 29.6721C1252.88 29.6793 1248.01 29.938 1250.63 30.0209C1252.16 30.3355 1252.82 29.6759 1252.88 29.6721C1253.68 29.7394 1263.49 29.3615 1265.47 29.1465C1266.51 29.0453 1265.91 28.7544 1266.44 28.7075C1266.51 28.7022 1266.57 28.6969 1266.63 28.6916C1279.87 27.5101 1293.1 27.0727 1306.38 26.4312C1306.62 26.4224 1306.29 26.8119 1306.84 26.7863C1314.44 26.3747 1319.65 25.5437 1324.57 23.2528C1328.36 21.3287 1328.83 19.0428 1329.23 15.9824C1329.31 15.4825 1329.32 15.0505 1329.26 14.5735C1328.29 11.8392 1321.42 8.95103 1317.9 7.95281C1313.98 6.79408 1313.53 7.52516 1313.14 7.44291C1302.52 5.97094 1296.29 5.17106 1284.99 4.49609C1279.62 4.18354 1268.94 4.05344 1263.08 4.12173C1258.49 4.16311 1255.66 4.00609 1251.43 4.1466C1250.86 4.16591 1251.44 4.47881 1251.27 4.49259C1246.5 4.92011 1248.78 4.12166 1247.79 3.99327C1244.24 3.53471 1242.05 3.29371 1235.94 3.66259C1234.55 3.7464 1230.49 4.96386 1227.9 5.10194C1224.58 5.27832 1225.84 4.84033 1222.36 5.04241C1222.06 5.05975 1222.13 5.30418 1219.97 5.36177C1210 5.63197 1199.07 5.91986 1188.7 6.39087C1187.28 6.45444 1189.12 6.76814 1187.07 6.86673C1177.45 7.32954 1166.93 7.80062 1157.53 8.04574C1155.22 8.10425 1156.65 7.76386 1156.15 7.70509C1153.07 7.35138 1148.7 8.26642 1147.99 8.31164C1130.85 9.41529 1117.68 9.34785 1100.1 10.1161C1099.19 10.1621 1091.76 11.0944 1088.53 10.469C1088.36 10.4278 1084.99 9.7968 1082.14 10.2699C1081.97 10.2989 1083.97 10.6401 1080.48 10.6165C1080.08 10.6133 1079.65 10.6133 1079.2 10.615C1075.9 10.6287 1071.03 10.7611 1066.48 10.4052C1064.97 10.2789 1061.57 9.59144 1056.91 10.1014C1056.61 10.1379 1049.36 11.3066 1047.02 10.4522C1046.6 10.3113 1047.1 9.42957 1041.6 9.84407C1040.54 9.92435 1042.47 10.1363 1039.67 10.2389C1035.13 10.3954 1030.05 10.1183 1024.75 10.4707C1023.87 10.5336 1019.83 11.1026 1016.79 10.6216C1016.32 10.5464 1017.21 10.2577 1015.64 10.2119C1008.25 9.99372 999.492 10.1218 992.04 9.86109C989.285 9.7685 990.705 9.30084 987.808 9.15122C984.967 9.00194 978.775 8.83456 975.917 9.03381C972.951 9.23234 976.198 10.0163 971.73 10.0475C951.656 10.1796 932.459 9.63739 912.633 9.51985C909.65 9.50254 912.133 9.78115 907.713 9.77606C892.146 9.75393 864.552 10.0158 850.629 9.47959C839.733 9.04566 853.457 8.65344 845.113 8.48864C841.078 8.41064 836.655 9.31345 835.665 9.34529C825.89 9.66743 819.346 9.30884 810.729 9.48139C808.944 9.5174 810.697 9.86568 809.177 9.89246C803.994 9.98717 798.422 9.86594 793.417 9.93396C791.232 9.96492 791.637 10.2361 789.619 10.231C784.786 10.2226 780.473 10.1449 775.676 9.93253C775.239 9.9132 778.183 9.54438 774.542 9.56583C768.649 9.60171 761.193 9.4248 754.602 9.76155C752.616 9.86301 752.552 10.4901 748.254 10.108C747.349 10.0285 748.501 9.2611 743.482 9.97307C742.645 10.0908 734.166 11.0758 729.934 10.5689C728.843 10.4349 726.109 9.85646 723.725 9.98721C723.402 10.0073 724.701 10.3255 722.727 10.3865C715.768 10.5959 708.389 10.6726 701.074 10.8977C699.119 10.9586 702.038 11.2591 699.123 11.3389C691.125 11.5587 681.501 11.6408 673.684 11.6803C670.818 11.6939 672.793 11.3676 672.584 11.3402C669.219 10.8933 667.236 11.6221 666.117 11.6843C659.054 12.0643 654.037 11.8439 647.687 12.0653C645.944 12.1255 647.187 12.4277 646.841 12.4644C643.242 12.8287 645.916 12.1802 643.63 12.1647C637.224 12.1198 630.009 12.3103 623.434 12.5085C621.087 12.5802 623.735 12.8986 621.628 12.9587C609.177 13.3216 595.879 13.6272 583.47 13.7591C581.173 13.7822 582.609 13.4489 582.129 13.3951C578.817 13.0171 573.971 13.7946 572.85 13.8878C567.757 14.3121 565.759 14.1108 561.222 14.2936C539.017 15.1889 511.598 15.9285 491.004 16.5772C463.649 17.442 442.796 18.001 416.782 19.2018C415.149 19.2773 416.813 19.5956 415.405 19.6658C397.244 20.5537 378.905 21.2508 360.741 22.2537C359.237 22.336 361.425 22.5994 359.01 22.7442C354.056 23.0438 347.749 23.3387 342.687 23.4774C340.217 23.5458 343.51 23.0603 341.024 23.1358C335.156 23.3152 329.209 23.5965 323.32 23.9658C320.827 24.1233 324.611 24.3031 321.368 24.4784C299.154 25.6897 277.487 26.968 254.962 27.811C253.415 27.869 252.013 27.181 248.728 27.5254C248.493 27.7535 248.521 28.0035 248.427 28.2379C216.316 30.2849 184.38 32.0787 151.858 34.2922C150.481 34.386 152.888 34.6277 150.223 34.8116C135.079 35.8586 120.271 37.033 104.493 37.7939C102.938 37.8694 103.64 37.0974 99.1085 37.5435C98.3129 37.6221 91.4868 38.2294 89.726 38.5191C89.4872 38.5607 90.7619 38.8002 88.5559 38.9767C75.7463 39.9955 62.1573 40.9721 49.2327 41.7944C47.1711 41.9245 49.3356 41.3946 47.8667 41.4844C37.4472 42.1235 26.0878 43.3182 16.0111 43.8279C9.26328 44.1687 11.7031 43.3481 8.07099 43.042C7.55771 42.9988 3.37678 43.3356 3.1302 43.0023C2.50049 42.1643 4.44525 41.0255 3.53472 40.1524C3.34307 39.9637 -2.24619 40.6549 1.03263 39.8844C1.26238 39.8317 5.3113 39.3275 6.29623 39.0777C6.98251 38.9043 5.69855 38.6515 7.10604 38.3853C8.51323 38.1195 10.0976 38.2892 12.4328 37.9208C14.7658 37.5508 13.702 37.2285 18.3811 36.7936ZM1300.56 4.31507C1296.86 3.60945 1296.18 4.81278 1300.25 4.89988C1300.45 4.90339 1300.75 4.35127 1300.56 4.31507ZM1043.89 59.248C1039.05 58.5165 1035.72 59.1811 1039.12 59.7531C1042.51 60.3196 1045.35 59.4683 1043.89 59.248ZM1094.14 59.7394C1088.48 59.292 1087.58 61.0689 1093.65 60.7232C1094.01 60.7033 1094.47 59.765 1094.14 59.7394ZM24.6321 41.4986C24.5801 41.4861 21.5913 41.715 21.5209 41.7375C19.9628 42.2657 25.9392 41.8075 24.6321 41.4986ZM1143.97 58.3196C1142.23 58.1622 1137.13 58.6412 1139.34 58.8538C1141.12 59.0288 1146.2 58.5186 1143.97 58.3196ZM1107.56 6.65912C1107.5 6.64262 1105.38 6.68883 1105.42 6.79295C1105.57 7.13031 1108.53 6.94473 1107.56 6.65912ZM1228.09 48.575C1215.77 50.9138 1208.05 50.561 1221.48 48.4456C1223.91 48.0448 1216.33 49.8326 1219.45 49.4185C1225.15 48.6438 1228.7 47.0847 1234.47 46.0962C1235.52 45.9419 1228.82 48.4862 1228.07 48.5814C1228.07 48.5793 1228.08 48.5772 1228.09 48.575ZM1298.55 2.60365C1298.42 2.57067 1295.56 2.31025 1295.47 2.32393C1293.86 2.5873 1299.73 3.11841 1298.55 2.60365ZM982.422 5.74618C982.231 5.70914 975.252 5.62738 975.103 5.65927C973.348 5.9735 975.852 6.17053 978.467 6.20184C981.214 6.23403 983.978 6.09993 982.422 5.74618ZM1177.35 34.3711C1174.13 34.8116 1170.23 34.909 1167.65 34.4404C1170.86 34.4235 1174.12 34.3859 1177.35 34.3711Z"
                                      fill="#E30613"/>
                            </svg>

                            Мы стремимся повышать узнаваемость Москвы, привлекать как российских,<br>
                            так и иностранных туристов, раскрывая туристический потенциал столицы <br>
                            и её богатое культурное наследие.

                        </div>
                    </div>

                    <h2 class="title title-blue">Наши ценности</h2>

                    <div class="values">
                        <div class="value value-1">
                            <div class="value-number">01.</div>
                            <div class="value-text">
                                <h3>Сотрудничество<br>и командная работа</h3>
                                <p>
                                    Мы убеждены, что лучшие результаты достигаются совместными усилиями.
                                </p>
                            </div>
                        </div>

                        <div class="value value-2">
                            <div class="value-number">02.</div>
                            <div class="value-text">
                                <h3>Стремление к развитию<br>и новым знаниям</h3>
                                <p>
                                    У нас регулярно проходят тренинги, обучения, организованы бесплатные
                                    курсы английского языка для сотрудников.
                                </p>
                            </div>
                        </div>

                        <div class="value value-3">
                            <div class="value-number">03.</div>
                            <div class="value-text">
                                <h3>Открытость<br>и прозрачность</h3>
                                <p>
                                    Мы стремимся к открытому и честному общению на всех уровнях нашей
                                    организации. Обращаемся на вы к руководителям, на ты к сотрудникам.
                                </p>
                            </div>
                        </div>

                        <div class="value value-4">
                            <div class="value-number">04.</div>
                            <div class="value-text">
                                <h3>Уважение<br>и взаимопонимание</h3>
                                <p>
                                    Мы ценим разнообразие наших сотрудников и уважаем их уникальные
                                    взгляды и идеи.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="projects">

                        <div class="projects-head" style="margin-bottom:50px ">
                            <h2 class="title title-red">
                                Проекты, которыми<br>
                                мы гордимся
                            </h2>
                        </div>

                        <div class="projects1">
                            <? $APPLICATION->IncludeComponent(
                                    "star:owlcarousel",
                                    "template1",
                                    [
                                            "AUTO" => "false",
                                            "COMPONENT_TEMPLATE" => "template1",
                                            "CONTROLS" => "true",
                                            "COUNT" => "999",
                                            "COUNT_SLIDES" => "3",
                                            "DATA_TYPE" => "IBLOCK",
                                            "FOLDER" => "/upload/",
                                            "IBLOCK_ID" => "49",
                                            "IBLOCK_TYPE" => "slider",
                                            "IMAGE" => "PREVIEW",
                                            "JQUERY" => "N",
                                            "LINK" => "Y",
                                            "LOOP" => "true",
                                            "MARGIN" => "1",
                                            "NEW_WINDOW" => "Y",
                                            "PAGER" => "true",
                                            "PAUSE" => "5",
                                            "PROPERTY_CODE" => "WCP_SLIDER1",
                                            "SORT_BY1" => "SORT",
                                            "SORT_ORDER1" => "DESC"
                                    ],
                                    false,
                                    [
                                            "ACTIVE_COMPONENT" => "Y"
                                    ]
                            ); ?>
                        </div>
                    </div>


                </div>
            </section>
        </div>


    </div>



    <div id="adresses" class="wcp-y container">
        <div class="container1">
            <section class="team wcp-map">
                <section class="section ano">
                    <header class="section-header">
                        <h1 class="section-title" style="padding-top: 30px; padding-bottom: 30px">
                            АДРЕСА ОФИСОВ
                        </h1>
                    </header>

                </section>
                <? $APPLICATION->IncludeComponent(
                        "webmaxima:yandexmap.pointview",
                        "template1",
                        [
                                "IBLOCK_ID" => "17"
                        ],
                        false,
                        [
                                "ACTIVE_COMPONENT" => "Н"
                        ]
                ); ?>
            </section>
            <section class="team wcp-slider2">
                <header class="section-header">
                    <h1 style="text-transform: uppercase;padding-top: 50px" id="addr" class="section-title"
                        style="padding-top: 30px; padding-bottom: 30px">
                        ТВЕРСКАЯ, 5А
                    </h1>
                </header>
                <? $APPLICATION->IncludeComponent(
                        "star:owlcarousel",
                        "template2",
                        [
                                "AUTO" => "false",
                                "COMPONENT_TEMPLATE" => "template2",
                                "CONTROLS" => "true",
                                "COUNT" => "999",
                                "COUNT_SLIDES" => "1",
                                "DATA_TYPE" => "IBLOCK",
                                "FOLDER" => "/upload/",
                                "IBLOCK_ID" => "50",
                                "IBLOCK_TYPE" => "slider",
                                "IMAGE" => "PREVIEW",
                                "JQUERY" => "N",
                                "LINK" => "Y",
                                "LOOP" => "true",
                                "MARGIN" => "20",
                                "NEW_WINDOW" => "Y",
                                "PAGER" => "true",
                                "PAUSE" => "5",
                                "PROPERTY_CODE" => "URL",
                                "SORT_BY1" => "SORT",
                                "SORT_ORDER1" => "DESC"
                        ],
                        false,
                        [
                                "ACTIVE_COMPONENT" => "Y"
                        ]
                ); ?>
            </section>

            <main class="page wcp-11">

                <section class="schedule">
                    <div class="schedule__header">
                        <h1 class="title-red">График работы</h1>
                        <p>График работы одинаковый<br>для отделов и сотрудников проектов</p>
                    </div>

                    <div class="schedule__grid">
                        <div class="card schedule__card">
                            <h3>Понедельник — четверг с 9:00 до 18:00</h3>
                            <p>работаем в едином ритме</p>
                        </div>

                        <div class="card schedule__card">
                            <h3>Пятница — с 9:00 до 16:45</h3>
                            <p>в пятницу у нас короткий день</p>
                        </div>

                        <div class="card schedule__card">
                            <h3>Обед 45 минут</h3>
                            <p>успеваем передохнуть и пообедать</p>
                        </div>
                        <div class="card schedule__card">
                            <h3>Суббота и воскресенье — выходные дни</h3>
                            <p>планируем прогулки по Москве и досуг по душе</p>
                        </div>
                    </div>
                </section>

                <section id="orders" class="rules">
                    <h2>Правила</h2>

                    <div class="rules__grid">

                        <div class="rules__column">
                            <article class="card rule-card">
                                <h3>Здоровый образ жизни</h3>
                                <ul>
                                    <li>У нас в офисе не курят.</li>
                                    <li>Для сотрудников доступна йога в офисе, настольный теннис.</li>
                                </ul>
                            </article>

                            <article class="card rule-card">
                                <h3>Работа в команде</h3>
                                <ul>
                                    <li>Уважайте коллег независимо от должности, опыта, возраста и взглядов.</li>
                                    <li>Поддерживайте атмосферу сотрудничества и взаимопомощи.</li>
                                    <li>Работайте на общий результат команды.</li>
                                    <li>Будьте вежливы и доброжелательны в общении.</li>
                                    <li>Берите ответственность за свои решения и задачи.</li>
                                    <li>Цените своё рабочее время и время коллег.</li>
                                </ul>
                            </article>

                            <article class="card rule-card">
                                <h3>Недопустимо</h3>
                                <ul>
                                    <li>Любые формы дискриминации.</li>
                                    <li>Грубость, оскорбления и агрессивное поведение.</li>
                                    <li>Домогательства любого характера.</li>
                                    <li>Использование служебного положения в личных целях.</li>
                                    <li>Распространение недостоверной информации.</li>
                                    <li>Действия, наносящие ущерб репутации организации.</li>
                                </ul>
                            </article>
                        </div>

                        <div class="rules__column">
                            <article class="card rule-card">
                                <h3>Конфликт интересов</h3>
                                <ul>
                                    <li>Сообщайте о возможных конфликтах интересов.</li>
                                    <li>Принимайте решения объективно и беспристрастно.</li>
                                    <li>Не допускайте влияния личной выгоды на рабочие решения.</li>
                                    <li>Соблюдайте установленные ограничения по подаркам и взаимодействию с
                                        партнёрами.
                                    </li>
                                </ul>
                            </article>

                            <article class="card rule-card">
                                <h3>Профессионализм</h3>
                                <ul>
                                    <li>Выполняйте задачи качественно и в срок.</li>
                                    <li>Постоянно развивайте свои знания и навыки.</li>
                                    <li>Ищите возможности для улучшения процессов.</li>
                                    <li>Делитесь опытом и лучшими практиками с коллегами.</li>
                                    <li>Проявляйте инициативу и предпринимательский подход.</li>
                                </ul>
                            </article>

                            <article class="card rule-card">
                                <h3>Забота о репутации</h3>
                                <ul>
                                    <li>Каждый сотрудник представляет организацию.</li>
                                    <li>Поддерживайте высокий уровень деловой культуры.</li>
                                    <li>Соблюдайте нормы делового общения как внутри организации, так и вне её.</li>
                                    <li>Бережно относитесь к ресурсам и имуществу организации.</li>
                                </ul>
                            </article>
                        </div>

                        <div class="rules__column">
                            <article class="card rule-card">
                                <h3>Коммуникации</h3>
                                <ul>
                                    <li>Общайтесь открыто и честно.</li>
                                    <li>Конструктивно обсуждайте рабочие вопросы.</li>
                                    <li>Участвуйте в обмене знаниями и опытом.</li>
                                    <li>Соблюдайте правила работы с конфиденциальной информацией.</li>
                                    <li>Используйте корпоративные каналы связи ответственно.</li>
                                </ul>
                            </article>

                            <article class="card rule-card">
                                <h3>Внешний вид</h3>
                                <ul>
                                    <li>Опрятность и аккуратность.</li>
                                    <li>Деловой стиль на официальных мероприятиях.</li>
                                    <li>Casual допустим в повседневной работе при соблюдении корпоративной культуры.
                                    </li>
                                    <li>Внешний вид должен соответствовать статусу организации и не наносить ущерб её
                                        репутации.
                                    </li>
                                </ul>
                            </article>
                        </div>

                    </div>
                </section>

            </main>
        </div>
    </div>
    <main style="padding-bottom: 50px;" class="page wcp-12">
        <section class="benefits">
            <div class="container1">
                <h1 class="benefits__title">Бенефиты</h1>
                <? $APPLICATION->IncludeComponent(
                        "bitrix:news",
                        "flat1",
                        [
                                "ADD_ELEMENT_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "Y",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "BROWSER_TITLE" => "-",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "Y",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CHECK_DATES" => "Y",
                                "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
                                "DETAIL_DISPLAY_TOP_PAGER" => "N",
                                "DETAIL_FIELD_CODE" => [
                                        0 => "",
                                        1 => "",
                                ],
                                "DETAIL_PAGER_SHOW_ALL" => "Y",
                                "DETAIL_PAGER_TEMPLATE" => "",
                                "DETAIL_PAGER_TITLE" => "Страница",
                                "DETAIL_PROPERTY_CODE" => array("DETAIL_IMG_TOP", "DETAIL_IMG_BOTTOM", "DETAIL_IMG_WIDTH"),
                                "DETAIL_SET_CANONICAL_URL" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "Y",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "Y",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "DISPLAY_TOP_PAGER" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "IBLOCK_ID" => "45",
                                "IBLOCK_TYPE" => "benefity",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                                "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "LIST_FIELD_CODE" => [
                                        0 => "",
                                        1 => "",
                                ],
                                "LIST_PROPERTY_CODE" => [
                                        0 => "IMG_TOP",
                                        1 => "IMG_BOTTOM",
                                        2 => "IMG_WIDTH",
                                        3 => "",
                                ],
                                "MESSAGE_404" => "",
                                "META_DESCRIPTION" => "-",
                                "META_KEYWORDS" => "-",
                                "NEWS_COUNT" => "20",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "PAGER_TITLE" => "Новости",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "SEF_MODE" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "SET_STATUS_404" => "N",
                                "SET_TITLE" => "N",
                                "SHOW_404" => "N",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER1" => "DESC",
                                "SORT_ORDER2" => "ASC",
                                "STRICT_SECTION_CHECK" => "N",
                                "USE_CATEGORIES" => "N",
                                "USE_FILTER" => "N",
                                "USE_PERMISSIONS" => "N",
                                "USE_RATING" => "N",
                                "USE_REVIEW" => "N",
                                "USE_RSS" => "N",
                                "USE_SEARCH" => "N",
                                "USE_SHARE" => "N",
                                "COMPONENT_TEMPLATE" => "flat",
                                "TEMPLATE_THEME" => "red",
                                "MEDIA_PROPERTY" => "",
                                "SLIDER_PROPERTY" => "",
                                "LIST_USE_SHARE" => "",
                                "VARIABLE_ALIASES" => [
                                        "SECTION_ID" => "SECTION_ID",
                                        "ELEMENT_ID" => "ELEMENT_ID",
                                ]
                        ],
                        false
                ); ?>

                <!--<div class="benefits__grid">

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279364&clear_cache=Y"
                       class="benefit-card ">
                    <span class="benefit-card__badge">
                        <img width="35" src="imgs/heart.png">
                    </span>
                        <div class="benefit-card__image1 rel">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 15%;top:20%;left: 20%;z-index: 999;"
                                 src="imgs/moneta-top.png" alt="">
                            <img class="abs" style="width: 27%;left: 10%;bottom: -25%;z-index: 999;"
                                 src="imgs/moneta-b.png" alt="">
                            <div class="benefit-card__bottom abs">
                                <span>Магазин бонусов</span>
                            </div>
                        </div>

                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279365&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 47%;left: -5%;z-index: 999;" src="imgs/hands.png" alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>ДМС</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279363&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 40%;left: 10%;top:10%;z-index: 999;" src="imgs/gift1.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Лотереи и розыгрыши</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279362&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 40%;left: 10%;top:10%;z-index: 999;" src="imgs/girl.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Йога в офисе</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279366&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 40%;left: 10%;top:10%;z-index: 999;" src="imgs/korp.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Корпоративные обучения</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279367&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 40%;left: 15%;top:10%;z-index: 999;" src="imgs/english.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Английский язык</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279368&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 40%;left: 10%;top:7%;z-index: 999;" src="imgs/klasses.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Мастер-классы, игры,<br>спортивные турниры</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/dfp.php" class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 40%;left: 10%;top:10%;z-index: 999;" src="imgs/Discounts.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Скидки от партнеров</span>
                        </div>
                    </a>

                    <a href="https://corp-portal.welcome.moscow/benefity/?ELEMENT_ID=279370&clear_cache=Y"
                       class="benefit-card">
                        <span class="benefit-card__badge"><img width="35" src="imgs/heart.png"></span>
                        <div class="benefit-card__image1">
                            <img class="abs" style="width: 100%;z-index: 9" src="imgs/fon.png">
                            <img class="abs" style="width: 80%;left: -10%;top:10%;z-index: 999;" src="imgs/sber.png"
                                 alt="">
                        </div>
                        <div class="benefit-card__bottom abs">
                            <span>Зарплатный проект от Сбера</span>
                        </div>
                    </a>
                </div>-->
            </div>
        </section>
    </main>
    <main id="links" class="page wcp-13">
        <div class="container1">
            <section class="section section-links">

                <h1 class="title title-blue">Полезные ссылки</h1>
                <div class="cards">
                    <a target="_blank" href="http://helpdesk.welcome.moscow/" class="card">Сервисдеск</a>
                    <a target="_blank" href="https://corp-portal.welcome.moscow/docs/shared/" class="card">Шаблоны
                        АНО</a>

                    <a href="https://corp-portal.welcome.moscow/hr/" class="card">Организационная структура</a>
                    <a href="#" class="card">Корпоративный мессенджер</a>

                    <a href="https://corp-portal.welcome.moscow/online/?IM_DIALOG=chat21130"
                       class="card card-handwritten rel">
                        <span style="font-family: " denistina", "Marck Script", cursive" class="hand
                        hand-left">интерактивы</span>
                        <div class="abs" style="left: 13%;top: 32%;">
                            <svg width="150" height="40" viewBox="0 0 421 122" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.97333 4.43209C7.16108 6.99867 7.28487 10.5061 7.40346 13.0444C7.44201 13.8596 7.17571 13.4737 7.19554 14.0414C7.23645 15.1491 7.48456 16.202 7.53405 17.2572C7.55083 17.6149 7.13729 17.2265 7.1634 17.6017C7.30807 19.6407 7.76156 21.6791 8.1062 23.6898C9.86737 33.9144 13.4071 43.6927 18.7205 52.8091C20.4371 55.784 22.6538 59.5501 24.9561 62.219C25.2278 62.5357 25.3161 62.1484 25.3853 62.1946C26.4657 62.9208 25.7207 62.9891 26.1757 63.677C28.0366 66.4582 30.4056 69.0695 32.7722 71.2247C33.7807 72.1366 33.2299 70.7458 33.976 71.9446C34.029 72.0275 33.5508 72.0251 33.7979 72.3098C34.3743 72.9729 35.5148 73.9442 35.9821 74.396C36.864 75.2544 37.0214 75.3872 37.9136 76.159C39.1127 77.1982 40.6192 78.634 41.9541 79.5691C42.2501 79.7762 42.0777 79.2182 42.5682 79.5231C43.0564 79.8291 44.2695 80.772 44.6487 81.144C45.0251 81.5168 44.6897 81.5997 44.7291 81.7036C45.0462 82.5719 46.9584 83.0454 47.1641 83.162C50.8802 85.3477 53.5802 87.4811 57.2037 89.5166C59.7845 90.9598 62.6671 92.2979 65.3194 93.5872C68.5607 95.1581 70.8841 96.3755 74.0613 97.6442C74.3919 97.7756 74.1551 97.2517 74.559 97.411C79.0184 99.1623 82.9254 100.61 87.3123 102.311C87.7475 102.48 87.0687 102.627 87.4982 102.815C90.2429 104.006 94.0887 105.836 96.1286 104.82C97.9251 105.379 100.161 106.026 101.782 106.742C102.83 107.203 101.673 107.31 103.064 107.483C103.378 107.522 103.058 107.103 103.524 107.186C105.398 107.513 107.321 108.064 109.2 108.296C109.58 108.342 109.541 108.029 109.658 108C110.634 107.768 111.982 108.781 112.302 108.899C114.718 109.789 116.657 110.004 119.018 110.44C119.538 110.536 118.994 110.032 119.497 110.11C122.105 110.518 124.851 111.005 127.487 111.583C128.073 111.712 127.477 111.967 127.755 112.046C128.986 112.389 129.358 111.794 130.553 112.105C130.805 112.169 130.62 112.437 130.662 112.475C131.326 113.076 132.067 112.459 132.259 112.47C134.74 112.59 136.055 113.504 138.288 113.665C138.554 113.683 137.999 113.246 138.747 113.319C143.678 113.8 149.339 114.444 154.296 115.117C155.055 115.22 154.49 115.455 154.535 115.492C155.278 116.084 155.842 115.408 156.094 115.4C158.082 115.35 159.919 116.068 161.344 115.302C170.109 116.123 178.321 115.901 187.004 116.74C187.115 116.749 186.914 117.1 187.134 117.117C189.796 117.334 189.291 116.782 191.068 116.117C191.53 115.95 192.847 116.756 192.948 116.815C193.36 117.032 192.935 117.557 193.072 117.599C194.217 117.927 194.022 116.197 195.569 116.187C195.617 116.19 195.416 116.51 195.546 116.541C195.951 116.637 196.352 116.508 196.75 116.614C197.662 116.859 197.996 117.698 199.391 117.191C199.568 117.125 199.282 116.147 200.689 116.173C201.248 116.185 203.746 116.123 204.456 116.17C205.162 116.218 204.685 116.545 204.735 116.558C206.648 116.933 205.931 117.123 206.905 117.719C207.882 118.311 207.578 116.846 207.965 116.672C208.353 116.498 209.776 116.802 210.129 116.912C210.481 117.021 210.053 117.235 210.365 117.294C212.44 117.673 211.246 116.535 211.918 116.28C213.507 115.673 214.803 116.718 216.165 116.772C216.54 116.786 216.225 116.55 216.558 116.513C217.876 116.372 218.69 116.678 220.196 116.298C220.442 116.236 221.642 115.524 222.467 115.966C222.583 116.03 222.274 116.318 222.851 116.351C223.713 116.4 225.269 116.38 226.197 116.239C226.882 116.135 226.068 115.73 226.943 115.822C227.332 115.863 226.631 116.164 227.361 116.219C228.087 116.275 229.94 116.157 230.65 116.105C231.357 116.054 230.95 115.737 231.005 115.711C231.343 115.56 232.696 115.312 232.817 115.305C239.1 114.868 244.208 115.061 250.042 114.383C250.495 114.33 250.162 113.998 250.231 113.987C251.776 113.705 250.628 114.759 251.746 114.984C251.526 114.272 252.18 114.349 253.278 114.173C253.807 114.087 253.492 113.832 253.538 113.799C254.29 113.303 254.891 113.804 255.044 113.903C255.606 114.271 256.135 113.729 256.519 113.973C256.904 114.215 256.114 114.698 257.184 114.595C257.461 114.307 256.603 113.703 257.406 113.518C260.455 112.821 266.232 112.781 268.527 112.592C270.896 112.398 271.663 112.266 273.063 112.207C275.599 112.099 274.621 111.698 276.164 111.292C277.716 110.884 277.09 112.205 277.347 112.445C277.601 112.685 277.814 112.384 278.109 112.498C278.403 112.612 277.823 112.861 278.482 112.841C279.141 112.821 280.88 112.139 281.081 111.834C281.504 111.188 280.62 110.668 282.387 110.677C282.489 110.678 282.202 111.042 282.59 111.028C285.663 110.915 288.908 110.528 291.999 110.355C292.404 110.334 292.092 110.741 292.328 110.733C294.789 110.649 293.842 110.153 295.09 109.303C294.908 110.24 295.717 110.695 297.583 110.451C298.706 110.304 298.46 109.938 299.15 109.563C299.357 109.45 300.19 109.611 300.31 109.407C300.705 108.739 299.859 108.168 301.833 108.171C301.92 108.172 301.695 108.539 301.991 108.518C303.913 108.386 304.054 107.724 306.114 107.355C307.729 107.066 310.694 107.03 312.582 106.815C312.691 107.829 314.434 107.336 315.054 106.532C316.283 106.378 317.605 106.138 318.813 106.052C319.229 106.022 319.004 106.355 319.06 106.378C319.931 106.751 320.366 105.993 320.629 105.925C323.342 105.243 325.624 105.409 328.179 104.487C328.269 104.454 328.308 103.731 329.093 104.128C329.145 104.156 328.326 104.614 329.569 104.439C330.812 104.264 333.222 104.256 334.911 103.643C335.319 103.496 336.291 102.752 337.224 103.051C337.348 103.092 337.257 103.313 337.441 103.397C338.035 103.662 338.208 102.908 338.939 103.401C339.67 103.894 341.058 104.121 342.771 103.402C342.826 103.38 343.152 102.749 343.715 102.661C344.614 102.521 345.911 102.416 346.853 102.248C347.304 102.167 346.955 101.871 347.032 101.851C350.021 101.128 352.322 101.038 355.378 100.624C355.949 100.547 357.496 99.7784 358.67 99.949C359.96 100.135 359.019 102.066 361.747 101.143C362.85 100.772 363.881 98.3504 366.139 99.0813C370.062 98.056 373.887 97.9129 377.88 96.7203C378.06 96.6669 378.521 95.9528 378.953 95.876C379.596 95.7632 380.003 95.9743 380.465 95.9021C382.391 95.6088 384.157 95.0064 385.963 94.6814C386.632 94.5607 386.458 94.8977 387.257 94.753C388.056 94.6079 388.509 94.1187 389.629 94.0826C390.475 94.0565 391.454 94.6689 392.564 94.5357C392.447 93.758 393.199 93.6205 394.492 93.1415C394.741 93.049 394.41 92.8384 394.525 92.7808C394.991 92.5406 395.495 92.6675 395.934 92.4196C396.041 92.3598 395.992 92.0962 396.076 92.0325C396.791 91.4808 398.229 91.7966 398.477 91.8518C399.425 92.0607 399.76 91.6342 400.459 91.6476C401.157 91.6611 400.202 92.0628 400.999 91.9922C402.653 91.8454 404.727 91.3609 406.383 91.019C407.044 90.881 406.519 90.6274 406.612 90.5995C407.957 90.2188 409.423 90.2921 410.034 89.4307C411.376 91.0262 410.822 89.0779 412.869 89.0102C413.031 89.0055 413.414 89.8143 412.845 90.0691C412.276 90.3237 411.316 90.2534 410.534 90.6265C410.349 90.7146 409.405 91.754 409.263 91.835C408.152 92.4697 407.711 91.2317 407.633 91.1754C406.758 90.5737 405.587 91.7238 405.243 91.8591C403.67 92.4792 402.252 92.592 400.938 93.0718C400.079 93.3839 400.168 94.2371 399.468 94.3571C397.144 94.7553 396.102 93.7561 394.154 95.2122C393.737 95.5229 394.383 96.3425 392.582 95.9639C392.469 95.9401 391.109 95.534 390.489 96.0935C390.411 96.164 390.468 96.4297 390.355 96.4759C389.602 96.7803 389.037 96.6742 388.207 96.9481C387.364 97.2247 386.432 97.5705 385.363 97.691C384.731 97.7615 383.838 97.4238 383.518 97.3612C382.612 97.1778 381.833 97.9296 381.233 97.6076C380.629 97.2841 381.516 96.4358 380.1 96.4295C380.186 97.1322 380.211 97.8661 380.267 98.5788C377.279 99.1435 374.322 99.486 371.381 100.081C371.016 100.156 371.461 100.457 371.073 100.546C369.432 100.922 367.945 100.928 366.267 101.354C364.968 101.684 363.605 102.348 362.259 102.7C361.278 102.06 359.309 102.702 357.837 102.895C357.322 102.963 357.317 102.744 356.942 102.741C355.527 102.733 356.296 103.319 355.941 103.497C354.41 104.261 354.03 102.687 352.091 103.074C351.985 103.095 350.837 104.174 350.668 104.258C349.595 104.803 350.428 103.996 349.707 104.058C348.394 104.172 347.462 104.126 345.997 104.637C345.57 104.787 344.608 105.499 343.688 105.235C343.567 105.201 343.581 104.91 343.488 104.909C341.814 104.914 341.923 105.802 341.047 106.259C340.17 106.715 338.642 106.346 338.323 106.219C337.49 105.891 338.871 105.927 338.905 105.875C339.299 105.264 338.275 105.53 337.476 105.618C337.216 104.772 336.12 105.008 336.649 105.712C336.708 105.788 337.31 105.605 337.476 105.618C336.923 106.583 335.724 106.894 334.259 106.321C333.573 106.051 334.09 105.522 332.224 105.99C331.378 106.201 331.807 106.743 331.077 107.029C329.41 107.68 327.09 107.48 325.346 107.845C324.712 107.977 325.385 108.206 324.955 108.308C323.811 108.578 322.756 108.491 321.621 108.795C321.518 108.823 321.827 109.126 321.469 109.186C318.572 109.673 316.024 109.834 313.04 110.267C311.596 110.477 309.061 111.038 307.581 111.209C305.012 111.511 301.827 111.444 299.698 111.855C298.308 112.122 298.662 112.423 298.095 112.737C296.999 113.345 294.86 112.801 294.572 112.71C294.562 112.707 295.745 112.47 295.085 112.377C294.667 112.058 294.585 112.707 294.572 112.71C294.37 112.654 292.03 112.878 291.507 113.011C291.239 113.082 291.433 113.388 291.292 113.418C287.945 114.141 284.602 114.635 281.23 114.878C281.17 114.878 281.216 114.503 281.076 114.508C279.118 114.574 277.936 115.161 276.382 115.605C274.829 116.048 273.571 116.096 272.575 115.571C271.399 115.779 269.681 115.682 268.697 115.615C267.713 115.548 267.653 116.272 267.569 116.295C265.002 117.014 263.341 116.77 260.429 116.985C259.048 117.087 256.346 117.441 254.886 117.649C253.731 117.815 253.02 117.709 251.967 117.907C251.825 117.934 251.997 118.238 251.954 118.254C250.802 118.735 251.306 117.911 251.05 117.794C250.123 117.366 249.555 117.144 248.073 117.503C247.731 117.586 246.813 118.783 246.19 118.897C245.38 119.044 245.656 118.62 244.811 118.783C244.739 118.797 244.772 119.043 244.246 119.072C241.761 119.215 239.301 119.249 236.531 119.459C236.167 119.486 236.66 119.847 236.135 119.892C233.67 120.102 230.994 120.252 228.632 120.201C228.05 120.187 228.398 119.892 228.267 119.818C227.469 119.368 226.421 120.143 226.243 120.166C221.985 120.736 218.745 120.233 214.535 120.698C214.288 120.726 212.421 121.562 211.561 120.928C211.502 120.886 210.652 120.238 209.936 120.721C209.891 120.751 210.416 121.084 209.517 121.075C208.619 121.066 207.187 121.336 205.959 120.983C205.569 120.872 204.644 120.22 203.514 120.779C203.44 120.818 201.701 122.064 201.064 121.236C200.961 121.1 201.051 120.213 199.711 120.686C199.455 120.778 199.924 120.969 199.249 121.102C198.214 121.305 196.845 121.082 195.588 121.485C195.369 121.556 194.437 122.163 193.69 121.709C193.573 121.638 193.793 121.342 193.4 121.31C191.587 121.157 189.359 121.351 187.388 121.13C186.68 121.053 187.046 120.578 186.311 120.443C185.574 120.308 184.102 120.167 183.337 120.373C182.572 120.58 183.392 121.356 182.25 121.398C177.198 121.574 172.827 120.996 167.568 120.69C166.82 120.646 167.426 120.948 166.326 120.9C162.251 120.734 155.444 120.54 152.194 119.684C149.4 118.987 153.006 118.932 150.845 118.562C149.804 118.384 148.6 119.165 148.346 119.17C145.867 119.229 144.226 118.671 142.065 118.562C141.619 118.54 142.016 118.944 141.636 118.92C140.341 118.843 139.018 118.532 137.806 118.418C137.262 118.367 137.329 118.651 136.834 118.57C135.632 118.377 134.683 118.141 133.459 117.722C133.35 117.684 134.151 117.445 133.218 117.31C131.71 117.092 129.894 116.584 128.163 116.594C127.651 116.598 127.536 117.216 126.527 116.625C126.316 116.501 126.714 115.802 125.373 116.252C125.147 116.328 122.929 116.864 122.017 116.137C121.775 115.946 121.202 115.225 120.606 115.22C120.525 115.222 120.783 115.608 120.296 115.558C118.61 115.379 116.775 115.01 114.906 114.76C114.412 114.694 115.074 115.176 114.34 115.067C112.321 114.768 109.886 114.178 108.026 113.66C107.341 113.469 107.882 113.292 107.838 113.25C107.138 112.574 106.515 113.141 106.239 113.12C104.503 112.975 103.432 112.392 101.775 112.078C101.326 111.992 101.563 112.388 101.468 112.395C100.483 112.449 101.306 112.043 100.744 111.837C99.1725 111.258 97.3965 110.825 95.8052 110.438C95.2209 110.296 95.773 110.841 95.2497 110.709C92.1136 109.921 89.1933 109.024 86.0941 107.814C85.534 107.592 85.9839 107.428 85.8853 107.326C85.2095 106.617 83.8265 106.833 83.5349 106.799C82.2113 106.649 81.8202 106.239 80.7342 105.911C75.634 104.405 68.8726 101.484 64.3194 99.208C57.9718 96.0621 53.5051 93.2985 47.8872 89.8247C47.5379 89.606 47.677 90.1592 47.373 89.9743C43.4008 87.5306 39.714 84.6833 35.9926 81.7724C35.6826 81.5283 35.8862 82.1763 35.3806 81.7926C34.3421 81.0074 33.1208 79.9296 32.2469 78.9873C31.8207 78.5286 32.6544 78.85 32.223 78.3939C31.2283 77.3374 30.1105 76.2537 28.9568 75.2034C28.4722 74.7625 28.91 75.7265 28.3007 75.1306C24.2171 71.1318 20.4557 66.8384 17.4976 61.8256C17.2929 61.4779 17.7202 60.7511 17.1242 60.1436C16.9122 60.2121 16.7067 60.3567 16.5026 60.4626C11.6911 53.6416 8.17502 46.0919 5.43067 38.1885C5.31656 37.859 5.13147 38.5637 4.90905 37.9249C3.60978 34.1648 2.46313 30.5421 1.96374 26.4654C1.9169 26.0679 2.65656 26.1103 2.30932 24.9987C2.24347 24.7979 1.81168 23.1334 1.57299 22.683C1.53842 22.6252 1.269 23.0096 1.15372 22.4413C0.495852 19.1586 0.102446 15.708 -0.00274086 12.4221C-0.0182281 11.8977 0.388724 12.4189 0.381227 12.0463C0.320655 9.3481 -0.113554 6.51317 0.138506 3.90376C0.313267 2.17547 0.946675 2.80183 1.53823 1.898C1.62108 1.76929 1.62563 0.744123 1.97931 0.692115C2.86627 0.554571 3.84105 1.03566 4.78608 0.830405C4.98828 0.784546 4.76727 -0.533703 5.26061 0.246078C5.2929 0.299231 5.46139 1.26021 5.6321 1.49881C5.74951 1.66376 6.10499 1.36047 6.25689 1.6973C6.40897 2.03391 6.11345 2.41275 6.29974 2.98038C6.48974 3.54908 6.88847 3.28258 6.97333 4.43209ZM264.288 115.308C263.305 115.061 263.289 116.325 264.286 115.923C264.335 115.902 264.339 115.319 264.288 115.308ZM374.166 98.5482C372.868 98.2351 372.209 99.1912 373.13 99.4497C374.051 99.7074 374.562 98.6425 374.166 98.5482ZM386.544 95.6895C385.122 95.5303 385.258 97.3199 386.622 96.6777C386.702 96.641 386.627 95.6986 386.544 95.6895ZM1.83251 6.06725C1.84809 6.05323 1.83358 5.32223 1.81691 5.30622C1.40195 4.93191 1.43094 6.39768 1.83251 6.06725ZM398.352 92.8145C397.876 92.6679 396.709 93.1952 397.298 93.3785C397.773 93.526 398.944 92.9969 398.352 92.8145ZM216.226 117.383C216.211 117.365 215.694 117.368 215.709 117.474C215.757 117.813 216.472 117.689 216.226 117.383ZM419.121 88.3234C415.987 88.9474 413.796 87.9198 417.213 87.3391C417.833 87.2334 415.992 88.0883 416.801 88.0461C418.282 87.9676 419.033 86.8553 420.606 87.0131C420.891 87.0428 419.3 88.2913 419.116 88.3266C419.117 88.3255 419.119 88.3245 419.121 88.3234ZM263.592 113.861C263.572 113.846 262.811 113.906 262.795 113.924C262.423 114.361 263.955 114.242 263.592 113.861ZM184.997 117.063C184.955 117.027 183.229 116.971 183.174 117.002C182.262 117.641 185.755 117.759 184.997 117.063ZM312.616 106.838C313.366 106.322 314.328 106.131 315.05 106.533C314.239 106.627 313.427 106.743 312.616 106.838Z"
                                      fill="#E30613"/>
                            </svg>
                        </div>
                        Канал на портале
                    </a>

                    <a href="https://max.ru/join/M479Fi38oAW_hI5hkZyrLH6DFlXpCnLnHq30LzQlJSE"
                       class="card card-handwritten rel">
                        Канал в MAX
                        <div style="left: 59%" class="abs">
                            <svg width="125" height="36" viewBox="0 0 254 72" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M248.084 70.4893C246.987 69.4307 245.8 68.0006 244.865 66.8193C244.573 66.4505 244.898 66.4635 244.701 66.2008C244.312 65.6838 243.813 65.3416 243.46 64.8498C243.341 64.6837 243.767 64.6078 243.637 64.439C242.916 63.5143 242.041 62.8014 241.193 61.9076C237.129 57.6182 232.703 53.78 227.939 50.0055C226.419 48.798 224.596 47.0838 222.832 46.0254C222.628 45.9036 222.567 46.2383 222.515 46.2335C221.712 46.1506 222.262 45.7716 221.927 45.4394C220.608 44.1298 218.83 42.9076 217.295 42.0559C216.634 41.6897 216.935 42.6166 216.484 41.915C216.452 41.8667 216.791 41.6941 216.636 41.5524C216.283 41.2313 215.494 40.7986 215.195 40.5801C214.622 40.1573 214.519 40.0974 213.947 39.7367C213.161 39.2391 212.309 38.5266 211.442 38.0923C211.255 37.9996 211.318 38.4302 211.004 38.3087C210.691 38.187 209.948 37.7121 209.731 37.4922C209.514 37.2722 209.758 37.1085 209.743 37.0294C209.628 36.366 208.377 36.459 208.254 36.4084C205.972 35.4347 204.445 34.123 202.43 33.0378C200.899 32.2153 199.221 31.4892 197.785 30.7392C196.076 29.8449 194.635 28.9317 192.93 28.1507C192.751 28.0684 192.789 28.5254 192.57 28.4257C190.251 27.3708 187.889 26.2455 185.649 24.9777C185.425 24.8506 185.866 24.6635 185.651 24.5173C184.334 23.6289 182.269 22.02 180.815 23.0649C179.833 22.5986 178.654 22.0611 177.883 21.4312C177.383 21.0243 178.077 20.8885 177.314 20.7645C177.141 20.7363 177.228 21.1259 176.979 21.0604C175.932 20.7844 174.864 20.2638 173.795 20.0499C173.577 20.0072 173.53 20.3068 173.453 20.3355C172.816 20.5614 172.245 19.5904 172.083 19.4765C170.871 18.6246 169.849 18.4265 168.592 17.9828C168.301 17.88 168.516 18.3765 168.231 18.2913C166.756 17.8453 165.24 17.3059 163.839 16.6701C163.535 16.5301 163.93 16.2995 163.79 16.216C163.169 15.852 162.826 16.4176 162.225 16.0833C162.097 16.0137 162.261 15.7596 162.245 15.7213C162.006 15.1204 161.456 15.6952 161.352 15.6794C160.022 15.4944 159.42 14.5379 158.146 14.2736C157.993 14.2429 158.225 14.6946 157.803 14.5868C154.953 13.8616 152.13 12.9905 149.396 11.9975C148.973 11.8443 149.351 11.6537 149.332 11.6138C149.015 10.9844 148.553 11.606 148.405 11.5971C147.227 11.5051 146.355 10.6812 145.405 11.3328C140.768 9.94337 135.97 9.45745 131.352 7.88813C131.292 7.86897 131.461 7.54092 131.343 7.50419C129.918 7.06078 130.089 7.64579 129.037 8.1505C128.772 8.27728 128.173 7.36354 128.117 7.29497C127.916 7.04228 128.242 6.56381 128.17 6.50961C127.567 6.07895 127.406 7.8056 126.527 7.67152C126.5 7.66447 126.663 7.36706 126.594 7.32447C126.379 7.19109 126.132 7.28186 125.922 7.13967C125.444 6.81185 125.38 5.95154 124.515 6.32194C124.406 6.37067 124.42 7.36386 123.634 7.20628C123.321 7.14184 121.913 6.96747 121.525 6.85351C121.138 6.73878 121.451 6.46046 121.424 6.44318C120.411 5.88986 120.837 5.77045 120.377 5.08761C119.927 4.40925 119.891 5.88854 119.658 6.02515C119.424 6.16109 118.696 5.72546 118.52 5.58406C118.345 5.44239 118.605 5.27064 118.444 5.18323C117.375 4.61131 117.868 5.85175 117.473 6.0413C116.544 6.49197 115.985 5.33282 115.193 5.14195C114.978 5.0905 115.13 5.3563 114.934 5.35931C114.159 5.36849 113.731 4.98301 112.824 5.20993C112.676 5.24669 111.907 5.83373 111.491 5.31287C111.432 5.23785 111.642 4.98347 111.318 4.89323C110.826 4.75722 109.965 4.62383 109.437 4.67228C109.047 4.70816 109.451 5.19054 108.979 5.01286C108.769 4.93375 109.189 4.70403 108.792 4.57727C108.398 4.45031 107.366 4.38401 106.971 4.36533C106.578 4.34649 106.769 4.70108 106.736 4.72165C106.534 4.83861 105.77 4.95054 105.703 4.94594C102.33 4.7626 99.2449 4.04393 95.9698 4.13864C95.7119 4.1457 95.8733 4.50904 95.8338 4.51381C94.9487 4.63991 95.6699 3.70553 95.0599 3.37048C95.1308 4.10029 94.7699 3.95877 94.1442 4.02498C93.8426 4.05759 94.0005 4.34281 93.9725 4.37106C93.5185 4.78959 93.2186 4.2315 93.1402 4.11698C92.8513 3.69578 92.5206 4.18214 92.323 3.90121C92.1248 3.6224 92.5965 3.21983 91.9932 3.2167C91.8209 3.47494 92.2585 4.16146 91.8005 4.26583C90.0559 4.6564 86.943 4.125 85.5545 4.08944C84.1835 4.05046 83.7391 4.1083 82.9344 4.03041C81.478 3.89297 82.0219 4.38602 81.1261 4.64112C80.2271 4.8983 80.63 3.64369 80.4914 3.38044C80.355 3.11743 80.2215 3.39656 80.0589 3.25489C79.8985 3.1132 80.23 2.9208 79.8617 2.87755C79.4929 2.83394 78.5051 3.34505 78.3859 3.62957C78.1336 4.23171 78.6096 4.83388 77.6319 4.65492C77.5755 4.64428 77.7433 4.31032 77.5281 4.28616C75.8185 4.10522 74.0405 4.18051 72.3077 4.06327C72.0693 4.04667 72.2587 3.67091 72.1195 3.65718C70.6676 3.51533 71.2192 4.09519 70.4782 4.82676C70.5967 3.91097 70.1119 3.3862 69.03 3.45915C68.3861 3.50281 68.526 3.88951 68.1313 4.19908C68.013 4.29232 67.5365 4.05676 67.4684 4.2489C67.2439 4.87718 67.7269 5.52075 66.6032 5.33999C66.5537 5.33112 66.6806 4.98655 66.5116 4.98022C65.4174 4.93903 65.342 5.58397 64.1769 5.76642C63.264 5.90932 61.5848 5.6827 60.5202 5.73149C60.4376 4.71555 59.4583 5.05355 59.1286 5.79676C58.4279 5.8445 57.7223 5.9645 57.0578 5.94449C56.8302 5.93783 56.9428 5.62767 56.9109 5.59933C56.4189 5.15371 56.2057 5.86767 56.0638 5.91173C54.5191 6.36064 53.1607 6.01339 51.7103 6.72143C51.6588 6.74691 51.668 7.4596 51.192 7.00374C51.1607 6.97215 51.6187 6.58368 50.9003 6.65808C50.1826 6.73214 48.7745 6.55089 47.8227 7.02609C47.5893 7.14085 47.0739 7.79997 46.5296 7.43025C46.4566 7.3799 46.4952 7.16853 46.3858 7.07121C46.0324 6.76197 45.9805 7.49398 45.5353 6.94914C45.09 6.40444 44.2836 6.07157 43.3594 6.64939C43.3297 6.66667 43.1878 7.26469 42.8749 7.30802C42.3751 7.37662 41.6476 7.38085 41.1267 7.47522C40.8773 7.52058 41.0975 7.83959 41.0553 7.85347C39.4123 8.34093 38.1598 8.2494 36.3208 8.46034C35.9754 8.4998 35.1385 9.15017 34.4383 8.90196C33.6676 8.63168 34.0425 6.79279 32.5269 7.51836C31.9126 7.81122 31.5471 10.124 30.1566 9.25749C27.9742 10.0085 25.7411 9.91214 23.5759 10.8337C23.4803 10.8745 23.3066 11.5441 23.0711 11.5915C22.7211 11.6607 22.4639 11.4282 22.2112 11.4695C21.1503 11.6358 20.254 12.1078 19.2374 12.3232C18.8514 12.4058 18.9132 12.0853 18.4519 12.1847C17.9905 12.2848 17.7823 12.7396 17.1153 12.7171C16.6113 12.6991 15.9412 12.0508 15.2916 12.1257C15.4674 12.8914 15.0349 12.9879 14.3255 13.392C14.1893 13.47 14.4171 13.692 14.3563 13.7425C14.1108 13.954 13.7909 13.8056 13.5627 14.0261C13.5071 14.0793 13.5741 14.3389 13.5327 14.397C13.184 14.9002 12.2765 14.5237 12.1221 14.4575C11.5376 14.2049 11.4066 14.6033 10.9971 14.5545C10.5876 14.5059 11.0842 14.1632 10.6292 14.1915C9.68577 14.2507 8.55007 14.6193 7.63786 14.8714C7.27427 14.9736 7.6216 15.2454 7.57189 15.268C6.84862 15.5726 5.98073 15.4321 5.7673 16.2393C4.72009 14.6281 5.3657 16.5444 4.18516 16.5152C4.09186 16.5123 3.73093 15.7107 4.02027 15.4896C4.30983 15.2685 4.88245 15.381 5.2772 15.0554C5.37059 14.9786 5.75203 14.0136 5.82211 13.9416C6.36953 13.3772 6.82808 14.6008 6.88255 14.6593C7.49067 15.2857 7.99016 14.2252 8.17048 14.1102C8.99258 13.5839 9.80494 13.5445 10.5006 13.1436C10.956 12.8831 10.7759 12.0477 11.1686 11.9661C12.4946 11.6904 13.2735 12.7128 14.2412 11.3865C14.4496 11.1036 13.948 10.2708 15.0863 10.7293C15.1583 10.758 16.0321 11.2227 16.3318 10.7072C16.3695 10.6422 16.2998 10.3794 16.3615 10.3399C16.7765 10.0803 17.1313 10.2129 17.5969 9.98753C18.0625 9.76224 18.6154 9.46377 19.1973 9.41428C19.5481 9.38389 20.1011 9.76931 20.2913 9.85039C20.83 10.086 21.1781 9.39916 21.5604 9.75194C21.9446 10.1066 21.548 10.8806 22.3522 10.9765C22.2152 10.2832 22.1097 9.56326 21.989 8.86198C23.5996 8.50279 25.3316 8.33776 26.9835 7.93966C27.1893 7.8894 26.8957 7.56558 27.1144 7.50217C28.0375 7.23658 28.9101 7.32463 29.8532 7.01481C30.5829 6.77365 31.3202 6.21008 32.0803 5.95221C32.7178 6.64668 33.816 6.14671 34.6661 6.05692C34.9637 6.02501 34.9851 6.24074 35.2063 6.26968C36.0568 6.37115 35.5478 5.74212 35.7473 5.58948C36.6075 4.93797 36.9878 6.51051 38.0907 6.26676C38.1488 6.25427 38.7145 5.27576 38.8047 5.20525C39.3732 4.74767 38.9622 5.48148 39.3673 5.47364C40.1067 5.46007 40.6398 5.57528 41.4373 5.18245C41.6693 5.06679 42.1682 4.43738 42.7118 4.76798C42.7833 4.81121 42.7955 5.09785 42.8484 5.10594C43.8022 5.22965 43.6807 4.34372 44.1525 3.9603C44.6248 3.57697 45.5231 4.0609 45.7124 4.21173C46.2086 4.60047 45.417 4.45698 45.4008 4.50548C45.2139 5.07935 45.7818 4.89592 46.2327 4.87236C46.4312 5.72921 47.0415 5.58248 46.6993 4.84472C46.6609 4.76424 46.328 4.89813 46.2327 4.87236C46.4864 3.96215 47.17 3.74594 48.0627 4.42703C48.4791 4.74694 48.2023 5.23062 49.2733 4.91299C49.7592 4.76993 49.4819 4.19937 49.8976 3.97407C50.8486 3.45974 52.224 3.84196 53.2376 3.62163C53.6059 3.54157 53.2007 3.26045 53.4512 3.19448C54.1162 3.01861 54.742 3.19037 55.4028 2.98194C55.4627 2.96265 55.271 2.63724 55.4811 2.60731C57.0714 2.37475 58.4797 2.43813 60.1871 2.26564C61.0066 2.18212 62.4484 1.84787 63.2954 1.8085C64.7652 1.73561 66.594 2.08737 67.8186 1.87161C68.6182 1.73227 68.4155 1.40087 68.7437 1.14005C69.3719 0.634804 70.6533 1.36892 70.8184 1.4857C70.8236 1.48937 70.121 1.61823 70.5107 1.77022C70.7547 2.12502 70.8104 1.48785 70.8184 1.4857C70.9309 1.55953 72.3466 1.54915 72.6216 1.46762C72.7713 1.42255 72.6672 1.10012 72.7457 1.08283C74.6131 0.679358 76.491 0.505246 78.3864 0.587082C78.4198 0.59245 78.3836 0.960967 78.4622 0.969019C79.5583 1.09151 80.2413 0.621021 81.1602 0.328805C82.0732 0.0372937 82.8051 0.110935 83.3584 0.730172C84.0471 0.637153 85.0383 0.901187 85.6053 1.06431C86.1723 1.22727 86.2447 0.512937 86.2945 0.498299C87.8444 0.0347698 88.7421 0.442568 90.3373 0.515889C91.1021 0.551564 92.6501 0.467422 93.4853 0.406162C94.1476 0.355907 94.5412 0.532142 95.1504 0.440244C95.2328 0.428029 95.1579 0.10804 95.1833 0.0964346C95.871 -0.266357 95.5246 0.502437 95.6605 0.644892C96.1523 1.16362 96.4545 1.44077 97.3223 1.23188C97.5219 1.18402 98.1398 0.0856907 98.5052 0.0342216C98.9791 -0.0306456 98.7859 0.363231 99.2816 0.286006C99.3239 0.279817 99.3254 0.0320094 99.6285 0.0556786C101.017 0.161494 102.601 0.385496 104.079 0.4489C104.281 0.458566 104.045 0.0510805 104.338 0.0587206C105.715 0.0949755 107.213 0.212657 108.522 0.497739C108.844 0.569162 108.618 0.827505 108.683 0.914332C109.077 1.44004 109.746 0.774409 109.849 0.769237C112.29 0.625332 114.144 1.45378 116.637 1.41275C116.773 1.40882 117.895 0.75947 118.279 1.46868C118.306 1.51594 118.682 2.23887 119.137 1.82839C119.165 1.80274 118.923 1.42383 119.413 1.51802C119.905 1.6126 120.711 1.47785 121.363 1.9464C121.566 2.09377 121.994 2.82749 122.712 2.38207C122.758 2.35 123.925 1.28337 124.167 2.16308C124.206 2.30785 124.023 3.17607 124.855 2.83526C125.014 2.76905 124.775 2.53519 125.18 2.46848C125.801 2.36533 126.549 2.71537 127.333 2.4356C127.47 2.38562 128.101 1.87383 128.461 2.39254C128.518 2.47375 128.344 2.74588 128.566 2.81428C129.617 3.14051 130.781 3.131 131.863 3.5241C132.244 3.6625 131.962 4.09915 132.348 4.29615C132.735 4.49304 133.532 4.75942 133.995 4.62141C134.459 4.48333 134.13 3.64878 134.779 3.70524C137.599 3.95314 140.22 4.93516 142.976 5.60458C143.382 5.70362 143.103 5.36267 143.705 5.49039C145.925 5.95393 149.764 6.61357 151.55 7.67717C152.96 8.52068 150.953 8.36674 152.069 8.85079C152.606 9.08333 153.436 8.38686 153.579 8.39603C154.971 8.47378 155.778 9.10268 156.981 9.31565C157.23 9.35991 157.088 8.94658 157.303 8.98931C158.041 9.13312 158.751 9.50426 159.442 9.6758C159.752 9.75207 159.773 9.47234 160.049 9.57562C160.709 9.81994 161.269 10.1082 161.844 10.5518C161.897 10.5922 161.399 10.7974 161.888 10.9594C162.679 11.2209 163.587 11.768 164.571 11.806C164.864 11.8165 165.064 11.2219 165.511 11.8201C165.608 11.9463 165.219 12.6112 166.105 12.2147C166.253 12.1484 167.68 11.6923 168.064 12.4173C168.165 12.608 168.346 13.3183 168.702 13.3363C168.751 13.3366 168.682 12.9578 168.962 13.0179C169.948 13.2354 170.926 13.6114 171.925 13.8519C172.192 13.9156 171.924 13.4515 172.319 13.5572C173.394 13.8438 174.707 14.4143 175.71 14.9038C176.079 15.0848 175.714 15.2567 175.731 15.2965C176 15.9419 176.505 15.3952 176.668 15.4135C177.675 15.5356 178.257 16.1058 179.127 16.352C179.367 16.421 179.322 16.0506 179.379 16.0413C179.961 15.9611 179.39 16.3721 179.668 16.5509C180.448 17.0545 181.382 17.3983 182.274 17.715C182.598 17.8294 182.384 17.3309 182.673 17.4379C184.388 18.0648 186.108 18.8288 187.625 19.7176C187.911 19.8856 187.604 20.0723 187.64 20.1611C187.886 20.7795 188.766 20.468 188.944 20.4803C189.734 20.5256 189.891 20.8801 190.503 21.1027C193.502 22.1851 196.907 24.0033 199.539 25.4532C202.893 27.3006 205.492 29.0076 208.692 30.725C208.895 30.835 208.884 30.3801 209.062 30.4708C211.418 31.6944 213.555 33.1306 215.831 34.3936C216.022 34.5008 215.947 34.02 216.26 34.1818C216.904 34.5114 217.665 35.0088 218.211 35.4928C218.478 35.7282 217.91 35.7459 218.182 35.9772C218.812 36.5161 219.533 37.0086 220.263 37.411C220.573 37.5817 220.332 36.9733 220.72 37.2203C223.43 38.939 225.993 40.6085 228.36 42.9115C228.52 43.0684 228.228 43.8059 228.692 43.9605C228.846 43.8083 228.992 43.6038 229.139 43.4281C232.895 46.0017 236.399 49.0702 239.919 52.1071C240.065 52.2336 240.074 51.706 240.359 51.9496C241.98 53.3345 243.759 54.7537 245.081 56.5252C245.217 56.7048 244.635 57.1429 245.205 57.5242C245.306 57.5908 246.106 58.2099 246.399 58.2746C246.44 58.2817 246.538 57.9131 246.779 58.1281C248.166 59.3772 249.475 60.7782 250.645 62.2814C250.829 62.5203 250.351 62.5388 250.479 62.7107C251.374 63.9095 252.702 64.9395 253.433 66.2469C253.91 67.1116 253.211 67.2532 253.085 68.0294C253.068 68.139 253.435 68.583 253.186 68.8355C252.563 69.4717 251.649 69.9007 251.006 70.6039C250.868 70.7554 251.514 71.1718 250.857 71.1614C250.812 71.1599 250.339 70.8609 250.125 70.8704C249.977 70.8767 249.815 71.2377 249.58 71.1933C249.346 71.1492 249.436 70.7947 249.096 70.6732C248.754 70.5532 248.544 70.9293 248.084 70.4893ZM88.1458 1.80387C88.6647 2.14641 88.742 0.890254 88.1793 1.19115C88.1521 1.20721 88.1191 1.78708 88.1458 1.80387ZM25.5215 9.27119C26.3153 9.65889 26.5954 8.76173 26.0275 8.45065C25.4584 8.14066 25.279 9.15421 25.5215 9.27119ZM18.7607 11.3058C19.6363 11.5368 19.3281 9.77784 18.5875 10.3344C18.5442 10.3662 18.7094 11.2926 18.7607 11.3058ZM251.389 66.3904C251.382 66.4073 251.66 66.7373 251.679 66.7336C252.129 66.6363 251.571 65.9744 251.389 66.3904ZM12.0556 13.525C12.3622 13.6904 12.9873 13.2318 12.6071 13.0249C12.3006 12.8583 11.6734 13.3193 12.0556 13.525ZM115.235 4.52983C115.241 4.54926 115.539 4.59764 115.544 4.49181C115.56 4.15067 115.132 4.20241 115.235 4.52983ZM0.657944 16.9016C2.37867 16.4332 3.83061 17.529 1.94376 17.9378C1.60133 18.0127 2.5233 17.2668 2.05961 17.2717C1.20978 17.2822 0.970242 18.3255 0.0265588 18.105C-0.145188 18.0641 0.558416 16.925 0.660356 16.8987C0.659572 16.8997 0.658727 16.9007 0.657944 16.9016ZM88.4451 3.31248C88.4555 3.32901 88.8692 3.34501 88.8791 3.32863C89.104 2.92998 88.2692 2.89745 88.4451 3.31248ZM132.513 7.74228C132.53 7.78076 133.478 7.98409 133.514 7.95803C134.129 7.40571 132.209 6.99144 132.513 7.74228ZM60.5008 5.7062C60.0875 6.15268 59.5495 6.25796 59.1308 5.79705C59.5872 5.77399 60.0435 5.72915 60.5008 5.7062Z"
                                      fill="#E30613"/>
                            </svg>
                        </div>
                        <span class="hand hand-right">анонсы</span>
                    </a>

                    <a href="/" class="card">Наш сайт</a>
                    <!-- <a target="_blank" href="https://corp-portal.welcome.moscow/online/?IM_DIALOG=chat21130"
                        class="card">Канал на портале</a>-->
                </div>
            </section>

            <section class="section section-portal">
                <h2 class="title title-red">Возможности<br> корпоративного портала</h2>

                <div class="cards">
                    <a href="https://corp-portal.welcome.moscow/hr/" class="card">Сориентироваться в оргструктуре</a>
                    <a href="https://corp-portal.welcome.moscow/company/" class="card">Найти нужную информацию о
                        коллегах</a>

                    <a href="/company/personal/user/<?= $curUser ?>/" class="card">Ставить задачи и работать в
                        команде</a>
                    <a href="https://corp-portal.welcome.moscow/news/" class="card">Узнавать новости</a>

                    <a href="https://corp-portal.welcome.moscow/calendar/" class="card">Вовремя узнавать о мероприятиях
                        в календаре</a>
                    <a href="https://corp-portal.welcome.moscow/lk/service/#/" class="card">Использовать HR сервисы</a>

                    <a href="https://corp-portal.welcome.moscow/services/idea/" class="card">Пополнять банк идей</a>
                    <a href="https://corp-portal.welcome.moscow/shop-bonus/" class="card">Делать покупки в Магазине
                        бонусов</a>
                </div>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>