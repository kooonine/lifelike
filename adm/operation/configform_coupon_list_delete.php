<?php
$sub_menu = '200180';
include_once('./_common.php');

check_demo();

auth_check($auth[substr($sub_menu,0,2)], 'd');

check_admin_token();

$count = count($_POST['chk']);
if(!$count)
    alert('선택삭제 하실 항목을 하나이상 선택해 주세요.');

for ($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    $sql = " update lt_shop_coupon_mng set cm_status = '삭제' where cm_no = '{$_POST['cm_no'][$k]}' ";
    sql_query($sql);
}

goto_url('./configform_coupon_list.php?'.$qstr);
?>
