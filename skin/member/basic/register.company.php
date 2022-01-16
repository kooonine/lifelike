<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

include_once(G5_PATH.'/head.php');
?>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar type2 mb30">
		<h1 class="title"><span>회원가입</span></h1>
		<div class="lnb_add_text">
			<p>라이프라이크에 오신 것을 환영합니다!</p>
			<span>회원가입 후 다양한 서비스를 이용해 보세요.</span>
		</div>
		<a href="#" class="btn_back"><span class="blind">뒤로가기</span></a>
	</div>
	<div class="content join sub">
		<!-- 컨텐츠 시작 -->
		<form id = "frm_member" name ="frm_member" action="<?=$register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off" enctype='multipart/form-data'>
			<div class="grid type2 border_box">
				<input type="hidden" name="w" id="w">
				<input type="hidden" name="register_type" value="company">
				<div class="inp_wrap">
					<div class="title count3"><label for="mb_1_1">사업자구분</label></div>
					<div class="inp_ele count6 r_btn_120">
						<span class="chk radio w50">
							<input type="radio" id="mb_1_1" name="company_type" value="0" checked="checked">
							<label for="mb_1_1">법인</label>
						</span>
						<span class="chk radio">
							<input type="radio" id="mb_1_2" name="company_type" value="1">
							<label for="mb_1_2">개인</label>
						</span>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="company_no">사업자등록번호</label></div>
					<div class="inp_ele count6 r_btn_120">
						<div class="input"><input type="text" placeholder="사업자등록번호 입력" id="company_no" name="company_no" required="required"></div>
						<input type="hidden" id="bisYN">
						<button type="button" class="btn small green" onclick="bisCheckSum()">사업자 인증</button>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="company_name">회사명</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" placeholder="" id="company_name" name ="company_name" required="required"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="company_leader">대표자명</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" placeholder="" id="company_leader" name ="company_leader" required="required"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="company_category">업태/종목</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" placeholder="" id="company_category" name ="company_category" required="required"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="company_hp">대표 전화번호<br>(회사 연락처)</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="tel" placeholder="대표 전화번호 입력" id="company_hp" name ="company_hp" required="required"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3">
						<label for="company_zip">회사 주소</label>
					</div>
					<div class="inp_ele count6 r_btn_120">
						<div class="input"><input type="text" placeholder="" id="company_zip" name="company_zip" title="우편번호" onclick="win_zip('frm_member', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3','company_addr_jibeon');" readonly required></div>
						<button type="button" class="btn small green" onclick="win_zip('frm_member', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3','company_addr_jibeon');">우편번호</button>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="inp_ele">
						<div class="input"><input type="text" title="상세주소" placeholder="" id="company_addr1"  name = "company_addr1" readonly="readonly" required="required"></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="inp_ele">
						<div class="input"><input type="text" title="상세주소" placeholder="" id="company_addr2" name = "company_addr2" ></div>
					</div>
				</div>
				<input type="hidden" id="company_addr3" name = "company_addr3" >
				<input type="hidden" id = "company_jibeon" name="company_addr_jibeon" value="">
			</div>
			<div class="grid type2 border_box">
				<h2 class="g_title_01">사업자등록증 사본 첨부</h2>
				<div class="clearfix"></div>
				<!--
					<div class="btn_group">
						<span class="btn_file">
							<button type="button" class="btn big border"><span>파일 찾기</span></button>
							<input type="file" title="파일 업로드" onchange="getCmaFileInfo(this)" id="company_file" name = "company_file" >
						</span>
					</div>
				-->
				<div class="inp_wrap">
					<div class="title count3"><label for="company_file">파일등록</label></div>
					<div class="inp_ele count6 r_btn_120">
						<div class="input">
							<input type="text" placeholder="" id="join7_1" title="파일" disabled="" value="">
						</div>
						<span class="inp_file btn"><input type="file" title="파일 업로드" onchange="getCmaFileInfo(this)" id="company_file" name = "company_file" >파일찾기</span>
					</div>
				</div>
				<div class="file_list text">
					<ul id="file_list">

					</ul>
				</div>
				<div class="list">
					<ul class="import">
						<li>첨부 파일 2mb 이하의 jpb, gif, png, pdf 파일만 첨부 가능합니다.</li>
						<li>사업자 필수 첨부 서류 - 사업자등록증, 법인 대표자 신분증, 법인 대표자 인감증명서</li>
					</ul>
				</div>
			</div>
			<div class="grid type2 border_box">
				<div class="inp_wrap">
					<div class="title count3"><label for="mb_id">아이디</label></div>
					<div class="inp_ele count6 r_btn_120">
						<div class="input">
							<input type="text" placeholder="아이디 입력" id="mb_id" name ="mb_id" required="required">
						</div>
						<button type="button" class="btn small green" id="btn_id_check">중복확인</button>
						<input type="hidden" id="idYN">
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="password">비밀번호</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="password" placeholder="영문, 숫자, 특수문자, 조합 8~16자" id="mb_password" name ="mb_password" required></div>
						<span class="error" id="pwderror"></span>
						<!--<span class="error">에러 메시지</span> -->
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="password">비밀번호 확인</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="password" placeholder="비밀번호 재입력" id="mb_password_re" name = "mb_password_re" required></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="mb_name">정산 담당자명</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" placeholder="담당자명 입력" id="mb_name" name ="mb_name" required></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="mb_email">정산 담당자 이메일 주소</label></div>
					<div class="inp_ele count6">
						<div class="input"><input type="text" placeholder="이메일 주소 입력" id="mb_email"  name ="mb_email" required></div>
					</div>
				</div>
				<div class="inp_wrap">
					<div class="title count3"><label for="mb_hp">정산 담당자 휴대전화 번호</label></div>
					<div class="inp_ele count6 r_btn_120">
						<div class="input"><input type="tel" placeholder="휴대전화 번호 입력" id="mb_hp" name="mb_hp" required></div>
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
			</div>
			<!-- 간격 여백 -->
			<hr class="full_line" />
			<div class="grid type2">
				<div class="inp_wrap alignR">
					<h2 class="g_title_01">약관 안내/동의</h2>
					<span class="chk check">
						<input type="checkbox" id="chk_all">
						<label for="chk_all">전체동의</label>
					</span>
				</div>
				<div class="gray_box">
					<div class="inp_wrap">
						<span class="chk check">
							<input type="checkbox" id="agree_01" name="agree_01">
							<label for="agree_01">서비스 이용 약관<span>(필수)</span></label>
						</span>
						<a style="cursor: pointer;" class="btn floatR arrow_r_green" id="btn_agree1">전문보기</a>
					</div>
					<div class="inp_wrap">
						<span class="chk check">
							<input type="checkbox" id="agree_02" name="agree_02">
							<label for="agree_02">개인정보 수집 동의<span>(필수)</span></label>
						</span>
						<a style="cursor: pointer;" class="btn floatR arrow_r_green" id="btn_agree2">전문보기</a>
					</div>
					<div class="inp_wrap">
						<span class="chk check">
							<input type="checkbox" id="agree_03" name="agree_03">
							<label for="agree_03">마케팅 정보 활용 동의<span>(선택)</span></label>
						</span>
						<a style="cursor: pointer;" class="btn floatR arrow_r_green" id="btn_agree3">전문보기</a>
					</div>
					<? if($cf_2['reg_sms_use']) {?>
						<div class="inp_wrap">
							<span class="chk check">
								<input type="checkbox" id="reg_mb_mailling" name="mb_mailling" value="1">
								<label for="reg_mb_mailling">메일링 서비스 동의<span>(선택)</span></label>
							</span>
						</div>
					<? } ?>
					<? if($cf_2['reg_mailing_use']) {?>
						<div class="inp_wrap">
							<span class="chk check">
								<input type="checkbox" id="reg_mb_sms" name="mb_sms" value="1">
								<label for="reg_mb_sms">SMS 수신 동의<span>(선택)</span></label>
							</span>
						</div>
					<? } ?>
				</div>
				<div class="clearfix"></div>
				<ul class="hyphen">
					<li>라이프라이크 이용약관 및 개인정보 동의 내용을 확인하였으며, 위 내용에 동의합니다. </li>
				</ul>
			</div>
			<div class="grid">
				<div class="btn_group"><button type="submit" class="btn big green"><span>회원가입</span></button></div>
			</div>
		</form>
		<!-- 컨텐츠 종료 -->
	</div>
