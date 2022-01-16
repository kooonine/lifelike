<?php
//$sub_menu = "100610";
$sub_menu = "10";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = "관리자 계정관리";
include_once('./admin.head.php');

if ($w == '') {

    $row['ad_type'] = 'admin';

} else if ($w == 'u') {

    $sql = " select *
                from    lt_admin a left join {$g5['member_table']} b on (a.mb_id=b.mb_id)
            where a.mb_id = '{$mb_id}' ";
    $row = sql_fetch($sql);
}

$token = get_admin_token();
?>
<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">

<form name="fwrite" id="fwrite" action="./admin_form_update.php" onsubmit="return fwrite_submit(this)" method="post">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<input type="hidden" name="sc_ad_del" value="<?php echo $sc_ad_del; ?>">
<input type="hidden" name="sc_ad_type" value="<?php echo $sc_ad_type; ?>">
<input type="hidden" name="sc_ad_reg_datetime" value="<?php echo $sc_ad_reg_datetime; ?>">

<input type="hidden" name="token" value="<?php echo $token; ?>">

    <div class="x_title">
      <h4><span class="fa fa-check-square"></span> 관리자 기본 정보<small></small></h2>
      <label class="nav navbar-right"></label>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <colgroup>
            <col class="grid_4">
            <col>
            <col>
            <col>
            </colgroup>
          <tbody>

          <tr>
          	<th scope="row"><label>회사</label></th>
          	<td>
          		<input type="text" name="mb_company" value="<?php echo get_text($row['mb_company']) ?>" id="mb_company" class="form-control" maxlength="50">
          	</td>
          	<th scope="row"><label>부서</label></th>
          	<td>
          		<input type="text" name="mb_dept" value="<?php echo get_text($row['mb_dept']) ?>" id="mb_dept" class="form-control" maxlength="50">
          	</td>
          </tr>

          <tr>
          	<th scope="row"><label>성명</label></th>
          	<td>
          		<input type="text" name="mb_name" value="<?php echo get_text($row['mb_name']) ?>" id="mb_name" class="form-control required" maxlength="20" required="required">
          	</td>
          	<th scope="row"><label>직위</label></th>
          	<td>
          		<input type="text" name="mb_title" value="<?php echo get_text($row['mb_title']) ?>" id="mb_title" class="form-control" maxlength="50">
          	</td>
          </tr>

          <tr>
          	<th scope="row"><label>아이디</label></th>
          	<td>
          		<input type="text" name="mb_id" value="<?php echo $row['mb_id'] ?>" id="reg_mb_id" class="form-control required" required="required" maxlength="20" <?php if ($w == 'u') echo 'readonly'; ?> >
          	</td>
          	<th scope="row"><label>이메일주소</label></th>
          	<td>
          		<input type="text" name="mb_email" value="<?php echo get_text($row['mb_email']) ?>" id="reg_mb_email" class="form-control required email" maxlength="100" required="required">
          	</td>
          </tr>

          <tr>
          	<th scope="row"><label>연락처</label></th>
          	<td>
          		<input type="text" name="mb_hp" value="<?php echo get_text($row['mb_hp']) ?>" id="reg_mb_hp" class="form-control required" maxlength="20" required="required">
          	</td>
          	<th scope="row"><label>관리자구분</label></th>
          	<td>
          		<div class="radio">
                    <label><input type="radio" value="admin" id="ad_type2" name="ad_type" <?php echo ($row['ad_type'] == 'admin')?'checked':''; ?>> 일반관리자</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="super" id="ad_type1" name="ad_type" <?php echo ($row['ad_type'] == 'super')?'checked':''; ?>> 슈퍼관리자</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="brand" id="ad_type3" name="ad_type" <?php echo ($row['ad_type'] == 'brand')?'checked':''; ?>> 입점몰관리자</label>&nbsp;&nbsp;&nbsp;
                </div>
          	</td>
          </tr>

          </tbody>
          </table>
		</div>
	</div>

	<div id="dvAuth" <?php echo ($row['ad_type'] == 'super')?'hidden':''; ?>>

    <div class="x_title">
      <h4><span class="fa fa-check-square"></span> 관리자 권한 정보<small></small></h2>
      <label class="nav navbar-right"></label>
      <div class="clearfix"></div>
    </div>

    <div class="x_content" >
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <colgroup>
            <col class="grid_2">
            <col>
            <col>
            <col>
            <col>
            <col>
            </colgroup>
          <tbody>
          <tr>
          	<th rowspan="20">권한<br/>설정</th>
          	<th style="text-align: center">메뉴</th>
          	<th style="text-align: center">
                <label><input type="checkbox" name="chkAllHead" value="1" id="chkAllHead" onclick="check_all_f('chkAllHead', 'chkAll[]')"> 전체권한</label>
			</th>
          	<th style="text-align: center">
                <label><input type="checkbox" name="chkrHead" value="1" id="chkrHead" onclick="check_all_f('chkrHead', 'chkr[]')"> 접근/조회</label></th>
          	<th style="text-align: center">
                <label><input type="checkbox" name="chkwHead" value="1" id="chkwHead" onclick="check_all_f('chkwHead', 'chkw[]')"> 등록/수정</label></th>
          	<th style="text-align: center">
                <label><input type="checkbox" name="chkdHead" value="1" id="chkdHead" onclick="check_all_f('chkdHead', 'chkd[]')"> 삭제</label></th>
          </tr>
          <?php
              $sql = " select a.me_code, a.me_name, ifnull(b.au_auth,'') au_auth
                        from lt_admin_menu as a
                             left outer join {$g5['auth_table']} as b
                               on a.me_code = b.au_menu  and b.mb_id = '{$row['mb_id']}' ";


              $result = sql_query($sql);
              for ($i=0; $row=sql_fetch_array($result); $i++) {
                  $bg = 'bg'.($i%2);
                  $all = $r = $w = $d = false;
                  if($row['au_auth']) {
                      $au_auth = explode(",", $row['au_auth']);
                      for ($k = 0; $k < count($au_auth); $k++) {
                          if($au_auth[$k] == "r") $r = true;
                          if($au_auth[$k] == "w") $w = true;
						  if($au_auth[$k] == "d") $d = true;
					   }
				   if(($row['au_auth'] == "r,w,d")){
				     $all = true;
				    }
                  }

          ?>
		  <tr class="<?php echo $bg; ?>">
          	<td><?php echo $row['me_name'] ?></td>
          	<td class="td_chk text-center" style="text-align: center">
                <input type="checkbox" name="chkAll[]" value="<?php echo $row['me_code'] ?>" seq="<?php echo $i ?>" id="chkAll_<?php echo $i ?>" <?php echo ($all) ?'checked':''; ?>>
                <input type="hidden" name="me_code[]" value="<?php echo $row['me_code'] ?>">
            </td>
          	<td style="text-align: center">
                <input type="checkbox" name="chkr[]" value="<?php echo $row['me_code'] ?>" id="chkr_<?php echo $i ?>" <?php echo ($r)?'checked':''; ?> >
			</td>
          	<td style="text-align: center">
                <input type="checkbox" name="chkw[]" value="<?php echo $row['me_code'] ?>" id="chkw_<?php echo $i ?>" <?php echo ($w)?'checked':''; ?>>
            </td>
          	<td style="text-align: center">
                <input type="checkbox" name="chkd[]" value="<?php echo $row['me_code'] ?>" id="chkd_<?php echo $i ?>" <?php echo ($d)?'checked':''; ?>>
            </td>
          </tr>
          <?php } ?>

          </tbody>
          </table>
		</div>
	</div>

	</div>

    <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
        	<a href="./admin_list.php?<?php echo $qstr?>" class="btn btn_02">목록</a>
        	<button type="submit" class="btn btn-success">저장</button>
        	<br/><br/>
        </div>
    </div>


