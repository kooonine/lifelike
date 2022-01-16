<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

unset($list);

$ttotal_price = 0;
$ttotal_point = 0;

//==============================================================================
// 메일보내기
//------------------------------------------------------------------------------
// Loop 배열 자료를 만들고
$sql = " select a.od_type, a.it_id, a.it_name, a.cp_price, a.ct_send_cost, a.it_sc_type
            , a.ct_id, a.ct_option, a.ct_qty, a.ct_price, a.ct_point, a.ct_status, a.io_type, a.io_price, a.ct_rental_price, a.ct_item_rental_month, a.ct_keep_month, a.ct_receipt_price
            , b.it_origin
        from lt_shop_cart a left join lt_shop_item b on ( a.it_id = b.it_id )
        where od_id = '$od_id'
        order by ct_id ";

$result = sql_query($sql);
for($m=0; $row=sql_fetch_array($result); $m++) {
    
    $opt_price = $row['ct_price'] + $row['io_price'];
    $sell_price = $opt_price * $row['ct_qty'];
    $point = $row['ct_point'] * $row['ct_qty'];
    $tot_point += $point;
    
    $opt_rental_price = $row['ct_rental_price'] + $row['io_price'];
    $sell_rental_price = $opt_rental_price * $row['ct_qty'];
    
    $list[$m]['g_dir']         = G5_URL;
    $list[$m]['it_id']         = $row['it_id'];
    $list[$m]['it_simg']       = get_it_image($row['it_id'], 80, 80);
    $list[$m]['it_name']       = $row['it_name'];
    $list[$m]['it_origin']     = $row['it_origin'];
    $list[$m]['it_opt']        = $row['ct_option'];
    $list[$m]['ct_option']     = $row['ct_option'];
    $list[$m]['qty']           = $row[ct_qty];
    $list[$m]['ct_status']     = $row[ct_status];
    $list[$m]['stotal_point']  = $tot_point;
    
    if($row['od_type'] == "O") {
        $list[$m]['ct_price']      = $opt_price;
        $list[$m]['stotal_price']  = $sell_price;
    } elseif($row['od_type'] == "R") {
        $list[$m]['ct_price']      = $opt_rental_price;
        $list[$m]['stotal_price']  = $sell_rental_price;
    } else {
        $list[$m]['stotal_price']  = $row['ct_receipt_price'];
    }
    
    $ttotal_price  += $list[$m]['stotal_price'];
    $ttotal_point  += $list[$m]['stotal_point'];
}
/*
$sql = " select a.it_id,
                a.it_name,
                a.ct_qty,
                a.ct_price,
                a.ct_point,
                b.it_sell_email,
                b.it_origin
           from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
          where a.od_id = '$od_id'
            and a.ct_select = '1'
          group by a.it_id
          order by a.ct_id asc ";
$result = sql_query($sql);
for ($m=0; $row=sql_fetch_array($result); $m++)
{
    // 합계금액 계산
    $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                    SUM(ct_point * ct_qty) as point,
                    SUM(ct_qty) as qty
                from {$g5['g5_shop_cart_table']}
                where it_id = '{$row['it_id']}'
                  and od_id = '$od_id'
                  and ct_select = '1' ";
    $sum = sql_fetch($sql);

    // 옵션정보
    $sql2 = " select ct_option, ct_qty, io_price
                from {$g5['g5_shop_cart_table']}
                where it_id = '{$row['it_id']}' and od_id = '$od_id' and ct_select = '1'
                order by io_type asc, ct_id asc ";
    $result2 = sql_query($sql2);
    
    $options_sms = '';
    $options = '';
    $options_ul = ' style="margin:0;padding:0"'; // ul style
    $options_li = ' style="padding:5px 0;list-style:none"'; // li style
    for($n=0; $row2=sql_fetch_array($result2); $n++) {
        if($n == 0)
            $options .= '<ul'.$options_ul.'>'.PHP_EOL;
        $price_plus = '';
        if($row2['io_price'] >= 0)
            $price_plus = '+';
        $options .= '<li'.$options_li.'>'.$row2['ct_option'].' ('.$price_plus.display_price($row2['io_price']).') '.$row2['ct_qty'].'개</li>'.PHP_EOL;
        $options_sms .= $row2['ct_option'].PHP_EOL;
    }

    if($n > 0)
        $options .= '</ul>';

    $list[$m]['g_dir']         = G5_URL;
    $list[$m]['it_id']         = $row['it_id'];
    $list[$m]['it_simg']       = get_it_image($row['it_id'], 70, 70);
    $list[$m]['it_name']       = $row['it_name'];
    $list[$m]['it_origin']     = $row['it_origin'];
    $list[$m]['it_opt']        = $options;
    $list[$m]['ct_option']      = $options_sms;
    $list[$m]['ct_price']      = $row['ct_price'];
    $list[$m]['qty']      = $sum['qty'];
    $list[$m]['stotal_price']  = $sum['price'];
    $list[$m]['stotal_point']  = $sum['point'];

    $ttotal_price  += $list[$m]['stotal_price'];
    $ttotal_point  += $list[$m]['stotal_point'];
}
*/
//------------------------------------------------------------------------------

// 배송비가 있다면 총계에 더한다
if ($od_send_cost)
    $ttotal_price += $od_send_cost;

// 추가배송비가 있다면 총계에 더한다
if ($od_send_cost2)
    $ttotal_price += $od_send_cost2;
?>