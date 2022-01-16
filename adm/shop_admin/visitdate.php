<?php
$sub_menu = '50';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");
$where = array();

if ($sc_od_time != "") {
	$sc_od_times = explode("~", $sc_od_time);
	$fr_date = trim($sc_od_times[0]);
	$to_date = trim($sc_od_times[1]);
}

if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if ($fr_date && $to_date) {
	$where[] = " od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
} else {
	$fr_date = date_create(G5_TIME_YMD);
	date_add($fr_date, date_interval_create_from_date_string('-6 days'));
	$fr_date = date_format($fr_date, "Y-m-d");

	$to_date = G5_TIME_YMD;
}

$g5['title'] = $fr_date . ' ~ ' . $to_date . ' 일간 접속통계';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<div class="x_panel">
	<form name="frm_sale_date" method="get" id="frm_sale_date">
		<input type="hidden" name="od_mobile" value="<?=$od_mobile?>">
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
							<input type='text' class="form-control" id="sc_od_time" name="sc_od_time" value="" />
							<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
						</div>
						<div class="btn-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
							<button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
							<button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
							<button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">검색메뉴</th>
					<td>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<?php if (!isset($sc_page_type)) $sc_page_type = "1"; ?>
							<label><input type="radio" name="sc_page_type" id="sc_page_type0" value="0" <?php echo get_checked($sc_page_type, "0") ?>> 메인</label>
							<label><input type="radio" name="sc_page_type" id="sc_page_type1" value="1" <?php echo get_checked($sc_page_type, "1") ?>> 메뉴별</label>

							<select name="sc_page1" id="sc_page1">
								<option value="">----전체-----</option>
								<?php
								$sql1 = " select * from {$g5['menu_table']} where length(me_code) = 2 and me_use = '1' order by me_depth, me_order, me_code ";
								$result1 = sql_query($sql1);
								for ($i = 0; $row1 = sql_fetch_array($result1); $i++) {
									echo '<option value="' . $row1['me_code'] . '" ' . get_selected($sc_page1, $row1['me_code']) . '>' . $row1['me_name'] . '</option>' . PHP_EOL;
								}
								?>
							</select>
							<select name="sc_page2" id="sc_page2">
								<option value="">----전체-----</option>
								<?php
								if ($sc_page1) {
									$sql1 = " select * from {$g5['menu_table']} where length(me_code) = 4 and me_use = '1' and me_code like '{$sc_page1}%' order by substr(me_code,1,2), me_order, me_code ";
									$result1 = sql_query($sql1);
									for ($i = 0; $row1 = sql_fetch_array($result1); $i++) {
										echo '<option value="' . $row1['me_code'] . '" ' . get_selected($sc_page2, $row1['me_code']) . '>' . $row1['me_name'] . '</option>' . PHP_EOL;
									}
								}
								?>
							</select>
							<select name="sc_page3" id="sc_page3">
								<option value="">----전체-----</option>
								<?php
								if ($sc_page2) {
									$sql1 = " select * from {$g5['menu_table']} where length(me_code) = 6 and me_use = '1' and me_code like '{$sc_page2}%' order by substr(me_code,1,4), me_order, me_code ";
									$result1 = sql_query($sql1);
									for ($i = 0; $row1 = sql_fetch_array($result1); $i++) {
										echo '<option value="' . $row1['me_code'] . '" ' . get_selected($sc_page3, $row1['me_code']) . '>' . $row1['me_name'] . '</option>' . PHP_EOL;
									}
								}
								?>
							</select>
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
	<div class="" role="tabpanel" data-example-id="togglable-tabs">
		<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
			<li role="presentation" class="<?php echo ($od_mobile == "" ? "active" : "") ?>"><a href="./visitdate.php?od_mobile=&sc_od_time=<?=$fr_date . '+~+' . $to_date?>">전체</a></li>
			<li role="presentation" class="<?php echo ($od_mobile == "0" ? "active" : "") ?>"><a href="./visitdate.php?od_mobile=0&sc_od_time=<?=$fr_date . '+~+' . $to_date?>">PC</a></li>
			<li role="presentation" class="<?php echo ($od_mobile == "1" ? "active" : "") ?>"><a href="./visitdate.php?od_mobile=1&sc_od_time=<?=$fr_date . '+~+' . $to_date?>">모바일</a></li>
			<li role="presentation" class="<?php echo ($od_mobile == "2" ? "active" : "") ?>"><a href="./visitdate.php?od_mobile=2&sc_od_time=<?=$fr_date . '+~+' . $to_date?>">APP</a></li>
		</ul>
		<div class="clearfix"></div>
	</div>

	<div class="x_title">
		<h4>페이지뷰(PV)</h4>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<canvas id="saledateChart"></canvas>
	</div>

	<div class="x_title">
		<h4>방문자수(UV)</h4>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<canvas id="saledateChart1"></canvas>
	</div>

	<div class="x_title">
		<h4>방문자수(PV/%)</h4>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<canvas id="saledateChart2"></canvas>
	</div>

