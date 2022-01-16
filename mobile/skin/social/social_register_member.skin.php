<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if( ! $config['cf_social_login_use']) {     //소셜 로그인을 사용하지 않으면
	return;
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal.css">', 11);
add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal-default-theme.css">', 12);
add_stylesheet('<link rel="stylesheet" href="'.get_social_skin_url().'/style.css">', 13);
add_javascript('<script src="'.G5_JS_URL.'/remodal/remodal.js"></script>', 10);

$email_msg = $is_exists_email ? '등록할 이메일이 중복되었습니다.다른 이메일을 입력해 주세요.' : '';
?>
<script>
	var header = '<div id="lnb" class="header_bar">';
	header += '<h1 class="title"><span>회원가입</span></h1>';
	header += '<a href="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
	header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
	header += '</div>';
	$('#header').html(header);
</script>
<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
<!-- 회원정보 입력/수정 시작 { -->
<div class="content comm sub">
	<div class="grid cont">
		<div class="title_bar none">
			<h2 class="g_title_01">라이프라이크에 오신 것을 환영합니다!</h2>
			<p class="g_title_02">회원가입 후 다양한 서비스를 이용해 보세요.</p>
		</div>
	</div>
	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<form id = "frm_member" name="frm_member" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="POST" autocomplete="off">
			<input type="hidden" name="w" value="<?php echo $w ?>" id="w">
			<input type="hidden" name="url" value="<?php echo $urlencode; ?>">
			<input type="hidden" name="provider" value="<?php echo $provider_name;?>" >
			<input type="hidden" name="action" value="register">
			<input type="hidden" name="idYN" id="idYN">

			<input type="hidden" name="mb_nick_default" value="<?php echo isset($user_nick)?get_text($user_nick):''; ?>">
			<input type="hidden" name="mb_nick" value="<?php echo isset($user_nick)?get_text($user_nick):''; ?>" id="reg_mb_nick">
			<div class="inp_wrap">
				<div class="title count3"><label for="reg_mb_id">아이디</label></div>
				<div class="inp_ele count6 r_btn_80">
					<div class="input">
						<input type="text" placeholder="아이디 입력" id="reg_mb_id" name="mb_id" value="<?php echo $user_id; ?>">
					</div>
					<button type="button" class="btn small green" id="btn_id_check">중복확인</button>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label for="mb_password">비밀번호</label></div>
				<div class="inp_ele count6">
					<div class="input"><input type="password" placeholder="영문, 숫자, 특수문자, 조합 8~16자" id="mb_password" name="mb_password">
					</div>
					<span class="error" id="pwderror"></span>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label for="mb_password_re">비밀번호 확인</label></div>
				<div class="inp_ele count6">
					<div class="input"><input type="password" placeholder="비밀번호 재입력" id="mb_password_re" name="mb_password_re"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label for="reg_mb_name">이름</label></div>
				<div class="inp_ele count6">
					<div class="input"><input type="text" placeholder="이름 입력" id="reg_mb_name" name = "mb_name" value="<?php echo $user_nick; ?>" ></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label for="reg_mb_email">이메일주소</label></div>
				<div class="inp_ele count6">
					<div class="input"><input type="text" placeholder="이메일주소 입력" id="reg_mb_email" name = "mb_email" value="<?php echo isset($user_email)?$user_email:''; ?>"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label for="mb_hp">휴대전화 번호</label></div>
				<div class="inp_ele count6 r_btn_100">
					<div class="input"><input type="tel" placeholder="숫자만 입력" id="mb_hp" name="mb_hp" /></div>
					<button type="button" class="btn small green" id="btn_send_auth_key">인증번호전송</button>
				</div>
			</div>
			<div class="inp_wrap" style="display: none" id="div_auth">
				<div class="title count9"><label for="join7">휴대전화 번호로 전송된 숫자를 입력해 주세요.</label></div>
				<div class="inp_ele count9 r_btn">
					<div class="input r_txt bg">
						<input type="tel" placeholder="인증번호 입력" id="auth_key" name="auth_key">
						<span class="time" id="timer">03:00</span>
					</div>
					<button type="button" class="btn small green" id="btn_auth">인증</button>
					<input type="hidden" id="auth_yn">
				</div>
				<div id="div_alert" style="display: none"></div>
			</div>

			<div class="inp_wrap">
				<div class="title count3"><label for="reg_mb_tel">연락처</label></div>
				<div class="inp_ele count6">
					<div class="input ">
						<input type="tel" placeholder="연락처 입력" id="reg_mb_tel" name = "mb_tel">
					</div>
				</div>
			</div>

			<div class="inp_wrap">
				<div class="title count3">
					<label for="mb_zip">주소(기본 배송지)</label>
				</div>
				<div class="inp_ele count6 r_btn_100">
					<div class="input"><input type="text" placeholder="" id="mb_zip" name="mb_zip" title="우편번호" onclick="win_zip('frm_member','mb_zip' , 'mb_addr1', 'mb_addr2', 'mb_addr3','mb_addr_jibeon');" readonly></div>
					<button type="button" class="btn small green" onclick="win_zip('frm_member','mb_zip' , 'mb_addr1', 'mb_addr2', 'mb_addr3','mb_addr_jibeon');">우편번호</button>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="inp_ele count6 col_r">
					<div class="input"><input type="text" placeholder="" id="mb_addr1"  name = "mb_addr1" readonly></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="inp_ele count6 col_r">
					<div class="input"><input type="text" placeholder="" id="mb_addr2"  name = "mb_addr2"></div>
				</div>
			</div>
			<input type="hidden" id="mb_addr3" name = "mb_addr3" >
			<input type="hidden" id = "mb_addr_jibeon" name="mb_addr_jibeon" value="">

			<!-- 간격 여백 -->
			<hr class="full_line">

			<div class="inp_wrap alignR mb20">
				<p class="floatL bold">약관 안내/동의</p>
				<span class="chk check">
					<input type="checkbox" id="chk_all" name="chk_all">
					<label for="chk_all">전체동의</label>
				</span>
			</div>
			<div class="essential">
				<div class="inp_wrap">
					<span class="chk check">
						<input type="checkbox" id="agree_01" name="agree_01">
						<label for="agree_01">서비스 이용 약관<span>(필수)</span></label>
					</span>
					<a href="<?php echo G5_MOBILE_URL?>/common/terms_agreement.php?id=agree_01&type=stipulation" class="btn floatR arrow_r_green">전문보기</a>
				</div>
				<div class="inp_wrap">
					<span class="chk check">
						<input type="checkbox" id="agree_02" name="agree_02">
						<label for="agree_02">개인정보 수집 동의<span>(필수)</span></label>
					</span>
					<a href="<?php echo G5_MOBILE_URL?>/common/terms_agreement.php?id=agree_02&type=user_privacy&title=개인정보 수집" class="btn floatR arrow_r_green">전문보기</a>
				</div>
				<div class="inp_wrap">
					<span class="chk check">
						<input type="checkbox" id="agree_03" name="agree_03">
						<label for="agree_03">마케팅 정보 활용 동의<span>(선택)</span></label>
					</span>
					<a href="<?php echo G5_MOBILE_URL?>/common/terms_agreement.php?id=agree_03&type=collection_privacy&title=마케팅 정보 활용" class="btn floatR arrow_r_green">전문보기</a>
				</div>
				<div class="inp_wrap">
					<span class="chk check">
						<input type="checkbox" id="reg_mb_mailling" name="mb_mailling" value="1">
						<label for="reg_mb_mailling">메일링 서비스 동의<span>(선택)</span></label>
					</span>
				</div>
				<div class="inp_wrap">
					<span class="chk check">
						<input type="checkbox" id="reg_mb_sms" name="mb_sms" value="1">
						<label for="reg_mb_sms">SMS 수신 동의<span>(선택)</span></label>
					</span>
				</div>
			</div>
			<!-- 간격 여백 -->
			<hr class="full_line">

			<div class="inp_wrap mt20m">
				<span class="chk check floatL">
					<input type="checkbox" id="agree_04" name="agree_04">
					<label for="chk_04">만 14세 이상입니다.</label>
				</span>
				<span class="cmt">라이프라이크 이용약관 및 개인정보 동의 내용을 확인하였으며, 위 내용에 동의합니다. 만 14세 미만 아동은 회원가입이 제한됩니다.</span>
			</div>

			<div class="btn_group"><button type="submit" class="btn big green" id="btn_submit"><span>회원가입</span></button></div>
		</form>
	</div>
	<!-- 컨텐츠 종료 -->
</div>
</div>
<!-- //container -->

<!-- footer -->

<!-- //footer -->
</div>


<script>
	// submit 최종 폼체크
	function fregisterform_submit(f){
		if($('#idYN').val() != 'Y'){
			alert("아이디 중복확인 바랍니다.");
			return false;
		}

		if($('#mb_password').val() ==  ''){
			alert("비밀번호를 입력 해 주세요.");
			$("#pwderror").text("비밀번호를 입력 해 주세요.");
			return false;
		}

		if($('#mb_password').val().length < 8){
			alert("비밀번호를 8자 이상 입력 해 주세요.");
			$("#pwderror").text("비밀번호를 8자 이상 입력 해 주세요.");
			return false;
		}

		if($('#mb_password_re').val() ==  ''){
			alert("비밀번호 확인을 입력 해 주세요.");
			$("#pwderror").text("비밀번호 확인을 입력 해 주세요.");
			return false;
		}
		if($('#mb_password').val() !=  $('#mb_password_re').val()){
			alert("비밀번호가 일치하지 않습니다.");
			$("#pwderror").text("비밀번호가 일치하지 않습니다.");
			return false;
		}else {
			if(!passwordCheck($('#mb_password').val(),2)){
				$("#pwderror").text("비밀번호를 잘못 설정하였습니다.");
				return false;
			}
		}
		$("#pwderror").text("");

		if($('#mb_name').val() ==  ''){
			alert("이름을 입력 해 주세요.");
			return false;
		}

		if($('#auth_yn').val() != 'Y'){
			alert("모든 인증이 완료되어야 가입이 가능합니다.");
			return false;
		}

		if (!f.agree_01.checked) {
			alert("서비스 이용 약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree_01.focus();
			return false;
		}

		if (!f.agree_02.checked) {
			alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree_02.focus();
			return false;
		}

		if (!f.agree_04.checked) {
			alert("만 14세 미만 아동은 회원가입이 제한 됩니다.");
			f.agree_04.focus();
			return false;
		}
		return true;
	}

	function flogin_submit(f){
		var mb_id = $.trim($(f).find("input[name=mb_id]").val()),
		mb_password = $.trim($(f).find("input[name=mb_password]").val());

		if(!mb_id || !mb_password){
			return false;
		}

		return true;
	}

	jQuery(function($){
		$('#btn_id_check').click(function () {
			$.ajax({
				type: "POST",
				url: "<?php echo G5_BBS_URL.'/ajax.mb_id.php'; ?>",
				data: {
					"reg_mb_id": encodeURIComponent($("#reg_mb_id").val())
				},
				cache: false,
				async: false,
				success: function(data) {
					var msg = data;
					if (msg) {
						alert(msg);
						$("#reg_mb_id").focus();
						return false;
					}else {
						$('#idYN').val('Y');
						alert('인증이 완료 되었습니다.');
					}
				}
			});
		});

		$('#btn_send_auth_key').click(function () {
			if($('#mb_hp').val() ==  ''){
				alert("휴대전화번호를 입력 해 주세요.");
				return false;
			}

			var regHp = /(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/g;
			var temp = $("#mb_hp").val();
			if(!regHp.test(temp))
			{
				alert('휴대전화번호를 정확히 입력하세요');
				$("#mb_hp").focus();
				return;
			}
			$.post(
				"<?=$register_auth_url; ?>",
				{ name: encodeURIComponent($('#mb_name').val()),  auth_phoneNumber: $('#mb_hp').val()},
				function(data) {

					if(data.result =='S'){
						$("#div_alert").html(data.view_text);
						$('#div_auth').css('display','block');
						timer = 180;
						var interval = setInterval(function(){
							minutes = parseInt(timer / 60, 10);
							seconds = parseInt(timer % 60, 10);

							minutes = minutes < 10 ? "0" + minutes : minutes;
							seconds = seconds < 10 ? "0" + seconds : seconds;


							$('#timer').text(minutes + ':'+seconds);

							if (--timer < 0) {
								timer = 0;
								clearInterval(interval);
								if($('#auth_yn').val() != 'Y'){
									alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
								}
							}
						}, 1000);
					}else {
						$("#div_alert").html(data.view_text);
					}
				}
				);
		});
		$('#btn_auth').click(function () {
			if(timer == 0){
				alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
				return;
			}
			$.post(
				"<?=$register_certify_url; ?>",
				{ auth_key: $('#auth_key').val(),  auth_phoneNumber: $('#mb_hp').val()},
				function(data) {

					if(data.result =='S'){
						$("#div_alert").html(data.view_text);
						$("#auth_yn").val('Y');
						timer = 0;

					}else {
						$("#div_alert").html(data.view_text);
					}
				}
				);
		});

		if( jQuery(".toggle .toggle-title").hasClass('active') ){
			jQuery(".toggle .toggle-title.active").closest('.toggle').find('.toggle-inner').show();
		}
		jQuery(".toggle .toggle-title .right_i").click(function(){

			var $parent = $(this).parent();

			if( $parent.hasClass('active') ){
				$parent.removeClass("active").closest('.toggle').find('.toggle-inner').slideUp(200);
			} else {
				$parent.addClass("active").closest('.toggle').find('.toggle-inner').slideDown(200);
			}
		});
		$(".arrow_r_green").on("click", function() {
			var url = this.href;
			window.open(url, "win_agree", "left=100,top=100,width=650,height=500,scrollbars=1");
			return false;
		});
		// 모두선택
		$("#chk_all").click(function() {
			chk = $("#chk_all").is(":checked");
			$("input[name^=agree]").prop("checked",chk);
			$("input[name^=mb_mailling]").prop("checked",chk);
			$("input[name^=mb_sms]").prop("checked",chk);
		});

		$('#agree1').click(function () {
			$('#agree_01').prop('checked', true);
		});
		$('#agree2').click(function () {
			$('#agree_02').prop('checked', true);
		});
		$('#agree3').click(function () {
			$('#agree_03').prop('checked', true);
		});

		$('.inp_wrap .check').click(function(){
			if($('.essential input[type="checkbox"]:checked').length == $('.essential .inp_wrap').length){
				$("#chk_all").prop('checked', true);
			} else {
				$("#chk_all").prop('checked', false);
			}
		});

		$(".btn_submit_trigger").on("click", function(e){
			e.preventDefault();
			$("#btn_submit").trigger("click");
		});
	});
</script>

</div>
<!-- } 회원정보 입력/수정 끝 -->
