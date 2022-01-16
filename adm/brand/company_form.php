<?php
$bank_names = array("국민은행","신한은행","KEB하나은행","농협","SC제일은행","한국시티은행","광주은행","경남은행","대구은행","부산은행","전북은행","제주은행","수협","우체국","산업은행","새마을금고","신협");
$required_mb_id = 'readonly';

if($w=="c") {
	$required = 'required';
	$submit = "승인요청";
} else if($w=="u") {
	$submit = "정보변경신청";
}

$mb['mb_name'] = get_text($mb['mb_name']);
$mb['mb_nick'] = get_text($mb['mb_nick']);
$mb['mb_email'] = get_text($mb['mb_email']);
$mb['mb_homepage'] = get_text($mb['mb_homepage']);
$mb['mb_birth'] = get_text($mb['mb_birth']);
$mb['mb_tel'] = get_text($mb['mb_tel']);
$mb['mb_hp'] = get_text($mb['mb_hp']);
$mb['mb_addr1'] = get_text($mb['mb_addr1']);
$mb['mb_addr2'] = get_text($mb['mb_addr2']);
$mb['mb_addr3'] = get_text($mb['mb_addr3']);
$mb['mb_signature'] = get_text($mb['mb_signature']);
$mb['mb_recommend'] = get_text($mb['mb_recommend']);
$mb['mb_profile'] = get_text($mb['mb_profile']);
$mb['mb_1'] = get_text($mb['mb_1']);
$mb['mb_2'] = get_text($mb['mb_2']);
$mb['mb_3'] = get_text($mb['mb_3']);
$mb['mb_4'] = get_text($mb['mb_4']);
$mb['mb_5'] = get_text($mb['mb_5']);
$mb['mb_6'] = get_text($mb['mb_6']);
$mb['mb_7'] = get_text($mb['mb_7']);
$mb['mb_8'] = get_text($mb['mb_8']);
$mb['mb_9'] = get_text($mb['mb_9']);
$mb['mb_10'] = get_text($mb['mb_10']);

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

if($w == "c")
{
	$cp['company_type'] = "1";
}
?>

