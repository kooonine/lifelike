<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('./_common.php');
include_once('./_head.php');
?>
<style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500&display=swap');

    @keyframes door2door {
        0% {
            margin-left: 400px;
            opacity: 0;
        }

        100% {
            margin-left: 0;
            opacity: 1;
        }
    }

    .section-listener {
        font-family: 'Montserrat', 'Noto Sans CJK KR', 'Malgun Gothic', '맑은 고딕', Tahoma, sans-serif !important;
        transition: all ease-in-out .5s;
        background: #ffffff center center no-repeat padding-box;
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
        min-height: 500px;
        width: 100%;
        text-align: center;
        overflow: hidden;
    }

    .section-listener div {
        font-family: 'Montserrat', 'Noto Sans CJK KR', 'Malgun Gothic', '맑은 고딕', Tahoma, sans-serif !important;
        font-size: 16px;
        font-weight: bold;
        color: #535353;
        line-height: 50px;
        box-sizing: border-box;
    }

    .section-listener strong {
        font-weight: bold;
    }

    .section-listener.section-1 {
        background-image: url(/img/listener/mobile/192204647.jpg);
        font-weight: bold;
        background-size: 350%;
        background-position-y: top;
        background-position-x: -560px;
    }

    .section-listener.section-1>div>div {
        transition: all ease-in-out .5s;
        padding-top: 50px;
        opacity: 0;
    }

    .section-listener.section-1.load>div>div {
        padding-top: 0;
        opacity: 1;
    }

    .section-listener>.section-image {
        height: 420px;
        background-size: cover;
        background-image: url(/img/listener/mobile/762@3x.png);
        background-position: center;
        background-repeat: no-repeat;
    }

    .section-listener.section-3>.section-image {
        background-image: url(/img/listener/mobile/759@3x.jpg);
        background-position: -170px -50px;
        background-size: 150%;
    }

    .section-listener.section-4>.section-image {
        background-image: url(/img/listener/mobile/762@3x.jpg);
    }

    .section-listener.section-5>.section-image {
        background-image: url(/img/listener/mobile/763@3x.jpg);
    }

    .section-listener.section-6>.section-image {
        background-image: url(/img/listener/mobile/box_mint_logo@3x.jpg);
    }

    .section-listener.section-7 {
        background: #F5F5F5;
    }

    .section-listener.section-7>#dummy-snow {
        background: url(/img/listener/mobile/snow_snow@3x.png) center top no-repeat;
        background-size: contain;
        position: absolute;
        width: 100%;
        height: 400px;
        display: inline-block;
        left: 0;
        opacity: 0;
        transition: all ease-in-out .5s;
    }

    .section-listener.section-7>#dummy-snow.active {
        opacity: 1;
    }

    .section-listener.section-8 {
        background-image: url(/img/listener/mobile/756.jpg);
        font-weight: bold;
        background-size: 300%;
        background-position: -170px -430px;
        background-attachment: scroll;
    }

    .section-listener span.section-sticker {
        letter-spacing: -0.48px;
        color: #A5A5A5;
        position: absolute;
        text-align: left;
        text-indent: -16px;
        display: block;
        opacity: 1;
        transition: all .5s ease-in-out .5s;
        font-family: 'Montserrat', 'Noto Sans CJK KR', 'Malgun Gothic', '맑은 고딕', Tahoma, sans-serif !important;
        font-size: 12px;
        line-height: 18px;
    }

    .section-listener.active span.section-sticker {
        padding-left: 0px;
        opacity: 1;
    }

    .section-listener span.section-sticker>strong {
        letter-spacing: -0.48px;
        color: #535353;
        border: 1px solid #535353;
        border-width: 0 0 1px 0;
        font-weight: 400;
        font-size: 14px;
    }

    .section-listener span.sticker-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 10px;
        background-color: #00BAB3;
        margin: 0 4px -2px 0;
    }

    #badgeScroll {
        border: 2px solid #FFFFFF;
        border-radius: 33px;
        text-align: center;
        width: 33px;
        height: 33px;
        box-sizing: border-box;
        display: inline-block;
    }

    #badgeScroll>span {
        transform: rotate(45deg);
        border: 2px solid #ffffff;
        border-width: 0 3px 3px 0;
        width: 8px;
        height: 8px;
        position: absolute;
        display: inline-block;
        margin-left: -5px;
        margin-top: 7px;
    }

    div.sectionBadge {
        font-size: 14px;
        color: rgba(0, 0, 0, .5);
        padding-top: 44px;
        line-height: 16px;
    }

    div.section-visual-wrapper {
        width: 100%;
    }


    section.section-listener .animation-visual {
        transition: all ease-in-out .3s;
        opacity: 0;
    }

    section.section-listener.focus .animation-visual {
        opacity: 1;
    }

    section.section-listener .animation-visual.visual-1 {}

    section.section-listener .animation-visual.visual-2 {
        transition-delay: .3s;
    }

    section.section-listener .animation-visual.visual-3 {
        transition-delay: .6s;
    }

    section.section-listener .animation-visual.visual-4 {
        transition-delay: .9s;
    }


    section.section-listener.section-3 .animation-visual.visual-1 {
        margin-top: -50px;
        width: 90%;
        margin-left: 24px;
    }

    section.section-listener.section-3 .animation-visual.visual-2 {
        margin-top: -154px;
        margin-left: 40px;
        width: 90%;
    }

    section.section-listener.section-3 .animation-visual.visual-3 {
        margin-top: -110px;
        margin-left: 20px;
    }

    section.section-listener.section-3.focus .animation-visual.visual-1 {
        margin-top: 0;
    }

    section.section-listener.section-3.focus .animation-visual.visual-2 {
        margin-top: -104px;
    }

    section.section-listener.section-3.focus .animation-visual.visual-3 {
        margin-top: -60px;
    }

    section.section-listener.section-4 .animation-visual.visual-1 {
        margin-top: -50px;
        margin-left: 30px;
        width: 65%;
    }

    section.section-listener.section-4 .animation-visual.visual-2 {
        margin-top: -160px;
        margin-left: 85px;
        width: 62%;
    }

    section.section-listener.section-4 .animation-visual.visual-3 {
        margin-top: -227px;
        margin-left: 10px;
        width: 60%;
    }

    section.section-listener.section-4 .animation-visual.visual-4 {
        margin-top: -340px;
        margin-left: 250px;
        width: 80px;
    }

    section.section-listener.section-4.focus .animation-visual.visual-1 {
        margin-top: 0;
    }

    section.section-listener.section-4.focus .animation-visual.visual-2 {
        margin-top: -110px;
    }

    section.section-listener.section-4.focus .animation-visual.visual-3 {
        margin-top: -177px;
    }

    section.section-listener.section-4.focus .animation-visual.visual-4 {
        margin-top: -290px;
    }

    section.section-listener.section-5 .animation-visual.visual-1 {
        width: 60%;
        margin-left: 130px;
        margin-top: -50px;
    }

    section.section-listener.section-5 .animation-visual.visual-2 {
        margin: -345px 0 0 20px;
    }

    section.section-listener.section-5.focus .animation-visual.visual-1 {
        width: 60%;
        margin-left: 130px;
        margin-top: 0;
    }

    section.section-listener.section-5.focus .animation-visual.visual-2 {
        margin: -295px 0 0 20px;
    }

    section.section-listener.section-6 .animation-visual.visual-1 {
        margin: 30px 0 0 10%;
        width: 80%;
    }

    section.section-listener.section-6 .animation-visual.visual-2 {
        margin: -170px 0 0 25%;
        width: 50%;
    }

    section.section-listener.section-6 .animation-visual.visual-3 {
        margin: -325px 0 0 38%;
        width: 55%;
    }

    section.section-listener.section-6 .animation-visual.visual-4 {
        margin: -345px 0 0 20px;
    }

    section.section-listener.section-6.focus .animation-visual.visual-1 {
        margin: 80px 0 0 10%;
    }

    section.section-listener.section-6.focus .animation-visual.visual-2 {
        margin: -170px 0 0 25%;
    }

    section.section-listener.section-6.focus .animation-visual.visual-3 {
        margin: -275px 0 0 38%;
    }

    section.section-listener.section-6.focus .animation-visual.visual-4 {
        margin: -295px 0 0 20px;
    }

    #goListener {
        position: fixed;
        width: 72px;
        height: 72px;
        background: #00BAB3;
        border: 3px solid #ffffff;
        border-radius: 72px;
        box-shadow: 0px 3px 6px #00000029;
        text-align: center;
        bottom: 36px;
        right: -80px;
        transition: right ease-in-out .5s;
    }

    #goListener.active {
        right: 20px;
    }

    .section-listener button {
        height: 52px;
        padding: 0 48px;
        background-color: #FFFFFF;
        box-shadow: 0px 3px 6px #00000029;
        text-shadow: 0px 3px 6px #00000029;
        border-radius: 23px;
        text-align: center;
        font: Bold 16px/28px Noto Sans CJK KR;
        letter-spacing: -0.64px;
        color: #202020;
        transition: all ease-in-out .2s;
    }

    .section-listener button:focus {
        background-color: #00BAB3;
        color: #FFFFFF;
        outline: 0;
    }

    .listener-step-wrapper {
        padding: 20px 0;
    }

    .listener-step-content-wrapper {
        width: 100%;
        height: 277px;
        background: #FFFFFF 0% 0% no-repeat;
        box-shadow: 0px 3px 6px #0000000D;
        padding: 27px 33px;
        text-align: left;
        margin-top: -20px;
    }

    div.listener-nav-wrapper {
        position: absolute;
        width: 100%;
        margin-top: 120px;
    }

    div.listener-nav-wrapper>span {
        position: absolute;
        display: inline-block;
        width: 12px;
        height: 19px;
        background: url(/img/listener/mobile/ico_nav_arrow.png) 0 0 no-repeat padding-box;
        opacity: 1;
        left: 0;
        margin-left: 4%;
    }

    div.listener-nav-wrapper>span.active {
        opacity: 1;
    }

    div.listener-nav-dot-wrapper {
        margin-bottom: 36px;
        line-height: 0;
    }

    div.listener-nav-dot-wrapper>span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 8px;
        background: rgba(183, 183, 183, 1) 0% 0% no-repeat padding-box;
        transition: opacity ease-in-out .2s;
        opacity: 0.5;
        margin: 0 3px;
    }

    div.listener-nav-dot-wrapper>span.active {
        opacity: 1;
    }

    .listener-step-image-wrapper {
        width: 318px;
        height: 241px;
        background: url(/img/listener/mobile/05@3x.png) bottom center no-repeat;
        background-size: 83%;
        display: inline-block;
        margin-top: 12px;
    }

    .listener-step-image-wrapper.step-2 {
        background: url(/img/listener/mobile/ll_door@3x.png) 90% 30px no-repeat;
        background-size: 22%;
    }

    .listener-step-image-wrapper.step-3 {
        background: url(/img/listener/mobile/ll_door@3x.png) 90% 30px no-repeat;
        background-size: 22%;
    }

    .listener-step-image-wrapper.step-4 {
        background-image: url(/img/listener/mobile/778@3x.png);
        background-position: top;
        margin-bottom: 20px;
    }

    .listener-step-image-wrapper.step-5 {
        background-image: url(/img/listener/mobile/776@3x.png);
        background-position: top;
    }

    .listener-step-image-wrapper.step-2>.step-animation-1 {
        margin-top: 75px;
        animation-duration: 2s;
        animation-iteration-count: 1;
        height: 100px;
        width: auto;
        right: 111px;
        display: flex;
        opacity: 0;
    }

    .listener-step-image-wrapper.step-2>.step-animation-2 {
        margin-top: -100px;
        height: 100px;
        width: auto;
        margin-left: 110px;
        display: flex;
        opacity: 0;
        transition: opacity ease-in-out 1s;
        transition-delay: 2s;
    }

    .listener-step.step-2.tns-slide-active .step-animation-1 {
        animation-name: door2door;
        opacity: 1;
    }

    .listener-step.step-2.tns-slide-active .step-animation-2 {
        opacity: 1;
    }

    .listener-step-image-wrapper.step-3>.step-animation-1 {
        margin-top: 75px;
        animation-duration: 2s;
        animation-iteration-count: 1;
        height: 100px;
        width: auto;
        right: 111px;
        display: flex;
        opacity: 0;
    }

    .listener-step-image-wrapper.step-3>.step-animation-2 {
        margin-top: -70px;
        height: 70px;
        width: auto;
        margin-left: 90px;
        display: flex;
        opacity: 0;
        transition: opacity ease-in-out 1s;
        transition-delay: 2s;
    }

    .listener-step.step-3.tns-slide-active .step-animation-1 {
        animation-name: door2door;
        opacity: 1;
    }

    .listener-step.step-3.tns-slide-active .step-animation-2 {
        opacity: 1;
    }

    .listener-step-content-wrapper>.listener-step-step {
        font: Bold 14px/18px Montserrat;
        letter-spacing: -0.42px;
        color: #02B8B1;
        text-transform: uppercase;
    }

    .listener-step-content-wrapper>.listener-step-title {
        font: Bold 28px/41px Noto Sans CJK KR;
        letter-spacing: -1.4px;
        color: #202020;
    }

    .listener-step-content-wrapper>.listener-step-link {
        font: Normal 12px/22px Noto Sans CJK KR;
        letter-spacing: -0.6px;
        opacity: 1;
        text-align: right;
        right: 50px;
        margin-top: -24px;
    }

    .listener-step-content-wrapper>.listener-step-link>a {
        color: #959595;
    }

    .listener-step-content-wrapper>.listener-step-seperator {
        border: 1px solid #E8E8E8;
        margin: 17px 0;
    }

    .listener-step-content-wrapper>.listener-step-desc {
        font: Normal 14px/22px Noto Sans CJK KR;
        letter-spacing: -0.21px;
        color: #535353;
        font-weight: 500;
    }

    .listener-step-content-wrapper>.listener-step-noti {
        font: Normal 14px/22px Noto Sans CJK KR;
        letter-spacing: -0.7px;
        color: #A5A5A5;
        margin-top: 8px;
        font-weight: 400;
    }

    #icon-step-alarm {
        background: url(/img/listener/mobile/alarm@3x.png) center center no-repeat;
        background-size: contain;
        width: 87px;
        height: 87px;
        display: inline-block;
        margin-top: 32px;
        margin-left: 240px;
    }

    @media screen and (max-width: 400px) {
        .section-listener.section-1 {
            background-position-x: -500px;
        }
    }
