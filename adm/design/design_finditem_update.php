<?php
$sub_menu = '800600';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();


$count = count($_POST['fi_subject']);

for ($i=0; $i<$count; $i++)
{
    $_POST = array_map_deep('trim', $_POST);
    
    $fi_id = $_POST['fi_id'][$i];
    
    $fi_subject    = $_POST['fi_subject'][$i];
    $fi_status    = $_POST['fi_status'][$i];
    $fi_contents = $_POST['fi_contents'][$i];
    
    if(!$fi_subject || !$fi_status || !$fi_contents)
    {
        continue;
    }
    
    // 
    $sql = "    set fi_subject         = '$fi_subject'
                    ,fi_status         = '$fi_status'
                    ,fi_contents         = '$fi_contents'
            ";

    if(!$fi_id)
    {
        $sql = $sql." , fi_regdate = now() ";
        
        //insert
        $sql = " insert into lt_shop_finditem " . $sql;
        
    } else {
        //update
        $sql = " update lt_shop_finditem " . $sql . " where fi_id = '$fi_id' ";
    }
    
    if(false)
    {
        //Test시 사용
        echo $sql;
        
    } else {
        sql_query($sql);
    }
}


alert('적용되었습니다.', './design_finditem.php', false);
?>
