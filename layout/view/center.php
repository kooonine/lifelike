<?php
ob_start();
$g5_title = "고객센터";
?>
<!-- 고객센터 시작 { -->
<link rel="stylesheet" href="/re/css/shop.css">
<script src="<?= G5_JS_URL; ?>/shop.js"></script>
<script src="<?= G5_JS_URL; ?>/shop.override.js"></script>

<!-- 컨텐츠 시작 -->
<div id="list-wrapper" class="center-wrapper">
    <div class="center_title on-big">고객센터</div>

    <?php $uri = $_SERVER['REQUEST_URI']; ?>

    <div class="containter_tab">
        <ul class="containter_tabs">
            <li class="tab-link <?= (strpos($uri, 'filter_faq') || strpos($uri, 'filter_noti')) ? (strpos($uri, 'filter_faq') ? 'current' : '') : 'current' ?>" data-tab="tab-1">FAQ</li>
            <li class="tab-link <?= (strpos($uri, 'filter_faq') || strpos($uri, 'filter_noti')) ? (strpos($uri, 'filter_noti') ? 'current' : '') : '' ?>" data-tab="tab-2">공지사항</li>
        </ul>

        <div id="tab-1" class="tab-content <?= (strpos($uri, 'filter_faq') || strpos($uri, 'filter_noti')) ? (strpos($uri, 'filter_faq') ? 'current' : '') : 'current' ?>">
            <form method="GET" id="form-faq">
                <input type="hidden" id="form-faq-filter" name="filter_faq" value="<?= $filter_faq ?>">
                <div class="member-content-title">
                    <span class="center-filter member-faq-filter <?= empty($filter_faq) ? "active" : "" ?>" onclick="applyFilter_faq('')"><a>전체</a></span>
                    <? foreach (explode('|', $db_filter_faq['fm_subject']) as $f) : ?>
                        <span class="center-filter member-faq-filter <?= $filter_faq == $f ? "active" : "" ?>" onclick="applyFilter_faq('<?= $f ?>')"><a><?= $f ?></a></span>
                    <? endforeach ?>
                </div>
                <div class="member-content-desc">
                    <div id="search-keyword-main" class="input-group" style="margin-left: unset; margin: 8px 0;">
                        <div class="on-big" style="width:200px; text-align:center;  font-weight: 500;">FAQ SEARCH</div>
                        <div class="on-small" style="margin-left: 10px; margin-right: 10px;"> SEARCH</div>
                        <input type="text" class="form-control form-input C1KOBLL" id="input-search-keyword" name="skeyword" placeholder="" aria-describedby="btn-search-action" value="<?php echo $skeyword ?>">
                        <div class="input-group-append" id="btn-search-action">
                            <button class="btn center-search" type="submit"><img src="/img/re/search.png" srcset="/img/re/search@2x.png 2x,/img/re/search@3x.png 3x"></button>
                        </div>
                    </div>
                    <!-- <div style="font-size: 14px;">원하는 답변을 찾지 못하셨다면 <a href="/member/customer.php" style="color: #00bbb4;">1:1문의</a>를 이용해주세요</div> -->
                </div>
                <div class="member-content-section" style="margin-bottom: 40px;">
                    <? if ($db_faq->num_rows > 0) : ?>
                        <table id = "member_faq_list_table">
                            <colgroup class="on-big">
                                <col style="width: 33px">
                                <col>
                            </colgroup>
                            <colgroup class="on-small">
                                <col style="width: 20px">
                                <col>
                            </colgroup>
                            <!-- <tr>
                                <th>유형</th>
                                <th>제목</th>
                            </tr> -->
                            <? for ($oi = 0; $faq = sql_fetch_array($db_faq); $oi++) : ?>
                                <tr>
                                    <!-- <td><?= strip_tags($faq['fa_category1']) ?></td> -->
                                    <td>Q</td>
                                    <td style="text-align: left; cursor: pointer; font-weight: normal; color: #000000;" onclick="openAnswer_faq(this)"><?= $faq['fa_subject'] ?></td>
                                </tr>
                                <tr class="faq-content">
                                    <td class="ans">A</td>
                                    <td colspan=4 class="answer">
                                        <?= $faq['fa_content'] ?>
                                    </td>
                                </tr>
                            <? endfor ?>
                        </table>
                    <? else : ?>
                        <div class="member-no-content">
                            <br><br>검색결과가 없습니다.
                        </div>
                    <? endif ?>
                </div>
                <? if ($paging_faq) : ?>
                    <div class="on-big page-margin-bottom"><?= $paging_faq ?></div>
                <? endif ?>
                <?php if ($total_count_faq > 10) : ?>
                    <div class="on-small add_faq_btn"><a onclick="addList_faq(<?= $total_page_faq ?>)">더보기</a></div>
                <? endif ?>
            </form>

            <div class="faq_footer_area">
                <div class="faq_footer_noti">답변에 만족 하셨나요? 원하시는 답변을 얻지 못하셨다면 1:1 문의를 통하여 상담하세요!</div>
                <div class="btn-customer" onclick="location.href='/member/customer.php'" >1:1 상담 신청</div>
            </div>

        </div>

        <div id="tab-2" class="tab-content <?= (strpos($uri, 'filter_faq') || strpos($uri, 'filter_noti')) ? (strpos($uri, 'filter_noti') ? 'current' : '') : '' ?>">
            <form method="GET" id="form-noti">
                <input type="hidden" id="form-noti-filter" name="filter_notice" value="<?= $filter_notice ?>">
                <div class="member-content-title on-big" style="text-align: left; margin-top: 10px;margin-bottom: 19px;">
                    <!-- <span class="center-filter member-noti-filter <?= empty($filter_notice) ? "active" : "" ?>" onclick="applyFilter_noti('')"><a>전체보기</a></span> -->
                    <!-- <span class="center-filter member-noti-filter <?= $filter_notice == '공지사항' ? "active" : "" ?>" onclick="applyFilter_noti('공지사항')"><a>공지사항</a></span>
                    <span class="center-filter member-noti-filter <?= $filter_notice == '이벤트' ? "active" : "" ?>" onclick="applyFilter_noti('이벤트')"><a>이벤트</a></span> -->
                </div>


                <div class="member-content-title on-small" style="text-align: left; margin-bottom: 12px;">
                    <!-- <span class="center-filter member-noti-filter <?= empty($filter_notice) ? "active" : "" ?>" onclick="applyFilter_noti('')"><a>전체보기</a></span> -->
                    <!-- <span class="center-filter member-noti-filter <?= $filter_notice == '공지사항' ? "active" : "" ?>" onclick="applyFilter_noti('공지사항')"><a>공지사항</a></span>
                    <span class="center-filter member-noti-filter <?= $filter_notice == '이벤트' ? "active" : "" ?>" onclick="applyFilter_noti('이벤트')"><a>이벤트</a></span> -->
                </div>

                <div class="member-content-section notice" style="margin-bottom: 40px;">
                    <? if ($db_notice->num_rows > 0) : ?>
                        <table id="member_noti_list_table">
                            <colgroup class="on-big">
                                <col style="width: 800px">
                                <!-- <col style="width: 140px"> -->
                                <col>
                            </colgroup>
                            <tr class="on-big" style="height : 55px; font-size : 16px; font-weight : 500; color : #424242">
                                <th style="text-align : center;">제목</th>
                                <!-- <th style="padding-left: 20px;">제목</th> -->
                                <th style="text-align : center">등록일</th>
                            </tr>
                            <colgroup>
                                <col style="width: 112px">
                                <col>
                            </colgroup class="on-small">
                            <!-- <tr class="on-small">
                                <th>유형</th>
                                <th>내용</th>
                            </tr> -->
                            <? for ($oi = 0; $notice = sql_fetch_array($db_notice); $oi++) : ?>
                                <tr>
                                    <!-- <td class="on-big" style="cursor: pointer; text-align : center; padding-left: 0px;" onclick="openAnswer_noti(this)"><?= strip_tags($notice['ca_name']) ?></td> -->
                                    <td class="noti_title" onclick="openAnswer_noti(this)">
                                        <p class="wr_subject"><?= $notice['wr_subject'] ?></p>
                                        <p class="on-small"><?= date("Y.m.d", strtotime($notice['wr_datetime'])) ?></p>
                                    </td>
                                    <td class="on-big" style="font-size: 16px; font-weight: 600; color: #7f7f7f; text-align : center; padding-left:0px;" ><?= date("Y.m.d", strtotime($notice['wr_datetime'])) ?></td>
                                </tr>
                                <tr class="noti-content">
                                    <!-- <td class="on-big"></td> -->
                                    <td colspan=2 class="on-big">
                                        <div style="text-align : center;"><?= $notice['wr_content'] ?></div><?= $notice['wr_content'] ?></div>
                                    </td>
                                    <td colspan=2 class="on-small">
                                        <?= $notice['wr_content_mobile'] ?>
                                    </td>
                                </tr>
                            <? endfor ?>
                        </table>
                    <? else : ?>
                        <div class="member-no-content">
                            <br><br>등록된  <?= $filter_notice == '이벤트' ? '이벤트가' : '공지사항이' ?> 없습니다.
                        </div>
                    <? endif ?>
                </div>
                <? if ($paging_noti) : ?>
                    <div class="on-big" style="margin-bottom: 170px;"><?= $paging_noti ?></div>
                <? endif ?>
                <?php if ($total_count_noti > 10) : ?>
                    <div class="on-small add_noti_btn"><a onclick="addList_noti(<?= $total_page_noti ?>)">더보기</a></div>
                <? endif ?>
            </form>

        </div>

    </div>
</div>

<style>
    .wr_subject {
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    #list-wrapper.center-wrapper {
        width: 1000px;
    }

    #btn-search-action>button.center-search {
        margin-left: 10px;
        margin-right: 50px;
    }

    #search-keyword-main {
        width: 100%;
        border: 0px;
        height: 84px;
        border: solid 1px #f2f2f2;
        background-color: #f2f2f2;
        line-height: 84px;
    }

    #btn-search-action>button {
        height: 82px;
    }

    #input-search-keyword {
        margin: 20px;
        border: 1px solid #f2f2f2;
        background-color: #ffffff;
        padding-left : 10px;
    }

    .center_title {
        height: 25px;
        font-size: 26px;
        font-weight: bold;
        line-height: normal;
        text-align: center;
        color: #0d0d0d;
        margin: 80px 0;
    }

    .noti_title {
        text-align: left;
        cursor: pointer;
        font-size: 16px;
        font-weight: normal;
        color: #333333;
    }

    #offset-nav-top {
        height: 90px;
        margin-bottom: 136px;
    }

    .faq-content {
        display: none;
    }

    .faq-content .ans {
        color: #f83f00;
    }

    .faq-content .answer {
        width: calc(100% - 120px);
    }

    .faq-content.active {
        display: table-row;
        border-bottom: solid 1px #f2f2f2;
        background-color: #f2f2f2;
    }

    .faq-content>td {
        font-size: 16px !important;
        color: #000000;
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

    td>p {
        font-weight: normal;
        margin-bottom: 0px;
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

    .center-filter {
        font-size: 16px;
        font-weight: normal;
        line-height: normal;
        color: #666666;
        cursor: pointer;
    }

    .center-filter:after {
        content: '|';
        font-size: 10px;
        color: #7f7f7f;
        margin: 0 18px;
    }

    .center-filter:last-child:after {
        display: none;
    }

    .center-filter.active {
        color: #3a3a3a;
        font-weight: bold;
    }

    .center-filter.active a {
        border-bottom: 1px solid #000000;
    }

    .member-content-section table {
        width: 100%;
        border-top: 1px solid #444444;
        border-collapse: collapse;
    }

    .member-content-section.notice table {
        width: 100%;
        border-top: 3px solid #333333;
        border-collapse: collapse;
    }

    .member-content-section table th,
    td {
        border-bottom: 1px solid #444444;
    }

    div.member-content-section tr>td {
        height: 56px;
        padding-left: 20px;
    }

    .noti-content {
        display: none;
    }

    .noti-content.active {
        display: table-row;
    }

    .noti-content>td {
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
        font-size: 16px;
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
        font-size: 16px;
        font-weight: normal;
        line-height: normal;
        color: #7f7f7f;
        cursor: pointer;
    }




    .member-faq-filter:last-child:after {
        display: none;
    }

    .member-faq-filter.active {
        color: #000000;
    }

    .container {
        width: 500px;
        margin: 0 auto;
    }

    ul.containter_tabs {
        margin: 0px;
        padding: 0px;
        list-style: none;
    }

    ul.containter_tabs li {
        background: none;
        color: #adadad;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
        width: calc(50% - 2px);
        text-align: center;
        border: 1px solid #e5e5e5;
        border-bottom: 1px solid #333333;
        margin-right: -3px;

    }

    ul.containter_tabs li.current {
        border: 1px solid #333333;
        border-bottom: none;
        color: #222;
        background-color: #ffffff;
    }

    .containter_tab .tab-content {
        display: none;
    }

    .containter_tab .tab-content.current {
        display: inherit;
        margin-top: 15px;
    }

    #input-search-keyword {
        margin: 20px 0;
        padding: 0;
        padding-left : 10px;
        border: 2px solid #3a3a3a;
    }

    .input-group-append {
        margin-left: 0px;
    }

    .faq_footer_area{
        position:relative;
        height: 84px;
        border: solid 1px #f2f2f2;
        margin-bottom : 150px;
    }

    .faq_footer_area .faq_footer_noti{      
        font-size: 18px;
        font-weight: normal;
        line-height: 84px;
        color: #333333;
        margin-left :50px;
    }

    .faq_footer_area .btn-customer{
        width : 111px;
        height: 44px;
        border-radius: 2px;
        border: solid 1px #333333;
        font-size: 16px;
        font-weight: 500;
        line-height: 44px;
        text-align: center;
        color: #3a3a3a;
        background-color: #ffffff;
        position: absolute;
        right: 18px;
        bottom: 19px;
        cursor: pointer;
    }

    .member-no-content{text-align : center;}


    @media (max-width: 1366px) {
        #list-wrapper.center-wrapper {
            width: 100vw;
        }

        .member-content-section.notice table {
            width: 100%;
            border-top: 1px solid #e5e5e5;
            border-collapse: collapse;
        }

        .member-content-section table {
            width: 100%;
            border-top: 1px solid #e5e5e5;
            border-collapse: collapse;
        }

        .member-content-section table th,
        td {
            border-bottom: 1px solid #e5e5e5;
        }

        .noti_title {
            text-align: left;
            cursor: pointer;
            font-size: 12px;
            font-weight: normal;
            color: #3a3a3a;
        }

        #offset-nav-top {
            height: 46px;
            margin-bottom: 0px;
        }

        #member-content-wrapper {
            padding: 0 20px;
        }

        #member-content-wrapper {
            padding: 0 20px;
        }

        div.member-content-section tr>td {
            height: 56px;
            padding-left: 10px;
        }

        div.member-content-section.notice tr>td {
            height: 56px;
            padding-left: 0px;
        }

        .containter_tab .tab-content {
            display: none;
            padding: 15px;
        }

        .member-content-title {
            overflow-x: scroll;
            white-space: nowrap;
        }

        .containter_tab .tab-content.current {
            display: inherit;
            margin-top: 0px;
        }

        #input-search-keyword {
            margin: 20px 0;
            padding: 0;
            padding-left : 10px;
            padding-right : 30px;
            border: 2px solid #3a3a3a;
        }

        .input-group-append {
            margin-left: -30px;
        }

        #btn-search-action>button.center-search {
            margin-left: 0px;
            margin-right: 15px;
        }

        ul.containter_tabs li {
            background-color: #f2f2f2;
            display: inline-block;
            padding: 10px 15px;
            cursor: pointer;
            width: calc(50% - 2px);
            font-size: 14px;
            height: 50px;
            text-align: center;
            border-bottom: 1px solid #333333;
            margin-right: -4px;
            color: #9f9f9f;
        }

        ul.containter_tabs li.current {
            border: 1px solid #333333;
            background-color: #ffffff;
            border-bottom: none;
            color: #222;

        }

        .center-filter {
            font-size: 12px;
            font-weight: normal;
            line-height: normal;
            color: #7f7f7f;
            cursor: pointer;
        }

        .center-filter:after {
            content: '';
            margin: 0 10px;
        }

        .center-filter:last-child:after {
            display: none;
        }

        .center-filter.active {
            color: #333333;
            font-weight: 500;
        }

        .center-filter.active a {
            border-bottom: 1px solid #000000;
        }

        td>p {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: unset;
        }

        .noti-content.active td img {
            width: 100%
        }

        .faq_footer_area{
            position:relative;
            height: 93px;
            border: solid 1px #e0e0e0;
            margin-bottom : 40px;
            padding : 15px 10px;
        }

        .faq_footer_area .faq_footer_noti{      
            font-size: 12px;
            font-weight: normal;
            line-height: normal;
            color: #8a8a8a;
            margin-left :0px;
        }

        .faq_footer_area .btn-customer{
            width : 64px;
            height: 24px;
            border-radius: 2px;
            border: solid 1px #333333;
            font-size: 10px;
            font-weight: normal;
            line-height: 24px;
            text-align: center;
            color: #424242;
            background-color: #ffffff;
            position: initial;
            margin-top : 6px;            
        }
        .add_noti_btn,
        .add_faq_btn {
            margin: 0 14px;
            height: 44px;
            text-align: center;
            line-height: 44px;
            border-radius: 2px;
            border: 1px solid #333333;
            font-size: 14px;
            font-weight: 500;
            margin-top: 24px;
            margin-bottom: 24px;
        }

    }
