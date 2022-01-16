<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');

if(empty($_POST))
    die(json_encode(array('error' => '올바른 방법으로 이용해 주십시오.')));

if (get_session('ss_direct')) {
    $tmp_cart_id = get_session('ss_cart_direct');
} else {
    $tmp_cart_id = get_session('ss_cart_id');
}

if (get_cart_count($tmp_cart_id) == 0) {// 장바구니에 담기
    die(json_encode(array('error' => '장바구니가 비어 있습니다.'.$tmp_cart_id)));
}
if(isset($_POST['ct_id']) && $_POST['ct_id']) {
    $ct_id = $_POST['ct_id'];
    
    if($act == "del")
    {
        sql_query(" delete from {$g5['g5_shop_cart_table']} where ct_id = '".$ct_id."' ");
        
        
    } else if($act == "mod") {
        if ($_POST['ct_json']) {
            // die(json_encode(array('error' => $_POST['ct_json'])));
           $ctJson = $_POST['ct_json'];
           foreach ($ctJson as $key => $value) { 
                if ($value < 1) {
                    die(json_encode(array('error' => '수량은 1 이상 입력해 주십시오.')));
                }

                $sqlJson = " update {$g5['g5_shop_cart_table']}
                set ct_qty = '{$value}'
                where ct_id = '{$key}' ";
                sql_query($sqlJson);
           }
        } else {
            if ($_POST['ct_qty'] < 1) {
                die(json_encode(array('error' => '수량은 1 이상 입력해 주십시오.')));
            }
            
            $sql = " update {$g5['g5_shop_cart_table']}
                                set ct_qty = '{$_POST['ct_qty']}'
                                where ct_id = '{$ct_id}' ";
            sql_query($sql);


        }
    }
} else {
    die(json_encode(array('error' => '올바른 방법으로 이용해 주십시오.')));
}

die(json_encode(array('error' => '')));
?>