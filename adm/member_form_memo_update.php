<?php
$sub_menu = "200100";
include_once("./_common.php");

if ($w == 'u')
    check_demo();

auth_check($auth[substr($sub_menu,0,2)], 'w');

check_admin_token();

$mb_id = trim($_POST['mb_id']);

$is_important           = isset($_POST['is_important'])             ? sql_real_escape_string(trim($_POST['is_important']))           : "0";

$sql_common = "  mm_memo = '{$_POST['mm_memo']}',
                 is_important = '{$is_important}',
                 mm_mb_id = '{$member['mb_id']}',
                 mm_mb_name = '{$member['mb_name']}',
                 mm_time = '".G5_TIME_YMDHIS."' ";

if ($w == '')
{
sql_query(" insert into lt_member_memo set mb_id = '$mb_id', {$sql_common} ");
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');
    
    $sql = " update lt_member_memo
             set     {$sql_common}
             where mm_no = '{$mm_no}' ";
    
    sql_query($sql);
}
else if ($w == 'd')
{
    $sql = " delete from lt_member_memo where mm_no = '{$mm_no}' ";
    sql_query($sql);
}

goto_url('./member_form.php?'.$qstr.'&amp;w=&amp;mb_id='.$mb_id.'&amp;mode='.$mode, false);

?>