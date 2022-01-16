<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
include_once(G5_PATH.'/head.php');
$todapth = "마이페이지";
$title = "나의 적립금";
?>
<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
<!-- container -->
<div id="container">
	<div class="content mypage">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="user_info my_point">
				<div class="tbl_list item_box">
					<ul class="count2">
						<li>
							<span class="tit ico"><?=$member['mb_name']?>님의 적립금</span>
							<strong class="big"><?=number_format($member['mb_point']); ?><span class="sm"> 원</span></strong>
						</li>
						<li>
							<div class="box">
								<span class="tit">사용가능</span>
								<strong class="sm"><?=number_format($member['mb_point']); ?> 원</strong>
							</div>
							<div class="box">
								<span class="tit">당월 소멸예정</span>
								<strong class="sm"><?=number_format($expire_point); ?> 원</strong>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar">
				<h1 class="g_title_01">적립금 이용 내역</h1>
			</div>

			<form name="fsearch" onsubmit="$('#page').val('1');" method="get">
				<input type="hidden" name="page" id="page" value="<?=$page; ?>">
				<input type="hidden" name="chk_date" id="chk_date" value="<?=$chk_date; ?>">


				<div class="gray_box form_box">
					<div class="tab fix">
						<ul class="type4 black onoff">
							<li class="<?=($chk_date=="1w"||$chk_date=="")?"on":"" ?>"><button type="button" name="dateBtn" data="1w">1주일</button></li>
							<li class="<?=($chk_date=="1m")?"on":"" ?>"><button type="button" name="dateBtn" data="1m">1개월</button></li>
							<li class="<?=($chk_date=="3m")?"on":"" ?>"><button type="button" name="dateBtn" data="3m">3개월</button></li>
							<li class="<?=($chk_date=="6m")?"on":"" ?>"><button type="button" name="dateBtn" data="6m">6개월</button></li>
							<li class="<?=($chk_date=="1y")?"on":"" ?>"><button type="button" name="dateBtn" data="1y">1년</button></li>
						</ul>
					</div>
					<div class="inp_wrap">
						<div class="inp_ele count4">
							<div class="input calendar">
								<input type="date" placeholder="" id="fr_date" name="fr_date" value="<?=$fr_date?>">
							</div>
						</div>
						<div class="inp_ele count1 alignC">-</div>
						<div class="inp_ele count4">
							<div class="input calendar">
								<input type="date" placeholder="" id="to_date" name="to_date" value="<?=$to_date?>">
							</div>
						</div>
					</div>
					<span class="sel_box">
						<select name="point_type">
							<? if(!isset($point_type)) $point_type = "";?>
							<option value=""  <?=get_selected($point_type, "") ?>>전체</option>
							<option value="1" <?=get_selected($point_type, "1") ?>>적립</option>
							<option value="2" <?=get_selected($point_type, "2") ?>>사용</option>
							<option value="3" <?=get_selected($point_type, "3") ?>>소멸예정</option>
						</select>
					</span>
					<button type="submit" class="btn small green"><span>조회</span></button>
				</div>
			</form>

			<div class="gray_box point_box">
				<ul class="count2">
					<li >
						<a href="#">
							<span class="tit">적립</span>
							<strong class="posi_p point bold"><?=number_format($plus_point); ?></strong>
						</a>
					</li>
					<li>
						<a href="#">
							<span class="tit">사용</span>
							<strong class="posi_p point_red bold"><?=number_format($minus_point); ?></strong>
						</a>
					</li>
				</ul>
			</div>

			<div class="div_wrap border_none">
				<div class="tbl_list">
					<table>
						<colgroup>
							<col style="width:15%;">
							<col style="width:10%;">
							<col style="width:45%;">
							<col style="width:15%;">
							<col style="width:15%;">
						</colgroup>
						<thead>
							<tr>
								<th>날짜</th>
								<th>구분</th>
								<th>내역</th>
								<th>주문번호</th>
								<th>금액</th>
							</tr>
						</thead>
						<tbody>
							<?
							$sum_point1 = $sum_point2 = $sum_point3 = 0;

							$sql = " select *
							{$sql_common}
							{$sql_order}
							limit {$from_record}, {$rows} ";
							$result = sql_query($sql);
							for ($i=0; $row=sql_fetch_array($result); $i++) {
								$point1 = $point2 = 0;
								if ($row['po_point'] > 0) {
									$point1 = '+' .number_format($row['po_point']);
									$sum_point1 += $row['po_point'];
								} else {
									$point2 = number_format($row['po_point']);
									$sum_point2 += $row['po_point'];
								}

								$po_content = $row['po_content'];

								$expr = '';
				//            if($row['po_expired'] == 1)
								$expr = ' txt_expired';
								?>
								<tr>
									<td class="date"><?=conv_date_format('y-m-d', $row['po_datetime']); ?></td>
									<? if ($point1) echo '<td class="point">적립</td>'; else echo '<td class="point_red">사용</td>'; ?>
									<td class="alignL">
										<a><?=$po_content; ?>
										<? if ($row['po_expired'] == 1) { ?>
											/ 만료: <?=substr($row['po_expire_date'], 2); ?>
										<? } else echo $row['po_expire_date'] == '9999-12-31' ? '&nbsp;' : '/ 소멸예정일: '.substr($row['po_expire_date'],2); ?>

									</a>
								</td>
								<td class="date"><? if($row['po_rel_table'] == '@order' || $row['po_rel_table'] == 'item_use') echo $row['po_rel_id'] ?></td>
								<td class="alignR posi_p point <?=($point2)?'point_red':'' ?>"><? if ($point1) echo $point1; else echo $point2; ?></td>
							</tr>

							<?
						}

						if ($i == 0)
							echo '<tr><td class="empty_list" colspan="5">자료가 없습니다.</td></tr>';
						else {
							if ($sum_point1 > 0)
								$sum_point1 = "+" . number_format($sum_point1);
							$sum_point2 = number_format($sum_point2);
						}
						?>
					</tbody>
				</table>
			</div>

			<?=get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>
		</div>

	</div>
</div>
</div>
<script src="<?=G5_ADMIN_URL ?>/vendors/moment/min/moment-with-locales.min.js"></script>
<script>
	$(function(){
	//날짜 버튼
	$("button[name='dateBtn']").click(function(){

		var d = $(this).attr("data");
		$("#chk_date").val(d);

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
			} else if(d == "6m") {
				startD = moment().subtract(6, 'month');
				endD = moment();
			} else if(d == "1y") {
				startD = moment().subtract(1, 'year');
				endD = moment();
			}
			$("#fr_date").val(startD.format("YYYY-MM-DD"));
			$("#to_date").val(endD.format("YYYY-MM-DD"));
		}

	});
});
</script>
<?
include_once(G5_PATH.'/tail.php');
?>
