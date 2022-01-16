<?php
include_once ('../common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

if ($is_member) {
    echo '<script>alert("이미 로그인중입니다.");window.close();</script>';
    exit;
}

$mb_id = trim($_POST['mb_id']);
$mb_name = trim($_POST['mb_name']);
$email = trim($_POST['mb_email']);

$mb_id        = clean_xss_tags($mb_id);
$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);

if (!$email){
    echo '<script>alert("메일주소 오류입니다.");window.close();</script>';
    exit;
}

$sql = " select count(*) as cnt from {$g5['member_table']} a inner join lt_admin b on a.mb_id=b.mb_id where b.ad_del = '0' and a.mb_id = '$mb_id' and a.mb_name = '$mb_name' and a.mb_email = '$email' ";    
$row = sql_fetch($sql);
if ($row['cnt'] > 1){
    echo '<script>alert("동일한 메일주소가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.");window.close();</script>';
    exit;
}

$sql = " select a.mb_no, a.mb_id, a.mb_name, a.mb_nick, a.mb_hp, a.mb_email, a.mb_datetime 
         from {$g5['member_table']} a inner join lt_admin b on a.mb_id=b.mb_id
         where b.ad_del = '0' and a.mb_id = '$mb_id' and a.mb_name = '$mb_name' and a.mb_email = '$email' ";

$mb = sql_fetch($sql);

if (!$mb['mb_id']){
    echo '<script>alert("입력하신 정보로 등록된 관리자 아이디는 존재하지 않습니다. 정확하게 입력하여 주십시오.");window.close();</script>';
    exit;
}

// 임시비밀번호 발급
$chars_array = range('a','z');
shuffle($chars_array);
$shuffle = implode('', $chars_array);
// 임시비밀번호 발급
$change_password = substr($shuffle,0,4).rand(10, 99).substr($shuffle,5,2);

$error_msg = "";

$sw = preg_match("/[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*@[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*/", $mb['mb_email']);
// 올바른 메일 주소만
if ($sw == true)
{
    $arr_change_data = array();
    $arr_change_data["고객명"] = $mb['mb_name'];
    $arr_change_data["이름"] = $mb['mb_name'];
    $arr_change_data["닉네임"] = $mb['mb_nick'];
    $arr_change_data["회원아이디"] = $mb['mb_id'];
    $arr_change_data["아이디"] = $mb['mb_id'];
    $arr_change_data["이메일"] = $mb['mb_email'];
    $arr_change_data["MEMBER_NAME"] = $mb['mb_name'];
    $arr_change_data["임시비밀번호"] = $change_password;
    
    $mail_result = mailer_autosend('회원-비밀번호 안내', $mb['mb_email'], $arr_change_data);
    
    if($mail_result){
        
        $sql = " update {$g5['member_table']}
                        set  mb_password = '".get_encrypt_string($change_password)."'
                        where mb_id = '{$mb_id}' ";
        sql_query($sql);
        
        $sql = "  insert into lt_member_memo
                        set mb_id = '{$mb_id}',
                            mm_memo = '임시비밀번호가 발송되었습니다. ".$error_msg."',
                            is_important = '1',
                            mm_mb_id = '{$member['mb_id']}',
                            mm_mb_name = '{$member['mb_name']}',
                            mm_time = '".G5_TIME_YMDHIS."' ";
        sql_query($sql);
?>
<script>
alert("입력한 이메일 주소로 메일이 발송되었습니다.");
window.close();
</script>
<?php         
    } else {
?>
<script>
alert("비밀번호 안내 EMAIL 발송이 실패하였습니다.");
window.close();
</script>
<?php 
    }
}
?>