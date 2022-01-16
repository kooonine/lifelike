
<div id="container">
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>아이디/비밀번호 찾기</span></h1>
	</div>
<div class="content comm sub">
				<!-- 컨텐츠 시작 -->
				<div class="grid">
					<div class="tab_cont_wrap">
						<div class="tab">
							<ul class="type3">
								<li class="" name="type"><a href="<?php echo G5_BBS_URL?>/id_lost.php"><span>아이디 찾기</span></a></li>
								<li class="on" name="type"><a href="<?php echo G5_BBS_URL?>/password_lost.php"><span>비밀번호 찾기</span></a></li>
							</ul>
						</div>
						<div class="tab_cont">
							<!-- tab1 -->
							<div class="tab_inner">

								<!-- 비밀번호 찾기 -->
								<div class="inner_wrap" id="page1">
									<div class="inp_wrap">
										<div class="title count3"><label for="f_04">이름</label></div>
										<div class="inp_ele count6">
											<div class="input">
												<input type="text" placeholder="이름 입력" id="name">
											</div>
										</div>
									</div>
									<div class="inp_wrap">
										<div class="title count3"><label for="f_02">인증수단</label></div>
										<div class="inp_ele count6">
											<span class="chk radio">
												<input type="radio" id="f_02_1" name="auth_type" value="phone">
												<label for="f_02_1">휴대전화 번호로 찾기</label>
											</span>
											<span class="chk radio">
												<input type="radio" id="f_02_2" name="auth_type" value="email">
												<label for="f_02_2">이메일로 찾기</label>
											</span>
											<div class="input">
												<input type="text" placeholder="" id="auth_text">
											</div>
										</div>
									</div>
									<div class="inp_wrap">
										<div class="title count3"><label for="f_07">아이디</label></div>
										<div class="inp_ele count6">
											<div class="input">
												<input type="text" placeholder="아이디 입력" id="userid">
											</div>
										</div>
									</div>

									<!-- 간격/여백 -->
									<hr class="full_line">

									<div class="info _box">
										<p class="ico_import red point_red">주의하세요.</p>
										<div class="list">
											<ul class="hyphen">
												<li>아이디, 인증 수단 정보 입력 시 가입하셨던 정보로 입력 하셔야 계정 정보 확인이 가능합니다.</li>
											</ul>
										</div>
									</div>
									<div id="div_1"  style="display:none"></div>
								</div>
								<!-- // 비밀전호 찾기 -->

								<!-- 비밀전호 찾기 완료 -->
								<div class="find_form" id="page2" style="display: none">
									<div class="title_bar">
										<h3 class="g_title_01">이메일로 인증 완료 후 비밀번호를 재 설정해 주세요</h3>
									</div>
									<div class="border_box" id="div_2">
										<p class="sm">입력하신 <span>Taepyu**@naver.com</span> 으로 인증번호가 발송되었습니다. 인증 완료 시 비밀번호가 재 발급 됩니다.</p>
									</div>
								</div>
								<!-- //비밀전호 찾기 완료 -->

								<!-- 인증번호 인증 -->
								<div class="find_form" id="page3" style="display: none">
									<div class="title_bar">
										<h3 class="g_title_01">인증번호 인증</h3>
									</div>
									<div class="border_box">
										<p class="sm">회원님의 휴대전화번호 또는 이메일 주소로 발송 된 인증 번호를 입력 후 확인 버튼을 선택해 주세요.</p>
										<div class="inp_wrap mt20">
											<div class="inp_ele">
												<div class="input r_txt bg">
													<input type="tel" placeholder="인증번호 입력" title="인증번호 입력" id="auth_key">
													<span class="time" id="timer">03:00</span>
												</div>
											</div>
										</div>
									</div>
									<div class="cmt_bar"><span>인증번호를 못 받으셨다면?</span> <button type="button" class="btn small green_line round" id="btn_resend"><span>재전송</span></button></div>
								</div>
								<!-- //인증번호 인증 -->

								<!-- 비밀번호 재설정 -->
								<div class="find_form" id="page4" style="display: none">
									<div class="title_bar">
										<h3 class="g_title_01">비밀번호 재설정</h3>
									</div>
									<div class="border_box">
										<p class="sm">인증이 정상적으로 완료 되었습니다.<br>
											신규 비밀번호를 입력 해 주시고 비밀번호 재설정 버튼을 선택 해 주세요.</p>
									</div>
									<form method="post" id = "frm_update">
										<input type="hidden" name = "mb_no" id="mb_no">
										<input type="hidden" name = "mb_nonce" id="mb_nonce">
    									<div class="inp_wrap">
    										<div class="title count3"><label for="f_09">신규 비밀번호</label></div>
    										<div class="inp_ele count6">
    											<div class="input"><input type="password" placeholder="영문, 숫자, 특수문자, 조합 8~16자" id="mb_password" name="password" required="required" minlength="8" maxlength="16"></div>
    											<span class="error" id = "check"></span>
    										</div>
    									</div>
    									<div class="inp_wrap">
    										<div class="title count3"><label for="f_10">신규 비밀번호 확인</label></div>
    										<div class="inp_ele count6">
    											<div class="input"><input type="password" placeholder="비밀번호 재입력" id="mb_password_re" required="required" minlength="8" maxlength="16"></div>
    										</div>
    									</div>
									</form>
									<!-- 간격/여백 -->
									<hr class="full_line">

									<div class="info _box">
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
								</div>
								<!-- //비밀번호 재설정 -->

							</div>
						</div>
					</div>
					<div class="btn_group"><button type="button" class="btn big green" id="btn_search"><span id="sp_search">확인</span></button></div>
				</div>
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
</div>
</div>
		
