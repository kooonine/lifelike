<?php
ob_start();
$g5_title = "회원등급";
include_once G5_LAYOUT_PATH . "/nav.member.php";

?>
<style>
    ol, ul {
	list-style: none;
    }
    /* 회원등급 */
    .member-benefits-wrap { max-width: 1420px; width: 100%; position: relative; margin: 0 auto; }
    .member-benefits-wrap .my-class-wrap { position: relative; }
    .member-benefits-wrap .my-class-wrap:after { content: ''; display: block; clear: both; }
    .member-benefits-wrap .my-class-wrap .boxs { width: 500px; position: relative; text-align: center; border: 1px solid #ddd; float: left; }
    .member-benefits-wrap .my-class-wrap .boxs .title { font-size: 20px; color: #fff; font-weight: 600; background: #333; padding: 7px 0; line-height: 1; }
    .member-benefits-wrap .my-class-wrap .boxs .ico-disc { position: relative; }
    .member-benefits-wrap .my-class-wrap .boxs .ico-disc:after { content: ''; display: block; clear: both; }

    .member-benefits-wrap .my-class-wrap .boxs .ico { position: relative; width: 40%; padding: 20px 0; float: left; height: 125px; }
    .member-benefits-wrap .my-class-wrap .boxs .ico img { vertical-align: middle; }
    .member-benefits-wrap .my-class-wrap .boxs .disc { position: relative; width: 60%; text-align: left; font-size: 20px;color: #222; line-height: 1.3; float: left; background: #f1f1f1; height: 125px; vertical-align: middle; box-sizing: border-box; padding: 15px; }
    .member-benefits-wrap .my-class-wrap .boxs .disc p { line-height: 30px; }
    .member-benefits-wrap .my-class-wrap .next-arrow { position: relative; float: left; height: 161px; margin: 0 83px; }
    .member-benefits-wrap .my-class-wrap .next-arrow img { position: relative; top: 45%; }
    .member-benefits-wrap .subs { font-size: 16px; color: #868686; text-align: left; padding: 20px 0; border-top: 1px solid 
    #ddd; margin-top: 35px; }
    .member-info-wrap { position: relative; margin-top: 60px; }
    .member-info-wrap h3 { font-size: 25px; color: #545863; font-weight: 600; margin-bottom: 30px; }
    .member-info-wrap table { width: 100%; position: relative; }
    .member-info-wrap table thead tr { border-top: 3px solid #545863; border-bottom: 2px solid #545863; }
    .member-info-wrap table thead tr td { border: 1px solid #ddd; vertical-align: middle; text-align: center; line-height: 1.2; padding: 20px 0; }
    .member-info-wrap table thead tr td .txt2 { font-size: 12px; }
    .member-info-wrap table tbody tr td { border: 1px solid #ddd; vertical-align: middle; text-align: center; line-height: 1.4; }
    .member-info-wrap table tbody tr td.td-ico { padding: 20px 0; }
    .member-info-wrap .member-info-li { position: relative; margin-top: 40px;}
    .member-info-wrap .member-info-li li { font-size: 16px; color: #868686; line-height: 1.5; }
    .member-content-section tr > td { padding-left: 0 !important; }
    .member-info-wrap.tb-pc { display: block; }
    .member-info-wrap.tb-mo { display: none; }
    @media screen and (max-width:1365px){
    /* 회원등급 */
    #member-content-wrapper { padding: 0 10px; }
    .member-benefits-wrap { max-width: 100%; }
    .member-benefits-wrap .my-class-wrap .boxs { width: 45%; position: relative; text-align: center; border: 1px solid #ddd; float: left; }
    .member-benefits-wrap .my-class-wrap .boxs .title { font-size: 14px; color: #fff; font-weight: 600; background: #333; padding: 7px 0; line-height: 1; }
    

    .member-benefits-wrap .my-class-wrap .boxs .ico { position: relative; width: 100%; padding: 20px 0; float: none; height: 125px; }
    .member-benefits-wrap .my-class-wrap .boxs .disc { position: relative; width: 100%; text-align: center; font-size: 12px; line-height: 1.1; float: none; padding: 10px; height: auto; word-spacing: 0px; letter-spacing: -1px;}
    .member-benefits-wrap .my-class-wrap .boxs .disc p { line-height: 1.1; }
    .member-benefits-wrap .my-class-wrap .next-arrow { position: relative; float: left; height: 161px; width: 10%; text-align: center; margin: 0; }
    .member-benefits-wrap .my-class-wrap .next-arrow img { position: relative; top: 45%; }
    .member-benefits-wrap .subs { font-size: 12px; color: #868686; text-align: left; padding: 20px 0; border-top: 1px solid 
    #ddd; margin-top: 35px; }
    .member-info-wrap { position: relative; margin-top: 60px; }
    .member-info-wrap h3 { font-size: 18px; color: #545863; font-weight: 600; margin-bottom: 0; }
    .member-info-wrap table { width: 100%; position: relative; }
    .member-info-wrap table thead tr { border-top: 3px solid #545863; border-bottom: 2px solid #545863; }
    .member-info-wrap table thead tr td { border: 1px solid #ddd; vertical-align: middle; text-align: center; line-height: 1.2; padding: 20px 0; }
    .member-info-wrap table thead tr td .txt2 { font-size: 12px; }
    .member-info-wrap table tbody tr td { border: 1px solid #ddd; vertical-align: middle; text-align: center; line-height: 1.4; height: auto !important; }
    .member-info-wrap table tbody tr.tr-head td { border-top: 2px solid #000; border-bottom: 1px solid #000; }
    .member-info-wrap table tbody tr td.td-ico { padding: 50px 0 20px 0; border: none; }
    .member-info-wrap table tbody tr td.per-cell { padding: 5px 0 !important; background-color: #f1f1f1; }
    .member-info-wrap .member-info-li { position: relative; margin-top: 40px; }
    .member-info-wrap .member-info-li li { font-size: 12px; }
    .member-content-section tr > td { padding-left: 0 !important; }
    .member-info-wrap.tb-mo { display: block; }
    .member-info-wrap.tb-pc { display: none; }


	/* .js_multi_layer .close { right: 4% !important; }
	.title-tab-wrap .subtit { display: none !important; }
    #contents { overflow: hidden; } */
    
    }
    applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
     abbr, acronym, address, big, cite, code,
    del, dfn, em, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, thead, tr, th, td
    {
    	margin: 0;
    	padding: 0;
    	border: 0;
        font-family: 'Noto Sans KR', sans-serif !important;
    	font-size: 100%;
    	font: inherit;
    	vertical-align: baseline;
    }

</style>
<!-- <link rel="stylesheet" href="/css/renewal2107.css"> -->
<!-- <link rel="stylesheet" href="/css/renewal2107_reset.css"> -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400&display=swap" rel="stylesheet">
<div id="member-content-wrapper">
    <div class="member-content-section" style="margin-bottom: 16px;">
    <div class="member-benefits-wrap">
        <div class="my-class-wrap">
            <div class="boxs current-class">
                <div class="title">현재등급</div>
                <div class="ico-disc">
                    <div class="inner">
                        <div class="ico"><img src ='<?= G5_DATA_URL . '/rating/' . $memberTier['icon'] ?>'></div>
                        <div class="disc">
                            <div class="inner"><?= $ratingMonth2 ?> ~ <?= $ratingMonth1 ?><br>(최근 1년동안 구매확정 기준)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="next-arrow"><img src="/img/renewal2107/member/ico_arrow.gif" alt=""></div>
            <div class="boxs next-class">
                <div class="title">다음달 예상 등급</div>
                <div class="ico-disc">
                    <div class="inner">
                        <div class="ico"><img src ='<?= G5_DATA_URL . '/rating/' . $memberTier['icon_next'] ?>'></div>
                        <div class="disc">
                            <div class="inner">현재 구매금액 : <span class="total-price"><?= number_format($memberTier['mb_tier_account'])?>원</span><br>(구매확정 기준)</div>
                            <!-- <p>현재 구매건수 : <span class="number-order">1</span></p> -->
                            <!-- <p>(구매확정 기준)</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<p class="subs">· 예상 등급은 익월 1일에 반영 예정 등급이며, 취소/반품 주문에 따라 실제 등급의 차이가 있을 수 있습니다. </p>
        <div class="member-info-wrap tb-pc">
			<h3>회원등급 산정기준</h3>
			<table cellspacing="0" cellpadding="0">
				<colgroup>
					<col width=""/>
					<col width=""/>
					<col width=""/>
					<col width=""/>
					<col width=""/>
					<col width=""/>
				</colgroup>
				<thead>
					<tr>
						<td>등급</td>
						<td>조건</td>
						<td>종류</td>
						<td>혜택</td>
						<td>수량</td>
						<td><p class="txt1">포인트 적립</p><p class="txt2">(결제금액 기준)</p></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="td-ico" rowspan="2"><img src="/img/renewal2107/member/COMFORT.png" alt=""></td>
						<td rowspan="2">~30만원</td>
						<td>상품</td>
						<td>
							20%할인 (20만원이상 최대 5만원)<br>
							20%할인 (10만원이상 최대 3만원)<br>
							20%할인 (5만원이상 최대 1.5만원)
						</td>
						<td>
							1<br>
							1<br>
							1
						</td>
						<td rowspan="2">3%</td>
					</tr>
					<tr>
						<td>장바구니</td>
						<td>5%할인 (10만원이상 최대 1만원)</td>
						<td>1</td>
					</tr>

					<tr>
						<td class="td-ico"><img src="/img/renewal2107/member/GOLD.png" alt=""></td>
						<td>30~50만원</td>
						<td>상품</td>
						<td>
						20%할인 (20만원이상 최대 4.5만원)<br>
						15%할인 (10만원이상 최대 3만원)
						</td>
						<td>
							1<br>
							1
						</td>
						<td>3%</td>
					</tr>

					<tr>
						<td class="td-ico" rowspan="2"><img src="/img/renewal2107/member/PREMIUM.png" alt=""></td>
						<td rowspan="2">50~100만원</td>
						<td>상품</td>
						<td>
							20%할인 (20만원이상 최대 5만원)<br>
							20%할인 (10만원이상 최대 3만원)
						</td>
						<td>
							2<br>
							2<br>
						</td>
						<td rowspan="2">3%</td>
					</tr>
					<tr>
						<td>장바구니</td>
						<td>3%할인 (10만원이상 최대 5천원)</td>
						<td>1</td>
					</tr>

					<tr>
						<td class="td-ico" rowspan="2"><img src="/img/renewal2107/member/SIGNATURE.png" alt=""></td>
						<td rowspan="2">100만원 이상</td>
						<td>상품</td>
						<td>
							20%할인 (30만원이상 최대 7만원)<br>
							20%할인 (20만원이상 최대 5만원)<br>
							20%할인 (10만원이상 최대 3만원)
						</td>
						<td>
							1<br>
							2<br>
							2
						</td>
						<td rowspan="2">5% 포인트 적립(결제금액 기준)</td>
					</tr>
					<tr>
						<td>장바구니</td>
						<td>5%할인 (10만원이상 최대 1만원)</td>
						<td>1</td>
					</tr>
				</tbody>
			</table>
			<ul class="member-info-li">
				<li>· 최근 1년간 구매금액을 기준으로 매월 1일 등급이 산정됩니다.</li>
				<li>· 구매확정 기준이며 취소/반품 시 등급기준산정에 포함되지 않습니다.</li>
				<li>· 쿠폰은 각 쿠폰의 유효기간까지 사용가능하며 이후 자동소멸됩니다.</li>
			</ul>
        </div>

        <div class="member-info-wrap tb-mo">
			<h3>회원등급 산정기준</h3>
			<table cellspacing="0" cellpadding="0">
				<colgroup>
					<col width=""/>
					<col width=""/>
					<col width=""/>
					<col width=""/>
					<col width=""/>
				</colgroup>
				<tbody>
                    <tr>
                        <td class="td-ico" colspan="5"><img src="/img/renewal2107/member/COMFORT.png" alt=""></td>
                    </tr>
                    <tr class="tr-head">
						<td>조건</td>
						<td>종류</td>
						<td>혜택</td>
						<td>수량</td>
					</tr>
					<tr>
						<td rowspan="2">~30만원</td>
						<td>상품</td>
						<td>
							20%할인 (20만원이상 최대 5만원)<br>
							20%할인 (10만원이상 최대 3만원)<br>
							20%할인 (5만원이상 최대 1.5만원)
						</td>
						<td>
							1<br>
							1<br>
							1
						</td>
					</tr>
					<tr>
						<td>장바구니</td>
						<td>5%할인 (10만원이상 최대 1만원)</td>
						<td>1</td>
					</tr>
                    <tr>
                        <td class="per-cell" colspan="4">3% 포인트 적립(결제금액 기준)</td>
                    </tr>

                    <tr>
                        <td class="td-ico" colspan="5"><img src="/img/renewal2107/member/GOLD.png" alt=""></td>
                    </tr>
                    <tr class="tr-head">
						<td>조건</td>
						<td>종류</td>
						<td>혜택</td>
						<td>수량</td>
					</tr>
					<tr>
						<td>30~50만원</td>
						<td>상품</td>
						<td>
						20%할인 (20만원이상 최대 4.5만원)<br>
						15%할인 (10만원이상 최대 3만원)
						</td>
						<td>
							1<br>
							1
						</td>
					</tr>
                    <tr>
                        <td class="per-cell" colspan="4">3% 포인트 적립(결제금액 기준)</td>
                    </tr>

                    <tr>
                        <td class="td-ico" colspan="5"><img src="/img/renewal2107/member/PREMIUM.png" alt=""></td>
                    </tr>
                    <tr class="tr-head">
						<td>조건</td>
						<td>종류</td>
						<td>혜택</td>
						<td>수량</td>
					</tr>
					<tr>
						<td rowspan="2">50~100만원</td>
						<td>상품</td>
						<td>
							20%할인 (20만원이상 최대 5만원)<br>
							20%할인 (10만원이상 최대 3만원)
						</td>
						<td>
							2<br>
							2
						</td>
					</tr>
					<tr>
						<td>장바구니</td>
						<td>3%할인 (10만원이상 최대 5천원)</td>
						<td>1</td>
					</tr>
                    <tr>
                        <td class="per-cell" colspan="4">3% 포인트 적립(결제금액 기준)</td>
                    </tr>

                    <tr>
                        <td class="td-ico" colspan="5"><img src="/img/renewal2107/member/SIGNATURE.png" alt=""></td>
                    </tr>
                    <tr class="tr-head">
						<td>조건</td>
						<td>종류</td>
						<td>혜택</td>
						<td>수량</td>
					</tr>
					<tr>
						<td rowspan="2">100만원 이상</td>
						<td>상품</td>
						<td>
							20%할인 (30만원이상 최대 7만원)<br>
							20%할인 (20만원이상 최대 5만원)<br>
							20%할인 (10만원이상 최대 3만원)
						</td>
						<td>
							1<br>
							2<br>
							2
						</td>
					</tr>
					<tr>
						<td>장바구니</td>
						<td>5%할인 (10만원이상 최대 1만원)</td>
						<td>1</td>
					</tr>
                    <tr>
                        <td class="per-cell" colspan="5">5% 포인트 적립(결제금액 기준)</td>
                    </tr>
				</tbody>
			</table>
			<ul class="member-info-li">
				<li>· 최근 1년간 구매금액을 기준으로 매월 1일 등급이 산정됩니다.</li>
				<li>· 구매확정 기준이며 취소/반품 시 등급기준산정에 포함되지 않습니다.</li>
				<li>· 쿠폰은 각 쿠폰의 유효기간까지 사용가능하며 이후 자동소멸됩니다.</li>
			</ul>
        </div>
    </div>

    </div>
</div>
<!-- 

<div class="modal fade" id="modal-publish-coupon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; padding: 17px 0; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: 500; color: #090909;">
                test
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: contain; position: absolute; right: 12px; top: 15px; border: 0;"></button>
            </div>
            <div id="modal-publish-coupon-content">
                <div style="height: 20px; font-size: 14px; font-weight: 500; font-style: normal; line-height: normal; color: #565656; text-align: center; margin-top: 27px;">발급받으신 쿠폰번호를 입력해주세요</div>
                <div style="text-align: center; margin-bottom: 80px; padding-top: 4px;">
                    <input type="text" id="coupon-id">
                </div>
            </div>
            <div id="modal-publish-coupon-button" style="text-align: center;">
                <button type="button" id="btn-check-coupon" class="btn-member btn-lg" onclick="checkCouponId()">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-target-coupon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: auto; margin-left: unset; padding: unset !important;">
            <div style="text-align: center; padding: 17px 0; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: 500; color: #090909;">
                <span id="title-target-coupon">특정상품</span>
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: cover; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div id="modal-target-coupon-content">
            </div>
            <div id="modal-target-coupon-desc" style="padding: 10px 20px 40px 20px; font-size: 12px; font-weight: normal; line-height: 1.5; color: #9f9f9f;">
                <span class="dot-desc"></span>상품 혹은 카테고리를 클릭하시면 해당 페이지로 이동됩니다.<br>
                <span class="dot-desc"></span>일부 상품의 경우 쿠폰 할인 적용대상에서 제외됩니다.
            </div>
        </div>
    </div>
</div> -->




<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>