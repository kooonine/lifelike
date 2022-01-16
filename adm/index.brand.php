<?php
//$sub_menu = '100000';
$sub_menu = '10';

$mb_id = $member['mb_id'];

$sql = "select * from lt_member_company where mb_id = '{$mb_id}' ";
$cp = sql_fetch($sql);
if(!$cp)
{
    goto_url(G5_ADMIN_URL."/brand/company.php");
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
        		,sum(case when od_status in ('배송완료','구매완료') and od_invoice != '' then 1 else 0 end) invoice_compl
                
                ,sum(case when od_status_claim = '주문취소' then 1 else 0 end) claim1
                ,sum(case when od_status_claim = '교환' then 1 else 0 end) claim2
        from 	lt_shop_order
        where	od_time >= '{$sday}'
        and     company_code = '{$cp['company_code']}' ";

$view = sql_fetch($sql);

$g5['title'] = '대시보드';
include_once ('./admin.head.php');
?>
<script src="./vendors/Chart.js/dist/Chart.min.js"></script>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
    	<div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
            	<th>입점사명</th>
            	<td><?php echo $cp['company_name']?></td>
            	<th>입점사 코드</th>
            	<td><?php echo $cp['company_code']?></td>
        	</tr>
            <tr>
            	<th>수수료</th>
            	<td><?php echo $cp['cp_commission']?>%</td>
            	<th>정산일</th>
            	<td>익월<?php echo $cp['cp_calculate_date']?>일</td>
        	</tr>
        	<?php if($cp['cp_status'] == "승인요청" || $cp['cp_status'] == "승인반려") { ?>
            <tr>
            	<th>가입상태</th>
            	<td><?php echo $cp['cp_status']?></td>
            	<th>사유</th>
            	<td><?php echo $cp['cp_reason']?></td>
        	</tr>
        	<?php } ?>
        	</tbody>
        	</table>
		</div>
		
    	<?php if($cp['cp_status'] == "승인반려") { ?>
        <div class="pull-right">
            <a href="<?php echo G5_ADMIN_URL?>/brand/company.php?w=c"><input type="button" value="재심사요청" class="btn_submit btn" ></a>
        </div>
    	<?php } ?>
	</div>
  </div>
</div>
<?php if($cp['cp_status'] == "승인요청" || $cp['cp_status'] == "승인반려") { 
    include_once ('./admin.tail.php');
    exit;    
}?>

<!-- top tiles -->
<div class="row tile_count">

	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;" onclick="location.href='./shop_admin/orderlist.brand.php';">
            	<h3>신규주문 <span class="bold" style="font-weight:600"><?php echo ($view['neworder'])?$view['neworder']:"0"?></span>건</h3>
            	<h3>금일출발 <span class="bold" style="font-weight:600"><?php echo ($view['todayinvoice'])?$view['todayinvoice']:"0"?></span>건</h3>
        	</div>
        	</div>
		</div>
    </div>
	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon glyphicon-plane" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;" onclick="location.href='./shop_admin/orderlist.brand.php';">
            	<h3 class="name_title">배송중 <span class="bold" style="font-weight:600"><?php echo ($view['invoice_ing'])?$view['invoice_ing']:"0"?></span>건</h3>
            	<h3 class="name_title">배송완료 <span class="bold" style="font-weight:600"><?php echo ($view['invoice_compl'])?$view['invoice_compl']:"0"?></span>건</h3>
        	</div>
        	</div>
		</div>
    </div>
	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;" onclick="location.href='./shop_admin/claimlist.brand.php';">
            	<h3 class="name_title">취소요청 <span class="bold" style="font-weight:600"><?php echo ($view['claim1'])?$view['claim1']:"0"?></span>건</h3>
            	<h3 class="name_title">반품요청 <span class="bold" style="font-weight:600"><?php echo ($view['claim3'])?$view['claim3']:"0"?></span>건</h3>
        	</div>
        	</div>
		</div>
    </div>
	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
			<span class="glyphicon glyphicon glyphicon-check" aria-hidden="true" style="font-size:80px;float: left"></span>
        	<div style="float: right;height:120px;vertical-align: middle;text-align: right;cursor: pointer;" onclick="location.href='./brand/company_cal_list.php';">
            	<h3 class="name_title">정산예정 <span class="bold" style="font-weight:600">0</span>원</h3>
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
            and		od_receipt_price > 0 and company_code = '{$cp['company_code']}' ";

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
                and		od_receipt_price > 0 and company_code = '{$cp['company_code']}' ) a";

