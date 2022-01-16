<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

unset($list);

$ttotal_price = 0;
$ttotal_point = 0;

//==============================================================================
// 주문확인 발송데이터 생성
//------------------------------------------------------------------------------
// Loop 배열 자료를 만들고

if (isset($rt_id)) {
    $sql = "SELECT a.*, b.*
        FROM {$g5['g5_shop_rental_order_table']} AS ro
        LEFT JOIN {$g5['g5_shop_cart_table']} a ON ro.od_id=a.od_id
        LEFT JOIN {$g5['g5_shop_item_table']} b ON a.it_id=b.it_id
        WHERE ro.rt_id='{$rt_id}'
        ORDER BY ct_id";
} else {
    $sql = " select a.od_type, a.it_id, a.it_name, a.cp_price, a.ct_send_cost, a.it_sc_type
            , a.ct_id, a.ct_option, a.ct_qty, a.ct_price, a.ct_point, a.ct_status, a.io_type, a.io_price, a.ct_rental_price, a.ct_item_rental_month, a.ct_keep_month, a.ct_receipt_price
            , b.it_origin
        from lt_shop_cart a left join lt_shop_item b on ( a.it_id = b.it_id )
        where od_id = '$od_id'
        order by ct_id ";
}
$result = sql_query($sql);

for ($m = 0; $row = sql_fetch_array($result); $m++) {

    $opt_price = $row['ct_price'] + $row['io_price'];
    $sell_price = $opt_price * $row['ct_qty'];
    $point = $row['ct_point'] * $row['ct_qty'];
    $tot_point += $point;

    $opt_rental_price = $row['ct_rental_price'] + $row['io_price'];
    $sell_rental_price = $opt_rental_price * $row['ct_qty'];

    $list[$m]['g_dir']         = G5_URL;
    $list[$m]['it_simg']       = get_it_image($row['it_id'], 80, 80);
    $list[$m]['it_id']         = $row['it_id'];
    $list[$m]['it_name']       = $row['it_name'];
    $list[$m]['it_origin']     = $row['it_origin'];
    $list[$m]['it_opt']        = $row['ct_option'];
    $list[$m]['ct_option']     = $row['ct_option'];
    $list[$m]['qty']           = $row['ct_qty'];
    $list[$m]['ct_status']     = $row['ct_status'];
    $list[$m]['stotal_point']  = $tot_point;

    if ($row['od_type'] == "O") {
        $list[$m]['ct_price']      = $opt_price;
        $list[$m]['stotal_price']  = $sell_price;
    } elseif ($row['od_type'] == "R") {
        $list[$m]['ct_price']      = $opt_rental_price;
        $list[$m]['stotal_price']  = $sell_rental_price;
    } else {
        $list[$m]['stotal_price']  = $row['ct_receipt_price'];
    }

    $ttotal_price  += $list[$m]['stotal_price'];
    $ttotal_point  += $list[$m]['stotal_point'];
}

// 배송비가 있다면 총계에 더한다
if ($od_send_cost)
    $ttotal_price += $od_send_cost;

// 추가배송비가 있다면 총계에 더한다
if ($od_send_cost2)
    $ttotal_price += $od_send_cost2;
