<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>본인 재인증</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>
<div class="content mypage sub">
				<!-- 컨텐츠 시작 -->
	<div class="grid bg_none">
		<div class="title_bar none">
			<h2 class="g_title_01">고객님의 개인 정보를 위해 비밀번호를 다시 한 번 입력해주세요.</h2>
		</div>
	</div>
	<div class="grid bg_none">
		<form name="fmemberconfirm" action="<?php echo $url ?>" onsubmit="return fmemberconfirm_submit(this);" method="post">
		<input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">
    	<input type="hidden" name="w" value="u">
		<div class="inp_wrap">
			<label for="fm1" class="blind">비밀번호 입력</label>
			<div class="inp_ele count9">
				<div class="input"><input type="password"  name="mb_password" placeholder="비밀번호 입력" id="fm1"></div>
			</div>
		</div>

		<div class="btn_group">
			<button type="submit" class="btn big green" id="btn_submit"><span>확인</span></button>
		</div>
		</form>
	</div>
	<!-- 컨텐츠 종료 -->
</div>


<script>
function fmemberconfirm_submit(f)
{
    document.getElementById("btn_submit").disabled = true;

    return true;
}
</script>
