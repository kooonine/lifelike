<?php
ob_start();
$g5_title = "Q&A";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    div.member-content-section tr>td {
        height: 56px;
    }

    .qna-content {
        display: none;
    }

    .qna-content.active {
        display: table-row;
    }

    .qna-content>td {
        font-size: 16px !important;
        font-weight: bold !important;
        color: #000000 !important;
        text-align: left !important;
        background-color: #f2f2f2;
        padding: 16px 0 16px 20px;

    }

    .qna-answer {
        font-size: 16px !important;
        font-weight: normal;
        color: #000000 !important;
        margin-top: 14px !important;
    }

    .qna-answer>p,
    .qna-content>td>p {
        margin-bottom: unset;
    }

    .btn-list,
    .btn-list-black {
        font-size: 12px;
        font-weight: normal !important;
        color: #7f7f7f;
        height: 32px;
        border: solid 1px #333333 !important;
        margin-top: unset;
        background-color: #ffffff;
    }

    .btn-list-black {
        color: #ffffff !important;
        background-color: var(--black-two) !important;
        border-color: var(--black-two) !important;
        vertical-align: top;
        float: right;
    }

    #member-qna-write th,
    #member-qna-write td {
        text-align: left;
        border-bottom: unset;
        height: auto;
        padding: 8px 0;
    }

    #member-qna-write td {
        font-weight: normal;
        font-size: 14px;
        line-height: 28px;
    }


    #qa-check-email {
        margin-left: 8px;
    }

    #qa-check-email>.custom-control-label::before {
        margin-left: 4px;
        margin-top: 2px;
    }

    #qa-check-email>.custom-control-label::after {
        margin-left: 4px;
        margin-top: 2px;
    }

    .popup-order-item-wrapper {
        font-size: 12px;
        padding: 8px 20px;
        line-height: 20px;

    }

    .popup-order-item-wrapper>div {
        padding: 0 4px;
    }

    .ellipsis {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        display: block;
        width: 160px;
        line-height: 56px;
        font-size: 14px;
    }

    @media (max-width: 1366px) {
        #member-content-wrapper {
            padding: 0 20px;
        }

        .ellipsis {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            display: block;
            line-height: 56px;
            font-size: 14px;
        }
    }

    .add_qna_btn {
        margin: 0 14px;
        height: 44px;
        text-align: center;
        line-height: 44px;
        border-radius: 2px;
        border: 1px solid #333333;
        font-size: 14px;
        font-weight: 500;
        margin-top: 24px;
    }

    .mo_modify_btn {
        width: 64px;
        height: 36px;
        border-radius: 20px;
        line-height: 36px;
        border: 1px solid #333333;
        text-align: center;
        font-size: 14px;
        color: #ffffff;
        background-color: #333333;
    }

    .mo_delete_btn {
        width: 64px;
        height: 36px;
        border-radius: 20px;
        line-height: 36px;
        border: 1px solid #333333;
        text-align: center;
        font-size: 14px;
        color: #3a3a3a;
    }

    .test {
        background: url(../img/mobile/gnb_bg2.png)
    }
