<?
$sub_menu = "900130";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == '') {
    $title_msg = '작성';
    if ($br_id) {
        alert('글쓰기에는 \$br_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_brand where br_id = '$br_id' ";
    $br = sql_fetch($sql);
    if (!$br['br_id']) alert("등록된 자료가 없습니다.");
}

// 그룹접근 가능
if (!empty($group['gr_use_access'])) {
    if ($is_guest) {
        alert("접근 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", 'login.php?' . $qstr . '&amp;url=' . urlencode($_SERVER['SCRIPT_NAME'] . '?bo_table=' . $bo_table));
    }

    if ($is_admin == 'super' || $group['gr_admin'] === $member['mb_id'] || $board['bo_admin'] === $member['mb_id']) {; // 통과
    } else {
        // 그룹접근
        $sql = " select gr_id from {$g5['group_member_table']} where gr_id = '{$board['gr_id']}' and mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['gr_id'])
            alert('접근 권한이 없으므로 글쓰기가 불가합니다.\\n\\n궁금하신 사항은 관리자에게 문의 바랍니다.');
    }
}

$g5['title'] = $title_msg;
$notice = '';
if ($w == 'r') {
    if (!strstr($br['br_option'], 'html')) {
        $notice = "\n\n\n &gt; "
            . "\n &gt; "
            . "\n &gt; " . str_replace("\n", "\n> ", get_text($br['br_notice'], 0))
            . "\n &gt; "
            . "\n &gt; ";
    }
} else {
    $notice = get_text($br['br_notice'], 0);
}

$is_dhtml_editor = true;
$is_dhtml_editor_use = false;
$editor_content_js = '';
if (!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE)
    $is_dhtml_editor_use = true;

// 모바일에서는 G5_IS_MOBILE_DHTML_USE 설정에 따라 DHTML 에디터 적용
if ($config['cf_editor'] && $is_dhtml_editor_use && $member['mb_level'] >= $board['bo_html_level']) {
    $is_dhtml_editor = true;

    if ($w == 'u' && (!$is_member || !$is_admin || $br['mb_id'] !== $member['mb_id'])) {
        // kisa 취약점 제보 xss 필터 적용
        $content = get_text(html_purifier($br['br_notice']), 0);

        $content_mobile = get_text(html_purifier($br['br_notice_mobile']), 0);
    }

    if (is_file(G5_EDITOR_PATH . '/' . $config['cf_editor'] . '/autosave.editor.js'))
        $editor_content_js = '<script src="' . G5_EDITOR_URL . '/' . $config['cf_editor'] . '/autosave.editor.js"></script>' . PHP_EOL;
}
$editor_html = editor_html('br_notice', $content, $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('br_notice', $is_dhtml_editor);
$editor_js .= chk_editor_js('br_notice', $is_dhtml_editor);

$editor_html_mobile = editor_html('br_notice_mobile', $content_mobile, $is_dhtml_editor);
$editor_js_mobile = '';
$editor_js_mobile .= get_editor_js('br_notice_mobile', $is_dhtml_editor);
$editor_js_mobile .= chk_editor_js('br_notice_mobile', $is_dhtml_editor);

$g5['title'] = "브랜드 " . $title_msg;
include_once('../admin.head.php');

$action_url = https_url('adm') . "/brand/brand.update.php";
?>

<!-- @START@ 내용부분 시작 -->

<style>
    button.btn-add {
        visibility: hidden;
    }

    button.btn-add.first {
        visibility: visible;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="uid" value="<?= get_uniqid(); ?>">
                <input type="hidden" name="w" value="<?= $w ?>">
                <input type="hidden" name="br_id" value="<?= $br_id ?>">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span> 게시글 등록<small></small></h4>
                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table id="compaign-content-wrapper">
                            <caption>게시글 등록</caption>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                                <col class="grid_3">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="row">브랜드</th>
                                    <td colspan="2">
                                        <input type="text" name="br_name" value="<?= $br['br_name'] ?>" id="br_name" required class="frm_input full_input required" size="100" maxlength="100" placeholder="최대 50자까지 입력가능" style="width: 100%">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드(영문)</th>
                                    <td colspan="2">
                                        <input type="text" name="br_name_en" value="<?= $br['br_name_en'] ?>" id="br_name_en" required class="frm_input full_input required" size="100" maxlength="100" placeholder="최대 50자까지 입력가능" style="width: 100%">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 사용여부</th>
                                    <td colspan="2">
                                        <div class="radio">
                                            <label><input type="radio" name="br_use" value=0 <?php echo get_checked($br['br_use'], 0) ?>>미사용</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="br_use" value=1 <?php echo get_checked($br['br_use'], 1) ?>>사용</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">슬로건</th>
                                    <td colspan="2">
                                        <input type="text" name="br_slogan" value="<?= $br['br_slogan'] ?>" id="br_slogan" required class="frm_input full_input" size="255" maxlength="255" placeholder="" style="width: 100%">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 소개</th>
                                    <td colspan="2">
                                        <input type="text" name="br_desc" value="<?= $br['br_desc'] ?>" id="br_desc" required class="frm_input full_input" size="255" maxlength="255" placeholder="" style="width: 100%">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 공지</th>
                                    <td colspan="2">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">공지사용</th>
                                                    <td colspan="2">
                                                        <div class="radio">
                                                            <label><input type="radio" name="br_notice_use" value=0 <?php echo get_checked($br['br_notice_use'], 0) ?>>미사용</label>&nbsp;&nbsp;
                                                            <label><input type="radio" name="br_notice_use" value=1 <?php echo get_checked($br['br_notice_use'], 1) ?>>사용</label>&nbsp;&nbsp;
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label for="br_notice_start_date">시작일시<strong class="sound_only"> 필수</strong></label></th>
                                                    <td>
                                                        <input type="text" name="br_notice_start_date" value="<?php echo $br['br_notice_start_date']; ?>" id="br_notice_start_date" required class="frm_input required" size="21" maxlength="19">
                                                        <input type="checkbox" name="br_notice_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="br_notice_begin_chk" onclick="if (this.checked == true) this.form.br_notice_start_date.value=this.form.br_notice_begin_chk.value; else this.form.br_notice_start_date.value = this.form.br_notice_start_date.defaultValue;">
                                                        <label for="br_notice_begin_chk">시작일시를 오늘로</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label for="br_notice_end_date">종료일시<strong class="sound_only"> 필수</strong></label></th>
                                                    <td>
                                                        <input type="text" name="br_notice_end_date" value="<?php echo $br['br_notice_end_date']; ?>" id="br_notice_end_date" required class="frm_input required" size="21" maxlength="19">
                                                        <input type="checkbox" name="br_notice_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME + (60 * 60 * 24 * 7)); ?>" id="br_notice_end_chk" onclick="if (this.checked == true) this.form.br_notice_end_date.value=this.form.br_notice_end_chk.value; else this.form.br_notice_end_date.value = this.form.br_notice_end_date.defaultValue;">
                                                        <label for="br_notice_end_chk">종료일시를 오늘로부터 7일 후로</label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">PC</th>
                                    <td colspan="2">
                                        <div class="br_notice <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                                            <? if ($write_min || $write_max) { ?>
                                                <!-- 최소/최대 글자 수 사용 시 -->
                                                <p id="char_count_desc">이 게시판은 최소 <strong><?= $write_min; ?></strong>글자 이상, 최대 <strong><?= $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                                            <? } ?>
                                            <?= $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 
                                            ?>
                                            <? if ($write_min || $write_max) { ?>
                                                <!-- 최소/최대 글자 수 사용 시 -->
                                                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                                            <? } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="trwr_content_mobile" <?= ($br['br_10'] == '0') ? 'hidden' : ''; ?>>
                                    <th scope="row">MOBILE</th>
                                    <td colspan="2">
                                        <div class="br_notice <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                                            <? if ($write_min || $write_max) { ?>
                                                <!-- 최소/최대 글자 수 사용 시 -->
                                                <p id="char_count_desc">이 게시판은 최소 <strong><?= $write_min; ?></strong>글자 이상, 최대 <strong><?= $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                                            <? } ?>
                                            <?= $editor_html_mobile; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 
                                            ?>
                                            <? if ($write_min || $write_max) { ?>
                                                <!-- 최소/최대 글자 수 사용 시 -->
                                                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                                            <? } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 로고</th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php
                                            $img_file = G5_DATA_PATH . '/brand/' . $br['br_logo'];
                                            if ($br['br_logo'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/brand/' . $br['br_logo'];
                                                echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="">
                                                    <div class="btn btn-info">
                                                        <span><?php if ($w == "u") echo '이미지 수정';
                                                                else echo '이미지 등록'; ?></span>
                                                        <input type="file" id="imgFile" name="br_logo" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
                                                    </div>
                                                </span>
                                                <button class="btn btn-danger <?php if (!$br['br_logo']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_br_logo" value="<?php echo $br['br_logo']; ?>">

                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <span class="red">
                                                    * 최대 15MB / 확장자 jpg, png만 가능 <br>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 이미지</th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php
                                            $img_file = G5_DATA_PATH . '/brand/' . $br['br_main_image'];
                                            if ($br['br_main_image'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/brand/' . $br['br_main_image'];
                                                echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="">
                                                    <div class="btn btn-info">
                                                        <span><?php if ($w == "u") echo '이미지 수정';
                                                                else echo '이미지 등록'; ?></span>
                                                        <input type="file" id="imgFile" name="br_main_image" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
                                                    </div>
                                                </span>
                                                <button class="btn btn-danger <?php if (!$br['br_main_image']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_br_main_image" value="<?php echo $br['br_main_image']; ?>">

                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <span class="red">
                                                    * 최대 15MB / 확장자 jpg, png만 가능 <br>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 이미지(모바일)</th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php
                                            $img_file = G5_DATA_PATH . '/brand/' . $br['br_main_image_mobile'];
                                            if ($br['br_main_image_mobile'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/brand/' . $br['br_main_image_mobile'];
                                                echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="">
                                                    <div class="btn btn-info">
                                                        <span><?php if ($w == "u") echo '이미지 수정';
                                                                else echo '이미지 등록'; ?></span>
                                                        <input type="file" id="imgFile" name="br_main_image_mobile" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
                                                    </div>
                                                </span>
                                                <button class="btn btn-danger <?php if (!$br['br_main_image_mobile']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_br_main_image_mobile" value="<?php echo $br['br_main_image_mobile']; ?>">

                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <span class="red">
                                                    * 최대 15MB / 확장자 jpg, png만 가능 <br>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 LOOKBOOK</th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php
                                            $img_file = G5_DATA_PATH . '/brand/' . $br['br_lookbook'];
                                            if ($br['br_lookbook'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/brand/' . $br['br_lookbook'];
                                                echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="">
                                                    <div class="btn btn-info">
                                                        <span><?php if ($w == "u") echo '이미지 수정';
                                                                else echo '이미지 등록'; ?></span>
                                                        <input type="file" id="imgFile" name="br_lookbook" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
                                                    </div>
                                                </span>
                                                <button class="btn btn-danger <?php if (!$br['br_lookbook']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_br_lookbook" value="<?php echo $br['br_lookbook']; ?>">

                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <span class="red">
                                                    * 최대 15MB / 확장자 jpg, png만 가능 <br>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">브랜드 LOOKBOOK(모바일)</th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php
                                            $img_file = G5_DATA_PATH . '/brand/' . $br['br_lookbook_mobile'];
                                            if ($br['br_lookbook_mobile'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/brand/' . $br['br_lookbook_mobile'];
                                                echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="">
                                                    <div class="btn btn-info">
                                                        <span><?php if ($w == "u") echo '이미지 수정';
                                                                else echo '이미지 등록'; ?></span>
                                                        <input type="file" id="imgFile" name="br_lookbook_mobile" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
                                                    </div>
                                                </span>
                                                <button class="btn btn-danger <?php if (!$br['br_lookbook_mobile']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_br_lookbook_mobile" value="<?php echo $br['br_lookbook_mobile']; ?>">

                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <span class="red">
                                                    * 최대 15MB / 확장자 jpg, png만 가능 <br>
                                                </span>
                                            </div>
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
    <? if ($write_min || $write_max) { ?>
        // 글자수 제한
        var char_min = parseInt(<?= $write_min; ?>); // 최소
        var char_max = parseInt(<?= $write_max; ?>); // 최대
        check_byte("br_notice", "char_count");

        $(function() {
            $("#br_notice").on("keyup", function() {
                check_byte("br_notice", "char_count");
            });
        });

    <? } ?>

    function html_auto_br(obj) {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        } else
            obj.value = "";
    }
    $('#startdatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
        locale: 'ko'
    });

    $('#enddatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
        locale: 'ko'
    });

    $("#startdatepicker").on("dp.change", function(e) {
        $('#enddatepicker').data("DateTimePicker").minDate(e.date);
    });

    $("#enddatepicker").on("dp.change", function(e) {
        $('#startdatepicker').data("DateTimePicker").maxDate(e.date);
    });

    $("#btn_cancel").click(function() {
        if (confirm("목록으로 이동 시 입력된 값은 삭제됩니다. 이동하시겠습니까?")) {
            location.href = "board_management.php";
        }
    });

    $("#btn_submit").click(function() {
        fwrite_submit($("#fwrite"));
    });

    var addItemCnt = 0;

    function fwrite_submit(f) {
        // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
        <?= $editor_js; ?>
        <?= $editor_js_mobile; ?>

        //기간 설정
        if ($('#startdate').val() == "") {
            alert('게시일을 입력하세요.');
            return false;
        }

        if ($('#enddate').val() == "") {
            alert('종료일을 입력하세요.');
            return false;
        }

        if (confirm("저장하시겠습니까?")) {
            document.getElementById("btn_submit").disabled = "disabled";
            f.submit();
        } else {
            return false;
        }
    }
</script>
<!-- @END@ 내용부분 끝 -->
<?
include_once('../admin.tail.php');
?>