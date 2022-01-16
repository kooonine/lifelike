<?php
$sub_menu = "200130";
include_once('./_common.php');

//print_r2($_POST); exit;

check_demo();

auth_check($auth[substr($sub_menu,0,2)], 'w');

check_admin_token();

$count = count($_POST['sf_no']);

if(!$count)
    alert('수정할 데이타가 없습니다.');

for ($i=0; $i<$count; $i++)
{
    $sf_no     = $_POST['sf_no'][$i];
    
    $sf_user_use = ($_POST['sf_user_use'][$sf_no])?'1':'0';
    $sf_admin_use = ($_POST['sf_admin_use'][$sf_no])?'1':'0';
    
    $sf_user_timelimit_use = ($_POST['sf_user_timelimit_use'][$sf_no])?'1':'0';
    $sf_user_starttime = str_pad($_POST['sf_user_starttime_h'][$i],2,'0', STR_PAD_LEFT).":".str_pad($_POST['sf_user_starttime_m'][$i],2,'0', STR_PAD_LEFT);
    $sf_user_endtime = str_pad($_POST['sf_user_endtime_h'][$i],2,'0', STR_PAD_LEFT).":".str_pad($_POST['sf_user_endtime_m'][$i],2,'0', STR_PAD_LEFT);
    
    $sf_admin_timelimit_use = ($_POST['sf_admin_timelimit_use'][$sf_no])?'1':'0';
    $sf_admin_starttime = str_pad($_POST['sf_admin_starttime_h'][$i],2,'0', STR_PAD_LEFT).":".str_pad($_POST['sf_admin_starttime_m'][$i],2,'0', STR_PAD_LEFT);
    $sf_admin_endtime = str_pad($_POST['sf_admin_endtime_h'][$i],2,'0', STR_PAD_LEFT).":".str_pad($_POST['sf_admin_endtime_m'][$i],2,'0', STR_PAD_LEFT);
    
    $sf_user_template_code = ($_POST['sf_user_template_code'][$i])?$_POST['sf_user_template_code'][$i]:'';
    $sf_admin_template_code = ($_POST['sf_admin_template_code'][$i])?$_POST['sf_admin_template_code'][$i]:'';
    
    $sql = " update lt_sms_form
                    set sf_user_use    = '{$sf_user_use}',
                        sf_admin_use    = '{$sf_admin_use}',
                        sf_user_msg = '{$_POST['sf_user_msg'][$i]}',
                        sf_admin_msg = '{$_POST['sf_admin_msg'][$i]}',
                        sf_user_timelimit_use    = '{$sf_user_timelimit_use}',
                        sf_user_starttime    = '{$sf_user_starttime}',
                        sf_user_endtime    = '{$sf_user_endtime}',
                        sf_admin_timelimit_use    = '{$sf_admin_timelimit_use}',
                        sf_admin_starttime    = '{$sf_admin_starttime}',
                        sf_admin_endtime    = '{$sf_admin_endtime}',
                        sf_datetime = now(),
                        sf_user_template_code    = '{$sf_user_template_code}',
                        sf_admin_template_code    = '{$sf_admin_template_code}'
                  where sf_no         = '{$sf_no}' ";
    
    sql_query($sql);
}

goto_url('./configform_sms_autoSend_config.php?sf_type='.$sf_type.'&sf_cate='.$sf_cate);
?>
