<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '결제방식설정';
include_once ('./admin.head.php');

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
		<h4><span class="fa fa-check-square"></span> 결제방식 설정<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >결제금액 표시 설정</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control" name="de_price_view" >
                    <option value="[:PRICE:]원" selected="selected">화폐단위표기 (10,000원)</option>
                    <!-- option value="₩[:PRICE:]">화폐부호표기 (₩10,000)</option>
                    <option value="KRW [:PRICE:]">통화단위표기 (KRW 10,000) </option>
                    <option value="₩[:PRICE:]원">화폐부호+화폐단위표기 (₩10,000원)</option>
                    <option value="KRW [:PRICE:]원">통화단위+화폐단위표기 (KRW 10,000원)</option -->
                  </select>
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >PG 에스크로 </span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="radio">
					<label for="de_escrow_use1">
    				<input type="radio" class="flat" name="de_escrow_use" value="0" <?php echo $default['de_escrow_use']==0?"checked":""; ?> id="de_escrow_use1" required>
                    사용안함</label>
                    
                    <label for="de_escrow_use2">
                    <input type="radio" class="flat" name="de_escrow_use" value="1" <?php echo $default['de_escrow_use']==1?"checked":""; ?> id="de_escrow_use2" required>
                     사용함</label>
                     <label for="de_iche_use">
                     (<input type="checkbox" class="flat" name="de_iche_use" value="1" <?php echo $default['de_iche_use']==1?"checked":""; ?> id="de_iche_use">실시간계좌이체 에스크로)
                     </label>
                </div>
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >카드결제 대행사</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;">
			 <a href="http://pgweb.uplus.co.kr" target="_blank"><input type="button" class="btn btn_01" value="LG 유플러스 상점관리자"></input></a>
			 <br/><br/>
			 1. 상점ID: lifelike1, 유형: 일반<br/>
			 2. 상점ID: lifelike, 유형: 정기결제<br/>
			 3. 상점ID: lifelike2, 유형: 세탁, 보관, 수선<br/>
			 4. 상점ID: kvplifelike, 유형: 상담원 결제			 
			</div>
		  </div>

		  <div class="ln_solid"></div>
	  </div>

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 적립금 정보 <small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >적립금 사용가능 기간</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($config['cf_point_term']); ?>일</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >회원가입 적립금</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($config['cf_register_point']); ?>원</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >최소 사용가능 적립금</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($default['de_settle_min_point']); ?>원</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >회원 1회 최대 적립금</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($config['cf_max_point_oentime']); ?>원</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >추천하는 사람</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($config['cf_recommender_point']); ?>원</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >추천받은 사람</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($config['cf_recommend_point']); ?>원</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" >적립금 사용 한도</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:8px;"><?php echo number_format($default['de_settle_max_point']); ?>원</div>
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
    f.action = "./configform_payment_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
