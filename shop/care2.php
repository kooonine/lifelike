<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/care2.php');
    return;
}

$g5['title'] = '세탁보관서비스';
include_once('./_head.php');

?>

<!-- container -->
        <div id="container">
            <div class="content_wrap">
                <div class="service-visual service-visual-04">
                    <div class="inner">
                        <span class="add-text">경험해보지 못했던 여유로움</span>

                        <h2>세탁<span class="text-dot"></span>보관 서비스</h2>
                    </div>
                </div>
                <div class="storage-content-01">
                    <div class="inner">
                        <div class="rental-text-lt title-deco">
                            <p>
                                내 집이 아닌 곳에 보관한다는 일은 굉장히 낯설게 느껴집니다.<br>
                                중량과 부피가 클수록, 계절에 따라 사용하는 침구가 다양해질수록<br>
                                집안 한 켠을 차지하고 있는 침구류는 공간을 비좁게 만듭니다.<br>
                                LIFELIKE는 좁아지는 공간에 대한 고민을 해결하고자 침구류 세탁 · 보관 서비스를 런칭 합니다.<br>
                                지금까지 경험해보지 못했던, 공간 활용의 즐거움을 느껴 보세요.
                            </p>

                        </div>
                        <div class="rental-box-wrap">
                            <ul class="clearfix">
                                <li>
                                    <div>
                                        <span>침구 수거 박스<span>전용 박스</span></span>
                                    </div>
                                    <p>
                                        서비스 신청 시<br>
                                        전용 박스 배송
                                    </p>
                                </li>
                                <li>
                                    <div>
                                        <span>보관 기간<span>최대 1년</span></span>
                                    </div>
                                    <p>
                                        최대 1년까지<br>
                                        경제적인 장기보관
                                    </p>
                                </li>
                                <li>
                                    <div>
                                        <span>세탁 포함가<span>웻 크리닝</span></span>
                                    </div>
                                    <p>
                                        필수적으로<br>
                                        세탁진행 후 보관
                                    </p>
                                </li>
                                <li>
                                    <div>
                                        <span>고객 식별 카드<span>RFID CARD</span></span>
                                    </div>
                                    <p>
                                        분실 우려 없는<br>
                                        개별 제품 관리
                                    </p>
                                </li>
                                <li>
                                    <div>
                                        <span>보관 시스템 제휴<span>CJ 대한통운</span></span>
                                    </div>
                                    <p>
                                        보관에 최적화 된<br>
                                        CJ 물류센터 제휴
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="washing-content-02">
                    <div class="inner">
                        <p class="content-title title-deco">
                            세탁<span></span>보관 요금 안내
                        </p>
                        <div class="clearfix">
                            <div class="washing-table storage-table left">
                                <p class="table-title">S</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="140px">
                                        <col width="140px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>1개월</th>
                                            <th>12개월</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>이불솜</th>
                                            <td>60,000</td>
                                            <td>93,000</td>
                                        </tr>
                                        <tr>
                                            <th>차렵 구스 이불</th>
                                            <td>46,700</td>
                                            <td>65,400</td>
                                        </tr>
                                        <tr>
                                            <th>페더베드 및 토퍼류</th>
                                            <td>90,000</td>
                                            <td>123,000</td>
                                        </tr>
                                        <tr>
                                            <th>필로우</th>
                                            <td>32,000</td>
                                            <td>65,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="washing-table storage-table right">
                                <p class="table-title">Q</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="140px">
                                        <col width="140px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>1개월</th>
                                            <th>12개월</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>이불솜</th>
                                            <td>65,000</td>
                                            <td>98,000</td>
                                        </tr>
                                        <tr>
                                            <th>차렵 구스 이불</th>
                                            <td>51,700</td>
                                            <td>70,400</td>
                                        </tr>
                                        <tr>
                                            <th>페더베드 및 토퍼류</th>
                                            <td>100,000</td>
                                            <td>133,000</td>
                                        </tr>
                                        <tr>
                                            <th>필로우</th>
                                            <td colspan="2">최대 12개월 보관시 평균 <span>37%</span> 적립</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="clearfix mt100">
                            <div class="washing-table storage-table left">
                                <p class="table-title">K</p>
                                <table>
                                    <colgroup>
                                        <col width="*">
                                        <col width="140px">
                                        <col width="140px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>1개월</th>
                                            <th>12개월</th>
                                        </tr>                                        
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>이불솜</th>
                                            <td>70,000</td>
                                            <td>103,000</td>
                                        </tr>
                                        <tr>
                                            <th>차렵 구스 이불</th>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>페더베드 및 토퍼류</th>
                                            <td>105,000</td>
                                            <td>138,000</td>
                                        </tr>
                                        <tr>
                                            <th>필로우</th>
                                            <td colspan="2">최대 12개월 보관시 평균 <span>37%</span> 적립</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-add-text">
                            <ul>
                                <li>라이프라이크 제품에 한하여 서비스 이용 가능 (단위 : 원)</li>
                                <li>해당 서비스는 이불솜/차렵이불/베개솜/토퍼류에 한해 가능합니다.</li>
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
                                                <p>보관서비스 신청<br>(일자 선택 가능)</p>
                                            </td>
                                            <td>
                                                <span>STEP 02.</span>
                                                <p>전용 수거<br>박스 배송</p>
                                            </td>
                                            <td>
                                                <span>STEP 03.</span>
                                                <p>제품 수거<br>및 검수</p>
                                            </td>
                                            <td>
                                                <span>STEP 04.</span>
                                                <p>세탁 후<br>보관</p>
                                            </td>
                                            <td>
                                                <span>STEP 05.</span>
                                                <p>보관 찾기<br>신청</p>
                                            </td>
                                            <td>
                                                <span>STEP 06.</span>
                                                <p>배송완료</p>
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