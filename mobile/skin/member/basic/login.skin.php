<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>

<div class="content login">
	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<div class="title_bar none">
			<h2 class="g_title_01 alignC"><?php echo $g5['title'] ?></h2>
		</div>
		<form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post" id="flogin">
			<input type="hidden" name="url" value="<?php echo $login_url ?>">
			<input type="hidden" name="apptoken" value="">
    		<div class="login_container">
    			<div class="inp_wrap">
    				<div class="inp_ele">
    					<div class="input"><input type="text" placeholder="아이디 입력" name="mb_id" id = "login_id"></div>
    				</div>
    			</div>
    			<div class="inp_wrap">
    				<div class="inp_ele">
    					<div class="input"><input type="password" placeholder="비밀번호 입력" name="mb_password" id="login_pw" ></div>
    				</div>
    			</div>
    			<div class="inp_wrap">
    				<span class="chk check">
    					<input type="checkbox" id="login_auto_login" name="auto_login" value="1">
    					<label for="chk_01">로그인 상태를 유지합니다.</label>
    				</span>
    			</div>
    			<div class="btn_group"><button type="submit" class="btn big green"><span>로그인</span></button></div>
    			<ul class="member_link">
    				<li><a href="<?php echo G5_MOBILE_URL ?>/common/register_select.php">회원가입</a></li>
    				<li><a href="<?php echo G5_BBS_URL ?>/id_lost.php" target="_blank" id="login_password_lost">아이디찾기</a></li>
    				<li><a href="<?php echo G5_BBS_URL ?>/password_lost.php" target="_blank" id="login_password_lost">비밀번호찾기</a></li>
    			</ul>
    		</div>
    		<div class="login_container">
    			<div class="title_bar none">
    				<h2 class="g_title_01 alignC">간편가입</h2>
    			</div>
    			
    			<?php
                // 소셜로그인 사용시 소셜로그인 버튼
                include_once(get_social_skin_path().'/social_outlogin.skin.1.php');
                ?>
    			
    			<div class="btn_group"><a href="<?php echo G5_MOBILE_URL ?>/common/nonmemberorder.php "><button type="button" class="btn big border"><span>비회원 주문조회</span></button></a></div>

    		</div>
		</form>
	</div>
	<!-- 컨텐츠 종료 -->
</div>

<script>
$(function(){
	var broswerInfo = navigator.userAgent;    // broswerInfo 에 user agent 정보를 담습니다.

	if(broswerInfo.indexOf("APP_ANDROID")>-1){
		var token = window.lifelike_android.getFcmToken();
		$("input[name='apptoken']").val(token);
	}
	
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function getFcmToken(data)
{
    var ele = data
	$("input[name='apptoken']").val(data);
}

function flogin_submit(f)
{
    return true;
}
</script>