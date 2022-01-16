<?php
include_once('./_common.php');


//$iq_id = $_REQUEST['iq_id'];
$iq_id = $_GET['iq_id'];
$w = $_GET['w'];

if($w == 'u'){
    $qa = sql_fetch(" select q.*, (select it_name from lt_shop_item where it_id = q.it_id) AS it_name from {$g5['g5_shop_item_qa_table']} AS q where iq_id = '$iq_id' ");
    if (!$qa) {
        alert_close("상품문의 정보가 없습니다.");
    }
    $return = $qa;
}else if ($w =='d'){
    
    $sql = " delete from {$g5['g5_shop_item_qa_table']} where iq_id = '$iq_id' ";
    $sql_d = sql_query($sql);

    $alert_msg = '상품문의가 삭제 되었습니다.';

    //goto_url('/member/qna.php');
    $return = $sql_d;
    
}


return_json($return);
