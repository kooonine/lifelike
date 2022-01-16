<?php
$sub_menu = "92";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$g5['title'] = '배송/반품 설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql = "select * from lt_member_company where mb_id = '{$member['mb_id']}' ";
$cp = sql_fetch($sql);

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$token = get_admin_token(); 
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="<?php echo $token?>" id="token">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 기본설정<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
	  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_type">배송방법</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select name="cp_send_type" id="cp_send_type" >				
                    <option value="택배" <?php echo get_selected($cp['cp_send_type'], '택배') ; ?>>택배</option>
                    <option value="빠른등기" <?php echo get_selected($cp['cp_send_type'], '빠른등기') ; ?>>빠른등기</option>
                    <option value="기타" <?php echo get_selected($cp['cp_send_type'], '기타') ; ?>>기타</option>
                </select>
           	</div>
		  </div>
	  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_term_start">배송기간</span>
			</label>
			<div class="col-md-1 col-sm-1 col-xs-1">
                <input type="text" name="cp_send_term_start"  value="<?php echo $cp['cp_send_term_start']; ?>" id="cp_send_term_start" class="form-control" >
            </div>
            <label class="col-md-1 col-sm-1 col-xs-1" style="padding-top:8px;">일 ~</label>
			<div class="col-md-1 col-sm-1 col-xs-1">
                <input type="text" name="cp_send_term_end"  value="<?php echo $cp['cp_send_term_end']; ?>" id="cp_send_term_end" class="form-control" >
            </div>
            <label class="control-label">일 정도 소요됩니다.</label>
		  </div>
		  
		  <div class="form-group" id="dvCostCase1">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_cost_limit">기본 배송비 설정</span>
			</label>
			<div class="col-md-1 col-sm-1 col-xs-1">
				<input type="hidden" name="cp_send_cost_case" id="cp_send_cost_case" value="고정" />
                <input type="text" name="cp_send_cost_limit"  value="<?php echo $cp['cp_send_cost_limit']; ?>" id="cp_send_cost_limit" class="form-control" >
            </div>
            <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원 미만일 때 배송비</label>
			<div class="col-md-1 col-sm-1 col-xs-1">
                <input type="text" name="cp_send_cost_list"  value="<?php echo $cp['cp_send_cost_list']; ?>" id="cp_send_cost_list" class="form-control" >
            </div>
            <label class="control-label">원을 부과합니다.</label>
		  </div>
	  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_condition">배송료 청구기준<br/>주문금액 조건설정</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="radio">
					<label ><input type="radio" class="flat" name="cp_send_condition" id="cp_send_condition0" value="판매"  <?php echo option_array_checked('판매', $cp['cp_send_condition']); ?> /> 할인전, 정상판매가격 기준(권장)</label>
                    <label ><input type="radio" class="flat" name="cp_send_condition" id="cp_send_condition1" value="최종" <?php echo option_array_checked('최종', $cp['cp_send_condition']); ?>/> 최종 주문(결제)금액 기준</label>
				</div>
			</div>
		  </div>
	  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_prepayment">배송비 선결제 설정</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">                
				<div class="radio">
                    <label><input type="radio" class="flat" name="cp_send_prepayment" id="cp_send_prepayment1" value="선결제" checked="checked" /> 선결제</label>
					<!-- <label><input type="radio" class="flat" name="cp_send_prepayment" id="cp_send_prepayment0" value="착불"  <?php echo option_array_checked('착불', $cp['cp_send_prepayment']); ?> /> 착불</label>
                    <label><input type="radio" class="flat" name="cp_send_prepayment" id="cp_send_prepayment2" value="착불/선결제" <?php echo option_array_checked('착불/선결제', $cp['cp_send_prepayment']); ?>/> 착불/선결제</label> -->
				</div>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_individual_costs_use">상품별 개별배송비 설정</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">                
				<div class="radio">
					<!-- label><input type="radio" class="flat" name="cp_individual_costs_use" id="cp_individual_costs_use0" value="0"  <?php echo option_array_checked('0', $cp['cp_individual_costs_use']); ?> /> 사용안함</label -->
                    <label><input type="radio" class="flat" name="cp_individual_costs_use" id="cp_individual_costs_use1" value="1" checked="checked"/> 사용함</label>
				</div>
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_type">반품/교환 택배사</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <select name="cp_delivery_company" id="cp_delivery_company">
                    <?php echo get_delivery_company($cp['cp_delivery_company']); ?>
                </select>
           	</div>
		  </div>
	  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_term_start">반품배송비(편도)</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-10">
                <input type="text" name="cp_return_costs"  value="<?php echo $cp['cp_return_costs']; ?>" id="cp_return_costs" class="form-control" >
            </div>
            <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
		  </div>
	  
		  <!-- <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_send_term_start">교환배송비(왕복)</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-10">
                <input type="text" name="cp_roundtrip_costs"  value="<?php echo $cp['cp_roundtrip_costs']; ?>" id="cp_roundtrip_costs" class="form-control" >
            </div>
            <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
		  </div> -->
		  
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cp_return_zip">반품 주소 설정</span>
			</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="input-group col-sm-4 col-sm-4">
					<input type="text" name="cp_return_zip" value="<?php echo $cp['cp_return_zip']; ?>" id="cp_return_zip" class="form-control col-md-6 col-xs-6"  size="5" maxlength="6">
					<span class="input-group-btn">
						&nbsp;<button type="button" class="btn btn-primary" onclick="win_zip('fconfigform', 'cp_return_zip', 'cp_return_address1', 'cp_return_address2', 'cp_return_address3', 'cp_return_address_jibeon');">주소검색</button>
					</span>
				</div>

				<div class="input-group col-sm-9 col-sm-9">
				<input type="text" name="cp_return_address1" value="<?php echo $cp['cp_return_address1']; ?>" id="cp_return_address1" class="form-control col-md-12 col-xs-12" size="30">
				</div>
				
				<div class="input-group col-sm-9 col-sm-9">
				<input type="text" name="cp_return_address2" value="<?php echo $cp['cp_return_address2']; ?>" id="cp_return_address2" class="form-control col-md-12 col-xs-12" size="30">
				</div>
				<input type="hidden" name="cp_return_address3" value="" id="cp_return_address3">
				<input type="hidden" name="cp_return_address_jibeon" value="" id="cp_return_address_jibeon">

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
$(function(){
    

});

function fconfigform_submit(f)
{
    f.action = "./company_delivery_update.php";
    return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
