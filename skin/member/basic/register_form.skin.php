<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH.'/head.php');
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="url" value="<?php echo $urlencode ?>">
<input type="hidden" name="agree" value="<?php echo $agree ?>">
<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
<input type="hidden" name="cert_no" value="">
<input type="hidden" name="mb_birth" id="mb_birth" value="">

<div class="user_info edit simple">
    <h1 class="blind">사용자정보-마이페이지</h1>
    <div class="inner">
        <div class="profile_photo">

        	<?php if ($w == 'u' && file_exists($mb_img_path)) {  ?>
			<p class="photo"><img  src="<?php echo $mb_img_url ?>" alt="" id="img"/></p>
			<?php }  else {?>
			<p class="photo"><img  src="<?php echo G5_URL?>/img/default.jpg" alt="" id="img"/></p>
			<?php } ?>

			<input type="file" id="mb_img" name="mb_img" style="display: none">
			<button type="button" class="profile_register" id="btn_profile_img"><span class="blind">프로필 등록</span></button>

            <script>
            $(function(){
            	$('#btn_profile_img').click(function () {
                	$("#mb_img").click();
            	});
            });
            </script>
            <span>(5MB 이하 등록)</span>
		</div>


        <div class="edit_cont">
            <!-- <p class="grade"><strong>PLATINUM</strong></p> -->
            <a href="#" class="name" id="a_nick"><strong ><?php echo (isset($member['mb_nick']) && $member['mb_nick'] != "")?get_text($member['mb_nick']):$member['mb_name']; ?></strong></a>

            <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
			<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
			<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
			<?php } else {  ?>
            <input type="text" class="input" hidden style="width:200px;" name="mb_nick" id="reg_mb_nick" placeholder="닉네임" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" >

            <button type="button" class="btn_modify" id="nick_modify">수정하기</button>
            <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
            <!-- <span class="cmt">※ 닉네임은 <php echo (int)$config['cf_nick_modify'] ?>일 1회만 변경 가능 합니다.</span> -->
            <script>
            	$(function(){
            		$("#nick_modify").click(function(){
            			var text = $(this).text();
            			if(text == '수정하기'){
                			$('#reg_mb_nick').removeAttr('hidden');
                			$('#a_nick').removeAttr('hidden').attr('hidden',true);
                			$(this).text('완료');
                			$('#reg_mb_nick').focus();

            			}else {

                			if($('#reg_mb_nick').val() == "") {
                    			alert("닉네임을 입력하세요.");
                    			$('#reg_mb_nick').focus();
                    			return;
                			}
            				$('#reg_mb_nick').removeAttr('hidden').attr('hidden',true);
                			$('#a_nick').removeAttr('hidden');
                			$('#a_nick strong').text($('#reg_mb_nick').val());
                			$(this).text('수정하기');
            			}
            		});

            	});
            </script>
            <?php } ?>
        </div>
    </div>
</div>