$orderchartdata2 = sql_fetch($orderSQL2);
?>
    <div class="col-md-12 col-sm-12 col-xs-12">
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
			  
			</div>
          </div>
        </div>
      </div>
      
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>공지 사항</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
				<div class="tab-pane fade active in" >
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                		<colgroup>
                			<col style="width:80%;">
                			<col style="width:20%;">
                		</colgroup>
                		<thead>
                        <tr>
                            <th scope="col">제목</th>
                            <th scope="col">작성일</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = " select 	a.*
                                		,if(wr_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                                from 	lt_write_mallnotice as a
                                where   ( wr_1 = '1' or  (length(wr_1) = 16 and substring(wr_1,1,16) <= now()) or  (length(wr_1) = 33 and now() BETWEEN substring(wr_1,1,16) and substring(wr_1,18,16)))
                                order by wr_num, wr_reply 
                                limit 5 ";
                        $result = sql_query($sql);
                        
                        for ($i=0; $row=sql_fetch_array($result); $i++)
                        {
                            $row['subject'] = conv_subject($row['wr_subject'], $subject_len, '…');
                        ?>
                        <tr>
                            <td class="text-left">
                            	<a href="./brand/company_notice_view.php?bo_table=mallnotice&wr_id=<?php echo $row['wr_id']?>">
                            	<?php echo ($row['newchk']=='new' ? '<span class="label label-danger">NEW</span>' : ''); ?>
                                <?php echo $row['subject']; ?>
                                </a>
                            </td>
                            <td><span class="mb_leave_msg"><?php echo substr($row['wr_datetime'],0,16); ?></span></td>
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

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>문의 현황</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
				<li role="presentation" class="active"><a href="#qa1" id="qa-tab1" role="tab" data-toggle="tab" aria-expanded="true">상품</a>
				</li>
				<li role="presentation" class=""><a href="#qa2" role="tab" id="qa-tab2" data-toggle="tab" aria-expanded="false">1:1문의</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade active in" id="qa1" aria-labelledby="qa-tab1">
        			  
                    <div class="tbl_frm01">
                        <table>
                		<colgroup>
                			<col style="width:10%;">
                			<col style="width:70%;">
                			<col style="width:20%;">
                		</colgroup>
                        <tbody>
                        <?php
                        $sql = " select 	a.*
                                		,if(qa_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                                from 	lt_qa_content as a
                                where   qa_type = '0' 
                                and INSTR(qa_category, '상품') > 0
                                and it_id in (select it_id from lt_shop_item where ca_id3 != '' and ca_id3 = '".$cp['company_code']."')
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
				<div role="tabpanel" class="tab-pane fade" id="qa2" aria-labelledby="qa-tab2">
					<div class="tbl_frm01">
                        <table>
                		<colgroup>
        			<col style="width:10%;">
        			<col style="width:70%;">
                			<col style="width:20%;">
                		</colgroup>
                        <tbody>
                        <?php
                        $sql = " select 	a.*
                                		,if(qa_datetime >=SUBSTRING(date_add(now(), INTERVAL -2 DAY),1,10),'new','') as newchk
                                from 	lt_qa_content as a
                                where   qa_type = '0' 
                                and INSTR(qa_category, '상품') <= 0
                                and it_id in (select it_id from lt_shop_item where ca_id3 != '' and ca_id3 = '".$cp['company_code']."')
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
	
</div>

<?php
include_once ('./admin.tail.php');
?>
