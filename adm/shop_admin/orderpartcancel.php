<?php
$sub_menu = '40';
include_once('./_common.php');

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if($is_admin == "brand") $auth_sub_menu = $auth['92'];
auth_check($auth_sub_menu, 'w');

$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

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

    
$g5['title'] = $od_type_name.' 취소(CS) 팝업';

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
                    
                    <form name="forderpartcancel" id="forderpartcancel" method="post" action="./orderpartcancelupdate.php" onsubmit="return form_check(this);" enctype="multipart/form-data">
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
                          and   ct_status in ('결제완료','계약등록','세탁신청','보관신청','수선신청','상품준비중')
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
                                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)" checked="checked">
                            </th>
                            <th scope="col">상품명</th>
                            <th scope="col">옵션항목</th>
                            <th scope="col">수량</th>
                            <th scope="col">판매가/리스가</th>
                            <th scope="col">배송비</th>
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
                                        from {$g5['g5_shop_cart_table']}
                                        where od_id = '$od_id'
                                          and it_id = '{$row['it_id']}'
                                          and ct_status in ('결제완료','계약등록','세탁신청','보관신청','수선신청','상품준비중')
                                        order by io_type asc, ct_id asc ";
                            $res = sql_query($sql);
                            $rowspan = sql_num_rows($res);
                
                            for($k=0; $opt=sql_fetch_array($res); $k++) {
                                $cnt++;
                                $opt_price = $opt['ct_price'] + $opt['io_price'];
                
                                // 소계
                                $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                                //$ct_point['stotal'] = $opt['ct_point'] * $opt['ct_qty'];
                                
                                if($od['od_type'] == "L" || $od['od_type'] == "K" || $od['od_type'] == "S"){
                                    $opt['ct_qty'] = "1";
                                    $opt_price = $od['od_receipt_price'];
                                    $ct_price['stotal'] = $od['od_receipt_price'];
                                } else if($od['od_type'] == "R"){
                                    $opt_price = $opt['ct_rental_price'] + $opt['io_price'];
                                    $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                                }
                            ?>
                            <tr>  	
                                <td class="td_chk" >
                                    <input type="hidden" name="ct_id[<?php echo $cnt ?>]" value="<?php echo $opt['ct_id'] ?>" id="ct_id_<?php echo $cnt ?>">
                                    <input type="hidden" name="it_name[<?php echo $cnt ?>]" value="<?php echo $row['it_name'] ?>" id="it_name_<?php echo $cnt ?>">
                                    <input type="checkbox" name="chk[]" value="<?php echo $cnt ?>" id="chk_<?php echo $cnt ?>" checked="checked">
                                    
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
                                <td class="td_cntsmall"><?php echo $opt['ct_qty']; ?></td>
                                <td class="td_num"><?php echo number_format($opt_price); ?></td>
                                <td class="td_num"><?php echo number_format($od['od_send_cost']); ?></td>
                                <!-- <td class="td_num"><?php echo number_format($ct_price['stotal']); ?></td> -->
                                <td class="td_num"><?php echo number_format($od['od_receipt_price']); ?></td>
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
                            	<textarea name="mod_memo" id="mod_memo" rows="4" class="form-control" required="required"></textarea>
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
                        		<?php if($cnt > 1) { ?><button type="button" class="btn btn-success" id="btnConfirm">부분취소</button><?php } ?>
                        		<button type="button" class="btn btn-danger" id="btnConfirmAll">전체취소</button>
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
	$("#btnConfirm").click(function(){
        
	    var chk = document.getElementsByName("chk[]");

        if (trim($("#mod_memo").val()) == "") {
            alert("취소 사유를 입력해 주십시오.");
            return false;
        }

	    if(chk.length == chkcount){
	    	 if (!confirm("전체 주문을 취소하시겠습니까?")) {
	             return false;
	         }
	 		$("#forderpartcancel").attr("action","./ordercancelupdate.php").submit();
	 		
	    } else {
            if (!is_checked("chk[]")) {
                alert("부분취소 하실 항목을 하나 이상 선택하세요.");
                return false;
            }
    		$("#forderpartcancel").attr("action","./orderpartcancelupdate.php").submit();
	    }

	});

	$("#btnConfirmAll").click(function(){
        if (trim($("#mod_memo").val()) == "") {
            alert("취소 사유를 입력해 주십시오.");
            return false;
        }
        
        if (!confirm("전체 주문을 취소하시겠습니까?")) {
            return false;
        }
		<?php if($od['od_type'] == "L" || $od['od_type'] == "K" || $od['od_type'] == "S"){?>
		$("#forderpartcancel").attr("action","./orderpartcancelupdate.php").submit();
		<?php } else { ?>
		$("#forderpartcancel").attr("action","./ordercancelupdate.php").submit();
		<?php } ?>
	});

	

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
