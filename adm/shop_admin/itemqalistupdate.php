<?php
$sub_menu = '400660';
include_once('./_common.php');

check_demo();

check_admin_token();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[substr($sub_menu,0,2)], 'd');

    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $sql = "delete from {$g5['g5_shop_item_qa_table']} where iq_id = '{$_POST['iq_id'][$k]}' ";
        sql_query($sql);
    }
}

goto_url("./itemqalist.php?sca=$sca&amp;sst=$sst&amp;sod=$sod&amp;sfl=$sfl&amp;stx=$stx&amp;page=$page");
?>