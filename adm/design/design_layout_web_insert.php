<?php
$sub_menu = '800110';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

$main_type2 = $_POST['main_type2'];
if($_POST['main_type1'] == "rolling")
{
    $main_type2 = $_POST['selMain_type2'];
}

$row = sql_fetch("select max(main_order) + 1 cnt from lt_design_main where main_order != 999");
$main_order = $row['cnt'];

$sql = " insert lt_design_main         
            set  main_name= '{$_POST['main_name']}'
                , main_fixed = 'N'
                , main_order = '{$main_order}'
                , main_onoff = 'N'
                , mobile_onoff = 'N'
                , main_type1 = '{$_POST['main_type1']}'
                , main_type2 = '{$main_type2}'
                , main_view_data = ''
                , main_datetime = now()
       ";

if(false)
{
    //Test시 사용
    echo $sql;
    
} else {
    sql_query($sql);
}

$sql = " insert lt_design_main_mobile
            set  main_name= '{$_POST['main_name']}'
                , main_fixed = 'N'
                , main_order = '{$main_order}'
                , main_onoff = 'N'
                , mobile_onoff = 'N'
                , main_type1 = '{$_POST['main_type1']}'
                , main_type2 = '{$main_type2}'
                , main_view_data = ''
                , main_datetime = now()
       ";

if(false)
{
    //Test시 사용
    echo $sql;
    
} else {
    sql_query($sql);
}

alert('저장되었습니다.', './design_layout_web.php', false);
//goto_url("./design_layout_web.php");

?>
