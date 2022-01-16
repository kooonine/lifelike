<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH.'/settle_lg3.inc.php');

// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');
?>

<?php
// 결제대행사별 코드 include (결제등록 필드)
require_once(G5_SHOP_PATH.'/lg3/orderform.1.php');
?>

<?php
// 주문상품
$sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '{$od['od_id']}' ";
$row = sql_fetch($sql);

$good_info = '';
$comm_tax_mny = 0; // 과세금액
$comm_vat_mny = 0; // 부가세
$comm_free_mny = 0; // 면세금액
$tot_tax_mny = 0;

$image_width = 150;
$image_height = 150;
$image = get_it_image($row['it_id'], $image_width, $image_height);
$it_name = stripslashes($row['it_name']);

$goods = $goods_it_id = "";

$sell_price = $od['od_cart_price'];
$send_cost = $od['od_send_cost']; //배송비
$title = "수선비용 결제";

$tot_sell_price = $sell_price;
//$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비

$tot_price = $od['od_misu'];

$goods = preg_replace("/\?|\'|\"|\||\,|\&|\;/", "", $row['it_name']).' '.$title;
$goods_it_id = $row['it_id'];
?>

<!-- container -->
<div id="container">

	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span><?php echo $title?></span></h1>
		<a href="#" class="btn_back"><span class="blind">뒤로가기</span></a>
		<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
	</div>
	<!-- // lnb -->

	<div class="content shop sub">
	<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<div class="title_bar ">
			<h3 class="g_title_01">제품 정보</h3>
		</div>
		<div class="orderwrap">
            <div class="order_cont">
                <div class="body">
                    <div class="cont right_cont">
                        <div class="photo"><?php echo $image; ?></div>
                        <div class="info">
                            <strong><?php echo $it_name; ?></strong>
                            <p><span class="txt">옵션</span><span class="point_black"><strong class="bold"><?php echo $row['ct_option']; ?></strong></span></p>
                        </div>
                    </div>
                </div>
            </div>
		</div>
        <input type="hidden" name="it_id[]"    value="<?php echo $row['it_id']; ?>">
        <input type="hidden" name="it_name[]"  value="<?php echo get_text($row['it_name']); ?>">
        <input type="hidden" name="it_price[]" value="<?php echo $sell_price; ?>">
        <input type="hidden" name="cp_id[]" value="">
        <input type="hidden" name="cp_price[]" value="0">

        <input type="hidden" name="od_price"    value="<?php echo $tot_sell_price; ?>">
        <input type="hidden" name="org_od_price"    value="<?php echo $tot_sell_price; ?>">
        <input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
        <input type="hidden" name="od_send_cost2" value="0">
        <input type="hidden" name="item_coupon" value="0">
        <input type="hidden" name="od_coupon" value="0">
        <input type="hidden" name="od_send_coupon" value="0">
        <input type="hidden" name="od_goods_name" value="<?php echo $goods; ?>">
        <input type="hidden" name="od_type" value="<?php echo $od_type?>" />
        <input type="hidden" name="laundry_price" value="<?php echo $laundry_price?>" />
        <input type="hidden" name="keep_price" value="<?php echo $keep_price?>" />
        <input type="hidden" name="ct_free_laundry_YN" value="<?php echo $ct_free_laundry_YN?>">
        <input type="hidden" name="ct_free_laundry_delivery_price" value="<?php echo $ct_free_laundry_delivery_price?>">

        <?php
        // 결제대행사별 코드 include (결제대행사 정보 필드)
        require_once(G5_SHOP_PATH.'/lg3/orderform.2.php');
        ?>
    </div>

	<div class="grid border_box gray_box">
    	<div class="box">
    		<div class="box">
    			<div class="title_bar none">
    				<h2 class="g_title_01">신청 제품 확인</span></h2>
    			</div>
    			<div class="border_box order_list reverse white_box result_right">
        			<ul>
        				<li>
        					<span class="item" style="width:250px;">* <?php echo $row['ct_option']; ?> * 1개 수선 </span>
        					<strong class="result"><?php echo number_format($sell_price)." 원"?></strong>
        				</li>
        				<!-- li>
        					<span class="item">* 택배비</span>
        					<strong class="result"><?php echo number_format($send_cost)?> 원</strong>
        				</li -->
        				<li>
        					<span class="item blind"></span>
        					<strong class="result bold point" id="ct_tot_price"><?php echo number_format($sell_price )?> 원</strong>
        				</li>
        			</ul>
        		</div>
        	</div>
		</div>
	</div>

	<div class="grid bg_none">
    	<div class="order_title">
            <span class="item"><?php echo $od_type_name?> 요청서</span>
        </div>
        <div class="order_list border_box">
            <ul>
                <!-- li>
                    <span class="item">수거일자 선택</span>
                    <strong class="result">
                       <?php echo $od['od_hope_date']?>
                    </strong>
                </li -->
                <li>
                    <span class="item">요청사항</span>
                    <strong class="result">
                       <?php echo nl2br($od['cust_memo']) ?>
                    </strong>
                </li>
            </ul>
            <div class="photo">
                <ul class="list">
                    <?php
                    $cust_file = json_decode($od['cust_file'], true);
                    for ($i = 0; $i < count($cust_file); $i++) {

                        $imgL = G5_DATA_URL.'/file/order/'.$od['od_id'].'/'.$cust_file[$i]['file'];

                        if ( preg_match("/\.(mp4|mov|avi)$/i", $cust_file[$i]['file'])){
                            echo "<li><video controls width='150' height='150' style='vertical-align:top;' >
                                  	<source src='$imgL' type='video/mp4' width='150' height='150' >
                                    </video></li>";
                        } else {
                            echo '<li><img src="'.$imgL.'" width="150px"></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

	<div class="grid">
        <div class="title_bar none">
            <h3 class="g_title_01">결제 수단 선택</h3>
        </div>
        <div class="border_box">
        <?php
        if (!$default['de_card_point'])
            echo '<p id="sod_frm_pt_alert"><strong>무통장입금</strong> 이외의 결제 수단으로 결제하시는 경우 적립금를 적립해드리지 않습니다.</p>';

        $multi_settle = 0;
        $checked = '';

        $escrow_title = "";
        if ($default['de_escrow_use']) {
            $escrow_title = "에스크로 ";
        }

        if ($is_kakaopay_use || $default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay()) {
            echo '<div class="order_list button_choice inline black"><ul class="onoff">';
        }

        // 신용카드 사용
        if ($default['de_card_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_card").click();\'>신용카드</a></li><input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 카카오페이
        if($is_kakaopay_use) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_kakaopay").click();\'>카카오페이</a></li><input type="radio" id="od_settle_kakaopay" name="od_settle_case" hidden value="KAKAOPAY" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 가상계좌 사용
        if ($default['de_vbank_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_vbank").click();\'>'.$escrow_title.'가상계좌</a></li><input type="radio" id="od_settle_vbank" name="od_settle_case" hidden value="가상계좌" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 계좌이체 사용
        if ($default['de_iche_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_iche").click();\'>'.$escrow_title.'계좌이체</a></li><input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 휴대전화 사용
        if ($default['de_hp_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_hp").click();\'>'.$escrow_title.'휴대전화</a></li><input type="radio" id="od_settle_hp" name="od_settle_case" hidden value="휴대전화" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // PG 간편결제
        if($default['de_easy_pay_use']) {
            switch($default['de_pg_service']) {
                case 'lg':
                    $pg_easy_pay_name = 'PAYNOW';
                    break;
                case 'inicis':
                    $pg_easy_pay_name = 'KPAY';
                    break;
                default:
                    $pg_easy_pay_name = 'PAYCO';
                    break;
            }

            $multi_settle++;
            echo '<li><input type="radio" id="od_settle_easy_pay" name="od_settle_case" value="간편결제" '.$checked.'> <label for="od_settle_easy_pay" class="'.$pg_easy_pay_name.' lb_icon">'.$pg_easy_pay_name.'</label></li>'.PHP_EOL;
            $checked = '';
        }

        //이니시스 삼성페이
        if($default['de_samsung_pay_use']) {
            echo '<li><input type="radio" id="od_settle_samsungpay" data-case="samsungpay" name="od_settle_case" value="삼성페이" '.$checked.'> <label for="od_settle_samsungpay" class="samsung_pay lb_icon">삼성페이</label></li>'.PHP_EOL;
            $checked = '';
        }

        //이니시스 Lpay
        if($default['de_inicis_lpay_use']) {
            echo '<li><input type="radio" id="od_settle_inicislpay" data-case="lpay" name="od_settle_case" value="lpay" '.$checked.'> <label for="od_settle_inicislpay" class="inicis_lpay">L.pay</label></li>'.PHP_EOL;
            $checked = '';
        }

        echo '</ul>';


        if ($default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay() ) {
            echo '</div>';
        }

        if ($multi_settle == 0)
            echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
        ?>
    	</div>
    
    	<div class="inp_wrap">
    		<span class="chk check">
    			<input type="checkbox" id="chk_user_privacy" name="chk_user_privacy" required="required">
    			<label for="chk_user_privacy">개인정보 수집 • 이용 동의<span>(필수)</span></label>
    		</span>
    		<button type="button" class="btn floatR arrow_r_green" id="btn_user_privacy" >전문보기</button>
    	</div>
		<hr class="full_line">

		<div class="page_title">
			<p class="g_title_03">위 주문 내용을 확인하였으며, 결제에 동의 합니다.</p>
		</div>
    </div>

	<div class="grid">
		<div class="order_info order_list border_box">
			<ul>
				<li>
				<span class="item">총 결제 금액<br>(배송료 포함)</span>
				<strong class="result"><em id="od_tot_price" class="point"><?php echo number_format($tot_price); ?></em> 원</strong>
				</li>
			</ul>
        </div>
	</div>

	<div class="grid">
        <?php
        // 결제대행사별 코드 include (결제대행사 정보 필드 및 주분버튼)
        require_once(G5_SHOP_PATH.'/lg3/orderform.3.php');
        ?>
	</div>

    <div id="show_progress" style="display:none;">
        <img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt="">
        <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>

    <input type="hidden" name="od_name" value="<?php echo get_text($member['mb_name']); ?>" id="od_name" >
    <input type="hidden" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" >
    <input type="hidden" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" >
    <input type="hidden" name="od_email" value="<?php echo $member['mb_email']; ?>" id="od_email">
    <input type="hidden" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
    <input type="hidden" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" >
    <input type="hidden" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" >
	<input type="hidden" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" >
    <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">

    <input type="hidden" name="od_b_name" value="<?php echo $od['od_b_name']; ?>">
    <input type="hidden" name="od_b_hp" value="<?php echo $od['od_b_hp']; ?>">
    <input type="hidden" name="od_b_tel" value="<?php echo $od['od_b_tel']; ?>">
    <input type="hidden" name="od_b_zip" value="<?php echo $od['od_b_zip']; ?>">
    <input type="hidden" name="od_b_addr1" value="<?php echo $od['od_b_addr1']; ?>">
    <input type="hidden" name="od_b_addr2" value="<?php echo $od['od_b_addr2']; ?>">
    <input type="hidden" name="od_b_addr3" value="<?php echo $od['od_b_addr3']; ?>">
    <input type="hidden" name="od_b_addr_jibeon" value="<?php echo $od['od_b_addr_jibeon']; ?>">
    </form>

    <?php
    if ($default['de_escrow_use']) {
        // 결제대행사별 코드 include (에스크로 안내)
        require_once(G5_SHOP_PATH.'/lg3/orderform.4.php');
    }
    ?>

</div>
</div>


<!-- popup -->
<section class="popup_container layer" id="popup_container" style="display: none">
	<div class="inner_layer" style="top:10%">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01" id='popuptitle'><?=$title; ?></h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box" id='popupbody1'><?=$config['cf_user_privacy'] ?></div>				
			</div>
			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree"><span>동의합니다</span></button></div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a class="btn_closed" onclick="$('#popup_container').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->

<!-- popup -->
<section class="popup_container layer" style="display: none" id="pricetable">
	<div class="inner_layer" style="top:0px">

	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>수선 단가표</span></h1>
		<a href="#" class="btn_closed" onclick="$('#pricetable').css('display','none');"><span class="blind">닫기</span></a>
	</div>
	<!-- // lnb -->

	<div class="content shop sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
                <div class="gray_box pad15">
                    <p>*아래 요금표는 안내를 위한 것으로, 전문 상담사와 통화 후 정확한 요금 안내가 이루어집니다.</p>
                    <p>*수선 서비스는 라이프라이크 제품에 한하여 이용이 가능합니다.</p>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">구스다운 충전 (100g당)</span>
                    <strong class="result"><em class="point">72,000원~</em></strong>
                </div>
                <div class="border_box order_list ">
                    <ul>
                        <li>
                            <span class="item">폴란드산 구스다운 90%</span>
                            <strong class="result">119,000원</strong>
                        </li>
                        <li>
                            <span class="item">헝가리산 구스다운 90%</span>
                            <strong class="result">72,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">사이즈 수선(줄임)</span>
                    <strong class="result"><em class="point">7,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">이불 커버</span>
                            <strong class="result">15,000원</strong>
                        </li>
                        <li>
                            <span class="item">누비 이불 커버</span>
                            <strong class="result">20,000원</strong>
                        </li>
                        <li>
                            <span class="item">매트리스 커버</span>
                            <strong class="result">10,000원</strong>
                        </li>
                        <li>
                            <span class="item">패드</span>
                            <strong class="result">10,000원</strong>
                        </li>
                        <li>
                            <span class="item">차렵 이불</span>
                            <strong class="result">15,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">솜 샤시(누비)</span>
                    <strong class="result"><em class="point">10,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">홑겹 이불 커버 → 누비 이불 커버</span>
                            <strong class="result">S → 40,000원</strong>
                        </li>
                        <li>
                            <span class="item">홑겹 매트리스 커버 → 누비 매트리스 커버</span>
                            <strong class="result">Q → 50,000원</strong>
                        </li>
                        <li>
                            <span class="item">홑겹 베개 커버 → 누비 베개 커버</span>
                            <strong class="result">50,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">밴드 교체</span>
                    <strong class="result"><em class="point">8,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">패드(모서리 4면)</span>
                            <strong class="result">8,000원</strong>
                        </li>
                        <li>
                            <span class="item">매트리스 커버(양 쪽 2개)</span>
                            <strong class="result">12,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">지퍼 수선</span>
                    <strong class="result"><em class="point">5,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">대품(이불, 이불커버)</span>
                            <strong class="result">8,000원</strong>
                        </li>
                        <li>
                            <span class="item">베개</span>
                            <strong class="result">5,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">매트리스 커버 수선</span>
                    <strong class="result"><em class="point">27,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">프릴단 전체 교체 ~10cm, 20cm 길이 수선</span>
                            <strong class="result">27,000원 ~ 32,000원</strong>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="grid bg_none">
                <div class="toggle inquiry_toggle">
                    <div class="toggle_group type2 opened">
                        <div class="title">
                            <h3 class="tit ellipsis">참조사항</h3>
                        </div>
                        <div class="cont">
                            <div class="white_box info_box">
                                <p class="tit">* 베이직라인 이불속통 (중량추가주입)</p>
                                <ul class="hyphen">
                                    <li>원산지별 100g 당 가격</li>
                                    <li>속통류는 베이직라인만 가능</li>
                                    <li>원단 덧댐 수선은 별도문의</li>
                                </ul>
                                <p class="tit">* 사이즈 수선 (줄임)</p>
                                <ul class="hyphen">
                                    <li>지퍼수선이 필요할경우 추가</li>
                                </ul>
                                <p class="tit">* 사이즈 수선 (늘임)</p>
                                <ul class="hyphen">
                                    <li>정상 런제품 가능하며,<br>원자재 보유제품이어야 함</li>
                                </ul>
                                <p class="tit">* 매커프릴수선 교체 / 높이연폭</p>
                                <ul class="hyphen">
                                    <li>프릴단 교체(완성 35cm)</li>
                                    <li>기존끝에서 추가(35+20)</li>
                                    <li>기존끝에서 추가(35+10)</li>
                                </ul>
                                <p class="tit">* 랍바수선 (면)</p>
                                <ul class="hyphen">
                                    <li>정상제품 가능하며, 원자재 보유제품이어야 함</li>
                                </ul>
                                <p class="tit">* 앞, 뒤 판갈이</p>
                                <ul class="hyphen">
                                    <li>패드 판갈이 불가능(짜집기, 덧뎀)</li>
                                </ul>
                                <p class="tit">* 손샤시</p>
                                <ul class="hyphen">
                                    <li>기존 사용솜 이외의 신청은 불가</li>
                                </ul>
                                <p class="tit">* 밴드교체</p>
                                <ul class="hyphen">
                                    <li>해당없음</li>
                                </ul>
                                <p class="tit">* 지퍼수선</p>
                                <ul class="hyphen">
                                    <li>옥매트 ㄷ자형 : 메모리폼 커버</li>
                                </ul>
                                <p class="tit">* 삥줄(3면)</p>
                                <ul class="hyphen">
                                    <li>해당없음</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<!-- 컨텐츠 종료 -->
	</div>
</section>
<!-- //popup -->
<script src="<?php echo G5_JS_URL?>/shop.order.js"></script>
<script>
var zipcode = "";

$(function() {

	$('#btn_user_privacy').click(function () {
		$("#popuptitle").text("개인정보 수집 • 이용 동의");
		$("#popup_container").css("display","");
	});

	$('#agree').click(function () {
		$('#chk_user_privacy').prop("checked",true);
		$("#popup_container").css("display","none");
	});


});

var temp_point = 0;
function forderform_check(f)
{
    errmsg = "";
    errfld = "";
    var deffld = "";

    var od_settle_bank = document.getElementById("od_settle_bank");
    if (od_settle_bank) {
        if (od_settle_bank.checked) {
            check_field(f.od_bank_account, "계좌번호를 선택하세요.");
            check_field(f.od_deposit_name, "입금자명을 입력하세요.");
        }
    }

    // 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
    f.od_send_cost.value = parseInt(f.od_send_cost.value);

    if (errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    var settle_case = document.getElementsByName("od_settle_case");
    var settle_check = false;
    var settle_method = "";
    for (i=0; i<settle_case.length; i++)
    {
        if (settle_case[i].checked)
        {
            settle_check = true;
            settle_method = settle_case[i].value;
            break;
        }
    }
    if (!settle_check)
    {
        alert("결제방식을 선택하십시오.");
        return false;
    }

	if (!$("#chk_user_privacy").is(":checked"))
	{
        alert("개인정보 수집 · 이용 동의에 동의하셔야 결제 하실 수 있습니다.");
		return false;
	}

    var od_price = parseInt(f.od_price.value);
    var send_cost = parseInt(f.od_send_cost.value);

    var tot_price = od_price + send_cost;

    if (document.getElementById("od_settle_iche")) {
        if (document.getElementById("od_settle_iche").checked) {
            if (tot_price < 150) {
                alert("계좌이체는 150원 이상 결제가 가능합니다.");
                return false;
            }
        }
    }

    if (document.getElementById("od_settle_card")) {
        if (document.getElementById("od_settle_card").checked) {
            if (tot_price < 1000) {
                alert("신용카드는 1000원 이상 결제가 가능합니다.");
                return false;
            }
        }
    }

    if (document.getElementById("od_settle_hp")) {
        if (document.getElementById("od_settle_hp").checked) {
            if (tot_price < 350) {
                alert("휴대전화은 350원 이상 결제가 가능합니다.");
                return false;
            }
        }
    }

    // 카카오페이 지불
    if(settle_method == "KAKAOPAY") {
        <?php if($default['de_tax_flag_use']) { ?>
        f.SupplyAmt.value = parseInt(f.comm_tax_mny.value) + parseInt(f.comm_free_mny.value);
        f.GoodsVat.value  = parseInt(f.comm_vat_mny.value);
        <?php } ?>
        getTxnId(f);
        return false;
    }

    var form_order_method = '';

    if( settle_method == "lpay" ){      //이니시스 L.pay 이면 ( 이니시스의 삼성페이는 모바일에서만 단독실행 가능함 )
        form_order_method = 'samsungpay';
    }

    if( jQuery(f).triggerHandler("form_sumbit_order_"+form_order_method) !== false ) {

        f.LGD_EASYPAY_ONLY.value = "";
        if(typeof f.LGD_CUSTOM_USABLEPAY === "undefined") {
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "LGD_CUSTOM_USABLEPAY");
            input.setAttribute("value", "");
            f.LGD_EASYPAY_ONLY.parentNode.insertBefore(input, f.LGD_EASYPAY_ONLY);
        }

        switch(settle_method)
        {
            case "계좌이체":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0030";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0030";
                break;
            case "가상계좌":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0040";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0040";
                break;
            case "휴대전화":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0060";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0060";
                break;
            case "신용카드":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0010";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0010";
                break;
            case "간편결제":
                var elm = f.LGD_CUSTOM_USABLEPAY;
                if(elm.parentNode)
                    elm.parentNode.removeChild(elm);
                f.LGD_EASYPAY_ONLY.value = "PAYNOW";
                break;
            default:
                f.LGD_CUSTOM_FIRSTPAY.value = "무통장";
                break;
        }
        f.LGD_BUYER.value = f.od_name.value;
        f.LGD_BUYEREMAIL.value = f.od_email.value;
        f.LGD_BUYERPHONE.value = f.od_hp.value;
        f.LGD_AMOUNT.value = f.good_mny.value;
        f.LGD_RECEIVER.value = f.od_b_name.value;
        f.LGD_RECEIVERPHONE.value = f.od_b_hp.value;
        <?php if($default['de_escrow_use']) { ?>
        f.LGD_ESCROW_ZIPCODE.value = f.od_b_zip.value;
        f.LGD_ESCROW_ADDRESS1.value = f.od_b_addr1.value;
        f.LGD_ESCROW_ADDRESS2.value = f.od_b_addr2.value;
        f.LGD_ESCROW_BUYERPHONE.value = f.od_hp.value;
        <?php } ?>
        <?php if($default['de_tax_flag_use']) { ?>
        f.LGD_TAXFREEAMOUNT.value = f.comm_free_mny.value;
        <?php } ?>

        if(f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
            launchCrossPlatform(f);
        } else {
            f.submit();
        }
    }

}

</script>
