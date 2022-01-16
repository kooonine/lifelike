<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');
?>

<?
// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = "select  b.ct_id, a.it_id, a.it_name, a.ct_option, a.rf_serial, a.od_type
, a.ct_price, a.io_price, a.ct_time
, a.ct_rental_price, a.ct_item_rental_month
, a.ct_free_laundry_use, a.ct_free_laundry, a.ct_free_laundry_delivery_price
, a.ct_laundry_use, a.ct_laundry_price, a.ct_laundry_delivery_price
, a.ct_laundrykeep_use, a.ct_laundrykeep_lprice, a.ct_laundrykeep_kprice, a.ct_laundrykeep_delivery_price
, a.ct_repair_use, a.ct_repair_price, a.ct_repair_delivery_price
, b.buy_ct_id, b.buy_od_sub_id, a.ct_status
from    lt_shop_order_item a
inner join lt_shop_cart b
on a.ct_id = b.buy_ct_id and a.od_sub_id = b.buy_od_sub_id
where   b.od_id = '$s_cart_id'
and   b.ct_select = '1' ";

$row = sql_fetch($sql);

if(($row['ct_status'] != "구매완료" && $row['ct_status'] != "리스중" && $row['ct_status'] != "") || $row['ct_repair_use'] != "1")
	alert('케어서비스를 이용하실 수 없습니다.');

$image_width = 150;
$image_height = 150;
$image = get_it_image($row['it_id'], $image_width, $image_height);
$it_name = stripslashes($row['it_name']);

$send_cost = 0; //배송비
$goods = $goods_it_id = "";

$laundry_price = 0; //유료세탁비
//$send_cost =  $row['ct_repair_delivery_price']; //배송비
$title = "수선 신청";
$sell_price = $laundry_price;

$tot_sell_price = $sell_price;
$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비

$goods = preg_replace("/\?|\'|\"|\||\,|\&|\;/", "", $row['it_name']).' '.$title;
$goods_it_id = $row['it_id'];
?>

