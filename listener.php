<?
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH . '/listener.php');
    return;
}

include_once(G5_PATH . '/head.php');
?>
<style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat&display=swap');

    #sectionBadge {
        position: fixed;
        top: 0;
        height: 100%;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        line-height: 22px;
        letter-spacing: -0.36px;
        color: #9C9C9C;
        opacity: 1;
        display: inline-block;
        left: -20px;
        font-family: 'Montserrat', 'Noto Sans CJK KR', Malgun Gothic, "맑은 고딕", Tahoma, sans-serif;
    }

    #sectionBadge>span {
        transform: rotate(90deg);
        position: absolute;
        width: 150px;
        left: 50%;
        top: 50%;
        display: inline-block;
        opacity: 0;
        transition: opacity ease-in-out .5s;
    }

    #sectionBadge.badge-1,
    #sectionBadge.badge-9 {
        opacity: 0;
    }

    #sectionBadge.badge-2>span.badge-text-1 {
        opacity: 1;
    }

    #sectionBadge.badge-3>span.badge-text-2,
    #sectionBadge.badge-4>span.badge-text-2,
    #sectionBadge.badge-5>span.badge-text-2,
    #sectionBadge.badge-6>span.badge-text-2 {
        opacity: 1;
    }

    #sectionBadge.badge-7>span.badge-text-3 {
        opacity: 1;
    }

    #sectionBadge.badge-8>span.badge-text-4 {
        opacity: 1;
    }

    #goListener {
        position: fixed;
        width: 80px;
        height: 110px;
        right: 0;
        bottom: 110px;
        background-color: #00BBBB;
        color: #ffffff;
        text-align: center;
        transition: right cubic-bezier(0.22, 0.61, 0.36, 1) .5s;
        font-weight: bold;
        padding: 8px 0;
        box-sizing: border-box;
    }

    #goListener>a {
        color: #ffffff;
    }

    #goListener span#badge-circle {
        border: 3px solid #ffffff;
        width: 30px;
        height: 30px;
        border-radius: 60px;
        display: inline-block;
        font-size: 24px;
        margin-top: 8px;
        line-height: 28px;
    }

    #goListener.badge-1,
    #goListener.badge-8,
    #goListener.badge-9 {
        right: -80px;
    }

    .section-listener {
        font-family: 'Montserrat', 'Noto Sans CJK KR', Malgun Gothic, "맑은 고딕", Tahoma, sans-serif;
        transition: all ease-in-out .5s;
        background: #ffffff center center no-repeat padding-box;
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
        min-height: 500px;
        width: 100%;
        text-align: center;
        color: #202020;
        overflow: hidden;
    }

    .section-listener strong {
        font-weight: bold;
    }

    .section-listener.section-1 {
        background-image: url(/img/listener/703.jpg);
    }

    .section-listener.section-1>div {
        position: absolute;
        width: 100%;
        transition: all .4s ease-in-out .5s;
    }

    .section-listener.section-1>div>div {
        transition: all .5s ease-in-out .5s;
        padding-top: 60px;
        font-weight: bold;
        font-size: 20px;
        text-shadow: 0px 0px 1px rgba(255, 255, 255, .5);
        opacity: 0;
    }

    .section-listener.section-1.active>div>div {
        padding-top: 0;
        opacity: 1;
    }

    .section-listener.section-2>div {
        position: absolute;
        width: 100%;
    }

    .section-listener.section-3>div,
    .section-listener.section-4>div,
    .section-listener.section-5>div,
    .section-listener.section-6>div {
        display: inline-block;
        width: 50%;
        height: 100%;
        float: left;
    }

    .section-listener>div.section-image {
        background: #ffffff center center no-repeat;
        background-position: right;
        background-attachment: fixed;
        background-size: auto;
    }

    .section-listener.section-3>div.section-image {
        background-image: url(/img/listener/759.jpg);
    }

    .section-listener.section-3>div>div>div,
    .section-listener.section-4>div>div>div,
    .section-listener.section-5>div>div>div,
    .section-listener.section-6>div>div>div {
        display: inline-block;
        width: 50%;
        height: 100%;
        float: left;
        padding: 32px 24px;
        text-align: left;
        box-sizing: border-box;
        line-height: 28px;
    }

    .section-listener.section-4>div.section-image {
        background-image: url(/img/listener/762.jpg);
        background-position: left;
    }

    .section-listener.section-5>div.section-image {
        background-image: url(/img/listener/763.jpg);
    }

    .section-listener.section-6>div.section-image {
        background-image: url(/img/listener/logo_box.jpg);
        background-position: left;
    }

    .section-listener.section-3 {
        background-color: #f5f5f5;
    }

    .section-listener.section-4 {}

    .section-listener.section-5 {
        background-color: #f5f5f5;
    }

    .section-listener.section-6 {}

    .section-listener.section-7,
    .section-listener.section-7.step-1 {
        background-image: url(/img/listener/bg01.jpg);
    }

    .section-listener.section-7.step-2 {
        background-image: url(/img/listener/bg02.jpg);
    }

    .section-listener.section-7.step-3 {
        background-image: url(/img/listener/bg03.jpg);
    }

    .section-listener.section-7.step-4 {
        background-image: url(/img/listener/bg04.jpg);
    }

    .section-listener.section-7.step-5 {
        background-image: url(/img/listener/769.jpg);
    }

    .section-listener.section-7 {
        color: #ffffff;
    }

    .section-listener.section-8 {
        background-image: url(/img/listener/756.jpg);
    }

    .section-listener button {
        user-select: none;
        padding: 12px 24px;
        background-color: #FFFFFF;
        box-shadow: 0px 3px 6px #0000001C;
        border-radius: 29px;
        text-align: center;
        letter-spacing: -0.4px;
        color: #202020;
        font-weight: bold;
        font-size: 20px;
        transition: all ease-in-out .2s;
    }

    .section-listener button:hover {
        background-color: #00BAB3;
        color: #FFFFFF;
    }

    .section-listener button:focus {
        outline: 0;
    }

    .section-listener img {
        float: left;
    }

    .section-listener.section-8 img {
        position: relative;
        float: none;
    }

    .section-listener span.section-sticker {
        letter-spacing: -0.48px;
        color: #707070;
        position: absolute;
        text-align: left;
        text-indent: -16px;
        display: block;
        padding-left: 40px;
        opacity: 0;
        transition: all .5s ease-in-out .5s;
    }

    .section-listener.active span.section-sticker {
        padding-left: 0px;
        opacity: 1;
    }

    .section-listener span.section-sticker>strong {
        letter-spacing: -0.48px;
        color: #202020;
        border: 1px solid #202020;
        border-width: 0 0 1px 0;
    }

    .section-listener span.sticker-dot {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 14px;
        background-color: #00BAB3;
    }

    .section-visual-wrapper {
        overflow: hidden;
        width: 100%;
        height: 100%;
        padding: 0 50% !important;
        padding-top: 10% !important;
    }

    .section-visual-wrapper>img,
    .section-visual-wrapper>span {
        position: absolute;
    }

    .listener-step-wrapper {
        position: absolute;
        width: 100%;
    }

    .listener-step-wrapper.active {
        opacity: 1;
    }

    .listener-step-wrapper::after {
        break: all;
    }

    .listener-step-wrapper .step-1,
    .listener-step-wrapper .step-2,
    .listener-step-wrapper .step-3,
    .listener-step-wrapper .step-4,
    .listener-step-wrapper .step-5 {
        display: none;
        opacity: 0;
    }

    .listener-step-wrapper .step-1.active,
    .listener-step-wrapper .step-2.active,
    .listener-step-wrapper .step-3.active,
    .listener-step-wrapper .step-4.active,
    .listener-step-wrapper .step-5.active {
        display: inline-block;
        opacity: 1;
    }

    .listener-step-wrapper>.step-left,
    .listener-step-wrapper>.step-right {
        margin-top: 5%;
        text-align: right;
        box-sizing: border-box;
        display: inline-block;
        min-height: 741px;
        float: left;
        transition: all ease-in-out .2s;
    }

    .listener-step-wrapper>.step-left {
        min-width: 43%;
        padding-right: 3%;
    }

    .listener-step-wrapper>.step-left>div {
        display: block !important;
    }

    .listener-step-wrapper>.step-right {
        min-width: 57%;
        margin-top: 15%;
        text-align: left;
    }

    .listener-step-wrapper .step-listener-step {
        font-size: 20px;
    }

    .listener-step-wrapper .step-listener-title {
        font-size: 50px;
        font-weight: bold;
    }

    .listener-step-wrapper .step-listener-flow {
        margin: 30px 0;
    }

    .listener-step-wrapper .step-listener-hypen {}

    .listener-step-wrapper .step-listener-desc {
        font-size: 18px;
    }

    .listener-step-wrapper .step-listener-noti {
        font-size: 16px;
        color: #BEBEBE;
    }

    .listener-step-wrapper .step-listener-seperator {
        display: inline-block;
        border-bottom: 1px solid #BEBEBE;
        margin: 18px 0 12px 0;
        width: 424px;
    }

    .step-listener-flow-wrapper {
        display: inline-block !important;
        opacity: 1 !important;
        width: 84px;
        height: 84px;
        border: 3px solid #ffffff;
        border-radius: 84px;
        overflow: hidden;
        font-size: 18px;
        line-height: 20px;
        text-align: center;
        cursor: pointer;
        transition: background-color ease-in-out .5s;
    }

    .step-listener-flow-wrapper.active {
        background-color: #00BAB3;
    }

    .step-listener-flow-wrapper>span {
        width: 100%;
        height: 100%;
        display: inline-block;
        margin-top: 25%;
    }

    .step-listener-flow-wrapper>.dummy {
        margin-top: 0;
        float: left;
        font-size: 40px;
        line-height: 84px;
    }

    .step-listener-flow-wrapper.active>.text-flow {
        display: none;
    }

    .listener-step-wrapper.active .dummy {}

    .step-listener-flow-hypen {
        width: 46px;
        display: inline-block;
        border-bottom: 3px solid #ffffff;
        height: 42px;
        vertical-align: top;
        margin-left: -4px;
        margin-right: -4px;
    }

    .step-listener-button {
        padding-top: 28px;
    }

    .step-bg-image {
        display: inline-block;
        opacity: 0;
        width: 368px;
        height: 741px;
        background-repeat: no-repeat;
        transition: all .5s ease-in-out 1s;
        position: absolute;
        left: 20%;
    }

    .listener-step-wrapper>.step-left>.active>.step-bg-image {
        opacity: 1;
    }

    .step-ven-image.go {
        left: 10% !important;
        opacity: 0 !important;
    }
