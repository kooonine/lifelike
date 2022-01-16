<?php
include_once('./_common.php');
$now_date = date("Y-m-d H:i:s");

$result = "";
foreach($mappingCov as $key=>$value) { 
    $result .= $key.', ';

    if (get_magic_quotes_gpc()) {
        $mater = addslashes(json_encode($value['cmMaterPurchaceIp'] , JSON_UNESCAPED_UNICODE));
    }
    else {
        $mater = json_encode($value['cmMaterPurchaceIp'] , JSON_UNESCAPED_UNICODE);
    }
    
    
    // $mater = json_encode($value['cmMaterPurchaceIp'] , JSON_UNESCAPED_UNICODE);
    $updateSql = "UPDATE lt_cover_merge SET update_datetime = '$now_date', cm_scent = '{$value['cm_scent']}', cm_manufacture_gubun = '{$value['cm_manufacture_gubun']}', cm_etc ='{$value['cm_etc']}', cm_approval_date_ps ='{$value["cmApprovalDatePs"]}', cm_mater_name_jo ='$mater', cm_balju_ps = '{$value['cmBaljuPs']}', cm_ipgo_date_ps = '{$value['cmIpgoDatePs']}', cm_expected_limit_date_ps='{$value['cmExpectedLimitDatePs']}', cm_so = '{$value['cmSo']}' WHERE cm_id = $key";
    $result .= $updateSql;
    mysql_real_escape_string();
    sql_query($updateSql);
}

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>