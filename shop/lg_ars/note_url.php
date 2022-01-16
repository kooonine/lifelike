<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH.'/settle_lg_ars.inc.php');

/*
 * [상점 결제결과처리(DB) 페이지]
 *
 * 1) 위변조 방지를 위한 hashdata값 검증은 반드시 적용하셔야 합니다.
 *
 */

/*
 * 공통결제결과 정보
 */
$LGD_RESPCODE = "";           // 응답코드: 0000(성공) 그외 실패
$LGD_RESPMSG = "";            // 응답메세지
$LGD_MID = "";                // 상점아이디
$LGD_OID = "";                // 주문번호
$LGD_AMOUNT = "";             // 거래금액
$LGD_TID = "";                // LG유플러스가 부여한 거래번호
$LGD_PAYTYPE = "";            // 결제수단코드
$LGD_PAYDATE = "";            // 거래일시(승인일시/이체일시)
$LGD_HASHDATA = "";           // 해쉬값
$LGD_HASHDATA2 = "";          // 해쉬값2
$LGD_FINANCECODE = "";        // 결제기관코드(카드종류/은행코드/이통사코드)
$LGD_FINANCENAME = "";        // 결제기관이름(카드이름/은행이름/이통사이름)
$LGD_ESCROWYN = "";           // 에스크로 적용여부
$LGD_FINANCEAUTHNUM = "";     // 결제기관 승인번호(신용카드, 계좌이체, 상품권)
$LGD_TAXFREEAMOUNT = "";      // 면세금액

/*
 * 신용카드 결제결과 정보
 */
$LGD_CARDNUM = "";            // 카드번호(신용카드)
$LGD_CARDINSTALLMONTH = "";   // 할부개월수(신용카드)
$LGD_CARDNOINTYN = "";        // 무이자할부여부(신용카드) - '1'이면 무이자할부 '0'이면 일반할부
$LGD_TRANSAMOUNT = "";        // 환율적용금액(신용카드)
$LGD_EXCHANGERATE = "";       // 환율(신용카드)


/*
 * 구매정보
 */
$LGD_BUYER = "";              // 구매자
$LGD_PRODUCTINFO = "";        // 상품명
$LGD_BUYERID = "";            // 구매자 ID
$LGD_BUYERADDRESS = "";       // 구매자 주소
$LGD_BUYERPHONE = "";         // 구매자 전화번호
$LGD_BUYEREMAIL = "";         // 구매자 이메일
$LGD_BUYERSSN = "";           // 구매자 주민번호
$LGD_PRODUCTCODE = "";        // 상품코드
$LGD_RECEIVER = "";           // 수취인
$LGD_RECEIVERPHONE = "";      // 수취인 전화번호
$LGD_DELIVERYINFO = "";       // 배송지

$LGD_RESPCODE            = $_POST["respcode"];
$LGD_RESPMSG             = $_POST["respmsg"];
$LGD_MID                 = $_POST["mid"];
$LGD_OID                 = $_POST["oid"];
$LGD_AMOUNT              = $_POST["amount"];
$LGD_TID                 = $_POST["transaction"];
$LGD_PAYTYPE             = $_POST["paytype"];
$LGD_PAYDATE             = $_POST["paydate"];
$LGD_HASHDATA            = $_POST["hashdata"];
$LGD_HASHDATA2           = $_POST["hashdata2"];
$LGD_FINANCECODE         = $_POST["financecode"];
$LGD_FINANCENAME         = $_POST["financename"];
$LGD_TAXFREEAMOUNT       = $_POST["taxfreeamount"];
$LGD_ESCROWYN            = $_POST["useescrow"];
$LGD_TRANSAMOUNT         = $_POST["transamount"];
$LGD_EXCHANGERATE        = $_POST["exchangerate"];
$LGD_CARDNUM             = $_POST["cardnumber"];
$LGD_CARDINSTALLMONTH    = $_POST["cardperiod"];
$LGD_CARDNOINTYN         = $_POST["nointerestflag"];
$LGD_FINANCEAUTHNUM      = $_POST["authnumber"];
$LGD_BUYER               = $_POST["buyer"];
$LGD_PRODUCTINFO         = $_POST["productinfo"];
$LGD_BUYERID             = $_POST["buyerid"];
$LGD_BUYERADDRESS        = $_POST["buyeraddress"];
$LGD_BUYERPHONE          = $_POST["buyerphone"];
$LGD_BUYEREMAIL          = $_POST["buyeremail"];
$LGD_BUYERSSN            = $_POST["buyerssn"];
$LGD_PRODUCTCODE         = $_POST["productcode"];
$LGD_RECEIVER            = $_POST["receiver"];
$LGD_RECEIVERPHONE       = $_POST["receiverphone"];
$LGD_DELIVERYINFO        = $_POST["deliveryinfo"];

