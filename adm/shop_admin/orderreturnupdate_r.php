<?php
$sub_menu = '400400';
include_once('./_common.php');
include_once(G5_LIB_PATH.'/samjin.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

if($change_status == "철회승인")
{
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '철회수거중' where od_id = '$od_id' and ct_status = '철회요청' ");
    
    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
    
    // 철회승인
    $sql = " update {$g5['g5_shop_order_table']}
                    set od_status         = '철회수거중',
                        od_hope_date      = '$od_hope_date',
                        od_b_name         = '$od_b_name',
                        od_b_tel          = '$od_b_tel',
                        od_b_hp           = '$od_b_hp',
                        od_b_zip1         = '$od_b_zip1',
                        od_b_zip2         = '$od_b_zip2',
                        od_b_addr1        = '$od_b_addr1',
                        od_b_addr2        = '$od_b_addr2',
                        od_b_addr3        = '$od_b_addr3',
                        od_b_addr_jibeon  = '$od_b_addr_jibeon',
                        od_shop_memo = concat(od_shop_memo,\"\\n관리자 철회승인 - ".G5_TIME_YMDHIS." \")
                    where od_id = '$od_id' ";
    sql_query($sql);
    
    $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '[철회승인] ', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회승인'); ";
    sql_query($sql);
        
} else if($change_status == "철회거부") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    $ct_status = '배송완료';
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '철회요청' ");

    // 철회거부
    $sql = " update {$g5['g5_shop_order_table']}
                set od_status = '$ct_status',
                    od_status_claim   = '',
                    od_shop_memo = concat(od_shop_memo,\"\\n관리자 철회거부 -".G5_TIME_YMDHIS." (거부사유 : {$mod_memo})\")
                where od_id = '$od_id' ";
    sql_query($sql);
    
    $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                     values
                        ('$od_id', 1, '[철회거부] ".$mod_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회거부'); ";
    sql_query($sql);
    
} else if($change_status == "철회취소") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    $ct_status = '배송완료';
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '철회요청' ");
    
    // 철회거부
    $sql = " update {$g5['g5_shop_order_table']}
            set od_status = '$ct_status',
                od_status_claim   = '',
                od_shop_memo = concat(od_shop_memo,\"\\n관리자 철회취소 -".G5_TIME_YMDHIS." (철회사유 : {$mod_memo})\")
            where od_id = '$od_id' ";
    sql_query($sql);
    
    $sql = " insert into lt_shop_order_history
                    (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                 values
                    ('$od_id', 1, '[철회취소] ".$mod_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회취소'); ";
    sql_query($sql);
        
} else if($change_status == "철회완료") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id']){
        alert_close('주문정보가 존재하지 않습니다.');
    }
        
    //환불처리   
    include_once(G5_SHOP_PATH.'/settle_lg2.inc.php');
        
    $tax_mny = preg_replace('/[^0-9]/', '', $_POST['tax_mny']);
    
    /*
     * [결제 부분취소 요청 페이지]
     *
     * LG유플러스으로 부터 내려받은 거래번호(LGD_TID)를 가지고 취소 요청을 합니다.(파라미터 전달시 POST를 사용하세요)
     * (승인시 LG유플러스으로 부터 내려받은 PAYKEY와 혼동하지 마세요.)
     */
    
    $LGD_TID              		= $od['od_tno'];			  		                            //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)
    $LGD_CANCELAMOUNT     		= (int)$tax_mny;                                                //부분취소 금액
    $LGD_REMAINAMOUNT     		= (int)$od['od_receipt_price'] - (int)$od['od_refund_price'];   //취소전 남은금액
    
    $LGD_CANCELTAXFREEAMOUNT    = (int)$free_mny;                                               //면세대상 부분취소 금액 (과세/면세 혼용상점만 적용)
    $LGD_CANCELREASON     		= $mod_memo;                                                    //취소사유
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
        
        if( '0000' == $xpay->Response_Code() || $default['de_card_test']) {
        
            $ct_status = '철회완료';
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status' where od_id = '$od_id' and ct_status = '수거완료' ");
            
            // 환불금액기록
            $tno = $xpay->Response("LGD_TID", 0);
            $mod_mny = (int)$tax_mny + (int)$free_mny;
            
            // 철회거부
            $sql = " update {$g5['g5_shop_order_table']}
                    set od_refund_price = od_refund_price + '$mod_mny',
                        od_status = '$ct_status',
                        od_shop_memo = concat(od_shop_memo,\"\\n관리자 철회완료 -".G5_TIME_YMDHIS." \")
                    where od_id = '$od_id' 
                      and od_tno = '$tno' ";
            sql_query($sql);
            
            // 미수금 등의 정보 업데이트
            $info = get_order_info($od_id);
            
            $sql = " update {$g5['g5_shop_order_table']}
                    set od_misu     = '{$info['od_misu']}',
                        od_tax_mny  = '{$info['od_tax_mny']}',
                        od_vat_mny  = '{$info['od_vat_mny']}',
                        od_free_mny = '{$info['od_free_mny']}'
                    where od_id = '$od_id' ";
            sql_query($sql);
            
            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '[철회완료] ', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회완료'); ";
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
        
        alert('철회 환불 요청이 실패하였습니다.\\n\\n'.$xpay->Response_Code().' : '.$xpay->Response_Msg());
    }
    
    //RSP             SMALLINT       취소시 귀책구분  1-리탠다드 귀책  2-고객귀책
    //리스 철회완료
    $RSP = "1";
    
    $rt = sql_fetch("select * from lt_shop_order_history where od_id = '$od_id' and ct_status_claim = '철회' limit 1");
    if($rt['cancel_select'] == "색상및사이즈변경" || $rt['cancel_select'] == "다른상품잘못주문") {
        //고객귀책
        $RSP = "2";
    }
    SM_ADD_RENTAL_CANCEL($od_id, $RSP);
    
    include(G5_SHOP_PATH.'/ordermail1.inc.php');
    $arr_change_data = array();
    $arr_change_data['고객명'] = $od['od_name'];
    $arr_change_data['이름'] = $od['od_name'];
    $arr_change_data['보낸분'] = $od['od_name'];
    $arr_change_data['받는분'] = $od['od_b_name'];;
    $arr_change_data['주문번호'] = $od_id;
    $arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
    $arr_change_data['결제금액'] = number_format($od_receipt_price);
    $arr_change_data['회원아이디'] = $od['mb_id'];
    $arr_change_data['회사명'] = $default['de_admin_company_name'];
    $arr_change_data["아이디"] = $od['mb_id'];
    $arr_change_data["고객명(아이디)"] = $od['od_name']."(".$od['mb_id'].")";
    $arr_change_data["od_list"] = $list;
    $arr_change_data['od_type'] = $od['od_type'];
    $arr_change_data['od_id'] = $od_id;
    
    msg_autosend('리스', '철회 완료', $od['mb_id'], $arr_change_data);
} 
else if($change_status == "해지결제요청")
{
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '해지결제요청' where od_id = '$od_id' and ct_status = '해지요청' ");
    
    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
    
    $od_send_cost2 = preg_replace('/[^0-9]/', '', $od_send_cost2);
    $od_penalty = preg_replace('/[^0-9]/', '', $od_penalty);
    // 철회승인
    $sql = " update {$g5['g5_shop_order_table']}
                set od_status         = '해지결제요청',
                    od_hope_date      = '$od_hope_date',
                    od_b_name         = '$od_b_name',
                    od_b_tel          = '$od_b_tel',
                    od_b_hp           = '$od_b_hp',
                    od_b_zip1         = '$od_b_zip1',
                    od_b_zip2         = '$od_b_zip2',
                    od_b_addr1        = '$od_b_addr1',
                    od_b_addr2        = '$od_b_addr2',
                    od_b_addr3        = '$od_b_addr3',
                    od_b_addr_jibeon  = '$od_b_addr_jibeon',
                    od_send_cost2     = '$od_send_cost2',
                    od_penalty        = '$od_penalty',
                    od_shop_memo = concat(od_shop_memo,\"\\n관리자 해지결제요청 - ".G5_TIME_YMDHIS." \")
                where od_id = '$od_id' ";
    sql_query($sql);
    
    $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                     values
                        ('$od_id', 1, '[해지결제요청] ', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','해지결제요청'); ";
    sql_query($sql);
}
else if($change_status == "해지수거중")
{
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '해지수거중' where od_id = '$od_id' and ct_status in ('해지요청','해지결제요청') ");
    
    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
    
    $od_send_cost2 = preg_replace('/[^0-9]/', '', $od_send_cost2);
    $od_penalty = preg_replace('/[^0-9]/', '', $od_penalty);
    
    // 해지승인
    $sql = " update {$g5['g5_shop_order_table']}
                set od_status         = '해지수거중',
                    od_hope_date      = '$od_hope_date',
                    od_b_name         = '$od_b_name',
                    od_b_tel          = '$od_b_tel',
                    od_b_hp           = '$od_b_hp',
                    od_b_zip1         = '$od_b_zip1',
                    od_b_zip2         = '$od_b_zip2',
                    od_b_addr1        = '$od_b_addr1',
                    od_b_addr2        = '$od_b_addr2',
                    od_b_addr3        = '$od_b_addr3',
                    od_b_addr_jibeon  = '$od_b_addr_jibeon',
                    od_send_cost2     = '$od_send_cost2',
                    od_penalty        = '$od_penalty',
                    od_shop_memo = concat(od_shop_memo,\"\\n관리자 해지승인 - ".G5_TIME_YMDHIS." \")
                where od_id = '$od_id' ";
    sql_query($sql);
    
    $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                     values
                        ('$od_id', 1, '[해지승인] ', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','해지승인'); ";
    sql_query($sql);
    
} else if($change_status == "해지거부") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
        $ct_status = '리스중';
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '해지요청' ");
        
        // 해지거부
        $sql = " update {$g5['g5_shop_order_table']}
                set od_status = '$ct_status',
                    od_status_claim   = '',
                    od_shop_memo = concat(od_shop_memo,\"\\n관리자 해지거부 -".G5_TIME_YMDHIS." (거부사유 : {$mod_memo})\")
                where od_id = '$od_id' ";
        sql_query($sql);
        
        $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                     values
                        ('$od_id', 1, '[해지거부] ".$mod_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','해지거부'); ";
        sql_query($sql);
        
} else if($change_status == "해지취소") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
        $ct_status = '리스중';
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '해지요청' ");
        
        // 해지거부
        $sql = " update {$g5['g5_shop_order_table']}
            set od_status = '$ct_status',
                od_status_claim   = '',
                od_shop_memo = concat(od_shop_memo,\"\\n관리자 해지취소 -".G5_TIME_YMDHIS." (해지사유 : {$mod_memo})\")
            where od_id = '$od_id' ";
        sql_query($sql);
        
        $sql = " insert into lt_shop_order_history
                    (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                 values
                    ('$od_id', 1, '[해지취소] ".$mod_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','해지취소'); ";
        sql_query($sql);
        
}else {
    
    if(!trim($mod_memo))
        alert('사유를 입력해 주십시오.');
    
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
    
    $sql = " select SUM(IF(ct_status = '배송완료', 1, 0)) as od_count2,
                COUNT(*) as od_count1
            from {$g5['g5_shop_cart_table']}
            where od_id = '$od_id' ";
    $ct = sql_fetch($sql);
    
    $count = count($_POST['chk']);
    
    if(!$count)
        alert('철회할 제품을 1개 이상 선택해 주세요.');
    
    if($ct['od_count2'] == $count)
    {
        
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '철회수거중', ct_status_claim = '철회' where od_id = '$od_id' and ct_status = '배송완료' ");
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '[철회처리CS] ".$mod_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회'); ";
        sql_query($sql);
        
    }else {
        for ($i=0; $i<$count; $i++)
        {
            $k = $_POST['chk'][$i];
            $ct_id = $_POST['ct_id'][$k];
            $it_name = $_POST['it_name'][$k];
            $ct_qty = $_POST['ct_qty'][$k];
            
            //철회요청
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '철회수거중', ct_status_claim = '철회' where ct_id = '$ct_id' ");
            
            $sql = " insert into lt_shop_order_history
                            (od_id, ct_id, it_name, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, ct_qty)
                         values
                            ('$od_id', '$ct_id', '$it_name', 1, '[철회처리CS] ".$mod_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회','$ct_qty'); ";
            sql_query($sql);
        }
    }
    
    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
        
    
    // 철회요청
    $sql = " update {$g5['g5_shop_order_table']}
                    set od_status_claim   = '철회',
                        od_status_claim_date = '".G5_TIME_YMDHIS."',
                        od_status         = '철회수거중',
                        od_hope_date      = '$od_hope_date',
                        od_send_cost2     = '$od_send_cost2',
                        od_b_name         = '$od_b_name',
                        od_b_tel          = '$od_b_tel',
                        od_b_hp           = '$od_b_hp',
                        od_b_zip1         = '$od_b_zip1',
                        od_b_zip2         = '$od_b_zip2',
                        od_b_addr1        = '$od_b_addr1',
                        od_b_addr2        = '$od_b_addr2',
                        od_b_addr3        = '$od_b_addr3',
                        od_b_addr_jibeon  = '$od_b_addr_jibeon',
                        od_shop_memo = concat(od_shop_memo,\"\\n관리자 철회처리CS - ".G5_TIME_YMDHIS." (철회이유 : {$mod_memo})\")
                    where od_id = '$od_id' ";
    sql_query($sql);
    
    $change_status = '철회';
}

include_once(G5_PATH.'/head.sub.php');
?>

<script>
alert("<?php echo $change_status?> 처리됐습니다.");
opener.document.location.reload();
self.close();
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>