</div>

<?php
$days = array();
$totall = array();

$sql = " select  substring(vi_date,1,10) vi_date, count(vi_id) cnt
        from    lt_visit_page
      where vi_date between '$fr_date' and '$to_date' ";

if (isset($od_mobile) && $od_mobile != '') {

	if ($od_mobile == "0") $sql .= " and vi_device = 'Desktop' ";
	if ($od_mobile == "1") $sql .= " and vi_device like 'Mobile%' ";
	if ($od_mobile == "2") $sql .= " and vi_device = 'App' ";
}

if ($sc_page_type == "0") {
	$sql .= " and vi_referer = '/index.php' ";
} elseif (isset($sc_page1) && $sc_page1 != "" && isset($sc_page2) && isset($sc_page3)) {
	$sql .= " and vi_referer in (
                select me_link from lt_menu where me_code like '{$sc_page1}%' and me_code like '{$sc_page2}%' and me_code like '{$sc_page3}%' and me_use = '1' and me_link not in ('','#')
                )
             ";
}
$sql .= "group by vi_date order by vi_date asc ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$days[] = substr($row['vi_date'], 5, 5);
	$totall[] = $row['cnt'];
}

$days1 = array();
$totall1 = array();
$tot0 = array();
$tot1 = array();

$sql = " select substring(vi_date,1,10) vi_date, count(vi_id) as cnt, sum(if(vi_new=1,1,0)) vi_new, sum(if(vi_new=0,1,0)) vi_old
        from  lt_visit
      where vi_date between '$fr_date' and '$to_date' ";

if (isset($od_mobile) && $od_mobile != '') {

	if ($od_mobile == "0") $sql .= " and vi_device = 'Desktop' ";
	if ($od_mobile == "1") $sql .= " and vi_device like 'Mobile%' ";
	if ($od_mobile == "2") $sql .= " and vi_device = 'App' ";
}
$sql .= "group by vi_date order by vi_date asc ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$days1[] = substr($row['vi_date'], 5, 5);
	$totall1[] = $row['cnt'];
	$tot0[] = $row['vi_new'];
	$tot1[] = $row['vi_old'];
}

$totall2_sum = 0;
$days2 = array();
$totall2 = array();
$sql = " select  case 
                  when ceil(vi_stay/1000/60) <= 1 then '1분미만'
                  when ceil(vi_stay/1000/60) < 3 then '3분미만'
                  when ceil(vi_stay/1000/60) < 5 then '5분미만'
                  when ceil(vi_stay/1000/60) < 7 then '7분미만'
                  when ceil(vi_stay/1000/60) < 10 then '10분미만'
                  else '10분이상'
                end  vi_stay_name
                ,count(vi_ip) cnt
        from    lt_visit_page a
      where     vi_date between '$fr_date' and '$to_date' ";

if (isset($od_mobile) && $od_mobile != '') {

	if ($od_mobile == "0") $sql .= " and vi_device = 'Desktop' ";
	if ($od_mobile == "1") $sql .= " and vi_device like 'Mobile%' ";
	if ($od_mobile == "2") $sql .= " and vi_device = 'App' ";
}

/*
if($sc_page_type == "0"){
    $sql .= " and vi_referer = '/index.php' ";
} elseif(isset($sc_page1) && $sc_page1 != "" && isset($sc_page2) && isset($sc_page3)) {
    $sql .= " and vi_referer in (
                select me_link from lt_menu where me_code like '{$sc_page1}%' and me_code like '{$sc_page2}%' and me_code like '{$sc_page3}%' and me_use = '1' and me_link not in ('','#')
                )
             ";
}
*/