<!-- container -->
<div id="container">
<div class="content mypage">
	<div class="grid">
	<div class="divide_two box">
		<div class="box">
		<div class="title_bar">
            <h3 class="g_title_01">기본 정보</h3>
        </div>
        <div class="border_box">
            <div class="inp_wrap">
                <div class="title count3"><label for="f_52">아이디</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                        <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id"  readonly class="readonly" placeholder="아이디">
                    </div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f_52">이름</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                        <input type="text" name="mb_name" value="<?php echo $member['mb_name'] ?>" id="reg_mb_name"  readonly class="readonly" placeholder="아이디">
                    </div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f_53">이메일 주소</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                        <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                		<input type="email" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required  size="50" maxlength="100" placeholder="E-mail">

                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="box">
        <div class="title_bar">
            <h3 class="g_title_01">비밀번호</h3>
        </div>
        <div class="border_box">
            <div class="inp_wrap">
                <div class="title count3"><label for="f_54">현재 비밀번호</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                        <input type="password" name="mb_password_org" id="reg_mb_password_org"  minlength="8" maxlength="16" placeholder="현재 비밀번호 입력">
                    </div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f_55">새 비밀번호</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                        <input type="password" name="mb_password" id="reg_mb_password" minlength="8" maxlength="16" placeholder="영문,숫자,특수문자,조합 8~16자">
                    </div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f_56">새 비밀번호 확인</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                        <input type="password" name="mb_password_re" id="reg_mb_password_re"  minlength="8" maxlength="16" placeholder="새 비밀번호 재입력">
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
	</div>

	<div class="grid">
	<div class="divide_two box">
		<div class="box">
        <div class="title_bar">
            <h3 class="g_title_01">연락처</h3>
        </div>
        <div class="border_box">
            <div class="inp_wrap">
                <div class="title count3"><label for="join13">휴대전화 번호</label></div>
                <div class="inp_ele count6 r_btn_100">
                    <div class="input">
                    	<input type="text" name="mb_hp" id="reg_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" required maxlength="20" placeholder="휴대전화 번호 입력">
                    </div>
                    <input type="hidden" name="old_mb_hp" id="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
                    <button type="button" class="btn small green" id="btn_send_auth_key">재인증</button>
                </div>
            </div>
            <div class="inp_wrap" style="display: none" id="div_auth">
                <div class="title count3"><label for="join7">인증번호</label></div>
                <div class="inp_ele count6 r_btn_100">
                    <div class="input">
                        <input type="tel" placeholder="인증번호 입력" id="auth_key" name="auth_key">
                        <span class="time" id="timer">03:00</span>
                    </div>
                    <button type="button" class="btn small green" id="btn_auth">인증</button>
                    <input type="hidden" id="auth_yn">
                </div>
                <div id="div_alert" style="display: none"></div>
            </div>


            <div class="inp_wrap">
                <div class="title count3"><label for="join14">연락처</label></div>
                <div class="inp_ele count6">
                    <div class="input">
						<input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" maxlength="20" placeholder="전화번호">
					</div>
                </div>
            </div>
        </div>
        </div>

		<div class="box">
        <div class="title_bar">
            <h3 class="g_title_01">기타</h3>
        </div>
        <div class="border_box">
            <div class="inp_wrap">
    			<div class="title count3"><label for="join2">생년월일</label></div>
    			<div class="inp_ele count2">
    				<span class="sel_box">
    					<select name="year" id= "year" title="목록" target1="month" target2="day">
    						<option value="">선택</option>
    						<?
                            $mb_birth_explode = explode('-',$member['mb_birth']);
                            //1960~현재년도까지

                            foreach(range(date('Y'), 1900) as $val){
                                if($mb_birth_explode[0] == $val) $selected = 'selected'; else $selected = '';
                                echo '<option value="'.$val.'" '.$selected.' >'.$val.'</option>';
                            }


                            ?>

    					</select>
    				</span>
    			</div>
    			<div class="inp_ele count2">
    				<span class="sel_box">
    					<select name="month"  id ="month" target1="year" target2="day">
    						<option value="">선택</option>
    						<?

                            //1월부터 12월까지
    						foreach(range(1, 12) as $val) {
    						    if($mb_birth_explode[1] == $val) $selected = 'selected'; else $selected = '';
    						    echo '<option value="'.sprintf('%0d' , $val).'" '.$selected.' >'.sprintf('%d월' , $val).'</option>';
    						}

                            ?>
    					</select>
    				</span>
    			</div>
    			<div class="inp_ele count2">
    				<span class="sel_box">
    					<select name="day"  id ="day" >
    						<option value="">선택</option>
    						<?

                            //1월부터 12월까지
    						foreach(range(1, 31) as $val){
    						    if($mb_birth_explode[2] == $val) $selected = 'selected'; else $selected = '';
    						    echo '<option value="'.sprintf('%0d' , $val).'" '.$selected.' >'.sprintf('%d일' , $val).'</option>';
    						}

                            ?>
    					</select>
    				</span>
    			</div>
    		</div>
    		<script>
    		$(function(){
    			$('#year, #year2').change(function (){
    	            var id = $(this).attr('id');
    	            var target1 = $(this).attr('target1');
    	            var target2 = $(this).attr('target2');
    	            var year = $('#'+id+' option:selected').val();
    	            var month = $('#'+target1+' option:selected').val();
    	            if(year != '' & month != '')
    	            //month 는 0 부터 시작해서..
    	            var day = 32 - new Date(year, month-1, 32).getDate();
    	            $.fn_append_day(day,target2);
    	        });

    	        $('#month, #month2').change(function ()
    	        {
    	        	var id = $(this).attr('id');
    	            var target1 = $(this).attr('target1');
    	            var target2 = $(this).attr('target2');
    	            var year = $('#'+target1+' option:selected').val();
    	            var month = $('#'+id+' option:selected').val();

    	            //month 는 0 부터 시작해서..
    	            var day = 32 - new Date(year, month-1, 32).getDate();
    	            $.fn_append_day(day,target2);
    	        });

    	        $.fn_append_day = function(day,target){
    	        	$('#'+target).html('');
    	        	var html = '';
    	            for(var i = 1 ; i < day+1 ; i++){
    	                html = '<option value="'+i+'">'+i+'일</option>';
    	            	$('#'+target).append(html);
    	            }

    	        }
    		});
    		</script>
            <div class="inp_wrap">
                <div class="title count3"><label for="join3">성별</label></div>
                <div class="inp_ele count3">
                    <span class="chk radio">

                        <input type="radio" id="mb_sex_1" name="mb_sex" value="M" <?php if($member['mb_sex'] == 'M') echo "checked='checked'";?>>
                        <label for="mb_sex_1">남성</label>
                    </span>
                </div>
                <div class="inp_ele count3">
                    <span class="chk radio">
                        <input type="radio" id="mb_sex_2" name="mb_sex" value="F" <?php if($member['mb_sex'] == 'F') echo "checked='checked'";?>>
                        <label for="mb_sex_2">여성</label>
                    </span>
                </div>
            </div>
        </div>
        </div>
	</div>
	</div>

	<div class="grid bg_none">
        <div class="title_bar">
            <h3 class="g_title_01">주소(기본 배송지)</h3>
            <a href="<?php echo G5_SHOP_URL ?>/orderaddress.php" id="order_address1"><button class="category round_none floatR"><span>배송지 관리</span></button></a>
            <script>
			$(function(){
			    // 배송지목록
			    $("#order_address,#order_address1").on("click", function() {
			        var url = this.href;
			        window.open(url, "win_address", "left=100,top=100,width=650,height=700,scrollbars=1");
			        return false;
			    });
			});
            </script>
        </div>
        <div class="border_box">
            <div class="inp_wrap">
				<div class="title count3">
					<label for="join7">주소</label>
				</div>
				<div class="inp_ele count6 r_btn_120 address">
                    <div class="input"><input type="text" placeholder="" id="mb_zip" name="mb_zip" title="우편번호" readonly class="readonly" value="<?php echo $member['mb_zip1'].$member['mb_zip2']?>"></div>
                    <button type="button" class="btn small green" onclick="win_zip('fregisterform','mb_zip' , 'mb_addr1', 'mb_addr2', 'mb_addr3','mb_addr_jibeon');">우편번호</button>

                    <div class="input"><input type="text" placeholder="" id="mb_addr1"  name = "mb_addr1" readonly class="readonly" value="<?php echo $member['mb_addr1']?>"></div>
                    <div class="input"><input type="text" placeholder="" id="mb_addr2"  name = "mb_addr2" value="<?php echo $member['mb_addr2']?>"></div>
                </div>
            </div>
            <input type="hidden" id="mb_addr3" name = "mb_addr3" value="<?php echo $member['mb_addr3']?>"  >
    		<input type="hidden" id = "mb_jibeon" name="mb_addr_jibeon"  value="<?php echo $member['mb_addr_jibeon']?>">
        </div>
        <div class="info_box">
            <p class="ico_import red point_red">안내사항</p>
            <ul class="hyphen">
                <li>비밀번호 변경 시 자동 로그인은 해제됩니다.</li>
                <li>변경된 비밀번호로 다시 로그인해 주세요.</li>
                <li>고객님의 개인 정보 유출 방지를 위해 주기적으로 변경하는 것을 권장합니다.</li>
                <li>회원 탈퇴를 원하시면 <a href="<?php echo G5_BBS_URL.'/member_confirm_leave.php'?>" class="bold">회원 탈퇴 바로가기></a> 를 클릭해 주세요.</li>
            </ul>
        </div>
        <div class="btn_group"><button type="sumbit" class="btn big green" id="btn_submit"><span>수정</span></button></div>
    </div>
    </div>