</style>
<link rel="stylesheet" href="/js/tiny-slider/tiny-slider.css">
<!--[if (lt IE 9)]><script src="/js/tiny-slider/tiny-slider.helper.ie8.js"></script><![endif]-->
<script src="/js/tiny-slider/tiny-slider.js"></script>

<!-- container -->
<section class="section-listener section-1">
    <div>
        <div style="letter-spacing: -0.6px; margin-bottom: 120px; margin-top: 20%;">국내 최초 비대면 침구 리스(lease) 서비스</div>
        <div style="transition-delay: 0.8s; letter-spacing: -3.2px; font-size: 50px; color: #202020;">구스 베딩 리스너</div>
        <div style="transition-delay: 1.2s; letter-spacing: -0.2px; font-size: 20px;">Goose Bedding Listener</div>
    </div>
    <div style="position: absolute; width: 100%; bottom: 24px; height: 34px;">
        <span id="badgeScroll">
            <span></span>
        </span>
    </div>
</section>
<section class="section-listener section-2">
    <div class="sectionBadge">What is it?</div>
    <div>
        <div style="letter-spacing: -1.08px; font-size: 30px; margin-top: 19%; margin-bottom: 33px; color: #202020;">국내 최초 침구 리스 서비스,<br /> 구스 베딩 리스너</div>
        <div style="letter-spacing: -0.9px; font-size: 14px; line-height: 26px; color: #707070; font-weight: normal;">
            고가의 프리미엄 구스 침구를 구매, 사용, 관리하며<br />
            발생하는 불편함에 귀 기울였습니다.<br />
            저렴한 가격으로 구입하고, 계절에 맞는 제품을 사용하고,<br />
            믿을 수 있는 세탁 서비스로 관리 받으세요.<br />
            만족스러운 침구 경험은 삶의 즐거움을 선물합니다.</div>
    </div>