$sql .= " group by vi_stay_name
            order by ceil(vi_stay/1000/60) asc ";
$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
	$days2[] = $row['vi_stay_name'];
	$totall2[] = $row['cnt'];
	$totall2_sum += (int) $row['cnt'];
}
?>

<script src="../vendors/Chart.js/dist/Chart.min.js"></script>
<script>
	$(function() {

		$('#sc_page_type0').click(function() {

			$("#sc_page1").val("");
			$("#sc_page2").empty();
			$("#sc_page2").append($('<option>', {
				value: '',
				text: '----전체-----'
			}));
			$("#sc_page3").empty();
			$("#sc_page3").append($('<option>', {
				value: '',
				text: '----전체-----'
			}));
			$("#sc_page1").prop("disabled", true);
			$("#sc_page2").prop("disabled", true);
			$("#sc_page3").prop("disabled", true);
		});

		$('#sc_page_type1').click(function() {

			$("#sc_page1").prop("disabled", false);
			$("#sc_page2").prop("disabled", false);
			$("#sc_page3").prop("disabled", false);
		});
		$('#sc_page1').change(function() {
			var me_code1 = $(this).val();
			if (me_code1 != "") {

				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					url: "../design/design_menu_get.php",
					dataType: "json",
					data: {
						me_code: me_code1,
						me_depth: 2
					},
					success: function(data) {
						if (data.error) {
							alert(data.error);
							return false;
						}

						//var responseJSON = JSON.parse(data);
						var count = data.length;

						$("#sc_page2").empty();
						$("#sc_page2").append($('<option>', {
							value: '',
							text: '----전체-----'
						}));

						for (i = 0; i < count; i++) {
							//alert(data[i]['me_name']);
							$("#sc_page2").append($('<option>', {
								value: data[i]['me_code'],
								text: data[i]['me_name']
							}));
						}

						$("#sc_page3").empty();
						$("#sc_page3").append($('<option>', {
							value: '',
							text: '----전체-----'
						}));

						return true;
					},
					error: function(request, status, error) {
						alert(error);
						return false;
					}
				});
			} else {
				$("#sc_page2").empty();
				$("#sc_page2").append($('<option>', {
					value: '',
					text: '----전체-----'
				}));
				$("#sc_page3").empty();
				$("#sc_page3").append($('<option>', {
					value: '',
					text: '----전체-----'
				}));
			}
		});

		$('#sc_page2').change(function() {
			var me_code1 = $(this).val();
			if (me_code1 != "") {

				$.ajax({
					type: "POST",
					cache: false,
					async: false,
					url: "../design/design_menu_get.php",
					dataType: "json",
					data: {
						me_code: me_code1,
						me_depth: 3
					},
					success: function(data) {
						if (data.error) {
							alert(data.error);
							return false;
						}

						//var responseJSON = JSON.parse(data);
						var count = data.length;

						$("#sc_page3").empty();
						$("#sc_page3").append($('<option>', {
							value: '',
							text: '----전체-----'
						}));

						for (i = 0; i < count; i++) {
							//alert(data[i]['me_name']);
							$("#sc_page3").append($('<option>', {
								value: data[i]['me_code'],
								text: data[i]['me_name']
							}));
						}

						return true;
					},
					error: function(request, status, error) {
						alert(error);
						return false;
					}
				});
			} else {
				$("#sc_page3").empty();
				$("#sc_page3").append($('<option>', {
					value: '',
					text: '----전체-----'
				}));
			}
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
				"daysOfWeek": ["일", "월", "화", "수", "목", "금", "토"],
				"monthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
				"firstDay": 1
			},
			"startDate": "<?php echo $fr_date ?>",
			"endDate": "<?php echo $to_date ?>"
		});

		//날짜 버튼
		$("button[name='dateBtn']").click(function() {

			var d = $(this).attr("data");
			if (d == "all") {
				$('#sc_od_time').val("");
			} else {
				var startD = moment();
				var endD = moment();

				if (d == "3d") {
					startD = moment().subtract(2, 'days');
					endD = moment();

				} else if (d == "1w") {
					startD = moment().subtract(6, 'days');
					endD = moment();

				} else if (d == "1m") {
					startD = moment().subtract(1, 'month');
					endD = moment();

				} else if (d == "3m") {
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
				label: '전체페이지수',
				backgroundColor: "rgba(220,220,0,0.5)",
				data: <?php echo json_encode($totall); ?>
			}]

		};


		var ctx = document.getElementById("saledateChart").getContext("2d");
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				responsive: true,
				scales: {
					yAxes: [{
						ticks: {
							//Y 축 0부터 시작
							beginAtZero: true
								//Y 축 정수로 보여주기 
								//숫자가 작거나 또는 0인 경우 등 자동으로 보여주므로 소숫점으로 나타난다
								,
							callback: function(value) {
								if (0 === value % 1) {
									return value;
								}
							}
						}
					}]
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							//+" / "+data.labels[tooltipItem.datasetIndex]
							return "/ 전체페이지수:" + number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
						}
					}
				}
			}
		});



		var barChartData1 = {
			labels: <?php echo json_encode($days1); ?>,
			datasets: [{
				type: 'line',
				label: '처음',
				data: <?php echo json_encode($tot0); ?>,
				borderColor: '#ff0000',
				backgroundColor: "rgba(3, 88, 106, 0)",
				borderWidth: 2
			}, {
				type: 'line',
				label: '재방문',
				data: <?php echo json_encode($tot1); ?>,
				borderColor: "rgba(38, 185, 154, 0.7)",
				backgroundColor: "rgba(3, 88, 106, 0)",
				borderWidth: 2
			}, {
				type: 'bar',
				label: '전체방문자수',
				backgroundColor: "rgba(0,0,128,0.5)",
				data: <?php echo json_encode($totall1); ?>
			}]

		};

		var ctx1 = document.getElementById("saledateChart1").getContext("2d");
		window.myBar1 = new Chart(ctx1, {
			type: 'bar',
			data: barChartData1,
			options: {
				responsive: true,
				scales: {
					yAxes: [{
						ticks: {
							//Y 축 0부터 시작
							beginAtZero: true
								//Y 축 정수로 보여주기 
								//숫자가 작거나 또는 0인 경우 등 자동으로 보여주므로 소숫점으로 나타난다
								,
							callback: function(value) {
								if (0 === value % 1) {
									return value;
								}
							}
						}
					}]
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							//+" / "+data.labels[tooltipItem.datasetIndex]
							return "/ 전체방문자수:" + number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]) + "명 / 처음:"+ number_format(data.datasets[0].data[tooltipItem.index]) + "명 / 재방문:"+ number_format(data.datasets[1].data[tooltipItem.index]);
						}
					}
				}
			}
		});



		var barChartData2 = {
			labels: <?php echo json_encode($days2); ?>,
			datasets: [{
				label: '전체방문자수',
				backgroundColor: "rgba(0,220,220,0.5)",
				data: <?php echo json_encode($totall2); ?>
			}]

		};

		var ctx2 = document.getElementById("saledateChart2").getContext("2d");
		window.myBar2 = new Chart(ctx2, {
			type: 'horizontalBar',
			data: barChartData2,
			options: {
				responsive: true,
				scales: {
					yAxes: [{
						ticks: {
							//Y 축 0부터 시작
							beginAtZero: true
								//Y 축 정수로 보여주기 
								//숫자가 작거나 또는 0인 경우 등 자동으로 보여주므로 소숫점으로 나타난다
								,
							callback: function(value) {
								if (0 === value % 1) {
									return value;
								}
							}
						}
					}]
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							//+" / "+data.labels[tooltipItem.datasetIndex]
							return ": " + number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]) + " / " + (data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] / <?php echo $totall2_sum ?> * 100).toFixed(1) + "%";
						}
					}
				}
			}
		});







		$('#randomizeData').click(function() {

			$.each(barChartData.datasets, function(i, dataset) {
				dataset.backgroundColor = 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
				dataset.data = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()];

			});

			$.each(barChartData1.datasets, function(i, dataset) {
				dataset.backgroundColor = 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
				dataset.data = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()];

			});

			$.each(barChartData2.datasets, function(i, dataset) {
				dataset.backgroundColor = 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
				dataset.data = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()];

			});
			window.myBar.update();
			window.myBar1.update();
			window.myBar2.update();
		});
		window.addEventListener("keydown", (e) => {
        	if (e.keyCode == 13) {
        	    document.getElementById('frm_sale_date').submit();
        	}
    	})
	});
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>