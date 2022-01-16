<?php
class coupon
{
    private $it_id = "";
    private $couponId = "";
    private $item = "";
    private $coupon = "";
    public $error = "";
    public $types = array(
        0 => "제품할인",
        2 => "주문금액할인",
        3 => "배송비할인",
        4 => "브랜드할인",
        11 => "플러스쿠폰",
    );
    public $sort = array(
        0 => 0,
        4 => 1,
        11 => 2,
        3 => 99,
        2 => 10,
    );

    public function __construct()
    {
        if (!function_exists("sql_query")) {
            die("Module_coupon: Load DB connection first");
        }
        return $this;
    }

    public function setItem($it_id = "")
    {
        if (!empty($it_id)) {

            $sql_it = "SELECT * FROM lt_shop_item WHERE it_id='{$it_id}'";
            $it = sql_fetch($sql_it);

            if (!empty($it['it_id'])) {
                $sql_io = "SELECT * FROM lt_shop_item_option WHERE it_id='{$it_id}' AND io_use=1";
                $db_io = sql_query($sql_io);
                $temp_io = array();

                while (false != ($io = sql_fetch_array($db_io))) {
                    $temp_io[] = $io;
                }

                $it['OPTIONS'] = $temp_io;

                $this->it_id = $it_id;
                $this->item = $it;

                return $this->item;
            }
        }

        $this->error = "NOT_FOUND_ITEM";
        return false;
    }

    public function setCoupon($couponId = "")
    {
        if (!empty($couponId)) {

            $sqlCp = "SELECT * FROM lt_shop_coupon AS cp LEFT JOIN lt_shop_coupon_zone AS cz ON cp.cz_id = cz.cz_id WHERE cp.cp_id='{$couponId}'";
            $cp = sql_fetch($sqlCp);

            if (!empty($cp['cp_no'])) {
                $this->cp_id = $couponId;
                $this->coupon = $cp;

                return $this->coupon;
            }
        }

        $this->error = "NOT_FOUND_COUPON";
        return false;
    }

    public function getItemList()
    {
        if (!empty($this->coupon)) {
            $cp_target = explode(',', $this->coupon['cp_target']);
            return $cp_target;
        }

        $this->error = "SET_COUPON_FIRST";
        return false;
    }

    public function getCouponList($e = false)
    {
        if (!empty($this->item)) {
            // $sqlCoupons = "SELECT *, IF(cz_download_limit > 0, cz_download_limit - cz_download, 999) AS cp_remain FROM lt_shop_coupon_zone WHERE cp_promotion_check = 0 AND cz_start <= NOW() AND cz_end >= NOW() AND cp_method IN (0,2,4,11) AND cp_target LIKE '%{$this->item['it_id']}%' ORDER BY cp_method ASC, cp_price DESC, cp_minimum ASC";
            $sqlCoupons = "SELECT *, IF(cz_download_limit > 0, cz_download_limit - cz_download, 999) AS cp_remain FROM lt_shop_coupon_zone WHERE cp_promotion_check = 0 AND cz_start <= NOW() AND cz_end >= NOW() AND cp_method IN (0,2,4,11) AND cp_target LIKE '%{$this->item['it_id']}%' AND cp_rating != 1 ORDER BY cp_method ASC, cp_price DESC, cp_minimum ASC";
            if ($e) {
                $mTier = sql_fetch(" SELECT mb_tier FROM lt_member WHERE mb_id = '$e' ");
                $sqlCoupons = "SELECT *, IF(cz_download_limit > 0, cz_download_limit - cz_download, 999) AS cp_remain FROM lt_shop_coupon_zone WHERE cp_promotion_check = 0 AND cz_start <= NOW() AND cz_end >= NOW() AND cp_method IN (0,2,4,11) AND cp_target LIKE '%{$this->item['it_id']}%' AND (cp_rating != 1 OR (cp_rating = 1 AND cz_subject LIKE '%{$mTier['mb_tier']}%')) ORDER BY cp_method ASC, cp_price DESC, cp_minimum DESC";
            }
            $db_coupons = sql_query($sqlCoupons);

            $coupons = array();
            while (false != ($cp = sql_fetch_array($db_coupons))) {
                $coupons[] = $cp;
            }

            return $coupons;
        }

        $this->error = "SET_ITEM_FIRST";
        return false;
    }