</section>
<section class="section-listener section-3">
    <div class="section-image">
        <div class="sectionBadge" style="color: #FFFFFF;">Why?</div>
    </div>
    <div>
        <div style="margin: 12% 0; padding: 0 8%; text-align: left;">
            <div style="letter-spacing: -1.08px; font-size: 28px; color:#00BAB3; padding-bottom: 20px; line-height: 46px;"><strong>부담 없이 저렴하게</strong></div>
            <div style="letter-spacing: -0.64px; font-size: 14px; color: #A5A5A5; line-height: 22px; margin-bottom: 40px; font-weight: normal;">
                <strong style="color: #535353;">160만원대 프리미엄 제품, 36개월 분납</strong><br />
                최대 55%, 약 88만원 할인 혜택<br /><br />
                <strong style="color: #535353;">월 1만원대로 구스다운 침구 2개 제공</strong><br />
                사계절용 1, 여름용 1
            </div>
            <div class="section-visual-wrapper" style="height: 230px;">
                <img class="animation-visual visual-1" style="" src="/img/listener/winter.png" alt="겨울용">
                <img class="animation-visual visual-2" style="" src="/img/listener/summer.png" alt="여름용">
                <span class="animation-visual visual-3 section-sticker" style="">
                    <span class="sticker-dot"></span>
                    <strong>2 SET</strong><br />
                    사계절용 (4 seasons)<br />
                    여름용 (summer)
                </span>
            </div>
        </div>
    </div>
