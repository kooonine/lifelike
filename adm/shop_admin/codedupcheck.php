<?php
include_once('./_common.php');

$name = '';

if ($it_id)
{
    $sql = " select it_name from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
    $row = sql_fetch($sql);
    $code = $it_id;
    $name = $row['it_name'];
}
else if ($ca_id)
{
    $sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $row = sql_fetch($sql);
    $code = $ca_id;
    $name = $row['ca_name'];
}
else if ($max_ca_id)
{
    $sql = "select right(concat('000000',max(substr(it_id,10,6))+1),6) it_id from lt_shop_item where ca_id = '$max_ca_id' and length(it_id)=15 ";
    $row = sql_fetch($sql);
    $code = $max_ca_id;
    $name = $row['it_id'];
}
else if ($company_code)
{
    $company_it_code = substr($company_code, 1);
    $sql = " select right(concat('".$company_it_code."000000000',substr(max(it_id),1,15)+1),15) it_id from {$g5['g5_shop_item_table']} where ca_id3 = '$company_code' ";
    $row = sql_fetch($sql);
    $code = $company_code;
    $name = $row['it_id'];
}

echo '{ "code": "' . $code . '", "name": "' . $name . '" }';
?>