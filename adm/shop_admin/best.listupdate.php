<?php
//$sub_menu = '300201';
$sub_menu = '30';
include_once('./_common.php');



if (!count($_POST['bs_category'])) {
    alert($_POST['bs_category']." 하실 항목을 하나 이상 체크하세요.");
}
$toDate = date('YmdHmmss');
if ($_POST['w'] == "add"){

    $it_list = explode(',',$_POST['it_id_list']);
    for($a= 0; $a < count($it_list); $a++){

        $check = "select count(*) cnt from lt_best_item where bs_category = '{$_POST['bs_category']}' and it_id = '{$it_list[$a]}' ";
        $check_yn = sql_fetch($check);

        if($check_yn['cnt'] == 0){
            $sql = "insert into lt_best_item
                               set sort         = (SELECT IFNULL(MAX(sort) + 1, 1) from lt_best_item as A where bs_category = '{$_POST['bs_category']}')
                                   ,bs_category = '{$_POST['bs_category']}'
                                   ,it_id = '{$it_list[$a]}'
                                   ,reg_date = '{$toDate}'
                    ";
            sql_query($sql);
        }else{
            alert($it_list[$a]." 상품이 중목입니다.");
        }

    }
    

}else if($_POST['w'] == "delete"){
    

    $it_list = explode(',',$_POST['it_id_list']);
    for($z= 0; $z < count($it_list); $z++){
        $dsql ="delete from lt_best_item
                where bs_category = '{$_POST['bs_category']}'
                and it_id = '{$it_list[$z]}'
                ";
        sql_query($dsql);
    }

}else{

    // $toDate = date("YmdHmmss");
  
    $reset = "delete from lt_best_item where bs_category = '{$_POST['bs_category']}'";
    sql_query($reset);
    
    for($i= 0; $i < count($_POST['bit_id']); $i++){

    
        $sql1 = "insert into lt_best_item
                           set sort         = '{$_POST['sort'][$i]}'
                               ,bs_category = '{$_POST['bs_category']}'
                               ,it_id = '{$_POST['bit_id'][$i]}'
                               ,reg_date = '{$toDate}'
                ";
        sql_query($sql1);
    }
}

if ($_POST['act'] == "it_use0") {
    // for ($i=0; $i<count($_POST['chk']); $i++) {
        
    //     // 실제 번호를 넘김
    //     $k = $_POST['chk'][$i];
        
    //     $sql = "update lt_best_item
    //                set it_use         = '0'
    //                    ,it_update_time = '".G5_TIME_YMDHIS."'
    //              where it_id   = '{$_POST['it_id'][$k]}' ";
    //     sql_query($sql);
    // }

} else if ($_POST['act'] == "it_use1") {
    // for ($i=0; $i<count($_POST['chk']); $i++) {
        
    //     // 실제 번호를 넘김
    //     $k = $_POST['chk'][$i];
        
    //     $sql = "update lt_best_item
    //                set it_use         = '1'
    //                    ,it_update_time = '".G5_TIME_YMDHIS."'
    //              where it_id   = '{$_POST['it_id'][$k]}' ";
    //     sql_query($sql);
    // }
    
} else if ($_POST['act_button'] == "선택수정") {

    // auth_check($auth[substr($sub_menu,0,2)], 'w');

    // for ($i=0; $i<count($_POST['chk']); $i++) {

    //     // 실제 번호를 넘김
    //     $k = $_POST['chk'][$i];

    //     if( ! $_POST['ca_id'][$k]) {
    //         alert("기본분류는 반드시 선택해야 합니다.");
    //     }

    //     $sql = "update {$g5['g5_shop_item_table']}
    //                set ca_id          = '".sql_real_escape_string($_POST['ca_id'][$k])."',
    //                    ca_id2         = '".sql_real_escape_string($_POST['ca_id2'][$k])."',
    //                    ca_id3         = '".sql_real_escape_string($_POST['ca_id3'][$k])."',
    //                    it_name        = '".sql_real_escape_string($_POST['it_name'][$k])."',
    //                    it_cust_price  = '".sql_real_escape_string($_POST['it_cust_price'][$k])."',
    //                    it_price       = '".sql_real_escape_string($_POST['it_price'][$k])."',
    //                    it_stock_qty   = '".sql_real_escape_string($_POST['it_stock_qty'][$k])."',
    //                    it_skin        = '".sql_real_escape_string($_POST['it_skin'][$k])."',
    //                    it_mobile_skin = '".sql_real_escape_string($_POST['it_mobile_skin'][$k])."',
    //                    it_use         = '".sql_real_escape_string($_POST['it_use'][$k])."',
    //                    it_soldout     = '".sql_real_escape_string($_POST['it_soldout'][$k])."',
    //                    it_order       = '".sql_real_escape_string($_POST['it_order'][$k])."',
    //                    it_update_time = '".G5_TIME_YMDHIS."'
    //              where it_id   = '".preg_replace('/[^a-z0-9_\-]/i', '', $_POST['it_id'][$k])."' ";
    //     sql_query($sql);
    // }
} else if ($_POST['act_button'] == "선택삭제") {

    // if ($is_admin != 'super')
    //     alert('상품 삭제는 최고관리자만 가능합니다.');

    // auth_check($auth[substr($sub_menu,0,2)], 'd');

    // // _ITEM_DELETE_ 상수를 선언해야 itemdelete.inc.php 가 정상 작동함
    // define('_ITEM_DELETE_', true);

    // for ($i=0; $i<count($_POST['chk']); $i++) {
    //     // 실제 번호를 넘김
    //     $k = $_POST['chk'][$i];

    //     // include 전에 $it_id 값을 반드시 넘겨야 함
    //     $it_id = preg_replace('/[^a-z0-9_\-]/i', '', $_POST['it_id'][$k]);
    //     include ('./itemdelete.inc.php');
    // }
}

goto_url("./best.itemlist.php?save_stx=&best_ca=".$_POST['bs_category']."&sc_it_use=&sc_it_soldout=&sst=it_time&sod=desc&page_rows=10");



?>
