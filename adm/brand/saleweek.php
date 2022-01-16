<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");
$where = array();

$fr_date = date_create(G5_TIME_YMD);
date_add($fr_date, date_interval_create_from_date_string('-7 weeks'));
$fr_date = date_format($fr_date,"Y-m-d");

$to_date = G5_TIME_YMD;

$days = array();
$totall = array();
$tot0 = array();
$tot1 = array();
$tot2 = array();

$sql = " select od_id,
            SUBSTRING(od_time,1,10) as od_date,
            WEEK(od_time) as od_week,
            SUBSTRING(DATE_ADD(od_time, INTERVAL(DAYOFWEEK(od_time)-1) * -1 DAY),1,10) as od_s_date,
            SUBSTRING(DATE_ADD(od_time, INTERVAL(7-DAYOFWEEK(od_time)) DAY),1,10) as od_e_date,
            od_settle_case,
            od_receipt_price,
            od_receipt_point,
            od_cart_price,
            od_cancel_price,
            od_send_cost,
            od_refund_price,
            od_misu,
            (od_cart_price + od_send_cost + od_send_cost2) as orderprice,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice,
            od_mobile,
            od_type,
            od_zbox_price
       from {$g5['g5_shop_order_table']}
      where SUBSTRING(od_time,1,10) between '$fr_date' and '$to_date'
        and company_code = (select company_code from lt_member_company where mb_id = '{$member['mb_id']}' )";

if(isset($od_mobile) && $od_mobile != '') $sql .= " and od_mobile = '$od_mobile' ";

$sql .= "order by od_time desc ";
$list_result = sql_query($sql);

$g5['title'] = '7주간 매출분석';

unset($list);
unset($save);
unset($tot);
$list = array();
for ($i=0; $row=sql_fetch_array($list_result); $i++)
{
    if ($i == 0){
        $save['od_week'] = $row['od_week'];
        $save['od_week_date'] = $row['od_week'].'('.$row['od_s_date'].'~'.$row['od_e_date'].')';
        
        $save['O'] = $save['R'] =$save['L'] =$save['K'] =$save['S'] =0;
        $save['O_count'] = $save['R_count'] =$save['L_count'] =$save['K_count'] =$save['S_count'] =0;
    }
    
    if ($save['od_week'] != $row['od_week']) {
        //print_line($save);
        //add_chart_saledate($save);
        array_push($list, $save);
        unset($save);
        $save['od_week'] = $row['od_week'];
        $save['od_week_date'] = $row['od_week'].'('.$row['od_s_date'].'~'.$row['od_e_date'].')';
        $save['O'] = $save['R'] =$save['L'] =$save['K'] =$save['S'] =0;
        $save['O_count'] = $save['R_count'] =$save['L_count'] =$save['K_count'] =$save['S_count'] =0;
    }
    
    $save['ordercount']++;
    $save['orderprice']    += $row['orderprice'];
    $save['ordercancel']   += $row['od_cancel_price'];
    $save['ordercoupon']   += $row['couponprice'];
    if($row['od_settle_case'] == '무통장')
        $save['receiptbank']   += $row['od_receipt_price'];
    if($row['od_settle_case'] == '가상계좌')
        $save['receiptvbank']   += $row['od_receipt_price'];
    if($row['od_settle_case'] == '계좌이체')
        $save['receiptiche']   += $row['od_receipt_price'];
    if($row['od_settle_case'] == '휴대전화')
        $save['receipthp']   += $row['od_receipt_price'];
    if($row['od_settle_case'] == '신용카드')
        $save['receiptcard']   += $row['od_receipt_price'];
    $save['receiptpoint']  += $row['od_receipt_point'];
    $save['misu']          += $row['od_misu'];
    $save['send_cost']  += $row['od_send_cost'];
    $save['refund_price'] += $row['od_refund_price'];
    $save['zbox_price'] += $row['od_zbox_price'];
    
    $save['discountprice']  += $row['couponprice'];
    $save['discountprice']  += $row['od_receipt_point'];
    
    $save['od_s_date'] = $row['od_s_date'];
    $save['od_e_date'] = $row['od_e_date'];
    
    $save['receipt_price'] += $row['od_receipt_price'];
    
    if($row['od_mobile'] == '0')
        $save['mobile0']   += $row['od_receipt_price'];
    if($row['od_mobile'] == '1')
        $save['mobile1']   += $row['od_receipt_price'];
    if($row['od_mobile'] == '2')
        $save['mobile2']   += $row['od_receipt_price'];
        
    $save[$row['od_type']]   += $row['od_receipt_price'];
    $save[$row['od_type'].'_count']++;
    
    
    $tot['ordercount']++;
    $tot['orderprice']     += $row['orderprice'];
    $tot['ordercancel']    += $row['od_cancel_price'];
    $tot['ordercoupon']    += $row['couponprice'];
    if($row['od_settle_case'] == '무통장')
        $tot['receiptbank']    += $row['od_receipt_price'];
    if($row['od_settle_case'] == '가상계좌')
        $tot['receiptvbank']    += $row['od_receipt_price'];
    if($row['od_settle_case'] == '계좌이체')
        $tot['receiptiche']    += $row['od_receipt_price'];
    if($row['od_settle_case'] == '휴대전화')
        $tot['receipthp']    += $row['od_receipt_price'];
    if($row['od_settle_case'] == '신용카드')
        $tot['receiptcard']    += $row['od_receipt_price'];
    $tot['receiptpoint']  += $row['od_receipt_point'];
    $tot['misu']           += $row['od_misu'];
    
    $tot['discountprice']  += $row['couponprice'];
    $tot['discountprice']  += $row['od_receipt_point'];
    
    $tot['send_cost']  += $row['od_send_cost'];
    $tot['receipt_price'] += $row['od_receipt_price'];
    $tot['refund_price'] += $row['od_refund_price'];
    
    if($row['od_mobile'] == '0')
        $tot['mobile0']   += $row['od_receipt_price'];
    if($row['od_mobile'] == '1')
        $tot['mobile1']   += $row['od_receipt_price'];
    if($row['od_mobile'] == '2')
        $tot['mobile2']   += $row['od_receipt_price'];
    
    $tot[$row['od_type']]   += $row['od_receipt_price'];
    $tot[$row['od_type'].'_count']++;
}
if ($i != 0) {
    array_push($list, $save);
}

