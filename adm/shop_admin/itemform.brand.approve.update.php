<?php
$sub_menu = '92';
include_once('./_common.php');
auth_check($auth[substr($sub_menu,0,2)], "w");

$it_id = trim($_POST['it_id']);
$w = $_POST['w'];

if ($w == 'approve')
{
    $sql = " update {$g5['g5_shop_item_table']}
                set  it_status = '승인'
                    , it_approve_mb_id = '{$member['mb_id']}'
                    , it_approve_mb_name = '{$member['mb_name']}'
                    , it_approve_date = '".G5_TIME_YMDHIS."'
                where it_id = '{$it_id}' ";
    
    sql_query($sql);
    alert('승인되었습니다.');
}
else if ($w == 'return')
{
    //반려
    $sql = " update {$g5['g5_shop_item_table']}
                set  it_status = '반려'
                    , it_approve_mb_id = '{$member['mb_id']}'
                    , it_approve_mb_name = '{$member['mb_name']}'
                    , it_approve_date = '".G5_TIME_YMDHIS."'
                    , it_reason = '{$_POST['it_reason']}'
                where it_id = '{$it_id}' ";
    
    sql_query($sql);
    alert('반려되었습니다.');
}
else if ($w == 'cancel')
{
    //수정철회
    //반려
    $sql = " update {$g5['g5_shop_item_table']}
                set  it_status = '승인'
                where it_id = '{$it_id}' ";
    
    sql_query($sql);
    alert('수정 철회되었습니다.');
    
    //롤백
}
?>