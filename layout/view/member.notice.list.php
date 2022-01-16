<?php
ob_start();
$g5_title = "공지사항";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    div.member-content-section tr>td {
        height: 56px;
        padding-left: 20px;
    }

    .qna-content {
        display: none;
    }

    .qna-content.active {
        display: table-row;
    }

    .qna-content>td {
        font-size: 16px !important;
        color: #000000 !important;
        text-align: left !important;
        background-color: #e0e0e0;
        padding: 16px 0 16px 20px;
    }

    .qna-answer {
        font-size: 16px !important;
        font-weight: normal;
        color: #000000 !important;
        margin-top: 14px !important;
    }

    td>p {
        font-size: 14px;
        font-weight: normal;
        margin-bottom: unset;
    }

    .btn-list,
    .btn-list-black {
        font-size: 12px !important;
        font-weight: normal !important;
        color: #7f7f7f !important;
        height: 32px !important;
        border: solid 1px #cecece !important;
        margin-top: unset !important;
        background-color: #ffffff !important;
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

    .member-faq-filter {
        font-size: 14px;
        font-weight: normal;
        line-height: normal;
        color: #7f7f7f;
        cursor: pointer;
    }

    .member-faq-filter:after {
        content: '';
        margin-left: 8px;
        border-left: 1px solid #000000;
        height: 13px;
        display: inline-block;
        vertical-align: middle;
    }

    .member-faq-filter:last-child:after {
        display: none;
    }

    .member-faq-filter.active {
        color: #000000;
    }

    @media (max-width: 1366px) {
        #member-content-wrapper {
            padding: 0 20px;
        }

        div.member-content-section tr>td {
            height: 56px;
            padding-left: 0px;
        }
    }
</style>
<div id="member-content-wrapper">
    <form method="GET" id="form-faq">
        <input type="hidden" id="form-faq-filter" name="filter" value="<?= $filter ?>">
        <div class="member-content-title on-big">공지사항</div>
        <div class="member-content-title on-big" style="text-align: right; margin-top: 50px;">
            <span class="member-faq-filter <?= empty($filter) ? "active" : "" ?>" onclick="applyFilter('')">전체보기</span>
            <span class="member-faq-filter <?= $filter == '공지사항' ? "active" : "" ?>" onclick="applyFilter('공지사항')">공지사항</span>
            <span class="member-faq-filter <?= $filter == '이벤트' ? "active" : "" ?>" onclick="applyFilter('이벤트')">이벤트</span>
        </div>


        <div class="member-content-title on-small" style="text-align: left;">
            <span class="member-faq-filter <?= empty($filter) ? "active" : "" ?>" onclick="applyFilter('')">전체보기</span>
            <span class="member-faq-filter <?= $filter == '공지사항' ? "active" : "" ?>" onclick="applyFilter('공지사항')">공지사항</span>
            <span class="member-faq-filter <?= $filter == '이벤트' ? "active" : "" ?>" onclick="applyFilter('이벤트')">이벤트</span>
        </div>

        <div class="member-content-section" style="margin-bottom: 40px;">
            <? if ($db_notice->num_rows > 0) : ?>
                <table>
                    <colgroup class="on-big">
                        <col style="width: 140px">
                        <col>
                    </colgroup>
                    <tr class="on-big">
                        <th>유형</th>
                        <th>제목</th>
                        <th>등록일</th>
                    </tr>
                    <colgroup>
                        <col style="width: 112px">
                        <col>
                    </colgroup class="on-small">
                    <tr class="on-small">
                        <th>유형</th>
                        <th>내용</th>
                    </tr>
                    <? for ($oi = 0; $notice = sql_fetch_array($db_notice); $oi++) : ?>
                        <tr>
                            <td style="cursor: pointer;" onclick="openAnswer(this)"><?= strip_tags($notice['ca_name']) ?></td>
                            <td style="text-align: left; cursor: pointer; font-size: 14px; font-weight: normal; color: #000000;" onclick="openAnswer(this)">
                                <?= $notice['wr_subject'] ?>
                                <p class="on-small"><?= date("Y.m.d", strtotime($notice['wr_datetime'])) ?></p>
                            </td>
                            <td class="on-big" style="font-size: 16px; font-weight: 600; color: #7f7f7f;"><?= date("Y.m.d", strtotime($notice['wr_datetime'])) ?></td>
                        </tr>
                        <tr class="qna-content">
                            <td class="on-big"></td>
                            <td colspan=2 class="on-big">
                                <?= $notice['wr_content'] ?>
                            </td>
                            <td colspan=2 class="on-small">
                                <?= $notice['wr_content_mobile'] ?>
                            </td>
                        </tr>
                    <? endfor ?>
                </table>
            <? else : ?>
                <div class="member-no-content">
                    등록된 공지사항이 없습니다.
                </div>
            <? endif ?>
        </div>
        <? if ($paging) : ?>
            <div style="margin-bottom: 170px;"><?= $paging ?></div>
        <? endif ?>
    </form>
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
        if ($(".qna-content").hasClass("active") === true) {
            $(".qna-content").removeClass("active");
        } else {
            $(elem).parent().next(".qna-content").addClass("active");
        }
    }

    function setOrderId(id) {
        $("#od_id").val(id);
        $("#modal-order-list-wrapper").modal("hide");
    }

    function applyFilter(filter) {
        $("#form-faq-filter").val(filter);
        $("#form-faq").submit();
    }
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>