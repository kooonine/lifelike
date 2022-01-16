<?php
include_once('./_common.php');

if (!$member['mb_id'])
    alert('회원만 접근하실 수 있습니다.');

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

//if (!($_POST['mb_password'] && check_password($_POST['mb_password'], $member['mb_password'])))
//    alert('비밀번호가 틀립니다.');

// 회원탈퇴일을 저장
$mb_3 = $_POST["mb_3"];
$mb_4 = $_POST["mb_4"];
$mb_5 = $_POST["mb_5"];
$sql = " update {$g5['member_table']} set mb_leave_date = '".G5_TIME_YMD."', mb_3 = '{$mb_3}', mb_4 = '{$mb_4}', mb_5 = '{$mb_5}' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

$arr_change_data = array();
msg_autosend('회원', '회원 탈퇴', $member['mb_id'], $arr_change_data);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

if (!$url)
    $url = G5_URL;

//소셜로그인 해제
if(function_exists('social_member_link_delete')){
    social_member_link_delete($member['mb_id']);
}

alert(''.$member['mb_nick'].'님께서는 '. G5_TIME_YMD .'에 회원에서 탈퇴 하셨습니다.', $url);
?>
