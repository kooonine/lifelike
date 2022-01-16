<?php
$sub_menu = "700110";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');


$g5['title'] = '회원가입항목설정';
include_once ('./admin.head.php');

?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
    <form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
    <input type="hidden" name="token" value="" id="token">
    <input type="hidden" name="cf_1" id="cf_1" value="<?php echo get_text($config['cf_1']) ?>">
    <input type="hidden" name="cf_2" id="cf_2" value="<?php echo get_text($config['cf_2']) ?>">

    <div class="x_content">
    	<div class="" role="tabpanel" data-example-id="togglable-tabs">
    	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
    		<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">회원가입 항목</a>
    		</li>
    		<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">회원정보 수정 항목</a>
    		</li>
    		<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">SNS 연동설정</a>
    		</li>
    	  </ul>
    	  <div class="clearfix"></div>
    	  
    	  <div id="myTabContent" class="tab-content">
			<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">	

    <div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 기본정보</h4>
    </div>
    
<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원가입 설정</caption>
        <colgroup>
            <col class="grid_3">
            <col class="grid_4">
            <col class="grid_4">
        </colgroup>
        <thead>
        	<tr>
        		<th>항목명</th>
        		<th>일반회원 가입 사용여부</th>
        		<th>사업자회원 가입 사용여부</th>
        	</tr>
        </thead>
        <tbody>
        	<tr>
        		<th>아이디</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>비밀번호</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>성명</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> - </td>
        	</tr>
        	<tr>
        		<th>휴대전화번호</th>
        		<td> <input type="radio" checked="checked" readonly="readonly" name="cf_1_reg_hp_use" value="1" > 사용필수</td>
        		<td> <label><input type="checkbox" name="cf_2_reg_hp_use" value="1" id="cf_2_reg_hp_use" > 보이기</label></td>
        	</tr>
        	<tr>
        		<th>연락처</th>
        		<td> <label><input type="checkbox" name="cf_1_reg_tel_use" value="1" id="cf_1_reg_tel_use" > 보이기</label></td>
        		<td> <input type="radio" checked="checked"  name="cf_2_reg_tel_use" value="1" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>E-mail 주소</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>사업자 등록번호</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>업태/종목</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>회사명</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>대표자명</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>세금계산서 발행 이메일 주소</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>세금계산서 발행 담당자명</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>세금계산서 발행 연락처</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>기본주소</th>
        		<td> <label><input type="checkbox" name="cf_1_reg_addr1_use" value="1" id="cf_1_reg_addr1_use" > 보이기</label></td>
        		<td> <input type="radio" checked="checked" name="cf_2_reg_addr1_use" value="1" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>나머지주소</th>
        		<td> <label><input type="checkbox" name="cf_1_reg_addr2_use" value="1" id="cf_1_reg_addr2_use" > 보이기</label></td>
        		<td> <input type="radio" checked="checked" name="cf_2_reg_addr2_use" value="1" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>SMS 수신동의</th>
        		<td> <label><input type="checkbox" name="cf_1_reg_sms_use" value="1" id="cf_1_reg_sms_use" > 보이기</label></td>
        		<td> <label><input type="checkbox" name="cf_2_reg_sms_use" value="1" id="cf_2_reg_sms_use" > 보이기</label></td>
        	</tr>
        	<tr>
        		<th>E-mail 수신동의</th>
        		<td> <label><input type="checkbox" name="cf_1_reg_mailing_use" value="1" id="cf_1_reg_mailing_use" > 보이기</label></td>
        		<td> <label><input type="checkbox" name="cf_2_reg_mailing_use" value="1" id="cf_2_reg_mailing_use" > 보이기</label></td>
        	</tr>
        </tbody>
        </table>
    </div>
</div>

			</div>
			<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

    <div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 기본정보</h4>
    </div>
    
