<?php
if (!isset($review_points)) {
    $sql_review_points = "SELECT cf_review_write_point,cf_review_photo_point,cf_review_first_point FROM lt_config";
    $review_points = sql_fetch($sql_review_points);
}
?>
<style>
    .product-info-review-stars,
    .product-info-review-stars>span {
        display: inline-block;
        background: url(/img/re/star-0@3x.png) left center no-repeat;
        background-size: 70px;
        width: 70px;
    }

    .product-info-review-stars>span {
        background-image: url(/img/re/star-5@3x.png);
        content: "&nbsp;";
    }

    .review-thumbnail {
        width: 100px;
        height: 100px;
        display: none;
    }
    .review-thumbnail-mobile {
        width: 100px;
        height: 100px;
        display: none;
    }

    .review-thumbnail.active {
        display: inline-block;
    }
    .review-thumbnail-mobile.active {
        display: inline-block;
    }

    #review-length {
        position: absolute;
        display: inline-block;
        right: 38px;
        margin-top: -93px;
        font-size: 12px;
        font-weight: 500;
        text-align: right;
        color: #8a8a8a;
    }

    #modal-update-review-content-wrapper th {
        text-align: left;
        font-size: 16px;
        font-weight: bold;
        width: 90px;
    }

    #modal-update-review-content-wrapper td {
        text-align: left;
        font-size: 16px;
    }

    #modal-update-review-content-wrapper .tr_point td {
        font-size: 14px;
        line-height: 1.71;
        letter-spacing: -0.5px;
        text-align: right;
        color: #00bbb4;
    }

    #modal-update-review-content-wrapper .tr_flie_img button {
        font-size: 12px;
        font-weight: normal;
        width: 90px;
        margin: unset;
        vertical-align: baseline;
    }

    #modal-update-review-content-wrapper .btn-review {
        font-size: 12px;
        font-weight: normal;
        width: 340px;
        height: 50px;
        margin: unset;
        vertical-align: baseline;
    }

    @media (max-width: 1366px) {
        #modal-update-review-wrapper .modal-dialog {
            position: fixed;
            bottom: 52px;
            width: 100%;
            margin: 0;
        }

        #modal-update-review-wrapper .modal-dialog .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
        }

        #modal-update-review-wrapper .modal-dialog .modal_header img {
            position: absolute;
            top: 50%;
            right: 0px;
            transform: translate(-50%, -50%);
        }

        .modal-heard-line {
            border-bottom: 1px solid #e0e0e0;
            height: 1px;
            margin: 0 -20px;
        }

        #modal-update-review-content-wrapper {
            border-radius: 20px 20px 0 0;
        }

        #modal-update-review-content-wrapper th {
            font-size: 12px;
            font-weight: 500;
            line-height: normal;
            color: #424242;
        }

        #modal-update-review-content-wrapper td {
            font-size: 12px;
            font-weight: normal;
            line-height: normal;
            color: #3a3a3a;
        }

        .tr_subject {
            height: 30px;
        }

        .tr_option {
            height: 30px;
        }

        .tr_stat {
            height: 30px;
        }

        #modal-update-review-content-wrapper .tr_point td {
            font-size: 12px;
            font-weight: normal;
            line-height: normal;
            color: #f93f00;
            text-align: left;
        }

        #modal-update-review-content {
            max-height: calc(100vh - 180px);
            overflow-x: scroll;
        }

        #modal-update-review-content .tr_image td {
            width: 100vw;
        }

        #modal-update-review-content-wrapper .tr_flie_img button {
            font-size: 14px;
            font-weight: 500;
            color: #333333;
            height: 44px;
            width: 100%;
            margin: unset;
            vertical-align: baseline;
        }

        #modal-update-review-content-wrapper .btn-review {
            font-size: 14px;
            font-weight: normal;
            width: calc((100vw - 55px)/2);
            margin: unset;
            vertical-align: baseline;
        }
        /* 이미지 업로드 버튼 만들기 */
        .fileBox .btn_file {display:inline-block;border:1px solid #000;width:100px;height:30px;font-size:0.8em;line-height:30px;text-align:center;vertical-align:middle}
    }
