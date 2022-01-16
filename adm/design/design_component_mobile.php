<?
$sub_menu = "800140";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super') {
	alert('최고관리자만 접근 가능합니다.');
}

$g5['title'] = '컴포넌트관리 (MOBILE)';
include_once('../admin.head.php');

if ($main_id == null && $main_id == '') {
	$main_id = '2';
}

$sql_common = " from lt_design_main_mobile ";
$sql_where = " where (1) ";

$token = get_admin_token();

//$sql = " select COUNT(*) as cnt {$sql_common} {$sql_where} ";
//$row = sql_fetch($sql);
//$cnt = $row['cnt'];

?>

<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="x_panel">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th scope="col" class="text-center active">레이아웃</th>
					</tr>
				</thead>
				<tbody>
					<?
					$sql = "
					select
					main_id,
					main_name,
					main_fixed,
					main_order,
					main_onoff,
					main_type1,
					main_type2,
					main_view_data,
					main_datetime
					$sql_common
					$sql_where
					order by
					main_order ";
					$result = sql_query($sql);
					$i = 0;

					while ($row = sql_fetch_array($result)) {
						$i++;

						$bg = 'bg' . ($i % 2);

						$bgType = '';

						$mainType1 = "";
						switch ($row['main_type1']) {
							case "gnb": {
									$mainType1 = $row['main_name'];
								}
								break;
							case "rolling":
							case "image":
							case "imagetext":
							case "banner":
							case "motion": {
									$mainType1 = "이미지 영역 - " . $row['main_name'];
								}
								break;
							case "subproduct": {
									$mainType1 = "서브상품영역 - " . $row['main_name'];
								}
								break;
							case "movie": {
									$mainType1 = "영상영역 - " . $row['main_name'];
								}
								break;
							case "sns": {
									$mainType1 = $row['main_name'];
								}
								break;
							default: {
									$mainType1 = $row['main_name'];
								}
						}


						if ($row['main_id'] == $main_id) $bgType = 'bg-primary';
						else if ($row['main_fixed'] == "Y") $bgType = 'bg-success';
						else $bgType = '';


						?>

							<tr class="<?= $bg; ?>">

								<? if ($row['main_id'] == "3") { ?>
									<td scope="col" class="text-center" rowspan="4">
										<table class="table table-bordered" style="margin-bottom: 0px;" style="height: 100px;">
											<tr class="bg-success">
												<td style="cursor:pointer;vertical-align: middle;" <? if ($main_id == "3") echo 'class="bg-primary"'; ?> onclick="location.href='./design_component_mobile.php?main_id=3';">메인 배너<br />영역 1</td>
												<td rowspan="2" style="cursor:pointer;vertical-align: middle;" <? if ($main_id == "5") echo 'class="bg-primary"'; ?> onclick="location.href='./design_component_mobile.php?main_id=5';">상품<br />영역</td>
												<td rowspan="2" style="cursor:pointer;vertical-align: middle;" <? if ($main_id == "6") echo 'class="bg-primary"'; ?> onclick="location.href='./design_component_mobile.php?main_id=6';">서비스<br />소개<br />영역</td>
											</tr>
											<tr class="bg-success">
												<td style="cursor:pointer;vertical-align: middle;" <? if ($main_id == "4") echo 'class="bg-primary"'; ?> onclick="location.href='./design_component_mobile.php?main_id=4';">메인 배너<br />영역 2</td>
											</tr>
										</table>
									</td>
								<? } else if ($row['main_id'] == "4" || $row['main_id'] == "5" || $row['main_id'] == "6") { ?>
								<? } else { ?>

									<td scope="col" class="text-center">

										<table class="table table-bordered" style="margin-bottom: 0px;">
											<tr>
												<td class="<?= $bgType; ?>" style="cursor:pointer;vertical-align: middle;" onclick="location.href='./design_component_mobile.php?main_id=<?= $row['main_id'] ?>';">
													<?= $mainType1 ?>
												</td>
											</tr>
										</table>

									<? } ?>
									</td>
							</tr>
						<? } ?>
				</tbody>
			</table>


			<?

			$sql = " select * from lt_design_main_mobile where main_id = '{$main_id}' ";
			$view = sql_fetch($sql);
			$main_type1 = $view['main_type1'];

			if ($chType1) $main_type1 = $chType1;

			$main_view_data = json_decode(str_replace('\\', '', $view['main_view_data']), true);
			?>
		</div>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="x_panel">
			<form name="frm" id="frm" method="post" onsubmit="return frm_submit(this);" enctype="multipart/form-data">
				<input type="hidden" name="token" value="<?= $token ?>">
				<input type="hidden" name="main_id" value="<?= $view['main_id'] ?>">

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> <?= $view['main_name']; ?> <small></small></h4>
					<div class="clearfix"></div>
				</div>

				<table class="table table-bordered">
					<tbody>
						<tr>
							<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">영역명</th>
							<td><input type="text" name="main_name" value="<?= $view['main_name']; ?>" id="main_name" class="form-control" <? if ($view['main_fixed'] == "Y") echo "readonly"; ?>></td>
						</tr>
						<tr>
							<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">타입설정</th>
							<td>
								<?
								if ($view['main_fixed'] == "Y" || $view['main_type1'] == "sns" || $view['main_id'] == "2") {
									?>
									<input type="hidden" name="main_type1" value="<?= $view['main_type1'] ?>">
									<select id="main_type1" class="form-control" disabled="disabled">
									<?
									} else {
										?>
										<select name="main_type1" id="main_type1" class="form-control">
										<?
										}
										?>
										<? if ($main_type1 == "gnb") { ?><option value="gnb" <?= get_selected($main_type1, 'gnb'); ?>>공통 GNB</option><? } ?>
										<? if ($main_type1 == "product") { ?><option value="product" <?= get_selected($main_type1, 'product'); ?>>상품타입</option><? } ?>
										<? if ($main_type1 == "sns") { ?><option value="sns" <?= get_selected($main_type1, 'sns'); ?>>SNS</option><? } ?>
										<? if ($main_type1 == "footer") { ?><option value="footer" <?= get_selected($main_type1, 'footer'); ?>>FOOTER</option><? } ?>

										<option value="image" <?= get_selected($main_type1, 'image'); ?>>이미지타입-일반이미지</option>
										<option value="imagetext" <?= get_selected($main_type1, 'imagetext'); ?>>이미지타입-이미지+텍스트</option>
										<option value="rolling" <?= get_selected($main_type1, 'rolling'); ?>>이미지타입-롤링이미지</option>
										<option value="motion" <?= get_selected($main_type1, 'motion'); ?>>이미지타입-모션이미지</option>
										<option value="banner" <?= get_selected($main_type1, 'banner'); ?>>이미지타입-띠배너</option>

										<option value="movie" <?= get_selected($main_type1, 'movie'); ?>>동영상타입</option>

										<option value="subproduct" <?= get_selected($main_type1, 'subproduct'); ?>>서브 상품타입</option>

										</select>
							</td>
						</tr>
						<?
						$main_type2_maxcount = 4;
						$selmain_type2_maxcount = 10;
						$selMain_type2_hidden = true;


						if ($main_type1 == "gnb") {
							?>
							<!-- 공통 GNB 영역 -->
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">영역소개문구</th>
								<td><input type="text" name=title_name value="<?= $main_view_data['title_name']; ?>" id="title_name" class="form-control" required></td>
							</tr>
						<?php
						} else if ($main_type1 == "movie") {
							?>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">타이틀명</th>
								<td><input type="text" name=title_name value="<?php echo $main_view_data['title_name']; ?>" id="title_name" class="form-control"></td>
							</tr>
						<?
						} else if ($main_type1 == "product") {
							?>
							<!-- 상품타입 -->
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">영역명</th>
								<td><input type="text" name=title_name value="<?= $main_view_data['title_name']; ?>" id="title_name" class="form-control" required placeholder="상품 영역"></td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">상품노출수량</th>
								<td>
									<div class="col-md-2 col-lg-2 col-sm-6">
										<input type="number" name="view_count" value="6" id="view_count" class="form-control" readonly="readonly">
									</div>
									<div class="text-left col-md-2 col-lg-2 col-sm-6" style="padding-top: 7px;"><label>개</label></div>
								</td>
							</tr>
						<?
						} else if ($main_type1 == "subproduct") {
							$main_type2_maxcount = 4;
							?>
							<!-- 서브 상품타입 -->
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">영역명</th>
								<td><input type="text" name=title_name value="<?= $main_view_data['title_name']; ?>" id="title_name" class="form-control" required placeholder="서브 상품 영역"></td>
							</tr>

							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">수량설정</th>
								<td>
									<div class="radio">
										<? for ($i = 1; $i <= $main_type2_maxcount; $i++) {
												echo '<label id="main_type2_' . $i . '"><input type="radio" name="main_type2" value="' . $i . '" ' . get_checked($view['main_type2'], $i) . ' >' . $i . '단 구성</label>&nbsp;&nbsp;&nbsp;';
											} ?>
									</div>
								</td>
							</tr>

							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">상품노출수량</th>
								<td>
									<div class="col-md-2 col-lg-2 col-sm-6">
										<input type="number" name="view_count" value="<?= $main_view_data['view_count']; ?>" id="view_count" class="form-control" required>
									</div>
									<div class="text-left col-md-2 col-lg-2 col-sm-6" style="padding-top: 7px;"><label>개</label></div>
								</td>
							</tr>
							<?
							} else if ($main_type1 == "image" || $main_type1 == "imagetext" || $main_type1 == "motion" || $main_type1 == "rolling" || $main_type1 == "banner" || $main_type1 == "sns") {
								$main_type2_maxcount = 5;
								$use_title = true;
								$use_type1 = true;
								$use_type2 = false;
								$use_type3 = false;

								if ($main_type1 == "imagetext") {
									$use_title = true;
									$use_type1 = false;
									$use_type2 = true;
									$main_type2_maxcount = 4;
								}

								if ($main_type1 == "rolling") {
									$use_title = true;
									$use_type1 = false;
									$use_type2 = false;
									$use_type3 = true;
									$main_type2_maxcount = 10;
								}

								if ($main_type1 == "banner") {
									$use_title = true;
									$use_type1 = false;
									$use_type2 = false;
									$main_type2_maxcount = 1;
									$view['main_type2'] = 1;
								}

								if ($main_type1 == "sns") {
									$use_title = true;
									$use_type1 = false;
									$use_type2 = false;
								}

								if ($use_title) {
									?>
								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">타이틀명</th>
									<td><input type="text" name=title_name value="<?= $main_view_data['title_name']; ?>" id="title_name" class="form-control"></td>
								</tr>
							<? } ?>

							<? if ($use_type1) { ?>
								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">수량설정</th>
									<td>
										<div class="col-md-10 col-lg-10 col-sm-10">
											<div class="radio">
												<? for ($i = 1; $i <= $main_type2_maxcount; $i++) {
															echo '<label id="main_type2_' . $i . '"><input type="radio" name="main_type2" value="' . $i . '" ' . get_checked($view['main_type2'], $i) . ' >' . $i . '개</label>&nbsp;&nbsp;&nbsp;';
														} ?>
											</div>
										</div>
										<div class="text-right col-md-2 col-lg-2 col-sm-2">
											<input type="button" class="btn btn-secondary" id="btnModifyMain_type2" value="수정"></input>
										</div>
									</td>
								</tr>
							<? } ?>
							<? if ($use_type2) { ?>
								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">수량설정</th>
									<td>
										<div class="col-md-10 col-lg-10 col-sm-10">
											<div class="radio">
												<? for ($i = 1; $i <= $main_type2_maxcount; $i++) {
															echo '<label id="main_type2_' . $i . '"><input type="radio" name="main_type2" value="' . $i . '" ' . get_checked($view['main_type2'], $i) . ' >' . $i . '단 구성</label>&nbsp;&nbsp;&nbsp;';
														} ?>
											</div>
										</div>
										<div class="text-right col-md-2 col-lg-2 col-sm-2">
											<input type="button" class="btn btn-secondary" id="btnModifyMain_type2" value="수정"></input>
										</div>
									</td>
								</tr>

							<? } ?>

							<? if ($use_type3) { ?>
								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">수량설정</th>
									<td>
										<div class="col-md-10 col-lg-10 col-sm-10">
											<select name="main_type2" id="selMain_type2" class="form-control">
												<option value="">이미지수량</option>
												<? for ($i = 1; $i <= 10; $i++) {
															echo '<option value="' . $i . '" ' . get_selected($view['main_type2'], $i) . '>' . $i . '</option>';
														} ?>
											</select>
										</div>
										<div class="text-right col-md-2 col-lg-2 col-sm-2">
											<input type="button" class="btn btn-secondary" id="btnModifySelMain_type2" value="수정"></input>
										</div>
									</td>
								</tr>
							<? } ?>

						<? } else { ?>
							<input type="hidden" name="main_type2" value="<?= $view['main_type2'] ?>">
						<? } ?>

					</tbody>
				</table>

				<?
				if ($main_type1 == "image" || $main_type1 == "imagetext" || $main_type1 == "rolling" || $main_type1 == "motion" || $main_type1 == "banner") {

					for ($i = 1; $i <= $main_type2_maxcount; $i++) {
						?>
						<table class="table table-bordered <? if ($view['main_type2'] < $i) echo 'hidden'; ?>" id="tblImage<?= $i ?>">
							<thead>
								<tr>
									<th scope="col" class="text-center active" colspan="2">이미지 <?= $i ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">전시순서</th>
									<td>
										<select name="imgOrder[]" id="imgOrder<?= $i ?>" class="form-control">
											<? for ($j = 1; $j <= $main_type2_maxcount; $j++) {
														echo '<option value="' . $j . '" ' . get_selected($i, $j) . '>순서 ' . $j . '</option>';
													} ?>
										</select>
									</td>
								</tr>

								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">이미지 등록</th>
									<td>
										<div class="col-md-6 col-lg-6 col-sm-6">
											<?
													$img_data = $main_view_data['imgFile'][$i - 1];

													$img_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $img_data['imgFile'];
													if ($img_data['imgFile'] && file_exists($img_file)) {
														$img_url = G5_DATA_URL . '/design/' . $main_id . '/' . $img_data['imgFile'];
														echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile' . $i . '" style="width: 100%; height: 30%;">';
													} else {
														echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile' . $i . '" style="width: 100%; height: 30%;">';
													}
													?>
										</div>

										<div class="col-md-6 col-lg-6 col-sm-6">
											<div class="input-group">
												<span class="">
													<div class="btn btn-info">
														<span><? if ($img_data) echo '이미지 수정';
																		else echo '이미지 등록'; ?></span>
														<input type="file" id="imgFile<?= $i ?>" name="imgFile[]" class="hiddenFile" delBtnID="btnDelimgFile<?= $i ?>" imgID="imgimgFile<?= $i ?>" style="width:100px" accept=".jpg, .png">
													</div>
												</span>
												<button class="btn btn-danger <? if (!$img_data['imgFile']) echo 'hidden'; ?>" type="button" id="btnDelimgFile<?= $i ?>" fileBtnID="imgFile<?= $i ?>">삭제</button>

												<input type="hidden" id="orgimgFile<?= $i ?>" name="orgimgFile[]" value="<?= $img_data['imgFile']; ?>">

											</div>
										</div>

										<div class="col-md-12 col-lg-12 col-sm-12">
											<div class="clearfix"></div><br />
											<span class="red">* 업로드 이미지 사이즈 (<?= $view['main_width'] ?>px * <?= $view['main_height'] ?>px) <br />
												* 최대 15MB / 확장자 jpg, png만 가능</span>
										</div>

									</td>
								</tr>

								<tr>
									<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">링크연결</th>
									<td>
										<div class="radio col-md-12 col-sm-12 col-xs-12">
											<label><input type="radio" class="imgLinkYN" name="imgLinkYN<?= $i ?>" data="<?= $i ?>" value="N" <?= get_checked($img_data['imgLinkYN'], 'N') ?> <?= get_checked($img_data['imgLinkYN'], null) ?>>링크없음</label>&nbsp;&nbsp;
											<label><input type="radio" class="imgLinkYN" name="imgLinkYN<?= $i ?>" data="<?= $i ?>" value="Y" <?= get_checked($img_data['imgLinkYN'], 'Y') ?>>URL</label>
										</div>
										<input type="text" class="form-control <?= get_hidden($img_data['imgLinkYN'], 'N') ?> <?= get_hidden($img_data['imgLinkYN'], null) ?>" id="linkURL<?= $i ?>" name="linkURL[]" value="<?= $img_data['linkURL']; ?>">
									</td>
								</tr>

								<? if ($main_type1 == "imagetext") { ?>
									<tr>
										<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">텍스트 사용</th>
										<td>
											<div class="radio col-md-12 col-sm-12 col-xs-12">
												<label><input type="radio" class="imgTextYN" name="imgTextYN<?= $i ?>" data="<?= $i ?>" value="N" <?= get_checked($img_data['imgTextYN'], 'N') ?> <?= get_checked($img_data['imgTextYN'], null) ?>>사용하지 않음</label>&nbsp;&nbsp;
												<label><input type="radio" class="imgTextYN" name="imgTextYN<?= $i ?>" data="<?= $i ?>" value="Y" <?= get_checked($img_data['imgTextYN'], 'Y') ?>>사용함</label>
											</div>
										</td>
									</tr>

									<tr id="trMainText<?= $i ?>" class="<?= get_hidden($img_data['imgTextYN'], 'N') ?> <?= get_checked($img_data['imgTextYN'], null) ?>">
										<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">메인 텍스트</th>
										<td>
											<input type="text" class="form-control" id="txtMainText<?= $i ?>" name="mainText[]" value="<?= $img_data['mainText']; ?>">
										</td>
									</tr>

									<tr id="trSubText<?= $i ?>" class="<?= get_hidden($img_data['imgTextYN'], 'N') ?> <?= get_checked($img_data['imgTextYN'], null) ?>">
										<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">서브 텍스트</th>
										<td>
											<input type="text" class="form-control" id="txtSubText<?= $i ?>" name="subText[]" value="<?= $img_data['subText']; ?>">
										</td>
									</tr>
								<? } ?>

							</tbody>
						</table>

						<div class="clearfix"></div>
					<? } ?>

				<? } else if ($main_type1 == "movie") { ?>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col" class="text-center active" colspan="2">동영상</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">동영상<br />대표이미지 등록</th>
								<td>
									<div class="col-md-6 col-lg-6 col-sm-6">
										<?
											$movieimg_file = G5_DATA_PATH . '/design/' . $main_id . '/' . $main_view_data['movieimg'];
											if (file_exists($movieimg_file)) {
												$movieimg_url = G5_DATA_URL . '/design/' . $main_id . '/' . $main_view_data['movieimg'];
												echo '<img src="' . $movieimg_url . '" class="img-thumbnail" id="imgmovieimg" style="width: 100%; height: 30%;">';
											} else {
												echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgmovieimg" style="width: 100%; height: 30%;">';
											}
											?>
									</div>
									<div class="col-md-6 col-lg-6 col-sm-6">
										<div class="input-group">
											<span class="">
												<div class="btn btn-info">
													<span><? if ($main_view_data['movieimg']) echo '이미지 수정';
																else echo '이미지 등록'; ?></span>
													<input type="file" id="movieimg" name="movieimg" class="hiddenFile" delBtnID="btnDelmovieimg" imgID="imgmovieimg" style="width:100px" accept=".jpg, .png">
												</div>
											</span>
											<button class="btn btn-danger <? if (!$main_view_data['movieimg']) echo 'hidden'; ?>" type="button" id="btnDelmovieimg" fileBtnID="movieimg">삭제</button>
											<input type="hidden" id="orgmovieimg" name="orgmovieimg" value="<?= $main_view_data['movieimg']; ?>">
										</div>
									</div>

									<div class="col-md-12 col-lg-12 col-sm-12">
										<div class="clearfix"></div><br />
										<span class="red">* 업로드 이미지 사이즈 (<?= $view['main_width'] ?>px * <?= $view['main_height'] ?>px) <br />
											* 최대 15MB / 확장자 jpg, png만 가능</span>
									</div>

								</td>
							</tr>

							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">동영상<br />등록</th>
								<td>
									<div class="col-md-12 col-lg-12 col-sm-12">
										<span class="red">* 최대 15MB / 확장자 mov, avi, mp4만 가능</span>
										<div class="clearfix"></div><br />
									</div>

									<div class="col-md-6 col-lg-6 col-sm-6">
										<input type="text" class="form-control" id="txtmoviefile" disabled="disabled" value="<?= $main_view_data['moviefile']; ?>">
									</div>

									<div class="col-md-6 col-lg-6 col-sm-6">
										<div class="input-group">
											<span class="">
												<div class="btn btn-info">
													<span><? if ($main_view_data['moviefile']) echo '동영상 수정';
																else echo '동영상 등록'; ?></span>
													<input type="file" id="moviefile" name="moviefile" class="hiddenFile" labalID="txtmoviefile" delBtnID="btnDelmoviefile" style="width:100px" accept=".mov, .avi, .mp4">
												</div>
											</span>
											<button class="btn btn-danger <? if (!$main_view_data['moviefile']) echo 'hidden'; ?>" type="button" id="btnDelmoviefile" fileBtnID="moviefile">삭제</button>

											<input type="hidden" id="orgmoviefile" name="orgmoviefile" value="<?= $main_view_data['moviefile']; ?>">
										</div>
									</div>

								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">링크연결</th>
								<td>
									<div class="radio col-md-12 col-sm-12 col-xs-12">
										<label><input type="radio" class="imgLinkYN" name="imgLinkYN0" data="0" value="N" <?php echo get_checked($main_view_data['imgLinkYN'], 'N') ?> <?php echo get_checked($main_view_data['imgLinkYN'], null) ?>>링크없음</label>&nbsp;&nbsp;
										<label><input type="radio" class="imgLinkYN" name="imgLinkYN0" data="0" value="Y" <?php echo get_checked($main_view_data['imgLinkYN'], 'Y') ?>>URL</label>
									</div>
									<input type="text" class="form-control <?php echo get_hidden($main_view_data['imgLinkYN'], 'N') ?> <?php echo get_hidden($main_view_data['imgLinkYN'], null) ?>" id="linkURL0" name="linkURL[]" value="<?php echo $main_view_data['linkURL']; ?>">
								</td>
							</tr>

						</tbody>
					</table>
				<? } else if ($main_type1 == "sns") { ?>

					<table class="table table-bordered">
						<tbody>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">해시태그</th>
								<td>
									<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
										<input type="text" class="form-control has-feedback-left" name="hashtag" placeholder="" value="<?= $main_view_data['hashtag']; ?>">
										<span class="form-control-feedback left" aria-hidden="true">#</span>
									</div>

									<div class="clearfix"></div><br />
									<div class="col-md-12 col-lg-12 col-sm-12">
										<span class="red">
											※ 해시태그는 최대 5개까지 등록할 수 있으며, 콤마(,)구분하여 주십시오.<br />
											예)aaa, bbb<br />
											※ 해시태그에 공백이 존재할 경우 정상적인 동작이 불가합니다.<br />
											※ 해시태그 등록 시 특수기호, 아이콘등이 포함되면 정상적으로 수집되지 않을 수 있습니다<br />
										</span>
									</div>

								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">정렬방식</th>
								<td>
									<div class="radio col-md-12 col-sm-12 col-xs-12">
										<label><input type="radio" name="imgOrder" value="R" <?= get_checked($main_view_data['imgOrder'], 'R') ?> <?= get_checked($main_view_data['imgOrder'], null) ?>>등록순</label>&nbsp;&nbsp;
										<label><input type="radio" name="imgOrder" value="L" <?= get_checked($main_view_data['imgOrder'], 'L') ?>>좋아요순</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">위젯타입</th>
								<td>
									<div class="radio col-md-12 col-sm-12 col-xs-12">
										<label><input type="radio" name="widget" value="grid" <?= get_checked($main_view_data['widget'], 'grid') ?> <?= get_checked($main_view_data['widget'], null) ?>>Grid</label>&nbsp;&nbsp;
										<label><input type="radio" name="widget" value="board" <?= get_checked($main_view_data['widget'], 'board') ?>>Board</label>
										<label><input type="radio" name="widget" value="scrolling" <?= get_checked($main_view_data['widget'], '"scrolling"') ?>>Scrolling</label>
										<label><input type="radio" name="widget" value="slideshow" <?= get_checked($main_view_data['widget'], 'slideshow') ?>>Slideshow</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">썸네일 사이즈</th>
								<td>
									<div class="col-md-6 col-sm-10 col-xs-10">
										<input type="number" class="form-control" name="imgsize" value="<?= $main_view_data['imgsize']; ?>">

									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" style="vertical-align: middle;">
										<label style="padding-top: 5px;">PX</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">레이아웃</th>
								<td>
									<div class="col-md-4 col-sm-4 col-xs-4">
										<input type="number" class="form-control" name="imgCol" value="<?= $main_view_data['imgCol']; ?>">
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" style="vertical-align: middle;">
										<label style="padding-top: 5px;">X</label>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4">
										<input type="number" class="form-control" name="imgRow" value="<?= $main_view_data['imgRow']; ?>">
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3"></div>

									<div class="clearfix"></div><br />
									<div class="col-md-12 col-lg-12 col-sm-12">
										<span class="red">
											※사진의 '가로 X 세로' 표시할 개수를 숫자로 입력해 주세요. <br /> (최대 20개 까지 설정 가능)
										</span>
									</div>

								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">이미지테두리</th>
								<td>
									<div class="radio col-md-12 col-sm-12 col-xs-12">
										<label><input type="radio" name="imgBorder" value="Y" <?= get_checked($main_view_data['imgBorder'], 'Y') ?> <?= get_checked($main_view_data['imgBorder'], null) ?>>표시함</label>&nbsp;&nbsp;
										<label><input type="radio" name="imgBorder" value="N" <?= get_checked($main_view_data['imgBorder'], 'N') ?>>표시안함</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">이미지간격</th>
								<td>
									<div class="col-md-6 col-sm-10 col-xs-10">
										<input type="number" class="form-control" name="imgDistance" value="<?= $main_view_data['imgDistance']; ?>">

									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" style="vertical-align: middle;">
										<label style="padding-top: 5px;">PX</label>
									</div>
								</td>
							</tr>

						</tbody>
					</table>
				<? } else if ($main_type1 == "product" || $main_type1 == "subproduct") { ?>

					<div class="x_title">
						<h5><span class="fa fa-check-square"></span> 상품선정</h5>
						<div class="clearfix"></div>
					</div>

					<div class="text-right">
						<input type="button" class="btn btn-secondary" value="상품찾기" id="btnProductSearch" />
						<input type="button" class="btn btn-danger" value="삭제" id="btnProductDel" />

						<input type="hidden" value="<?= implode(",", $main_view_data['it_id']); ?>" name="it_id_list" id="it_id_list" />
					</div>

					<div class="tbl_frm01 tbl_wrap" id="tblProductForm">

					</div>

					<script>
						function tblProductFormBind() {

							var $table = $("#tblProductForm");
							$.post(
								"./design_component_itemsearch.php", {
									w: "u",
									it_id_list: $("#it_id_list").val()
								},
								function(data) {
									$table.empty().html(data);
								}
							);

						};

						tblProductFormBind();
					</script>

				<? } ?>

				<div class="x_content">
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12 text-right">
							<a href="/index.php?device=pc&preview_main_id=<?= $main_id ?>" id="preview"><input type="button" class="btn btn-secondary" value="미리보기"></input></a>
							<input type="submit" class="btn btn-success" value="적용하기" id="btnSubmit"></input>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