</div>
<!-- //container -->
</div>

<!-- popup -->
<section class="popup_container layer" id="popup_container1" style="display: none">
	<div class="inner_layer" style="top:10%">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01" id='popuptitle'>서비스 이용 약관</h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box" id='popupbody1'><?=$config['cf_stipulation'] ?></div>
			</div>
			
			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree1"><span>동의합니다</span></button></div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a style="cursor: pointer;" class="btn_closed" onclick="$('#popup_container1').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->
<!-- popup -->
<section class="popup_container layer" id="popup_container2" style="display: none">
	<div class="inner_layer" style="top:10%">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01" id='popuptitle'>개인정보 수집 동의</h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box" id='popupbody2'><?=$config['cf_user_privacy'] ?></div>
			</div>
			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree2"><span>동의합니다</span></button></div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a style="cursor: pointer;" class="btn_closed" onclick="$('#popup_container2').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->
<!-- popup -->
<section class="popup_container layer" id="popup_container3" style="display: none">
	<div class="inner_layer" style="top:10%">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01" id='popuptitle'>마케팅 정보 활용 동의</h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box" id='popupbody3'><?=$config['cf_collection_privacy'] ?></div>				
			</div>
			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree3"><span>동의합니다</span></button></div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a style="cursor: pointer;" class="btn_closed" onclick="$('#popup_container3').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->

