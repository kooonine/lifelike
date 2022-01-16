<?php
$sub_menu = "101510";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
	alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '배송/반품 설정';
include_once('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

get_admin_token();
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
				<input type="hidden" name="token" value="" id="token">

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 기본설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_type">배송방법</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="de_send_type" id="de_send_type">
								<option value="택배" <?php echo get_selected($default['de_send_type'], '택배'); ?>>택배</option>
								<option value="빠른등기" <?php echo get_selected($default['de_send_type'], '빠른등기'); ?>>빠른등기</option>
								<option value="기타" <?php echo get_selected($default['de_send_type'], '기타'); ?>>기타</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_term_start">배송기간</span>
						</label>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<input type="text" name="de_send_term_start" value="<?php echo $default['de_send_term_start']; ?>" id="de_send_term_start" class="form-control">
						</div>
						<label class="col-md-1 col-sm-1 col-xs-1" style="padding-top:8px;">일 ~</label>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<input type="text" name="de_send_term_end" value="<?php echo $default['de_send_term_end']; ?>" id="de_send_term_end" class="form-control">
						</div>
						<label class="control-label">일 정도 소요됩니다.</label>
					</div>

					<div class="form-group" id="dvCostCase1">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_cost_limit">기본 배송비 설정</span>
						</label>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<input type="hidden" name="de_send_cost_case" id="de_send_cost_case" value="고정" />
							<input type="text" name="de_send_cost_limit" value="<?php echo $default['de_send_cost_limit']; ?>" id="de_send_cost_limit" class="form-control">
						</div>
						<label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원 미만일 때 배송비</label>
						<div class="col-md-1 col-sm-1 col-xs-1">
							<input type="text" name="de_send_cost_list" value="<?php echo $default['de_send_cost_list']; ?>" id="de_send_cost_list" class="form-control">
						</div>
						<label class="control-label">원을 부과합니다.</label>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_condition">배송료 청구기준<br />주문금액 조건설정</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="radio">
								<label><input type="radio" class="flat" name="de_send_condition" id="de_send_condition0" value="판매" <?php echo option_array_checked('판매', $default['de_send_condition']); ?> /> 할인전, 정상판매가격 기준(권장)</label>
								<label><input type="radio" class="flat" name="de_send_condition" id="de_send_condition1" value="최종" <?php echo option_array_checked('최종', $default['de_send_condition']); ?> /> 최종 주문(결제)금액 기준</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_prepayment">배송비 선결제 설정</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="radio">
								<label><input type="radio" class="flat" name="de_send_prepayment" id="de_send_prepayment1" value="선결제" checked="checked" /> 선결제</label>
								<!-- <label><input type="radio" class="flat" name="de_send_prepayment" id="de_send_prepayment0" value="착불"  <?php echo option_array_checked('착불', $default['de_send_prepayment']); ?> /> 착불</label>
                    <label><input type="radio" class="flat" name="de_send_prepayment" id="de_send_prepayment2" value="착불/선결제" <?php echo option_array_checked('착불/선결제', $default['de_send_prepayment']); ?>/> 착불/선결제</label> -->
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_individual_costs_use">상품별 개별배송비 설정</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="radio">
								<!-- label><input type="radio" class="flat" name="de_individual_costs_use" id="de_individual_costs_use0" value="0"  <?php echo option_array_checked('0', $default['de_individual_costs_use']); ?> /> 사용안함</label -->
								<label><input type="radio" class="flat" name="de_individual_costs_use" id="de_individual_costs_use1" value="1" <?php echo option_array_checked('1', $default['de_individual_costs_use']); ?> /> 사용함</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_tracking_api_company">택배사 목록 API</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" name="de_tracking_api_company" value="<?php echo $default['de_tracking_api_company']; ?>" id="de_tracking_api_company" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_tracking_api">배송조회 API URL</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" name="de_tracking_api" value="<?php echo $default['de_tracking_api']; ?>" id="de_tracking_api" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_tracking_api_key">배송조회 API KEY</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" name="de_tracking_api_key" value="<?php echo $default['de_tracking_api_key']; ?>" id="de_tracking_api_key" class="form-control">
						</div>
					</div>

					<div class="ln_solid"></div>
				</div>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 배송비 설정 <small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_type">반품/교환 택배사</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="de_delivery_company" id="de_delivery_company">
								<?php echo get_delivery_company($default['de_delivery_company']); ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_term_start">반품배송비(편도)</span>
						</label>
						<div class="col-md-2 col-sm-2 col-xs-10">
							<input type="text" name="de_return_costs" value="<?php echo $default['de_return_costs']; ?>" id="de_return_costs" class="form-control">
						</div>
						<label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
					</div>

					<!-- <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_send_term_start">교환배송비(왕복)</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-10">
                <input type="text" name="de_roundtrip_costs"  value="<?php echo $default['de_roundtrip_costs']; ?>" id="de_roundtrip_costs" class="form-control" >
            </div>
            <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
		  </div> -->

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_return_zip">반품 주소 설정</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="input-group col-sm-4 col-sm-4">
								<input type="text" name="de_return_zip" value="<?php echo $default['de_return_zip']; ?>" id="de_return_zip" class="form-control col-md-6 col-xs-6" size="5" maxlength="6">
								<span class="input-group-btn">
									&nbsp;<button type="button" class="btn btn-primary" onclick="win_zip('fconfigform', 'de_return_zip', 'de_return_address1', 'de_return_address2', 'de_return_address3', 'de_return_address_jibeon');">주소검색</button>
								</span>
							</div>

							<div class="input-group col-sm-9 col-sm-9">
								<input type="text" name="de_return_address1" value="<?php echo $default['de_return_address1']; ?>" id="de_return_address1" class="form-control col-md-12 col-xs-12" size="30">
							</div>

							<div class="input-group col-sm-9 col-sm-9">
								<input type="text" name="de_return_address2" value="<?php echo $default['de_return_address2']; ?>" id="de_return_address2" class="form-control col-md-12 col-xs-12" size="30">
							</div>
							<input type="hidden" name="de_return_address3" value="" id="de_return_address3">
							<input type="hidden" name="de_return_address_jibeon" value="" id="de_return_address_jibeon">

						</div>
					</div>

					<div class="ln_solid"></div>
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
	$(function() {


	});

	function fconfigform_submit(f) {
		f.action = "./configform_delivery_update.php";
		return true;
	}
</script>

<?php
include_once('./admin.tail.php');
?>