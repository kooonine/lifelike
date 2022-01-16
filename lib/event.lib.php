<?php
class event
{
    protected $event;
    protected $item_set;
    protected $coupons;

    public function __construct($event = null)
    {
        if (!empty($event)) {
            $item_set = json_decode($event['cp_item_set'], TRUE);
            $this->item_set = $item_set;
            $this->event = $event;

            preg_match_all('/##.*##/', $event['cp_content'], $coupons_pc);
            preg_match_all('/##.*##/', $event['cp_content_mobile'], $coupons_mobile);

            $tmp_coupons = array_merge($coupons_pc[0], $coupons_mobile[0]);
            $this->coupons = $tmp_coupons;
        }
    }

    public function __set($item, $value)
    {
        $this->$item = $value;
    }

    public function print_coupon_html($coupon_set = array())
    {
        $coupon_count = 0;
        foreach ($coupon_set['coupon'] as $cp) {
            $coupon_count += $cp['count'];
        }
        $lastChar = mb_substr($coupon_set['subject'], mb_strlen($coupon_set['subject']) - 1, 1);
        if ($lastChar == '%' || $lastChar == '원') {
            $subject = mb_substr($coupon_set['subject'], 0, mb_strlen($coupon_set['subject']) - 1) . "<small>" . $lastChar . "</small>";
        }
        $download_info = urlencode(json_encode($coupon_set['coupon']));

        $db_coupon = sql_fetch("SELECT * FROM lt_shop_coupon WHERE cp_id='" . $coupon_set['coupon'][0]['id'] . "'");

        $html = '<div class="coupon-set coupon-set-color-' . $coupon_set['color'] . '">';
        $html .= '<div class="coupon-set-inner"><div class="coupon-set-inner-left">' . $coupon_set['title'] . '<div>' . $db_coupon['cp_subject'];


        if ($coupon_count > 1) {
            $html .= '<span class="coupon-set-count">' . $coupon_count . '장</span>';
        }

        $html .= '</div><div class="coupon-set-subject">' . $subject . '</div></div>';
        $html .= '<div class="coupon-set-inner-right"><span class="coupon-set-inner-right-deco"></span></div></div>';
        $html .= '<div class="coupon-set-desc">' . nl2br($coupon_set['description']) . '</div>';
        $html .= '<div><button type="button" class="btn btn-download-coupon" data-coupon="' . $download_info . '">쿠폰 다운</button></div></div>';

        return $html;
    }


    public function print_product_html($item_set_no = null, $reviewCheck = false)
    {
        $tmp_item_set = $this->item_set[$item_set_no];
        $tmp_item_list = array();

        if ($reviewCheck) {
            $html = include(G5_VIEW_PATH . "/review.list.event.php");
            return $html;
        }

        if (!empty($tmp_item_set["item"])) {
            $tmp_item_list[] = "a.it_id IN ({$tmp_item_set["item"]})";
            $itemNum = "ORDER BY FIELD(a.it_id, {$tmp_item_set["item"]})";
        }
        if (!empty($tmp_item_set["category"])) $tmp_item_list[] = "a.ca_id={$tmp_item_set["category"]}";

        if (!empty($tmp_item_list)) {
            $sql_items = "SELECT b.io_hoching,a.* FROM lt_shop_item a , lt_shop_item_option b  WHERE (a.it_id = b.it_id) AND b.io_use=1 AND a.it_use=1  AND a.it_total_size = 1 AND (" . implode(' OR ', $tmp_item_list) . ") GROUP BY a.it_id $itemNum";
            $db_items = sql_query($sql_items);
            $is_subject = $tmp_item_set['subject'];
            $html = include(G5_VIEW_PATH . "/product.list.event.php");
            return $html;
        }

        return false;
    }

    public function print_content($is_mobile = FALSE, $reviewChcek = FALSE)
    {
        $content = $is_mobile ? $this->event['cp_content_mobile'] : $this->event['cp_content'];

        foreach ($this->coupons as $cz_encoded) {
            preg_match("/##(.*)##/", $cz_encoded, $cz_matches);
            $coupon_set = json_decode(urldecode($cz_matches[1]), TRUE);
            $content = str_replace($cz_encoded, $this->print_coupon_html($coupon_set), $content);
        }
        foreach ($this->item_set as $is_id => $item_set) {
            if (strpos($content, "@@" . $is_id . "@@") !== false) {

                $content = str_replace("@@" . $is_id . "@@", $this->print_product_html($is_id,$reviewChcek), $content);
                unset($this->item_set[$is_id]);
            }
        }
        foreach ($this->item_set as $is_id => $item_set) {
            $content .= $this->print_product_html($is_id,$reviewChcek);
        }

        return $content;
    }

    public function special_print_product_html($item_set_no = null)
    {
        $tmp_item_set = $this->item_set[$item_set_no];
        $tmp_item_list = array();

        if (!empty($tmp_item_set["item"])) {
            $tmp_item_list[] = "a.it_id IN ({$tmp_item_set["item"]})";
            $itemNum = "ORDER BY FIELD(a.it_id, {$tmp_item_set["item"]})";
        }
        if (!empty($tmp_item_set["category"])) $tmp_item_list[] = "a.ca_id={$tmp_item_set["category"]}";

        if (!empty($tmp_item_list)) {
            $sql_items = "SELECT " . $item_set_no . " as area, b.io_hoching,a.* FROM lt_shop_item a , lt_shop_item_option b  WHERE (a.it_id = b.it_id) AND b.io_use=1 AND a.it_use=1 AND a.it_total_size = 1 AND (" . implode(' OR ', $tmp_item_list) . ") GROUP BY a.it_id $itemNum";
            $db_items = sql_query($sql_items);
            $is_subject = $tmp_item_set['subject'];
            $html = include(G5_VIEW_PATH . "/product.list.special.php");
            return $html;
        }

        return false;
    }

    public function special_print_content($is_mobile = FALSE)
    {
        $end_date = $this->event['cp_end_date'];
        $content = '<div class="special_content">';
        $content .= $is_mobile ? $this->event['cp_content_mobile'] : $this->event['cp_content'];
        $content .= '</div>';

        $content .= '<div id="count_down_special">';
        if ($end_date && $end_date > date("Y-m-d H:i:s")) { 
            $content .= '<div class="count_down_title">특가</div>';
        }
        if ($is_mobile) {
            $content .= '<div id="count_down_area_mo" class="count_down_area" ></div>';
        } else {
            $content .= '<div id="count_down_area" class="count_down_area" ></div>';
        }

        $content .= '</div>';

        foreach ($this->coupons as $cz_encoded) {
            preg_match("/##(.*)##/", $cz_encoded, $cz_matches);
            $coupon_set = json_decode(urldecode($cz_matches[1]), TRUE);
            $content = str_replace($cz_encoded, $this->print_coupon_html($coupon_set), $content);
        }
        foreach ($this->item_set as $is_id => $item_set) {
            if (strpos($content, "@@" . $is_id . "@@") !== false) {

                $content = str_replace("@@" . $is_id . "@@", $this->special_print_product_html($is_id), $content);
                unset($this->item_set[$is_id]);
            }
        }
        foreach ($this->item_set as $is_id => $item_set) {
            $content .= $this->special_print_product_html($is_id);
        }

        return $content;
    }
}
