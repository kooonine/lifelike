<?php
//$sub_menu = '100310';
$$sub_menu = '800031';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$si_id = preg_replace('/[^0-9]/', '', $si_id);

$html_title = "시그니처";
if ($w == "u") {
    $html_title .= " 수정";
    $sql = " select * from lt_signature where si_id = '$si_id' ";
    $si = sql_fetch($sql);
    if (!$si['si_id']) alert("등록된 자료가 없습니다.");
} else {
    $html_title .= " 입력";
}

$g5['title'] = $html_title;

include_once(G5_ADMIN_PATH . '/admin.head.php');
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">


            <form name="frmnewwin" action="./signature.update.php" onsubmit="return frmnewwin_check(this);" method="post" enctype="multipart/form-data">
                <input type="hidden" name="w" value="<?php echo $w; ?>">
                <input type="hidden" name="si_id" value="<?php echo $si_id; ?>">
                

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
                                            <label><input type="radio" name="si_use" value=0 <?php echo get_checked($si['si_use'], 0) ?>>미사용</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="si_use" value=1 <?php echo get_checked($si['si_use'], 1) ?>>사용</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">타입</label></th>
                                    <td>
                                        <div class="radio">
                                            <label><input type="radio" name="si_type" value="1" <?php echo get_checked($si['si_type'], "1") ?>>SIGNATURE</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="si_type" value="2" <?php echo get_checked($si['si_type'], "2") ?>>PREMIUM</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="si_type" value="3" <?php echo get_checked($si['si_type'], "3") ?>>GOLD</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="si_type" value="4" <?php echo get_checked($si['si_type'], "4") ?>>COMFORT</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>

                                <!-- 이미지 부분 처리하자  이게 더 좋은거같다 !! -->
                                <tr>
                                    <th scope="row"><label for="ba_subject">PC 이미지<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php

                                            $img_file = G5_DATA_PATH . '/banner/' . $si['si_image'];
                                            if ($si['si_image'] && file_exists($img_file)) {
                                                $img_url = G5_DATA_URL . '/banner/' . $si['si_image'];
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
                                                <button class="btn btn-danger <?php if (!$si['si_image']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_ba_image" value="<?php echo $si['si_image']; ?>">

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
                                    <th scope="row"><label for="ba_subject">MOBILE 이미지<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php

                                            $img_file2 = G5_DATA_PATH . '/banner/' . $si['si_image_mobile'];
                                            if ($si['si_image_mobile'] && file_exists($img_file2)) {
                                                $img_url2 = G5_DATA_URL . '/banner/' . $si['si_image_mobile'];
                                                echo '<img src="' . $img_url2 . '" class="img-thumbnail2" id="imgimgFile2" style="width: 100%; height: 30%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail2" id="imgimgFile2" style="width: 100%; height: 30%;">';
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
                                                <button class="btn btn-danger <?php if (!$si['si_image_mobile']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button>
                                                <input type="hidden" id="orgimgFile" name="org_ba_image" value="<?php echo $si['si_image_mobile']; ?>">

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
                                    <th scope="row">상품</th>
                                    <td>
                                        <input type="text" id="si_link" name="si_link" value="<?php echo $si['si_link']; ?>" class="frm_input" size="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">링크</th>
                                    <td>
                                        <input type="text" id="si_link" name="si_link" value="<?php echo $si['si_link']; ?>" class="frm_input" size="100">
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