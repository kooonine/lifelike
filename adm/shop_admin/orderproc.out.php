<?php
include_once('./_common.php');
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

$disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);

if(!$od['od_id'])
    alert_close('주문정보가 존재하지 않습니다.');

$od_type_name = "해지";


$g5['title'] = $change_status.' 팝업'; 

include_once ('../admin.head.sub.php');
?>
<div class="container body" >
	<div class="main_container">
		<div class="row">
  			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel" >
                	<div id="menu_frm" class="new_win">
                        <h3><?php echo $g5['title']; ?></h3>
                    </div>
                    
	
                    <form name="forderpartchange" method="post" action="./orderreturnupdate_r.php" onsubmit="return form_check(this);">
                    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
                    <input type="hidden" name="change_status" value="">
    
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">상품주문번호 : <label class="red"><?php echo $disp_od_id; ?></label></span>
                	</div>
                
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">주문상품정보</span></h4>
                	</div>
                	
                	<div class="tbl_frm01 tbl_wrap">
                        <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="col">해지구분</th>
                            <td><?php echo $od['od_contractout'] ?></td>
						</tr>
                        <tr>
                            <th scope="col">수납횟수정보</th>
                            <td><?php echo $od['rt_payment_count'].'회 / '.$od['rt_month'].'회' ?> (현재/전체)</td>
						</tr>
                        <tr>
                            <th scope="col">계약서</th>
                            <td>
                            	<a href="<?php G5_SHOP_URL?>/shop/orderinquiryview.rental.php?od_id=<?php echo $od['od_id']?>" target="_blank"><input type="button" value="리스계약서" class="btn btn_01"></a>
                            </td>
						</tr>
						</tbody>
						</table>
					</div>
                	
	
                	<div class="tbl_head01 tbl_wrap">
                        <table>
                        <caption>주문 상품</caption>
                        <thead>
                        <tr>
                            <th scope="col">상품개별번호</th>
                            <th scope="col">RFID</th>
                            <th scope="col">상품명</th>
                            <th scope="col">옵션항목</th>
                            <th scope="col">계약금액</th>
                            <th scope="col">리스료</th>
                            <th scope="col">무료세탁</th>
                        </tr>
                        </thead>
                        <tbody>
                    	<?php
                    	$sql = " select a.it_id, a.it_name, a.ct_option, a.ct_rental_price, a.io_price
                                      , b.od_sub_id, b.rf_serial,b.ct_item_rental_month,b.ct_free_laundry_use,b.ct_free_laundry,b.ct_free_laundry_delivery_price
                                from  lt_shop_cart as a
                                      inner join lt_shop_order_item as b
                                        on a.od_id = b.od_id and a.ct_id = b.ct_id
                                where a.od_id = '$od_id'
                                order by a.ct_id ";
                    	$res = sql_query($sql);
                    	for($k=0; $row=sql_fetch_array($res); $k++) {
                    	    
                    	    $opt_price = $row['ct_rental_price'] + $row['io_price'];
                    	    $tot_price = $opt_price * (int)$row['ct_item_rental_month'];
                    	?>
                        <tr>  	
                            <td class="td_num"><?php echo $row['od_sub_id']; ?></td>
                            <td class="td_num"><?php echo $row['rf_serial']; ?></td>
                            <td class="td_itname" >
                                <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>" target="_blank"><?php echo $image; ?> <?php echo stripslashes($row['it_name']); ?></a>
                            </td>
                            <td class="td_itopt_tl">
                                <?php echo $row['ct_option']; ?>
                            </td>
                            <td class="td_price"><?php echo number_format($tot_price); ?> 원</td>
                            <td class="td_price"><?php echo number_format($opt_price); ?> 원</td>
                            <td class="td_itopt_tl"><?php echo $row['ct_free_laundry_use']?>회 / <?php echo $row['ct_free_laundry']?>회</td>
                        </tr>
                        <?php } ?>
                        </tbody>
                        </table>
                	</div>
	
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">해지요청정보</span></h4>
                	</div>

                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                          	<col class="grid_3">
                        </colgroup>
                        <tbody>
                        <tr hidden>
                            <th scope="row"><label for="od_hope_date">수거일자</label></th>
                          	<td colspan="2">
                            <div class='input-group date' id='od_hope_date_datepicker'>
                                <input type="text" name="od_hope_date" value="<?php echo $od['od_hope_date']?>" id="od_hope_date" class="frm_input" maxlength="10" minlength="10" required="required">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            </td>
                        </tr>
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
	
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">해지상품정보 </span></h4>
                	</div>
                	
                	
                	<div class="tbl_frm01 tbl_wrap">
                        <table>
                        <colgroup>
                          	<col class="grid_2">
                          	<col class="grid_3">
                          	<col class="grid_3">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="col" rowspan="2">해지 위약금 예정금액</th>
                            <th style="text-align: center" scope="col">위약금</th>
                            <th style="text-align: center" scope="col">배송비</th>
                            <th style="text-align: center" scope="col" colspan="2">합계</th>
                        </tr>
                        <tr>
                        	<td class="td_price"><?php echo number_format($od['od_penalty']); ?></td>
                        	<td class="td_price"><?php echo number_format($default['de_return_costs'])?></td>
                        	<td class="td_price" colspan="2"><?php echo number_format((int)$od['od_penalty'] + (int)$default['de_return_costs'])?></td>
                        </tr>
                        <?php if($change_status == "해지관리") {?>
                        <tr>
                            <th scope="col" rowspan="2">해지 위약금 확정금액</th>
                            <th style="text-align: center" scope="col">위약금</th>
                            <th style="text-align: center" scope="col">배송비</th>
                            <th style="text-align: center" scope="col" colspan="2">합계</th>
                        </tr>
                        <tr>
                        	<td style="text-align: center">
                        	<input type="text" name="od_penalty_item" id="od_penalty_item" value="<?php echo $od['od_penalty'] ?>" class="frm_input text-right" size="10">
                        	</td>
                        	<td  style="text-align: center">
                        	<input type="text" name="od_send_cost2" id="od_send_cost2" value="<?php echo $default['de_return_costs']; ?>" class="frm_input text-right" size="10">
                        	</td>
                        	<td  style="text-align: center">
                        	<input type="text" name="od_penalty" id="od_penalty" value="<?php echo (int)$od['od_penalty'] + (int)$default['de_return_costs'] ?>" class="frm_input text-right readonly" readonly size="10">
                        	</td>
                        	<td  style="text-align: center">
	                        	<input type="button" value="고객결제" class="btn btn_01" id="btnUserPenalty"><br/>
    	                    	<a href="http://pgweb.uplus.co.kr/" target="_blank"><input type="button" value="결제처리(CS)" class="btn btn_01"></a>
                            </td>
                        </tr>
                        <?php } ?>
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
    
                	<div class="row">
                		<div class="form-group">
                			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
                        		<button type="button" class="btn btn-secondary" onclick="self.close();">닫기</button>
                        		<button type="submit" class="btn btn-danger" id="btnConfirmAll" onclick='$("input[name=change_status]").val("<?=($change_status == "해지관리")?"해지수거중":$change_status?>");'><?=($change_status == "해지관리")?"저장":$change_status?></button>
                        	</div>
                        </div>
                    </div>
        
    				</form>
                	
    			</div>
			</div>
		</div>
	</div>
