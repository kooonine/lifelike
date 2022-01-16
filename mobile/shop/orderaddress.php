<?
include_once('./_common.php');
$g5['title'] = '배송지 목록';
?>
<html lang="ko">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<!-- 스타일 -->
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/css/m_common.css" />
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/css/m_ui.css?v=<?=date('Ymdhis')?>" />
	<!-- 스크립트 -->
	<script src="<?=G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="<?=G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?=G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
	<script type="text/javascript">
		function close_address(){
			var win = window.open('', '_self');
			win.close();return false;
		}
	</script>
</head>
<body>
	<form name="forderaddress" method="post" action="<?=$order_action_url; ?>" autocomplete="off">
		<!-- popup -->
		<section class="popup_container layer">
			<div class="inner_layer">
				<div id="lnb" class="header_bar">
					<h1 class="title"><span>배송지 관리</span></h1>
					<a href="#" class="btn_closed" onclick="close_address();"><span class="blind">닫기</span></a>
				</div>
				<div class="content comm sub">
					<!-- 컨텐츠 시작 -->
					<div class="grid bg_none" style="min-height:541px;">
						<div class="title_bar">
							<h2 class="g_title_01">배송지 목록</h2>
							<a href="<?=G5_SHOP_URL?>/orderaddress_edit.php" class="more btn_full arrow_r" ><span>배송지 추가</span></a>
						</div>
						<? if(sql_num_rows($addr_result)){ ?>
							<table class="TBasic">
								<colgroup>
									<col width="10%" />
									<col width="10%" />
									<col width="35%" />
									<col width="13%" />
									<col width="22%" />
								</colgroup>
								<tr>
									<th>기본배송지</th>
									<th>받는분</th>
									<th>주소</th>
									<th>연락처</th>
									<th>비고</th>
								</tr>
								<?
								$sep = chr(30);
								for($i=0; $row=sql_fetch_array($addr_result); $i++) {
									$addr = $row['ad_name'].$sep.$row['ad_tel'].$sep.$row['ad_hp'].$sep.$row['ad_zip1'].$sep.$row['ad_zip2'].$sep.$row['ad_addr1'].$sep.$row['ad_addr2'].$sep.$row['ad_addr3'].$sep.$row['ad_jibeon'].$sep.$row['ad_subject'];
									$addr = get_text($addr);
									?>
									<tr>
										<td class="">
											<span class="chk radio" style="margin-left:10px;">
												<input type="radio" name="ad_default" value="<?=$row['ad_id'];?>" id="ad_default<?=$i;?>" <?=$row['ad_default']?'checked="checked"':'';?>>
												<label for="ad_default<?=$i;?>"><?=$row['ad_subject']; ?></label>
											</span>
										</td>
										<td class="tcenter"><?=$row['ad_name']; ?></td>
										<td>
											(<?=$row['ad_zip1'].$row['ad_zip2']?>)<br/><?=print_address($row['ad_addr1'], $row['ad_addr2'], $row['ad_addr3'], $row['ad_jibeon']); ?>
										</td>
										<td class="tcenter">
											<?=$row['ad_tel']; ?><br/><?=$row['ad_hp']; ?>
										</td>
										<td class="tcenter">
											<input type="hidden" value="<?=$addr; ?>">
											<button type="button" class="btn gray_line small sel_address"><span>선택</span></button>
											<button type="button" class="btn gray_line small" onclick="location.href='<?=$_SERVER['SCRIPT_NAME']; ?>?w=u&amp;ad_id=<?=$row['ad_id']; ?>'"><span>수정</span></button>
											<button type="button" class="btn gray_line small" onclick="location.href='<?=$_SERVER['SCRIPT_NAME']; ?>?w=d&amp;ad_id=<?=$row['ad_id']; ?>'"><span>삭제</span></button>
										</td>
									</tr>
								<? } ?>
							</table>
							<div class="btn_group">
								<button type="submit" class="btn big green"><span>기본 배송지 설정</span></button>
							</div>
						<? } else { ?>
							<div class="border_box">
								<p class="sm">등록 된 주소지가 없습니다.<br>'배송지 추가' 버튼 선택 후 배송지를 등록 해주세요.</p>
							</div>
						<!-- div class="btn_group">
							<a href="<?=G5_SHOP_URL?>/orderaddress_edit.php" ><button type="button" class="btn big green"><span>신규 배송지 등록</span></button></a>
						</div -->
					<? } ?>
				</div>
			</div>
		</div>
	</section>
</form>

<?=get_paging($config['cf_mobile_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
	$(function() {
		$(".sel_address").on("click", function() {
			var addr = $(this).siblings("input").val().split(String.fromCharCode(30));

			var f = window.opener.forderform;
			var f2 = window.opener.fregisterform;
			if(f) {
				f.od_b_name.value        = addr[0];
				f.od_b_tel.value         = addr[1];
				f.od_b_hp.value          = addr[2];
				f.od_b_zip.value         = addr[3] + addr[4];
				f.od_b_addr1.value       = addr[5];
				f.od_b_addr2.value       = addr[6];
				f.od_b_addr3.value       = addr[7];
				f.od_b_addr_jibeon.value = addr[8];
				f.ad_subject.value       = addr[9];

				window.opener.ad_subject_change();

				var zip1 = addr[3].replace(/[^0-9]/g, "");
				var zip2 = addr[4].replace(/[^0-9]/g, "");

				if(zip1 != "" && zip2 != "") {
					var code = String(zip1) + String(zip2);

					if(window.opener.zipcode != code) {
						window.opener.zipcode = code;
						window.opener.calculate_sendcost(code);
					}
				}

				window.close();
			} else if(f2) {
				f2.mb_zip.value         = addr[3] + addr[4];
				f2.mb_addr1.value       = addr[5];
				f2.mb_addr2.value       = addr[6];
				f2.mb_addr3.value       = addr[7];
				f2.mb_addr_jibeon.value = addr[8];

				window.close();
			}
		});

		$(".del_address").on("click", function() {
			return confirm("배송지 목록을 삭제하시겠습니까?");
		});

		// 전체선택 부분
		$("#chk_all").on("click", function() {
			if($(this).is(":checked")) {
				$("input[name^='chk[']").attr("checked", true);
			} else {
				$("input[name^='chk[']").attr("checked", false);
			}
		});
		$(".btn_submit").on("click", function() {
			if($("input[name^='chk[']:checked").length==0 ){
				alert("수정하실 항목을 하나 이상 선택하세요.");
				return false;
			}
		});
	});
</script>
</body>
</html>
