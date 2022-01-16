<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

if (!$board['bo_table']) {
    alert('존재하지 않는 게시판입니다.', G5_URL);
}

$view = get_view($write, $board, $board_skin_path);

if($view['wr_9'] == '답변전') {
    $w = "r";
    
    // 게시글 배열 참조
    $reply_array = &$write;
    if (strlen($reply_array['wr_reply']) == 10)
        alert('더 이상 답변하실 수 없습니다.\\n\\n답변은 10단계 까지만 가능합니다.');
    $reply_len = strlen($reply_array['wr_reply']) + 1;
    if ($board['bo_reply_order']) {
        $begin_reply_char = 'A';
        $end_reply_char = 'Z';
        $reply_number = +1;
        $sql = " select MAX(SUBSTRING(wr_reply, {$reply_len}, 1)) as reply from {$write_table} where wr_num = '{$reply_array['wr_num']}' and SUBSTRING(wr_reply, {$reply_len}, 1) <> '' ";
    } else {
        $begin_reply_char = 'Z';
        $end_reply_char = 'A';
        $reply_number = -1;
        $sql = " select MIN(SUBSTRING(wr_reply, {$reply_len}, 1)) as reply from {$write_table} where wr_num = '{$reply_array['wr_num']}' and SUBSTRING(wr_reply, {$reply_len}, 1) <> '' ";
    }
    if ($reply_array['wr_reply']) $sql .= " and wr_reply like '{$reply_array['wr_reply']}%' ";
    $row = sql_fetch($sql);
    
    if (!$row['reply'])
        $reply_char = $begin_reply_char;
    else if ($row['reply'] == $end_reply_char) // A~Z은 26 입니다.
        alert('더 이상 답변하실 수 없습니다.\\n\\n답변은 26개 까지만 가능합니다.');
    else
        $reply_char = chr(ord($row['reply']) + $reply_number);
                
    $reply = $reply_array['wr_reply'] . $reply_char;
    
    $title_msg = '글답변';
    
    $write['wr_subject'] = 'Re: '.$write['wr_subject'];
    
    // 글자수 제한 설정값
    if ($is_admin || $board['bo_use_dhtml_editor'])
    {
        $write_min = $write_max = 0;
    }
    else
    {
        $write_min = (int)$board['bo_write_min'];
        $write_max = (int)$board['bo_write_max'];
    }
    $is_dhtml_editor = true;
    
    $subject = "";
    if (isset($write['wr_subject'])) {
        $subject = str_replace("\"", "&#034;", get_text(cut_str($write['wr_subject'], 255), 0));
    }
    
    $content = '';
    if (!strstr($write['wr_option'], 'html')) {
        $content = "\n\n\n &gt; "
            ."\n &gt; "
                ."\n &gt; ".str_replace("\n", "\n> ", get_text($write['wr_content'], 0))
                ."\n &gt; "
                    ."\n &gt; ";
                    
    }
    $editor_html = editor_html('wr_content', $content, $is_dhtml_editor);
    $editor_js = '';
    $editor_js .= get_editor_js('wr_content', $is_dhtml_editor);
    $editor_js .= chk_editor_js('wr_content', $is_dhtml_editor);
}

$html = 1;
/*if (strstr($view['wr_option'], 'html1'))
    $html = 1;
else if (strstr($view['wr_option'], 'html2'))
    $html = 2;
*/    
$view['content'] = conv_content($view['wr_content'], $html);
function conv_rich_content($matches)
{
    global $view;
    return view_image($view, $matches[1], $matches[2]);
}
$view['rich_content'] = preg_replace_callback("/{이미지\:([0-9]+)[:]?([^}]*)}/i", "conv_rich_content", $view['content']);

if(!isset($rtnURL) || $rtnURL == "") $rtnURL = G5_ADMIN_URL."/community/post_management.php";
?>