<script>
$(document).ready(function(){
	$('#sp_search').html('비밀번호 찾기');
	var stage = 1;
	var timer = 180;
	var interval = null;

    $('#btn_search').click( function() {
        if(stage == 1){
			if(timer < 180 && timer >= 170) {
				alert("연속해서 재전송하실 수 없습니다."+(timer-170)+"초 후 재시도바랍니다.");
				stage = 3;
				return;
			}
			if(interval != null) clearInterval(interval);
			
        	$.post(
                    "<?php echo $stage1_url; ?>",
                    { name: $('#name').val(), auth_type: $('input[type="radio"]:checked').val(),  auth_text: $('#auth_text').val(), id: $('#userid').val()},
                    function(data) {
                        
                        if(data.result =='S'){
                        	stage = 3;
                        	$("#div_2").html(data.view_text);
                        	$("#mb_no").val(data.mb_no);
                        	$("#mb_nonce").val(data.mb_nonce);
                            $('#page1').css('display','none');
                            $('#page2').css('display','none');
                            $('#page3').css('display','block');
                            $('#page4').css('display','none');
                            $('#sp_search').html('확인');
                            
    						timer = 180;
    						interval = setInterval(function(){
    							minutes = parseInt(timer / 60, 10);
    							seconds = parseInt(timer % 60, 10);

    							minutes = minutes < 10 ? "0" + minutes : minutes;
    							seconds = seconds < 10 ? "0" + seconds : seconds;


    							$('#timer').text(minutes + ':'+seconds);

    							if (--timer < 0) {
    								timer = 0;
    								clearInterval(interval);
    								if(stage != 4){
    									alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
    								}
    							}
    						}, 1000);
                        }else {
                        	$("#div_1").html(data.view_text);
                        }
                    }
                );
        }else if(stage == 2){
        	stage = 3;
            $('#page1').css('display','none');
            $('#page2').css('display','none');
            $('#page3').css('display','block');
            $('#page4').css('display','none');
            $('#sp_search').html('확인');
        }else if(stage == 3){
            
			if(timer == 0){
				alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
				return;
			}
			
        	$.post(
                    "<?php echo $stage3_url; ?>",
                    { auth_key : $('#auth_key').val(), mb_no : $('#mb_no').val(),  mb_nonce : $('#mb_nonce').val()},
                    function(data) {
                        
                        if(data.result=='S'){
                        	stage = 4;
                            $('#page1').css('display','none');
                            $('#page2').css('display','none');
                            $('#page3').css('display','none');
                            $('#page4').css('display','block');
                            $('#sp_search').html('비밀번호 변경');
                        }else {
                        	$("#div_1").html(data.view_text);
                        }
                        
                    }
                ); 
        }else if(stage == 4){

            if($("#mb_password").val() != $("#mb_password_re").val()) {
                alert('비밀번호가 같지 않습니다.');
                $("#mb_password_re").focus();
                return false;
            }
            if($("#mb_password").val().length < 8 || !$.passwordCheck($("#mb_password").val(), 2)){
                $("#mb_password").focus();
				alert("비밀번호를 영문, 숫자, 특수문자, 조합 8~16자로 입력 해 주세요.");
				return false;
			}
            
        	$('#frm_update').attr('action','<?php echo G5_BBS_URL?>/password_lost_update.php');
        	$('#frm_update').submit();
        }
    });
    $('#btn_resend').click(function() {
    	stage = 1;
    	$('#btn_search').click();
    });

    $.passwordCheck = function (pw, passwordComplexity) {

    	var num = pw.search(/[0-9]/g);
    	var eng = pw.search(/[a-z]/ig);
    	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

    	var engUpper = pw.substring(0,1).search(/[A-Z]/g);

    	if(passwordComplexity == "2")
    	{
    		if(num < 0 || eng < 0 || spe < 0 )
    		{
    			//alert("비밀번호를 영문,숫자,특수문자를 혼합하여 입력해주세요.");
    			return false;
    		}
    	}
    	else if(passwordComplexity == "3")
    	{
    		var engUpper = pw.substring(0,1).search(/[A-Za-z]/g);

    		// 첫글짜 영문
    		if(engUpper < 0 )
    		{
    			alert("비밀번호의 첫글자를 영문으로 입력해주세요.");
    			return false;
    		}
    		// 연속된 영문,숫자 3글자이상 안됨
    		if(kin4(pw, 3))
    		{
    			alert("비밀번호를 영문,숫자가 3글자 이상 연속되지 않은 비밀번호로 입력해주세요.");
    			return false;
    		}

    		// 영문,숫자,특수문자 포함
    		if(num < 0 || eng < 0 || spe < 0 )
    		{
    			alert("비밀번호를 영문,숫자,특수문자를 혼합하여 입력해주세요.");
    			return false;
    		}
    	}
    	return true;
    	
	    /*var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z]) (?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	    var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	    var enoughRegex = new RegExp("(?=.{8,}).*", "g");
	    var pwd = document.getElementById("pw1");
	    if (pwd.value.length == 0) {
	        document.getElementById('check').innerHTML = '패스워드 입력';
	    } else if (false == enoughRegex.test(pwd.value)) {
	        document.getElementById('check').innerHTML = '조금만 더 길게 쓰세요';
	    } else if (strongRegex.test(pwd.value)) {
	        document.getElementById('check').innerHTML = '<b><span style="color:green">보안성 좋은 패스워드</span>';
	    } else if (mediumRegex.test(pwd.value)) {
	        document.getElementById('check').innerHTML = '</b><b><span style="color:orange">적당한 패스워드</span>';
	    } else {
	        document.getElementById('check').innerHTML = '</b><b><span style="color:red">위험한 패스워드</span>';
	    }*/
	} 
});

	
	</script> 
</body>
</html>

