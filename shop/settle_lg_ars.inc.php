<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

require_once(G5_LGXPAY_PATH.'/lgdacom/XPayClient.php');

class XPay extends XPayClient
{
    public function set_config_value($key, $val)
    {
        $this->config[$key] = $val;
    }
}

/*
 * 1. 기본결제 인증요청 정보 변경
 *
 * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
 */
//$CST_PLATFORM           = $default['de_card_test'] ? 'test' : 'service';    //LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
$CST_PLATFORM           = 'service';
$CST_MID                = "1113397";                       //상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
$ARS_NUMBER             = "16446962";                       //ARS 전화번호
//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)

$LGD_TIMESTAMP          = date('YmdHis');                                   //타임스탬프
$LGD_BUYERIP            = $_SERVER['REMOTE_ADDR'];                          //구매자IP
$LGD_MERTKEY            = '00b1ea06713d6cf58ec0dccb448560ec';               //상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
$LGD_NOTEURL            = G5_SHOP_URL.'/lg_ars/note_url.php';               //결제결과 전송 URL
$note_url             	= $LGD_NOTEURL;						//결제결과 전송 URL
$LGD_CALLBACK           = $ARS_NUMBER;						//ARS결제 전화 번호

$configPath             = G5_LGXPAY_PATH.'/lgdacom';                               //LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.

/*
$LGD_REQTYPE                = $_POST["LGD_REQTYPE"];						//Create:생성, Update:변경, Search:조회
$LGD_RESERVENUMBER          = $_POST["LGD_RESERVENUMBER"];					//예약번호 (상점관리자 상품명에 명시됩니다)
$LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];					//고객 SMS 발송용 상품명(상점관리자에서는 조회되지 않습니다)
$LGD_BUYERPHONE         	= $_POST["LGD_BUYERPHONE"];						//고객 SMS 수신 핸드폰번호
$LGD_AMOUNT           		= $_POST["LGD_AMOUNT"];							//결제금액 (,를 제외한 숫자만)
$LGD_RESERVEDATE            = $_POST["LGD_RESERVEDATE"];					//예약일자
$LGD_EXPIREDATE             = $_POST["LGD_EXPIREDATE"];						//만료일자
$LGD_EX_PARAM             	= $_POST["LGD_EX_PARAM"];						//예외상점 전용 파라미터
$LGD_STATUS               	= $_POST["LGD_STATUS"];							//사용여부
$LGD_EX_PARAM = htmlspecialchars_decode($LGD_EX_PARAM);
 */

?>