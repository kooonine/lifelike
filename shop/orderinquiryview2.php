<?
include_once('./_common.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

if( isset($_GET['ini_noti']) && !isset($_GET['uid']) ){
	goto_url(G5_SHOP_URL.'/orderinquiry.php');
}

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

if (!$is_member) {
	if (get_session('ss_orderview_uid') != $_GET['uid'])
		alert("직접 링크로는 주문서 조회가 불가합니다.\\n\\n주문조회 화면을 통하여 조회하시기 바랍니다.", G5_SHOP_URL);
}

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin){
	$sql .= " and mb_id = '{$member['mb_id']}' ";
}
$od = sql_fetch($sql);

if(!$is_member && $od_id){
	alert("로그인이 필요한 서비스입니다.", G5_HTTPS_BBS_URL.'/login.php?url='.urlencode(G5_HTTPS_SHOP_URL.'/orderinquiryview.php?od_id='.$od_id), false);
	return;
} else if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
	alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

	// 결제방법
$settle_case = $od['od_settle_case'];
$order_step = 'step5';
$od_status_step = '';

switch($od['od_type']) {
	case 'O':
	if (G5_IS_MOBILE) {
		require_once G5_MSHOP_PATH.'/settle_lg.inc.php';
	} else {
		require_once G5_SHOP_PATH.'/settle_lg.inc.php';
	}
	$od_type_name = '상품 정보';

	$od_status_step .= '<li class="on"><span>결제완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "상품준비중" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료")?'class="on"':'').'><span>상품준비중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료")?'class="on"':'').'><span>배송중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" || $od['od_status'] == "구매완료")?'class="on"':'').'><span>배송완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "구매완료")?'class="on"':'').'><span>구매완료</span></li>';
	break;
	case 'R':
	if (G5_IS_MOBILE) {
		require_once G5_MSHOP_PATH.'/settle_lg2.inc.php';
	} else {
		require_once G5_SHOP_PATH.'/settle_lg2.inc.php';
	}
	$od_type_name = '리스';

	$order_step = 'step6';
	$od_status_step .= '<li class="on"><span>계약등록</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "리스완료" || $od['od_status'] == "상품준비중" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "리스중")?'class="on"':'').'><span>상품준비중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "리스완료" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "리스중")?'class="on"':'').'><span>배송중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "리스완료" || $od['od_status'] == "배송완료" || $od['od_status'] == "리스중")?'class="on"':'').'><span>배송완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "리스완료" || $od['od_status'] == "리스중")?'class="on"':'').'><span>리스중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "리스완료")?'class="on"':'').'><span>리스완료</span></li>';
	break;
	case 'L':
	if (G5_IS_MOBILE) {
		require_once G5_MSHOP_PATH.'/settle_lg3.inc.php';
	} else {
		require_once G5_SHOP_PATH.'/settle_lg3.inc.php';
	}
	$od_type_name = '세탁';
	$order_step = 'step8';
	$od_status_step = '<li class="on"><span>세탁요청</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수거박스배송"||$od['od_status'] == "박스배송완료"||$od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>박스배송</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "박스배송완료"||$od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>박스배송완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>수거 중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>세탁 수거 완료</span></li>';

	if($od['od_status_claim'] == "펭귄반려"){
		$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>세탁반려</span></li>';
	} else {
		$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>세탁 중</span></li>';
	}

	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "배송중"||$od['od_status'] == "세탁완료")?'class="on"':'').'><span>배송 중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" || $od['od_status'] == "세탁완료")?'class="on"':'').'><span>서비스완료</span></li>';
	break;
	case 'K':
	if (G5_IS_MOBILE) {
		require_once G5_MSHOP_PATH.'/settle_lg3.inc.php';
	} else {
		require_once G5_SHOP_PATH.'/settle_lg3.inc.php';
	}
	$od_type_name = '세탁보관';
	$order_step = 'step9';
	$od_status_step = '<li class="on"><span>보관요청</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "수거박스배송"||$od['od_status'] == "박스배송완료"||$od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>박스배송</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "박스배송완료"||$od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>박스배송완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>수거 중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "수거완료"||$od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>세탁 수거 완료</span></li>';

	if($od['od_status_claim'] == "펭귄반려"){
		$od_status_step .= '<li '.(($od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>세탁반려</span></li>';
	} else {
		$od_status_step .= '<li '.(($od['od_status'] == "세탁중"||$od['od_status'] == "세탁반려"||$od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>세탁 중</span></li>';
	}

	$od_status_step .= '<li '.(($od['od_status'] == "보관중"||$od['od_status'] == "보관완료"||$od['od_status'] == "배송중"||$od['od_status'] == "배송완료")?'class="on"':'').'><span>보관 중</span></li>';

	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "배송중"||$od['od_status'] == "보관완료")?'class="on"':'').'><span>배송 중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" || $od['od_status'] == "보관완료")?'class="on"':'').'><span>서비스완료</span></li>';
	break;
	case 'S':
	if (G5_IS_MOBILE) {
		require_once G5_MSHOP_PATH.'/settle_lg3.inc.php';
	} else {
		require_once G5_SHOP_PATH.'/settle_lg3.inc.php';
	}
	$od_type_name = '수선';
	$order_step = 'step7';
	$od_status_step = '<li class="on"><span>수선요청</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수거중"||$od['od_status'] == "수거완료"||$od['od_status'] == "상품 정보확인"||$od['od_status'] == "수선중"||$od['od_status'] == "배송중"||$od['od_status'] == "수선완료")?'class="on"':'').'><span>수거 중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수거완료"||$od['od_status'] == "상품 정보확인"||$od['od_status'] == "수선중"||$od['od_status'] == "배송중"||$od['od_status'] == "수선완료")?'class="on"':'').'><span>수선 수거 완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "상품 정보확인"||$od['od_status'] == "수선중"||$od['od_status'] == "배송중"||$od['od_status'] == "수선완료")?'class="on"':'').'><span>상품 정보확인</span></li>';

	if($od['od_status_claim'] == "고객반려" || $od['od_status_claim'] == "리탠다드반려"){
		$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수선중"||$od['od_status'] == "배송중"||$od['od_status'] == "수선완료")?'class="on"':'').'><span>수선반려</span></li>';
	} else {
		$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수선중"||$od['od_status'] == "배송중"||$od['od_status'] == "수선완료")?'class="on"':'').'><span>수선 중</span></li>';
	}

	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "배송중"||$od['od_status'] == "수선완료")?'class="on"':'').'><span>배송 중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" ||$od['od_status'] == "수선완료")?'class="on"':'').'><span>서비스완료</span></li>';
	break;
	default:
	$od_type_name = '상품 정보';
	$od_status_step .= '<li class="on"><span>결제완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "상품준비중" || $od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료")?'class="on"':'').'><span>상품준비중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송중" || $od['od_status'] == "배송완료" || $od['od_status'] == "구매완료")?'class="on"':'').'><span>배송중</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "배송완료" || $od['od_status'] == "구매완료")?'class="on"':'').'><span>배송완료</span></li>';
	$od_status_step .= '<li '.(($od['od_status'] == "구매완료")?'class="on"':'').'><span>구매완료</span></li>';
	break;
}
$od_status = $od['od_status'];
$uid = md5($od['od_id'].$od['od_time'].$od['od_ip']);

