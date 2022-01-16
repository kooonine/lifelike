<?php
//$sub_menu = '100000';
$sub_menu = '10';
include_once('./_common.php');

if($is_admin == 'brand') {
    include_once ('./index.brand.php');
    exit;
}

@include_once('./safe_check.php');
if(function_exists('social_log_file_delete')){
    social_log_file_delete(86400);      //소셜로그인 디버그 파일 24시간 지난것은 삭제
}
$max_limit = 7; // 몇행 출력할 것인지?
$new_point_rows = 5;
$subject_len = 40;

$sday = '';
$days = array();
for ($i = 1; $i <= $max_limit; $i++) {
    $m_days = $max_limit - $i;
    $add_day = date_create(G5_TIME_YMD);
    date_add($add_day, date_interval_create_from_date_string('-'.$m_days.' days'));
    $days[] = date_format($add_day,"m-d");
    if($i == 1) $sday = date_format($add_day,"Y-m-d");
}

$sql = "select  sum(case when od_time >= date_add(now(), INTERVAL -1 DAY) then 1 else 0 end) neworder
        		,sum(case when od_status = '배송중'and od_invoice_time >= '".G5_TIME_YMD."' then 1 else 0 end) todayinvoice
                
        		,sum(case when od_status = '배송중'and od_invoice != '' then 1 else 0 end) invoice_ing
        		,sum(case when od_status in ('배송완료','구매완료','리스중','세탁완료','수선완료') and od_invoice != '' then 1 else 0 end) invoice_compl
                
                ,sum(case when od_status_claim = '주문취소' then 1 else 0 end) claim1
                ,sum(case when od_status_claim = '교환' then 1 else 0 end) claim2
                ,sum(case when od_status_claim = '반품' then 1 else 0 end) claim3
                ,sum(case when od_status_claim = '철회' then 1 else 0 end) claim4
                ,sum(case when od_status_claim = '해지' then 1 else 0 end) claim5        
        from 	lt_shop_order
        where	od_time >= '{$sday}'";

$view = sql_fetch($sql);

// $sql2= "SELECT sum(case when return_status = '반품접수' then 1 else 0 end) returnReceipt,
//               sum(case when return_status = '입고확인' then 1 else 0 end) returnCheck,
//               sum(case when return_status = '환불완료' then 1 else 0 end) returnConfirm,
//               sum(case when return_status = '반품완료' then 1 else 0 end) returnClear
//         from 	sabang_return_origin
//         where	reg_datetime >= '{$sday}'";
// $view2 = sql_fetch($sql2);
$sql2= "SELECT sum(case when return_status = '반품접수' then 1 else 0 end) returnReceipt from sabang_return_origin where reg_datetime >= '{$sday}'";
$view_fet = sql_fetch($sql2);
if($view_fet['returnReceipt'] == null) $view2['returnReceipt'] = 0;
else $view2['returnReceipt'] = $view_fet['returnReceipt'];
$sql2= "SELECT sum(case when return_status = '입고확인' then 1 else 0 end) returnCheck from sabang_return_origin where ware_datetime >= '{$sday}'";
$view_fet = sql_fetch($sql2);
if($view_fet['returnCheck'] == null) $view2['returnCheck'] = 0;
else $view2['returnCheck'] = $view_fet['returnCheck'];
$sql2= "SELECT sum(case when return_status = '환불완료' then 1 else 0 end) returnConfirm from sabang_return_origin where refund_datetime >= '{$sday}'";
$view_fet = sql_fetch($sql2);
if($view_fet['returnConfirm'] == null) $view2['returnConfirm'] = 0;
else $view2['returnConfirm'] = $view_fet['returnConfirm'];
$sql2= "SELECT sum(case when return_status = '반품완료' then 1 else 0 end) returnClear from sabang_return_origin where returnclear_datetime >= '{$sday}'";
$view_fet = sql_fetch($sql2);
if($view_fet['returnClear'] == null) $view2['returnClear'] = 0;
else $view2['returnClear'] = $view_fet['returnClear'];