</section>
<section class="section-listener section-4">
    <div class="section-image"></div>
    <div>
        <div style="margin: 12% 0; padding: 0 8%; text-align: left;">
            <div style="letter-spacing: -1.08px; font-size: 28px; color:#00BAB3; padding-bottom: 20px; line-height: 46px;"><strong>내 것으로 완전하게</strong></div>
            <div style="letter-spacing: -0.64px; font-size: 14px; color: #A5A5A5; line-height: 22px; margin-bottom: 40px; font-weight: normal;">
                <strong style="color: #535353;">나를 위한 새로운 제품 생산 및 제공</strong><br />
                계약 후 소유권 자동 이전<br /><br />
                <strong style="color: #535353;">1·2차로 나눠 계절에 맞는 제품 배송</strong><br />
                인수형 장기 렌탈 상품
            </div>
            <div class="section-visual-wrapper" style="height: 296px">
                <img class="animation-visual visual-1" src="/img/listener/mintbox.png" alt="민트박스">
                <img class="animation-visual visual-2" src="/img/listener/winter_pack.png" alt="겨울용 패키지">
                <img class="animation-visual visual-3" src="/img/listener/summer_pack.png" alt="여름용 패키지">
                <span class="animation-visual visual-4 section-sticker">
                    <span class="sticker-dot"></span>
                    <strong>소유권 이전</strong>
                </span>
            </div>
        </div>
    </div>
