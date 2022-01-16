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
// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = "select  b.ct_id, a.it_id, a.it_name, a.ct_option, a.rf_serial, a.od_type
                , a.ct_price, a.io_price, a.ct_time
                , a.ct_rental_price, a.ct_item_rental_month
                , a.ct_free_laundry_use, a.ct_free_laundry, a.ct_free_laundry_delivery_price
                , a.ct_laundry_use, a.ct_laundry_price, a.ct_laundry_delivery_price
                , a.ct_laundrykeep_use, a.ct_laundrykeep_lprice, a.ct_laundrykeep_kprice, a.ct_laundrykeep_delivery_price
                , a.ct_repair_use, a.ct_repair_price, a.ct_repair_delivery_price
                , b.buy_ct_id, b.buy_od_sub_id
        from    lt_shop_order_item a
                inner join lt_shop_cart b
                  on a.ct_id = b.buy_ct_id and a.od_sub_id = b.buy_od_sub_id
        where   b.od_id = '$s_cart_id'
          and   b.ct_select = '1' ";

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

$laundry_price = 0; //세탁비
$send_cost = 0; //배송비
$keep_price = 0; //1개월 보관비
$keep_month = 6; // 최소 보관 개월 수
$goods = $goods_it_id = "";

$ct_free_laundry_YN = 0; //무료세탁여부

$ct_free_laundry = $row['ct_free_laundry']; //무료세탁횟수
$ct_free_laundry_use = $row['ct_free_laundry_use']; //무료세탁 사용횟수
$ct_free_laundry_delivery_price = $row['ct_free_laundry_delivery_price']; //무료세탁일 경우 배송비
$ct_free_laundry_count = $ct_free_laundry-$ct_free_laundry_use; //무료세탁 남은 횟수 (무료세탁 먼저 사용)

$sell_price = 0;
if($od_type == "L")
{
    //세탁
    $laundry_price = $row['ct_laundry_price'];  //유료세탁비
    $org_send_cost =  $row['ct_laundry_delivery_price']; //유료세탁배송비
    $send_cost =  $row['ct_laundry_delivery_price']; //유료세탁배송비
    $title = "세탁신청";

    $sell_price = $laundry_price;
    if($ct_free_laundry_count > 0) {
        $ct_free_laundry_YN = 1; //무료세탁여부
        $sell_price = 0; //무료세탁일 경우 세탁비 없음.
        $send_cost = 0; //무료세탁일 경우 배송비도 없음.
        //$send_cost = $ct_free_laundry_delivery_price; //무료세탁 배송비로 설정
        
    }

} elseif($od_type == "K") {
    //세탁보관
    $laundry_price = $row['ct_laundrykeep_lprice']; //유료세탁비
    $org_send_cost =  $row['ct_laundrykeep_delivery_price']; //유료세탁배송비
    $send_cost =  $row['ct_laundrykeep_delivery_price']; //배송비

    $title = "세탁 보관 신청";
    $sell_price = $laundry_price;
    if($ct_free_laundry_count > 0) {
        $ct_free_laundry_YN = 1; //무료세탁여부
        $sell_price = 0; //무료세탁일 경우 세탁비 없음.
        $send_cost = 0; //무료세탁일 경우 배송비도 없음.
        //$send_cost = $ct_free_laundry_delivery_price; //무료세탁보관일 경우 세탁보관의 배송비로 설정
    }

    $keep_price = $row['ct_laundrykeep_kprice']; //보관비
    $sell_price = $sell_price + ($keep_price * $keep_month);
}

//배송비 제외
$send_cost = 0;

