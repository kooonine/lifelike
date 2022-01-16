<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH . '/rental.php');
    return;
}

include_once(G5_PATH . '/head.php');
?>
<!-- container -->
<div id="container">
    <div class="content_wrap">
        <div class="service-visual service-visual-02">
            <div class="inner">
                <span class="add-text">합리적 소비의 시작</span>

                <h2>리스 서비스</h2>
            </div>
        </div>
        <div class="rental-content-01">
            <div class="inner">
                <div class="rental-text-lt title-deco">
                    <p>
                        40년 이상의 제조 노하우를 가진 태평양물산그룹의 리탠다드㈜ LIFELIKE가 고심 끝에 준비했습니다.<br>
                        LIFELIKE가 말하는 침구 리스이란, 한 개의 침구를 여러 고객님이 사용 하는 리스가 아닙니다.<br>
                        무선 인식 시스템 고객 식별 카드 RFID를 통해 사용자 개별로 제품 관리가 철저히 이루어 집니다.<br>
                        계약 종료 후에는 소유권을 이전하여 평생 사용도 가능합니다.<br>
                        업계 최초 침구 리스 서비스, 합리적 소비의 시작입니다.
                    </p>

                </div>
                <div class="rental-box-wrap">
                    <ul class="clearfix">
                        <li>
                            <div>
                                <span>총 약정기간<span>36개월</span></span>
                            </div>
                            <p>
                                계약 종료 후<br>
                                소유권 자동이전
                            </p>
                        </li>
                        <li>
                            <div>
                                <span>무료 세탁 서비스<span>연 1회</span></span>
                            </div>
                            <p>
                                연 1회,<br>
                                총 3회 제공
                            </p>
                        </li>
                        <li>
                            <div>
                                <span>맞춤 서비스<span>세탁 알림</span></span>
                            </div>
                            <p>
                                계절별<br>
                                자동 알람
                            </p>
                        </li>
                        <li>
                            <div>
                                <span>고객 식별 카드<span>RFID CARD</span></span>
                            </div>
                            <p>
                                개별 제품<br>
                                맞춤 관리
                            </p>
                        </li>
                        <li>
                            <div>
                                <span>생산부터 관리까지<span>업계 최초</span></span>
                            </div>
                            <p>
                                각 분야 전문가들의<br>
                                원스톱 서비스
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="rental-content-02">
            <div class="inner">
                <p class="content-title title-deco">
                    리스 요금 안내
                </p>
                <div class="clearfix">
                    <div class="rental-table left">
                        <p class="table-title">헝가리 구스 이불</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <span>겨울용 S</span>
                                        <p>17,900</p>
                                    </td>
                                    <td>
                                        <span>여름용 S</span>
                                        <p>11,900</p>
                                    </td>
                                    <td>
                                        <span>SET S</span>
                                        <p class="mint-color">25,900</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>겨울용 Q</span>
                                        <p>19,900</p>
                                    </td>
                                    <td>
                                        <span>여름용 Q</span>
                                        <p>13,900</p>
                                    </td>
                                    <td>
                                        <span>SET Q</span>
                                        <p class="mint-color">27,900</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>겨울용 Q</span>
                                        <p>19,900</p>
                                    </td>
                                    <td>
                                        <span>여름용 Q</span>
                                        <p>13,900</p>
                                    </td>
                                    <td>
                                        <span>SET Q</span>
                                        <p class="mint-color">27,900</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="rental-table right">
                        <p class="table-title">폴란드 구스 이불</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <span>겨울용 S</span>
                                        <p>17,900</p>
                                    </td>
                                    <td>
                                        <span>여름용 S</span>
                                        <p>11,900</p>
                                    </td>
                                    <td>
                                        <span>SET S</span>
                                        <p class="mint-color">25,900</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>겨울용 Q</span>
                                        <p>19,900</p>
                                    </td>
                                    <td>
                                        <span>여름용 Q</span>
                                        <p>13,900</p>
                                    </td>
                                    <td>
                                        <span>SET Q</span>
                                        <p class="mint-color">27,900</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>겨울용 Q</span>
                                        <p>19,900</p>
                                    </td>
                                    <td>
                                        <span>여름용 Q</span>
                                        <p>13,900</p>
                                    </td>
                                    <td>
                                        <span>SET Q</span>
                                        <p class="mint-color">27,900</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-add-text">
                    <ul>
                        <li>리스 1개월 이용금액(단위 : 원)</li>
                        <li>SET는 겨울용 1개(봄,가을 사용 가능), 여름용 1개 를 포함.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="rental-content-03">
            <div class="inner">
                <p class="content-title title-deco">
                    서비스 프로세스
                </p>
                <div class="clearfix">
                    <div class="rental-service-table">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <span>STEP 01.</span>
                                        <p>서비스 신청<br>및 계약</p>
                                    </td>
                                    <td>
                                        <span>STEP 02.</span>
                                        <p>제품 배송<br>및 사용</p>
                                    </td>
                                    <td>
                                        <span>STEP 03.</span>
                                        <p class="color-mint">무료 세탁<br>서비스 알림</p>
                                    </td>
                                    <td>
                                        <span>STEP 04.</span>
                                        <p>제품 수거<br>및 검수</p>
                                    </td>
                                    <td>
                                        <span>STEP 05.</span>
                                        <p>세탁</p>
                                    </td>
                                    <td>
                                        <span>STEP 06.</span>
                                        <p>배송 완료</p>
                                    </td>
                                    <td>
                                        <span>STEP 07.</span>
                                        <p>제품 사용</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="<?php echo G5_SHOP_URL ?>/list.php?ca_id=102010">
                    <div class="rental-service-btn">
                        <button>서비스 신청</button>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- //container -->

<?php
include_once(G5_PATH . '/tail.php');
?>