</section>
<section class="section-listener section-5">
    <div class="section-image"></div>
    <div>
        <div style="margin: 12% 0; padding: 0 8%; text-align: left;">
            <div style="letter-spacing: -1.08px; font-size: 28px; color:#00BAB3; padding-bottom: 20px; line-height: 46px;"><strong>전문가가 깔끔하게</strong></div>
            <div style="letter-spacing: -0.64px; font-size: 14px; color: #A5A5A5; line-height: 22px; margin-bottom: 40px; font-weight: normal;">
                <strong style="color: #535353;">계약 기간 내 세탁 서비스 무제한 제공</strong><br />
                건당 55,000원 상당(Q사이즈 이불솜, 물세탁 기준)<br /><br />
                <strong style="color: #535353;">전문가 직접 검수 및 제품별 맞춤 방식 진행</strong><br />
                구스 제품에 최적화된 세탁 공식과 가이드 마련
            </div>
            <div class="section-visual-wrapper" style="height: 291px;">
                <img class="animation-visual visual-1" src="/img/listener/luandry.png" alt="세탁기">
                <span class="animation-visual visual-2 section-sticker">
                    <span class="sticker-dot"></span>
                    <strong>세탁 무제한</strong>
                </span>
            </div>
        </div>
    </div>
</section>
<section class="section-listener section-6">
    <div class="section-image"></div>
    <div>
        <div style="margin: 12% 0; padding: 0 8%; text-align: left;">
            <div style="letter-spacing: -1.08px; font-size: 28px; color:#00BAB3; padding-bottom: 20px; line-height: 46px;"><strong>독립적으로 안전하게</strong></div>
            <div style="letter-spacing: -0.64px; font-size: 14px; color: #A5A5A5; line-height: 22px; margin-bottom: 40px; font-weight: normal;">
                <strong style="color: #535353;">개별 인식 카드(RFID)를 통한 맞춤 침구 관리</strong><br />
                고객별·제품별 세탁 과정 분류 및 단독 세탁 진행<br /><br />
                <strong style="color: #535353;">세탁 서비스 진행 사항 추적 가능</strong><br />
                검수-세탁-배송 등 마이페이지 확인
            </div>
            <div class="section-visual-wrapper" style="height: 230px;">
                <img class="animation-visual visual-1" src="/img/listener/down.png" alt="이불">
                <img class="animation-visual visual-2" src="/img/listener/light.png" alt="리더기 범위">
                <img class="animation-visual visual-3" src="/img/listener/reader.png" alt="리더기">
                <span class="animation-visual visual-4 section-sticker">
                    <span class="sticker-dot"></span>
                    <strong>RFID CARD</strong>
                </span>
            </div>
        </div>
    </div>