$tot_sell_price = $sell_price;
$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비

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
			<h3 class="g_title_01">제품 정보</span></h3>
			<a href="<?php echo G5_SHOP_URL ?>/care.php" class="title-more"><span>제품 변경</span></a>
		</div>
		<div class="orderwrap">
            <div class="order_cont">
                <div class="body">
                    <div class="cont right_cont">
                        <div class="photo"><?php echo $image; ?></div>
                        <div class="info">
                            <strong><?php echo $it_name; ?></strong>
                            <p><span class="txt">옵션</span><span class="point_black"><strong class="bold"><?php echo $row['ct_option']; ?></strong></span></p>
                            <p><span class="txt">구매일</span><span class="point_black"><?php echo substr($row['ct_time'], 0, 10)?></span></p>

                			<?php if($od_type == "K") { ?>
    						<p><span class="txt">보관기간</span>
    							<span class="point_black">
    								<select name="ct_keep_month" id="ct_keep_month" class="btn_select">
    								<?php for ($i = $keep_month; $i <= 36; $i++) {
    								    echo '<option value="'.$i.'">'.$i.'개월</option>';
    								} ?>
    								</select>
    							</span>
    						</p>
    						<?php } else { ?>
        						<input type="hidden" name="ct_keep_month" value="0">
    						<?php } ?>

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
    	<div class="divide_two box">
            <div class="title_bar none">
                <h2 class="g_title_01">신청 제품 확인</span></h2>
            </div>
            <div class="border_box order_list reverse white_box result_right">
                <ul>
                    <li>
                        <span class="item" style="width:auto;">* <?php echo $row['ct_option']; ?> * 1개 세탁 </span>
                        <strong class="result"><?php echo number_format($laundry_price)?> 원</strong>
                    </li>
                    
                    <?php if($ct_free_laundry_count > 0) { ?>
                    <!-- 무료 세탁권 있을경우 -->
                    <li>
                        <span class="item" style="width:auto;">
                            <input type="checkbox" name="" value="" id="" checked="checked" readonly="readonly" disabled="disabled"> 무료 세탁권 / 잔여<?php echo $ct_free_laundry_count?>회 <span>(만료일 : <?php
                            $enddate = date_add(date_create($row['ct_time']), date_interval_create_from_date_string("1 years"));
                            echo date_format($enddate,"Y-m-d")?>)</span>
                        </span>
                        <strong class="result">-<?php echo number_format($laundry_price)?> 원</strong>
                    </li>           
                     <!-- //무료 세탁권 있을경우 -->
                    <?php } ?>

                    <?php if($od_type == "K") { ?>
                    <li>
                        <span class="item" style="width:auto;">* <?php echo $row['ct_option']; ?> * <em id="keep_month1"><?php echo $keep_month ?></em>개월 보관</span>
                        <strong class="result"><?php echo number_format($keep_price)?> 원 X <em id="keep_month2"><?php echo $keep_month ?></em>개월</strong>
                    </li>
                    <?php }?>
                    <!-- <li>
                        <span class="item">* 택배비</span>
                        <strong class="result"><php echo number_format($org_send_cost)> 원</strong>
                    </li> -->
                    <li>
                        <span class="item blind"></span>
                        <strong class="result bold point" id="ct_tot_price"><?php echo number_format($tot_sell_price)?> 원</strong>
                    </li>
                </ul>
            </div>

		</div>
		<?php if($od_type == "K") { ?>
		<p class="ico_import red point_red floatL">세탁 보관 신청은 장 기간 쾌적한 환경에서 보관을 위하여 보관 전 세탁을 필수로 진행 하셔야 하며, 환불이 불가합니다.</p>
		<?php } ?>
	</div>

	<div class="grid">
    	<div class="title_bar">
    		<h3 class="g_title_01">신청서</h3>
    	</div>
    	<div class="border_box">
        	<div class="inp_wrap">
        		<div class="title count3"><label for="f_01">요청사항</label></div>
        		<div class="inp_ele count6">
        			<div class="input">
        				<textarea name="cust_memo" id="cust_memo" rows="6" cols="20" maxlength="200" placeholder="최대 200자 이내 세탁 시 주요 오염 위치와 주의사항 및 필요사항을 입력하세요."></textarea>
        				<p class="ico_import red point_red floatL">신청사항은 주요 세탁이 필요한 부분, 주의 해야 할 부분을 반드시 입력 해 주세요.</p>
						<span class="byte"><span id="byte">0</span>/200</span>
        			</div>
        		</div>
        	</div>
			<div class="inp_wrap">
				<div class="title count3"><label for="f_01">첨부파일</label></div>
				<div class="inp_ele count6 r_btn_80">
					<div class="input">
						<input type="text" placeholder="" id="join7_1" title="파일" disabled="" value="">
					</div>
					<button type="button" class="inp_file btn" id="btnFile1" accept="image/*,video/*">파일찾기</button>
					<p class="ico_import red point_red">첨부파일 최대 5개, 동영상의 경우 20mb 이하의 파일만 첨부 가능합니다.</p>
				</div>
			</div>

			<div class="file_list">
				<ul id="file_list">
				</ul>
			</div>

			<?php for ($i=0; $i<5; $i++) { ?>
            <input type="file" id="bf_file<?php echo $i+1 ?>" name="bf_file[]" hidden idx="<?php echo $i+1 ?>" act="">
            <?php } ?>

    	</div>
    </div>

 <div class="grid">
	<div class="divide_two box">
		<div class="box">
			<div class="title_bar none">
				<h2 class="g_title_01">고객 정보</h2>
			</div>
			<div class="border_box">
				<div class="inp_wrap">
					<div class="title count3"><label>이름</label></div>
                    <div class="inp_ele count6"><div class="input">
                    <input type="text" name="od_name" value="<?php echo get_text($member['mb_name']); ?>" id="od_name" required class="frm_input required" maxlength="20">
                    </div></div>

                    <input type="hidden" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" >
                    <input type="hidden" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
                    <input type="hidden" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" >
                    <input type="hidden" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" >
					<input type="hidden" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" >
                    <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
                </div>

                <div class="inp_wrap">
					<div class="title count3"><label>휴대전화 번호</label></div>
                    <div class="inp_ele count6"><div class="input">
                    <input type="text" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" required class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력">
                    </div></div>
                </div>
				<div class="inp_wrap">
					<div class="title count3"><label>E-mail</label></div>
                    <div class="inp_ele count6"><div class="input">
                    <input type="email" name="od_email" value="<?php echo $member['mb_email']; ?>" id="od_email" required class="frm_input required" maxlength="100">
                    </div></div>
                 </div>
            </div>
			<div class="info_box gray_box mt15">
				<p class="ico_import red point_red">세탁 신청 안내</p>
				<p class="dep">
					토사/오줌/젖은세탁물/피/동물털/과도한 음식물/과일즙/알수없는 액체 및 직접 제거 시도로 약품처리를 하신 경우에는 세탁 진행이 거부 될수 있습니다.
				</p>
			</div>
    	</div>

	<div class="box">
		<div class="title_bar none">
			<h2 class="g_title_01">수거지 정보</h2>
			<span class="chk radio">
            	<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
            	<label for="ad_sel_addr_same">주문자 정보와 동일</label>
            </span>
        </div>

		<div class="border_box">
        	
        

			<div class="inp_wrap">
				<div class="title count3"><label>받는분</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20"></div>
				</div>
                    <input type="hidden" name="ad_subject" id="ad_subject" value="">
			</div>
			<div class="inp_wrap"> 
				<div class="title count3"><label>휴대전화 번호</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>연락처</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20"></div>
				</div>
			</div>

			<div class="inp_wrap">
				<div class="title count9">
					<label for="join7">제품 수거지 주소</label>
					<a href="<?php echo G5_SHOP_URL ?>/orderaddress.php" id="order_address"><button class="btn gray round floatR"><span class="point_black">배송지 관리</span></button></a>
				</div>
				<div class="inp_ele count9 r_btn_80">
                    <div class="input"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6" readonly="readonly"></div>
                    <button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
				</div>
			</div>

            <div class="inp_wrap">
                <div class="inp_ele count9 col_r">
                    <div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required" readonly="readonly"></div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="inp_ele count9 col_r">
                    <div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address">
                    <input type="hidden" name="od_b_addr3" id="od_b_addr3" >
                    <input type="hidden" name="od_b_addr_jibeon" value=""></div>
                </div>
            </div>
			<div class="inp_wrap">
				<div class="title count3"><label>배송 메시지</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
				</div>
			</div>
		</div>
    </div>

    <?php
    $oc_cnt = $sc_cnt = 0;
    if($tot_price > 0) {
    ?>

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
    <?php } ?>
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
	
	$("textarea[name='cust_memo']").keyup(function() {
		var str = $(this).val();
		var mx = parseInt($(this).attr("maxlength"));
		document.getElementById('byte').innerHTML = str.length;
		
		if (str.length > mx) {
			$(this).val(str.substr(0, mx));
			return false;
		}
	});

	var fileCount = <?php echo $i?>;

    $("#btnFile1,#btnFile2,#btnFile3").click(function() {
		var accept = $(this).attr("accept");

    	var nextID = "";
    	var notadd = true;

    	for (var i = 1; i <= 5; i++) {
			var $bf_file = $("#bf_file"+i);
			var act = $bf_file.attr("act");

            if($bf_file.val() == "" && act != "in") {
            	$bf_file.attr("accept", accept);
            	$bf_file.click();
            	notadd = false;
            	break;
            }
		}
		if(notadd) alert("최대 사진5매, 동영상 100mb 이하의 파일만 첨부 가능합니다.");
    });

    $("input[name='bf_file[]']").change(function() {

        var idx = $(this).attr("idx");

    	var fileName = "";
    	if(window.FileReader){
    		fileName = $(this)[0].files[0].name;
    	} else {
    		fileName = $(this)[0].val().split('/').pop().split('\\').pop();
    	}

        if (fileName != "") {
    		var html = '';
    		html += '<li>';
    		html += '<span class="name">'+fileName+'</span>';
    		html += '<button type="button" class="btn_delete gray" id="file_delete" idx="'+idx+'">';
    		html += '<span class="blind">삭제</span>';
    		html += '</button>';
    		html += '</li>';

            if($("#file_list li").size() > 0) {
    			$('#file_list li:last').after(html);
            } else {
            	$('#file_list').html(html);
            }
            fileCount++;
            $(this).attr("act", "in");

        } else {
                alert("파일을 선택해주세요");
                return false;
        }
    });

	$(document).on("click", "#file_delete", function() {
    	var result = confirm('첨부 파일을 삭제 하시겠습니까?');

    	if(result){
			var idx = $(this).attr('idx');
			$("#bf_file_del"+idx).prop('checked',true);

			$("#bf_file"+idx).attr("act", "del");
			$("#bf_file"+idx).replaceWith($("#bf_file"+idx).val('').clone(true));
			$(this).closest("li").remove();
			fileCount--;
    	}
	});


    var $cp_btn_el;
    var $cp_row_el;

    $("#od_b_addr2").focus(function() {
        var zip = $("#od_b_zip").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

    $("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_samsungpay").bind("click", function() {
        $("#settle_bank").hide();
        $("#show_req_btn").css("display", "block");
        $("#show_pay_btn").css("display", "none");

        $("input[name='submitChecked']").val($("#od_tot_price").text()+"원 결제");
    });

    // 배송지선택
    $("input[name=ad_sel_addr]").on("click", function() {
        var addr = $(this).val().split(String.fromCharCode(30));

        if (addr[0] == "same") {
            gumae2baesong();
        } else {
            if(addr[0] == "new") {
                for(i=0; i<10; i++) {
                    addr[i] = "";
                }
            }

            var f = document.forderform;
            f.od_b_name.value        = addr[0];
            f.od_b_tel.value         = addr[1];
            f.od_b_hp.value          = addr[2];
            f.od_b_zip.value         = addr[3] + addr[4];
            f.od_b_addr1.value       = addr[5];
            f.od_b_addr2.value       = addr[6];
            f.od_b_addr3.value       = addr[7];
            f.od_b_addr_jibeon.value = addr[8];
            f.ad_subject.value       = addr[9];

            var zip1 = addr[3].replace(/[^0-9]/g, "");
            var zip2 = addr[4].replace(/[^0-9]/g, "");

            var code = String(zip1) + String(zip2);

            if(zipcode != code) {
                calculate_sendcost(code);
            }

            ad_subject_change();
        }
    });

    // 배송지목록
    $("#order_address,#order_address1").on("click", function() {
        var url = this.href;
        window.open(url, "win_address", "left=100,top=100,width=650,height=500,scrollbars=1");
        return false;
    });

    $("#ct_keep_month").on("change", function() {
    	calculate_total_price();
    });

});

