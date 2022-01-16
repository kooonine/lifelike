<?php
include_once('./_common.php');

$sql_rental = "SELECT * FROM {$g5['g5_shop_rental_table']} WHERE rt_id={$rt_id}";
$sql_rental_order = "SELECT * FROM {$g5['g5_shop_rental_order_table']} WHERE rt_id={$rt_id} ORDER BY rt_id";
$sql_rental_order_sum = "SELECT  FROM {$g5['g5_shop_rental_order_table']} WHERE rt_id={$rt_id} ORDER BY rt_id";

$db_rental = sql_query($sql_rental);
$db_rental_order = sql_query($sql_rental_order);

$rental = sql_fetch_array($db_rental);
$odcols = array(
	'c.od_id',
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
	'ct_item_rental_month',
	'o.od_delivery_company',
	'o.od_invoice'
);

$html = array();
$html[] = "<input type='hidden' name='claim_type' value={$type} />";
$html[] = "<table><thead><tr>
<th><input type='checkbox' class='cb-claim' onclick='claim_check_all()' /></th>
<th>상품주문번호</th>
<th>상품명</th>
<th>수량</th>
<th>주문상태</th>
<th>배송업체</th>
<th>송장번호</th>
</tr></thead><tbody>";

for ($i = 0; $row = sql_fetch_array($db_rental_order); $i++) {
	$odsql = "SELECT " . implode(',', $odcols) . ",(SELECT GROUP_CONCAT(od_sub_id) FROM {$g5['g5_shop_order_item_table']} WHERE c.ct_id = c.ct_id ) AS od_sub_id
										FROM {$g5['g5_shop_cart_table']} AS c JOIN {$g5['g5_shop_order_table']} AS o ON c.od_id=o.od_id
										WHERE c.od_id = '{$row['od_id']}'
										AND io_type = '0'
                                        ORDER BY io_type ASC, ct_id ASC";

	$db_order = sql_query($odsql);
	$order = sql_fetch_array($db_order);
	$option = get_item_option($order['it_id']);
	$str_option = sprintf("%s : %s", $option[$order['io_sapcode_color_gz']]['subject'], $option[$order['io_sapcode_color_gz']]['id']);
	$od_delivery_company = !empty($order['od_delivery_company']) ? $order['od_delivery_company'] : "";
	$od_invoice = !empty($order['od_invoice']) ? $order['od_invoice'] : "";

	$html[] = "<tr>
               <td><input type='checkbox' name='cb_claim[]' class='cb-claim' value='{$order['od_id']}' /></td>
               <td>" . get_disp_id($order['od_id']) . "</td>
               <td style='text-align: left !important;'><div>{$order['it_name']}</div><div style='font-size: 12px; line-height: 14px;'>{$option[$order['io_sapcode_color_gz']]['sapcode']}<br />{$str_option}</div></td>
               <td>{$order['ct_qty']}</td>
               <td>{$order['ct_status']}</td>
               <td><input type='text' name='od_delivery_company[{$order['od_id']}]' value='{$od_delivery_company}'></td>
               <td><input type='text' name='od_invoice[{$order['od_id']}]' value='{$od_invoice}'></td>
               </tr>";
}
$html[] = "</tbody></table>";

$return['body'] = implode('', $html);
$return['title'] = "계약확인";
$return['desc'] = "계약번호 : " . get_disp_id($rt_id, 'T');
$return['footer'] = "<button type='button' class='btn btn-default' data-dismiss='modal'>닫기</button> <button type='submit' class='btn btn-default'>배송정보변경</button>";
