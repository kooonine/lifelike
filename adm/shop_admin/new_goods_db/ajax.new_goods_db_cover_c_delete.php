<?php
include_once('./_common.php');
$now_date = date("Y-m-d H:i:s");

$result = "";

$cmId = $_POST['selects_id'];

// 배정표 삭제
$cover_c = "SELECT jo_id  FROM lt_cover_merge WHERE cm_id in ($cmId)";
$cover_c_res = sql_query($cover_c);

$jo_ids = array();

for($i = 0 ; $c_row = sql_fetch_array($cover_c_res); $i++){
    $jo = $c_row['jo_id'];

    $jo_ids[$i]= $jo;
}

$jos = implode( ',', $jo_ids );


if(!empty($cmId)){
    $cm_del = "DELETE FROM lt_cover_merge WHERE cm_id in ($cmId)";
    sql_query($cm_del);
    $ca_del = "DELETE FROM lt_cover_allocation WHERE jo_id in ($jos)";
    sql_query($ca_del);
}


$result = 200;
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>