<form name="fmember" id="fmember" action="./company_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="x_title">
		<h4><span class="fa fa-check-square"></span> 판매자 정보<small></small></h4>
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
						<th rowspan="2">사업자구분</th>
						<td>
							<input type="radio" id="mb_1_2" name="company_type" value="1" <?php echo get_checked($cp['company_type'], "1")?> required="required">
							<label for="mb_1_2">일반사용자</label>
							<input type="radio" id="mb_1_1" name="company_type" value="0" <?php echo get_checked($cp['company_type'], "0")?> required="required">
							<label for="mb_1_1">법인사용자</label>
						</td>
					</tr>
					<tr>
						<td>
							<select name="company_type2" id="company_type2" required>
								<option <?php echo get_selected($cp['company_type2'], "과세사업자")?>  <?php echo get_selected($cp['company_type2'], "일반과세자")?>>과세사업자</option>
								<option <?php echo get_selected($cp['company_type2'], "면세사업자")?>>면세사업자</option>
							</select>

							<select name="company_type2_1" id="company_type2_1" <?php if($cp['company_type'] == "0" ||  $cp['company_type2'] == "면세사업자") echo "hidden"; ?>>
								<option <?php echo get_selected($cp['company_type2'], "일반과세자")?>>일반과세자</option>
							</select>
							<script>
								$("input[name='company_type'],#company_type2").change(function(){
									var company_type = $("input[name='company_type']:checked").val();
									var company_type2 = $("#company_type2").val();

									if(company_type == "1" && company_type2 == "과세사업자"){
										$("#company_type2_1").prop("hidden", false);
									}else if(company_type == "0" ||  company_type2 == "면세사업자"){
										$("#company_type2_1").prop("hidden", true);
									}
								});

							</script>
						</td>
					</tr>
					<tr>
						<th>사업자등록번호</th>
						<td>
							<input type="text" placeholder="사업자등록번호 입력" id="company_no" name="company_no" required="required" class="frm_input" value="<?php echo $cp['company_no']?>">
							<input type="hidden" id="bisYN">
							<button type="button" class="btn " onclick="bisCheckSum()">사업자 인증</button>
						</td>
					</tr>
					<tr>
						<th>상호명</th>
						<td>
							<input type="text" placeholder="" id="company_name" name ="company_name" required="required" class="frm_input" value="<?php echo $cp['company_name']?>">
						</td>
					</tr>

					<tr>
						<th rowspan="3">회사 주소</th>
						<td>
							<input type="text" placeholder="" id="company_zip" name="company_zip" title="우편번호" readonly required class="frm_input" value="<?php echo $cp['company_zip1'].$cp['company_zip2']?>">
							<button type="button" class="btn " onclick="win_zip('fmember', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3','company_addr_jibeon');">우편번호</button>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" title="상세주소" placeholder="" id="company_addr1"  name = "company_addr1" readonly="readonly" required="required" class="frm_input col-xs-12"  value="<?php echo $cp['company_addr1']?>">
						</td>

						<tr>
							<td>
								<input type="text" title="상세주소" placeholder="" id="company_addr2" name = "company_addr2"  class="frm_input col-xs-12" value="<?php echo $cp['company_addr2']?>">
								<input type="hidden" id="company_addr3" name = "company_addr3"  value="<?php echo $cp['company_addr3']?>">
								<input type="hidden" id = "company_jibeon" name="company_addr_jibeon"  value="<?php echo $cp['company_addr_jibeon']?>">
							</td>
						</tr>
						<tr>
							<th>업태/종목</th>
							<td>
								<input type="text" placeholder="" id="company_category" name ="company_category" required="required" class="frm_input" value="<?php echo $cp['company_category']?>">
							</td>
						</tr>
						<tr>
							<th>통신판매업신고번호</th>
							<td>
								<input type="text" placeholder="- 없이 번호 입력" id="cp_tongsin_no" name ="cp_tongsin_no" required="required" class="frm_input" value="<?php echo $cp['cp_tongsin_no']?>">
							</td>
						</tr>
						<tr>
							<th>대표자 성명</th>
							<td>
								<input type="text" placeholder="" id="company_leader" name ="company_leader" required="required" class="frm_input" value="<?php echo $cp['company_leader']?>">
							</td>
						</tr>
						<tr>
							<th>대표 전화번호<br>(회사 연락처)</th>
							<td>
								<input type="tel" placeholder="대표 전화번호 입력" id="company_hp" name ="company_hp" required="required" class="frm_input" value="<?php echo $cp['company_hp']?>">
							</td>
						</tr>
						<tr>
							<th>팩스번호</th>
							<td>
								<label><input type="checkbox" id="chk_cp_fax" name="chk_cp_fax" <?php echo ($cp['cp_fax'] != "")?"checked":"" ?>> 있음</label>
								<input type="tel" id="cp_fax" name ="cp_fax" class="frm_input <?php echo ($cp['cp_fax'] != "")?"":"readonly" ?>" value="<?php echo $cp['cp_fax']?>" <?php echo ($cp['cp_fax'] != "")?"":"readonly" ?>>
								<script>
									$("#chk_cp_fax").click(function(){
										var chk = $(this).is(':checked');
										if(chk){
											$('#cp_fax').prop('readonly', false);
											$('#cp_fax').removeClass('readonly');
											$('#cp_fax').focus();
										}else{
											$('#cp_fax').val("");
											$('#cp_fax').prop('readonly', true);
											$('#cp_fax').removeClass('readonly').addClass('readonly');
										}
									});

								</script>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

		<div class="x_title">
			<h4><span class="fa fa-check-square"></span> 파트너 담당자 정보<small></small></h4>
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
							<th>담당자 성명</th>
							<td><?php echo $mb['mb_name'] ?>
							<input type="hidden" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" >
						</td>
					</tr>
					<tr>
						<th>로그인 ID</th>
						<td><?php echo $mb['mb_id'] ?>
						<input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" ></td>
					</tr>
					<tr>
						<th>이메일 주소</th>
						<td><?php echo $mb['mb_email'] ?>
						<input type="hidden" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" ></td>
					</tr>
					<tr>
						<th>휴대전화 번호</th>
						<td><?php echo $mb['mb_hp'] ?>
						<input type="hidden" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" ></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td>
							<input type="text" placeholder="" id=mb_tel name ="mb_tel" class="frm_input" value="<?php echo $mb['mb_tel']?>" required>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>


	<div class="x_title">
		<div class="pull-left">
			<h4><span class="fa fa-check-square"></span> 정산 담당자 정보<small></small></h4>
		</div>
		<div class="pull-right"><label class="nav navbar-right"><input type="checkbox" onclick="same_partner(this, 'jeongsan_mb_name','jeongsan_mb_email','jeongsan_mb_tel','jeongsan_mb_hp');"> 파트너 담당자와 동일</label></div>
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
						<th>담당자 성명</th>
						<td><input type="text" name="jeongsan_mb_name" value="<?php echo $cp['jeongsan_mb_name'] ?>" id="jeongsan_mb_name" class="frm_input" size="15"  maxlength="45" required>
						</td>
					</tr>
					<tr>
						<th>이메일 주소</th>
						<td><input type="text" name="jeongsan_mb_email" value="<?php echo $cp['jeongsan_mb_email'] ?>" id="jeongsan_mb_email" maxlength="255" class="frm_input email" size="30" required></td>
					</tr>
					<tr>
						<th>휴대전화 번호</th>
						<td><input type="text" name="jeongsan_mb_tel" value="<?php echo $cp['jeongsan_mb_tel'] ?>" id="jeongsan_mb_tel" maxlength="20" class="frm_input" size="30" required></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><input type="text" name="jeongsan_mb_hp" value="<?php echo $cp['jeongsan_mb_hp'] ?>" id="jeongsan_mb_hp" maxlength="20" class="frm_input" size="30" required></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>


	<div class="x_title">
		<div class="pull-left">
			<h4><span class="fa fa-check-square"></span> 배송 담당자 정보<small></small></h4>
		</div>
		<div class="pull-right"><label class="nav navbar-right"><input type="checkbox" onclick="same_partner(this, 'delivery_mb_name','delivery_mb_email','delivery_mb_tel','delivery_mb_hp');"> 파트너 담당자와 동일</label></div>
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
						<th>담당자 성명</th>
						<td><input type="text" name="delivery_mb_name" value="<?php echo $cp['delivery_mb_name'] ?>" id="delivery_mb_name" class="frm_input" size="15"  maxlength="45" required>
						</td>
					</tr>
					<tr>
						<th>이메일 주소</th>
						<td><input type="text" name="delivery_mb_email" value="<?php echo $cp['delivery_mb_email'] ?>" id="delivery_mb_email" maxlength="255" class="frm_input email" size="30" required></td>
					</tr>
					<tr>
						<th>휴대전화 번호</th>
						<td><input type="text" name="delivery_mb_tel" value="<?php echo $cp['delivery_mb_tel'] ?>" id="delivery_mb_tel" maxlength="20" class="frm_input" size="30" required></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><input type="text" name="delivery_mb_hp" value="<?php echo $cp['delivery_mb_hp'] ?>" id="delivery_mb_hp" maxlength="20" class="frm_input" size="30" required></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>


	<div class="x_title">
		<div class="pull-left">
			<h4><span class="fa fa-check-square"></span> CS 담당자 정보<small></small></h4>
		</div>
		<div class="pull-right"><label class="nav navbar-right"><input type="checkbox" onclick="same_partner(this, 'cs_mb_name','cs_mb_email','cs_mb_tel','cs_mb_hp');"> 파트너 담당자와 동일</label></div>
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
						<th>담당자 성명</th>
						<td><input type="text" name="cs_mb_name" value="<?php echo $cp['cs_mb_name'] ?>" id="cs_mb_name" class="frm_input" size="15"  maxlength="45" required>
						</td>
					</tr>
					<tr>
						<th>이메일 주소</th>
						<td><input type="text" name="cs_mb_email" value="<?php echo $cp['cs_mb_email'] ?>" id="cs_mb_email" maxlength="255" class="frm_input email" size="30" required></td>
					</tr>
					<tr>
						<th>휴대전화 번호</th>
						<td><input type="text" name="cs_mb_tel" value="<?php echo $cp['cs_mb_tel'] ?>" id="cs_mb_tel" maxlength="20" class="frm_input" size="30" required></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><input type="text" name="cs_mb_hp" value="<?php echo $cp['cs_mb_hp'] ?>" id="cs_mb_hp" maxlength="20" class="frm_input" size="30" required></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<script>
		function same_partner(clt, mb_name, mb_email, mb_hp, mb_tel){
			var chk = $(clt).is(":checked");
			if(chk)
			{
				$("#"+mb_name).val($("#mb_name").val());
				$("#"+mb_email).val($("#mb_email").val());
				$("#"+mb_tel).val($("#mb_tel").val());
				$("#"+mb_hp).val($("#mb_hp").val());
			} else {
				$("#"+mb_name).val('');
				$("#"+mb_email).val('');
				$("#"+mb_tel).val('');
				$("#"+mb_hp).val('');
			}
		//alert(chk);
	}