</div>
<script>

$(function(){
	$("#od_penalty_item").autoNumeric('init', {mDec: '0'});
	$("#od_send_cost2").autoNumeric('init', {mDec: '0'});
	$("#od_penalty").autoNumeric('init', {mDec: '0'});
	/*
	$('#od_hope_date_datepicker').datetimepicker({
	    ignoreReadonly: true,
	    allowInputToggle: true,
	    format: 'YYYY-MM-DD',
	    locale : 'ko'
	});
	*/

	$("#od_penalty_item").keyup(function() {
		cancel_price_chk();
    });

	$("#od_send_cost2").keyup(function() {
		cancel_price_chk();
    });

    $("#btnUserPenalty").click(function(){
        if(parseInt($("#od_penalty").autoNumeric('get')) <= 0) {
            alert("결제요청할 금액이 없습니다.");
            return false;
        }

        if(confirm("해지위약금 "+$("#od_penalty").val()+"원을 고객결제 요청하시겠습니까?")){

        	$("input[name='change_status']").val("해지결제요청");
            $("form[name='forderpartchange']").submit();
        }
    });

});

function cancel_price_chk(){
	var od_penalty_item = parseInt($("#od_penalty_item").autoNumeric('get'));
	var cancel_send_costs = parseInt($("#od_send_cost2").autoNumeric('get'));
	var od_penalty = od_penalty_item + cancel_send_costs;

	$("#od_penalty").autoNumeric('set', od_penalty);
}

function form_check(f)
{
    return true;
}

window.setInterval(function(){
	var odate = new Date();
	$("#proc_date").text(date_format(odate, "yyyy-MM-dd HH:mm:ss"));
}, 1000);
</script>


<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL ?>/jquery.anchorScroll.js?ver=<?php echo G5_JS_VER; ?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?php echo G5_ADMIN_URL ?>/js/custom.min.js"></script>


<?php
include_once(G5_PATH.'/adm/admin.tail.sub.php');
?>
