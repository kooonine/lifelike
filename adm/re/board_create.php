<?php
$sub_menu = "900110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
  alert('최고관리자만 접근 가능합니다.');

$html_title = '컨탠츠 분류';



$required = "";
$readonly = "";
if ($w == '') {

  $html_title .= ' 생성';

  $required = 'required';
  $required_valid = 'alnum_';
  $sound_only = '<strong class="sound_only">필수</strong>';

  $board['bo_count_delete'] = 1;
  $board['bo_count_modify'] = 1;
  $board['bo_read_point'] = $config['cf_read_point'];
  $board['bo_write_point'] = $config['cf_write_point'];
  $board['bo_comment_point'] = $config['cf_comment_point'];
  $board['bo_download_point'] = $config['cf_download_point'];

  $board['bo_gallery_cols'] = 4;
  $board['bo_gallery_width'] = 202;
  $board['bo_gallery_height'] = 150;
  $board['bo_mobile_gallery_width'] = 125;
  $board['bo_mobile_gallery_height'] = 100;
  $board['bo_table_width'] = 100;
  $board['bo_page_rows'] = $config['cf_page_rows'];
  $board['bo_mobile_page_rows'] = $config['cf_page_rows'];
  $board['bo_subject_len'] = 60;
  $board['bo_mobile_subject_len'] = 30;
  $board['bo_new'] = 24;
  $board['bo_hot'] = 100;
  $board['bo_image_width'] = 600;
  $board['bo_upload_count'] = 1;
  $board['bo_upload_size'] = 1048576;
  $board['bo_reply_order'] = 1;
  $board['bo_use_search'] = 1;
  $board['bo_skin'] = 'basic';
  $board['bo_mobile_skin'] = 'basic';
  $board['gr_id'] = 'shop';
  $board['bo_use_secret'] = 0;
  $board['bo_include_head'] = '_head.php';
  $board['bo_include_tail'] = '_tail.php';


  $board['bo_use'] = 0;
  $board['bo_use_comment'] = 0;
  $board['bo_use_good'] = 1;
  $board['bo_use_reply'] = 0;
  $board['bo_use_secretreply'] = 0;
  $board['bo_writeimage_point'] = 0;
  $board['bo_reply_rows'] = 10;
  $board['bo_reply_sort'] = 0;
  $board['bo_use_shop'] = 1;

  $board['bo_filter'] = '';
  $board['bo_view_rows'] = 10;

  $board['bo_use_view'] = 1;
  $board['bo_use_view_hit'] = 0;
  $board['bo_use_view_reply'] = 0;
  $board['bo_use_view_good'] = 0;
  $board['bo_use_view_scrap'] = 0;
  $board['bo_use_point'] = 0;

  $board['bo_read_level'] = 2;
  $board['bo_write_level'] = 2;
  $board['bo_reply_level'] = 2;
} else if ($w == 'u') {

  $html_title .= ' 수정';

  if (!$board['bo_table'])
    alert('존재하지 않은 게시판 입니다.');

  if ($is_admin == 'group') {
    if ($member['mb_id'] != $group['gr_admin'])
      alert('그룹이 틀립니다.');
  }

  $readonly = 'readonly';
}

if ($is_admin != 'super') {
  $group = get_group($board['gr_id']);
  $is_admin = is_admin($member['mb_id']);
}

$g5['title'] = $html_title;
include_once('../admin.head.php');

