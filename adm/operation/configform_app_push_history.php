<?
$sub_menu = "20";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

$where = " and ";
$sql_search = "";
if ($stx != "") {
	if ($sfl != "") {
		$sql_search .= " $where $sfl like '%$stx%' ";
		$where = " and ";
	}
	if ($save_stx != $stx)
		$page = 1;
}

if ($sh_datetime != "") {
	$sh_datetimes = explode("~", $sh_datetime);
	$sql_search .= " and sh_datetime between '".trim($sh_datetimes[0])." 00:00:00' and '".trim($sh_datetimes[1])." 23:59:59' ";
}

$sql_common = " from lt_sms_sendhistory where sf_type = 'push' ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
	$sst  = "sh_no";
	$sod = "desc";
}
$sql_order = "order by $sst $sod";


$sql  = " select *
$sql_common
$sql_order
limit $from_record, $rows ";

$result = sql_query($sql);
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

$g5['title'] = 'PUSH 발송내역 조회';
include_once ('../admin.head.php');

?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<form name="flist" id="flistSearch" class="local_sch01 local_sch">

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> PUSH 발송내역 조회<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="tbl_frm01 tbl_wrap">
					<table>
						<colgroup>
							<col class="grid_4">
							<col>
							<col class="grid_3">
						</colgroup>
						<tr>
							<th scope="row">검색기간</th>
							<td>

								<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
									<input type='text' class="form-control" id="sh_datetime" name="sh_datetime" value=""/>
									<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
								</div>
								<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
									<div class="btn-group" >
										<button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
										<button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
										<button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
										<button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
										<button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
										<button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
									</div>
								</div>

							</td>
							<td></td>
						</tr>
						<tr>
							<th scope="col">전송결과</th>
							<td colspan="2">
								<label><input type="radio" name="msg_type" value="" /> 전체</label>
								<label><input type="radio" name="msg_type" value="android" /> Android</label>
								<label><input type="radio" name="msg_type" value="ios" /> IOS</label>
							</td>
						</tr>
						<tr>
							<th scope="col">발송결과</th>
							<td colspan="2">
								<label><input type="radio" name="result_code"value="" checked="checked" /> 전체</label>&nbsp;&nbsp;&nbsp;
								<label><input type="radio" name="result_code" value="200" /> 성공</label>&nbsp;&nbsp;&nbsp;
								<label><input type="radio" name="result_code" value="100" /> 실패</label>
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
			</div>

			<div class="x_panel">
				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> PUSH 발송내역<small></small></h4>
					<div class="clearfix"></div>
				</div>

				<div class="tbl_head01 tbl_wrap">
					<div class="pull-left">
						<span class="btn_ov01"><span class="ov_txt">검색결과</span><span class="ov_num"> <?=$total_count; ?>건</span></span>
					</div>
					<div class="pull-right">
						<select name="page_rows" onchange="$('#flistSearch').submit();">
							<option value="10" <?=get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
							<option value="20" <?=get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
							<option value="30" <?=get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
						</select>
						<br/><br/>
					</div>
				</div>
			</form>

			<form name="fitemlistupdate" id="fitemlistupdate" method="post" action="./itemlistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off" >
				<input type="hidden" name="sca" value="<?=$sca; ?>">
				<input type="hidden" name="sst" value="<?=$sst; ?>">
				<input type="hidden" name="sod" value="<?=$sod; ?>">
				<input type="hidden" name="sfl" value="<?=$sfl; ?>">
				<input type="hidden" name="stx" value="<?=$stx; ?>">
				<input type="hidden" name="page" value="<?=$page; ?>">

				<input type="hidden" name="token" value="<?=$token; ?>" id="token">

				<div class="tbl_head01 tbl_wrap">
					<table id="test">
						<caption>목록</caption>
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">앱(OS)</th>
								<th scope="col">발송시간</th>
								<th scope="col">메시지제목</th>
								<th scope="col">발송건수</th>
								<th scope="col">결과코드</th>
							</tr>
						</thead>
						<tbody>
							<?
							for ($i=0; $row=sql_fetch_array($result); $i++)
							{
								$bg = 'bg'.($i%2);
								?>

								<tr class="<?=$bg; ?>">
									<td class="td_chk">
										<?=$row['sh_no']; ?>
									</td>
									<td class="td_num grid_2">
										<?=($row['msg_type']=="")?"전체":$row['msg_type']; ?>
									</td>
									<td  class="td_sort grid_1">
										<?=$row['sh_datetime']; ?>
									</td>
									<td  class="td_sort grid_1">
										<label onclick='showDetail("<?=$row['sh_no']; ?>");' style="cursor: pointer;"><?=$row['msg_title']; ?></label>
									</td>
									<td  class="td_sort grid_1">
										<?=count(explode(",", $row['dest_phone']))?>건
									</td>
									<td  class="td_sort grid_1">
										<?=($row['result_code'] =="200")?"성공":"실패"; ?>
									</td>
								</tr>
								<?
							}
							if ($i == 0)
								echo '<tr><td colspan="12" class="empty_table">자료가 한건도 없습니다.</td></tr>';
							?>
						</tbody>
					</table>
				</div>
			</form>

			<?=get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
		</div></div></div>


		<script>
			$(function() {

				$('#sh_datetime').daterangepicker({
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
				$('#sh_datetime').val("<?=$sh_datetime ?>");

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){

		var d = $(this).attr("data");
		if(d == "all") {
			$('#sh_datetime').val("");
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

			$('#sh_datetime').data('daterangepicker').setStartDate(startD);
			$('#sh_datetime').data('daterangepicker').setEndDate(endD);
		}
	});
	window.addEventListener("keydown", (e) => {
    	if (e.keyCode == 13) {
        	document.getElementById('flistSearch').submit();
    	}
  	})
});

			function showDetail(sh_no)
			{
				$.post(
					"configform_app_push_history_detail.php",
					{ sh_no: sh_no },
					function(data) {
						$("#dvDetail").empty().html(data);
					}
					);

				$('#detail_modal').modal('show');
			}

		</script>


		<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">전송결과 상세 팝업</h4>
					</div>
					<div class="modal-body">
						<div class="" role="tabpanel" data-example-id="togglable-tabs">
							<div id="myTabContent" class="tab-content">
								<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="all-tab">
								</div>
								<div role="tabpanel" class="tab-pane fade active in" id="tab_content2" aria-labelledby="stay-tab">
								</div>
								<div role="tabpanel" class="tab-pane fade active in" id="tab_content3" aria-labelledby="success-tab">
								</div>
								<div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="fail-tab">
								</div>
							</div>
							<div class="tbl_frm01 tbl_wrap" id="dvDetail">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<br><br><br>
						<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
					</div>
				</div>
			</div>
		</div>

		<!-- @END@ 내용부분 끝 -->

		<?
		include_once ('../admin.tail.php');
		?>
