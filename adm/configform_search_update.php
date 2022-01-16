<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

//
// 영카트 default
//
$sql = " update {$g5['config_table']}
            set 
                cf_add_seo_use = '{$_POST['cf_add_seo_use']}',
                cf_add_meta_common_title = '{$_POST['cf_add_meta_common_title']}',
                cf_add_meta_common_author = '{$_POST['cf_add_meta_common_author']}',
                cf_add_meta_common_description = '{$_POST['cf_add_meta_common_description']}',
                cf_add_meta_common_keywords = '{$_POST['cf_add_meta_common_keywords']}',
                
                cf_add_meta_category_title = '{$_POST['cf_add_meta_category_title']}',
                cf_add_meta_category_author = '{$_POST['cf_add_meta_category_author']}',
                cf_add_meta_category_description = '{$_POST['cf_add_meta_category_description']}',
                cf_add_meta_category_keywords = '{$_POST['cf_add_meta_category_keywords']}',
                
                cf_add_meta_detail_title = '{$_POST['cf_add_meta_detail_title']}',
                cf_add_meta_detail_author = '{$_POST['cf_add_meta_detail_author']}',
                cf_add_meta_detail_description = '{$_POST['cf_add_meta_detail_description']}',
                cf_add_meta_detail_keywords = '{$_POST['cf_add_meta_detail_keywords']}',
                
                cf_add_meta_bbs_title = '{$_POST['cf_add_meta_bbs_title']}',
                cf_add_meta_bbs_author = '{$_POST['cf_add_meta_bbs_author']}',
                cf_add_meta_bbs_description = '{$_POST['cf_add_meta_bbs_description']}',
                cf_add_meta_bbs_keywords = '{$_POST['cf_add_meta_bbs_keywords']}',
                
                cf_add_html_head_pc = '{$_POST['cf_add_html_head_pc']}',
                cf_add_html_body_pc = '{$_POST['cf_add_html_body_pc']}',
                cf_add_html_head_mobile = '{$_POST['cf_add_html_head_mobile']}',
                cf_add_html_body_mobile = '{$_POST['cf_add_html_body_mobile']}',
                
                cf_add_robots_pc = '{$_POST['cf_add_robots_pc']}',
                cf_add_robots_mobile = '{$_POST['cf_add_robots_mobile']}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_search.php");
}
?>
