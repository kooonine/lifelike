<?php
$html[] = sprintf("<div class='modal-header'><h4 class='modal-title'>%s</h4></div>", $title);
$html[] = sprintf("<div class='modal-header'><h4 class='modal-title'>리스계약번호 : %s</h4></div>", $rt_id);
$html[] = "
<div class='modal-body'>
			<form name='procForm' id='procForm' method='post'>
			<div class='tbl_frm01 tbl_wrap'>
			<table>
			<tbody>
				<tr>
                    <th scope='row'><input type='checkbox' onClick='check_all(this.form)' /></th>
                    <th scope='row'>상품주문번호</th>
                    <th scope='row' colspan=2>상품명</th>
                    <th scope='row'>수량</th>
                    <th scope='row'>상품금액</th>
                    <th scope='row'>월이용료<br />(개월)</th>
                    <th scope='row'>총계약금액<br />(총월이용료)</th>
                    <th scope='row'>주문상태</th>
                    <th scope='row'>RFID</th>
                </tr>";
for ($i = 0; $order = sql_fetch_array($db_order); $i++) {
    $image = get_it_image($order['it_id'], 50, 50);
    $opt_rental_price = $order['ct_rental_price'] + $order['io_price'];
    $item_options = get_item_option($order['it_id']);
    $disp_od_id = $order['od_type'] . '-' . substr($order['od_id'], 0, 8) . '-' . substr($order['od_id'], 8);
    $sql_sum = "SELECT SUM((a.ct_rental_price + a.io_price) * a.ct_qty) AS price
                                FROM {$g5['g5_shop_rental_order_table']} AS r
			                    INNER JOIN {$g5['g5_shop_cart_table']} AS a ON r.od_id = a.od_id
                                WHERE  r.rt_id = '{$rental['rt_id']}' ";
    $sum = sql_fetch($sql_sum);
    $html[] = "<tr>
                    <td><input type='checkbox' value='{$order['od_id']}' /></td>
                    <td>{$disp_od_id}</td>
                    <td>{$image}</td>
                    <td>" . stripslashes($order['it_name']) . "</td>
                    <td>{$order['ct_qty']}</td>
                    <td>" . number_format($opt_rental_price * $order['ct_item_rental_month']) . "원</td>
                    <td>" . number_format($opt_rental_price) . "원<br/>({$order['ct_item_rental_month']}개월)</td>";
    if ($i == 0) $html[] = "<td rowspan={$db_order->num_rows}>" . number_format($sum['price'] * $order['ct_item_rental_month']) . "원<br />(" . number_format($sum['price']) . "원)</td>";
    $html[] = "<td>{$order['ct_status']}</td>
                    <td>{$order['rf_serial']}</td>
				</tr>";
}

$html[] = "</tbody></table></div></form></div>";
$html[] = "<div class='modal-footer'>
		  <button type='button' class='btn btn-default' data-dismiss='modal'>닫기</button>
          <button type='button' class='btn btn-success' id='btn-claim-cancel-part'>선택취소</button>
          <button type='button' class='btn btn-success' id='btn-claim-cancel-all'>전체취소</button>
        </div>";

$return['result'] = true;