$today2 = date("Y-m-d");
$g5['title'] = '대시보드';
include_once ('./admin.head.php');
?>
<script src="./vendors/Chart.js/dist/Chart.min.js"></script>
<!-- top tiles -->
<div class="row tile_count">

	<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;" onclick="location.href='./shop_admin/orderlist.php';">
            	<h4>신규주문 <span class="bold" style="font-weight:600"><?php echo $view['neworder']?></span>건</h4>
              <h4>금일출발 <span class="bold" style="font-weight:600"><?php echo $view['todayinvoice']?></span>건</h4>
              <h4>배송중 <span class="bold" style="font-weight:600"><?php echo $view['invoice_ing']?></span>건</h4>
              <h4>배송완료 <span class="bold" style="font-weight:600"><?php echo $view['invoice_compl']?></span>건</h4>
        	</div>
        	</div>
		</div>
    </div>
	<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon glyphicon-plane" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;">
            	<h4 class="name_title" onclick="location.href='./shop_admin/total_order/total_order_return_list.php?&sc_it_time=<?echo $sday?>+~+<?echo $today2?>&return_status_case%5B%5D=반품접수';">반품접수 <span class="bold" style="font-weight:600"><?php echo $view2['returnReceipt']?></span>건</h4>
              <h4 class="name_title" onclick="location.href='./shop_admin/total_order/total_order_return_list.php?&sc_it_time=<?echo $sday?>+~+<?echo $today2?>&return_status_case%5B%5D=입고확인';">입고확인 <span class="bold" style="font-weight:600"><?php echo $view2['returnCheck']?></span>건</h4>
              <h4 class="name_title" onclick="location.href='./shop_admin/total_order/total_order_return_list.php?&sc_it_time=<?echo $sday?>+~+<?echo $today2?>&return_status_case%5B%5D=환불완료';">환불완료 <span class="bold" style="font-weight:600"><?php echo $view2['returnConfirm']?></span>건</h4>
              <h4 class="name_title" onclick="location.href='./shop_admin/total_order/total_order_return_list.php?&sc_it_time=<?echo $sday?>+~+<?echo $today2?>&return_status_case%5B%5D=반품완료';">반품완료 <span class="bold" style="font-weight:600"><?php echo $view2['returnClear']?></span>건</h4>
        	</div>
        	</div>
		</div>
    </div>
	<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;" onclick="location.href='./shop_admin/claimlist.php';">
            	<h4 class="name_title">취소요청 <span class="bold" style="font-weight:600"><?php echo $view['claim1']?></span>건</h4>
            	<!-- h4 class="name_title">교환요청 <span class="bold" style="font-weight:600"><?php echo $view['claim2']?></span>건</h4 -->
            	<h4 class="name_title">반품요청 <span class="bold" style="font-weight:600"><?php echo $view['claim3']?></span>건</h4>
            	<h4 class="name_title">철회요청 <span class="bold" style="font-weight:600"><?php echo $view['claim4']?></span>건</h4>
            	<h4 class="name_title">해지요청 <span class="bold" style="font-weight:600"><?php echo $view['claim5']?></span>건</h4>
          </div>
       </div>
	  </div>
  </div>
<?php 

$orderSQL1 = "select  count(*) cnt
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then 1 else 0 end) 7days
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then 1 else 0 end) 6days
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then 1 else 0 end) 5days
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then 1 else 0 end) 4days
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then 1 else 0 end) 3days
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then 1 else 0 end) 2days
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then 1 else 0 end) 1days
            		
                    ,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then od_receipt_price else 0 end) 7days_od_receipt_price
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then od_receipt_price else 0 end) 6days_od_receipt_price
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then od_receipt_price else 0 end) 5days_od_receipt_price
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then od_receipt_price else 0 end) 4days_od_receipt_price
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then od_receipt_price else 0 end) 3days_od_receipt_price
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then od_receipt_price else 0 end) 2days_od_receipt_price
            		,sum(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then od_receipt_price else 0 end) 1days_od_receipt_price
            from 	lt_shop_order
            where	od_receipt_time >= '{$sday}'
            and		od_receipt_price > 0";

$orderchartdata1 = sql_fetch($orderSQL1);


$orderSQL2 = "select 	count(distinct 7days) 7days
                		,count(distinct 6days) 6days
                		,count(distinct 5days) 5days
                		,count(distinct 4days) 4days
                		,count(distinct 3days) 3days
                		,count(distinct 2days) 2days
                		,count(distinct 1days) 1days
                from (
                select  (case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then mb_id else null end) 7days
                		,(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then mb_id else null end) 6days
                		,(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then mb_id else null end) 5days
                		,(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then mb_id else null end) 4days
                		,(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then mb_id else null end) 3days
                		,(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then mb_id else null end) 2days
                		,(case when SUBSTRING(od_receipt_time, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then mb_id else null end) 1days
                from 	lt_shop_order
                where	od_receipt_time >= '{$sday}'
                and		od_receipt_price > 0 ) a";

$orderchartdata2 = sql_fetch($orderSQL2);
?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>매출현황</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
				<li role="presentation" class="active"><a href="#orderprice1" id="orderprice-tab1" role="tab" data-toggle="tab" aria-expanded="true">결제건수</a>
				</li>
				<li role="presentation" class=""><a href="#orderprice2" role="tab" id="orderprice-tab2" data-toggle="tab" aria-expanded="false">결제자수</a>
				</li>
				<li role="presentation" class=""><a href="#orderprice3" role="tab" id="orderprice-tab3" data-toggle="tab" aria-expanded="false">결제금액</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade active in" id="orderprice1" aria-labelledby="orderprice-tab1">
				<canvas id="lineChart_orderprice1"></canvas>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="orderprice2" aria-labelledby="orderprice-tab2">
				<canvas id="lineChart_orderprice2"></canvas>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="orderprice3" aria-labelledby="orderprice-tab3">
				<canvas id="lineChart_orderprice3"></canvas>
				</div>
			  </div>
<script>
if ($('#lineChart_orderprice1').length ){	
  var ctx = document.getElementById("lineChart_orderprice1");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "결제건수",
		backgroundColor: "rgba(38, 185, 154, 0)",
		borderColor: "rgba(38, 185, 154, 0.7)",
		pointBorderColor: "rgba(38, 185, 154, 0.7)",
		pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointBorderWidth: 1,
		data: [<?php echo $orderchartdata1['7days'].','.$orderchartdata1['6days'].','.$orderchartdata1['5days'].','.$orderchartdata1['4days'].','.$orderchartdata1['3days'].','.$orderchartdata1['2days'].','.$orderchartdata1['1days'] ?>]
	  }]
	},
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
        responsive: true   //auto size : true
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
	}
  });
}

