<?php
include_once('./_common.php');

if(!empty($_POST)) {
    
    $where = " and ";
    $sql_search = "";
    if ($stx != "") {
        $sql_search .= " $where (it_name like '%$stx%' or it_id like '%$stx%')";
        $where = " and ";
    }
    
    if ($ca_id != "") {
        $sql_search .= " $where a.ca_id like '$ca_id%' ";
        $where = " and ";
    } else {
        $sql_search .= " $where (0) ";
        $where = " and ";
    }
    
    if(is_checked('not_it_id_list'))
    {
        $sql_search_it_id = "";
        $it_id_lists = explode(",", $not_it_id_list);
        
        for ($i = 0; $i < count($it_id_lists); $i++) {
            if($i!=0) $sql_search_it_id .= ",";
            $sql_search_it_id .= "'".$it_id_lists[$i]."'";
        }
        $sql_search .= " $where it_id not in ($sql_search_it_id)";
    }
    
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
    
    $result = sql_query($sql);
    
    $total_count = sql_num_rows($result);
}
?>
<!-- popup -->
<div class="popup_container layer">
    <div class="inner_layer">
        	<!-- lnb -->
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>제품 추가</span></h1>
			<a href="#" class="btn_closed" onclick="$('#popup').empty();"><span class="blind">닫기</span></a>
		</div>
		<!-- // lnb -->
    
        <div class="content ">
           
			
			<div class="grid ">
				<ul class="info_option">
    				<li>
    					<span class="item">카테고리</span>
    
    					<strong>
                    		<select id="ca_id" class="btn_select">
    							<option value="">선택</option>
                                <?php
                                    $sql = " select * from lt_shop_category where ca_use = '1' ";
                                    $sql .= " order by ca_order, ca_id ";
                                    $ca_result = sql_query($sql);
                                    for ($i=0; $row=sql_fetch_array($ca_result); $i++)
                                    {
                                        $len = strlen($row['ca_id']) / 2 - 1;
            
                                        $nbsp = "";
                                        for ($i=0; $i<$len; $i++)
                                            $nbsp .= "&nbsp;&nbsp;&nbsp;";
            
                                         echo "<option value=\"{$row['ca_id']}\" ".($row['ca_id']==$ca_id?"selected":"").">$nbsp{$row['ca_name']}</option>\n";
                                    }
                                ?>
    						</select>
    					</strong>
    
    				</li>
				</ul>
				<div class="clearfix mt20 float-r w33">
					<input type="button" class="btn big green w135 btnSearch" value="검색" id="btnSearch">
                </div>
                <div class="clearfix"></div>
            </div>
            



            <div class="grid cont">
				<div class="clearfix">
					<span class="search-result-title">
						검색 결과
					</span>
					<?php if($total_count) {?>
					<div class="clearfix text-r float-r">
						<input type="button" class="btn big white w135 btnAddItem" value="선택추가" id="btnAddItem">
					</div>
					<?php } ?>
				</div>
				
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++)
                {
                    $it_name = get_text($row['it_name']);
                    $image = get_it_image($row['it_id'], 100, 100, '', '', $row['it_name']);
                ?>
                <div class="order_cont">
                    <div class="body">
    
    					<span class="chk check pos-add">
            				<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
            				<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
    					</span>
    
                        <div class="cont">
                            <div class="photo" onclick="$('#chk_<?php echo $i?>').click();">
                                <?php echo $image; ?>
                            </div>
                            <div class="info">
                                <strong>[<?php echo $row['ca_name3']?>]<?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?></strong>
                                <div class="price">
                                    <span>제품 금액 : </span><?=($row['it_item_type'])?number_format($row['it_rental_price']):number_format($row['it_price']) ?>원
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                }
                
                if($i==0) echo '<p class="ta-c">검색 결과가 없습니다.</p>';
                ?>
			</div>
			<div class="grid cont">
			<div class="clearfix">
				<span class="search-result-title">
					선택한 제품
				</span>
			</div>
            <?php
            
            if(is_checked('not_it_id_list'))
            {
                $sql_search = "";
                $sql_search_it_id = "";
                $it_id_lists = explode(",", $not_it_id_list);
                
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
                
                for ($i=0; $row=sql_fetch_array($result); $i++)
                {
                    $it_name = get_text($row['it_name']);
                    $image = get_it_image($row['it_id'], 100, 100, '', '', $row['it_name']);
                ?>
                <div class="order_cont">
                    <div class="body">
    
    					<span class="chk check pos-add">
            				<input type="checkbox" name="chk2[]" value="<?php echo $i ?>" id="chk2_<?php echo $i; ?>">
            				<input type="hidden" name="it_id2[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
    					</span>
    
                        <div class="cont">
                            <div class="photo" onclick="$('#chk2_<?php echo $i?>').click();">
                                <?php echo $image; ?>
                            </div>
                            <div class="info">
                                <strong>[<?php echo $row['ca_name3']?>]<?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?></strong>
                                <div class="price">
                                    <span>제품 금액 : </span><?=($row['it_item_type'])?number_format($row['it_rental_price']):number_format($row['it_price']) ?>원
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                }
                }
                ?>
                </div>

            <div class="btn_group two">
                <input type="button" class="btn big border btnDelItem" value="선택삭제">
                <input type="button" class="btn big green btnAddSubmit" value="등록"  onclick="$('#popup').empty();">
            </div>
        </div>
        
	</div>
</div>