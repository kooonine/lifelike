<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

if($cancel_select == "재세탁")
{
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, cancel_select, sh_file)
                     values
                        ('{$od_id}', 1, '{$sh_memo}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','{$cancel_select}','{$sh_file}'); ";
    sql_query($sql);
        
} else if($cancel_select == "추가비용발생") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id']){
        alert_close('주문정보가 존재하지 않습니다.');
    }
    $sh_add_price = (int)preg_replace('/[^0-9]/', '', $_POST['sh_add_price']);
    
    if($add_price_type == "1"){
        //결제처리(안심키인) 으로 결제처리가 완료됨.
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_receipt_price = ct_receipt_price + '{$sh_add_price}' where od_id = '$od_id'  ");
        
        $sql = " update {$g5['g5_shop_order_table']}
                set od_receipt_price = od_receipt_price + '{$sh_add_price}'
                where od_id = '$od_id' ";
        sql_query($sql);
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, cancel_select, sh_add_price, sh_file)
                         values
                            ('{$od_id}', 1, '{$sh_memo}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','{$cancel_select}','{$sh_add_price}','{$sh_file}'); ";
        sql_query($sql);
        
    } else if($add_price_type == "2"){
        
        require_once(G5_SHOP_PATH.'/settle_lg_ars.inc.php');
        //ARS 결제 요청
        $sh_add_price_times = explode("~", $sh_add_price_time);
        
        //ARS 임시 결제번호 만들기
        $ymd = preg_replace('/[^0-9]/', '',G5_TIME_YMD);
        $od_add = sql_fetch("select ifnull(max(od_app_no)+1,'".$ymd."01') od_app_no from lt_shop_order_add_receipt where od_receipt_type = 'ars' and od_app_no like '".$ymd."%' ");
        $LGD_RESERVENUMBER = $od_add['od_app_no'];
        
        $LGD_REQTYPE = "Create";
        $LGD_PRODUCTINFO =  iconv("UTF-8", "EUC-KR", $sh_add_price_productinfo);
        $LGD_BUYERPHONE = preg_replace('/[^0-9]/', '', $sh_add_price_mb_hp);
        $LGD_AMOUNT = $sh_add_price;
        $LGD_RESERVEDATE = preg_replace('/[^0-9]/', '', trim($sh_add_price_times[0]));
        $LGD_EXPIREDATE = preg_replace('/[^0-9]/', '', trim($sh_add_price_times[1]));
        $LGD_EX_PARAM = "";
        $LGD_STATUS = "Y";
        
        $xpay = new XPay($configPath, $CST_PLATFORM);
        
        // Mert Key 설정
        $xpay->set_config_value('t'.$LGD_MID, $LGD_MERTKEY);
        $xpay->set_config_value($LGD_MID, $LGD_MERTKEY);
        
        $xpay->Init_TX($LGD_MID);
        
        $xpay->Set("LGD_TXNAME", "ARSReserve");
        $xpay->Set("LGD_REQTYPE", $LGD_REQTYPE);
        $xpay->Set("LGD_RESERVENUMBER", $LGD_RESERVENUMBER);
        $xpay->Set("LGD_PRODUCTINFO", $LGD_PRODUCTINFO);
        $xpay->Set("LGD_BUYERPHONE", $LGD_BUYERPHONE);
        $xpay->Set("LGD_CALLBACK", $LGD_CALLBACK);
        $xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
        $xpay->Set("LGD_RESERVEDATE", $LGD_RESERVEDATE);
        $xpay->Set("LGD_EXPIREDATE", $LGD_EXPIREDATE);
        $xpay->Set("LGD_NOTEURL", $LGD_NOTEURL);
        $xpay->Set("note_url", $note_url);
        $xpay->Set("LGD_EX_PARAM", $LGD_EX_PARAM);
        $xpay->Set("LGD_STATUS", $LGD_STATUS);
        
        /*
         * 1. 예약번호 등록/수정/조회 요청 결과처리
         *
         * 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
         */
        if ($xpay->TX()) {
            //1)요청 결과 처리(등록, 변경, 조회  요청결과를 처리 하시기 바랍니다.)
            /*
            echo "요청이 완료되었습니다  <br>";
            echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
            echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
            $keys = $xpay->Response_Names();
            LGD_STATUS = Y
            LGD_RESERVENUMBER = 1234567891
            LGD_BUYERPHONE = 01035674433
            LGD_CALLBACK = 16446962
            LGD_TIMESTAMP = 20190714171428
            LGD_RESERVEDATE = 20190714
            LGD_EXPIREDATE = 20190715
            LGD_RESPMSG = [1234567891]등록성공
            LGD_PRODUCTINFO = ��二쇰�� 2諛�3��
            LGD_NOTEURL = http://localhost/adm/shop_admin/lg_ars/note_url.php
            LGD_RESPCODE = 0000
            LGD_HASHDATA = b8625818c40680a7f025abbe3a96fcc5
            LGD_AMOUNT = 1004
            LGD_MID = t1113397
            LGD_EX_PARAM = 
            
            echo "<p>";
            */
            if( '0000' == $xpay->Response_Code() ) {
                sql_query(" update {$g5['g5_shop_cart_table']} set ct_price = ct_price + '{$sh_add_price}' where od_id = '$od_id'  ");
                
                $sql = " update {$g5['g5_shop_order_table']}
                    set od_cart_price = od_cart_price + '{$sh_add_price}'
                        ,od_misu = ifnull(od_misu,0) + '{$sh_add_price}'
                    where od_id = '$od_id' ";
                sql_query($sql);
                
                $sh_memo .= PHP_EOL."예약일자:".$xpay->Response("LGD_RESERVEDATE", 0)."-".$xpay->Response("LGD_EXPIREDATE", 0);
                $sql = " insert into lt_shop_order_history
                                (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, cancel_select, sh_add_price, sh_file)
                             values
                                ('{$od_id}', 1, '{$sh_memo}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','{$cancel_select}','{$sh_add_price}','{$sh_file}'); ";
                sql_query($sql);
                
                // 추가결제 내역에 입력
                $sql = " insert lt_shop_order_add_receipt
                set od_id             = '$od_id',
                    od_type           = '{$od['od_type']}',
                    mb_id             = '{$member['mb_id']}',
                    od_send_cost      = '0',
                    od_receipt_price  = '0',
                    od_bank_account   = '',
                    od_deposit_name   = '".$xpay->Response("LGD_BUYERPHONE", 0)."',
                    od_receipt_time   = '".$xpay->Response("LGD_TIMESTAMP", 0)."',
                    od_misu           = '".$xpay->Response("LGD_AMOUNT", 0)."',
                    od_pg_id          = 'lg_ars',
                    od_settle_case    = '',
                    od_mobile         = '0',
                    od_pg             = 'lg',
                    od_tno            = '',
                    od_app_no         = '".$xpay->Response("LGD_RESERVENUMBER", 0)."',
                    od_escrow         = '',
                    od_tax_flag       = '',
                    od_tax_mny        = '',
                    od_vat_mny        = '',
                    od_free_mny       = '',
                    od_ip             = '$REMOTE_ADDR',
                    od_mb_id          = '{$member['mb_id']}',
                    od_receipt_type   = 'ars',
                    od_test           = '{$default['de_card_test']}'
                    ";
                $result = sql_query($sql, false);
            } else {
                alert("ARS 결제 요청에 실패 하였습니다.".$xpay->Response_Msg().' 코드 : '.$xpay->Response_Code());
            }
        }else {
            //2)API 요청 실패 화면처리
            alert("ARS 결제 요청에 실패 하였습니다.".$xpay->Response_Msg().' 코드 : '.$xpay->Response_Code());
            
            //echo "API 요청이 실패하였습니다.  <br>";
            //echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
            //echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
        }
    }
    
} else if($cancel_select == "고객반려" || $cancel_select == "펭귄반려") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id']){
        alert_close('주문정보가 존재하지 않습니다.');
    }
    
    $sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' ";
    $ct = sql_fetch($sql);
    
    //환불처리   
    include_once(G5_SHOP_PATH.'/settle_lg3.inc.php');
        
    $tax_mny = (int)$od['od_receipt_price'] - (int)preg_replace('/[^0-9]/', '', $_POST['sh_add_price']);

    if($tax_mny > 0) {
        /*
         * [결제 부분취소 요청 페이지]
         *
         * LG유플러스으로 부터 내려받은 거래번호(LGD_TID)를 가지고 취소 요청을 합니다.(파라미터 전달시 POST를 사용하세요)
         * (승인시 LG유플러스으로 부터 내려받은 PAYKEY와 혼동하지 마세요.)
         */
        
        $LGD_TID              		= $od['od_tno'];			  		                            //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)
        $LGD_CANCELAMOUNT     		= (int)$tax_mny;                        //부분취소 금액
        $LGD_REMAINAMOUNT     		= (int)$od['od_receipt_price'] - (int)$od['od_refund_price'];   //취소전 남은금액
        
        $LGD_CANCELTAXFREEAMOUNT    = (int)$free_mny;                                               //면세대상 부분취소 금액 (과세/면세 혼용상점만 적용)
        $LGD_CANCELREASON     		= $sh_memo;                                                    //취소사유
        $LGD_RFACCOUNTNUM           = $_POST['LGD_RFACCOUNTNUM'];	 		                        //환불계좌 번호(가상계좌 환불인경우만 필수)
        $LGD_RFBANKCODE             = $_POST['LGD_RFBANKCODE'];	 		                            //환불계좌 은행코드(가상계좌 환불인경우만 필수)
        $LGD_RFCUSTOMERNAME         = $_POST['LGD_RFCUSTOMERNAME']; 		                        //환불계좌 예금주(가상계좌 환불인경우만 필수)
        $LGD_RFPHONE                = $_POST['LGD_RFPHONE'];		 		                        //요청자 연락처(가상계좌 환불인경우만 필수)
        
        $xpay = new XPay($configPath, $CST_PLATFORM);
        
        // Mert Key 설정
        $xpay->set_config_value('t'.$LGD_MID, $LGD_MERTKEY);
        $xpay->set_config_value($LGD_MID, $LGD_MERTKEY);
        
        $xpay->Init_TX($LGD_MID);
        
        $xpay->Set("LGD_TXNAME",                "PartialCancel");
        $xpay->Set("LGD_TID",                   $LGD_TID);
        $xpay->Set("LGD_CANCELAMOUNT",          $LGD_CANCELAMOUNT);
        $xpay->Set("LGD_REMAINAMOUNT",          $LGD_REMAINAMOUNT);
        $xpay->Set("LGD_CANCELTAXFREEAMOUNT",   $LGD_CANCELTAXFREEAMOUNT);
        $xpay->Set("LGD_CANCELREASON",          $LGD_CANCELREASON);
        $xpay->Set("LGD_RFACCOUNTNUM",          $LGD_RFACCOUNTNUM);
        $xpay->Set("LGD_RFBANKCODE",            $LGD_RFBANKCODE);
        $xpay->Set("LGD_RFCUSTOMERNAME",        $LGD_RFCUSTOMERNAME);
        $xpay->Set("LGD_RFPHONE",               $LGD_RFPHONE);
        $xpay->Set("LGD_REQREMAIN",             "0");
        $xpay->Set("LGD_ENCODING",              "UTF-8");
        
        /*
         * 1. 결제 부분취소 요청 결과처리
         *
         */
        if ($xpay->TX()) {
            //1)결제 부분취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
            /*
             echo "결제 부분취소 요청이 완료되었습니다.  <br>";
             echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
             echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
             
             $keys = $xpay->Response_Names();
             foreach($keys as $name) {
             echo $name . " = " . $xpay->Response($name, 0) . "<br>";
             }
             echo "<p>";
             */
            
            if( '0000' == $xpay->Response_Code() ) {
                $ct_status = '세탁반려';
                sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '펭귄반려' where od_id = '$od_id' ");
                
                // 환불금액기록
                $tno = $xpay->Response("LGD_TID", 0);
                $mod_mny = (int)$tax_mny + (int)$free_mny;
                
                // 반품거부
                $sql = " update {$g5['g5_shop_order_table']}
                        set od_refund_price = od_refund_price + '$mod_mny',
                            od_status = '$ct_status',
                            od_status_claim = '펭귄반려',
                            od_shop_memo = concat(od_shop_memo,\"\\n리탠다드 세탁반려 환불처리 -".G5_TIME_YMDHIS." \")
                        where od_id = '$od_id' 
                          and od_tno = '$tno' ";
                sql_query($sql);
                
                //sql_query("update lt_shop_order_item set ct_status = '' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id = '{$ct['buy_od_sub_id']} ");
                
                $sql = " insert into lt_shop_order_history
                                (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, cancel_select, sh_add_price, sh_file, ct_status_claim)
                             values
                                ('{$od_id}', 1, '{$sh_memo}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','{$cancel_select}','{$sh_add_price}','{$sh_file}','펭귄반려'); ";
                
                sql_query($sql);
            } else {
                alert($xpay->Response_Msg().' 코드 : '.$xpay->Response_Code());
            }
        } else {
            //2)API 요청 실패 화면처리
            /*
             echo "결제 부분취소 요청이 실패하였습니다.  <br>";
             echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
             echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
             */
            
            alert('반려 요청이 실패하였습니다.\\n\\n'.$xpay->Response_Code().' : '.$xpay->Response_Msg());
        }
    } else {
        //환불금액 없이 반품처리
        $ct_status = '반품완료';
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '반품' where od_id = '$od_id' ");
        
        // 반품거부
        $sql = " update {$g5['g5_shop_order_table']}
                        set od_status = '$ct_status',
                            ct_status_claim = '반품',
                            od_shop_memo = concat(od_shop_memo,\"\\n관리자 반품완료 -".G5_TIME_YMDHIS." \")
                        where od_id = '$od_id' ";
        sql_query($sql);
        
        sql_query("update lt_shop_order_item set ct_status = '' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id = '{$ct['buy_od_sub_id']} ");
        
        $sql = " insert into lt_shop_order_history
                                (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, cancel_select, sh_add_price, sh_file, ct_status_claim)
                             values
                                ('{$od_id}', 1, '{$sh_memo}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','{$cancel_select}','{$sh_add_price}','{$sh_file}','반품완료'); ";
        
        sql_query($sql);
    }
} 

include_once(G5_PATH.'/head.sub.php');
?>

<script>
alert("<?php echo $cancel_select?> 처리됐습니다.");
opener.document.location.reload();
self.close();
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>