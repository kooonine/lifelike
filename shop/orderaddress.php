<?
include_once('./_common.php');
if (!$is_member) {
	alert_close('회원이시라면 회원로그인 후 이용해 주십시오.');
}

if ($w == 'd') {
	$sql = " delete from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' and ad_id = '$ad_id' ";
	sql_query($sql);
	goto_url($_SERVER['SCRIPT_NAME']);
}

$sql_common = " from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' ";
if ($w == 'u') {
	$ad_id = $_GET['ad_id'];
	$sql_common .= "and ad_id = {$ad_id}";
}
$sql = " select count(ad_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
	$page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
$sql_common
order by ad_default desc, ad_id desc
limit $from_record, $rows";

$addr_result = sql_query($sql);
if ($w == 'u') {
	$addr_result = sql_fetch($sql);
}
$order_action_url = G5_HTTPS_SHOP_URL . '/orderaddressupdate.php';

if (G5_IS_MOBILE) {
	if ($w == 'u') {
		include_once(G5_MSHOP_PATH . '/orderaddress_edit.php');
	} else {
		include_once(G5_MSHOP_PATH . '/orderaddress.php');
	}
	return;
}

if ($w == 'u') {
	include_once(G5_MSHOP_PATH . '/orderaddress_edit.php');
} else {
	include_once(G5_MSHOP_PATH . '/orderaddress.php');
}
return;

if ($w == 'u') {
	include_once('./orderaddress_edit.php');
	return;
}

$g5['title'] = '배송지 목록';
include_once(G5_PATH . '/head.sub.php');
?>
<div class="">
	<p id="skipNavi"><a href="#container">본문 바로가기</a></p>
	<!-- container -->
	<div id="container">
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>배송지 관리</span></h1>
		</div>

		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<form name="forderaddress" method="post" action="<?= $order_action_url; ?>" autocomplete="off">
				<div class="grid type2" style="">
					<div class="title_bar">
						<h2 class="g_title_02">배송지 목록<a href="<?= G5_SHOP_URL ?>/orderaddress_edit.php"><button type="button" class="category round_none floatR" id="addr_add"><span>배송지 추가</span></button></a></h2>
					</div>
					<?
					if (sql_num_rows($addr_result)) {
						$sep = chr(30);
						for ($i = 0; $row = sql_fetch_array($addr_result); $i++) {
							$addr = $row['ad_name'] . $sep . $row['ad_tel'] . $sep . $row['ad_hp'] . $sep . $row['ad_zip1'] . $sep . $row['ad_zip2'] . $sep . $row['ad_addr1'] . $sep . $row['ad_addr2'] . $sep . $row['ad_addr3'] . $sep . $row['ad_jibeon'] . $sep . $row['ad_subject'];
							$addr = get_text($addr);
					?>
							<div class="order_cont">
								<div class="body">
									<div class="cont">
										<div class="chk_box">
											<span class="chk radio">
												<input type="radio" name="ad_default" value="<?= $row['ad_id']; ?>" id="ad_default<?= $i; ?>" <? $row['ad_default'] ? 'checked="checked"' : ''; ?>>
												<label for="ad_subject<?= $i; ?>" class="sound_only"><?= $row['ad_subject']; ?></label>
											</span>
											<? $row['ad_default'] ? '<span class="category round">기본배송지</span>' : ''; ?>
											<div class="info">
												<strong>(<?= $row['ad_zip1'] . $row['ad_zip2'] ?>)</strong>
												<p><?= print_address($row['ad_addr1'], $row['ad_addr2'], $row['ad_addr3'], $row['ad_jibeon']); ?></p>
											</div>
											<div class="btn_comm">
												<input type="hidden" value="<?= $addr; ?>">
												<button type="button" class="btn gray_line small sel_address"><span>선택</span></button>
												<button type="button" class="btn gray_line small" onclick="location.href='<?= $_SERVER['SCRIPT_NAME']; ?>?w=u&amp;ad_id=<?= $row['ad_id']; ?>'"><span>수정</span></button>
												<button type="button" class="btn gray_line small" onclick="location.href='<?= $_SERVER['SCRIPT_NAME']; ?>?w=d&amp;ad_id=<?= $row['ad_id']; ?>'"><span>삭제</span></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						<? } ?>
				</div>
				<div class="grid">
					<div class="btn_group">
						<button type="submit" class="btn big green"><span>기본 배송지 설정</span></button>
					</div>
				</div>
			<? } else { ?>
				<div class="border_box alignC">
					<p class="sm">등록 된 주소지가 없습니다.<br />아래 “배송지 추가“ 버튼을 선택하신 후 배송지를 등록해 주세요.</p>
				</div>
				<!--
						<div class="grid">
							<div class="btn_group">
								<a href="<?= G5_SHOP_URL ?>/orderaddress_edit.php" ><button type="button" class="btn big green"><span>신규 배송지 등록</span></button></a>
							</div>
						</div>
					-->
			<? } ?>
			</form>
		</div>
		<?= get_paging($config['cf_mobile_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
	</div>
</div>
<script>
	$(function() {
		$(".sel_address").on("click", function() {
			var addr = $(this).siblings("input").val().split(String.fromCharCode(30));
			var f = window.opener.forderform;
			f.od_b_name.value = addr[0];
			f.od_b_tel.value = addr[1];
			f.od_b_hp.value = addr[2];
			f.od_b_zip.value = addr[3] + addr[4];
			f.od_b_addr1.value = addr[5];
			f.od_b_addr2.value = addr[6];
			f.od_b_addr3.value = addr[7];
			f.od_b_addr_jibeon.value = addr[8];
			f.ad_subject.value = addr[9];
			window.opener.ad_subject_change();
			var zip1 = addr[3].replace(/[^0-9]/g, "");
			var zip2 = addr[4].replace(/[^0-9]/g, "");
			if (zip1 != "" && zip2 != "") {
				var code = String(zip1) + String(zip2);
				if (window.opener.zipcode != code) {
					window.opener.zipcode = code;
					window.opener.calculate_sendcost(code);
				}
			}
			window.close();
		});
		$(".del_address").on("click", function() {
			return confirm("배송지 목록을 삭제하시겠습니까?");
		});

		// 전체선택 부분
		$("#chk_all").on("click", function() {
			if ($(this).is(":checked")) {
				$("input[name^='chk[']").attr("checked", true);
			} else {
				$("input[name^='chk[']").attr("checked", false);
			}
		});

		$(".btn_submit").on("click", function() {
			if ($("input[name^='chk[']:checked").length == 0) {
				alert("수정하실 항목을 하나 이상 선택하세요.");
				return false;
			}
		});
	});
</script>
<?
include_once(G5_PATH . '/tail.sub.php');
?>