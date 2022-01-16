<?
$sub_menu = "100100";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
	alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '기타이용안내설정';
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
							<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">이용안내</a>
							</li>
							<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">배송정보 제공방침안내</a>
							</li>
						</ul>
						<div class="clearfix"></div>
						<div id="myTabContent" class="tab-content">
							<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
								<div class="" role="tabpanel2" data-example-id="togglable-tabs">
									<ul id="myTab1" class="nav nav-tabs bar_tabs" role="tablist">
										<li role="presentation" class="active"><a href="#de_user_reg_info" id="de_user_reg_info-tab" role="tab" data-toggle="tab" aria-expanded="true">회원가입안내</a>
										</li>
										<li role="presentation" class=""><a href="#de_order_info" role="tab" id="de_order_info-tab" data-toggle="tab" aria-expanded="false">법적고지</a>
										</li>
										<li role="presentation" class=""><a href="#de_pay_info" role="tab" id="de_pay_info-tab" data-toggle="tab" aria-expanded="false">이용안내</a>
										</li>
										<li role="presentation" class=""><a href="#de_shipping_info" role="tab" id="de_shipping_info-tab" data-toggle="tab" aria-expanded="false">배송안내</a>
										</li>
										<li role="presentation" class=""><a href="#de_exchange_info" role="tab" id="de_exchange_info-tab" data-toggle="tab" aria-expanded="false">교환안내</a>
										</li>
										<li role="presentation" class=""><a href="#de_refund_info" role="tab" id="de_refund_info-tab" data-toggle="tab" aria-expanded="false">환불안내</a>
										</li>
										<li role="presentation" class=""><a href="#de_point_info" role="tab" id="de_point_info-tab" data-toggle="tab" aria-expanded="false">적립금안내</a>
										</li>
									</ul>
									<div class="clearfix"></div>
									<div id="myTabContent2" class="tab-content">
										<div role="tabpanel2" class="tab-pane fade active in" id="de_user_reg_info" aria-labelledby="de_user_reg_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 회원가입안내 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_user_reg_info', get_text($default['de_user_reg_info'], 0)); ?>
										</div>

										<div role="tabpanel2" class="tab-pane fade" id="de_order_info" aria-labelledby="de_order_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 법적고지 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_order_info', get_text($default['de_order_info'], 0)); ?>
										</div>

										<div role="tabpanel2" class="tab-pane fade" id="de_pay_info" aria-labelledby="de_pay_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 이용안내 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_pay_info', get_text($default['de_pay_info'], 0)); ?>
										</div>

										<div role="tabpanel2" class="tab-pane fade" id="de_shipping_info" aria-labelledby="de_shipping_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 배송안내 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_shipping_info', get_text($default['de_shipping_info'], 0)); ?>
										</div>

										<div role="tabpanel2" class="tab-pane fade" id="de_exchange_info" aria-labelledby="de_exchange_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 교환안내 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_exchange_info', get_text($default['de_exchange_info'], 0)); ?>
										</div>

										<div role="tabpanel2" class="tab-pane fade" id="de_refund_info" aria-labelledby="de_refund_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 환불안내 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_refund_info', get_text($default['de_refund_info'], 0)); ?>
										</div>

										<div role="tabpanel2" class="tab-pane fade" id="de_point_info" aria-labelledby="de_point_info-tab">
											<div class="x_title">
												<h4><span class="fa fa-check-square"></span> 적립금안내 <small></small></h4>
												<div class="clearfix"></div>
											</div>
											<?=editor_html('de_point_info', get_text($default['de_point_info'], 0)); ?>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
								<div class="x_title">
									<h4><span class="fa fa-check-square"></span> 배송정보 제공방침 안내 <small></small></h4>
									<div class="clearfix"></div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="de_admin_company_saupja_no">사용여부</span>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="radio">
											<label><input type="radio" class="flat" name="de_baesong_content_use" id="de_baesong_content_use0" value="1" <? if($default['de_baesong_content_use'] == "1") { ?>checked="" <? } ?> required /> 사용함</label>
											<label><input type="radio" class="flat" name="de_baesong_content_use" id="de_baesong_content_use1" value="0" <? if($default['de_baesong_content_use'] == "0") { ?>checked="" <? } ?> /> 사용안함</label>
										</div>
										<br/>
										<label>※ 배송정보제공 사용함으로 설정하시면, 주문시 반드시 배송정보제공 동의를 해야만 주문이 가능합니다.</label>
									</div>
								</div>
								<div class="ln_solid"></div>
								<?=editor_html('de_baesong_content', get_text($default['de_baesong_content'], 0)); ?>
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
		function fconfigform_submit(f){
			<?=get_editor_js('de_user_reg_info'); ?>
			<?=get_editor_js('de_order_info'); ?>
			<?=get_editor_js('de_pay_info'); ?>
			<?=get_editor_js('de_shipping_info'); ?>
			<?=get_editor_js('de_exchange_info'); ?>
			<?=get_editor_js('de_point_info'); ?>
			<?=get_editor_js('de_refund_info'); ?>
			<?=get_editor_js('de_baesong_content'); ?>
			f.action = "./configform_etc_update.php";
			return true;
		}
	</script>
	<? include_once ('./admin.tail.php'); ?>
