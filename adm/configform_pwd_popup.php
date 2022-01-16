<?php
include_once('./_common.php');

$g5['title'] = '관리자 정보 변경 팝업';
include_once ('./admin.head.sub.php');

$sql = " select *
                from    lt_admin a left join {$g5['member_table']} b on (a.mb_id=b.mb_id)
            where a.mb_id = '{$member['mb_id']}' ";
$row = sql_fetch($sql);


?>

<div class="container body">
<div class="main_container">
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	<div id="menu_frm" class="new_win">
        <h3><?php echo $g5['title']; ?></h3>
    </div>
    
    <div class="x_title">
      <h4><span class="fa fa-check-square"></span> 관리자 기본 정보<small></small></h4>
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
          	<th scope="row"><label>관리자구분</label></th>
          	<td colspan="3">
      			<?php 
      			if($row['ad_type'] == 'super') echo '슈퍼관리자';
      			elseif($row['ad_type'] == 'admin') echo '일반관리자';
      			elseif($row['ad_type'] == 'brand') echo '입점몰관리자';
      			?>
          	</td>
          </tr>
          
          <tr>
          	<th scope="row"><label>회사</label></th>
          	<td>
          		<?php echo get_text($row['mb_company']) ?>
          	</td>
          	<th scope="row"><label>부서</label></th>
          	<td>
          		<?php echo get_text($row['mb_dept']) ?>
          	</td>
          </tr>
          
          <tr>
          	<th scope="row"><label>성명</label></th>
          	<td>
          		<?php echo get_text($row['mb_name']) ?>
          	</td>
          	<th scope="row"><label>직위</label></th>
          	<td>
          		<?php echo get_text($row['mb_title']) ?>
          	</td>
          </tr>
          
          <tr>
          	<th scope="row"><label>아이디</label></th>
          	<td>
          		<?php echo $row['mb_id'] ?>
          	</td>
          	<th scope="row"><label>이메일주소</label></th>
          	<td>
          		<?php echo get_text($row['mb_email']) ?>
          	</td>
          </tr>
          </tbody>
          </table>
		</div>
	</div>
    
    <div class="x_title">
      <h4><span class="fa fa-check-square"></span> 비밀번호 변경<small></small></h4>
      <div class="clearfix"></div>
    </div>

	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="" id="token">
	
	<div class="x_content">
      <div class="tbl_frm01 tbl_wrap">
          <table>
          	<colgroup>
            <col class="grid_4">
            <col class="grid_4">
            <col>
			</colgroup>
          <tbody>
          
          <tr>
          	<th scope="row"><label>비밀번호</label></th>
          	<td>
                <input type="password" name="pwd1"  id="pwd1" class="form-control col-md-7 col-xs-12" size="30" required="required" maxlength="16">
          	</td>
          	<td></td>
          </tr>
          
          <tr>
          	<th scope="row"><label>새 비밀번호</label></th>
          	<td>
                <input type="password" name="pwd2"  id="pwd2" class="form-control col-md-7 col-xs-12" size="30" required="required" maxlength="16">
          	</td>
          	<td>
          		/ 암호 보안수준  : <label id="lblPwd" class="red">낮음</label>
          	</td>
          </tr>
          
          <tr>
          	<th scope="row"><label>새 비밀번호 확인</label></th>
          	<td>
                <input type="password" name="pwd3"  id="pwd3" class="form-control col-md-7 col-xs-12" size="30" required="required" maxlength="16">
          	</td>
          	<td></td>
          </tr>
		</tbody>
		</table>
	</div>
	</div>


	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
				<button type="button" class="btn btn-secondary" onclick="window.close();">취소</button>
				<input type="submit" class="btn btn-success" value="저장"></input>
			</div>
		  </div>
	  </div>

	</form>

	</div>
  </div>
</div>

<script>
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
</script>
<!-- } 회원정보 찾기 끝 -->

<?php
include_once(G5_PATH.'/tail.sub.php');
?>