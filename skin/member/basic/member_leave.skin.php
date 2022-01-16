<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH.'/head.php');
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<form name="fmemberconfirm" action="<?php echo $url ?>" onsubmit="return fmemberconfirm_submit(this);" method="post">
<!-- container -->
	<div id="container">
		<!-- lnb -->
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>회원 탈퇴</span></h1>
		</div>
		<!-- //lnb -->
		<div class="content mypage sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid bg_none">
				<div class="title_bar none">
					<h2 class="g_title_02">회원 탈퇴 안내 사항을 반드시 확인해 주세요.</h2>
				</div>
				<div class="terms_box">
					<strong class="tit">1. 회원 탈퇴 시 처리 내용</strong>
					<p>1) 쿠폰, 적립금 : 리탠다드 적립금 잔여금액 및 보유 쿠폰은 소멸되며 환불 되지 않습니다.</p>
					<p>2) 보유 정보 : 구매정보가 모두 삭제 됩니다.</p>
					<p>3) 소비자 보호법에 관한 법률 제 6조(거래기록의 보전 등) 및 동법 시행 제 6조에 의거 ,</p>
					<p>* 계약 또는 청약철회 등에 관한 기록은 5년, 대금 결제 및 재화등의 공급에 관한 기록은 5년,</p>
					<p>* 소비자의 불만 또는 분쟁처리에 관한 기록은 3년동안 보관됩니다.</p>
					<p>동 개인정보는 법률에 의한 경우가 아니고서는 보유 되어지는 이외의 다른 목적으로는 이용되지 않습니다.</p>
					<p>4) 회원정보 : 탈퇴 완료 시 당사 사이트에 이용권한이 삭제되며, 기존 주문 이력 및 본인인증 필요성 등을 위해 회원가입에 따른 사용자 정보는 3개월 동안 보관됩니다.</p>

					<strong class="tit">2. 회원 탈퇴 시 게시물 관리</strong>
					<p>회원 탈퇴 후 당사 사이트에 입력하신 게시물 및 댓글은 삭제되지 않으며, 회원정보 삭제로 인한 작성자 본인을 확인 할 수 없으므로 게시물 편집 및 삭제 처리가 원천적으로 불가능 합니다. 게시물 삭제를 원하시는 경우에는 먼저 해당 게시물을 삭제 하신 후 탈퇴를 신청 하시기 바랍니다.</p>

					<strong class="tit">3. 회원 탈퇴 후 재 가입 규정</strong>
					<p>1) 탈퇴 회원이 재 가입하더라도 기존의 적립금 및 쿠폰등은 이미 소멸되었으므로 현재의 적립금, 쿠폰에 양도되지 않습니다.</p>
					<p>2) 탈퇴 후 3개월간 가입하셨던 동일한 아이디로 재 가입이 불가합니다.</p>
				</div>
			</div>
			
			<hr class="full_line">
			
            <div class="grid type2">
				<div class="inp_wrap">
					<div class="title count3"><label for="f1">탈퇴 사유</label></div>
                    <div class="inp_ele count6">
                        <span class="sel_box">
                            <select name="mb_4" id= "mb_4" title="목록">
                                <option value="사생활 기록 삭제 목적">사생활 기록 삭제 목적</option>
                                <option value="새 아이디 생성 목적">새 아이디 생성 목적</option>
                                <option value="이벤트 등의 목적으로 한시 사용함">이벤트 등의 목적으로 한시 사용함 </option>
                                <option value="서비스 기능 불편">서비스 기능 불편</option>
                                <option value="제재조치로 이용제한됨">제재조치로 이용제한됨</option>
                                <option value="개인정보 및 보안 우려">개인정보 및 보안 우려</option>
                                <option value="라이프라이크 정책 불만">라이프라이크 정책 불만</option>
                                <option value="라이프라이크 이용 안함">라이프라이크 이용 안함</option>
                                <option value="기타(직접입력)">기타(직접입력)</option>
                            </select>
                        </span>
                        <div class="input" id="mb_5_dv" hidden>
                        <input type="text" id="mb_5" name="mb_5" >
                        </div>
                    </div>
                </div>
                <div class="inp_wrap">
                    <label for="f2" class="blind">내용</label>
                    <div class="inp_ele">
                        <div class="input">
                            <textarea rows="6" cols="20" id="mb_3" name="mb_3" placeholder="한글 100자 이내 입력" maxlength="100"></textarea>
                        </div>
                    </div>
                </div>
                <div class="inp_wrap">
                    <span class="chk check floatL">
                        <input type="checkbox" id="chk_04" name="chk_04">
                        <label for="chk_04">위 내용을 모두 확인하였습니다.</label>
                    </span>
                    <span class="byte" id="mb_3_counter">0/100</span>
                </div>

                <div class="btn_group">
                    <button type="submit" class="btn big border" id="btn_submit"><span>탈퇴 신청</span></button>
                </div>
            </div>
            <!-- 컨텐츠 종료 -->
        </div>
	</div>            
</form>

<script>
$(function(){
	
	  $('textarea[name="mb_3"]').keyup(function (e){

	      var content = $(this).val();
	      var counter_id = $(this).attr('id')+'_counter';

	    //  $(this).height(((content.split('\n').length + 1) * 1.5) + 'em');
	      $('#'+counter_id).html(''+content.length + '/100');
	  });
	  
	$('#mb_4').change(function(){
		if($("#mb_4 option:selected").val() == '기타(직접입력)') {
			$("#mb_5_dv").removeAttr('hidden');
		}else {
			$("#mb_5_dv").removeAttr('hidden').attr('hidden',true);
			$("#mb_5").val("");
		}
	});
});
function fmemberconfirm_submit(f)
{
	if(!f.chk_04.checked) {
		alert("내용에 동의하셔야 회원탈퇴를 하실 수 있습니다.");
        f.chk_04.focus();
        return false;
    }
    document.getElementById("btn_submit").disabled = true;

    return true;
}
</script>
<?php
include_once(G5_PATH.'/tail.php');
?>