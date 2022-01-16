<?php
include_once('./_common.php');

//orderinquiry.contractoutform.php
// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);
if (!$od['od_id']) {
    exit;
}

$rt_month = $od['rt_month'];
$rt_rental_enddate = date_create($od['rt_rental_startdate']);
date_add($rt_rental_enddate, date_interval_create_from_date_string($rt_month.' months'));
$rt_rental_enddate = date_format($rt_rental_enddate,"Y-m-d");

$penalty1 = rental_contractout_calc($od, "일반해지");
$penalty2 = rental_contractout_calc($od, "분실/파손해지");

?>
<?php if(G5_IS_MOBILE) { ?>
<form method="post" action="./orderinquirycontractout.php" onsubmit="return fcontractout_check(this);" id="od_contractout_form" name="od_contractout_form">
<input type="hidden" name="act" value="contractout">
<input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
<input type="hidden" name="token"  value="<?php echo $token; ?>">
<input type="hidden" name="uid"  value="<?php echo $uid; ?>">
<input type="hidden" name="od_contractout"  value="">

<div class="popup_container layer" id="od_contractout_frm">
	<div class="inner_layer" style="top:10%;">
		<div class="title_bar">
				<h1 class="g_title_01">리스 해지 신청</h1>
			</div>
		<div class="content">
		
        <!-- 컨텐츠 시작 -->
        <div class="grid ">  
			

			<div class="title_bar none alignC">
				<p class="g_title_03 ">리스 해지 사유를 선택해 주세요.</p>
				<div class="order_list button_choice black">
					<ul class="onoff">
						<li id="out1"><a style="cursor: pointer;">일반해지</a></li>
						<li id="out2"><a style="cursor: pointer;">분실/파손해지</a></li>
					</ul>
				</div>
			</div>
			<div class="title_bar none">
				<h1 class="g_title_02">위약금 정보</h1>
			</div>
			
			<div class="order_list gray_box">
    			<ul>
    				<li>
    					<span class="item">계약 금액</span>
    					<strong class="result">
    						<em class="point bold"><?php echo number_format($od['rt_rental_price'] * $od['rt_month']); ?> 원</em>
    					</strong>
    				</li>
    				<li>
    					<span class="item">리스료</span>
    					<strong class="result">
                                <em class="point bold"><?php echo number_format($od['rt_rental_price']); ?> 원</em>
    					</strong>
    				</li>
    				<li>
    					<span class="item">수납 방법</span>
    					<strong class="result">
    						카드 자동 이체
    					</strong>
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
    				<li>
    					<span class="item">예상 위약금 금액</span>
    					<strong class="result">
    						<em class="bold big point_red" id="penalty_price"><?php echo number_format($penalty1) ?> 원</em>
    					</strong>
    				</li>
    			</ul>
    		</div>
    
    		
		</div>
		</div>
		<div class="btn_group">
			<button type="submit" class="btn big border"><span>해지 신청</span></button>
		</div>
    	<a href="#" class="btn_closed btn_close" id="od_coupon_close"  onclick="$('#od_contractout_frm').remove();"><span class="blind">닫기</span></a>
	</div>
</div>
</form>
<?php } else { ?>
<form method="post" action="./orderinquirycontractout.php" onsubmit="return fcontractout_check(this);" id="od_contractout_form" name="od_contractout_form">
<input type="hidden" name="act" value="contractout">
<input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
<input type="hidden" name="token"  value="<?php echo $token; ?>">
<input type="hidden" name="uid"  value="<?php echo $uid; ?>">
<input type="hidden" name="od_contractout"  value="">

<div class="popup_container layer" id="od_contractout_frm">
	<div class="inner_layer" style="top:10%;">
		<div class="title_bar">
			<h1 class="g_title_01">리스 해지 신청</h1>
		</div>
		<div class="content">
		
        <!-- 컨텐츠 시작 -->
        <div class="grid ">  
			

			<div class="title_bar none alignC">
				<p class="g_title_03 ">리스 해지 사유를 선택해 주세요.</p>
				<div class="order_list button_choice black">
					<ul class="onoff">
						<li id="out1"><a style="cursor: pointer;">일반해지</a></li>
						<li id="out2"><a style="cursor: pointer;">분실/파손해지</a></li>
					</ul>
				</div>
			</div>
			<div class="title_bar none">
				<h1 class="g_title_02">위약금 정보</h1>
			</div>
			
			<div class="order_list gray_box">
    			<ul>
    				<li>
    					<span class="item">계약 금액</span>
    					<strong class="result">
    						<em class="point bold"><?php echo number_format($od['rt_rental_price'] * $od['rt_month']); ?> 원</em>
    					</strong>
    				</li>
    				<li>
    					<span class="item">리스료</span>
    					<strong class="result">
                                <em class="point bold"><?php echo number_format($od['rt_rental_price']); ?> 원</em>
    					</strong>
    				</li>
    				<li>
    					<span class="item">수납 방법</span>
    					<strong class="result">
    						카드 자동 이체
    					</strong>
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
    				<li>
    					<span class="item">예상 위약금 금액</span>
    					<strong class="result">
    						<em class="bold big point_red" id="penalty_price"><?php echo number_format($penalty1) ?> 원</em>
    					</strong>
    				</li>
    			</ul>
    		</div>
    
    	
		</div>
		</div>
		<div class="btn_group">
			<button type="submit" class="btn big border"><span>해지 신청</span></button>
		</div>
    	<a href="#" class="btn_closed btn_close" id="od_coupon_close"  onclick="$('#od_contractout_frm').remove();"><span class="blind">닫기</span></a>
	</div>
</div>
</form>
<?php }?>	
<script>
function fcontractout_check(f) {
	if($("input[name='od_contractout']").val() == ""){
		alert("리스 해지 사유를 선택해 주세요.");
		return false;
	}

	if(!confirm("리스 계약 해지를 신청 하시겠습니까?")){
		return false;
	}

	return true;
}

$(function() {
	$("#out1,#out2,#out3").click(function(){
		var status = $(this).text();

		$("#out1,#out2,#out3").removeClass("on");
		$(this).addClass("on");
		$("input[name='od_contractout']").val(status);

		if(status == "일반해지") $("#penalty_price").text(number_format('<?php echo $penalty1?>')+" 원");
		else if(status == "분실/파손해지") $("#penalty_price").text(number_format('<?php echo $penalty2?>')+" 원");
		//alert(status);
	});
});

</script>

