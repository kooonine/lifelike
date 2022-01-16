<?php
$sub_menu = "900110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'r');


// $sql_common = " from {$g5['board_design_table']} a ";
$sql_common = " from {$g5['board_design_table']} a ";
$sql_common .= " left outer join {$g5['menu_table']} c on substring(a.bo_me_code,1,2) = c.me_code";
$sql_search = " where (1) ";

if ($is_admin != "super") {
	$sql_common .= " , {$g5['group_table']} b ";
	$sql_search .= " and (a.gr_id = b.gr_id and b.gr_admin = '{$member['mb_id']}') ";
}

if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		case "bo_table":
			$sql_search .= " ($sfl like '$stx%') ";
			break;
		case "a.gr_id":
			$sql_search .= " ($sfl = '$stx') ";
			break;
		default:
			$sql_search .= " ($sfl like '%$stx%') ";
			break;
	}
	$sql_search .= " ) ";
}

if (!$sst) {
	$sst  = "a.gr_id, a.bo_me_code, a.bo_table";
	$sod = "asc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';

$g5['title'] = '디자인 컨텐츠 관리';
include_once('../admin.head.php');

$colspan = 15;


$token = get_admin_token();
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<div class="x_title">
				<h4><span class="fa fa-check-square"></span> 목록<small></small></h4>
				<label class="nav navbar-right"></label>
				<div class="clearfix"></div>
			</div>

			<div class="x_content">

				<form name="fboardlist" id="fboardlist" action="./board_management_update.php" onsubmit="return fboardlist_submit(this);" method="post">
					<input type="hidden" name="sst" value="<?php echo $sst ?>">
					<input type="hidden" name="sod" value="<?php echo $sod ?>">
					<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
					<input type="hidden" name="stx" value="<?php echo $stx ?>">
					<input type="hidden" name="page" value="<?php echo $page ?>">
					<input type="hidden" name="token" value="<?php echo $token ?>">

					<div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
						<table>
							<thead>
								<tr>
									<th colspan="<?php echo $colspan ?>" style="text-align: right;">
										<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn-danger">
										<a href="board_create.php"><button class="btn btn_03" type="button">컨텐츠 분류 추가</button></a>
									</th>
								</tr>
								<tr>
									<th scope="col">
										<label for="chkall" class="sound_only">게시판 전체</label>
										<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
									</th>
									<!-- <th scope="col">카테고리 분류</th> -->
									<th scope="col">레이아웃</th>
									<th scope="col">컨텐츠 분류</th>
									<!-- <th scope="col">권한(쓰기/읽기)</th> -->
									<th scope="col">전체</th>
									<th scope="col">관리</th>
									<th scope="col">사용여부</th>
								</tr>
							</thead>

							<tbody>

								<?php
								for ($i = 0; $row = sql_fetch_array($result); $i++) {
									$one_update = '<a href="./board_create.php?w=u&amp;bo_table=' . $row['bo_table'] . '&amp;' . $qstr . '">' . get_text($row['bo_subject']) . '</a>';

									$bg = 'bg' . ($i % 2);
								?>

									<tr class="<?php echo $bg; ?>">
										<td class="td_chk">
											<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['bo_subject']) ?></label>
											<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
											<input type="hidden" name="board_design_table[<?php echo $i ?>]" value="<?php echo $row['bo_table'] ?>">
										</td>
										<!-- <td class="td_category2"><?php echo $row['me_name'] ?></td> -->
										<td class="td_category2"><?php
																	if ($row['bo_skin'] == 'basic') {
																		echo '텍스트타입';
																	} else if ($row['bo_skin'] == 'gallery') {
																		echo '이미지타입<br/>(가로타입)';
																	} else if ($row['bo_skin'] == 'gallery2') {
																		echo '이미지타입<br/>(세로타입)';
																	} else {
																		echo $row['bo_subject'];
																	}
																	?></td>
										<td class="td_left" style="min-width:100px;">
											<?php echo $one_update ?>
										</td>
										<!--
										<td class="td_auth">
											<?php if ($row['bo_write_level'] == 9) echo '관리자';
											else if ($row['bo_write_level'] == 2) echo '회원';
											else if ($row['bo_write_level'] == 1) echo '비회원';
											?>/<?php if ($row['bo_read_level'] == 9) echo '관리자';
												else if ($row['bo_read_level'] == 2) echo '회원';
												else if ($row['bo_read_level'] == 1) echo '비회원';
												?></td>
												-->
										<td class="td_cnt"><?php echo number_format($row['bo_count_write']) ?>/<?php echo number_format($row['bo_count_comment']) ?></td>
										<td class="td_odrnum">
											<a href="board_management.php?bo_table=<?php echo $row['bo_table']; ?>"><button class="btn btn_02" type="button">목록</button></a>
											<a href="post_create.php?bo_table=<?php echo $row['bo_table']; ?>"><button class="btn btn_02" type="button">글등록</button></a>
											<button class="btn btn_02" type="button" onclick="openBanner('<?php echo $row['bo_table']; ?>', '<?php echo $row['bo_subject']; ?>', '<?php echo get_text($row['bo_banner']); ?>');">배너관리</button>
										</td>
										<td class="td_etc"><?php echo ($row['bo_use']) ? '사용' : '사용안함'; ?></td>
									</tr>
								<?php
								}
								if ($i == 0)
									echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
								?>
							</tbody>

							<thead>
								<tr>
									<th colspan="<?php echo $colspan ?>" style="text-align: right;">
										<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn-danger">
										<a href="board_create.php"><button class="btn btn_03" type="button">컨텐츠 분류 추가</button></a>
									</th>
								</tr>
							</thead>

						</table>
					</div>
				</form>

			</div>

			<?php
			if ($bo_table) {
				if (!$board['bo_table']) {
					alert('존재하지 않는 게시판입니다.', './board_management.php');
				}

				$sop = strtolower($sop);
				if ($sop != 'and' && $sop != 'or')
					$sop = 'and';

				// 분류 선택 또는 검색어가 있다면
				$stx = trim($stx);
				//검색인지 아닌지 구분하는 변수 초기화
				$is_search_bbs = false;

				if ($sca || $stx || $stx === '0') {     //검색이면
					$is_search_bbs = true;      //검색구분변수 true 지정
					$sql_search = get_sql_search($sca, $sfl, $stx, $sop);

					// 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
					$sql = " select MIN(wr_num) as min_wr_num from {$write_table} ";
					$row = sql_fetch($sql);
					$min_spt = (int) $row['min_wr_num'];

					if (!$spt) $spt = $min_spt;

					$sql_search .= " and (wr_num between {$spt} and ({$spt} + {$config['cf_search_part']})) ";

					// 원글만 얻는다. (코멘트의 내용도 검색하기 위함)
					// 라엘님 제안 코드로 대체 http://sir.kr/g5_bug/2922
					$sql = " SELECT COUNT(DISTINCT `wr_parent`) AS `cnt` FROM {$write_table} WHERE {$sql_search} ";
					$row = sql_fetch($sql);
					$total_count = $row['cnt'];
					/*
		 $sql = " select distinct wr_parent from {$write_table} where {$sql_search} ";
		 $result = sql_query($sql);
		 $total_count = sql_num_rows($result);
		 */
				} else {
					$sql_search = "";

					$total_count = $board['bo_count_write'];
				}

				$page_rows = $board['bo_page_rows'];
				$list_page_rows = $board['bo_page_rows'];

				if ($page < 1) {
					$page = 1;
				} // 페이지가 없으면 첫 페이지 (1 페이지)


				// 년도 2자리
				$today2 = G5_TIME_YMD;

				$list = array();
				$i = 0;
				$notice_count = 0;
				$notice_array = array();

				// 공지 처리
				if (!$is_search_bbs) {
					$arr_notice = explode(',', trim($board['bo_notice']));
					$from_notice_idx = ($page - 1) * $page_rows;
					if ($from_notice_idx < 0)
						$from_notice_idx = 0;
					$board_notice_count = count($arr_notice);

					for ($k = 0; $k < $board_notice_count; $k++) {
						if (trim($arr_notice[$k]) == '') continue;

						$row = sql_fetch(" select * from {$write_table} where wr_id = '{$arr_notice[$k]}' ");

						if (!$row['wr_id']) continue;

						$notice_array[] = $row['wr_id'];

						if ($k < $from_notice_idx) continue;

						$list[$i] = get_list($row, $board, $board_skin_url, G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);
						$list[$i]['is_notice'] = true;

						$i++;
						$notice_count++;

						if ($notice_count >= $list_page_rows)
							break;
					}
				}

				$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
				$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

				// 공지글이 있으면 변수에 반영
				if (!empty($notice_array)) {
					$from_record -= count($notice_array);

					if ($from_record < 0)
						$from_record = 0;

					if ($notice_count > 0)
						$page_rows -= $notice_count;

					if ($page_rows < 0)
						$page_rows = $list_page_rows;
				}

				// 관리자라면 CheckBox 보임
				$is_checkbox = false;
				if ($is_member && ($is_admin == 'super' || $group['gr_admin'] == $member['mb_id'] || $board['bo_admin'] == $member['mb_id']))
					$is_checkbox = true;

				// 정렬에 사용하는 QUERY_STRING
				$qstr2 = 'bo_table=' . $bo_table . '&amp;sop=' . $sop;

				// 0 으로 나눌시 오류를 방지하기 위하여 값이 없으면 1 로 설정
				$bo_gallery_cols = $board['bo_gallery_cols'] ? $board['bo_gallery_cols'] : 1;
				$td_width = (int) (100 / $bo_gallery_cols);

				// 정렬
				// 인덱스 필드가 아니면 정렬에 사용하지 않음
				//if (!$sst || ($sst && !(strstr($sst, 'wr_id') || strstr($sst, "wr_datetime")))) {
				if (!$sst) {
					if ($board['bo_sort_field']) {
						$sst = $board['bo_sort_field'];
					} else {
						$sst  = "wr_num, wr_reply";
						$sod = "";
					}
				} else {
					// 게시물 리스트의 정렬 대상 필드가 아니라면 공백으로 (nasca 님 09.06.16)
					// 리스트에서 다른 필드로 정렬을 하려면 아래의 코드에 해당 필드를 추가하세요.
					// $sst = preg_match("/^(wr_subject|wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
					$sst = preg_match("/^(wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
				}

				if (!$sst)
					$sst  = "wr_num, wr_reply";

				if ($sst) {
					$sql_order = " order by {$sst} {$sod} ";
				}

				if ($is_search_bbs) {
					$sql = " select distinct wr_parent from {$write_table} where {$sql_search} {$sql_order} limit {$from_record}, $page_rows ";
				} else {
					$sql = " select * from {$write_table} where wr_is_comment = 0 ";
					if (!empty($notice_array))
						$sql .= " and wr_id not in (" . implode(', ', $notice_array) . ") ";
					$sql .= " {$sql_order} limit {$from_record}, $page_rows ";
				}

				// 페이지의 공지개수가 목록수 보다 작을 때만 실행
				if ($page_rows > 0) {
					$result = sql_query($sql);

					$k = 0;

					while ($row = sql_fetch_array($result)) {
						// 검색일 경우 wr_id만 얻었으므로 다시 한행을 얻는다
						if ($is_search_bbs)
							$row = sql_fetch(" select * from {$write_table} where wr_id = '{$row['wr_parent']}' ");

						$list[$i] = get_list($row, $board, $board_skin_url, 200);
						if (strstr($sfl, 'subject')) {
							$list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
						}
						$list[$i]['is_notice'] = false;
						$list_num = $total_count - ($page - 1) * $list_page_rows - $notice_count;
						$list[$i]['num'] = $list_num - $k;

						$i++;
						$k++;
					}
				}

				$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './board_management.php?bo_table=' . $bo_table . $qstr . '&amp;page=');

				$list_href = '';
				$prev_part_href = '';
				$next_part_href = '';
				if ($is_search_bbs) {
					$list_href = './board_management.php?bo_table=' . $bo_table;

					$patterns = array('#&amp;page=[0-9]*#', '#&amp;spt=[0-9\-]*#');

					//if ($prev_spt >= $min_spt)
					$prev_spt = $spt - $config['cf_search_part'];
					if (isset($min_spt) && $prev_spt >= $min_spt) {
						$qstr1 = preg_replace($patterns, '', $qstr);
						$prev_part_href = './board_management.php?bo_table=' . $bo_table . $qstr1 . '&amp;spt=' . $prev_spt . '&amp;page=1';
						$write_pages = page_insertbefore($write_pages, '<a href="' . $prev_part_href . '" class="pg_page pg_prev">이전검색</a>');
					}

					$next_spt = $spt + $config['cf_search_part'];
					if ($next_spt < 0) {
						$qstr1 = preg_replace($patterns, '', $qstr);
						$next_part_href = './board_management.php?bo_table=' . $bo_table . $qstr1 . '&amp;spt=' . $next_spt . '&amp;page=1';
						$write_pages = page_insertafter($write_pages, '<a href="' . $next_part_href . '" class="pg_page pg_end">다음검색</a>');
					}
				}


				$write_href = '';
				if ($member['mb_level'] >= $board['bo_write_level']) {
					$write_href = './board_create.php?bo_table=' . $bo_table;
				}

				$nobr_begin = $nobr_end = "";
				if (preg_match("/gecko|firefox/i", $_SERVER['HTTP_USER_AGENT'])) {
					$nobr_begin = '<nobr>';
					$nobr_end   = '</nobr>';
				}
				$stx = get_text(stripslashes($stx));

				// 선택옵션으로 인해 셀합치기가 가변적으로 변함
				$colspan = 7;

				if ($is_checkbox) $colspan++;
				if ($is_good) $colspan++;
				if ($is_nogood) $colspan++;

			?>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> <?php echo $board['bo_subject'] ?> 게시글 목록 <small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<!-- 게시판 페이지 정보 및 버튼 시작 { -->
					<div id="bo_btn_top">
						<div id="bo_list_total">
							<span>Total <?php echo number_format($total_count) ?>건</span>
							<?php echo $page ?> 페이지
						</div>
					</div>
					<!-- } 게시판 페이지 정보 및 버튼 끝 -->

					<div class="tbl_head01 tbl_wrap">
						<table>
							<caption><?php echo $board['bo_subject'] ?> 목록</caption>
							<thead>
								<tr>
									<?php if ($is_checkbox) { ?>
										<th scope="col">
											<label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
											<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
										</th>
									<?php } ?>
									<th scope="col">번호</th>
									<th scope="col">구분</th>
									<th scope="col">제목</th>
									<th scope="col">작성자</th>
									<th scope="col">조회</th>
									<?php if ($is_good) { ?><th scope="col"><?php echo subject_sort_link('wr_good', $qstr2, 1) ?>추천 <i class="fa fa-sort" aria-hidden="true"></i></a></th><?php } ?>
									<?php if ($is_nogood) { ?><th scope="col"><?php echo subject_sort_link('wr_nogood', $qstr2, 1) ?>비추천 <i class="fa fa-sort" aria-hidden="true"></i></a></th><?php } ?>
									<th scope="col">작성일</th>
									<?php if ($board['bo_use_userform'] == "1") { ?><th scope="col">다운로드</th><?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								for ($i = 0; $i < count($list); $i++) {
								?>
									<tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
										<?php if ($is_checkbox) { ?>
											<td class="td_chk">
												<label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
												<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
											</td>
										<?php } ?>
										<td class="td_num">
											<?php
											if ($list[$i]['is_notice']) // 공지사항
												echo '<strong class="notice_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i><span class="sound_only">공지</span></strong>';
											else if ($wr_id == $list[$i]['wr_id'])
												echo "<span class=\"bo_current\">열람중</span>";
											else
												echo $list[$i]['num'];
											?>
										</td>
										<td class="td_num">
											<?php
											if ($list[$i]['wr_1'] == '0') echo '비공개';
											else echo '공개';
											?>
										</td>

										<td class="td_subject" style="text-align:left; padding-left:<?php echo $list[$i]['reply'] ? (strlen($list[$i]['wr_reply']) * 10) : '0'; ?>px">
											<?php
											if ($is_category && $list[$i]['ca_name']) {
											?>
												<a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
											<?php } ?>
											<div class="bo_tit">

												<a href="<?php echo './post_create.php?w=u&bo_table=' . $bo_table . '&wr_id=' . $list[$i]['wr_id'] ?>">
													<?php echo $list[$i]['icon_reply'] ?>
													<?php
													if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
													?>
													<?php echo $list[$i]['subject'] ?>

												</a>
												<?php
												// if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }
												if (isset($list[$i]['icon_file'])) echo rtrim($list[$i]['icon_file']);
												if (isset($list[$i]['icon_link'])) echo rtrim($list[$i]['icon_link']);
												if (isset($list[$i]['icon_new'])) echo rtrim($list[$i]['icon_new']);
												if (isset($list[$i]['icon_hot'])) echo rtrim($list[$i]['icon_hot']);
												?>
												<?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><span class="cnt_cmt">+ <?php echo $list[$i]['wr_comment']; ?></span><span class="sound_only">개</span><?php } ?>
											</div>

										</td>
										<td class="td_name sv_use"><?php echo $list[$i]['name'] ?></td>
										<td class="td_num"><?php echo $list[$i]['wr_hit'] ?></td>
										<?php if ($is_good) { ?><td class="td_num"><?php echo $list[$i]['wr_good'] ?></td><?php } ?>
										<?php if ($is_nogood) { ?><td class="td_num"><?php echo $list[$i]['wr_nogood'] ?></td><?php } ?>
										<td class="td_datetime"><?php echo $list[$i]['datetime2'] ?></td>

										<?php if ($board['bo_use_userform'] == "1") { ?><td class="td_datetime"><a href="<?php echo './board_management_excel.php?bo_table=' . $bo_table . '&wr_id=' . $list[$i]['wr_id'] ?>" download><button type="button" class="btn btn_02" id="btnDownload<?php echo $list[$i]['wr_id'] ?>">다운로드</button></a></td><?php } ?>

									</tr>
								<?php } ?>
								<?php if (count($list) == 0) {
									echo '<tr><td colspan="' . $colspan . '" class="empty_table">게시물이 없습니다.</td></tr>';
								} ?>
							</tbody>
						</table>
					</div>


				<?php
			}
				?>




				</div>

		</div>

	</div>

	<form name="fmodalbanner" id="fmodalbanner" method="post" enctype="multipart/form-data">
		<!-- Modal : 배너 관리-->
		<div id="modal_banner" class="modal fade" role="dialog">


			<input type="hidden" name="token" value="<?php echo $token ?>">
			<input type="hidden" name="bo_table" id="banner_bo_table" value="">

			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">배너관리 팝업</h4>
					</div>
					<div class="modal-body">
						<h4><span class="fa fa-check-square"></span> <span id="spnbannerTableName"></span></h4>
						<div class="tbl_frm01 tbl_wrap">
							<table>
								<colgroup>
									<col width="30%">
									<col width="70%">
								</colgroup>
								<tbody>
									<tr>
										<th scope="row">배너 수량</th>
										<td colspan="2">
											<div class="radio">
												<label><input type="radio" value="1" id="rdo_banner_count1" name="rdo_banner_count" checked="checked"> 1개</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" value="2" id="rdo_banner_count2" name="rdo_banner_count"> 2개</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" value="3" id="rdo_banner_count3" name="rdo_banner_count"> 3개</label>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
							<br />
							<div class="clearfix"></div>
							<?php
							for ($i = 1; $i <= 3; $i++) {
							?>
								<table class="table table-bordered " id="tblImage<?php echo $i ?>">
									<colgroup>
										<col width="30%">
										<col width="70%">
									</colgroup>
									<thead>
										<tr>
											<th scope="col" class="text-center active" style="text-align: center;" colspan="2">배너 <?php echo $i ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="col" class="text-center success" style="vertical-align: middle;">전시순서</th>
											<td>
												<select name="imgOrder[]" id="imgOrder<?php echo $i ?>" class="form-control">
													<?php for ($j = 1; $j <= 3; $j++) {
														echo '<option value="' . $j . '" ' . get_selected($i, $j) . '>순서 ' . $j . '</option>';
													} ?>
												</select>
											</td>
										</tr>

										<tr>
											<th scope="col" class="text-center success" style="vertical-align: middle;">이미지 등록<br />(PC)</th>
											<td>
												<div class="col-md-5 col-lg-5 col-sm-5">
													<?php echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile' . $i . '" style="width: 100%; height: 30%;">'; ?>
												</div>

												<div class="col-md-7 col-lg-7 col-sm-7">
													<div class="input-group">
														<span class="">
															<div class="btn btn-info">
																<span><?php if ($img_data) echo '이미지 수정';
																		else echo '이미지 등록'; ?></span>
																<input type="file" id="imgFile<?php echo $i ?>" name="imgFile[]" class="hiddenFile" delBtnID="btnDelimgFile<?php echo $i ?>" imgID="imgimgFile<?php echo $i ?>" style="width:100px;height: 30px;" accept=".jpg, .png">
															</div>
														</span>
														<button class="btn btn-danger hidden" type="button" id="btnDelimgFile<?php echo $i ?>" fileBtnID="imgFile<?php echo $i ?>">삭제</button>

														<input type="hidden" id="orgimgFile<?php echo $i ?>" name="orgimgFile[]" value="">

													</div>
												</div>

												<div class="col-md-12 col-lg-12 col-sm-12">
													<div class="clearfix"></div><br />
													<span class="red">* 업로드 이미지 사이즈 (1000px * 360px) <br />
														* 최대 15MB / 확장자 jpg, png만 가능</span>
												</div>

											</td>
										</tr>

										<tr>
											<th scope="col" class="text-center success" style="vertical-align: middle;">URL</th>
											<td>
												<input type="text" class="form-control" id="linkURL<?php echo $i ?>" name="linkURL[]">
											</td>
										</tr>

										<tr>
											<th scope="col" class="text-center success" style="vertical-align: middle;">모바일 이미지</th>
											<td>
												<div class="radio">
													<label><input type="radio" value="0" id="mimg_use0_<?php echo $i ?>" name="mimg_use<?php echo $i ?>" checked="checked"> PC내용과 동일</label>&nbsp;&nbsp;&nbsp;
													<label><input type="radio" value="1" id="mimg_use1_<?php echo $i ?>" name="mimg_use<?php echo $i ?>"> 사용하기</label>&nbsp;&nbsp;&nbsp;
												</div>
											</td>
										</tr>

										<tr id="trmimg<?php echo $i ?>" class="hidden">
											<th scope="col" class="text-center success" style="vertical-align: middle;">이미지 등록<br />(Mobile)</th>
											<td>
												<div class="col-md-5 col-lg-5 col-sm-5">
													<?php echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgmimgFile' . $i . '" style="width: 100%; height: 30%;">'; ?>
												</div>

												<div class="col-md-7 col-lg-7 col-sm-7">
													<div class="input-group">
														<span class="">
															<div class="btn btn-info">
																<span><?php if ($img_data) echo '이미지 수정';
																		else echo '이미지 등록'; ?></span>
																<input type="file" id="mimgFile<?php echo $i ?>" name="mimgFile[]" class="hiddenFile" delBtnID="btnDelmimgFile<?php echo $i ?>" imgID="imgmimgFile<?php echo $i ?>" style="width:100px;height: 30px;" accept=".jpg, .png">
															</div>
														</span>
														<button class="btn btn-danger hidden" type="button" id="btnDelmimgFile<?php echo $i ?>" fileBtnID="mimgFile<?php echo $i ?>">삭제</button>

														<input type="hidden" id="orgmimgFile<?php echo $i ?>" name="orgmimgFile[]" value="">

													</div>
												</div>

												<div class="col-md-12 col-lg-12 col-sm-12">
													<div class="clearfix"></div><br />
													<span class="red">* 업로드 이미지 사이즈 (480px * 328px) <br />
														* 최대 15MB / 확장자 jpg, png만 가능</span>
												</div>

											</td>
										</tr>

									</tbody>
								</table>

								<div class="clearfix"></div>

							<?php } ?>

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" id="btnSubmitBanner">등록</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
					</div>
				</div>

			</div>
		</div>
	</form>

	<script>
		$(function() {
			$(document).ready(function($) {

				$('input[type="radio"][name="mimg_use1"]').click(function() {
					if ($(this).val() == '1') {
						$('#trmimg1').removeClass('hidden');
					} else {
						$('#trmimg1').removeClass('hidden').addClass('hidden');
					}
				});
				$('input[type="radio"][name="mimg_use2"]').click(function() {
					if ($(this).val() == '1') {
						$('#trmimg2').removeClass('hidden');
					} else {
						$('#trmimg2').removeClass('hidden').addClass('hidden');
					}
				});
				$('input[type="radio"][name="mimg_use3"]').click(function() {
					if ($(this).val() == '1') {
						$('#trmimg3').removeClass('hidden');
					} else {
						$('#trmimg3').removeClass('hidden').addClass('hidden');
					}
				});


				$.delBtnFileUpload = function(event) {
					var fileBt = $("#" + $(this).attr("fileBtnID"));

					var fileBtnID = fileBt.attr("id");
					var labalID = fileBt.attr("labalID");
					var delBtnID = fileBt.attr("delBtnID");
					var imgID = fileBt.attr("imgID");

					$("#" + fileBtnID).val("");
					$("#org" + fileBtnID).val("");
					if (labalID != "") $("#" + labalID).val("");
					if (imgID != "") {
						$("#" + imgID).attr("src", "../img/theme_img.jpg");
						//$("#"+imgID).removeClass('hidden').addClass('hidden');
					}

					$(this).removeClass('hidden').addClass('hidden');
				}

				$.imgFileUploadChange = function(event) {

					var fileBtnID = $(this).attr("id");
					var labalID = $(this).attr("labalID");
					var delBtnID = $(this).attr("delBtnID");
					var imgID = $(this).attr("imgID");

					var fileName = "";
					if (window.FileReader) {
						fileName = $(this)[0].files[0].name;
					} else {
						fileName = $(this)[0].val().split('/').pop().split('\\').pop();
					}

					if (fileName != "" && imgID != "") {
						var reader = new FileReader();
						reader.onload = function(e) {
							$("#" + imgID).attr("src", e.target.result);
						}
						reader.readAsDataURL($(this)[0].files[0]);

						$("#" + imgID).removeClass('hidden');
					}

					//$("#btnDelMainImgFile").removeClass('d-none').addClass('d-none');
					$("#" + delBtnID).removeClass('hidden');
					if (labalID != "") $("#" + labalID).val(fileName);
				}

				$.setImgFileUpload = function(fileInputId) {

					$("#" + fileInputId).on('change', $.imgFileUploadChange);
					var delBtnID = $("#" + fileInputId).attr("delBtnID");
					$("#" + delBtnID).click($.delBtnFileUpload);
				}

				$.setImgFileUpload('imgFile1');
				$.setImgFileUpload('imgFile2');
				$.setImgFileUpload('imgFile3');

				$.setImgFileUpload('mimgFile1');
				$.setImgFileUpload('mimgFile2');
				$.setImgFileUpload('mimgFile3');

			});



			$("#btnSubmitBanner").click(function() {

				fmodalbanner_submit();
			});


			$("#btn_view_post_list").click(function() {

				$("#divPostList").removeClass("hidden");
				//게시글 목록 데이터 받아서 뿌리기

			});

			$('input[type="radio"][name="rdo_banner_count"]').click(function() {

				var cnt = $(this).val();
				for (i = 1; i <= 3; i++) {
					if (i <= cnt) {
						$("#tblImage" + i).removeClass('hidden');
					} else {
						$("#tblImage" + i).removeClass('hidden').addClass('hidden');
					}
				}

			});



			function fmodalbanner_submit() {

				//배너관리 팝업 form submit
				//fboardlist
				//alert(data);

				var cnt = $('input[type="radio"][name="rdo_banner_count"]:checked').val();

				for (i = 1; i <= cnt; i++) {
					//배너 이미지 세팅
					if ($("#imgFile" + i).val() == "" && $("#imgFile" + i).val() == "" && $("#orgimgFile" + (i)).val() == "") {
						alert("이미지(PC)를 등록해주십시오.");
						return false;
					}

					if ($("#linkURL" + i).val() == "") {
						alert("URL를 입력해주십시오.");
						return false;
					}

					if ($('input[type="radio"][name="mimg_use' + i + '"]:checked').val() == "1") {
						if ($("#mimgFile" + i).val() == "" && $("#mimgFile" + i).val() == "" && $("#orgmimgFile" + (i)).val() == "") {
							alert("이미지(Mobile)를 등록해주십시오.");
							return false;
						}
					}
				}


				var form = $('#fmodalbanner')[0];
				var data = new FormData(form);

				$("#btnSubmitBanner").prop("disabled", true);


				$.ajax({
					type: "POST",
					enctype: 'multipart/form-data',
					url: "board_banner_update.php",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 600000,
					success: function(res) {
						$("#btnSubmitBanner").prop("disabled", false);
						//alert(res);

						var responseJSON = JSON.parse(res);

						if (responseJSON.result == "S") {

							alert("정상적으로 배너가 등록되었습니다.");
							//$("#modal_banner").modal('hide');
							document.location.reload();

						} else if (responseJSON.alertMsg != undefined && responseJSON.alertMsg != null) {
							alert(responseJSON.alertMsg);
						}
						return true;
					},
					error: function(request, status, error) {
						$("#btnSubmitBanner").removeAttr("disabled");
						alert(error);
						return false;
					}
				});

				return false;

			}
		});


		function fboardlist_submit(f) {
			if (!is_checked("chk[]")) {
				alert("삭제 하실 항목을 하나 이상 선택하세요.");
				return false;
			}

			if (!confirm("게시판을 삭제하시겠습니까?\n모든 데이타가 삭제되며 복원되지 않습니다.")) {
				//선택한 게시판 삭제
				return false;
			}

			return true;

		}

		function openBanner(bo_table, bo_name, bo_banner) {
			var bo_banner_json = {
				bannerCount: 1,
				imgFile: []
			};

			for (i = 1; i <= 3; i++) {
				$("#imgimgFile" + (i)).attr("src", "../img/theme_img.jpg");
				$("#btnDelimgFile" + (i)).removeClass('hidden').addClass('hidden');

				$("#orgimgFile" + (i)).val("");
				$("#linkURL" + (i)).val("");

				$("#mimg_use0_" + (i)).click();

				$("#imgmimgFile" + (i)).attr("src", "../img/theme_img.jpg");
				$("#btnDelmimgFile" + (i)).removeClass('hidden').addClass('hidden');

				$("#orgmimgFile" + (i)).val("");

			}
			if (bo_banner != "") {
				bo_banner_json = JSON.parse(bo_banner.replace("&#034;", "\""));

				for (i = 0; i < bo_banner_json.bannerCount; i++) {
					//alert(bo_banner_json.imgFile[i].imgFile);

					var src = '<?php echo G5_DATA_URL . '/banner/'; ?>' + bo_table + '/' + bo_banner_json.imgFile[i].imgFile;
					$("#imgimgFile" + (i + 1)).attr("src", src);
					$("#btnDelimgFile" + (i + 1)).removeClass('hidden');

					$("#orgimgFile" + (i + 1)).val(bo_banner_json.imgFile[i].imgFile);
					$("#linkURL" + (i + 1)).val(bo_banner_json.imgFile[i].linkURL);

					if (bo_banner_json.imgFile[i].muse == "1") {
						$("#mimg_use1_" + (i + 1)).click();

						var msrc = '<?php echo G5_DATA_URL . '/banner/'; ?>' + bo_table + '/' + bo_banner_json.imgFile[i].mimgFile;
						$("#imgmimgFile" + (i + 1)).attr("src", msrc);
						$("#btnDelmimgFile" + (i + 1)).removeClass('hidden');

						$("#orgmimgFile" + (i + 1)).val(bo_banner_json.imgFile[i].mimgFile);

					} else {
						$("#mimg_use0_" + (i + 1)).click();
					}
				}
			}

			$("#rdo_banner_count" + bo_banner_json.bannerCount).click();
			$("#banner_bo_table").val(bo_table);
			$("#spnbannerTableName").text(bo_name + " 배너관리");
			$("#modal_banner").modal('show');
		}
	</script>




	<!-- @END@ 내용부분 끝 -->

	<?php
	include_once('../admin.tail.php');
	?>