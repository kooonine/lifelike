<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
?>

<!-- container -->
<div id="container">
    <!-- lnb -->
    <div id="lnb" class="header_bar">
        <h1 class="title"><span>리스 테스트</span></h1>
    </div>
    <!-- //lnb -->
    <div class="content mypage sub">
        <!-- 컨텐츠 시작 -->
        <div class="grid border_box divide_inp">
        	<form id="frm" name="frm">
            <div class="inp_wrap">
                <div class="title count3"><label for="f2">od_id</label></div>
                <div class="inp_ele count6">
                    <div class="input"><input type="text" id="od_id" name="od_id" value=""></div>
                </div>
            </div>
            
            <div class="btn_group two">
                <button type="button" class="btn big green" onclick="orderproc();"><span>리스 납부 처리</span></button>
            </div>
            
            <div class="inp_wrap">
                <div class="title count3"><label for="f5">결과값</label></div>
                <div class="inp_ele count6" id="result">
                </div>
            </div>
            </form>
        </div>
        <!-- 컨텐츠 끝 -->
    </div>
</div>
<!-- //container -->

<script>
$(function() {
});
function orderproc()
{
	
    $.ajax({
            url: "./cron_rental.php",
            type: "POST",
            data: {
                "od_id" : $("#od_id").val()
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data) {
                $("#result").html(JSON.stringify(data));
            }
        });
}
</script>