</style>
<div class="modal fade" id="modal-select-review-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-select-review-content-wrapper" class="modal-content" style="padding: 0 !important; height: auto; width: 100%;">
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: left;">
                <div id="modal-select-review-content" style="padding: 16px;"></div>
            </div>
            <div id="modal-select-review-button">
                <button type="button" id="btn-select-review">리뷰 작성</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update-review-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 940px;">
        
    
    <form id="form-review">
        <input type="hidden" id="is_id" name="is_id" value="">
        <input type="hidden" id="it_id" name="it_id" value="">
        <input type="hidden" id="ct_id" name="ct_id" value="">
        <input type="hidden" id="is_subject" name="is_subject" value="">
        <input type="hidden" id="is_score" name="is_score" value=5>
        <input type="hidden" id="img1" name="img1" value="">
        <input type="hidden" id="img2" name="img2" value="">
        <input type="hidden" id="img3" name="img3" value="">
    
    <div id="modal-update-review-content-wrapper" class="modal-content on-small" style="width: 100%; height: auto; margin-left: unset;">
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: center;">

                <div class="modal_header">리뷰작성
                    <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
                </div>
                <div class="modal-heard-line"></div>
                <div id="modal-update-review-content">

  
                        <table style="width: 100%;">
                            <tr class="tr_subject">
                                <td>구매상품</td>
                                <td id="review-content-subject-mobile"></td>
                            </tr>
                            <!-- <tr class="tr_option">
                                <td>옵션</td>
                                <td id="review-content-option-mobile"></td>
                            </tr> -->
                            <tr class="tr_stat">
                                <td>만족도</td>
                                <td>
                                    <span id="review-content-stars-mobile" class="product-info-review-stars" style="width: 70px; background-size: 70px;"><span style="width: 100%; background-size: 70px;">&nbsp;</span></span>
                                </td>
                            </tr>
                            <tr class="tr_point">
                                <td colspan=2>50자 이상 포토리뷰 작성 시 <?= number_format($review_points['cf_review_photo_point']) ?> 포인트 즉시 적립</td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <!-- <textarea id="review-content-mobile" name="is_content-mobile" style="width: 100%; height: 200px; font-size: 14px; border: unset; font-weight: normal; box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, 0.24); padding: 16px;" placeholder="최소 15자 이상 작성해주세요. 직접 촬영한 사진이 아닐경우 포인트 지급이 보류될 수 있습니다. 상품과 무관하거나 욕설 및 비속어가 포함된 컨텐츠는 사전고지 없이 삭제될 수 있습니다." onkeyup="$('#review-length').text($(this).val().length);" onblur="$('#review-length').text($(this).val().length);"></textarea> -->
                                    <textarea id="review-content_mobile" name="is_content_mobile" style="width: 100%; height: 200px; font-size: 14px; border: unset; font-weight: normal; box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, 0.24); padding: 16px;" placeholder="최소 15자 이상 작성해주세요. 직접 촬영한 사진이 아닐경우 포인트 지급이 보류될 수 있습니다. 상품과 무관하거나 욕설 및 비속어가 포함된 컨텐츠는 사전고지 없이 삭제될 수 있습니다."></textarea>
                                </td>
                            </tr>
                            <tr class="tr_flie_img">
                                <td colspan=2 style=" text-align: left;">
                                    <button type="button" class="btn btn-review" id="btn-upload-photo-mobile">사진 첨부</button>
                                </td>
                            </tr>
                            <!-- <tr>
                                <td colspan=2 style=" text-align: left;">
                                    jpg, png 형읜식 파일로 1매당 최대 10MB 업로드 가능
                                </td>
                            </tr> -->

                            <tr class="tr_image">
                                <td colspan=2>
                                    <div id="review-length"></div>
                                    <div id="modal-update-review-photo" style="text-align: left; padding: 16px 0;">
                                        <img src="../img/theme_img.jpg" class="review-thumbnail-mobile" style="width: 57px; height: 57px;" id="imgimgFile1-mobile" onclick="$('#imgFile1-mobile').click()">
                                        <img src="/img/re/cancle.png" id="imgimgDel1-mobile" style="position: relative; top: -18px; left: -22px; display: none;" onclick="delImage('imgFile1-mobile');">
                                        <!-- <img src="/img/re/cancle.png" onclick="delImage('imgFile1-mobile');"> -->
                                        <img src="../img/theme_img.jpg" class="review-thumbnail-mobile" style="width: 57px; height: 57px;" id="imgimgFile2-mobile" onclick="$('#imgFile2-mobile').click()">
                                        <img src="/img/re/cancle.png" id="imgimgDel2-mobile" style="position: relative; top: -18px; left: -22px; display: none;" onclick="delImage('imgFile2-mobile');">

                                        <img src="../img/theme_img.jpg" class="review-thumbnail-mobile" style="width: 57px; height: 57px;" id="imgimgFile3-mobile" onclick="$('#imgFile3-mobile').click()">
                                        <img src="/img/re/cancle.png" id="imgimgDel3-mobile" style="position: relative; top: -18px; left: -22px; display: none;" onclick="delImage('imgFile3-mobile');">

                                        <input class="img-thumbnail-file-mobile" type="file" id="imgFile1-mobile" name="review_image-mobile[]" style="display:none;" imgCheck='img1' delBtnCheck="imgimgDel1-mobile" delBtnID="btnDelimgFile1" imgID="imgimgFile1-mobile" style="width:100px" accept=".jpg, .png">
                                        <input class="img-thumbnail-file-mobile" type="file" id="imgFile2-mobile" name="review_image-mobile[]" style="display:none;" imgCheck='img2' delBtnCheck="imgimgDel2-mobile" delBtnID="btnDelimgFile2" imgID="imgimgFile2-mobile" style="width:100px" accept=".jpg, .png">
                                        <input class="img-thumbnail-file-mobile" type="file" id="imgFile3-mobile" name="review_image-mobile[]" style="display:none;" imgCheck='img3' delBtnCheck="imgimgDel3-mobile" delBtnID="btnDelimgFile3" imgID="imgimgFile3-mobile" style="width:100px" accept=".jpg, .png">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style=" text-align: left;">
                                    <button type="button" class="btn btn-review" data-dismiss="modal">취소</button>
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn btn-review btn-black" id="btn-update-review-mobile">확인</button>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>

        <div id="modal-update-review-content-wrapper" class="modal-content on-big" style="width: 940px; height: auto; margin-left: unset;">
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: center;">

                <div class="modal_header" style="height: 73px; border-bottom: 1px solid #e0e0e0;">
                    <span style="height: 73px; font-size: 26px; color: #070707; text-align: center; font-weight: bold; position: relative; top: 13px; ">리뷰작성</span>
                    <span style= "position: relative; top: 8px; left: 380px;"><img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal"></span>
                </div>
                <div class="modal-heard-line"></div>
                <div id="modal-update-review-content">
                        <table style="width: 100%; margin-top: 15px;">
                            <colgroup class="on-big">
                                <col style="width: 240px">
                                <col>
                            </colgroup>
                            <tr class="tr_subject">
                                <th style="position: relative; left: 90px; color: #606060; font-size: 14px; font-weight: 500;">구매상품</th>
                                <td id="review-content-subject" style= "color: #424242; font-size: 14px;"></td>
                            </tr>
                            <tr class="tr_option">
                                <!-- <th style="position: relative; left: 90px; color: #606060; font-size: 14px; font-weight: 500;">옵션</th>
                                <td id="review-content-option" style= "color: #424242; font-size: 14px;"></td> -->
                            </tr>
                            <tr class="tr_stat">
                                <th style="position: relative; left: 90px; color: #606060; font-size: 14px; font-weight: 500;">만족도</th>
                                <td>
                                    <span id="review-content-stars" class="product-info-review-stars" style="width: 70px; background-size: 70px; position:relative; z-index:9"><span style="width: 100%; background-size: 70px; position:relative; z-index:10">&nbsp;</span></span>
                                </td>
                            </tr>
                            <tr>
                                <th style="position: relative; left: 90px; color: #606060; font-size: 14px; font-weight: 500;">내용</th>
                                <td style= "color: #f93f00; font-size: 14px;">50자 이상 포토리뷰 작성 시 <?= number_format($review_points['cf_review_photo_point']) ?> 포인트 즉시 적립</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <textarea id="review-content" name="is_content" style="margin-top: 10px; width: 570px; height: 200px; font-size: 14px; border: unset; font-weight: normal; box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, 0.24); padding: 16px;" placeholder="최소 15자 이상 작성해주세요. 직접 촬영한 사진이 아닐경우 포인트 지급이 보류될 수 있습니다. 상품과 무관하거나 욕설 및 비속어가 포함된 컨텐츠는 사전고지 없이 삭제될 수 있습니다."></textarea>
                                </td>
                                <!-- 글자수 <textarea id="review-content" name="is_content" style="width: 500px; height: 200px; font-size: 14px; border: unset; font-weight: normal; box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, 0.24); padding: 16px;" placeholder="최소 15자 이상 작성해주세요. 직접 촬영한 사진이 아닐경우 포인트 지급이 보류될 수 있습니다. 상품과 무관하거나 욕설 및 비속어가 포함된 컨텐츠는 사전고지 없이 삭제될 수 있습니다." onkeyup="$('#review-length').text($(this).val().length);" onblur="$('#review-length').text($(this).val().length);"></textarea> -->
                            </tr>
                            <tr class="tr_flie_img">
                                <th style="position: relative; left: 90px; color: #606060; font-size: 14px; font-weight: 500;">첨부이미지</th>
                                <td>
                                    <div class="fileBox">
                                        <span style="position: relative; top: 1px;"><input type="text" class="fileName" id="fileCheck" style="background-color:#ffffff; width:340px; height:44px;" readonly="readonly"></span>
                                        <span style="margin-left: 15px;"><button type="button" class="btn btn-review" id="btn-upload-photo" style="width:111px; height:44px; border-radius: 2px;  border: solid 1px #333333; background-color: #ffffff;">사진 첨부</button></span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="tr_image">
                                <td colspan=2>
                                    <div id="review-length"></div>
                                    <div id="modal-update-review-photo" style="text-align: left; padding: 16px 240px;">
                                        <img src="../img/theme_img.jpg" class="review-thumbnail" id="imgimgFile1" onclick="$('#imgFile1').click()">
                                        <img src="/img/re/cancle.png" id="imgimgDel1" style="position: relative; top: -35px; left: -25px; display:none;" onclick="delImage('imgFile1');">

                                        <img src="../img/theme_img.jpg" class="review-thumbnail" id="imgimgFile2" onclick="$('#imgFile2').click()" style="margin-left:-6px;">
                                        <img src="/img/re/cancle.png" id="imgimgDel2" style="position: relative; top: -35px; left: -25px; display:none;" onclick="delImage('imgFile2');">

                                        <img src="../img/theme_img.jpg" class="review-thumbnail" id="imgimgFile3" onclick="$('#imgFile3').click()" style="margin-left:-6px;">
                                        <img src="/img/re/cancle.png" id="imgimgDel3" style="position: relative; top: -35px; left: -25px; display:none;" onclick="delImage('imgFile3');">

                                        <input class="img-thumbnail-file" type="file" id="imgFile1" name="review_image[]" style="display:none;" imgCheck='img1' delBtnCheck="imgimgDel1" delBtnID="btnDelimgFile1" imgID="imgimgFile1" style="width:100px" accept=".jpg, .png">
                                        <input class="img-thumbnail-file" type="file" id="imgFile2" name="review_image[]" style="display:none;" imgCheck='img2' delBtnCheck="imgimgDel2" delBtnID="btnDelimgFile2" imgID="imgimgFile2" style="width:100px" accept=".jpg, .png">
                                        <input class="img-thumbnail-file" type="file" id="imgFile3" name="review_image[]" style="display:none;" imgCheck='img3' delBtnCheck="imgimgDel3" delBtnID="btnDelimgFile3" imgID="imgimgFile3" style="width:100px" accept=".jpg, .png">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td td colspan=2>
                                    <button type="button" class="btn btn-review" data-dismiss="modal" style="margin-left: 110px;">취소</button>
                                    <button type="button" class="btn btn-review btn-black" id="btn-update-review" style="margin-left: 20px;">확인</button>
                                </td>
                            </tr>
                            <tr height="30px">

                            </tr>
                        </table>
                    
                </div>
            </div>
        </div>
        </form>
    </div>
