<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<div class="grid foot bg_none" id="display_pay_button" style="display:none;">
	<div class="two" style="text-align:center;">
		<button type="button" class="btn big border" onclick="javascript:history.go(-1);"><span>취소</span></button>
		<input type="button" value="다음" class="btn big green" onclick="forderform_check(this.form);"/>
	</div>
</div>
<div id="display_pay_process" style="display:none">
	<img src="<?=G5_URL; ?>/shop/img/loading.gif" alt="">
	<span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script>
	document.getElementById("display_pay_button").style.display = "" ;
</script>
