<?php
$sub_menu = '800110';
include_once('./_common.php');

$sql = "select * from {$g5['menu_table']}
        where   me_code like '".$_POST['me_code']."%'
        and     me_depth = '".$_POST['me_depth']."'
        and     me_use = '1'
        ";

$result = sql_query($sql);
//echo $sql;
echo "[";
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    if($i != 0) echo ",";
    echo "{ \"me_code\":\"".$row['me_code']."\", \"me_name\":\"".$row['me_name']."\", \"me_link\":\"".$row['me_link']."\" }";
}
echo "]";
?>