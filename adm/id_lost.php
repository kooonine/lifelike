<?php
include_once ('../common.php');

if ($is_member) {
    alert("이미 로그인중입니다.");
}

$g5['title'] = '아이디 찾기';
include_once ('./admin.head.sub.php');

$action_url = "./id_lost2.php";
?>

<!-- 회원정보 찾기 시작 { -->
<div id="find_info" class="new_win">
<h1 id="win_title">아이디 찾기 팝업</h1>

<form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">

<div class="container body">
<div class="main_container">
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<table class="table table-bordered" style="height: 100%">
    <tbody>
    <tr>
        <th scope="col" class="text-center active" style="vertical-align: middle;" width="20%">이름</th>
        <td scope="col" class="text-center" style="vertical-align: middle;">
        	<input type="text" name="mb_name" id="mb_name" required class="required frm_input full_input" size="30" placeholder="이름">
        
        </td>
	</tr>
    <tr>
        <th scope="col" class="text-center active" style="vertical-align: middle;" width="20%">이메일 주소</th>
        <td scope="col" class="text-center" style="vertical-align: middle;">
        	<input type="text" name="mb_email" id="mb_email" required class="required frm_input full_input email" size="30" placeholder="이메일 주소">
        </td>
	</tr>
	</tbody>
	</table>
	
	
	<div class="x_content">
		<div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
        		<button type="submit" class="btn btn-success" id="btnConfirm">확인</button>
        	</div>
        </div>
    </div>
    
	</div>
  </div>
</div>
</div>
</div>

</form>

<button type="button" onclick="window.close();" class="btn_close">창닫기</button>
</div>

<script>
function fpasswordlost_submit(f)
{
    return true;
}

$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>
<!-- } 회원정보 찾기 끝 -->


<?php
include_once(G5_PATH.'/tail.sub.php');
?>