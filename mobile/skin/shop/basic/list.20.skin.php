<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
?>
<div class="item_row_list">

    <!-- 메인상품진열 10 시작 { -->
    <?php

    for ($i = 0; $row = sql_fetch_array($result); $i++) {

        $this->view_lease = strpos($row['ca_id'], "1020")  === 0 ? true : false;
        if ($row['it_view_list_items'] != "") {
            //list view 설정
            $it_view_list_items = ',' . $row['it_view_list_items'] . ',';
            $this->view_it_name = true;
            $this->view_it_price = true;

            $this->view_it_basic = (preg_match("/,한줄설명,/i", $it_view_list_items));
            $this->view_wish = preg_match("/,좋아요,/i", $it_view_list_items);
            $this->view_new = (preg_match("/,신상품,/i", $it_view_list_items));
            $this->view_sale = (preg_match("/,할인블릿,/i", $it_view_list_items));
            $this->view_event = (preg_match("/,이벤트블릿,/i", $it_view_list_items));
            $this->view_only = (preg_match("/,단독블릿,/i", $it_view_list_items));
            $this->view_best = (preg_match("/,인기블릿,/i", $it_view_list_items));
        } else {
            $this->view_it_name = true;
            $this->view_it_price = true;
            $this->view_it_basic = true;
            $this->view_wish = true;

            $reg_time = gap_time(strtotime($row['it_time']), G5_SERVER_TIME);
            $this->view_new = ($reg_time['days'] <= 7);
            $this->view_sale = true;
            $this->view_event = true;
            $this->view_only = true;
            $this->view_best = true;
        }

        $badge_bg_html = "";
        $badge_text_html = "";
        $badge_count = 0;
        if ($this->view_new && $badge_count < 4) {
            $badge_bg_html .= "<span class='badge_new'></span>";
            $badge_text_html .= "<span class='badge_text'>NEW</span>";
            $badge_count++;
        }
        if ($this->view_sale && $badge_count < 4) {
            $badge_bg_html .= "<span class='badge_sale'></span>";
            $badge_text_html .= "<span class='badge_text'>SALE</span>";
            $badge_count++;
        }
        if ($this->view_only && $badge_count < 4) {
            $badge_bg_html .= "<span class='badge_only'></span>";
            $badge_text_html .= "<span class='badge_text'>ONLY</span>";
            $badge_count++;
        }
        if ($this->view_best && $badge_count < 4) {
            $badge_bg_html .= "<span class='badge_best'></span>";
            $badge_text_html .= "<span class='badge_text'>BEST</span>";
            $badge_count++;
        }
        if ($this->view_event && $badge_count < 4) {
            $badge_bg_html .= "<span class='badge_event'></span>";
            $badge_text_html .= "<span class='badge_text'>EVENT</span>";
            $badge_count++;
        }

        if (!empty($badge_bg_html)) $badge_bg_html = "<div class='item_badge'>" . $badge_bg_html . "</div><div class='item_badge_text'>" . $badge_text_html . "</div>";
        $badge_lease_html = $this->view_lease ? "<div class='item_badge item_badge_bottom'><span class='badge_lease'></span></div><div class='item_badge_text item_badge_bottom'><span class='badge_text'>리스</span></div>" : "";

        if ($i == 0) {
            echo "<ul id=\"sct_wrap\">\n";
        }

        if (is_soldout($row['it_id'])) echo "<li class=\"soldout\">\n";
        else  echo "<li>\n";

        if ($this->href) {
            echo "<a href=\"{$this->href}{$row['it_id']}\">\n";
        }

        if ($this->view_it_img) {
            // echo "<div class=\"photo\"><img src=\"" . get_it_image_path($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) . "\"></div>\n";
            echo "<div class=\"photo\">" . $badge_bg_html . $badge_lease_html . get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) . "</div>\n";
        }

        if ($this->href) {
            echo "</a>\n";
        }

        echo "<div class=\"cont\"><div class=\"top\">\n";

        echo "<span class=\"category round\">" . $row['it_item_rental_month'] . "개월</span>\n";

        if ($this->view_wish) {
            $sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '" . $member['mb_id'] . "' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='" . $row['it_id'] . "' ";
            $rowwish = sql_fetch($sqlwish);
            echo "<div class=\"btn_comm big bottom\"><!-- 찜 눌르면 class=\"on\" 추가 --> ";
            echo "<a href=\"javascript:item_wish(document.fitem, '" . $row['it_id'] . "');\" >";
            echo "<button type=\"button\" class=\"pick ico " . (($rowwish['wishis'] != '0') ? 'on' : '') . "\" it_id=\"" . $row['it_id'] . "\"><span class=\"blind\">찜</span></button>";
            echo "</a></div>";
        }
        echo "</div>";

        echo "<div class=\"divide_right\"><div class=\"box\">\n";

        echo "<strong class=\"title bold\">";
        if ($this->view_it_name) {
            echo stripslashes($row['it_name']);
        }
        if ($this->view_new) {
            // echo "<span class=\"new\">N</span>";
        }
        echo "</strong>\n";

        if ($this->view_it_basic) {
            echo "<span class=\"text\">" . stripslashes($row['it_basic']) . "</span>\n";
        }
        echo "</div>\n";

        echo "<div class=\"box small\">\n";
        if ($this->view_it_price) {
            echo "<p>월 이용료<span class=\"price\">\n";
            echo display_price((int) $row['it_rental_price'], $row['it_tel_inq']) . "\n";
            echo "</span></p>\n";
        }
        echo "<div>\n";

        echo "</div>\n";

        echo "</li>\n";
    }
    ?>

    <?php
    if ($i > 0) echo "</ul>\n";

    if ($i == 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
    ?>
    <!-- } 상품진열 10 끝 -->
</div>
<!-- } 상품진열 20 끝 -->
<script>
    //상품보관
    function item_wish(f, it_id) {
        if ($(".pick[it_id='" + it_id + "']").attr("class").indexOf("on") < 0) {
            $.post(
                "<?php echo G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {

                        if (confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href = '<?php echo G5_SHOP_URL; ?>/wishlist.php';

                        $(".pick[it_id='" + it_id + "']").addClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        } else {
            $.post(
                "<?php echo G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id,
                    w: 'r'
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {
                        $(".pick[it_id='" + it_id + "']").removeClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        }
    }
</script>