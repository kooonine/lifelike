<?php
include_once('./_common.php');

if(!empty($_POST)) {
    
    $sql_col = "*";
    $sql_common = " from lt_shop_info";
    $sql_search = " where (1) ";
    $sql_group = "";
    
    $if_id = $_POST['if_id'];
    if($if_id) {
        $sql_search .= "and if_id = ".$if_id;
    }
    
    
    $ca_name1 = $_POST['ca_name1'];
    $ca_name2 = $_POST['ca_name2'];
    $ca_name3 = $_POST['ca_name3'];
    if($ca_name1) {
        $sql_col = "ca_name1, ca_name2";
        $sql_search .= "and ca_name1 = '".$ca_name1."'";
        $sql_group = "group by ca_name1, ca_name2";
    }
    if($ca_name2) {
        $sql_col .= ", ca_name3";
        $sql_search .= "and ca_name2 = '".$ca_name2."'";
        $sql_group .= ", ca_name3";
    }
    if($ca_name3) {
        $sql_col .= ", ca_name4";
        $sql_search .= "and ca_name3 = '".$ca_name3."'";
        $sql_group .= ", ca_name4";
    }
    if($ca_name1) {
        $sql_col .= ", count(*) cnt, max(if_id) as if_id";
    }
        
    $sql = " select {$sql_col} {$sql_common} {$sql_search} {$sql_group}";
    
    $result = sql_query($sql);
    //echo $sql;
    $rows = array();
    
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $rows[$i] = $row;
    }
    $my_array_json_string = json_encode_raw($rows, JSON_UNESCAPED_UNICODE);
    
    echo $my_array_json_string;
}
?>