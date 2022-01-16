<?php
$sub_menu = '400100';
include_once('./_common.php');
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

auth_check($auth[substr($sub_menu,0,2)], "w");

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

$g5['title'] = '교환요청(CS) 팝업';

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
	
        <form name="forderpartchange" method="post" action="./orderpartcancelupdate.php" onsubmit="return form_check(this);">
        <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    
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
          group by it_id
          order by ct_id ";
	$result = sql_query($sql);	
	?>
	
    	<div class="tbl_head01 tbl_wrap">
            <table>
            <caption>주문 상품 목록</caption>
            <thead>
            <tr>
            	<th scope="col">
                    <label for="chkall" class="sound_only">전체</label>
                    <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                </th>
                <th scope="col">번호</th>
                <th scope="col">상품명</th>
                <th scope="col">RFID</th>                
                <th scope="col">옵션항목</th>
                <th scope="col">상태</th>
                <th scope="col">수량</th>
                <th scope="col">판매가</th>
                <th scope="col">소계</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {
                // 상품이미지
                $image = get_it_image($row['it_id'], 50, 50);
    
                // 상품의 옵션정보
                $sql = " select a.ct_id, a.it_id, a.ct_price, a.ct_qty, a.ct_option, a.ct_status, a.cp_price, a.ct_send_cost
                                ,a.io_id, a.io_type, a.io_price, a.od_sub_id, a.rf_serial, a.it_name
                                ,b.it_option_subject, b.it_supply_subject
                            from {$g5['g5_shop_cart_table']} a, {$g5['g5_shop_item_table']} b
                            where a.od_id = '$od_id'
                              and a.it_id = '{$row['it_id']}'
                              and a.it_id = b.it_id
                            order by a.io_type asc, a.ct_id asc ";
                $res = sql_query($sql);
                $rowspan = sql_num_rows($res);
                    
                for($k=0; $opt=sql_fetch_array($res); $k++) {
                    if($opt['io_type'])
                        $opt_price = $opt['io_price'];
                    else
                        $opt_price = $opt['ct_price'] + $opt['io_price'];
    
                    // 소계
                    $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                    $ct_point['stotal'] = $opt['ct_point'] * $opt['ct_qty'];
                ?>
                <tr>	
                    <td class="td_chk" >
                        <input type="hidden" name="ct_id[<?php echo $i ?>]" value="<?php echo $opt['ct_id'] ?>" id="ct_id_<?php echo $i ?>">
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        
                        <input type="hidden" name="stotal[<?php echo $i ?>]" value="<?php echo $ct_price['stotal'] ?>" id="stotal_<?php echo $i ?>">
                    </td>
                	<td><?php echo $opt['od_sub_id']; ?></td>
                    <?php if($k == 0) { ?>
                    <td class="td_itname" rowspan="<?php echo $rowspan; ?>">
                        <?php echo $image; ?> <?php echo stripslashes($opt['it_name']); ?>
                    </td>
                    <?php } ?>
                    <td class="td_cntsmall"><?php echo $opt['rf_serial']; ?></td>
                    <td class="td_itname">
                        <?php
                        if($opt['io_type'] == '0') {
                            echo get_item_options($opt['it_id'], $opt['it_option_subject'], 'div');
                        } else if($opt['io_type'] == '1') {
                            echo get_item_supply($opt['it_id'], $opt['it_supply_subject'], 'div');
                        }
                        ?>
                    </td>
                    <td class="td_cntsmall"><?php echo $opt['ct_status']; ?></td>
                    <td class="td_cntsmall">
                    	<input type="text" name="ct_qty[<?php echo $i ?>]" id="ct_qty_<?php echo $i ?>" required class="form-control" value="<?php echo $opt['ct_qty']; ?>" >
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
	
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">교환차액 예정금액</span></h4>
    	</div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row" rowspan="2"><label >교환차액<br/>예정금액</label></th>
                <th scope="row"><label >상품변경차액</label></th>
                <th scope="row"><label >배송비</label></th>
                <th scope="row"><label >합계</label></th>
                <th scope="row"><label >교환차액 결제처리</label></th>
            </tr>
            <tr>
                <td><span>0</span></td>
                <td><span>0</span></td>
                <td><span>0</span></td>
                <td>
                	<span>0</span>
            		<button type="button" class="btn btn-secondary" onclick="">결제처리(CS)</button>
                </td>
            </tr>
            </tbody>
            </table>
        </div>
	
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">수거지정보</span></h4>
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
                <th scope="row"><label for="mod_memo">수거일자</label></th>
              	<td colspan="2">
                <div class='input-group date' id='od_hope_date_datepicker'>
                    <input type="text" name="sh_sc_time" value="" id="sh_sc_time" class="frm_input" maxlength="10" minlength="10">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                        
                	
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mod_memo">수거지 주소</label></th>
              	<td colspan="2">
                    <label for="sh_b_zip" class="sound_only">우편번호</label>
                    <input type="text" name="sh_b_zip" value="<?php echo $od['od_b_zip1'].$od['od_b_zip2']; ?>" id="sh_b_zip" class="frm_input" size="6">
                    <button type="button" class="btn_frmline" onclick="win_zip('forderpartchange', 'sh_b_zip', 'sh_b_addr1', 'sh_b_addr2', 'sh_b_addr3', 'sh_b_addr_jibeon');">주소 검색</button><br>
                    
                    <input type="text" name="sh_b_addr1" value="<?php echo get_text($od['od_b_addr1']); ?>" id="sh_b_addr1" placeholder="기본주소" class="form-control" size="35">
                    <input type="text" name="sh_b_addr2" value="<?php echo get_text($od['od_b_addr2']); ?>" id="sh_b_addr2" placeholder="상세주소" class="form-control" size="35">
                    <input type="text" name="sh_b_addr3" value="<?php echo get_text($od['od_b_addr3']); ?>" id="sh_b_addr3" placeholder="참고항목" class="form-control" size="35">
                    <input type="hidden" name="sh_b_addr_jibeon" value="<?php echo get_text($od['od_b_addr_jibeon']); ?>">
                
                </td>
            </tr>
            </tbody>
            </table>
        </div>
	
      	<div class="row">
      		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">메모</span></h4>
    	</div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="mod_memo">상세사유입력</label></th>
                <td>
                	<textarea name="mod_memo" id="mod_memo" rows="4" class="form-control"></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>처리담당자</label></th>
                <td><?php echo $member['mb_name'] ?>(<?php echo $member['mb_id'] ?>)</td>
            </tr>
            <tr>
                <th scope="row"><label>처리일자</label></th>
                <td><span id="proc_date"><?php echo G5_TIME_YMDHIS;?></span></td>
            </tr>
            </tbody>
            </table>
        </div>
    
	
    	<div class="x_content">
    		<div class="form-group">
    			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
            		<button type="button" class="btn btn-secondary" onclick="self.close();">취소</button>
            		<button type="submit" class="btn btn-success" id="btnConfirm">교환승인</button>
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

	$('#od_hope_date_datepicker').datetimepicker({
	    ignoreReadonly: true,
	    allowInputToggle: true,
	    format: 'YYYY-MM-DD',
	    locale : 'ko'
	});
});

function form_check(f)
{

    return true;
}

window.setInterval(function(){
	var odate = new Date();
	$("#proc_date").html(date_format(odate, "yyyy-MM-dd HH:mm:ss"));
}, 1000);

</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>