function fileCheck( file )
{
        // 사이즈체크
        var maxSize  = 10 * 1024 * 1024
        var fileSize = 0;

	// 브라우저 확인
	var browser=navigator.appName;

	// 익스플로러일 경우
	if (browser=="Microsoft Internet Explorer")
	{
		var oas = new ActiveXObject("Scripting.FileSystemObject");
		fileSize = oas.getFile( file.value ).size;
	}
	// 익스플로러가 아닐경우
	else
	{
		fileSize = file.files[0].size;
	}


    if(fileSize > maxSize)
    {
        alert("첨부파일 사이즈는 10MB 이내로 등록 가능합니다.    ");
        return false;
    }
	return true;
}

function ad_subject_change()
{
    $("#addr").text("["+$("#od_b_zip").val()+"]"+$("#od_b_addr1").val()+" "+$("#od_b_addr2").val());
    $("#spn_od_b_name").text($("#od_b_name").val());
    $("#spn_od_b_tel").text($("#od_b_tel").val());
    $("#spn_od_b_hp").text($("#od_b_hp").val());
    $("#spn_ad_subject").text($("#od_b_name").val());
}

function calculate_total_price()
{
	var keep_month = $("#ct_keep_month").val();
	$("#keep_month1").text(keep_month);
	$("#keep_month2").text(keep_month);

    var laundry_price = parseInt($("input[name=laundry_price]").val());
    var keep_price = parseInt($("input[name=keep_price]").val());
    var send_cost = parseInt($("input[name=od_send_cost]").val());
    var ct_free_laundry_YN = parseInt($("input[name=ct_free_laundry_YN]").val());
    var ct_free_laundry_delivery_price = parseInt($("input[name=ct_free_laundry_delivery_price]").val());

    //tot_sell_price = laundry_price + (keep_price * keep_month) + send_cost;

    od_price = 0;
    if(ct_free_laundry_YN == '0') od_price = laundry_price;
    od_price = od_price + (keep_price * keep_month);
    $("input[name=od_price]").val(od_price);

    var tot_price = od_price + send_cost;
    $("#od_tot_price").text(number_format(String(tot_price)));
    $("#ct_tot_price").text(number_format(String(tot_price))+" 원");

    $("input[name=good_mny]").val(tot_price);
}

