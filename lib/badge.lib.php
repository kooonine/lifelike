<?php
class badge
{
    protected $item = array();
    public $badgeHtml = "";
    public $leaseHtml = "";
    public $innerHtml = "";
    public $photoHtml = "";
    public $html = "";
    public $max = 4;

    protected $view_it_name = true;
    protected $view_it_price = true;
    protected $view_it_basic = true;
    protected $view_wish = true;
    protected $view_new = false;
    protected $view_sale = false;
    protected $view_event = false;
    protected $view_only = false;
    protected $view_best = false;
    protected $view_lease = false;

    public function __construct($item = array())
    {
        if (!empty($item)  && is_array($item)) $this->item = $item;

        return $this;
    }

    public function __set($name, $value)
    {
        if ($name === 'item') {
            if (!isset($value['it_view_list_items']) || !isset($value['it_time']) || !isset($value['ca_id'])) {
                die("Parameter not found");
            } else {
                $this->item = $value;
            }
        }
    }

    private function _makeBadge()
    {
        if (!empty($this->item["it_view_list_items"])) {
            $it_view_list_items = ',' . $this->item["it_view_list_items"] . ',';

            $this->view_it_basic = (preg_match("/,한줄설명,/i", $it_view_list_items));
            $this->view_wish = (preg_match("/,좋아요,/i", $it_view_list_items));
            $this->view_new = (preg_match("/,신상품,/i", $it_view_list_items));
            $this->view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
            // $this->view_event = (preg_match("/,이벤트블릿,/i", $it_view_list_items));
            // $this->view_only = (preg_match("/,단독블릿,/i", $it_view_list_items));
            // $this->view_best = (preg_match("/,인기블릿,/i", $it_view_list_items));
        } else {
            // $reg_time = gap_time(strtotime($this->item['it_time']), time());
            // $this->view_new = ($reg_time['days'] <= 7);
        }

        $this->view_lease = strpos($this->item['ca_id'], "1020")  === 0 ? true : false;

        $badge_bg_html = "";
        $badge_text_html = "";
        $badge_count = 0;

        if ($this->view_new && $badge_count < $this->max) {
            $badge_bg_html .= "<span class='badge_new'></span>";
            $badge_text_html .= "<span class='badge_new'>NEW</span>";
            $badge_count++;
        }
        if ($this->view_sale && $badge_count < $this->max) {
            $badge_bg_html .= "<span class='badge_sale'></span>";
            $badge_text_html .= "<span class='badge_sale'>SALE</span>";
            $badge_count++;
        }
        if ($this->view_only && $badge_count < $this->max) {
            $badge_bg_html .= "<span class='badge_only'></span>";
            $badge_text_html .= "<span class='badge_only'>ONLY</span>";
            $badge_count++;
        }
        if ($this->view_best && $badge_count < $this->max) {
            $badge_bg_html .= "<span class='badge_best'></span>";
            $badge_text_html .= "<span class='badge_best'>BEST</span>";
            $badge_count++;
        }
        if ($this->view_event && $badge_count < $this->max) {
            $badge_bg_html .= "<span class='badge_event'></span>";
            $badge_text_html .= "<span class='badge_event'>EVENT</span>";
            $badge_count++;
        }

        $this->badgeHtml = !empty($badge_bg_html) ? "<div class='item_badge'>" . $badge_bg_html . "</div><div class='item_badge_text'>" . $badge_text_html . "</div>" : "";
        // $this->leaseHtml = $this->view_lease ? "<div class='item_badge item_badge_bottom'><span class='badge_lease'></span></div><div class='item_badge_text item_badge_bottom'><span class='badge_text'>리스</span></div>" : "";

        return $this;
    }

    public function makeHtml()
    {
        $this->_makeBadge();

        $this->html = $this->badgeHtml . $this->leaseHtml . $this->innerHtml;
        $this->photoHtml = "<div class='photo'>" . $this->html . "</div>";

        return $this;
    }
}