?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

      <form name="fboardform" id="fboardform" action="./board_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="gr_id" value="<?php echo $board['gr_id'] ?>">
        <input type="hidden" name="token" value="">

        <div class="x_title">
          <h4><span class="fa fa-check-square"></span> <?php echo $html_title; ?><small></small></h2>
            <label class="nav navbar-right"></label>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <div class="form-group">
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">

              <button type="button" class="btn btn_02" id="btn_cancel1">목록이동</button>
              <input type="submit" class="btn btn-success" id="btn_submit1" value="<?php echo ($w == 'u') ? '수정' : '등록' ?>"></input>
            </div>
          </div>
        </div>

        <div class="x_content">
          <div class="tbl_frm01 tbl_wrap">
            <table>
              <caption>게시판 생성</caption>
              <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
              </colgroup>
              <tbody>
                <tr>
                  <th scope="row"><label for="bo_table">TABLE CODE</label></th>
                  <td colspan="2">
                    <input type="text" name="bo_table" value="<?php echo $board['bo_table'] ?>" id="bo_table" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $reaonly ?> <?php echo $required ?> <?php echo $required_valid ?>" maxlength="20">
                    <?php if ($w == '') { ?>
                      영문자, 숫자, _ 만 가능 (공백없이 20자 이내)
                    <?php } else { ?>
                      <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $board['bo_table'] ?>&device=pc" class="btn_frmline" target="_blank">PC게시판 바로가기</a>
                      <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $board['bo_table'] ?>&device=mobile" class="btn_frmline" target="_blank">모바일 게시판 바로가기</a>
                    <?php } ?>
                  </td>
                </tr>

                <tr>
                  <th scope="row">게시판 사용여부</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_use1" name="bo_use" <?php echo $board['bo_use'] ? 'checked' : ''; ?>> 사용</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_use0" name="bo_use" <?php echo $board['bo_use'] ? '' : 'checked'; ?>> 사용 안함</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">게시판 연동타입</th>
                  <td colspan="2">
                    <?php if ($w == '' || ($board['bo_use_userform'] == '0' && $board['bo_use_shop'] == '0' && $board['bo_use_grade'] == '0')) { ?>
                      <label><input type="checkbox" value="1" name="bo_use_userform" <?php echo ($board['bo_use_userform'] == '1') ? 'checked' : ''; ?>> 고객정보 입력폼</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" value="1" name="bo_use_shop" <?php echo ($board['bo_use_shop'] == '1') ? 'checked' : ''; ?>> 적용상품설정</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" value="1" name="bo_use_grade" <?php echo ($board['bo_use_grade'] == '1') ? 'checked' : ''; ?>> 리뷰 기능(별점 기능)</label>&nbsp;&nbsp;&nbsp;
                      <script>
                        $('input[type="checkbox"][name="bo_use_userform"]').click(function() {
                          //클릭 이벤트 발생한 요소가 체크 상태인 경우
                          if ($(this).prop('checked')) {
                            //체크박스 그룹의 요소 전체를 체크 해제후 클릭한 요소 체크 상태지정
                            $('input[type="checkbox"][name="bo_use_shop"]').prop('checked', false);
                            $('input[type="checkbox"][name="bo_use_grade"]').prop('checked', false);
                          }
                        });
                        $('input[type="checkbox"][name="bo_use_shop"]').click(function() {
                          //클릭 이벤트 발생한 요소가 체크 상태인 경우
                          if ($(this).prop('checked')) {
                            //체크박스 그룹의 요소 전체를 체크 해제후 클릭한 요소 체크 상태지정
                            $('input[type="checkbox"][name="bo_use_userform"]').prop('checked', false);
                            $('input[type="checkbox"][name="bo_use_grade"]').prop('checked', false);
                          }
                        });
                        $('input[type="checkbox"][name="bo_use_grade"]').click(function() {
                          //클릭 이벤트 발생한 요소가 체크 상태인 경우
                          if ($(this).prop('checked')) {
                            //체크박스 그룹의 요소 전체를 체크 해제후 클릭한 요소 체크 상태지정
                            $('input[type="checkbox"][name="bo_use_shop"]').prop('checked', false);
                            $('input[type="checkbox"][name="bo_use_userform"]').prop('checked', false);
                          }
                        });
                      </script>
                    <?php } else {

                      if ($board['bo_use_userform'] == '1') echo '<label><input type="checkbox" value="" checked disabled >고객정보 입력폼</label><input type="hidden" name="bo_use_userform" value="1"><input type="hidden" name="bo_use_shop" value="0"><input type="hidden" name="bo_use_grade" value="0"> ';
                      else if ($board['bo_use_shop'] == '1') echo '<label><input type="checkbox" value="" checked disabled >적용상품설정</label><input type="hidden" name="bo_use_userform" value="0"><input type="hidden" name="bo_use_shop" value="1"><input type="hidden" name="bo_use_grade" value="0">';
                      else if ($board['bo_use_grade'] == '1') echo '<label><input type="checkbox" value="" checked disabled >리뷰 기능(별점 기능)</label><input type="hidden" name="bo_use_userform" value="0"><input type="hidden" name="bo_use_shop" value="0"><input type="hidden" name="bo_use_grade" value="1">';
                    } ?>
                  </td>
                </tr>
                <tr>
                  <th scope="row">게시판 타입</th>
                  <td colspan="2">
                    <?php if ($board['bo_skin'] != 'basic' && $board['bo_skin'] != 'gallery' && $board['bo_skin'] != 'gallery2') {

                      echo '<label><input type="radio" value="' . $board['bo_skin'] . '" name="bo_skin" checked > ' . get_text($board['bo_subject']) . '</label>';
                    } else {
                    ?>
                      <label><input type="radio" value="gallery" name="bo_skin" <?php echo ($board['bo_skin'] == 'gallery') ? 'checked' : ''; ?>> 웹진형(가로형)</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" value="gallery2" name="bo_skin" <?php echo ($board['bo_skin'] == 'gallery2') ? 'checked' : ''; ?>> 카드형(세로형)</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" value="basic" name="bo_skin" <?php echo ($board['bo_skin'] == 'basic') ? 'checked' : ''; ?>> 기본형(텍스트형)</label>&nbsp;&nbsp;&nbsp;
                    <?php } ?>


                  </td>
                </tr>
                <tr>
                  <th scope="row">게시판 카테고리 설정</th>
                  <td colspan="2">
                    <select id="me_code1" name="me_code1">
                      <option value="">---선택---</option>
                      <?php
                      $sql = " select * from {$g5['menu_table']} where me_depth = 1 order by me_depth, me_order, me_code ";
                      $result = sql_query($sql);

                      for ($i = 0; $row = sql_fetch_array($result); $i++) {
                      ?>
                        <option value="<?php echo $row['me_code'] ?>" <?php echo ($row['me_code'] == substr($board['bo_me_code'], 0, 2)) ? "selected" : "" ?>><?php echo $row['me_name'] ?></option>
                      <?php } ?>
                    </select>
                    <select id="bo_me_code" name="bo_me_code">
                      <option value="">---선택---</option>
                      <?php
                      $sql = " select * from {$g5['menu_table']} where me_code like '" . substr($board['bo_me_code'], 0, 2) . "%' and me_depth = 2 order by me_depth, me_order, me_code ";
                      $result = sql_query($sql);

                      for ($i = 0; $row = sql_fetch_array($result); $i++) {
                      ?>
                        <option value="<?php echo $row['me_code'] ?>" <?php echo ($row['me_code'] == substr($board['bo_me_code'], 0, 4)) ? "selected" : "" ?>><?php echo $row['me_name'] ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th scope="row"><label for="bo_subject">게시판 제목<strong class="sound_only">필수</strong></label></th>
                  <td colspan="2">
                    <input type="text" name="bo_subject" value="<?php echo get_text($board['bo_subject']) ?>" id="bo_subject" required class="required frm_input" size="80" maxlength="120">
                  </td>
                </tr>

                <tr>
                  <th scope="row"><label for="bo_subject">게시판 분류 설정</label></th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_use_cate1" name="bo_use_cate" <?php echo ($board['bo_1_subj'] != '') ? 'checked' : ''; ?>> 사용</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_use_cate0" name="bo_use_cate" <?php echo ($board['bo_1_subj'] == '') ? 'checked' : ''; ?>> 사용 안함</label><br />
                  </td>
                </tr>
                <?php for ($i = 1; $i <= 9; $i++) { ?>
                  <tr>
                    <th scope="row"><label for="bo_subject">게시판 분류<?php echo $i ?></label></th>
                    <td>
                      <input type="text" name="bo_<?php echo $i ?>_subj" value="<?php echo $board['bo_' . $i . '_subj'] ?>" id="bo_<?php echo $i ?>_subj" class="frm_input full_input" <?php echo ($board['bo_' . $i . '_subj'] == '') ? 'disabled' : ''; ?> size="10" maxlength="5" placeholder=" 분류명<?php echo $i ?>">
                      <input type="text" name="bo_<?php echo $i ?>" value="<?php echo $board['bo_' . $i] ?>" id="bo_<?php echo $i ?>" class="frm_input full_input" <?php echo ($board['bo_' . $i . '_subj'] == '') ? 'disabled' : ''; ?> size="80" maxlength="255" placeholder=" 콤마로 분류명을 구분하여 입력">
                    </td>
                    <td>
                      <label><input type="radio" value="1" id="bo_<?php echo $i ?>_use1" name="bo_<?php echo $i ?>_use" <?php echo ($board['bo_' . $i . '_subj'] == '') ? '' : 'checked'; ?> <?php echo ($board['bo_1_subj'] == '') ? 'disabled' : ''; ?>> 뱃지로 노출</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" value="0" id="bo_<?php echo $i ?>_use0" name="bo_<?php echo $i ?>_use" <?php echo ($board['bo_' . $i . '_subj'] == '') ? 'checked' : ''; ?> <?php echo ($board['bo_1_subj'] == '') ? 'disabled' : ''; ?>> 사용 안함</label><br />
                    </td>
                  </tr>
                <?php
                }
                ?>
                <script>
                  $('input[type="radio"][name="bo_use_cate"]').click(function() {
                    if ($(this).val() == '1') {
                      for (i = 1; i <= 9; i++) {
                        //$('#bo_'+i+'_subj').prop('disabled', false);
                        //$('#bo_'+i).prop('disabled', false);

                        $('#bo_' + i + '_use1').prop('disabled', false);
                        $('#bo_' + i + '_use0').prop('disabled', false);
                      }

                      $('#bo_1_subj').prop('disabled', false);
                      $('#bo_1').prop('disabled', false);
                      $('#bo_1_use1').prop('checked', true);
                    } else {
                      for (i = 1; i <= 9; i++) {
                        $('#bo_' + i + '_subj').val('');
                        $('#bo_' + i + '_subj').prop('disabled', true);

                        $('#bo_' + i).val('');
                        $('#bo_' + i).prop('disabled', true);

                        $('#bo_' + i + '_use1').prop('disabled', true);
                        $('#bo_' + i + '_use0').prop('disabled', true);

                        $('#bo_' + i + '_use0').prop('checked', true);
                      }
                    }
                  });

                  <?php for ($i = 1; $i <= 9; $i++) { ?>
                    $('input[type="radio"][name="bo_<?php echo $i ?>_use"]').click(function() {
                      if ($(this).val() == '1') {
                        $('#bo_<?php echo $i ?>_subj').prop('disabled', false);
                        $('#bo_<?php echo $i ?>').prop('disabled', false);
                      } else {
                        $('#bo_<?php echo $i ?>_subj').val('');
                        $('#bo_<?php echo $i ?>_subj').prop('disabled', true);

                        $('#bo_<?php echo $i ?>').val('');
                        $('#bo_<?php echo $i ?>').prop('disabled', true);
                      }
                    });
                  <?php } ?>
                </script>
                <tr>
                  <th scope="row">페이지당 목록수</th>
                  <td colspan="2">
                    <input type="number" name="bo_page_rows" value="<?php echo $board['bo_page_rows'] ?>" id="bo_page_rows" required class="required numeric frm_input" size="4" min="1" max="50">
                    페이지당 노출 가능수량(1~50)
                  </td>
                </tr>
                <tr>
                  <th scope="row">페이지당 표시수</th>
                  <td colspan="2">
                    <input type="number" name="bo_view_rows" value="<?php echo $board['bo_view_rows'] ?>" id="bo_view_rows" required class="required numeric frm_input" size="4" min="1" max="10">
                    페이지당 노출 페이지 수량(1~10)
                  </td>
                </tr>
                <tr>
                  <th scope="row">파일첨부기능</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_upload_count1" name="bo_upload_count" <?php echo $board['bo_upload_count'] ? 'checked' : ''; ?>> 사용</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_upload_count0" name="bo_upload_count" <?php echo $board['bo_upload_count'] ? '' : 'checked'; ?>> 사용 안함</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">첨부파일 용량제한</th>
                  <td colspan="2">
                    <input type="number" name="bo_upload_size" value="<?php echo $board['bo_upload_size'] / 1024 / 1024 ?>" id="bo_upload_size" required class="required numeric frm_input" size="10" max="100">
                    MB (최대 : 100MB)
                  </td>
                </tr>
                <tr hidden>
                  <th scope="row">답변기능</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_use_reply1" name="bo_use_reply" <?php echo $board['bo_use_reply'] ? 'checked' : ''; ?>> 사용</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_use_reply0" name="bo_use_reply" <?php echo $board['bo_use_reply'] ? '' : 'checked'; ?>> 사용 안함</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">게시물 정렬방식</th>
                  <td>
                    <label><input type="radio" value="" id="bo_sort_field1" name="bo_sort_field" <?php echo get_checked($board['bo_sort_field'], ""); ?>> 등록순</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="wr_datetime desc" id="bo_sort_field2" name="bo_sort_field" <?php echo get_checked($board['bo_sort_field'], "wr_datetime desc"); ?>> 작성일 최근 순</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="wr_hit desc, wr_num, wr_reply" id="bo_sort_field3" name="bo_sort_field" <?php echo get_selected($board['bo_sort_field'], "wr_hit desc, wr_num, wr_reply"); ?>> 조회수 많은 순</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="wr_1 desc, wr_num, wr_reply" id="bo_sort_field4" name="bo_sort_field" <?php echo get_selected($board['bo_sort_field'], "wr_1 desc, wr_num, wr_reply"); ?>> 마감 순</label>
                  </td>
                  <td>
                    <label><input type="radio" value="1" id="bo_10_1" name="bo_10" <?php echo ($board['bo_10'] == '1') ? 'checked' : ''; ?>> 정렬방식으로 노출</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_10_0" name="bo_10" <?php echo ($board['bo_10'] == '0') ? 'checked' : ''; ?>> 사용 안함</label><br />
                  </td>
                </tr>
                <tr>
                  <th scope="row">좋아요기능사용</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_use_good1" name="bo_use_good" <?php echo $board['bo_use_good'] ? 'checked' : ''; ?>> 사용</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_use_good0" name="bo_use_good" <?php echo $board['bo_use_good'] ? '' : 'checked'; ?>> 사용 안함</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">댓글 기능</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_use_comment1" name="bo_use_comment" <?php echo $board['bo_use_comment'] ? 'checked' : ''; ?>> 사용</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_use_comment0" name="bo_use_comment" <?php echo $board['bo_use_comment'] ? '' : 'checked'; ?>> 사용 안함</label>
                  </td>
                </tr>
                <tr hidden>
                  <th scope="row">비밀글 기능</th>
                  <td colspan="2">
                    <label><input type="radio" value="2" id="bo_use_secret2" name="bo_use_secret" <?php echo ($board['bo_use_secret'] == 2) ? 'checked' : ''; ?>> 사용</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_use_secret0" name="bo_use_secret" <?php echo $board['bo_use_secret'] ? '' : 'checked'; ?>> 사용 안함</label>
                  </td>
                </tr>
                <tr hidden>
                  <th scope="row">비밀 댓글 기능</th>
                  <td colspan="2">
                    <label><input type="radio" value="2" id="bo_use_secretreply2" name="bo_use_secretreply" <?php echo ($board['bo_use_secretreply'] == 2) ? 'checked' : ''; ?>> 공개 댓글 또는 비밀 댓글 선택하여 쓰기</label> <br />
                    <label><input type="radio" value="0" id="bo_use_secretreply0" name="bo_use_secretreply" <?php echo ($board['bo_use_secretreply'] == 0) ? 'checked' : ''; ?>> 공개 댓글만 쓰기</label> <br />
                    <label><input type="radio" value="1" id="bo_use_secretreply1" name="bo_use_secretreply" <?php echo ($board['bo_use_secretreply'] == 1) ? 'checked' : ''; ?>> 비밀 댓글만 쓰기</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">작성자 표시 설정</th>
                  <td colspan="2">
                    <label><input type="radio" value="3" id="bo_use_name3" name="bo_use_name" <?php echo ($board['bo_use_name'] == 3) ? 'checked' : ''; ?>> 사용안함</label> &nbsp;&nbsp;&nbsp;
                    <!-- label><input type="radio" value="1" id="bo_use_name1" name="bo_use_name" <?php echo ($board['bo_use_name'] == 1) ? 'checked' : ''; ?>> 성명</label> &nbsp;&nbsp;&nbsp; -->
                    <label><input type="radio" value="0" id="bo_use_name0" name="bo_use_name" <?php echo ($board['bo_use_name'] == 0) ? 'checked' : ''; ?>> 아이디</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="2" id="bo_use_name2" name="bo_use_name" <?php echo ($board['bo_use_name'] == 2) ? 'checked' : ''; ?>> 닉네임(닉네임이 없는 경우 아이디 노출됨)</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">표시 설정</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="rdo_post_info1" name="bo_use_view" <?php echo $board['bo_use_view'] ? 'checked' : ''; ?>> 전체 설정</label> <br />
                    <label><input type="radio" value="0" id="rdo_post_info2" name="bo_use_view" <?php echo $board['bo_use_view'] ? '' : 'checked'; ?>> 선택 설정</label>
                    (
                    <input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_subject" value="1" id="bo_use_view_subject" <?php echo $board['bo_use_view_subject'] ? 'checked' : ''; ?> disabled="disabled">
                    <label for="bo_use_view_subject">제목</label> &nbsp;

                    <input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_summary" value="1" id="bo_use_view_summary" <?php echo $board['bo_use_view_summary'] ? 'checked' : ''; ?>>
                    <label for="bo_use_view_summary">요약설명</label> &nbsp;

                    <!-- input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_username" value="1" id="bo_use_view_username" <?php echo $board['bo_use_view_username'] ? 'checked' : ''; ?>>
                  <label for="bo_use_view_username">작성자</label> &nbsp; -->

                    <!-- input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_hit" value="1" id="chk_post_info_viewcount" <?php echo $board['bo_use_view_hit'] ? 'checked' : ''; ?>>
                  <label for="chk_post_info_viewcount">조회수</label> &nbsp;  -->

                    <!-- input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_reply" value="1" id="chk_post_info_comment" <?php echo $board['bo_use_view_reply'] ? 'checked' : ''; ?>>
                  <label for="chk_post_info_comment">댓글</label> &nbsp;

                  <input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_good" value="1" id="chk_post_info_like" <?php echo $board['bo_use_view_good'] ? 'checked' : ''; ?>>
                  <label for="chk_post_info_like">좋아요</label> &nbsp; -->

                    <input type="checkbox" <?php echo $board['bo_use_view'] ? 'disabled' : ''; ?> name="bo_use_view_datetime" value="1" id="bo_use_view_datetime" <?php echo $board['bo_use_view_datetime'] ? 'checked' : ''; ?>>
                    <label for="bo_use_view_datetime">게시일</label> &nbsp;
                    )
                  </td>
                </tr>
                <tr hidden>
                  <th scope="row">새 글 설정</th>
                  <td colspan="2">
                    등록 후 <input type="number" name="bo_new" value="<?php echo $board['bo_new'] ?>" id="bo_new" required class="required numeric frm_input" size="4"> 시간 이내의 글을 새 글로 설정합니다.
                  </td>
                </tr>
                <tr hidden>
                  <th scope="row">적립금 설정</th>
                  <td colspan="2">

                    <p><label><input type="radio" value="1" id="bo_use_point1" name="bo_use_point" <?php echo $board['bo_use_point'] ? 'checked' : ''; ?>> 적립금 주기 버튼 표시</label></p>
                    <p><label><input type="radio" value="0" id="bo_use_point0" name="bo_use_point" <?php echo $board['bo_use_point'] ? '' : 'checked'; ?>> 설정금액 표시 후 바로 지급</label></p>

                    <p>&nbsp;&nbsp;<label> - 이미지 등록한 게시글 적립금 &nbsp;<input type="number" name="bo_writeimage_point" value="<?php echo $board['bo_writeimage_point'] ?>" id="bo_writeimage_point" class="numeric frm_input"></label></p>
                    <p>&nbsp;&nbsp;<label> - 텍스트 게시글 적립금 &nbsp;<input type="number" name="bo_write_point" value="<?php echo $board['bo_write_point'] ?>" id="bo_write_point" class="numeric frm_input"></label></p>
                    <p>&nbsp;&nbsp;<label> - 댓글 적립금 &nbsp;<input type="number" name="bo_comment_point" value="<?php echo $board['bo_comment_point'] ?>" id="bo_comment_point" class="numeric frm_input"></label></p>
                  </td>
                </tr>
                <tr>
                  <th scope="row">쓰기권한</th>
                  <td colspan="2">
                    <label><input type="radio" value="9" id="bo_write_level9" name="bo_write_level" <?php echo ($board['bo_write_level'] == 9) ? 'checked' : ''; ?>> 관리자 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="2" id="bo_write_level2" name="bo_write_level" <?php echo ($board['bo_write_level'] == 2) ? 'checked' : ''; ?>> 회원 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="bo_write_level1" name="bo_write_level" <?php echo ($board['bo_write_level'] == 1) ? 'checked' : ''; ?>> 비회원</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">읽기권한</th>
                  <td colspan="2">
                    <label><input type="radio" value="9" id="bo_read_level9" name="bo_read_level" <?php echo ($board['bo_read_level'] == 9) ? 'checked' : ''; ?>> 관리자 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="2" id="bo_read_level2" name="bo_read_level" <?php echo ($board['bo_read_level'] == 2) ? 'checked' : ''; ?>> 회원 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="bo_read_level1" name="bo_read_level" <?php echo ($board['bo_read_level'] == 1) ? 'checked' : ''; ?>> 비회원</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">답변(댓글)쓰기 권한</th>
                  <td colspan="2">
                    <label><input type="radio" value="9" id="bo_reply_level9" name="bo_reply_level" <?php echo ($board['bo_reply_level'] == 9) ? 'checked' : ''; ?>> 관리자 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="2" id="bo_reply_level2" name="bo_reply_level" <?php echo ($board['bo_reply_level'] == 2) ? 'checked' : ''; ?>> 회원 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="bo_reply_level1" name="bo_reply_level" <?php echo ($board['bo_reply_level'] == 1) ? 'checked' : ''; ?>> 비회원</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">답변(댓글) 목록수</th>
                  <td colspan="2">
                    <input type="number" name="bo_reply_rows" value="<?php echo $board['bo_reply_rows'] ?>" id="bo_reply_rows" required class="required numeric frm_input" size="4" min="1" max="50">
                    페이지당 노출 가능수량(1~50)
                  </td>
                </tr>
                <tr>
                  <th scope="row">답변(댓글) 정렬</th>
                  <td colspan="2">
                    <label><input type="radio" value="1" id="bo_reply_sort1" name="bo_reply_sort" <?php echo $board['bo_reply_sort'] ? 'checked' : ''; ?>> 오름차순</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="bo_reply_sort0" name="bo_reply_sort" <?php echo $board['bo_reply_sort'] ? '' : 'checked'; ?>> 내림차순</label>
                  </td>
                </tr>
                <tr>
                  <th scope="row">원글 삭제 불가</th>
                  <td colspan="2">
                    댓글 <input type="text" name="bo_count_delete" value="<?php echo $board['bo_count_delete'] ?>" id="bo_count_delete" required class="required numeric frm_input" size="3">개 이상 달리면 삭제불가
                  </td>
                </tr>
                <tr>
                  <th scope="row">금지어 설정</th>
                  <td colspan="2">
                    <input type="text" name="bo_filter" value="<?php echo $board['bo_filter'] ?>" id="" class="required numeric frm_input" style="width: 100%"><br />
                    * 금지단어는 콤마(,)로 구분됩니다.(최대 10개까지 설정 가능)
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="x_content">
          <div class="form-group">
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">

              <button type="button" class="btn btn_02" id="btn_cancel2">목록이동</button>
              <input type="submit" class="btn btn-success" id="btn_submit2" value="<?php echo ($w == 'u') ? '수정' : '등록' ?>"></input>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
  $(function() {
    $(document).ready(function($) {

      $('#me_code1').change(function() {
        var me_code1 = $(this).val();

        $.ajax({
          type: "POST",
          cache: false,
          async: false,
          url: "../design/design_menu_get.php",
          dataType: "json",
          data: {
            me_code: me_code1,
            me_depth: 2
          },
          success: function(data) {
            if (data.error) {
              alert(data.error);
              return false;
            }

            //var responseJSON = JSON.parse(data);
            var count = data.length;

            $("#bo_me_code").empty();
            $("#bo_me_code").append($('<option>', {
              value: '',
              text: '----선택-----'
            }));

            for (i = 0; i < count; i++) {
              //alert(data[i]['me_name']);
              $("#bo_me_code").append($('<option>', {
                value: data[i]['me_code'],
                text: data[i]['me_name']
              }));
            }

            return true;
          },
          error: function(request, status, error) {
            alert(error);
            return false;
          }
        });
      });

      $("#bo_subject").bind("keyup", function() {

        re = /[ \{\}\[\]\/?.,;:|\)*~`!^\-_+┼<>@\#$%&\'\"\\\(\=]/gi;
        var temp = $("#bo_subject").val();

        if (re.test(temp)) { //특수문자가 포함되면 삭제하여 값으로 다시셋팅

          $("#bo_subject").val(temp.replace(re, ""));
          alert("특수문자는 입력하실 수 없습니다.");
        }
      });
      //
    });
  });

  $('input[type="checkbox"][name="chk_post_delete_reply"]').click(function() {
    //클릭 이벤트 발생한 요소가 체크 상태인 경우
    if ($(this).prop('checked')) {
      //체크박스 그룹의 요소 전체를 체크 해제후 클릭한 요소 체크 상태지정
      $('input[type="checkbox"][name="chk_post_delete_reply"]').prop('checked', false);
      $(this).prop('checked', true);
    }
  });

  $('input[type="radio"][name="bo_use_view"]').click(function() {

    if ($('#rdo_post_info1').prop('checked')) {

      $('#chk_post_info_viewcount').prop('checked', false);
      $('#chk_post_info_comment').prop('checked', false);
      $('#chk_post_info_like').prop('checked', false);


      $('#chk_post_info_viewcount').prop('disabled', true);
      $('#chk_post_info_comment').prop('disabled', true);
      $('#chk_post_info_like').prop('disabled', true);

      $('#bo_use_view_subject').prop('checked', false);
      $('#bo_use_view_summary').prop('checked', false);
      $('#bo_use_view_username').prop('checked', false);
      $('#bo_use_view_datetime').prop('checked', false);

      $('#bo_use_view_subject').prop('disabled', true);
      $('#bo_use_view_summary').prop('disabled', true);
      $('#bo_use_view_username').prop('disabled', true);
      $('#bo_use_view_datetime').prop('disabled', true);


    } else {

      $('#chk_post_info_viewcount').prop('checked', true);
      $('#chk_post_info_comment').prop('checked', true);
      $('#chk_post_info_like').prop('checked', true);

      $('#chk_post_info_viewcount').prop('disabled', false);
      $('#chk_post_info_comment').prop('disabled', false);
      $('#chk_post_info_like').prop('disabled', false);

      $('#bo_use_view_subject').prop('checked', true);
      $('#bo_use_view_summary').prop('checked', true);
      $('#bo_use_view_username').prop('checked', true);
      $('#bo_use_view_datetime').prop('checked', true);

      $('#bo_use_view_subject').prop('disabled', true);
      $('#bo_use_view_summary').prop('disabled', false);
      $('#bo_use_view_username').prop('disabled', false);
      $('#bo_use_view_datetime').prop('disabled', false);

    }

  });



  $("#btn_cancel1,#btn_cancel2").click(function() {

    if (confirm("목록으로 이동 시 입력된 값은 삭제됩니다. 이동하시겠습니까?")) {
      location.href = "./board_management.php?<?php echo $qstr; ?>";
    }

  });

  function fboardform_submit(f) {
    if (confirm("저장하시겠습니까?")) {
      return true;
    } else {
      return false;
    }
  }
</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once('../admin.tail.php');
?>