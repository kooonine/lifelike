<?php
include_once('./_common.php');

$enc = new str_encrypt();
$return = array('title' => '장바구니 등록 회원 목록', 'desc' => '', 'footer' => '', 'body' => '', 'error' => '', 'result' => false);
$html = array();
$html[] = "<table><thead><tr>
<th></th>
<th>회원아이디</th>
<th>회원이름</th>
<th>장바구니 수량</th>
</tr></thead><tbody>";

$it_id = $_GET['it_id'];
$where_sql = $enc->decrypt($_GET['q']);
$sql_cart = sprintf("SELECT a.*,m.mb_name, SUM(a.ct_qty) AS mb_qty FROM lt_shop_cart a, lt_shop_item b, lt_member m WHERE a.mb_id != '' AND a.mb_id=m.mb_id AND a.it_id=%s AND %s GROUP BY a.mb_id", $it_id, $where_sql);
$db_cart = sql_query($sql_cart);

if ($db_cart) {
    for ($idx = 1; $cart = sql_fetch_array($db_cart); $idx++) {
        $html[] = "<tr>
               <td>{$idx}</td>
               <td><a onclick=openMemberPopup('{$cart['mb_id']}')>{$cart['mb_id']}</a></td>
               <td>{$cart['mb_name']}</td>
               <td>{$cart['mb_qty']}</td>
               </tr>";
    }

    $return['desc'] = $cart['it_name'];
}
$html[] = "</tbody></table>";

$return['body'] = implode('', $html);
$return['footer'] = "<button type='button' class='btn btn-default' data-dismiss='modal'>닫기</button>";
$error = "";
if (!empty($error)) {
    $return['result'] = true;
} else {
    $return['error'] = $error;
}


return_json($return);
