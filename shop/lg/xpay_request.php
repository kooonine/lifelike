<?php


include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

// LG유플러스 공통 설정
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

/*
 * 1. 기본결제 인증요청 정보 변경
 *
 * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
 */
$LGD_OID                    = $_POST['LGD_OID'];                //주문번호(상점정의 유니크한 주문번호를 입력하세요)
$LGD_AMOUNT                 = $_POST['LGD_AMOUNT'];             //결제금액("," 를 제외한 결제금액을 입력하세요)
$LGD_TIMESTAMP              = $_POST['LGD_TIMESTAMP'];          //타임스탬프
$LGD_BUYER                  = $_POST['LGD_BUYER'];              //구매자명
$LGD_PRODUCTINFO            = $_POST['LGD_PRODUCTINFO'];        //상품명
$LGD_BUYEREMAIL             = $_POST['LGD_BUYEREMAIL'];         //구매자 이메일
$LGD_CUSTOM_FIRSTPAY        = $_POST['LGD_CUSTOM_FIRSTPAY'];    //상점정의 초기결제수단
$LGD_CUSTOM_SKIN            = 'red';                            //상점정의 결제창 스킨
$LGD_CUSTOM_USABLEPAY       = $_POST['LGD_CUSTOM_USABLEPAY'];   //디폴트 결제수단 (해당 필드를 보내지 않으면 결제수단 선택 UI 가 노출됩니다.)
$LGD_WINDOW_VER             = '2.5';                            //결제창 버젼정보
$LGD_WINDOW_TYPE            = $LGD_WINDOW_TYPE;                 //결제창 호출방식 (수정불가)
$LGD_CUSTOM_SWITCHINGTYPE   = $LGD_CUSTOM_SWITCHINGTYPE;        //신용카드 카드사 인증 페이지 연동 방식 (수정불가)
$LGD_CUSTOM_PROCESSTYPE     = 'TWOTR';                          //수정불가



/*
 *************************************************
 * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
 *
 * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다.
 *************************************************
 *
 * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
 * LGD_MID          : 상점아이디
 * LGD_OID          : 주문번호
 * LGD_AMOUNT       : 금액
 * LGD_TIMESTAMP    : 타임스탬프
 * LGD_MERTKEY      : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
 *
 * MD5 해쉬데이터 암호화 검증을 위해
 * LG유플러스에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
 */

$xpay = new XPay($configPath, $CST_PLATFORM);

// alert("asd");

// Mert Key 설정
$xpay->set_config_value('t' . $LGD_MID, $config['cf_lg_mert_key']);
$xpay->set_config_value($LGD_MID, $config['cf_lg_mert_key']);

$xpay->Init_TX($LGD_MID);
$LGD_HASHDATA = md5($LGD_MID . $LGD_OID . $LGD_AMOUNT . $LGD_TIMESTAMP . $xpay->config[$LGD_MID]);

/*
 *************************************************
 * 2. MD5 해쉬암호화 (수정하지 마세요) - END
 *************************************************
 */

$payReqMap['CST_PLATFORM']              = $CST_PLATFORM;                // 테스트, 서비스 구분
$payReqMap['LGD_WINDOW_TYPE']           = $LGD_WINDOW_TYPE;             // 수정불가
$payReqMap['CST_MID']                   = $CST_MID;                     // 상점아이디
$payReqMap['LGD_MID']                   = $LGD_MID;                     // 상점아이디
$payReqMap['LGD_OID']                   = $LGD_OID;                     // 주문번호
$payReqMap['LGD_BUYER']                 = $LGD_BUYER;                   // 구매자
$payReqMap['LGD_PRODUCTINFO']           = $LGD_PRODUCTINFO;             // 상품정보
$payReqMap['LGD_AMOUNT']                = $LGD_AMOUNT;                  // 결제금액
$payReqMap['LGD_BUYEREMAIL']            = $LGD_BUYEREMAIL;              // 구매자 이메일
$payReqMap['LGD_CUSTOM_SKIN']           = $LGD_CUSTOM_SKIN;             // 결제창 SKIN
$payReqMap['LGD_CUSTOM_PROCESSTYPE']    = $LGD_CUSTOM_PROCESSTYPE;      // 트랜잭션 처리방식
$payReqMap['LGD_TIMESTAMP']             = $LGD_TIMESTAMP;               // 타임스탬프
$payReqMap['LGD_HASHDATA']              = $LGD_HASHDATA;                // MD5 해쉬암호값
$payReqMap['LGD_RETURNURL']             = $LGD_RETURNURL;               // 응답수신페이지
$payReqMap['LGD_VERSION']               = $LGD_VERSION;                 // 버전정보 (삭제하지 마세요)
$payReqMap['LGD_CUSTOM_USABLEPAY']      = $LGD_CUSTOM_USABLEPAY;        // 디폴트 결제수단
$payReqMap['LGD_CUSTOM_SWITCHINGTYPE']  = $LGD_CUSTOM_SWITCHINGTYPE;    // 신용카드 카드사 인증 페이지 연동 방식
$payReqMap['LGD_WINDOW_VER']            = $LGD_WINDOW_VER;
$payReqMap['LGD_ENCODING']              = 'UTF-8';
$payReqMap['LGD_ENCODING_RETURNURL']    = 'UTF-8';



$payReqMap['LGD_CUSTOM_ROLLBACK']            = '';

$payReqMap['LGD_KVPMISPNOTEURL']            = $LGD_KVPMISPNOTEURL;
$payReqMap['LGD_KVPMISPWAPURL']            = $LGD_KVPMISPWAPURL;
//$payReqMap['LGD_KVPMISPCANCELURL']            = $LGD_KVPMISPCANCELURL;
$payReqMap['LGD_KVPMISPAUTOAPPYN']            = $LGD_KVPMISPAUTOAPPYN;