</form>

</div>
</div>
</div>

<script>
function check_all_f(chkName, targetChkName)
{
	var $targetChk = $("input[name='"+targetChkName+"']");
    var chk = $("#"+chkName).is(":checked");

    for (i=0; i<$targetChk.size(); i++)
    {
		$($targetChk[i]).prop("checked", chk);
    }

    if(chkName == "chkAllHead")
    {
    	$("#chkrHead").prop("checked", chk);
    	$("#chkwHead").prop("checked", chk);
    	$("#chkdHead").prop("checked", chk);

    	$("#chkrHead").prop("disabled", chk);
    	$("#chkwHead").prop("disabled", chk);
    	$("#chkdHead").prop("disabled", chk);

 	    var $targetChk1 = $("input[name='chkr[]']");
 	    var $targetChk2 = $("input[name='chkw[]']");
 	    var $targetChk3 = $("input[name='chkd[]']");

 	    for (i=0; i<$targetChk1.size(); i++)
 	    {
 	 	   $($targetChk1[i]).prop("checked", chk);
 	 	   $($targetChk2[i]).prop("checked", chk);
 	 	   $($targetChk3[i]).prop("checked", chk);

 	 	   $($targetChk1[i]).prop("disabled", chk);
 	 	   $($targetChk2[i]).prop("disabled", chk);
 	 	   $($targetChk3[i]).prop("disabled", chk);
 	    }
    }
}

