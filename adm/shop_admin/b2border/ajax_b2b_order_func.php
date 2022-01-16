<?php

    $sub_menu = '96';
    include_once('./_common.php');
    
    $referer = $_SERVER["HTTP_REFERER"];
    $cut_url = explode("?" , $referer);

    $qstr=$cut_url[1];

    $result = "";

    $nowDay=date("Y-m-s h:i:s");
    $nowDay2=date("Y-m-s");

    $bos = $_POST['selects_ord'];

    if($_POST["fnc_type"] == 'clgo_request'){
        // 출고요청
        $clgo_r = "UPDATE b2b_order SET order_status ='출고요청' , up_date = '{$nowDay}' WHERE bo_no IN ($bos) AND order_status = '주문접수' ";
        sql_query($clgo_r);

        $result['code'] = "100";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
        
    }else if($_POST["fnc_type"] == 'clgo_instruction'){
        // 출고지시
        if($_POST['cp_code'] != '19941'){
            $select_ord = "SELECT * FROM b2b_order WHERE bo_no IN ($bos) ";
            $res_ord = sql_query($select_ord);
    
            $stock_object = new stdClass();
    
            for($sbo = 0 ; $sbo_Row = sql_fetch_array($res_ord); $sbo ++){
                $order_qty = $sbo_Row["order_qty"];
                $stock_object-> $sbo_Row["samjin_it_name"] += $order_qty;  

                $addr_ck = "SELECT * FROM b2b_store_list WHERE cp_code = '{$sbo_Row['cp_code']}' AND st_name = '{$sbo_Row['st_name']}' limit 1 ";
                $addr_res = sql_fetch($addr_ck);

                if(!empty($addr_res)){
                    $addr_up = " UPDATE b2b_order SET receive_tel =  '{$addr_res['st_tel']}' , receive_zip  = '{$addr_res['st_zip']}' , receive_addr1  = '{$addr_res['st_addr1']}' , receive_addr2  = '{$addr_res['st_addr2']}'  WHERE bo_no = '{$sbo_Row[bo_no]}' ";
                    sql_query($addr_up);
                }

            }
           
            foreach($stock_object as $samjin_it_name => $total_qty){
                $sale_item = "SELECT stock FROM b2b_sale_item_list WHERE samjin_it_name = '{$samjin_it_name}' limit 1";
                $stock = sql_fetch($sale_item);
    
                if($total_qty > $stock['stock'] ){
                    $result['code'] = '299';
                    $result['samjin_it_name'] = $samjin_it_name;
                    $result['total_qty'] = $total_qty;
                    $result['stock'] = $stock['stock'];
                    
                    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                    return false;
                }
            }
        }


        //차수
        $num_sql = "select max(dpart_num) as degress from b2b_order_form where reg_date like '{$nowDay2}%' limit 1 ";
        $res_num = sql_fetch($num_sql);

        if(!empty($res_num['degress'])){
            $num = 1;
        }else{
            $num = $res_num['degress'] + 1;
        }
        
        $ord_f_sql =  "INSERT INTO b2b_order_form (cp_name , cp_code , order_date, reg_date , mall_code , supply_cp, order_no, st_name, st_tel, receive_name, receive_tel, receive_zip, receive_addr, samjin_modi_it_code, samjin_it_name, samjin_code, sap_code, color, size, order_qty, box_qty, dpart_type, deliver_type, dpart_num , order_price , box_num ,sku_id)
        SELECT cp_name, cp_code, reg_date , '$nowDay' , mall_code , supply_cp , order_no, st_name, st_tel, receive_name, receive_tel,  receive_zip, CONCAT(receive_addr1 ,' ',receive_addr2),  CONCAT(sap_code,color,IFNULL(hb.barcode_size,size)) , samjin_it_name ,  samjin_code, sap_code, color, size, order_qty, order_qty , dpart_type, deliver_type , $num , order_price , '0001' , sku_id FROM b2b_order AS b2bo LEFT JOIN samjin_hoching_barcode AS hb ON hb.hoching = b2bo.size WHERE bo_no IN ($bos)";
        
        sql_query($ord_f_sql);
        
        $clgo_i = "UPDATE b2b_order SET order_status ='출고지시' , up_date = '{$nowDay}' WHERE bo_no IN ($bos) AND order_status = '출고요청' ";
        sql_query($clgo_i);

        $result['code'] = "200";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }else if($_POST["fnc_type"] == 'order_cancel'){
        // 취소

        $clgo_c = "UPDATE b2b_order SET order_status ='주문취소' , cancel_date = '{$nowDay}' WHERE bo_no IN ($bos) AND order_status in ('주문접수' , '출고요청') ";
        sql_query($clgo_c);

        $result['code'] = "300";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }else if($_POST["fnc_type"] == "all_save"){
        // 일괄저장
        $bos = array();
        foreach ($_POST["bo_no"] as $bo => $bo_no) {
            $sql_common = "";
        
            $bos =  $_POST["bo_no"][$bo];
            $order_qty =  preg_replace('/,/', '',$_POST["order_qty"][$bo]);
            $dpart_type =  $_POST["dpart_type"][$bo];
            $deliver_type =  $_POST["deliver_type"][$bo];
    
            if(!empty($bos)){
                $sql_common .= " order_qty = '{$order_qty}' ";
                $sql_common .= " , dpart_type = '{$dpart_type}' ";
                $sql_common .= " , deliver_type = '{$deliver_type}' ";
                $up_set_sql = "update b2b_order set  $sql_common where bo_no = '$bos' ";
                sql_query($up_set_sql);
            }
        }

        $result['code'] = "400";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }else if($_POST["fnc_type"] == "box_separation"){
        $box_separation = intval($_POST['box_num']);
        $bf_no = $_POST['selects_ord'];
        
        
        if(!empty($bf_no)){

            for($i = 1 ; $i < $box_separation; $i++ ){

                $box_num = sprintf('%04d',($i+1)); 

                $chk_bf = "INSERT INTO b2b_order_form (cp_name , cp_code , order_date, reg_date , mall_code , supply_cp, order_no, st_name, st_tel, receive_name, receive_tel, receive_zip, receive_addr, samjin_modi_it_code, samjin_it_name, samjin_code, sap_code, color, size, order_qty, box_qty, dpart_type, deliver_type, dpart_num , order_price , box_num )
                SELECT cp_name , cp_code , order_date, reg_date , mall_code , supply_cp, order_no, st_name, st_tel, receive_name, receive_tel, receive_zip, receive_addr, samjin_modi_it_code, samjin_it_name, samjin_code, sap_code, color, size, order_qty, box_qty, dpart_type, deliver_type, dpart_num , order_price , '{$box_num}'  FROM b2b_order_form WHERE bf_no = '{$bf_no}'";

                sql_query($chk_bf);

            }
        }


        $result['code'] = "500";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;


    }else if($_POST["fnc_type"] == "all_save_form"){
        // 일괄저장
        $bfs = array();
        foreach ($_POST["bf_no"] as $bf => $bf_no) {
            $sql_common = "";
            
            $bfs =  $_POST["bf_no"][$bf];
            $box_num =  $_POST["box_num"][$bf];
            $clgo_qty =  $_POST["clgo_qty"][$bf];
            $invoice_no =  $_POST["invoice_no"][$bf];
            $dpart_type =  $_POST["dpart_type"][$bf];

            if(!empty($bfs)){
                $sql_common .= " clgo_qty = '{$clgo_qty}' ";
                $sql_common .= " , invoice_no = '{$invoice_no}' ";
                $sql_common .= " , dpart_type = '{$dpart_type}' ";

                $sql_common .= " , invoice_up_dt = '{$nowDay}' ";
                $up_set_sql = "update b2b_order_form set  $sql_common where bf_no = '$bfs' ";
                sql_query($up_set_sql);
            }
        }


        $result['code'] = "600";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }else if($_POST["fnc_type"] == "box_delete"){
        // 일괄저장
        

        $bfs = $bos;
        $box_delete = "DELETE FROM b2b_order_form WHERE bf_no IN ($bfs) AND box_num <> '0001' ";
        
        sql_query($box_delete);
        


        $result['code'] = "700";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }else if($_POST["fnc_type"] == 'order_form_conform'){
        // 확정처리
        $bfs = $bos;
        $o_f_c = "UPDATE b2b_order_form SET order_form_status ='출고확정'  WHERE bf_no IN ($bfs) ";
        sql_query($o_f_c);

        $result['code'] = "800";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;

    }


    // goto_url("./b2b_order.php?".$qstr);


