<?
$sub_menu = "800030";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == '') {
    $title_msg = '작성';
    if ($cp_id) {
        alert('글쓰기에는 \$cp_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_special where cp_id = '$cp_id' ";
    $cp = sql_fetch($sql);
    if (!$cp['cp_id']) alert("등록된 자료가 없습니다.");

    $cp_banner_checked = array();
    foreach (explode(',', $cp['cp_banner']) as $cb) {
        $cp_banner_checked[$cb] = "checked";
    }
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

$cp_item_set = array();
if (!empty($cp['cp_item_set'])) {
    $cp_item_set = json_decode($cp['cp_item_set'], true);
}

$g5['title'] = $title_msg;

$is_html = true;
$is_category = false;
$is_link = true;
$is_file = true;
$is_file_content = true;

$html_checked   = "";
$html_value     = "";
$subject = "";

if (isset($cp['cp_subject'])) {
    $subject = str_replace("\"", "&#034;", get_text(cut_str($cp['cp_subject'], 255), 0));
}

$content = '';
if ($w == 'r') {
    if (!strstr($cp['cp_option'], 'html')) {
        $content = "\n\n\n &gt; "
            . "\n &gt; "
            . "\n &gt; " . str_replace("\n", "\n> ", get_text($cp['cp_content'], 0))
            . "\n &gt; "
            . "\n &gt; ";
    }
} else {
    $content = get_text($cp['cp_content'], 0);
}

$content_mobile = '';
if ($w == 'r') {
    if (!strstr($cp['cp_option'], 'html')) {
        $content_mobile = "\n\n\n &gt; "
            . "\n &gt; "
            . "\n &gt; " . str_replace("\n", "\n> ", get_text($cp['cp_content_mobile'], 0))
            . "\n &gt; "
            . "\n &gt; ";
    }
} else {
    $content_mobile = get_text($cp['cp_content_mobile'], 0);
}

$is_dhtml_editor = true;
$is_dhtml_editor_use = false;
$editor_content_js = '';
if (!is_mobile() || defined('G5_IS_MOBILE_DHTML_USE') && G5_IS_MOBILE_DHTML_USE)
    $is_dhtml_editor_use = true;

// 모바일에서는 G5_IS_MOBILE_DHTML_USE 설정에 따라 DHTML 에디터 적용
if ($config['cf_editor'] && $is_dhtml_editor_use && $member['mb_level'] >= $board['bo_html_level']) {
    $is_dhtml_editor = true;

    if ($w == 'u' && (!$is_member || !$is_admin || $cp['mb_id'] !== $member['mb_id'])) {
        // kisa 취약점 제보 xss 필터 적용
        $content = get_text(html_purifier($cp['cp_content']), 0);

        $content_mobile = get_text(html_purifier($cp['cp_content_mobile']), 0);
    }

    if (is_file(G5_EDITOR_PATH . '/' . $config['cf_editor'] . '/autosave.editor.js'))
        $editor_content_js = '<script src="' . G5_EDITOR_URL . '/' . $config['cf_editor'] . '/autosave.editor.js"></script>' . PHP_EOL;
}
$editor_html = editor_html('cp_content', $content, $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('cp_content', $is_dhtml_editor);
$editor_js .= chk_editor_js('cp_content', $is_dhtml_editor);

$editor_html_mobile = editor_html('cp_content_mobile', $content_mobile, $is_dhtml_editor);
$editor_js_mobile = '';
$editor_js_mobile .= get_editor_js('cp_content_mobile', $is_dhtml_editor);
$editor_js_mobile .= chk_editor_js('cp_content_mobile', $is_dhtml_editor);

$g5['title'] = "특가 " . $title_msg;
include_once('../admin.head.php');

$action_url = https_url('adm') . "/re/special.update.php";
$categories = array(
    'THEME' => '테마전',
    'MD' => 'MD`s Pick',
    'BRAND' => 'BRAND',
    'BEST' => 'BEST',
    'NEW' => 'NEW',
    'HOT' => 'HOT',
    'EVENT' => 'EVENT',
);
$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');
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
                <input type="hidden" name="cp_id" value="<?= $cp_id ?>">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span> <?= $g5['title'] ?><small></small></h4>
                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table id="compaign-content-wrapper">
                            <caption><?= $g5['title'] ?></caption>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                                <col class="grid_3">
                            </colgroup>
                            <tbody>
                                <!-- <tr>
                                    <th scope="row">기획전 유형</th>
                                    <td colspan="2">
                                        <select name="cp_category">
                                            <? foreach ($categories as $ck => $category) : ?>
                                                <option value="<?= $ck ?>" <?= $cp['cp_category'] == $ck ? "selected" : "" ?>><?= $category ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr> -->
                                <tr>
                                    <th scope="row">제목</th>
                                    <td colspan="2">
                                        <input type="text" name="cp_subject" value="<?= $subject ?>" id="cp_subject" required class="frm_input full_input required" size="100" maxlength="100" placeholder="제목은 100자까지 입력가능" style="width: 100%">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">요약설명</th>
                                    <td colspan="2">
                                        <input type="text" name="cp_desc" value="<?= $cp['cp_desc'] ?>" id="cp_desc" required class="frm_input full_input" size="100" maxlength="100" placeholder="" style="width: 100%">
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
                                                        <div class="radio">
                                                            <label><input type="radio" name="cp_use" value=0 <?php echo get_checked($cp['cp_use'], 0) ?>>비공개</label>&nbsp;&nbsp;
                                                            <label><input type="radio" name="cp_use" value=1 <?php echo get_checked($cp['cp_use'], 1) ?>>공개</label>&nbsp;&nbsp;
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- <tr>
                                                    <th scope="row">배너 타입</th>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="cp_banner[]" value="MAIN" <?= $cp_banner_checked['MAIN'] ?>>MAIN</label>&nbsp;&nbsp;
                                                            <label><input type="checkbox" name="cp_banner[]" value="LIST" <?= $cp_banner_checked['LIST'] ?>>LIST</label>&nbsp;&nbsp;
                                                            <label><input type="checkbox" name="cp_banner[]" value="GNB" <?= $cp_banner_checked['GNB'] ?>>GNB</label>&nbsp;&nbsp;
                                                            <label><input type="checkbox" name="cp_banner[]" value="LNB" <?= $cp_banner_checked['LNB'] ?>>LNB</label>&nbsp;&nbsp;
                                                            <label><input type="checkbox" name="cp_banner[]" value="HISTORY" <?= $cp_banner_checked['HISTORY'] ?>>HISTORY</label>&nbsp;&nbsp;
                                                        </div>
                                                    </td>
                                                </tr> -->
                                                <!-- <tr>
                                                    <th scope="row">링크</th>
                                                    <td>
                                                        <div class="checkbox">
                                                            <?
                                                            $cp_link = $cp['cp_link'];
                                                            if (empty($cp_link) && $w == "u")
                                                                $cp_link = "/special/view.php?cp_id=" . $cp['cp_id'];
                                                            ?>
                                                            <input type="text" name="cp_link" value="<?= $cp_link ?>" size="80">
                                                        </div>
                                                    </td>
                                                </tr> -->
                                                <tr>
                                                    <th scope="row"><label for="cp_start_date">시작일시 *<strong class="sound_only"> 필수</strong></label></th>
                                                    <td>
                                                        <span style="position: relative;">
                                                            <input type="text" name="cp_start_date" value="<?php echo $cp['cp_start_date']; ?>" id="startdatepicker" required class="frm_input required" size="21" maxlength="19">
                                                            <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                                        </span>
                                                        <input type="checkbox" name="cp_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="cp_begin_chk" onclick="if (this.checked == true) this.form.cp_start_date.value=this.form.cp_begin_chk.value; else this.form.cp_start_date.value = this.form.cp_start_date.defaultValue;">
                                                        <label for="cp_begin_chk">시작일시를 오늘로</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><label for="cp_end_date">종료일시 * <strong class="sound_only"> 필수</strong></label></th>
                                                    <td>
                                                        <span style="position: relative;">
                                                            <input type="text" name="cp_end_date" value="<?php echo $cp['cp_end_date']; ?>" id="enddatepicker" required class="frm_input required" size="21" maxlength="19">
                                                            <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                                        </span>
                                                        <input type="checkbox" name="cp_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME + (60 * 60 * 24 * 7)); ?>" id="cp_end_chk" onclick="if (this.checked == true) this.form.cp_end_date.value=this.form.cp_end_chk.value; else this.form.cp_end_date.value = this.form.cp_end_date.defaultValue;">
                                                        <label for="cp_end_chk">종료일시를 오늘로부터 7일 후로</label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">PC (1420*200)</th>
                                    <td colspan="2">
                                        <div class="cp_content <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
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
                                <tr id="trwr_content_mobile" <?= ($cp['cp_10'] == '0') ? 'hidden' : ''; ?>>
                                    <th scope="row">MOBILE (1080*330)</th>
                                    <td colspan="2">
                                        <div class="cp_content <?= $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
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
                                <!-- <tr>
                                    <th scope="row">쿠폰 자동 생성기</th>
                                    <td colspan="2">
                                        <button type="button" onclick="window.open('/adm/shop_admin/couponmaker.popup.php', '_blank', 'width=500px,height=800px');">열기</button>
                                    </td>
                                </tr> -->

                                <!-- <?php foreach ($banners as $bi => $banner) : ?>
                                    <?php $i = $bi + 1 ?>
                                    <tr>
                                        <th scope="row"><?= $banner ?> 이미지</th>
                                        <td colspan="2">
                                            <input type="file" name="cp_image_<?= $i ?>" id="cp_image[<?= $i ?>]" title="<?= $banner ?> 이미지" class="frm_file " accept="image/*">
                                            <?php if ($w == 'u' && $cp['cp_image_' . $i]) : ?>
                                                <span class="file_del">
                                                    <input type="checkbox" id="cp_image_del_<?= $i ?>" name="cp_image_del[<?= $i ?>]" value="1"> <label for="cp_image_del_<?= $i ?>"><?= $cp['cp_image_' . $i] ?> 파일 삭제</label>
                                                </span>
                                                <button class="btn btn_01" type="button" id="btn_preview_img" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $cp['cp_image_' . $i] ?>")>미리보기</button>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?> -->
                                <!-- <tr>
                                    <th scope="row">리스트이미지(PC) </th>
                                    <td colspan="2">
                                        <input type="file" name="cp_image_1" id="cp_image[1]" title="<?= $banner ?> 이미지" class="frm_file " accept="image/*">
                                        <?php if ($w == 'u' && $cp['cp_image_1']) : ?>
                                            <span class="file_del">
                                                <input type="checkbox" id="cp_image_del_1" name="cp_image_del1" value="1"> <label for="cp_image_del_1"><?= $cp['cp_image_1'] ?> 파일 삭제</label>
                                            </span>
                                            <button class="btn btn_01" type="button" id="btn_preview_img" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $cp['cp_image_1'] ?>")>미리보기</button>
                                        <?php endif ?>
                                    </td>
                                </tr> -->
                                <!-- <tr>
                                    <th scope="row">리스트이미지(모바일)</th>
                                    <td colspan="2">
                                        <input type="file" name="cp_image_2" id="cp_image[2]" title="<?= $banner ?> 이미지" class="frm_file " accept="image/*">
                                        <?php if ($w == 'u' && $cp['cp_image_2']) : ?>
                                            <span class="file_del">
                                                <input type="checkbox" id="cp_image_del_2" name="cp_image_del2" value="1"> <label for="cp_image_del_2"><?= $cp['cp_image_2'] ?> 파일 삭제</label>
                                            </span>
                                            <button class="btn btn_01" type="button" id="btn_preview_img" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $cp['cp_image_2'] ?>")>미리보기</button>
                                        <?php endif ?>
                                    </td>
                                </tr> -->
                                <?php if (empty($cp_item_set)) : ?>
                                    <tr>
                                        <th scope="row">상품세트 1 <button type="button" class="btn-add first" data-item-idx=1>+</button></th>
                                        <td scope="row" colspen=2>
                                            세트 제목&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_subject[1]" id="cp_item_set_subject_1" value=""><br>
                                            선택 상품&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_item[1]" id="cp_item_set_item_1" value="" readonly><button type="button" class="btn frm_input" target-data="coupon_product_modal" data-item-idx=1 onclick=openCpItemPopup(this)>상품선택</button><br>
                                            <!-- 선택 분류&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_category[1]" id="cp_item_set_category_1" value="" readonly><button type="button" class="btn frm_input" target-data="coupon_category_modal" data-item-idx=1 onclick=openCpItemPopup(this)>분류선택</button> -->
                                        </td>
                                    </tr>
                                <? else : ?>

                                    <?php foreach ($cp_item_set as $ii => $cp_item) : ?>
                                        <tr>
                                            <th scope="row">상품세트 <?= $ii ?><button type="button" class="btn-add <?= $first_item !== false ? "first" : "" ?>" data-item-idx=<?= $ii ?>>+</button></th>
                                            <td scope="row" colspen=2>
                                                세트 제목&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_subject[<?= $ii ?>]" id="cp_item_set_subject_<?= $ii ?>" value="<?= $cp_item['subject'] ?>"><br>
                                                선택 상품&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_item[<?= $ii ?>]" id="cp_item_set_item_<?= $ii ?>" value="<?= $cp_item['item'] ?>" readonly><button type="button" class="btn frm_input" target-data="coupon_product_modal" data-item-idx=<?= $ii ?> onclick=openCpItemPopup(this)>상품선택</button><br>
                                                <!-- 선택 분류&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_category[<?= $ii ?>]" id="cp_item_set_category_<?= $ii ?>" value="<?= $cp_item['category'] ?>" readonly><button type="button" class="btn frm_input" target-data="coupon_category_modal" data-item-idx=<?= $ii ?> onclick=openCpItemPopup(this)>분류선택</button> -->
                                            </td>
                                        </tr>
                                        <?php $first_item = false ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                            <button class="btn btn_02" type="button" id="btn_cancel">취소</button>
                            <button class="btn btn_02 hidden" type="button" id="btn_delete">삭제</button>
                            <a href="/adm/re/special.list.php"><button class="btn btn_02" type="button">목록</button></a>
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
        check_byte("cp_content", "char_count");

        $(function() {
            $("#cp_content").on("keyup", function() {
                check_byte("cp_content", "char_count");
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
        if ($('#startdatepicker').val() == "") {
            alert('게시일을 입력하세요.');
            return false;
        }

        if ($('#enddatepicker').val() == "") {
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
<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품선택</h4>
            </div>

            <div class="modal-body">

                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="row"><label>제품분류</label></th>
                                <td>
                                    <select id="ca_id">
                                        <option value=''>분류별 상품</option>
                                        <?
                                        $sql = " select * from {$g5['g5_shop_category_table']} ";
                                        if ($is_admin != 'super')
                                            $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
                                        $sql .= " order by ca_order, ca_id ";
                                        $result = sql_query($sql);
                                        for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                            $len = strlen($row['ca_id']) / 2 - 1;

                                            $nbsp = "";
                                            for ($i = 0; $i < $len; $i++)
                                                $nbsp .= "&nbsp;&nbsp;&nbsp;";

                                            echo "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>상품번호/상품명/sap코드</label></th>
                                <td>
                                    <input type="text" name="stx" id="stx" value="" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    <button type="button" class="btn btn-success" id="btnSearch">검색</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form name="procForm" id="procForm" method="post">
                    <div class="tbl_frm01 tbl_wrap" id="tblProduct">
                        <? include_once(G5_ADMIN_URL . '/design/design_component_itemsearch.php'); ?>
                    </div>
                </form>

                <div style="text-align: right;">
                    <button type="button" class="btn btn-success" id="btnProductSubmit">추가</button>
                </div>

                <div class="x_title">
                    <h5><span class="fa fa-check-square"></span> 선택된 지정상품</h5>
                    <div style="text-align: right;">
                        <input type="button" class="btn btn-danger" value="삭제" id="btnProductDel" />
                    </div>
                </div>

                <form name="procForm1" id="procForm1" method="post">
                    <div class="tbl_frm01 tbl_wrap" id="tblProductForm">

                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <br><br><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_preview_img" tabindex="-1" role="dialog" aria-labelledby="modal_preview_img">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">미리보기</h4>
            </div>

            <div class="modal-body">
                <div style="width: 100%; max-height: 80%;">
                    <img id="imgPath" src="#" style="width: 50%; max-height: 80%;">
                </div>
                <div id="imgStr">
                </div>
            </div>
            <div class="modal-footer">
                <br><br><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<script>
    function preview_Img(imgPath) {
        $("#imgPath").attr('src', imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }
</script>

<script>
    $(function() {
        $('#coupon_btn_category_add').click(function() {

            var ca_id = $('#coupon_sel_product_main').val();
            if (ca_id != "") {
                let ca_name = $('#coupon_sel_product_main :selected').text();
                let ca_ids = [];

                let stop = false;
                $('#coupon_ul_category li').each(function() {
                    if ($(this).attr("data") == ca_id) {
                        alert("등록된 상품분류입니다.");
                        stop = true;
                        return;
                    }
                    ca_ids.push($(this).attr("data"));
                });
                if (stop) return;

                let li_script = '<li data="' + ca_id + '">' + ca_name +
                    '<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>' +
                    '</li>';

                $('#coupon_ul_category').append(li_script);
                $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
                $("button[name='coupon_btn_category_delete']").css("height", "100%");

                ca_ids.push(ca_id);
                $("#cp_item_set_category_" + CpItemIndex).val(ca_ids.join(','))
            }
        });

        $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
        $("button[name='coupon_btn_category_delete']").css("height", "100%");


        $("#btnSearch").click(function(event) {
            var $table = $("#tblProduct");
            $.post(
                "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                    ca_id: $("#ca_id").val(),
                    stx: $("#stx").val(),
                    not_it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
                },
                function(data) {
                    $table.empty().html(data);
                }
            );
        });

        $("#btnProductDel").click(function(event) {
            if (!is_checked("chk2[]") && !is_checked("n_chk2[]")) {
                alert("삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }
            if (confirm("삭제하시겠습니까?")) {

                var $chk = $("input[name='chk2[]']");
                var $it_id = new Array();

                for (var i = 0; i < $chk.size(); i++) {
                    if (!$($chk[i]).is(':checked')) {
                        var k = $($chk[i]).val();
                        $it_id.push($("input[name='it_id2[" + k + "]']").val());
                    }
                }
                var $nchk = $("input[name='n_chk2[]']");
				for (var j = 0; j < $nchk.size(); j++) {
					if (!$($nchk[j]).is(':checked')) {
						var k = $($nchk[j]).val();
						$it_id.push($("input[name='n_it_id2[" + k + "]']").val());
					}
				}
                $("#cp_item_set_item_" + CpItemIndex).val($it_id.join(","));
                tblProductFormBind();
            }
        });


        $("#btnProductSubmit").click(function(event) {
            if (!is_checked("chk[]")) {
                alert("등록 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            var $chk = $("input[name='chk[]']:checked");
            var $it_id = new Array();

            for (var i = 0; i < $chk.size(); i++) {
                var k = $($chk[i]).val();
                $it_id.push($("input[name='it_id[" + k + "]']").val());
            }

            var it_ids = $it_id.join(",");
            if ($("#cp_item_set_item_" + CpItemIndex).val() != "") it_ids += "," + $("#cp_item_set_item_" + CpItemIndex).val();
            $("#cp_item_set_item_" + CpItemIndex).val(it_ids);

            tblProductFormBind();
            $("#btnSearch").click();
        });

        $("#btnProductSearch").click(function(event) {
            $("#stx").val("");
            var $table = $("#tblProduct");
            $table.empty();
            $("#modal_product").modal('show');
        });
    });
</script>
<div class="modal fade" id="coupon_category_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_category_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품분류 선택</h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="4" style="text-align:center;">
                                        <label>상품분류 선택</label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th rowspan="2">상품분류</th>
                                    <td>
                                        <select name="coupon_sel_product_main" id="coupon_sel_product_main">
                                            <option value=''>분류별 상품</option>
                                            <?
                                            $sql = " select  a.ca_id, a.ca_name
												,b.ca_id as ca_id1, b.ca_name as ca_name1
												,c.ca_id as ca_id2, c.ca_name as ca_name2
												from    {$g5['g5_shop_category_table']} as a
												left outer join {$g5['g5_shop_category_table']} as b
												on left(a.ca_id,2) = b.ca_id
												left outer join {$g5['g5_shop_category_table']} as c
												on left(a.ca_id,4) = c.ca_id
												order by a.ca_order, a.ca_id; ";

                                            $result = sql_query($sql);
                                            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                                $ca_name = $row['ca_name'];
                                                if ($row['ca_name'] != $row['ca_name2']) {
                                                    $ca_name = $row['ca_name2'] . '>' . $ca_name;
                                                }
                                                if ($row['ca_name'] != $row['ca_name1']) {
                                                    $ca_name = $row['ca_name1'] . '>' . $ca_name;
                                                }

                                                echo "<option value=\"{$row['ca_id']}\">$nbsp{$ca_name}</option>\n";
                                            }
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-default" id="coupon_btn_category_add">추가</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <ul data-role="listview" id="coupon_ul_category">
                                            <?
                                            if ($cp['cp_6'] != '') {
                                                $cm_item_ca_id_list = implode("','", explode(',', $cp['cp_6']));

                                                $sql = " select  a.ca_id, a.ca_name
													,b.ca_id as ca_id1, b.ca_name as ca_name1
													,c.ca_id as ca_id2, c.ca_name as ca_name2
													from    {$g5['g5_shop_category_table']} as a
													left outer join {$g5['g5_shop_category_table']} as b
													on left(a.ca_id,2) = b.ca_id
													left outer join {$g5['g5_shop_category_table']} as c
													on left(a.ca_id,4) = c.ca_id
													where   a.ca_id in ('{$cm_item_ca_id_list}')
													order by a.ca_order, a.ca_id; ";

                                                $result = sql_query($sql);
                                                for ($i = 0; $ca_row = sql_fetch_array($result); $i++) {
                                                    $ca_name = $ca_row['ca_name'];
                                                    if ($ca_row['ca_name'] != $ca_row['ca_name2']) {
                                                        $ca_name = $ca_row['ca_name2'] . '>' . $ca_name;
                                                    }
                                                    if ($ca_row['ca_name'] != $ca_row['ca_name1']) {
                                                        $ca_name = $ca_row['ca_name1'] . '>' . $ca_name;
                                                    }
                                            ?>
                                                    <li data="<?= $ca_row['ca_id'] ?>"><?= $ca_name ?>
                                                        <div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>
                                                    </li>
                                            <?
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <br><br><br>
                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">저장</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var CpItemIndex = 0;

        function check_all(f) {
            var chk = document.getElementsByName("chk[]");

            for (i = 0; i < chk.length; i++)
                chk[i].checked = f.chkall.checked;
        }

        function check_all2(f) {
            var chk = document.getElementsByName("chk2[]");

            for (i = 0; i < chk.length; i++)
                chk[i].checked = f.chkall.checked;
        }

        function tblProductFormBind() {

            var $table = $("#tblProductForm");
            $.post(
                "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                    w: "u",
                    it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
                },
                function(data) {
                    $table.empty().html(data);
                }
            );
        };

        function openCpItemPopup(elem) {
            const id = $(elem).attr("target-data");
            CpItemIndex = $(elem).data("item-idx");

            console.log('id ',id);
            tblProductFormBind();
            $('#coupon_ul_category').html("");
            $('#' + id).modal('show');
        }

        $(".btn-add").on("click", function() {
            let nextIdx = $("button.btn-add").last().data("item-idx") * 1 + 1;
            let setHtml = '';
            setHtml += '<tr>';
            setHtml += '<th scope="row">상품세트 ' + nextIdx + ' <button type="button" class="btn-add" data-item-idx=' + nextIdx + '>+</button></th>';
            setHtml += '<td scope="row" colspen=2>';
            setHtml += '세트 제목&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_subject[' + nextIdx + ']" id="cp_item_set_subject_' + nextIdx + '" value=""><br>';
            setHtml += '선택 상품&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_item[' + nextIdx + ']" id="cp_item_set_item_' + nextIdx + '" value="">';
            setHtml += '<button type="button" class="btn frm_input" target-data="coupon_product_modal" data-item-idx=' + nextIdx + ' onclick=openCpItemPopup(this)>상품선택</button><br>';
            setHtml += '선택 분류&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_category[' + nextIdx + ']" id="cp_item_set_category_' + nextIdx + '" value="">';
            setHtml += '<button type="button" class="btn frm_input" target-data="coupon_category_modal" data-item-idx=' + nextIdx + ' onclick=openCpItemPopup(this)>분류선택</button>';
            setHtml += '</td>';
            setHtml += '</tr>';

            $("#compaign-content-wrapper").append(setHtml);
        });
    </script>
    <?
    include_once('../admin.tail.php');
    ?>