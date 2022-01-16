<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');



$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];

   
if($_POST["fnc_type"]  == 'display'){
    $select_si_no = $_POST['seiects_no'];

    $dis_sql = "update b2b_sale_item_list set display_yn = 'N' where si_no in ($select_si_no)";
    sql_query($dis_sql);

}else{
    
    foreach ($_POST["si_no"] as $si => $si_no) {
    
        $supply_price= preg_replace('/,/', '',$_POST["supply_price"][$si]);
        $minium_order= preg_replace('/,/', '',$_POST["minium_order"][$si]);
        $display_yn = $_POST["display_yn"][$si];
    
        $sql_common = "";
        if(!empty($si_no)){
            $sql_common .= " supply_price = '{$supply_price}' ";
            $sql_common .= " , minium_order = '{$minium_order}' ";
            $sql_common .= " , display_yn = '{$display_yn}' ";
            $sql_common .= " , up_date = now() ";
            $up_si_sql = "update b2b_sale_item_list set  $sql_common where si_no = '$si_no' ";
            sql_query($up_si_sql);
        }else{
            $sql_common .= "  cp_name = '{$_POST["cp_name"]}' ";
            $sql_common .= " , cp_code = '{$_POST["cp_code"]}' ";
            $sql_common .= " , reg_date = now() ";
            $sql_common .= " , it_name = '{$_POST["samjin_it_name"][$si]}' ";
            $sql_common .= " , color = '{$_POST["color"][$si]}' ";
            $sql_common .= " , size = '{$_POST["size"][$si]}' ";
            $sql_common .= " , samjin_it_name = '{$_POST["samjin_it_name"][$si]}' ";
            $sql_common .= " , samjin_code = '{$_POST["samjin_code"][$si]}' ";
            $sql_common .= " , sap_code = '{$_POST["sap_code"][$si]}' ";
            $sql_common .= " , normal_price = '{$_POST["normal_price"][$si]}' ";
            $sql_common .= " , supply_price = '{$supply_price}' ";
            $sql_common .= " , display_yn = '{$display_yn}' ";
            $sql_common .= " , stock = '{$_POST["stock"][$si]}' ";
            $sql_common .= " , minium_order = '{$minium_order}' ";
    
            $add_si_sql = "insert into b2b_sale_item_list set  $sql_common  ";
            
            sql_query($add_si_sql);
    
        }
    }


}    


?>