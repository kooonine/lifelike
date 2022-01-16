<?php
include_once('./_common.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

if( isset($_GET['ini_noti']) && !isset($_GET['uid']) ){
    goto_url(G5_SHOP_URL.'/orderinquiry.php');
}

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

if (!$is_member) {
    if (get_session('ss_orderview_uid') != $_GET['uid'])
        alert("직접 링크로는 주문서 조회가 불가합니다.\\n\\n주문조회 화면을 통하여 조회하시기 바랍니다.", G5_SHOP_URL);
}

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);
if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
    alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

// 결제방법
$settle_case = $od['od_settle_case'];

switch($od['od_type']) {
    case 'O':
        $od_type_name = '제품';
        break;
    case 'R':
        $od_type_name = '리스';
        break;
    case 'L':
        $od_type_name = '세탁';
        break;
    case 'K':
        $od_type_name = '세탁보관';
        break;
    case 'S':
        $od_type_name = '수선';
        break;
    default:
        $od_type_name = '제품';
        break;
}
$od_status = $od['od_status'];

$title = '교환요청';
$subtitle = '교환';


if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquirychangeform.php');
    return;
}

$g5['title'] = $title.'|주문 상세';
include_once('./_head.php');

// LG 현금영수증 JS
if($od['od_pg'] == 'lg') {
    if($default['de_card_test']) {
    echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    } else {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    }
}


// 총계 = 주문상품금액합계 + 배송비 - 제품 할인 - 결제할인 - 배송비할인
$tot_price = $od['od_cart_price'] + $od['od_send_cost'] + $od['od_send_cost2']
            - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
            - $od['od_cancel_price'];
$sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];

?>
<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span><?php echo $title ?></span></h1>
	</div>
