<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

// 대표전화번호 유효성 체크
if(!check_vaild_callback($_POST['de_admin_company_tel']))
    alert('대표전화번호를 올바르게 입력해 주세요.');

// 로그인을 바로 이 주소로 하는 경우 쇼핑몰설정값이 사라지는 현상을 방지
if (!$_POST['de_admin_company_owner']) goto_url("./configform.php");


$de_admin_accounting_name = !empty($_POST['de_admin_accounting_name']) ? implode('||', $_POST['de_admin_accounting_name']) : '';
$de_admin_accounting_tel = !empty($_POST['de_admin_accounting_tel']) ? implode('||', $_POST['de_admin_accounting_tel']) : '';
$de_admin_accounting_email = !empty($_POST['de_admin_accounting_email']) ? implode('||', $_POST['de_admin_accounting_email']) : '';
//
// 영카트 default
//
$sql = " update {$g5['g5_shop_default_table']}
            set 
				de_admin_company_saupja_no = '{$_POST['de_admin_company_saupja_no']}',
				de_admin_company_name = '{$_POST['de_admin_company_name']}',
				de_admin_company_owner = '{$_POST['de_admin_company_owner']}',
				de_admin_company_industry = '{$_POST['de_admin_company_industry']}',
				de_admin_company_item = '{$_POST['de_admin_company_item']}',
				de_admin_company_zip = '{$_POST['de_admin_company_zip']}',
				de_admin_company_addr = '{$_POST['de_admin_company_addr']}',
				de_admin_company_tel = '{$_POST['de_admin_company_tel']}',
				de_admin_company_email = '{$_POST['de_admin_company_email']}',
				de_admin_tongsin_no = '{$_POST['de_admin_tongsin_no']}',
				de_admin_buga_no = '{$_POST['de_admin_buga_no']}',
				de_admin_call_tel = '{$_POST['de_admin_call_tel']}',
				de_admin_call_email = '{$_POST['de_admin_call_email']}',
				de_admin_company_fax = '{$_POST['de_admin_company_fax']}',
				de_sms_hp = '{$_POST['de_sms_hp']}',
				de_admin_call_time = '{$_POST['de_admin_call_time']}',
				de_admin_info_name = '{$_POST['de_admin_info_name']}',
				de_admin_info_tel = '{$_POST['de_admin_info_tel']}',
				de_admin_info_email = '{$_POST['de_admin_info_email']}',

				de_admin_accounting_name = '{$de_admin_accounting_name}',
				de_admin_accounting_tel = '{$de_admin_accounting_tel}',
				de_admin_accounting_email = '{$de_admin_accounting_email}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_biz.php");
}
?>
