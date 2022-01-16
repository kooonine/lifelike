<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

$ct = sql_fetch(" select * from lt_shop_cart where od_id = '$od_id' ");
$buy_item = sql_fetch(" select * from lt_shop_order_item where ct_id = '{$ct['buy_ct_id']}' and  od_sub_id = '{$ct['buy_od_sub_id']}' ");

$mb = get_member($od['mb_id'], "mb_hp");

$disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);

if(!$od['od_id'])
    alert_close('주문정보가 존재하지 않습니다.');

$od_type_name = '수선';

$fr_date = G5_TIME_YMD;
$to_date = date_create(G5_TIME_YMD);
date_add($to_date, date_interval_create_from_date_string('+1 days'));
$to_date = date_format($to_date,"Y-m-d");
    
$g5['title'] = $od_type_name.' 처리(CS) 팝업';

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
                    
                    <form name="forderpartcancel" id="forderpartcancel" method="post" action="./orderproc.s.update.php" onsubmit="return form_check(this);" enctype="multipart/form-data">
                    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">상품주문번호 : <label class="red"><?php echo $disp_od_id; ?></label></span></h4>
                	</div>
                
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">주문상품정보</span></h4>
                	</div>
				
                  	<div class="row">
                    	<div class="tbl_head01 tbl_wrap">
                            <table>
                            <caption>주문 상품</caption>
                            <thead>
                            <tr>
                                <th scope="col">상품개별번호</th>
                                <th scope="col">RFID</th>
                                <th scope="col">상품명</th>
                                <th scope="col">옵션항목</th>
                                <th scope="col">주문금액</th>
                                <th scope="col">결제금액</th>
                            </tr>
                            </thead>
                            <tbody>
                        	<?php
                        	// 상품이미지
                        	$image = get_it_image($ct['it_id'], 50, 50);
                        	?>
                            <tr>  	
                                <td class="td_num"><?php echo $ct['od_sub_id']; ?></td>
                                <td class="td_num"><?php echo $ct['rf_serial']; ?></td>
                                <td class="td_itname" style="padding:10px;" >
                                    <a href="./itemform.php?w=u&amp;it_id=<?php echo $ct['it_id']; ?>" target="_blank"><?php echo $image; ?> <?php echo stripslashes($ct['it_name']); ?></a>
                                </td>
                                <td class="td_itopt_tl">
                                    <?php echo $ct['ct_option']; ?>
                                </td>
                                <td class="td_num"><?php echo number_format($od['od_cart_price']); ?>원</td>
                                <td class="td_num"><?php echo number_format($od['od_receipt_price']); ?>원</td>
                            </tr>
                            </tbody>
                            </table>
                    	</div>
                	</div>
	
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">요청서</span></h4>
                	</div>

                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                        <!-- tr>
                            <th scope="row"><label for="mod_memo">수거 요청일자</label></th>
                            <td> <?php echo $od['od_hope_date']?></td>
                        </tr -->
                        <tr>
                            <th scope="row"><label for="mod_memo">요청사항</label></th>
                            <td> <?php echo nl2br($od['cust_memo']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php 
                                $cust_file = json_decode($od['cust_file'], true);
                                for ($i = 0; $i < count($cust_file); $i++) {
                                    
                                    $imgL = G5_DATA_URL.'/file/order/'.$od['od_id'].'/'.$cust_file[$i]['file'];
                                    
                                    if ( preg_match("/\.(mp4|mov|avi)$/i", $cust_file[$i]['file'])){
                                        echo "<video controls width='150' height='150' style='vertical-align:top;' >
                                              	<source src='$imgL' type='video/mp4' width='150' height='150' >
                                                </video>";
                                    } else {
                                        echo '<img src="'.$imgL.'" width="150px">';
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                      	</tbody>
                      	</table>
					</div>
                	
                	
                	
                	
	
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle"><?php echo $od_type_name?> CS 처리 </span></h4>
                	</div>

                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                        <colgroup>
                            <col class="grid_4">
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row"><label for="mod_memo">처리상태입력</label></th>
                            <td colspan="2">
                            	<select id="cancel_select" name="cancel_select" required="required">
                            		<option value="">선택</option>
                            		<option value="수거완료">수거완료</option>
                            		<option value="제품확인">제품확인(결제요청)</option>
                            		<option value="수선중">수선중</option>
                            		<option value="추가비용발생">추가비용발생</option>
                            		<option value="배송중">배송중</option>
                            		<option value="고객반려">고객반려</option>
                            		<option value="리탠다드반려">리탠다드반려</option>
                            		<option value="재수선">재수선</option>
                            	</select>
                            </td>
                        </tr>
                        <tr id="proc0" style="display: none;">
                            <th scope="row"><label for="mod_memo">반려비용</label></th>
                            <td colspan="2">
                            	<input type="text" class="frm_input text-right" id="od_refund_price" name="od_refund_price" value="0" >
                            </td>
                        </tr>
                        <tr id="proc1" style="display: none;">
                            <th scope="row"><label for="mod_memo">수선비용</label></th>
                            <td colspan="2">
                            	<input type="text" class="frm_input text-right" id="od_cart_price" name="od_cart_price" value="<?php echo $od['od_cart_price']?>" >
                        		<a href="<?php echo G5_URL?>/common/suseon_pricetable.php" target="_blank" id="btnGuide"><button type="button" class="btn" >수선단가표</button></a>
                            </td>
                        </tr>
                        <tr id="proc2" style="display: none;">
                            <th scope="row"><label for="mod_memo">배송비</label></th>
                            <td colspan="2"><input type="text" class="frm_input text-right" id="od_send_cost" value="<?php echo $buy_item['ct_repair_delivery_price']?>" disabled="disabled" ></td>
                        </tr>
                        <tr id="proc3" style="display: none;">
                            <th scope="row"><label for="mod_memo">결제요청금액</label></th>
                            <td colspan="2">
                            	요청금액 : <input type="text" class="frm_input text-right readonly" id="od_misu" readonly="readonly" name="od_misu" value="<?php echo $od['od_misu']?>" >
                            	<br/>
                            	결제금액 : <input type="text" class="frm_input text-right readonly" readonly="readonly" id="od_receipt_price" name="od_receipt_price" value="<?php echo $od['od_receipt_price']?>" >
                            </td>
                        </tr>
                        <tr id="proc4" style="display: none;">
                            <th scope="row"><label id="cancel_select_title">추가비용</label></th>
                            <td colspan="2">
                            	<input type="text" class="frm_input text-right" id="sh_add_price" name="sh_add_price" value="" >
                            	
                            	<div id="tblAddPrice1" style="float: right;" hidden >
                            	<label><input type="radio" name="add_price_type" value="1" id="add_price_typ1" >결제처리(CS)</label>
                            	<a href="http://pgweb.uplus.co.kr/" target="_blank"><button type="button" class="btn btn-danger">결제처리(CS)</button></a>
                            	<label><input type="radio" name="add_price_type" value="2" id="add_price_typ2" >결제처리(ARS요청)</label>
                        		</div>
                        		<div class="tbl_frm01 tbl_wrap" id="tblAddPrice2" hidden >
                                    <table>
                                    <colgroup>
                                        <col class="grid_4">
                                        <col class="grid_4">
                                        <col>
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                    	<th scope="row">고객 SMS 발송 상품명</th>
                                    	<td><input type="text" id="sh_add_price_productinfo" name="sh_add_price_productinfo" value="<?php echo $od_type_name ?>" maxlength="10" ></td>
                                   	</tr>
                                    <tr>
                                    	<th scope="row">고객핸드폰번호</th>
                                    	<td><input type="text" id="sh_add_price_mb_hp" name="sh_add_price_mb_hp" value="<?php echo $mb['mb_hp']?>" ></td>
                                   	</tr>
                                    <tr>
                                    	<th scope="row">예약일자</th>
                                    	<td>
                                    		<input type='text' class="form-control" id="sh_add_price_time" name="sh_add_price_time" value=""/>
            								<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                                    	</td>
                                   	</tr>
                                   	</tbody>
                                   	</table>
                                </div>
                            </td>
                        </tr>
                        <tr id="proc5" style="display: none;">
                            <th scope="row"><label >운송장번호</label></th>
                            <td colspan="2"><input type="text" name="od_invoice" id="od_invoice" value=""></td>
                        </tr>
                        <tr id="proc_memo">
                            <th scope="row"><label for="sh_memo"> 사유</label></th>
                            <td colspan="2">
                            	<textarea name="sh_memo" id="sh_memo" rows="4" class="form-control" required="required"></textarea>
                            </td>
                        </tr>
                        <tr id="proc_file">
                            <th scope="row"><label for="mod_memo">첨부파일</label></th>
                            <td colspan="2">
                            	<input type="file" name="sh_file" id="sh_file">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>처리담당자</label></th>
                            <td colspan="2"><?php echo $member['mb_name'] ?>(<?php echo $member['mb_id'] ?>)</td>
                        </tr>
                        <tr>
                            <th scope="row"><label>처리일자</label></th>
                            <td colspan="2"><span id="proc_date"><?php echo G5_TIME_YMDHIS;?></span></td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
    
                	<div class="row">
                		<div class="form-group">
                			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
                        		<button type="button" class="btn btn-secondary" onclick="self.close();">닫기</button>
                        		<button type="button" class="btn btn-danger" id="btnConfirmAll">저장</button>
                        	</div>
                        </div>
                    </div>
        
    				</form>
    				
                    <?php 
                    $sql = " select *
                               from lt_shop_order_history
                              where od_id = '{$od['od_id']}'
                              and  cancel_select in ('수거완료','제품확인','수선중','추가비용발생','고객반려','리탠다드반려','재수선','배송중')
                              order by sh_id desc ";
                    $result = sql_query($sql);
                    $count = sql_num_rows($result);
                    if($count){
                        echo '<div class="row">
                          		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">'.$od_type_name.' CS 처리 이력</span></h4>
                        	</div>';
                    }
                        
                    for($i=0; $row=sql_fetch_array($result); $i++) {
                    ?>
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                        <colgroup>
                            <col class="grid_4">
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row"><label for="mod_memo">처리상태입력</label></th>
                            <td colspan="2"><?php echo $row['cancel_select'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" rowspan="3"><label for="mod_memo">비용발생처리</label></th>
                            <th scope="row"><label for="mod_memo">추가비용</label></th>
                            <td><?php echo number_format($row['sh_add_price'])?> 원</td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sh_memo">사유</label></th>
                            <td><?php echo nl2br($row['sh_memo']) ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="mod_memo">첨부파일</label></th>
                            <td><?php
                            if($row['sh_file']){
                                $imgL = G5_DATA_URL.'/file/order/'.$od['od_id'].'/'.$row['sh_file'];
                                echo '<img src="'.$imgL.'">';
                            }?></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>처리담당자</label></th>
                            <td colspan="2"><?php echo $row['sh_mb_name'] ?>(<?php echo $row['sh_mb_id'] ?>)</td>
                        </tr>
                        <tr>
                            <th scope="row"><label>처리일자</label></th>
                            <td colspan="2"><span id="proc_date"><?php echo $row['sh_time'];?></span></td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                    <?php } ?>
    	
    			</div>
			</div>
		</div>
	</div>
</div>
<script>

$(function(){

	$("#btnGuide").on("click", function() {
		var $this = $(this);
		var url = $this.attr("href");
		window.open(url, "guide", "left=100,top=100,width=600,height=600,scrollbars=1");
		return false;
	});
	

	$('#sh_add_price_time').daterangepicker({
		"autoApply": true,
		"opens": "right",
		 "maxSpan": {
		        "months": 1
		    },
		locale: {
	        "format": "YYYY-MM-DD",
	        "separator": " ~ ",
	        "applyLabel": "선택",
	        "cancelLabel": "취소",
	        "fromLabel": "시작일자",
	        "toLabel": "종료일자",
	        "customRangeLabel": "직접선택",
	        "weekLabel": "W",
	        "daysOfWeek": ["일","월","화","수","목","금","토"],
	        "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
	        "firstDay": 1
	    }
		,"startDate": "<?php echo $fr_date?>"
	    ,"endDate": "<?php echo $to_date?>"
	});

	
	$("#od_refund_price").autoNumeric('init', {mDec: '0'});
	$("#od_cart_price").autoNumeric('init', {mDec: '0'});
	$("#od_send_cost").autoNumeric('init', {mDec: '0'});
	
	$("#od_misu").autoNumeric('init', {mDec: '0'});
	$("#od_receipt_price").autoNumeric('init', {mDec: '0'});
	$("#sh_add_price").autoNumeric('init', {mDec: '0'});

	$("#cancel_select").change(function(){
		var cancel_select = $(this).val();
		proc0 = false;
		proc1 = false;
		proc2 = false;
		proc3 = false;
		proc4 = false;
		proc5 = false;
		proc_memo = true;
		proc_file = true;
		if(cancel_select == "제품확인")
		{
			proc1 = true;
			proc2 = true;
			proc3 = true;
			
		} 
		else if(cancel_select == "고객반려" || cancel_select == "리탠다드반려")
		{
			proc0 = true;
			proc2 = true;
			
		} 
		else if(cancel_select == "추가비용발생")
		{
			proc4 = true;
			
		}
		else if(cancel_select == "배송중")
		{
			proc5 = true;
			proc_memo = false;
			proc_file = false;
		}

		$("#proc0").css("display", (proc0)?"":"none");
		$("#proc1").css("display", (proc1)?"":"none");
		$("#proc2").css("display", (proc2)?"":"none");
		$("#proc3").css("display", (proc3)?"":"none");
		$("#proc4").css("display", (proc4)?"":"none");
		$("#proc5").css("display", (proc5)?"":"none");
		$("#proc_memo").css("display", (proc_memo)?"":"none");
		$("#proc_file").css("display", (proc_file)?"":"none");
		
	});

	$("#od_cart_price,#od_send_cost").keyup(function(){
		var od_misu = parseInt($("#od_cart_price").autoNumeric('get')) + parseInt($("#od_receipt_price").autoNumeric('get'));
		$("#od_misu").autoNumeric('set', od_misu);
	});

	$("#btnConfirmAll").click(function(){
		var cancel_select = trim($("#cancel_select").val());
		if (cancel_select == "") {
            alert("처리상태를 선택해주세요.");
            return false;
        }
		if(cancel_select == "제품확인")
		{
	        if (trim($("#od_cart_price").val()) == "") {
	            alert("수선비용를 입력해주세요.");
	            return false;
	        }
	        if (trim($("#od_send_cost").val()) == "") {
	            alert("배송비를 입력해주세요.");
	            return false;
	        }
		} else if(cancel_select == "추가비용발생")
		{
			 if (trim($("#sh_add_price").val()) == "") {
	            alert("추가발생비용을 입력해주세요.");
	            return false;
	        }
		} else if(cancel_select == "배송중")
		{
			 if (trim($("#od_invoice").val()) == "") {
	            alert("운송장 번호를 입력해주세요.");
	            return false;
	        }
		}
        
        if (!confirm($("#cancel_select").val()+" 처리하시겠습니까?")) {
            return false;
        }

		$("#forderpartcancel").submit();
	});

	$("#cancel_select").change(function(){
		$('#sh_add_price').val('');
		var cancel_select = $(this).val();
		if(cancel_select =='추가비용발생'){
			$('#cancel_select_title').text("추가비용");
			$('#sh_add_price').prop('disabled', false);

			$('#tblAddPrice1').prop('hidden', false);
			$('#tblAddPrice2').prop('hidden', false);
			
		} else {
			
			$('#sh_add_price').prop('disabled', true);

			$('#tblAddPrice1').prop('hidden', true);
			$('#tblAddPrice2').prop('hidden', true);
			
			
		}  
	});
	

	$("#cancel_select").trigger("change");

});
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
