<?php
include_once ('../common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

if ($is_member) {
    echo '<script>alert("이미 로그인중입니다.");window.close();</script>';
    exit;
}

$mb_name        = trim($_POST['mb_name']);
$mb_email       = trim($_POST['mb_email']);
$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);

if (!$mb_email){
    echo '<script>alert("메일주소 오류입니다.");window.close();</script>';
    exit;
}

// 이름, 닉네임에 utf-8 이외의 문자가 포함됐다면 오류
// 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
$tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
if($tmp_mb_name != $mb_name) {
    echo '<script>alert("이름을 정확하게 입력하여 주십시오.");window.close();</script>';
    exit;
}

$sql = " select count(*) as cnt from {$g5['member_table']} a inner join lt_admin b on a.mb_id=b.mb_id where b.ad_del = '0' and a.mb_name = '$mb_name' and a.mb_email = '$mb_email' ";
$row = sql_fetch($sql);


if ($row['cnt'] > 1){
    echo '<script>alert("동일한 메일주소가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.");window.close();</script>';
    exit;
}
    
$sql = " select a.mb_no, a.mb_id, a.mb_name, a.mb_nick, a.mb_email, a.mb_datetime 
         from {$g5['member_table']} a inner join lt_admin b on a.mb_id=b.mb_id
         where b.ad_del = '0' and a.mb_name = '$mb_name' and a.mb_email = '$mb_email' ";

$mb = sql_fetch($sql);

if (!$mb['mb_id']){
    echo '<script>alert("입력하신 정보로 등록된 관리자 아이디는 존재하지 않습니다. 정확하게 입력하여 주십시오.");window.close();</script>';
    exit;
}

$subject = "[".$config['cf_title']."] 요청하신 아이디 찾기 안내 메일입니다.";

$content = "";

$content .= '<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">';
$content .= '<div style="border:1px solid #dedede">';
$content .= '<h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">';
$content .= '아이디 찾기 안내';
$content .= '</h1>';
$content .= '<span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">';
$content .= '<a href="'.G5_URL.'" target="_blank">'.$config['cf_title'].'</a>';
$content .= '</span>';
$content .= '<p style="margin:20px 0 0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
$content .= addslashes($mb['mb_name'])." 회원님은 ".G5_TIME_YMDHIS." 에 아이디 찾기 요청을 하셨습니다.<br>";
$content .= '</p>';
$content .= '<p style="margin:0;padding:30px 30px 30px;border-bottom:1px solid #eee;line-height:1.7em">';
$content .= '<span style="display:inline-block;width:100px">회원아이디</span> '.$mb['mb_id'].'<br>';
$content .= '</p>';
$content .= '</div>';
$content .= '</div>';

mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb['mb_email'], $subject, $content, 1);
?>
<script>
alert("입력한 이메일 주소로 메일이 발송되었습니다.");
window.close();
</script>