<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원정보 수정 설정</caption>
        <colgroup>
            <col class="grid_3">
            <col class="grid_4">
            <col class="grid_4">
        </colgroup>
        <thead>
        	<tr>
        		<th>항목명</th>
        		<th>일반회원 수정 사용여부/필수입력여부</th>
        		<th>사업자회원 수정 사용여부/필수입력여부</th>
        	</tr>
        </thead>
        <tbody>
        	<tr>
        		<th>아이디</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>비밀번호</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>성명</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> - </td>
        	</tr>
        	<tr>
        		<th>휴대전화번호</th>
        		<td> <input type="radio" checked="checked" readonly="readonly" name="cf_1_mod_hp_use" value="1" > 사용필수</td>
        		<td> <label><input type="checkbox" name="cf_2_mod_hp_use" value="1" id="cf_2_mod_hp_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_2_mod_hp_req" value="1" id="cf_2_mod_hp_req" > 필수입력</label></td>
        	</tr>
        	<tr>
        		<th>연락처</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_tel_use" value="1" id="cf_1_mod_tel_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_tel_req" value="1" id="cf_1_mod_tel_req" > 필수입력</label></td>
        		<td> <input type="radio" checked="checked"  name="cf_2_mod_tel_use" value="1" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>E-mail 주소</th>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>사업자 등록번호</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>업태/종목</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>회사명</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>대표자명</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>세금계산서 발행 이메일 주소</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>세금계산서 발행 담당자명</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>세금계산서 발행 연락처</th>
        		<td> -</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>기본주소</th>
        		<td> 
        			<label><input type="checkbox" name="cf_1_mod_addr1_use" value="1" id="cf_1_mod_addr1_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_addr1_req" value="1" id="cf_1_mod_addr1_req" > 필수입력</label>
        		</td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>나머지주소</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_addr2_use" value="1" id="cf_1_mod_addr2_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_addr2_req" value="1" id="cf_1_mod_addr2_req" > 필수입력</label></td>
        		<td> <input type="radio" checked="checked" readonly="readonly"> 사용필수</td>
        	</tr>
        	<tr>
        		<th>SMS 수신동의</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_sms_use" value="1" id="cf_1_mod_sms_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_sms_req" value="1" id="cf_1_mod_sms_req" > 필수입력</label></td>
        		<td> <label><input type="checkbox" name="cf_2_mod_sms_use" value="1" id="cf_2_mod_sms_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_2_mod_sms_req" value="1" id="cf_2_mod_sms_req" > 필수입력</label></td>
        	</tr>
        	<tr>
        		<th>E-mail 수신동의</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_mailing_use" value="1" id="cf_1_mod_mailing_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_mailing_req" value="1" id="cf_1_mod_mailing_req" > 필수입력</label></td>
        		<td> <label><input type="checkbox" name="cf_2_mod_mailing_use" value="1" id="cf_2_mod_mailing_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_2_mod_mailing_req" value="1" id="cf_2_mod_mailing_req" > 필수입력</label></td>
        	</tr>
        </tbody>
        </table>
    </div>
</div>
			
			<div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 추가정보</h4>
    </div>
    