<!-- container -->
<div id="container">

	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span><?=$title?></span></h1>
		<a href="#" class="btn_back"><span class="blind">뒤로가기</span></a>
		<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
	</div>
	<!-- // lnb -->

	<div class="content shop sub">
		<form name="forderform" id="forderform" method="post" action="<?=$order_action_url; ?>" autocomplete="off" enctype="multipart/form-data">

			<!-- 컨텐츠 시작 -->
			<div class="grid">
				<div class="title_bar ">
					<h3 class="g_title_01">제품 정보
						<button type="button" class="category round_none floatR" onclick="$('#pricetable').css('display','');"><span>수선단가표</span></button>
					</span></h3>
					<a href="<?=G5_SHOP_URL ?>/care.php" class="title-more"><span>제품 변경</span></a>
				</div>
				<div class="orderwrap">
					<div class="order_cont">
						<div class="body">
							<div class="cont right_cont">
								<div class="photo"><?=$image; ?></div>
								<div class="info">
									<strong><?=$it_name; ?></strong>
									<p><span class="txt">옵션</span><span class="point_black"><strong class="bold"><?=$row['ct_option']; ?></strong></span></p>
									<p><span class="txt">구매일</span><span class="point_black"><?=substr($row['ct_time'], 0, 10)?></span></p>

								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="it_id[]"    value="<?=$row['it_id']; ?>">
				<input type="hidden" name="it_name[]"  value="<?=get_text($row['it_name']); ?>">
				<input type="hidden" name="it_price[]" value="<?=$sell_price; ?>">
				<input type="hidden" name="cp_id[]" value="">
				<input type="hidden" name="cp_price[]" value="0">

				<input type="hidden" name="od_price"    value="<?=$tot_sell_price; ?>">
				<input type="hidden" name="od_send_cost" value="<?=$send_cost; ?>">
				<input type="hidden" name="od_send_cost2" value="0">
				<input type="hidden" name="item_coupon" value="0">
				<input type="hidden" name="od_coupon" value="0">
				<input type="hidden" name="od_send_coupon" value="0">
				<input type="hidden" name="od_goods_name" value="<?=$goods; ?>">
				<input type="hidden" name="od_type" value="<?=$od_type?>" />
			</div>

			<div class="grid border_box gray_box">
				<div class="divide_two box">
					<div class="box">
						<div class="title_bar none">
							<h2 class="g_title_01">신청 제품 확인</span></h2>
						</div>
						<div class="border_box order_list reverse white_box result_right">
							<ul>
								<li>
									<span class="item" style="width:250px;">* <?=$row['ct_option']; ?> * 1개 수선 </span>
									<strong class="result"><?=($laundry_price)?number_format($laundry_price)." 원":"후불"?></strong>
								</li>
								<!-- <li>
									<span class="item">* 택배비</span>
									<strong class="result"><?=number_format($send_cost)?> 원</strong>
								</li>
								<li>
									<span class="item blind"></span>
									<strong class="result bold point" id="ct_tot_price"><?=number_format($laundry_price + ($keep_price * $keep_month) + $send_cost)?> 원</strong>
								</li> -->
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="grid">
				<div class="title_bar">
					<h3 class="g_title_01">신청서</h3>
				</div>
				<div class="border_box">
					<div class="inp_wrap">
						<div class="title count3"><label for="f_01">요청사항</label></div>
						<div class="inp_ele count6">
							<div class="input">
								<textarea name="cust_memo" id="cust_memo" rows="6" cols="20" maxlength="200" placeholder="최대 200자 이내 세탁 시 주요 오염 위치와 주의사항 및 필요사항을 입력하세요."></textarea>
								<p class="ico_import red point_red floatL">신청사항은 주요 세탁이 필요한 부분, 주의 해야 할 부분을 반드시 입력 해 주세요.</p>
								<span class="byte"><span id="byte">0</span>/200</span>
							</div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="title count3"><label for="f_01">첨부파일</label></div>
						<div class="inp_ele count6 r_btn_80">
							<div class="input">
								<input type="text" placeholder="" id="join7_1" title="파일" disabled="" value="">
							</div>
							<button type="button" class="inp_file btn" id="btnFile1" accept="image/*,video/*">파일찾기</button>
							<p class="ico_import red point_red">첨부파일 최대 5개, 동영상의 경우 20mb 이하의 파일만 첨부 가능합니다.</p>
						</div>
					</div>

					<div class="file_list">
						<ul id="file_list">
						</ul>
					</div>

					<? for ($i=0; $i<5; $i++) { ?>
						<input type="file" id="bf_file<?=$i+1 ?>" name="bf_file[]" hidden idx="<?=$i+1 ?>" act="">
					<? } ?>

				</div>
			</div>

			<div class="grid">
				<div class="divide_two box">
					<div class="box">
						<div class="title_bar none">
							<h2 class="g_title_01">고객 정보</h2>
						</div>
						<div class="border_box">
							<div class="inp_wrap">
								<div class="title count3"><label>이름</label></div>
								<div class="inp_ele count6"><div class="input">
									<input type="text" name="od_name" value="<?=get_text($member['mb_name']); ?>" id="od_name" required class="frm_input required" maxlength="20">
								</div></div>

								<input type="hidden" name="od_tel" value="<?=get_text($member['mb_tel']); ?>" id="od_tel" >
								<input type="hidden" name="od_zip" value="<?=$member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
								<input type="hidden" name="od_addr1" value="<?=get_text($member['mb_addr1']) ?>" id="od_addr1" >
								<input type="hidden" name="od_addr2" value="<?=get_text($member['mb_addr2']) ?>" id="od_addr2" >
								<input type="hidden" name="od_addr3" value="<?=get_text($member['mb_addr3']) ?>" id="od_addr3" >
								<input type="hidden" name="od_addr_jibeon" value="<?=get_text($member['mb_addr_jibeon']); ?>">
							</div>

							<div class="inp_wrap">
								<div class="title count3"><label>휴대전화 번호</label></div>
								<div class="inp_ele count6"><div class="input">
									<input type="text" name="od_hp" value="<?=get_text($member['mb_hp']); ?>" id="od_hp" required class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력">
								</div></div>
							</div>
							<div class="inp_wrap">
								<div class="title count3"><label>E-mail</label></div>
								<div class="inp_ele count6"><div class="input">
									<input type="email" name="od_email" value="<?=$member['mb_email']; ?>" id="od_email" required class="frm_input required" maxlength="100">
								</div></div>
							</div>
						</div>
						<div class="info_box gray_box mt15">
							<p class="ico_import red point_red">세탁 신청 안내</p>
							<p class="dep">
								토사/오줌/젖은세탁물/피/동물털/과도한 음식물/과일즙/알수없는 액체 및 직접 제거 시도로 약품처리를 하신 경우에는 세탁 진행이 거부 될수 있습니다.
							</p>
						</div>
					</div>

					<div class="box">
						<div class="title_bar none">
							<h2 class="g_title_01">수거지 정보</h2>
							<span class="chk radio">
								<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
								<label for="ad_sel_addr_same">주문자 정보와 동일</label>
							</span>
						</div>

						<div class="border_box">
					<!-- <div class="inp_wrap">
						<div class="title count3"><label for="od_hope_date">수거일자 선택</label></div>
						<div class="inp_ele count6">
							<div class="input calendar">
								<input type="date" placeholder="" id="od_hope_date" name="od_hope_date">
							</div>
						</div>
					</div> -->

					<div class="inp_wrap">
						<div class="title count3"><label>받는분</label></div>
						<div class="inp_ele count6">
							<div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20"></div>
						</div>
						<input type="hidden" name="ad_subject" id="ad_subject" value="">
					</div>
					<div class="inp_wrap">
						<div class="title count3"><label>휴대전화 번호</label></div>
						<div class="inp_ele count6">
							<div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20"></div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="title count3"><label>연락처</label></div>
						<div class="inp_ele count6">
							<div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20"></div>
						</div>
					</div>

					<div class="inp_wrap">
						<div class="title count9">
							<label for="join7">제품 수거지 주소</label>
							<a href="<?=G5_SHOP_URL ?>/orderaddress.php" id="order_address"><button class="btn gray round floatR"><span class="point_black">배송지 관리</span></button></a>
						</div>
						<div class="inp_ele count9 r_btn_80">
							<div class="input"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6" readonly="readonly"></div>
							<button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
						</div>
					</div>

					<div class="inp_wrap">
						<div class="inp_ele count9 col_r">
							<div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required" readonly="readonly"></div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="inp_ele count9 col_r">
							<div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address">
								<input type="hidden" name="od_b_addr3" id="od_b_addr3" >
								<input type="hidden" name="od_b_addr_jibeon" value=""></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label>배송 메시지</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>

		<div class="grid">
			<div class="btn_group two" id="display_pay_button">
				<a href="javascript:history.go(-1);" ><button type="button" class="btn big border gray"><span>취소</span></button></a>
				<button type="button" onClick="forderform_check(this.form);" class="btn big green"><span>신청</span></button>
			</div>
		</div>

	</div>
