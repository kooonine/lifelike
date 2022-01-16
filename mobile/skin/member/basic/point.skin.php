<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<script>
$('#header').html('');
</script>
<div class="wrap_all">
	<p id="skipNavi"><a href="#container">본문 바로가기</a></p>
	<!-- header -->
	<div class="user_info my_point">
		<h1 class="blind">나의 적립금</h1>
		<div class="inner">
			<p class="title">적립금</p>
			<a href="#"><strong class="point posi_p"><?php echo number_format($member['mb_point']); ?></strong></a>
		</div>
		<div class="tbl_list item_box padN">
			<ul class="count2">
				<li>
					<a href="#">
						<span>사용 가능</span>
						<strong class="big"><?php echo number_format($member['mb_point']); ?></strong>
					</a>
				</li>
				<li>
					<a href="#">
						<span>당월 소멸 예정</span>
						<strong class="big"><?php echo number_format($expire_point); ?></strong>
					</a>
				</li>
			</ul>
		</div>
		<a href="#" onclick="window.close();" class="btn_closed"><span class="blind">닫기</span></a>
	</div>
	<!-- //header -->

	<!-- container -->
	<div id="container">
		<div class="content mypage">
			<form name="fsearch" onsubmit="$('#page').val('1');" method="get">
			<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
			<input type="hidden" name="chk_date" id="chk_date" value="<?php echo $chk_date; ?>">

			<div class="grid cont">
				<div class="tab">
					<h3 class="g_title_01 floatL">적립금 이용 내역</h3>
					<ul class="type2 onoff">
					<!--
						<li class="<?php echo ($point_type=="")?"on":"" ?>"><a name="typeBtn" data="" ><span>전체</span></a></li>
						<li class="<?php echo ($point_type=="1")?"on":"" ?>"><a name="typeBtn" data="1" ><span>적립</span></a></li>
						<li class="<?php echo ($point_type=="2")?"on":"" ?>"><a name="typeBtn" data="2" ><span>사용</span></a></li>
						<li class="<?php echo ($point_type=="3")?"on":"" ?>"><a name="typeBtn" data="3" ><span>소멸 예정</span></a></li>
					-->
					</ul>
				</div>
			</div>

			<div class="grid bg_none">
				<div class="tab fix">
					<ul class="type3 onoff">
						<li class="<?php echo ($chk_date=="1w"||$chk_date=="")?"on":"" ?>" onclick="dateBtnChange('1w');"><a name="dateBtn" data="1w"><span>1주일</span></a></li>
						<li class="<?php echo ($chk_date=="1m")?"on":"" ?>" onclick="dateBtnChange('1m');"><a name="dateBtn" data="1m"><span>1개월</span></a></li>
						<li class="<?php echo ($chk_date=="3m")?"on":"" ?>" onclick="dateBtnChange('3m');"><a name="dateBtn" data="3m"><span>3개월</span></a></li>
						<li class="<?php echo ($chk_date=="6m")?"on":"" ?>" onclick="dateBtnChange('6m');"><a name="dateBtn" data="6m"><span>6개월</span></a></li>
						<li class="<?php echo ($chk_date=="1y")?"on":"" ?>" onclick="dateBtnChange('1y');"><a name="dateBtn" data="1y"><span>1년</span></a></li>
					</ul>
				</div>
				<div class="inner_wrap">
					<div class="inp_wrap">
						<div class="inp_ele count4">
							<div class="input calendar">
								<input type="date" placeholder="" id="fr_date" name="fr_date" value="<?php echo $fr_date?>">
							</div>
						</div>
						<div class="inp_ele count1 alignC">~</div>
						<div class="inp_ele count4">
							<div class="input calendar">
								<input type="date" placeholder="" id="to_date" name="to_date" value="<?php echo $to_date?>">
							</div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="title count3"><label for="f_03">내역</label></div>
						<div class="inp_ele count6">
						<?php if(!isset($point_type)) $point_type = "";?>
							<ul class="count4">
								<li>
									<span class="chk radio">
										<input type="radio" id="f_03_1" name="point_type" value=""  <?php echo get_checked($point_type, "") ?>>
										<label for="f_03_1">전체</label>
									</span>
								</li>
								<li>
									<span class="chk radio">
										<input type="radio" id="f_03_2" name="point_type" value="1" <?php echo get_checked($point_type, "1") ?>>
										<label for="f_03_2">적립</label>
									</span>
								</li>
								<li>
									<span class="chk radio">
										<input type="radio" id="f_03_3" name="point_type" value="2" <?php echo get_checked($point_type, "2") ?>>
										<label for="f_03_3">사용</label>
									</span>
								</li>
								<li>
									<span class="chk radio">
										<input type="radio" id="f_03_4" name="point_type" value="3" <?php echo get_checked($point_type, "3") ?>>
										<label for="f_03_4">소멸</label>
									</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="btn_group"><button type="submit" class="btn big green"><span>조회</span></button></div>
				</div>
			</div>

			</form>

			<div class="grid none">
				<div class="point_info tbl_list">
					<ul class="count2">
						<li class="ico_1">
							<a href="#">
								<span>적립</span>
								<strong class="point posi_p">+<?php echo number_format($plus_point); ?></strong>
							</a>
						</li>
						<li class="ico_2">
							<a href="#">
								<span>사용</span>
								<strong class="posi_p point_red"><?php echo number_format($minus_point); ?></strong>
							</a>
						</li>
					</ul>
				</div>

				<div class="list">
					<ul class="type1">
					<?php
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

					<li>
						<a>
							<div class="head">
								<?php if ($point1) echo '<span class="category round floatL">적립'; else echo '<span class="category round_gray floatL">사용'; ?></span>
								<span class="data floatL"></span>
								<span class="point posi_p <?php echo ($point2)?'point_red':'' ?>"><?php if ($point1) echo $point1; else echo $point2; ?></span>
							</div>
							<strong class="title">
								<?php echo $po_content; ?> / 발급 : <?php echo conv_date_format('y-m-d', $row['po_datetime']); ?>
                                <?php if ($row['po_expired'] == 1) { ?>
                                / 만료: <?php echo substr($row['po_expire_date'], 2); ?>
                                <?php } else echo $row['po_expire_date'] == '9999-12-31' ? '&nbsp;' : '/ 소멸예정일: '.substr($row['po_expire_date'],2); ?>
							</strong>
						</a>
					</li>

                    <?php
                    }

                    if ($i == 0)
                        echo '<li class="empty_list">자료가 없습니다.</li>';
                    else {
                        if ($sum_point1 > 0)
                            $sum_point1 = "+" . number_format($sum_point1);
                        $sum_point2 = number_format($sum_point2);
                    }
                    ?>
					</ul>
				</div>
				<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>


			</div>
		</div>
	</div>
</div>

<script src="<?php echo G5_ADMIN_URL ?>/vendors/moment/min/moment-with-locales.min.js"></script>
<script>
$(function(){
	//날짜 버튼
	$("a[name='dateBtn']").click(function(){

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

function dateBtnChange(d){

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
}
</script>