<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>회원정보 수정 설정</caption>
        <colgroup>
            <col class="grid_3">
            <col class="grid_4">
            <col class="grid_4">
        </colgroup>
        <thead>
        	<tr>
        		<th>항목명</th>
        		<th>일반회원 수정 사용여부/필수입력여부</th>
        		<th>사업자회원 수정 사용여부/필수입력여부</th>
        	</tr>
        </thead>
        <tbody>
        	<tr>
        		<th>닉네임</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_nick_use" value="1" id="cf_1_mod_nick_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_nick_req" value="1" id="cf_1_mod_nick_req" > 필수입력</label></td>
        		<td> <label><input type="checkbox" name="cf_2_mod_nick_use" value="1" id="cf_2_mod_nick_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_2_mod_nick_req" value="1" id="cf_2_mod_nick_req" > 필수입력</label></td>
        	</tr>
        	<tr>
        		<th>프로필사진</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_profile_use" value="1" id="cf_1_mod_profile_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_profile_req" value="1" id="cf_1_mod_profile_req" > 필수입력</label></td>
        		<td> -</td>
        	</tr>
        	<tr>
        		<th>생년월일</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_birth_use" value="1" id="cf_1_mod_birth_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_birth_req" value="1" id="cf_1_mod_birth_req" > 필수입력</label></td>
        		<td> -</td>
        	</tr>
        	<tr>
        		<th>성별</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_sex_use" value="1" id="cf_1_mod_sex_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_sex_req" value="1" id="cf_1_mod_sex_req" > 필수입력</label></td>
        		<td>-</td>
        	</tr>
        	<tr>
        		<th>결혼유무</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_wedding_use" value="1" id="cf_1_mod_wedding_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_wedding_req" value="1" id="cf_1_mod_wedding_req" > 필수입력</label></td>
        		<td> -</td>
        	</tr>
        	<tr>
        		<th>추천인 아이디</th>
        		<td> <label><input type="checkbox" name="cf_1_mod_recommend_use" value="1" id="cf_1_mod_recommend_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_1_mod_recommend_req" value="1" id="cf_1_mod_recommend_req" > 필수입력</label></td>
        		<td> <label><input type="checkbox" name="cf_2_mod_recommend_use" value="1" id="cf_2_mod_recommend_use" > 보이기</label>
        			<label><input type="checkbox" name="cf_2_mod_recommend_req" value="1" id="cf_2_mod_recommend_req" > 필수입력</label></td>
        	</tr>
		</tbody>
		</table>
	</div>