    public function getMemberCoupon($mb_id, $all = false)
    {
        if (!empty($mb_id)) {
            // $sqlCoupons = "SELECT *, IF(cz_download_limit > 0, cz_download_limit - cz_download, 999) AS cp_remain FROM lt_shop_coupon_zone WHERE cz_start <= NOW() AND cz_end >= NOW() AND cp_method IN (0,2,4,11) AND cp_target LIKE '%{$this->item['it_id']}%'";
            // $sqlCoupons = "SELECT sc.*, scz.cp_promotion FROM lt_shop_coupon AS sc LEFT JOIN lt_shop_coupon_zone AS scz ON sc.cz_id = scz.cz_id WHERE sc.mb_id='{$mb_id}' AND sc.cp_start <= '".G5_TIME_YMD."' AND sc.cp_end >= '".G5_TIME_YMD."' AND sc.od_id='' ORDER BY sc.cp_method";
            $sqlCoupons = "SELECT * FROM lt_shop_coupon WHERE mb_id='{$mb_id}' AND cp_start <= '".G5_TIME_YMD."' AND cp_end >= '".G5_TIME_YMD."' AND od_id='' ORDER BY cp_method";
            if ($all) {
                $sqlCoupons = "SELECT * FROM lt_shop_coupon WHERE mb_id='{$mb_id}' ORDER BY cp_datetime";
                // $sqlCoupons = "SELECT sc.*, scz.cp_promotion FROM lt_shop_coupon AS sc LEFT JOIN lt_shop_coupon_zone AS scz ON sc.cz_id = scz.cz_id  WHERE sc.mb_id='{$mb_id}' ORDER BY sc.cp_datetime";
            }
            $db_coupons = sql_query($sqlCoupons);

            $coupons = array();
            while (false != ($cp = sql_fetch_array($db_coupons))) {
                $coupons[] = $cp;
            }

            return $coupons;
        }

        $this->error = "NOT_FOUND_MEMBER";
        return false;
    }
    public function getCampaignCoupon($mb_id, $all = false)
    {
        if (!empty($mb_id)) {
        $sqlCampaignCoupons = "SELECT * FROM lt_campaign WHERE cp_promotion != 0  AND cp_use = 1 AND cp_start_date <= '".G5_TIME_YMD."' AND cp_end_date >= '".G5_TIME_YMD."'";
        $campaign_coupons = sql_query($sqlCampaignCoupons);
        $campaignCoupons = array();
        while (false != ($cp = sql_fetch_array($campaign_coupons))) {
            $campaignCoupons[] = $cp;
        }
        return $campaignCoupons;
        }

        $this->error = "NOT_FOUND_MEMBER";
        return false;
    }

    public function getCouponInfo()
    {
        if (!empty($this->coupon)) {
            return $this->coupon;
        }

        $this->error = "SET_COUPON_FIRST";
        return false;
    }

