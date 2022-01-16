<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

$mb = get_member($od['mb_id'], "mb_hp");

$disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);

if(!$od['od_id'])
    alert_close('주문정보가 존재하지 않습니다.');

switch($od['od_type']) {
    case 'O':
        $od_type_name = '주문';
        break;
    case 'R':
        $od_type_name = '계약'; 
        break;
    case 'L':
        $od_type_name = '세탁';
        break;
    case 'K':
        $od_type_name = '세탁보관';
        break;
    case 'S':
        $od_type_name = '수선';
        break;
    default:
        $od_type_name = '주문';
        break;
}

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
                    
                    <form name="forderpartcancel" id="forderpartcancel" method="post" action="./orderproc.l.update.php" onsubmit="return form_check(this);" enctype="multipart/form-data">
                    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">상품주문번호 : <label class="red"><?php echo $disp_od_id; ?></label></span>
                	</div>
                
                  	<div class="row">
                  		<h4><span class="fa fa-check-square"></span> <span id="modalTitle">주문상품정보</span></h4>
                	</div>
	
                	<div class="tbl_head01 tbl_wrap">
                        <table>
                        <caption>주문 상품</caption>
                        <thead>
                        <tr>
                            <th scope="col">세탁구분</th>
                            <th scope="col">상품개별번호</th>
                            <th scope="col">RFID</th>
                            <th scope="col">상품명</th>
                            <th scope="col">옵션항목</th>
                            <?php if($od['od_type'] == "K"){?>
                            <th scope="col">보관기간</th>
                            <?php }?>
                            <th scope="col">주문금액</th>
                            <th scope="col">결제금액</th>
                        </tr>
                        </thead>
                        <tbody>
                    	<?php
                    	// 상품목록
                    	$sql = " select *
                               from {$g5['g5_shop_cart_table']}
                              where od_id = '$od_id'
                              order by ct_id ";
                    	$row = sql_fetch($sql);
                    	// 상품이미지
                    	$image = get_it_image($row['it_id'], 50, 50);
                    	?>
                        <tr>  	
                            <td class="td_num"><?php echo ($row['ct_free_laundry_use'])?"무료":"유료"; ?></td>
                            <td class="td_num"><?php echo $row['od_sub_id']; ?></td>
                            <td class="td_num"><?php echo $row['rf_serial']; ?></td>
                            <td class="td_itname" >
                                <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>" target="_blank"><?php echo $image; ?> <?php echo stripslashes($row['it_name']); ?></a>
                            </td>
                            <td class="td_itopt_tl">
                                <?php echo $row['ct_option']; ?>
                            </td>
                            <?php if($od['od_type'] == "K"){?>
                            <td> <?php echo $row['ct_keep_month']?>개월</td>
                            <?php }?>
                            <td class="td_num"><?php echo number_format($od['od_cart_price'] + $od['od_send_cost'] + $od['od_send_cost2']); ?>원</td>
                            <td class="td_num"><?php echo number_format($od['od_receipt_price']); ?>원</td>
                        </tr>
                        </tbody>
                        </table>
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
                        <tr>
                            <th scope="row"><label for="">수거 요청일자</label></th>
                            <td> <?php echo $od['od_hope_date']?></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="">요청사항</label></th>
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
                            		<option value="재세탁">재세탁</option>
                            		<option value="추가비용발생">추가비용발생</option>
                            		<option value="고객반려">고객반려</option>
                            		<option value="펭귄반려">펭귄반려</option>
                            	</select>
                            </td>
                        </tr>
                        <tr >
                            <th scope="row" rowspan="3"><label >비용발생처리</label></th>
                            <th scope="row"><label id="cancel_select_title">추가비용</label></th>
                            <td>
                            	<input type="text" id="sh_add_price" name="sh_add_price" value="" >
                            	
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
                        <tr>
                            <th scope="row"><label for="sh_memo">사유</label></th>
                            <td>
                            	<textarea name="sh_memo" id="sh_memo" rows="4" class="form-control" required="required"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="mod_memo">첨부파일</label></th>
                            <td>
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
                              and  cancel_select in ('재세탁','추가비용발생','고객반려','펭귄반려')
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

	$("#btnConfirmAll").click(function(){
		if (trim($("#cancel_select").val()) == "") {
            alert("처리상태를 선택해주세요.");
            return false;
        }
        
        if (trim($("#sh_memo").val()) == "") {
            alert("사유를 입력해주세요.");
            return false;
        }
        
        if (!confirm($("#cancel_select").val()+" 처리하시겠습니까?")) {
            return false;
        }

		$("#forderpartcancel").submit();
	});

	$("#cancel_select").change(function(){
		$('#sh_add_price').val('');
		var cancel_select = $(this).val();
		if(cancel_select =='' || cancel_select =='재세탁') {
			
			$('#cancel_select_title').text("추가비용");
			$('#sh_add_price').prop('disabled', true);

			$('#tblAddPrice1').prop('hidden', true);
			$('#tblAddPrice2').prop('hidden', true);
			
			
		} else if(cancel_select =='추가비용발생'){
			$('#cancel_select_title').text("추가비용");
			$('#sh_add_price').prop('disabled', false);

			$('#tblAddPrice1').prop('hidden', false);
			$('#tblAddPrice2').prop('hidden', false);
			
		} else if(cancel_select =='고객반려' || cancel_select =='펭귄반려'){
			$('#cancel_select_title').text("추가비용(환불제외금액)");
			$('#sh_add_price').prop('disabled', false);
			
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