<?php if($view['wr_is_comment'] == "0") { ?> 
<form name="fwrite" id="fwrite" action="<?php echo G5_ADMIN_URL?>/community/write_update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
<input type="hidden" value="html1" name="html">
<input type="hidden" name="wr_subject" value="<?php echo $subject ?>" >

<input type="hidden" name="rtnURL" value="<?php echo $rtnURL?>" >
<table>
<caption>게시글 내용</caption>
<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
</colgroup>
<tbody>
<tr>
    <th scope="row">카테고리 분류</th>
    <td colspan="2"><?php echo $board['bo_subject'] ?></td>
</tr>
<tr>
    <th scope="row">제목</th>
    <td colspan="2">
      <?php echo get_text($view['subject']); ?>
    </td>
</tr>
<tr>
  <th scope="row">작성자</th>
  <td colspan="2"><?php echo $view['name'].'('.$view['mb_id'].')'?></td>
</tr>
<tr>
  <th scope="row">작성일시</th>
  <td colspan="2"><?php echo $view['wr_datetime'] ?></td>
</tr>
<tr>
  <th scope="row">답변상태</th>
  <td colspan="2"> <?php echo $view['wr_9'] ?>
  </td>
</tr>
<tr>
  <th scope="row">작성글</th>
  <td colspan="2" class="pre-scrollable">
    <?php if($view['wr_10'] == '1') {echo $view['wr_content_mobile'];} else {echo get_view_thumbnail($view['content']); } ?>
  </td>
</tr>
<?php if($view['wr_9'] == '답변전') { ?>
<tr>
  <th scope="row">답변글</th>
  <td colspan="2">
  	<?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
  </td>
</tr>
<tr>
  <td colspan="3" style="text-align: center">
    <input type="submit" class="btn btn-success" id="btn_post_reply_save" value="답변 저장"></input>
  </td>
</tr>
<?php } ?>
</tbody>
</table>
</form>
<script>
function fwrite_submit(f)
{
    <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": f.wr_subject.value,
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (subject) {
        alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined")
            ed_wr_content.returnFalse();
        else
            f.wr_content.focus();
        return false;
    }

    if ( confirm("저장하시겠습니까?") ) {

    	$("#btn_post_reply_save").prop("disabled", true);

    	var bo_table = $('input[name="bo_table"]').val();
        var token = get_write_token(bo_table);

        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }

        var $f = $(f);

        if(typeof f.token === "undefined")
            $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);

		return true;
    } else {
		return false;
	}
}
</script>
<?php } elseif($view['wr_is_comment'] == "1") { ?>
<table>
<caption>댓글 내용</caption>
<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
</colgroup>
<tbody>
<tr>
    <th scope="row">카테고리 분류</th>
    <td colspan="2"><?php echo $board['bo_subject'] ?></td>
</tr>
<tr>
  <th scope="row">작성자</th>
  <td colspan="2"><?php echo $view['name'].'('.$view['mb_id'].')'?></td>
</tr>
<tr>
  <th scope="row">작성일시</th>
  <td colspan="2"><?php echo $view['wr_datetime'] ?></td>
</tr>
<tr>
  <th scope="row">댓글</th>
  <td colspan="2" class="pre-scrollable">
    <?php if($view['wr_10'] == '1') {echo $view['wr_content_mobile'];} else {echo get_view_thumbnail($view['content']); } ?>
  </td>
</tr>
<tr>
  <th scope="row">게시글</th>
  <td colspan="2" class="pre-scrollable">
  [Original Message]<br/>  
  <?php 
  $orgview = sql_fetch(" select * from {$write_table} where wr_is_comment = '0' and wr_reply = '' and wr_num = '{$view['wr_num']}' ");
  
  $html = 1;
  /*if (strstr($orgview['wr_option'], 'html1'))
    $html = 1;
  else if (strstr($orgview['wr_option'], 'html2'))
    $html = 2;
  */
  $orgview['content'] = conv_content($orgview['wr_content'], $html);
      
  echo $orgview['content'];
  ?>
  </td>
</tr>
</tbody>
</table>
<?php 
if($view['wr_nogood'] > 0){
    $sql = "select a.bg_comment, b.mb_name, a.mb_id, a.bg_datetime from  lt_board_good as a left join lt_member as b on a.mb_id = b.mb_id where a.wr_id = '{$wr_id}' and a.bg_flag = 'nogood' ";
    
?>
<br/><h4>신고내역</h4>
<table>
<caption>신고내역</caption>
<colgroup>
    <col >
    <col class="grid_4">
    <col class="grid_4">
</colgroup>
<tbody>
<?php 
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
<tr>
    <td><?=$row['bg_comment']?></td>
    <td><?=$row['mb_name'].'('.$row['mb_id'].')'?></td>
    <td><?=$row['bg_datetime']?></td>
</tr>
<?php }?>
</tbody>
</table>
<?php }
} ?> 