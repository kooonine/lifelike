<?php
$sub_menu = '800820';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');



if ($w == "u") { 
    if (isset($_FILES['ba_image']) && is_uploaded_file($_FILES['ba_image']['tmp_name'])) {
    
        $ftp_server = "litandard-org.daouidc.com"; 
        $ftp_port = 2021; 
        $ftp_user_name = "litandard"; 
        $ftp_user_pass = "flxosekem_ftp!@34"; 
        $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
        ftp_pasv($conn_id, true);
        
        $tpImg = sql_fetch(" SELECT tp_img FROM lt_temper WHERE tp_id = '$tp_id' ");

        $tpImg['tp_img'] = str_replace('https://lifelikecdn.co.kr/','',$tpImg['tp_img']);
        $ftpDel = ftp_delete($conn_id, $tpImg['tp_img']);

        $filepath = $_FILES['ba_image']['tmp_name'];
    
        $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
    
        $upload = ftp_put($conn_id, '/newbanner/'.$path, $filepath, FTP_BINARY);
        $path = 'https://lifelikecdn.co.kr/newbanner/'.$path;
        if ($upload) {
            sql_query(" UPDATE lt_temper SET tp_img='$path',tp_use='$tp_use', tp_update_datetime = '".G5_TIME_YMDHIS."' WHERE tp_id = '$tp_id' ");
        } else {
           
        }
    
    } else {
        sql_query(" UPDATE lt_temper SET tp_use='$tp_use', tp_update_datetime = '".G5_TIME_YMDHIS."' WHERE tp_id = '$tp_id' ");
    }
} else if ($w == "d") {
    sql_query(" DELETE FROM lt_temper WHERE tp_id = '$tp_id' ");
} else if ($w == "s") { 

    $tpNumArr = explode(',', $tpNum);
    $i = 1;
    foreach($tpNumArr as $tpId) {
        sql_query(" UPDATE lt_temper SET tp_num='$i' WHERE tp_id = '$tpId' ");
        $i += 1;
    }
    $result = 'success';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;

} else if ($w == "is") { 
    sql_query(" UPDATE lt_temper SET tp_item='$tpItem', tp_update_datetime = '".G5_TIME_YMDHIS."' WHERE tp_type = '0' ");
    $result = 'success';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;
} else {

    
    if (isset($_FILES['ba_image']) && is_uploaded_file($_FILES['ba_image']['tmp_name'])) {
    
        $ftp_server = "litandard-org.daouidc.com"; 
        $ftp_port = 2021; 
        $ftp_user_name = "litandard"; 
        $ftp_user_pass = "flxosekem_ftp!@34"; 
        $conn_id = ftp_connect($ftp_server,$ftp_port) or die("Couldn't connect to $ftp_server");
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<Br> Couldn't connect to $ftp_server"); 
        ftp_pasv($conn_id, true);
        $filepath = $_FILES['ba_image']['tmp_name'];
    
        $path = md5(microtime()) . '.' . $_FILES['ba_image']['name'];
    
        $upload = ftp_put($conn_id, '/newbanner/'.$path, $filepath, FTP_BINARY);
        $path = 'https://lifelikecdn.co.kr/newbanner/'.$path;
        if ($upload) {
            $tpType = 1;
            
            $tpNum = sql_fetch(" SELECT tp_num+1 AS tn FROM lt_temper WHERE tp_type = $tpType ORDER BY tp_num DESC LIMIT 1 ");
            $sql = " INSERT INTO lt_temper (tp_img,tp_use,tp_num,tp_type) VALUES ('$path','$tp_use', '{$tpNum['tn']}',$tpType)";
            sql_query($sql);
        } else {
           
        }
    
    } else {
       
    }
} 

goto_url('./temper.list.php');
return;
?>