</style>

<script>
    var add_faq_page = 2;

    function addList_faq(totalPage) {

        var filter_faq = $('#form-faq-filter').val();
        
        
        $.ajax({
            url: '/ajax_front/ajax.faq.php',
            type: 'post',
            data: {
                page_faq: add_faq_page,
                filter_faq: filter_faq,
                
            },

            success: function(response) {
                $('#member_faq_list_table').append(response);
                add_faq_page++;
            }
        });
        if (add_faq_page >= totalPage) {
            $('.add_faq_btn').css('display', 'none');
        }
    }

    var add_noti_page = 2;

    function addList_noti(totalPage) {

        var filter_notice = $('#form-noti-filter').val();
        
        
        $.ajax({
            url: '/ajax_front/ajax.noti.php',
            type: 'post',
            data: {
                page_noti: add_noti_page,
                filter_notice: filter_notice,
                
            },

            success: function(response) {
                $('#member_noti_list_table').append(response);
                add_noti_page++;
            }
        });
        if (add_noti_page >= totalPage) {
            $('.add_noti_btn').css('display', 'none');
        }
    }
    $('ul.containter_tabs li').click(function() {
        var tab_id = $(this).attr('data-tab');

        $('ul.containter_tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    })

    $(".modal-custom-scrollbar").scrollbar({
        height: 300,
        disableBodyScroll: true
    });

    function writeQna() {
        $(".member-qna-list").hide();
        $(".member-qna-write").show();
    }


    function openAnswer_faq(elem) {
        if ($(elem).parent().next(".faq-content").hasClass("active") === true) {
            $(elem).parent().next(".faq-content").removeClass("active");
        } else {
            $(".faq-content").removeClass("active");
            $(elem).parent().next(".faq-content").addClass("active");
        }
    }

    function openAnswer_noti(elem) {
        if ($(elem).parent().next(".noti-content").hasClass("active") === true) {
            $(elem).parent().next(".noti-content").removeClass("active");
        } else {
            $(".noti-content").removeClass("active");
            $(elem).parent().next(".noti-content").addClass("active");
        }
    }


    function setOrderId(id) {
        $("#od_id").val(id);
        $("#modal-order-list-wrapper").modal("hide");
    }

    function applyFilter_faq(filter) {
        $("#form-faq-filter").val(filter);
        $("#form-faq").submit();
    }

    function applyFilter_noti(filter) {
        $("#form-noti-filter").val(filter);
        $("#form-noti").submit();
    }
</script>



<?php
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>