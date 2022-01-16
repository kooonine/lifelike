<?php

$temp_pay_date = explode('-', $rental['rt_rental_startdate']);
$rental_pay_date = $temp_pay_date[2] > 28 ? $temp_pay_date[2] - (($temp_pay_date[2] - 28) * -1) : $temp_pay_date[2];
$temp_use_date = floor((time() - strtotime($rental['rt_rental_startdate'])) / 60 / 60 / 24);


$html[] = "<input type='hidden' name='claim_type' value='5'>";

$html[] = "<div>계약 상품목록</div>
            <table>
			<tbody>
				<tr>
                    <th scope='row'>철회유형선택</th>
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
                                WHERE r.rt_id = '{$rental['rt_id']}' ";
    $sum = sql_fetch($sql_sum);
    if ($i == 0) {
        $html[] = "<tr>
        <td rowspan={$db_order->num_rows}><select id='claim-reaason' onchange='change_reason(this)'><option name='claim_reason' value='0'>일반철회</option><option name='claim_reason' value='1'>분실/파손</option><option name='claim_reason' value='2'>관리자 입력</option></select></td>";
    } else {
        $html[] = "<tr>";
    }
    $html[] = "<td>{$disp_od_id}
               <input type='hidden' name='cb_claim[]' value='{$order['od_id']}'></td>
               <td>{$image}</td>
               <td>" . stripslashes($order['it_name']) . "</td>
               <td>{$order['ct_qty']}</td>
               <td>" . number_format($opt_rental_price * $order['ct_item_rental_month']) . "원</td>
               <td>" . number_format($opt_rental_price) . "원<br/>({$order['ct_item_rental_month']}개월)</td>";
    if ($i == 0) $html[] = "<td rowspan={$db_order->num_rows}>" . number_format($sum['price'] * $order['ct_item_rental_month']) . "원<br />(" . number_format($sum['price']) . "원)</td>";
    $html[] = "<td>{$order['ct_status']}</td>
               <td>{$order['RFID']}</td>
			   </tr>";
}


$html[] = "</tbody></table>";

// 위약금 계산
$temp_claim_price = ($rental['rt_rental_price'] / 30) * ($rental['rt_month'] * 30 - $temp_use_date);

if ($temp_use_date < 365) {
    $claim_price = $temp_claim_price * 0.3;
} else if ($temp_use_date < 730) {
    $claim_price = $temp_claim_price * 0.2;
} else {
    $claim_price = $temp_claim_price * 0.1;
}
$claim_price = floor(($claim_price / 10)) * 10;

$html[] = "<div>계약 철회내용</div><table><tbody>";
$html[] = "<tr><th style='width: 109px;'>철회유형</th><td id='claim-reason'>일반철회</td></tr>";

$html[] = "<tr><th>총계약금액</th><td>" . number_format($rental['rt_rental_price'] * $rental['rt_month']) . "원</td></tr>";
$html[] = "<tr><th>총월이용료</th><td>" . number_format($rental['rt_rental_price']) . "원</td></tr>";
$html[] = "<tr><th>수납방법</th><td>{$rental['rt_settle_case']}</td></tr>";
$html[] = "<tr><th>카드사</th><td>{$rental['rt_bank_account']}</td></tr>";
$html[] = "<tr><th>정기결제일</th><td>" . ($rental_pay_date * 1) . "일</td></tr>";
$html[] = "<tr><th>수납횟수</th><td>{$rental['rt_payment_count']}회</td></tr>";
$html[] = "<tr><th>수납시작일</th><td>{$rental['rt_rental_startdate']}</td></tr>";
$html[] = "<tr><th>수납종료일</th><td>" . date("Y-m-d", strtotime("+" . $rental['rt_month'] - 1 . "month", strtotime($rental['rt_rental_startdate']))) . "</td></tr>";
$html[] = "<tr><th>예상위약금</th><td><input type='text' id='claim-price' data-price='{$claim_price}' name='claim_price' disabled value='" . number_format($claim_price) . "'></span>원</td></tr>";
$html[] = "<tr id='input-claim-reason' style='display: none;'><th>관리자입력</th><td><input type='text' id='claim-reason-text' name='claim_reason_text' style='width: 100%;'></td></tr>";

$html[] = "</tbody></table>";
// $return['result'] = true;

$return['body'] = implode('', $html);
$return['title'] = "철회요청(CS)";
$return['desc'] = "계약번호 : " . get_disp_id($rt_id, 'T');
$return['footer'] = "<button type='button' class='btn btn-default' data-dismiss='modal'>닫기</button><button type='submit' class='btn btn-success' id='btn-claim-cancel-rental'>철회요청</button>";