</div>



<script>
    function delImage(e){
        $('#fileCheck').val('');
        var fileList = document.getElementsByTagName("input");
        for (var i=0; i < fileList.length ;i++ )
        {
          if(fileList[i].id == e)
          {
            $.delBtnFileUploadSelect(fileList[i]);
          }
        }
    }

    function writeReview(subject, clear) {
        $('#fileCheck').val('');
        if (subject.length > 0) {
            if (clear == true) {
                $("#review-content").html("");
                $("#review-content_mobile").html("");
                for (ffidx = 1; ffidx <= 3; ffidx++) {
                    $("#imgimgFile" + ffidx).attr("src", "/img/theme_img.jpg").removeClass("active");
                    $("#imgimgDel" + ffidx).css("display", "none");
                    $("#imgimgDel-mobile" + ffidx).css("display", "none");
                }
            }
            $("#is_subject").val(subject);
            var option_texts = subject.split('/');
            var option_texts2 = subject;

            $("#review-content-subject").text(option_texts[0]);
            $("#review-content-subject").text(option_texts2);

            $("#review-content-option").text(option_texts[1]);
            
            $("#review-content-subject-mobile").text(option_texts[0]);
            $("#review-content-subject-mobile").text(option_texts2);

            $("#review-content-option-mobile").text(option_texts[1]);

            // $("#review-content-subject").text(subject);
            $("#modal-update-review-wrapper").modal("show");
        }
    }

    function updateUserRating(rating) {
        let stars = Math.floor((rating * 10 / 70) / 2);
        if (stars < 1) stars = 1;
        $("#review-content-stars > span").css("width", stars * 20 + "%");
        $("#review-content-stars-mobile > span").css("width", stars * 20 + "%");
        $("#is_score").val(stars);
    }

    function updateReview(e) {
        const is_id_check = $("#is_id").val();
        var result = false;
        if (is_id_check.length >0) {
            result = confirm("리뷰를 수정하시겠습니까?");
        } else {
            result = confirm("리뷰 작성을 완료하고\n구매를 확정하시겠습니까?");
        }


        if (result) { 
            var form = $('#form-review')[0];
            var data = new FormData(form);
            $("#btn-update-review").prop("disabled", true);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/shop/ajax.review.php?type=write&check='+e,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function(res) {
                    var responseJSON = JSON.parse(res);
                    alert(responseJSON.msg);
                    $("#btn-update-review").prop("disabled", false);
                    $("#modal-update-review-wrapper").modal("hide");
                    location.reload();
                    return responseJSON.result;
                },
                error: function(request, status, error) {
                    $("#btn-update-review").prop("disabled", false);
                    alert(error);
                    return false;
                }
            });
            closePopup();
            return false;
        } else {

        }
    }

    function deleteReview() {
        var result = confirm('삭제된 리뷰는 복구되지 않습니다.\n그래도 삭제하시겠습니까?');
        if (result) {
            // alert("삭제된 리뷰는 복구되지 않습니다.\n그래도 삭제하시겠습니까?");
            const is_id = $("#is_id").val();
            $.get('/shop/ajax.review.php?type=delete&is_id=' + is_id, function(response) {
                // let popupData = {
                //     content: "리뷰가 삭제되었습니다.",
                //     close: {
                //         text: "확인",
                //         action: "location.reload()"
                //     }
                // };
                if (response.result == true) {
                    alert("리뷰가 삭제되었습니다.");
                        return location.reload();
                } else {
                    if (response.msg == 'NOT_FOUND_MEMBER') {
                        return openLogin();
                    }
                    if (response.msg == 'NOT_FOUND_REVIEW') {
                        alert("구매한 제품에 한해 작성할 수 있습니다.");
                        return location.reload();
                    }
                }
            }, "JSON");

            closePopup();
            return false;
        } else {

        }
    }

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
                }

                $(this).removeClass('hidden').addClass('hidden');
            }

            $.delBtnFileUploadSelect = function(event) {
                var fileBt = $("#" + $(event).attr("fileBtnID"));

                var fileBtnID = $(event).attr("id");
                var labalID = $(event).attr("labalID");
                var delBtnID = $(event).attr("delBtnID");
                var imgID = $(event).attr("imgID");
                var delBtnCheck = $(event).attr("delBtnCheck");
                var imgCheck = $(event).attr("imgCheck");
                $("#"+imgCheck).val('');
                $("#" + fileBtnID).val("");
                $("#org" + fileBtnID).val("");
                if (labalID != "") $("#" + labalID).val("");
                if (imgID != "") {
                    $("#" + imgID).attr("src", "../img/theme_img.jpg");
                }
                $("#" + delBtnID).addClass('hidden');
                $("#" + imgID).removeClass('active');
                $("#" + imgID).css("display", "none");
                $(event).removeClass('hidden').addClass('hidden');
                $("#" + delBtnCheck).css("display", "none");
            }

            $.imgFileUploadChange = function(event) {

                var fileBtnID = $(this).attr("id");
                var labalID = $(this).attr("labalID");
                var delBtnID = $(this).attr("delBtnID");
                var imgID = $(this).attr("imgID");
                var delBtnCheck = $(this).attr("delBtnCheck");
                var imgCheck = $(this).attr("imgCheck");
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
                        $("#" +imgCheck).val(e.target.result);
                    }
                    reader.readAsDataURL($(this)[0].files[0]);

                    $("#" + imgID).addClass('active');
                }
                $('#fileCheck').val(fileName);
                $("#" + delBtnID).removeClass('hidden');
                if (labalID != "") $("#" + labalID).val(fileName);
                $("#" + delBtnCheck).css("display", "inline-block");
                $("#" + imgID).css("display", "inline-block");
            }

            $.setImgFileUpload = function(fileInputId) {

                $("#" + fileInputId).on('change', $.imgFileUploadChange);
                var delBtnID = $("#" + fileInputId).attr("delBtnID");
                $("#" + delBtnID).click($.delBtnFileUpload);
            }

            $.setImgFileUpload('imgFile1');
            $.setImgFileUpload('imgFile2');
            $.setImgFileUpload('imgFile3');
            $.setImgFileUpload('imgFile1-mobile');
            $.setImgFileUpload('imgFile2-mobile');
            $.setImgFileUpload('imgFile3-mobile');
        });
    });
    $("#btn-update-review-mobile").on("click", function() {
        updateReview('mobile');
        // let popupData = {
        //     content: "",
        //     confirm: {
        //         action: "updateReview('mobile');"
        //     }
        // };

        // popupData.content = $("#is_id").val().length == 0 ? "리뷰 작성을 완료하고\n구매를 확정하시겠습니까?" : "리뷰를 수정하시겠습니까?";
        // return openPopup(popupData, 'confirm');
    });

    $("#btn-update-review").on("click", function() {
        updateReview('pc');
    });

    $("#btn-upload-photo").on("click", function() {
        const countUploaded = $(".review-thumbnail.active").length;
        if (countUploaded >= 3) {
            alert("사진은 최대 3장까지 첨부 가능합니다.");
        } else {
            $(".img-thumbnail-file:eq(" + countUploaded + ")").click();
        }
    });
    $("#btn-upload-photo-mobile").on("click", function() {
        const countUploadedMobile = $(".review-thumbnail-mobile.active").length;
        if (countUploadedMobile >= 3) {
            alert("사진은 최대 3장까지 첨부 가능합니다.");
        } else {
            $(".img-thumbnail-file-mobile:eq(" + countUploadedMobile + ")").click();
        }
    });

    $("#review-content-stars").on("mousedown", function() {
        $(this).addClass("do-rating");
    }).on("mousemove", function(evt) {
        if ($(this).hasClass("do-rating")) {
            updateUserRating(evt.offsetX);
        }
    }).on("mouseup mouseleave", function(evt) {
        if ($(this).hasClass("do-rating")) {
            $(this).removeClass("do-rating");
            updateUserRating(evt.offsetX);
        }
    })

    $("#review-content-stars-mobile").on("mousedown", function() {
        $(this).addClass("do-rating");
    }).on("mousemove", function(evt) {
        if ($(this).hasClass("do-rating")) {
            updateUserRating(evt.offsetX);
        }
    }).on("mouseup mouseleave", function(evt) {
        if ($(this).hasClass("do-rating")) {
            $(this).removeClass("do-rating");
            updateUserRating(evt.offsetX);
        }
    })

    $(".btn-write-review").on("click", function() {
        $("#is_id").val("");
        const it_id = $(this).data("it");
        const ct_id = $(this).data("ct");
        const review_type = $(this).data("type");
        let popupData = {};

        if (review_type) {
            if (review_type == "update") {
                $.get('/shop/ajax.review.php?it_id=' + it_id + '&ct_id=' + ct_id, function(response) {
                    if (response.result == true) {

                        updateUserRating(response.data['is_score'] * 14);
                        $("#is_id").val(response.data['is_id']);
                        $("#ct_id").val(response.data['ct_id']);
                        var option_text = response.data['is_subject'].split('/');
                        var option_text2 = response.data['is_subject'];
                        $("#review-content-subject").html(option_text[0]);
                        $("#review-content-subject").html(option_text2);
                        
                        $("#review-content-option").html(option_text[1]);
                        $("#review-content-subject-mobile").html(option_text[0]);
                        $("#review-content-subject-mobile").html(option_text2);

                        $("#review-content-option-mobile").html(option_text[1]);
                        $("#review-content").html(response.data['is_content']);
                        $("#review-content_mobile").html(response.data['is_content']);
                        if (response.file.length > 0) {
                            $(response.file).each(function(fidx) {
                                const ffidx = fidx +1
                                // const ffidx = 3 - (fidx * 1);
                                $("#img"+ ffidx).val('check');
                                $("#imgimgFile" + ffidx).attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");
                                $("#imgimgFile"+ffidx+"-mobile").attr("src", "/data/file/itemuse/" + response.file[fidx]['file']).css("display", "inline-block");
                                $("#imgimgDel" + ffidx).css("display", "inline-block");
                                $("#imgimgDel" + ffidx+"-mobile").css("display", "inline-block");
                            });

                        }
                        return writeReview(response.data['is_subject']);
                    } else {
                        if (response.msg == 'NOT_FOUND_MEMBER') {
                            return openLogin();
                        }
                        if (response.msg == 'NOT_FOUND_REVIEW') {
                            popupData.content = "주문 기록이 없습니다.";
                            return openPopup(popupData);
                        }
                    }
                }, "JSON");
            } else if (review_type == "delete") {
                $.get('/shop/ajax.review.php?it_id=' + it_id + '&ct_id=' + ct_id, function(response) {
                    if (response.result == true) {
                        $("#is_id").val(response.data['is_id']);
                        deleteReview(response.data['is_id']);
                    } else {
                        if (response.msg == 'NOT_FOUND_MEMBER') {
                            return openLogin();
                        }
                        if (response.msg == 'NOT_FOUND_REVIEW') {
                            popupData.content = "주문 기록이 없습니다.";
                            return openPopup(popupData);
                        }
                    }
                }, "JSON");
                // deleteReview(response.data['is_id'])
                // let popupData = {
                //     content: "삭제된 리뷰는 복구되지 않습니다.<br>그래도 삭제하시겠습니까?",
                //     confirm: {
                //         text: "삭제",
                //         action: "deleteReview()"
                //     }
                // };

                // openPopup(popupData, 'confirm');
            }
            return false;
        }

        if (it_id.length > 0) {
            $("#it_id").val(it_id);

            $.get('/shop/ajax.review.php?it_id=' + it_id + '&type=orderlist', function(response) {
                let popupData = {
                    content: ""
                };
                if (response.result == true) {
                    if (response.data.length > 1) {
                        if (ct_id > 0) {
                            $(response.data).each(function(idx) {
                                if (response.data[idx]['ct_id'] == ct_id) {
                                    $("#ct_id").val(response.data[idx]['ct_id']);

                                    return writeReview(response.data[idx]['subject'], true);
                                }
                            });
                        } else {
                            let selectContent = [];
                            $(response.data).each(function(idx) {
                                selectContent.push('<div class="custom-control custom-radio custom-control-inline"><input type="radio" class="custom-control-input review-select" id="review-select-' + idx + '" data-ct_id="' + response.data[idx]['ct_id'] + '" name="review-select" value="' + response.data[idx]['subject'] + '"><label class="custom-control-label" for="review-select-' + idx + '" style="line-height: 30px; padding-left: 4px;">' + response.data[idx]['subject'] + '</label></div>')
                            });
                            $("#modal-select-review-content").html(selectContent.join(""));
                            $("#modal-select-review-wrapper").modal("show");
                        }
                    } else {
                        $("#ct_id").val(response.data[0]['ct_id']);

                        return writeReview(response.data[0]['subject'], true);
                    }
                } else {
                    if (response.msg == 'NOT_FOUND_MEMBER') {
                        return openLogin();
                    }
                    if (response.msg == 'NOT_FOUND_ORDER') {
                        popupData.content = "주문 기록이 없습니다.";
                        return openPopup(popupData);
                    }
                }
            }, "JSON");
        }
    });

    $("#btn-select-review").on("click", function() {
        const selectedItem = $(".review-select:checked");
        if (selectedItem.length > 0) {
            $("#ct_id").val(selectedItem.data('ct_id'));
            $("#modal-select-review-wrapper").modal("hide");

            return writeReview(selectedItem.val(), true);
        }
    });
</script>