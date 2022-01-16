<?php
include_once './../common.php';

$token = explode(".",$_REQUEST['id_token']);
$id_token = base64_decode($token[1]);
$token_data = json_decode($id_token,true);
// $id_token2 ='{"iss":"https://appleid.apple.com","aud":"com.litandard.lifelike.singin","exp":1600218935,"iat":1600132535,"sub":"001772.65de7668e3fd45c58a3b29b7f76a4074.0613","c_hash":"vNJkwM3gAcH6fgDC6AQzBw","email":"ef95yesf8f@privaterelay.appleid.com","email_verified":"true","is_private_email":"true","auth_time":1600132535,"nonce_supported":true}';

$checkEmail = $token_data['email'];
// 이거 참고 !!
// $test2 = json_decode($id_token2,true);
// $checkEmail = $test2['email'];

if ($appCheck) {
    $sql = "SELECT count(*) as cnt FROM  lt_member WHERE mb_email = '$appCheck'";
    $row = sql_fetch($sql);
    $count = $row['cnt'];
    // alert('ttt');
    if($count==0) {
        $register_url = '/plugin/social/register_member.php?provider=apple&ecode='.$appCheck; 
        Header("Location:$register_url"); 
    } else if ($count==1) {
        $appLoginID = explode("@",$appCheck);
        $mb_id =  'apple_'.$appLoginID[0];
        $register_url = '/bbs/login_check.php?appopt='.$mb_id;
        Header("Location:$register_url"); 
    } else {
        Header("Location:/"); 
    Header("Location:/"); 
        Header("Location:/"); 
    }
}

?>
<script type="text/javascript">
window.opener.location.replace('/auth/apple_login.php?appCheck=<?=$checkEmail ?>'); 
self.close();
</script>