</script>


<div class="x_title">
	<div class="pull-left">
		<h4><span class="fa fa-check-square"></span> 정산 정보<small></small></h4>
	</div>
	<div class="pull-right"><label class="nav navbar-right"></div>
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
						<th rowspan="3">정산 대금 입금 계좌</th>
						<td>
							<select class="frm_input" id="cp_bank_name" name="cp_bank_name" required="required">
								<option value="">은행선택</option>
								<?php
								for ($i = 0; $i < count($bank_names); $i++) {
									echo '<option '.get_selected($bank_names[$i], $cp['cp_bank_name']).'>'.$bank_names[$i].'</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" name="cp_bank_account" value="<?php echo $cp['cp_bank_account'] ?>" required="required" id="cp_bank_account" maxlength="100" class="frm_input" size="30" placeholder="예금주명">
							<input type="text" name="cp_bank_account_no" value="<?php echo $cp['cp_bank_account_no'] ?>" required="required" id="cp_bank_account_no" maxlength="100" class="frm_input" size="30" placeholder="-없이 계좌번호입력">
						</td>
					</tr>
					<tr>
						<td>일반(간이)사업자의 예금주는 대표자명 또는 사업자등록증의 상호명과 동일해야하며, <br/>
							법인사업자의 예금주는 사업자등록증의 법인명의와 동일해야 합니다.<br/>
						임금 계좌 변경시, 심사가 최종 승인된 이후 구매확정 주문 건 부터 변경된 계좌로 정산 됩니다.</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="x_title">
		<div class="pull-left">
			<h4><span class="fa fa-check-square"></span> 배송 정보<small></small></h4>
		</div>
		<div class="pull-right"><label class="nav navbar-right"></div>
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
							<td colspan="2">※ 출고지 주소와 반품/교환지 주소는 주소록에서 수정/변경이 가능합니다.</td>
						</tr>
						<tr>
							<th scope="row">출고지 주소</th>
							<td class="td_addr_line">
								<label for="mb_zip" class="sound_only">우편번호</label>
								<input type="text" name="cp_out_zip" value="<?php echo $cp['cp_out_zip']; ?>" id="cp_out_zip" class="frm_input readonly" size="5" maxlength="6" required>
								<button type="button" class="btn_frmline" onclick="win_zip('fmember', 'cp_out_zip', 'cp_out_address1', 'cp_out_address2', 'cp_out_address3', 'cp_out_address_jibeon');">주소 검색</button><br>
								<input type="text" name="cp_out_address1" value="<?php echo $cp['cp_out_address1'] ?>" id="cp_out_address1" class="frm_input readonly" size="60" required>
								<br>
								<input type="text" name="cp_out_address2" value="<?php echo $cp['cp_out_address2'] ?>" id="cp_out_address2" class="frm_input" size="60">
								<input type="hidden" name="cp_out_address3" value="">
								<input type="hidden" name="cp_out_address_jibeon" value="">
							</td>
						</tr>
						<tr>
							<th scope="row">반품/교환지 주소</th>
							<td class="td_addr_line">
								<label for="mb_zip" class="sound_only">우편번호</label>
								<input type="text" name="cp_return_zip" value="<?php echo $cp['cp_return_zip']; ?>" id="cp_return_zip" class="frm_input readonly" size="5" maxlength="6" required>
								<button type="button" class="btn_frmline" onclick="win_zip('fmember', 'cp_return_zip', 'cp_return_address1', 'cp_return_address2', 'cp_return_address3', 'cp_return_address_jibeon');">주소 검색</button><br>
								<input type="text" name="cp_return_address1" value="<?php echo $cp['cp_return_address1'] ?>" id="cp_return_address1" class="frm_input readonly" size="60" required>
								<br>
								<input type="text" name="cp_return_address2" value="<?php echo $cp['cp_return_address2'] ?>" id="cp_return_address2" class="frm_input" size="60">
								<input type="hidden" name="cp_return_address3" value="" >
								<input type="hidden" name="cp_return_address_jibeon" value="">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="x_title">
			<div class="pull-left">
				<h4><span class="fa fa-check-square"></span> 판매자 개인정보처리방침 관리<small></small></h4>
			</div>
			<div class="pull-right"><label class="nav navbar-right"></div>
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
								<td colspan="2">※ 정보통신서비스 제공자로부터 개인정보를 제공받은 사업자는 정보통신망법을 준수해야 하며, 정보통신망법 제27조의2에 따라 개인정보 처리방침을 정하여 공개할 의무가 있습니다.<br/>
								회사 홈페이지에 별도로 ‘개인정보 처리방침’을 공개하지 않는 스마트스토어 판매자 회원의 경우 스마트스토어에서 제공하는 ‘개인정보 처리방침’ 공개 기능을 이용해 구매자에게 해당 내용을 고지 할 수 있습니다.</td>
							</tr>
							<tr>
								<th>개인정보처리방침공개 여부</th>
								<td>
									<label><input type="radio" id="cp_ps_open_use_1" name="cp_ps_open_use" value="1" <?php echo get_checked($cp['cp_ps_open_use'], "1")?> required="required"> 공개</label>
									<label><input type="radio" id="cp_ps_open_use_0" name="cp_ps_open_use" value="0" <?php echo get_checked($cp['cp_ps_open_use'], "0")?> required="required"> 비공개</label>
								</td>
							</tr>
							<tr>
								<th>기타업무 위탁 업체 입력</th>
								<td>
									<label><input type="radio" id="cp_ps_mb_use_0" name="cp_ps_mb_use" value="0" <?php echo get_checked($cp['cp_ps_mb_use'], "0")?> required="required"> 없음</label>
									<label><input type="radio" id="cp_ps_mb_use_1" name="cp_ps_mb_use" value="1" <?php echo get_checked($cp['cp_ps_mb_use'], "1")?> required="required"> 있음</label>
								</td>
							</tr>
							<tr>
								<th rowspan="2">개인정보 보호 책임자</th>
								<td><input type="text" name="cp_ps_mb_name" value="<?php echo $cp['cp_ps_mb_name'] ?>" id="cp_ps_mb_name" maxlength="45" class="frm_input" size="30" required="required" placeholder="담당자 성명"></td>
							</tr>
							<tr>
								<td><input type="text" name="cp_ps_mb_email" value="<?php echo $cp['cp_ps_mb_email'] ?>" id="cp_ps_mb_email" maxlength="255" class="frm_input" size="30" required="required" placeholder="이메일주소 직접입력"></td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>

			<div class="x_title">
				<div class="pull-left">
					<h4><span class="fa fa-check-square"></span> 입점몰 이미지 등록<small></small></h4>
				</div>
				<div class="pull-right"><label class="nav navbar-right"></div>
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
									<th scope="row"><label for="mb_img">입점몰 이미지 등록</label></th>
									<td>
										<input type="file" name="company_img" id="company_img" <?php echo ($w=="")?$required:"" ?> >
										<span class="red">* 업로드 이미지 사이즈 (480px * 480px) <br />
											* 최대 15MB / 확장자 jpg, png만 가능</span>
									</td>
									<td>
										<?php
										$mb_dir = $mb['mb_id'];

										$icon_file = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$mb['mb_id'].'_company_img.gif';
										if (file_exists($icon_file)) {
											$icon_url = G5_DATA_URL.'/company/'.$mb_dir.'/'.$mb['mb_id'].'_company_img.gif';
											echo '<img src="'.$icon_url.'" alt="">';
										}
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>


				<div class="x_title">
					<div class="pull-left">
						<h4><span class="fa fa-check-square"></span> 서류제출<small></small></h4>
					</div>
					<div class="pull-right"><label class="nav navbar-right"></div>
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
										<th scope="row"><label>사업자 등록증</label></th>
										<td>
											<input type="file" name="company_file" id="company_file">
										</td>
										<td>
											<?php
											$company_file = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$cp['company_file'];
											if (file_exists($company_file)) {
												echo $cp['company_file'];
												echo '&nbsp;&nbsp;&nbsp;<label><input type="checkbox" id="del_company_file" name="del_company_file" value="1">삭제</label>';
											}
											?>
										</td>
									</tr>
									<tr>
										<th scope="row"><label>추가 서류제출 1</label></th>
										<td>
											<input type="file" name="company_file1" id="company_file1">
										</td>
										<td>
											<?php
											$company_file1 = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$cp['company_file1'];
											if (file_exists($company_file1)) {
												echo $cp['company_file1'];
												echo '&nbsp;&nbsp;&nbsp;<label><input type="checkbox" id="del_company_file1" name="del_company_file1" value="1">삭제</label>';
											}
											?>
										</td>
									</tr>
									<tr id="tr_file_2" hidden>
										<th scope="row"><label>추가 서류제출 2</label></th>
										<td>
											<input type="file" name="company_file2" id="company_file2">
										</td>
										<td>
											<?php
											$company_file2 = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$cp['company_file2'];
											if (file_exists($company_file2)) {
												echo $cp['company_file2'];
												echo '&nbsp;&nbsp;&nbsp;<label><input type="checkbox" id="del_company_file2" name="del_company_file2" value="1">삭제</label>';
											}
											?>
										</td>
									</tr>
									<tr id="tr_file_3" hidden>
										<th scope="row"><label>추가 서류제출 3</label></th>
										<td>
											<input type="file" name="company_file3" id="company_file3">
										</td>
										<td>
											<?php
											$company_file3 = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$cp['company_file3'];
											if (file_exists($company_file3)) {
												echo $cp['company_file3'];
												echo '&nbsp;&nbsp;&nbsp;<label><input type="checkbox" id="del_company_file3" name="del_company_file3" value="1">삭제</label>';
											}
											?>
										</td>
									</tr>
									<tr id="tr_file_4" hidden>
										<th scope="row"><label>추가 서류제출 4</label></th>
										<td>
											<input type="file" name="company_file4" id="company_file4">
										</td>
										<td>
											<?php
											$company_file4 = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$cp['company_file4'];
											if (file_exists($company_file4)) {
												echo $cp['company_file4'];
												echo '&nbsp;&nbsp;&nbsp;<label><input type="checkbox" id="del_company_file4" name="del_company_file4" value="1">삭제</label>';
											}
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="pull-right">
						<input type="submit" value="<?php echo $submit ?>" class="btn_submit btn" accesskey='s'>
					</div>
				</form>


				<script>
					$(function(){
						$('.datepicker').datetimepicker({
							ignoreReadonly: true,
							allowInputToggle: true,
							format: 'YYYY-MM-DD',
							locale : 'ko'
						});


					});


					function bisCheckSum(){
						if($('#company_no').val() != "" && ckBisNo($('#company_no').val())){
							alert('인증이 완료 되었습니다.');
							$('#bisYN').val('Y');
						}else {
							alert('사업자 번호를 확인 해주세요');
						}
					}


					function ckBisNo(bisNo)

					{

	// 넘어온 값의 정수만 추츨하여 문자열의 배열로 만들고 10자리 숫자인지 확인합니다.

	if ((bisNo = (bisNo+'').match(/\d{1}/g)).length != 10) { return false; }



	// 합 / 체크키

	var sum = 0, key = [1, 3, 7, 1, 3, 7, 1, 3, 5];



	// 0 ~ 8 까지 9개의 숫자를 체크키와 곱하여 합에더합니다.

	for (var i = 0 ; i < 9 ; i++) { sum += (key[i] * bisNo[i])); }



	// 각 8번배열의 값을 곱한 후 10으로 나누고 내림하여 기존 합에 더합니다.

	// 다시 10의 나머지를 구한후 그 값을 10에서 빼면 이것이 검증번호 이며 기존 검증번호와 비교하면됩니다.



	// 체크섬구함

	var chkSum = 0;

	chkSum = Math.floor(key[8] * bisNo[8]) / 10);

	// 체크섬 합계에 더해줌

	sum +=chkSum;

	var reminder = (10 - (sum % 10)) % 10;

	//값 비교

	if(reminder==bisNo[9])) return true;

	return false;

}

function fmember_submit(f)
{
	if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
		alert('아이콘은 이미지 파일만 가능합니다.');
		return false;
	}

	if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
		alert('회원이미지는 이미지 파일만 가능합니다.');
		return false;
	}

	return true;
}
</script>
