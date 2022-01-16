<?php
$sub_menu = "100100";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '이용약관설정';
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
				<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">쇼핑몰 이용약관</a>
				</li>
				<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">개인정보 처리방침</a>
				</li>
				<li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">리스 계약서</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">					
					<div class="x_title">
						<h4><span class="fa fa-check-square"></span> 쇼핑몰 이용약관 <small></small></h4>
						<div class="clearfix"></div>
					</div>

					<?php echo editor_html('cf_stipulation', get_text($config['cf_stipulation'], 0)); ?>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
					
					<div class="" role="tabpanel2" data-example-id="togglable-tabs">
                      <ul id="myTab1" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#privacy" id="privacy-tab" role="tab" data-toggle="tab" aria-expanded="true">개인정보 처리방침 전체내용</a>
                        </li>
                        <li role="presentation" class=""><a href="#user_privacy" role="tab" id="user_privacy-tab" data-toggle="tab" aria-expanded="false">개인정보 수집 및 이용동의 (회원가입시)</a>
                        </li>
                        <li role="presentation" class=""><a href="#collection_privacy" role="tab" id="collection_privacy-tab" data-toggle="tab" aria-expanded="false">개인정보 수집 및 이용동의(고유식별정보수집 동의)</a>
                        </li>
                      </ul>
					  <div class="clearfix"></div>
                      <div id="myTabContent2" class="tab-content">

                        <div role="tabpanel2" class="tab-pane fade active in" id="privacy" aria-labelledby="privacy-tab">
							<div class="x_title">
								<h4><span class="fa fa-check-square"></span> 개인정보 처리방침 전체내용 <small></small></h4>
								<div class="clearfix"></div>
							</div>
							<?php echo editor_html('cf_privacy', get_text($config['cf_privacy'], 0)); ?>                       
                        </div>

                        <div role="tabpanel2" class="tab-pane fade" id="user_privacy" aria-labelledby="user_privacy-tab">
							<div class="x_title">
								<h4><span class="fa fa-check-square"></span> 개인정보 수집 및 이용동의 (회원가입시) <small></small></h4>
								<div class="clearfix"></div>
							</div>
							<?php echo editor_html('cf_user_privacy', get_text($config['cf_user_privacy'], 0)); ?>                          
                        </div>

                        <div role="tabpanel2" class="tab-pane fade" id="collection_privacy" aria-labelledby="collection_privacy-tab">
							<div class="x_title">
								<h4><span class="fa fa-check-square"></span> 개인정보 수집 및 이용동의(고유식별정보수집 동의) <small></small></h4>
								<div class="clearfix"></div>
							</div>
							<?php echo editor_html('cf_collection_privacy', get_text($config['cf_collection_privacy'], 0)); ?>
                        </div>

                      </div>
                    </div>

				</div>

				<div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab2">
					<div class="form-group" hidden>
						<label class="control-label col-md-2 col-sm-2 col-xs-12" for="de_admin_company_saupja_no">청약철회방침 사용여부</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="cf_contract_cancel_use" id="cf_contract_cancel_use0" value="1" <? if($config['cf_contract_cancel_use'] == "1") { ?>checked="" <? } ?> required /> 사용함</label>
	                        <label><input type="radio" class="flat" name="cf_contract_cancel_use" id="cf_contract_cancel_use1" value="0" <? if($config['cf_contract_cancel_use'] == "0") { ?>checked="" <? } ?> /> 사용안함</label>
						</div>
						</div>
					</div>
                    <div class="ln_solid"></div>

					<div class="x_title">
						<h4><span class="fa fa-check-square"></span> 리스 계약서 <small></small></h4>
						<div class="clearfix"></div>
					</div>

					<?php echo editor_html('cf_contract_cancel', get_text($config['cf_contract_cancel'], 0)); ?>


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
    <?php echo get_editor_js('cf_stipulation'); ?>
    <?php echo get_editor_js('cf_privacy'); ?>
    <?php echo get_editor_js('cf_user_privacy'); ?>
    <?php echo get_editor_js('cf_collection_privacy'); ?>
    <?php echo get_editor_js('cf_contract_cancel'); ?>

    f.action = "./configform_stipulation_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