if ($('#lineChart_orderprice2').length ){	
  var ctx = document.getElementById("lineChart_orderprice2");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "결제자수",
		backgroundColor: "rgba(38, 185, 154, 0)",
		borderColor: "rgba(38, 185, 154, 0.7)",
		pointBorderColor: "rgba(38, 185, 154, 0.7)",
		pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointBorderWidth: 1,
		data: [<?php echo $orderchartdata2['7days'].','.$orderchartdata2['6days'].','.$orderchartdata2['5days'].','.$orderchartdata2['4days'].','.$orderchartdata2['3days'].','.$orderchartdata2['2days'].','.$orderchartdata2['1days'] ?>]
	  }]
	}, 
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
       responsive: true   //auto size : true
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
	}
  });
}

if ($('#lineChart_orderprice3').length ){	
  var ctx = document.getElementById("lineChart_orderprice3");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "결제금액",
		backgroundColor: "rgba(38, 185, 154, 0)",
		borderColor: "rgba(38, 185, 154, 0.7)",
		pointBorderColor: "rgba(38, 185, 154, 0.7)",
		pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointBorderWidth: 1,
		data: [<?php echo $orderchartdata1['7days_od_receipt_price'].','.$orderchartdata1['6days_od_receipt_price'].','.$orderchartdata1['5days_od_receipt_price'].','.$orderchartdata1['4days_od_receipt_price'].','.$orderchartdata1['3days_od_receipt_price'].','.$orderchartdata1['2days_od_receipt_price'].','.$orderchartdata1['1days_od_receipt_price'] ?>]
	  }]
	},
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
        responsive: true   //auto size : true
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
                      return number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index])+" 원";
                 }
           }
      }
	}
  });
}

</script>
<style>
  #lineChart_orderprice1 {height : 411px !important;}
  #lineChart_member1 {height : 320px !important;}
</style>
			  
			</div>
          </div>
        </div>
      </div>

<!-- 사이트 현황 (방문자수) ver 1 2021-01-20
<?php 

$visitSQL = "select 	sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then vs_count else 0 end) 7days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then vs_count else 0 end) 6days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then vs_count else 0 end) 5days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then vs_count else 0 end) 4days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then vs_count else 0 end) 3days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then vs_count else 0 end) 2days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then vs_count else 0 end) 1days
                from 	lt_visit_sum
                where 	vs_date >= '{$sday}'
                order by vs_date desc";
$visitchartdata = sql_fetch($visitSQL);
?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>사이트 현황(방문자수)</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          	<canvas id="lineChart_visit"></canvas>
          	<script>
if ($('#lineChart_visit').length ){	
  var ctx = document.getElementById("lineChart_visit");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "방문자수",
		backgroundColor: "rgba(3, 88, 106, 0)",
		borderColor: "#3498DB",
		pointBorderColor: "rgba(3, 88, 106, 0.70)",
		pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(151,187,205,1)",
		pointBorderWidth: 1,
		data: [<?php echo $visitchartdata['7days'].','.$visitchartdata['6days'].','.$visitchartdata['5days'].','.$visitchartdata['4days'].','.$visitchartdata['3days'].','.$visitchartdata['2days'].','.$visitchartdata['1days'] ?>]
	  }]
	},
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
        responsive: true   //auto size : true
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
                      return number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index])+"명";
                 }
           }
      }
	}
  });
}
</script>
          </div>
        </div>
      </div>
	<div class="clearfix"></div> -->




<?php 