</section>
<section class="section-listener section-7">
    <div id="dummy-snow"></div>
    <div class="sectionBadge" style="color: rgba(32, 32, 32, .5);">How to?</div>
    <div class="listener-nav-wrapper">
        <span></span>
        <span style="left: auto; right: 0; margin-right: 4%; background-position: right;"></span>
    </div>
    <div class="listener-step-wrapper">
        <div class="listener-step step-1">
            <div class="listener-step-image-wrapper step-1"></div>
            <div style="padding: 0 20px;">
                <div class="listener-step-content-wrapper">
                    <div class="listener-step-step">STEP 1</div>
                    <div class="listener-step-title">리스 신청</div>
                    <div class="listener-step-link"><a href="">&nbsp;</a></div>
                    <div class="listener-step-seperator"></div>
                    <div class="listener-step-desc">
                        라이프라이크 사이트 또는 앱에 가입하고, 내가 원하는 리스 제품을 선택하세요.
                    </div>
                    <div class="listener-step-noti">
                        구스 베딩 리스너는 1년 동안 사용할 2종의 이불을 리스합니다.
                    </div>
                </div>
            </div>
        </div>
        <div class="listener-step step-2">
            <div class="listener-step-image-wrapper step-2">
                <img class="step-animation-1" src="/img/listener/mobile/ll_van@3x.png">
                <img class="step-animation-2" src="/img/listener/mobile/ll_box@3x.png">
            </div>
            <div style="padding: 0 20px;">
                <div class="listener-step-content-wrapper">
                    <div class="listener-step-step">STEP 2</div>
                    <div class="listener-step-title">1차 제품 수령</div>
                    <div class="listener-step-link"><a href="/bbs/board.php?bo_table=event&wr_id=23">웰컴키트 보러가기 ></a></div>
                    <div class="listener-step-seperator"></div>
                    <div class="listener-step-desc">
                        사계절용 이불과 여름용 이불 중, 지금 계절에 사용할 이불을 받으세요.
                    </div>
                    <div class="listener-step-noti">
                        1차 제품은 시즌에 따라 사계절 또는 여름용이 배송됩니다. 리스 이불과 함께 라이프라이크의 감성이 담긴 웰컴키트(Welcome Kit)를 받아보세요.
                    </div>
                </div>
            </div>
        </div>
        <div class="listener-step step-3">
            <div class="listener-step-image-wrapper step-3">
                <img class="step-animation-1" src="/img/listener/mobile/van_snow@3x.png">
                <img class="step-animation-2" src="/img/listener/mobile/box_snow@3x.png"></div>
            <div style="padding: 0 20px;">
                <div class="listener-step-content-wrapper">
                    <div class="listener-step-step">STEP 3</div>
                    <div class="listener-step-title">2차 제품 수령</div>
                    <div class="listener-step-link"><a href="">&nbsp;</a></div>
                    <div class="listener-step-seperator"></div>
                    <div class="listener-step-desc">
                        계절 바뀌고 새로운 이불이 필요할 때, 미 수령한 2차 이불을 받으세요.
                    </div>
                    <div class="listener-step-noti">
                        사계절용 이불을 받으신 분은 여름용 이불이 배송됩니다. 여름용 이불을 받으신 분은 사계절용 이불이 배송됩니다.
                    </div>
                </div>
            </div>
        </div>
        <div class="listener-step step-4">
            <div class="listener-step-image-wrapper step-4">
                <span id="icon-step-alarm"></span>
            </div>
            <div style="padding: 0 20px;">
                <div class="listener-step-content-wrapper">
                    <div class="listener-step-step">STEP 4</div>
                    <div class="listener-step-title">세탁 알람</div>
                    <div class="listener-step-link"><a href="/mobile/common/setting.php">알람 수신 설정하기 ></a></div>
                    <div class="listener-step-seperator"></div>
                    <div class="listener-step-desc">
                        사이트 또는 앱에서 세탁 서비스를 신청하고,<br />
                        서비스 안내에 따라 이용해주세요.
                    </div>
                    <div class="listener-step-noti">
                        세탁 알림은 앱 푸시, SMS으로 받으실 수 있습니다. 알림 수신 설정을 ON으로 변경해주세요.
                    </div>
                </div>
            </div>
        </div>
        <div class="listener-step step-5">
            <div class="listener-step-image-wrapper step-5"></div>
            <div style="padding: 0 20px;">
                <div class="listener-step-content-wrapper">
                    <div class="listener-step-step">STEP 5</div>
                    <div class="listener-step-title">세탁 신청</div>
                    <div class="listener-step-link"><a href="/shop/caremain.php">세탁서비스 신청하기 ></a></div>
                    <div class="listener-step-seperator"></div>
                    <div class="listener-step-desc">
                        사이트 또는 앱에서 세탁 서비스를 신청하고,<br />
                        서비스 안내에 따라 이용해주세요.
                    </div>
                    <div class="listener-step-noti">
                        구스 베딩 리스너를 신청하시면 세탁 서비스를<br />
                        무제한으로 받으실 수 있습니다. 품질의 저하가<br />
                        우려될 잦은 세탁은 별도의 안내를 드립니다.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="listener-nav-dot-wrapper">
        <span onclick="slideStep(0)" class="active"></span><span onclick="slideStep(1)"></span><span onclick="slideStep(2)"></span><span onclick="slideStep(3)"></span><span onclick="slideStep(4)"></span>
    </div>
