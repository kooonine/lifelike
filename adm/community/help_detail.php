<?php
$sub_menu = "900210";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

//auth_check($auth[substr($sub_menu,0,2)], 'w');

$qaconfig = get_qa_config();

$sql = " select * from {$g5['qa_content_table']} where qa_id = '$qa_id' ";

$view = sql_fetch($sql);

if(!$view['qa_id'])
    alert('게시글이 존재하지 않습니다.');

$view['category'] = get_text($view['qa_category']);
$view['subject'] = $view['qa_subject'];
$view['content'] = conv_content($view['qa_content'], $view['qa_html']);
$view['name'] = get_text($view['qa_name']);
$view['datetime'] = $view['qa_datetime'];
$view['email'] = get_text(get_email_address($view['qa_email']));
$view['hp'] = $view['qa_hp'];

if (trim($stx))
    $view['subject'] = search_font($stx, $view['subject']);

if (trim($stx))
    $view['content'] = search_font($stx, $view['content']);

// 첨부파일
$view['img_file'] = array();
$view['download_href'] = array();
$view['download_source'] = array();
$view['img_count'] = 0;
$view['download_count'] = 0;

for ($i=1; $i<=2; $i++) {
    if(preg_match("/\.({$config['cf_image_extension']})$/i", $view['qa_file'.$i])) {
        $view['img_file'][] = '<a href="'.G5_BBS_URL.'/view_image.php?fn='.urlencode('/data/qa/'.$view['qa_file'.$i]).'" target="_blank" class="view_image"><img src="'.G5_DATA_URL.'/qa/'.$view['qa_file'.$i].'"></a>';
        $view['img_count']++;
        continue;
    }
    
    if ($view['qa_file'.$i]) {
        $view['download_href'][] = G5_BBS_URL.'/qadownload.php?qa_id='.$view['qa_id'].'&amp;no='.$i;
        $view['download_source'][] = $view['qa_source'.$i];
        $view['download_count']++;
    }
}

$answer = array();
$content = '';

if(!$view['qa_type'] && $view['qa_status']) {
    $sql = " select *
                    from {$g5['qa_content_table']}
                    where qa_type = '1'
                      and qa_parent = '{$view['qa_id']}' ";
    $answer = sql_fetch($sql);
    
    $content = get_text(html_purifier($answer['qa_content']), 0);
}

$g5['title'] = '문의내역';
include_once ('../admin.head.php');

