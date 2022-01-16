<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

if($change_status == "주소변경")
{
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');
        
    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
    
    // 주소변경
    $sql = " update {$g5['g5_shop_order_table']}
                    set od_b_name         = '$od_b_name',
                        od_b_tel          = '$od_b_tel',
                        od_b_hp           = '$od_b_hp',
                        od_b_zip1         = '$od_b_zip1',
                        od_b_zip2         = '$od_b_zip2',
                        od_b_addr1        = '$od_b_addr1',
                        od_b_addr2        = '$od_b_addr2',
                        od_b_addr3        = '$od_b_addr3',
                        od_b_addr_jibeon  = '$od_b_addr_jibeon'
                    where od_id = '$od_id' ";
    sql_query($sql);
    
} else if($change_status == "배송중") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id']){
        alert_close('주문정보가 존재하지 않습니다.');
    }
    
    $delivery['invoice'] = $od_invoice;
    $delivery['invoice_time'] = $od_invoice_time;
    $delivery['delivery_company'] = $od_delivery_company;
    
    order_update_delivery($od_id, $od['mb_id'], $change_status, $delivery);
    change_status($od_id, '상품준비중', '배송중');
    
    // SMS
    if($config['cf_sms_use'] == 'icode' && $_POST['send_sms'] && $default['de_sms_use5']) {
        $sms_contents = conv_sms_contents($od_id, $default['de_sms_cont5']);
        if($sms_contents) {
            $receive_number = preg_replace("/[^0-9]/", "", $od['od_hp']);	// 수신자번호
            $send_number = preg_replace("/[^0-9]/", "", $default['de_admin_company_tel']); // 발신자번호
            
            if($receive_number)
                $sms_messages[] = array('recv' => $receive_number, 'send' => $send_number, 'cont' => $sms_contents);
        }
    }
    
    // 메일
    if($config['cf_email_use'] && $_POST['od_send_mail'])
        include './ordermail.inc.php';
        
    // 에스크로 배송
    if($_POST['send_escrow'] && $od['od_tno'] && $od['od_escrow']) {
        $escrow_tno  = $od['od_tno'];
        $escrow_numb = $invoice;
        $escrow_corp = $delivery_company;
        
        include(G5_SHOP_PATH.'/'.$od['od_pg'].'/escrow.register.php');
    }
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