$LGD_HASHDATA3 = md5($LGD_TID.$LGD_MID.$LGD_OID.$LGD_PAYDATE.$LGD_RESPCODE.$LGD_AMOUNT.$LGD_MERTKEY);

/*
 * 상점 처리결과 리턴메세지
 *
 * OK   : 상점 처리결과 성공
 * 그외 : 상점 처리결과 실패
 *
 * ※ 주의사항 : 성공시 'OK' 문자이외의 다른문자열이 포함되면 실패처리 되오니 주의하시기 바랍니다.
 */
$resultMSG = "결제결과 상점 DB처리(NOTE_URL) 결과값을 입력해 주시기 바랍니다.";

if ($LGD_HASHDATA3 == $LGD_HASHDATA2) {      //해쉬값 검증이 성공하면
    if($LGD_RESPCODE == "0000"){            //결제가 성공이면
        /*
         * 거래성공 결과 상점 처리(DB) 부분
         * 상점 결과 처리가 정상이면 "OK"
         */
        $od_add = sql_fetch("select * from lt_shop_order_add_receipt where od_receipt_type = 'ars' and od_app_no = '$LGD_OID' ");
        $od_tax_flag      = $default['de_tax_flag_use'];
        $od_tax_mny = round($LGD_AMOUNT / 1.1);
        $od_vat_mny = $LGD_AMOUNT - $od_tax_mny;
        $od_free_mny = 0;
        
        $od_id = $od_add['od_id'];
        sql_query(" update {$g5['g5_shop_cart_table']} set ct_receipt_price = ct_receipt_price + '{$LGD_AMOUNT}' where od_id = '$od_id'  ");
        
        $sql = " update {$g5['g5_shop_order_table']}
                set od_receipt_price = od_receipt_price + '{$LGD_AMOUNT}'
                    ,od_misu         =  od_misu - '$LGD_AMOUNT'
                where od_id = '$od_id' ";
        sql_query($sql);
        
        $sql = "update lt_shop_order_add_receipt
                set od_receipt_price  = '$LGD_AMOUNT',
                    od_bank_account   = '$LGD_FINANCENAME',
                    od_deposit_name   = '$LGD_BUYER',
                    od_receipt_time   = '$LGD_PAYDATE',
                    od_misu           =  od_misu - '$LGD_AMOUNT',
                    od_pg_id          = 'lg_ars',
                    od_settle_case    = '신용카드',
                    od_tno            = '$LGD_TID',
                    od_escrow         = '$LGD_ESCROWYN',
                    od_tax_flag       = '$od_tax_flag',
                    od_tax_mny        = '$od_tax_mny',
                    od_vat_mny        = '$od_vat_mny',
                    od_free_mny       = '$od_free_mny'
                where oa_id = '{$od_add['oa_id']}'
                ";
        $result = sql_query($sql);
        
        if($default['de_card_test'] || !$result){
            //Test모드라면 바로 결제 취소 실행
            $xpay = new XPay($configPath, $CST_PLATFORM);
            
            // Mert Key 설정
            $xpay->set_config_value('t'.$LGD_MID, $LGD_MERTKEY);
            $xpay->set_config_value($LGD_MID, $LGD_MERTKEY);
            $xpay->Init_TX($LGD_MID);
            
            $xpay->Set("LGD_TXNAME", "Cancel");
            $xpay->Set("LGD_TID", $LGD_TID);
            
            if ($xpay->TX()) {
                //1)결제취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
                /*
                 echo "결제 취소요청이 완료되었습니다.  <br>";
                 echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
                 echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
                 */
            } else {
                //2)API 요청 실패 화면처리
                $msg = "결제 취소요청이 실패하였습니다.\\n";
                $msg .= "TX Response_code = " . $xpay->Response_Code() . "\\n";
                $msg .= "TX Response_msg = " . $xpay->Response_Msg();
                alert($msg);
            }
        } else {
            $resultMSG = "OK";
        }
        
    }else {                                 //결제가 실패이면
        /*
         * 거래실패 결과 상점 처리(DB) 부분
         * 상점결과 처리가 정상이면 "OK"
         */
        $resultMSG = "OK";
    }
} else {                                    //해쉬값 검증이 실패이면
    /*
     * hashdata검증 실패 로그를 처리하시기 바랍니다.
     */
    $resultMSG = "결제결과 상점 DB처리(note_url) 해쉬값 검증이 실패하였습니다.";
}

$sql = "update lt_shop_order_add_receipt
                set od_cash_info  = '$resultMSG'
                where od_receipt_type = 'ars' and od_app_no = '$LGD_OID' 
                ";
$result = sql_query($sql);

echo $resultMSG;
?>
