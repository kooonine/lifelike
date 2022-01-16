<?php

$where = array();

if ($sc_od_time != "") {
    $sc_od_times = explode("~", $sc_od_time);
    $fr_date = trim($sc_od_times[0]);
    $to_date = trim($sc_od_times[1]);
}

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

if ($fr_date && $to_date) {
    $where[] = " od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

$od_settle_case = get_search_string($od_settle_case);
if ($od_settle_case) {
    $where[] = " od_settle_case = '$od_settle_case' ";
}
if ($od_type) {
    $where[] = " od_type = '$od_type' ";
}

if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_time";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           $sql_common
           order by $sort1 $sort2
           limit $from_record, $rows ";

$result = sql_query($sql);

$qstr = "cal_type=2&amp;page_rows=".urlencode($page_rows);
?>
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
<input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
<input type="hidden" name="cal_type"  value="<?php echo $cal_type; ?>">
<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    </colgroup>
    <tr>
        <th scope="row">검색기간</th>
        <td>
        	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="sc_od_time" name="sc_od_time" value=""/>
            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
        	</div>
        	<div class="btn-group col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
            </div>
        </td>
	</tr>
    <tr>
        <th scope="row">구분</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php if(!isset($od_type)) $od_type = ""?>
            <input type="radio" name="od_type" value="" id="od_type_all" <?php echo get_checked($od_type, ''); ?>>
            <label for="od_type_all">전체</label>
            <input type="radio" name="od_type" value="O" id="od_typeO" <?php echo get_checked($od_type, 'O');  ?>>
            <label for="od_typeO">제품</label>
            <input type="radio" name="od_type" value="R" id="od_typeR" <?php echo get_checked($od_type, 'R');  ?>>
            <label for="od_typeR">리스</label>
            <input type="radio" name="od_type" value="L" id="od_typeL" <?php echo get_checked($od_type, 'L');  ?>>
            <label for="od_typeL">세탁</label>
            <input type="radio" name="od_type" value="K" id="od_typeK" <?php echo get_checked($od_type, 'K');  ?>>
            <label for="od_typeK">세탁보관</label>
            <input type="radio" name="od_type" value="S" id="od_typeS" <?php echo get_checked($od_type, 'S');  ?>>
            <label for="od_typeS">수선</label>
		</div>
		</td>
	</tr>
    <tr>
        <th scope="row">결제수단</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($od_settle_case)) $od_settle_case = ""?>
            <input type="radio" name="od_settle_case" value="" id="od_settle_case01"        <?php echo get_checked($od_settle_case, '');          ?>>
            <label for="od_settle_case01">전체</label>
            <input type="radio" name="od_settle_case" value="가상계좌" id="od_settle_case03" <?php echo get_checked($od_settle_case, '가상계좌');  ?>>
            <label for="od_settle_case03">가상계좌</label>
            <input type="radio" name="od_settle_case" value="계좌이체" id="od_settle_case04" <?php echo get_checked($od_settle_case, '계좌이체');  ?>>
            <label for="od_settle_case04">계좌이체</label>
            <input type="radio" name="od_settle_case" value="휴대전화" id="od_settle_case05"   <?php echo get_checked($od_settle_case, '휴대전화');    ?>>
            <label for="od_settle_case05">휴대전화</label>
            <input type="radio" name="od_settle_case" value="신용카드" id="od_settle_case06" <?php echo get_checked($od_settle_case, '신용카드');  ?>>
            <label for="od_settle_case06">신용카드</label>
            <input type="radio" name="od_settle_case" value="간편결제" id="od_settle_case07" <?php echo get_checked($od_settle_case, '간편결제');  ?>>
            <label for="od_settle_case07">간편결제</label>
		</div>
		</td>
	</tr>
	</table>
</div>
<div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
    	<button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
    	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
    </div>
</div>
<div class="clearfix"></div>

<div class="tbl_head01 tbl_wrap">
    <div class="pull-left">
    <span class="btn_ov01">[ 검색결과 <?php echo number_format($total_count); ?>건 ]</span>
	</div>
    <div class="pull-right">
		<input type="button" value="엑셀다운로드" class="btn btn_02" >
        <select name="page_rows" onchange="$('#fsearch').submit();">
            <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
            <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
            <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
        </select>
	</div>
</div>
</form>