$is_dhtml_editor = true;
if (!$content) $content ='안녕하세요 고객님 <br><br><br><br> 감사합니다.';
$editor_html = editor_html('qa_content', $content, $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('qa_content', $is_dhtml_editor);
$editor_js .= chk_editor_js('qa_content', $is_dhtml_editor);


$qstr = "stx=".urlencode($stx)."&search_date=".urlencode($search_date)."&qa_status=".urlencode($qa_status)."&sca=".urlencode($sca)."&page=".$page;
?>


<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<!-- <form name="fhelpdetail" id="fhelpdetail" method="post" onsubmit="return fhelpdetail_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
	<!-- <input type="hidden" name="token" value="" id="token"> -->

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 문의내역<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

    <div class="x_content">
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>문의내역</caption>
          <colgroup>
              <col class="grid_4">
              <col>
              <col class="grid_3">
          </colgroup>
          <tbody>
          <tr>
              <th scope="row">문의유형</th>
              <td colspan="3"><?php echo $view['category'] ?></td>
          </tr>
          <tr>
              <th scope="row">문의제목</th>
              <td colspan="3">
              	<?php echo $view['subject']; // 글제목 출력 ?>
              	<?php echo ($view['qa_status'] ? '<span class="label label-success">답변 완료</span>' : '<span class="label label-warning">미답변</span>'); ?>
              </td>
          </tr>
          <tr>
              <th scope="row">주문번호</th>
              <td colspan="3"><?php if($view['od_id']) echo '<a href="'.G5_ADMIN_URL.'/shop_admin/orderform.php?od_id='.$view['od_id'].'" target="_blank">'.$view['od_id'].'</a>' ?></td>
          </tr>
          <tr>
              <th scope="row">상품번호</th>
              <td colspan="3"><?php if($view['it_id']) echo '<a href="'.G5_SHOP_URL.'/item.php?it_id='.$view['it_id'].'" target="_blank">'.$view['it_id'].'</a>' ?></td>
          </tr>
          <tr>
              <th scope="row">내용</th>
              <td colspan="3">
				<?php echo get_view_thumbnail($view['content'], $qaconfig['qa_image_width']); ?>
              </td>
          </tr>
		<?php
        // 파일 출력
        if($view['img_count']) {
            echo "<tr><th scope=\"row\">첨부파일</th><td colspan=\"3\">\n";
            for ($i=0; $i<$view['img_count']; $i++) {
                //echo $view['img_file'][$i];
                echo get_view_thumbnail($view['img_file'][$i], $qaconfig['qa_image_width']);
            }
            echo "</td></tr>\n";
        }
         ?>
          <tr rowspan="3">
            <th scope="row">문의자</th>
            <td><?php echo $view['name'] ?>(<?php echo $view['mb_id'] ?>) <?php echo $view['email']; ?></td>
            <th scope="row">문의일</th>
            <td><?php echo $view['datetime']; ?></td>
          </tr>
          </tbody>
          </table>
      </div>
      
    <form name="fanswer" method="post" action="./help_update.php" onsubmit="return fwrite_submit(this);" autocomplete="off">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="stx" value="<?php echo $stx; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <input type="hidden" name="qa_html" value="1">
        
        <?php if($answer) { 
        //답변수정
        ?>
        <input type="hidden" name="qa_id" value="<?php echo $answer['qa_id']; ?>">
        <input type="hidden" name="w" value="u">
        
        <input type="hidden" name="qa_email" value="<?php echo $answer['qa_email']; ?>">
        <input type="hidden" name="qa_hp" value="<?php echo $answer['qa_hp']; ?>">
        <input type="hidden" name="qa_category" value="<?php echo $answer['qa_category']; ?>">
        <input type="hidden" name="qa_1" value="<?php echo $answer['qa_1']; ?>">
        <input type="hidden" name="qa_2" value="<?php echo $answer['qa_2']; ?>">
        <input type="hidden" name="qa_3" value="<?php echo $answer['qa_3']; ?>">
        <input type="hidden" name="qa_4" value="<?php echo $answer['qa_4']; ?>">
        <input type="hidden" name="qa_5" value="<?php echo $answer['qa_5']; ?>">
            
        <?php } else {
        //답변?>
        <input type="hidden" name="qa_id" value="<?php echo $view['qa_id']; ?>">
        <input type="hidden" name="w" value="a">
        <?php } ?>
        
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>답변</caption>
          <colgroup>
              <col class="grid_4">
              <col>
              <col class="grid_3">
          </colgroup>
          <tbody>
          <!-- <tr>
              <th scope="row">제목</th>
              <td colspan="3">
              	<input type="text" name="qa_subject" value="<?php echo $answer['qa_subject']; ?>" id="qa_subject" required class="frm_input required" size="100"  maxlength="255" placeholder="제목">
              </td>
          </tr> -->
          <tr>
              <th scope="row">답변</th>
              <td colspan="3">
            	<?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
              </td>
          </tr>
          <?php if($answer) { ?>
          <tr rowspan="3">
            <th scope="row">처리자</th>
            <td><?php echo $answer['qa_name']; ?>(<?php echo $answer['mb_id']; ?>)</td>
            <th scope="row">처리일</th>
            <td><?php echo $answer['qa_datetime']; ?></td>
          </tr>
          <?php } ?>
          </tbody>
          </table>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
          <button class="btn btn_02" type="button" id="btn_cancel">목록이동</button>
          <input type="submit" class="btn btn-success" value="답변저장" id="btn_save"></input>
          <?php if($answer) { ?>
          <button class="btn btn-danger" type="button" id="btn_delete_answer">답변삭제</button>
          <?php } else { ?>
          <button class="btn btn-danger" type="button" id="btn_delete">문의삭제</button>
          <?php } ?>
        </div>
      </div>
      </form>
      
    </div>

	<!-- </form> -->

	</div>
  </div>
</div>


<script>


$("#btn_cancel").click(function(){

  if ( confirm("목록으로 이동하면 입력한 정보는 저장되지 않습니다.") ) {
    location.href="<?php echo 'help_management.php?'.$qstr?>";
  }
});

$("#btn_delete").click(function(){
  if ( confirm("문의내역을 삭제하시겠습니까?") ) {
    location.href="./help_update.php?w=d&qa_id=<?php echo $view['qa_id']; ?>";
  }
});

$("#btn_delete_answer").click(function(){
  if ( confirm("답변내역을 삭제하시겠습니까?") ) {
    location.href="./help_update.php?w=d&qa_id=<?php echo $answer['qa_id']; ?>";
  }
});

function fwrite_submit(f)
{
    <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

    return true;
}
</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>