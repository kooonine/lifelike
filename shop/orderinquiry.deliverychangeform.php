<?php
include_once('./_common.php');

//orderinquiry.deliverychangeform.php
// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);
if (!$od['od_id']) {
    exit;
}
?>
<?php if(G5_IS_MOBILE) { ?>
<!-- popup -->
<section class="popup_container layer" id="od_deliverychange_frm">
	<div class="inner_layer">
	
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>배송지 수정</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content comm sub">
		<form method="post" action="./orderinquirychange.php" onsubmit="return fdelivery_check(this);" id="od_deliverychange_form" name="od_deliverychange_form">
		<input type="hidden" name="act" value="delivery">
		<input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
		<input type="hidden" name="uid"  value="<?php echo $uid; ?>">
		<input type="hidden" name="token"  value="<?php echo $token; ?>">
		<!-- 컨텐츠 시작 -->
		<div class="border_box">
			<div class="inp_wrap">
				<div class="title count3"><label>받는분</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" value="<?php echo $od['od_b_name']; ?>"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>휴대전화 번호</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20" value="<?php echo $od['od_b_hp']; ?>"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>연락처</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20" value="<?php echo $od['od_b_tel']; ?>"></div>
				</div>
			</div>
            <div class="inp_wrap">
				<div class="title count3"><label>주소</label></div>
                <div class="inp_ele count6 r_btn_100">
                    <div class="input"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6" value="<?php echo $od['od_b_zip1'].$od['od_b_zip2']; ?>" readonly="readonly"></div>
                    <button type="button" class="btn small green" onclick="win_zip('od_deliverychange_form', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="inp_ele count6 col_r">
                    <div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required" value="<?php echo $od['od_b_addr1']; ?>" readonly="readonly"></div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="inp_ele count6 col_r">
                    <div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address" value="<?php echo $od['od_b_addr2']; ?>">
                    <input type="hidden" name="od_b_addr3" value="<?php echo $od['od_b_addr3']; ?>">
                    <input type="hidden" name="od_b_addr_jibeon" value="<?php echo $od['od_b_addr_jibeon']; ?>"></div>
                </div>
            </div>
			<div class="inp_wrap">
				<div class="title count3"><label>배송 메시지</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="<?php echo $od['od_memo']; ?>" maxlength="20"></div>
				</div>
			</div>
			<div class="btn_group two">
				<button type="button" class="btn big border" onclick="$('#od_deliverychange_frm').remove();"><span>취소</span></button>
				<button type="submit" class="btn big green"><span>수정</span></button>
			</div>
		</div>
		</form>
		<!-- 컨텐츠 종료 -->
	</div>

		<!-- a href="#" class="btn_closed" onclick="$(#od_deliverychange_frm).remove();"><span class="blind">닫기</span></a -->
    <a href="#" class="btn_closed btn_close" id="od_coupon_close"  onclick="$('#od_deliverychange_frm').remove();"><span class="blind">닫기</span></a>
	
	
	</div></section>
<?php } else { ?>
<section class="popup_container layer" id="od_deliverychange_frm">
	<div class="inner_layer" style="top:10%;padding-top: 0px;">
		<div class="comm sub">
		<form method="post" action="./orderinquirychange.php" onsubmit="return fdelivery_check(this);" id="od_deliverychange_form" name="od_deliverychange_form">
		<input type="hidden" name="act" value="delivery">
		<input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
		<input type="hidden" name="token"  value="<?php echo $token; ?>">
		<input type="hidden" name="uid"  value="<?php echo $uid; ?>">
		
        <!-- 컨텐츠 시작 -->
        <div class="grid ">                 
            <div class="title_bar">
                <h2 class="g_title_01">배송지 수정</h2>
            </div>
			<div class="inp_wrap">
				<div class="title count3"><label>받는분</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" value="<?php echo $od['od_b_name']; ?>"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>휴대전화 번호</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20" value="<?php echo $od['od_b_hp']; ?>"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>연락처</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20" value="<?php echo $od['od_b_tel']; ?>"></div>
				</div>
			</div>
            <div class="inp_wrap">
				<div class="title count3"><label>주소</label></div>
                <div class="inp_ele count6 r_btn_100 address">
                    <div class="input"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6" value="<?php echo $od['od_b_zip1'].$od['od_b_zip2']; ?>" readonly="readonly"></div>
                    <button type="button" class="btn small green" onclick="win_zip('od_deliverychange_form', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
                    <div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required" value="<?php echo $od['od_b_addr1']; ?>" readonly="readonly"></div>
                    <div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address" value="<?php echo $od['od_b_addr2']; ?>">
                    <input type="hidden" name="od_b_addr3" value="<?php echo $od['od_b_addr3']; ?>">
                    <input type="hidden" name="od_b_addr_jibeon" value="<?php echo $od['od_b_addr_jibeon']; ?>"></div>
                </div>
            </div>
			<div class="inp_wrap">
				<div class="title count3"><label>배송 메시지</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="<?php echo $od['od_memo']; ?>" maxlength="20"></div>
				</div>
			</div>
			<div class="btn_group two">
				<button type="button" class="btn big border" onclick="$('#od_deliverychange_frm').remove();"><span>취소</span></button>
				<button type="submit" class="btn big green"><span>수정</span></button>
			</div>
		</div>
		</form>
		</div>
    	<a href="#" class="btn_closed btn_close" id="od_coupon_close"  onclick="$('#od_deliverychange_frm').remove();"><span class="blind">닫기</span></a>
	</div>
</section>
<?php }?>	
<script>

function fdelivery_check(f) {
	
}

</script>