$visitSQL = "select 	sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then vs_count else 0 end) 7days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then vs_count else 0 end) 6days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then vs_count else 0 end) 5days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then vs_count else 0 end) 4days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then vs_count else 0 end) 3days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then vs_count else 0 end) 2days
                		,sum(case when SUBSTRING(vs_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then vs_count else 0 end) 1days
                from 	lt_visit_sum
                where 	vs_date >= '{$sday}'
                order by vs_date desc";
$visitchartdata = sql_fetch($visitSQL);

$ipgo_y = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_ipgo_status = 'Y' AND ps_code_year > 20 AND ps_display = 'Y'" ;
$ipgo_n = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_ipgo_status = 'N' AND ps_code_year > 20 AND ps_display = 'Y'";

$dpart_ipgo_y = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_ipgo_status = 'N' AND ps_dpart_stock = 'Y' AND ps_code_year > 20 AND ps_display = 'Y'";
$dpart_ipgo_n = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_ipgo_status = 'N' AND ps_dpart_stock = 'N' AND ps_code_year > 20 AND ps_display = 'Y'";

$shooting_y = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_shooting_yn = 'Y' AND ps_code_year > 20 AND ps_display = 'Y'";
$shooting_n = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_shooting_yn = 'N' AND ps_code_year > 20 AND ps_display = 'Y'";

$gumsu_y = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_gumsu = '100' AND ps_code_year > 20 AND ps_display = 'Y'";
$gumsu_n = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE (ps_gumsu = '400' OR (ps_gumsu = 200  and ps_gumsu_sub in (100,300))) AND ps_code_year > 20 AND ps_display = 'Y'";

$gumsu_sub_o = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_gumsu NOT IN  (100,200) AND ps_gumsu_sub = '400' AND ps_code_year > 20 AND ps_display = 'Y'";
$gumsu_sub_c = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_gumsu NOT IN  (100,200) AND ps_gumsu_sub = '100' AND ps_code_year > 20 AND ps_display = 'Y'";
// $gumsu_sub_o = "SELECT COUNT(*) AS CNT FROM lt_prod_info WHERE pi_gumsu <> '100' AND pi_gumsu <> '200' AND pi_gumsu_sub = '400' AND SUBSTRING(pi_model_no, 4, 2) > 20";
// $gumsu_sub_c = "SELECT COUNT(*) AS CNT FROM lt_prod_info WHERE pi_gumsu <> '100' AND pi_gumsu <> '200' AND pi_gumsu_sub = '100' AND SUBSTRING(pi_model_no, 4, 2) > 20";

$item_detail_y = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_item_detail ='100' AND ps_gumsu = '100' AND ps_code_year > 20 AND ps_display = 'Y'";
$item_detail_n = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_item_detail ='200' AND ps_gumsu = '100' AND ps_code_year > 20 AND ps_display = 'Y'";

$sabang_send_y = "SELECT COUNT(*) AS CNT  FROM lt_prod_info WHERE pi_sabang_send ='100' AND SUBSTRING(pi_model_no, 4, 2) > 20";
$sabang_send_n = "SELECT COUNT(*) AS CNT  FROM lt_prod_info WHERE pi_sabang_send ='200'AND pi_item_detail ='100' AND pi_gumsu = '100' AND SUBSTRING(pi_model_no, 4, 2) > 20 AND pi_company_it_id IS NOT NULL AND pi_company_it_id <> '' AND pi_item_detail = 100";

$reorder = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_re_order = 'Y' AND ps_code_year > 20 AND ps_display = 'Y'";

$price_fixed_y = "SELECT COUNT(*) AS CNT  FROM lt_job_order WHERE jo_price_fixed ='100' AND SUBSTRING(jo_prod_year, 3, 2) > 20";
$price_fixed_n = "SELECT COUNT(*) AS CNT  FROM lt_job_order WHERE jo_price_fixed ='200' AND SUBSTRING(jo_prod_year, 3, 2) > 20";

$gumsu_user_cnt = "SELECT uu.users AS reg_user , COUNT(uu.users) AS CNT FROM (
  SELECT jo.jo_user AS users
  FROM lt_prod_schedule AS ps LEFT JOIN lt_job_order AS jo ON jo.ps_id = ps.ps_id WHERE (ps.ps_gumsu = '400' OR (ps_gumsu = 200  AND ps_gumsu_sub IN (100,300))) AND ps.ps_code_year > 20 AND ps.ps_display = 'Y'
  GROUP BY jo.jo_user, ps.ps_id
  ) AS uu GROUP BY uu.users";

$webD = "SELECT COUNT(*) AS CNT FROM lt_prod_schedule WHERE ps_shooting_yn = 'Y' AND ps_code_year > 20 AND ps_display = 'Y' AND ps_item_detail =200 AND ps_online = 'Y' ";
$webD_r = sql_fetch($webD);

$dpart_ipgo_y_r = sql_fetch($dpart_ipgo_y);
$dpart_ipgo_n_r = sql_fetch($dpart_ipgo_n);

$ipgo_y_r = sql_fetch($ipgo_y);
$ipgo_n_r = sql_fetch($ipgo_n);

$shooting_y_r = sql_fetch($shooting_y);
$shooting_n_r = sql_fetch($shooting_n);

$gumsu_y_r = sql_fetch($gumsu_y);
$gumsu_n_r = sql_fetch($gumsu_n);
$gumsu_sub_o_r = sql_fetch($gumsu_sub_o);
$gumsu_sub_c_r = sql_fetch($gumsu_sub_c);

$item_detail_y_r = sql_fetch($item_detail_y);
$item_detail_n_r = sql_fetch($item_detail_n);

$sabang_send_y_r = sql_fetch($sabang_send_y);
$sabang_send_n_r = sql_fetch($sabang_send_n);

$reorder_r = sql_fetch($reorder);

$price_fixed_y_r = sql_fetch($price_fixed_y);
$price_fixed_n_r = sql_fetch($price_fixed_n);

$gumsu_users = sql_query($gumsu_user_cnt);

