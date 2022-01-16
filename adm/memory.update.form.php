<?php
//$sub_menu = '100310';
$sub_menu = '800840';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

// $tp_id = preg_replace('/[^0-9]/', '', $tp_id);

$html_title = "메모리폼";
if ($w == "u") {
    $html_title .= " 수정";
    $sql = " select * from lt_memoryform where mf_id = '$mf_id' ";
    $mf = sql_fetch($sql);

} else {
    $html_title .= " 등록";
}

$g5['title'] = $html_title;

include_once(G5_ADMIN_PATH . '/admin.head.php');
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">


            <form name="frmnewwin" action="./memory.update.php" onsubmit="return submit_check(this);" method="post" enctype="multipart/form-data">
                <input type="hidden" name="w" id="w" value="<?php echo $w; ?>">
                <input type="hidden" name="mf_id" value="<?php echo $mf_id; ?>">
                

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                            <caption><?php echo $g5['title']; ?></caption>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                            </colgroup>
                            <tbody>
                                <!-- 이미지 체크 start -->
                                <!-- -->
                                <tr>
                                    <th scope="row"><label for="ba_subject">이미지<strong class="sound_only"> 필수</strong></label></th>
                                    <td>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <?php

                                            $img_file = $mf['mf_img'];
                                            if ($mf['mf_img']) {
                                                $img_url = $mf['mf_img'];
                                                echo '<img src="' . $img_url . '" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 100%;">';
                                            } else {
                                                echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 100%;">';
                                            }
                                            ?>
                                        </div>

                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="">
                                                    <div class="btn btn-info">
                                                        <span><?php if ($w == "u") echo '이미지 수정';
                                                                else echo '이미지 등록'; ?></span>
                                                        <input type="file" id="imgFile" name="ba_image" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png" value="<?php echo $tp['tp_img']; ?>">
                                                    </div>
                                                </span>
                                                <!-- <button class="btn btn-danger <?php if (!$mf['mf_img']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile">삭제</button> -->
                                                <!-- <input type="hidden" id="orgimgFile" name="org_ba_image" value="<?php echo $mf['mf_img']; ?>"> -->

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
                                <!-- 이미지 체크 end -->
                                
                                <tr>
                                    <th scope="row">사용여부</label></th>
                                    <td>
                                        <div class="radio">
                                            <label><input type="radio" name="mf_use" value=0 <?php echo get_checked($mf['mf_use'], 0) ?>>미사용</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="mf_use" value=1 <?php echo get_checked($mf['mf_use'], 1) ?>>사용</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                            <a href="./memory.list.php" class=" btn btn_02">목록</a>
                            <input type="submit" value="저장" class="btn btn-success">
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

    function submit_check(f) {
        if(confirm("저장하시겠습니까?")) {
            let w = $("#w").val();
            if (w != 'u') {
                let imgFile = $("#imgFile").val();
                if (imgFile == '') {
                    alert("이미지를 확인해주세요.");
                    return false;
                }  
            }
            return true;
        } else {
            return false;
        }
    }
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>