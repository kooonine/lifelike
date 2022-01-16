<?php
include_once('./_common.php');
require_once(G5_LGXPAY_PATH.'/lgdacom/XPayClient.php');

class XPay extends XPayClient
{
    public function set_config_value($key, $val)
    {
        $this->config[$key] = $val;
    }
}
/*
 * [ARS 신용카드 예약번호 등록/수정/조회 요청 페이지]
 *
 * 파라미터 전달시 POST를 사용하세요
 */
$CST_PLATFORM               = $_POST["CST_PLATFORM"];						//LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
$CST_MID                    = "1113397";							//상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)

$LGD_REQTYPE                = $_POST["LGD_REQTYPE"];						//Create:생성, Update:변경, Search:조회
$LGD_RESERVENUMBER          = $_POST["LGD_RESERVENUMBER"];					//예약번호 (상점관리자 상품명에 명시됩니다)
$LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];					//고객 SMS 발송용 상품명(상점관리자에서는 조회되지 않습니다)
$LGD_BUYERPHONE         	= $_POST["LGD_BUYERPHONE"];						//고객 SMS 수신 핸드폰번호
$LGD_CALLBACK               = $_POST["LGD_CALLBACK"];						//ARS결제 전화 번호
$LGD_AMOUNT           		= $_POST["LGD_AMOUNT"];							//결제금액 (,를 제외한 숫자만)
$LGD_RESERVEDATE            = $_POST["LGD_RESERVEDATE"];					//예약일자
$LGD_EXPIREDATE             = $_POST["LGD_EXPIREDATE"];						//만료일자
$LGD_NOTEURL             	= $_POST["LGD_NOTEURL"];						//결제결과 전송 URL
$note_url             		= $_POST["LGD_NOTEURL"];						//결제결과 전송 URL
$LGD_EX_PARAM             	= $_POST["LGD_EX_PARAM"];						//예외상점 전용 파라미터
$LGD_STATUS               	= $_POST["LGD_STATUS"];							//사용여부

$LGD_EX_PARAM = htmlspecialchars_decode($LGD_EX_PARAM);

$configPath             = G5_LGXPAY_PATH.'/lgdacom';                               //LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.

$xpay = new XPay($configPath, $CST_PLATFORM);

// Mert Key 설정
$xpay->set_config_value('t'.$LGD_MID, "00b1ea06713d6cf58ec0dccb448560ec");
$xpay->set_config_value($LGD_MID, "00b1ea06713d6cf58ec0dccb448560ec");

$xpay->Init_TX($LGD_MID);

$xpay->Set("LGD_TXNAME", "ARSReserve");
$xpay->Set("LGD_REQTYPE", $LGD_REQTYPE);
$xpay->Set("LGD_RESERVENUMBER", $LGD_RESERVENUMBER);
$xpay->Set("LGD_PRODUCTINFO", $LGD_PRODUCTINFO);
$xpay->Set("LGD_BUYERPHONE", $LGD_BUYERPHONE);
$xpay->Set("LGD_CALLBACK", $LGD_CALLBACK);
$xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
$xpay->Set("LGD_RESERVEDATE", $LGD_RESERVEDATE);
$xpay->Set("LGD_EXPIREDATE", $LGD_EXPIREDATE);
$xpay->Set("LGD_NOTEURL", $LGD_NOTEURL);
$xpay->Set("note_url", $note_url);
$xpay->Set("LGD_EX_PARAM", $LGD_EX_PARAM);
$xpay->Set("LGD_STATUS", $LGD_STATUS);

/*
 * 1. 예약번호 등록/수정/조회 요청 결과처리
 *
 * 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
 */
if ($xpay->TX()) {
    //1)요청 결과 처리(등록, 변경, 조회  요청결과를 처리 하시기 바랍니다.)
    echo "요청이 완료되었습니다  <br>";
    echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
    echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    
    $keys = $xpay->Response_Names();
    foreach($keys as $name) {
        echo $name . " = " . $xpay->Response($name, 0) . "<br>";
    }
    
    echo "<p>";
    
}else {
    //2)API 요청 실패 화면처리
    echo "API 요청이 실패하였습니다.  <br>";
    echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
    echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
}
?>
