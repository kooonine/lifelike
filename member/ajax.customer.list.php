<?php
include_once('./_common.php');

// / 삭제 수정 만들어야하는데 !!!!!!!!!!!!!!!!!

//$iq_id = $_REQUEST['iq_id'];
$qa_id = $_GET['qa_id'];
$w = $_GET['w'];

if($w == 'u'){
    $qa = sql_fetch(" select q.*, (select it_name from lt_shop_item where it_id = q.it_id) AS it_name from {$g5['g5_shop_item_qa_table']} AS q where iq_id = '$iq_id' ");
    if (!$qa) {
        alert_close("상품문의 정보가 없습니다.");
    }
    $return = $qa;
}else if ($w =='d'){
    
    $sql2 = " delete from lt_qa_content where qa_parent = '$qa_id' ";
    $sql_d2 = sql_query($sql2);

    $alert_msg2 = '상품문의가 삭제 되었습니다.';

    //goto_url('/member/qna.php');
    $return2 = $sql_d2;
    
}


return_json($qa_id);
