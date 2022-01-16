<?php
include_once('./_common.php');
            
if(is_checked('it_id_lists'))
{
    $sql_search = "";
    $sql_search_it_id = "";
    $it_id_lists = explode(",", $it_id_lists);
    
    for ($i = 0; $i < count($it_id_lists); $i++) {
        if($i!=0) $sql_search_it_id .= ",";
        $sql_search_it_id .= "'".$it_id_lists[$i]."'";
    }
    $sql_search .= " and it_id in ($sql_search_it_id)";
    
    $sql_common = " from {$g5['g5_shop_item_table']} a ,
             {$g5['g5_shop_category_table']} b,
             {$g5['g5_shop_category_table']} c,
             {$g5['g5_shop_category_table']} d
       where a.it_use = 1
            and  (b.ca_id = left(a.ca_id,2)
            and   c.ca_id = left(a.ca_id,4)
            and   d.ca_id = left(a.ca_id,6)
            ) ";
             
    $sql_common .= $sql_search;
             
     if (!$sst) {
         $sst  = "it_time";
         $sod = "desc";
     }
     $sql_order = "order by $sst $sod";
     
     $sql  = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*
        $sql_common
        $sql_order ";
    //echo $sql;
    $result = sql_query($sql);
?>
<div class="item_row_list swiper-container">
    <ul class="swiper-wrapper">    
<?php 
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $it_name = get_text($row['it_name']);
        $image = get_it_image($row['it_id'], 200, 200, '', '', $row['it_name']);
    ?>           	
        	<li class="swiper-slide">
    			<div class="photo">
    				<?php echo $image; ?>
    			</div>
    			<div class="cont">
    				<div class="inner">
    					<strong class="title bold line2">[<?php echo $row['ca_name3']?>]<?php echo htmlspecialchars2(cut_str($row['it_name'],50, "")); ?></strong>
    					<span class="price"><?=($row['it_item_type'])?number_format($row['it_rental_price']):number_format($row['it_price']) ?> 원</span>
    				</div>
    			</div>
        	</li>
<?php 
    }
?>
	</ul>
</div>
<script>
	var swiperColumn_three = new Swiper('.pdt1 .swiper-container', {
		slidesPerView: 'auto',
		spaceBetween: 10,
		//loop: true,
	});
</script>
<?php 
}
?>