</style>
<div id="member-content-wrapper">
    <!-- 이건 밑으로 내려야함 -->
    <!-- <div class="member-content-title on-big">상품 Q&A 내역</div>
    <div class="member-content-section" style="margin-bottom: 90px;">
        <div style="font-size: 14px; color: #7f7f7f; padding-top: 8px;">
            상품 Q&A 내역은 조회 가능 기간은 3년입니다.<br>
            개별 상품에 관한 문의 내용은 해당 상세 페이지 하단 Q&A에서도 확인 하실 수 있습니다.
        </div>
    </div> -->
    <section class="member-qna-list">
        <div class="member-content-title on-big" style="color: #333333; font-size: 22px; font-weight: bold;">Q&A</div>
        <div class="member-content-title on-small"></div>
        <div class="member-content-section" style="margin-bottom: 40px;">
            <table id="member_qna_list_table">
                <colgroup class="on-big">
                    <col style="width: 180px">
                    <col style="width: 140px">
                    <col style="width: 530px">
                    <col style="width: 160px">
                    <col style="width: 85px">
                    <col style="width: 85px">
                </colgroup>
                <colgroup class="on-small">
                    <col style="width: 280px">
                    <col style="width: 52px">
                </colgroup>
                <tr class="on-big" style="border-top : 3px solid #333333;">
                    <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">상품명</th>
                    <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">문의 종류</th>
                    <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">문의 내용</th>
                    <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">문의일</th>
                    <th colspan=2 style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">답변여부</th>
                </tr>
                <? for ($oi = 0; $qna = sql_fetch_array($db_qna); $oi++) : ?>
                    <tr height="143px" class="on-big" style="border-bottom : 1px solid #333333;">
                        <td class="ellipsis" style="font-size: 16px; font-weight: normal; border:0px; color: #606060;  margin-top: 42px;"><a style="cursor: pointer" onclick="location.href='/shop/item.php?it_id=<?= $qna['it_id'] ?>'"><?= $qna['it_name'] ?></a></td>
                        <td style="font-size: 16px; font-weight: normal; border:0px; color: #f93f00"><?= $qna['iq_category'] ?></td>
                        <td style="text-align:left; font-size: 18px; font-weight: normal; border:0px; color: #606060" onclick="openAnswer(this)"><?= $qna['iq_subject'] ?></td>
                        <td style="font-size: 14px; font-weight: normal; color: #7f7f7f;border:0px;"><?= date("Y.m.d", strtotime($qna['iq_time'])) ?></td>
                        <td colspan=2 style="font-size: 18px; font-weight: normal; color: #f54600;border:0px;">
                            <? if (!empty($qna['iq_answer'])) : ?>
                                답변완료
                                <br>
                            <? else : ?>
                                <!-- <button type="button" class="btn btn-black btn-list" data-id="<? $qna['iq_id'] ?>" data-type="update">수정</button> -->
                                <button type="button" class="btn btn-black btn-list" style="width: 111px; height: 44px; border-radius: 2px; background-color: #333333; margin-top: -1px; color:#ffffff; font-size: 16px;" onclick="qaUpdate('<?= $qna['iq_id'] ?>')">수정</button>
                                <br>
                            <? endif ?>
                            <button type="button" class="btn btn-black btn-list" style="width: 111px; height: 44px; border-radius: 2px; background-color: #ffffff; margin-top: 15px; color:#333333; font-size: 16px;" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</button>
                        </td>
                        <!-- <td> -->
                        <!-- <button type="button" class="btn btn-black btn-list"  data-id="<? $qna['iq_id'] ?>" data-type="delete">삭제</button> -->
                        <!-- <button type="button" class="btn btn-black btn-list" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</button> -->
                        <!-- </td> -->
                    </tr>
                    <? if (!empty($qna['iq_answer'])) : ?>
                    <tr class="qna-content on-big">
                        <td></td>
                        <td colspan=5>
                            Q. <?= $qna['iq_question'] ?>
                            <? if (!empty($qna['iq_answer'])) : ?>
                                <div class="qna-answer">
                                    A. <?= $qna['iq_answer'] ?>
                                </div>
                            <? endif ?>
                        </td>
                    </tr>
                    <? endif ?>
                    <tr height="116px" class="on-small" style="border-bottom : 1px solid #e0e0e0 ;">
                        <td style="font-size: 14px; font-weight: normal; border:0px" onclick="openAnswerMo(this)">
                            <!-- 화살표 ㅋㅋ -->

                            <!-- ///  -->
                            <div style="text-align: left; font-size: 16px;  font-weight: 500;  line-height: normal; color: #333333;"><?= $qna['iq_category'] ?></div>
                            <div style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width:100px; display:block;font-size: 12px;  font-weight: normal; color: #3a3a3a;"><?= $qna['iq_subject'] ?>
                                <? if (!empty($qna['iq_answer'])) : ?>
                                    <img src="/img/mobile/gnb_bg2.png">
                                <? endif ?>
                            </div>
                            <div style="text-align: left;font-size: 12px;  font-weight: normal;  color: #959595"> <span class="lt-col-4"><?= date("Y.m.d", strtotime($qna['iq_time'])) ?></span></div>
                            <div style="text-align: left; font-size: 12px;  font-weight: normal;  color: #f93f00;"><?= $qna['iq_answer'] ? "답변완료" : "답변대기" ?></div>

                        </td>

                        <td style="border:0px;font-size: 14px; font-weight: normal; color: #f54600;border:0px;">
                            <? if (!empty($qna['iq_answer'])) : ?>
                                <div style="margin-left:5px; margin-bottom:-20px;">답변완료</div>
                                <br>
                            <? else : ?>
                            <div class="mo_modify_btn" onclick="qaUpdate('<?= $qna['iq_id'] ?>')">수정</div>
                            <? endif ?>
                            <div class="mo_delete_btn" style="margin-top:7px" onclick="qaDelete('<?= $qna['iq_id'] ?>')" >삭제</div>
                            <!-- <button type="button" class="btn btn-black btn-list" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</button> -->
                        </td>
                    </tr>
                    <? if (!empty($qna['iq_answer'])) : ?>
                        <tr height="79px" class="qna-content on-small">
                            <td colspan=3 style="font-size: 12px !important; font-weight: normal !important; border:0px;">
                                <div style="float:left; margin-right:3px; font-size: 12px; color: #3a3a3a;"> 답변 : </div>
                                <div style="font-size: 12px; color: #3a3a3a;"> <?= $qna['iq_answer'] ?></div>
                                <!-- <br> -->
                                <!-- <div style= "margin-top:-39px; font-size: 12px; color: #959595;"><?= date("Y.m.d", strtotime($qna['iq_time'])) ?></div> -->
                            </td>
                        </tr>
                    <? endif ?>
                <? endfor ?>
            </table>
            <div class="member-content-section on-big" style="margin-bottom: 90px;">
                <div style="font-size: 12px; color: #989898; padding-top: 8px;">
                    · 상품 Q&A 내역은 조회 가능 기간은 3년입니다.<br>
                    · 개별 상품에 관한 문의 내용은 해당 상세 페이지 하단 Q&A에서도 확인 하실 수 있습니다.
                </div>
            </div>
            <? if ($db_qna->num_rows <= 0) : ?>
                <div class="member-no-content" style="padding : 18px 0; border-bottom : 1px solid #7f7f7f;">
                    상품 Q&A 내역이 없습니다
                </div>
            <? endif ?>
        </div>
        <? if ($paging) : ?>
            <div class="on-big" style="margin-bottom: 170px;"><?= $paging ?></div>
        <? endif ?>

        <!-- 더보기 찬스같은소리하고있내ㅋ ㅋ-->
        <?php if ($total_count > 5) : ?>
            <div class="on-small add_qna_btn"><a onclick="addList(<?= $total_page ?>)">더보기</a></div>
        <? endif ?>

        <!-- end0------------- -->
    </section>
    <div style="margin-bottom: 120px; display: inline-block;"></div>
