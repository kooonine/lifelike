<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

if(empty($_POST))
    die(json_encode(array('error' => '올바른 방법으로 이용해 주십시오.')));

    
if(isset($_POST['cancel_type']) && $_POST['cancel_type']) $cancel_type = $_POST['cancel_type'];
else $cancel_type = "cancel";

    
if(isset($_POST['od_id']) && $_POST['od_id']) {
    $od_id = $_POST['od_id'];
    $ct_id_arr = $_POST['ct_id_arr'];
    $total_return_cost = 0;
    if($cancel_type == "return") {
        $total_return_cost = get_return_sendcost($od_id, $ct_id_arr);
        $total_send_cost = get_cancel_sendcost($od_id, $ct_id_arr);
    }
    else {
        $total_send_cost = get_cancel_sendcost($od_id, $ct_id_arr);
    }

    die(json_encode(array('od_id' => $od_id, 'ct_id_arr'=>$ct_id_arr,'total_return_cost'=>$total_return_cost, 'total_send_cost'=>$total_send_cost, 'error' => '')));    
} else {
    die(json_encode(array('error' => '올바른 방법으로 이용해 주십시오.')));
}

die(json_encode(array('error' => '')));
?>