function calculate_order_price()
{
    var sell_price = parseInt($("input[name=od_price]").val());
    var send_cost = parseInt($("input[name=od_send_cost]").val());
    var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
    var send_coupon = parseInt($("input[name=od_send_coupon]").val());
    var tot_price = sell_price + send_cost + send_cost2 - send_coupon - temp_point;

    $("form[name=sm_form] input[name=good_mny]").val(tot_price);
    $("#od_tot_price").text(number_format(String(tot_price)));
    <?php if($temp_point > 0 && $is_member) { ?>
    calculate_temp_point();
    <?php } ?>

    $("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
    $("#od_point_cost").text(number_format(String(temp_point)));
    $("#od_tot_price").text(number_format(String(tot_price)));
    $("input[name='submitChecked']").val(number_format(String(tot_price))+"원 결제");
    //$("input[name='submitChecked']").val(number_format(String($("input[name=od_price]").val()))+"원 결제");
}

function calculate_temp_point()
{
    var sell_price = parseInt($("input[name=od_price]").val());
    var mb_point = parseInt(<?php echo $member['mb_point']; ?>);
    var max_point = parseInt(<?php echo $default['de_settle_max_point']; ?>);
    var point_unit = parseInt(<?php echo $default['de_settle_point_unit']; ?>);
    var temp_point = max_point;

    if(temp_point > sell_price)
        temp_point = sell_price;

    if(temp_point > mb_point)
        temp_point = mb_point;

    temp_point = parseInt(temp_point / point_unit) * point_unit;

    $("#use_max_point").text(number_format(String(temp_point))+"원");
    $("input[name=max_temp_point]").val(temp_point);
}

function calculate_sendcost(code)
{
    $.post(
        "./ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=od_send_cost2]").val(data);
            $("#od_send_cost2").text(number_format(String(data)));

            zipcode = code;

            calculate_order_price();
        }
    );
}

