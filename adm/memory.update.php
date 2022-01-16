<?php
$sub_menu = '800840';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');



if ($w == "u") { 
    if (isset($_FILES['ba_image']) && is_uploaded_file($_FILES['ba_image']['tmp_name'])) {
    
        $ftp_server = "litandard-org.daouidc.com"; 
        $ftp_port = 2021; 
        $fmf_user_name = "litandard"; 
        $fmf_user_pass = "flxosekem_ftp!@34"; 
        $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
        $login_result = ftp_login($conn_id, $fmf_user_name, $fmf_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
        ftp_pasv($conn_id, true);
        
        $mfImg = sql_fetch(" SELECT mf_img FROM lt_memoryform WHERE mf_id = '$mf_id' ");

        $mfImg['mf_img'] = str_replace('https://lifelikecdn.co.kr/','',$mfImg['mf_img']);
        $ftpDel = ftp_delete($conn_id, $mfImg['mf_img']);

        $filepath = $_FILES['ba_image']['tmp_name'];
    
        $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
    
        $upload = ftp_put($conn_id, '/newbanner/'.$path, $filepath, FTP_BINARY);
        $path = 'https://lifelikecdn.co.kr/newbanner/'.$path;
        if ($upload) {
            sql_query(" UPDATE lt_memoryform SET mf_img='$path',mf_use='$mf_use', mf_update_datetime = '".G5_TIME_YMDHIS."' WHERE mf_id = '$mf_id' ");
        } else {
           
        }
    
    } else {
        sql_query(" UPDATE lt_memoryform SET mf_use='$mf_use', mf_update_datetime = '".G5_TIME_YMDHIS."' WHERE mf_id = '$mf_id' ");
    }
} else if ($w == "d") {
    sql_query(" DELETE FROM lt_memoryform WHERE mf_id = '$mf_id' ");
} else if ($w == "s") { 

    $mfNumArr = explode(',', $mfNum);
    $i = 1;
    foreach($mfNumArr as $mfId) {
        sql_query(" UPDATE lt_memoryform SET mf_num='$i' WHERE mf_id = '$mfId' ");
        $i += 1;
    }
    $result = 'success';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;

}else if ($w == "is") { 
    sql_query(" UPDATE lt_memoryform SET mf_item='$mfItem', mf_update_datetime = '".G5_TIME_YMDHIS."' WHERE mf_type = '0' ");
    $result = 'success';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;
} else {

    if (isset($_FILES['ba_image']) && is_uploaded_file($_FILES['ba_image']['tmp_name'])) {
    
        $ftp_server = "litandard-org.daouidc.com"; 
        $ftp_port = 2021; 
        $fmf_user_name = "litandard"; 
        $fmf_user_pass = "flxosekem_ftp!@34"; 
        $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
        $login_result = ftp_login($conn_id, $fmf_user_name, $fmf_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
        ftp_pasv($conn_id, true);
        $filepath = $_FILES['ba_image']['tmp_name'];
    
        $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
    
        $upload = ftp_put($conn_id, '/newbanner/'.$path, $filepath, FTP_BINARY);
        $path = 'https://lifelikecdn.co.kr/newbanner/'.$path;
        if ($upload) {
            $mfType = 1;
            
            $tpNum = sql_fetch(" SELECT mf_num+1 AS tn FROM lt_memoryform WHERE mf_type = $mfType ORDER BY mf_num DESC LIMIT 1 ");
            $sql = " INSERT INTO lt_memoryform (mf_img,mf_use,mf_num,mf_type) VALUES ('$path','$mf_use', '{$tpNum['tn']}',$mfType)";
            sql_query($sql);
        } else {
           
        }
    
    } else {
       
    }
} 




goto_url('./memory.list.php');
return;
?>


