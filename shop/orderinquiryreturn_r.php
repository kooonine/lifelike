<?php
include_once('./_common.php');
//리스 철회

// 세션에 저장된 토큰과 폼으로 넘어온 토큰을 비교하여 틀리면 에러
if ($token && get_session("ss_token") == $token) {
    // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
    set_session("ss_token", "");
} else {
    set_session("ss_token", "");
    alert("토큰 에러", G5_SHOP_URL);
}

$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' and mb_id = '{$member['mb_id']}' ");

if (!$od['od_id']) {
    alert("존재하는 주문이 아닙니다.");
}

$sql = " select SUM(IF(ct_status = '배송완료', 1, 0)) as od_count2,
                COUNT(*) as od_count1
            from {$g5['g5_shop_cart_table']}
            where od_id = '$od_id' ";
$ct = sql_fetch($sql);

$uid = md5($od['od_id'].$od['od_time'].$od['od_ip']);

if($act == "return")
{
    $count = count($_POST['chk']);
    if(!$count){
        alert('철회할 제품을 1개 이상 선택해 주세요.');
    }
    
    $cancel_select      = clean_xss_tags($cancel_select);
    $cancel_memo      = '['.$cancel_select.']'.clean_xss_tags($cancel_memo);
    if($ct['od_count2'] == $count)
    {
        
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '철회요청', ct_status_claim = '철회' where od_id = '$od_id' and ct_status = '배송완료' ");
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, cancel_select)
                         values
                            ('$od_id', 1, '[철회요청] ".$cancel_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회','$cancel_select'); ";
        sql_query($sql);
        
    }else {
        for ($i=0; $i<$count; $i++)
        {
            $k = $_POST['chk'][$i];
            $ct_id = $_POST['ct_id'][$k];
            $it_name = $_POST['it_name'][$k];
            $ct_qty = $_POST['ct_qty'][$k];
            
            //철회요청
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '철회요청', ct_status_claim = '철회' where ct_id = '$ct_id' ");
            
            $sql = " insert into lt_shop_order_history
                            (od_id, ct_id, it_name, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, cancel_select, ct_qty)
                         values
                            ('$od_id', '$ct_id', '$it_name', 1, '[철회요청] ".$cancel_memo."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','철회','$cancel_select', '$ct_qty'); ";
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
                    od_status         = '철회요청',
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
                    od_shop_memo = concat(od_shop_memo,\"\\n주문자 본인 철회 요청 - ".G5_TIME_YMDHIS." (철회이유 : {$cancel_memo})\")
                where od_id = '$od_id' ";
    sql_query($sql);
}
goto_url(G5_SHOP_URL."/orderinquiryview.php?od_id=$od_id&amp;uid=$uid");
?>