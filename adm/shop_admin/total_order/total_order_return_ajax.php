<?php
include_once('./_common.php');
$now_date = date("Y-m-d H:i:s");


$result = '';
if ($sro_idDelete && $sro_idDelete != null && $sro_idDelete != '') {
    $result = $sro_idDelete;
    $delSql = "DELETE FROM sabang_return_origin WHERE sro_id IN ($sro_idRegister)";
    sql_query($delSql);
} else if ($sro_idRegister && $sro_idRegister != null && $sro_idRegister != '') {
    $result = $sro_idRegister;
    $updateSql = "UPDATE sabang_return_origin SET return_status ='접수완료', register_datetime = '{$now_date}' WHERE sro_id IN ($sro_idRegister)";
    sql_query($updateSql);
} else if ($sro_idReturnCom && $sro_idReturnCom != null && $sro_idReturnCom != '') {
    $result = $sro_idReturnCom;
    $updateSql = "UPDATE sabang_return_origin SET return_status ='환불완료', refund_datetime = '{$now_date}' WHERE sro_id IN ($sro_idReturnCom)";
    sql_query($updateSql);
} else if ($sro_idManual && $sro_idManual != null && $sro_idManual != '') { 
    $result = $sro_idManual;
    $updateSql = "UPDATE sabang_return_origin SET auto_check ='수동' WHERE sro_id IN ($sro_idManual)";
    sql_query($updateSql);
} else if ($sro_id && $sro_id != null && $sro_id != '') {
    $insertSql = "INSERT INTO sabang_return_memo SET sro_id = $sro_id, mb_id='{$member["mb_id"]}', srm_memo='$memoTextVal'";
    sql_query($insertSql);
    $result .= $sro_id;
    $result .= $memoTextVal;
} else if ($mappingCel && $mappingCel != null && $mappingCel != '') {
    $result .= 'tel cel save';
    foreach($mappingCel as $key=>$value) { 
        $mapTel = $mappingTel[$key];
        $updateSql = "UPDATE sabang_return_origin SET ro_tel_check = 1, RECEIVE_CEL = '$value', RECEIVE_TEL = '$mapTel' WHERE sro_id = $key";
        sql_query($updateSql);
    }
}else {
    $result .= $reason;
    foreach($mappingInvoie as $key=>$value) { 
        $roCnt = $mappingRoCnt[$key];
        $updateSql = "UPDATE sabang_return_origin SET ro_reason = '$reason', ro_invoice = '$value', return_status =  CASE WHEN return_status != '반품완료' THEN '입고확인' ELSE return_status END, ro_cnt ='$roCnt', ware_datetime = '{$now_date}'  WHERE sro_id = $key";
        // $updateSql = "UPDATE sabang_return_origin SET ro_reason = '$reason', ro_invoice = '$value', return_status ='입고확인', ro_cnt ='$roCnt', ware_datetime = '{$now_date}'  WHERE sro_id = $key";
        sql_query($updateSql);
    }
}

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>