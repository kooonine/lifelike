<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_MSHOP_PATH.'/settle_lg3.inc.php');

$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)


// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span class="">수선비용 결제</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>


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

<?php
// 결제대행사별 코드 include (결제등록 필드)
require_once(G5_MSHOP_PATH.'/lg3/orderform.1.php');
?>
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

    </div>

	<div class="grid">
    	<div class="title_bar ">
    		<h3 class="g_title_01">신청 제품 확인</span></h3>
    	</div>
    	<div class="border_box order_list mt20 reverse">
    		<ul>
    			<li>
    				<span class="item">* <?php echo $row['ct_option']; ?> * 1개 수선 </span>
    				<strong class="result"><?php echo number_format($sell_price)." 원"?></strong>
    			</li>
    			<li>
    				<span class="item">* 택배비</span>
    				<strong class="result"><?php echo number_format($send_cost)?> 원</strong>
    			</li>
    			<li>
    				<span class="item blind"></span>
    				<strong class="result bold point" id="ct_tot_price"><?php echo number_format($sell_price + $send_cost)?> 원</strong>
    			</li>
    		</ul>
    	</div>
	</div>

	<div class="grid">
    	<div class="title_bar">
    		<h3 class="g_title_01"><?php echo $od_type_name?> 요청서</h3>
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

	<div class="grid">

		<div class="order_list">

			<div class="line_box tbl">
				<span class="item cell">총 결제 금액<br>(배송료 포함)</span>
				<strong class="result cell"><em id="od_tot_price" class="point"><?php echo number_format($tot_price); ?></em> 원</strong>
			</div>
        </div>

        <?php
        // 결제대행사별 코드 include (결제대행사 정보 필드 및 주분버튼)
        require_once(G5_MSHOP_PATH.'/lg3/orderform.2.php');
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
        require_once(G5_MSHOP_PATH.'/lg3/orderform.3.php');
    }
    ?>

</div>

<script src="<?php echo G5_JS_URL?>/shop.order.js"></script>
<script>
var zipcode = "";

$(function() {


    $("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_samsungpay").bind("click", function() {
        $("#settle_bank").hide();
        $("#show_req_btn").css("display", "block");
        $("#show_pay_btn").css("display", "none");

        $("input[name='submitChecked']").val($("#od_tot_price").text()+"원 결제");
    });

});

/* 결제방법에 따른 처리 후 결제등록요청 실행 */
var settle_method = "";
var temp_point = 0;

function pay_approval()
{
    var f = document.sm_form;
    var pf = document.forderform;

    // 필드체크
    if(!orderfield_check(pf))
        return false;

    // 금액체크
    if(!payment_check(pf))
        return false;

    // pg 결제 금액에서 적립금 금액 차감
    if(settle_method != "무통장") {
        var od_price = parseInt(pf.od_price.value);
        var send_cost = parseInt(pf.od_send_cost.value);
        var send_cost2 = parseInt(pf.od_send_cost2.value);
        var send_coupon = parseInt(pf.od_send_coupon.value);
        f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
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

function forderform_check(f)
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



//주문폼 필드체크
function orderfield_check(f)
{
 errmsg = "";
 errfld = "";
 var deffld = "";
 
 if (errmsg)
 {
     alert(errmsg);
     errfld.focus();
     return false;
 }

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

    if (!$("#chk_user_privacy").is(":checked"))
    {
     alert("개인정보 수집 · 이용 동의에 동의하셔야 결제 하실 수 있습니다.");
    	return false;
    }

 return true;
}

//결제체크
function payment_check(f)
{
 var od_price = parseInt(f.od_price.value);
 var send_cost = parseInt(f.od_send_cost.value);
 var tot_price = od_price + send_cost;
 
	if(tot_price == 0) {
     return true;
	}

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

 <?php if($default['de_tax_flag_use']) { ?>
 calculate_tax();
 <?php } ?>

 return true;
}

</script>
