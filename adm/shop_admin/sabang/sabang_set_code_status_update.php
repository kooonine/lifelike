<?php

// $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
// $root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));
// $outputs = array();
// // $outputs[] = date('Y-m-d H:i:s', time()) . " : 크론시작  ";
// // include_once($root_path . '/../../common.php');

// include_once($root_path.'/_common.php');
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');



$cnos = $_POST['cnos'];
$status = $_POST['status'];


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];


if(!empty($cnos)){
    $sql_sts = " status = '{$status}' ";
    $up_set_sql = "update sabang_set_code_mapping set  $sql_sts where cno in ($cnos) ";
    sql_query($up_set_sql);
    // $result = $up_set_sql;
    // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    // return false;

}

$result = '200';
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return false;

goto_url("./sabang_set_code_mapping.php?".$qstr);


?>