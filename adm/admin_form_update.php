<?php
//$sub_menu = "100200";
$sub_menu = "10";
include_once('./_common.php');
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/mailer.lib.php');

$test = false;

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();


$mb_id = trim($_POST['mb_id']);
$mb_id = preg_replace("/[^0-9a-z_]+/i", "", $mb_id);
$change_password = '';

if($w == "" || $w == "u")
{
    $mb_hp = hyphen_hp_number($_POST['mb_hp']);

    // 휴대전화번호 체크
    /*$mb_hp = hyphen_hp_number($_POST['mb_hp']);
    if($mb_hp) {
        $result = exist_mb_hp($mb_hp, $mb_id);
        if ($result)
            alert($result);
    }*/


    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
    {
        // 임시비밀번호 발급
        $change_password = rand(100000, 999999);

        //alert('존재하는 회원아이디가 아닙니다.');
        //회원아이디 생성.
        $sql = "insert into {$g5['member_table']}
                    set mb_id = '{$mb_id}'
                        , mb_password = '".get_encrypt_string($change_password)."'
                        , mb_datetime = '".G5_TIME_YMDHIS."'
                        , mb_ip = '{$_SERVER['REMOTE_ADDR']}'
                        , mb_name = '{$_POST['mb_name']}'
                        , mb_email = '{$_POST['mb_email']}'
                        , mb_hp = '{$mb_hp}'
                        , mb_level = '9'
                        , mb_email_certify = '".G5_TIME_YMDHIS."'
                        ";
    } else {
        $sql = " update {$g5['member_table']}
                    set mb_name = '{$_POST['mb_name']}'
                        ,mb_email = '{$_POST['mb_email']}'
                        ,mb_hp = '{$mb_hp}'
                    where mb_id = '{$mb_id}' ";
    }

    if($test){
        echo $sql."<br/>";
    } else {
        sql_query($sql);
    }
}

$sql_common = " set mb_company = '{$_POST['mb_company']}'
                      ,mb_dept = '{$_POST['mb_dept']}'
                      ,mb_title = '{$_POST['mb_title']}'
                      ,ad_type = '{$_POST['ad_type']}'
                      ,ad_upd_datetime = '".G5_TIME_YMDHIS."'
               ";

if($w == "")
{
    $sql_common .= ",ad_reg_datetime = '".G5_TIME_YMDHIS."'";
    $sql_common .= ",mb_id = '{$mb_id}' ";

    $sql = " insert into lt_admin
                    $sql_common ";
    if($test){
        echo $sql."<br/>";
    } else {
        sql_query($sql);
    }

    $subject = "[".$config['cf_title']."] 관리자 정보등록 안내 메일입니다.";

    $content = "";

    $content .= '<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">';
    $content .= '<div style="border:1px solid #dedede">';
    $content .= '<h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">';
    $content .= '관리자 정보등록 안내';
    $content .= '</h1>';
    $content .= '<span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">';
    $content .= '<a href="'.G5_URL.'" target="_blank">'.$config['cf_title'].'</a>';
    $content .= '</span>';
    $content .= '<p style="margin:20px 0 0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
    $content .= addslashes($_POST['mb_name'])." 관리자님은 ".G5_TIME_YMDHIS." 에 관리자 정보가 등록되었습니다.<br>";
    $content .= '</p>';
    $content .= '<p style="margin:0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
    $content .= '<span style="display:inline-block;width:100px">회원아이디</span> '.$mb_id.'<br>';

    if($change_password != '') $content .= '<span style="display:inline-block;width:100px">임시 비밀번호</span> <strong style="color:#ff3061">'.$change_password.'</strong>';

    $content .= '</p>';
    $content .= '</div>';
    $content .= '</div>';

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $_POST['mb_email'], $subject, $content, 1);

} else if($w == "u") {

    $sql = " update lt_admin
                $sql_common
            where mb_id = '{$mb_id}' ";
    if($test){
        echo $sql."<br/>";
    } else {
        sql_query($sql);
    }



    $subject = "[".$config['cf_title']."] 관리자 정보변경 안내 메일입니다.";

    $content = "";

    $content .= '<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">';
    $content .= '<div style="border:1px solid #dedede">';
    $content .= '<h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">';
    $content .= '관리자 정보변경 안내';
    $content .= '</h1>';
    $content .= '<span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">';
    $content .= '<a href="'.G5_URL.'" target="_blank">'.$config['cf_title'].'</a>';
    $content .= '</span>';
    $content .= '<p style="margin:20px 0 0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
    $content .= addslashes($_POST['mb_name'])." 관리자님은 ".G5_TIME_YMDHIS." 에 관리자 정보가 변경되었습니다.<br>";
    $content .= '</p>';
    $content .= '<p style="margin:0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
    $content .= '<span style="display:inline-block;width:100px">회원아이디</span> '.$mb_id.'<br>';
    $content .= '</p>';
    $content .= '</div>';
    $content .= '</div>';

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $_POST['mb_email'], $subject, $content, 1);
} else if($w == "d") {

    $sql = " delete from lt_admin
            where mb_id = '{$mb_id}' ";

    if($test){
        echo $sql."<br/>";
    } else {
        sql_query($sql);
    }
}

$sql = " delete from {$g5['auth_table']}  where mb_id = '{$mb_id}' ";

if($test){
    echo $sql."<br/>";
} else {
    sql_query($sql);
}

if($w == "" || $w == "u" && $_POST['ad_type'] != "super")
{
    $au_auth = array();
    for ($i=0; $i<count($chkAll); $i++)
    {
        $au_auth[$chkAll[$i]]['r'] = true;
        $au_auth[$chkAll[$i]]['w'] = true;
        $au_auth[$chkAll[$i]]['d'] = true;
    }

    for ($i=0; $i<count($chkr); $i++)
    {
        $au_auth[$chkr[$i]]['r'] = true;
    }

    for ($i=0; $i<count($chkw); $i++)
    {
        $au_auth[$chkw[$i]]['w'] = true;
    }

    for ($i=0; $i<count($chkd); $i++)
    {
        $au_auth[$chkd[$i]]['d'] = true;
    }

    foreach ($au_auth as $au_menu => $value) {

        $au_auth_var = (($value['r'])?'r':'');
        $au_auth_var .= ',';
        $au_auth_var .= (($value['w'])?'w':'');
        $au_auth_var .= ',';
        $au_auth_var .= (($value['d'])?'d':'');
        /*
        foreach ($value as $k => $v) {
            if($au_auth_var != '') $au_auth_var .= ',';
            $au_auth_var .= $k;
        }
        */
        $sql = " insert into {$g5['auth_table']}
                    set mb_id   = '{$mb_id}'
                        ,au_menu = '{$au_menu}'
                        ,au_auth = '{$au_auth_var}' ";

        if($test){
            echo $sql."<br/>";
        } else {
            sql_query($sql);
        }
    }
}

if(!$test){
    if($w == "") alert("등록되었습니다.",'./admin_list.php?'.$qstr,false);
    elseif($w == "u") alert("수정되었습니다.",'./admin_list.php?'.$qstr,false);
}
?>
