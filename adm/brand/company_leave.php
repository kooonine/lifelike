<?php
$sub_menu = "920101";
include_once('./_common.php');

$mb_id = $member['mb_id'];

$w = 'u';
    
$mb = get_member($mb_id);
if (!$mb['mb_id'])
    alert('존재하지 않는 회원자료입니다.');

$g5['title'] = '탈퇴신청';

include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql = "select * from lt_member_company where mb_id = '{$mb_id}' ";
$cp = sql_fetch($sql);

$token = get_admin_token();
?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
    
    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 판매자 정보<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
    </div>

	<div class="x_content">
	<div class="tbl_frm01 tbl_wrap">
    <table>
    <tr>
        <th scope="row">
	재가입 제한<br/>
    판매자 탈퇴를 하신 경우 동일 판매자 정보(사업자번호, 개인식별정보)로 30일간 다시 가입하실 수 없습니다.<br/>
    회사는 안정적인 서비스 운영 등을 위해 다음의 경우 재가입을 제한할 수 있습니다.<br/>
    - 약관 및 서비스 운영정책 위반으로 직권 해지된 이력이 있는 경우<br/>
    - 약관 및 서비스 운영정책 위반으로 서비스 이용 정지된 상태에서 탈퇴한 이력이 있는 경우<br/>
    - 약관 및 판매자 운영정책에 중대하게 어긋나는 행동을 한 후 자진하여 탈퇴한 이력이 있는 경우<br/>
    - 단, 부정거래 소명 또는 특수 사유로 인해 재가입을 허용할 경우 소정의 절차에 따라 재가입이 허용됩니다.<br/>
<br/>    
    탈퇴 조건<br/> 
    진행중인 거래, 판매상품의 클레임 등 배송, CS 에 대한 처리를 완료해주세요.<br/>
    쇼핑 광고주로 입점되어 있는 경우 쇼핑파트너센터에서 광고주 퇴점에 필요한 절차를 진행해주세요.<br/>
    </th>
    </tr>
    </table>
    </div>
	</div>
	  
    <div class="pull-right">
        <input type="button" value="탈퇴 신청" class="btn_submit btn" accesskey='s'>
    </div>

	</div>
  </div>
</div>

<script>
$(function(){
	$(".btn_submit").click(function(){
		if(confirm("탈퇴를 신청하시겠습니까?"))
		{
			location.href="./company_leave_act.php";
		}
	});

	
});

</script>

<?php

include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>