?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>상품정보집 진행현황<span style="font-size: 14px; ">(2020년도 이후 기준)<span></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div style="font-size: 18px; color : #000;">
              <br>
              <br>
              <div>입고확정 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=Y&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                  <sqan style=" color : blue;">완료 : <?=$ipgo_y_r['CNT']?> 건</span></a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=N&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                  <sqan style="color : red;">미완료 : <?=$ipgo_n_r['CNT']?> 건</span></a>
                )
              </div>
              <br>
              <div>창고입고 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=N&dpart_ipgos=Y&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                  <sqan style=" color : blue;">완료 : <?=$dpart_ipgo_y_r['CNT']?> 건</span></a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=N&dpart_ipgos=N&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                  <sqan style="color : red;">미완료 : <?=$dpart_ipgo_n_r['CNT']?> 건</span></a>
                )
              </div>
              <br>
              <div>촬영 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=Y&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : blue;">완료 : <?=$shooting_y_r['CNT']?> 건</span> </a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=N&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : red;">미완료 : <?=$shooting_n_r['CNT']?> 건</span></a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=Y&gumsus=&gumsu_subs=&item_details=N&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : #f0ad4e;">미등록 : <?=$webD_r['CNT']?> 건</span></a>
                )
              </div>
              <br>
              <div>검수여부 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=N&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : red;">미검수 : <?=$gumsu_n_r['CNT']?> 건</span> </a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=Y&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : blue;">검수완료 : <?=$gumsu_y_r['CNT']?> 건</span></a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=N&gumsu_subs=N&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style="color : #f0ad4e;">수정요청 : <?=$gumsu_sub_o_r['CNT']?> 건</span> </a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=N&gumsu_subs=Y&item_details=&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style="color : #5cb85c;">수정완료 : <?=$gumsu_sub_c_r['CNT']?> 건</span></a>
                )
                
                <button class="btn btn-dark" onclick="pop_insert_user()">상세</button>

              </div>
              <br>
              <div>상세기술서 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=Y&gumsu_subs=&item_details=Y&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : blue;">완료 : <?=$item_detail_y_r['CNT']?> 건</span> </a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=Y&gumsu_subs=&item_details=N&sabangs=&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : red;">미완료 : <?=$item_detail_n_r['CNT']?> 건</span></a>
                )
              </div>
              <br>
              <div>사방넷등록여부 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=Y&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : blue;">완료 : <?=$sabang_send_y_r['CNT']?> 건</span> </a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=Y&gumsu_subs=&item_details=Y&sabangs=N&fixeds=&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : red;">미완료 : <?=$sabang_send_n_r['CNT']?> 건</span></a>
                )
              </div>
              <br>
              <div>리오더여부 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=N%2CY&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=Y&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : blue;">리오더 : <?=$reorder_r['CNT']?> 건</span> </a>
              )</div>
              <br>
              <div>원가확정여부 (
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=Y&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : blue;">완료 : <?=$price_fixed_y_r['CNT']?> 건</span> </a>
                <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=&gumsu_subs=&item_details=&sabangs=&fixeds=N&reorders=&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
                <sqan style=" color : red;">미완료 : <?=$price_fixed_n_r['CNT']?> 건</span></a>
              )</div>
              <br>
            </div>

<div class="modal fade" id="insert_user" tabindex="-1" role="dialog" aria-labelledby="insert_user">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">담당자 구분</h4>
      </div>
      <div class="modal-body">
        <?php 
        for ($gsu=0; $row=sql_fetch_array($gumsu_users); $gsu++){
          if(!empty($row['reg_user'])) {
          ?>
            <div> 
              <a href="/adm/shop_admin/new_goods/new_goods_process.php?tabs=list&brands=&code_year=>2021&code_season=&ipgos=&dpart_ipgos=&shootings=&gumsus=N&gumsu_subs=&item_details=&sabangs=&fixeds=&reorders=&ps_user_chks=<?=$row['reg_user']?>&folds=down&sfl=it_name&stx=&sc_it_time=&ps_code_year=>2021&ps_code_season=&limit_list=10">
              <?=$row['reg_user']?> : <?=$row['CNT']?> 건 </a>
            </div>
          <?php 
          }
        }
        ?>
          
      </div>
      <div class="modal-footer">
          <div class="col-md-12 col-sm-12 col-xs-12 text-center">
              <button class="btn btn_02" type="button" data-dismiss="modal"  id="btn_cancel">확인</button>
          </div>
      </div>
    </div>
  </div>
</div>

          	<!-- <canvas id="lineChart_visit"></canvas> -->
          	<!-- <script>
if ($('#lineChart_visit').length ){	
  var ctx = document.getElementById("lineChart_visit");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "방문자수",
		backgroundColor: "rgba(3, 88, 106, 0)",
		borderColor: "#3498DB",
		pointBorderColor: "rgba(3, 88, 106, 0.70)",
		pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(151,187,205,1)",
		pointBorderWidth: 1,
		data: [<?php echo $visitchartdata['7days'].','.$visitchartdata['6days'].','.$visitchartdata['5days'].','.$visitchartdata['4days'].','.$visitchartdata['3days'].','.$visitchartdata['2days'].','.$visitchartdata['1days'] ?>]
	  }]
	},
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
        responsive: true   //auto size : true
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
                      return number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index])+"명";
                 }
           }
      }
	}
  });
}
</script> -->
          </div>
        </div>
      </div>
	<div class="clearfix"></div>


<?php 

