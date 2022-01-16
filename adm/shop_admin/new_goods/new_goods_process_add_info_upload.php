<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];


$ps_id = $_POST['ps_id'];
$file = $_FILES['file'];
$type = $_POST['type'];

if($type == 'add'){
  var_dump("add");
  foreach ($_FILES['file']['name'] as $idx => $info) {
    if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'][$idx])) {
          $ftp_server = "litandard-org.daouidc.com"; 
          $ftp_port = 2021; 
          $ftp_user_name = "litandard"; 
          $ftp_user_pass = "flxosekem_ftp!@34"; 
          $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
          $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
          ftp_pasv($conn_id, true);
          $filepath = $_FILES['file']['tmp_name'][$idx];
      
          // $path = md5(microtime()) . '.' . $_FILES['file']['name'][$idx];
          $path =  $_FILES['file']['name'][$idx];
      
          $upload = ftp_put($conn_id, '/new_goods/job_order_info/'.$path, $filepath, FTP_BINARY);
          // $path = 'https://lifelikecdn.co.kr/new_goods/job_order_info/'.$path;
          if ($upload) {
              $tpType = 1;
              $upsql = "UPDATE lt_prod_schedule SET ps_add_info_file = '{$path}' WHERE  ps_id = {$ps_id} ";
              sql_query($upsql);
              // $chk_cnt = sql_fetch( "SELECT COUNT(*) AS cnt FROM lt_prod_schedule WHERE ps_id = {$ps_id} AND ps_add_info_file IS NULL LIMIT 1" );
              // if($chk_cnt['cnt'] > 0){
              //   $upsql = "UPDATE lt_prod_schedule SET ps_add_info_file = '{$path}' WHERE  ps_id = {$ps_id} ";
              //   sql_query($upsql);
              // }else{
              //   $insql = "UPDATE lt_prod_schedule SET ps_add_info_file = '{$path}' WHERE  ps_id = {$ps_id} ";
              //   sql_query($insql);
              // }
              // $tpNum = sql_fetch(" SELECT tp_num+1 AS tn FROM lt_temper WHERE tp_type = $tpType ORDER BY tp_num DESC LIMIT 1 ");
              // $sql = " INSERT INTO lt_temper (tp_img,tp_use,tp_num,tp_type) VALUES ('$path','$tp_use', '{$tpNum['tn']}',$tpType)";
              // sql_query($sql);
          } else {
             
          }      
    }
    $infofile[$idx] = $path;
  }
}else if ($type == 'delete'){
  $ftp_server = "litandard-org.daouidc.com"; 
  $ftp_port = 2021; 
  $ftp_user_name = "litandard"; 
  $ftp_user_pass = "flxosekem_ftp!@34"; 
  $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
  $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
  ftp_pasv($conn_id, true);

  $f_name_sql = "select ps_add_info_file from lt_prod_schedule where ps_id = {$ps_id} limit 1 ";
  $res_sql = sql_fetch($f_name_sql);

  $file_name = $res_sql['ps_add_info_file'];

  ftp_chdir($conn_id, '/new_goods/job_order_info/');

  $res = ftp_size($conn_id, $file_name);


  ftp_delete($conn_id, $file_name);
  if($res != -1){
  }

  $upsql = "UPDATE lt_prod_schedule SET ps_add_info_file = NULL WHERE  ps_id = {$ps_id} ";
  sql_query($upsql);
}else if ($type == 'download'){

}


?>





