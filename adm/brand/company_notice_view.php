<?php
$sub_menu = "92";
include_once('./_common.php');

if (!$board['bo_table']) {
    alert('존재하지 않는 게시판입니다.', G5_URL);
}
$notice_array = explode(',', trim($board['bo_notice']));

if ($write['wr_id']) {
    // 가변 변수로 $wr_1 .. $wr_10 까지 만든다.
    for ($i=1; $i<=10; $i++) {
        $vvar = "wr_".$i;
        $$vvar = $write['wr_'.$i];
    }
} else {
    alert("글이 존재하지 않습니다.\\n삭제되었거나 이동된 경우입니다.", G5_URL);
}

$sql_search = "( wr_1 = '1' or  (length(wr_1) = 16 and substring(wr_1,1,16) <= now()) or  (length(wr_1) = 33 and now() BETWEEN substring(wr_1,1,16) and substring(wr_1,18,16)))";

if ($sql_search)
    $sql_search = " and " . $sql_search;
    
// 윗글을 얻음
$sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num = '{$write['wr_num']}' and wr_reply < '{$write['wr_reply']}' {$sql_search} order by wr_num desc, wr_reply desc limit 1 ";
$prev = sql_fetch($sql);
// 위의 쿼리문으로 값을 얻지 못했다면
if (!$prev['wr_id'])     {
    $sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num < '{$write['wr_num']}' {$sql_search} order by wr_num desc, wr_reply desc limit 1 ";
    $prev = sql_fetch($sql);
}

// 아래글을 얻음
$sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num = '{$write['wr_num']}' and wr_reply > '{$write['wr_reply']}' {$sql_search} order by wr_num, wr_reply limit 1 ";
$next = sql_fetch($sql);
// 위의 쿼리문으로 값을 얻지 못했다면
if (!$next['wr_id']) {
    $sql = " select wr_id, wr_subject, wr_datetime from {$write_table} where wr_is_comment = 0 and wr_num > '{$write['wr_num']}' {$sql_search} order by wr_num, wr_reply limit 1 ";
    $next = sql_fetch($sql);
}
// 이전글 링크
$prev_href = '';
if (isset($prev['wr_id']) && $prev['wr_id']) {
    $prev_wr_subject = get_text(cut_str($prev['wr_subject'], 255));
    $prev_href = './company_notice_view.php?bo_table='.$bo_table.'&amp;wr_id='.$prev['wr_id'].$qstr;
    $prev_wr_date = $prev['wr_datetime'];
}

// 다음글 링크
$next_href = '';
if (isset($next['wr_id']) && $next['wr_id']) {
    $next_wr_subject = get_text(cut_str($next['wr_subject'], 255));
    $next_href = './company_notice_view.php?bo_table='.$bo_table.'&amp;wr_id='.$next['wr_id'].$qstr;
    $next_wr_date = $next['wr_datetime'];
}
$list_href = './company_notice.php?bo_table='.$bo_table.'&amp;page='.$page;

$view = get_view($write, $board, $board_skin_path);

if (strstr($sfl, 'subject'))
    $view['subject'] = search_font($stx, $view['subject']);

$html = 1;
$view['content'] = conv_content($view['wr_content'], $html);

$g5['title'] = '공지사항 상세';
include_once ('../admin.head.php');

?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

  <div class="x_title">
	  <h4><span class="fa fa-check-square"></span> 공지사항 상세<small></small></h4>
	  <label class="nav navbar-right"></label>
	  <div class="clearfix"></div>
  </div>

	  <div class="x_content">
          <table class="table table-bordered">
          <colgroup>
              <col>
              <col class="grid_4">
          </colgroup>
          <thead>
          <tr class="headings">
              <th scope="row" class="text-center" style="background:#eff3f9">제목</th>
              <th scope="row" class="text-center" style="background:#eff3f9">작성일</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td class="text-left" style="padding-left: 20px;padding-top: 10px;"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></td>
            <td class="text-center" style="padding: 10px;"><?php echo $view['wr_datetime']?></td>
          </tr>
          <tr>
          	<td colspan="2" style="min-height: 400px;overflow: visible;padding: 20px;">
          	<?php echo $view['wr_content']; ?>
          	</td>
          </tr>
          </tbody>
          </table>
    </div>

	<?php if ($prev_href || $next_href) { ?>
	  <div class="x_content">
	  	<table class="table table-bordered">
	  	<?php if ($prev_href) { ?>
	  		<tr style="padding: 10px;">
	  			<td style="width: 200px;" class="text-center"><i class="fa fa-caret-up" aria-hidden="true"></i> 이전글</td>
	  			<td><a href="<?php echo $prev_href ?>"><?php echo $prev_wr_subject;?></a> </td>
	  			<td style="width: 200px;" class="text-center"><?php echo str_replace('-', '.', substr($prev_wr_date, '0', '10')); ?></td>
			</tr>
	  	<?php } ?>
	  	<?php if ($next_href) { ?>
	  		<tr style="padding: 10px;">
	  			<td style="width: 200px;" class="text-center"><i class="fa fa-caret-down" aria-hidden="true"></i> 다음글</td>
	  			<td><a href="<?php echo $next_href ?>"><?php echo $next_wr_subject;?></a> </td>
	  			<td style="width: 200px;" class="text-center"><?php echo str_replace('-', '.', substr($next_wr_date, '0', '10')); ?></td>
			</tr>
	  	<?php } ?>
	  	</table>
	  </div>
	<?php } ?>

	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
                <a href="<?php echo $list_href ?>"><button class="btn btn_02" type="button" id="btn_cancel">목록</button></a>
			</div>
		  </div>
	  </div>

	</div>
  </div>
</div>

<script>
$(function() {
	
});
</script>

<?php
include_once ('../admin.tail.php');
?>