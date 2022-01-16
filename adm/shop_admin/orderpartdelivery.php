<?php
$sub_menu = '92';
include_once('./_common.php');
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

auth_check($auth[substr($sub_menu,0,2)], "w");

$sql = "select * from lt_member_company where mb_id = '{$member['mb_id']}' ";
$cp = sql_fetch($sql);

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

// 주문번호에 - 추가
switch(strlen($od['od_id'])) {
    case 16:
        $disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);
        break;
    default:
        $disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,6).'-'.substr($od['od_id'],6);
        break;
}

if(!$od['od_id'])
    alert_close('주문정보가 존해하지 않습니다.');

$g5['title'] = '발송처리(CS) 팝업';

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
	
        <form name="forderpartchange" method="post" action="./orderpartdeliveryupdate.php" onsubmit="return form_check(this);">
        <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
        <input type="hidden" name="change_status" value="">
    
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
              and   ct_status = '상품준비중'
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
                <th scope="col">판매가</th>
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
                $sql = " select ct_id, it_id, ct_price, ct_qty, ct_option, ct_status, cp_price, ct_send_cost, io_type, io_price
                            ,(select group_concat(od_sub_id) from lt_shop_order_item where lt_shop_order_item.ct_id = lt_shop_cart.ct_id ) as od_sub_id
                            from {$g5['g5_shop_cart_table']}
                            where od_id = '$od_id'
                              and it_id = '{$row['it_id']}'
                              and ct_status = '상품준비중'
                            order by io_type asc, ct_id asc ";
                $res = sql_query($sql);
                $rowspan = sql_num_rows($res);
    
                for($k=0; $opt=sql_fetch_array($res); $k++) {
                    $cnt++;
                    if($opt['io_type'])
                        $opt_price = $opt['io_price'];
                    else
                        $opt_price = $opt['ct_price'] + $opt['io_price'];
    
                    // 소계
                    $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                    $ct_point['stotal'] = $opt['ct_point'] * $opt['ct_qty'];
                ?>
                <tr>  	
                    <td class="td_itname" >
                        <?php echo $opt['od_sub_id']; ?>
                        <input type="hidden" name="ct_id[<?php echo $cnt ?>]" value="<?php echo $opt['ct_id'] ?>" id="ct_id_<?php echo $cnt ?>">
                        <input type="hidden" name="it_name[<?php echo $cnt ?>]" value="<?php echo $row['it_name'] ?>" id="it_name_<?php echo $cnt ?>">
                        
                        <input type="hidden" name="stotal[<?php echo $cnt ?>]" value="<?php echo $ct_price['stotal'] ?>" id="stotal_<?php echo $cnt ?>">
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
                    <td class="td_cntsmall">
                        <?php echo $opt['ct_qty']; ?>
					</td>
                    <td class="td_num"><?php echo number_format($opt_price); ?></td>
                    <td class="td_num"><?php echo number_format($ct_price['stotal']); ?></td>
                </tr>
                <?php
                }
                ?>
            <?php
            }
            
            
            $invoice_time = is_null_time($od['od_invoice_time']) ? G5_TIME_YMDHIS : $od['od_invoice_time'];
            $delivery_company = $od['od_delivery_company'] ? $od['od_delivery_company'] : $cp['cp_delivery_company'];
            
            ?>
            <tr>
                <th scope="col">운송장번호 입력</th>
                <td>
                    <select name="od_delivery_company" class="frm_input">
                        <?php echo get_delivery_company($delivery_company); ?>
                    </select>
                </td>
                <td>
                	<input type="text" name="od_invoice" value="<?php echo $row['od_invoice']; ?>" class="frm_input" size="20">
                </td>
                <td colspan="3">
                    <div class='input-group date' id='od_invoice_time_datepicker' style="margin-bottom: 0px;">
                        <input type="text" name="od_invoice_time" value="<?php echo $invoice_time; ?>" id="od_invoice_time" class="frm_input" maxlength="19" >
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    
                </td>
            </tr>
            </tbody>
            </table>
    	</div>
	
    	
	
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">배송지정보</span></h4>
    	</div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
              	<col class="grid_3">
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="mod_memo">이름</label></th>
              	<td colspan="2"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" placeholder="이름 입력" value="<?php echo $od['od_b_name']?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mod_memo">휴대전화 번호</label></th>
              	<td colspan="2"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input required" maxlength="20" placeholder="휴대전화 번호 입력" value="<?php echo $od['od_b_hp']?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mod_memo">연락처</label></th>
              	<td colspan="2"><input type="text" name="od_b_tel" id="od_b_tel" required class="frm_input" maxlength="20" placeholder="연락처 입력" value="<?php echo $od['od_b_tel']?>">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mod_memo">수거지 주소</label></th>
              	<td colspan="2">
                    <label for="sh_b_zip" class="sound_only">우편번호</label>
                    <input type="text" name="od_b_zip" value="<?php echo $od['od_b_zip1'].$od['od_b_zip2']; ?>" id="sh_b_zip" class="frm_input required" size="6" required>
                    <button type="button" class="btn_frmline" onclick="win_zip('forderpartchange', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button><br>
                    
                    <input type="text" name="od_b_addr1" value="<?php echo get_text($od['od_b_addr1']); ?>" id="od_b_addr1" placeholder="기본주소" class="form-control required" size="35" required>
                    <input type="text" name="od_b_addr2" value="<?php echo get_text($od['od_b_addr2']); ?>" id="od_b_addr2" placeholder="상세주소" class="form-control required" size="35">
                    <input type="text" name="od_b_addr3" value="<?php echo get_text($od['od_b_addr3']); ?>" id="od_b_addr3" placeholder="참고항목" class="form-control" size="35">
                    <input type="hidden" name="od_b_addr_jibeon" value="<?php echo get_text($od['od_b_addr_jibeon']); ?>">
                
                </td>
            </tr>
            </tbody>
            </table>
        </div>
	
    	<div class="x_content">
    		<div class="form-group">
    			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
            		<button type="submit" class="btn" id="btnAddr">주문자 정보수정완료</button><br/><br/>
            	</div>
            </div>
        </div>
        
	
    	<div class="x_content">
    		<div class="form-group">
    			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
            		<button type="button" class="btn btn-secondary" onclick="self.close();">취소</button>
            		<button type="submit" class="btn btn-success" id="btnConfirm">발송처리</button>
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
	$('#od_invoice_time_datepicker').datetimepicker({
	    ignoreReadonly: true,
	    allowInputToggle: true,
	    format: 'YYYY-MM-DD HH:mm',
	    locale : 'ko'
	});

	$("#btnAddr").click(function(){
		$("input[name='change_status']").val("주소변경");
	});

	$("#btnConfirm").click(function(){

		if($("input[name='od_invoice']").val() == ""){
			alert("운송장번호를 입력해주세요.");
			$("input[name='od_invoice']").focus();
			return false;
		}
		
		$("input[name='change_status']").val("배송중");
	});
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
