<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$ba_id = preg_replace('/[^0-9]/', '', $ba_id);

$html_title = "배너";
$g5['title'] = "배너 관리";

if ($w == "u") {
    $html_title .= " 수정";
    $sql = " select * from lt_banner where ba_id = '$ba_id' ";
    $ba = sql_fetch($sql);
    if (!$ba['ba_id']) alert("등록된 자료가 없습니다.");
} else {
    $html_title .= " 입력";
}

include_once(G5_ADMIN_PATH . '/admin.head.php');
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">


            <form name="frmnewwin" action="./banner.update.php" onsubmit="return frmnewwin_check(this);" method="post" enctype="multipart/form-data">
                <input type="hidden" name="w" value="<?php echo $w; ?>">
                <input type="hidden" name="ba_id" value="<?php echo $ba_id; ?>">
                <input type="hidden" name="token" value="">

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                            <caption><?php echo $g5['title']; ?></caption>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="row">사용여부</label></th>
                                    <td>
                                        <div class="radio">
                                            <label><input type="radio" name="ba_use" value=0 <?php echo get_checked($ba['ba_use'], 0) ?>>미사용</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="ba_use" value=1 <?php echo get_checked($ba['ba_use'], 1) ?>>사용</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">배너 타입</label></th>
                                    <td>
                                        <div class="radio">
                                            <label><input type="radio" name="ba_type" value="MAIN" <?php echo get_checked($ba['ba_type'], "MAIN") ?>>메인</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="ba_type" value="GNB_TOP" <?php echo get_checked($ba['ba_type'], "GNB_TOP") ?>>GNB 상단</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="ba_type" value="GNB_IN" <?php echo get_checked($ba['ba_type'], "GNB_IN") ?>>GNB 내부</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="ba_type" value="BAG" <?php echo get_checked($ba['ba_type'], "BAG") ?>>장바구니 상단</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ba_start_date">시작일시<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <input type="text" name="ba_start_date" value="<?php echo $ba['ba_start_date']; ?>" id="ba_start_date" required class="frm_input required" size="21" maxlength="19">
                                        <input type="checkbox" name="ba_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="ba_begin_chk" onclick="if (this.checked == true) this.form.ba_start_date.value=this.form.ba_begin_chk.value; else this.form.ba_start_date.value = this.form.ba_start_date.defaultValue;">
                                        <label for="ba_begin_chk">시작일시를 오늘로</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ba_end_date">종료일시<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <input type="text" name="ba_end_date" value="<?php echo $ba['ba_end_date']; ?>" id="ba_end_date" required class="frm_input required" size="21" maxlength="19">
                                        <input type="checkbox" name="ba_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME + (60 * 60 * 24 * 7)); ?>" id="ba_end_chk" onclick="if (this.checked == true) this.form.ba_end_date.value=this.form.ba_end_chk.value; else this.form.ba_end_date.value = this.form.ba_end_date.defaultValue;">
                                        <label for="ba_end_chk">종료일시를 오늘로부터 7일 후로</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ba_subject">배너 제목<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <input type="text" name="ba_subject" value="<?php echo get_sanitize_input($ba['ba_subject']); ?>" id="ba_subject" required class="frm_input required" size="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ba_content">배너 내용<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <input type="text" name="ba_content" value="<?php echo get_sanitize_input($ba['ba_content']); ?>" id="ba_content" required class="frm_input required" size="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ba_subject">배너 이미지등록<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php

                                            $img_file = G5_DATA_PATH . '/banner/' . $ba['ba_image'];
                                            if ($ba['ba_image'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/banner/' . $ba['ba_image'];
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
                                                        <input type="file" id="imgFile" name="ba_image" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
                                                    </div>
                                                </span>
                                                <button class="btn btn-danger <?php if (!$ba['ba_image']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_ba_image" value="<?php echo $ba['ba_image']; ?>">

                                            </div>

                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <span class="red">
                                                    * 최대 15MB / 확장자 jpg, png만 가능 <br>
                                                    * 배너 이미지 등록 시, 배너 넓이와 높이는 이미지 비율에 맞추어 설정
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">링크</th>
                                    <td>
                                        <input type="text" id="ba_link" name="ba_link" value="<?php echo $ba['ba_link']; ?>" class="frm_input" size="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">글꼴색</th>
                                    <td>
                                        <input type="text" id="ba_color" name="ba_color" value="<?php echo $ba['ba_color']; ?>" class="frm_input" size="6">
                                        <span class="red">* RRGGBB</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">배경색</th>
                                    <td>
                                        <input type="text" id="ba_bg_color" name="ba_bg_color" value="<?php echo $ba['ba_bg_color']; ?>" class="frm_input" size="6">
                                        <span class="red">* RRGGBB</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                            <a href="./banner.update.php?w=d&amp;ba_id=<?php echo $ba_id; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo $ba['ba_subject']; ?> </span>삭제</a>
                            <a href="./design_banner.php" class=" btn btn_02">목록</a>
                            <input type="submit" value="저장" class="btn btn-success" accesskey="s">
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

            $.delBtnFileUpload = function(event) {
                var fileBt = $("#" + $(this).attr("fileBtnID"));

                var fileBtnID = fileBt.attr("id");
                var labalID = fileBt.attr("labalID");
                var delBtnID = fileBt.attr("delBtnID");
                var imgID = fileBt.attr("imgID");

                $("#" + fileBtnID).val("");
                $("#org" + fileBtnID).val("");
                if (labalID != "") $("#" + labalID).val("");
                if (imgID != "") {
                    $("#" + imgID).attr("src", "../img/theme_img.jpg");
                    //$("#"+imgID).removeClass('hidden').addClass('hidden');
                }

                $(this).removeClass('hidden').addClass('hidden');
            }

            $.imgFileUploadChange = function(event) {

                var fileBtnID = $(this).attr("id");
                var labalID = $(this).attr("labalID");
                var delBtnID = $(this).attr("delBtnID");
                var imgID = $(this).attr("imgID");

                var fileName = "";
                if (window.FileReader) {
                    fileName = $(this)[0].files[0].name;
                } else {
                    fileName = $(this)[0].val().split('/').pop().split('\\').pop();
                }

                if (fileName != "" && imgID != "") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#" + imgID).attr("src", e.target.result);
                    }
                    reader.readAsDataURL($(this)[0].files[0]);

                    $("#" + imgID).removeClass('hidden');
                }

                //$("#btnDelMainImgFile").removeClass('d-none').addClass('d-none');
                $("#" + delBtnID).removeClass('hidden');
                if (labalID != "") $("#" + labalID).val(fileName);
            }

            $.setImgFileUpload = function(fileInputId) {

                $("#" + fileInputId).on('change', $.imgFileUploadChange);
                var delBtnID = $("#" + fileInputId).attr("delBtnID");
                $("#" + delBtnID).click($.delBtnFileUpload);
            }

            $.setImgFileUpload('imgFile');
        });
    });

    function frmnewwin_check(f) {
        errmsg = "";
        errfld = "";


        check_field(f.ba_subject, "제목을 입력하세요.");

        if (errmsg != "") {
            alert(errmsg);
            errfld.focus();
            return false;
        }
        return true;
    }
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>