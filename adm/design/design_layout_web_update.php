<?php
$sub_menu = '800110';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

//
// 영카트 default
//
$onofflist = json_decode(str_replace('\\','',$_POST['onofflist']), true);

for ($i=0; $i<count($onofflist); $i++) {
    //$mainData = $onofflist[$i];
    
    //var_dump($mainData);
    $main_onoff = $onofflist[$i]['main_onoff'];
    $main_id = $onofflist[$i]['main_id'];
    
    $sql = " update lt_design_main
                set main_onoff = '{$main_onoff}'
            where   main_id = '{$main_id}'
           ";
    if(false)
    {
        //Test시 사용
        echo $sql;
        
    } else {
        sql_query($sql);
    }
}

alert('적용되었습니다.', './design_layout_web.php', false);
//goto_url("./design_layout_web.php");

?>
