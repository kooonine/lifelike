<?php
ob_start();
$g5_title = "FAQ";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
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
    }
</style>
<div id="member-content-wrapper">
    <form method="GET" id="form-faq">
        <input type="hidden" id="form-faq-filter" name="filter" value="<?= $filter ?>">
        <div class="member-content-title on-big">FAQ</div>
        <div class="member-content-desc">
            <div id="search-keyword-main" class="input-group" style="margin-left: unset; margin: 8px 0;">
                <input type="text" class="form-control form-input C1KOBLL" id="input-search-keyword" name="skeyword" placeholder="검색" aria-describedby="btn-search-action" value="<?php echo $skeyword ?>">
                <div class="input-group-append" id="btn-search-action">
                    <button class="btn" type="button" id="btn-search-clear"><img src="/img/re/x.png" srcset="/img/re/x@2x.png 2x,/img/re/x@3x.png 3x"></button>
                    <button class="btn" type="submit"><img src="/img/re/search.png" srcset="/img/re/search@2x.png 2x,/img/re/search@3x.png 3x"></button>
                </div>
            </div>
            <div style="font-size: 14px;">원하는 답변을 찾지 못하셨다면 <a href="/member/customer.php" style="color: #00bbb4;">1:1문의</a>를 이용해주세요</div>
        </div>


        <div class="member-content-title" style="text-align: right; margin-top: 50px;">
            <span class="member-faq-filter <?= empty($filter) ? "active" : "" ?>" onclick="applyFilter('')">전체보기</span>
            <? foreach (explode('|', $db_filter['fm_subject']) as $f) : ?>
                <span class="member-faq-filter <?= $filter == $f ? "active" : "" ?>" onclick="applyFilter('<?= $f ?>')"><?= $f ?></span>
            <? endforeach ?>
        </div>

        <div class="member-content-section" style="margin-bottom: 40px;">
            <? if ($db_faq->num_rows > 0) : ?>
                <table>
                    <colgroup>
                        <col style="width: 140px">
                        <col>
                    </colgroup>
                    <tr>
                        <th>유형</th>
                        <th>제목</th>
                    </tr>
                    <? for ($oi = 0; $faq = sql_fetch_array($db_faq); $oi++) : ?>
                        <tr>
                            <td><?= strip_tags($faq['fa_category1']) ?></td>
                            <td style="text-align: left; cursor: pointer; font-weight: normal; color: #000000;" onclick="openAnswer(this)"><?= $faq['fa_subject'] ?></td>
                        </tr>
                        <tr class="qna-content">
                            <td class="on-big"></td>
                            <td colspan=4>
                                <?= $faq['fa_content'] ?>
                            </td>
                        </tr>
                    <? endfor ?>
                </table>
            <? else : ?>
                <div class="member-no-content">
                    검색결과가 없습니다.
                </div>
            <? endif ?>
        </div>
        <? if ($paging) : ?>
            <div class="page-margin-bottom"><?= $paging ?></div>
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
        if ($(elem).parent().next(".qna-content").hasClass("active") === true) {
            $(elem).parent().next(".qna-content").removeClass("active");
        } else {
            $(".qna-content").removeClass("active");
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