if(isset($act) && $act == "exceldownload"){
    
    $headers = array('NO','주차','시작일','종료일','주문수 총 합계','(총)주문금액','(총)배송비','쿠폰 할인','적립금사용'
        ,'결제 총 합계','할인합계','주문취소','환불금액','미수금');
    
    $bodys = array('NO','od_week','ordercount','orderprice','send_cost','ordercoupon','receiptpoint'
        ,'receipt_price','discountprice','ordercancel','refund_price','misu');
    
    include_once (G5_ADMIN_PATH.'/ajax.excel_download_direct.php');
    exit;
}
include_once (G5_ADMIN_PATH.'/admin.head.php');

function add_chart_saledate($save)
{
    global $days,$totall,$tot0,$tot1,$tot2;

    $days[] = $save['od_week'].'('.$save['od_s_date'].'~'.$save['od_e_date'].')';
    $totall[] = $save['receipt_price'];
    $tot0[] = $save['mobile0'];
    $tot1[] = $save['mobile1'];
    $tot2[] = $save['mobile2'];
}
function print_line($save)
{
    ?>
    <tr>
        <td class="td_alignc"><?php echo $save['od_week']; ?><br/>
        <?php echo $save['od_s_date']; ?><br/>
        ~ <?php echo $save['od_e_date']; ?></td>

        <td class="td_num"><?php echo number_format($save['ordercount']); ?></td>

        <td class="td_numsum"><?php echo number_format($save['orderprice']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['send_cost']); ?></td>

        <td class="td_numcoupon"><?php echo number_format($save['ordercoupon']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptpoint']); ?></td>


        <td class="td_numincome"><?php echo number_format($save['receipt_price']); ?></td>

        <td class="td_numincome"><?php echo number_format($save['ordercoupon']+$save['receiptpoint']); ?></td>

        <td class="td_numcancel1"><?php echo number_format($save['ordercancel']); ?></td>
        <td class="td_numcancel1"><?php echo number_format($save['refund_price']); ?></td>
        <td class="td_numrdy"><?php echo number_format($save['misu']); ?></td>

    </tr>

    <?php
}
?>

<div class="x_panel">
<form name="frm_sale_date" method="get">
<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    </colgroup>
    <tr>
        <th scope="row">검색기간</th>
        <td>
        	최근
        	<select id="sc_week" name="sc_week">
        		<option value="7" selected="selected">7</option>
        	</select>
        	주
        </td>
	</tr>
    <tr>
        <th scope="row">주문경로</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($od_mobile)) $od_mobile = ""?>
            <input type="radio" name="od_mobile" value="" id="od_mobile_all" <?php echo get_checked($od_mobile, ''); ?> checked="checked">
            <label for="od_mobile_all">전체</label>
            <input type="radio" name="od_mobile" value="0" id="od_mobile0" <?php echo get_checked($od_mobile, '0');  ?>>
            <label for="od_mobile0">PC</label>
            <input type="radio" name="od_mobile" value="1" id="od_mobile1" <?php echo get_checked($od_mobile, '1');  ?>>
            <label for="od_mobile1">Mobile</label>
            <input type="radio" name="od_mobile" value="2" id="od_mobile2"   <?php echo get_checked($od_mobile, '2');    ?>>
            <label for="od_mobile2">APP</label>
		</div>
		</td>
	</tr>
	</table>
