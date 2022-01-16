<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '개인정보제공설정';
include_once ('./admin.head.php');
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="" id="token">

		<div class="x_content">

			<div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
				<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">개인정보 제3자 제공 동의</a>
				</li>
				<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">개인정보 처리 위탁 동의</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">	
				
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">개인정보 제3자 제공동의 사용설정</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="cf_user_thirdparty_privacy_use" id="cf_user_thirdparty_privacy_use0" value="1" <? if($config['cf_user_thirdparty_privacy_use'] == "1") { ?>checked="" <? } ?> required /> 사용함</label>
	                        <label><input type="radio" class="flat" name="cf_user_thirdparty_privacy_use" id="cf_user_thirdparty_privacy_use1" value="0" <? if($config['cf_user_thirdparty_privacy_use'] == "0") { ?>checked="" <? } ?> /> 사용안함</label>
						</div>
						</div>
					</div>
                    <div class="ln_solid"></div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">약관내용</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<textarea class="resizable_textarea form-control" name="cf_user_thirdparty_privacy" rows="10"><?=$config['cf_user_thirdparty_privacy']?></textarea>
						</div>
					</div>


				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
					
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">개인정보처리 위탁동의 사용설정</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="cf_consent_privacy_use" id="cf_consent_privacy_use0" value="1" <? if($config['cf_consent_privacy_use'] == "1") { ?>checked="" <? } ?> required /> 사용함</label>
	                        <label><input type="radio" class="flat" name="cf_consent_privacy_use" id="cf_consent_privacy_use1" value="0" <? if($config['cf_consent_privacy_use'] == "0") { ?>checked="" <? } ?> /> 사용안함</label>
						</div>
						</div>
					</div>
                    <div class="ln_solid"></div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">개인정보처리 위탁동의 약관내용</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<textarea class="resizable_textarea form-control" name="cf_consent_privacy" rows="10"><?=$config['cf_consent_privacy']?></textarea>
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
    

});

function fconfigform_submit(f)
{

    f.action = "./configform_privacy_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