</style>
<!-- container -->
<div id="container" style="min-width: 1280px !important;">
    <section class="section-listener section-1">
        <div>
            <div style="letter-spacing: -0.6px; margin-bottom: 120px; margin-top: 9%;">국내 최초 비대면 침구 리스(lease) 서비스</div>
            <div style="transition-delay: 0.8s; letter-spacing: -3.2px; font-size: 80px; color: #202020;">구스 베딩 리스너</div>
            <div style="transition-delay: 1.2s; letter-spacing: -0.2px;">Goose Bedding Listener</div>
        </div>
    </section>
    <section class="section-listener section-2">
        <div>
            <div style="letter-spacing: -1.08px; font-size: 36px; margin-top: 19%; margin-bottom: 33px; font-weight: bold;">국내 최초 침구 리스 서비스, 구스 베딩 리스너</div>
            <div style="letter-spacing: -0.9px; font-size: 18px; line-height: 35px; color: #707070;">
                고가의 프리미엄 구스 침구를 구매, 사용, 관리하며 발생하는 불편함에 귀 기울였습니다.<br />
                저렴한 가격으로 구입하고, 계절에 맞는 제품을 사용하고, 믿을 수 있는 세탁 서비스로 관리 받으세요.<br />
                만족스러운 침구 경험은 삶의 즐거움을 선물합니다.</div>
        </div>
    </section>
    <section class="section-listener section-3">
        <div>
            <div style="margin: 12% 0;">
                <div style="letter-spacing: -1.08px; font-size: 36px; text-align: right; color:#00BAB3; line-height: 46px;"><strong>부담 없이 저렴하게</strong></div>
                <div style="letter-spacing: -0.64px; font-size: 16px; color: #707070;">
                    <strong style="color: #202020;">160만원대 프리미엄 제품, 36개월 분납</strong><br />
                    최대 55%, 약 88만원 할인 혜택<br /><br />
                    <strong style="color: #202020;">월 1만원대로 구스다운 침구 2개 제공</strong><br />
                    사계절용 1, 여름용 1
                </div>
                <div class="section-visual-wrapper">
                    <img style="margin: 0 0 0 -290px;" src="/img/listener/winter.png" alt="겨울용">
                    <img style="margin: 130px 0 0 -280px;" src="/img/listener/summer.png" alt="여름용">
                    <span style="margin: 80px 0 0 215px;" class="section-sticker">
                        <span class="sticker-dot"></span>
                        <strong>2 SET</strong><br />
                        사계절용 (4 seasons)<br />
                        여름용 (summer)
                    </span>
                </div>
            </div>
        </div>
        <div class="section-image"></div>
    </section>
    <section class="section-listener section-4">
        <div class="section-image"></div>
        <div>
            <div style="margin: 12% 0;">
                <div style="letter-spacing: -1.08px; font-size: 36px; text-align: right; color:#00BAB3; line-height: 46px;"><strong>내 것으로 완전하게</strong></div>
                <div style="letter-spacing: -0.64px; font-size: 16px; color: #707070;">
                    <strong style="color: #202020;">나를 위한 새로운 제품 생산 및 제공</strong><br />
                    계약 후 소유권 자동 이전<br /><br />
                    <strong style="color: #202020;">1·2차로 나눠 계절에 맞는 제품 배송</strong><br />
                    인수형 장기 렌탈 상품
                </div>
                <div class="section-visual-wrapper">
                    <img style="margin: 0 0 0 -200px;;" src="/img/listener/mintbox.png" alt="민트박스">
                    <img style="margin: 120px 0 0 -120px;" src="/img/listener/winter_pack.png" alt="겨울용 패키지">
                    <img style="margin: 110px 0 0 -230px;" src="/img/listener/summer_pack.png" alt="여름용 패키지">
                    <span style="margin: 140px 0 0 220px; width: 80px;" class="section-sticker">
                        <span class="sticker-dot"></span>
                        <strong>소유권 이전</strong>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="section-listener section-5">
        <div>
            <div style="margin: 12% 0;">
                <div style="letter-spacing: -1.08px; font-size: 36px; text-align: right; color:#00BAB3; line-height: 46px;"><strong>전문가가 깔끔하게</strong></div>
                <div style="letter-spacing: -0.64px; font-size: 16px; color: #707070;">
                    <strong style="color: #202020;">계약 기간 내 세탁 서비스 무제한 제공</strong><br />
                    건당 55,000원 상당(Q사이즈 이불솜, 물세탁 기준)<br /><br />
                    <strong style="color: #202020;">전문가 직접 검수 및 제품별 맞춤 방식 진행</strong><br />
                    구스 제품에 최적화된 세탁 공식과 가이드 마련
                </div>
                <div class="section-visual-wrapper">
                    <img style="margin: 0 0 0 -280px;" src="/img/listener/luandry.png" alt="세탁기">
                    <span style="margin: -12px 0 0 -8px;" class="section-sticker">
                        <span class="sticker-dot"></span>
                        <strong>세탁 무제한</strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="section-image"></div>
    </section>
    <section class="section-listener section-6">
        <div class="section-image"></div>
        <div>
            <div style="margin: 12% 0;">
                <div style="letter-spacing: -1.08px; font-size: 36px; text-align: right; color:#00BAB3; line-height: 46px;"><strong>독립적으로 안전하게</strong></div>
                <div style="letter-spacing: -0.64px; font-size: 16px; color: #707070;">
                    <strong style="color: #202020;">개별 인식 카드(RFID)를 통한 맞춤 침구 관리</strong><br />
                    고객별·제품별 세탁 과정 분류 및 단독 세탁 진행<br /><br />
                    <strong style="color: #202020;">세탁 서비스 진행 사항 추적 가능</strong><br />
                    검수-세탁-배송 등 마이페이지 확인
                </div>
                <div class="section-visual-wrapper">
                    <img style="margin: 80px 0 0 -340px;" src="/img/listener/down.png" alt="이불">
                    <img style="margin: 80px 0 0 -240px;" src="/img/listener/light.png" alt="리더기 범위">
                    <img style="margin: 0 0 0 -170px;" src="/img/listener/reader.png" alt="리더기">
                    <span style="margin: 0 0 0 120px;" class="section-sticker">
                        <span class="sticker-dot"></span>
                        <strong>RFID CARD</strong>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="section-listener section-7">
        <div class="listener-step-wrapper">
            <div class="step-left">
                <div class="step-1">
                    <div class="step-bg-image" style="background-image: url(/img/listener/step1/01.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 2s; background-image: url(/img/listener/step1/02.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 3s; background-image: url(/img/listener/step1/03.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 4s; background-image: url(/img/listener/step1/04.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 5s; background-image: url(/img/listener/step1/05.png)"></div>
                </div>
                <div class="step-2">
                    <div class="step-bg-image" style="background-image: url(/img/listener/door.png); background-position: 220px 310px;"></div>
                    <div class="step-bg-image" style="transition-delay: 2s; background-image: url(/img/listener/door_box.png); background-position: 0px 400px; width: 456px; left: 15%;"></div>
                    <div class="step-bg-image step-ven-image" style="transition-delay: 1s; background-image: url(/img/listener/ven.png); background-position: 0px 400px; left: 14%; width: 532px;"></div>
                </div>
                <div class="step-3">
                    <div class="step-bg-image" style="background-image: url(/img/listener/door.png); background-position: 220px 310px;"></div>
                    <div class="step-bg-image" style="transition-delay: 2s; background-image: url(/img/listener/door_box_snow.png); background-position: 0px 464px; width: 456px; left: 15%;"></div>
                    <div class="step-bg-image step-ven-image" style="transition-delay: 1s; background-image: url(/img/listener/ven2.png); background-position: -20px 380px; left: 14%; width: 532px;"></div>
                </div>
                <div class="step-4">
                    <div class="step-bg-image" style="background-image: url(/img/listener/step4/01.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 2s; background-image: url(/img/listener/step4/02.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 3s; background-image: url(/img/listener/step4/03.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 4s; background-image: url(/img/listener/step4/04.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 5s; background-image: url(/img/listener/step4/05.png)"></div>
                </div>
                <div class="step-5">
                    <div class="step-bg-image" style="background-image: url(/img/listener/step5/01.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 2s; background-image: url(/img/listener/step5/02.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 3s; background-image: url(/img/listener/step5/03.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 4s; background-image: url(/img/listener/step5/04.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 5s; background-image: url(/img/listener/step5/05.png)"></div>
                    <div class="step-bg-image" style="transition-delay: 6s; background-image: url(/img/listener/step5/06.png)"></div>
                </div>
            </div>
            <div class="step-right">
                <div id="step-title" class="step-listener-step">STEP 1</div>
                <div class="step-listener-title">
                    <div class="step-1">리스 신청</div>
                    <div class="step-2">1차 제품 수령</div>
                    <div class="step-3">2차 제품 수령</div>
                    <div class="step-4">세탁 알림</div>
                    <div class="step-5">세탁 신청</div>
                </div>
                <div class="step-listener-flow">
                    <span class="step-listener-flow-wrapper step-1" data-step=1>
                        <span class="text-flow">리스<br />신청</span>
                        <span class="dummy">1</span>
                    </span>
                    <span class="step-listener-flow-hypen"></span>
                    <span class="step-listener-flow-wrapper step-2" data-step=2>
                        <span class="text-flow">1차<br />수령</span>
                        <span class="dummy">2</span>
                    </span>
                    <span class="step-listener-flow-hypen"></span>
                    <span class="step-listener-flow-wrapper step-3" data-step=3>
                        <span class="text-flow">2차<br />수령</span>
                        <span class="dummy">3</span>
                    </span>
                    <span class="step-listener-flow-hypen"></span>
                    <span class="step-listener-flow-wrapper step-4" data-step=4>
                        <span class="text-flow">세탁<br />알림</span>
                        <span class="dummy">4</span>
                    </span>
                    <span class="step-listener-flow-hypen"></span>
                    <span class="step-listener-flow-wrapper step-5" data-step=5>
                        <span class="text-flow">세탁<br />신청</span>
                        <span class="dummy">5</span>
                    </span>
                </div>
                <div class="step-listener-desc">
                    <div class="step-1">
                        라이프라이크 사이트 또는 앱에 가입하고,<br />
                        내가 원하는 리스 제품을 선택하세요.
                    </div>
                    <div class="step-2">
                        사계절용 이불과 여름용 이불 중,<br />
                        지금 계절에 사용할 이불을 받으세요.
                    </div>
                    <div class="step-3">
                        계절 바뀌고 새로운 이불이 필요할 때,<br />
                        미 수령한 2차 이불을 받으세요.
                    </div>
                    <div class="step-4">
                        세탁이 필요한 시점에 자동 세탁 알림이 울립니다.<br />
                        구스 베딩 리스너 세탁 서비스를 신청하세요.
                    </div>
                    <div class="step-5">
                        사이트 또는 앱에서 세탁 서비스를 신청하고,<br />
                        서비스 안내에 따라 이용해주세요.
                    </div>
                </div>
                <div class="step-listener-seperator"></div>
                <div class="step-listener-noti">
                    <div class="step-1">
                        구스 베딩 리스너는 1년 동안 사용할 2종의 이불을 리스합니다.
                    </div>
                    <div class="step-2">
                        1차 제품은 시즌에 따라 사계절 또는 여름용이 배송됩니다.<br />
                        리스 이불과 함께 라이프라이크의 감성이 담긴 웰컴키트(Welcome Kit)를 받아보세요.
                    </div>
                    <div class="step-3">
                        사계절용 이불을 받으신 분은 ‘여름용 이불’이 배송됩니다.<br />
                        여름용 이불을 받으신 분은 ‘사계절용 이불’이 배송됩니다.
                    </div>
                    <div class="step-4">
                        세탁 알림은 앱 푸시, SMS로 받으실 수 있습니다.<br />
                        알림 수진 설정을 ON으로 변경해주세요.
                    </div>
                    <div class="step-5">
                        구스 베딩 리스너를 신청하시면 세탁 서비스를 무제한으로 받으실 수 있습니다.<br />
                        제품의 품질을 저하를 시킬 수 있는 잦은 세탁은 별도의 안내를 드립니다.
                    </div>
                </div>
                <div class="step-listener-button">
                    <a class="step-2" href="/bbs/board.php?bo_table=event&wr_id=23"><button>웰컴키트 보러가기</button></a>
                    <a class="step-4" href=""><button>알람 수신 설정하기</button></a>
                    <a class="step-5" href="/shop/caremain.php"><button>세탁서비스 신청하기</button></a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-listener section-8">
        <div style="margin-top: 10%;">
            <img style="margin-top: 4px;" src="/img/listener/logo_ll_t.png" alt="LIFELIKE"><span style="font-size: 20px; letter-spacing: -0.6px; padding: 8px;">x</span><img src="/img/listener/logo_prauden_t.png" alt="PRAUDEN">
        </div>
        <div style="letter-spacing: -1.5px; color: #202020; font-size: 50px; font-weight: bold; line-height: 74px; margin: 26px 0;">프리미엄 제품을 가장 합리적인 가격에</div>
        <div style="letter-spacing: -0.72px; color: #202020; font-size: 18px; line-height: 35px; margin-bottom: 40px;">
            태평양물산㈜ 프리미엄 다운 전문 브랜드 프라우덴의 패밀리 기업으로서,<br />
            최고 품질의 우모를 공급받아 프리미엄 제품을 생산합니다.<br />
            복잡한 유통, 불필요한 인건비를 없앤 비대면 리스 방식과<br />
            36개월 분납 방식으로 부담 없이 이용할 수 있는 합리적인 가격을 제공합니다.
        </div>
        <div>
            <a href="/shop/item.php?it_id=0100200100000010"><button id="btn-listener">구스 베딩 리스너 신청하기</button></a>
        </div>
    </section>
    <div id="sectionBadge" class="badge-1">
        <span class="badge-text-1">What is it?</span>
        <span class="badge-text-2">Why?</span>
        <span class="badge-text-3">How to?</span>
        <span class="badge-text-4">Product & Price</span>
    </div>
    <div id="goListener" class="badge-1">
        <a href="/shop/item.php?it_id=0100200100000010">
            <span>
                <div>리스신청<br />바로가기</div>
                <div>
                    <span id="badge-circle">
                        →
                    </span>
                </div>
            </span>
        </a>
    </div>
