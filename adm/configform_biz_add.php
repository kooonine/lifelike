<?php
//$sub_menu = "100100";
$sub_menu = "10";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '사업자 정보';
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
		<h4><span class="fa fa-check-square"></span> 기본정보 설정<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">사업자 등록번호</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_company_saupja_no"  value="<?php echo $default['de_admin_company_saupja_no']; ?>" id="de_admin_company_saupja_no" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">상호(법인명)</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" name="de_admin_company_name" value="<?php echo $default['de_admin_company_name']; ?>" id="de_admin_company_name" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">대표자 성명</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_company_owner" value="<?php echo $default['de_admin_company_owner']; ?>" id="de_admin_company_owner" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">업태</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_company_industry" value="<?php echo $default['de_admin_company_industry']; ?>" id="de_admin_company_industry" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">종목</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_company_item" value="<?php echo $default['de_admin_company_item']; ?>" id="de_admin_company_item" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">사업장 주소</span>
			</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="input-group col-sm-4 col-sm-4">
					<input type="text" name="de_admin_company_zip" value="<?php echo $default['de_admin_company_zip']; ?>" id="de_admin_company_zip" class="form-control col-md-6 col-xs-6"  size="5" maxlength="6">
					<span class="input-group-btn">
						<button type="button" class="btn btn-primary" onclick="win_zip('fconfigform', 'de_admin_company_zip', 'de_admin_company_addr', 'de_admin_company_addr', 'de_admin_company_addr3', 'de_admin_company_jibeon');">주소검색</button>
					</span>
				</div>

				<div class="input-group col-sm-9 col-sm-9">
				<input type="text" name="de_admin_company_addr" value="<?php echo $default['de_admin_company_addr']; ?>" id="de_admin_company_addr" class="form-control col-md-12 col-xs-12" size="30">
				</div>
				<input type="hidden" name="de_admin_company_addr3" value="<?php echo $default['de_admin_company_addr3'] ?>" id="de_admin_company_addr3">
				<input type="hidden" name="de_admin_company_jibeon" value="<?php echo $default['de_admin_company_jibeon']; ?>" id="de_admin_company_jibeon">

			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">대표전화</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" name="de_admin_company_tel" value="<?php echo $default['de_admin_company_tel']; ?>" id="de_admin_company_tel" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">대표 이메일 주소</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_company_email" value="<?php echo $default['de_admin_company_email']; ?>" id="de_admin_company_email" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">통신판매업 정보</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<div class="radio">
					<label><input type="radio" class="" name="de_admin_tongsin_no_use" id="de_admin_tongsin_no_use1" value="1" <? if($default['de_admin_tongsin_no'] != "") { ?>checked="" <? } ?> required onclick="useChange('de_admin_tongsin_no_use','de_admin_tongsin_no');"/> 신고함</label>
                    <label><input type="radio" class="" name="de_admin_tongsin_no_use" id="de_admin_tongsin_no_use0" value="0" <? if($default['de_admin_tongsin_no'] == "") { ?>checked="" <? } ?>  onclick="useChange('de_admin_tongsin_no_use','de_admin_tongsin_no');"/> 신고안함</label>
				</div>
			</div>
			<label class="control-label col-md-2 col-sm-2 col-xs-12" for="de_admin_company_saupja_no">/ 통신판매업 번호</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="de_admin_tongsin_no" value="<?php echo $default['de_admin_tongsin_no']; ?>" <? if($default['de_admin_tongsin_no'] == "") { ?>readonly="readonly" <? } ?> id="de_admin_tongsin_no" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">부가통신사업자 정보</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<div class="radio">
					<label><input type="radio" class="" name="de_admin_buga_no_use" id="de_admin_buga_no_use1" value="1" <? if($default['de_admin_buga_no'] != "") { ?>checked="" <? } ?> required onclick="useChange('de_admin_buga_no_use','de_admin_buga_no');" /> 신고함</label>
                    <label><input type="radio" class="" name="de_admin_buga_no_use" id="de_admin_buga_no_use0" value="0" <? if($default['de_admin_buga_no'] == "") { ?>checked="" <? } ?> onclick="useChange('de_admin_buga_no_use','de_admin_buga_no');" /> 신고안함</label>
				</div>
			</div>
			<label class="control-label col-md-2 col-sm-2 col-xs-12" for="de_admin_company_saupja_no">/ 부가통신사업자 번호</span>
			</label>
			<div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="de_admin_buga_no" value="<?php echo $default['de_admin_buga_no']; ?>" <? if($default['de_admin_buga_no'] == "") { ?>readonly="readonly" <? } ?> id="de_admin_buga_no" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="ln_solid"></div>
	  </div>

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 고객센터정보 안내설정 <small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">상담/주문전화</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_call_tel" value="<?php echo $default['de_admin_call_tel']; ?>" id="de_admin_call_tel" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">상담/주문 이메일</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_call_email" value="<?php echo $default['de_admin_call_email']; ?>" id="de_admin_call_email" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_fax">팩스번호</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" name="de_admin_company_fax" value="<?php echo $default['de_admin_company_fax']; ?>" id="de_admin_company_fax" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">SMS 수신번호</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" name="de_sms_hp" value="<?php echo $default['de_sms_hp']; ?>" id="de_sms_hp" class="form-control col-md-7 col-xs-12" size="20">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">고객센터 운영시간</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_call_time" value="<?php echo $default['de_admin_call_time']; ?>" id="de_admin_call_time" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="ln_solid"></div>
	  </div>

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 개인정보 관리책임자 안내 설정 <small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">개인정보 관리 책임자</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_info_name" value="<?php echo $default['de_admin_info_name']; ?>" id="de_admin_info_name" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">책임자 연락처</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_info_tel" value="<?php echo $default['de_admin_info_tel']; ?>" id="de_admin_info_tel" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">책임자 이메일</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_info_email" value="<?php echo $default['de_admin_info_email']; ?>" id="de_admin_info_email" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>

		  <div class="ln_solid"></div>
	  </div>

	  <div class="x_title">
		<h4>
			<span class="fa fa-check-square"></span> 업무별 책임자 설정
			<div style="float: right;">
			  <input type="button" class="btn" id="accounting_add" value="책임자 추가">
			</div>
		</h4>
		
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">회계(정산) 담당자</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_accounting_name[]" value="<?php echo $default['de_admin_accounting_name']; ?>" id="de_admin_accounting_name" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">담당자 연락처</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_accounting_tel[]" value="<?php echo $default['de_admin_accounting_tel']; ?>" id="de_admin_accounting_tel" class="form-control col-md-7 col-xs-12" size="30">
			</div>
		  </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">담당자 이메일</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="de_admin_accounting_email[]" value="<?php echo $default['de_admin_accounting_email']; ?>" id="de_admin_accounting_email" class="form-control col-md-7 col-xs-12" size="30">
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

<script language="javascript">
$(function(){	


});

function useChange(rdoName, ctl)
{
	var rdoVal = $('input[name="' +rdoName+ '"]:checked').val();
	//alert(rdoVal);

	if(rdoVal == '1'){
		$('#'+ctl).removeAttr("readOnly");
	} else {
		$('#'+ctl).val("");
		$('#'+ctl).attr("readOnly","readOnly");
	}
}

function fconfigform_submit(f)
{
	
    f.action = "./configform_biz_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
