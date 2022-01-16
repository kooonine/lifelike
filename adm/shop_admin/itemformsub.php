<?php
include_once('./_common.php');

if (!$w && $w != "u") {
	$its['its_discount_type'] = '0';
	$its['its_free_laundry'] = '1';
	$its['its_laundry_use'] = '0';
	$its['its_laundrykeep_use'] = '0';
	$its['its_repair_use'] = '0';

	$sql = " select 0 as its_discount_type, 1 as its_free_laundry, 0 as its_laundry_use, 0 as its_laundrykeep_use, 0 as its_repair_use";
	$result = sql_query($sql);
} else if ($w == "u") {
	$it['it_id'] = $it_id;

	$sql = " select * from lt_shop_item_sub where it_id = '$it_id' ";
	$result = sql_query($sql);
}

if (!$s) $s = 0;

for ($x = 0; $its = sql_fetch_array($result); $x++) {
	if (!$s || $x != 0) $s = ($x + 1);
	?>
	<div class="tbl_frm01 tbl_wrap" id="tbl_shop_item_sub<?php echo $s ?>">
		<table>
			<thead>
				<tr>
					<td colspan="6" class="bg-primary" style="text-align: center;">
						<div style="float: left;width:10%">&nbsp;</div>
						<div style="float: left;text-align: center;width:80%">
							<h4>마스터 연동 상품 <?php echo $s ?></h4>
						</div>
						<div style="float: right;text-align: right;width:10%">
							<?php if ($s != "1") { ?>
								<button class="btn btn-danger" type="button" id="btnItitemDel<?php echo $s ?>" target="tbl_shop_item_sub<?php echo $s ?>">삭제</button>
								<script>
									jQuery(function($) {

										$(document).on("click", "#btnItitemDel<?php echo $s ?>", function() {
											$(this).closest("table").closest("div").remove();
										});

									});
								</script>
							<?php } ?>
						</div>
					</td>
				</tr>
			</thead>
			<thead>
				<tr>
					<td colspan="6" class="bg-info" style="text-align: center;">
						<h5>A.연동 정보</h5>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row"><label for="it_skin">SAP코드</label></th>
					<td>
						<input type="text" name="its_sap_code[]" value="<?php echo $its['its_sap_code']; ?>" s="<?php echo $s ?>" id="its_sap_code<?php echo $s ?>" required class="frm_input readonly" size="12" maxlength="12" readonly="readonly">
						<button class="btn" type="button" id="btnSapSearch" txtID="its_sap_code<?php echo $s ?>">검색</button>
						<input type="hidden" name="its_no[]" value="<?php echo $its['its_no']; ?>">
						<input type="hidden" name="itscnt[]" id="itscnt<?php echo $s; ?>" value="<?php echo $s; ?>">
					</td>
					<th scope="row"><label for="it_skin">삼진코드</label></th>
					<td>
						<input type="text" name="its_order_no[]" value="<?php echo $its['its_order_no']; ?>" id="its_order_no<?php echo $s ?>" required class="frm_input readonly" size="20" readonly="readonly">
					</td>
					<th scope="row"><label for="it_skin">상품명</label></th>
					<td>
						<input type="text" name="its_item[]" value="<?php echo $its['its_item']; ?>" id="its_item<?php echo $s ?>" required class="frm_input readonly" size="30" readonly="readonly">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="it_skin">할인설정</label></th>
					<td colspan="5">

						<label><input type="radio" value="0" id="its_discount_type0<?php echo $s ?>" name="its_discount_type<?php echo $s ?>" <?php echo ($its['its_discount_type'] == '0') ? 'checked' : ''; ?>> 사용안함 </label>&nbsp;&nbsp;&nbsp;

						<label><input type="radio" value="1" id="its_discount_type1<?php echo $s ?>" name="its_discount_type<?php echo $s ?>" <?php echo ($its['its_discount_type'] == '1') ? 'checked' : ''; ?>> 할인율(%)</label>&nbsp;&nbsp;&nbsp;
						<input type="text" name="its_discount1[]" value="<?php echo ($its['its_discount_type'] == '1') ? $its['its_discount'] : ''; ?>" id="its_discount1<?php echo $s ?>" class="frm_input text-right <?php echo ($its['its_discount_type'] == '1') ? '' : 'readonly'; ?>" size="10" <?php echo ($its['its_discount_type'] == '1') ? '' : 'readonly'; ?>>&nbsp;&nbsp;&nbsp;

						<label><input type="radio" value="2" id="its_discount_type2<?php echo $s ?>" name="its_discount_type<?php echo $s ?>" <?php echo ($its['its_discount_type'] == '2') ? 'checked' : ''; ?>> 할인가(원)</label>&nbsp;&nbsp;&nbsp;
						<input type="text" name="its_discount2[]" value="<?php echo ($its['its_discount_type'] == '2') ? $its['its_discount'] : ''; ?>" id="its_discount2<?php echo $s ?>" class="frm_input text-right <?php echo ($its['its_discount_type'] == '2') ? '' : 'readonly'; ?>" size="10" <?php echo ($its['its_discount_type'] == '2') ? '' : 'readonly'; ?>>

					</td>
				</tr>
				<tr>
					<th scope="row"><label for="it_skin">박스가</label></th>
					<td colspan="5">
						<?php
							$connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
							$g5['connect_samjindb'] = $connect_db;
							$zbox_sql = " SELECT ORDER_NO,SAP_CODE,ITEM,CAT_NO,CAT_ITEM,STATUS,COLOR,COLOR_NAME,SZ,HOCHING,PRICE,STOCK FROM S_MALL_ORDERS where SAP_CODE like  N'Z%' order by COLOR, SZ ";
							$zbox_result = mssql_sql_query($zbox_sql);
							?>

						<select name="its_zbox[]" id="its_zbox<?php echo $s ?>">
							<?php
								if (!$its['its_zbox_name']) $its['its_zbox_name'] = "1";

								while ($zbox = mssql_sql_fetch_array($zbox_result)) {
									echo "<option value='" . $zbox['SZ'] . "," . $zbox['PRICE'] . "' " . get_selected((int) $zbox['SZ'], (int) $its['its_zbox_name']) . ">" . $zbox['SZ'] . ". " . $zbox['COLOR_NAME'] . " / " . number_format($zbox['PRICE']) . " 원</option>";

									if (!$its['its_zbox_price'] && $zbox['SZ'] == $its['its_zbox_name']) $its['its_zbox_price'] = $zbox['PRICE'];
								}
								?>
						</select>
						<input type="text" name="its_zbox_price[]" value="<?php echo $its['its_zbox_price']; ?>" id="its_zbox_price<?php echo $s ?>" required class="frm_input full_input text-right">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="it_skin">판매가</label></th>
					<td>
						<input type="text" name="its_price[]" value="<?php echo $its['its_price']; ?>" id="its_price<?php echo $s ?>" required class="frm_input full_input text-right">
					</td>
					<th scope="row"><label for="it_skin">최종판매가</label></th>
					<td colspan="3">
						<label></label>
						<input type="text" name="its_final_price[]" value="<?php echo $its['its_final_price']; ?>" id="its_final_price<?php echo $s ?>" required class="frm_input readonly text-right" readonly="readonly">
					</td>
				</tr>
				<tr <?php if ($it['it_item_type'] != '1' && $it_item_type != '1') echo 'hidden' ?> id="tr_its_rental<?php echo $s ?>">
					<th scope="row"><label for="it_skin">월리스료</label></th>
					<td>
						<input type="text" name="its_rental_price[]" value="<?php echo $its['its_rental_price']; ?>" id="its_rental_price<?php echo $s ?>" class="frm_input full_input text-right">
					</td>
					<th scope="row"><label for="it_skin">최종월리스료</label></th>
					<td>
						<input type="text" name="its_final_rental_price[]" value="<?php echo $its['its_final_rental_price']; ?>" id="its_final_rental_price<?php echo $s ?>" class="frm_input readonly text-right" readonly="readonly">
					</td>
					<th scope="row"><label for="it_skin">최종완납가</label></th>
					<td>
						<input type="text" name="its_final_rental_total_price[]" value="<?php echo ($its['its_final_rental_price'] * $it['it_item_rental_month']); ?>" id="its_final_rental_total_price<?php echo $s ?>" class="frm_input readonly text-right" readonly="readonly">
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			jQuery(function($) {
				var s = '<?php echo $s ?>';

				$("#its_discount1" + s).autoNumeric('init', {
					mDec: '0',
					vMax: 100
				});
				$("#its_discount2" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_final_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_rental_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_final_rental_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_final_rental_total_price" + s).autoNumeric('init', {
					mDec: '0'
				});

				$("#its_zbox_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_zbox" + s).change(function() {
					var zbox = $(this).val();
					$("#its_zbox_price" + s).autoNumeric('set', zbox.split(",")[1]);
				});

				$("#its_discount_type0" + s).click(function() {
					$("#its_discount1" + s).val("");
					$("#its_discount2" + s).val("");

					$("#its_discount1" + s).prop("readonly", true);
					$("#its_discount1" + s).removeClass("readonly").addClass("readonly");
					$("#its_discount2" + s).prop("readonly", true);
					$("#its_discount2" + s).removeClass("readonly").addClass("readonly");
					$.final_price_set<?php echo $s ?>();
				});
				$("#its_discount_type1" + s).click(function() {
					$("#its_discount1" + s).val("");
					$("#its_discount2" + s).val("");

					$("#its_discount1" + s).prop("readonly", false);
					$("#its_discount1" + s).removeClass("readonly");
					$("#its_discount2" + s).prop("readonly", true);
					$("#its_discount2" + s).removeClass("readonly").addClass("readonly");
					$.final_price_set<?php echo $s ?>();

					$("#its_discount1" + s).focus();
				});
				$("#its_discount_type2" + s).click(function() {
					$("#its_discount1" + s).val("");
					$("#its_discount2" + s).val("");

					$("#its_discount1" + s).prop("readonly", true);
					$("#its_discount1" + s).removeClass("readonly").addClass("readonly");
					$("#its_discount2" + s).prop("readonly", false);
					$("#its_discount2" + s).removeClass("readonly");
					$.final_price_set<?php echo $s ?>();

					$("#its_discount2" + s).focus();
				});

				$.final_price_set<?php echo $s ?> = function() {

					if ($("#its_discount1" + s).val() != "") {
						var final_price = $("#its_price" + s).autoNumeric('get') - ($("#its_price" + s).autoNumeric('get') / 100 * $("#its_discount1" + s).autoNumeric('get'));
						$("#its_final_price" + s).autoNumeric('set', final_price);

						//$("#it_price").val($("#its_final_price"+s).val());

						if ($("#its_rental_price" + s).val() != "" && $("#its_rental_price" + s).val() != "0") {
							final_price = $("#its_rental_price" + s).autoNumeric('get') - ($("#its_rental_price" + s).autoNumeric('get') / 100 * $("#its_discount1" + s).autoNumeric('get'));
							$("#its_final_rental_price" + s).autoNumeric('set', final_price);

							var it_item_rental_month = $("#it_item_rental_month").val();
							$("#its_final_rental_total_price" + s).autoNumeric('set', final_price * it_item_rental_month);
						}
					} else if ($("#its_discount2" + s).val() != "") {
						$("#its_discount2" + s).autoNumeric('update', {
							vMax: $("#its_price" + s).autoNumeric('get')
						});

						var final_price = $("#its_price" + s).autoNumeric('get') - $("#its_discount2" + s).autoNumeric('get');
						$("#its_final_price" + s).autoNumeric('set', final_price);

						//$("#it_price").val($("#its_final_price"+s).val());

						if ($("#its_rental_price" + s).val() != "" && $("#its_rental_price" + s).val() != "0") {
							final_price = $("#its_rental_price" + s).autoNumeric('get') - $("#its_discount2" + s).autoNumeric('get');
							$("#its_final_rental_price" + s).autoNumeric('set', final_price);

							var it_item_rental_month = $("#it_item_rental_month").val();
							$("#its_final_rental_total_price" + s).autoNumeric('set', final_price * it_item_rental_month);
						}
					} else {
						$("#its_final_price" + s).val($("#its_price" + s).val());
						//$("#it_price").val($("#its_final_price"+s).val());

						if ($("#its_rental_price" + s).val() != "" && $("#its_rental_price" + s).val() != "0") {
							var final_price = $("#its_rental_price" + s).autoNumeric('get');
							$("#its_final_rental_price" + s).autoNumeric('set', final_price);

							var it_item_rental_month = $("#it_item_rental_month").val();
							$("#its_final_rental_total_price" + s).autoNumeric('set', final_price * it_item_rental_month);
						}
					}

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
				}

				$("#its_discount1" + s + ",#its_discount2" + s + ",#its_price" + s + ",#its_rental_price" + s).keyup(function() {
					$.final_price_set<?php echo $s ?>();
				});

			});
		</script>

		<table>
			<thead>
				<tr>
					<td class="bg-info" style="text-align: center;">
						<h5>B. 연동 고정 옵션</h5>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div id="sit_option_frm<?php echo $s ?>"></div>
						<script>
							$.post(
								"<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsapoption.php", {
									w: "<?php echo $w; ?>",
									it_id: '<?php echo $its['it_id']; ?>',
									its_no: '<?php echo $its['its_no']; ?>',
									its_option_subject: '<?php echo $its['its_option_subject']; ?>',
									subID: '<?php echo $s ?>'
								},
								function(data) {
									$("#sit_option_frm<?php echo $s ?>").empty().html(data);
								}
							);
						</script>
					</td>
				</tr>
			</tbody>
		</table>


		<table>
			<colgroup>
				<col width="10%">
				<col width="15%">
				<col width="10%">
				<col width="15%">
				<col width="10%">
				<col width="15%">
				<col width="10%">
				<col width="15%">
			</colgroup>
			<thead>
				<tr>
					<td class="bg-info" style="text-align: center;" colspan="8">
						<h5>C. 케어서비스 정보</h5>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row"><label for="it_skin">무료세탁권</label></th>
					<td>
						<input type="text" name="its_free_laundry[]" id="its_free_laundry<?php echo $s ?>" value="<?php echo $its['its_free_laundry'] ?>" class="frm_input text-right">
						<label>회</label>
					</td>
					<td colspan="4">
						<span class="red">※ 무료세탁권은 연간 단품 1회 가능,무료세탁가의 측정금액은 세탁서비스와 동일 <br />※ 만료일은 등록/수정일기준 3개월</span>
					</td>
					<th scope="row"><label for="it_skin">택배비</label></th>
					<td>
						<input type="text" name="its_free_laundry_delivery_price[]" id="its_free_laundry_delivery_price<?php echo $s ?>" value="<?php echo $its['its_free_laundry_delivery_price'] ?>" class="frm_input text-right">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="it_skin">세탁서비스</label></th>
					<td>
						<label><input type="radio" value="0" id="its_laundry_use0<?php echo $s ?>" name="its_laundry_use<?php echo $s ?>" <?php echo ($its['its_laundry_use'] == '0') ? 'checked' : ''; ?>> 사용안함 </label>&nbsp;&nbsp;&nbsp;
						<label><input type="radio" value="1" id="its_laundry_use1<?php echo $s ?>" name="its_laundry_use<?php echo $s ?>" <?php echo ($its['its_laundry_use'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
					</td>
					<th scope="row"><label for="it_skin">세탁비</label></th>
					<td colspan="3">
						<input type="text" name="its_laundry_price[]" id="its_laundry_price<?php echo $s ?>" value="<?php echo $its['its_laundry_price'] ?>" class="frm_input text-right <?php echo ($its['its_laundry_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_laundry_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
					<th scope="row"><label for="it_skin">택배비</label></th>
					<td>
						<input type="text" name="its_laundry_delivery_price[]" id="its_laundry_delivery_price<?php echo $s ?>" value="<?php echo $its['its_laundry_delivery_price'] ?>" class="frm_input text-right <?php echo ($its['its_laundry_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_laundry_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="it_skin">세탁보관서비스</label></th>
					<td>
						<label><input type="radio" value="0" id="its_laundrykeep_use0<?php echo $s ?>" name="its_laundrykeep_use<?php echo $s ?>" <?php echo ($its['its_laundrykeep_use'] == '0') ? 'checked' : ''; ?>> 사용안함 </label>&nbsp;&nbsp;&nbsp;
						<label><input type="radio" value="1" id="its_laundrykeep_use1<?php echo $s ?>" name="its_laundrykeep_use<?php echo $s ?>" <?php echo ($its['its_laundrykeep_use'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
					</td>
					<th scope="row"><label for="it_skin">세탁비</label></th>
					<td>
						<input type="text" name="its_laundrykeep_lprice[]" id="its_laundrykeep_lprice<?php echo $s ?>" value="<?php echo $its['its_laundrykeep_lprice'] ?>" class="frm_input text-right <?php echo ($its['its_laundrykeep_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_laundrykeep_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
					<th scope="row"><label for="it_skin">보관비(1개월)</label></th>
					<td>
						<input type="text" name="its_laundrykeep_kprice[]" id="its_laundrykeep_kprice<?php echo $s ?>" value="<?php echo $its['its_laundrykeep_kprice'] ?>" class="frm_input text-right <?php echo ($its['its_laundrykeep_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_laundrykeep_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
					<th scope="row"><label for="it_skin">택배비</label></th>
					<td>
						<input type="text" name="its_laundrykeep_delivery_price[]" id="its_laundrykeep_delivery_price<?php echo $s ?>" value="<?php echo $its['its_laundrykeep_delivery_price'] ?>" class="frm_input text-right <?php echo ($its['its_laundrykeep_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_laundrykeep_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="it_skin">수선서비스</label></th>
					<td>
						<label><input type="radio" value="0" id="its_repair_use0<?php echo $s ?>" name="its_repair_use<?php echo $s ?>" <?php echo ($its['its_repair_use'] == '0') ? 'checked' : ''; ?>> 사용안함 </label>&nbsp;&nbsp;&nbsp;
						<label><input type="radio" value="1" id="its_repair_use1<?php echo $s ?>" name="its_repair_use<?php echo $s ?>" <?php echo ($its['its_repair_use'] == '1') ? 'checked' : ''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
					</td>
					<th scope="row"><label for="it_skin">수선비</label></th>
					<td colspan="3">
						<input type="text" name="its_repair_price[]" id="its_repair_price<?php echo $s ?>" value="<?php echo $its['its_repair_price'] ?>" class="frm_input text-right <?php echo ($its['its_repair_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_repair_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
					<th scope="row"><label for="it_skin">택배비</label></th>
					<td>
						<input type="text" name="its_repair_delivery_price[]" id="its_repair_delivery_price<?php echo $s ?>" value="<?php echo $its['its_repair_delivery_price'] ?>" class="frm_input text-right <?php echo ($its['its_repair_use'] == '1') ? '' : 'readonly'; ?>" <?php echo ($its['its_repair_use'] == '1') ? '' : 'readonly'; ?>>
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			jQuery(function($) {
				var s = '<?php echo $s ?>';

				$("#its_free_laundry" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_free_laundry_delivery_price" + s).autoNumeric('init', {
					mDec: '0'
				});

				$("#its_laundry_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_laundry_delivery_price" + s).autoNumeric('init', {
					mDec: '0'
				});

				$("#its_laundrykeep_lprice" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_laundrykeep_kprice" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_laundrykeep_delivery_price" + s).autoNumeric('init', {
					mDec: '0'
				});

				$("#its_repair_price" + s).autoNumeric('init', {
					mDec: '0'
				});
				$("#its_repair_delivery_price" + s).autoNumeric('init', {
					mDec: '0'
				});

				$("#its_laundry_use0" + s + ",#its_laundry_use1" + s).change(function() {
					$("#its_laundry_price" + s).val("");
					$("#its_laundry_delivery_price" + s).val("");
					var use = ($(this).val() == '0');

					$("#its_laundry_price" + s).prop("readonly", use);
					$("#its_laundry_delivery_price" + s).prop("readonly", use);

					$("#its_laundry_price" + s).removeClass("readonly");
					$("#its_laundry_delivery_price" + s).removeClass("readonly");
					if (use) {
						$("#its_laundry_price" + s).addClass("readonly");
						$("#its_laundry_delivery_price" + s).addClass("readonly");
					}
				});

				$("#its_laundrykeep_use0" + s + ",#its_laundrykeep_use1" + s).change(function() {
					$("#its_laundrykeep_lprice" + s).val("");
					$("#its_laundrykeep_kprice" + s).val("");
					$("#its_laundrykeep_delivery_price" + s).val("");
					var use = ($(this).val() == '0');

					$("#its_laundrykeep_lprice" + s).prop("readonly", use);
					$("#its_laundrykeep_kprice" + s).prop("readonly", use);
					$("#its_laundrykeep_delivery_price" + s).prop("readonly", use);

					$("#its_laundrykeep_lprice" + s).removeClass("readonly");
					$("#its_laundrykeep_kprice" + s).removeClass("readonly");
					$("#its_laundrykeep_delivery_price" + s).removeClass("readonly");
					if (use) {
						$("#its_laundrykeep_lprice" + s).addClass("readonly");
						$("#its_laundrykeep_kprice" + s).addClass("readonly");
						$("#its_laundrykeep_delivery_price" + s).addClass("readonly");
					}
				});

				$("#its_repair_use0" + s + ",#its_repair_use1" + s).change(function() {
					$("#its_repair_price" + s).val("");
					$("#its_repair_delivery_price" + s).val("");
					var use = ($(this).val() == '0');

					$("#its_repair_price" + s).prop("readonly", use);
					$("#its_repair_delivery_price" + s).prop("readonly", use);

					$("#its_repair_price" + s).removeClass("readonly");
					$("#its_repair_delivery_price" + s).removeClass("readonly");
					if (use) {
						$("#its_repair_price" + s).addClass("readonly");
						$("#its_repair_delivery_price" + s).addClass("readonly");
					}
				});

			});
		</script>


		<table>
			<thead>
				<tr>
					<td class="bg-info" style="text-align: center;" colspan="2">
						<h5>D. 옵션 정보</h5>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php
					$spl_subject = explode(',', $its['its_supply_subject']);
					$spl_count = count($spl_subject);

					?>
				<tr>
					<th scope="row">상품추가옵션</th>
					<td colspan="2">
						<div id="sit_supply_frm<?php echo $s ?>" class="sit_option tbl_frm01">
							<?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 스마트폰을 예로 들어 [추가1 : 추가구성상품 , 추가1 항목 : 액정보호필름,케이스,충전기]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
							<table>
								<caption>상품추가옵션 입력</caption>
								<colgroup>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
									<?php
										$i = 0;
										do {
											$seq = $i + 1;
											?>
										<tr>
											<th scope="row">
												<label for="spl_subject_<?php echo $seq; ?><?php echo $s ?>">추가<?php echo $seq; ?></label>
												<input type="text" name="spl_subject<?php echo $s ?>[]" id="spl_subject_<?php echo $seq; ?><?php echo $s ?>" value="<?php echo $spl_subject[$i]; ?>" class="frm_input" size="15">
											</th>
											<td>
												<label for="spl_item_<?php echo $seq; ?><?php echo $s ?>"><b>추가<?php echo $seq; ?> 항목</b></label>
												<input type="text" name="spl<?php echo $s ?>[]" id="spl_item_<?php echo $seq; ?><?php echo $s ?>" value="" class="frm_input" size="40">
												<?php
														if ($i > 0)
															echo '<button type="button" id="del_supply_row" class="btn_frmline">삭제</button>';
														?>
											</td>
										</tr>
									<?php
											$i++;
										} while ($i < $spl_count);
										?>
								</tbody>
							</table>
							<div id="sit_option_addfrm_btn"><button type="button" id="add_supply_row<?php echo $s ?>" class="btn_frmline">옵션추가</button></div>
							<div class="btn_confirm02 btn_confirm">
								<button type="button" id="supply_table_create<?php echo $s ?>">옵션목록생성</button>
							</div>

							<script>
								$(function() {
									var s = '<?php echo $s ?>';

									<?php if ($it['it_id']) { ?>
										// 추가옵션의 항목 설정
										var arr_subj = new Array();
										var subj, spl;

										$("input[name='spl_subject" + s + "[]']").each(function() {
											subj = $.trim($(this).val());
											if (subj && $.inArray(subj, arr_subj) == -1)
												arr_subj.push(subj);
										});

										for (i = 0; i < arr_subj.length; i++) {
											var arr_spl = new Array();
											$(".spl-subject-cell").each(function(index) {
												subj = $(this).text();
												if (subj == arr_subj[i]) {
													spl = $(".spl-cell:eq(" + index + ")").text();
													arr_spl.push(spl);
												}
											});

											$("input[name='spl" + s + "[]']:eq(" + i + ")").val(arr_spl.join());
										}
									<?php } ?>
									// 입력필드추가
									$("#add_supply_row" + s).click(function() {
										var $el = $("#sit_supply_frm" + s + " tr:last");
										var fld = "<tr>\n";
										fld += "<th scope=\"row\">\n";
										fld += "<label for=\"\">추가</label>\n";
										fld += "<input type=\"text\" name=\"spl_subject" + s + "[]\" value=\"\" class=\"frm_input\" size=\"15\">\n";
										fld += "</th>\n";
										fld += "<td>\n";
										fld += "<label for=\"\"><b>추가 항목</b></label>\n";
										fld += "<input type=\"text\" name=\"spl" + s + "[]\" value=\"\" class=\"frm_input\" size=\"40\">\n";
										fld += "<button type=\"button\" id=\"del_supply_row\" class=\"btn_frmline\">삭제</button>\n";
										fld += "</td>\n";
										fld += "</tr>";

										$el.after(fld);

										supply_sequence(s);
									});

									// 입력필드삭제
									$(document).on("click", "#del_supply_row", function() {
										$(this).closest("tr").remove();

										supply_sequence(s);
									});

									// 옵션목록생성
									$("#supply_table_create" + s).click(function() {
										var it_id = $.trim($("input[name=it_id]").val());
										var subject = new Array();
										var supply = new Array();
										var subj, spl;
										var count = 0;
										var $el_subj = $("input[name='spl_subject" + s + "[]']");
										var $el_spl = $("input[name='spl" + s + "[]']");
										var $supply_table = $("#sit_option_addfrm" + s);

										$el_subj.each(function(index) {
											subj = $.trim($(this).val());
											spl = $.trim($el_spl.eq(index).val());

											if (subj && spl) {
												subject.push(subj);
												supply.push(spl);
												count++;
											}
										});

										if (!count) {
											alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
											return false;
										}

										$.post(
											"<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsupply.php", {
												w: "<?php echo $w; ?>",
												'subject[]': subject,
												'supply[]': supply,
												subID: '<?php echo $s ?>'
											},
											function(data) {
												$supply_table.empty().html(data);
											}
										);
									});

									// 모두선택
									$(document).on("click", "input[name=spl_chk_all]", function() {
										if ($(this).is(":checked")) {
											$("input[name='spl_chk[]']").attr("checked", true);
										} else {
											$("input[name='spl_chk[]']").attr("checked", false);
										}
									});

									// 선택삭제
									$(document).on("click", "#sel_supply_delete", function() {
										var $el = $("input[name='spl_chk[]']:checked");
										if ($el.size() < 1) {
											alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
											return false;
										}

										$el.closest("tr").remove();
									});

									function supply_sequence(subid) {
										var $tr = $("#sit_supply_frm" + subid + " tr");
										var seq;
										var th_label, td_label;

										$tr.each(function(index) {
											seq = index + 1;
											$(this).find("th label").attr("for", "spl_subject_" + seq + "" + subid).text("추가" + seq);
											$(this).find("th input").attr("id", "spl_subject_" + seq + "" + subid);
											$(this).find("td label").attr("for", "spl_item_" + seq + "" + subid);
											$(this).find("td label b").text("추가" + seq + " 항목");
											$(this).find("td input").attr("id", "spl_item_" + seq + "" + subid);
										});
									}

								});
							</script>
						</div>

						<div id="sit_option_addfrm<?php echo $s ?>"></div>
						<script>
							$.post(
								"<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsupply.php", {
									w: "<?php echo $w; ?>",
									it_id: '<?php echo $its['it_id']; ?>',
									its_no: '<?php echo $its['its_no']; ?>',
									subID: '<?php echo $s ?>'
								},
								function(data) {
									$("#sit_option_addfrm<?php echo $s ?>").empty().html(data);
								}
							);
						</script>

					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php } ?>