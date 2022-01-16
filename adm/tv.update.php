<?php
$sub_menu = '800830';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($w == "u") { 
    sql_query(" UPDATE lt_lifeliketv SET tv_url='$tv_url',tv_use='$tv_use', tv_update_datetime = '".G5_TIME_YMDHIS."' WHERE tv_id = '$tv_id' ");
} else if ($w == "d") {
    sql_query(" DELETE FROM lt_lifeliketv WHERE tv_id = '$tv_id' ");
} else if ($w == "s") { 

    $tvNumArr = explode(',', $tvNum);
    $i = 1;
    foreach($tvNumArr as $tvId) {
        sql_query(" UPDATE lt_lifeliketv SET tv_num='$i' WHERE tv_id = '$tvId' ");
        $i += 1;
    }
    $result = 'success';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;

} else {
    $tvNum = sql_fetch(" SELECT tv_num+1 AS tn FROM lt_lifeliketv ORDER BY tv_num DESC LIMIT 1 ");
    $sql = " INSERT INTO lt_lifeliketv (tv_url,tv_use,tv_num) VALUES ('$tv_url','$tv_use', '{$tvNum['tn']}')";
    sql_query($sql);
}



goto_url('./tv.list.php');
return;
?>


