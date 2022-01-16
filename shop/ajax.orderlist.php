<?php
include_once('./_common.php');
$sql = " select *
           from {$g5['g5_shop_order_table']}
          where mb_id = '{$member['mb_id']}' ";

if(isset($od_id) && $od_id != ""){
    $sql .= " and od_id = '".$od_id."' ";
}
$sql .=  "order by od_time desc";

$result = sql_query($sql);

if($is_mobile){
?>
<div class="popup_container layer">
    <!-- Modal content -->
	<div class="inner_layer" style="top:0px;">
    	<div id="lnb" class="header_bar">
			<h1 class="title"><span>주문번호 조회</span></h1>
			<a href="#" class="btn_closed close" onclick="$('#popup').empty();"><span class="blind">닫기</span></a>
		</div>  
        <div class="grid" style="overflow-y:scroll;height:500px;">
<?php     
} else {
?>
<!-- popup -->
<section class="popup_container layer">
	<div class="inner_layer" style="top:100px;">
		<div class="grid" style="overflow-y:scroll;height:500px;">
			<div class="title_bar">
				<h2 class="g_title_01">주문번호 조회</h2>
			</div>
<?php
}

for ($i=0; $od=sql_fetch_array($result); $i++)
{
    // 주문상품
    $sql = " select it_name, ct_option, it_id, ct_keep_month
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '{$od['od_id']}'
                        order by io_type, ct_id
                        limit 1 ";
    $ct = sql_fetch($sql);
    $ct_name = get_text($ct['it_name']).' ';
    
    $sql = " select count(*) as cnt
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '{$od['od_id']}' ";
    $ct2 = sql_fetch($sql);
    if($ct2['cnt'] > 1){
        $ct_name .= ' 외 '.($ct2['cnt'] - 1).'건';
    }
    $od['ct_name'] = $ct_name;
    $od['ct_option'] = $ct['ct_option'];
    $od['ct_keep_month'] = $ct['ct_keep_month'];
    
    $image = get_it_image($ct['it_id'], 100, 100, '', '', $ct['it_name']);
    
    if($is_mobile){
?>
            <div class="order_cont chk_order">
            	<div class="body">     		
                    <div class="cont">
                    	<span class="chk check">
                    		<input type="radio" id="chk_id_<?php echo $i?>" name="chk_od" SEQ = "<?php echo $i?>" value="<?php echo $od['od_id']?>" it_id="<?php echo $ct['it_id']?>" >
                    		<label for="chk_id_<?php echo $i?>" class="blind">선택</label>
                    	</span>
                    	<div class="photo" onclick="$('#chk_id_<?php echo $i?>').click();">
                    		<?php echo $image; ?>
                    	</div>
                    	<div class="info">
                    		<strong><?php echo $ct_name?></strong>
                    		<p>옵션 : <?php echo $ct['ct_option']?></p>
                    	</div>
                    </div>
					<div class="order_list">
						<ul>
							<li>
								<span class="item">주문번호</span>
								<strong class="result"><?php echo $od['od_id'];?></strong>
							</li>
							<li>
								<span class="item">주문일</span>
								<strong class="result"><?php echo $od['od_time']?></strong>
							</li>
							<li>
								<span class="item">총 결제 금액</span>
								<strong class="result"><span class="point"><?php echo number_format($od['od_receipt_price']) ?></span> 원</strong>
							</li>
						</ul>
					</div>
            	</div>
            </div>
<?php         
    } else {
?>
            <div class="order_cont chk_order">
            	<div class="body">     		
                    <div class="cont">
                    	<span class="chk check">
                    		<input type="radio" id="chk_id_<?php echo $i?>" name="chk_od" SEQ = "<?php echo $i?>" value="<?php echo $od['od_id']?>" it_id="<?php echo $ct['it_id']?>" >
                    		<label for="chk_id_<?php echo $i?>" class="blind">선택</label>
                    	</span>
                    	<div class="photo" onclick="$('#chk_id_<?php echo $i?>').click();">
                    		<?php echo $image; ?>
                    	</div>
                    	<div class="info">
                    		<p>주문번호 <?php echo $od['od_id'];?></p>
                    		<strong><?php echo $ct_name?></strong>
                    		<p>옵션 : <?php echo $ct['ct_option']?></p>
                    	</div>
                    </div>
            		<div class="btn_comm count3">
            			<span class="date line floatL"><?php echo $od['od_time']?></span>
            			<p class="total floatR">
            				<strong>총 결제 금액</strong>
            				<span class="point bold"><em><?php echo number_format($od['od_receipt_price']) ?></em> 원</span>
            			</p>
            		</div>
            	</div>
            </div>
<?php 
    }
}

if($is_mobile){
    ?>
			</div>

        	<div class="grid">
			<div class="btn_group">
				<button type="button" class="btn big green btn_order_submit" id="btn_order_submit"><span>확인</span></button>
			</div>
			</div>
		</div>
	</div>


<?php     
} else {
?>

			<a href="#" class="btn_closed" onclick="$('#popup').empty();"><span class="blind">닫기</span></a>
		</div>
		<div class="btn_group">
			<button type="button" class="btn big green btn_order_submit" id="btn_order_submit"><span>확인</span></button>
		</div>
	</div>
</section>
<?php
}
?>