</div>

<!-- popup -->
<section class="popup_container layer" style="display: none" id="pricetable">
	<div class="inner_layer" style="top:0px">

		<!-- lnb -->
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>수선 단가표</span></h1>
			<a href="#" class="btn_closed" onclick="$('#pricetable').css('display','none');"><span class="blind">닫기</span></a>
		</div>
		<!-- // lnb -->

		<div class="content shop sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid">
				<div class="gray_box pad15">
					<p>*아래 요금표는 안내를 위한 것으로, 전문 상담사와 통화 후 정확한 요금 안내가 이루어집니다.</p>
					<p>*수선 서비스는 라이프라이크 제품에 한하여 이용이 가능합니다.</p>
				</div>

				<div class="order_title reverse type2">
					<span class="item">구스다운 충전 (100g당)</span>
					<strong class="result"><em class="point">72,000원~</em></strong>
				</div>
				<div class="border_box order_list ">
					<ul>
						<li>
							<span class="item">폴란드산 구스다운 90%</span>
							<strong class="result">119,000원</strong>
						</li>
						<li>
							<span class="item">헝가리산 구스다운 90%</span>
							<strong class="result">72,000원</strong>
						</li>
					</ul>
				</div>

				<div class="order_title reverse type2">
					<span class="item">사이즈 수선(줄임)</span>
					<strong class="result"><em class="point">7,000원~</em></strong>
				</div>
				<div class="border_box order_list reverse ">
					<ul>
						<li>
							<span class="item">이불 커버</span>
							<strong class="result">15,000원</strong>
						</li>
						<li>
							<span class="item">누비 이불 커버</span>
							<strong class="result">20,000원</strong>
						</li>
						<li>
							<span class="item">매트리스 커버</span>
							<strong class="result">10,000원</strong>
						</li>
						<li>
							<span class="item">패드</span>
							<strong class="result">10,000원</strong>
						</li>
						<li>
							<span class="item">차렵 이불</span>
							<strong class="result">15,000원</strong>
						</li>
					</ul>
				</div>

				<div class="order_title reverse type2">
					<span class="item">솜 샤시(누비)</span>
					<strong class="result"><em class="point">10,000원~</em></strong>
				</div>
				<div class="border_box order_list reverse ">
					<ul>
						<li>
							<span class="item">홑겹 이불 커버 → 누비 이불 커버</span>
							<strong class="result">S → 40,000원</strong>
						</li>
						<li>
							<span class="item">홑겹 매트리스 커버 → 누비 매트리스 커버</span>
							<strong class="result">Q → 50,000원</strong>
						</li>
						<li>
							<span class="item">홑겹 베개 커버 → 누비 베개 커버</span>
							<strong class="result">50,000원</strong>
						</li>
					</ul>
				</div>

				<div class="order_title reverse type2">
					<span class="item">밴드 교체</span>
					<strong class="result"><em class="point">8,000원~</em></strong>
				</div>
				<div class="border_box order_list reverse ">
					<ul>
						<li>
							<span class="item">패드(모서리 4면)</span>
							<strong class="result">8,000원</strong>
						</li>
						<li>
							<span class="item">매트리스 커버(양 쪽 2개)</span>
							<strong class="result">12,000원</strong>
						</li>
					</ul>
				</div>

				<div class="order_title reverse type2">
					<span class="item">지퍼 수선</span>
					<strong class="result"><em class="point">5,000원~</em></strong>
				</div>
				<div class="border_box order_list reverse ">
					<ul>
						<li>
							<span class="item">대품(이불, 이불커버)</span>
							<strong class="result">8,000원</strong>
						</li>
						<li>
							<span class="item">베개</span>
							<strong class="result">5,000원</strong>
						</li>
					</ul>
				</div>

				<div class="order_title reverse type2">
					<span class="item">매트리스 커버 수선</span>
					<strong class="result"><em class="point">27,000원~</em></strong>
				</div>
				<div class="border_box order_list reverse ">
					<ul>
						<li>
							<span class="item">프릴단 전체 교체 ~10cm, 20cm 길이 수선</span>
							<strong class="result">27,000원 ~ 32,000원</strong>
						</li>
					</ul>
				</div>

			</div>

			<div class="grid bg_none">
				<div class="toggle inquiry_toggle">
					<div class="toggle_group type2 opened">
						<div class="title">
							<h3 class="tit ellipsis">참조사항</h3>
						</div>
						<div class="cont">
							<div class="white_box info_box">
								<p class="tit">* 베이직라인 이불속통 (중량추가주입)</p>
								<ul class="hyphen">
									<li>원산지별 100g 당 가격</li>
									<li>속통류는 베이직라인만 가능</li>
									<li>원단 덧댐 수선은 별도문의</li>
								</ul>
								<p class="tit">* 사이즈 수선 (줄임)</p>
								<ul class="hyphen">
									<li>지퍼수선이 필요할경우 추가</li>
								</ul>
								<p class="tit">* 사이즈 수선 (늘임)</p>
								<ul class="hyphen">
									<li>정상 런제품 가능하며,<br>원자재 보유제품이어야 함</li>
								</ul>
								<p class="tit">* 매커프릴수선 교체 / 높이연폭</p>
								<ul class="hyphen">
									<li>프릴단 교체(완성 35cm)</li>
									<li>기존끝에서 추가(35+20)</li>
									<li>기존끝에서 추가(35+10)</li>
								</ul>
								<p class="tit">* 랍바수선 (면)</p>
								<ul class="hyphen">
									<li>정상제품 가능하며, 원자재 보유제품이어야 함</li>
								</ul>
								<p class="tit">* 앞, 뒤 판갈이</p>
								<ul class="hyphen">
									<li>패드 판갈이 불가능(짜집기, 덧뎀)</li>
								</ul>
								<p class="tit">* 손샤시</p>
								<ul class="hyphen">
									<li>기존 사용솜 이외의 신청은 불가</li>
								</ul>
								<p class="tit">* 밴드교체</p>
								<ul class="hyphen">
									<li>해당없음</li>
								</ul>
								<p class="tit">* 지퍼수선</p>
								<ul class="hyphen">
									<li>옥매트 ㄷ자형 : 메모리폼 커버</li>
								</ul>
								<p class="tit">* 삥줄(3면)</p>
								<ul class="hyphen">
									<li>해당없음</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- 컨텐츠 종료 -->
		</div>
	</section>
	<!-- //popup -->
	<script src="<?=G5_JS_URL?>/shop.order.js"></script>
	<script>
		var zipcode = "";

		$(function() {
			$("textarea[name='cust_memo']").keyup(function() {
				var str = $(this).val();
				var mx = parseInt($(this).attr("maxlength"));
				document.getElementById('byte').innerHTML = str.length;
				
				if (str.length > mx) {
					$(this).val(str.substr(0, mx));
					return false;
				}
			});

			var fileCount = <?=$i?>;

			$("#btnFile1,#btnFile2,#btnFile3").click(function() {
				var accept = $(this).attr("accept");

				var nextID = "";
				var notadd = true;

				for (var i = 1; i <= 5; i++) {
					var $bf_file = $("#bf_file"+i);
					var act = $bf_file.attr("act");

					if($bf_file.val() == "" && act != "in") {
						$bf_file.attr("accept", accept);
						$bf_file.click();
						notadd = false;
						break;
					}
				}
				if(notadd) alert("최대 사진5매, 동영상 100mb 이하의 파일만 첨부 가능합니다.");
			});

			$("input[name='bf_file[]']").change(function() {

				var idx = $(this).attr("idx");

				var fileName = "";
				if(window.FileReader){
					fileName = $(this)[0].files[0].name;
				} else {
					fileName = $(this)[0].val().split('/').pop().split('\\').pop();
				}

				if (fileName != "") {
					var html = '';
					html += '<li>';
					html += '<span class="name">'+fileName+'</span>';
					html += '<button type="button" class="btn_delete gray" id="file_delete" idx="'+idx+'">';
					html += '<span class="blind">삭제</span>';
					html += '</button>';
					html += '</li>';

					if($("#file_list li").size() > 0) {
						$('#file_list li:last').after(html);
					} else {
						$('#file_list').html(html);
					}
					fileCount++;
					$(this).attr("act", "in");

				} else {
					alert("파일을 선택해주세요");
					return false;
				}
			});

			$(document).on("click", "#file_delete", function() {
				var result = confirm('첨부 파일을 삭제 하시겠습니까?');

				if(result){
					var idx = $(this).attr('idx');
					$("#bf_file_del"+idx).prop('checked',true);

					$("#bf_file"+idx).attr("act", "del");
					$("#bf_file"+idx).replaceWith($("#bf_file"+idx).val('').clone(true));
					$(this).closest("li").remove();
					fileCount--;
				}
			});


			var $cp_btn_el;
			var $cp_row_el;

			$("#od_b_addr2").focus(function() {
				var zip = $("#od_b_zip").val().replace(/[^0-9]/g, "");
				if(zip == "")
					return false;

				var code = String(zip);

				if(zipcode == code)
					return false;

				zipcode = code;
				calculate_sendcost(code);
			});

			$("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_samsungpay").bind("click", function() {
				$("#settle_bank").hide();
				$("#show_req_btn").css("display", "block");
				$("#show_pay_btn").css("display", "none");

				$("input[name='submitChecked']").val($("#od_tot_price").text()+"원 결제");
			});

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val().split(String.fromCharCode(30));

		if (addr[0] == "same") {
			gumae2baesong();
		} else {
			if(addr[0] == "new") {
				for(i=0; i<10; i++) {
					addr[i] = "";
				}
			}

			var f = document.forderform;
			f.od_b_name.value        = addr[0];
			f.od_b_tel.value         = addr[1];
			f.od_b_hp.value          = addr[2];
			f.od_b_zip.value         = addr[3] + addr[4];
			f.od_b_addr1.value       = addr[5];
			f.od_b_addr2.value       = addr[6];
			f.od_b_addr3.value       = addr[7];
			f.od_b_addr_jibeon.value = addr[8];
			f.ad_subject.value       = addr[9];

			var zip1 = addr[3].replace(/[^0-9]/g, "");
			var zip2 = addr[4].replace(/[^0-9]/g, "");

			var code = String(zip1) + String(zip2);

			if(zipcode != code) {
				calculate_sendcost(code);
			}

			ad_subject_change();
		}
	});

	// 배송지목록
	$("#order_address,#order_address1").on("click", function() {
		var url = this.href;
		window.open(url, "win_address", "left=100,top=100,width=650,height=500,scrollbars=1");
		return false;
	});

	$("#ct_keep_month").on("change", function() {
		calculate_total_price();
	});

});

		function fileCheck( file )
		{
		// 사이즈체크
		var maxSize  = 10 * 1024 * 1024
		var fileSize = 0;

	// 브라우저 확인
	var browser=navigator.appName;

	// 익스플로러일 경우
	if (browser=="Microsoft Internet Explorer")
	{
		var oas = new ActiveXObject("Scripting.FileSystemObject");
		fileSize = oas.getFile( file.value ).size;
	}
	// 익스플로러가 아닐경우
	else
	{
		fileSize = file.files[0].size;
	}


	if(fileSize > maxSize)
	{
		alert("첨부파일 사이즈는 10MB 이내로 등록 가능합니다.    ");
		return false;
	}
	return true;
}

