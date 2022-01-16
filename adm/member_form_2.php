<?php
$where = array();

$where[] = " mb_id = '{$mb['mb_id']}' ";

$where[] = " ((od_type = 'R' and od_status in ('리스중','리스완료')) or ( od_type in ('L','K','S') ))";

if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_time";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           $sql_common
           order by $sort1 $sort2
           limit $from_record, $rows ";
           $result = sql_query($sql);
?><div>
    <a href="#" onclick="opener.location.href='./shop_admin/orderlist.php?sel_field=mb_id&search=<?php echo $mb['mb_id']?>';self.close();" class="btn btn_02">자세히보기</a>
</div>
<div class="tbl_head01 tbl_wrap">
    <table id="sodr_list">
    <caption>주문 내역 목록</caption>
    <thead>
    <tr>
    	<th scope="col" style="width: 70px;" >주문일시</th>
    	<th scope="col" style="width: 80px;"><a href="<?php echo title_sort("od_id", 1)."&amp;$qstr1"; ?>">주문번호</a></th>
    	<th scope="col">수량자명</th>
    	
    	<th scope="col">상품주문번호</th>
    	<th scope="col">상품코드</th>
    	<th scope="col">상품명</th>
    	<th scope="col">옵션항목</th>
    	<th scope="col">총결제금액</th>
    	
    	<th scope="col">주문상태</th>
    	<th scope="col">운송장정보</th>
    	
    	<th scope="col">결제수단</th>
    	
    	<th scope="col">클레임상태</th>
	</tr>
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        // 결제 수단
        $s_receipt_way = $s_br = "";
        if ($row['od_settle_case'])
        {
            $s_receipt_way = $row['od_settle_case'];
            $s_br = '<br />';

            // 간편결제
            if($row['od_settle_case'] == '간편결제') {
                switch($row['od_pg']) {
                    case 'lg':
                        $s_receipt_way = 'PAYNOW';
                        break;
                    case 'inicis':
                        $s_receipt_way = 'KPAY';
                        break;
                    case 'kcp':
                        $s_receipt_way = 'PAYCO';
                        break;
                    default:
                        $s_receipt_way = $row['od_settle_case'];
                        break;
                }
            }
        }
        else
        {
            $s_receipt_way = '결제수단없음';
            $s_br = '<br />';
        }

        if ($row['od_receipt_point'] > 0)
            $s_receipt_way .= $s_br."포인트";

        $mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');

        $od_cnt = 0;
        if ($row['mb_id'])
        {
            $sql2 = " select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$row['mb_id']}' ";
            $row2 = sql_fetch($sql2);
            $od_cnt = $row2['cnt'];
        }

        // 주문 번호에 device 표시
        $od_mobile = '';
        if($row['od_mobile'])
            $od_mobile = '(M)';

        // 주문번호에 - 추가
        /*switch(strlen($row['od_id'])) {
            case 16:
                $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'<br/>-'.substr($row['od_id'],8,6);
                break;
            default:
                $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,6).'<br/>-'.substr($row['od_id'],6);
                break;
        }*/
        $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'<br/>-'.substr($row['od_id'],8,6);

        // 주문 번호에 에스크로 표시
        $od_paytype = '';
        if($row['od_test'])
            $od_paytype .= '<span class="list_test">테스트</span>';

        if($default['de_escrow_use'] && $row['od_escrow'])
            $od_paytype .= '<span class="list_escrow">에스크로</span>';

        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

        $invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
        $delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

        $bg = 'bg'.($i%2);
        $td_color = 0;
        if($row['od_cancel_price'] > 0) {
            $bg .= 'cancel';
            $td_color = 1;
        }
        
        $odsql = " select it_id, od_sub_id, it_name, its_sap_code, its_order_no
                        ,ct_id, it_id, ct_price, ct_qty, ct_option, ct_status, cp_price, ct_send_cost, io_type, io_price, rf_serial
            from {$g5['g5_shop_cart_table']} 
            where od_id = '".$row['od_id']."'
                  and io_type = '0'
            order by io_type asc, ct_id asc
            ";
        
        $od = sql_query($odsql);
        $rowspan = sql_num_rows($od);
        
        for($k=0; $opt=sql_fetch_array($od); $k++) {
            
            // 상품이미지
            $image = get_it_image($opt['it_id'], 50, 50);
            
            
            if($opt['io_type'])
                $opt_price = $opt['io_price'];
            else
                $opt_price = $opt['ct_price'] + $opt['io_price'];
            
    ?>
    <tr>
		<?php if($k == 0) { ?>  
        <td rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문일시</label>
        	<?php echo substr($row['od_time'],2,8) ?><br/>
        	<?php echo substr($row['od_time'],11,8) ?>
        </td>
        <td headers="th_ordnum" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문번호</label>
        	<a href="#" onclick="opener.location.href='./shop_admin/orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>';self.close();"><?php echo $disp_od_id; ?></a>
        </td>
        <td headers="th_recvr" class="td_name" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">수령자명</label>
        	<?php echo get_text($row['od_b_name']); ?>
       	</td>
        <!-- td headers="th_ordnum" class="td_odrnum2">
            <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="orderitem"><?php echo $disp_od_id; ?></a>
        </td -->
        <?php } ?>
        
        <td headers="td_odrnum2" class="td_odrnum2">
        	<label class="sound_only">상품주문번호</label>
        	<?php echo $disp_od_id."-".$opt['od_sub_id'] ?>
        </td>
        <td headers="td_odrnum2" class="td_odrnum2">
        	<label class="sound_only">상품코드</label>
        	<?php echo $opt['it_id'] ?>
        	<?php echo ($opt['its_order_no'])?"<br/>(".$opt['its_order_no'].")":"" ?>
        	<?php echo ($opt['rf_serial'])?"<br/>(".$opt['rf_serial'].")":"" ?>
        </td>
        <td class="td_name">
        	<label class="sound_only">상품명</label>
        	<?php echo stripslashes($opt['it_name']); ?>
        </td>
        <td class="td_itopt_tl">
            <?php echo $opt['ct_option']; ?>
        </td>
        
        <?php if($k == 0) { ?>   
        <td class="td_num_right" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">총결제금액</label>
        	<?php echo number_format($row['od_receipt_price']); ?>
        </td>
        <?php } ?>
        
        <td headers="odrstat" class="odrstat">
        	<label class="sound_only">주문상태</label>
            <?php echo $opt['ct_status']; ?>
        </td>
        
        <td headers="delino" class="delino">
        	<label class="sound_only">운송장번호</label>
            <?php 
                echo ($row['od_invoice'] ? $row['od_invoice'] : '-').'<br/>';
                echo ($row['od_delivery_company'] ? $row['od_delivery_company'] : '-').'<br/>';
                echo (is_null_time($row['od_invoice_time']) ? '-' : substr($row['od_invoice_time'],0,16));
            ?>
        </td>
        
        <?php if($k == 0) { ?>   
        <td headers="odrpay" class="odrpay" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">결재수단</label>
            <input type="hidden" name="current_settle_case[<?php echo $i ?>]" value="<?php echo $row['od_settle_case'] ?>">
            <?php echo $s_receipt_way; ?>
        </td>
        <?php } ?>
        <td headers="odrstat" class="odrstat">
        	<label class="sound_only">클레임상태</label>
            <?php echo ($opt['ct_status_claim'] != '')?$opt['ct_status_claim']:'-' ?>
        </td>
        
    </tr>    
    <?php
        }
    }
    sql_free_result($result);
    if ($i == 0)
        echo '<tr><td colspan="19" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
