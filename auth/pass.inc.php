<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// PASS 본인인증
$sitecode = PASS_SITECODE;      // NICE로부터 부여받은 사이트 코드
$sitepasswd = PASS_SITEPASS;      // NICE로부터 부여받은 사이트 패스워드
$authtype = "";          // 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
$popgubun   = "N";      // Y : 취소버튼 있음 / N : 취소버튼 없음
$customize   = "";      // 없으면 기본 웹페이지 / Mobile : 모바일페이지
$gender = "";            // 없으면 기본 선택화면, 0: 여자, 1: 남자


// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
// 리턴url은 인증 전 인증페이지를 호출하기 전 url과 동일해야 합니다. ex) 인증 전 url : http://www.~ 리턴 url : http://www.~
$returnurl = G5_URL . "/auth/pass.return.php";  // 성공시 이동될 URL
$errorurl = G5_URL . "/auth/pass.return.php";    // 실패시 이동될 URL

// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.

if (!empty($_SESSION["PASS_TIMEOUT"]) && $_SESSION["PASS_TIMEOUT"] >= G5_SERVER_TIME) {
    $reqseq = $_SESSION["PASS_REQ_SEQ"];
} else {
    $reqseq = get_cprequest_no($sitecode);
    $pass_timeout = G5_SERVER_TIME + 60;
    $_SESSION["PASS_TIMEOUT"] = $pass_timeout;
    $_SESSION["PASS_REQ_SEQ"] = $reqseq;
}

// 입력될 plain 데이타를 만든다.
$plaindata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
    "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
    "9:AUTH_TYPE" . strlen($authtype) . ":" . $authtype .
    "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
    "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
    "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
    "9:CUSTOMIZE" . strlen($customize) . ":" . $customize .
    "6:GENDER" . strlen($gender) . ":" . $gender;

$enc_data = get_encode_data($sitecode, $sitepasswd, $plaindata);
$pass_error = "";

switch ($enc_data) {
    case -1:
        $pass_error = "암/복호화 시스템 오류";
        break;
    case -2:
        $pass_error = "암호화 처리 오류";
        break;
    case -3:
        $pass_error = "암호화 데이터 오류";
        break;
    case -9:
        $pass_error = "입력값 오류";
        break;
}
// PASS 본인인증 끝
