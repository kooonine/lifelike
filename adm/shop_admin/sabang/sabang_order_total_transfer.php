<? #!/usr/local/php53/bin/php


include_once('./_common.php');


// 여기서 합쳐보띾 ㅋㅋ?? ? ? ? ? ?
$sooSql = "SELECT * FROM sabang_ordr_origin  WHERE total_transfer = 0";
// 
$sooTransferSql = "";

$sooUpdateSql = "";
$send_xml_sql = "select xml_name from sabang_order_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_xml_sql);

// 둘다 트리거로 만들까??
// 물음
// ??? durlsrk !!dd vvsvvsvsvsv
// ssjjonnn a gk 
// ??? ?? 

foreach($object->children() as $orders) {
  $sql_common = "IDX = '{$orders->IDX}'
                  ,ORDER_ID = '{$orders->ORDER_ID}'
                  ,MALL_ID = '{$orders->MALL_ID}'
                  ,MALL_USER_ID = '{$orders->MALL_USER_ID}'
                  ,ORDER_STATUS = '{$orders->ORDER_STATUS}'
                  ,USER_ID = '{$orders->USER_ID}'
                  ,USER_NAME = '{$orders->USER_NAME}'
                  ,USER_TEL = '{$orders->USER_TEL}'
                  ,USER_CEL = '{$orders->USER_CEL}'
                  ,USER_EMAIL = '{$orders->USER_EMAIL}'
                  ,RECEIVE_TEL = '{$orders->RECEIVE_TEL}'
                  ,RECEIVE_CEL = '{$orders->RECEIVE_CEL}'
                  ,RECEIVE_EMAIL = '{$orders->RECEIVE_EMAIL}'
                  ,DELV_MSG = '{$orders->DELV_MSG}'
                  ,RECEIVE_NAME = '{$orders->RECEIVE_NAME}'
                  ,RECEIVE_ZIPCODE = '{$orders->RECEIVE_ZIPCODE}'
                  ,RECEIVE_ADDR = '{$orders->RECEIVE_ADDR}'
                  ,TOTAL_COST = '{$orders->TOTAL_COST}'
                  ,PAY_COST = '{$orders->PAY_COST}'
                  ,ORDER_DATE = '{$orders->ORDER_DATE}'
                  ,PARTNER_ID = '{$orders->PARTNER_ID}'
                  ,DPARTNER_ID = '{$orders->DPARTNER_ID}'
                  ,MALL_PRODUCT_ID = '{$orders->MALL_PRODUCT_ID}'
                  ,PRODUCT_ID = '{$orders->PRODUCT_ID}'
                  ,SKU_ID = '{$orders->SKU_ID}'
                  ,P_PRODUCT_NAME = '{$orders->P_PRODUCT_NAME}'
                  ,P_SKU_VALUE = '{$orders->P_SKU_VALUE}'
                  ,PRODUCT_NAME = '{$orders->PRODUCT_NAME}'
                  ,SALE_COST = '{$orders->SALE_COST}'
                  ,MALL_WON_COST = '{$orders->MALL_WON_COST}'
                  ,WON_COST = '{$orders->WON_COST}'
                  ,SKU_VALUE = '{$orders->SKU_VALUE}'
                  ,SALE_CNT = '{$orders->SALE_CNT}'
                  ,DELIVERY_METHOD_STR = '{$orders->DELIVERY_METHOD_STR}'
                  ,DELV_COST = '{$orders->DELV_COST}'
                  ,COMPAYNY_GOODS_CD = '{$orders->COMPAYNY_GOODS_CD}'
                  ,SKU_ALIAS = '{$orders->SKU_ALIAS}'
                  ,BOX_EA = '{$orders->BOX_EA}'
                  ,JUNG_CHK_YN = '{$orders->JUNG_CHK_YN}'
                  ,MALL_ORDER_SEQ = '{$orders->MALL_ORDER_SEQ}'
                  ,MALL_ORDER_ID = '{$orders->MALL_ORDER_ID}'
                  ,ETC_FIELD3 = '{$orders->ETC_FIELD3}'
                  ,ORDER_GUBUN = '{$orders->ORDER_GUBUN}'
                  ,P_EA = '{$orders->P_EA}'
                  ,REG_DATE = '{$orders->REG_DATE}'
                  ,ORDER_ETC_1 = '{$orders->ORDER_ETC_1}'
                  ,ORDER_ETC_2 = '{$orders->ORDER_ETC_2}'
                  ,ORDER_ETC_3 = '{$orders->ORDER_ETC_3}'
                  ,ORDER_ETC_4 = '{$orders->ORDER_ETC_4}'
                  ,ORDER_ETC_5 = '{$orders->ORDER_ETC_5}'
                  ,ORDER_ETC_6 = '{$orders->ORDER_ETC_6}'
                  ,ORDER_ETC_7 = '{$orders->ORDER_ETC_7}'
                  ,ORDER_ETC_8 = '{$orders->ORDER_ETC_8}'
                  ,ORDER_ETC_9 = '{$orders->ORDER_ETC_9}'
                  ,ORDER_ETC_10 = '{$orders->ORDER_ETC_10}'
                  ,ORDER_ETC_11 = '{$orders->ORDER_ETC_11}'
                  ,ORDER_ETC_12 = '{$orders->ORDER_ETC_12}'
                  ,ORDER_ETC_13 = '{$orders->ORDER_ETC_13}'
                  ,ORDER_ETC_14 = '{$orders->ORDER_ETC_14}'
                  ,ord_field2 = '{$orders->ord_field2}'
                  ,copy_idx = '{$orders->copy_idx}'
                  ,GOODS_NM_PR = '{$orders->GOODS_NM_PR}'
                  ,GOODS_KEYWORD = '{$orders->GOODS_KEYWORD}'
                  ,ORD_CONFIRM_DATE = '{$orders->ORD_CONFIRM_DATE}'
                  ,RTN_DT = '{$orders->RTN_DT}'
                  ,CHNG_DT = '{$orders->CHNG_DT}'
                  ,DELIVERY_CONFIRM_DATE = '{$orders->DELIVERY_CONFIRM_DATE}'
                  ,CANCEL_DT = '{$orders->CANCEL_DT}'
                  ,CLASS_CD1 = '{$orders->CLASS_CD1}'
                  ,CLASS_CD2 = '{$orders->CLASS_CD2}'
                  ,CLASS_CD3 = '{$orders->CLASS_CD3}'
                  ,CLASS_CD4 = '{$orders->CLASS_CD4}'
                  ,BRAND_NM = '{$orders->BRAND_NM}'
                  ,DELIVERY_ID = '{$orders->DELIVERY_ID}'
                  ,INVOICE_NO = '{$orders->INVOICE_NO}'
                  ,HOPE_DELV_DATE = '{$orders->HOPE_DELV_DATE}'
                  ,FLD_DSP = '{$orders->FLD_DSP}'
                  ,INV_SEND_MSG = '{$orders->INV_SEND_MSG}'
                  ,MODEL_NO = '{$orders->MODEL_NO}'
                  ,SET_GUBUN = '{$orders->SET_GUBUN}'
                  ,ETC_MSG = '{$orders->ETC_MSG}'
                  ,DELV_MSG1 = '{$orders->DELV_MSG1}'
                  ,MUL_DELV_MSG = '{$orders->MUL_DELV_MSG}'
                  ,BARCODE = '{$orders->BARCODE}'
                  ,INV_SEND_DM = '{$orders->INV_SEND_DM}'
                  ,DELIVERY_METHOD_STR2 = '{$orders->DELIVERY_METHOD_STR2}'
  
  
  
  ";
  // echo $orders->ORDER_ID . ", ";
  // echo $orders->MALL_ID . ", ";
  // echo $orders->MALL_USER_ID . ", ";
  // echo $orders->ORDERSTATUS . ", ";
  // echo $orders->USER_ID . "<br>";

  if(!empty($orders->IDX)){
    $sql = " insert sabang_order_origin set $sql_common ";
    sql_query($sql);
  }


}

