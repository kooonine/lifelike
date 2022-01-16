<?php
$sub_menu = "800300";
include_once('./_common.php');
include_once(G5_EDITOR_PATH."/cheditor5/editor.lib.php");
$config['cf_editor'] = 'cheditor5';

auth_check($auth[substr($sub_menu,0,2)], 'r');

$html_title = '회원메일';

if ($w == 'u') {
    $html_title .= '수정';
    $readonly = ' readonly';

    $sql = " select * from {$g5['mail_table']} where ma_id = '{$ma_id}' ";
    $ma = sql_fetch($sql);
    if (!$ma['ma_id'])
        alert('등록된 자료가 없습니다.');
} else {
    $html_title .= '입력';
}

$g5['title'] = $html_title;
include_once('./admin.head.php');
?>
<div class="row">
		<div class="x_panel">
		
			<div class="x_title">
				<h4><span class="fa fa-check-square"></span> 메일 폼 수정<small></small></h4>
				<div class="clearfix"></div>
			</div>

<div class="local_desc"><p>메일 내용에 {이름} , {닉네임} , {회원아이디} , {이메일} 처럼 내용에 삽입하면 해당 내용에 맞게 변환하여 메일을 발송합니다.</p></div>

<form name="fmailform" id="fmailform" action="./mail_update.php" onsubmit="return fmailform_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w ?>" id="w">
<input type="hidden" name="ma_id" value="<?php echo $ma['ma_id'] ?>" id="ma_id">
<input type="hidden" name="token" value="" id="token">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ma_name">대상</label></th>
        <td><?php 
            if($ma['ma_type'] == '0') echo '고객'; 
            elseif($ma['ma_type'] == '1') echo '관리자';
            elseif($ma['ma_type'] == '2') echo '브랜드';
        ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ma_name">메일 항목<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="ma_name" value="<?php echo $ma['ma_name'] ?>" id="ma_name" required class="required frm_input" size="100"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ma_subject">메일 제목<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="ma_subject" value="<?php echo $ma['ma_subject'] ?>" id="ma_subject" required class="required frm_input" size="100"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ma_content">메일 내용<strong class="sound_only">필수</strong></label></th>
        <td><?php echo editor_html("ma_content", get_text($ma['ma_content'], 0)); ?></td>
    </tr>
    <tr>
        <th scope="row"><label for="ma_name">사용 여부<strong class="sound_only">필수</strong></label></th>
        <td>
			<div class="radio">
        	<label id="ma_use1"><input type="radio" name="ma_use" value="1" <?php echo get_checked($ma['ma_use'], '1') ?> > 사용함</label>&nbsp;&nbsp;&nbsp;
        	<label id="ma_use0"><input type="radio" name="ma_use" value="0" <?php echo get_checked($ma['ma_use'], '0') ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
        	</div>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="">
    <input type="submit" class="btn_submit btn" accesskey="s" value="확인">
</div>
</form>

</div></div>

<script>
function fmailform_check(f)
{
    errmsg = "";
    errfld = "";

    check_field(f.ma_subject, "제목을 입력하세요.");
    //check_field(f.ma_content, "내용을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    <?php echo get_editor_js("ma_content"); ?>
    <?php echo chk_editor_js("ma_content"); ?>

    return true;
}

document.fmailform.ma_subject.focus();
</script>

<?php
include_once('./admin.tail.php');
?>
