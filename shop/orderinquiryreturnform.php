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

$title = '반품요청';
$subtitle = '반품';
$action_url = G5_SHOP_URL."/orderinquiryreturn.php";
$partCancel = true;

switch($od['od_type']) {
    case 'O':
        $od_type_name = '제품';
        break;
    case 'R':
        $od_type_name = '리스';
        $title = '철회요청';
        $subtitle = '철회';
        $action_url = G5_SHOP_URL."/orderinquiryreturn_r.php";
        $partCancel = false;
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

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquiryreturnform.php');
    return;
}

$g5['title'] = $title.'|주문 상세';
include_once('./_head.php');

// 총계 = 주문상품금액합계 + 배송비 - 제품 할인 - 결제할인 - 배송비할인
$tot_price = $od['od_cart_price'] + $od['od_send_cost'] 
            - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
            - $od['od_cancel_price'];

$sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];


if(($od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point']) > 0){
    /* 2019-07-30 김석근 파트장 요청
     * 문제 2. 포인트, 할인쿠폰(%) 사용시 부분취소건에 대해서 해당 %만큼 같이 취소가 안됩니다.
     해결방안 : 포인트 및 할인쿠폰을 사용시에는 부분취소가 안되도록하고 전체 취소만 되도록 처리해주세요.
     => 포인트,할인쿠폰은 제품에만 적용됨.
     */
    $partCancel = false;
}

?>
<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>
<!-- container -->
<div id="container">
		<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
		<div class="content mypage sub">
	<form method="post" name="forderform" action="<?php echo $action_url; ?>" onsubmit="return fcancel_check(this);" autocomplete="off">
    <input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
    <input type="hidden" name="token"  value="<?php echo $token; ?>">
    <input type="hidden" name="act"  value="<?php echo $act; ?>">
    <input type="hidden" name="od_send_cost2" id="od_send_cost2" value="<?php echo $default['de_return_costs']; ?>">
    <input type="hidden" name="od_receipt_price" id="od_receipt_price" value="<?php echo $od['od_receipt_price']-$od['od_refund_price']; ?>">
    <input type="hidden" name="od_send_cost"  value="<?php echo $od['od_send_cost']; ?>">
    <input type="hidden" name="ct_id_arr"  value="">
    
    <!-- 컨텐츠 시작 -->
    <div class="grid">
        <p class="ico_import red point_red"><? echo $title ?>할 제품을 선택해 주세요.</p>
        
        <div class="order_cont chk_order">
            <div class="head">
                <span class="category round_green"><?php echo $od_type_name; ?></span>
				<span class="order_number">주문번호 : <strong><?php echo $od_id; ?></strong></span>
            </div>
            <?php
            $st_count1 = $st_count2 = 0;
            $sql = "select a.*, ((b.its_price + a.io_price) * a.ct_qty) as before_price
                                from   lt_shop_cart as a
                                        left join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
                                        left join lt_shop_item c on ( a.it_id = c.it_id )
                            where a.od_id = '$od_id' and a.ct_status = '배송완료'
                            order by c.ca_id3 desc, a.it_sc_type desc, a.it_id ";
            
            $result = sql_query($sql);
            
            for($i=0; $row=sql_fetch_array($result); $i++) {
                $image = get_it_image($row['it_id'], 150, 150, '', '', $row['it_name']);
                $ct_send_cost = $row['ct_send_cost'];
            ?>
            <div class="body">
                <div class="cont right_cont">
                    <span class="chk check">
                    	<?php if($partCancel) { ?>
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" checked="checked">                        
                        <?php } else { 
                        //부분취소 불가 ?>
                        <input type="hidden" name="chk[]" value="<?php echo $i ?>" >
                        <?php } ?>
                        <input type="hidden" name="ct_id[<?php echo $i ?>]" value="<?php echo $row['ct_id'] ?>" id="ct_id_<?php echo $i ?>">
                        <input type="hidden" name="it_name[<?php echo $i ?>]" value="<?php echo $row['it_name'] ?>" id="it_name_<?php echo $i ?>">
                    </span>
                    <div class="photo"><?php echo $image; ?></div>
                    <div class="info" id="sit_sel_option">
					<li>
                        <strong><a href="./item.php?it_id=<?php echo $row['it_id']?>"><?php echo $row['it_name']; ?></a></strong>
                        <?php
                        $opt_price = $row['ct_price'] + $row['io_price'];
                        $sell_price = $opt_price * $row['ct_qty'];
                        $point = $row['ct_point'] * $row['ct_qty'];
                        ?>
                        <p><span class="txt">옵션</span><span class="point_black"><?php echo get_text($row['ct_option']) ?> / 수량<strong class="bold"><?php echo $row['ct_qty'].'개' ?></strong></span></p>
						<p><span class="txt">수량</span> 
							<span class="sel_box" style="width:100px;">
								<select name="ct_qty[<?php echo $i ?>]">
									<?php 
									echo "<option value='".$row['ct_qty']."' selected>".$row['ct_qty']."</option>";
									/*for ($q = 1; $q <= (int)$row['ct_qty']; $q++) {
									    echo "<option value='".$q."' ".(($q==$row['ct_qty'])?"selected":"")." >".$q."</option>";
									}*/
									?>
								</select>
                            </span>
						</p>
					</li>
 					</div>
 					<?php if($od['od_type'] == "R") {
 					    
 					    $opt_price = $row['ct_rental_price'] + $row['io_price'];
 					    $sell_price = $opt_price * $row['ct_qty'];
 					    $point = $row['ct_point'] * $row['ct_qty'];
 					?>
					<div class="pay_item">
                        리스 금액<span class="amount"><strong><?php echo number_format($sell_price); ?></strong> 원</span>
                        <input type="hidden" name="stotal[<?php echo $i ?>]" value="0" id="stotal_<?php echo $i ?>">
					</div>
					<?php } else {?>
					<div class="pay_item">
                        결제 금액<span class="amount"><strong><?php echo number_format($sell_price); ?></strong> 원</span>
                        <input type="hidden" name="stotal[<?php echo $i ?>]" value="<?php echo $sell_price ?>" id="stotal_<?php echo $i ?>">
                        <input type="hidden" name="ct_send_cost[<?php echo $i ?>]" value="<?php echo $ct_send_cost ?>" id="ct_send_cost_<?php echo $i ?>">
					</div>
					<?php }?>
                    <?php
                    $tot_point += $point;

                    $st_count1++;
                    if($row['ct_status'] == '배송완료')
                        $st_count2++;
                    ?>
                </div>
			</div>
        <?php
        }
        ?>
		</div>
	</div>
    
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
                <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input" maxlength="20" placeholder="연락처 입력" value="<?php echo $od['od_b_tel']?>"></div>
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
                        <option value="다른상품잘못주문">다른 상품 잘못 주문 </option>
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
    


    <!--  -->
    <div class="grid">
        <div class="divide_two box stick">
            <div class="box">
                <div class="order_title">
                    <span class="item">결제 금액</span>
                    <strong class="result">
                    <em class="big"> <?=number_format($od['od_receipt_price'] - $od['od_refund_price']); ?> 원</em>
                    </strong>
                </div>
                <div class="order_title white">
                    <?php if($od['od_type'] == "R") {?>
                    <span class="item">계약 금액</span>
                    <?php } else { ?>
                    <span class="item">주문 금액</span>
                    <?php } ?>
                    <strong class="result">
                        <em class="bold"><?php echo number_format($tot_price); ?> 원</em>
                    </strong>
                </div>
                <div class="order_list result_right">
                    <ul>
                        <li>
                            <?php if($od['od_type'] == "R") {?>
                            <span class="item">리스료</span>
                            <strong class="result"><?php echo number_format($od['rt_rental_price']); ?> 원</strong>
                            <?php } else { ?>
                            <span class="item">제품 금액</span>
                            <strong class="result"><?php echo number_format($od['od_cart_price']); ?> 원</strong>
                            <?php } ?>
                        </li>
                        <li>
                            <span class="item">배송비</span>
                            <strong class="result"><?php echo ($od['od_send_cost'] > 0)?number_format($od['od_send_cost'])." 원":"무료"; ?> </strong>
                        </li>              
                    </ul>
                </div>
            </div>
            <div class="box">
                <div class="order_title">
                    <span class="item">반품 예정 금액
                        <span>(총 반품 배송비)</span>
                    </span>
                    <strong class="result">
                        <em class="big point_red" id="cancel_price">
                            <?php echo number_format($od['od_receipt_price']-$od['od_refund_price']); ?> 원               
                        </em>
                        <span id="return_cost_price">(0 원)</span>
                    </strong>
                </div>
                <div class="order_title white">
                    <span class="item">할인 금액</span>
                    <strong class="result">
                        <em>
                            <? if($sale_price > 0) { ?>
                                - <?=number_format($sale_price); ?> 원
                            <? } else { ?>
                                <?=number_format($sale_price); ?> 원
                            <? } ?>
                        </em>
                    </strong>
                </div>
                <div class="order_list result_right">
                    <ul>
                        <li>
                            <span class="item">쿠폰 할인</span>
                            <strong class="result">
                                <? if($od['od_coupon'] > 0) { ?>
                                    - <?=number_format($od['od_coupon']); ?> 원
                                <? } else { ?>
                                    <?=number_format($od['od_coupon']); ?> 원
                                <? } ?>
                            </strong>
                        </li> 
                        <li>
                            <span class="item">적립금 할인</span>
                            <strong class="result">
                                <? if($od['od_receipt_point'] > 0) { ?>
                                    - <?=number_format($od['od_receipt_point']); ?> 원
                                <? } else { ?>
                                    <?=number_format($od['od_receipt_point']); ?> 원
                                <? } ?>                                                
                            </strong>
                        </li>                 
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--  -->


    <!-- <div class="grid">
		<?
		/*
		echo "제품 : ".$od['od_cart_price']."<br/>";
		echo "배송비 : ".$od['od_send_cost']."<br/>";
		echo "추가배송비 : ".$od['od_send_cost2']."<br/>";
		echo "?".$od['od_cart_coupon']."<br/>";
		echo "쿠폰 : ".$od['od_coupon']."<br/>";
		echo "?".$od['od_send_coupon']."<br/>";
		echo "취소금액 : ".$od['od_cancel_price']."<br/>";
		echo "적립금사용금액 : ".$od['od_receipt_point']."<br/>";
		*/
		?>
		<div class="order_title">
			<span class="item">환불 예정 금액</span>
			<strong class="result">
				<em class="big point_red" id="cancel_price">
                    <?php echo number_format($od['od_receipt_price']-$od['od_refund_price']); ?> 원
                </em>
			</strong>
		</div>
		<div class="divide_three box stick">
			<div class="box">
				<div class="order_title white">
					<?php if($od['od_type'] == "R") {?>
                    <span class="item">계약 금액</span>
                    <?php } else { ?>
                    <span class="item">주문 금액</span>
                    <?php } ?>
                    <strong class="result">
                        <em class="bold"><?php echo number_format($tot_price); ?> 원</em>
                    </strong>
                </div>
                <div class="order_list result_right">
                    <ul>
                        <li>
                            <?php if($od['od_type'] == "R") {?>
                            <span class="item">리스료</span>
                            <strong class="result"><?php echo number_format($od['rt_rental_price']); ?> 원</strong>
                            <?php } else { ?>
                            <span class="item">제품 금액</span>
                            <strong class="result"><?php echo number_format($od['od_cart_price']); ?> 원</strong>
                            <?php } ?>
                        </li>
                        <li>
                            <span class="item">배송비</span>
                            <strong class="result"><?php echo ($od['od_send_cost'] > 0)?number_format($od['od_send_cost'])."원":"무료"; ?> </strong>
                        </li>
                        <?php if ($od['od_send_cost2'] > 0) { ?>
                        <li>
                            <span class="item">추가배송비</span>
                            <strong class="result"><?php echo number_format($od['od_send_cost2']); ?> 원</strong>
                        </li>
                        <?php } ?>
                        <?php if ($od['od_cancel_price'] > 0) { ?>
                        <li>
                            <span class="item">취소 금액</span>
                            <strong class="result">-<?php echo number_format($od['od_cancel_price']); ?> 원</strong>
                        </li>
                        <?php } ?>
                        <?php if ($od['od_refund_price'] > 0) { ?>
                        <li>
                            <span class="item">환불 금액</span>
                            <strong class="result">-<?php echo number_format($od['od_refund_price']); ?> 원</strong>
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
	</div> -->
    
    <div class="grid">
        <hr class="full_line">

        <div class="info_box">
            <p class="ico_import red point_red"><?php echo $subtitle ?> 신청 안내</p>
            <div class="list">
                <ul class="hyphen">
                    <li>해당  상품을 <?php echo $subtitle ?>하려는 사유를 정확하게 기재해 주세요.</li>
                    <li>할인쿠폰, 즉시 할인으로 인해 환불 금액이 다를 수 있습니다.</li>
                    <li>환불 완료 후 주문 상세 현황에서 자세한 환불 내역을 확인하실 수 있습니다.</li>
                    <li>환불 처리를 위해 계좌 정보를 수집 이용하며, 입력하신 정보는 환불 목적으로만 사용됩니다.</li>
                </ul>
            </div>
        </div>
		
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
		cancel_price_chk();
    });
    
	$("#cancel_select").change(function() {
		cancel_price_chk();
	});
	
});

