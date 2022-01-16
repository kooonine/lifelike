<?php
include_once ('../common.php');

if (!trim($_SESSION['ss_mb_id']))
    alert('로그인 되어 있지 않습니다.');

$mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';

if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

if (!$pwd2)
    alert('비밀번호가 넘어오지 않았습니다.');
    
if ($pwd2 != $pwd3)
    alert('비밀번호가 일치하지 않습니다.');

$sql = " select mb_no, mb_password from {$g5['member_table']} where mb_id = '$mb_id' ";
$mb = sql_fetch($sql);

if (!check_password($pwd1, $mb['mb_password']))
    alert('비밀번호가 일치하지 않습니다.');
    
$sql = " update {$g5['member_table']}
         set mb_password = '".get_encrypt_string($pwd2)."' 
        where mb_id = '$mb_id' ";

sql_query($sql);

?>
<script>
alert("비밀번호가 변경되었습니다.");
window.close();
document.location.replace("<?php echo str_replace('&amp;', '&', G5_ADMIN_URL.'/configform_pwd.php'); ?>");
</script>