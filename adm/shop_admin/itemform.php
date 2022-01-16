<?php
$sub_menu = '30';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH . '/iteminfo.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$html_title = "제품 ";

if ($w == "") {
	$html_title .= "등록";

	// 옵션은 쿠키에 저장된 값을 보여줌. 다음 입력을 위한것임
	//$it[ca_id] = _COOKIE[ck_ca_id];
	$it['ca_id'] = get_cookie("ck_ca_id");
	$it['ca_id2'] = get_cookie("ck_ca_id2");
	$it['ca_id3'] = get_cookie("ck_ca_id3");
	if (!$it['ca_id']) {
		$sql = " select ca_id from {$g5['g5_shop_category_table']} order by ca_order, ca_id limit 1 ";
		$row = sql_fetch($sql);
		if (!$row['ca_id'])
			alert("등록된 분류가 없습니다. 우선 분류를 등록하여 주십시오.", './categorylist.php');
		$it['ca_id'] = $row['ca_id'];
	}
	//$it[it_maker]  = stripslashes($_COOKIE[ck_maker]);
	//$it[it_origin] = stripslashes($_COOKIE[ck_origin]);
	$it['it_maker']  = stripslashes(get_cookie("ck_maker"));
	$it['it_origin'] = stripslashes(get_cookie("ck_origin"));


	$it['it_use'] = 1;
	$it['it_item_type'] = 0; //0:제품,1:리스

	$it['its_discount_type'] = 0; //할인설정

	$it['its_free_laundry'] = 1; //
	$it['its_laundry_use'] = 0; //
	$it['its_laundrykeep_use'] = 0; //
	$it['its_repair_use'] = 0; //

	$it['it_item_rental_month'] = '36';


	$it['it_sc_type'] = 0;
	$it['it_send_type'] = $default['de_send_type'];;
	$it['it_send_term_start'] = $default['de_send_term_start'];
	$it['it_send_term_end'] = $default['de_send_term_end'];

	$it['it_sc_minimum'] = $default['de_send_cost_limit'];
	$it['it_sc_price'] = $default['de_send_cost_list'];

	$it['it_send_condition'] = $default['de_send_condition'];
	$it['it_sc_method'] = $default['de_send_prepayment'];

	$it['it_individual_costs_use'] = '0';

	$it['it_delivery_company'] = $default['de_delivery_company'];
	$it['it_return_costs'] = $default['de_return_costs'];
	$it['it_roundtrip_costs'] = $default['de_roundtrip_costs'];

	$it['it_return_zip'] = $default['de_return_zip'];
	$it['it_return_address1'] = $default['de_return_address1'];
	$it['it_return_address2'] = $default['de_return_address2'];


	$it['it_skin'] = 'basic';
	$it['it_mobile_skin'] = 'basic';

	$it['it_brand'] = '';
	$it['it_model'] = '';

	$it['it_type1'] = '';
	$it['it_type2'] = '';
	$it['it_type3'] = '';
	$it['it_type4'] = '';
	$it['it_type5'] = '';

	$it['it_level_sell'] = '0';
	$it['it_point_type'] = '9';
	$it['it_point_only'] = '0';
	$it['it_nocoupon'] = '0';
	$it['it_point_use'] = '0';
	$it['it_use_use'] = '0';
	$it['it_review_use'] = '0';

	$it['it_sc_method'] = '2';

	$it['it_mobile_explan_use'] = '0';

	$it['it_view_list_items'] = "한줄설명,좋아요";
	$it['it_view_detail_items'] = "한줄설명,좋아요,공유하기";
} else if ($w == "u") {
	$html_title .= "수정";

	// if ($is_admin != 'super') {
	// 	$sql = " select it_id from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b
	// 	where a.it_id = '$it_id'
	// 	and a.ca_id = b.ca_id
	// 	and b.ca_mb_id = '{$member['mb_id']}' ";
	// 	$row = sql_fetch($sql);
	// 	if (!$row['it_id'])
	// 		alert("\'{$member['mb_id']}\' 님께서 수정 할 권한이 없는 제품입니다.");
	// }

	$sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
	$it = sql_fetch($sql);

	if (!$it)
		alert('제품정보가 존재하지 않습니다.');

	if (!$ca_id)
		$ca_id = $it['ca_id'];

	$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
	$ca = sql_fetch($sql);
} else {
	alert();
}

$qstr  = $qstr . '&amp;sca=' . $sca . '&amp;page=' . $page;

$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH . '/admin.head.php');

// 분류리스트
$category_select = '';
$script = '';
$category_sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super') {
	$category_sql .= " where ca_mb_id = '{$member['mb_id']}' ";
}
$category_sql .= " order by ca_order, ca_id ";
$category_result = sql_query($category_sql);
for ($i = 0; $row = sql_fetch_array($category_result); $i++) {
	$len = strlen($row['ca_id']) / 2 - 1;

	$nbsp = "";
	for ($i = 0; $i < $len; $i++)
		$nbsp .= "&nbsp;&nbsp;&nbsp;";

	$category_select .= "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";

	$script .= "ca_use['{$row['ca_id']}'] = {$row['ca_use']};\n";
	$script .= "ca_stock_qty['{$row['ca_id']}'] = {$row['ca_stock_qty']};\n";
	//$script .= "ca_explan_html['$row[ca_id]'] = $row[ca_explan_html];\n";
	$script .= "ca_sell_email['{$row['ca_id']}'] = '{$row['ca_sell_email']}';\n";
}

$pg_anchor = '';