</div>
			</div>
			
			
			<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab2">
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
			
			</div>
    	  </div>
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
	if($("#cf_1").val() != "")
	{
		var cf_1 = JSON.parse($("#cf_1").val().replace("&#034;","\""));

		$('input[name="cf_1_reg_hp_use"]:checkbox[value="'+cf_1.reg_hp_use+'"]').prop("checked",true);
		$('input[name="cf_1_reg_tel_use"]:checkbox[value="'+cf_1.reg_tel_use+'"]').prop("checked",true);
		$('input[name="cf_1_reg_addr1_use"]:checkbox[value="'+cf_1.reg_addr1_use+'"]').prop("checked",true);
		$('input[name="cf_1_reg_addr2_use"]:checkbox[value="'+cf_1.reg_addr2_use+'"]').prop("checked",true);
		$('input[name="cf_1_reg_sms_use"]:checkbox[value="'+cf_1.reg_sms_use+'"]').prop("checked",true);
		$('input[name="cf_1_reg_mailing_use"]:checkbox[value="'+cf_1.reg_mailing_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_tel_use"]:checkbox[value="'+cf_1.mod_tel_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_tel_req"]:checkbox[value="'+cf_1.mod_tel_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_addr1_use"]:checkbox[value="'+cf_1.mod_addr1_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_addr1_req"]:checkbox[value="'+cf_1.mod_addr1_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_addr2_use"]:checkbox[value="'+cf_1.mod_addr2_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_addr2_req"]:checkbox[value="'+cf_1.mod_addr2_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_sms_use"]:checkbox[value="'+cf_1.mod_sms_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_sms_req"]:checkbox[value="'+cf_1.mod_sms_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_mailing_use"]:checkbox[value="'+cf_1.mod_mailing_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_mailing_req"]:checkbox[value="'+cf_1.mod_mailing_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_nick_use"]:checkbox[value="'+cf_1.mod_nick_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_nick_req"]:checkbox[value="'+cf_1.mod_nick_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_profile_use"]:checkbox[value="'+cf_1.mod_profile_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_profile_req"]:checkbox[value="'+cf_1.mod_profile_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_birth_use"]:checkbox[value="'+cf_1.mod_birth_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_birth_req"]:checkbox[value="'+cf_1.mod_birth_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_sex_use"]:checkbox[value="'+cf_1.mod_sex_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_sex_req"]:checkbox[value="'+cf_1.mod_sex_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_wedding_use"]:checkbox[value="'+cf_1.mod_wedding_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_wedding_req"]:checkbox[value="'+cf_1.mod_wedding_req+'"]').prop("checked",true);
		$('input[name="cf_1_mod_recommend_use"]:checkbox[value="'+cf_1.mod_recommend_use+'"]').prop("checked",true);
		$('input[name="cf_1_mod_recommend_req"]:checkbox[value="'+cf_1.mod_recommend_req+'"]').prop("checked",true);
	}

	if($("#cf_2").val() != "")
	{
		var cf_2 = JSON.parse($("#cf_2").val().replace("&#034;","\""));
		
		$('input[name="cf_2_reg_hp_use"]:checkbox[value="'+cf_2.reg_hp_use+'"]').prop("checked",true);
		$('input[name="cf_2_reg_tel_use"]:checkbox[value="'+cf_2.reg_tel_use+'"]').prop("checked",true);
		$('input[name="cf_2_reg_addr1_use"]:checkbox[value="'+cf_2.reg_addr1_use+'"]').prop("checked",true);
		$('input[name="cf_2_reg_sms_use"]:checkbox[value="'+cf_2.reg_sms_use+'"]').prop("checked",true);
		$('input[name="cf_2_reg_mailing_use"]:checkbox[value="'+cf_2.reg_mailing_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_hp_use"]:checkbox[value="'+cf_2.mod_hp_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_hp_req"]:checkbox[value="'+cf_2.mod_hp_req+'"]').prop("checked",true);
		$('input[name="cf_2_mod_tel_use"]:checkbox[value="'+cf_2.mod_tel_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_sms_use"]:checkbox[value="'+cf_2.mod_sms_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_sms_req"]:checkbox[value="'+cf_2.mod_sms_req+'"]').prop("checked",true);
		$('input[name="cf_2_mod_mailing_use"]:checkbox[value="'+cf_2.mod_mailing_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_mailing_req"]:checkbox[value="'+cf_2.mod_mailing_req+'"]').prop("checked",true);
		$('input[name="cf_2_mod_nick_use"]:checkbox[value="'+cf_2.mod_nick_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_nick_req"]:checkbox[value="'+cf_2.mod_nick_req+'"]').prop("checked",true);
		$('input[name="cf_2_mod_recommend_use"]:checkbox[value="'+cf_2.mod_recommend_use+'"]').prop("checked",true);
		$('input[name="cf_2_mod_recommend_req"]:checkbox[value="'+cf_2.mod_recommend_req+'"]').prop("checked",true);
		
	}
});