    public function useCoupon($couponId, $orderId, $cartId = null)
    {
        if (!empty($couponId) && !empty($orderId)) {
            $sqlCouponLog = "SELECT * FROM lt_shop_coupon_log WHERE cp_id='{$couponId}'";
            $dbCouponLog = sql_query($sqlCouponLog);

            if ($dbCouponLog->num_rows > 0) {
                $this->error = "COUPON_USED_ALREADY";
                return false;
            }

            $sqlOrder = "SELECT * FROM lt_shop_order WHERE od_id='{$orderId}'";
            $order = sql_fetch($sqlOrder);
            $sqlCoupon = "SELECT * FROM lt_shop_coupon WHERE cp_id='{$couponId}'";
            $coupon = sql_fetch($sqlCoupon);

            $memberId = $order['mb_id'];
            $sqlSet = array();

            switch ($coupon['cp_method']) {
                case 0:     // 제품 쿠폰
                case 4:     // 브랜드 쿠폰
                case 11:    // 플러스 쿠폰
                    if (empty($cartId)) {
                        $this->error = "NOT_FOUND_CART_ID";
                        return false;
                    }

                    $sqlCart = "SELECT * FROM lt_shop_cart WHERE ct_id={$cartId}";
                    $cart = sql_fetch($sqlCart);
                    $cartPrice = ($cart['ct_price'] * $cart['ct_qty']) - $cart['cp_price'];

                    $discountPrice = $this->calcDiscountPrice($cartPrice, $coupon['cp_price'], $coupon['cp_type'] == 1, $coupon['cp_trunc'], $coupon['cp_maximum']);
                    $couponPrice = (int) $cart['cp_price'] + $discountPrice;

                    $sqlSet[] = "UPDATE lt_shop_cart SET cp_price={$couponPrice},cp_price_ori={$couponPrice} WHERE ct_id={$cartId}";
                    $sqlSet[] = "UPDATE lt_shop_coupon SET od_id={$orderId} WHERE cp_id='{$couponId}'";
                    $sqlSet[] = "INSERT INTO lt_shop_coupon_log SET cp_id='{$couponId}',mb_id='{$memberId}',od_id={$orderId},ct_id={$cartId},cp_price={$discountPrice},cl_datetime=NOW()";
                    break;
                case 3:     // 배송비 쿠폰
                    $discountPrice = $this->calcDiscountPrice($order['od_send_cost'], $coupon['cp_price'], $coupon['cp_type'] == 1, $coupon['cp_trunc'], $coupon['cp_maximum']);
                    $sqlSet[] = "UPDATE lt_shop_coupon SET od_id={$orderId} WHERE cp_id='{$couponId}'";
                    $sqlSet[] = "INSERT INTO lt_shop_coupon_log SET cp_id='{$couponId}',mb_id='{$memberId}',od_id={$orderId},cp_price={$discountPrice},cl_datetime=NOW()";
                    break;
                case 2:     // 주문서 쿠폰
                    $usedCouponPrice = sql_fetch("SELECT SUM(cp_price) AS price FROM lt_shop_coupon_log WHERE od_id={$orderId} GROUP BY od_id");
                    $discountPrice = $order['od_cart_price'] + $order['od_send_cost'] + $order['od_send_cost2'] - $order['od_receipt_price'] - $order['od_receipt_point'] - $usedCouponPrice['price'];

                    $sqlSet[] = "UPDATE lt_shop_coupon SET od_id={$orderId} WHERE cp_id='{$couponId}'";
                    $sqlSet[] = "INSERT INTO lt_shop_coupon_log SET cp_id='{$couponId}',mb_id='{$memberId}',od_id={$orderId},cp_price={$discountPrice},cl_datetime=NOW()";
                    break;
            }

            // dd($sqlSet);
            foreach ($sqlSet as $ss) {
                sql_query($ss);
            }

            return $this->updateOrderCouponPrice($orderId);
        }

        $this->error = "SET_COUPON_FIRST";
        return false;
    }

    public function returnCoupon($couponId)
    {
        $sqlCoupon = "SELECT * FROM lt_shop_coupon_log WHERE cp_id='{$couponId}'";
        $sqlDeleteCouponLog = "DELETE FROM lt_shop_coupon_log WHERE cp_id='{$couponId}'";
        $sqlReturnCoupon = "UPDATE lt_shop_coupon SET od_id=0 WHERE cp_id='{$couponId}'";
        $coupon = sql_fetch($sqlCoupon);
        sql_query($sqlDeleteCouponLog);
        sql_query($sqlReturnCoupon);

        return $this->updateOrderCouponPrice($coupon['od_id']);
    }