</div>

<div class="modal fade" id="modal-order-list-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 340px;">
        <div id="modal-popup-orderlist-wrapper" class="modal-content" style="padding: unset !important; width: 339px;">
            <div id="modal-popup-title" style="font-size: 16px; font-weight: bold; color: #000000; margin: 0 20px; padding: 8px 0; border-bottom: 1px solid #000;">주문번호 조회</div>
            <div id="modal-popup-orderlist" class="modal-custom-scrollbar scrollbar-inner">
                <? while (false != ($order = sql_fetch_array($db_order))) : ?>
                    <div class="popup-order-item-wrapper">
                        <div style="font-weight: 600; background-color: #e0e0e0; cursor: pointer;" onclick=setOrderId(<?= $order['od_id'] ?>)>주문번호 : <?= $order['od_id'] ?></div>
                        <div><?= $order['it_name'] ?></div>
                        <div>주문일 : <?= $order['od_time'] ?></div>
                        <div>결제금액 : <?= number_format($order['od_cart_price']) ?>원</div>
                    </div>
                <? endwhile ?>
            </div>
            <div id="modal-popup-button" style="text-align: center;"></div>
        </div>
    </div>
</div>

<script>
    $(".modal-custom-scrollbar").scrollbar({
        height: 300,
        disableBodyScroll: true
    });

    function writeQna() {
        $(".member-qna-list").hide();
        $(".member-qna-write").show();
    }

    function openAnswer(elem) {
        if ($(elem).parent().next(".qna-content.on-big").hasClass("active") === true) {
            $(elem).parent().next(".qna-content.on-big").removeClass("active");
        } else {
            $(".qna-content.on-big").removeClass("active");
            $(elem).parent().next(".qna-content.on-big").addClass("active");
        }
    }

    function openAnswerMo(elem) {
        if ($(elem).parent().next(".qna-content.on-small").hasClass("active") === true) {
            $(elem).parent().next(".qna-content.on-small").removeClass("active");
        } else {
            $(".qna-content.on-small").removeClass("active");
            $(elem).parent().next(".qna-content.on-small").addClass("active");
        }
    }

    function setOrderId(id) {
        $("#od_id").val(id);
        $("#modal-order-list-wrapper").modal("hide");
    }

    function qaUpdate(iq_id) {
        var error = "";
        $.get('ajax.member.qna.list.php?iq_id=' + iq_id, function(data) {
            console.log(data);
            // const $modal = $("#modal-claim");
            // const $data = JSON.parse(data);

            // if ($data.error) {
            // 	alert($data.error);
            // 	return false;
            // }

            // $($modal).find(".modal-title").html($data.title);
            // $($modal).find(".modal-desc").html($data.desc);
            // $($modal).find(".modal-body").html($data.body);
            // $($modal).find(".modal-footer").html($data.footer);

            // $modal.modal('show');
        });
    }

    var add_qna_page = 2;

    function addList(totalPage) {

        var type = $('.product-detail-qna-type-mo').val();
        var it_id = $('#qna_it_id_hi').val();
        if (type == '전체보기') {
            type = "";
        }
        $.ajax({
            url: '/ajax_front/ajax.qna_member.php',
            type: 'post',
            data: {
                page: add_qna_page,
                it_id: it_id,
                type: type
            },

            success: function(response) {
                $('#member_qna_list_table').append(response);
                add_qna_page++;
            }
        });
        if (add_qna_page >= totalPage) {
            $('.add_qna_btn').css('display', 'none');
        }
    }
</script>
<?php
include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>