<script>
	function fregister_submit(f)
	{
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

		if($('#bisYN').val() != 'Y' || $('#idYN').val() != 'Y' || $('#auth_yn').val() != 'Y'){
			alert("모든 인증이 완료되어야 가입이 가능합니다.");

			return false;
		}


		return true;
	}


	function getCmaFileInfo(obj) {
		var fileObj, pathHeader , pathMiddle, pathEnd, allFilename, fileName, extName;
		if(obj == "[object HTMLInputElement]") {
			fileObj = obj.value
			if(!fileCheck(obj)){
				return false;
			}
		} else {
			fileObj = document.getElementById(obj).value;
			if(!fileCheck(document.getElementById(obj))){
				return false;
			}
		}

		if (fileObj != "") {
			pathHeader = fileObj.lastIndexOf("\\");
			pathMiddle = fileObj.lastIndexOf(".");
			pathEnd = fileObj.length;
			fileName = fileObj.substring(pathHeader+1, pathMiddle);
			extName = fileObj.substring(pathMiddle+1, pathEnd);
			allFilename = fileName+"."+extName;
			if(extName != 'jpg' && extName != 'gif' && extName != 'png' && extName != 'pdf'){
				alert(" jpg, gif, png, pdf 파일만 첨부 가능합니다.");
				return false;
			}
			var html = '';
			html += '<li id="file_data">';
			html += '<span class="name">'+allFilename+'</span>';
			html += '<button type="button" class="btn_delete gray" id="file_delete" >';
			html += '<span class="blind">삭제</span>';
			html += '</button>';
			html += '</li>';
			$('#file_list').html(html);

		} else {
			alert("파일을 선택해주세요");
			return false;
		}
		// getCmaFileView(this,'name');
		// getCmaFileView('upFile','all');
	}

	function ckBisNo(bisNo)

	{

		// 넘어온 값의 정수만 추츨하여 문자열의 배열로 만들고 10자리 숫자인지 확인합니다.

		if ((bisNo = (bisNo+'').match(/\d{1}/g)).length != 10) { return false; }



		// 합 / 체크키

		var sum = 0, key = [1, 3, 7, 1, 3, 7, 1, 3, 5];



		// 0 ~ 8 까지 9개의 숫자를 체크키와 곱하여 합에더합니다.

		for (var i = 0 ; i < 9 ; i++) { sum += (key[i] * Number(bisNo[i])); }



		// 각 8번배열의 값을 곱한 후 10으로 나누고 내림하여 기존 합에 더합니다.

		// 다시 10의 나머지를 구한후 그 값을 10에서 빼면 이것이 검증번호 이며 기존 검증번호와 비교하면됩니다.



		// 체크섬구함

		var chkSum = 0;

		chkSum = Math.floor(key[8] * Number(bisNo[8]) / 10);

		// 체크섬 합계에 더해줌

		sum +=chkSum;

		var reminder = (10 - (sum % 10)) % 10;

		//값 비교

		if(reminder==Number(bisNo[9])) return true;

		return false;

	}

	function bisCheckSum(){
		if($('#company_no').val() != "" && ckBisNo($('#company_no').val())){
			alert('인증이 완료 되었습니다.');
			$('#bisYN').val('Y');
		}else {
			alert('사업자 번호를 확인 해주세요');
		}
	}

	function fileCheck( file )
	{
			// 사이즈체크
			var maxSize  = 2 * 1024 * 1024
			var fileSize = 0;

		// 브라우저 확인
		var browser=navigator.appName;

		// 익스플로러일 경우
		if (browser=="Microsoft Internet Explorer")
		{
			var oas = new ActiveXObject("Scripting.FileSystemObject");
			fileSize = oas.getFile( file.value ).size;
		}
		// 익스플로러가 아닐경우
		else
		{
			fileSize = file.files[0].size;
		}



		if(fileSize > maxSize)
		{
			alert("첨부파일 사이즈는 2MB 이내로 등록 가능합니다.    ");
			return false;
		}
		return true;

	}

	jQuery(function($){
		// 모두선택
		$(document).on("click", "#file_delete", function() {
			var result = confirm('첨부 파일을 삭제 하시겠습니까?');

			if(result){
				$("#mb_7").replaceWith($("#mb_7").val('').clone(true));
				$('#file_list').html('');
			}
		});

		$('#btn_id_check').click(function () {
			var mb_id = $("#mb_id").val();
			var num = mb_id.search(/[0-9]/g);
			var eng = mb_id.search(/[a-z]/ig);

			var objPattern = /^[a-z0-9_]{4,20}$/;
			if(!objPattern.test(mb_id))
			{
				alert("아이디를 영문, 숫자, 조합 4~20자리로 입력 해 주세요.");
				$("#mb_id").focus();
				$("#mb_id").select();
				return false;
			}

			$.ajax({
				type: "POST",
				url: "<?=G5_BBS_URL.'/ajax.mb_id.php'; ?>",
				data: {
					"reg_mb_id": encodeURIComponent($("#mb_id").val())
				},
				cache: false,
				async: false,
				success: function(data) {
					var msg = data;
					if (msg) {
						alert(msg);
						$("#mb_id").focus();

						return false;
					}else {
						$('#idYN').val('Y');
						alert('중복확인 되었습니다. 사용가능한 아이디입니다.');
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

		$("#chk_all").click(function() {

			chk = $("#chk_all").is(":checked");
			$("input[name^=agree]").prop("checked",chk);

			$("input[name^=mb_mailling]").prop("checked",chk);
			$("input[name^=mb_sms]").prop("checked",chk);

		});

		$('#btn_agree1').click(function () {
			$("#popup_container1").css("display","");
		});
		$('#btn_agree2').click(function () {
			$("#popup_container2").css("display","");
		});
		$('#btn_agree3').click(function () {
			$("#popup_container3").css("display","");
		});
		$('#agree1').click(function () {
			$('#agree_01').prop('checked', true);
			$("#popup_container1").css("display","none");
		});
		$('#agree2').click(function () {
			$('#agree_02').prop('checked', true);
			$("#popup_container2").css("display","none");
		});
		$('#agree3').click(function () {
			$('#agree_03').prop('checked', true);
			$("#popup_container3").css("display","none");
		});

		$('.inp_wrap .check').click(function(){
			if($('.gray_box input[type="checkbox"]:checked').length == $('.gray_box .inp_wrap').length){
				$("#chk_all").prop('checked', true);
			}else {
				$("#chk_all").prop('checked', false);
			}
		});
	});
</script>

<?
include_once(G5_PATH.'/tail.php');
?>

