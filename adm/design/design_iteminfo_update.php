<?php
$sub_menu = '800700';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

$_POST = array_map_deep('trim', $_POST);

$if_id = $_POST['if_id'];
$title    = $_POST['title'];
$ca_name1    = $_POST['ca_name1'];
$ca_name2    = $_POST['ca_name2'];
$ca_name3    = $_POST['ca_name3'];
$ca_name4    = $_POST['ca_name4'];

$sql = " set title         = '$title'
            ,ca_name1         = '$ca_name1'
            ,ca_name2         = '$ca_name2'
            ,ca_name3         = '$ca_name3'
            ,ca_name4         = '$ca_name4'
    ";

$count = count($_POST['name']);

$articleArray = array();
for ($i=0; $i<$count; $i++)
{
    $name = $_POST['name'][$i];
    $value = $_POST['value'][$i];
    if(!$name)
    {
        continue;
    }
    $article = array("name" => $name, "value" => $value);
    $articleArray[] = $article;
}

$strArticle = json_encode_raw($articleArray, JSON_UNESCAPED_UNICODE);

$sql = $sql." , article = '$strArticle' ";


if($if_id){
    //수정
    $sql = " update lt_shop_info " . $sql . " where if_id = '$if_id' ";
} else {
    //신규
    $sql = " insert into lt_shop_info " . $sql;
}


if(false)
{
    //Test시 사용
    echo $sql;
    
} else {
    sql_query($sql);
    alert('적용되었습니다.', './design_iteminfo.php?'.$qstr, false);
}

?>
