<?php
include_once('./_common.php');

if (!$member['mb_id'])
    alert('회원만 접근하실 수 있습니다.');

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

$sql = "select * from lt_member_company where mb_id = '{$member['mb_id']}' ";
$cp = sql_fetch($sql);

// 회원탈퇴일을 저장
//$sql = " update {$g5['member_table']} set mb_leave_date = '".G5_TIME_YMD."' where mb_id = '{$member['mb_id']}' ";
//sql_query($sql);
$sql = " update {$g5['member_table']} set mb_leave_date = '".G5_TIME_YMD."', mb_5 = '입점몰 탈퇴신청' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);


$sql = " update lt_member_company
                set  cp_status = '탈퇴신청'
                    ,modify_date = '".G5_TIME_YMDHIS."'
                where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

if (!$url)
    $url = G5_URL;

//소셜로그인 해제
if(function_exists('social_member_link_delete')){
    social_member_link_delete($member['mb_id']);
}

alert(''.$cp['company_name'].'님께서는 '. G5_TIME_YMD .'에 판매자에서 탈퇴신청 하셨습니다.', $url);
?>
