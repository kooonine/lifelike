<?php
$sub_menu = "200820";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$g5['title'] = '사용자 우회접속';
include_once('./admin.head.php');
?>

<form name="finception" method="post" action="./inception.php" onsubmit="return form_submit(this);">
    <div class="visit_del_bt">
        <label for="mb_id">우회할 사용자 ID<strong class="sound_only"> 필수</strong></label>
        <input type="mb_id" name="mb_id" id="mb_id" class="frm_input required">
    </div>
    <div class="visit_del_bt">
        <label for="pass">관리자 비밀번호<strong class="sound_only"> 필수</strong></label>
        <input type="password" name="pass" id="pass" class="frm_input required">
        <input type="submit" value="확인" class="btn_submit">
    </div>
</form>
<?php
include_once('./admin.tail.php');
?>