<!-- //lnb -->
<div class="content mypage sub">
<form method="post" name="forderform" action="<?php echo G5_SHOP_URL; ?>/orderinquirycancel.php" onsubmit="return fcancel_check(this);" autocomplete="off">
    <input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
    <input type="hidden" name="token"  value="<?php echo $token; ?>">
    <input type="hidden" name="act"  value="<?php echo $act; ?>">
    
    <!-- 컨텐츠 시작 -->
    <div class="grid">
        <p class="ico_import"><? echo $title ?>할 제품을 선택해 주세요.</p>
        
        <div class="order_cont chk_order">
            <div class="head">
                <span class="category round_green"><?php echo $od_type_name; ?></span>
				<span class="order_number">주문번호 : <strong><?php echo $od_id; ?></strong></span>
            </div>
            <?php
            $st_count1 = $st_count2 = 0;
            $custom_cancel = false;
    
            $sql = " select ct_id, it_id, it_name, cp_price, ct_send_cost, it_sc_type
                            ,ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '$od_id'
                        order by ct_id ";
            $result = sql_query($sql);
            
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $image = get_it_image($row['it_id'], 150, 150, '', '', $row['it_name']);

            ?>
            <div class="body">
                <div class="cont right_cont">
                    <span class="chk check">
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" checked="checked">
                        <input type="hidden" name="ct_id[<?php echo $i ?>]" value="<?php echo $row['ct_id'] ?>" id="ct_id_<?php echo $i ?>">
                        <input type="hidden" name="it_name[<?php echo $i ?>]" value="<?php echo $row['it_name'] ?>" id="it_name_<?php echo $i ?>">
                    </span>
                    <div class="photo"><?php echo $image; ?></div>
                    <div class="info">
                        <strong><?php echo $row['it_name']; ?></strong>
                        <?php
                        if($row['io_type'])
                            $opt_price = $row['io_price'];
                        else
                            $opt_price = $row['ct_price'] + $row['io_price'];

                        $sell_price = $opt_price * $row['ct_qty'];
                        $point = $row['ct_point'] * $row['ct_qty'];
                        ?>
                        <p><span class="txt">옵션</span><span class="point_black"><?php echo get_text($row['ct_option']) ?> / 수량<strong class="bold"><?php echo $row['ct_qty'].'개' ?></strong></span></p>
					</div>
					<div class="pay_item">
                        결제 금액<span class="amount"><strong><?php echo number_format($sell_price); ?></strong> 원</span>
                        <input type="hidden" name="stotal[<?php echo $i ?>]" value="<?php echo $sell_price ?>" id="stotal_<?php echo $i ?>">
					</div>
                    <?php
                    $tot_point       += $point;

                    $st_count1++;
                    if($row['ct_status'] == '결제완료')
                        $st_count2++;
                    ?>
                </div>
			</div>
        <?php
        }
        ?>
		</div>
		
        <div class="order_cont">
			<div class="head">
				<span class="title">교체 제품 옵션 선택</span>
			</div>
            <?php
            $st_count1 = $st_count2 = 0;
            $custom_cancel = false;
    
            $sql = " select a.it_id, a.it_name, a.cp_price, a.ct_send_cost, a.it_sc_type, a.its_no, b.its_final_price, b.its_option_subject, b.its_supply_subject
                            ,a.ct_id, a.it_name, a.ct_option, a.ct_qty, a.ct_price, a.ct_point, a.ct_status, a.io_type, a.io_price, a.io_id
                        from {$g5['g5_shop_cart_table']} as a, lt_shop_item_sub as b
                        where   a.its_no = b.its_no
                        and     a.od_id = '$od_id'
                        order by a.ct_id ";
            $result = sql_query($sql);
            
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $image = get_it_image($row['it_id'], 150, 150, '', '', $row['it_name']);
            ?>
            <div class="body">
                <div class="cont right_cont">
                    <div class="photo">
                        <div class="photo"><?php echo $image; ?></div>
                    </div>
                    <div class="info">
    					<input type="hidden" name="it_id[]" value="<?php echo $row['it_id']; ?>">
						<input type="hidden" name="its_final_price[]" value="<?php echo $row['its_final_price']; ?>" its_no="<?php echo $row['its_no'] ?>">
						
                        <strong><?php echo $row['it_name']; ?></strong>
                        <p>
							<span class="txt" id="spn_it_option_<?php echo $i?>"><?php echo $row['its_option_subject']?></span>
							<span class="point_black">
                            	<?php
                            	$io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '{$row['it_id']}' and its_no = '{$row['its_no']}' and io_use = '1' order by io_no asc ";
                            	$io_result = sql_query($io_sql);
                            	
                            	$io_select = '<select id="it_option_'.$i.'" name="sel_it_option[]" its_no="'.$row['its_no'].'" class="btn_select">'.PHP_EOL;
                            	$io_select .= '<option value="">선택</option>'.PHP_EOL;
                            	for($j=0; $io_row=sql_fetch_array($io_result); $j++) {
                            	    
                            	    if($io_row['io_price'] >= 0) {
                            	        $price = '&nbsp;&nbsp;+ '.number_format($io_row['io_price']).'원';
                            	    } else {
                            	        $price = '&nbsp;&nbsp; '.number_format($io_row['io_price']).'원';
                            	    }
                            	    $io_stock_qty = get_option_stock_qty($row['it_id'], $io_row['io_id'], $io_row['io_type']);
                            	    
                            	    if($io_stock_qty < 1) {
                            	        $soldout = '&nbsp;&nbsp;[품절]';
                            	    } else {
                            	        $soldout = '';
                            	    }
                            	    
                            	    $io_select .= '<option value="'.$io_row['io_id'].','.$io_row['io_price'].','.$io_row['io_stock_qty'].'" io_price="'.$io_row['io_price'].'" io_stock_qty="'.$io_row['io_stock_qty'].'" '.(($io_row['io_id']==$row['io_id'])?"selected":"").'  >'.$io_row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
                            	}
                            	$io_select .= '</select>'.PHP_EOL;
                            	
                            	echo $io_select.PHP_EOL;
                            	
                            	?>
                            </span>
                         </p>
					<?php 
					if($row['its_supply_subject']) {
					    $it_supply_subjects = explode(',', $row['its_supply_subject']);
					    $supply_count = count($it_supply_subjects);
					    
					    $it_supply_subjects_select = explode(',', $row['ct_option']);
					    
					    for($j=0; $j<$supply_count; $j++) {
					        echo '<p><span class="txt" id="spn_it_supply_'.$j.'">'.$it_supply_subjects[$j].'</span><span class="point_black">';
					        
					        $io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '1' and it_id = '{$row['it_id']}' and its_no = '{$row['its_no']}' and io_use = '1' and io_id like '{$it_supply_subjects[$j]}%' order by io_no asc ";
					        $io_result = sql_query($io_sql);
					        
					        $io_select = '<select id="it_supply_'.$j.'" name="sel_it_supply[]" its_no="'.$row['its_no'].'" class="btn_select">'.PHP_EOL;
					        $io_select .= '<option value="">선택</option>'.PHP_EOL;
					        for($k=0; $io_row=sql_fetch_array($io_result); $k++) {
					            //$io_id = str_replace($it_supply_subjects[$j], "", $io_row['io_id']);
					            $opt_id = explode(chr(30), $io_row['io_id']);
					            
					            if($io_row['io_price'] >= 0) {
					                $price = '&nbsp;&nbsp;+ '.number_format($io_row['io_price']).'원';
					            } else {
				                    $price = '&nbsp;&nbsp; '.number_format($io_row['io_price']).'원';
				                }
				                $io_stock_qty = get_option_stock_qty($row['it_id'], $io_row['io_id'], $io_row['io_type']);
			                    
				                if($io_stock_qty < 1) {
			                        $soldout = '&nbsp;&nbsp;[품절]';
				                } else {
		                            $soldout = '';
				                }
				                $io_select .= '<option value="'.$io_row['io_id'].','.$io_row['io_price'].','.$io_row['io_stock_qty'].'" io_price="'.$io_row['io_price'].'" io_stock_qty="'.$io_row['io_stock_qty'].'"  '.(($opt_id[1]==trim($it_supply_subjects_select[$j+1]))?"selected":"").' >'.$opt_id[1].$price.$soldout.'</option>'.PHP_EOL;
					        }
					        $io_select .= '</select>'.PHP_EOL;
					        
					        echo $io_select.PHP_EOL;
					        
					        echo' </span></p>';
					    }
					}
					?>
					<div id="sit_sel_option">
					<li>
					<p>
						<span class="txt">수량</span>
						<span class="point_black">
                            <input type="hidden" name="io_type[<?php echo $row['it_id'] ?>][]" value="<?php echo $row['io_type']?>">
                            <input type="hidden" name="io_id[<?php echo $row['it_id'] ?>][]" value="<?php echo $row['io_id']?>">
                            <input type="hidden" name="io_value[<?php echo $row['it_id'] ?>][]" value="<?php echo $row['ct_option']?>">
                            <input type="hidden" class="it_price" value="<?php echo $row['ct_price']?>" it_id="<?php echo $row['it_id'] ?>">
                            <input type="hidden" class="io_price" value="<?php echo $row['io_price']?>" it_id="<?php echo $row['it_id'] ?>">
                            <span class="count_control">
                                <em class="num">
                                    <span class="blind">현재수량</span>
                                    <span><input type="text" name="ct_qty[<?php echo $row['it_id']; ?>][]" value="<?php echo $row['ct_qty']; ?>" id="ct_qty_<?php echo $i; ?>" class="num_input" size="5"  style="height:18px;"></span>
                                </em>
                                <button type="button" class="count_minus"><span class="blind">감소</span></button>
                                <button type="button" class="count_plus"><span class="blind">증가</span></button>
                            </span>
                        </span>
                    </p>
                    </li>
                    </div>
                </div>
                <div class="pay_item">
                	결제 금액 <span class="amount"><strong><?php echo display_price($row['its_final_price']) ?> 원</strong></span>
				</div>
            </div>
        </div>
		<?php } ?>
	
        <div class="body">
            <div class="order_input">
                <div class="inp_wrap">
                    <div class="title count3"><label for="f_014">금액</label></div>
                    <div class="inp_ele count6 alignR">
                    	<em class="point" id="sit_tot_price"><?php echo number_format($od['od_receipt_price']); ?> 원</em>
                    </div>
                </div>
                <div class="inp_wrap">
                    <div class="title count3"><label for="f_015">교환 차액</label></div>
                    <div class="inp_ele count6 alignR">
                        <em class="point">0 원</em>
                    </div>
                </div>
			</div>
		</div>
		
	</div>
    
    <?php if($act == "return" || $act == "change") { ?>
    <div class="grid">
        <div class="title_bar">
            <h3 class="g_title_01"><?php echo $subtitle ?> 수거 요청</h3>
        </div>
        
		<div class="inp_wrap">
			<div class="title count3"><label>이름</label></div>
			<div class="inp_ele count6">
                <div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" placeholder="이름 입력" value="<?php echo $od['od_b_name']?>"></div>
			</div>
			<input type="hidden" name="ad_subject" id="ad_subject" value="<?php echo $row['ad_subject'] ?>">
		</div>
		<div class="inp_wrap">
			<div class="title count3"><label>연락처</label></div>
			<div class="inp_ele count6">
                <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20" placeholder="연락처 입력" value="<?php echo $od['od_b_tel']?>"></div>
			</div>
		</div>
		<div class="inp_wrap">
			<div class="title count3"><label>휴대전화 번호</label></div>
			<div class="inp_ele count6">
                <div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력" value="<?php echo $od['od_b_hp']?>"></div>
			</div>
		</div>

        <div class="inp_wrap">
            <div class="title count9">
                <label for="join7">제품 수거지 주소</label>
                <a href="<?php echo G5_SHOP_URL ?>/orderaddress.php" id="order_address" ><button type="button" class="btn green_line round floatR"><span>배송지 관리</span></button></a>
            </div>
        </div>

        <div class="inp_wrap">
            <div class="title count3">
                <label for="join77"class="blind">주소</label>
            </div>
            
            <div class="inp_ele count6 r_btn_80">
                <div class="input"  onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">
                	<input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required disabled readonly" size="5" maxlength="6" readonly="readonly" value="<?php echo $od['od_b_zip1'].$od['od_b_zip2']?>">
                </div>
                <button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">우편번호</button>
            </div>
        </div>
        <div class="inp_wrap">
            <div class="title count3">
                <label for="join77_2" class="blind">주소 상세</label>
            </div>
            <div class="inp_ele count6 ">
                <div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required disabled readonly" readonly="readonly" value="<?php echo $od['od_b_addr1']?>"></div>
            </div>
        </div>
        <div class="inp_wrap">
            <div class="title count3">
                <label for="join77_2" class="blind">주소 상세</label>
            </div>
            <div class="inp_ele count6 ">
                <div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address" value="<?php echo $od['od_b_addr2']?>">
                <input type="hidden" name="od_b_addr3" id="od_b_addr3"  value="<?php echo $od['od_b_addr3']?>">
                <input type="hidden" name="od_b_addr_jibeon" value="<?php echo $od['od_b_addr_jibeon']?>"></div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="grid">
		<div class="order_title">
			<span class="item"><? echo $subtitle ?> 예정 금액</span>
			<strong class="result">
				<em class="big point_red" id="cancel_price"><?php echo number_format($od['od_receipt_price']); ?> 원</em>
			</strong>
		</div>
		<div class="divide_three box stick">
			<div class="box">
				<div class="order_title white">
                    <span class="item">주문 금액</span>
                    <strong class="result">
                        <em class="bold"><?php echo number_format($tot_price); ?> 원</em>
                    </strong>
                </div>
                <div class="order_list result_right">
                    <ul>
                        <li>
                            <span class="item">제품 금액</span>
                            <strong class="result"><?php echo number_format($od['od_cart_price']); ?> 원</strong>
                        </li>
                        <!-- li>
                            <span class="item">세탁 금액</span>
                            <strong class="result ">40,000 원</strong>
                        </li>
                        <li>
                            <span class="item">보관 금액</span>
                            <strong class="result ">0 원</strong>
                        </li>
                        <li>
                            <span class="item">수선 금액</span>
                            <strong class="result ">후불</strong>
                        </li -->
                        <li>
                            <span class="item">배송비</span>
                            <strong class="result"><?php echo ($od['od_send_cost'] > 0)?number_format($od['od_send_cost']).원:"무료"; ?> </strong>
                        </li>
                        <?php if ($od['od_send_cost2'] > 0) { ?>
                        <li>
                            <span class="item">추가배송비</span>
                            <strong class="result"><?php echo number_format($od['od_send_cost2']); ?> 원</strong>
                        </li>
                        <?php } ?>
                        <?php if ($od['od_cancel_price'] > 0) { ?>
                        <li>
                            <span class="item"><? echo $subtitle ?>금액</span>
                            <strong class="result"><?php echo number_format($od['od_cancel_price']); ?> 원</strong>
                        </li>
                        <?php } ?>
                        <?php if ($tot_point > 0) { ?>
                        <li>
                            <span class="item">적립금</span>
                            <strong class="result"><?php echo number_format($tot_point); ?> 원</strong>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
			</div>
			<div class="box">
                <div class="order_title white">
                    <span class="item">할인 금액</span>
                    <strong class="result">
                        <em class="bold"><?php echo number_format($sale_price); ?> 원</em>
                    </strong>
                </div>
                <div class="order_list result_right">
                    <ul>
                        <?php if($od['od_cart_coupon'] > 0) { ?>
                        <li>
                            <span class="item">제품 할인</span>
                            <strong class="result"><?php echo number_format($od['od_cart_coupon']); ?> 원</strong>
                        </li>
                        <?php } ?>
                        <?php if($od['od_coupon'] > 0) { ?>
                        <li>
                            <span class="item">결제할인</span>
                            <strong class="result"><?php echo number_format($od['od_coupon']); ?> 원</strong>
                        </li>
                        <?php } ?>
        
                        <?php if($od['od_send_coupon'] > 0) { ?>
                        <li>
                            <span class="item">배송비할인</span>
                            <strong class="result"><?php echo number_format($od['od_send_coupon']); ?> 원</strong>
                        </li>
                        <?php } ?>
                        <!-- li>
                            <span class="item">제품 할인</span>
                            <strong class="result">10,000 원</strong>
                        </li>
                        <li>
                            <span class="item">쿠폰 할인</span>
                            <strong class="result">0 원</strong>
                        </li>
                        <li>
                            <span class="item">적립금 할인</span>
                            <strong class="result">0 원</strong>
                        </li -->
                    </ul>
                </div>
			</div>
			<div class="box">
				<div class="order_title white">
                    <span class="item">적립금 결제</span>
                    <strong class="result">
                        <em class=""><?php echo number_format($od['od_receipt_point']); ?> 원</em>
                    </strong>
                </div>
        	</div>
		</div>
	</div>
	
	<div class="grid">
        <div class="title_bar">
            <h2 class="g_title_01"><? echo $subtitle ?> 사유 선택</h2>
        </div>
        <div class="inp_wrap">
            <label for="f40" class="blind">제목</label>
            <div class="inp_ele">
                <span class="sel_box">
                    <select name="cancel_select" id="cancel_select">
                        <option value="" selected="">선택</option>
                        <option value="서비스불만족">서비스 불만족</option>
                        <option value="상품파손">상품파손</option>
                        <option value="상품정보상이">상품정보 상이</option>
                        <option value="오배송">오배송</option>
                        <option value="색상및사이즈변경">색상 및 사이즈 변경</option>
                        <option value="다른상품잘못주문 ">다른 상품 잘못 주문 </option>
                    </select>
                </span>
            </div>
        </div>
        <div class="inp_wrap">
            <label for="f5" class="blind">내용</label>
            <div class="inp_ele">
                <div class="input"><textarea name="cancel_memo" id="cancel_memo" rows="6" cols="20" required placeholder="상세 사유를 최대 50자 이내로 입력 해 주세요" maxlength="50"></textarea></div>
            </div>
        </div>
	</div>
    
    <div class="grid">
        <hr class="full_line">
		<?php if($act == "change") { ?>
		
		
		<?php } elseif($act == "return") { ?>

        <div class="info_box">
            <p class="ico_import red point_red">반품 신청 안내</p>
            <div class="list">
                <ul class="hyphen">
                    <li>해당  상품을 반품하려는 사유를 정확하게 기재해 주세요.</li>
                    <li>할인쿠폰, 즉시 할인으로 인해 환불 금액이 다를 수 있습니다.</li>
                    <li>환불 완료 후 주문 상세 현황에서 자세한 환불 내역을 확인하실 수 있습니다.</li>
                    <li>환불 처리를 위해 계좌 정보를 수집 이용하며, 입력하신 정보는 환불 목적으로만 사용됩니다.</li>
                </ul>
            </div>
        </div>
		
		<?php } else { ?>
        <div class="info_box">
            <p class="ico_import red point_red">주문 취소 안내</p>
            <div class="list">
                <ul class="hyphen">
                    <li>해당  상품을 취소하려는 사유를 정확하게 기재해 주세요.</li>
                    <li>묶음배송 상품 중 일부를 취소하실 경우 배송비는 고객이 부담해야 합니다.</li>
                    <li>할인 쿠폰, 즉시 할인으로 인해 환불 금액이 다를 수 있습니다.</li>
                    <li>환불 완료 후 주문 상세 현황에서 자세한 환불 내역을 확인하실 수 있습니다.</li>
                    <li>환불 처리를 위해 계좌 정보를 수집 이용하며, 입력하신 정보는 환불 목적으로만 사용됩니다.</li>
                </ul>
            </div>
        </div>
        <?php } ?>
        <div class="btn_group">
        	<button type="submit" class="btn big border"><span><?php echo $title?></span></button>
        </div>
    </div>
	</form>
	
</div>

<!-- The Modal -->
<div id="optionModal" class="modal" style="display: none;">
<!-- Modal content -->       
    <div class="content sub">
    	<div style="float: right;">
    		<a href="#" class="close"><span class="blind">닫기</span></a>
    	</div>
    	<div class="grid cont" style="border-top-width: 0px;">
    		<div class="title_bar" style="overflow:visible;">
    			<h3 class="g_title_01" id="optionModalTitle">선택한 :<span class="none"></span></h3>
    		</div>
    		<div class="list">
    			<ul class="type1 pad"  id="optionModalList">
    			</ul>
    		</div>
    
    	</div>
    </div>
</div>
<!--End Modal-->

<script>
$(function() {

    // 배송지목록
    $("#order_address,#order_address1").on("click", function() {
        var url = this.href;
        window.open(url, "win_address", "left=100,top=100,width=650,height=500,scrollbars=1");
        return false;
    });
    	
    $(".it_option").click(function() {
    	var seq = $(this).attr("SEQ");
    	
    	var optionName = $(this).text();
    	var title = $('#spn_it_option_'+seq).text();
    	$('#optionModalTitle').html("선택한 "+title+":<span class=\"none\">"+optionName+"</span></h3>");

    	var optionList = "";
    	var $option = $('#it_option_'+seq+' option');

    	$option.each(function() {
    		io_id = $(this).text();
    		io_price = $(this).attr("io_price");
    		io_price_mark = (parseInt(io_price) > 0)?"+":"";
    		
    		io_stock_qty = $(this).attr("io_stock_qty");
    		
    		io_value = $(this).val();
    		

        	if(io_value != ""){
            	if(io_stock_qty < 1) {
                	//품절
            		optionList += "<li class=\"soldout\">";
            		optionList += "<a >";
            		optionList += "<span class=\"bold point_black\">"+io_id+"</span>";
            		optionList += "<span class=\"r_box point\">"+io_price_mark+number_format(io_price)+"</span>";
        			optionList += "</a></li>";
            	} else {
            		optionList += "<li>";
            		optionList += "<a onclick='io_option_select(\"it_option_"+seq+"\", \""+io_value+"\");' >";
            		optionList += "<span class=\"bold\">"+io_id+"</span>";
            		optionList += "<span class=\"r_box point\">"+io_price_mark+number_format(io_price)+"</span>";
        			optionList += "</a></li>";
            	}
        	}
        });
    	
    	$('#optionModalList').html(optionList);

    	$('#it_option_'+seq).val("");
    	$("#optionModal").css("display","block");
    });
    	
    $(".it_supply").click(function() {
    	var seq = $(this).attr("SEQ");
    	
    	var optionName = $(this).text();
    	var title = $('#spn_it_supply_'+seq).text();
    	$('#optionModalTitle').html("선택한 "+title+":<span class=\"none\">"+optionName+"</span></h3>");

    	var optionList = "";
    	var $option = $('#it_supply_'+seq+' option');

    	$option.each(function() {
    		io_id = $(this).text();
    		io_price = $(this).attr("io_price");
    		io_stock_qty = $(this).attr("io_stock_qty");
    		
    		io_value = $(this).val();

        	if(io_value != ""){
            	if(io_stock_qty < 1) {
                	//품절
            		optionList += "<li class=\"soldout\">";
            		optionList += "<a >";
            		optionList += "<span class=\"bold point_black\">"+io_id+"</span>";
            		optionList += "<span class=\"r_box point\">"+number_format(io_price)+"</span>";
        			optionList += "</a></li>";
            	} else {
            		optionList += "<li>";
            		optionList += "<a onclick='io_supply_select(\"it_supply_"+seq+"\", \""+io_value+"\");' >";
            		optionList += "<span class=\"bold\">"+io_id+"</span>";
            		optionList += "<span class=\"r_box point\">"+number_format(io_price)+"</span>";
        			optionList += "</a></li>";
            	}
        	}
        });
    	
    	$('#optionModalList').html(optionList);

    	$('#it_supply_'+seq).val("");
    	$("#optionModal").css("display","block");
    });

	$("a[name='modalClose']").click(function() {
    	$(".modal").css("display","none");
    });
    
    $(".close").click(function() {
    	$(".modal").css("display","none");
    });;

	$("input[name='chk[]']").click(function() {
		var cancel_price = 0;

		$("input[name='chk[]']").each(function() {
			if($(this).is(":checked")){
				i = $(this).val();
				cancel_price = cancel_price +  parseInt($("#stotal_"+i).val());
			}
		});
		$("#cancel_price").html(number_format(cancel_price) + "원");
		
    });
});

function fcancel_check(f)
{
    var checked = false;
    var chk = document.getElementsByName("chk[]");
    for (var i=0; i<chk.length; i++) {
        if (chk[i].checked) {
            checked = true;
        }
    }
    
    if (!checked) {
        alert("<?php echo $subtitle?> 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
    
    var cancel_select = f.cancel_select.value;
    if(cancel_select == "") {
        alert("<?php echo $subtitle?>사유를 선택해 주십시오.");
        return false;
    }
    var memo = f.cancel_memo.value;
    if(memo == "") {
        alert("<?php echo $subtitle?>사유를 입력해 주십시오.");
        return false;
    }
    
    if(!confirm("주문을 정말 <?php echo $subtitle?>하시겠습니까?"))
        return false;

    return true;
}

function io_option_select(optionid, optionval){
	//alert(optionid+","+optionval);
	
	$('#'+optionid).val(optionval);
	
	$('#btn_'+optionid).text($('#'+optionid+" option:selected").text());
	$(".modal").css("display","none");

	var its_no = $('#'+optionid).attr("its_no");
	
	var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='"+its_no+"']");
    if($sel_it_supply.size() > 0){
    	$sel_it_supply.each(function() {
    		$(this).val("");
    		optionid = $(this).attr("id");
    		$('#btn_'+optionid).text("선택");
    	});
    }

	//alert($('#'+optionid).attr("its_no"));

	add_sel_option_mobile_chk(its_no);
}

function io_supply_select(optionid, optionval){
	//alert(optionid+","+optionval);
	
	$('#'+optionid).val(optionval);
	
	$('#btn_'+optionid).text($('#'+optionid+" option:selected").text());
	$(".modal").css("display","none");

	add_sel_option_mobile_chk($('#'+optionid).attr("its_no"));
}

function add_sel_option_mobile_chk(its_no){
    var add_exec = true;
    
    var $sel_it_option = $("select[name='sel_it_option[]'][its_no='"+its_no+"']");
    if($sel_it_option.val() == "") add_exec = false;
    
    var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='"+its_no+"']");
    if($sel_it_supply.size() > 0){
    	$sel_it_supply.each(function() {
    		if($(this).val() == "") add_exec = false;
    	});
    }
    
    //add_option
    if(add_exec){
    	var id = "";
        var value, info, sel_opt, item, price, stock, run_error = false;
        var option = sep = "";

        var it_price = parseInt($("input[name='its_final_price[]'][its_no='"+its_no+"']").val());
    	//var it_price = parseInt($("input#it_price").val());
    	var item = $sel_it_option.closest("li").find("span[id^=spn_it_option]").text();
    
        value = $sel_it_option.val();
        info = value.split(",");
        sel_opt = info[0];
        id = sel_opt;
        option += sep + item + ":" + sel_opt;
    
        price = info[1];
        stock = info[2];

        $sel_it_supply.each(function() {
    		//if($(this).val() == "") add_exec = false;
			value = $(this).val();
        	info = value.split(",");
            sel_opt = info[0].split(chr(30))[1];
        	
            //id += chr(30)+sel_opt;
            sep = " , ";
            option += sep + sel_opt;
            price = parseInt(price)+parseInt(info[1]);
    	});
    
        //alert(option);
        
        if(same_option_check(option))
            return;
    
        add_sel_option_mobile(0, id, option, price, stock, it_price, its_no);
    }
    
    function add_sel_option_mobile(type, id, option, price, stock, it_price, its_no)
    {
        alert(option);
    }
}
</script>

<?php
include_once('./_tail.php');
?>