function cancel_price_chk(){
	var cancel_price_str = '';
	var cancel_price = 0; //상품금액
	var cancel_od_send_cost = 0; //상품의 배송비
	var tot_cancel_price = 0; //환불예정금액
	
	var cancel_send_costs = 0; //반품배송비
	var od_send_cost = $("input[name='od_send_cost']").val();
	var ct_id_arr = new Array();
	
	<?php if($od['od_type'] == "R" || !$partCancel) { ?>

    cancel_price = $("#od_receipt_price").val();
	tot_cancel_price = cancel_price; //결제한 금액 전체
	    
	<?php } else if($partCancel){ ?>
	$("input[name='chk[]']").each(function() {
		i = $(this).val();
		
		if($(this).is(":checked")){
			cancel_price = cancel_price +  parseInt($("#stotal_"+i).val());
		} else {
			ct_id = $("#ct_id_"+i).val();
			ct_id_arr.push(ct_id);
		}
	});
	$("input[name='ct_id_arr']").val(ct_id_arr.toString());
	tot_cancel_price = cancel_price + parseInt(od_send_cost); //제품금액 + 결제한 배송비
	<?php } ?>
	
	$.ajax({
		url: g5_url+"/shop/ajax.getsendcost.php",
		type: "POST",
		data: {
			od_id : $("input[name='od_id']").val()
			,ct_id_arr : ct_id_arr.toString()
			,cancel_type : "return" 
			},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data) {
			
			if(data.error != undefined && data.error != "") {
				alert(data.error);
				return false;
			}

			
			
			if(data.total_send_cost != undefined && data.total_send_cost != "" && data.total_send_cost > 0) {
				cancel_od_send_cost = data.total_send_cost;
			}
			
			if(data.total_return_cost != undefined && data.total_return_cost != "" && data.total_return_cost > 0) {
				cancel_send_costs = data.total_return_cost;
			}
			
			
			//console.log(od_send_cost);
			cancel_price = parseInt(cancel_price) + parseInt(od_send_cost); 
			$("#cancel_price").html(number_format(cancel_price) + "원");
			//cancel_od_send_cost = parseInt(od_send_cost);

			var cancel_select = $("#cancel_select").val();
			if(cancel_select == "색상및사이즈변경" || cancel_select == "다른상품잘못주문"){
				//배송비 유료 환불조건
				tot_cancel_price = parseInt(tot_cancel_price) - parseInt(cancel_od_send_cost); 
				tot_cancel_price = parseInt(tot_cancel_price) - parseInt(cancel_send_costs);
				//cancel_price_str += "(<?php echo $subtitle ?> 배송비 : -"+number_format(cancel_send_costs) + " 원) ";
				$("#od_send_cost2").val(cancel_send_costs);
				$("#return_cost_price").html("("+number_format(cancel_send_costs) + " 원)");	
				
				//사용자 변심일때는 기존 배송비도 환불하지 않음.
			} else {
				$("#od_send_cost2").val("0");
				//업체 잘못일 경우 배송비 환불
				tot_cancel_price = parseInt(tot_cancel_price) - parseInt(cancel_od_send_cost); 

				$("#return_cost_price").html("(0 원)");	
			}
			
			cancel_price_str += number_format(tot_cancel_price) + " 원";
			$("#cancel_price").html(cancel_price_str);	
			

			
		}
	});	
	
}

function fcancel_check(f)
{
	<?php if($partCancel) { ?>
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
    <?php } ?>
    
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