<?
include_once('./_common.php');
$now_date = date("Y-m-d H:i:s");
foreach($inputOpt as $key=>$value) {
    $result .= $key.', ';
    $updateSql = "UPDATE lt_cover_allocation SET ca_input = '$value', update_datetime = '$now_date' WHERE ca_id = $key";
    $result .= $updateSql;
    sql_query($updateSql);
}

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>