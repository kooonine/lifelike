<?
$sub_menu = '40';
include_once('./_common.php');
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if($is_admin == "brand") $auth_sub_menu = $auth['92'];
auth_check($auth_sub_menu, 'w');

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

// 주문번호에 - 추가

// $disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8,6);
$disp_od_id = $od['od_id'];
if($od['company_code'] == "")
{
    $de_individual_costs_use = $default['de_individual_costs_use'];
} else {
    $cp = sql_fetch("select * from lt_member_company where company_code = '{$ca_id3}' ");
    
    $de_individual_costs_use = $cp['cp_individual_costs_use'];
}

if(!$od['od_id']){
	alert_close('주문정보가 존해하지 않습니다.');
}

$g5['title'] = $change_status.' 팝업';

include_once ('../admin.head.sub.php');
?>
<div class="container body" >
	<div class="main_container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div id="menu_frm" class="new_win">
						<h3><?=$g5['title']; ?></h3>
					</div>
					<div class="x_content">
						<form name="forderpartchange" method="post" action="./orderreturnupdate.php" onsubmit="return form_check(this);">
							<input type="hidden" name="od_id" value="<?=$od_id; ?>">
							<input type="hidden" name="change_status" value="<?=$change_status; ?>">
							<div class="row">
								<h4>
									<span class="fa fa-check-square"></span>
									<span id="modalTitle">상품주문번호 : <label class="red"><?=$disp_od_id; ?></label></span>
								</h4>
							</div>

							<div class="row">
								<h4>
									<span class="fa fa-check-square"></span>
									<span id="modalTitle">주문상품정보</span>
								</h4>
							</div>
							
							<div class="tbl_head01 tbl_wrap">
                    		<table>
                    		<thead>
                    		<tr>
                    			<th scope="col">번호</th>
                    			<th scope="col">상품명</th>
                    			<th scope="col">옵션항목</th>
                    			<th scope="col">상태</th>
                    			<th scope="col">제품금액<br/>(판매가+옵션가)</th>
                    			<th scope="col">배송료</th>
                    			<th scope="col">주문 금액</th>
                    		</tr>
                    		</thead>
                    		<tbody>
                    		<?
                    		
                    		// 상품목록
                    		$sql = "
                    				select a.ct_id, a.it_id, a.it_name, a.cp_price, a.ct_send_cost, a.it_sc_type
                                            , a.ct_option, a.ct_qty, a.ct_price, a.ct_point, a.ct_status, a.io_type, a.io_price, a.ct_rental_price, a.ct_item_rental_month, a.ct_keep_month, a.ct_receipt_price
                                            , if(a.ct_status IN ( '결제완료', '상품준비중', '배송중', '배송완료', '구매완료' ), 0, 1) as ct_status_order
                                            , b.it_option_subject, b.it_supply_subject, d.od_sub_id, d.rf_serial
                    				from
                    					lt_shop_cart as a
                    					inner join lt_shop_item as b on a.it_id = b.it_id
                                        inner join lt_shop_item_sub as c on a.it_id = c.it_id and a.its_no = c.its_no
                    					left outer join lt_shop_order_item as d on a.ct_id = d.ct_id
                    				where
                    					a.od_id = '{$od['od_id']}'
                                    order by a.it_id, a.it_sc_type, ct_status_order
                    			";
                    		$result = sql_query($sql);
                    		$tot_rows = sql_num_rows($result);
                    		$rowspan = 0;
                    		$rowspanCnt = 0;
                    		$total_send_cost = (int)$od['od_send_cost'];
                    		$cancel_price = 0;
                    		$ct_id_arr = array();
                    		$cancel_cnt = 0;
                    		for($i=0; $row=sql_fetch_array($result); $i++) {
                    		    // 상품이미지
                    		    $image = get_it_image($row['it_id'], 50, 50);
                    		    
                    		    if($row['od_sub_id']) $rfid_modify = true;
                    		    $opt_price = $row['ct_price'] + $row['io_price'];
                    		    
                    		    $ct_send_cost_str = "-";
                    		    if($rowspanCnt == 0){
                        		    if($row['it_sc_type'] == '2' && $de_individual_costs_use == '1'){
                        		        $sql_sc = " select  SUM((b.its_final_price + a.io_price) * a.ct_qty) as price,
                                                    SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
                                                    SUM(a.ct_qty) as qty,
                                                    count(distinct a.ct_id) as ct_cnt
                                				from lt_shop_cart as a
                                				inner join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
                                				inner join lt_shop_item as c on a.it_id = c.it_id
                                				where  a.od_id = '{$od['od_id']}'
                                                and    a.it_id = '{$row['it_id']}' ";
                        		        $sc = sql_fetch($sql_sc);
                        		            
                        	            //선택설정(상품별 개별배송비 사용 필수) : 상품별로 배송비 부과
                        	            $ct_send_cost = (int)get_item_sendcost($row['it_id'], $sc['price'], $sc['qty'], $od_id, $sc['before_price']);
                        	            $rowspan = (int)$sc['ct_cnt'] * (int)$sc['qty'];
                        	            if($ct_send_cost > 0) $total_send_cost = (int)$total_send_cost - (int)$ct_send_cost;
                        	            
                        	            $stotal_price = ($opt_price * $row['ct_qty']) + $ct_send_cost;
                        	        } else {
                        	            $sql_sc = " select  SUM((a.ct_price + a.io_price) * a.ct_qty) as price
                                				from lt_shop_cart as a
                                				where  a.od_id = '{$od['od_id']}'
                                                and  a.it_sc_type = '0'
                                             ";
                        	            $sc = sql_fetch($sql_sc);
                        	            
                        	            $rowspan = $tot_rows;
                        	            //$rowspan = $sc['ct_cnt'];
                        	            $ct_send_cost = $total_send_cost;
                        	            
                        	            $stotal_price = (int)$sc['price'] + (int)$ct_send_cost;
                        	        }
                        	        //print_r2($sc);
                        	        if($ct_send_cost == 0) $ct_send_cost_str = "무료배송";
                        	        else $ct_send_cost_str = number_format($ct_send_cost)." 원";
                    		    }
                    		    $tot_rows--;
                    
                    		    if($row['ct_status'] != '반품요청'){
                    		        echo "<tr hidden>";
									$ct_id_arr[] = $row['ct_id'];
                    			} else {
                    		        echo "<tr>";
                    		        $cancel_price += $opt_price;
									$cancel_cnt++; ?>
									<input type="hidden" name="ct_id[]" id="ct_id" value="<? echo $row['ct_id']; ?>">

									<td>
                    					<? echo $row['od_sub_id'] ; ?>
										<input type="hidden" name="od_sub_id[]" id="od_sub_id" value="<? echo $row['od_sub_id']; ?>">
                    				</td>
                    				<td class="td_itname">
                    					<? echo $image; ?> <? echo stripslashes($row['it_name']); ?>
                    				</td>
                    				<td class="td_itname">
                    					<? echo get_text($row['ct_option']); ?>
                    					<?if($row['io_price']>0){?>
                    						<br/>(옵션금액 + <?=number_format($row['io_price']);?> 원)
                    					<? } ?>
                    				</td>
                    				<td><? echo $row['ct_status']; ?></td>
                    				<td class="td_num_right"><? echo number_format($opt_price); ?> 원</td>
                    				<?php if($rowspanCnt == 0) {?>
                    				<td class="td_num_right" rowspan='<?=$rowspan?>'><? echo $ct_send_cost_str ?></td>
                    				<td class="td_num_right" rowspan='<?=$rowspan?>'><? echo number_format($stotal_price); ?> 원</td>
									<?php } ?>
									
								<?
							        if($rowspanCnt>0) $rowspanCnt--; 
									   if($rowspan>0) {
										   $rowspanCnt = $rowspan - 1;
										   $rowspan = 0;
									   }	
								}
                    			?>
                    			</tr>
                    		<?
                    		}
                    		
                    		?>
                    		</tbody>
                    		</table>
                    	</div>
							
						<?
						$rt = sql_fetch("select * from lt_shop_order_history where od_id = '$od_id' and ct_status_claim = '반품' limit 1");
						$cancel_price_send_cost = $od['od_send_cost2'];
						
						if($cancel_cnt == $tot_rows){
						    //전체 반품
						    $tot_cancel_price = $od['od_receipt_price'] - $cancel_price_send_cost;
						} else {
							//부분 반품
							$tot_cancel_price = 0;
							
							$partSql = "SELECT * FROM lt_shop_cart WHERE od_id = '$od_id' AND ct_status_claim = '반품' GROUP BY ct_return_link";
							$partResult = sql_query($partSql);
							while($row = sql_fetch_array($partResult)) {
								$tot_cancel_price += $row['ct_return_price_save'];
							}
							
						    // if(count($ct_id_arr) > 0) $cancel_od_send_cost = get_cancel_sendcost($od_id, implode(",",$ct_id_arr));
						    // else $cancel_od_send_cost = 0;
						    
						    // $tot_cancel_price = $cancel_price + (int)$od['od_send_cost'];
						    
						    // if($cancel_price_send_cost){
						    //     //구매자 귀책 - 배송비 유료 환불조건 - 색상및사이즈변경,다른상품잘못주문
						    //     $tot_cancel_price -= $cancel_od_send_cost;
						    //     $tot_cancel_price -= $cancel_price_send_cost;
						    // } else {
						    //     //반품 배송비 없음 * 해당 제품의 배송비는 같이 반품
						    //     $tot_cancel_price -= $cancel_od_send_cost;
						    // }
						}
						
						?>
						<div class="row">
							<h4><span class="fa fa-check-square"></span> <span id="modalTitle">반품차액 예정금액</span></h4>
						</div>
						<div class="tbl_frm01 tbl_wrap">
							<table>
								<colgroup>
									<col class="grid_4" />
									<col />
									<col />
									<col />
								</colgroup>
								<tbody>
									<tr>
										<th scope="row" rowspan="2"><label>반품차액<br/>예정금액</label></th>
										<!-- <th scope="row"><label>재승인결제금액</label></th> -->
										<th scope="row"><label>최종결제금액</label></th>
										<th scope="row"><label>반품배송비</label></th>
										<th scope="row"><label>환불금액</label></th>
									</tr>
									<tr>
										<!-- <td><?php echo number_format($od['od_receipt_price']-$od['od_refund_price']- $tot_cancel_price)?> 원</td> -->
										<!-- <td><span ><?=number_format($od['od_receipt_price']-$od['od_refund_price']); ?> 원</span></td> -->
										<td><span ><?=number_format($od['od_receipt_price']); ?> 원</span></td>
										<td><span><?=number_format($cancel_price_send_cost); ?> 원</span></td>
										<td><span id="tot_cancel_price"><?=number_format($tot_cancel_price); ?> 원</span></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="row">
							<h4><span class="fa fa-check-square"></span> <span id="modalTitle">구매자 반품사유</span></h4>
						</div>

						<div class="tbl_frm01 tbl_wrap">
							<table>
								<colgroup>
									<col class="grid_4" />
									<col />
								</colgroup>
								<tbody>
									<tr>
										<th scope="row"><label >반품사유선택</label></th>
										<td><?=$rt['cancel_select']; ?></td>
									</tr>
									<tr>
										<th scope="row"><label >반품사유</label></th>
										<td><?=$rt['sh_memo']; ?></td>
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
									<!--
									<tr>
										<th scope="row"><label for="od_hope_date">수거일자</label></th>
										<td colspan="2">
											<div class='input-group date' id='od_hope_date_datepicker'>
												<input type="text" name="od_hope_date" value="<?=$od['od_hope_date'] ?>" id="od_hope_date" class="frm_input" maxlength="10" minlength="10">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</td>
									</tr>
									-->
									<tr>
										<th scope="row"><label for="mod_memo">이름</label></th>
										<td colspan="2"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" placeholder="이름 입력" value="<?=$od['od_b_name']?>">
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="mod_memo">휴대전화 번호</label></th>
										<td colspan="2"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input required" maxlength="20" placeholder="휴대전화 번호 입력" value="<?=$od['od_b_hp']?>">
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="mod_memo">수거지 주소</label></th>
										<td colspan="2">
											<label for="sh_b_zip" class="sound_only">우편번호</label>
											<input type="text" name="od_b_zip" value="<?=$od['od_b_zip1'].$od['od_b_zip2']; ?>" id="sh_b_zip" class="frm_input required" size="6" required>
											<button type="button" class="btn_frmline" onclick="win_zip('forderpartchange', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button><br>

											<input type="text" name="od_b_addr1" value="<?=get_text($od['od_b_addr1']); ?>" id="od_b_addr1" placeholder="기본주소" class="form-control required" size="35" required>
											<input type="text" name="od_b_addr2" value="<?=get_text($od['od_b_addr2']); ?>" id="od_b_addr2" placeholder="상세주소" class="form-control required" size="35">
											<input type="text" name="od_b_addr3" value="<?=get_text($od['od_b_addr3']); ?>" id="od_b_addr3" placeholder="참고항목" class="form-control" size="35">
											<input type="hidden" name="od_b_addr_jibeon" value="<?=get_text($od['od_b_addr_jibeon']); ?>">

										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<? if($change_status != "반품승인") {?>
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
											<th scope="row"><label for="mod_memo"><?=$change_status?> 사유입력</label></th>
											<td>
												<textarea name="mod_memo" id="mod_memo" rows="4" class="form-control"></textarea>
											</td>
										</tr>
										<tr>
											<th scope="row"><label>처리담당자</label></th>
											<td><?=$member['mb_name'] ?>(<?=$member['mb_id'] ?>)</td>
										</tr>
										<tr>
											<th scope="row"><label>처리일자</label></th>
											<td><span id="proc_date"><?=G5_TIME_YMDHIS;?></span></td>
										</tr>
									</tbody>
								</table>
							</div>
						<? } ?>

						<div class="x_content">
							<div class="form-group">
								<div class="col-md-12 col-sm-12 col-xs-12 text-right">
									<button type="button" class="btn btn-secondary" onclick="self.close();">취소</button>
									<button type="submit" class="btn btn-success" id="btnConfirm"><?=$change_status?></button>
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

window.setInterval(function(){
	var odate = new Date();
	$("#proc_date").html(date_format(odate, "yyyy-MM-dd HH:mm:ss"));
}, 1000);

</script>


<script src="<?=G5_ADMIN_URL ?>/admin.js?ver=<?=G5_JS_VER; ?>"></script>
<script src="<?=G5_JS_URL ?>/jquery.anchorScroll.js?ver=<?=G5_JS_VER; ?>"></script>
<!-- Custom Theme Scripts -->
<script src="<?=G5_ADMIN_URL ?>/js/custom.min.js"></script>


<?
include_once(G5_PATH.'/adm/admin.tail.sub.php');
?>
