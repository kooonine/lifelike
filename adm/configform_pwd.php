<?php
$sub_menu = "10";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '비밀번호 변경';
include_once ('./admin.head.php');


get_admin_token();
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="" id="token">


	  <div class="x_content">
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">비밀번호</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" name="pwd1"  id="pwd1" class="form-control col-md-7 col-xs-12" size="30" required="required" maxlength="16">
			</div>
		  </div>
		  <div class="ln_solid"></div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">새 비밀번호</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" name="pwd2"  id="pwd2" class="form-control col-md-7 col-xs-12" size="30" required="required" maxlength="16">
                / 암호 보안수준  : <label id="lblPwd" class="red">낮음</label>
			</div>
		  </div>

		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">새 비밀번호 확인</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" name="pwd3"  id="pwd3" class="form-control col-md-7 col-xs-12" size="30" required="required" maxlength="16">
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
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);

    $("#pwd2").keyup(function(){

        var pw = $("#pwd2").val();

    	var num = pw.search(/[0-9]/g);
    	var eng = pw.search(/[a-z]/ig);
    	var engUpper = pw.search(/[A-Z]/ig);
    	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

    	if(num >= 0 && eng >= 0 && engUpper >= 0 && spe >= 0 && pw.length >= 12)
		{
    		$("#lblPwd").text("높음");
		} 
		else if(num >= 0 && eng >= 0 && spe >= 0 && pw.length >= 8 )
		{
    		$("#lblPwd").text("보통");
		} 
		else
		{
    		$("#lblPwd").text("낮음");
		}

    });
});

function fconfigform_submit(f)
{
	if( $("#pwd1").val().trim() == $("#pwd2").val().trim() ) {
		alert('새 비밀번호가 동일합니다.');
		return false;
	}
	
    var pw = $("#pwd2").val();

	var num = pw.search(/[0-9]/g);
	var eng = pw.search(/[a-z]/ig);
	var engUpper = pw.search(/[A-Z]/ig);
	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);
	
	if(!(num >= 0 && eng >= 0 && spe >= 0 && pw.length >= 8 ))
	{
		alert('비밀번호를 영문,숫자,특수문자 포함 8~16자로 입력해주세요.');
		return false;
	}

    if( pw.length < 8 ) {
		alert('비밀번호를 8~16자로 입력해주세요.');
		return false;
    }
    
	if( $("#pwd2").val().trim() != $("#pwd3").val().trim() ) {
		alert('새 비밀번호가 일치하지 않습니다.');
		return false;
	}
	
    f.action = "./configform_pwd_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