</section>
<section class="section-listener section-8" style="height: 950px;">
    <div style=" margin-top: 10%;">
        <img style="vertical-align: middle; width: auto; margin-top: 2px; width: 76px;" src="/img/listener/mobile/ll_logo@3x.png" alt="LIFELIKE">
        <span style="text-align: center; font: 100 15px/19px Montserrat; letter-spacing: -0.45px; color: #FFFFFF; vertical-align: middle; margin: 0px 4px;">x</span>
        <img style="vertical-align: middle; width: auto; margin-top: 8px; width: 83px;" src="/img/listener/mobile/prauden_logo.png" alt="PRAUDEN">
    </div>
    <div style="color: #ffffff; font-size: 32px; font-weight: bold; line-height: 46px; margin: 26px 0; letter-spacing: -0.48px; ">
        프리미엄 제품을<br />가장 합리적인 가격에
    </div>
    <div style="letter-spacing: -0.84px; color: #ffffff; font-size: 14px; line-height: 26px; margin-bottom: 40px; font-weight: 100;">
        태평양물산㈜ 프리미엄 다운 전문 브랜드<br />
        프라우덴의 패밀리 기업으로서, 최고 품질의 우모를<br />
        공급받아 프리미엄 제품을 생산합니다.<br />
        복잡한 유통, 불필요한 인건비를 없앤<br />
        비대면 리스 방식과 36개월 분납 방식으로<br />
        부담 없이 이용할 수 있는 합리적인 가격을 제공합니다.
    </div>
    <div>
        <a href="/shop/item.php?it_id=0100200100000010"><button id="btn-listener">구스 베딩 리스너 신청하기
                <span style="width: 16px; height: 16px; display: inline-block; background: url(/img/listener/mobile/ico_arrow.png) 0 0 no-repeat; background-position: right; background-size: cover; vertical-align: middle; margin: -4px 0px 0 6px;"></span>
            </button></a>
    </div>