</div>

</form>

    <script>
    var file_max_size = <?=$config['cf_member_img_size'] ?> ; 

    function handleImgFileSelect(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);

        filesArr.forEach(function(f) {
            if(!f.type.match("image.*")) {
                alert("확장자는 이미지 확장자만 가능합니다.");
                return;
            }
            if(f.size > file_max_size) {
                alert("회원이미지를 5MB이하로 업로드 해주십시오.");
                return;
            }

            sel_file = f;

            var reader = new FileReader();
            reader.onload = function(e) {
                $("#img").attr("src", e.target.result);
            }
            reader.readAsDataURL(f);
        });
    }

    $(function() {
    	$("#mb_img").on("change", handleImgFileSelect);

        // 휴대전화인증
        $("#btn_send_auth_key").click(function(e) {
            if($('#auth_yn').val() == 'Y'){
                //인증완료 후 재인증
                $('#auth_yn').val('N');

            	$('#reg_mb_hp').prop("readonly", false);
            	$('#reg_mb_hp').removeClass("readonly");
    			alert("재인증 할 휴대전화번호를 입력 해 주세요.");
    			$('#reg_mb_hp').focus();
            	return false;
            }
        	if($('#reg_mb_hp').val() ==  ''){
    			alert("휴대전화번호를 입력 해 주세요.");
    			$('#reg_mb_hp').focus();
    			return false;
    		}

        	if($('#reg_mb_hp').val() ==  $('#old_mb_hp').val()){
    			alert("재인증할 새로운 휴대전화 번호를 입력 해 주세요.");
    			$('#reg_mb_hp').focus();
    			return false;
    		}

        	var regHp = /(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/g;
            var temp = $("#reg_mb_hp").val();

            // 기존 번호에서 - 를 삭제합니다.
            var temp = temp.replace(/-/gi,'');
            $("#reg_mb_hp").val(temp);

            if(!regHp.test(temp))
            {
            	alert('휴대전화번호를 정확히 입력하세요');
                $("#reg_mb_hp").focus();
                return;
            }

        	$.post(
                    "<?php echo $register_auth_url; ?>",
                    { name: encodeURIComponent($('#reg_mb_name').val()),  auth_phoneNumber: $('#reg_mb_hp').val()},
                    function(data) {
                        if(data.result =='S'){
                        	$('#reg_mb_hp').prop("readonly", true);
                        	$('#reg_mb_hp').removeClass("readonly").addClass("readonly");
                            $("#btn_send_auth_key").prop("disabled", true);

                        	$("#div_alert").html(data.view_text);
                            $('#div_auth').css('display','block');
                            $('#auth_yn').val("N");
                            $("#auth_key").val("");
                            $("#auth_key").focus();
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
                                        $("#btn_send_auth_key").prop("disabled", false);
                                    	$('#reg_mb_hp').prop("readonly", false);
                                    	$('#reg_mb_hp').removeClass("readonly");
                                    }
                                }
                            }, 1000);
                        }else {
                        	$("#div_alert").html(data.view_text);
                        	$('#reg_mb_hp').removeClass("readonly");
                        	$('#reg_mb_hp').prop("readonly", false);
                            $("#btn_send_auth_key").prop("disabled", false);
                        }
                    }
                );
        });

        $('#btn_auth').click(function () {
        	if(timer == 0){
        		alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
            	$('#reg_mb_hp').prop("readonly", false);
            	$('#reg_mb_hp').removeClass("readonly");
                $("#btn_send_auth_key").prop("disabled", false);
        		return;
            }

        	$.post(
                    "<?php echo $register_certify_url; ?>",
                    { auth_key: $('#auth_key').val(),  auth_phoneNumber: $('#reg_mb_hp').val()},
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
    });

    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
        try{

        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert('비밀번호가 같지 않습니다.');
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {

            if (f.mb_password_org.value.length < 8) {
                alert('현재 비밀번호를 입력하십시오.');
                f.mb_password_re.focus();
                return false;
            }

            if (f.mb_password_re.value.length < 8) {
                alert('비밀번호를 8글자 이상 입력하십시오.');
                f.mb_password_re.focus();
                return false;
            }

			if(!passwordCheck(f.mb_password_re.value, 2)){
				//alert("비밀번호를 영문, 숫자, 특수문자, 조합 8~16자로 입력 해 주세요.");
				return false;
			}
        }

        // 이름 검사
        if (f.w.value=='') {
            if (f.mb_name.value.length < 1) {
                alert('이름을 입력하십시오.');
                f.mb_name.focus();
                return false;
            }
        }

        // 닉네임 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
            var msg = reg_mb_nick_check();
            if (msg) {
                alert(msg);
                f.reg_mb_nick.select();
                return false;
            }
        }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        // 휴대전화번호 체크
        /*var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }*/

    	if($('#reg_mb_hp').val() !=  $('#old_mb_hp').val() && $('#auth_yn').val() != 'Y'){
        	//미인증시 기존핸드폰 번호로 되돌림.
			$('#reg_mb_hp').val($('#old_mb_hp').val());
			return false;
		}

        if (typeof f.mb_img != "undefined") {
            if (f.mb_img.value) {
                if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                    alert("회원이미지가 이미지 파일이 아닙니다.");
                    f.mb_img.focus();
                    return false;
                }
            }
        }

        if (f.mb_sex.value == '' || f.mb_nick == '' || f.year.value == '' || f.month.value == '' || f.day.value == '') {
            alert("닉네임등록, 생년월일, 성별 등록은 필수 입니다.");
            return false;
        }
		var mb_birth = f.year.value +'-'+ f.month.value +'-'+  f.day.value
        $('#mb_birth').val(mb_birth);

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
        }catch(error){
            alert(error.message);
            return false;
        }
    }

    function passwordCheck(pw, passwordComplexity){

    	var num = pw.search(/[0-9]/g);
    	var eng = pw.search(/[a-z]/ig);
    	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

    	var engUpper = pw.substring(0,1).search(/[A-Z]/g);

    	if(passwordComplexity == "2")
    	{
    		if(num < 0 || eng < 0 || spe < 0 )
    		{
    			alert("비밀번호를 영문,숫자,특수문자를 혼합하여 입력해주세요.");
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
    }

    </script>
<?php
include_once(G5_PATH.'/tail.php');
?>