// 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 유플러스에 전송해야 합니다 .
$payReqMap['LGD_CASNOTEURL']            = $LGD_CASNOTEURL;              // 가상계좌 NOTEURL


///DB 처리 파라미터

$payReqMap['od_price']            = $od_price;

$payReqMap['od_send_cost'] =$od_send_cost;
$payReqMap['od_send_cost2'] =$od_send_cost2;
$payReqMap['od_send_coupon'] =$od_send_coupon;
$payReqMap['od_temp_point'] =$od_temp_point ;

 

$payReqMap['cp_id'] = json_encode($_POST['cp_id']);
$payReqMap['it_id'] = json_encode($_POST['it_id']);
$payReqMap['od_cp_id'] =$od_cp_id;
$payReqMap['od_send_cp_id'] =$od_send_cp_id;
$payReqMap['od_pwd'] =$od_pwd;



$payReqMap['$od_id']  = $_POST['od_id'];
$payReqMap['$od_type'] = $_POST['od_type'];
// $payReqMap['{$member['mb_id']}'] = {$member['mb_id']};
$payReqMap['$od_pwd'] = $_POST['od_pwd'];
$payReqMap['$od_name'] = $_POST['od_name'];
$payReqMap['$od_email'] = $_POST['od_email'];
$payReqMap['$od_tel'] = $_POST['od_tel'];
$payReqMap['$od_hp'] = $_POST['od_hp'];
$payReqMap['$od_zip'] = $_POST['od_zip'];

$payReqMap['$od_addr1'] = $_POST['od_addr1'];
$payReqMap['$od_addr2'] = $_POST['od_addr2'];
$payReqMap['$od_addr3'] = $_POST['od_addr3'];
$payReqMap['$od_addr_jibeon'] = $_POST['od_addr_jibeon'];
$payReqMap['$od_b_name'] = $_POST['od_b_name'];
$payReqMap['$od_b_tel'] = $_POST['od_b_tel'];
$payReqMap['$od_b_hp'] = $_POST['od_b_hp'];
$payReqMap['$od_b_hp_1'] = $_POST['od_b_hp_1'];
$payReqMap['$od_b_hp_2'] = $_POST['od_b_hp_2'];
$payReqMap['$od_b_zip'] = $_POST['od_b_zip'];
$payReqMap['$od_b_zip1'] = $_POST['od_b_zip1'];
$payReqMap['$od_b_zip2'] = $_POST['od_b_zip2'];
$payReqMap['$od_b_addr1'] = $_POST['od_b_addr1'];
$payReqMap['$od_b_addr2'] = $_POST['od_b_addr2'];
$payReqMap['$od_b_addr3'] = $_POST['od_b_addr3'];
$payReqMap['$od_b_addr_jibeon'] = $_POST['od_b_addr_jibeon'];
$payReqMap['$od_deposit_name'] = $_POST['od_deposit_name'];
$payReqMap['$od_memo'] = $_POST['od_memo'];
$payReqMap['$cart_count'] = $_POST['cart_count'];
$payReqMap['$tot_ct_price'] = $_POST['tot_ct_price'];
$payReqMap['$tot_it_cp_price'] = $_POST['tot_it_cp_price'];
$payReqMap['$od_send_cost'] = $_POST['od_send_cost'];
$payReqMap['$tot_sc_cp_price'] = $_POST['tot_sc_cp_price'];
$payReqMap['$od_send_cost2'] = $_POST['od_send_cost2'];
$payReqMap['$tot_od_cp_price'] = $_POST['tot_od_cp_price'];
$payReqMap['$od_receipt_price'] = $_POST['od_receipt_price'];
$payReqMap['$od_receipt_point'] = $_POST['od_receipt_point'];
$payReqMap['$od_bank_account'] = $_POST['od_bank_account'];
$payReqMap['$od_receipt_time'] = $_POST['od_receipt_time'];
$payReqMap['$od_misu'] = $_POST['od_misu'];
$payReqMap['$od_pg'] = $_POST['od_pg'];
$payReqMap['$od_tno'] = $_POST['od_tno'];
$payReqMap['$od_app_no'] = $_POST['od_app_no'];
$payReqMap['$od_escrow'] = $_POST['od_escrow'];
$payReqMap['$od_tax_flag'] = $_POST['od_tax_flag'];
$payReqMap['$od_tax_mny'] = $_POST['od_tax_mny'];
$payReqMap['$od_vat_mny'] = $_POST['od_vat_mny'];
$payReqMap['$od_free_mny'] = $_POST['od_free_mny'];
$payReqMap['$od_status'] = $_POST['od_status'];

$payReqMap['$od_hope_date'] = $_POST['od_hope_date'];

$payReqMap['$REMOTE_ADDR'] = $_POST['REMOTE_ADDR'];
$payReqMap['$od_settle_case'] = $_POST['od_settle_case'];
$payReqMap['$od_pcmobile'] = $_POST['od_pcmobile'];


///




//Return URL에서 인증 결과 수신 시 셋팅될 파라미터 입니다.*/
$payReqMap['LGD_RESPCODE']              = '';
$payReqMap['LGD_RESPMSG']               = '';
$payReqMap['LGD_PAYKEY']                = '';

$_SESSION['PAYREQ_MAP'] = $payReqMap;

die(json_encode(array('LGD_HASHDATA' => $LGD_HASHDATA, 'error' => '')));