</section>
<div id="goListener">
    <a href="/shop/item.php?it_id=0100200100000010">
        <span>
            <div style="margin-top: 16px; color: #ffffff;">리스하기</div>
            <div>
                <span style="position: absolute; width: 18px; height: 18px; display: inline-block; background: url(/img/listener/mobile/ico_arrow.png) left 0 no-repeat; background-size: cover; vertical-align: middle; margin-top: 6px; margin-left: -9px;"></span>
            </div>
        </span>
    </a>
</div>

<script>
    let slider = null;

    function slideStep(step) {
        return slider.goTo(step);
    }

    function slideIndexChange(info, event) {
        const index = info.displayIndex - 1;
        if (index === 2) {
            $(".section-listener.section-7>#dummy-snow").addClass("active");
        } else {
            $(".section-listener.section-7>#dummy-snow").removeClass("active");
        }

        $("div.listener-nav-dot-wrapper>span.active").removeClass("active");
        $("div.listener-nav-dot-wrapper>span").eq(index).addClass("active");

    }
    slider = tns({
        container: '.listener-step-wrapper',
        controls: false,
        nav: false,
        autoplayButtonOutput: false,
        autoplay: true,
        items: 1,
        slideBy: 'page'

    });
    slider.events.on('indexChanged', slideIndexChange);

    $(function() {
        const headerHeight = $("div#header").height();
        let contentHeight = $(window).innerHeight();
        let scrollHeight;
        let bottomHeight;
        let scrollLock = false;
        let sectionTop = [];

        function sectionResize() {
            let sections = $("section.section-listener");
            $("section.section-listener.section-1").height(contentHeight - headerHeight).addClass("load");
            scrollHeight = document.documentElement.scrollHeight;
            bottomHeight = 2000;
        }

        $(document).ready(function() {
            sectionResize();
            sectionTop = [];
        });

        $(window).on("scroll", function(e) {
            if (scrollLock) {
                return true;
            }
            scrollLock = true;

            if (sectionTop.length == 0) {
                $("section.section-listener").each(function(idx, section) {
                    sectionTop.push(section.offsetTop);
                });

                sectionTop[0] = 0;
            }

            setTimeout(() => {
                const currentPos = $(window).scrollTop() + headerHeight;
                let sectionIdx = 1;

                $(sectionTop).each(function(si) {
                    if (sectionTop[si] <= currentPos) sectionIdx = si;
                });

                $("section.section-listener").removeClass("focus").eq(sectionIdx).addClass("focus");
                if (sectionIdx == 6) {
                    slider.play();
                    // console.log("play");
                } else {
                    slider.pause();
                    // console.log("pause");
                }

                if (currentPos > 200 && scrollHeight - bottomHeight > currentPos) {
                    $("#goListener").addClass("active");
                } else {
                    $("#goListener").removeClass("active");
                }
                scrollLock = false;
            }, 500);
        })
    })
</script>
<?php
include_once('./_tail.php');