function fconfigform_submit(f)
{
	var cf_1 = {};
	cf_1.reg_hp_use = $('input[name="cf_1_reg_hp_use"]:checked').val();
	cf_1.reg_tel_use = $('input[name="cf_1_reg_tel_use"]:checked').val();
	cf_1.reg_addr1_use = $('input[name="cf_1_reg_addr1_use"]:checked').val();
	cf_1.reg_addr2_use = $('input[name="cf_1_reg_addr2_use"]:checked').val();
	cf_1.reg_sms_use = $('input[name="cf_1_reg_sms_use"]:checked').val();
	cf_1.reg_mailing_use = $('input[name="cf_1_reg_mailing_use"]:checked').val();
	cf_1.mod_tel_use = $('input[name="cf_1_mod_tel_use"]:checked').val();
	cf_1.mod_tel_req = $('input[name="cf_1_mod_tel_req"]:checked').val();
	cf_1.mod_addr1_use = $('input[name="cf_1_mod_addr1_use"]:checked').val();
	cf_1.mod_addr1_req = $('input[name="cf_1_mod_addr1_req"]:checked').val();
	cf_1.mod_addr2_use = $('input[name="cf_1_mod_addr2_use"]:checked').val();
	cf_1.mod_addr2_req = $('input[name="cf_1_mod_addr2_req"]:checked').val();
	cf_1.mod_sms_use = $('input[name="cf_1_mod_sms_use"]:checked').val();
	cf_1.mod_sms_req = $('input[name="cf_1_mod_sms_req"]:checked').val();
	cf_1.mod_mailing_use = $('input[name="cf_1_mod_mailing_use"]:checked').val();
	cf_1.mod_mailing_req = $('input[name="cf_1_mod_mailing_req"]:checked').val();
	cf_1.mod_nick_use = $('input[name="cf_1_mod_nick_use"]:checked').val();
	cf_1.mod_nick_req = $('input[name="cf_1_mod_nick_req"]:checked').val();
	cf_1.mod_profile_use = $('input[name="cf_1_mod_profile_use"]:checked').val();
	cf_1.mod_profile_req = $('input[name="cf_1_mod_profile_req"]:checked').val();
	cf_1.mod_birth_use = $('input[name="cf_1_mod_birth_use"]:checked').val();
	cf_1.mod_birth_req = $('input[name="cf_1_mod_birth_req"]:checked').val();
	cf_1.mod_sex_use = $('input[name="cf_1_mod_sex_use"]:checked').val();
	cf_1.mod_sex_req = $('input[name="cf_1_mod_sex_req"]:checked').val();
	cf_1.mod_wedding_use = $('input[name="cf_1_mod_wedding_use"]:checked').val();
	cf_1.mod_wedding_req = $('input[name="cf_1_mod_wedding_req"]:checked').val();
	cf_1.mod_recommend_use = $('input[name="cf_1_mod_recommend_use"]:checked').val();
	cf_1.mod_recommend_req = $('input[name="cf_1_mod_recommend_req"]:checked').val();
	
	$('input[name="cf_1"]').val(JSON.stringify(cf_1));

	
	var cf_2 = {};
	cf_2.reg_hp_use = $('input[name="cf_2_reg_hp_use"]:checked').val();
	cf_2.reg_tel_use = $('input[name="cf_2_reg_tel_use"]:checked').val();
	cf_2.reg_addr1_use = $('input[name="cf_2_reg_addr1_use"]:checked').val();
	cf_2.reg_sms_use = $('input[name="cf_2_reg_sms_use"]:checked').val();
	cf_2.reg_mailing_use = $('input[name="cf_2_reg_mailing_use"]:checked').val();
	cf_2.mod_hp_use = $('input[name="cf_2_mod_hp_use"]:checked').val();
	cf_2.mod_hp_req = $('input[name="cf_2_mod_hp_req"]:checked').val();
	cf_2.mod_tel_use = $('input[name="cf_2_mod_tel_use"]:checked').val();
	cf_2.mod_sms_use = $('input[name="cf_2_mod_sms_use"]:checked').val();
	cf_2.mod_sms_req = $('input[name="cf_2_mod_sms_req"]:checked').val();
	cf_2.mod_mailing_use = $('input[name="cf_2_mod_mailing_use"]:checked').val();
	cf_2.mod_mailing_req = $('input[name="cf_2_mod_mailing_req"]:checked').val();
	cf_2.mod_nick_use = $('input[name="cf_2_mod_nick_use"]:checked').val();
	cf_2.mod_nick_req = $('input[name="cf_2_mod_nick_req"]:checked').val();
	cf_2.mod_recommend_use = $('input[name="cf_2_mod_recommend_use"]:checked').val();
	cf_2.mod_recommend_req = $('input[name="cf_2_mod_recommend_req"]:checked').val();
	
	$('input[name="cf_2"]').val(JSON.stringify(cf_2));
	
    f.action = "./member_config_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
