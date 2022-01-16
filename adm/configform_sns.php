<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = 'SNS 로그인 설정';
include_once ('./admin.head.php');
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="" id="token">

	  	<div class="x_title">
			<h4><span class="fa fa-check-square"></span> 로그인 연동 설정<small></small></h4>
			<label class="nav navbar-right"></label>
			<div class="clearfix"></div>
	  	</div>
	  	
		<div class="x_content">
		
			<div class="form-group">
    			<label class="control-label col-md-3 col-sm-3 col-xs-12 align-middle" for="check_social_facebook0">페이스북 로그인</span>
    			</label>
    			<div class="col-md-9 col-sm-9 col-xs-12">
    				<div class="input-group col-sm-12 col-sm-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="check_social_facebook" id="check_social_facebook0" value="facebook"  required <?php echo option_array_checked('facebook', $config['cf_social_servicelist']); ?> /> 사용함</label>
	                        <label><input type="radio" class="flat" name="check_social_facebook" id="check_social_facebook1" value="" <?php echo (empty(option_array_checked('facebook', $config['cf_social_servicelist']))?"checked":""); ?>/> 사용안함</label>
						</div>
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_appid">App ID</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_facebook_appid" value="<?php echo $config['cf_facebook_appid']; ?>" id="cf_facebook_appid" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_secret">App Secret Code</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_facebook_secret" value="<?php echo $config['cf_facebook_secret']; ?>" id="cf_facebook_secret" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    
    			</div>
    		</div>
    		
    		<div class="ln_solid"></div>
		
			<div class="form-group">
    			<label class="control-label col-md-3 col-sm-3 col-xs-12 align-middle" for="check_social_kakao0">카카오 계정 로그인</span>
    			</label>
    			<div class="col-md-9 col-sm-9 col-xs-12">
    				<div class="input-group col-sm-12 col-sm-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="check_social_kakao" id="check_social_kakao0" value="kakao"  required <?php echo option_array_checked('kakao', $config['cf_social_servicelist']); ?> /> 사용함</label>
	                        <label><input type="radio" class="flat" name="check_social_kakao" id="check_social_kakao1" value="" <?php echo (empty(option_array_checked('kakao', $config['cf_social_servicelist']))?"checked":""); ?>/> 사용안함</label>
						</div>
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_appid">REST API</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_kakao_rest_key" value="<?php echo $config['cf_kakao_rest_key']; ?>" id="cf_kakao_rest_key" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_secret">Client Secret Key</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_kakao_client_secret" value="<?php echo $config['cf_kakao_client_secret']; ?>" id="cf_kakao_client_secret" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_secret">JavaScript Key</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_kakao_js_apikey" value="<?php echo $config['cf_kakao_js_apikey']; ?>" id="cf_kakao_js_apikey" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    
    			</div>
    		</div>
    		
    		<div class="ln_solid"></div>
		
			<div class="form-group">
    			<label class="control-label col-md-3 col-sm-3 col-xs-12 align-middle" for="check_social_naver0">네이버 로그인</span>
    			</label>
    			<div class="col-md-9 col-sm-9 col-xs-12">
    				<div class="input-group col-sm-12 col-sm-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="check_social_naver" id="check_social_naver0" value="naver"  required <?php echo option_array_checked('naver', $config['cf_social_servicelist']); ?> /> 사용함</label>
	                        <label><input type="radio" class="flat" name="check_social_naver" id="check_social_naver1" value="" <?php echo (empty(option_array_checked('naver', $config['cf_social_servicelist']))?"checked":""); ?>/> 사용안함</label>
						</div>
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_naver_clientid">Client ID</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_naver_clientid" value="<?php echo $config['cf_naver_clientid']; ?>" id="cf_naver_clientid" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_naver_secret">Client Secret</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_naver_secret" value="<?php echo $config['cf_naver_secret']; ?>" id="cf_naver_secret" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    
    			</div>
    		</div>
    		
    		<div class="ln_solid"></div>
		
			<div class="form-group">
    			<label class="control-label col-md-3 col-sm-3 col-xs-12 align-middle" for="check_social_google0">구글플러스 로그인</span>
    			</label>
    			<div class="col-md-9 col-sm-9 col-xs-12">
    				<div class="input-group col-sm-12 col-sm-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="check_social_google" id="check_social_google0" value="google"  required <?php echo option_array_checked('google', $config['cf_social_servicelist']); ?> /> 사용함</label>
	                        <label><input type="radio" class="flat" name="check_social_google" id="check_social_google1" value="" <?php echo (empty(option_array_checked('google', $config['cf_social_servicelist']))?"checked":""); ?>/> 사용안함</label>
						</div>
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_google_clientid">Client ID</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_google_clientid" value="<?php echo $config['cf_google_clientid']; ?>" id="cf_google_clientid" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_secret">Client Secret</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_google_secret" value="<?php echo $config['cf_google_secret']; ?>" id="cf_google_secret" class="form-control col-md-12 col-xs-12" size="30">
    				</div>
    				
    				<label class="text-left col-md-3 col-sm-3 col-xs-12" style="padding-top: 8px;" for="cf_facebook_secret">짧은주소 API Key</span>
    				</label>
    				<div class="input-group col-sm-9 col-sm-9">
    					<input type="text" name="cf_googl_shorturl_apikey" value="<?php echo $config['cf_googl_shorturl_apikey']; ?>" id="cf_googl_shorturl_apikey" class="form-control col-md-12 col-xs-12" size="30">
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
    

});

function fconfigform_submit(f)
{

    f.action = "./configform_sns_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
