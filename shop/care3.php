<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/care3.php');
    return;
}

$g5['title'] = '수선서비스';
include_once('./_head.php');

?>


        <!-- container -->
        <div id="container">
            <div class="content_wrap">
                <div class="service-visual service-visual-05">
                    <div class="inner">
                        <span class="add-text">전문가의 1:1 밀착 관리</span>

                        <h2>수선 서비스</h2>
                    </div>
                </div>
                <div class="repair-content-01">
                    <div class="inner">
                        <div class="rental-text-lt title-deco">
                            <p>
                                그동안 고장 난 지퍼를 그대로 사용하진 않으셨나요?<br>
                                우리가 매일 사용하는 침구지만 조그마한 불편은 감수하며 사용하기 일쑤입니다.<br>
                                일반적인 수선 조차 소비자가 스스로 해결 해야 하는 것이 현실이죠. 이러한 불편함에서 시작한 고민.<br>
                                전문적인 곳에서 수선을 원하시는 분들을 위해 LIFELIKE가 시작합니다.<br>
                                전문 상담사의 1:1 맞춤 관리, 지금 누려보세요.
                            </p>

                        </div>
                        <div class="rental-box-wrap repair-box-wrap">
                            <ul class="clearfix">
                                <li>
                                    <div>
                                        <span>선 상담 후 결제<span>1:1 관리</span></span>
                                    </div>
                                    <p>
                                        전문 상담사가<br>
                                        수선 내용 안내
                                    </p>
                                </li>
                                <li>
                                    <div>
                                        <span>수선 기간<span>평균 7일</span></span>
                                    </div>
                                    <p>
                                        일반 수선은 평균 7일,<br>
                                        난이도에 따라 15일 소요.
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="table-add-text">
                            <p>Q 01. LIFELKE 제품만 수선하게 된 이유</p>
                            <ul>
                                <li class="list-style-none">같은 아이템이더라도 제조 방식이 다를 수 있으며 그에 따른 책임소지의 문제가 생길 수 있습니다.<br>따라서 LIFELIKE 제품만 전문적으로 수선하겠습니다.</li>
                            </ul> 
                        </div>
                        <div class="table-add-text">
                            <p>Q 02. 수선이 불가한 경우</p>
                            <ul>
                                <li>제품의 훼손 정도가 심각한 경우</li>
                                <li>타사 제품의 경우</li>
                            </ul> 
                        </div>
                    </div>
                </div>
                <div class="repair-content-02">
                    <div class="inner">
                        <p class="content-title title-deco">
                            수선 요금 안내
                        </p>
                        <div class="clearfix">
                            <div class="washing-table storage-table left">
                                <p class="table-title">구스다운 충전(100g당)</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="130px">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>폴란드산 구스다운 90%</th>
                                            <td>119,000</td>
                                        </tr>
                                        <tr>
                                            <th>헝가리산 구스다운 90%</th>
                                            <td>72,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="washing-table storage-table right">
                                <p class="table-title">사이즈 수선(줄임)</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="130px">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>이불 커버</th>
                                            <td>15,000</td>
                                        </tr>
                                        <tr>
                                            <th>누비 이불 커버</th>
                                            <td>20,000</td>
                                        </tr>
                                        <tr>
                                            <th>매트리스 커버</th>
                                            <td>10,000</td>
                                        </tr>
                                        <tr>
                                            <th>패드</th>
                                            <td>7,000</td>
                                        </tr>
                                        <tr>
                                            <th>차렵 이불</th>
                                            <td>15,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="clearfix mt100">
                            <div class="washing-table storage-table left">
                                <p class="table-title">솜 샤시(누비)</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="130px">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>홑겹 이불 커버 → 누비 이불 커버</th>
                                            <td>(S)119,000</td>
                                        </tr>
                                        <tr>
                                            <th>홑겹 매트리스 커버 → 누비 매트리스 커버</th>
                                            <td>(Q)72,000</td>
                                        </tr>
                                        <tr>
                                            <th>홑겹 베개 커버 → 누비 베개 커버</th>
                                            <td>15,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="washing-table storage-table right">
                                <p class="table-title">밴드 교체</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="130px">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>패드(모서리 4면)</th>
                                            <td>20,000</td>
                                        </tr>
                                        <tr>
                                            <th>매트리스 커버(양 쪽 2개)</th>
                                            <td>10,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="clearfix mt100">
                            <div class="washing-table storage-table left">
                                <p class="table-title">지퍼 수선</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="130px">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th>대품(이불, 이불커버)</th>
                                            <td>7,000</td>
                                        </tr>
                                        <tr>
                                            <th>베개</th>
                                            <td>15,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="washing-table storage-table right">
                                <p class="table-title">매트리스 커버 수선</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="140px">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th class="ls">프릴단 전체 교체 ~ 10cm, 20cm 길이 수선</th>
                                            <td>27,000 ~ 32,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-add-text">
                            <ul>
                                <li>위 요금표는 안내를 위한 것으로, 전문 상담사와 통화 후 정확한 요금 안내가 이루어집니다.</li>
                                <li>라이프라이크 제품에 한하여 서비스 이용 가능 (단위 : 원)</li>
                            </ul> 
                        </div>
                    </div>
                </div>

                <div class="repair-content-03">
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
                                                <p>수선<br>서비스 신청</p>
                                            </td>
                                            <td>
                                                <span>STEP 02.</span>
                                                <p>전문 상담사<br>1:1 통화</p>
                                            </td>
                                            <td>
                                                <span>STEP 03.</span>
                                                <p>제품 수거<br>및 검수</p>
                                            </td>
                                            <td>
                                                <span>STEP 04.</span>
                                                <p>수선</p>
                                            </td>
                                            <td>
                                                <span>STEP 05.</span>
                                                <p>배송 완료</p>
                                            </td>
                                            <td>
                                                <span>STEP 06.</span>
                                                <p>제품 사용</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <a href="care.php"><div class="rental-service-btn">
                            <button>서비스 신청</button>
                        </div></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- //container -->



</div>
<?php
include_once('./_tail.php');
?>