<div id="modal_product" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">상품찾기 팝업</h4>
			</div>
			<div class="modal-body">

				<form name="procForm" id="procForm" method="post">

					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label>상품번호/상품명</label></th>
									<td>
										<input type="text" name="stx" id="stx" value="" class="form-control">
									</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align: right;">
										<button type="button" class="btn btn-success" id="btnSearch">검색</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="tbl_frm01 tbl_wrap" id="tblProduct">
						<? include_once('./design_component_itemsearch.php'); ?>
					</div>

				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
				<button type="button" class="btn btn-success" id="btnProductSubmit">상품등록</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		$(document).ready(function($) {

			$("#preview").on("click", function() {
				var url = this.href;
				window.open(url, "preview", "left=100,top=100,width=1920,height=800,scrollbars=1");
				return false;
			});

			$("#btnSearch").click(function(event) {
				var $table = $("#tblProduct");

				$.post(
					"./design_component_itemsearch.php", {
						stx: $("#stx").val(),
						not_it_id_list: $("#it_id_list").val()
					},
					function(data) {
						$table.empty().html(data);
					}
				);
			});

			$("#btnProductDel").click(function(event) {
				if (!is_checked("chk2[]")) {
					alert("삭제 하실 항목을 하나 이상 선택하세요.");
					return false;
				}

				if (confirm("삭제하시겠습니까?")) {

					var $chk = $("input[name='chk2[]']");
					var $it_id = new Array();

					for (var i = 0; i < $chk.size(); i++) {
						if (!$($chk[i]).is(':checked')) {
							var k = $($chk[i]).val();
							$it_id.push($("input[name='it_id2[" + k + "]']").val());
						}
					}

					$("#it_id_list").val($it_id.join(","));
					tblProductFormBind();
				}
			});


			$("#btnProductSubmit").click(function(event) {

				if (!is_checked("chk[]")) {
					alert("등록 하실 항목을 하나 이상 선택하세요.");
					return false;
				}

				var $chk = $("input[name='chk[]']:checked");
				var $it_id = new Array();

				for (var i = 0; i < $chk.size(); i++) {
					var k = $($chk[i]).val();
					$it_id.push($("input[name='it_id[" + k + "]']").val());
				}
				var it_id_list = $it_id.join(",");

				if ($("#it_id_list").val() != "") it_id_list += "," + $("#it_id_list").val();

				$("#it_id_list").val(it_id_list);

				tblProductFormBind();

				$("#modal_product").modal('hide');
			});


			$("#btnProductSearch").click(function(event) {
				$("#stx").val("");
				var $table = $("#tblProduct");
				$table.empty();
				$("#modal_product").modal('show');
			});

			$("input.imgTextYN").change(function(event) {
				var imgTextYN = $(this).val();
				var i = $(this).attr("data");


				if (imgTextYN == "Y") {
					$("#trMainText" + i).removeClass('hidden');
					$("#trSubText" + i).removeClass('hidden');
				} else {
					$("#trMainText" + i).removeClass('hidden').addClass('hidden');
					$("#trSubText" + i).removeClass('hidden').addClass('hidden');
					$("#txtMainText" + i).val("");
					$("#txtSubText" + i).val("");

				}
			});

			$("input.imgLinkYN").change(function(event) {
				var imgLinkYN = $(this).val();
				var i = $(this).attr("data");

				if (imgLinkYN == "Y") {
					$("#linkURL" + i).removeClass('hidden');
				} else {
					$("#linkURL" + i).removeClass('hidden').addClass('hidden');
					$("#linkURL" + i).val("");
				}
			});

			$("#main_type1").change(function(event) {
				//alert($(this).val());
				location.href = './design_component_mobile.php?main_id=<?= $view['main_id'] ?>&chType1=' + $(this).val();
			});


			$("#btnModifyMain_type2").click(function(event) {
				//alert($("input:radio[name=main_type2]:checked").val());
				var main_type2 = $("input:radio[name=main_type2]:checked").val();
				for (i = 1; i <= 5; i++) {
					if (i <= main_type2) {
						$("#tblImage" + i).removeClass('hidden');
					} else {
						$("#tblImage" + i).removeClass('hidden').addClass('hidden');
					}
				}
			});

			$("#btnModifySelMain_type2").click(function(event) {
				//alert($("input:radio[name=main_type2]:checked").val());
				var main_type2 = $("#selMain_type2").val();
				for (i = 1; i <= 10; i++) {
					if (i <= main_type2) {
						$("#tblImage" + i).removeClass('hidden');
					} else {
						$("#tblImage" + i).removeClass('hidden').addClass('hidden');
					}
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


			$.setImgFileUpload('movieimg');
			$.setImgFileUpload('moviefile');

			$.setImgFileUpload('imgFile1');
			$.setImgFileUpload('imgFile2');
			$.setImgFileUpload('imgFile3');
			$.setImgFileUpload('imgFile4');
			$.setImgFileUpload('imgFile5');
			$.setImgFileUpload('imgFile6');
			$.setImgFileUpload('imgFile7');
			$.setImgFileUpload('imgFile8');
			$.setImgFileUpload('imgFile9');
			$.setImgFileUpload('imgFile10');

		});
	});

	function frm_submit(f) {
		var main_type1 = $("#main_type1").val();

		//동영상타입
		if (main_type1 == "movie") {
			if ($("#orgmovieimg").val() == "" && $("#movieimg").val() == "") {
				alert('동영상 이미지를 확인해주세요.');
				return false;
			}
			/*
			if($("#orgmoviefile").val() == "" &&  $("#moviefile").val() == "")
			{
				alert('동영상을 확인해주세요.');
				return false;
			}*/
		}

		if (confirm("적용하시겠습니까?")) {
			f.action = "./design_component_mobile_update.php";
			return true;
		}
		return false;
	}

	function check_all2(f) {
		var chk = document.getElementsByName("chk2[]");

		for (i = 0; i < chk.length; i++)
			chk[i].checked = f.chkall.checked;
	}

	function changeSort(elem, action) {
		const value = $(elem).parent().data("value");
		const $rows = $("#tbodyProduct>tr>td.it_id_sort");
		let $current, targetIdx;

		$rows.each(function(idx, elem) {
			if ($(elem).data("value") == value) {
				targetIdx = action == 'up' ? idx - 1 : idx + 1;
				$current = $(elem).parent();
			}
		});

		if (targetIdx >= 0 && targetIdx < $rows.length) {
			$rows.each(function(idx, elem) {
				if (idx == targetIdx) {
					if (action == 'up') {
						$(elem).parent().before($current);
					} else {
						$(elem).parent().after($current)
						// $current.after($(elem).parent());
					}
				}
			});

			let values = [];
			$("#tbodyProduct>tr>td.it_id_sort").each(function(idx, elem) {
				values.push($(elem).data("value"));
			});
			$("#it_id_list").val(values.join(','));
		}
	}
</script>

<?
include_once('../admin.tail.php');
?>