$btn_act = '';
switch($od_status) {
	case '주문':
	$btn_act .= '';
	break;
	case '세탁신청':
	case '보관신청':
	case '수선신청':
	case '결제완료':
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>주문취소</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>배송지변경</span></button>';
	break;
	case '상품준비중':
	$btn_act .= '';
	break;
	case '배송중':
	if ($od['od_invoice'] && $od['od_delivery_company']){
		$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
		for($i=0; $i<count($dlcomp); $i++) {
			if(strstr($dlcomp[$i], $od['od_delivery_company'])) {
				list($com, $url, $tel) = explode("^", $dlcomp[$i]);
				break;
			}
		}

		if($com && $url) {
			$btn_act .= '<a href="'.$url.$od['od_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
		}
	}
	break;
	case '박스배송완료':
	case '수거박스배송':
	if ($od['od_boxsend_invoice']){
		$url = G5_URL."/common/tracking.php?invc_co=롯데택배&invc_no=";
		$btn_act .= '<a href="'.$url.$od['od_boxsend_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
	}
	break;
	case '수거중':
	if ($od['od_pickup_invoice'] && $od['od_pickup_delivery_company']){
		$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
		for($i=0; $i<count($dlcomp); $i++) {
			if(strstr($dlcomp[$i], $od['od_pickup_delivery_company'])) {
				list($com, $url, $tel) = explode("^", $dlcomp[$i]);
				break;
			}
		}

		if($com && $url) {
			$btn_act .= '<a href="'.$url.$od['od_pickup_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
		}
	}
	break;
	case '배송완료':
	if($od['od_type'] == "R") {
		$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>철회요청</span></button>';
		$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>리스시작하기</span></button>';
	} else if($od['od_type'] == "O") {
				//$btn_a    ct .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>교환요청</span></button>';
		$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>반품요청</span></button>';
		$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>구매확정</span></button>';
	} else if($od['od_type'] == "L" || $od['od_type'] == "S") {
		$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>구매확정</span></button>';
	}
	break;
	case '구매완료':
	break;
	case '계약등록':
			//$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>계약서작성</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>배송지변경</span></button>';
			//$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>계약수정</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>계약취소</span></button>';
	break;
	case '리스중':
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>계약서다운로드</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>해지신청</span></button>';
	break;
	case '반품요청':
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>반품철회</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>수거지변경</span></button>';

	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>반품신청</span></li>';
	$od_status_step .= '<li><span>수거 중</span></li>';
	$od_status_step .= '<li><span>반품 수거 완료</span></li>';
	$od_status_step .= '<li><span>반품 완료</span></li>';
	break;
	case '반품수거중':
	if ($od['od_invoice'] && $od['od_delivery_company']){
		$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
		for($i=0; $i<count($dlcomp); $i++) {
			if(strstr($dlcomp[$i], $od['od_delivery_company'])) {
				list($com, $url, $tel) = explode("^", $dlcomp[$i]);
				break;
			}
		}

		if($com && $url) {
			$btn_act .= '<a href="'.$url.$od['od_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
		}
	}
	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>반품신청</span></li>';
	$od_status_step .= '<li class="on"><span>수거 중</span></li>';
	$od_status_step .= '<li><span>반품 수거 완료</span></li>';
	$od_status_step .= '<li><span>반품 완료</span></li>';
	break;
	case '수거완료':
	if($od['od_status_claim'] == "반품") {
		$order_step = 'step4';
		$od_status_step = '<li class="on"><span>반품신청</span></li>';
		$od_status_step .= '<li class="on"><span>수거 중</span></li>';
		$od_status_step .= '<li class="on"><span>반품 수거 완료</span></li>';
		$od_status_step .= '<li><span>반품 완료</span></li>';
	} else if($od['od_status_claim'] == "철회") {
		$order_step = 'step4';
		$od_status_step = '<li class="on"><span>철회신청</span></li>';
		$od_status_step .= '<li class="on"><span>수거 중</span></li>';
		$od_status_step .= '<li class="on"><span>철회 수거 완료</span></li>';
		$od_status_step .= '<li><span>철회 완료</span></li>';
	} else {
		if ($od['od_pickup_invoice'] && $od['od_pickup_delivery_company']){
			$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
			for($i=0; $i<count($dlcomp); $i++) {
				if(strstr($dlcomp[$i], $od['od_pickup_delivery_company'])) {
					list($com, $url, $tel) = explode("^", $dlcomp[$i]);
					break;
				}
			}
			if($com && $url) {
				$btn_act .= '<a href="'.$url.$od['od_pickup_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
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
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>철회취소</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>수거지변경</span></button>';

	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>철회신청</span></li>';
	$od_status_step .= '<li><span>수거 중</span></li>';
	$od_status_step .= '<li><span>철회 수거 완료</span></li>';
	$od_status_step .= '<li><span>철회 완료</span></li>';
	break;
	case '철회수거중':
	if ($od['od_invoice'] && $od['od_delivery_company']){
		$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
		for($i=0; $i<count($dlcomp); $i++) {
			if(strstr($dlcomp[$i], $od['od_delivery_company'])) {
				list($com, $url, $tel) = explode("^", $dlcomp[$i]);
				break;
			}
		}
		if($com && $url) {
			$btn_act .= '<a href="'.$url.$od['od_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
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
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>해지취소</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>수거지변경</span></button>';

	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>해지신청</span></li>';
	$od_status_step .= '<li><span>수거 중</span></li>';
	$od_status_step .= '<li><span>해지 수거 완료</span></li>';
	$od_status_step .= '<li><span>해지 완료</span></li>';
	break;
	case '해지결제요청':
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'" uid="'.$uid.'"><span>해지취소</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>수거지변경</span></button>';
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>위약금납부</span></button>';

	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>해지신청</span></li>';
	$od_status_step .= '<li><span>수거 중</span></li>';
	$od_status_step .= '<li><span>해지 수거 완료</span></li>';
	$od_status_step .= '<li><span>해지 완료</span></li>';
	break;
	case '해지수거중':
	if ($od['od_invoice'] && $od['od_delivery_company']){
		$dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
		for($i=0; $i<count($dlcomp); $i++) {
			if(strstr($dlcomp[$i], $od['od_delivery_company'])) {
				list($com, $url, $tel) = explode("^", $dlcomp[$i]);
				break;
			}
		}

		if($com && $url) {
			$btn_act .= '<a href="'.$url.$od['od_invoice'].'" target="_blank" class="btn_invoice"><button class="btn gray_line small"><span>배송조회</span></button></a>';
		}
	}
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>위약금영수증</span></button>';

	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>해지신청</span></li>';
	$od_status_step .= '<li class="on"><span>수거 중</span></li>';
	$od_status_step .= '<li><span>해지 수거 완료</span></li>';
	$od_status_step .= '<li><span>해지 완료</span></li>';
	break;
	case '해지완료':
	$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>위약금영수증</span></button>';
	$order_step = 'step4';
	$od_status_step = '<li class="on"><span>해지신청</span></li>';
	$od_status_step .= '<li class="on"><span>수거 중</span></li>';
	$od_status_step .= '<li class="on"><span>해지 수거 완료</span></li>';
	$od_status_step .= '<li class="on"><span>해지 완료</span></li>';
	break;
	case '상품 정보확인':
	if((int)$od['od_misu'] > 0){
		$btn_act .= '<button class="btn gray_line small" od_id="'.$od['od_id'].'"><span>수선비용결제</span></button>';
	}
	break;
	default:
	$btn_act .= '';
	break;
}

if (G5_IS_MOBILE) {
	include_once(G5_MSHOP_PATH.'/orderinquiryview.php');
	return;
}

	// 테마에 orderinquiryview.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
	$theme_inquiryview_file = G5_THEME_SHOP_PATH.'/orderinquiryview.php';
	if(is_file($theme_inquiryview_file)) {
		include_once($theme_inquiryview_file);
		return;
		unset($theme_inquiryview_file);
	}
}

$g5['title'] = '주문상세내역';
$title = "주문상세내역";
include_once('./_head.php');

	// LG 현금영수증 JS
if($od['od_pg'] == 'lg') {
	if($default['de_card_test'] && $od['od_type'] != 'R') {
		echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
	} else {
		echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
	}
}
?>

<!-- container -->
<div id="container">
	<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid cont">
			<div class="title_bar none padNone bold">
				주문 정보
			</div>
			<div class="grid">
				<div class="order_cont" style="margin-top:0px;">
					<div class="head">
						<span class="category round_green"><?=$od_type_name; ?></span>
						<?
						if($od['od_status_claim'] == '반품') echo "<span class='category round_black black'>반품</span>";
						if($od['od_status_claim'] == '교환') echo "<span class='category round_black black'>교환</span>";
						if($od['od_status_claim'] == '철회') echo "<span class='category round_black black'>철회</span>";
						if($od['od_status_claim'] == '주문취소') echo "<span class='category round_black black'>취소</span>";

						if($od['od_status'] == '리스중') echo "<span class='category round_black black'>리스중</span>";
						if($od['od_status'] == '리스종료') echo "<span class='category round_black black'>리스종료</span>";
						if($od['od_status_claim'] == '해지') echo "<span class='category round_black black'>해지</span>";
						?>
						<span class="order_number">주문번호 : <strong><?=$od_id; ?></strong></span>
					</div>

					<div class="body">
						<ul class="order_step <?=$order_step?>">
							<?=$od_status_step ?>
						</ul>
						<table class="TBasic">
							<colgroup>
								<col width="10%" />
								<col width="30%" />
								<col width="10%" />
								<col width="10%" />
								<col width="10%" />
								<col width="10%" />
								<col width="10%" />
								<col width="10%" />
							</colgroup>
							<thead>
								<tr>
									<th>사진</th>
									<th>상품정보</th>
									<th><?=$od['od_type'] == "R"?'리스기간':'배송비'?></th>
									<th>수량</th>
									<th><?=$od['od_type'] == "R"?'이용요금(월)':'상품금액'?></th>
									<th><?=$od['od_type'] == "R"?'전체금액':'결제금액'?></th>
									<th>상태</th>
									<th>리뷰</th>
								</tr>
							</thead>
							<tbody>
								<?
								$sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type, ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price from {$g5['g5_shop_cart_table']} where od_id = '$od_id' order by ct_id ";
								$result = sql_query($sql);
								while($row=sql_fetch_array($result)){
									$image = get_it_image($row['it_id'], 80, 80, '', '', $row['it_name']);
									?>
									<tr>
										<td class="tcenter"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></td>
										<td class="">
											<span class="bold"><a href="./item.php?it_id=<?=$row['it_id']; ?>"><?=stripslashes($row['it_name']); ?></a></span>
											<ul class="disc">
												<?if($row['ct_option']){?>
													<li>
														<span class="bold">옵션</span> : <?=get_text($row['ct_option']); ?>
														<?if($row['io_price']>0){?>
															(옵션금액 + <?=number_format($row['io_price']);?>원)
														<? } ?>
													</li>
												<? } ?>
												<li><span class="bold">계약일</span> : <?=substr($od['od_time'],0,10) ?></li>
											</ul>
										</td>
										<td class="tcenter">
											<? if($od['od_type'] == "R") {?>
												<?if($row['ct_item_rental_month'] > 0){?>
													<?=number_format($row['ct_item_rental_month']);?>개월<br/>
													(<?=number_format($od['rt_payment_count']); ?></span>회차)
												<? } ?>
											<? } else { ?>
												<?
												if($row['ct_send_cost'] > 0){
													echo number_format($row['ct_send_cost']);
												} else {
													echo "무료배송";
												}
												?>
											<? } ?>
										</td>
										<td class="tcenter"><?=number_format($row['ct_qty']);?>개</td>
										<td class="tcenter">
											<? if($od['od_type'] == "O") {?>
												<?=number_format($row['ct_price'])?> 원<br/>
												<?if($row['io_price']>0){?>
													(옵션금액 + <?=number_format($row['io_price']);?>원)
												<? } ?>
											<? } else if($od['od_type'] == "R"){ ?>
												<?=number_format($row['ct_rental_price'])?> 원<br/>
												<?if($row['io_price']>0){?>
													(옵션금액 + <?=number_format($row['io_price']);?>원)
												<? } ?>
											<? } else if($od['od_type'] == "L" || $od['od_type'] == "K"){ ?>
												<?=number_format($row['ct_receipt_price'])?>원
											<? } else if($od['od_type'] == "S"){ ?>
												<?=($od['od_cart_price'])?number_format($od['od_cart_price']+$od['od_send_cost']+$od['od_send_cost2'])."원":"후불"; ?>
											<? } else { ?>
												금액오류
											<? } ?>
										</td>
										<td class="tcenter">
											<? if($od['od_type'] == "O") {?>
												<?=number_format(($row['ct_price'] + $row['io_price'])*$row['ct_qty'])?>원
											<? } else if($od['od_type'] == "R"){ ?>
												<?=number_format((($row['ct_rental_price'] + $row['io_price'])*$row['ct_qty'])*$row['ct_item_rental_month'])?>원
											<? } else if($od['od_type'] == "L" || $od['od_type'] == "K"){ ?>
												<?=number_format(($row['ct_receipt_price'] + $row['io_price'])*$row['ct_qty'])?>원
											<? } else if($od['od_type'] == "S"){ ?>
												<?=($od['od_cart_price'])?number_format($od['od_cart_price']+$od['od_send_cost']+$od['od_send_cost2'])."원":"후불"; ?>
											<? } else { ?>
												금액오류
											<? } ?>
										</td>
										<td class="tcenter">
											<?=$row['ct_status']; ?>
										</td>
										<td class="tcenter">
											<?
											$review = sql_fetch("select count(*) cnt from lt_shop_item_use where it_id = '{$row['it_id']}' and mb_id = '{$member['mb_id']}' ");
											?>
											<div class="button_item tcenter" id="orderinquiry_btn">
												<?if($row['ct_status']=="구매확정"){?>
													<? if($review['cnt'] <= 0){ ?>
														<button class="btn gray_line small" it_id="<?=$row['it_id']?>" ct_id="<?=$row['ct_id']?>"><span>리뷰작성</span></button>
													<? } else { ?>
														<button class="btn gray_line small" it_id="<?=$row['it_id']?>" ><span>리뷰보기</span></button>
													<? } ?>
												<? } else { ?>
													구매확정 후 작성이 가능합니다.
												<? } ?>
											</div>
										</td>
									</tr>
								<? } ?>
							</tbody>
						</table>

						<? if($btn_act != '') {?>
							<div class="clear"></div>
							<div class="btnarea tright" id="orderinquiry_btn">
								<?=$btn_act; ?>
							</div>
						<? } ?>
					</div>
				</div>
			</div>

			<? if($od['od_type'] == "L" || $od['od_type'] == "K" || $od['od_type'] == "S") { ?>
				<div class="order_title">
					<span class="item"><?=$od_type_name?> 요청서</span>
				</div>
				<div class="order_list border_box">
					<ul>
						<!-- li>
							<span class="item">수거일자 선택</span>
							<strong class="result">
								<?=$od['od_hope_date']?>
							</strong>
						</li -->
						<li>
							<span class="item">요청사항</span>
							<strong class="result">
								<?=nl2br($od['cust_memo']) ?>
							</strong>
						</li>
					</ul>
					<div class="photo">
						<ul class="list">
							<?
							$cust_file = json_decode($od['cust_file'], true);
							for ($i = 0; $i < count($cust_file); $i++) {

								$imgL = G5_DATA_URL.'/file/order/'.$od['od_id'].'/'.$cust_file[$i]['file'];

								if ( preg_match("/\.(mp4|mov|avi)$/i", $cust_file[$i]['file'])){
									echo "<li><video controls width='150' height='150' style='vertical-align:top;' >
									<source src='$imgL' type='video/mp4' width='150' height='150' >
									</video></li>";
								} else {
									echo '<li><img src="'.$imgL.'" width="150px"></li>';
								}
							}
							?>
						</ul>
					</div>
				</div>
			<? } ?>

			<?
			/*
			총계 = 주문상품금액합계
			+ 배송비(기본 + 추가)
			- 쿠폰 금액(쿠폰 사용금액 //상품쿠폰, 주문쿠폰, 배송비쿠폰)
			- 취소금액
			 */
			$tot_price = $od['od_cart_price']
			+ $od['od_send_cost']
			- $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
			- $od['od_cancel_price'];

			//쿠폰 할인 금액(쿠폰 사용금액 //상품쿠폰, 주문쿠폰, 배송비쿠폰)
			$sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];

			//총 결제금액(결제금액 + 적립금결제)
			$receipt_price = $od['od_receipt_price']
			+ $od['od_receipt_point'];
			//주문취소금액
			$cancel_price = $od['od_cancel_price'];

			//미수금액 (총금액 - 결제금액 - 취소금액) : 수선-추가금 결제요청시 발생함.
			$misu = true;
			$misu_price = $tot_price - $receipt_price - $cancel_price;

			if ($misu_price == 0 && ($od['od_cart_price'] > $od['od_cancel_price'])) {
				$wanbul = " (완불)";
				$misu = false;
				// 미수금 없음
			} else {
				$wanbul = display_price($receipt_price);
			}

			// 결제정보처리
			if($od['od_receipt_price'] > 0 || $od['od_type'] != "S"){
				$od_receipt_price = display_price($od['od_receipt_price']);
			} else {
				//수선일떄 발생.
				$od_receipt_price = '후불';
			}

			$app_no_subj = '';
			$disp_bank = false;
			$disp_receipt = false;
			$easy_pay_name = '';
			if($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == 'KAKAOPAY' || is_inicis_order_pay($od['od_settle_case']) ) {
				$app_no_subj = '승인번호';
				$app_no = $od['od_app_no'];
				$disp_bank = false;
				$disp_receipt = true;
			} else if($od['od_settle_case'] == '간편결제') {
				$app_no_subj = '승인번호';
				$app_no = $od['od_app_no'];
				$disp_bank = false;
				switch($od['od_pg']) {
					case 'lg':
					$easy_pay_name = 'PAYNOW';
					break;
					case 'inicis':
					$easy_pay_name = 'KPAY';
					break;
					case 'kcp':
					$easy_pay_name = 'PAYCO';
					break;
					default:
					break;
				}
			} else if($od['od_settle_case'] == '휴대전화') {
				$app_no_subj = '휴대전화번호';
				$app_no = $od['od_bank_account'];
				$disp_bank = false;
				$disp_receipt = true;
			} else if($od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') {
				$app_no_subj = '거래번호';
				$app_no = $od['od_tno'];
			}
			?>

			<div class="grid">
				<div class="floatL bold" style="width:50%; min-height:34px;">결제상세정보</div>
				<div class="floatL bold" style="width:50%; min-height:34px;">배송지정보</div>
				<div class="clear"></div>
				<table class="">
					<colgroup>
						<col width="50%" />
						<col width="50%" />
					</colgroup>
					<tr>
						<td valign="top">
							<table class="TBasic3">
								<colgroup>
									<col width="25%" />
									<col width="75%" />
								</colgroup>
								<tr>
									<th class="tleft">주문일시</th>
									<td><?=$od['od_time']; ?></td>
								</tr>
								<? if($od['od_type'] == "O"){ ?>
									<tr>
										<th class="tleft">주문금액</th>
										<td><?=number_format($od['od_cart_price']+$od['od_send_cost']); ?> 원</td>
									</tr>
									<tr>
										<th class="tleft">할인금액</th>
										<td>-<?=number_format($od['od_receipt_point']+$od['od_cart_coupon']+$od['od_coupon']); ?> 원</td>
									</tr>
									<tr>
										<th class="tleft">적립금</th>
										<td><?=number_format($tot_point); ?> 원</td>
									</tr>
									<tr>
										<th class="tleft">결제방식</th>
										<td>
											<?=($easy_pay_name ? $easy_pay_name.'('.$od['od_settle_case'].')' : check_pay_name_replace($od['od_settle_case'])); ?>
											<? if($disp_receipt) { ?>
												<?
												if($od['od_settle_case'] == '휴대전화'){
													$LGD_TID      = $od['od_tno'];
													//$LGD_MERTKEY  = $config['cf_lg_mert_key'];
													$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);
													$hp_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
													?>
													<button class="btn gray_trne small" onctrck="<?=$hp_receipt_script; ?>">영수증 출력</button>
												<? } ?>

												<?
												if($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])){
													$LGD_TID      = $od['od_tno'];
													//$LGD_MERTKEY  = $config['cf_lg_mert_key'];
													$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);
													$card_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
													?>
													<button class="btn gray_trne small" onctrck="<?=$card_receipt_script; ?>">영수증 출력</button>
												<? } ?>

												<?
												if($od['od_settle_case'] == 'KAKAOPAY'){
													$card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$od['od_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
													?>
													<button class="btn gray_trne small" onctrck="<?=$card_receipt_script; ?>">영수증 출력</button>
												<? } ?>
											<? } ?>
										</td>
									</tr>

									<!--계좌정보-->
									<? if($disp_bank){ ?>
										<tr>
											<th class="tleft">입금자명</th>
											<td><?=get_text($od['od_deposit_name']); ?></td>
										</tr>
										<tr>
											<th class="tleft">입금계좌</th>
											<td><?=get_text($od['od_bank_account']); ?></td>
										</tr>
									<? } ?>

									<? if ($default['de_taxsave_use']) {?>
										<?if ($misu_price == 0 && $od['od_receipt_price'] && ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '계좌이체' || $od['od_settle_case'] == '가상계좌')) {?>
											<tr>
												<th class="tleft">현금영수증</th>
												<td>
													<?
													if ($od['od_cash']){
														switch($od['od_settle_case']) {
															case '계좌이체':
															$trade_type = 'BANK';
															break;
															case '가상계좌':
															$trade_type = 'CAS';
															break;
															default:
															$trade_type = 'CR';
															break;
														}
														$cash_receipt_script = 'javascript:showCashReceipts(\''.$LGD_MID.'\',\''.$od['od_id'].'\',\''.$od['od_casseqno'].'\',\''.$trade_type.'\',\''.$CST_PLATFORM.'\');';
														?>
														<a href="javascript:;" onctrck="<?=$cash_receipt_script; ?>" class="btn gray_trne small">현금영수증 확인하기</a>
													<? } else { ?>
														<a href="javascript:;" onctrck="window.open('<?=G5_SHOP_URL; ?>/taxsave.php?od_id=<?=$od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn gray_trne small">현금영수증을 발급</a>
													<? } ?>
												</td>
											</tr>
										<? } ?>
									<? } ?>
								<? } ?>
								<? if($od['od_type'] == "R"){ ?>
									<tr>
										<th class="tleft">총 리스 금액</th>
										<td><?=number_format($od['rt_rental_price'] * $od['rt_month']); ?> 원</td>
									</tr>
									<tr>
										<th class="tleft">월 이용료</th>
										<td><?=number_format($od['rt_rental_price']); ?>원 (카드자동이체)</td>
									</tr>
									<tr>
										<th class="tleft">카드사</th>
										<td><?=$od['od_bank_account']; ?></td>
									</tr>
									<tr>
										<th class="tleft">납부일</th>
										<td><?=$od['rt_billday']; ?>일</td>
									</tr>
									<tr>
										<th class="tleft">납부횟수</th>
										<td>
											<select id="rt_payment_count" name="rt_payment_count" style="float:left; width:100px; height:20px; padding:0px;">
												<?
												$sql = "select * from lt_shop_order_add_receipt where od_id = '$od_id' and od_receipt_type = 'rental' order by od_receipt_rental_month desc";
												$result = sql_query($sql);
												if(sql_num_rows($result)){
													for ($i=0; $row=sql_fetch_array($result); $i++){
														$LGD_TID = $row['od_tno'];
														$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

														echo '<option value="'.$LGD_HASHDATA.'" od_tno="'.$LGD_TID.'" >'.$row['od_receipt_rental_month'].' 회</option>';
													}
												} else {
													$LGD_TID = $od['od_tno'];
													$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

													echo '<option value="'.$LGD_HASHDATA.'" od_tno="'.$LGD_TID.'" >1 회</option>';
												}
												?>
											</select>
											<button class="btn gray_line small" id="btnshowReceipt" ><span>영수증 출력</span></button>
										</td>
									</tr>
									<? if($od['rt_rental_startdate']) {?>
										<tr>
											<th class="tleft">납부 시작일</th>
											<td><?=$od['rt_rental_startdate']; ?></td>
										</tr>
									<? } ?>
									<? if ($od['od_refund_price'] > 0) { ?>
										<tr>
											<th class="tleft">환불 금액</th>
											<td><?=number_format($od['od_refund_price']); ?> 원</td>
										</tr>
									<? } ?>
								<? } else { ?>
									<tr>
										<th class="tleft"><?=$od_type_name?> 금액</th>
										<td><?=number_format($od['od_cart_price']); ?> 원</td>
									</tr>
									<tr>
										<th class="tleft">배송비</th>
										<td><?=number_format($od['od_send_cost']); ?> 원</td>
									</tr>
									<? if ($od['od_receipt_point'] > 0){?>
										<tr>
											<th class="tleft">사용적립금</th>
											<td><?=display_point($od['od_receipt_point']); ?></td>
										</tr>
									<? } ?>
									<? if($od['od_cart_coupon'] > 0) { ?>
										<tr>
											<th class="tleft">상품 정보 할인</th>
											<td><?=($od['od_cart_coupon'])?'-'.number_format($od['od_cart_coupon']):''; ?> 원</td>
										</tr>
									<? } ?>
									<? if($od['od_coupon'] > 0) { ?>
										<tr>
											<th class="tleft">쿠폰 할인</th>
											<td><?=number_format($od['od_coupon']); ?> 원</td>
										</tr>
									<? } ?>
									<? if ($od['od_cancel_price'] > 0) { ?>
										<tr>
											<th class="tleft">취소 금액</th>
											<td><?=number_format($od['od_cancel_price']); ?> 원</td>
										</tr>
									<? } ?>
									<? if ($od['od_refund_price'] > 0) { ?>
										<tr>
											<th class="tleft">환불 금액</th>
											<td><?=number_format($od['od_refund_price']); ?> 원</td>
										</tr>
									<? } ?>
									<? if ($od['od_send_cost2'] > 0) { ?>
										<tr>
											<th class="tleft">반품배송비</th>
											<td><?=number_format($od['od_send_cost2']); ?> 원</td>
										</tr>
									<? } ?>
								<? } ?>
							</table>
						</td>
						<td valign="top">
							<table class="TBasic3">
								<colgroup>
									<col width="25%" />
									<col width="75%" />
								</colgroup>
								<tr>
									<th class="tleft">이름</th>
									<td><?=get_text($od['od_b_name']); ?></td>
								</tr>
								<tr>
									<th class="tleft">주소</th>
									<td><?=get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']).' '.print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></td>
								</tr>
								<tr>
									<th class="tleft">이메일 주소</th>
									<td><?=get_text($od['od_email']); ?></td>
								</tr>
								<?if($od['od_b_tel']){?>
									<tr>
										<th class="tleft">연락처</th>
										<td><?=get_text($od['od_b_tel']); ?></td>
									</tr>
								<? } ?>
								<?if($od['od_b_hp']){?>
									<tr>
										<th class="tleft">휴대전화 번호</th>
										<td><?=get_text($od['od_b_hp']); ?></td>
									</tr>
								<? } ?>
								<? if ($default['de_hope_date_use']){ ?>
									<tr>
										<th class="tleft">수거요청일</th>
										<td><?=substr($od['od_hope_date'],0,10).' ('.get_yoil($od['od_hope_date']).')' ;?></td>
									</tr>
								<? } ?>
								<? if ($od['od_memo']){ ?>
									<tr>
										<th class="tleft">요청사항</th>
										<td><?=conv_content($od['od_memo'], 0); ?></td>
									</tr>
								<? } ?>
								<? if ($od['od_invoice'] && $od['od_delivery_company']){ ?>
									<tr>
										<th class="tleft">배송회사</th>
										<td>
											<?=$od['od_delivery_company']; ?>
											<?
											if($od['od_delivery_company'] == "CJ대한통운"){
												echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'btn gray_line small btn_invoice2');
											} else {
												echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'btn gray_line small');
											}
											?>
										</td>
									</tr>
									<tr>
										<th class="tleft">운송장번호</th>
										<td><?=$od['od_invoice']; ?></td>
									</tr>
									<tr>
										<th class="tleft">배송일시</th>
										<td><?=$od['od_invoice_time']; ?></td>
									</tr>
								<? } ?>
							</table>
						</td>
					</tr>
				</table>
			</div>


			<div class="grid">
				<div class="title_bar none padNone bold">
					배송지 정보
				</div>
				<table class="TBasic2">
					<colgroup>
						<col width="15%" />
						<col width="75%" />
					</colgroup>
					<tr>
						<th class="tleft">이름</th>
						<td><?=get_text($od['od_b_name']); ?></td>
					</tr>
					<tr>
						<th class="tleft">주소</th>
						<td><?=get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']).' '.print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></td>
					</tr>
					<tr>
						<th class="tleft">이메일 주소</th>
						<td><?=get_text($od['od_email']); ?></td>
					</tr>
					<?if($od['od_b_tel']){?>
						<tr>
							<th class="tleft">연락처</th>
							<td><?=get_text($od['od_b_tel']); ?></td>
						</tr>
					<? } ?>
					<?if($od['od_b_hp']){?>
						<tr>
							<th class="tleft">휴대전화 번호</th>
							<td><?=get_text($od['od_b_hp']); ?></td>
						</tr>
					<? } ?>
					<? if ($default['de_hope_date_use']){ ?>
						<tr>
							<th class="tleft">수거요청일</th>
							<td><?=substr($od['od_hope_date'],0,10).' ('.get_yoil($od['od_hope_date']).')' ;?></td>
						</tr>
					<? } ?>
					<? if ($od['od_memo']){ ?>
						<tr>
							<th class="tleft">요청사항</th>
							<td><?=conv_content($od['od_memo'], 0); ?></td>
						</tr>
					<? } ?>
					<? if ($od['od_invoice'] && $od['od_delivery_company']){ ?>
						<tr>
							<th class="tleft">배송회사</th>
							<td>
								<?=$od['od_delivery_company']; ?>
								<?
								if($od['od_delivery_company'] == "CJ대한통운"){
									echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'btn gray_line small btn_invoice2');
								} else {
									echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'btn gray_line small');
								}
								?>
							</td>
						</tr>
						<tr>
							<th class="tleft">운송장번호</th>
							<td><?=$od['od_invoice']; ?></td>
						</tr>
						<tr>
							<th class="tleft">배송일시</th>
							<td><?=$od['od_invoice_time']; ?></td>
						</tr>
					<? } ?>
				</table>
			</div>

			<? if ($is_member) {?>
				<div class="btn_group">
					<a href="<?=G5_SHOP_URL; ?>/orderinquiry.php"><button type="button" class="btn big border"><span>목록 이동</span></button></a>
				</div>
			<? } ?>
		</div>
	</div>

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
							<li><a href="#" onclick="location.href='<?=G5_SHOP_URL?>/itemuseform.php?mode=txt&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">일반 리뷰</span></a></li>
							<li><a href="#" onclick="location.href='<?=G5_SHOP_URL?>/itemuseform.php?mode=img&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">프리미엄 리뷰</span></a></li>
						</ul>
					</div>
				</div>
			</div>
			<a href="#" class="btn_closed btn_close" onclick="$('#od_review_select').prop('hidden', true);"><span class="blind">닫기</span></a>
		</div>
	</section>

	<form method="post" action="./orderinquirychange.php" id="orderinquirychange_form" name="orderinquirychange_form">
		<input type="hidden" name="act" value="">
		<input type="hidden" name="od_id"  value="">
		<input type="hidden" name="token"  value="">
		<input type="hidden" name="uid"  value="">
	</form>
	<script>
		$(function() {
			$("#btnshowReceipt").click(function(){
				$opt = $("#rt_payment_count option:selected");
				showReceiptByTID('<?=$LGD_MID?>', $opt.attr("od_tno"), $opt.val());
			});

			$(".btn_invoice2").click(function(){
				var href=$(this).attr("href");
				if(href.indexOf("<?=G5_URL?>") >= 0){
					$.post(href, { od_id: '<?=$od_id?>' },
						function(data) {
							$("#dvOrderinquiryPopup").html(data);
						});
					//url = href+"&view_popup=1";
					//window.open(url, "popupinvoice2", "left=100,top=100,width=600,height=600,scrollbars=0");
					return false;
				}
			});

			$(document).on("click", "#orderinquiry_btn button", function() {
				var mode = $(this).text();
				var od_id = $(this).attr("od_id");
				var uid=$(this).attr("uid");

				switch(mode) {
					case "주문취소":
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&uid="+uid;
					break;
					case "수거지변경":
					case "배송지변경":
					$.post(
						"./orderinquiry.deliverychangeform.php",
						{ od_id: od_id, uid:uid },
						function(data) {
							$("#dvOrderinquiryPopup").html(data);
						}
						);
					break;
					case "배송조회":
					var href=$(this).closest("a").attr("href");
					if(href.indexOf("<?=G5_URL?>") >= 0){
						$.post(href, { od_id: od_id },
							function(data) {
								$("#dvOrderinquiryPopup").html(data);
							}
							);
					//url = href+"&view_popup=1";
					//window.open(url, "popupinvoice2", "left=100,top=100,width=600,height=600,scrollbars=0");
					return false;
				}
				break;
				case "교환요청":
				if(confirm("교환을 요청 하시겠습니까? 교환 시 사유에 따라 배송료가 발생 될 수 있습니다."))
				{
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquirychangeform.php?od_id="+od_id+"&act=change&uid="+uid;
				}
				break;
				case "교환철회":
				if(confirm("교환을 철회 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("교환철회");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "반품요청":
				if(confirm("반품을 요청 하시겠습니까? 반품 시 사유에 따라 배송료가 발생 될 수 있습니다."))
				{
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquiryreturnform.php?od_id="+od_id+"&act=return&uid="+uid;
				}
				break;
				case "반품철회":
				if(confirm("반품을 철회 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("반품철회");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "철회요청":
				if(confirm("철회를 요청 하시겠습니까? 철회 시 사유에 따라 배송료가 발생 될 수 있습니다."))
				{
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquiryreturnform.php?od_id="+od_id+"&act=return&uid="+uid;
				}
				break;
				case "철회취소":
				if(confirm("철회요청을 취소 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("철회취소");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "해지신청":
				$.post(
					"./orderinquiry.contractout.php",
					{ od_id: od_id, uid:uid },
					function(data) {
						$("#dvOrderinquiryPopup").html(data);
					}
					);
				break;
				case "해지취소":
				if(confirm("해지신청을 취소 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("해지취소");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "위약금납부":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderform.out.php?od_id="+od_id+"&uid="+uid;
				break;
				case "위약금영수증":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderform.out2.php?od_id="+od_id+"&uid="+uid;
				break;
				case "구매확정":
				if(confirm("구매확정 시 반품 및 교환이 불가합니다. 확정 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("구매확정");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "리뷰작성":
				var it_id = $(this).attr("it_id");
				var ct_id = $(this).attr("ct_id");
				$('#od_review_select').attr("it_id",it_id);
				$('#od_review_select').attr("ct_id",ct_id);
				$('#od_review_select').prop('hidden', false);

				//location.href="<?=G5_SHOP_URL;?>/itemuseform.php?it_id="+it_id;
				break;
				case "리뷰보기":
				var it_id = $(this).attr("it_id");
				location.href="<?=G5_SHOP_URL;?>/item.php?it_id="+it_id+"#review";
				break;
				case "계약취소":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&uid="+uid;
				break;
				case "리스시작하기":
				if(confirm("리스를 시작 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("리스시작하기");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "수선비용결제":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderform2.php?od_id="+od_id;
				break;
				case "계약서다운로드":
				url = "<?=G5_SHOP_URL;?>/orderinquiryview.rental.php?od_id="+od_id;
				window.open(url, "rentalpdf", "left=100,top=100,width=800,height=600,scrollbars=0");
				break;

			}
		});
});
</script>

<div id="dvOrderinquiryPopup"></div>
<?
include_once('./_tail.php');
?>
