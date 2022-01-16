<?php
include_once('./_common.php');

$pattern = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]#';
$it_id  = preg_replace($pattern, '', $_POST['it_id']);

$sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' and it_use = '1' ";
$it = sql_fetch($sql);
$it_point = get_item_point($it);

if(!$it['it_id'])
    die('no-item');

$subsql = " select * from lt_shop_item_sub where it_id = '{$it_id}' ";
$subresult = sql_query($subsql);

$subits = array();
$subits_count = 0;
for ($i=0; $its=sql_fetch_array($subresult);  $i++)
{
    $subits[] = $its;
    $subits_count++;
}

// 장바구니 자료
$cart_id = get_session('ss_cart_id');
$sql = " select * from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' and it_id = '$it_id' order by io_type asc, ct_id asc ";
$result = sql_query($sql);

// 판매가격
$sql2 = " select ct_price, it_name, ct_send_cost from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' and it_id = '$it_id' order by ct_id asc limit 1 ";
$row2 = sql_fetch($sql2);

if(!sql_num_rows($result))
    die('no-cart');

    
$it_price = $row2['ct_price'];
if($it['it_item_type'] == "1") $it_price = $it['it_rental_price'];
?>
<div class="info">
	
    <!-- 장바구니 옵션 시작 { -->
    <form name="foption" method="post" action="<?php echo G5_SHOP_URL; ?>/cartupdate.php" onsubmit="return formcheck(this);">
    <input type="hidden" name="act" value="optionmod">
    <input type="hidden" name="it_id[]" value="<?php echo $it['it_id']; ?>">
	<input type="hidden" name="od_type" value="<?php echo $it['it_item_type']; ?>">
    <input type="hidden" id="it_price" value="<?php echo $it_price; ?>">
    <input type="hidden" name="ct_send_cost" value="<?php echo $row2['ct_send_cost']; ?>">
	<input type="hidden" name="it_item_rental_month" value="<?php echo $it['it_item_rental_month']; ?>">
    <input type="hidden" name="sw_direct">
    
	<div class="grid cont" style="border-top-width: 0px;">
		<div class="title_bar" style="overflow:visible;">
			<h3 class="g_title_01" id="optionModalTitle">상품옵션수정</h3>
		</div>
		<div class="list">
		<ul class="basic_info none">
<?php 
for ($i=0; $i<count($subits);  $i++)
{
    $its = $subits[$i]; 
    $its_final_price = $its['its_final_price'];
    if($it['it_item_type'] == "1") $its_final_price = $its['its_final_rental_price'];
?>
	<li>
		<p><?php echo $its['its_item']?> <span class="point"><?php echo display_price($its_final_price) ?></span></p>
		<input type="hidden" name="its_final_price[]" value="<?php echo $its_final_price; ?>" its_no="<?php echo $its['its_no'] ?>">
                            
        <ul class="info_option">
        <li>
            <span id="spn_it_option_<?php echo $i?>"><?php echo $its['its_option_subject']?></span>
            <strong>
            	<?php
            	$io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '{$its['it_id']}' and its_no = '{$its['its_no']}' and io_use = '1' order by io_no asc ";
            	$io_result = sql_query($io_sql);
            	
            	$io_select = '<select id="it_option_'.$i.'" name="sel_it_option[]" its_no="'.$its['its_no'].'" class="cart_it_option btn_select" >'.PHP_EOL;
            	$io_select .= '<option value="">선택</option>'.PHP_EOL;
            	for($j=0; $io_row=sql_fetch_array($io_result); $j++) {
            	    
            	    if($io_row['io_price'] >= 0) {
            	        $price = '&nbsp;&nbsp;+ '.number_format($io_row['io_price']).'원';
            	    } else {
                        $price = '&nbsp;&nbsp; '.number_format($io_row['io_price']).'원';
            	    }
                    
            	    if($io_row['io_stock_qty'] < 1) {
            	        $soldout = '&nbsp;&nbsp;[품절]';
            	    } else {
            	        $soldout = '';
            	    }
            	                
            	    $io_select .= '<option value="'.$io_row['io_id'].','.$io_row['io_price'].','.$io_row['io_stock_qty'].'" io_price="'.$io_row['io_price'].'" io_stock_qty="'.$io_row['io_stock_qty'].'">'.$io_row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
            	}
            	$io_select .= '</select>'.PHP_EOL;
            	
            	echo $io_select.PHP_EOL;
            	?>
            </strong>
        </li>
		<?php 
		
		if($its['its_supply_subject']) {
		    $it_supply_subjects = explode(',', $its['its_supply_subject']);
		    $supply_count = count($it_supply_subjects);
		    
		    for($j=0; $j<$supply_count; $j++) {
		        echo '<li><span id="spn_it_supply_'.$j.'">'.$it_supply_subjects[$j].'</span><strong>'.PHP_EOL;
		        
		        $io_sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '1' and it_id = '{$its['it_id']}' and its_no = '{$its['its_no']}' and io_use = '1' and io_id like '{$it_supply_subjects[$j]}%' order by io_no asc ";
		        $io_result = sql_query($io_sql);
		        
		        $io_select = '<select id="it_supply_'.$j.'"  class="cart_it_supply btn_select" name="sel_it_supply[]" its_no="'.$its['its_no'].'">'.PHP_EOL;
		        $io_select .= '<option value="">선택</option>'.PHP_EOL;
		        for($k=0; $io_row=sql_fetch_array($io_result); $k++) {
		            //$io_id = str_replace($it_supply_subjects[$j], "", $io_row['io_id']);
		            $opt_id = explode(chr(30), $io_row['io_id']);
		            
		            $io_select .= '<option value="'.$io_row['io_id'].','.$io_row['io_price'].','.$io_row['io_stock_qty'].'" io_price="'.$io_row['io_price'].'" io_stock_qty="'.$io_row['io_stock_qty'].'">'.$opt_id[1].'</option>'.PHP_EOL;
		        }
		        $io_select .= '</select>'.PHP_EOL;
		        
		        echo $io_select.'</strong></li>'.PHP_EOL;
		    }
		}
        ?>
        </ul>
<?php 
}
?>
		</li>
		</ul>
		</div>
	</div>
	
    <div id="sit_sel_option">
        <ul id="sit_opt_added">
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {
                if(!$row['io_id'])
                    $it_stock_qty = get_it_stock_qty($row['it_id']);
                else
                    $it_stock_qty = get_option_stock_qty($row['it_id'], $row['io_id'], $row['io_type']);
    
                $ct_price = $row['ct_price'];
                if($it['it_item_type'] == "1") $ct_price = $row['ct_rental_price'];
                    
                if($row['io_price'] < 0)
                    $io_price = number_format($ct_price).'원 ('.number_format($row['io_price']).'원)';
                else
                    $io_price = number_format($ct_price).'원 (+'.number_format($row['io_price']).'원)';
    
                $cls = 'opt';
                if($row['io_type'])
                    $cls = 'spl';
            ?>
            <li class="sit_<?php echo $cls; ?>_list">
                <input type="hidden" name="io_type[<?php echo $it['it_id']; ?>][]" value="<?php echo $row['io_type']; ?>">
                <input type="hidden" name="io_id[<?php echo $it['it_id']; ?>][]" value="<?php echo $row['io_id']; ?>">
                <input type="hidden" name="io_value[<?php echo $it['it_id']; ?>][]" value="<?php echo $row['ct_option']; ?>">
                <input type="hidden" class="it_price" value="<?php echo $ct_price; ?>">
                <input type="hidden" class="io_price" value="<?php echo $row['io_price']; ?>">
                <input type="hidden" class="io_stock" value="<?php echo $it_stock_qty; ?>">
                <div class="cont">
                	<p class="txt"><span><?php echo $row['ct_option']; ?></span></p>
                	<span class="sit_opt_prc"><?php echo $io_price; ?></span>
    			</div>
                <div class="cont alignR">
                    <div class="count_control">
                        <em class="num">
                            <span><input type="text" name="ct_qty[<?php echo $it['it_id']; ?>][]" value="<?php echo $row['ct_qty']; ?>" id="ct_qty_<?php echo $i; ?>" class="num_input" size="5" style="height:18px;"></span>
                        </em>
                        <button type="button" class="count_minus"><span class="blind">감소</span></button>
                        <button type="button" class="count_plus"><span class="blind">증가</span></button>
                    </div>
                	<button type="button" class="count_del" ><span class="blind">삭제</span></button>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <div class="cont">
        <div id="sit_tot_price"><strong class="price">0원</strong></div>
    </div>
    <div class="btn_comm count3">
        <button type="button" class="btn gray_line small" onclick="$(this).closest('form').submit();">옵션적용</button>
        <button type="button" id="mod_option_close" class="btn gray_line small">닫기</button>
    </div>
</form>
</div>

<script>
function formcheck(f)
{
    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
    var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}
</script>
<!-- } 장바구니 옵션 끝 -->