</div>
<div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
    	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
    </div>
</div>
<div class="clearfix"></div>
</form>
</div>
<div class="x_panel">
    <div class="x_title">
    	<h4>통계그래프</h4>
    	<div class="clearfix"></div>
    </div>
    <div class="x_content">
    	<canvas id="saledateChart"></canvas>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
    	<h4>주별 매출 내역</h4>
    	<div class="clearfix"></div>
    </div>
    <div class="x_content">

	<div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        
        </div>
        <div class="pull-right">
        
            <input type="button" class="btn btn_02" id="excel_download" value="엑셀다운로드"></input>
        
        </div>
	</div>

<div class="tbl_head01 tbl_wrap">

    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <thead>
    <tr>
        <th scope="col">주차</th>

        <th scope="col">결제건수</th>

        <th scope="col">(총)주문금액</th>
        <th scope="col">(총)배송비</th>

        <th scope="col">쿠폰 할인</th>
        <th scope="col">적립금사용</th>

        <th scope="col">결제합계</th>
        <th scope="col">할인합계</th>

        <th scope="col">주문취소</th>
        <th scope="col">환불금액</th>
        <th scope="col">미수금</th>
    </tr>
    </thead>
    <tbody>
    <?php
    
    
    $count = count($list);
    for ($i=0; $i < $count; $i++)
    {
        $save = $list[$i];
        print_line($save);
        add_chart_saledate($save);
    }
    if ($count == 0) {
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
    }

    //순서뒤집기
    $days = array_reverse($days);
    $totall = array_reverse($totall);
    $tot0 = array_reverse($tot0);
    $tot1 = array_reverse($tot1);
    $tot2 = array_reverse($tot2);
    ?>
    </tbody>
    <!-- tfoot>
    <tr>
        <td>합계</td>
        <td class="td_num_right"><?php echo number_format($tot['ordercount']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['orderprice']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['ordercoupon']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptbank']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptvbank']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptiche']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptcard']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receipthp']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptpoint']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['ordercancel']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['misu']); ?></td>
    </tr>
    </tfoot -->
    </table>
</div>

    </div>
</div>

<script src="../vendors/Chart.js/dist/Chart.min.js"></script>
<script>
$(function() {
	
	$("#excel_download").click(function(){
		var $form = $("form[name='frm_sale_date']");     

		var exceldata = $('<input type="hidden" value="exceldownload" name="act">');
	    $form.append(exceldata);
	    $form.submit();
	});

	$('#sc_od_time').daterangepicker({
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



	var randomScalingFactor = function() {
	    return Math.round(Math.random() * 100);
	};
	var randomColorFactor = function() {
	    return Math.round(Math.random() * 255);
	};

	var barChartData = {
	    labels: <?php echo json_encode($days); ?>,
	    datasets: [{
	        type: 'line',
	        label: 'PC',
	        data: <?php echo json_encode($tot0); ?>,
	        borderColor: "rgba(38, 185, 154, 0.7)",
			backgroundColor: "rgba(3, 88, 106, 0)",
	        borderWidth: 2
	    }, {
	        type: 'line',
	        label: 'Mobile',
	        data: <?php echo json_encode($tot1); ?>,
	        borderColor: '#00ff00',
			backgroundColor: "rgba(3, 88, 106, 0)",
	        borderWidth: 2
	    }, {
	        type: 'line',
	        label: 'App',
	        data: <?php echo json_encode($tot2); ?>,
	    	borderColor: '#ff0000',
			backgroundColor: "rgba(3, 88, 106, 0)",
	        borderWidth: 2
	    }, {
	        type: 'bar',
	        label: '매출',
	        backgroundColor: "rgba(220,220,0,0.5)",
	        data: <?php echo json_encode($totall); ?>
	    },
	    ]

	};

	$('#randomizeData').click(function() {
	    $.each(barChartData.datasets, function(i, dataset) {
	        dataset.backgroundColor = 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
	        dataset.data = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()];

	    });
	    window.myBar.update();
	});


    var ctx = document.getElementById("saledateChart").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar',
        data: barChartData,
        options: {
            responsive: true
    		, scales: {
                yAxes: [{
                    ticks: {
    				//Y 축 0부터 시작
                        beginAtZero:true
    					//Y 축 정수로 보여주기
    					//숫자가 작거나 또는 0인 경우 등 자동으로 보여주므로 소숫점으로 나타난다
                        , callback: function (value) {
                            if (0 === value % 1) {
                                return value;
                            }
                        }
                    }
                }]
            }
            ,tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                          return number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index])+"원";
                     }
               }
          }
        }
    });
});
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
