<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<div class="btn_group two" id="display_pay_button" style="display:none;">
    <a href="javascript:history.go(-1);" class="btn big black">취소</a>
	<input type="button" onClick="forderform_check(this.form);" value="주문하기" class="btn big green">
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo G5_URL; ?>/shop/img/loading.gif" alt="">
    <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script>
document.getElementById("display_pay_button").style.display = "" ;
</script>