//삼진 자료 요청
SM_TRAN_ORDER_DATA();

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<form name="fitemform" id="fitemform" action="./itemformupdate.php" method="post" onsubmit="return fitemformcheck(this)" enctype="multipart/form-data">

				<input type="hidden" name="codedup" value="<?php echo $default['de_code_dup_use']; ?>">
				<input type="hidden" name="codedup_it_id" value="">
				<input type="hidden" name="w" value="<?php echo $w; ?>">
				<input type="hidden" name="sca" value="<?php echo $sca; ?>">
				<input type="hidden" name="sst" value="<?php echo $sst; ?>">
				<input type="hidden" name="sod" value="<?php echo $sod; ?>">
				<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
				<input type="hidden" name="stx" value="<?php echo $stx; ?>">
				<input type="hidden" name="page" value="<?php echo $page; ?>">

				<!-- 기본 설정 -->
				<input type="hidden" name="it_skin" value="<?php echo $it['it_skin']; ?>">
				<input type="hidden" name="it_mobile_skin" value="<?php echo $it['it_mobile_skin']; ?>">

				<input type="hidden" name="it_maker" value="<?php echo $it['it_maker']; ?>">
				<input type="hidden" name="it_origin" value="<?php echo $it['it_origin']; ?>">
				<!-- <input type="hidden" name="it_brand" value="<?php echo $it['it_brand']; ?>"> -->
				<input type="hidden" name="it_model" value="<?php echo $it['it_model']; ?>">

				<input type="hidden" name="it_type1" value="<?php echo $it['it_type1']; ?>">
				<input type="hidden" name="it_type2" value="<?php echo $it['it_type2']; ?>">
				<input type="hidden" name="it_type3" value="<?php echo $it['it_type3']; ?>">
				<input type="hidden" name="it_type4" value="<?php echo $it['it_type4']; ?>">
				<input type="hidden" name="it_type5" value="<?php echo $it['it_type5']; ?>">

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 전시 위치 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<caption>제품분류 입력</caption>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>

								<tr>
									<th scope="row"><label for="it_use">진열상태</label></th>
									<td>
										<?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 \"진열안함\"을 선택해 놓으면 출력되지 않으며, 주문도 받지 않습니다."); ?>
										<div class="radio">
											<label><input type="radio" value="0" id="it_use0" name="it_use" <?php echo ($it['it_use'] == '0') ? 'checked' : ''; ?>> 진열안함 </label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" value="1" id="it_use1" name="it_use" <?php echo ($it['it_use'] != '0') ? 'checked' : ''; ?>> 진열함</label>
										</div>
									</td>
								</tr>

								<tr>
									<th scope="row"><label for="ca_id">카테고리 선택</label></th>
									<td>

										<table class="table table-bordered" style="height: 100%">
											<thead>
												<tr>
													<th scope="col" class="text-center active" width="25%">1 Depth</th>
													<th scope="col" class="text-center active" width="25%">2 Depth</th>
													<th scope="col" class="text-center active" width="25%">3 Depth</th>
													<th scope="col" class="text-center active" width="25%">4 Depth</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td scope="col" class="text-center" style="vertical-align: top;">
														<table class="table table-bordered catblGroup1" style="margin-bottom:0;">
															<?php
															$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 2 order by ca_order ";
															$result = sql_query($sql);
															$ca_name = "";
															$depth2 = "";
															$depth3 = "";
															$depth4 = "";

															$selected_menu_class = '';

															for ($i = 0; $row = sql_fetch_array($result); $i++) {
																if ($i == 0) {
																	$selected_menu_class = 'bg-primary';
																	$ca_name .= $row['ca_name'];
																} else $selected_menu_class = '';

																/*<tr><td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu('<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td></tr>*/
															?>
																<tr>
																	<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu('<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
																</tr>
															<?php } ?>
														</table>
													</td>
													<td scope="col" class="text-center" style="vertical-align: top;">
														<?php
														$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 4 order by substr(ca_id,1,2), ca_order ";
														$result = sql_query($sql);

														$selected_menu_class = '';
														$hidden_menu_class = '';
														$menu_code1 = '';
														$j = 0;

														for ($i = 0; $row = sql_fetch_array($result); $i++) {
															$cur_menu_code1 = substr($row['ca_id'], 0, 2);

															if ($menu_code1 != $cur_menu_code1) {
																$j = 0;

																if ($i != 0) echo '</table>';
																echo '<table class="table table-bordered catblGroup2 ' . $hidden_menu_class . '" style="margin-bottom:0;" id="catable' . $cur_menu_code1 . '">';
															}

															$selected_menu_class = '';
															if ($it_item_type == '0' && $row['ca_id'] == '1010') {
																$selected_menu_class = 'bg-primary';
																$ca_name .= ' > ' . $row['ca_name'];
																$depth2 = $row['ca_id'];
															} else if ($it_item_type == '1' && $row['ca_id'] == '1020') {
																$selected_menu_class = 'bg-primary';
																$ca_name .= ' > ' . $row['ca_name'];
																$depth2 = $row['ca_id'];
															}
															/*else if($i == 0) {
				$selected_menu_class = 'bg-primary';
				$ca_name .= ' > '.$row['ca_name'];
				$depth2 = $row['ca_id'];
			}
			else $selected_menu_class = '';

			<tr><td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu1('<?php echo $cur_menu_code1 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td></tr>*/
														?>
												<tr>
													<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu1('<?php echo $cur_menu_code1 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
												</tr>
											<?php
															if ($menu_code1 != $cur_menu_code1) {
																$menu_code1 = $cur_menu_code1;
															}
															$hidden_menu_class = 'hidden';
															$j++;
														}
														echo '</table>';
											?>

									</td>
									<td scope="col" class="text-center" style="vertical-align: top;">

										<?php
										$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 6 order by substr(ca_id,1,4), ca_order ";
										$result = sql_query($sql);

										$selected_menu_class = '';
										$hidden_menu_class = 'hidden';
										$menu_code2 = '';
										$j = 0;

										for ($i = 0; $row = sql_fetch_array($result); $i++) {
											$cur_menu_code2 = substr($row['ca_id'], 0, 4);
											$selected_menu_class = '';

											if ($depth2 == $cur_menu_code2) {
												$hidden_menu_class = '';
											}

											if ($menu_code2 != $cur_menu_code2) {
												$j = 0;

												if ($i != 0) echo '</table>';
												echo '<table class="table table-bordered catblGroup3 ' . $hidden_menu_class . '" style="margin-bottom:0;" id="catable' . $cur_menu_code2 . '">';
											}

											if ($depth2 == $cur_menu_code2 && $j == 0) {
												$selected_menu_class = 'bg-primary';
												$ca_name .= ' > ' . $row['ca_name'];
												$depth3 = $row['ca_id'];
											}
										?>
								<tr>
									<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu2('<?php echo $cur_menu_code2 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
								</tr>
							<?php
											if ($menu_code2 != $cur_menu_code2) {
												$menu_code2 = $cur_menu_code2;
											}
											$hidden_menu_class = 'hidden';
											$j++;
										}
										echo '</table>';
							?>

							</td>
							<td scope="col" class="text-center" style="vertical-align: top;">
								<!-- ekffk ekffk durt -->
								<?php
									$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 8 order by substr(ca_id,1,8), ca_order ";
									$result = sql_query($sql);

									$selected_menu_class = '';
									$hidden_menu_class = 'hidden';
									$menu_code3 = '';
									$j = 0;
									for ($i = 0; $row = sql_fetch_array($result); $i++) { 
										$cur_menu_code3 = substr($row['ca_id'], 0, 6);
										$selected_menu_class = '';
										// echo '$depth3 : '.$depth3.'<br>';
										// echo '$cur_menu_code3 : '.$cur_menu_code3.'<br>';
										// echo '$menu_code3 : '.$menu_code3.'<br>';
										if ($depth3 == $cur_menu_code3) {
											$hidden_menu_class = '';
										}

										if ($menu_code3 != $cur_menu_code3) {
											$j = 0;


											if ($i != 0) echo '</table>';
											echo '<table class="table table-bordered catblGroup4 ' . $hidden_menu_class . '" style="margin-bottom:0;" id="catable' . $cur_menu_code3 . '">';
										}

										if ($depth3 == $cur_menu_code3 && $j == 0) {
											$selected_menu_class = 'bg-primary';
											$ca_name .= ' > ' . $row['ca_name'];
											$depth4 = $row['ca_id'];

											// echo '$depth4 : '.$depth4.'<br>';
										}
									?>
								<tr>
								
									<!-- <td class="<?php echo $selected_menu_class ?>">tasda</td> -->
									<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu3('<?php echo $cur_menu_code3 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
								</tr>
								<?php
										if ($menu_code3 != $cur_menu_code3) {
											$menu_code3 = $cur_menu_code3;
										}
										$hidden_menu_class = 'hidden';
										$j++;
									}
									echo '</table>';
								?>
							</td>
							</tr>
							</tbody>
						</table>
						<label>선택한 카테고리 : <span id="ca_name"><?php echo $ca_name ?></span></label>

						<script>
							var ca_use = new Array();
							var ca_stock_qty = new Array();
							//var ca_explan_html = new Array();
							var ca_sell_email = new Array();
							var ca_opt1_subject = new Array();
							var ca_opt2_subject = new Array();
							var ca_opt3_subject = new Array();
							var ca_opt4_subject = new Array();
							var ca_opt5_subject = new Array();
							var ca_opt6_subject = new Array();
							<?php echo "\n$script"; ?>
						</script>

						<input type="hidden" name="ca_id" id="ca_id" value="<?php echo $it['ca_id']; ?>">
						<input type="hidden" name="ca_id2" value="<?php echo $it['ca_id2']; ?>">
						<input type="hidden" name="ca_id3" value="<?php echo $it['ca_id3']; ?>">
						</td>
						</tr>



						<!-- 서브 카테고르 start -->
						<tr>
							<th scope="row"><label for="ca_id">서브 카테고리 선택</label></th>
							<td>
								<table class="table table-bordered" style="height: 100%">
									<thead>
										<tr>
											<th scope="col" class="text-center active" width="25%">1 Depth</th>
											<th scope="col" class="text-center active" width="25%">2 Depth</th>
											<th scope="col" class="text-center active" width="25%">3 Depth</th>
											<th scope="col" class="text-center active" width="25%">4 Depth</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td scope="col" class="text-center" style="vertical-align: top;">
												<table class="table table-bordered catblGroup1" style="margin-bottom:0;">
													<?php
													$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 2 order by ca_order ";
													$result = sql_query($sql);
													$sub_ca_name = "";
													$sub_depth2 = "";
													$sub_depth3 = "";
													$sub_depth4 = "";
													$selected_menu_class = '';
													for ($i = 0; $row = sql_fetch_array($result); $i++) {
														if ($i == 0) {
															$selected_menu_class = 'bg-primary';
															$sub_ca_name .= $row['ca_name'];
														} else $selected_menu_class = '';
														/*<tr><td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu('<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td></tr>*/
													?>
														<tr>
															<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu('<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
														</tr>
													<?php } ?>
												</table>
											</td>
											<td scope="col" class="text-center" style="vertical-align: top;">
												<?php
												$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 4 order by substr(ca_id,1,2), ca_order ";
												$result = sql_query($sql);
												$selected_menu_class = '';
												$hidden_menu_class = '';
												$menu_code1 = '';
												$j = 0;
												for ($i = 0; $row = sql_fetch_array($result); $i++) {
													$cur_menu_code1 = substr($row['ca_id'], 0, 2);
													if ($menu_code1 != $cur_menu_code1) {
														$j = 0;
														if ($i != 0) echo '</table>';
														echo '<table class="table table-bordered catblGroup2 ' . $hidden_menu_class . '" style="margin-bottom:0;" id="catable' . $cur_menu_code1 . '">';
													}
													$selected_menu_class = '';
													if ($it_item_type == '0' && $row['ca_id'] == '1010') {
														$selected_menu_class = 'bg-primary';
														$ca_name .= ' > ' . $row['ca_name'];
														$depth2 = $row['ca_id'];
													} else if ($it_item_type == '1' && $row['ca_id'] == '1020') {
														$selected_menu_class = 'bg-primary';
														$ca_name .= ' > ' . $row['ca_name'];
														$depth2 = $row['ca_id'];
													}
													?>
												<tr>
													<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu1('<?php echo $cur_menu_code1 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
												</tr>
											<?php
															if ($menu_code1 != $cur_menu_code1) {
																$menu_code1 = $cur_menu_code1;
															}
															$hidden_menu_class = 'hidden';
															$j++;
														}
														echo '</table>';
											?>

									</td>
									<td scope="col" class="text-center" style="vertical-align: top;">

										<?php
										$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 6 order by substr(ca_id,1,4), ca_order ";
										$result = sql_query($sql);

										$selected_menu_class = '';
										$hidden_menu_class = 'hidden';
										$menu_code2 = '';
										$j = 0;

										for ($i = 0; $row = sql_fetch_array($result); $i++) {
											$cur_menu_code2 = substr($row['ca_id'], 0, 4);
											$selected_menu_class = '';

											if ($depth2 == $cur_menu_code2) {
												$hidden_menu_class = '';
											}

											if ($menu_code2 != $cur_menu_code2) {
												$j = 0;

												if ($i != 0) echo '</table>';
												echo '<table class="table table-bordered catblGroup3 ' . $hidden_menu_class . '" style="margin-bottom:0;" id="catable' . $cur_menu_code2 . '">';
											}

											if ($depth2 == $cur_menu_code2 && $j == 0) {
												$selected_menu_class = 'bg-primary';
												$ca_name .= ' > ' . $row['ca_name'];
												$depth3 = $row['ca_id'];
											}
										?>
								<tr>
									<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu2('<?php echo $cur_menu_code2 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
								</tr>
							<?php
											if ($menu_code2 != $cur_menu_code2) {
												$menu_code2 = $cur_menu_code2;
											}
											$hidden_menu_class = 'hidden';
											$j++;
										}
										echo '</table>';
							?>

							</td>
							<td scope="col" class="text-center" style="vertical-align: top;">
								<!-- ekffk ekffk durt -->
								<?php
									$sql = " select * from {$g5['g5_shop_category_table']} where length(ca_id) = 8 order by substr(ca_id,1,8), ca_order ";
									$result = sql_query($sql);

									$selected_menu_class = '';
									$hidden_menu_class = 'hidden';
									$menu_code3 = '';
									$j = 0;
									for ($i = 0; $row = sql_fetch_array($result); $i++) { 
										$cur_menu_code3 = substr($row['ca_id'], 0, 6);
										$selected_menu_class = '';
										// echo '$depth3 : '.$depth3.'<br>';
										// echo '$cur_menu_code3 : '.$cur_menu_code3.'<br>';
										// echo '$menu_code3 : '.$menu_code3.'<br>';
										if ($depth3 == $cur_menu_code3) {
											$hidden_menu_class = '';
										}

										if ($menu_code3 != $cur_menu_code3) {
											$j = 0;


											if ($i != 0) echo '</table>';
											echo '<table class="table table-bordered catblGroup4 ' . $hidden_menu_class . '" style="margin-bottom:0;" id="catable' . $cur_menu_code3 . '">';
										}

										if ($depth3 == $cur_menu_code3 && $j == 0) {
											$selected_menu_class = 'bg-primary';
											$ca_name .= ' > ' . $row['ca_name'];
											$depth4 = $row['ca_id'];

											// echo '$depth4 : '.$depth4.'<br>';
										}
									?>
								<tr>
								
									<!-- <td class="<?php echo $selected_menu_class ?>">tasda</td> -->
									<td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['ca_id'] ?>" onclick="viewMenu3('<?php echo $cur_menu_code3 ?>','<?php echo $row['ca_id'] ?>');" style="cursor: pointer;" id="catd<?php echo $row['ca_id'] ?>"><?php echo $row['ca_name'] ?></td>
								</tr>
								<?php
										if ($menu_code3 != $cur_menu_code3) {
											$menu_code3 = $cur_menu_code3;
										}
										$hidden_menu_class = 'hidden';
										$j++;
									}
									echo '</table>';
								?>
							</td>
							</tr>
							</tbody>
						</table>
						<label>선택한 카테고리 : <span id="ca_name"><?php echo $ca_name ?></span></label>

						<script>
							var ca_use = new Array();
							var ca_stock_qty = new Array();
							//var ca_explan_html = new Array();
							var ca_sell_email = new Array();
							var ca_opt1_subject = new Array();
							var ca_opt2_subject = new Array();
							var ca_opt3_subject = new Array();
							var ca_opt4_subject = new Array();
							var ca_opt5_subject = new Array();
							var ca_opt6_subject = new Array();
							<?php echo "\n$script"; ?>
						</script>

						<input type="hidden" name="ca_id" id="ca_id" value="<?php echo $it['ca_id']; ?>">
						<input type="hidden" name="ca_id2" value="<?php echo $it['ca_id2']; ?>">
						<input type="hidden" name="ca_id3" value="<?php echo $it['ca_id3']; ?>">
						</td>
						</tr>
						<!-- 서브 카테고르 end -->

						</tbody>
						</table>
					</div>
				</div>
				<script>
					function viewMenu(depth1) {
						$("#ca_id").val(depth1);

						$("td.bg-primary").removeClass('bg-primary');
						$("table.catblGroup2").removeClass('hidden').addClass('hidden');
						$("table.catblGroup3").removeClass('hidden').addClass('hidden');

						$("#catd" + depth1).addClass('bg-primary');
						$("#catable" + depth1).removeClass('hidden');

						$("#ca_name").text($("table.catblGroup1").find("td.bg-primary").text());

						var firstTd = $("#catable" + depth1).find("td:first");

						if (firstTd.attr("data") != undefined) {
							firstTd.addClass('bg-primary');
							viewMenu1(depth1, firstTd.attr("data"));
							//alert(depth1);
						}
					}

					function viewMenu1(depth1, depth2) {
						$("#ca_id").val(depth2);

						$("#catable" + depth1).find("td.bg-primary").removeClass('bg-primary');
						$("table.catblGroup3").removeClass('hidden').addClass('hidden');

						$("#catd" + depth2).addClass('bg-primary');
						$("#catable" + depth2).removeClass('hidden');
						//alert(depth2);

						$("#ca_name").text($("table.catblGroup1").find("td.bg-primary").text() + " > " + $("table.catblGroup2").find("td.bg-primary").text());

						var firstTd = $("#catable" + depth2).find("td:first");

						if (firstTd.attr("data") != undefined) {
							firstTd.addClass('bg-primary');
							viewMenu2(depth2, firstTd.attr("data"));
							//alert(depth1);
						}
					}

					function viewMenu2(depth2, depth3) {
						$("#ca_id").val(depth3);

						$("#catable" + depth2).find("td.bg-primary").removeClass('bg-primary');
						$("table.catblGroup4").removeClass('hidden').addClass('hidden');

						$("#catd" + depth3).addClass('bg-primary');
						$("#catable" + depth3).removeClass('hidden');
						$("#ca_name").text($("table.catblGroup1").find("td.bg-primary").text() + " > " + $("table.catblGroup2").find("td.bg-primary").text() + " > " + $("table.catblGroup3").find("td.bg-primary").text());

						var firstTd = $("#catable" + depth3).find("td:first");

						if (firstTd.attr("data") != undefined) {
							firstTd.addClass('bg-primary');
							viewMenu3(depth3, firstTd.attr("data"));
							//alert(depth1);
						}
						// 훔 ????  -----------------------------------------------------------
						// $.post(
						// 	"./codedupcheck.php", {
						// 		max_ca_id: depth3
						// 	},
						// 	function(data) {
						// 		//alert(data.name);
						// 		if (data.name) {
						// 			var it_id = "0" + depth3.substring(0, 2) + "0" + depth3.substring(2, 4) + "0" + depth3.substring(4, 6) + data.name;
						// 			$("#it_id").val(it_id);
						// 		} else {
						// 			var it_id = "0" + depth3.substring(0, 2) + "0" + depth3.substring(2, 4) + "0" + depth3.substring(4, 6) + "000001";
						// 			$("#it_id").val(it_id);
						// 		}
						// 	}, "json"
						// );
						// -----------------------------------------------------------------
					}
					function viewMenu3(depth3, depth4) { 
						$("#ca_id").val(depth4);

						$("#catable" + depth3).find("td.bg-primary").removeClass('bg-primary');

						$("#catd" + depth4).addClass('bg-primary');

						$("#ca_name").text($("table.catblGroup1").find("td.bg-primary").text() + " > " + $("table.catblGroup2").find("td.bg-primary").text() + " > " + $("table.catblGroup3").find("td.bg-primary").text() + " > " + $("table.catblGroup4").find("td.bg-primary").text());
					
						// 
						$.post(
							"./codedupcheck.php", {
							max_ca_id: depth4
							},
							function(data) {
								//alert(data.name);
								if (data.name) {
									var it_id = "0" + depth4.substring(0, 2) + "0" + depth4.substring(2, 4) + "0" + depth4.substring(4, 6) + depth4.substring(6, 8) + data.name;
									$("#it_id").val(it_id);
								} else {
									var it_id = "0" + depth4.substring(0, 2) + "0" + depth4.substring(2, 4) + "0" + depth4.substring(4, 6) + depth4.substring(6, 8) + "000001";
									$("#it_id").val(it_id);
								}
							}, "json"
						);
					}

					<?php
					if (isset($ca_id) && $ca_id && $w = 'u') {
						echo 'viewMenu("' . substr($ca_id, 0, 2) . '");';
						echo 'viewMenu1("' . substr($ca_id, 0, 2) . '","' . substr($ca_id, 0, 4) . '");';
						echo 'viewMenu2("' . substr($ca_id, 0, 4) . '","' . substr($ca_id, 0, 6) . '");';
						
						echo 'viewMenu3("' . substr($ca_id, 0, 6) . '","' . substr($ca_id, 0, 8) . '");';
					} else {
						echo 'viewMenu2("' . $depth2 . '","' . $depth3 . '");';
					}

					?>
				</script>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 기본 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<tbody>
								<tr>
									<th scope="row"><label for="it_skin">제품코드</label></th>
									<td>
										<?php if ($w == '') { // 추가 
										?>
											<!-- 최근에 입력한 코드(자동 생성시)가 목록의 상단에 출력되게 하려면 아래의 코드로 대체하십시오. -->
											<!-- <input type=text class=required name=it_id value="<?php echo 10000000000 - time() ?>" size=12 maxlength=10 required> <a href='javascript:;' onclick="codedupcheck(document.all.it_id.value)"><img src='./img/btn_code.gif' border=0 align=absmiddle></a> -->
											<?php echo help("제품코드는  자동으로 생성됩니다. <b>직접 제품코드를 입력할 수도 있습니다.</b>\n제품코드는 영문자, 숫자만 입력 가능합니다."); ?>
											<input type="text" name="it_id" value="<?php echo time(); ?>" id="it_id" required class="frm_input required" size="20" maxlength="20">
											<?php if ($default['de_code_dup_use']) { ?><button type="button" class="btn_frmline" onclick="codedupcheck(document.all.it_id.value)">중복검사</a><?php } ?>
											<?php } else { ?>
												<input type="hidden" name="it_id" value="<?php echo $it['it_id']; ?>">
												<span class="frm_ca_id"><?php echo $it['it_id']; ?></span>
												<a href="<?php echo G5_SHOP_URL; ?>/item.php?it_id=<?php echo $it_id; ?>" class="btn_frmline">제품확인</a>
												<!-- <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemuselist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">사용후기</a>
						<a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemqalist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">제품문의</a> -->
											<?php } ?>
									</td>
									<th scope="row"><label for="it_skin">분류</label></th>
									<td class="td_grpset">
										<?php if ($it['it_id']) {
											echo '<input type="hidden" name="it_item_type" value="' . $it['it_item_type'] . '">';
											echo '<input type="hidden" name="it_item_rental_month" value="' . $it['it_item_rental_month'] . '">';

											if ($it['it_item_type'] == '0') {
												echo '제품';
											} else if ($it['it_item_type'] == '1') {
												echo '리스&nbsp;&nbsp;';
												echo '<label id="lbl_it_name"> <input type="text" name="it_item_rental_month" id="it_item_rental_month" value="' . $it['it_item_rental_month'] . '" class="frm_input" size="2" >개월</label>';
											}
										} else {
										?>
											<div class="radio">
												<?php if ($it_item_type != '1') { ?>
													<label><input type="radio" value="0" id="it_item_type0" name="it_item_type" <?php echo ($it_item_type != '1') ? 'checked' : ''; ?>> 제품 </label>&nbsp;&nbsp;&nbsp;
												<?php } else { ?>
													<label><input type="radio" value="1" id="it_item_type1" name="it_item_type" <?php echo ($it_item_type == '1') ? 'checked' : ''; ?>> 리스</label>&nbsp;&nbsp;&nbsp;
												<?php } ?>
											</div>

											<label <?php echo ($it_item_type == '1') ? '' : 'hidden'; ?> id="lbl_it_name">
												<input type="text" name="it_item_rental_month" id="it_item_rental_month" value="<?php echo $it['it_item_rental_month'] ?>" class="frm_input" size="2">
												개월</label>
											<script>
												$(function() {
													$("#it_item_type0").click(function() {
														$("#lbl_it_name").prop('hidden', true);
														$("#tr_its_rental").prop('hidden', true);
													});
													$("#it_item_type1").click(function() {
														$("#lbl_it_name").prop('hidden', false);
														$("#tr_its_rental").prop('hidden', false);

													});
												});
											</script>

										<?php } ?>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_name">제품명</label></th>
									<td colspan="3">
										<input type="text" name="it_name" value="<?php echo get_text(cut_str($it['it_name'], 80, "")); ?>" id="it_name" required maxlength="80" class="frm_input required col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_name').text($(this).val().length);" onblur="$('#len_it_name').text($(this).val().length);">
										<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_name"><?php echo strlen(get_text($it['it_name'])); ?></span> / 80</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_brand">브랜드</label></th>
									<td colspan="3">
										<input type="text" name="it_brand" value="<?php echo get_text(cut_str($it['it_brand'], 80, "")); ?>" id="it_brand" required maxlength="80" class="frm_input required col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_brand').text($(this).val().length);" onblur="$('#len_it_brand').text($(this).val().length);">
										<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_brand"><?php echo strlen(get_text($it['it_brand'])); ?></span> / 80</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">한줄 제품 설명</label></th>
									<td colspan="3">
										<input type="text" name="it_basic" value="<?php echo get_text(html_purifier($it['it_basic'])); ?>" id="it_basic" maxlength="200" class="frm_input col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_basic').text($(this).val().length);" onblur="$('#len_it_basic').text($(this).val().length);">
										<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_basic"><?php echo strlen(get_text($it['it_basic'])); ?></span> / 200</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">검색어 설정</label></th>
									<td colspan="3">
										<input type="text" name="it_search_word" value="<?php echo get_text($it['it_search_word']); ?>" id="it_search_word" maxlength="200" class="frm_input col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_search_word').text($(this).val().length);" onblur="$('#len_it_search_word').text($(this).val().length);">
										<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_search_word"><?php echo strlen(get_text($it['it_search_word'])); ?></span> / 200</label>
										<div class="clearfix"></div>
										<?php echo help("- 검색어는 \",\" (콤마)로 구분해주세요 "); ?>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_order">출력순서</label></th>
									<td colspan="3">
										<?php echo help("숫자가 작을 수록 상위에 출력됩니다. 음수 입력도 가능하며 입력 가능 범위는 -2147483648 부터 2147483647 까지입니다.\n<b>입력하지 않으면 자동으로 출력됩니다.</b>"); ?>
										<input type="text" name="it_order" value="<?php echo $it['it_order']; ?>" id="it_order" class="frm_input" size="12">
									</td>
								</tr>

							</tbody>
						</table>

					</div>
				</div>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 이미지 정보 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<tbody>
								<tr>
									<th scope="row"><label for="it_basic">대표이미지</label></th>
									<td>
										<div style="width:180px;position:relative;float:left;">
											<?php
											$i = 1;

											$it_img = G5_DATA_PATH . '/item/' . $it['it_img' . $i];
											if (is_file($it_img) && $it['it_img' . $i]) {
												$size = @getimagesize($it_img);
												$thumb = get_it_thumbnail($it['it_img' . $i], 150, 150, 'imgimgFile' . $i);
											?>
												<span class="sit_wimg_limg<?php echo $i; ?>"><?php echo $thumb; ?></span>
											<?php
											} else {
												echo '<img src="' . G5_ADMIN_URL . '/img/theme_img.jpg" class="img-thumbnail" id="imgimgFile' . $i . '" style="width: 150px; height: 150px;">';
											}
											?>
										</div>

										<div class="col-md-6 col-lg-6 col-sm-6">
											<div class="input-group" id="imgInputGroup<?php echo $i ?>">
												<span class="">
													<div class="btn btn-info">
														<span><?php if (is_file($it_img) && $it['it_img' . $i]) echo '이미지 수정';
																else echo '이미지 등록'; ?></span>
														<input type="file" id="imgFile<?php echo $i ?>" name="it_img<?php echo $i ?>" class="hiddenFile" delBtnID="btnDelimgFile<?php echo $i ?>" imgID="imgimgFile<?php echo $i ?>" style="width:100px" accept=".jpg, .png">
													</div>
												</span>
												<input type="hidden" id="orgimgFile<?php echo $i ?>" name="orgit_img<?php echo $i ?>" value="<?php echo $it['it_img' . $i]; ?>">
											</div>

											<div class="col-md-12 col-lg-12 col-sm-12">
												<span class="red">* 업로드 이미지 사이즈 (480px * 480px) <br />
													* 최대 15MB / 확장자 jpg, png만 가능</span>
											</div>
										</div>


									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">추가이미지(<span id="">0</span>/4)</label></th>
									<td>
										<table>
											<tr>
												<?php for ($i = 2; $i <= 5; $i++) { ?>
													<td style="width: 25%">
														<?php

														$it_img = G5_DATA_PATH . '/item/' . $it['it_img' . $i];
														if (is_file($it_img) && $it['it_img' . $i]) {
															$size = @getimagesize($it_img);
															$thumb = get_it_thumbnail($it['it_img' . $i], 150, 150, 'imgimgFile' . $i);
														?>
															<span class="sit_wimg_limg<?php echo $i; ?>"><?php echo $thumb; ?></span>
														<?php
														} else {
															echo '<img src="' . G5_ADMIN_URL . '/img/theme_img.jpg" class="img-thumbnail" id="imgimgFile' . $i . '" style="width: 150px; height: 150px;">';
														}
														?>
													</td>
												<?php } ?>
											</tr>

											<tr>
												<?php for ($i = 2; $i <= 5; $i++) { ?>
													<td>
														<div class="input-group" id="imgInputGroup<?php echo $i ?>">
															<span class="">
																<div class="btn btn-info">
																	<span><?php if (is_file($it_img) && $it['it_img' . $i]) echo '이미지 수정';
																			else echo '이미지 등록'; ?></span>
																	<input type="file" id="imgFile<?php echo $i ?>" name="it_img<?php echo $i ?>" class="hiddenFile" delBtnID="btnDelimgFile<?php echo $i ?>" imgID="imgimgFile<?php echo $i ?>" style="width:100px" accept=".jpg, .png">
																</div>
															</span>
															<button class="btn btn-danger <?php if (!$it['it_img' . $i]) echo 'hidden'; ?>" type="button" id="btnDelimgFile<?php echo $i ?>" fileBtnID="imgFile<?php echo $i ?>">삭제</button>
															<input type="hidden" id="orgimgFile<?php echo $i ?>" name="orgit_img<?php echo $i ?>" value="<?php echo $it['it_img' . $i]; ?>">
														</div>
													</td>
												<?php } ?>
											</tr>
										</table>

										<div class="col-md-12 col-lg-12 col-sm-12">
											<span class="red">* 업로드 이미지 사이즈 (480px * 480px) <br />
												* 최대 15MB / 확장자 jpg, png만 가능</span>
										</div>

									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="x_title">
					<div style="float: left;">
						<h4><span class="fa fa-check-square"></span> 제품상세 정보 설정 - SAP삼진연동정보/케어서비스/옵션정보<small></small></h4>
					</div>
					<div style="float: right;">
						<button class="btn btn02" type="button" id="btnItitemAdd">제품 생성</button>
					</div>
					<div class="clearfix"></div>
				</div>

				<div class="x_content" id="lt_shop_item_sub">
					<?php include_once(G5_ADMIN_PATH . '/shop_admin/itemformsub.php'); ?>
				</div>
				<script>
					$(function() {
						$("#btnItitemAdd").click(function() {
							var s = 0;
							$("input[name='itscnt[]']").each(function() {
								s = $.trim($(this).val());
							});

							s = parseInt(s) + 1;
							//alert(s);

							$.post(
								"<?php echo G5_ADMIN_URL; ?>/shop_admin/itemformsub.php", {
									s: s,
									it_item_type: '<?php echo $it_item_type ?>'
								},
								function(data) {

									$("#lt_shop_item_sub").append(data);
								}
							);
						});
					});
				</script>


				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 혜택 / 기능 / 표시 정보 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<tbody>
								<tr>
									<th scope="row"><label for="it_basic">총합계 :<br />최종 판매가</label></th>
									<td>
										<div style="float: left"><input type="text" name="it_price" value="<?php echo $it['it_price']; ?>" id="it_price" class="frm_input text-right readonly" readonly="readonly"></div>
										<div style="float: left;padding-left: 20px; padding-top: 4px;"><?php echo help("※ 1개등록시 최종판매가 노출, 세트구성시 제품별 최종판매가의 합계금액이 노출됩니다. "); ?></div>
									</td>
								</tr>
								<tr <?php if ($it_item_type == '0') echo "hidden"; ?>>
									<th scope="row"><label for="it_basic">총합계 :<br />최종월리스료</label></th>
									<td>
										<div style="float: left"><input type="text" name="it_rental_price" value="<?php echo $it['it_rental_price']; ?>" id="it_rental_price" class="frm_input text-right readonly" readonly="readonly"></div>
										<div style="float: left;padding-left: 20px; padding-top: 4px;">총 납부금: <strong id="it_final_rental_price">0</strong>원</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">총할인금액</label></th>
									<td>
										<div style="float: left"><input type="text" name="it_discount_price" value="<?php echo $it['it_discount_price']; ?>" id="it_discount_price" class="frm_input text-right readonly" readonly="readonly"></div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">판매기간</label></th>
									<td>
										<div style="float: left">
											<input type='text' class="frm_input" id="it_period" name="it_period" <?php echo ($it['it_period'] == '') ? 'disabled' : ''; ?> value="<?php echo $it['it_period'] ?>" />
										</div>
										<div style="float: left;padding-left: 20px;padding-top: 4px;">
											<label><input type="checkbox" name="it_period_chk" value="1" id="it_period_chk" <?php echo ($it['it_period'] == '') ? 'checked' : ''; ?>>미설정</label>&nbsp;&nbsp;
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">구매회원구분</label></th>
									<td>
										<label><input type="radio" value="0" id="it_level_sell0" name="it_level_sell" <?php echo ($it['it_level_sell'] == '0') ? 'checked' : ''; ?>> 전체</label>&nbsp;&nbsp;&nbsp;
										<label><input type="radio" value="2" id="it_level_sell2" name="it_level_sell" <?php echo ($it['it_level_sell'] == '2') ? 'checked' : ''; ?>> 회원</label>&nbsp;&nbsp;&nbsp;
										<label><input type="radio" value="1" id="it_level_sell1" name="it_level_sell" <?php echo ($it['it_level_sell'] == '1') ? 'checked' : ''; ?>> 비회원</label>&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
								<tr <?php if ($it_item_type == '1') echo "hidden"; ?>>
									<th scope="row"><label for="it_basic">주문수량제한</label></th>
									<td>
										<div style="float: left; padding-top: 4px;">
											<label><input type="radio" value="0" id="it_buy_max_qty0" name="rdo_it_buy_max_qty" <?php echo ($it['it_buy_max_qty'] == '0') ? 'checked' : ''; ?>> 제한없음</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" value="1" id="it_buy_max_qty1" name="rdo_it_buy_max_qty" <?php echo ($it['it_buy_max_qty'] != '0') ? 'checked' : ''; ?>> 갯수설정</label>&nbsp;&nbsp;&nbsp;
										</div>
										<div style="float: left">
											<input type="text" name="it_buy_max_qty" value="<?php echo $it['it_buy_max_qty']; ?>" id="it_buy_max_qty" class="frm_input">
											<label>개 ( 설정값 이하 구매 가능 )</label>
										</div>
									</td>
								</tr>
								<tr <?php if ($it_item_type == '1') echo "hidden"; ?>>
									<th scope="row"><label for="it_basic">구매시 적립금 설정</label></th>
									<td>
										<div style="float: left; padding-top: 4px;">
											<label><input type="radio" value="9" id="it_buy_max_qty9" name="it_point_type" <?php echo ($it['it_point_type'] == '9') ? 'checked' : ''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" value="3" id="it_buy_max_qty3" name="it_point_type" <?php echo ($it['it_point_type'] == '3') ? 'checked' : ''; ?>> 기본사용 (구매액의 <?php echo $default['de_point_percent'] ?>%)</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" value="2" id="it_buy_max_qty2" name="it_point_type" <?php echo ($it['it_point_type'] == '2') ? 'checked' : ''; ?>> 적립율(%)</label>&nbsp;&nbsp;&nbsp;
										</div>
										<div style="float: left"><input type="text" name="it_point2" value="<?php echo ($it['it_point_type'] == '2') ? $it['it_point'] : '0'; ?>" id="it_point2" class="frm_input">&nbsp;&nbsp;&nbsp;</div>

										<div style="float: left; padding-top: 4px;">
											<label><input type="radio" value="0" id="it_buy_max_qty0" name="it_point_type" <?php echo ($it['it_point_type'] == '0') ? 'checked' : ''; ?>> 적립금액 (원)</label>&nbsp;&nbsp;&nbsp;
										</div>
										<div style="float: left"><input type="text" name="it_point0" value="<?php echo ($it['it_point_type'] == '0') ? $it['it_point'] : '0'; ?>" id="it_point0" class="frm_input"></div>
									</td>
								</tr>
								<tr hidden>
									<th scope="row"><label for="it_basic">적립금 전용결제</label></th>
									<td>
										<label><input type="radio" value="0" id="it_point_only0" name="it_point_only" <?php echo ($it['it_point_only'] == '0') ? 'checked' : ''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
										<label><input type="radio" value="1" id="it_point_only1" name="it_point_only" <?php echo ($it['it_point_only'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
								<tr hidden>
									<th scope="row"><label for="it_basic">쿠폰 사용</label></th>
									<td>
										<label><input type="radio" value="0" id="it_nocoupon0" name="it_nocoupon" <?php echo ($it['it_nocoupon'] == '0') ? 'checked' : ''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
										<label><input type="radio" value="1" id="it_nocoupon1" name="it_nocoupon" <?php echo ($it['it_nocoupon'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
								<tr hidden>
									<th scope="row"><label for="it_basic">적립금 사용</label></th>
									<td>
										<label><input type="radio" value="0" id="it_point_use0" name="it_point_use" <?php echo ($it['it_point_use'] == '0') ? 'checked' : ''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
										<label><input type="radio" value="1" id="it_point_use1" name="it_point_use" <?php echo ($it['it_point_use'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="it_basic">후기 쓰기</label></th>
									<td>
										<label><input type="radio" value="0" id="it_use_use0" name="it_use_use" <?php echo ($it['it_use_use'] == '0') ? 'checked' : ''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
										<label><input type="radio" value="1" id="it_use_use1" name="it_use_use" <?php echo ($it['it_use_use'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
								<!-- <tr>
					<th scope="row"><label for="it_basic">제품 문의</label></th>
					<td>
						<label><input type="radio" value="0" id="it_review_use0" name="it_review_use" <?php echo ($it['it_review_use'] == '0') ? 'checked' : ''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
						<label><input type="radio" value="1" id="it_review_use1" name="it_review_use" <?php echo ($it['it_review_use'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
					</td>
				</tr> -->
								<tr>
									<th scope="row" rowspan="2"><label for="it_basic">리스트 표시 설정</label></th>
									<td>
										<div style="float: left; padding-top: 4px;">
											<label><input type="radio" value="0" id="it_view_list_items0" name="rdo_it_view_list_items" <?php echo ($it['it_view_list_items'] == '') ? 'checked' : ''; ?>> 전체설정</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" value="1" id="it_view_list_items1" name="rdo_it_view_list_items" <?php echo ($it['it_view_list_items'] != '') ? 'checked' : ''; ?>> 선택설정</label>&nbsp;&nbsp;&nbsp;
										</div>
										<div style="float: left;padding-left: 20px; padding-top: 4px;"><?php echo help("※ 선택 설정시 표시 할 항목설정이 가능합니다."); ?></div>
									</td>
								</tr>
								<tr>
									<td>
										<label><input type="checkbox" value="상품명" checked="checked" disabled> 제품명</label>&nbsp;&nbsp;
										<label><input type="checkbox" value="최종 판매가" checked="checked" disabled> 최종 판매가</label>&nbsp;&nbsp;
										<?php
										//$it_view_list_items_arr = explode(",", "할인블릿,이벤트블릿,인기블릿,신상품,쿠폰,좋아요,공유하기,리뷰수,상품명,한줄설명,할인전금액,할인가/할인율,판매가,최종 판매가");
										$it_view_list_items_arr = explode(",", "신상품,할인블릿,이벤트블릿,단독블릿,인기블릿,한줄설명,좋아요");

										for ($li = 0; $li < count($it_view_list_items_arr); $li++) {
										?>

											<label><input type="checkbox" name="it_view_list_items[]" value="<?php echo $it_view_list_items_arr[$li] ?>" id="it_view_list_items<?php echo $li ?>" <?php echo option_array_checked($it_view_list_items_arr[$li], $it['it_view_list_items']); ?> <?php echo ($it['it_view_list_items'] != '') ? '' : 'disabled'; ?>> <?php echo $it_view_list_items_arr[$li] ?></label>&nbsp;&nbsp;

										<?php } ?>
										<input type="hidden" name="it_view_list_items[]" value="상품명">
										<input type="hidden" name="it_view_list_items[]" value="최종 판매가">
									</td>
								</tr>

								<tr>
									<th scope="row" rowspan="2"><label for="it_basic">상세 표시 설정</label></th>
									<td>
										<div style="float: left; padding-top: 4px;">
											<label><input type="radio" value="0" id="it_view_detail_items0" name="rdo_it_view_detail_items" <?php echo ($it['it_view_detail_items'] == '') ? 'checked' : ''; ?>> 전체설정</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" value="1" id="it_view_detail_items1" name="rdo_it_view_detail_items" <?php echo ($it['it_view_detail_items'] != '') ? 'checked' : ''; ?>> 선택설정</label>&nbsp;&nbsp;&nbsp;
										</div>
										<div style="float: left;padding-left: 20px; padding-top: 4px;"><?php echo help("※ 선택 설정시 표시 할 항목설정이 가능합니다."); ?></div>
									</td>
								</tr>
								<tr>
									<td>
										<label><input type="checkbox" value="상품명" checked="checked" disabled> 제품명</label>&nbsp;&nbsp;
										<label><input type="checkbox" value="최종 판매가" checked="checked" disabled> 최종 판매가</label>&nbsp;&nbsp;

										<?php
										// $it_view_list_items_arr = explode(",", "할인블릿,이벤트블릿,인기블릿,좋아요,한줄설명");
										$it_view_list_items_arr = explode(",", "할인전금액,신상품,할인블릿,이벤트블릿,단독블릿,인기블릿,한줄설명,좋아요,공유하기");

										for ($li = 0; $li < count($it_view_list_items_arr); $li++) {
											if ($li == 3) echo "<br/>";
										?>

											<label><input type="checkbox" name="it_view_detail_items[]" value="<?php echo $it_view_list_items_arr[$li] ?>" id="it_view_detail_items<?php echo $li ?>" <?php echo option_array_checked($it_view_list_items_arr[$li], $it['it_view_detail_items']); ?> <?php echo ($it['it_view_detail_items'] != '') ? '' : 'disabled'; ?>> <?php echo $it_view_list_items_arr[$li] ?></label>&nbsp;&nbsp;

										<?php } ?>
										<input type="hidden" name="it_view_detail_items[]" value="상품명">
										<input type="hidden" name="it_view_detail_items[]" value="최종 판매가">
									</td>
								</tr>

							</tbody>
						</table>
						<script>
							$(function() {
								$("input[name='rdo_it_view_list_items']").click(function() {

									$("input[name='it_view_list_items[]']").prop("checked", false);

									var chk = ($(this).val() == "0");
									$("input[name='it_view_list_items[]']").prop("disabled", chk);
								});

								$("input[name='rdo_it_view_detail_items']").click(function() {

									$("input[name='it_view_detail_items[]']").prop("checked", false);

									var chk = ($(this).val() == "0");
									$("input[name='it_view_detail_items[]']").prop("disabled", chk);

								});
							});
						</script>

					</div>
				</div>


				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 배송 정보 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col class="grid_4">
								<col>
								<col class="grid_3">
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="it_sc_type">배송정보</label></th>
									<td>
										<div class="radio">
											<?php
											$it_sc_type_readonly = "";

											if ($default['de_individual_costs_use'] == '0') {
												$it_sc_type_readonly = "disabled";
											?>
												<label><input type="radio" value="0" id="it_sc_type0" name="it_sc_type" <?php echo ($it['it_sc_type'] == '0') ? 'checked' : ''; ?>> 기본설정 사용</label>&nbsp;&nbsp;&nbsp;
											<?php } else {
												if ($it['it_sc_type'] == '0') $it_sc_type_readonly = "disabled";
											?>
												<label><input type="radio" value="0" id="it_sc_type0" name="it_sc_type" <?php echo ($it['it_sc_type'] == '0') ? 'checked' : ''; ?>> 기본설정 사용</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" value="2" id="it_sc_type2" name="it_sc_type" <?php echo ($it['it_sc_type'] == '2') ? 'checked' : ''; ?>> 선택설정</label>&nbsp;&nbsp;&nbsp;
											<?php } ?>
										</div>
									</td>
								</tr>

								<tr>
									<th scope="row"><label>배송방법</label></th>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<select name="it_send_type" id="it_send_type" <?php echo $it_sc_type_readonly ?>>
												<option value="택배" <?php echo get_selected($it['it_send_type'], '택배'); ?>>택배</option>
												<option value="빠른등기" <?php echo get_selected($it['it_send_type'], '빠른등기'); ?>>빠른등기</option>
												<option value="기타" <?php echo get_selected($it['it_send_type'], '기타'); ?>>기타</option>
											</select>
										</div>
									</td>
								</tr>

								<tr>
									<th scope="row"><label>배송기간</label></th>
									<td>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<input type="text" name="it_send_term_start" value="<?php echo $it['it_send_term_start']; ?>" id="it_send_term_start" class="form-control" <?php echo $it_sc_type_readonly ?>>
										</div>
										<label class="col-md-1 col-sm-1 col-xs-1" style="padding-top:8px;">일 ~</label>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<input type="text" name="it_send_term_end" value="<?php echo $it['it_send_term_end']; ?>" id="it_send_term_end" class="form-control" <?php echo $it_sc_type_readonly ?>>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-9" style="padding-top:8px;">
											<label class="control-label">일 정도 소요됩니다.</label>
										</div>
									</td>
								</tr>

								<tr id="dvCostCase1">
									<th scope="row"><label>기본 배송비 설정</label></th>
									<td>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<input type="text" name="it_sc_minimum" value="<?php echo $it['it_sc_minimum']; ?>" id="it_sc_minimum" class="form-control" <?php echo $it_sc_type_readonly ?>>
										</div>
										<label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원 미만일 때 배송비</label>
										<div class="col-md-1 col-sm-1 col-xs-1">
											<input type="text" name="it_sc_price" value="<?php echo $it['it_sc_price']; ?>" id="it_sc_price" class="form-control" <?php echo $it_sc_type_readonly ?>>
										</div>
										<div class="col-md-7 col-sm-7 col-xs-7" style="padding-top:8px;">
											<label class="control-label">원을 부과합니다.</label>
										</div>
									</td>
								</tr>

								<tr>
									<th scope="row"><label>배송료 청구기준<br />주문금액 조건설정</label></th>
									<td>
										<div class="radio">
											<label><input type="radio" name="it_send_condition" id="it_send_condition0" value="판매" <?php echo option_array_checked('판매', $it['it_send_condition']); ?> <?php echo $it_sc_type_readonly ?> /> 할인전, 정상판매가격 기준(권장)</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" name="it_send_condition" id="it_send_condition1" value="최종" <?php echo option_array_checked('최종', $it['it_send_condition']); ?> <?php echo $it_sc_type_readonly ?> /> 최종 주문(결제)금액 기준</label>
										</div>
									</td>
								</tr>

								<tr>
									<th scope="row"><label>배송비 선결제 설정</label></th>
									<td>
										<div class="radio">
											<label><input type="radio" name="it_sc_method" id="it_sc_method0" value="0" checked="checked" /> 선결제</label>&nbsp;&nbsp;&nbsp;
											<!-- label><input type="radio" name="it_sc_method" id="it_sc_method1" value="1"  /> 착불</label>&nbsp;&nbsp;&nbsp;
							<label><input type="radio" name="it_sc_method" id="it_sc_method2" value="2" /> 착불/선결제</label> -->
										</div>
									</td>
								</tr>

								<!-- tr>
				<th scope="row"><label>상품별 개별배송비 설정</label></th>
				<td>
					<div class="radio">
						<label><input type="radio" name="it_individual_costs_use" id="it_individual_costs_use0" value="0"  <?php echo option_array_checked('0', $it['it_individual_costs_use']); ?> /> 사용안함</label>&nbsp;&nbsp;&nbsp;
						<label><input type="radio" name="it_individual_costs_use" id="it_individual_costs_use1" value="1" <?php echo option_array_checked('1', $it['it_individual_costs_use']); ?>/> 사용함</label>
					</div>
				</td>
			</tr -->

								<tr>
									<th scope="row"><label>반품/교환 택배사</label></th>
									<td>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<select name="it_delivery_company" id="it_delivery_company" <?php echo $it_sc_type_readonly ?>>
												<?php echo get_delivery_company($it['it_delivery_company']); ?>
											</select>
										</div>
									</td>
								</tr>

								<tr>
									<th scope="row"><label>반품배송비(편도)</label></th>
									<td>
										<div class="col-md-2 col-sm-2 col-xs-10">
											<input type="text" name="it_return_costs" value="<?php echo $it['it_return_costs']; ?>" id="it_return_costs" class="form-control" <?php echo $it_sc_type_readonly ?>>
										</div>
										<label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
									</td>
								</tr>

								<!-- <tr>
				<th scope="row"><label>교환배송비(왕복)</label></th>
				<td>
					<div class="col-md-2 col-sm-2 col-xs-10">
						<input type="text" name="it_roundtrip_costs"  value="<?php echo $it['it_roundtrip_costs']; ?>" id="it_roundtrip_costs" class="form-control"  <?php echo $it_sc_type_readonly ?>>
					</div>
					<label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
				</td>
			</tr> -->

								<tr>
									<th scope="row"><label>반품 주소 설정</label></th>
									<td>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<div class="input-group col-sm-4 col-sm-4">
												<input type="text" name="it_return_zip" value="<?php echo $it['it_return_zip']; ?>" id="it_return_zip" class="form-control col-md-6 col-xs-6" size="5" maxlength="6" <?php echo $it_sc_type_readonly ?>>

												<span class="input-group-btn <?php if ($it_sc_type_readonly != "") echo "hidden"; ?>" id="btnZip">
													&nbsp;<button type="button" class="btn btn-primary" onclick="win_zip('fitemform', 'it_return_zip', 'it_return_address1', 'it_return_address2', 'it_return_address3', 'it_return_address_jibeon');">주소검색</button>
												</span>
											</div>

											<div class="input-group col-sm-9 col-sm-9">
												<input type="text" name="it_return_address1" value="<?php echo $it['it_return_address1']; ?>" id="it_return_address1" class="form-control col-md-12 col-xs-12" size="30" <?php echo $it_sc_type_readonly ?>>
											</div>

											<div class="input-group col-sm-9 col-sm-9">
												<input type="text" name="it_return_address2" value="<?php echo $it['it_return_address2']; ?>" id="it_return_address2" class="form-control col-md-12 col-xs-12" size="30" <?php echo $it_sc_type_readonly ?>>
											</div>
											<input type="hidden" name="it_return_address3" value="" id="it_return_address3">
											<input type="hidden" name="it_return_address_jibeon" value="" id="it_return_address_jibeon">

										</div>
									</td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>

				<script>
					$(function() {
						$("#it_sc_type0,#it_sc_type2").change(function() {
							var type = $(this).val();

							switch (type) {
								case "2":
									$("#it_send_type").prop("disabled", false);
									$("#it_send_term_start").prop("disabled", false);
									$("#it_send_term_end").prop("disabled", false);
									$("#it_sc_minimum").prop("disabled", false);
									$("#it_sc_price").prop("disabled", false);
									$("#it_send_condition0").prop("disabled", false);
									$("#it_send_condition1").prop("disabled", false);
									$("#it_sc_method0").prop("disabled", false);
									$("#it_delivery_company").prop("disabled", false);
									$("#").prop("disabled", false);
									$("#it_return_costs").prop("disabled", false);
									$("#it_roundtrip_costs").prop("disabled", false);
									$("#it_return_zip").prop("disabled", false);
									$("#it_return_address1").prop("disabled", false);
									$("#it_return_address2").prop("disabled", false);


									$("#btnZip").removeClass("hidden");

									break;
								default:
									$("#it_send_type").val("<?php echo $default['de_send_type'] ?>");
									$("#it_send_term_start").val("<?php echo $default['de_send_term_start'] ?>");
									$("#it_send_term_end").val("<?php echo $default['de_send_term_end'] ?>");
									$("#it_sc_minimum").val("<?php echo $default['de_send_cost_limit'] ?>");
									$("#it_sc_price").val("<?php echo $default['de_send_cost_list'] ?>");

									$("input:radio[name='it_send_condition']:input[value='<?php echo $default['de_send_condition'] ?>']").click();

									$("#it_delivery_company").val("<?php echo $default['de_delivery_company'] ?>");
									$("#it_return_costs").val("<?php echo $default['de_return_costs'] ?>");
									$("#it_roundtrip_costs").val("<?php echo $default['de_roundtrip_costs'] ?>");
									$("#it_return_zip").val("<?php echo $default['de_return_zip'] ?>");
									$("#it_return_address1").val("<?php echo $default['de_return_address1'] ?>");
									$("#it_return_address2").val("<?php echo $default['de_return_address2'] ?>");
									$("#it_return_address3").val("");
									$("#it_return_address_jibeon").val("");


									$("#it_send_type").prop("disabled", true);
									$("#it_send_term_start").prop("disabled", true);
									$("#it_send_term_end").prop("disabled", true);
									$("#it_sc_minimum").prop("disabled", true);
									$("#it_sc_price").prop("disabled", true);
									$("#it_send_condition0").prop("disabled", true);
									$("#it_send_condition1").prop("disabled", true);
									$("#it_sc_method0").prop("disabled", true);
									$("#it_delivery_company").prop("disabled", true);
									$("#").prop("disabled", true);
									$("#it_return_costs").prop("disabled", true);
									$("#it_roundtrip_costs").prop("disabled", true);
									$("#it_return_zip").prop("disabled", true);
									$("#it_return_address1").prop("disabled", true);
									$("#it_return_address2").prop("disabled", true);
									$("#btnZip").removeClass('hidden').addClass('hidden');
									break;
							}
						});
					});
				</script>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 필터 정보 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
								<?php
								$sql = " select * from lt_shop_finditem where fi_status = 'Y' ";
								$result = sql_query($sql);

								while ($row = sql_fetch_array($result)) {
								?>
									<tr>
										<th scope="row">
											<?php echo $row['fi_subject'] ?>
											<input type="hidden" name="fi_subject[]" value="<?php echo $row['fi_subject'] ?>">
											<input type="hidden" name="fi_id[]" value="<?php echo $row['fi_id'] ?>">
										</th>
										<td colspan="2"><?php
														$fi_contents = explode(",", $row['fi_contents']);
														$l = 0;
														foreach ($fi_contents as $key => $val) {
															echo '<label><input type="checkbox" name="fi_contents_' . $row['fi_id'] . '[]" value="' . $val . '" id="fi_contents_' . $row['fi_id'] . '_' . $l . '">' . $val . '</label>&nbsp;&nbsp;&nbsp;';
															$l++;
														}

														?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<script>
							$(function() {
								<?php
								if ($it['it_id'] && $it['it_info_finditem'] != '') {
									$it_info_finditem = json_decode($it['it_info_finditem'], true);
									foreach ($it_info_finditem as $key => $value) {

										if (isset($value['fi_contents']) && $value['fi_contents'] != "" && !is_array($value['fi_contents'])) {
											$fi_contents = explode(",", $value['fi_contents']);

											foreach ($fi_contents as $key2 => $value2) {
												echo "$(\"input[name='fi_contents_" . $value['fi_id'] . "[]'][value='" . $value2 . "']\").prop(\"checked\",true);";
											}
										}
									}
								}
								?>
							});
						</script>
					</div>
				</div>



				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 제품 설명<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th scope="row">제품설명<br />(PC)</th>
									<td colspan="2">
										<?php echo editor_html('it_explan', get_text($it['it_explan'], 0)); ?>
										<span class="red">※ 업로드 이미지 권장 사이즈 (960px * @) <br>※ 최대 15MB / 확장자 jpg, png만 가능</span>
									</td>
								</tr>
								<tr>
									<th scope="row" rowspan="2">제품설명<br />(모바일)</th>
									<td colspan="2">
										<div class="radio">
											<label><input type="radio" name="it_mobile_explan_use" id="it_mobile_explan_use0" value="0" <?php echo option_array_checked('0', $it['it_mobile_explan_use']); ?> /> 사용안함</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" name="it_mobile_explan_use" id="it_mobile_explan_use1" value="1" <?php echo option_array_checked('1', $it['it_mobile_explan_use']); ?> /> 사용함</label>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2"> <?php echo editor_html('it_mobile_explan', get_text($it['it_mobile_explan'], 0)); ?>
										<span class="red">※ 업로드 이미지 권장 사이즈 (300px * @) <br>※ 최대 15MB / 확장자 jpg, png만 가능</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 제품 상세 정보 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th scope="row">제품분류</th>
									<td>
										<select name="sel_ca_name1" id="sel_ca_name1" class="form-control" target="ca_name1" next="sel_ca_name2">
											<option value="">----선택-----</option>
											<?php
											$sql = "select ca_name1 from lt_shop_info group by ca_name1";
											$result = sql_query($sql);
											$ca_name1 = "";
											for ($i = 0; $row = sql_fetch_array($result); $i++) {
												echo '<option value="' . $row['ca_name1'] . '">' . $row['ca_name1'] . '</option>';
												$ca_name1 = $row['ca_name1'];
											}
											?>
										</select>
									</td>
									<td><select name="sel_ca_name2" id="sel_ca_name2" class="form-control" target="ca_name2" next="sel_ca_name3">
											<option value="">----선택-----</option>
										</select>
									</td>
									<td><select name="sel_ca_name3" id="sel_ca_name3" class="form-control" target="ca_name3" next="sel_ca_name4">
											<option value="">----선택-----</option>
										</select>
									</td>
									<td><select name="sel_ca_name4" id="sel_ca_name4" class="form-control" target="ca_name4">
											<option value="">----선택-----</option>
										</select>
									</td>
									<td>
										<button type="button" class="btn btn-success" id="btnItemInfoUpdate">수정</button>
									</td>
								</tr>
								<?php
								$sel_ca_name = "";
								if ($it['it_id'] && $it['it_info_gubun']) {
									$sql = "select * from lt_shop_info where if_id = '" . $it['it_info_gubun'] . "'";
									$row = sql_fetch($sql);
									$sel_ca_name = $row['ca_name1'];
									if ($row['ca_name2']) $sel_ca_name .= ' > ' . $row['ca_name2'];
									if ($row['ca_name3']) $sel_ca_name .= ' > ' . $row['ca_name3'];
									if ($row['ca_name4']) $sel_ca_name .= ' > ' . $row['ca_name4'];
								}
								?>
								<tr>
									<th scope="row">선택된 제품분류</th>
									<td colspan="5">
										<label id="spnItemInfoUpdate"><?php echo $sel_ca_name ?></label>
										<input type="hidden" name="it_info_gubun" id="it_info_gubun" value="<?php echo $it['it_info_gubun'] ?>">
									</td>
								</tr>
							</tbody>
						</table>

						<table id="tblItemInfo">
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody id="tbodyItemInfo">
								<?php
								if ($it['it_id'] && $it['it_info_value'] != '') {
									$article = json_decode($it['it_info_value'], true);
									foreach ($article as $key => $value) {
										$list = '<tr>';
										$list .= '    <th scope="row">' . $value['name'] . '</th>';
										$list .= '    <td>';
										$list .= '    	<input type="hidden" name="ii_article[]" value="' . $value['name'] . '" >';
										$list .= '    	<input type="text" name="ii_value[]" value="' . $value['value'] . '" class="form-control">';
										$list .= '    </td>';
										$list .= '</tr>';

										echo $list;
									}
								}
								?>
							</tbody>
						</table>

					</div>
				</div>

				<script>
					$(function() {
						var sel_if_id = '';

						$.get_ca_name = function(ca_name1, ca_name2, ca_name3, targetuid, targetSel) {
							sel_if_id = '';

							$targetSel = $("#" + targetSel);
							$.post(
								"<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php", {
									ca_name1: ca_name1,
									ca_name2: ca_name2,
									ca_name3: ca_name3
								},
								function(data) {
									var responseJSON = JSON.parse(data);
									var count = responseJSON.length;
									for (i = 0; i < count; i++) {
										//alert(data[i]['me_name']);
										if (responseJSON[i][targetuid] != "") {
											$targetSel.append($('<option>', {
												value: responseJSON[i][targetuid],
												text: responseJSON[i][targetuid],
												data: responseJSON[i]['if_id']
											}));
										} else if (responseJSON[i]['cnt'] == "1") {
											//$.get_if_info(responseJSON[i]['if_id']);
											sel_if_id = responseJSON[i]['if_id'];
										}
									}
								}
							);
						};

						var rowCnt = 0;
						$.get_if_info = function(if_id) {

							$.post(
								"<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php", {
									if_id: if_id
								},
								function(data) {
									var responseJSON = JSON.parse(data)[0];

									var ca_name = responseJSON['ca_name1'];
									if (responseJSON['ca_name2'] != "") ca_name += " > " + responseJSON['ca_name2'];
									if (responseJSON['ca_name3'] != "") ca_name += " > " + responseJSON['ca_name3'];
									if (responseJSON['ca_name4'] != "") ca_name += " > " + responseJSON['ca_name4'];

									$("#spnItemInfoUpdate").text(ca_name);

									var article = JSON.parse(responseJSON['article']);
									var count = article.length;
									var $tblItemInfo = $("#tblItemInfo");
									$("#tbodyItemInfo").empty();
									rowCnt = 0;

									for (i = 0; i < count; i++) {

										var list = "<tr id='tr" + rowCnt + "'>";
										list += "    <th scope=\"row\">" + article[i]['name'] + "</th>";
										list += "    <td>";
										list += "    	<input type=\"hidden\" name=\"ii_article[]\" value=\"" + article[i]['name'] + "\" >";
										list += "    	<input type=\"text\" name=\"ii_value[]\" value=\"" + article[i]['value'] + "\" class=\"form-control\">";
										list += "    </td>";
										list += "</tr>";

										var $menu_last = null;
										$menu_last = $tblItemInfo.find("tbody").find("tr:last");
										if ($menu_last.size() > 0) {
											$menu_last.after(list);
										} else {
											$tblItemInfo.find("tbody").append(list);
										}

										rowCnt++;
									}

									$("#it_info_gubun").val(responseJSON['if_id']);
								}
							);
						};

						$("#btnItemInfoUpdate").click(function(e) {
							var change = false;
							if (sel_if_id != "") {
								if ($("#it_info_gubun").val() == "") {
									change = true;
								} else {
									change = confirm("제품 정보 고시 항목이 변경되어 작성된 내용이 모두 삭제될 수 있습니다. 수정하시겠습니까?")
								}
							} else {
								alert("하위 분류를 선택해주세요.");
							}

							if (change) {
								$.get_if_info(sel_if_id);
							}

						});


						$("#sel_ca_name1").change(function(e) {

							$("#sel_ca_name2").empty().append($('<option>', {
								value: '',
								text: '----선택-----'
							}));
							$("#sel_ca_name3").empty().append($('<option>', {
								value: '',
								text: '----선택-----'
							}));
							$("#sel_ca_name4").empty().append($('<option>', {
								value: '',
								text: '----선택-----'
							}));
							if ($(this).val() != "") {
								$.get_ca_name($(this).val(), '', '', 'ca_name2', 'sel_ca_name2');
							}
						});

						$("#sel_ca_name2").change(function() {

							$("#sel_ca_name3").empty().append($('<option>', {
								value: '',
								text: '----선택-----'
							}));
							$("#sel_ca_name4").empty().append($('<option>', {
								value: '',
								text: '----선택-----'
							}));
							if ($(this).val() != "") {
								$.get_ca_name($("#sel_ca_name1").val(), $(this).val(), '', 'ca_name3', 'sel_ca_name3');
							}
						});

						$("#sel_ca_name3").change(function() {
							$("#sel_ca_name4").empty().append($('<option>', {
								value: '',
								text: '----선택-----'
							}));
							if ($(this).val() != "") {
								$.get_ca_name($("#sel_ca_name1").val(), $("#sel_ca_name2").val(), $(this).val(), 'ca_name4', 'sel_ca_name4');
							}
						});
					});
				</script>



				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 관련제품 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="local_desc02 local_desc">
					<p>
						등록된 전체제품 목록에서 제품분류를 선택하면 해당 제품 리스트가 연이어 나타납니다.<br>
						제품리스트에서 관련 제품으로 추가하시면 선택된 관련제품 목록에 <strong>함께</strong> 추가됩니다.<br>
						예를 들어, A 제품에 B 제품을 관련제품으로 등록하면 B 제품에도 A 제품이 관련제품으로 자동 추가됩니다.</strong>
					</p>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<h4><span class="fa fa-check-square"></span> 등록된 전체제품 목록</h4>
						<label for="sch_relation" class="sound_only">제품분류</label>
						<span class="srel_pad">
							<select id="sch_relation">
								<option value=''>분류별 제품</option>
								<?php
								$sql = " select * from {$g5['g5_shop_category_table']} ";
								$sql .= " order by ca_order, ca_id ";
								$result = sql_query($sql);
								for ($i = 0; $row = sql_fetch_array($result); $i++) {
									$len = strlen($row['ca_id']) / 2 - 1;

									$nbsp = "";
									for ($i = 0; $i < $len; $i++)
										$nbsp .= "&nbsp;&nbsp;&nbsp;";

									echo "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
								}
								?>
							</select>
							<label for="sch_name" class="sound_only">제품명</label>
							<input type="text" name="sch_name" id="sch_name" class="frm_input" size="15">
							<button type="button" id="btn_search_item" class="btn_frmline">검색</button>
						</span>
						<div id="relation" class="srel_list">
							<p>제품의 분류를 선택하시거나 제품명을 입력하신 후 검색하여 주십시오.</p>
							<div class="tbl_head01 tbl_wrap">
								<table>
									<thead>
										<tr>
											<th scope="col">제품코드</a></th>
											<th scope="col">분류</a></th>
											<th scope="col">카테고리</a></th>
											<th scope="col">제품명</a></th>
											<th scope="col">최종판매가<br />(최종월리스료)</a></th>
											<th scope="col">진열<br />상태</a></th>
											<th scope="col">추가</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<script>
							$(function() {
								$("#btn_search_item").click(function() {
									var ca_id = $("#sch_relation").val();
									var it_name = $.trim($("#sch_name").val());
									var $relation = $("#relation");

									if (ca_id == "" && it_name == "") {
										$relation.html("<p>제품의 분류를 선택하시거나 제품명을 입력하신 후 검색하여 주십시오.</p>");
										return false;
									}

									$("#relation").load(
										"./itemformrelation.php", {
											it_id: "<?php echo $it_id; ?>",
											ca_id: ca_id,
											it_name: it_name
										}
									);
								});

								$(document).on("click", "#relation .add_item", function() {
									// 이미 등록된 상품인지 체크
									var $li = $(this).closest("tr");
									var it_id = $li.find("input:hidden").val();
									var it_id2;
									var dup = false;
									$("#reg_relation input[name='re_it_id[]']").each(function() {
										it_id2 = $(this).val();
										if (it_id == it_id2) {
											dup = true;
											return false;
										}
									});

									if (dup) {
										alert("이미 선택된 제품입니다.");
										return false;
									}

									var cont = "<tr>" + $li.html().replace("add_item", "del_item").replace("추가", "삭제") + "</tr>";
									var count = $("#reg_relation tr").size();

									if (count > 0) {
										$("#reg_relation tr:last").after(cont);
									} else {
										$("#reg_relation").html("<tr>" + cont + "</tr>");
									}

									$li.remove();
								});

								$(document).on("click", "#reg_relation .del_item", function() {
									if (!confirm("제품을 삭제하시겠습니까?"))
										return false;

									$(this).closest("tr").remove();

									var count = $("#reg_relation tr").size();
									if (count < 1)
										$("#reg_relation").html("<p>선택된 상품이 없습니다.</p>");
								});
							});
						</script>
					</div>

					<br /><br />
					<section class="">
						<h4>선택된 관련제품 목록</h4>
						<span class="srel_pad"></span>
						<div class="tbl_head01 tbl_wrap">
							<table>
								<thead>
									<tr>
										<th scope="col">제품코드</a></th>
										<th scope="col">분류</a></th>
										<th scope="col">카테고리</a></th>
										<th scope="col">제품명</a></th>
										<th scope="col">최종판매가<br />(최종월리스료)</a></th>
										<th scope="col">진열<br />상태</a></th>
										<th scope="col">추가</th>
									</tr>
								</thead>
								<tbody id="reg_relation">
									<?php
									$str = array();
									$sql = " select b.ca_id, b.it_id, b.it_name, b.it_price,b.it_use,ca1.ca_name as ca_name1, ca2.ca_name as ca_name2, ca3.ca_name as ca_name3
					from {$g5['g5_shop_item_relation_table']} a
					left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id)
					left join {$g5['g5_shop_category_table']} ca1 on ca1.ca_id = left(b.ca_id,2)
					left join {$g5['g5_shop_category_table']} ca2 on ca2.ca_id = left(b.ca_id,4)
					left join {$g5['g5_shop_category_table']} ca3 on ca3.ca_id = left(b.ca_id,6)
					where a.it_id = '$it_id'
					order by ir_no asc ";
									$result = sql_query($sql);
									for ($g = 0; $row = sql_fetch_array($result); $g++) {
										$it_name = get_it_image($row['it_id'], 50, 50) . ' ' . $row['it_name'];

										$list = '';
										$list .= '<tr>';
										$list .= '<td>' . $row['it_id'] . '</td>';
										$list .= '<td>' . ($row['it_item_type'] == '0' ? '제품' : '리스') . '</td>';
										$list .= '<td>' . $row['ca_name1'] . ($row['ca_name2'] ? ' > ' . $row['ca_name2'] : '') . ($row['ca_name3'] ? ' > ' . $row['ca_name3'] : '') . '</td>';
										$list .= '<td style="text-align:left;">' . $it_name;
										$list .= '<td>' . number_format($row['it_price']) . '</td>';
										$list .= '<input type="hidden" name="re_it_id[]" value="' . $row['it_id'] . '">' . '</td>';
										$list .= '<td>' . ($row['it_use'] ? '진열' : '진열안함') . '</td>';
										$list .= '<td><button type="button" class="del_item btn_frmline">삭제</button></td>';
										$list .= '</tr>' . PHP_EOL;

										echo $list;

										$str[] = $row['it_id'];
									}
									$str = implode(",", $str);

									if ($g <= 0)
										echo '<p>선택된 제품이 없습니다.</p>';
									?>
								</tbody>
							</table>
						</div>
				</div>
				<input type="hidden" name="it_list" value="<?php echo $str; ?>">
				</section>

				<div class="x_title">
					<h4><span class="fa fa-check-square"></span> 기타 설정<small></small></h4>
					<label class="nav navbar-right"></label>
					<div class="clearfix"></div>
				</div>

				<div class="x_content">
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<colgroup>
								<col class="grid_4">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="it_shop_memo">상점메모</label></th>
									<td>
										<textarea name="it_shop_memo" id="it_shop_memo" class="resizable_textarea form-control" rows="4"><?php echo html_purifier($it['it_shop_memo']); ?></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>



				<div class="text-center">
					<input type="submit" value="저장" class="btn_submit btn" accesskey="s">
					<?php if ($w == "u") { ?><a href="<?php echo G5_SHOP_URL; ?>/item.php?it_id=<?php echo $it_id; ?>" class="btn_02  btn" target="_blank">미리보기</a> <?php } ?>
					<a href="./itemlist.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
				</div>
			</form>

			<div id="modal_sapsearch" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="sapsearchLabel" aria-hidden="true">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title" id="sapsearchLabel">SAP 코드검색</h4>
						</div>
						<div class="modal-body">
							<div class="tbl_frm01 tbl_wrap">
								<table>
									<tr>
										<td><label class="control-label">SAP Code</label></td>
										<td><input type="text" class="form-control" id="txtModalSapCode"></td>
										<td><button type="button" class="btn btn-primary" id="btnModalSapSearch">검색</button></td>
									</tr>
								</table>
							</div>

							<div class="clearfix"></div>

							<div id="modal_sap_option_frm">
								<div class="tbl_frm01 tbl_wrap">
									<table>
										<colgroup>
											<col width="30%">
											<col width="70%">
										</colgroup>
										<tbody>
											<tr>
												<td>검색되는 코드값이 없습니다.</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>




<script>
	var f = document.fitemform;
	var subID = "";

	$(function() {
		$(document).ready(function($) {

			$("#it_price").autoNumeric('init', {
				mDec: '0'
			});
			$("#it_rental_price").autoNumeric('init', {
				mDec: '0'
			});
			$("#it_discount_price").autoNumeric('init', {
				mDec: '0'
			});

			var its_org_price = 0;
			var its_org_rental_price = 0;
			var its_final_rental_price = 0;
			var its_final_price = 0;

			$("input[name='its_price[]']").each(function() {
				its_org_price += parseInt($(this).autoNumeric('get'));
			});

			$("input[name='its_rental_price[]']").each(function() {
				its_org_rental_price += parseInt($(this).autoNumeric('get'));
			});

			$("input[name='its_final_rental_price[]']").each(function() {
				its_final_rental_price += parseInt($(this).autoNumeric('get'));
			});

			$("input[name='its_final_price[]']").each(function() {
				its_final_price += parseInt($(this).autoNumeric('get'));
			});
			$("#it_price").autoNumeric('set', its_final_price);
			$("#it_rental_price").autoNumeric('set', its_final_rental_price);
			if (its_org_rental_price == 0) {
				$("#it_discount_price").autoNumeric('set', its_org_price - its_final_price);
			} else {
				var it_item_rental_month = parseInt($("#it_item_rental_month").val());
				$("#it_final_rental_price").text(number_format(its_final_rental_price * it_item_rental_month));
				$("#it_discount_price").autoNumeric('set', (its_org_rental_price - its_final_rental_price) * it_item_rental_month);
			}

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
			$.setImgFileUpload('imgFile4');
			$.setImgFileUpload('imgFile5');

			$(document).on("click", "#btnSapSearch", function() {
				$("#" + $(this).attr("txtID")).trigger("click");
			});

			$(document).on("click", "input[name='its_sap_code[]']", function() {
				$("#txtModalSapCode").val($(this).val());
				subID = $(this).attr("s");

				$("#modal_sap_option_frm").empty();
				$("#modal_sapsearch").modal('show');
			});

			$('#modal_sapsearch').on('shown.bs.modal', function() {
				$('#txtModalSapCode').focus();
			})

			$("#txtModalSapCode").bind("keydown", function(e) {
				if (e.keyCode == 13) {
					$("#btnModalSapSearch").trigger("click");
					return false;
				}
			});

			$("#btnModalSapSearch").click(function() {
				var sap_code = $("#txtModalSapCode").val();

				var $option_table = $("#modal_sap_option_frm");
				//alert(subID);

				$.post(
					"./itemsapsearch.php", {
						sap_code: sap_code,
						subID: subID
					},
					function(data) {
						$option_table.empty().html(data);
					}
				);

			});

			$('#it_period').daterangepicker({
				"autoApply": true,
				"opens": "right",
				locale: {
					"format": "YYYY-MM-DD",
					"separator": " - ",
					"applyLabel": "선택",
					"cancelLabel": "취소",
					"fromLabel": "시작일자",
					"toLabel": "종료일자",
					"customRangeLabel": "직접선택",
					"weekLabel": "W",
					"daysOfWeek": ["일", "월", "화", "수", "목", "금", "토"],
					"monthNames": ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
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

			$('#it_period').val("<?php echo $it['it_period'] ?>");

			$("#it_period_chk").click(function() {
				var chk = $(this).is(":checked");

				$('#it_period').val("");
				if (chk) {
					$('#it_period').prop('disabled', true);
				} else {
					$('#it_period').prop('disabled', false);
				}
			});

		});
	});

	function sapconfirm(orderno, sapcode, item, price, s) {
		$("#its_order_no" + s).val(orderno);
		$("#its_sap_code" + s).val(sapcode);
		$("#its_item" + s).val(item);

		//$("#its_price"+s).val(price);
		//$("#its_final_price"+s).val(price);

		$("#its_price" + s).autoNumeric('set', price);
		$("#its_final_price" + s).autoNumeric('set', price);
		$("#its_price" + s).trigger("keyup");

		makeOption(price, s);

		$("#modal_sapsearch").modal('hide');
	}

	function makeOption(price, s) {
		//var it_id = $.trim($("input[name=it_id]").val());
		var sap_code = $("#its_sap_code" + s).val();
		var order_no = $("#its_order_no" + s).val();

		var $option_table = $("#sit_option_frm" + s);

		$.post(
			"./itemsapoption.php", {
				w: "<?php echo $w; ?>",
				sap_code: sap_code,
				order_no: order_no,
				min_price: price,
				subID: subID,
				subID: s
			},
			function(data) {
				$option_table.empty().html(data);
			}
		);
	}

	function codedupcheck(id) {
		if (!id) {
			alert('제품코드를 입력하십시오.');
			f.it_id.focus();
			return;
		}

		var it_id = id.replace(/[A-Za-z0-9\-_]/g, "");
		if (it_id.length > 0) {
			alert("제품코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
			return false;
		}

		$.post(
			"./codedupcheck.php", {
				it_id: id
			},
			function(data) {
				if (data.name) {
					alert("코드 '" + data.code + "' 는 '" + data.name + "' (으)로 이미 등록되어 있으므로\n사용하실 수 없습니다.");
					document.fitemform.codedup.value = '1';
					document.fitemform.codedup_it_id.value = '';
					return false;
				} else {
					alert("'" + data.code + "' 은(는) 등록된 코드가 없으므로 사용하실 수 있습니다.");
					document.fitemform.codedup.value = '';
					document.fitemform.codedup_it_id.value = id;
					return false;
				}
			}, "json"
		);
	}

	function fitemformcheck(f) {
		if (!f.ca_id.value) {
			alert("기본분류를 선택하십시오.");
			f.ca_id.focus();
			return false;
		}

		if (f.w.value == "") {
			var error = "";
			$.ajax({
				url: "./ajax.it_id.php",
				type: "POST",
				data: {
					"it_id": f.it_id.value
				},
				dataType: "json",
				async: false,
				cache: false,
				success: function(data, textStatus) {
					error = data.error;
				}
			});

			if (error) {
				alert(error);
				return false;
			}
		}

		var maxSendCost = parseInt(f.it_sc_price.value) + parseInt(f.it_return_costs.value);

		var error = false;
		if (f.it_item_type.value == "0") {
			$("input[name='its_final_price[]']").each(function() {
				if (parseInt($(this).autoNumeric('get')) < maxSendCost) {
					alert("판매가는 왕복배송비(" + maxSendCost + "원)보다 낮은금액으로 등록할 수 없습니다.");
					error = true;
					return false;
				}
			});
		} else if (f.it_item_type.value == "1") {
			$("input[name='its_final_rental_price[]']").each(function() {
				if (parseInt($(this).autoNumeric('get')) < maxSendCost) {
					alert("월 리스료는 왕복배송비(" + maxSendCost + "원)보다 낮은금액으로 등록할 수 없습니다.");
					error = true;
					return false;
				}
			});
		}
		if (error) {
			$("input[name='its_final_price[]']").focus();
			return false;
		}

		if (f.it_point_type.value == "2") {
			var point = parseInt(f.it_point2.value);
			if (point > 99) {
				alert("적립금 비율을 0과 99 사이의 값으로 입력해 주십시오.");
				return false;
			}
		}

		if (parseInt(f.it_sc_type.value) > 1) {
			if (!f.it_sc_price.value || f.it_sc_price.value == "0") {
				alert("기본배송비를 입력해 주십시오.");
				return false;
			}

			if (f.it_sc_type.value == "2" && (!f.it_sc_minimum.value || f.it_sc_minimum.value == "0")) {
				alert("배송비 상세조건의 주문금액을 입력해 주십시오.");
				return false;
			}

			if (f.it_sc_type.value == "4" && (!f.it_sc_qty.value || f.it_sc_qty.value == "0")) {
				alert("배송비 상세조건의 주문수량을 입력해 주십시오.");
				return false;
			}
		}
		var chk = false;

		$("input[name='its_sap_code[]']").each(function() {
			if (trim($(this).val()) == "") {
				chk = true;
				$(this).focus();
			}
		});
		if (chk) {
			alert("SAP코드는 필수입력 사항입니다.");
			return false;
		}
		$("input[name='it_option_subject[]']").each(function() {
			if (trim($(this).val()) == "") {
				chk = true;
				$(this).focus();
			}
		});
		if (chk) {
			alert("옵션명은 필수입력 사항입니다.");
			return false;
		}

		// 관련상품처리
		var item = new Array();
		var re_item = it_id = "";

		$("#reg_relation input[name='re_it_id[]']").each(function() {
			it_id = $(this).val();
			if (it_id == "")
				return true;

			item.push(it_id);
		});

		if (item.length > 0)
			re_item = item.join();

		$("input[name=it_list]").val(re_item);


		<?php echo get_editor_js('it_explan'); ?>
		<?php echo get_editor_js('it_mobile_explan'); ?>


		return true;
	}
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>