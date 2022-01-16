<?php
error_reporting(E_ALL);
include_once('./../common.php');

$mb_id = 'balance';

$arr_change_data = array();

/*
msg_autosend('회원', '회원 가입', $mb_id, $arr_change_data);
msg_autosend('회원', '비밀번호 안내', $mb_id, $arr_change_data);
$arr_change_data['소멸예정포인트'] = $member['mb_point'];
$arr_change_data['소멸예정일'] = date("Y.m.d", strtotime("+7day"));
$arr_change_data['0000.00.00'] = date("Y.m.d", strtotime("+30day"));
msg_autosend('회원', '휴면회원안내', $mb_id, $arr_change_data);
msg_autosend('게시판', '1:1문의 답변 통보', $mb_id, $arr_change_data);
$arr_change_data['상품명'] = '상품명 어쩌구';
$arr_change_data['브랜드'] = '소프라움';
// msg_autosend('게시판', 'QNA답변안내', $mb_id, $arr_change_data);
*/
$arr_change_data['button'] = array(
    "type" => "웹링크",
    "txt" => "상품 바로가기",
    "link" => "https://lifelike.co.kr/shop/item.php?it_id=010010020000005"
);
msg_autosend('게시판', '재입고알림', $mb_id, $arr_change_data);
/*
msg_autosend('회원', '적립금 소멸 안내', $mb_id, $arr_change_data);
msg_autosend('회원', '포인트 적립 안내', $mb_id, $arr_change_data);
msg_autosend('회원', '쿠폰 소멸 안내', $mb_id, $arr_change_data);
*/

/*
$arr_change_data['od_id'] = '20200727000161'; // 롯데택배
// $arr_change_data['od_id'] = '20191205000013'; // CJ배송정보

// $arr_change_data['button'] = array(
//     "type" => "웹링크",
//     "txt" => "주문상세보기",
//     "link" => "https://www.lifelike.co.kr/member/order.php?od_id=" . $arr_change_data['od_id']
// );
// msg_autosend('주문', '결제 완료', $mb_id, $arr_change_data);
// msg_autosend('주문', '배송 시작', $mb_id, $arr_change_data);

// $arr_change_data['button'] = array(
//     "type" => "웹링크",
//     "txt" => "주문내역 확인하기",
//     "link" => "https://lifelike.co.kr/member/order.php?od_id=" . $arr_change_data['od_id']
// );
// msg_autosend('주문', '배송 완료', $mb_id, $arr_change_data);
// $arr_change_data['취소금액'] = "100,700원";
// msg_autosend('주문', '취소 완료', $mb_id, $arr_change_data);
$arr_change_data['반품상품'] = "윙필로우 배게 어쩌구 외 1건";
msg_autosend('주문', '반품 완료', $mb_id, $arr_change_data);
msg_autosend('주문', '반품 접수', $mb_id, $arr_change_data);
$arr_change_data['교환상품'] = "윙필로우 배게 어쩌구 외 1건";
msg_autosend('주문', '교환 접수', $mb_id, $arr_change_data);
msg_autosend('주문', '교환 완료', $mb_id, $arr_change_data);
*/
