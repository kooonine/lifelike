<?php
$sub_menu = '400100';
include_once('./_common.php');
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

auth_check($auth[substr($sub_menu,0,2)], "w");

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

// 주문번호에 - 추가

$disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);

if(!$od['od_id'])
    alert_close('주문정보가 존해하지 않습니다.');

$g5['title'] = '철회완료 팝업';

include_once ('../admin.head.sub.php');
?>
<div class="container body" >
<div class="main_container">
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	<div id="menu_frm" class="new_win">
        <h3><?php echo $g5['title']; ?></h3>
    </div>
    
	<div class="x_content">
	
        <form name="forderpartchange" method="post" action="./orderreturnupdate_r.php" onsubmit="return form_check(this);">
        <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
        <input type="hidden" name="change_status" value="<?php echo $change_status; ?>">
    
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">상품주문번호 : <label class="red"><?php echo $disp_od_id; ?></label></span>
    	</div>
    
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">주문상품정보</span></h4>
    	</div>
    	
    	<?php
    	// 상품목록
    	$sql = " select it_id,
                    it_name,
                    cp_price,
                    ct_notax,
                    ct_send_cost,
                    it_sc_type
               from {$g5['g5_shop_cart_table']}
              where od_id = '$od_id'
              and   ct_status = '수거완료'
              group by it_id
              order by ct_id ";
    	$result = sql_query($sql);	
    	?>

    	<div class="tbl_head01 tbl_wrap">
            <table>
            <caption>주문 상품 목록</caption>
            <thead>
            <tr>
                <th scope="col">상품주문번호</th>
                <th scope="col">상품명</th>
                <th scope="col">옵션항목</th>
                <th scope="col">수량</th>
                <th scope="col">리스가</th>
                <th scope="col">소계</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $cnt = 0;
            for($i=0; $row=sql_fetch_array($result); $i++) {
                // 상품이미지
                $image = get_it_image($row['it_id'], 50, 50);
    
                // 상품의 옵션정보
                $sql = " select ct_id, it_id, ct_price, ct_qty, ct_option, ct_status, cp_price, ct_send_cost, io_type, io_price, ct_rental_price
                            ,(select group_concat(od_sub_id) from lt_shop_order_item where lt_shop_order_item.ct_id = lt_shop_cart.ct_id ) as od_sub_id
                            from {$g5['g5_shop_cart_table']}
                            where od_id = '$od_id'
                              and it_id = '{$row['it_id']}'
                              and ct_status = '수거완료'
                            order by io_type asc, ct_id asc ";
                $res = sql_query($sql);
                $rowspan = sql_num_rows($res);
    
                for($k=0; $opt=sql_fetch_array($res); $k++) {
                    $cnt++;
                    $opt_price = $opt['ct_rental_price'] + $opt['io_price'];
                    $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                    $ct_point['stotal'] = $opt['ct_point'] * $opt['ct_qty'];
                ?>
                <tr>
                    <td class="td_itname" >
                        <?php echo $opt['od_sub_id']; ?>
                    </td>
                    <?php if($k == 0) { ?>
                    <td class="td_itname" rowspan="<?php echo $rowspan; ?>">
                        <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>" target="_blank"><?php echo $image; ?> <?php echo stripslashes($row['it_name']); ?></a>
                        <?php if($od['od_tax_flag'] && $row['ct_notax']) echo '[비과세상품]'; ?>
                    </td>
                    <?php } ?>
                    <td class="td_itopt_tl">
                        <?php echo $opt['ct_option']; ?>
                    </td>
                    <td class="td_cntsmall"><?php echo $opt['ct_qty'] ?>
					</td>
                    <td class="td_num"><?php echo number_format($opt_price); ?></td>
                    <td class="td_num"><?php echo number_format($ct_price['stotal']); ?></td>
                </tr>
                <?php
                }
                ?>
            <?php
            }
            ?>
            </tbody>
            </table>
    	</div>
    	
    	<?php 
    	$rt = sql_fetch("select * from lt_shop_order_history where od_id = '$od_id' and ct_status_claim = '철회' limit 1");
    	
    	$cancel_price_send_cost = $od['od_send_cost2'];
    	$cancel_price = $od['od_receipt_price'];
    	$tot_cancel_price = $cancel_price - $cancel_price_send_cost;
    	?>
	
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">철회차액 예정금액</span></h4>
    	</div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row" rowspan="2"><label >철회차액<br/>예정금액</label></th>
                <th scope="row"><label >배송비</label></th>
                <th scope="row"><label >합계</label></th>
                <th scope="row"><label >철회차액 환불처리</label></th>
            </tr>
            <tr>
                <td><?php echo number_format($cancel_price_send_cost); ?>원</td>
                <td><span id="cancel_price"><?php echo number_format($cancel_price); ?> 원</span></td>
                <td>
                	<h4><span id="tot_cancel_price"><?php echo number_format($tot_cancel_price); ?> 원</span></h4>
                	<input type="hidden" name="tax_mny" value="<?php echo $tot_cancel_price ?>">
                </td>
            </tr>
            </tbody>
            </table>
        </div>
	
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">구매자 철회사유</span></h4>
    	</div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label >철회사유선택</label></th>
                <td><?php echo $rt['cancel_select']; ?></td>
            </tr>
            <tr>
                <th scope="row"><label >철회사유</label></th>
                <td>
                	<textarea name="mod_memo" id="mod_memo" rows="4" readonly="readonly" class="form-control"><?php echo $rt['sh_memo']; ?></textarea>
                </td>
            </tr>
            </tbody>
            </table>
        </div>
	
    	<div class="x_content">
    		<div class="form-group">
    			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
            		<button type="button" class="btn btn-secondary" onclick="self.close();">취소</button>
            		<button type="submit" class="btn btn-success" id="btnConfirm">철회완료(환불처리)</button>
            	</div>
            </div>
        </div>
        
    	</form>
    	
    </div>

	</div>
  </div>
</div>
</div>
</div>
<script>
$(function(){

});

function form_check(f)
{

    return true;
}

</script>


<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL ?>/jquery.anchorScroll.js?ver=<?php echo G5_JS_VER; ?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php echo G5_ADMIN_URL ?>/js/custom.min.js"></script>


<?php
include_once(G5_PATH.'/adm/admin.tail.sub.php');
?>
