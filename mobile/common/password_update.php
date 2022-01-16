<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../../common.php');

?>
<!DOCTYPE html>
<html lang="ko">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
</head>
<body>
<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
    

</head>
<body>

		<!-- popup -->
		<section class="popup_container layer">
			<div class="inner_layer">
				<div id="lnb" class="header_bar">
					<h1 class="title"><span>비밀번호 변경</span></h1>
					<a href="#" class="btn_closed"><span class="blind">닫기</span></a>
				</div>
				<div class="content login">
					<!-- 컨텐츠 시작 -->
					<div class="grid cont">

						<div class="title_bar none">
							<h2 class="g_title_01">고객님의 개인 정보 보호를 위해 비밀번호를 다시 한 번 입력해 주세요.</h2>
						</div>
					</div>
					<div class="grid">
						<div class="inp_wrap">
							<div class="title count3"><label for="f1">기존 비밀번호</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="password" placeholder="" id="f1"></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f2">신규 비밀번호</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="password" placeholder="영문, 숫자, 특수문자, 조합 8~16자" id="f2"  onkeyup = "$.passwordCheck();" ></div>
								<span class="error" id = "check"></span>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f3">신규 비밀번호 확인</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="password" placeholder="비밀번호 재입력" id="f3"></div>
							</div>
						</div>
                        <!-- 간격 여백 -->
					    <hr class="full_line">
						<div class="info_box">
							<p class="ico_import red point_red">주의하세요.</p>
							<div class="list">
								<ul class="hyphen">
									<li>영문+숫자+특수문자를 조합하여 8~16자 미만으로 설정 해주세요.</li>
									<li>비밀번호 변경 시 자동 로그인은 해제됩니다.</li>
									<li>변경된 비밀번호 다시 로그인해 주세요.</li>
									<li>고객님의 개인 정보 유출 방지를 위해 주기적으로 변경하는 것을 권장합니다.</li>
								</ul>
							</div>
						</div>
						<div class="btn_group"><button type="button" class="btn big green"><span>변경</span></button></div>
					</div>
					<!-- 컨텐츠 종료 -->
				</div>
			</div>
		</section>
		<!-- //popup -->
<script>
$(document).ready(function(){
	$.passwordCheck = function () {
	    var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z]) (?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	    var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	    var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	    var pwd = document.getElementById("f2");
	    if (pwd.value.length == 0) {
	        document.getElementById('check').innerHTML = '패스워드 입력';
	    } else if (false == enoughRegex.test(pwd.value)) {
	        document.getElementById('check').innerHTML = '조금만 더 길게 쓰세요';
	    } else if (strongRegex.test(pwd.value)) {
	        document.getElementById('check').innerHTML = '<b><span style="color:green">보안성 짱이네요</span>';
	    } else if (mediumRegex.test(pwd.value)) {
	        document.getElementById('check').innerHTML = '</b><b><span style="color:orange">적당한 패스워드</span>';
	    } else {
	        document.getElementById('check').innerHTML = '</b><b><span style="color:red">위험한 패스워드</span>';
	    }
	} 
});
	
	</script> 

</body>
</html>
