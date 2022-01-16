<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/coupon_item.php');
    return;
}

if ($is_guest)
    alert('회원만 조회하실 수 있습니다.');
    
if(!empty($_POST)) {
    $cm_type = $_POST['cm_type'];
    $cm = sql_fetch("select * from lt_shop_coupon_mng where cm_no = '".$_POST['cm_no']."' ");
    
    $sql_search = "";
    
    $where = " and ";
    $cm_item_it_id_list = $cm['cm_item_it_id_list'];
    $cm_item_ca_id_list = $cm['cm_item_ca_id_list'];
    
    $cm_item_type = $cm['cm_item_type'];
    $cm_category_type = $cm['cm_category_type'];
    
    if($cm_item_it_id_list != "" && $cm_type == $cm_item_type)
    {
        $sql_search_it_id = "";
        $it_id_lists = explode(",", $cm_item_it_id_list);
        
        for ($i = 0; $i < count($it_id_lists); $i++) {
            if($i!=0) $sql_search_it_id .= ",";
            $sql_search_it_id .= "'".$it_id_lists[$i]."'";
        }
        $sql_search .= " or it_id in ($sql_search_it_id)";
    }
    if($cm_item_ca_id_list != "" && $cm_type == $cm_category_type)
    {
        $sql_search_ca_id = "";
        $ca_id_lists = explode(",", $cm_item_ca_id_list);
        
        for ($i = 0; $i < count($ca_id_lists); $i++) {
            if($i!=0) $sql_search_ca_id .= ",";
            $sql_search_ca_id .= "'".$ca_id_lists[$i]."'";
        }
        $sql_search .= " or ca_id in ($sql_search_ca_id)";
    }
    
    $sql_common = " from {$g5['g5_shop_item_table']} a
               where (0) ";
                     
    $sql_common .= $sql_search;
    
    if (!$sst) {
        $sst  = "it_time";
        $sod = "desc";
    }
    $sql_order = "order by $sst $sod";
    
    $sql  = " select a.*
            $sql_common
            $sql_order ";
            
    $result = sql_query($sql);
?>

<div class="popup_container layer" id="popup_item">
    <div class="inner_layer" style="top: 100px;">
        <div class="content ">
            <div class="title_bar">
                <h2 class="g_title_01"><?php echo ($cm_type=="1")?"적용 제품":"적용 제외 제품"?></h2>
            </div>
            <div class="grid cont">
            <?php 
            for ($i=0; $row=sql_fetch_array($result); $i++)
            {
                $bg = 'bg'.($i%2);
            ?>
                <div class="order_cont">
                    <div class="body">
                        <div class="cont">
                        <?php echo "<a href=\"".G5_SHOP_URL."/item.php?it_id=".$row['it_id']."\">\n"; ?>
                            <div class="photo"><?php echo get_it_image($row['it_id'], 100, 100); ?></div>
                            <div class="info">
                                <strong><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?></strong>
                                <div class="pay_item">
                                    제품금액 : <span class="amount"><strong><?php echo number_format($row['it_price']); ?></strong> 원</span>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
			<?php } ?>
            </div>
        </div>

        <a href="#" class="btn_closed" onclick="$('#popup').empty();"><span class="blind">닫기</span></a>
    </div>
</div>

<?php 
}
?>