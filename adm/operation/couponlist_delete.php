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

    $sql = " delete from {$g5['g5_shop_coupon_table']} where cp_id = '{$_POST['cp_id'][$k]}' ";
    sql_query($sql);
}

alert('발급된 쿠폰이 삭제되었습니다.','./configform_coupon_issuance_history.php?'.$qstr.'&cm_no='.$cm_no,true);
?>
