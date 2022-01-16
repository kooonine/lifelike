<?php

    $sub_menu = '96';
    include_once('./_common.php');
    
    $referer = $_SERVER["HTTP_REFERER"];
    $cut_url = explode("?" , $referer);

    $qstr=$cut_url[1];


    if($_POST['type'] == "add"){
        $cp_name = $_POST['add_cp_name'];
        $cp_code = $_POST['add_cp_code'];
        $cp_number = $_POST['add_cp_number'];
    
        $result = "";
    
        
    
        $sql = "SELECT count(*) AS cnt FROM b2b_company WHERE cp_name = '{$cp_name}' AND cp_code = '{$cp_code}' AND cp_number = '{$cp_number}' ";
        
        $res_sql = sql_fetch($sql);
    
        if($res_sql['cnt'] > 0){
            $result  = '200';
            echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            return false;
        }else{
            $in_sql = "INSERT INTO b2b_company set cp_name = '{$cp_name}' , cp_code = '{$cp_code}' , cp_number = '{$cp_number}' , reg_date = now() , cp_gubun = 'Y', use_yn = 'Y'  ";
            sql_query($in_sql);
            $c_no = sql_insert_id();
    
            $cp_sql = " update b2b_company set sort = '$c_no'  where c_no = '$c_no' ";
            sql_query($cp_sql);
    
        }
    }else if($_POST['type'] == "comform"){
        $st_no = $_POST['st_no'];

        $st_sql = " update b2b_store_list set st_comform = 'Y'  where st_no = '$st_no' ";
        sql_query($st_sql);

        $result  = '300';
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }else if($_POST['type'] == "shutdown"){
        $st_no = $_POST['st_no'];

        $st_sql = " update b2b_store_list set st_shutdown = 'Y'  where st_no = '$st_no' ";
        sql_query($st_sql);
        
        $result  = '300';
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
        
    }



