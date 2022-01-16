<?
if (!defined("_GNUBOARD_")) exit;		// 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);		//다음 주소 js

if (!defined("_ORDERINQUIRY_")) exit;	// 개별 페이지 접근 불가

$sql = " select *, (od_cart_coupon + od_coupon + od_send_coupon) as couponprice from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}'";

if (isset($is_claim) && $is_claim != "") $sql .= " and od_status_claim in ('주문취소','교환','반품','철회','해지') ";
if (isset($is_care) && $is_care != "") $sql .= " and ((od_type = 'R' and od_status = '리스중') or od_type in ('L','K','S')) ";
if (isset($od_status_claim) && $od_status_claim != "") $sql .= " and od_status_claim = '{$od_status_claim}' ";
if (isset($od_type) && $od_type != "") $sql .= " and od_type = '{$od_type}' ";
if (isset($od_stime) && $od_stime != "") $sql .= " and od_time >= '{$od_stime}' ";
if (isset($od_etime) && $od_etime != "") $sql .= " and od_time <= '{$od_etime} 23:59:59' ";

$sql .=  "order by od_time desc $limit ";
$result = sql_query($sql);
for ($i = 0; $od = sql_fetch_array($result); $i++) {
	// 주문상품
	$sql = " select it_name, ct_option, it_id, ct_keep_month, ct_id from {$g5['g5_shop_cart_table']} where od_id = '{$od['od_id']}'
	order by io_type, ct_id limit 1 ";
	$ct = sql_fetch($sql);
	$ct_name = get_text($ct['it_name']) . ' ';

	$image = get_it_image($ct['it_id'], 150, 150, '', '', $ct['it_name']);

	$sql = " select count(*) as cnt from {$g5['g5_shop_cart_table']} where od_id = '{$od['od_id']}' ";
	$ct2 = sql_fetch($sql);
	if ($ct2['cnt'] > 1) {
		$ct_name .= ' 외 ' . ($ct2['cnt'] - 1) . '건';
	}

	$od_status_step = '';
	$order_step = 'step5';

	switch ($od['od_type']) {
		case 'O':
			$od_type_name = '제품';

			$od_status_step .= '<li class="on"><span>결제완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "상품준비중" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>상품준비중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>배송중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>배송완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>구매완료</span></li>';
			break;
		case 'R':
			$od_type_name = '리스';

			$order_step = 'step6';
			$od_status_step .= '<li class="on"><span>계약등록</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "리스완료" || $od['od_status'] == "상품준비중" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "리스중") ? 'class="on"' : '') . '><span>상품준비중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "리스완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "리스중") ? 'class="on"' : '') . '><span>배송중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "리스완료" || $od['od_status'] == "배송완료" || $od['od_status'] == "리스중") ? 'class="on"' : '') . '><span>배송완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "리스완료" || $od['od_status'] == "리스중") ? 'class="on"' : '') . '><span>리스중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "리스완료") ? 'class="on"' : '') . '><span>리스완료</span></li>';
			break;
		case 'L':
			$od_type_name = '세탁';
			$order_step = 'step8';
			$od_status_step = '<li class="on"><span>세탁요청</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수거박스배송" || $od['od_status'] == "박스배송완료" || $od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>박스배송</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "박스배송완료" || $od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>박스배송완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>수거 중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>세탁 수거 완료</span></li>';

			if ($od['od_status_claim'] == "펭귄반려") $od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>세탁반려</span></li>';
			else $od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>세탁 중</span></li>';

			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "배송중" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>배송 중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "세탁완료") ? 'class="on"' : '') . '><span>서비스완료</span></li>';
			break;
		case 'K':
			$od_type_name = '세탁보관';
			$order_step = 'step9';
			$od_status_step = '<li class="on"><span>보관요청</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "수거박스배송" || $od['od_status'] == "박스배송완료" || $od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>박스배송</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "박스배송완료" || $od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>박스배송완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>수거 중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "수거완료" || $od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>세탁 수거 완료</span></li>';

			if ($od['od_status_claim'] == "펭귄반려") {
				$od_status_step .= '<li ' . (($od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>세탁반려</span></li>';
			} else {
				$od_status_step .= '<li ' . (($od['od_status'] == "세탁중" || $od['od_status'] == "세탁반려" || $od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>세탁 중</span></li>';
			}

			$od_status_step .= '<li ' . (($od['od_status'] == "보관중" || $od['od_status'] == "보관완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료") ? 'class="on"' : '') . '><span>보관 중</span></li>';

			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "배송중" || $od['od_status'] == "보관완료") ? 'class="on"' : '') . '><span>배송 중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "보관완료") ? 'class="on"' : '') . '><span>서비스완료</span></li>';
			break;
		case 'S':
			$od_type_name = '수선';
			$order_step = 'step7';
			$od_status_step = '<li class="on"><span>수선요청</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수거중" || $od['od_status'] == "수거완료" || $od['od_status'] == "제품확인" || $od['od_status'] == "수선중" || $od['od_status'] == "배송중" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>수거 중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수거완료" || $od['od_status'] == "제품확인" || $od['od_status'] == "수선중" || $od['od_status'] == "배송중" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>수선 수거 완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "제품확인" || $od['od_status'] == "수선중" || $od['od_status'] == "배송중" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>제품 확인</span></li>';

			if ($od['od_status_claim'] == "고객반려" || $od['od_status_claim'] == "리탠다드반려") {
				$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수선중" || $od['od_status'] == "배송중" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>수선반려</span></li>';
			} else {
				$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수선중" || $od['od_status'] == "배송중" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>수선 중</span></li>';
			}

			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "배송중" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>배송 중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "수선완료") ? 'class="on"' : '') . '><span>서비스완료</span></li>';
			break;
		default:
			$od_type_name = '제품';

			$od_status_step .= '<li class="on"><span>결제완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "상품준비중" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>상품준비중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>배송중</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "배송완료" || $od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>배송완료</span></li>';
			$od_status_step .= '<li ' . (($od['od_status'] == "구매완료") ? 'class="on"' : '') . '><span>구매완료</span></li>';
			break;
	}

	$uid = md5($od['od_id'] . $od['od_time'] . $od['od_ip']);

	$btn_act = '';
	$od_status = $od['od_status'];
	switch ($od['od_status']) {
		case '주문':
			$btn_act .= '';
			break;
		case '세탁신청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>세탁취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>배송지변경</span></button>';
			break;
		case '보관신청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>세탁보관취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>배송지변경</span></button>';
			break;
		case '수선신청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>수선취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>배송지변경</span></button>';
			break;
		case '결제완료':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>주문취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>배송지변경</span></button>';
			break;
		case '상품준비중':
			$btn_act .= '';
			break;
		case '배송중':
			if ($od['od_invoice'] && $od['od_delivery_company']) {
				$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
				for ($i = 0; $i < count($dlcomp); $i++) {
					if (strstr($dlcomp[$i], $od['od_delivery_company'])) {
						list($com, $url, $tel) = explode("^", $dlcomp[$i]);
						break;
					}
				}
				if ($com && $url) {
					$btn_act .= '<a href="' . $url . $od['od_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
				}
			}
			break;
		case '박스배송완료':
		case '수거박스배송':
			if ($od['od_boxsend_invoice']) {
				$url = G5_URL . "/common/tracking.php?invc_co=롯데택배&invc_no=";
				$btn_act .= '<a href="' . $url . $od['od_boxsend_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
			}
			break;
		case '수거중':
			if ($od['od_pickup_invoice'] && $od['od_pickup_delivery_company']) {
				$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
				for ($i = 0; $i < count($dlcomp); $i++) {
					if (strstr($dlcomp[$i], $od['od_pickup_delivery_company'])) {
						list($com, $url, $tel) = explode("^", $dlcomp[$i]);
						break;
					}
				}

				if ($com && $url) {
					$btn_act .= '<a href="' . $url . $od['od_pickup_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
				}
			}
			break;
		case '배송완료':
			if ($od['od_type'] == "R") {
				$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>철회요청</span></button>';
				$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>리스시작하기</span></button>';
			} else if ($od['od_type'] == "O") {
				//$btn_a    ct .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>교환요청</span></button>';
				$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>반품요청</span></button>';
				$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>구매확정</span></button>';
			}
			/*
		else if($od['od_type'] == "L" || $od['od_type'] == "S") {
			$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>구매확정</span></button>';
		}
		*/
			break;
		case '구매완료':
			if ($od['od_type'] == "R" || $od['od_type'] == "O") {
				$review = sql_fetch("select count(*) cnt from lt_shop_item_use where it_id = '{$ct['it_id']}' and mb_id = '{$member['mb_id']}' and ct_id = '{$ct['ct_id']}' ");

				if ($review['cnt'] <= 0) {
					$btn_act .= '<button class="btn gray_line small" it_id="' . $ct['it_id'] . '" ct_id="' . $ct['ct_id'] . '" od_id="' . $od['od_id'] . '"><span>리뷰작성</span></button>';
				} else {
					$btn_act .= '<button class="btn gray_line small" it_id="' . $ct['it_id'] . '" ct_id="' . $ct['ct_id'] . '" od_id="' . $od['od_id'] . '"><span>리뷰보기</span></button>';
				}
			}
			break;
		case '계약등록':
			//$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>계약서작성</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>배송지변경</span></button>';
			//$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>계약수정</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>계약취소</span></button>';
			break;
		case '리스중':
			$ro = sql_fetch("SELECT rt_id FROM lt_shop_rental_order WHERE od_id=" . $od['od_id']);
			$rt_id = $ro['rt_id'];
			$review = sql_fetch("select count(*) cnt from lt_shop_item_use where it_id = '{$ct['it_id']}' and mb_id = '{$member['mb_id']}' ");

			$btn_act .= '<button class="btn gray_line small" od_id="' . $rt_id . '"><span>계약서다운로드</span></button>';

			if ($review['cnt'] <= 0) {
				$btn_act .= '<button class="btn gray_line small" it_id="' . $ct['it_id'] . '" ct_id="' . $ct['ct_id'] . '" od_id="' . $od['od_id'] . '"><span>리뷰작성</span></button>';
			} else {
				$btn_act .= '<button class="btn gray_line small" it_id="' . $ct['it_id'] . '" ct_id="' . $ct['ct_id'] . '" od_id="' . $od['od_id'] . '"><span>리뷰보기</span></button>';
			}

			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>해지신청</span></button>';
			break;
		case '교환요청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>교환철회</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>수거지변경</span></button>';

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>교환신청</span></li>';
			$od_status_step .= '<li><span>수거 중</span></li>';
			$od_status_step .= '<li><span>반품 수거 완료</span></li>';
			$od_status_step .= '<li><span>반품 완료</span></li>';
			break;
		case '교환수거중':
			if ($od['od_invoice'] && $od['od_delivery_company']) {
				$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
				for ($i = 0; $i < count($dlcomp); $i++) {
					if (strstr($dlcomp[$i], $od['od_delivery_company'])) {
						list($com, $url, $tel) = explode("^", $dlcomp[$i]);
						break;
					}
				}

				if ($com && $url) {
					$btn_act .= '<a href="' . $url . $od['od_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
				}
			}

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>반품신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li><span>반품 수거 완료</span></li>';
			$od_status_step .= '<li><span>반품 완료</span></li>';
			break;
		case '반품요청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>반품철회</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>수거지변경</span></button>';

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>반품신청</span></li>';
			$od_status_step .= '<li><span>수거 중</span></li>';
			$od_status_step .= '<li><span>반품 수거 완료</span></li>';
			$od_status_step .= '<li><span>반품 완료</span></li>';
			break;
		case '반품수거중':
			if ($od['od_invoice'] && $od['od_delivery_company']) {
				$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
				for ($i = 0; $i < count($dlcomp); $i++) {
					if (strstr($dlcomp[$i], $od['od_delivery_company'])) {
						list($com, $url, $tel) = explode("^", $dlcomp[$i]);
						break;
					}
				}

				if ($com && $url) {
					$btn_act .= '<a href="' . $url . $od['od_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
				}
			}

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>반품신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li><span>반품 수거 완료</span></li>';
			$od_status_step .= '<li><span>반품 완료</span></li>';
			break;
		case '수거완료':
			if ($od['od_status_claim'] == "반품") {
				$order_step = 'step4';
				$od_status_step = '<li class="on"><span>반품신청</span></li>';
				$od_status_step .= '<li class="on"><span>수거 중</span></li>';
				$od_status_step .= '<li class="on"><span>반품 수거 완료</span></li>';
				$od_status_step .= '<li><span>반품 완료</span></li>';
			} else if ($od['od_status_claim'] == "철회") {
				$order_step = 'step4';
				$od_status_step = '<li class="on"><span>철회신청</span></li>';
				$od_status_step .= '<li class="on"><span>수거 중</span></li>';
				$od_status_step .= '<li class="on"><span>철회 수거 완료</span></li>';
				$od_status_step .= '<li><span>철회 완료</span></li>';
			} else if ($od['od_status_claim'] == "해지") {
				$order_step = 'step4';
				$od_status_step = '<li class="on"><span>해지신청</span></li>';
				$od_status_step .= '<li class="on"><span>수거 중</span></li>';
				$od_status_step .= '<li class="on"><span>해지 수거 완료</span></li>';
				$od_status_step .= '<li><span>해지 완료</span></li>';
			} else {
				if ($od['od_pickup_invoice'] && $od['od_pickup_delivery_company']) {
					$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
					for ($i = 0; $i < count($dlcomp); $i++) {
						if (strstr($dlcomp[$i], $od['od_pickup_delivery_company'])) {
							list($com, $url, $tel) = explode("^", $dlcomp[$i]);
							break;
						}
					}
					if ($com && $url) {
						$btn_act .= '<a href="' . $url . $od['od_pickup_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
					}
				}
			}
			break;
		case '반품완료':

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>반품신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li class="on"><span>반품 수거 완료</span></li>';
			$od_status_step .= '<li class="on"><span>반품 완료</span></li>';
			break;
		case '철회요청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>철회취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>수거지변경</span></button>';

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>철회신청</span></li>';
			$od_status_step .= '<li><span>수거 중</span></li>';
			$od_status_step .= '<li><span>철회 수거 완료</span></li>';
			$od_status_step .= '<li><span>철회 완료</span></li>';
			break;
		case '철회수거중':
			if ($od['od_invoice'] && $od['od_delivery_company']) {
				$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
				for ($i = 0; $i < count($dlcomp); $i++) {
					if (strstr($dlcomp[$i], $od['od_delivery_company'])) {
						list($com, $url, $tel) = explode("^", $dlcomp[$i]);
						break;
					}
				}

				if ($com && $url) {
					$btn_act .= '<a href="' . $url . $od['od_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
				}
			}
			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>철회신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li><span>철회 수거 완료</span></li>';
			$od_status_step .= '<li><span>철회 완료</span></li>';
			break;
		case '철회완료':
			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>철회신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li class="on"><span>철회 수거 완료</span></li>';
			$od_status_step .= '<li class="on"><span>철회 완료</span></li>';
			break;
		case '해지요청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>해지취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>수거지변경</span></button>';

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>해지신청</span></li>';
			$od_status_step .= '<li><span>수거 중</span></li>';
			$od_status_step .= '<li><span>해지 수거 완료</span></li>';
			$od_status_step .= '<li><span>해지 완료</span></li>';
			break;
		case '해지결제요청':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '" uid="' . $uid . '"><span>해지취소</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>수거지변경</span></button>';
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>위약금납부</span></button>';

			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>해지신청</span></li>';
			$od_status_step .= '<li><span>수거 중</span></li>';
			$od_status_step .= '<li><span>해지 수거 완료</span></li>';
			$od_status_step .= '<li><span>해지 완료</span></li>';
			break;
		case '해지수거중':
			if ($od['od_invoice'] && $od['od_delivery_company']) {
				$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
				for ($i = 0; $i < count($dlcomp); $i++) {
					if (strstr($dlcomp[$i], $od['od_delivery_company'])) {
						list($com, $url, $tel) = explode("^", $dlcomp[$i]);
						break;
					}
				}

				if ($com && $url) {
					$btn_act .= '<a href="' . $url . $od['od_invoice'] . '" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
				}
			}
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>위약금영수증</span></button>';
			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>해지신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li><span>해지 수거 완료</span></li>';
			$od_status_step .= '<li><span>해지 완료</span></li>';
			break;
		case '해지완료':
			$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>위약금영수증</span></button>';
			$order_step = 'step4';
			$od_status_step = '<li class="on"><span>해지신청</span></li>';
			$od_status_step .= '<li class="on"><span>수거 중</span></li>';
			$od_status_step .= '<li class="on"><span>해지 수거 완료</span></li>';
			$od_status_step .= '<li class="on"><span>해지 완료</span></li>';
			break;
		case '제품확인':
			if ((int) $od['od_misu'] > 0) {
				$btn_act .= '<button class="btn gray_line small" od_id="' . $od['od_id'] . '"><span>수선비용결제</span></button>';
			}
			break;
		default:
			$btn_act .= '';
			break;
	}

	$od_invoice = '';
	if ($od['od_delivery_company'] && $od['od_invoice']) {
		$od_invoice = ' ' . get_text($od['od_delivery_company']) . ' / ' . get_text($od['od_invoice']) . '';
	}

	/*
	제품 - 상태값
	결제완료/상품준비중/배송 중/배송완료/구매완료
	*/
?>
	<div class="order_cont">
		<div class="head">
			<span class="category round_green"><?= $od_type_name; ?></span>
			<?
			if ($od['od_status_claim'] == '반품') echo "<span class='category round_black black'>반품</span>";
			if ($od['od_status_claim'] == '교환') echo "<span class='category round_black black'>교환</span>";
			if ($od['od_status_claim'] == '철회') echo "<span class='category round_black black'>철회</span>";
			if ($od['od_status_claim'] == '주문취소') echo "<span class='category round_black black'>취소</span>";

			if ($od['od_status'] == '리스중') echo "<span class='category round_black black'>리스중</span>";
			if ($od['od_status'] == '리스종료') echo "<span class='category round_black black'>리스종료</span>";
			if ($od['od_status_claim'] == '해지') echo "<span class='category round_black black'>해지</span>";
			?>
			<span class="order_number">주문번호 : <strong><a href="<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?= $od['od_id']; ?>&amp;uid=<?= $uid; ?>"><?= $od['od_id']; ?></a></strong></span>
			<a href="<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?= $od['od_id']; ?>&amp;uid=<?= $uid; ?>" class="arrow_r_gray floatR">상세보기</a>
		</div>
		<div class="body">
			<ul class="order_step <?= $order_step ?>">
				<?= $od_status_step ?>
			</ul>
			<? if ($od['od_type'] == "O") { ?>
				<div class="cont right_cont">
					<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= $image; ?></a></div>
					<div class="info">
						<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
						<p><span class="txt">옵션</span>
							<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
						</p>
						<p><span class="txt">주문일</span>
							<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
						</p>
					</div>

					<? if ($btn_act != '') { ?>
						<div class="button_item" id="orderinquiry_btn">
							<?= $btn_act; ?>
						</div>
					<? } ?>
					<div class="pay_item">
						결제 금액<span class="amount"><strong><?= display_price($od['od_receipt_price']); ?> 원</strong></span>
					</div>
				</div>
			<? } else if ($od['od_type'] == "R") { ?>
				<div class="cont right_cont">
					<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= $image; ?></a></div>
					<div class="info">
						<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
						<p><span class="txt">옵션</span>
							<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong>
								/ 계약기간<strong class="bold"><?= number_format($od['rt_month']); ?>개월</strong>
							</span>
						</p>
						<p><span class="txt">계약일</span>
							<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
						</p>
					</div>

					<? if ($btn_act != '') { ?>
						<div class="button_item" id="orderinquiry_btn">
							<?= $btn_act; ?>
						</div>
					<? } ?>
					<div class="pay_item">
						리스 금액<span class="amount"><strong><?= display_price($od['rt_rental_price']); ?> 원</strong></span>
					</div>
				</div>
			<? } else if ($od['od_type'] == "L") { ?>
				<div class="cont right_cont">
					<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= $image; ?></a></div>
					<div class="info">
						<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
						<p><span class="txt">옵션</span>
							<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
						</p>
						<p><span class="txt">신청일</span>
							<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
						</p>
					</div>

					<? if ($btn_act != '') { ?>
						<div class="button_item" id="orderinquiry_btn">
							<?= $btn_act; ?>
						</div>
					<? } ?>
					<div class="pay_item">
						결제 금액<span class="amount"><strong><?= display_price($od['od_receipt_price']); ?> 원</strong></span>
					</div>
				</div>
			<? } else if ($od['od_type'] == "K") { ?>
				<div class="cont right_cont">
					<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= $image; ?></a></div>
					<div class="info">
						<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
						<p><span class="txt">옵션</span>
							<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
						</p>
						<p><span class="txt">신청일</span>
							<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
						</p>
						<p><span class="txt">보관 기간</span>
							<span class="point_black"><strong class="bold"><?= $ct['ct_keep_month']; ?>개월</strong></span>
						</p>
					</div>

					<? if ($btn_act != '') { ?>
						<div class="button_item" id="orderinquiry_btn">
							<?= $btn_act; ?>
						</div>
					<? } ?>
					<div class="pay_item">
						결제 금액<span class="amount"><strong><?= display_price($od['od_receipt_price']); ?> 원</strong></span>
					</div>
				</div>
			<? } else if ($od['od_type'] == "S") { ?>
				<?
				$tot_price = (int) $od['od_cart_price'] + (int) $od['od_send_cost'] + (int) $od['od_send_cost2'];
				?>
				<div class="cont right_cont">
					<div class="photo"><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= $image; ?></a></div>
					<div class="info">
						<strong><a href="./item.php?it_id=<?= $ct['it_id']; ?>"><?= stripslashes($ct_name); ?></a></strong>
						<p><span class="txt">옵션</span>
							<span class="point_black"><strong class="bold"><?= get_text($ct['ct_option']); ?></strong></span>
						</p>
						<p><span class="txt">주문일</span>
							<span class="point_black"><strong class="bold"><?= substr($od['od_time'], 0, 10); ?></strong></span>
						</p>
					</div>

					<? if ($btn_act != '') { ?>
						<div class="button_item" id="orderinquiry_btn">
							<?= $btn_act; ?>
						</div>
					<? } ?>
					<div class="pay_item">
						결제 금액<span class="amount"><strong><?= ($od['od_cart_price']) ? display_price($tot_price) . " 원" : "후불"; ?></strong></span>
					</div>
				</div>
			<? } ?>
		</div>
	</div>
<? } ?>
<?
if ($i == 0) {
	echo '<div class="none-item">주문 내역이 없습니다.</div>';
}
?>
<section class="popup_container layer" id="od_review_select" hidden it_id="" ct_id="">
	<div class="inner_layer" style="top:10%">
		<!-- lnb -->
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>리뷰 작성 유형 선택</span></h1>
		</div>
		<!-- //lnb -->
		<div class="content sub">
			<div class="grid cont">
				<div class="list">
					<ul class="type1 pad">
						<li><a href="#" onclick="location.href='<?= G5_SHOP_URL ?>/itemuseform.php?mode=txt&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">일반 리뷰</span></a></li>
						<li><a href="#" onclick="location.href='<?= G5_SHOP_URL ?>/itemuseform.php?mode=img&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">프리미엄 리뷰</span></a></li>
					</ul>
				</div>
			</div>
		</div>
		<a href="#" class="btn_closed btn_close" onclick="$('#od_review_select').prop('hidden', true);"><span class="blind">닫기</span></a>
	</div>
</section>

<form method="post" action="./orderinquirychange.php" id="orderinquirychange_form" name="orderinquirychange_form">
	<input type="hidden" name="act" value="">
	<input type="hidden" name="od_id" value="">
	<input type="hidden" name="token" value="">
	<input type="hidden" name="uid" value="">
</form>
<script>
	$(function() {
		if ($.fn.orderinquiry_btn_click == null) {
			$.fn.orderinquiry_btn_click = function() {
				var mode = $(this).text();
				var od_id = $(this).attr("od_id");
				switch (mode) {
					case "세탁취소":
					case "세탁보관취소":
					case "수선취소":
					case "주문취소":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderinquirycancelform.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "수거지변경":
					case "배송지변경":
						$.post(
							"./orderinquiry.deliverychangeform.php", {
								od_id: od_id,
								uid: uid
							},
							function(data) {
								$("#dvOrderinquiryPopup").html(data);
							}
						);
						break;
					case "배송조회":
						var href = $(this).closest("a").attr("href");
						if (href.indexOf("<?= G5_URL ?>") >= 0) {
							$.post(href, {
									od_id: od_id
								},
								function(data) {
									$("#dvOrderinquiryPopup").html(data);
								}
							);
							return false;
						}
						break;
					case "교환요청":
						if (confirm("교환을 요청 하시겠습니까? 교환 시 사유에 따라 배송료가 발생 될 수 있습니다.")) {
							var uid = $(this).attr("uid");
							location.href = "<?= G5_SHOP_URL; ?>/orderinquirychangeform.php?od_id=" + od_id + "&act=change&uid=" + uid;
						}
						break;
					case "교환철회":
						if (confirm("교환을 철회 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("교환철회");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "반품요청":
						if (confirm("반품을 요청 하시겠습니까? 반품 시 사유에 따라 배송료가 발생 될 수 있습니다.")) {
							var uid = $(this).attr("uid");
							location.href = "<?= G5_SHOP_URL; ?>/orderinquiryreturnform.php?od_id=" + od_id + "&act=return&uid=" + uid;
						}
						break;
					case "반품철회":
						if (confirm("반품을 철회 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("반품철회");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "철회요청":
						if (confirm("철회를 요청 하시겠습니까? 철회 시 사유에 따라 배송료가 발생 될 수 있습니다.")) {
							var uid = $(this).attr("uid");
							location.href = "<?= G5_SHOP_URL; ?>/orderinquiryreturnform.php?od_id=" + od_id + "&act=return&uid=" + uid;
						}
						break;
					case "철회취소":
						if (confirm("철회요청을 취소 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("철회취소");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "해지신청":
						$.post(
							"./orderinquiry.contractout.php", {
								od_id: od_id,
								uid: uid
							},
							function(data) {
								$("#dvOrderinquiryPopup").html(data);
							}
						);
						break;
					case "해지취소":
						if (confirm("해지신청을 취소 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("해지취소");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "위약금납부":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderform.out.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "위약금영수증":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderform.out2.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "구매확정":
						if (confirm("구매확정 시 반품 및 교환이 불가합니다. 확정 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("구매확정");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "리뷰작성":
						/*var it_id = $(this).attr("it_id");
						var ct_id = $(this).attr("ct_id");
						$('#od_review_select').attr("it_id",it_id);
						$('#od_review_select').attr("ct_id",ct_id);
						$('#od_review_select').prop('hidden', false);*/

						location.href = "<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=" + od_id;
						break;
					case "리뷰보기":
						//var it_id = $(this).attr("it_id");
						//location.href="<?= G5_SHOP_URL; ?>/item.php?it_id="+it_id+"#review";
						location.href = "<?= G5_SHOP_URL; ?>/orderinquiryview.php?od_id=" + od_id;
						break;
					case "계약취소":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderinquirycancelform.php?od_id=" + od_id + "&uid=" + uid;
						break;
					case "리스시작하기":
						if (confirm("리스를 시작 하시겠습니까?")) {
							var uid = $(this).attr("uid");

							$("form[name=orderinquirychange_form] input[name=act]").val("리스시작하기");
							$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
							$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
							$("form[name=orderinquirychange_form]").submit();
						}
						break;
					case "수선비용결제":
						var uid = $(this).attr("uid");
						location.href = "<?= G5_SHOP_URL; ?>/orderform2.php?od_id=" + od_id;
						break;
					case "계약서다운로드":
						url = "<?= G5_SHOP_URL; ?>/orderinquiryview.rental.php?od_id=" + od_id;
						window.open(url, "rentalpdf", "left=100,top=100,width=800,height=600,scrollbars=0");
						break;
				}
			}

			$(document).on("click", "#orderinquiry_btn button", $.fn.orderinquiry_btn_click);
		};
	});
</script>
<div id="dvOrderinquiryPopup"></div>