$(function() {


    $('#sc_ad_reg_datetime').daterangepicker({
    	"autoApply": true,
    	"opens": "right",
    	locale: {
            "format": "YYYY-MM-DD",
            "separator": " ~ ",
            "applyLabel": "선택",
            "cancelLabel": "취소",
            "fromLabel": "시작일자",
            "toLabel": "종료일자",
            "customRangeLabel": "직접선택",
            "weekLabel": "W",
            "daysOfWeek": ["일","월","화","수","목","금","토"],
            "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
            "firstDay": 1
        }
    });
    $('#sc_ad_reg_datetime').val("<?php echo $sc_ad_reg_datetime ?>");

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){

		var d = $(this).attr("data");
		if(d == "all") {
			$('#sc_ad_reg_datetime').val("");
		} else {
    		var startD = moment();
    		var endD = moment();

    		if(d == "3d") {
    			startD = moment().subtract(2, 'days');
    			endD = moment();

    		} else if(d == "1w") {
    			startD = moment().subtract(6, 'days');
    			endD = moment();

    		} else if(d == "1m") {
    			startD = moment().subtract(1, 'month');
    			endD = moment();

    		} else if(d == "3m") {
    			startD = moment().subtract(3, 'month');
    			endD = moment();
    		}

    		$('#sc_ad_reg_datetime').data('daterangepicker').setStartDate(startD);
    		$('#sc_ad_reg_datetime').data('daterangepicker').setEndDate(endD);
		}

	});

	$("input[name='chkAll[]']").click(function(){
		var i = $(this).attr("seq");
		var chk = $(this).is(":checked");
 	    var $targetChk1 = $("#chkr_"+i);
 	    var $targetChk2 = $("#chkw_"+i);
 	    var $targetChk3 = $("#chkd_"+i);

 	   $targetChk1.prop("checked", chk);
 	   $targetChk2.prop("checked", chk);
 	   $targetChk3.prop("checked", chk);

 	   $targetChk1.prop("disabled", chk);
 	   $targetChk2.prop("disabled", chk);
 	   $targetChk3.prop("disabled", chk);
	});

	$("input[name='ad_type']").change(function(){

		if($(this).val() == "super")
		{
			$("#dvAuth").prop("hidden", true);
		} else {
			$("#dvAuth").prop("hidden", false);
		}

 	    var targetChk = document.getElementsByName("chkAll[]");
	    for (i=0; i<targetChk.length; i++)
	    {
	    	targetChk[i].checked = false;
	    }

	});

});

function fwrite_submit(f)
{
	// 회원아이디 검사
    if (f.w.value == "") {
		var objPattern = /^[a-zA-Z0-9]{1}[a-zA-Z0-9_]+$/;
		if(!objPattern.test($("#reg_mb_id").val()))
		{
			alert("아이디는 영문, 숫자, _ 만 사용가능합니다.");
			$("#reg_mb_id").focus();
			$("#reg_mb_id").select();
			return false;
		}
    }

    // 이름 검사
    if (f.mb_name.value.length < 1) {
        alert('이름을 입력하십시오.');
        f.mb_name.focus();
        return false;
    }

	var objPattern = /^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
	if(!objPattern.test($("#reg_mb_email").val()))
	{
		alert("이메일주소 형식이 아닙니다.");
		$("#reg_mb_email").focus();
		return false;
	}
	/*
    // E-mail 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
        var msg = reg_mb_email_check();
        if (msg) {
            alert(msg);
            f.reg_mb_email.select();
            return false;
        }
    }

    // 휴대전화번호 체크
    var msg = reg_mb_hp_check();
    if (msg) {
        alert(msg);
        f.reg_mb_hp.select();
        return false;
    }
    */

    return confirm("저장하시겠습니까?");
}
</script>

<?php
include_once ('./admin.tail.php');
?>
