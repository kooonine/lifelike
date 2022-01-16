<?php
include_once('./_common.php');

$sql_rental = "SELECT * FROM {$g5['g5_shop_rental_table']} WHERE rt_id={$rt_id}";
$sql_rental_order = "SELECT * FROM {$g5['g5_shop_rental_order_table']} WHERE rt_id={$rt_id} ORDER BY rt_id";
$sql_rental_order_sum = "SELECT  FROM {$g5['g5_shop_rental_order_table']} WHERE rt_id={$rt_id} ORDER BY rt_id";

$db_rental = sql_query($sql_rental);
$db_rental_order = sql_query($sql_rental_order);

$rental = sql_fetch_array($db_rental);
$odcols = array(
    'od_id',
    'it_id',
    'it_name',
    'its_sap_code',
    'its_order_no',
    'ct_id',
    'it_id',
    'ct_price',
    'ct_qty',
    'ct_option',
    'ct_status',
    'cp_price',
    'ct_send_cost',
    'io_type',
    'io_price',
    'io_sapcode_color_gz',
    'rf_serial',
    'ct_status_claim',
    'ct_rental_price',
    'ct_item_rental_month'
);

$html = array();
$html[] = "<input type='hidden' name='claim_type' value={$type} />";
$html[] = "<table><thead><tr>
<th><input type='checkbox' class='cb-claim' onclick='claim_check_all()' /></th>
<th>상품주문번호</th>
<th>상품명</th>
<th>수량</th>
<th>상품금액</th>
<th>월이용료<br />(개월)</th>
<th>총 계약금액</th>
<th>주문상태</th>
</tr></thead><tbody>";

for ($i = 0; $row = sql_fetch_array($db_rental_order); $i++) {
    $odsql = "SELECT " . implode(',', $odcols) . ",(SELECT GROUP_CONCAT(od_sub_id) FROM {$g5['g5_shop_order_item_table']} WHERE lt_shop_order_item.ct_id = lt_shop_cart.ct_id ) AS od_sub_id
										FROM {$g5['g5_shop_cart_table']}
										WHERE od_id = '{$row['od_id']}'
										AND io_type = '0'
                                        ORDER BY io_type ASC, ct_id ASC";



    $db_order = sql_query($odsql);
    $order = sql_fetch_array($db_order);
    $option = get_item_option($order['it_id']);
    $str_option = sprintf("%s : %s", $option[$order['io_sapcode_color_gz']]['subject'], $option[$order['io_sapcode_color_gz']]['id']);

    $html[] = "<tr>
               <td><input type='checkbox' name='cb_claim[]' class='cb-claim' value='{$order['od_id']}' /></td>
               <td>" . get_disp_id($order['od_id']) . "</td>
               <td style='text-align: left !important;'><div>{$order['it_name']}</div><div style='font-size: 12px; line-height: 14px;'>{$option[$order['io_sapcode_color_gz']]['sapcode']}<br />{$str_option}</div></td>
               <td>{$order['ct_qty']}</td>
               <td>" . number_format($order['ct_rental_price'] * $order['ct_item_rental_month']) . "원</td>
               <td>" . number_format($order['ct_rental_price']) . "원<br />({$order['ct_item_rental_month']}개월)</td>";

    if ($i === 0) $html[] = "<td rowspan={$db_rental_order->num_rows}>" . number_format($rental['rt_cart_price']) . "원<br />(" . number_format($rental['rt_receipt_price']) . "원)</td>";
    $html[] = "<td>{$order['ct_status']}</td>
               </tr>";
}
$html[] = "</tbody></table>";

$return['body'] = implode('', $html);
$return['title'] = "계약확인";
$return['desc'] = "계약번호 : " . get_disp_id($rt_id, 'T');
$return['footer'] = "<button type='button' class='btn btn-default' data-dismiss='modal'>닫기</button> <button type='submit' class='btn btn-default'>계약확인</button>";
