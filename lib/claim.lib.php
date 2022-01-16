<?php
class lt_claim
{
    public $order;

    private $_coupons = array();
    private $_actionStatus = array(
        'cancel' => array('결제완료', '상품준비중'),
        'change' => array('배송중', '배송완료', '리스중'),
        'return' => array('배송중', '배송완료', '리스중'),
    );

    public function __construct()
    {
        if (!function_exists("sql_query")) {
            die("Module_claim: Load DB connection first");
        }

        return $this;
    }
    public function setOrder($od_id)
    {
        if (!empty($od_id)) {

            $sql_order = "SELECT * FROM lt_shop_order AS o
            LEFT JOIN lt_shop_cart AS c ON o.od_id=c.od_id
            LEFT JOIN lt_shop_order_item AS oi ON c.ct_id=oi.ct_id
            WHERE o.od_id='{$od_id}' GROUP BY c.ct_id ORDER BY c.ct_id";

            $db_order = sql_query($sql_order);
            if ($db_order->num_rows > 0) {
                $tmp_order = array();
                $tmp_coupons = array();
                while (false != ($orderCartItem = sql_fetch_array($db_order))) {
                    if (empty($tmp_order)) {
                        $tmp_order['id']            = $orderCartItem["od_id"];
                        $tmp_order['count']         = $orderCartItem["od_cart_count"];
                        $tmp_order['cart_price']    = $orderCartItem["od_cart_price"];
                        $tmp_order['receipt_price'] = $orderCartItem["od_receipt_price"];
                        $tmp_order['cancel_price']  = $orderCartItem["od_cancel_price"];
                        $tmp_order['receipt_point'] = $orderCartItem["od_receipt_point"];
                        $tmp_order['refund_price']  = $orderCartItem["od_refund_price"];
                        $tmp_order['payment_type']  = $orderCartItem["od_settle_case"];
                        $tmp_order['payment_info']  = $orderCartItem["od_bank_account"];
                        $tmp_order['receipt_time']  = $orderCartItem["od_receipt_time"];
                        $tmp_order['coupon_price']  = $orderCartItem["od_coupon"];
                        $tmp_order['memo']          = $orderCartItem["od_shop_memo"];
                        $tmp_order['ITEM']          = array();
                    }

                    $tmp_order['ITEM'][$orderCartItem['ct_id']] = $orderCartItem;
                }

                $sql_coupons = "SELECT * FROM lt_shop_coupon_log AS cl LEFT JOIN lt_shop_coupon AS cp ON cl.cp_id= cp.cp_id WHERE cl.od_id='{$tmp_order['id']}'";
                $db_coupons = sql_query($sql_coupons);

                if ($db_coupons->num_rows > 0) {
                    while (false != ($coupon = sql_fetch_array($db_coupons))) {
                        $tmp_coupons[] = $coupon;
                    }
                    $this->_coupons = $tmp_coupons;
                }
                $this->order = $tmp_order;

                return $this->order;
            }
        }

        return false;
    }
    public function cancelPoint()
    {
    }
    public function cancelCoupon()
    {
    }
    public function cancelSamjin()
    {
    }
    public function cancelPayment()
    {
    }
    public function changeStatus()
    {
    }
    public function calcDeliveriCost()
    {
    }
    public function createInvoice()
    {
    }
    public function simulate($action, $ct_id = array())
    {
        if (!empty($action) && !empty($ct_id)) {
            if (!is_array($ct_id)) $ct_id = array($ct_id);

            $ableStatus = $this->_actionStatus[$action];
            $tmp_new = $this->order;
            $tmp_new_item = array();
            $tmp_diff = array();
            $return = array(
                "ORDER" => $this->order,
                "NEW" => array(),
                "DIFF" => array(),
                "RESULT" => false,
                "MSG" => ""
            );

            switch ($action) {
                case "cancel":
                    foreach ($ct_id as $cid) {
                        if (isset($this->order['ITEM'][$cid]) && in_array($this->order['ITEM'][$cid]['ct_status'], $ableStatus)) {
                            $cartOrderItem = $this->order['ITEM'][$cid];

                            // 쿠폰 취소
                            if ($cartOrderItem['cp_price'] > 0) {
                            }

                            // 주문상태 변경
                            $cartOrderItem['ct_status_claim'] = "주문취소";
                            $tmp_new_item[] = $cartOrderItem;
                        }
                    }

                    $tmp_new["ITEM"] = $tmp_new_item;
                    // 장바구니 쿠폰 재계산
                    // 적립금 재계산

                    break;
                case "change":
                    break;
                case "return":
                    break;
            }
        }

        return false;
    }
    public function exec($action)
    {
        if (!empty($action)) {
            $calcOrder = $this->simulate($action);

            if ($calcOrder !== false) {
            }
        }

        return false;
    }
}