</div>
<div style="display: none;">
    <img src="/img/listener/bg01.jpg" alt="" />
    <img src="/img/listener/bg02.jpg" alt="" />
    <img src="/img/listener/bg03.jpg" alt="" />
    <img src="/img/listener/bg04.jpg" alt="" />
    <img src="/img/listener/769.jpg" alt="" />
</div>
<!-- //container -->
<script>
    $(function() {
        // Arror Up, Down, Page Up, Down
        const scrollKeyCode = [33, 38, 34, 40];
        let sectionTop = [];
        let onScroll = false;
        let stepAutoPlay;

        function sectionResize() {
            const headerHeight = $("div#header").height();
            let contentHeight = $(window).innerHeight();
            let sections = $("section.section-listener");

            $("section.section-listener").height(contentHeight);
            $("section.section-listener.section-1").height(contentHeight - headerHeight);
        }

        $(document).ready(function() {

            sectionResize();
            $(".step-listener-flow-wrapper").on("click", function() {
                clearInterval(stepAutoPlay);
                activeStep($(this).data("step"));
            });

            $(".section-listener.section-1").addClass("active");
            document.addEventListener('wheel', sectionScroll, {
                passive: false
            });
            document.addEventListener('keydown', sectionScroll);
        });

        $(window).resize(function() {
            sectionResize();
            sectionTop = [];
        });

        function sectionScroll(e) {
            if (e.type == "keydown" && scrollKeyCode.indexOf(e.keyCode) === -1) {
                return true;
            }

            e.preventDefault();
            e.stopPropagation();

            if (onScroll) {
                return false;
            }
            onScroll = true;
            if (sectionTop.length == 0) {
                $("section.section-listener").each(function(idx, section) {
                    sectionTop.push(section.offsetTop);
                });

                sectionTop[0] = 0;
                sectionTop.push($("#footer")[0].offsetTop);
            }

            let toUp = false;
            if (e.type == "keydown") {
                toUp = (scrollKeyCode.indexOf(e.keyCode) < 2);
            } else {
                toUp = (e.deltaY < 0);
            }
            let scrollSize = toUp ? -100 : +100;
            let currentY = $(window).scrollTop() + scrollSize;
            let nextIdx = 0;
            let scrollTo = 0;

            $(sectionTop).each(function(si) {
                if (sectionTop[si] <= currentY && currentY < sectionTop[si + 1]) {
                    nextIdx = toUp ? si : si + 1;
                }
            });

            if (nextIdx <= 0) {
                nextIdx = 0;
            } else if (nextIdx == 6 && stepAutoPlay == undefined) {
                playSectionStep();
            }

            $("#sectionBadge, #goListener").attr("class", "").addClass("badge-" + (nextIdx * 1 + 1));

            $(".section-listener").removeClass("active");
            $(".section-listener.section-" + (nextIdx * 1 + 1)).addClass("active");
            scrollTo = sectionTop[nextIdx];

            $("html, body").stop().animate({
                scrollTop: scrollTo
            }, 300, function() {
                onScroll = false;
            });
        }

        function playSectionStep() {
            const stepCount = 5;
            const stepDelay = 8000;
            let step = 1;

            activeStep(step);
            stepAutoPlay = setInterval(function() {
                if (step >= stepCount) step = 0;
                step++;
                activeStep(step);
            }, stepDelay);
        }

        function activeStep(step) {
            const section = $(".section-listener.section-7");
            $(section).attr("class", "section-listener section-7 step-" + step);
            $(".step-ven-image.go").removeClass("go");

            $("#step-title").text("STEP " + step);
            $(section).find(".active").removeClass("active");
            $(section).find(".step-" + step).addClass("active");

            setTimeout(function() {
                $(section).find(".step-ven-image").addClass("go");
            }, 3000);
        }
    })
</script>
<?
include_once(G5_PATH . '/tail.php');

if ($preview_main_id) {
    echo "<script>$('#header').html('');$('#footer').html('');</script>";
}
?>