$memberSQL1 = "select 	sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then 1 else 0 end) 7days
                        ,sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then 1 else 0 end) 6days
                        ,sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then 1 else 0 end) 5days
                        ,sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then 1 else 0 end) 4days
                        ,sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then 1 else 0 end) 3days
                        ,sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then 1 else 0 end) 2days
                        ,sum(case when SUBSTRING(mb_open_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then 1 else 0 end) 1days
                from 	lt_member
                where 	mb_open_date >= '{$sday}'
                ";
$memberchartdata1 = sql_fetch($memberSQL1);


$memberSQL2 = "select 	sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then 1 else 0 end) 7days
                		,sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then 1 else 0 end) 6days
                		,sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then 1 else 0 end) 5days
                		,sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then 1 else 0 end) 4days
                		,sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then 1 else 0 end) 3days
                		,sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then 1 else 0 end) 2days
                		,sum(case when SUBSTRING(mb_today_login, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then 1 else 0 end) 1days
                from 	lt_member
                where 	mb_today_login >= '{$sday}'
                ";
$memberchartdata2 = sql_fetch($memberSQL2);

$memberSQL3 = "select 	sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -6 DAY),1,10) then 1 else 0 end) 7days
                		,sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -5 DAY),1,10) then 1 else 0 end) 6days
                		,sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -4 DAY),1,10) then 1 else 0 end) 5days
                		,sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -3 DAY),1,10) then 1 else 0 end) 4days
                		,sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10) then 1 else 0 end) 3days
                		,sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL -1 DAY),1,10) then 1 else 0 end) 2days
                		,sum(case when SUBSTRING(mb_leave_date, 1, 10) = SUBSTRING(date_add(now(), INTERVAL 0 DAY),1,10) then 1 else 0 end) 1days
                from 	lt_member
                where 	mb_leave_date >= '{$sday}' and		mb_leave_date <> ''
                ";
$memberchartdata3 = sql_fetch($memberSQL3);

?>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>회원현황</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
				<li role="presentation" class="active"><a href="#member1" id="member-tab1" role="tab" data-toggle="tab" aria-expanded="true">가입회원</a>
				</li>
				<li role="presentation" class=""><a href="#member2" role="tab" id="member-tab2" data-toggle="tab" aria-expanded="false">방문회원</a>
				</li>
				<li role="presentation" class=""><a href="#member3" role="tab" id="member-tab3" data-toggle="tab" aria-expanded="false">탈퇴회원</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade active in" id="member1" aria-labelledby="member-tab1">
				<canvas id="lineChart_member1"></canvas>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="member2" aria-labelledby="member-tab2">
				<canvas id="lineChart_member2"></canvas>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="member3" aria-labelledby="member-tab3">
				<canvas id="lineChart_member3"></canvas>
				</div>
			  </div>

<script>
if ($('#lineChart_member1').length ){	
  var ctx = document.getElementById("lineChart_member1");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "가입회원수",
		backgroundColor: "rgba(38, 185, 154, 0)",
		borderColor: "rgba(38, 185, 154, 0.7)",
		pointBorderColor: "rgba(38, 185, 154, 0.7)",
		pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointBorderWidth: 1,
		data: [<?php echo $memberchartdata1['7days'].','.$memberchartdata1['6days'].','.$memberchartdata1['5days'].','.$memberchartdata1['4days'].','.$memberchartdata1['3days'].','.$memberchartdata1['2days'].','.$memberchartdata1['1days'] ?>]
	  }]
	},
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
        responsive: true   //auto size : true
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
	}
  });
}

if ($('#lineChart_member2').length ){	
  var ctx = document.getElementById("lineChart_member2");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "방문회원수",
		backgroundColor: "rgba(38, 185, 154, 0)",
		borderColor: "rgba(38, 185, 154, 0.7)",
		pointBorderColor: "rgba(38, 185, 154, 0.7)",
		pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointBorderWidth: 1,
		data: [<?php echo $memberchartdata2['7days'].','.$memberchartdata2['6days'].','.$memberchartdata2['5days'].','.$memberchartdata2['4days'].','.$memberchartdata2['3days'].','.$memberchartdata2['2days'].','.$memberchartdata2['1days'] ?>]
	  }]
	}, 
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
       responsive: true   //auto size : true
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
	}
  });
}

