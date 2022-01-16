<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
?>
<div class="item_row_list">

<?php if(!defined('G5_IS_SHOP_AJAX_LIST') && $config['cf_kakao_js_apikey'] && false) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>

<!-- 메인상품진열 10 시작 { -->
<?php

for ($i=0; $row=sql_fetch_array($result); $i++) {
    
    if($row['it_view_list_items'] != "")
    {
        //list view 설정
        $it_view_list_items = ','.$row['it_view_list_items'].',';
        if(!preg_match("/,상품명,/i", $it_view_list_items)) $this->view_it_name = false;
        if(!preg_match("/,최종 판매가,/i", $it_view_list_items)) $this->view_it_price = false;
        
        $this->view_it_basic = (preg_match("/,한줄설명,/i", $it_view_list_items));
        $this->view_new = (preg_match("/,신상품,/i", $it_view_list_items));
        $this->view_wish = preg_match("/,좋아요,/i", $it_view_list_items);
    } else {
        $this->view_it_basic = true;
        $this->view_wish = true;
        
        $reg_time = gap_time(strtotime($row['it_time']), G5_SERVER_TIME);
        $this->view_new = ($reg_time['days'] <= 7);
    }
    
    if ($i == 0) {
        echo "<ul class=\"count2\">\n";
    }
    
    if (is_soldout($row['it_id'])) echo "<li class=\"soldout\">\n";
    else  echo "<li>\n";
    
    if ($this->href) {
        echo "<a href=\"{$this->href}{$row['it_id']}\">\n";
    }

    if ($this->view_it_img) {
        echo "<div class=\"photo\"><img src=\"".get_it_image_path($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\"></div>\n";
    }
    echo "<div class=\"cont\">\n";

    echo "<strong class=\"title bold\">";
    if ($this->view_it_name) {
        echo stripslashes($row['it_name']);
    }
    if($this->view_new) {
        echo "<span class=\"new\">N</span>";
    }
    echo "</strong>\n";
    
    if ($this->view_it_basic) {
        echo "<span class=\"text ellipsis\">".stripslashes($row['it_basic'])."</span>\n";
    }

    if ($this->view_it_price) {
        echo "<span class=\"price\">\n";
        echo display_price(get_price($row), $row['it_tel_inq'])."\n";
        echo "</span>\n";
    }
    
    echo "</div>\n";
    
    if ($this->href) {
        echo "</a>\n";
    }
    
    if($this->view_wish) {
        $sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '".$member['mb_id']."' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='".$row['it_id']."' ";
        $rowwish = sql_fetch($sqlwish);
        
        echo "<div class=\"btn_comm big bottom\"><!-- 찜 눌르면 class=\"on\" 추가 --> ";
        
        echo "<a href=\"javascript:item_wish(document.fitem, '".$row['it_id']."');\" >";
        echo "<button type=\"button\" class=\"pick ico ".(($rowwish['wishis'] != '0')?'on':'')."\" it_id=\"".$row['it_id']."\"><span class=\"blind\">찜</span></button>";
        echo "</a></div>";
    }
    
    echo "</li>\n";
}
?>

<?php 
if ($i > 0) echo "</ul>\n";

if($i == 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<!-- } 상품진열 10 끝 -->
</div>