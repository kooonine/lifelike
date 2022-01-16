<?php
$sub_menu = "92";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

// wr_1 상태설정(공개구분, 게시 기간 설정)
// wr_2 태그
// wr_3 요약설정
// wr_4 고객정보 => 체험단용
// wr_5 상품목록
// wr_6 분류목록
// wr_7 모집마감일 => 체험단, 이벤트 등
// wr_8 스팸
// wr_9 답변전,답변완료
// wr_10 모바일 내용 별도 설정(0: 동일, 1: 사용) //wr_content_mobile 에 저장됨

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

if (!$board['bo_table']) {
    alert('존재하지 않는 게시판입니다.', G5_URL);
}
$notice_array = explode(',', trim($board['bo_notice']));

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == 'u' || $w == 'r') {
    if ($write['wr_id']) {
        // 가변 변수로 $wr_1 .. $wr_10 까지 만든다.
        for ($i=1; $i<=10; $i++) {
            $vvar = "wr_".$i;
            $$vvar = $write['wr_'.$i];
        }
    } else {
        alert("글이 존재하지 않습니다.\\n삭제되었거나 이동된 경우입니다.", G5_URL);
    }
}

if ($w == '') {
    if ($wr_id) {
        alert('글쓰기에는 \$wr_id 값을 사용하지 않습니다.', G5_BBS_URL.'/board.php?bo_table='.$bo_table);
    }

    if ($member['mb_level'] < $board['bo_write_level']) {
        if ($member['mb_id']) {
            alert('글을 쓸 권한이 없습니다.');
        } else {
            alert("글을 쓸 권한이 없습니다.\\n회원이시라면 로그인 후 이용해 보십시오.", './login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
        }
    }

    // 음수도 true 인것을 왜 이제야 알았을까?
    if ($is_member) {
        $tmp_point = ($member['mb_point'] > 0) ? $member['mb_point'] : 0;
        if ($tmp_point + $board['bo_write_point'] < 0 && !$is_admin) {
            alert('보유하신 포인트('.number_format($member['mb_point']).')가 없거나 모자라서 글쓰기('.number_format($board['bo_write_point']).')가 불가합니다.\\n\\n포인트를 적립하신 후 다시 글쓰기 해 주십시오.');
        }
    }

    $title_msg = '글쓰기';
    $write['wr_10'] = '0';
    $write['wr_1'] = '1';

} else if ($w == 'u') {
    // 김선용 1.00 : 글쓰기 권한과 수정은 별도로 처리되어야 함
    //if ($member['mb_level'] < $board['bo_write_level']) {
    if($member['mb_id'] && $write['mb_id'] === $member['mb_id']) {
        ;
    } else if ($member['mb_level'] < $board['bo_write_level']) {
        if ($member['mb_id']) {
            alert('글을 수정할 권한이 없습니다.');
        } else {
            alert('글을 수정할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', './login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
        }
    }

    $len = strlen($write['wr_reply']);
    if ($len < 0) $len = 0;
    $reply = substr($write['wr_reply'], 0, $len);

    // 원글만 구한다.
    $sql = " select count(*) as cnt from {$write_table}
                where wr_reply like '{$reply}%'
                and wr_id <> '{$write['wr_id']}'
                and wr_num = '{$write['wr_num']}'
                and wr_is_comment = 0 ";
    $row = sql_fetch($sql);
    if ($row['cnt'] && !$is_admin)
        alert('이 글과 관련된 답변글이 존재하므로 수정 할 수 없습니다.\\n\\n답변글이 있는 원글은 수정할 수 없습니다.');

        // 코멘트 달린 원글의 수정 여부
        $sql = " select count(*) as cnt from {$write_table}
                where wr_parent = '{$wr_id}'
                and mb_id <> '{$member['mb_id']}'
                and wr_is_comment = 1 ";
        $row = sql_fetch($sql);
        if ($board['bo_count_modify'] && $row['cnt'] >= $board['bo_count_modify'] && !$is_admin)
            alert('이 글과 관련된 댓글이 존재하므로 수정 할 수 없습니다.\\n\\n댓글이 '.$board['bo_count_modify'].'건 이상 달린 원글은 수정할 수 없습니다.');

            $title_msg = '글수정';
} else if ($w == 'r') {
    if ($member['mb_level'] < $board['bo_reply_level']) {
        if ($member['mb_id'])
            alert('글을 답변할 권한이 없습니다.');
            else
                alert('답변글을 작성할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', './login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
    }

    $tmp_point = isset($member['mb_point']) ? $member['mb_point'] : 0;
    if ($tmp_point + $board['bo_write_point'] < 0 && !$is_admin)
        alert('보유하신 포인트('.number_format($member['mb_point']).')가 없거나 모자라서 글답변('.number_format($board['bo_comment_point']).')가 불가합니다.\\n\\n포인트를 적립하신 후 다시 글답변 해 주십시오.');

        //if (preg_match("/[^0-9]{0,1}{$wr_id}[\r]{0,1}/",$board['bo_notice']))
        if (in_array((int)$wr_id, $notice_array))
            alert('공지에는 답변 할 수 없습니다.');

            //----------
            // 4.06.13 : 비밀글을 타인이 열람할 수 있는 오류 수정 (헐랭이, 플록님께서 알려주셨습니다.)
            // 코멘트에는 원글의 답변이 불가하므로
            if ($write['wr_is_comment'])
                alert('정상적인 접근이 아닙니다.');

                // 비밀글인지를 검사
                if (strstr($write['wr_option'], 'secret')) {
                    if ($write['mb_id']) {
                        // 회원의 경우는 해당 글쓴 회원 및 관리자
                        if (!($write['mb_id'] === $member['mb_id'] || $is_admin))
                            alert('비밀글에는 자신 또는 관리자만 답변이 가능합니다.');
                    } else {
                        // 비회원의 경우는 비밀글에 답변이 불가함
                        if (!$is_admin)
                            alert('비회원의 비밀글에는 답변이 불가합니다.');
                    }
                }
                //----------

                // 게시글 배열 참조
                $reply_array = &$write;

                // 최대 답변은 테이블에 잡아놓은 wr_reply 사이즈만큼만 가능합니다.
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
}

// 그룹접근 가능
if (!empty($group['gr_use_access'])) {
    if ($is_guest) {
        alert("접근 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", 'login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
    }

    if ($is_admin == 'super' || $group['gr_admin'] === $member['mb_id'] || $board['bo_admin'] === $member['mb_id']) {
        ; // 통과
    } else {
        // 그룹접근
        $sql = " select gr_id from {$g5['group_member_table']} where gr_id = '{$board['gr_id']}' and mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['gr_id'])
            alert('접근 권한이 없으므로 글쓰기가 불가합니다.\\n\\n궁금하신 사항은 관리자에게 문의 바랍니다.');
    }
}

// 본인확인을 사용한다면
if ($config['cf_cert_use'] && !$is_admin) {
    // 인증된 회원만 가능
    if ($board['bo_use_cert'] != '' && $is_guest) {
        alert('이 게시판은 본인확인 하신 회원님만 글쓰기가 가능합니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', 'login.php?'.$qstr.'&amp;url='.urlencode($_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table));
    }

    if ($board['bo_use_cert'] == 'cert' && !$member['mb_certify']) {
        alert('이 게시판은 본인확인 하신 회원님만 글쓰기가 가능합니다.\\n\\n회원정보 수정에서 본인확인을 해주시기 바랍니다.', G5_URL);
    }

    if ($board['bo_use_cert'] == 'adult' && !$member['mb_adult']) {
        alert('이 게시판은 본인확인으로 성인인증 된 회원님만 글쓰기가 가능합니다.\\n\\n성인인데 글쓰기가 안된다면 회원정보 수정에서 본인확인을 다시 해주시기 바랍니다.', G5_URL);
    }

    if ($board['bo_use_cert'] == 'hp-cert' && $member['mb_certify'] != 'hp') {
        alert('이 게시판은 휴대전화 본인확인 하신 회원님만 글읽기가 가능합니다.\\n\\n회원정보 수정에서 휴대전화 본인확인을 해주시기 바랍니다.', G5_URL);
    }

    if ($board['bo_use_cert'] == 'hp-adult' && (!$member['mb_adult'] || $member['mb_certify'] != 'hp')) {
        alert('이 게시판은 휴대전화 본인확인으로 성인인증 된 회원님만 글읽기가 가능합니다.\\n\\n현재 성인인데 글읽기가 안된다면 회원정보 수정에서 휴대전화 본인확인을 다시 해주시기 바랍니다.', G5_URL);
    }
}

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

$g5['title'] = ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']).' '.$title_msg;

$is_notice = false;
$notice_checked = '';
if ($is_admin && $w != 'r') {
    $is_notice = true;

    if ($w == 'u') {
        // 답변 수정시 공지 체크 없음
        if ($write['wr_reply']) {
            $is_notice = false;
        } else {
            if (in_array((int)$wr_id, $notice_array)) {
                $notice_checked = 'checked';
            }
        }
    }
}

$is_html = false;
if ($member['mb_level'] >= $board['bo_html_level'])
    $is_html = true;

$is_secret = $board['bo_use_secret'];

$is_mail = false;
if ($config['cf_email_use'] && $board['bo_use_email'])
    $is_mail = true;

$recv_email_checked = '';
if ($w == '' || strstr($write['wr_option'], 'mail'))
    $recv_email_checked = 'checked';

$is_name     = false;
$is_password = false;
$is_email    = false;
$is_homepage = false;
if ($is_guest || ($is_admin && $w == 'u' && $member['mb_id'] !== $write['mb_id'])) {
    $is_name = true;
    $is_password = true;
    $is_email = true;
    $is_homepage = true;
}

$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
    $ca_name = "";
    if (isset($write['ca_name']))
        $ca_name = $write['ca_name'];
        $category_option = get_category_option($bo_table, $ca_name);
        $is_category = true;
}

$is_link = false;
if ($member['mb_level'] >= $board['bo_link_level']) {
    $is_link = true;
}

$is_file = false;
if ($member['mb_level'] >= $board['bo_upload_level']) {
    $is_file = true;
}

$is_file_content = false;
if ($board['bo_use_file_content']) {
    $is_file_content = true;
}

$file_count = (int)$board['bo_upload_count'];

$name     = "";
$email    = "";
$homepage = "";
if ($w == "" || $w == "r") {
    if ($is_member) {
        if (isset($write['wr_name'])) {
            $name = get_text(cut_str(stripslashes($write['wr_name']),20));
        }
        $email = get_email_address($member['mb_email']);
        $homepage = get_text(stripslashes($member['mb_homepage']));
    }
}

$html_checked   = "";
$html_value     = "";
$secret_checked = "";

if ($w == '') {
    $password_required = 'required';
} else if ($w == 'u') {
    $password_required = '';

    if (!$is_admin) {
        if (!($is_member && $member['mb_id'] === $write['mb_id'])) {
            if (!check_password($wr_password, $write['wr_password'])) {
                alert('비밀번호가 틀립니다.');
            }
        }
    }

    $name = get_text(cut_str(stripslashes($write['wr_name']),20));
    $email = get_email_address($write['wr_email']);
    $homepage = get_text(stripslashes($write['wr_homepage']));

    for ($i=1; $i<=G5_LINK_COUNT; $i++) {
        $write['wr_link'.$i] = get_text($write['wr_link'.$i]);
        $link[$i] = $write['wr_link'.$i];
    }

    if (strstr($write['wr_option'], 'html1')) {
        $html_checked = 'checked';
        $html_value = 'html1';
    } else if (strstr($write['wr_option'], 'html2')) {
        $html_checked = 'checked';
        $html_value = 'html2';
    }

    if (strstr($write['wr_option'], 'secret')) {
        $secret_checked = 'checked';
    }

    $file = get_file($bo_table, $wr_id);
    if($file_count < $file['count'])
        $file_count = $file['count'];
} else if ($w == 'r') {
    if (strstr($write['wr_option'], 'secret')) {
        $is_secret = true;
        $secret_checked = 'checked';
    }

    $password_required = "required";

    for ($i=1; $i<=G5_LINK_COUNT; $i++) {
        $write['wr_link'.$i] = get_text($write['wr_link'.$i]);
    }
}

set_session('ss_bo_table', $_REQUEST['bo_table']);
set_session('ss_wr_id', $_REQUEST['wr_id']);

$subject = "";
if (isset($write['wr_subject'])) {
    $subject = str_replace("\"", "&#034;", get_text(cut_str($write['wr_subject'], 255), 0));
}

$content = '';
if ($w == '') {
    $content = $board['bo_insert_content'];
} else if ($w == 'r') {
    if (!strstr($write['wr_option'], 'html')) {
        $content = "\n\n\n &gt; "
            ."\n &gt; "
                ."\n &gt; ".str_replace("\n", "\n> ", get_text($write['wr_content'], 0))
                ."\n &gt; "
                    ."\n &gt; ";

    }
} else {
    $content = get_text($write['wr_content'], 0);
}



$content_mobile = '';
if ($w == '') {
    $content_mobile = $board['bo_insert_content'];
} else if ($w == 'r') {
    if (!strstr($write['wr_option'], 'html')) {
        $content_mobile = "\n\n\n &gt; "
            ."\n &gt; "
                ."\n &gt; ".str_replace("\n", "\n> ", get_text($write['wr_content_mobile'], 0))
                ."\n &gt; "
                    ."\n &gt; ";

    }
} else {
    $content_mobile = get_text($write['wr_content_mobile'], 0);
}

$upload_max_filesize = number_format($board['bo_upload_size']) . ' 바이트';

$captcha_html = '';
$captcha_js   = '';
$is_use_captcha = ((($board['bo_use_captcha'] && $w !== 'u') || $is_guest) && !$is_admin) ? 1 : 0;

if ($is_use_captcha) {
    $captcha_html = captcha_html();
    $captcha_js   = chk_captcha_js();
}

$is_dhtml_editor = true;
$is_dhtml_editor_use = false;
$editor_content_js = '';
if(!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE)
    $is_dhtml_editor_use = true;

// 모바일에서는 G5_IS_MOBILE_DHTML_USE 설정에 따라 DHTML 에디터 적용
if ($config['cf_editor'] && $is_dhtml_editor_use && $board['bo_use_dhtml_editor'] && $member['mb_level'] >= $board['bo_html_level']) {
    $is_dhtml_editor = true;

    if ( $w == 'u' && (! $is_member || ! $is_admin || $write['mb_id'] !== $member['mb_id']) ){
        // kisa 취약점 제보 xss 필터 적용
        $content = get_text(html_purifier($write['wr_content']), 0);

        $content_mobile = get_text(html_purifier($write['wr_content_mobile']), 0);
    }

    if(is_file(G5_EDITOR_PATH.'/'.$config['cf_editor'].'/autosave.editor.js'))
        $editor_content_js = '<script src="'.G5_EDITOR_URL.'/'.$config['cf_editor'].'/autosave.editor.js"></script>'.PHP_EOL;
}
$editor_html = editor_html('wr_content', $content, $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('wr_content', $is_dhtml_editor);
$editor_js .= chk_editor_js('wr_content', $is_dhtml_editor);



$editor_html_mobile = editor_html('wr_content_mobile', $content_mobile, $is_dhtml_editor);
$editor_js_mobile = '';
$editor_js_mobile .= get_editor_js('wr_content_mobile', $is_dhtml_editor);
$editor_js_mobile .= chk_editor_js('wr_content_mobile', $is_dhtml_editor);


// 임시 저장된 글 수
$autosave_count = autosave_count($member['mb_id']);



$g5['title'] = '게시글 등록/수정';
include_once ('../admin.head.php');

$action_url = https_url('adm')."/community/write_update.php";
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

<form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off" >
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="wr_1" value="<?php echo $write['wr_1'] ?>">

    <input type="hidden" name="rtnURL" value="<?php echo G5_ADMIN_URL?>/brand/admin_notice.php">

  <div class="x_title">
	  <h4><span class="fa fa-check-square"></span> 게시글 등록<small></small></h4>
	  <label class="nav navbar-right"></label>
	  <div class="clearfix"></div>
  </div>

	  <div class="x_content">
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>게시글 등록</caption>
          <colgroup>
              <col class="grid_4">
              <col>
              <col class="grid_3">
          </colgroup>
          <tbody>
          <tr>
              <th scope="row">제목</th>
              <td colspan="2">
              	<input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" size="100" maxlength="100" placeholder="제목은 100자까지 입력가능" style="width: 100%">
              </td>
          </tr>
          <tr>
            <th scope="row">상세설정</th>
            <td colspan="2">
              <table>
                <tbody>
                  <tr>
                      <th scope="row">공개구분</th>
                      <td colspan="2">
                        <label><input type="radio" value="0" id="wr_1_1_0" name="wr_1_1" > 비공개 </label>&nbsp;&nbsp;&nbsp;
                        <label><input type="radio" value="1" id="wr_1_1_1" name="wr_1_1" checked="checked" > 공개</label>
                      </td>
                  </tr>
                  <tr>
                      <th scope="row">기간설정</th>
                      <td colspan="2">
                        <label><input type="radio" value="1" id="wr_1_2_1" name="wr_1_2" checked="checked" > 등록 후 게시 </label>&nbsp;&nbsp;&nbsp;
                        <label><input type="radio" value="0" id="wr_1_2_0" name="wr_1_2" > 기간 설정 </label>
                        (
                        <label><input type="radio" value="Y" id="rdo_end_date_useY" name="rdo_end_date_useYN" disabled="disabled"> 종료일 설정 </label>&nbsp;&nbsp;&nbsp;
                        <label><input type="radio" value="N" id="rdo_end_date_useN" name="rdo_end_date_useYN" disabled="disabled" checked="checked"> 종료일 설정 없음 </label>
                        )
                      </td>
                  </tr>
                  <tr id="tr_start_date" hidden>
                      <th scope="row">게시일</th>
                      <td colspan="2">
                        <div class='input-group date' id='startdatepicker'>
                            <input type='text' class="form-control" id="startdate" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </td>
                  </tr>
                  <tr id="tr_end_date" hidden>
                      <th scope="row">종료일</th>
                      <td colspan="2">
                        <div class='input-group date' id='enddatepicker'>
                            <input type='text' class="form-control" id="enddate" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>

          <tr>
            <th scope="row">내용<br/>(PC)</th>
            <td colspan="2">
            <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                <?php if($write_min || $write_max) { ?>
                <!-- 최소/최대 글자 수 사용 시 -->
                <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                <?php } ?>
                <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                <?php if($write_min || $write_max) { ?>
                <!-- 최소/최대 글자 수 사용 시 -->
                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                <?php } ?>
            </div>
            </td>
          </tr>

          </tbody>
          </table>
      </div>
    </div>

	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">

                <button class="btn btn_02" type="button" id="btn_cancel">취소</button>
            	<button class="btn btn_02 hidden" type="button" id="btn_delete">삭제</button>
      			<input type="button" class="btn btn-success" id="btn_submit" value="저장"></input>
			</div>
		  </div>
	  </div>

	</form>
	</div>
  </div>
</div>

<script src="../vendors/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.js"></script>
<script>

<?php if($write_min || $write_max) { ?>
// 글자수 제한
var char_min = parseInt(<?php echo $write_min; ?>); // 최소
var char_max = parseInt(<?php echo $write_max; ?>); // 최대
check_byte("wr_content", "char_count");

$(function() {
    $("#wr_content").on("keyup", function() {
        check_byte("wr_content", "char_count");
    });
});

<?php } ?>
function html_auto_br(obj)
{
    if (obj.checked) {
        result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
        if (result)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}
$('#startdatepicker').datetimepicker({
    ignoreReadonly: true,
    allowInputToggle: true,
    format: 'YYYY-MM-DD HH:mm',
    locale : 'ko'
});

$('#enddatepicker').datetimepicker({
    ignoreReadonly: true,
    allowInputToggle: true,
    format: 'YYYY-MM-DD HH:mm',
    locale : 'ko'
});

$("#startdatepicker").on("dp.change", function (e) {
	$('#enddatepicker').data("DateTimePicker").minDate(e.date);
});

$("#enddatepicker").on("dp.change", function (e) {
    $('#startdatepicker').data("DateTimePicker").maxDate(e.date);
});

$('input[type="radio"][name="wr_10"]').click(function(){
	if ( $(this).val() == '1') {
		$('#trwr_content_mobile').prop('hidden', false);
	} else {
		$('#trwr_content_mobile').prop('hidden', true);
	}
});

$('#wr_1_1_0').click(function(){
	//비공개
    $('#wr_1_2_1').click();
    $('#wr_1_2_1').prop('disabled', true);
    $('#wr_1_2_0').prop('disabled', true);
});

$('#wr_1_1_1').click(function(){
	//공개
    $('#wr_1_2_1').click();
    $('#wr_1_2_1').prop('disabled', false);
    $('#wr_1_2_0').prop('disabled', false);
});

$('#wr_1_2_1').click(function(){

	$("#tr_start_date").prop('hidden', true);
    $("#tr_end_date").prop('hidden', true);

    $('#rdo_end_date_useN').click();
    $('#rdo_end_date_useY').prop('disabled', true);
    $('#rdo_end_date_useN').prop('disabled', true);
});

$('#wr_1_2_0').click(function(){
	//등록 후 게시
	var nowDate = new Date();
	nowDate.setHours(0);
	nowDate.setMinutes(0);

    //$("#startdate").val(date_format(nowDate, "yyyy-MM-dd HH:mm"));
	$('#startdatepicker').data("DateTimePicker").date(nowDate);
	nowDate.setMonth(nowDate.getMonth() + 1);

	$('#enddatepicker').data("DateTimePicker").date(nowDate);
    //$("#enddate").val("");

	//기간 설정
	$("#tr_start_date").prop('hidden', false);

    $('#rdo_end_date_useY').prop('disabled', false);
    $('#rdo_end_date_useN').prop('disabled', false);
    $('#rdo_end_date_useY').click();
});

$('#rdo_end_date_useY').click(function(){
    $("#tr_end_date").prop('hidden', false);
});
$('#rdo_end_date_useN').click(function(){
    $("#tr_end_date").prop('hidden', true);
});

$("#btn_cancel").click(function(){
    if ( confirm("목록으로 이동 시 입력된 값은 삭제됩니다. 이동하시겠습니까?") ) {
      location.href="admin_notice.php";
    }
});

$("#btn_submit").click(function(){
	fwrite_submit($("#fwrite"));
});

var addItemCnt = 0;

function fwrite_submit(f)
{
	<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

	if($('input[name="wr_1_1"]:checked').val() == '0')
	{
		//비공개
		$('input[name="wr_1"]').val('0');
	} else if($('input[name="wr_1_2"]:checked').val() == '1') {
		//공개 - 등록 후 게시
		$('input[name="wr_1"]').val('1');
	} else {
		var wr1 = "";

		//기간 설정
		if($('#startdate').val() == "" )
		{
			alert('게시일을 입력하세요.');
			return false;
		} else {
			wr1 = $('#startdate').val();
		}

		if($('input[name="rdo_end_date_useYN"]:checked').val() == 'Y')
		{
			if($('#enddate').val() == "" )
    		{
    			alert('종료일을 입력하세요.');
    			return false;
    		} else if($('#enddate').val() != "" ){
    			wr1 += ","+$('#enddate').val();
    		}
		}
		$('input[name="wr_1"]').val(wr1);
	}

	//alert($('input[name="wr_1"]').val());
	//alert($('textarea[name="wr_content"]').val());

	if ( confirm("저장하시겠습니까?") ) {
	 	document.getElementById("btn_submit").disabled = "disabled";

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

		f.submit();
	} else {
		return false;
	}
}
<?php if($w == 'u') { ?>
var wr1 = $('input[name="wr_1"]').val();
if(wr1 == '0')
{
	$('#wr_1_1_0').click();
}
else if(wr1 == '1')
{
	$('#wr_1_1_1').click();
	$('#wr_1_2_1').click();
} else if(wr1 != ''){
	$('#wr_1_1_1').click();
	$('#wr_1_2_0').click();

	splitDate = wr1.split(',');
	$('#startdatepicker').data("DateTimePicker").date(splitDate[0]);

	if(splitDate.length == 2){
		$('#rdo_end_date_useY').click();
		$('#enddatepicker').data("DateTimePicker").date(splitDate[1]);
	} else {
		$('#rdo_end_date_useN').click();
	}
} else {
	$('#wr_1_1_0').click();
}
<?php } ?>
</script>

<?php
include_once ('../admin.tail.php');
?>