function ad_subject_change()
{
	$("#addr").text("["+$("#od_b_zip").val()+"]"+$("#od_b_addr1").val()+" "+$("#od_b_addr2").val());
	$("#spn_od_b_name").text($("#od_b_name").val());
	$("#spn_od_b_tel").text($("#od_b_tel").val());
	$("#spn_od_b_hp").text($("#od_b_hp").val());
	$("#spn_ad_subject").text($("#od_b_name").val());
}

function forderform_check(f)
{
	errmsg = "";
	errfld = "";
	var deffld = "";

	check_field(f.cust_memo, "요청사항을 입력하십시오.");
	check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
	if (typeof(f.od_pwd) != 'undefined')
	{
		clear_field(f.od_pwd);
		if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
			error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
	}
	check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
	//check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
	//check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
	//check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
	//check_field(f.od_zip, "");

	clear_field(f.od_email);
	if(f.od_email.value=='' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
		error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");

	if (typeof(f.od_hope_date) != "undefined")
	{
		clear_field(f.od_hope_date);
		if (!f.od_hope_date.value)
			error_field(f.od_hope_date, "수거 일자를 선택하여 주십시오.");
	}

	check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
	check_field(f.od_b_hp, "받으시는 분 휴대전화 번호를 입력하십시오.");
	check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
	//check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
	check_field(f.od_b_zip, "");


	// 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
	f.od_send_cost.value = parseInt(f.od_send_cost.value);

	if (errmsg)
	{
		alert(errmsg);
		errfld.focus();
		return false;
	}

	f.submit();
}

// 구매자 정보와 동일합니다.
function gumae2baesong() {
	var f = document.forderform;

	f.od_b_name.value = f.od_name.value;
	f.od_b_tel.value  = f.od_tel.value;
	f.od_b_hp.value   = f.od_hp.value;
	f.od_b_zip.value  = f.od_zip.value;
	f.od_b_addr1.value = f.od_addr1.value;
	f.od_b_addr2.value = f.od_addr2.value;
	f.od_b_addr3.value = f.od_addr3.value;
	f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;
	f.ad_subject.value = f.od_name.value;

	//calculate_sendcost(String(f.od_b_zip.value));

	ad_subject_change();
}
</script>
