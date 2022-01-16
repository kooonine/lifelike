<?php
//$sub_menu = '300700';
$sub_menu = '30';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], "w");

$fm_id = 1;
$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);
$fa_category1_arr = array();
$fa_category1_arr = explode("|", $fm['fm_subject']);

$fa_id = isset($fa_id) ? (int) $fa_id : 0;

$html_title = 'FAQ ';

if ($w == "u")
{
    $html_title .= " 수정";
    $readonly = " readonly";

    $sql = " select * from {$g5['faq_table']} where fa_id = '$fa_id' ";
    $fa = sql_fetch($sql);
    if (!$fa['fa_id']) alert("등록된 자료가 없습니다.");
}
else
    $html_title .= ' 항목 입력';

$g5['title'] = $html_title.' 관리';

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><div class="x_panel">
<div class="x_content">

<form name="frmfaqform" action="./faqformupdate.php" onsubmit="return frmfaqform_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
<input type="hidden" name="fa_id" value="<?php echo $fa_id; ?>">
<input type="hidden" name="token" value="">

<div class="col-md-12 col-sm-12 col-xs-12 text-right">
    <input type="submit" value="저장" class="btn-success btn" accesskey="s">
    <a href="./faqlist.php?fm_id=<?php echo $fm_id; ?>&amp;fa_category1=<?php echo $fa_category1; ?>" class="btn btn_02">목록</a>
</div>

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">카테고리1</th>
        <td>
            <select name="fa_category1" id="fa_category1" class="frm_input" >
            <?php for ($i=0; $i<count($fa_category1_arr); $i++) {
                
                echo '<option value="'.$fa_category1_arr[$i].'" '.get_selected($fa_category1_arr[$i], $fa_category1).'>'.$fa_category1_arr[$i].'</option>';
                
            } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">카테고리2</th>
        <td>
			<?php 
			$sql = "select fa_category2 from {$g5['faq_table']} where fa_category1 = '$fa_category1' group by fa_category2 ";
			$fa_category2_row = sql_query($sql);
			
			if(($fa_category2_row -> num_rows) > 0) {
			?>
			<?php echo help('카테고리2의 카테고리명 수정이 필요한 경우 내용을 수정바랍니다.'); ?>
            <select name="selfa_category2" id="selfa_category2" class="frm_input" >
            	<option value="" <?php if(!$fa) echo 'selected';?>>신규입력</option>
			<?php             	
            	for ($i=0; $row=sql_fetch_array($fa_category2_row); $i++) {
            	    echo '<option value="'.$row['fa_category2'].'" '.get_selected($row['fa_category2'], $fa['fa_category2']).'>'.$row['fa_category2'].'</option>';
           	} ?>
            </select>
			<?php } ?>
        	<input type="text" class="frm_input" size="50" id="fa_category2" name="fa_category2" value="<?php echo $fa['fa_category2']; ?>">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="fa_order">출력순서</label></th>
        <td>
            <?php echo help('숫자가 작을수록 FAQ 페이지에서 먼저 출력됩니다.'); ?>
            
            <input type="text" name="fa_order" value="<?php echo $fa['fa_order']; ?>" id="fa_order" class="frm_input" maxlength="10" size="10">
            <?php if ($w == 'u') { ?><a href="<?php echo G5_BBS_URL; ?>/faq.php?fm_id=<?php echo $fm_id; ?>" class="btn_frmline">내용보기</a><?php } ?>
        </td>
    </tr>
    <tr>
        <th scope="row">질문</th>
        <td><?php echo editor_html('fa_subject', get_text($fa['fa_subject'], 0)); ?></td>
    </tr>
    <tr>
        <th scope="row">답변</th>
        <td><?php echo editor_html('fa_content', get_text($fa['fa_content'], 0)); ?></td>
    </tr>
    </tbody>
    </table>
</div>



</form>

</div>
</div></div></div>


<script>

$("#selfa_category2").change(function(){
	$("#fa_category2").val($("#selfa_category2").val());
	$("#fa_category2").focus();
});


function frmfaqform_check(f)
{
    <?php echo get_editor_js('fa_subject'); ?>
    <?php echo get_editor_js('fa_content'); ?>
    
    errmsg = "";
    errfld = "";

    check_field(f.fa_category2, "카테고리2를 입력하세요.");

    check_field(f.fa_subject, "질문을 입력하세요.");
    //check_field(f.fa_content, "답변을 입력하세요.");
	
    if (errmsg != "")
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    return true;
}

// document.getElementById('fa_order').focus(); 포커스 해제
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
