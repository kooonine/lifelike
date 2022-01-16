<?php
include_once('./_common.php');

// 테마에 wishlist.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_wishlist_file = G5_THEME_MSHOP_PATH.'/viewlist.php';
    if(is_file($theme_wishlist_file)) {
        include_once($theme_wishlist_file);
        return;
        unset($theme_wishlist_file);
    }
}


$g5['title'] = "최근 본 상품";
include_once(G5_MSHOP_PATH.'/_head.php');
?>

<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>최근 본 상품</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>
<script>
var g5_shop_url = "<?php echo G5_SHOP_URL; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.mobile.list.js"></script>

<!-- container -->
<div id="container">
	<div class="content shop">
    	<!-- 컨텐츠 시작 -->
    	<div class="grid">
    	
<div class="title_bar">
	<div class="none_sel floatR">
	</div>
</div>
    	
    	
			<div class="item_row_list">
<?php 

$tv_idx = get_session("ss_tv_idx");


$tv_tot_count = 0;
for ($i=1;$i<=$tv_idx;$i++)
{
    $tv_it_idx = $tv_idx - ($i - 1);
    $tv_it_id = get_session("ss_tv[$tv_it_idx]");
    
    $row = sql_fetch(" select * from {$g5['g5_shop_item_table']} where it_id = '$tv_it_id' ");
    if(!$row['it_id'])
        continue;
    
    $it_price = get_price($row);
        
    if ($tv_tot_count == 0) {
        echo "<ul class=\"count2\">\n";
    }
    
    if (is_soldout($row['it_id'])) echo "<li class=\"soldout\">\n";
    else  echo "<li>\n";
    
    echo "<a href=\"".G5_SHOP_URL."/item.php?it_id=".$row['it_id']."\">\n";
    
    echo "<div class=\"photo\"><img src=\"".get_it_image_path($row['it_id'], 230, 230, '', '', stripslashes($row['it_name']))."\"></div>\n";

    echo "<div class=\"cont\">\n";
    
    if($row['it_item_type'] == '1') {
        //리스
        echo "<strong class=\"title bold\">";
        echo stripslashes($row['it_name']);
        
        echo "</strong>\n";
        
        echo "<span class=\"text\">".stripslashes($row['it_basic'])."</span>\n";
        
        echo "<span class=\"price\">\n";
        echo display_price((int)$row['it_rental_price'], $row['it_tel_inq'])."\n";
        echo "</span>\n";
        
        echo "</div>\n";
        
        echo "</a>\n";
        
        
        echo "<div class=\"btn_comm big bottom\">";
        
        echo "<span class=\"category round mb5\">".$row['it_item_rental_month']."개월</span>\n";
        /*
         echo " <a href=\"./wishupdate.php?w=d&amp;wi_id=".$row['wi_id']."\" >";
         echo "<button type=\"button\" class=\"pick ico on\" it_id=\"".$row['it_id']."\"><span class=\"blind\">찜</span></button>";
         echo "</a>";*/
        echo "</div>\n";
        
    } else {
        //제품
        echo "<strong class=\"title bold\">";
        echo stripslashes($row['it_name']);
        
        echo "</strong>\n";
        
        echo "<span class=\"text\">".stripslashes($row['it_basic'])."</span>\n";
        
        echo "<span class=\"price\">\n";
        echo display_price(get_price($row), $row['it_tel_inq'])."\n";
        echo "</span>\n";
        
        echo "</div>\n";
        
        echo "</a>\n";
        
        echo "<div class=\"btn_comm big bottom\">";
        /*
        echo " <a href=\"./wishupdate.php?w=d&amp;wi_id=".$row['wi_id']."\" >";
        echo "<button type=\"button\" class=\"pick ico on\" it_id=\"".$row['it_id']."\"><span class=\"blind\">찜</span></button>";
        echo "</a>";*/
        echo "</div>\n";
    }
    
    echo "</li>\n";
    $tv_tot_count++;
}
    
if ($tv_tot_count > 0) echo "</ul>\n";

if($tv_tot_count == 0) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";

?>

		</div>

	</div>
	<!-- 컨텐츠 종료 -->
    <!-- } 상품 목록 끝 -->
				
	</div>
</div>
<!-- //container -->

<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>