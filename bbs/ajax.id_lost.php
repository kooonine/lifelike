<?php
include_once('./_common.php');
$name = $_POST['name'];
$auth_type = $_POST['auth_type'];
$auth_text = $_POST['auth_text'];


if($name == '' || $auth_type  == '' || $auth_text  == ''){
    ?>
    F<script>
    	alert('모든 정보를 입력 해 주세요');
    </script>
    <?php 
    
    
}else {
    if($auth_type == 'phone'){
        $auth_text = hyphen_hp_number($auth_text);
        
        $sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from {$g5['member_table']} where mb_name = '$name' and mb_hp = '$auth_text'";
    }else {
        $sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from {$g5['member_table']} where mb_name = '$name' and mb_email = '$auth_text'";
    }
    $mb = sql_fetch($sql);
    
    if (!$mb['mb_id']){
        ?>
        F<script>
        alert('일치하는 회원정보가 없습니다.'); 
        </script>
        <?php 
    }else {
        $mb_id = $mb['mb_id'];
    ?>
    S
    <p class="big">
    <?php echo substr($mb_id, 0, mb_strlen($mb_id)-2).'**'?>
    </p>
<?php }
}?>