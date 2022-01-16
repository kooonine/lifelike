<?php
$sub_menu = "200150";
include_once('./_common.php');
auth_check($auth[substr($sub_menu,0,2)], "r");

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

$sql_search .= " and mb_hp != '' and mb_leave_date = '' and mb_intercept_date = '' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select mb_id, mb_name, mb_hp, mb_email, mb_sms, mb_mailling, mb_leave_date {$sql_common} {$sql_search} {$sql_order} limit 500 ";
$result = sql_query($sql);

$rows = array();

for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $rows[$i] = $row;
}
$my_array_json_string = json_encode_raw($rows,JSON_UNESCAPED_UNICODE);

echo $my_array_json_string;
?>