if ($('#lineChart_member3').length ){	
  var ctx = document.getElementById("lineChart_member3");
  var lineChart = new Chart(ctx, {
	type: 'line',
	data: {
	  labels: <?php echo json_encode($days); ?>,
	  datasets: [{
		label: "탈퇴회원수",
		backgroundColor: "rgba(38, 185, 154, 0)",
		borderColor: "rgba(38, 185, 154, 0.7)",
		pointBorderColor: "rgba(38, 185, 154, 0.7)",
		pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
		pointHoverBackgroundColor: "#fff",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointBorderWidth: 1,
		data: [<?php echo $memberchartdata3['7days'].','.$memberchartdata3['6days'].','.$memberchartdata3['5days'].','.$memberchartdata3['4days'].','.$$memberchartdata3['3days'].','.$memberchartdata3['2days'].','.$memberchartdata3['1days'] ?>]
	  }]
	},
	options: {
		// responsive, maintainAspectRatio의 설정이 아래와 같이 해야
		// 브라우저의 크기를 변경해도 canvas를 감싸고 있는
		// div의 크기에 따라 차트가 깨지지 않고 이쁘게 출력 됩니다. 
        responsive: true   //auto size : true
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
	}
  });
}
function pop_insert_user(){
  $("#insert_user").modal('show');
}
</script>
			  
			</div>	
          </div>
        </div>
      </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>문의 현황</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#qa2" role="tab" id="qa-tab2" data-toggle="tab" aria-expanded="true">1:1문의</a>
				</li>
				<li role="presentation" class=""><a href="#qa1" id="qa-tab1" role="tab" data-toggle="tab" aria-expanded="false">상품</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade" id="qa1" aria-labelledby="qa-tab1">
        			  
                    <div class="tbl_frm01">
                        <table>
                		<colgroup>
                			<col style="width:10%;">
                			<col style="width:70%;">
                			<col style="width:20%;">
                		</colgroup>
                        <tbody>
                        <?php
                        // $sql = " select 	a.*
                        //         		,if(qa_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                        //         from 	lt_qa_content as a
                        //         where   qa_type = '0' 
                        //         and qa_category in ('제품','리스')
                        //         order by qa_num 
                        //         limit 5 ";
                        $sql = " select a.* ,if(iq_time >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk from lt_shop_item_qa AS a order by iq_id DESC LIMIT 5 ";
                        $result = sql_query($sql);
                        
                        for ($i=0; $row=sql_fetch_array($result); $i++)
                        {
                            $row['category'] = get_text($row['iq_category']);
                            $row['subject'] = conv_subject($row['iq_subject'], $subject_len, '…');
                        ?>
                        <tr>
                	        <td><?php echo ($row['newchk']=='new' ? '<span class="label label-danger">NEW</span>' : ''); ?></td>
                            <td class="text-left">
                                [<?php echo $row['category'] ?>] <?php echo $row['subject']; ?>
                                <?php echo ($row['iq_answer'] ? '<span class="label label-success">답변완료</span>' : '<span class="label label-warning">답변대기</span>'); ?><br/>
                                <span class="mb_leave_msg"><?php echo substr($row['qa_datetime'],0,16); ?></span>
                            </td>
                            <td><a href="./shop_admin/itemqalist.php?sfl=a.it_id&stx=<?php echo $row['it_id']?>"><button class="btn  pull-right">보기</button></a></td>
                        </tr>
                        <?php }
                            if ($i == 0) {
                                echo '<tr><td colspan="3" class="empty_table">등록된 자료가 없습니다.</td></tr>';
                            }?>
                        </tbody>
                        </table>
        			</div>
        			
				</div>
				<div role="tabpanel" class="tab-pane fade active in" id="qa2" aria-labelledby="qa-tab2">
					<div class="tbl_frm01">
                        <table>
                		<colgroup>
        			<col style="width:10%;">
        			<col style="width:70%;">
                			<col style="width:20%;">
                		</colgroup>
                        <tbody>
                        <?php
                        // $sql = " select 	a.*
                        //         		,if(qa_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                        //         from 	lt_qa_content as a
                        //         where   qa_type = '0' 
                        //         and qa_category not in ('제품','리스')
                        //         order by qa_num 
                        //         limit 5 ";
                        $sql = " select 	a.*
                                ,if(qa_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                            from 	lt_qa_content as a
                            where   qa_type = '0' 
                            order by qa_num 
                            limit 5 ";
                        $result = sql_query($sql);
                        
                        for ($i=0; $row=sql_fetch_array($result); $i++)
                        {
                            $row['category'] = get_text($row['qa_category']);
                            $row['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
                        ?>
                        <tr>
                	        <td><?php echo ($row['newchk']=='new' ? '<span class="label label-danger">NEW</span>' : ''); ?></td>
                            <td class="text-left">
                                [<?php echo $row['category'] ?>] <?php echo $row['subject']; ?>
                                <?php echo ($row['qa_status'] ? '<span class="label label-success">답변완료</span>' : '<span class="label label-warning">답변대기</span>'); ?><br/>
                                <span class="mb_leave_msg"><?php echo substr($row['qa_datetime'],0,16); ?></span>
                            </td>
                            <td><a href="./community/help_detail.php?qa_id=<?php echo $row['qa_id']?>"><button class="btn  pull-right">보기</button></a></td>
                        </tr>
                        <?php }
                            if ($i == 0) {
                                echo '<tr><td colspan="3" class="empty_table">등록된 자료가 없습니다.</td></tr>';
                            }?>
                        </tbody>
                        </table>
        			</div>
				</div>
			  </div>
          </div>
        </div>
      </div>
	</div>
	
	<div class="clearfix"></div>
    <!-- <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>적립금 수기 지급</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <?php
            $sql_common = " from {$g5['point_table']} a ";
            $sql_search = " where po_request_id != '' ";
            $sql_order = " order by po_id desc ";
            
            $sql = " select *
                            ,if(po_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                            {$sql_common} {$sql_search} {$sql_order} limit {$new_point_rows} ";
            $result = sql_query($sql);
            ?>
			<div class="tbl_frm01">
                <table>
        		<colgroup>
        			<col style="width:10%;">
        			<col style="width:70%;">
        			<col style="width:20%;">
        		</colgroup>
                <tbody>
                <?php
                
                for ($i=0; $row=sql_fetch_array($result); $i++)
                {
                    $row['subject'] = conv_subject($row['po_content'], $subject_len, '…');
                ?>
                <tr>
        	        <td><?php echo ($row['newchk']=='new' ? '<span class="label label-danger">NEW</span>' : ''); ?></td>
                    <td class="text-left">
                        <?php echo $row['subject']; ?>
                        <?php 
                            echo '<span class="label ';
                            if($row['po_rel_action'] == "요청완료") echo "label-warning";
                            else if($row['po_rel_action'] == "처리완료") echo "label-success";
                            else if($row['po_rel_action'] == "반려") echo "label-danger";
                            
                            echo '">'.$row['po_rel_action'].'</span>'; 
                        ?><br/>
                        <span class="mb_leave_msg"><?php echo substr($row['po_datetime'],0,16); ?> <?php echo $row['po_request_id']?></span>
                    </td>
                    <td><a href="./operation/configform_saveMoney_handwriting.php?sfl=mb_id&stx=<?php echo $row['mb_id']?>&po_id=<?php echo $row['po_id']?>"><button class="btn  pull-right">보기</button></a></td>
                </tr>
                <?php }
                            if ($i == 0) {
                                echo '<tr><td colspan="3" class="empty_table">등록된 자료가 없습니다.</td></tr>';
                            }?>
                </tbody>
                </table>
    		</div>
          </div>
        </div>
      </div> -->

    <!-- <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>브랜드 승인</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          	
          <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
              <li role="presentation" class="active"><a href="#brand1" id="brand-tab1" role="tab" data-toggle="tab" aria-expanded="true">입정사승인</a>
              </li>
              <li role="presentation" class=""><a href="#brand2" role="tab" id="brand-tab2" data-toggle="tab" aria-expanded="false">상품승인</a>
              </li>
            </ul>
            <div class="clearfix"></div>

            <div id="myTabContent" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="brand1" aria-labelledby="brand-tab1">
                <div class="tbl_frm01">
                  <table>
                  <colgroup>
                    <col style="width:10%;">
                    <col style="width:70%;">
                    <col style="width:20%;">
                  </colgroup>
                    <tbody>
                      <?php
                      $sql = " select *
                          ,if(po_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                          {$sql_common} {$sql_search} {$sql_order} limit {$new_point_rows} ";
                          $result = sql_query($sql);
                      for ($i=0; false && $row=sql_fetch_array($result); $i++)
                      {
                          $row['subject'] = conv_subject($row['po_content'], $subject_len, '…');
                      ?>
                      <tr>
                        <td><?php echo ($row['newchk']=='new' ? '<span class="label label-danger">NEW</span>' : ''); ?></td>
                          <td class="text-left">
                              <?php echo $row['subject']; ?>
                              <?php 
                                  echo '<span class="label label-warning">신규승인,정보변경승인</span>&nbsp;'; 
                                  
                                  echo '<span class="label ';
                                  if($row['po_rel_action'] == "요청완료") echo "label-warning"; //승인대기
                                  else if($row['po_rel_action'] == "처리완료") echo "label-success"; //승인완료
                                  else if($row['po_rel_action'] == "반려") echo "label-danger";
                                  
                                  echo '">'.$row['po_rel_action'].'</span>'; 
                              ?><br/>
                              <span class="mb_leave_msg"><?php echo substr($row['po_datetime'],0,16); ?> <?php echo $row['po_request_id']?></span>
                          </td>
                          <td><a href="./operation/configform_saveMoney_handwriting.php?sfl=mb_id&stx=<?php echo $row['mb_id']?>&po_id=<?php echo $row['po_id']?>"><button class="btn  pull-right">보기</button></a></td>
                      </tr>
                      <?php } 
                          if ($i == 0) {
                              echo '<tr><td colspan="3" class="empty_table">등록된 자료가 없습니다.</td></tr>';
                          }?>
                      </tbody>
                  </table>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="brand2" aria-labelledby="brand-tab2">
                <div class="tbl_frm01">
                  <table>
                    <colgroup>
                      <col style="width:10%;">
                      <col style="width:70%;">
                      <col style="width:20%;">
                    </colgroup>
                    <tbody>
                      <?php
                      $sql = " select *
                          ,if(po_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                          {$sql_common} {$sql_search} {$sql_order} limit {$new_point_rows} ";
                          $result = sql_query($sql);
                      for ($i=0; false && $row=sql_fetch_array($result); $i++)
                      {
                          $row['subject'] = conv_subject($row['po_content'], $subject_len, '…');
                      ?>
                      <tr>
                        <td><?php echo ($row['newchk']=='new' ? '<span class="label label-danger">NEW</span>' : ''); ?></td>
                          <td class="text-left">
                              <?php echo $row['subject']; ?>
                              <?php 
                                  echo '<span class="label label-warning">신규상품승인,수정상품승인</span>&nbsp;'; 
                                  
                                  echo '<span class="label ';
                                  if($row['po_rel_action'] == "요청완료") echo "label-warning"; //승인대기
                                  else if($row['po_rel_action'] == "처리완료") echo "label-success"; //승인완료
                                  else if($row['po_rel_action'] == "반려") echo "label-danger";
                                  
                                  echo '">'.$row['po_rel_action'].'</span>'; 
                              ?><br/>
                              <span class="mb_leave_msg"><?php echo substr($row['po_datetime'],0,16); ?> <?php echo $row['po_request_id']?></span>
                          </td>
                          <td><a href="./operation/configform_saveMoney_handwriting.php?sfl=mb_id&stx=<?php echo $row['mb_id']?>&po_id=<?php echo $row['po_id']?>"><button class="btn  pull-right">보기</button></a></td>
                      </tr>
                      <?php }
                          if ($i == 0) {
                              echo '<tr><td colspan="3" class="empty_table">등록된 자료가 없습니다.</td></tr>';
                          }?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <style>
      .new_goods_list_more{
        float: right;
        font-size: 15px;
        line-height: 33px;
        border: 1px solid #333333;
        width: 75px;
        text-align: center;
        color: #333333 !important;
        cursor: pointer;
        height: 30px;
        margin-top: 9px;
      }

    </style>
    

</div>

<?php
include_once ('./admin.tail.php');
?>
