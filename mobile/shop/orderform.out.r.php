<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_MSHOP_PATH.'/settle_lg.inc.php');
require_once(G5_SHOP_PATH.'/settle_kakaopay.inc.php');

if( is_inicis_simple_pay() ){   //이니시스 삼성페이 또는 Lpay 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/incSamsungpayCommon.php');
}

$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)

$multi_company = false;

// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>위약금 납부</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>
<?php 
$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);

$rt_month = $od['rt_month'];
$rt_rental_enddate = date_create($od['rt_rental_startdate']);
date_add($rt_rental_enddate, date_interval_create_from_date_string($rt_month.' months'));
$rt_rental_enddate = date_format($rt_rental_enddate,"Y-m-d");

$goods = "리스위약금";
$tot_price = $od['od_penalty'];


set_session('ss_order_id', $od_id);

$org_od_id = $od_id;
//$od_id = $od_id."9999";
// 결제대행사별 코드 include (결제대행사 정보 필드)
require_once(G5_MSHOP_PATH.'/lg/orderform.1.php');

if( is_inicis_simple_pay() ){   //이니시스 삼성페이 또는 lpay 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/orderform.1.php');
}

if($is_kakaopay_use) {
    require_once(G5_SHOP_PATH.'/kakaopay/orderform.1.php');
}
?>
<!-- //lnb -->
<div class="content comm sub">
<!-- 컨텐츠 시작 -->

	<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
    <input type="hidden" name="od_price"    value="<?php echo $od['od_penalty']; ?>">
    <input type="hidden" name="od_penalty"    value="<?php echo $od['od_penalty']; ?>">
    <input type="hidden" name="od_send_cost2" value="<?php echo $od['od_send_cost2']; ?>">
    <input type="hidden" name="od_goods_name" value="리스위약금">
    <input type="hidden" name="od_type"    value="R">

    <input type="hidden" name="od_name" value="<?php echo get_text($member['mb_name']); ?>" id="od_name" >
    <input type="hidden" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" >
    <input type="hidden" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" >
    <input type="hidden" name="od_email" value="<?php echo get_text($member['mb_email']); ?>" id="od_email" >
    <input type="hidden" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
    <input type="hidden" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" >
    <input type="hidden" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" >
	<input type="hidden" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" >
    <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">

    <input type="hidden" name="od_b_name" id="od_b_name" value="<?php echo $od['od_b_name']?>">
    <input type="hidden" name="od_b_hp" id="od_b_hp" value="<?php echo $od['od_b_hp']?>">
    <input type="hidden" name="od_b_tel" id="od_b_tel" value="<?php echo $od['od_b_tel']?>">
    <input type="hidden" name="od_b_zip" id="od_b_zip" value="<?php echo $od['od_b_zip']?>">
    <input type="hidden" name="od_b_addr1" id="od_b_addr1" value="<?php echo $od['od_b_addr1']?>">
    <input type="hidden" name="od_b_addr2" id="od_b_addr2" value="<?php echo $od['od_b_addr2']?>">
    <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?php echo $od['od_b_addr3']?>">
    <input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?php echo $od['od_b_addr_jibeon']?>">
   
    <div class="grid cont">
        <div class="order_cont">
            <div class="head">
                <span class="category round_green">리스</span>
                <span class="category round_none">해지</span>
            </div>
            <div class="body">
                <div class="order_num">
                    <span class="tit">주문번호 : <?php echo $org_od_id; ?></span>
                </div>
                <?php
                $sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type
                                ,ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price
                            from {$g5['g5_shop_cart_table']}
                            where od_id = '$org_od_id'
                            order by ct_id ";
                $result = sql_query($sql);

                for($i=0; $row=sql_fetch_array($result); $i++) {
                    $image = get_it_image($row['it_id'], 150, 150, '', '', $row['it_name']);

                    $opt_rental_price = $row['ct_rental_price'] + $row['io_price'];
                    $sell_rental_price = $opt_rental_price * $row['ct_qty'];
                ?>
                
                <div class="cont">
                    <div class="photo"><a href="./item.php?it_id=<?php echo $row['it_id']; ?>" ><?php echo $image; ?></a></div>
                    <div class="info">
                        <a href="./item.php?it_id=<?php echo $row['it_id']; ?>"><strong><?php echo $row['it_name']; ?></strong></a>
                        <p>옵션 : <?php echo get_text($row['ct_option']); ?></p>

                        <p>수량 : <?php echo number_format($row['ct_qty']);?></p>
                        <p>계약기간 : <?php echo number_format($row['ct_item_rental_month']);?>개월</p>
                        <p class="price"><span>리스 금액 : </span>  <?php echo number_format($sell_rental_price); ?> 원</p>
                    </div>
                </div>
                <?php } ?>

                <div class="order_list bottom_cont">
                    <ul>
                        <li>
                            <span class="item">계약일</span>
                            <strong class="result"><?php echo substr($od['od_time'],0,10) ?></strong>
                        </li>
                        <li>
                            <span class="item">리스료</span>
                            <strong class="result">월 <?php echo number_format($od['rt_rental_price']); ?> 원</strong>
                        </li>
                        <li>
                            <span class="item">횟수정보</span>
                            <strong class="result"><span class="point"><?php echo number_format($od['rt_payment_count']); ?></span>회 / <?php echo number_format($od['rt_month']); ?>회 (현재 횟수/전체 횟수)</strong>
                        </li>
                        <li>
                            <span class="item">해지사유</span>
                            <strong class="result"><?php echo $od['od_contractout']; ?></strong>
                        </li>
                    </ul>
                </div>
			</div>
		</div>
	</div>

    <div class="grid">
    	<div class="title_bar">
    		<h3 class="g_title_01">위약금 정보</h3>
    	</div>
		<div class="order_list border_box">
			<ul>
				<!-- li>
					<span class="item">계약 금액</span>
					<strong class="result">
						<em class="bold"><?php echo number_format($od['rt_rental_price'] * $od['rt_month']); ?> 원</em>
					</strong>
				</li -->
				<li>
					<span class="item">리스료</span>
					<strong class="result">
						<em class="bold"><?php echo number_format($od['rt_rental_price']); ?> 원</em>
					</strong>
				</li>
				<li>
					<span class="item">수납 방법</span>
					<strong class="result">카드자동이체</strong>
				</li>
				<li>
					<span class="item">카드사</span>
					<strong class="result"><?php echo $od['od_bank_account']; ?></strong>
				</li>
				<li>
					<span class="item">수납일</span>
					<strong class="result"><?php echo $od['rt_billday']; ?>일</strong>
				</li>
				<li>
					<span class="item">수납 횟수</span>
					<strong class="result"><?php echo $od['rt_payment_count']; ?> 회</strong>
				</li>
			<li>
				<span class="item">수납일 시작일</span>
				<strong class="result"><?php echo $od['rt_rental_startdate']; ?></strong>
			</li>
			<li>
				<span class="item">수납일 종료일</span>
				<strong class="result"><?php echo $rt_rental_enddate; ?></strong>
			</li>
			</ul>
		</div>
		<div class="order_title reverse">
            <span class="item">예상 위약금 금액</span>
            <strong class="result">
                <em class="point_red" id="od_tot_price"><?php echo number_format($od['od_penalty']) ?> 원</em>
            </strong>
        </div>
	</div>
	
    <div class="grid">

        <div class="title_bar">
            <h3 class="g_title_01">해지 요청 수거지 정보 </h3>
        </div>
        <div class="order_list border_box">
            <ul>
                <li>
                    <span class="item">이름</span>
                    <strong class="result"><?php echo get_text($od['od_b_name']); ?></strong>
                </li>
                <li>
                    <span class="item">주소</span>
                    <strong class="result">
                        <span class="addr"><?php echo get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']).' '.print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></span>
                    </strong>
                </li>
                <li>
                    <span class="item">이메일 주소</span>
                    <strong class="result"><?php echo get_text($od['od_email']); ?></strong>
                </li>
                <li>
                    <span class="item">연락처</span>
                    <strong class="result"><?php echo get_text($od['od_b_tel']); ?></strong>
                </li>
                <li>
                    <span class="item">휴대전화 번호</span>
                    <strong class="result"><?php echo get_text($od['od_b_hp']); ?></strong>
                </li>
                <li>
                    <span class="item">요청사항</span>
                    <strong class="result"><?php echo conv_content($od['od_memo'], 0); ?></strong>
                </li>
            </ul>
        </div>
    </div>

	<div class="grid bg_none">
        <div class="title_bar">
            <h3 class="g_title_01">결제 수단 선택</h3>
        </div>
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
            echo '<div class="order_list button_choice"><ul class="onoff">';
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

        // 무통장입금 사용
        if ($default['de_bank_use']) {
            $multi_settle++;
            echo '<li><input type="radio" id="od_settle_bank" name="od_settle_case" value="무통장" '.$checked.'> <label for="od_settle_bank" class="lb_icon  bank_icon">무통장입금</label></li>'.PHP_EOL;
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

        if ($default['de_bank_use']) {
            // 은행계좌를 배열로 만든후
            $str = explode("\n", trim($default['de_bank_account']));
            if (count($str) <= 1)
            {
                $bank_account = '<input type="hidden" name="od_bank_account" value="'.$str[0].'">'.$str[0].PHP_EOL;
            }
            else
            {
                $bank_account = '<select name="od_bank_account" id="od_bank_account">'.PHP_EOL;
                $bank_account .= '<option value="">선택하십시오.</option>';
                for ($i=0; $i<count($str); $i++)
                {
                    //$str[$i] = str_replace("\r", "", $str[$i]);
                    $str[$i] = trim($str[$i]);
                    $bank_account .= '<option value="'.$str[$i].'">'.$str[$i].'</option>'.PHP_EOL;
                }
                $bank_account .= '</select>'.PHP_EOL;
            }
            echo '<div id="settle_bank" style="display:none">';
            echo '<label for="od_bank_account" class="sound_only">입금할 계좌</label>';
            echo $bank_account;
            echo '<br><label for="od_deposit_name">입금자명</label> ';
            echo '<input type="text" name="od_deposit_name" id="od_deposit_name" size="10" maxlength="20">';
            echo '</div>';
        }

        if ($default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay() ) {
            echo '</div>';
        }

        if ($multi_settle == 0)
            echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
        ?>

        <div class="order_list">
            <div class="inp_wrap">
                <span class="chk check">
                    <input type="checkbox" id="chk_user_privacy" required="required">
                    <label for="chk_user_privacy">개인정보 수집 · 이용 동의<span>(필수)</span></label>
                </span>
                <a href="<?php echo G5_MOBILE_URL?>/common/terms_agreement.php?id=chk_user_privacy&type=user_privacy&title=<?php echo urlencode("개인정보 수집 • 이용 동의")?>" class="btn floatR arrow_r_green point" target="_blank">전문보기</a>
            </div>
        </div>

    </div>


    <?php
    // 결제대행사별 코드 include (결제대행사 정보 필드 및 주분버튼)
    require_once(G5_MSHOP_PATH.'/lg/orderform.2.php');

    if( is_inicis_simple_pay() ){   //삼성페이 또는 L.pay 사용시
        require_once(G5_MSHOP_PATH.'/samsungpay/orderform.2.php');
    }

    if($is_kakaopay_use) {
        require_once(G5_SHOP_PATH.'/kakaopay/orderform.2.php');
    }
    ?>

    <div id="show_progress" style="display:none;">
        <img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt="">
        <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>

    <?php
    if($is_kakaopay_use) {
        require_once(G5_SHOP_PATH.'/kakaopay/orderform.3.php');
    }
    ?>
    </form>

    <?php
    if ($default['de_escrow_use']) {
        // 결제대행사별 코드 include (에스크로 안내)
        require_once(G5_MSHOP_PATH.'/lg/orderform.3.php');

        if( is_inicis_simple_pay() ){   //삼성페이 사용시
            require_once(G5_MSHOP_PATH.'/samsungpay/orderform.3.php');
        }
    }
    ?>
</div>

<?php
if( is_inicis_simple_pay() ){   //삼성페이 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/order.script.php');
}
?>
<script src="<?php echo G5_JS_URL?>/shop.order.js"></script>
<script>
var zipcode = "";
var form_action_url = "<?php echo $order_action_url; ?>";
$(function() {

    $("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_samsungpay").bind("click", function() {
        $("#settle_bank").hide();
        $("#show_req_btn").css("display", "block");
        $("#show_pay_btn").css("display", "none");

        $("input[name='submitChecked']").val($("#od_tot_price").text()+" 결제");
    });
});

/* 결제방법에 따른 처리 후 결제등록요청 실행 */
var settle_method = "";
var temp_point = 0;
function pay_approval()
{
    var f = document.sm_form;
    var pf = document.forderform;

    var settle_case = document.getElementsByName("od_settle_case");
    var settle_check = false;
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

    if (!$("#chk_user_privacy").is(":checked")) {
        alert("개인정보 수집 · 이용 동의에 동의하셔야 결제 하실 수 있습니다.");
        $("#chk_user_privacy").focus();
        return false;
    }

    // 카카오페이 지불
    if(settle_method == "KAKAOPAY") {
        <?php if($default['de_tax_flag_use']) { ?>
        pf.SupplyAmt.value = parseInt(pf.comm_tax_mny.value) + parseInt(pf.comm_free_mny.value);
        pf.GoodsVat.value  = parseInt(pf.comm_vat_mny.value);
        <?php } ?>
        pf.good_mny.value = f.good_mny.value;
        getTxnId(pf);
        return false;
    }

    var form_order_method = '';

    if( settle_method == "삼성페이" || settle_method == "lpay" ){
        form_order_method = 'samsungpay';
    }

    if( jQuery(pf).triggerHandler("form_sumbit_order_"+form_order_method) !== false ) {
        <?php if($default['de_pg_service'] == 'kcp') { ?>
        f.buyr_name.value = pf.od_name.value;
        f.buyr_mail.value = pf.od_email.value;
        f.buyr_tel1.value = pf.od_tel.value;
        f.buyr_tel2.value = pf.od_hp.value;
        f.rcvr_name.value = pf.od_b_name.value;
        f.rcvr_tel1.value = pf.od_b_tel.value;
        f.rcvr_tel2.value = pf.od_b_hp.value;
        f.rcvr_mail.value = pf.od_email.value;
        f.rcvr_zipx.value = pf.od_b_zip.value;
        f.rcvr_add1.value = pf.od_b_addr1.value;
        f.rcvr_add2.value = pf.od_b_addr2.value;
        f.settle_method.value = settle_method;
        if(settle_method == "간편결제")
            f.payco_direct.value = "Y";
        else
            f.payco_direct.value = "";
        <?php } else if($default['de_pg_service'] == 'lg') { ?>
        var pay_method = "";
        var easy_pay = "";
        switch(settle_method) {
            case "계좌이체":
                pay_method = "SC0030";
                break;
            case "가상계좌":
                pay_method = "SC0040";
                break;
            case "휴대전화":
                pay_method = "SC0060";
                break;
            case "신용카드":
                pay_method = "SC0010";
                break;
            case "간편결제":
                easy_pay = "PAYNOW";
                break;
        }
        f.LGD_CUSTOM_FIRSTPAY.value = pay_method;
        f.LGD_BUYER.value = pf.od_name.value;
        f.LGD_BUYEREMAIL.value = pf.od_email.value;
        f.LGD_BUYERPHONE.value = pf.od_hp.value;
        f.LGD_AMOUNT.value = f.good_mny.value;
        f.LGD_EASYPAY_ONLY.value = easy_pay;
        <?php if($default['de_tax_flag_use']) { ?>
        f.LGD_TAXFREEAMOUNT.value = pf.comm_free_mny.value;
        <?php } ?>
        <?php } else if($default['de_pg_service'] == 'inicis') { ?>
        var paymethod = "";
        var width = 330;
        var height = 480;
        var xpos = (screen.width - width) / 2;
        var ypos = (screen.width - height) / 2;
        var position = "top=" + ypos + ",left=" + xpos;
        var features = position + ", width=320, height=440";
        var p_reserved = f.DEF_RESERVED.value;
        f.P_RESERVED.value = p_reserved;
        switch(settle_method) {
            case "계좌이체":
                paymethod = "bank";
                break;
            case "가상계좌":
                paymethod = "vbank";
                break;
            case "휴대전화":
                paymethod = "mobile";
                break;
            case "신용카드":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "");
                break;
            case "간편결제":
                paymethod = "wcard";
                f.P_RESERVED.value = p_reserved+"&d_kpay=Y&d_kpay_app=Y";
                break;
            case "삼성페이":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "")+"&d_samsungpay=Y";
                //f.DEF_RESERVED.value = f.DEF_RESERVED.value.replace("&useescrow=Y", "");
                f.P_SKIP_TERMS.value = "Y"; //약관을 skip 해야 제대로 실행됨
                break;
            case "lpay":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "")+"&d_lpay=Y";
                //f.DEF_RESERVED.value = f.DEF_RESERVED.value.replace("&useescrow=Y", "");
                f.P_SKIP_TERMS.value = "Y"; //약관을 skip 해야 제대로 실행됨
                break;
        }
        f.P_AMT.value = f.good_mny.value;
        f.P_UNAME.value = pf.od_name.value;
        f.P_MOBILE.value = pf.od_hp.value;
        f.P_EMAIL.value = pf.od_email.value;
        <?php if($default['de_tax_flag_use']) { ?>
        f.P_TAX.value = pf.comm_vat_mny.value;
        f.P_TAXFREE = pf.comm_free_mny.value;
        <?php } ?>
        f.P_RETURN_URL.value = "<?php echo $return_url.$od_id; ?>";
        f.action = "https://mobile.inicis.com/smart/" + paymethod + "/";
        <?php } ?>

        // 주문 정보 임시저장
        var order_data = $(pf).serialize();
        var save_result = "";
        $.ajax({
            type: "POST",
            data: order_data,
            url: g5_url+"/shop/ajax.orderdatasave.php",
            cache: false,
            async: false,
            success: function(data) {
                save_result = data;
            }
        });

        if(save_result) {
            alert(save_result);
            return false;
        }

        f.submit();
    }

    return false;
	
}

function forderform_check()
{
    var f = document.forderform;

    // 필드체크
    if(!orderfield_check(f))
        return false;

    // 금액체크
    if(!payment_check(f))
        return false;

    if(settle_method != "무통장" && f.res_cd.value != "0000") {
        alert("결제등록요청 후 주문해 주십시오.");
        return false;
    }

    document.getElementById("display_pay_button").style.display = "none";
    document.getElementById("show_progress").style.display = "block";

    setTimeout(function() {
        f.submit();
    }, 300);
}


</script>