    public function getDiscountPrice($itPrice = 0)
    {
        if (!empty($this->item) && !empty($this->coupon)) {

            if (empty($itPrice)) {
                $io_price = $this->item['OPTIONS'][0]['io_price'];
                foreach ($this->item['OPTIONS'] as $io) {
                    if ($io['io_price'] < $io_price) $io_price = $io['io_price'];
                }

                $itPrice = $this->item['it_price'] - $this->item['it_discount_price'] + $io_price;
            }
        }

        $this->error = "SET_ITEM(COUPON)_FIRST";
        return false;
    }

    public function maxDiscountPrice()
    {
        $coupons = $this->getCouponList();

        if (!empty($coupons)) {
            $tmp_dc = array();
            $tmp_plus = array();

            foreach ($coupons as $cp) {
                if ($cp['cp_method'] == "11") {
                    $tmp_plus[] = $cp;
                    continue;
                }
                $dc = $this->calcDiscountPrice($this->item['it_price'], $cp['cp_price'], $cp['cp_type'] == "1", $cp['cp_trunc'], $cp['cp_maximum']);
                $cp['DISCOUNT_PRICE'] = $this->item['it_price'] - $dc;
                $tmp_dc[$dc] = $cp;
            }

            krsort($tmp_dc);
            $dcPrice = array_pop($tmp_dc);

            if (count($tmp_plus) > 0) {
                $tmp_plus_dc = array();

                foreach ($tmp_plus as $cpp) {
                    $dc = $this->calcDiscountPrice($dcPrice['DISCOUNT_PRICE'], $cpp['cp_price'], $cpp['cp_type'] == "1", $cpp['cp_trunc'], $cpp['cp_maximum']);
                    $cpp['DISCOUNT_PRICE'] = $dcPrice['DISCOUNT_PRICE'] - $dc;
                    $tmp_plus_dc[$dc] = $cpp;
                }

                krsort($tmp_plus_dc);
                $dcPrice = array_pop($tmp_plus_dc);
            }

            return $dcPrice;
        }

        return false;
    }

    public function calcDiscountPrice($itemPrice, $discountPrice, $isPercent = false, $trunc = 1, $maximum)
    {
        $dcPrice = $isPercent ? ($itemPrice / 100 * $discountPrice) : $discountPrice;
        $dcPrice = floor($dcPrice / ($trunc * 10)) * ($trunc * 10);

        if (!empty($maximum) && $dcPrice > $maximum) $dcPrice = $maximum;
        if ($itemPrice < $dcPrice) $dcPrice = $itemPrice;

        return $dcPrice;
    }

    public function updateOrderCouponPrice($od_id)
    {
        if (!empty($od_id)) {
            $orderCoupon = 0;
            $orderCartCoupon = 0;
            $orderSendCoupon = 0;
            $sqlOrderCouponLog = "SELECT cl.*,cp.cp_method FROM lt_shop_coupon_log AS cl LEFT JOIN lt_shop_coupon AS cp ON cl.cp_id=cp.cp_id WHERE cl.od_id={$od_id}";
            $dbOrderCouponLog = sql_query($sqlOrderCouponLog);

            while (false != ($cl = sql_fetch_array($dbOrderCouponLog))) {
                switch ($cl['cp_method']) {
                    case 0:     // 제품 쿠폰
                    case 4:     // 브랜드 쿠폰
                    case 11:    // 플러스 쿠폰
                        $orderCartCoupon += (int) $cl['cp_price'];
                        break;
                    case 3:     // 배송비 쿠폰
                        $orderSendCoupon += (int) $cl['cp_price'];
                        break;
                    case 2:     // 주문서 쿠폰
                        $orderCoupon += (int) $cl['cp_price'];
                        break;
                }
            }

            $sqlOrderCoupon = "UPDATE lt_shop_order
            SET od_coupon={$orderCoupon},
                od_send_coupon={$orderSendCoupon},
                od_cart_coupon={$orderCartCoupon}
            WHERE od_id={$od_id}";
            return sql_query($sqlOrderCoupon);
        }

        $this->error = "NOT_FOUND_ORDER";
        return false;
    }
}
