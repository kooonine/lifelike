<?php
$sub_menu = "700110";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');


$g5['title'] = '회원가입항목설정';
include_once ('./admin.head.php');

?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
<input type="hidden" name="token" value="" id="token">

<div class="x_title">
	<h4><span class="fa fa-check-square"></span> 회원가입 항목설정<small></small></h4>
	<label class="nav navbar-right"></label>
	<div class="clearfix"></div>
</div>


<div class="x_content">

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원가입 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">주소 입력</th>
            <td>
                <input type="checkbox" name="cf_use_addr" value="1" id="cf_use_addr" <?php echo $config['cf_use_addr']?'checked':''; ?>> <label for="cf_use_addr">보이기</label>
                <input type="checkbox" name="cf_req_addr" value="1" id="cf_req_addr" <?php echo $config['cf_req_addr']?'checked':''; ?>> <label for="cf_req_addr">필수입력</label>
            </td>
        </tr>
        <tr>
            <th scope="row">전화번호 입력</th>
            <td>
                <input type="checkbox" name="cf_use_tel" value="1" id="cf_use_tel" <?php echo $config['cf_use_tel']?'checked':''; ?>> <label for="cf_use_tel">보이기</label>
                <input type="checkbox" name="cf_req_tel" value="1" id="cf_req_tel" <?php echo $config['cf_req_tel']?'checked':''; ?>> <label for="cf_req_tel">필수입력</label>
            </td>
            <th scope="row">휴대전화번호 입력</th>
            <td>
                <input type="checkbox" name="cf_use_hp" value="1" id="cf_use_hp" <?php echo $config['cf_use_hp']?'checked':''; ?>> <label for="cf_use_hp">보이기</label>
                <input type="checkbox" name="cf_req_hp" value="1" id="cf_req_hp" <?php echo $config['cf_req_hp']?'checked':''; ?>> <label for="cf_req_hp">필수입력</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_register_point">회원가입시 적립금</label></th>
            <td><input type="text" name="cf_register_point" value="<?php echo $config['cf_register_point'] ?>" id="cf_register_point" class="frm_input" size="5"> 원</td>
        </tr>
        <tr>
            <th scope="row" id="th310"><label for="cf_leave_day">회원탈퇴후 삭제일</label></th>
            <td colspan="3"><input type="text" name="cf_leave_day" value="<?php echo $config['cf_leave_day'] ?>" id="cf_leave_day" class="frm_input" size="2"> 일 후 자동 삭제</td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_member_img_size">회원이미지 용량</label></th>
            <td><input type="text" name="cf_member_img_size" value="<?php echo $config['cf_member_img_size'] ?>" id="cf_member_img_size" class="frm_input" size="10"> 바이트 이하</td>
            <th scope="row">회원이미지 사이즈</th>
            <td>
                <label for="cf_member_img_width">가로</label>
                <input type="text" name="cf_member_img_width" value="<?php echo $config['cf_member_img_width'] ?>" id="cf_member_img_width" class="frm_input" size="2">
                <label for="cf_member_img_height">세로</label>
                <input type="text" name="cf_member_img_height" value="<?php echo $config['cf_member_img_height'] ?>" id="cf_member_img_height" class="frm_input" size="2">
                픽셀 이하
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_use_recommend">추천인제도 사용</label></th>
            <td><input type="checkbox" name="cf_use_recommend" value="1" id="cf_use_recommend" <?php echo $config['cf_use_recommend']?'checked':''; ?>> 사용</td>
            <th scope="row"><label for="cf_recommend_point">추천인 적립금</label></th>
            <td><input type="text" name="cf_recommend_point" value="<?php echo $config['cf_recommend_point'] ?>" id="cf_recommend_point" class="frm_input"> 원</td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_prohibit_id">아이디,닉네임 금지단어</label></th>
            <td>
                <?php echo help('회원아이디, 닉네임으로 사용할 수 없는 단어를 정합니다. 쉼표 (,) 로 구분') ?>
                <textarea name="cf_prohibit_id" id="cf_prohibit_id" rows="5"><?php echo $config['cf_prohibit_id'] ?></textarea>
            </td>
            <th scope="row"><label for="cf_prohibit_email">입력 금지 메일</label></th>
            <td>
                <?php echo help('입력 받지 않을 도메인을 지정합니다. 엔터로 구분 ex) hotmail.com') ?>
                <textarea name="cf_prohibit_email" id="cf_prohibit_email" rows="5"><?php echo $config['cf_prohibit_email'] ?></textarea>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</div>

<div class="x_title">
	<h4><span class="fa fa-check-square"></span> 소셜네트워크서비스(SNS : Social Network Service)<small></small></h4>
	<label class="nav navbar-right"></label>
	<div class="clearfix"></div>
</div>


