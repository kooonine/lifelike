<?
include_once('./_common.php');

if (G5_IS_MOBILE) {
	include_once(G5_MSHOP_PATH . '/care.php');
	return;
}

$g5['title'] = '케어 신청';
include_once('./_head.php');

// 회원인 경우
if ($is_member) {
	$sql_common = " from lt_shop_order as a
	inner join lt_shop_cart as b
	on a.od_id = b.od_id
	inner join lt_shop_order_item as c
	on b.od_id = c.od_id and b.ct_id = c.ct_id
	where	a.mb_id = '{$member['mb_id']}'
	and 	a.od_type in ('O','R')
	and		a.od_status in ('구매완료','리스중')
	and     (c.rf_serial is not null and c.rf_serial != '')
	and     c.ct_status in ('구매완료','리스중', '')
	and     (c.ct_laundry_use != 0 or c.ct_laundrykeep_use != 0 or c.ct_repair_use != 0) ";

	$sql_common = " from lt_shop_order as a
	inner join lt_shop_cart as b
	on a.od_id = b.od_id
	inner join lt_shop_order_item as c
	on b.od_id = c.od_id and b.ct_id = c.ct_id
	where	a.mb_id = '{$member['mb_id']}'
	and 	a.od_type in ('O','R')
	and		a.od_status in ('구매완료','리스중')
	and     (c.rf_serial is not null and c.rf_serial != '')";

	// 테이블의 전체 레코드수만 얻음
	$sql = " select count(*) as cnt " . $sql_common;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
	// 조건에 맞는 주문서가 없다면

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) {
		$page = 1;
	} // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$limit = " limit $from_record, $rows ";
} else {
	// 그렇지 않다면 로그인으로 가기
	//goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_SHOP_URL.'/care.php'));
	$total_count = 0;
}
?>
<!-- container -->
<div id="container">
	<? if ($total_count > 0) { ?>
		<!-- popup -->
		<section class="popup_container layer">
			<div class="inner_layer">
				<div class="grid">
					<div class="title_bar">
						<h2 class="g_title_01">제품 목록</h2>
					</div>

					<div class="title_bar none">
						<p class="ico_import red point_red">신청 버튼 클릭 시 해당 케어 신청이 가능합니다.</p>
					</div>

					<div style="overflow-y: auto; height: 640px;">
						<?
						$sql = " select c.* ";
						$sql .=  $sql_common;
						$sql .=  "order by b.ct_time desc, c.od_sub_id asc $limit ";
						$result = sql_query($sql);
						for ($i = 0; $row = sql_fetch_array($result); $i++) {
							$image = get_it_image($row['it_id'], 100, 100, '', '', $row['it_name']);
						?>
							<div class="hover_btn">
								<div class="order_cont">
									<div class="body">
										<div style="background:#eaeaea; margin-bottom:15px; padding:5px 10px;">주문상품번호 : <?= $row['od_id'] . $row['od_sub_id'] ?></div>
										<div class="cont">
											<div class="photo"><?= $image ?></div>
											<div class="info">
												<strong><?= $row['it_name'] ?></strong>
												<p><span class="">옵션 : </span> <span class="point_black"><strong class="bold"><?= get_text($row['ct_option']); ?></strong></span></p>
												<? if (((int) $row['ct_free_laundry'] - (int) $row['ct_free_laundry_use'] > $row['ct_free_laundry_use']) || $row['ct_laundry_use']) { ?>
													<p>세탁<span class="mint"><?= (int) $row['ct_free_laundry'] - (int) $row['ct_free_laundry_use']; ?></span>회 무료</p>
												<? } ?>
											</div>
											<div class="btn_comm count3">
												<? if (((int) $row['ct_free_laundry'] - (int) $row['ct_free_laundry_use'] > $row['ct_free_laundry_use']) || $row['ct_laundry_use']) { ?><button class="btn gray_line small" onclick="location.href='./cartupdate.php?sw_direct=1&act=care&od_type=L&ct_id=<?= $row['ct_id'] ?>&od_sub_id=<?= $row['od_sub_id'] ?>';"><span>세탁 신청</span></button><? } ?>
												<? if ($row['ct_laundrykeep_use']) { ?><button class="btn gray_line small" onclick="location.href='./cartupdate.php?sw_direct=1&act=care&od_type=K&ct_id=<?= $row['ct_id'] ?>&od_sub_id=<?= $row['od_sub_id'] ?>';"><span>세탁 보관 신청</span></button><? } ?>
												<? if ($row['ct_repair_use']) { ?><button class="btn gray_line small" onclick="location.href='./cartupdate.php?sw_direct=1&act=care&od_type=S&ct_id=<?= $row['ct_id'] ?>&od_sub_id=<?= $row['od_sub_id'] ?>';"><span>수선 신청</span></button><? } ?>
											</div>
										</div>
									</div>
								</div>
							<? } ?>
							</div>
							<?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
					</div>
					<a href="#" class="btn_closed" onclick="history.back();"><span class="blind">닫기</span></a>
				</div>
		</section>
	<? } else { ?>
		<!-- popup -->
		<section class="popup_container layer">
			<div class="inner_layer">
				<div class="grid">
					<div class="title_bar">
						<h2 class="g_title_01">제품 목록</h2>
					</div>
					<!-- 목록 없을 경우 -->
					<div class="detail_wrap none mt40">
						<div class="photo"><img src="../img/mb/content/shop_noPdt_img.jpg" alt=""></div>
						<p>당신의 일상을 더 선명하게 즐겁게 할 라이프라이크의 케어 서비스 <br>지금 경험해 보세요.</p>
					</div>
					<div class="btn_group">
						<a href="/shop/list.php?ca_id=102010"><button type="button" class="btn big border"><span>리스 서비스 바로 가기</span></button></a>
						<!--
								"케어 서비스 바로 가기" 링크 : 라이프라이크의 리스로 이동
							-->
					</div>
					<!-- // 목록 없을 경우 -->

				</div>
				<a href="#" class="btn_closed" onclick="history.back();"><span class="blind">닫기</span></a>
			</div>
		</section>
		<!-- //popup -->
	<? } ?>

</div>
<?
include_once('./_tail.php');
?>