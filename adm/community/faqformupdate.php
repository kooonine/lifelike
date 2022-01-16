<?php
//$sub_menu = '300700';
$sub_menu = '30';
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

$sql_common = " fa_subject = '$fa_subject',
                fa_content = '$fa_content',
                fa_order = '$fa_order',
                fa_category1 = '$fa_category1',
                fa_category2 = '$fa_category2' ";

if ($w == "")
{
    $sql = " insert {$g5['faq_table']}
                set fm_id ='$fm_id',
                    $sql_common ";
    sql_query($sql);

    $fa_id = sql_insert_id();
}
else if ($w == "u")
{
    $sql = " update {$g5['faq_table']}
                set $sql_common
              where fa_id = '$fa_id' ";
    sql_query($sql);
}
else if ($w == "d")
{
	$sql = " delete from {$g5['faq_table']} where fa_id = '$fa_id' ";
    sql_query($sql);
}

//if ($w == 'd')
    goto_url("./faqlist.php?fa_category1=$fa_category1");
//else
//    goto_url("./faqlist.php?w=u&amp;fm_id=$fm_id&amp;fa_id=$fa_id");
?>