<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>소셜네트워크서비스 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="cf_social_login_use">소셜로그인설정</label></th>
            <td colspan="3">
                <?php echo help('소셜로그인을 사용합니다. <a href="https://sir.kr/manual/g5/276" class="btn btn_03" target="_blank" style="margin-left:10px" >설정 관련 메뉴얼 보기</a> ') ?>
                <input type="checkbox" name="cf_social_login_use" value="1" id="cf_social_login_use" <?php echo (!empty($config['cf_social_login_use']))?'checked':''; ?>> 사용
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_social_servicelist">소셜로그인설정</label></th>
            <td colspan="3" class="social_config_explain">
                <div class="explain_box">
                    <input type="checkbox" name="cf_social_servicelist[]" id="check_social_naver" value="naver" <?php echo option_array_checked('naver', $config['cf_social_servicelist']); ?> >
                    <label for="check_social_naver">네이버 로그인을 사용합니다</label>
                    <div>
                    <h3>네이버 CallbackURL</h3>
                    <p><?php echo get_social_callbackurl('naver'); ?></p>
                    </div>
                </div>
                <div class="explain_box">
                    <input type="checkbox" name="cf_social_servicelist[]" id="check_social_kakao" value="kakao" <?php echo option_array_checked('kakao', $config['cf_social_servicelist']); ?> >
                    <label for="check_social_kakao">카카오 로그인을 사용합니다</label>
                    <div>
                    <h3>카카오 웹 Redirect Path</h3>
                    <p><?php echo get_social_callbackurl('kakao', true); ?></p>
                    </div>
                </div>
                <div class="explain_box">
                    <input type="checkbox" name="cf_social_servicelist[]" id="check_social_facebook" value="facebook" <?php echo option_array_checked('facebook', $config['cf_social_servicelist']); ?> >
                    <label for="check_social_facebook">페이스북 로그인을 사용합니다</label>
                    <div>
                    <h3>페이스북 유효한 OAuth 리디렉션 URI</h3>
                    <p><?php echo get_social_callbackurl('facebook'); ?></p>
                    </div>
                </div>
                <div class="explain_box">
                    <input type="checkbox" name="cf_social_servicelist[]" id="check_social_google" value="google" <?php echo option_array_checked('google', $config['cf_social_servicelist']); ?> >
                    <label for="check_social_google">구글 로그인을 사용합니다</label>
                    <div>
                    <h3>구글 승인된 리디렉션 URI</h3>
                    <p><?php echo get_social_callbackurl('google'); ?></p>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_naver_clientid">네이버 Client ID</label></th>
            <td>
                <input type="text" name="cf_naver_clientid" value="<?php echo $config['cf_naver_clientid'] ?>" id="cf_naver_clientid" class="frm_input" size="40"> <a href="https://developers.naver.com/apps/#/register" target="_blank" class="btn_frmline">앱 등록하기</a>
            </td>
            <th scope="row"><label for="cf_naver_secret">네이버 Client Secret</label></th>
            <td>
                <input type="text" name="cf_naver_secret" value="<?php echo $config['cf_naver_secret'] ?>" id="cf_naver_secret" class="frm_input" size="45">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_facebook_appid">페이스북 앱 ID</label></th>
            <td>
                <input type="text" name="cf_facebook_appid" value="<?php echo $config['cf_facebook_appid'] ?>" id="cf_facebook_appid" class="frm_input" size="40"> <a href="https://developers.facebook.com/apps" target="_blank" class="btn_frmline">앱 등록하기</a>
            </td>
            <th scope="row"><label for="cf_facebook_secret">페이스북 앱 Secret</label></th>
            <td>
                <input type="text" name="cf_facebook_secret" value="<?php echo $config['cf_facebook_secret'] ?>" id="cf_facebook_secret" class="frm_input" size="45">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_google_clientid">구글 Client ID</label></th>
            <td>
                <input type="text" name="cf_google_clientid" value="<?php echo $config['cf_google_clientid'] ?>" id="cf_google_clientid" class="frm_input" size="40"> <a href="https://console.developers.google.com" target="_blank" class="btn_frmline">앱 등록하기</a>
            </td>
            <th scope="row"><label for="cf_google_secret">구글 Client Secret</label></th>
            <td>
                <input type="text" name="cf_google_secret" value="<?php echo $config['cf_google_secret'] ?>" id="cf_google_secret" class="frm_input" size="45">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_googl_shorturl_apikey">구글 짧은주소 API Key</label></th>
            <td colspan="3">
                <input type="text" name="cf_googl_shorturl_apikey" value="<?php echo $config['cf_googl_shorturl_apikey'] ?>" id="cf_googl_shorturl_apikey" class="frm_input" size="40"> <a href="http://code.google.com/apis/console/" target="_blank" class="btn_frmline">API Key 등록하기</a>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_kakao_rest_key">카카오 REST API 키</label></th>
            <td>
                <input type="text" name="cf_kakao_rest_key" value="<?php echo $config['cf_kakao_rest_key'] ?>" id="cf_kakao_rest_key" class="frm_input" size="40"> <a href="https://developers.kakao.com/apps/new" target="_blank" class="btn_frmline">앱 등록하기</a>
            </td>
            <th scope="row"><label for="cf_kakao_client_secret">카카오 Client Secret</label></th>
            <td>
                <input type="text" name="cf_kakao_client_secret" value="<?php echo $config['cf_kakao_client_secret'] ?>" id="cf_kakao_client_secret" class="frm_input" size="45">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_kakao_js_apikey">카카오 JavaScript 키</label></th>
            <td colspan="3">
                <input type="text" name="cf_kakao_js_apikey" value="<?php echo $config['cf_kakao_js_apikey'] ?>" id="cf_kakao_js_apikey" class="frm_input" size="45">
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</div>


	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
			  <input type="submit" class="btn btn-success" value="저장"></input>
			</div>
		  </div>
	  </div>


</form>

	</div>
  </div>
</div>
<script>
$(function(){

});

function fconfigform_submit(f)
{
    f.action = "./member_config_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
