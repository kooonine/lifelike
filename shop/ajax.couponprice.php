<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

if (get_session("ss_direct"))
    $tmp_cart_id = get_session('ss_cart_direct');
else
    $tmp_cart_id = get_session('ss_cart_id');

if (get_cart_count($tmp_cart_id) == 0) // 장바구니에 담기
    alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', G5_SHOP_URL . '/cart.php');

$data = $_POST;

if (!$member['mb_id'])
    die(json_encode(array('error' => '회원 로그인 후 이용해 주십시오.')));

if (!$data['cp_id'])
    die(json_encode(array('error' => '쿠폰 번호를 입력해주세요.')));

$result = array();

$i_price       = (int) $data['od_price'];
$i_send_cost   = (int) $data['od_send_cost'];
$i_send_cost2  = (int) $data['od_send_cost2'];
$i_send_coupon = (int) $data['od_send_coupon'];
$i_temp_point  = (int) $data['od_temp_point'];

// 주문금액이 상이함
$sql_cart = "SELECT SUM((a.ct_price + a.io_price) * a.ct_qty) AS od_price,
        SUM((b.its_price + a.io_price) * a.ct_qty) AS before_price,
        COUNT(DISTINCT a.it_id) AS cart_count 
        FROM  lt_shop_cart AS a
              LEFT JOIN lt_shop_item_sub AS b ON a.it_id = b.it_id AND a.its_no = b.its_no 
        WHERE a.od_id = '$tmp_cart_id' 
        AND   a.ct_select = '1' ";

$row = sql_fetch($sql_cart);
$tot_ct_price = $row['od_price'];
$tot_before_price = $row['before_price'];

$cart_count = $row['cart_count'];
$tot_od_price = $tot_ct_price;
$tot_cp_price = 0;
$tot_it_cp_price = 0;
$tot_od_cp_price = 0;

// 쿠폰금액계산
if ($is_member) {

    // 상품쿠폰
    $it_cp_cnt = count($data['cp_id']);
    $arr_it_cp_prc = array();
    for ($i = 0; $i < $it_cp_cnt; $i++) {
        $cid = $data['cp_id'][$i];
        $it_id = $data['it_id'][$i];
        $sql_coupon = "SELECT cp_id, cp_method, cp_target, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum FROM {$g5['g5_shop_coupon_table']} WHERE cp_id = '$cid' and mb_id IN ( '{$member['mb_id']}', '전체회원' ) and cp_start <= '" . G5_TIME_YMD . "' and cp_end >= '" . G5_TIME_YMD . "' and cp_method IN ( 0, 1, 4 ) ";
        $cp = sql_fetch($sql_coupon);

        // 쿠폰 존재/사용 여부 확인
        if (!$cp['cp_id'] || is_used_coupon($member['mb_id'], $cp['cp_id'])) {
            continue;
        }

        // 상품/카테고리 할인 - TODO: 카테고리 할인 프로세스 설계
        if (in_array($cp['cp_method'], array(0, 1, 4))) {
            if (strpos($cp['cp_method'], $it_id) === false) continue;
        } else {
            if ($cp['cp_target'] != $it_id) {
                continue;
            }
        }

        // 상품금액
        $sql_ct = "SELECT SUM(IF(io_type = '1', io_price * ct_qty, (ct_price + io_price) * ct_qty)) AS sum_price FROM {$g5['g5_shop_cart_table']} WHERE od_id='{$tmp_cart_id}' AND it_id='{$it_id}' AND ct_select='1' ";
        $ct = sql_fetch($sql_ct);
        $item_price = $ct['sum_price'];

        if ($cp['cp_minimum'] > $item_price) {
            continue;
        }

        // 할인금액 계산
        $dc = 0;
        if ($cp['cp_type']) {
            $dc = floor(($item_price * ($cp['cp_price'] / 100)) / $cp['cp_trunc']) * $cp['cp_trunc'];
        } else {
            $dc = $cp['cp_price'];
        }

        if ($cp['cp_maximum'] && $dc > $cp['cp_maximum']) {
            $dc = $cp['cp_maximum'];
        }

        if ($item_price < $dc) {
            continue;
        }

        $tot_it_cp_price += $dc;
        $arr_it_cp_prc[$it_id] = $dc;
    }

    $tot_od_price -= $tot_it_cp_price;

    // 주문쿠폰
    if ($_POST['od_cp_id']) {
        $sql = "SELECT a.*, b.cm_use_price_type
                    FROM lt_shop_coupon AS a, lt_shop_coupon_mng AS b
                    WHERE a.cm_no = b.cm_no
                    AND a.cp_id = '{$_POST['od_cp_id']}' 
                    AND a.mb_id IN ( '{$member['mb_id']}', '전체회원' )
                    AND a.cp_method = '2'
                    AND a.cp_start <= '" . G5_TIME_YMD . "'
                    AND a.cp_end >= '" . G5_TIME_YMD . "'
                    AND a.cp_minimum <= '$tot_od_price' ";
        $cp = sql_fetch($sql);

        // 사용한 쿠폰인지
        $cp_used = is_used_coupon($member['mb_id'], $cp['cp_id']);

        $dc = 0;
        if (!$cp_used && $cp['cp_id'] && ($cp['cp_minimum'] <= $tot_od_price)) {

            if ($cp['cp_type']) {
                if ($cp['cm_use_price_type']) {
                    $dc = floor(($tot_od_price * ($cp['cp_price'] / 100)) / $cp['cp_trunc']) * $cp['cp_trunc'];
                } else {
                    $dc = floor(($tot_before_price * ($cp['cp_price'] / 100)) / $cp['cp_trunc']) * $cp['cp_trunc'];
                }
            } else {
                $dc = $cp['cp_price'];
            }

            if ($cp['cp_maximum'] && $dc > $cp['cp_maximum']) {
                $dc = $cp['cp_maximum'];
            }

            if ($tot_od_price < $dc) {
                die('Order coupon error.');
            }

            $tot_od_cp_price = $dc;
            $tot_od_price -= $tot_od_cp_price;
        }
    }

    $tot_cp_price = $tot_it_cp_price + $tot_od_cp_price;
}

if ((int) ($row['od_price'] - $tot_cp_price) !== $i_price) {
    die("Error.");
}