function calculate_tax()
{
    var $it_prc = $("input[name^=it_price]");
    var $cp_prc = $("input[name^=cp_price]");
    var sell_price = tot_cp_price = 0;
    var it_price, cp_price, it_notax;
    var tot_mny = comm_free_mny = tax_mny = vat_mny = 0;
    var send_cost = parseInt($("input[name=od_send_cost]").val());
    var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
    var od_coupon = parseInt($("input[name=od_coupon]").val());
    var send_coupon = parseInt($("input[name=od_send_coupon]").val());
    var temp_point = 0;

    $it_prc.each(function(index) {
        it_price = parseInt($(this).val());
        cp_price = parseInt($cp_prc.eq(index).val());
        sell_price += it_price;
        tot_cp_price += cp_price;
        it_notax = $("input[name^=it_notax]").eq(index).val();
        if(it_notax == "1") {
            comm_free_mny += (it_price - cp_price);
        } else {
            tot_mny += (it_price - cp_price);
        }
    });

    if($("input[name=od_temp_point]").size())
        temp_point = parseInt($("input[name=od_temp_point]").val());

    tot_mny += (send_cost + send_cost2 - od_coupon - send_coupon - temp_point);
    if(tot_mny < 0) {
        comm_free_mny = comm_free_mny + tot_mny;
        tot_mny = 0;
    }

    tax_mny = Math.round(tot_mny / 1.1);
    vat_mny = tot_mny - tax_mny;
    $("input[name=comm_tax_mny]").val(tax_mny);
    $("input[name=comm_vat_mny]").val(vat_mny);
    $("input[name=comm_free_mny]").val(comm_free_mny);
}