<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
        <th scope="col" rowspan="2" >일시</th>
        <th scope="col" rowspan="2" >주문<br/>번호</th>
        <th scope="col" rowspan="2" >결제<br/>항목</th>
        <th scope="col" rowspan="2" >결제수단</th>
        <th scope="col" rowspan="2" >1.상품금액</th>
        <th scope="col" rowspan="2" >2.박스비</th>
        <th scope="col" rowspan="2">3.배송료</th>
        <th scope="col" rowspan="2">4.최종판매가<br/>(1+2+3)</th>
        <th scope="col" rowspan="2">적립금<br/>지급</th>

        <th scope="col" colspan="2">5. 할인</th>
        <th scope="col" rowspan="2" >6.사용자결제금액<br/>(4-5)</th>
        <th scope="col" rowspan="2" >7.수수료<br/>(각 결제수단별 , 사용자결제<br/>금액에서 할인율적용)</th>
        <th scope="col" rowspan="2" >8.부가세<br/>(6*10%)</th>

        <th scope="col" rowspan="2" >소계<br/>(6-7-8)</th>
        <th scope="col" rowspan="2" >환불<br/>금액</th>
        <th scope="col" colspan="3">리스위약금</th>
        <th scope="col" rowspan="2" >메모</th>
    </tr>
    <tr>
        <th scope="col" >쿠폰할인</th>
        <th scope="col" >적립금사용</th>
        <th scope="col" >일반<br/>위약금</th>
        <th scope="col" >파손<br/>위약금</th>
        <th scope="col" >분실<br/>위약금</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
        $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'<br/>-'.substr($row['od_id'],8,6);

        switch($row['od_type']) {
            case 'O':
                $od_type_name = '제품';
                break;
            case 'R':
                $od_type_name = '리스';
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
                $od_type_name = '제품';
                break;
        }

        // 결제 수단
        $s_receipt_way = $s_br = "";
        if ($row['od_settle_case'])
        {
            $s_receipt_way = $row['od_settle_case'];
            $s_br = '<br />';

            // 간편결제
            if($row['od_settle_case'] == '간편결제') {
                switch($row['od_pg']) {
                    case 'lg':
                        $s_receipt_way = 'PAYNOW';
                        break;
                    case 'inicis':
                        $s_receipt_way = 'KPAY';
                        break;
                    case 'kcp':
                        $s_receipt_way = 'PAYCO';
                        break;
                    default:
                        $s_receipt_way = $row['od_settle_case'];
                        break;
                }
            }
        }
        else
        {
            $s_receipt_way = '결제수단없음';
            $s_br = '<br />';
        }

    ?>
    <tr class="<?php echo $bg; ?>">
    	<td><?php echo substr($row['od_time'],0,10)."<br/>".substr($row['od_time'],11,10) ?></td>
    	<td><?php echo $disp_od_id ?></td>
    	<td><?php echo $od_type_name ?></td>
    	<td><?php echo $s_receipt_way ?></td>

        <td><?php echo number_format($row['od_cart_price']-$row['od_zbox_price']) ?></td>
        <td><?php echo number_format($row['od_zbox_price']) ?></td>
        <td><?php echo number_format($row['od_send_cost']) ?></td>
        <td><?php echo number_format($row['od_cart_price']+$row['od_send_cost']) ?></td>
        <td><?php echo number_format($row['od_point']) ?></td>
        <td><?php echo number_format($row['od_coupon']) ?></td>
        <td><?php echo number_format($row['od_receipt_point']) ?></td>
        <td><?php echo number_format($row['od_receipt_price']) ?></td>
        <td><?php echo number_format(pg_charge_calc($row['od_settle_case'],$row['od_receipt_price'])) ?></td>
        <td><?php echo number_format($row['od_receipt_price'] * 0.1) ?></td>

        <td><?php echo number_format($row['od_receipt_price'] - pg_charge_calc($row['od_settle_case'],$row['od_receipt_price']) - ($row['od_receipt_price'] * 0.1)) ?></td>
        <td><?php echo number_format($row['od_refund_price']) ?></td>
        <td><?php if($row['od_contractout']=='일반해지') echo number_format($row['od_penalty']) ?> </td>
        <td><?php if($row['od_contractout']=='파손해지') echo number_format($row['od_penalty']) ?> </td>
        <td><?php if($row['od_contractout']=='분실해지') echo number_format($row['od_penalty']) ?> </td>
        <td>
        	<label class="sound_only">메모</label>
        	<?php if($row['od_memo'] != '') { ?>
        	<input type="button" value="USER" class="btn btn_01 od_memo" od_memo='<?php echo $row['od_memo']?>'>
        	<?php } else { ?>
        	<input type="button" value="USER" class="btn">
        	<?php } ?>
		</td>
    </tr>
    <?php
    }

    if ($i == 0)
        echo "<tr><td colspan=\"20\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<div id="modal_od_memo" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">구매자 메모</h4>
        	</div>
        	<div class="modal-body">
				<div class="tbl_frm01 tbl_wrap">
					<table>
					<tr>
						<th scope="row"><label id="od_memo"></label></th>
					</tr>
					</table>
				</div>
			</div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
		</div>
	</div>
</div>

<script>
$(function(){


	$('#sc_od_time').daterangepicker({
		"autoApply": true,
		"opens": "right",
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
    	/*,ranges: {
	           '오늘': [moment(), moment()],
	           '3일': [moment().subtract(2, 'days'), moment()],
	           '1주': [moment().subtract(6, 'days'), moment()],
	           '1개월': [moment().subtract(1, 'month'), moment()],
	           '3개월': [moment().subtract(3, 'month'), moment()],
	           '이번달': [moment().startOf('month'), moment().endOf('month')],
	           '마지막달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	        }*/
	});
	//alert($("button[name='dateBtn'].btn_03").attr("data"));
	<?php
	   if($fr_date !='') echo "$('#sc_od_time').val('".$fr_date." ~ ".$to_date."');";
	   else if($sc_od_time !='') echo "$('#sc_od_time').val('".$sc_od_time."');";
	   else echo "$('#sc_od_time').val('');";
	?>

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){

		var d = $(this).attr("data");
		if(d == "all") {
			$('#sc_od_time').val("");
		} else {
    		var startD = moment();
    		var endD = moment();

    		if(d == "3d") {
    			startD = moment().subtract(2, 'days');
    			endD = moment();

    		} else if(d == "1w") {
    			startD = moment().subtract(6, 'days');
    			endD = moment();

    		} else if(d == "1m") {
    			startD = moment().subtract(1, 'month');
    			endD = moment();

    		} else if(d == "3m") {
    			startD = moment().subtract(3, 'month');
    			endD = moment();
    		}

    		$('#sc_od_time').data('daterangepicker').setStartDate(startD);
    		$('#sc_od_time').data('daterangepicker').setEndDate(endD);
		}

	});

    $(".od_memo").on("click", function() {
        var $this = $(this);
        var od_memo = $this.attr("od_memo");

        $("#od_memo").text(od_memo);
        $("#modal_od_memo").modal('show');

        return false;
    });
});

function fwrite_submit(f)
{

    return true;
}


</script>
