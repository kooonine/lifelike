<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");
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
} else {
    $fr_date = date_create(G5_TIME_YMD);
    date_add($fr_date, date_interval_create_from_date_string('-6 days'));
    $fr_date = date_format($fr_date,"Y-m-d");
    
    $to_date = G5_TIME_YMD;
}

$days = array();
$totall = array();

$sql = " select od_id,
            SUBSTRING(od_time,1,10) as od_date,
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

$sql .= "order by od_settle_case desc ";
$result = sql_query($sql);

$g5['title'] = $fr_date.' ~ '.$to_date.' 결제수단별 매출분석';
unset($list);
unset($save);
unset($tot);
$list = array();

for ($i=0; $row=sql_fetch_array($result); $i++)
{
    if ($i == 0)
        $save['od_settle_case'] = $row['od_settle_case'];
        
        if ($save['od_settle_case'] != $row['od_settle_case']) {
            //print_line($save);
            //add_chart_saledate($save);
            array_push($list, $save);
            unset($save);
            $save['od_settle_case'] = $row['od_settle_case'];
        }
        
        $save['ordercount']++;
        $save['orderprice']    += $row['orderprice'];
        $save['ordercancel']   += $row['od_cancel_price'];
        $save['ordercoupon']   += $row['couponprice'];
        
        $save['receiptpoint']  += $row['od_receipt_point'];
        $save['misu']          += $row['od_misu'];
        $save['send_cost']  += $row['od_send_cost'];
        $save['refund_price'] += $row['od_refund_price'];
        $save['zbox_price'] += $row['od_zbox_price'];
        
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
                    
                    $tot['receiptpoint']  += $row['od_receipt_point'];
                    $tot['misu']           += $row['od_misu'];
                    
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
    
    $headers = array('NO','결제방법','(총)주문금액','주문취소','환불금액','미수금','결제합계');
    
    $bodys = array('NO','od_settle_case','orderprice','ordercancel','refund_price','misu','receipt_price');
    
    include_once (G5_ADMIN_PATH.'/ajax.excel_download_direct.php');
    exit;
}


include_once (G5_ADMIN_PATH.'/admin.head.php');

function add_chart_saledate($save)
{
    global $days,$totall;
    
    $days[] = $save['od_settle_case'];
    $totall[]  = $save['receipt_price'];
}
function print_line($save)
{
    ?>
    <tr>
        <td class="td_alignc"><?php echo $save['od_settle_case']; ?></td>
        
        <td class="td_numsum"><?php echo number_format($save['orderprice']); ?></td>
        
        <td class="td_numcancel1"><?php echo number_format($save['ordercancel']); ?></td>
        <td class="td_numcancel1"><?php echo number_format($save['refund_price']); ?></td>
        <td class="td_numrdy"><?php echo number_format($save['misu']); ?></td>
        
        <td class="td_numincome"><?php echo number_format($save['receipt_price']); ?></td>
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
        	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="sc_od_time" name="sc_od_time" value=""/>
            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
        	</div>
        	<div class="btn-group col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
            </div>
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
    	<h4>일별 매출 내역</h4>
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
        <th scope="col" >결제방법</th>
        <th scope="col" >(총)주문금액</th>
        <th scope="col" >주문취소</th>
        <th scope="col" >환불금액</th>
        <th scope="col" >미수금</th>
        <th scope="col" >결제합계</th>
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
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
    }
    
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
	        type: 'bar',
	        label: '매출',
	        backgroundColor: "rgba(220,220,0,0.5)",
	        data: <?php echo json_encode($totall); ?>
	    }
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