var temp_point = 0;
function forderform_check(f)
{
    errmsg = "";
    errfld = "";
    var deffld = "";

    check_field(f.cust_memo, "요청사항을 입력하십시오.");
    check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
    check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
    //check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
    //check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
    //check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
    //check_field(f.od_zip, "");

    clear_field(f.od_email);
    if(f.od_email.value=='' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
        error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");

    if (typeof(f.od_hope_date) != "undefined")
    {
        clear_field(f.od_hope_date);
        if (!f.od_hope_date.value)
            error_field(f.od_hope_date, "수거 일자를 선택하여 주십시오.");
    }

    check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
    check_field(f.od_b_hp, "받으시는 분 휴대전화 번호를 입력하십시오.");
    check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
    //check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
    check_field(f.od_b_zip, "");

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

    var od_price = parseInt(f.od_price.value);
    var send_cost = parseInt(f.od_send_cost.value);
    var tot_price = od_price + send_cost;
    
	if(tot_price == 0) {
        document.getElementById("forderform").enctype = "multipart/form-data";
		f.submit();
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

    <?php if($default['de_pg_service'] == 'inicis') { ?>
    if( f.action != form_action_url ){
        f.action = form_action_url;
        f.removeAttribute("target");
        f.removeAttribute("accept-charset");
    }
    <?php } ?>

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

        // pay_method 설정
        <?php if($default['de_pg_service'] == 'kcp') { ?>
        f.site_cd.value = f.def_site_cd.value;
        f.payco_direct.value = "";
        switch(settle_method)
        {
            case "계좌이체":
                f.pay_method.value   = "010000000000";
                break;
            case "가상계좌":
                f.pay_method.value   = "001000000000";
                break;
            case "휴대전화":
                f.pay_method.value   = "000010000000";
                break;
            case "신용카드":
                f.pay_method.value   = "100000000000";
                break;
            case "간편결제":
                <?php if($default['de_card_test']) { ?>
                f.site_cd.value      = "S6729";
                <?php } ?>
                f.pay_method.value   = "100000000000";
                f.payco_direct.value = "Y";
                break;
            default:
                f.pay_method.value   = "무통장";
                break;
        }
        <?php } else if($default['de_pg_service'] == 'lg') { ?>
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
        <?php }  else if($default['de_pg_service'] == 'inicis') { ?>
        switch(settle_method)
        {
            case "계좌이체":
                f.gopaymethod.value = "DirectBank";
                break;
            case "가상계좌":
                f.gopaymethod.value = "VBank";
                break;
            case "휴대전화":
                f.gopaymethod.value = "HPP";
                break;
            case "신용카드":
                f.gopaymethod.value = "Card";
                f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
                break;
            case "간편결제":
                f.gopaymethod.value = "Kpay";
                break;
            case "lpay":
                f.gopaymethod.value = "onlylpay";
                f.acceptmethod.value = f.acceptmethod.value+":cardonly";
                break;
            default:
                f.gopaymethod.value = "무통장";
                break;
        }
        <?php } ?>

        // 결제정보설정
        <?php if($default['de_pg_service'] == 'kcp') { ?>
        f.buyr_name.value = f.od_name.value;
        f.buyr_mail.value = f.od_email.value;
        f.buyr_tel1.value = f.od_tel.value;
        f.buyr_tel2.value = f.od_hp.value;
        f.rcvr_name.value = f.od_b_name.value;
        f.rcvr_tel1.value = f.od_b_tel.value;
        f.rcvr_tel2.value = f.od_b_hp.value;
        f.rcvr_mail.value = f.od_email.value;
        f.rcvr_zipx.value = f.od_b_zip.value;
        f.rcvr_add1.value = f.od_b_addr1.value;
        f.rcvr_add2.value = f.od_b_addr2.value;

        if(f.pay_method.value != "무통장") {
            jsf__pay( f );
        } else {
            f.submit();
        }
        <?php } ?>
        <?php if($default['de_pg_service'] == 'lg') { ?>
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
        <?php } ?>
        <?php if($default['de_pg_service'] == 'inicis') { ?>
        f.price.value       = f.good_mny.value;
        <?php if($default['de_tax_flag_use']) { ?>
        f.tax.value         = f.comm_vat_mny.value;
        f.taxfree.value     = f.comm_free_mny.value;
        <?php } ?>
        f.buyername.value   = f.od_name.value;
        f.buyeremail.value  = f.od_email.value;
        f.buyertel.value    = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
        f.recvname.value    = f.od_b_name.value;
        f.recvtel.value     = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
        f.recvpostnum.value = f.od_b_zip.value;
        f.recvaddr.value    = f.od_b_addr1.value + " " +f.od_b_addr2.value;

        if(f.gopaymethod.value != "무통장") {
            // 주문정보 임시저장
            var order_data = $(f).serialize();
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

            if(!make_signature(f))
                return false;

            paybtn(f);
        } else {
            f.submit();
        }
        <?php } ?>
    }

}


//결제체크
function payment_check(f)
{
 var max_point = 0;
 var od_price = parseInt(f.od_price.value);
 var send_cost = parseInt(f.od_send_cost.value);
 var send_cost2 = parseInt(f.od_send_cost2.value);
 var send_coupon = parseInt(f.od_send_coupon.value);
 temp_point = 0;

 if (typeof(f.max_temp_point) != "undefined")
     var max_point  = parseInt(f.max_temp_point.value);

 if (typeof(f.od_temp_point) != "undefined") {
     if (f.od_temp_point.value)
     {
         var point_unit = parseInt(<?php echo $default['de_settle_point_unit']; ?>);
         temp_point = parseInt(f.od_temp_point.value);

         if (temp_point < 0) {
             alert("적립금를 0 이상 입력하세요.");
             f.od_temp_point.select();
             return false;
         }

         if (temp_point > od_price) {
             alert("상품 주문금액(배송비 제외) 보다 많이 적립금결제할 수 없습니다.");
             f.od_temp_point.select();
             return false;
         }

         if (temp_point > <?php echo (int)$member['mb_point']; ?>) {
             alert("회원님의 적립금보다 많이 결제할 수 없습니다.");
             f.od_temp_point.select();
             return false;
         }

         if (temp_point > max_point) {
             alert(max_point + "원 이상 결제할 수 없습니다.");
             f.od_temp_point.select();
             return false;
         }

         if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
             alert("적립금를 "+String(point_unit)+"원 단위로 입력하세요.");
             f.od_temp_point.select();
             return false;
         }

     }
 }

 var tot_price = od_price + send_cost + send_cost2 - send_coupon - temp_point;

 $("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
 $("#od_point_cost").text(number_format(String(temp_point)));

 $("#od_tot_price").text(number_format(String(tot_price)));
 $("input[name='submitChecked']").val(number_format(String(tot_price))+"원 결제");


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

// 구매자 정보와 동일합니다.
function gumae2baesong() {
    var f = document.forderform;

    f.od_b_name.value = f.od_name.value;
    f.od_b_tel.value  = f.od_tel.value;
    f.od_b_hp.value   = f.od_hp.value;
    f.od_b_zip.value  = f.od_zip.value;
    f.od_b_addr1.value = f.od_addr1.value;
    f.od_b_addr2.value = f.od_addr2.value;
    f.od_b_addr3.value = f.od_addr3.value;
    f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;
    f.ad_subject.value = f.od_name.value;

    calculate_sendcost(String(f.od_b_zip.value));

    ad_subject_change();
}
</script>
