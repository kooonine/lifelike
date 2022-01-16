<?php
ob_start();
$g5_title = "1:1 문의";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .review-thumbnail-answer {
        width: 90px;
        height: 90px;
    }

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
        background-color: #e0e0e0;
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
        border: solid 1px #cecece !important;
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
        position : relative;
        font-size: 12px;
        line-height: 20px;
        margin : 20px;
        border : 1px solid #e0e0e0;
    }
    .popup-order-item-wrapper>div {
        font-size : 16px;
        color : #3a3a3a;
        font-weight: 500;
    }
    .popup-order-item-wrapper .order_no{
        height : 40px;
        line-height : 40px;
    }
    .popup-order-item-wrapper .order_item_name{margin-top : 10px;}
    .popup-order-item-wrapper .order_date{margin :10px 0 ;}
    .popup-order-item-wrapper .order_price{margin-bottom : 10px;}
    .popup-order-item-wrapper .order_select{cursor: pointer; position: absolute;     bottom: 20px;    right: 20px;    width: 111px;    height: 44px;    border: 1px solid #333333;    line-height: 44px;    text-align: center;}

    #modal-order-list-wrapper .modal-dialog{width : 500px; height : 460px; margin : 200px auto; padding : 0;}
    #modal-popup-orderlist-wrapper {width : 500px; margin : 0; padding : 0; height : 460px;}

    #modal-popup-orderlist-wrapper .modal_header {
        height: 50px;
        line-height: 50px;
        text-align: center;
        font-size: 18px;
        font-weight: 500;
        color: #090909;
        position: relative;
        border-bottom: 1px solid #e0e0e0;
    }

    #modal-popup-orderlist-wrapper .modal_header img {
        position: absolute;
        top: 50%;
        right: 7px;
        transform: translate(-50%, -50%);
    }

    @media (max-width: 1366px) {
        #member-content-wrapper {
            padding: 0 20px;
        }

        .btn.btn-outline-secondary.btn-black-2 {
            height: 32px;
        }

        
        .popup-order-item-wrapper {
            position : relative;
            font-size: 12px;
            line-height: 20px;
            margin : 20px;
            border : 0px;
            
        }
        .popup-order-item-wrapper>div {
            font-size : 14px;
            color : #3a3a3a;
            font-weight: normal;
        }
        .popup-order-item-wrapper .order_no{
            height : 43px;
            line-height : 43px;
            border : 1px solid #e0e0e0;

        }
        .popup-order-item-wrapper .order_item_name{margin-top : 10px; font-size : 16px;}
        .popup-order-item-wrapper .order_date{margin :10px 0 ;}
        .popup-order-item-wrapper .order_price{margin-bottom : 10px;}
        .popup-order-item-wrapper .order_select{cursor: pointer; position: absolute;     bottom: 0px;    right: 0px;    width: 64px;    height: 36px;  border-radius: 20px;   border: 1px solid #333333;    line-height: 36px;    text-align: center;}

        #modal-order-list-wrapper .modal-dialog{width : 100%; height : 476px; margin : 0; padding : 0;}
        #modal-popup-orderlist-wrapper {width : 100%; margin : 0; height : 476px; border-radius: 20px 20px 0 0;     position: fixed;    bottom: 0;    padding: 0 !important;}
        #modal-popup-orderlist-wrapper .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }
        .popup-order-item-wrapper:last-child{
            margin-bottom : 100px;
        }

        #modal-popup-orderlist-wrapper .modal_header img {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translate(-50%, -50%);
        }
        #od_id{line-height : 32px !important}
    }
    .mo_modify_1_btn {
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

    .mo_delete_1_btn {
        width: 64px;
        height: 36px;
        border-radius: 20px;
        line-height: 36px;
        border: 1px solid #333333;
        text-align: center;
        font-size: 14px;
        color: #3a3a3a;
    }

    .add_cus_btn {
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
</style>
<div id="member-content-wrapper">
    <div class="member-content-title on-big">1:1 문의</div>
    <div class="member-content-section" style="margin-bottom: 90px;">
        <div class="on-big" style="font-size: 16px; color: #000000; padding-top: 16px;">
            <div class="font16to14" style="font-size: 18px; font-weight: 500;">혹시 확인해보셨나요?<a href="/member/member.center.php"><span style="color: #00bbb4; margin-left: 11px;">FAQ 바로가기 ></span></a></div>
            <div class="font16to14" style="font-size: 18px; font-weight: 500;">상담 시간</div>
            <div style="font-size: 18px; font-weight: 500;">10:00 ~ 17:00 <span style="font-size: 12px; font-weight: 500;">(토/일/공휴일 휴무)</span></div>
            <div style="font-size: 18px; font-weight: 500;">11:30 ~ 13:00 <span style="font-size: 12px; font-weight: 500;">(점심시간)</span></div>
        </div>
        <div class="on-big" class="font14to12" style="color: #8d8d8d; padding-top: 8px; font-size: 14px;">
            1:1 문의 내역은 전자상거래법에 따라 3년간 보관 및 조회됩니다.<br>
            문의량에 따라 답변 시간이 지연될 수 있습니다. 빠른 답변을 위해 노력하겠습니다.
        </div>

        <div class="on-small" style="font-size: 16px; color: #000000; padding-top: 16px;">
            <div class="font16to14" style="font-size: 16px; font-weight: 500;">상담 시간 <a href="/member/member.center.php" style="float: right;"><span style="color: #9f9f9f; font-size: 12px;">FAQ 바로가기 ></span></a></div>
            <div style="font-size: 14px; font-weight: 500; padding-top: 10px;">10:00 ~ 17:00 <span style="font-size: 14px; font-weight: 500;">(토/일/공휴일 휴무)</span></div>
            <div style="font-size: 14px; font-weight: 500;">11:30 ~ 13:00 <span style="font-size: 14px; font-weight: 500;">(점심시간)</span></div>
        </div>
        <div class="on-small" class="font14to12" style="color: #959595; padding-top: 8px; font-size: 12px;">
            1:1 문의 내역은 전자상거래법에 따라 3년간 보관 및 조회됩니다.<br>
            문의량에 따라 답변 시간이 지연될 수 있습니다. 빠른 답변을 위해 노력하겠습니다.
        </div>

    </div>



    <div class="line8"></div>
    <section class="member-qna-list">
        <div class="member-content-title on-big" style="color: #333333; font-size: 22px; font-weight: bold;">1:1 문의
        <span>
            <button type="button" class="btn btn-black btn-list-black" onclick="writeQna()" style="width: 130px; height: 44px; margin-top: 0;">1:1 문의하기</button>
        </span>
        </div>
        
        <div class="member-content-title on-small" style="color: #333333; font-size: 18px; font-weight: bold; padding-bottom:0"> 
            <!-- 문의 내역 -->
            <span>
                <button type="button" class="btn btn-black btn-list-black" style="height: 30px; margin-top :0; line-height:0;;"  onclick="writeQna()">1:1 문의하기</button>
            </span>
            <span>
                <!-- <button type="button" class="btn btn-black btn-list-black" style="width: 100px; height: 24px; font-size: 12px;" onclick="writeQna()">1:1 문의하기</button> -->
            </span>
        </div>
        <!-- PC -->
        <div class="member-content-section" style="margin-bottom: 40px;">
            <? if ($db_qna->num_rows > 0) : ?>
                <table id="member_cus_list_table">
                    <colgroup class="on-big">
                        <!-- <col style="width: 140px">
                        <col>
                        <col style="width: 180px">
                        <col style="width: 70px">
                        <col style="width: 70px"> -->

                        <col style="width: 180px">
                        <col style="width: 600px">
                        <col style="width: 210px">
                        <col style="width: 85px">
                        <col style="width: 85px">
                    </colgroup>

                    <colgroup class="on-small">
                        <col style="width: 280px">
                        <col style="width: 52px">
                    </colgroup>
<!-- 
                    <tr class="on-big">
                        <th>문의유형</th>
                        <th>제목</th>
                        <th>문의일</th>
                        <th colspan=2>답변여부</th>
                    </tr> -->
                    <tr class="on-big" style="border-top : 3px solid #333333;">
                        <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">문의유형</th>
                        <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">제목</th>
                        <th style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">문의일</th>
                        <th colspan=2 style="font-size: 16px; font-weight: 500; text-align: center; color: #828282;">답변여부</th>
                    </tr>

                    <!-- <colgroup class="on-small">
                        <col>
                        <col style="width: 53px">
                        <col style="width: 57px">
                    </colgroup> -->


                    <!-- <tr class="on-small">
                        <th>문의내용</th>
                        <th colspan=2 style="text-align : center;"> 답변여부</th>
                    </tr> -->
                    <? for ($oi = 0; $qna = sql_fetch_array($db_qna); $oi++) : ?>
                        <tr height="143px" class="on-big" style="border-bottom : 1px solid #333333;">
                            <td class="on-big" style="font-size: 16px; font-weight: normal; border:0px; color: #f93f00;"><?= $qna['qa_category'] ?></td>
                            <td style="text-align:left; font-size: 18px; font-weight: normal; border:0px; color: #606060;" onclick="openAnswer_cus_pc(this)">
                                <?= $qna['qa_subject'] ?>
                                <!-- <p class="on-small" style="font-size : 10px; margin-bottom:0px;"><?= date("Y.m.d", strtotime($qna['qa_datetime'])) ?></p> -->
                            </td>
                            <td class="on-big" style="font-size: 14px; font-weight: normal; color: #7f7f7f;border:0px;"><?= date("Y.m.d", strtotime($qna['qa_datetime'])) ?></td>
                            <td colspan=2 style="font-size: 18px; font-weight: normal; color: #f54600;border:0px;">
                            <? if (!empty($qna['qa_answer'])) : ?>
                                답변완료
                                <br>
                            <? else : ?>
                                <!-- <button type="button" class="btn btn-black btn-list" data-id="<? $qna['iq_id'] ?>" data-type="update">수정</button> -->
                                <!-- <button type="button" class="btn btn-black btn-list" style="width: 111px; height: 44px; border-radius: 2px; background-color: #333333; margin-top: -1px; color:#ffffff; font-size: 16px;" data-id="<? $qna['qa_id'] ?>" data-type="modify">수정</button> -->
                                <br>
                            <? endif ?>
                                <button type="button" class="btn btn-black btn-list" style="width: 111px; height: 44px; border-radius: 2px; background-color: #ffffff; margin-top: 15px; color:#333333; font-size: 16px;" data-id="<? $qna['qa_id'] ?>" data-type="delete" onclick="cusDelete('<?= $qna['qa_id'] ?>')">삭제</button>
                            </td>
                            <!-- <td>
                                <button type="button" class="btn btn-black btn-list" data-id="<? $qna['qa_id'] ?>" data-type="delete">삭제</button>
                            </td> -->
                        </tr>

                        <tr height="116px" class="on-small" style="border-bottom : 1px solid #e0e0e0;">
                            <td style="font-size: 14px; font-weight: normal; border:0px" onclick="openAnswer_cus(this)">
                                <!-- 화살표 ㅋㅋ -->

                                <!-- ///  -->
                                <div style="text-align: left; font-size: 16px;  font-weight: 500;  line-height: normal; color: #333333;"><?= $qna['qa_category'] ?></div>
                                <div style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width:100px; display:block;font-size: 12px;  font-weight: normal; color: #3a3a3a;"><?= $qna['qa_subject'] ?>
                                    <? if (!empty($qna['qa_answer'])) : ?>
                                        <img src="/img/mobile/gnb_bg2.png">
                                    <? endif ?>
                                </div>
                                <div style="text-align: left;font-size: 12px;  font-weight: normal;  color: #959595"> <span class="lt-col-4"><?= date("Y.m.d", strtotime($qna['qa_datetime'])) ?></span></div>
                                <div style="text-align: left; font-size: 12px;  font-weight: normal;  color: #f93f00;"><?= $qna['qa_answer'] ? "답변완료" : "답변대기" ?></div>

                            </td>
                            <td style="border:0px;font-size: 14px; font-weight: normal; color: #f54600;border:0px;">
                            <? if (!empty($qna['qa_answer'])) : ?>
                                <div style="margin-left:5px; margin-bottom:-20px;">답변완료</div>
                                <br>
                            <? else : ?>
                                <br>
                            <? endif ?>
                                <div class="mo_delete_1_btn" style="margin-top:7px" onclick="cusDelete('<?= $qna['qa_id'] ?>')" >삭제</div>


                                <!-- <div class="mo_modify_btn" onclick="qaUpdate('<?= $qna['iq_id'] ?>')">수정</div> -->
                                <!-- <div class="mo_delete_btn" style="margin-top:7px" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</div> -->
                                <!-- <div class="mo_delete_btn" style="margin-bottom:-35px" >삭제</div> -->
                                <!-- 삭제버튼 ㅋㅋㅋㅋㅋㅋㅋㅋㅋ
                                     -->
                                <!-- <button type="button" class="btn btn-black btn-list" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</button> -->
                            </td>
                        </tr>


             
                        <!-- 이건 답변 같음  -->
                        <tr class="qna-content">
                            <td class="on-big"></td>
                            <td colspan=4 id="qna-content-answer">
                                <div class="qna-answer">
                                <span style ="font-size: 12px; color:#3a3a3a font-weight: normal;";> 문의유형 : <?= $qna['qa_category'] ?> </span>
                                </div>
                                <div class="qna-answer">
                                    <span style ="font-size: 12px; color:#3a3a3a font-weight: normal;";> 제목 : <?= $qna['qa_subject'] ?> </span>
                                </div>
                                <div class="qna-answer">
                                    <span style ="font-size: 12px; color:#3a3a3a font-weight: normal;";> 내용 : <?= $qna['qa_content'] ?> </span>
                                </div>
   
                                <? if (!empty($qna['qa_answer'])) : ?>
                                <div class="qna-answer" style="margin-bottom: 15px;">
                                    <span style ="font-size: 12px; color:#3a3a3a font-weight: normal;";> 답변 : <?= $qna['qa_answer'] ?> </span>
                                </div>
                                <? endif ?>

                                <? if ($qna['qa_file1']) : ?>
                                        <div><img src="/data/qa/<?= $qna['qa_file1'] ?>" class="review-thumbnail-answer" id="imgQAFile1"></div>
                                        <br>
                                <? endif ?>
                                <? if ($qna['qa_file2']) : ?>
                                        <div><img src="/data/qa/<?= $qna['qa_file2'] ?>" class="review-thumbnail-answer" id="imgQAFile2"></div>
                                <? endif ?>
                            </td>
                        </tr>
                      
                    <? endfor ?>
                </table>
            <? else : ?>
                <div class="member-no-content">
                    문의 내역이 없습니다
                </div>
            <? endif ?>
        </div>
        
        <? if ($paging) : ?>
            <div style="margin-bottom: 170px;" class="on-big"><?= $paging ?></div>
        <? endif ?>>
        <?php if ($total_count > 5) : ?>
            <div class="on-small add_cus_btn"><a onclick="addListCus(<?= $total_page ?>)">더보기</a></div>
        <? endif ?>
    </section>
    <section class="member-qna-write" style="display: none;">
        <div class="member-content-title left">
            문의 내역
        </div>
        <div class="member-content-section">
            <form name="fwrite" id="fwrite" action="/bbs/qawrite_update.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="qa_name" value="<?= $member['mb_name'] ?>">
                <input type="hidden" name="qa_email" value="<?= $member['mb_email'] ?>">
                <table id="member-qna-write">
                    <tr>
                        <th>이름</th>
                        <td><?= $member['mb_name'] ?></td>
                    </tr>
                    <tr>
                        <th>아이디</th>
                        <td><?= $member['mb_id'] ?>
                            <!-- <div class="custom-control custom-checkbox custom-control-inline" id="qa-check-email">
                                <input type="checkbox" class="custom-control-input" id="qa-email" name="qa_email_recv" value="1" checked>
                                <label class="custom-control-label" for="qa-email" style="font-size: 14px; color: #7f7f7f;">답변 내용을 메일로 받기</label>
                            </div> -->
                        </td>
                    </tr>
                    <tr>
                        <th class="baseline">구매상품문의</th>
                        <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-1" name="qa_category" value="배송" required>
                                <label class="custom-control-label" for="qa-category-1">배송</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-2" name="qa_category" value="취소" required>
                                <label class="custom-control-label" for="qa-category-2">취소</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-3" name="qa_category" value="반품" required>
                                <label class="custom-control-label" for="qa-category-3">반품</label>
                            </div>
                            <!-- <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-4" name="qa_category" value="교환" required>
                                <label class="custom-control-label" for="qa-category-4">교환</label>
                            </div> -->
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-5" name="qa_category" value="환불" required>
                                <label class="custom-control-label" for="qa-category-5">환불</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-6" name="qa_category" value="사은품" required>
                                <label class="custom-control-label" for="qa-category-6">사은품</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-7" name="qa_category" value="증빙서류" required>
                                <label class="custom-control-label" for="qa-category-7">증빙서류</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="baseline">개인정보문의</th>
                        <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-8" name="qa_category" value="회원정보" required>
                                <label class="custom-control-label" for="qa-category-8">회원정보</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-9" name="qa_category" value="쿠폰/포인트" required>
                                <label class="custom-control-label" for="qa-category-9">쿠폰/포인트</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-10" name="qa_category" value="이벤트 당첨" required>
                                <label class="custom-control-label" for="qa-category-10">이벤트 당첨</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>리스문의</th>
                        <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-11" name="qa_category" value="세탁서비스" required>
                                <label class="custom-control-label" for="qa-category-11">세탁서비스</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>기타문의</th>
                        <td>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-12" name="qa_category" value="시스템오류" required>
                                <label class="custom-control-label" for="qa-category-12">시스템오류</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="qa-category-13" name="qa_category" value="기타" required>
                                <label class="custom-control-label" for="qa-category-13">기타</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>주문번호</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control form-input" id="od_id" name="od_id" aria-describedby="btn-od-id" value="<?= $od_id ?>">
                                <div class="input-group-append" id="btn-od-id">
                                    <button class="btn btn-outline-secondary btn-black-2" type="button" style="margin-top: 0;" data-toggle="modal" data-target="#modal-order-list-wrapper">조회</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="font-size: 14px; color: #fa3f00;">주문번호 기입 시, 더욱 정확한 답변 및 처리가 가능합니다.</td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <input type="text" name="qa_subject" style="width: 100%; font-size: 14px; border: unset; font-weight: normal; border:1px solid #e0e0e0; padding: 16px;" placeholder="(필수) 제목을 입력하세요.">
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <textarea id="qa-content" name="qa_content" style="width: 100%; height: 200px; font-size: 14px; border: unset; font-weight: normal; border:1px solid #e0e0e0; padding: 16px;" placeholder="(필수) 내용을 입력하세요. 한 번 등록된 문의 내용은 수정할 수 없습니다." onkeyup="$('#content-length').text($(this).val().length);" onblur="$('#content-length').text($(this).val().length);"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" style="font-size: 12px; font-weight: normal; width: 90px; margin: unset; vertical-align: baseline;" class="btn btn-review" id="btn-upload-photo">사진 첨부</button>
                        </td>
                        <td>
                            <div class="on-big" style="display: inline-block; font-size: 14px; color: #fa3f00; vertical-align: middle; margin-left: -60px;">상품 불량 및 오배송의 경우 해당 사진을 첨부 부탁드립니다.<br>최대 2장, 10MB 이내로 첨부할 수 있습니다.</div>
                            <button type="submit" style="font-size: 12px; font-weight: normal; width: 90px; margin: unset; vertical-align: baseline; float: right;" class="btn btn-review btn-black">작성 완료</button>
                        </td>
                    </tr>
                </table>
                <div id="content-length"></div>
                <div id="modal-update-review-photo" style="text-align: left; padding: 16px 0;">
                    <img src="../img/theme_img.jpg" class="review-thumbnail" id="imgimgFile1" onclick="$('#imgFile1').click()">
                    <img src="../img/theme_img.jpg" class="review-thumbnail" id="imgimgFile2" onclick="$('#imgFile2').click()">
                    <input class="img-thumbnail-file" type="file" id="imgFile1" name="bf_file[]" style="visibility: hidden;" delBtnID="btnDelimgFile1" imgID="imgimgFile1" style="width:100px" accept=".jpg, .png">
                    <input class="img-thumbnail-file" type="file" id="imgFile2" name="bf_file[]" style="visibility: hidden;" delBtnID="btnDelimgFile2" imgID="imgimgFile2" style="width:100px" accept=".jpg, .png">
                </div>

                <div class="on-small" style="display: inline-block; font-size: 14px; color: #fa3f00; vertical-align: middle;">상품 불량 및 오배송의 경우 해당 사진을 첨부 부탁드립니다.<br>최대 2장, 10MB 이내로 첨부할 수 있습니다.</div>

            </form>
        </div>
    </section>

    <div style="margin-bottom: 120px; display: inline-block;"></div>
</div>

<div class="modal fade" id="modal-order-list-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-popup-orderlist-wrapper" class="modal-content">
            <div class="modal_header">주문번호 조회
                <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>    
        
            <div id="modal-popup-orderlist" class="modal-custom-scrollbar scrollbar-inner">
                <? while (false != ($order = sql_fetch_array($db_order))) : ?>
                    <div class="popup-order-item-wrapper">
                        <div class="order_no" style="font-weight: 600; border-bottom : 1px solid #e0e0e0;" onclick=setOrderId(<?= $order['od_id'] ?>)>주문번호 : <?= $order['od_id'] ?></div>
                        <div class="order_item_name"><?= $order['it_name'] ?></div>
                        <div class="order_date">주문일 : <?= $order['od_time'] ?></div>
                        <div class="order_price">결제금액 : <?= number_format($order['od_cart_price']) ?>원</div>
                        <div class="order_select" onclick=setOrderId(<?= $order['od_id'] ?>)>선택</div>
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
    function openAnswer_cus_pc(elem) { 
        if ($(elem).parent().next().next(".qna-content").hasClass("active") === true) {
            $(elem).parent().next().next(".qna-content").removeClass("active");
        } else {
            $(".qna-content").removeClass("active");
            $(elem).parent().next().next(".qna-content").addClass("active");
        }
    }

    function openAnswer_cus(elem) {
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

    $(document).ready(function() {
        if ($("#od_id").val()) {
            return writeQna();
        }
    });
    function cusDelete(qa_id) {
        var result = confirm('1:1 문의를 삭제 하시겠습니까?');
        if (result) {
            $.get('ajax.customer.list.php?w=d&qa_id=' + qa_id, function(data) {
                const $data = data;
                if ($data) {
                    location.reload();
                }
            });
        } else {

        }
    }
    var add_cus_page = 2;

    function addListCus(totalPage) {

    $.ajax({
        url: '/ajax_front/ajax.cus.php',
        type: 'post',
        data: {
            page: add_cus_page,
        },
        success: function(response) {
            $('#member_cus_list_table').append(response);
            add_cus_page++;
        }
    });
    if (add_cus_page >= totalPage) {
        $('.add_cus_btn').css('display', 'none');
    }
}
</script>
<?php
include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>