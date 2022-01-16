<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가  itemqaform.skin.php

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
// add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
function MobileCheck() {
    global $HTTP_USER_AGENT;
    $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

    $checkCount = 0;
    for($i=0; $i<sizeof($MobileArray); $i++){
        if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
    }
    return ($checkCount >= 1) ? "Mobile" : "Computer";
}
?>

<!-- 상품문의 쓰기 시작 { -->
<style>
    .headText {float: left; width : 10%; font-weight: bold;}
    .qaContent {font-weight : bold;}
    .line-clear{clear : both;}
    .qatextArea{width :876px; height : 170px;}
    .qa_submit_btn{float: right;    background-color: #333333;    font-size: 122px;    color: #ffffff;    width: 90px;    height: 50px;}
    @media (max-width: 1366px) {
        .qa_submit_btn{
            background-color: #333333 !important;
        }
    }


</style>

<div id="sit_qa_write" class="new_win">
    <!-- <h1 id="win_title">상품문의 쓰기</h1> -->

    <form name="fitemqa" method="post" action="<?=G5_SHOP_URL ?>/itemqaformupdate.php" onsubmit="return fitemqa_submit(this);" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="it_id" value="<?php echo $it_id; ?>">
    <input type="hidden" name="iq_id" value="<?php echo $iq_id; ?>">
    <input type="hidden" name="iq_email" id="iq_email" value="<?php echo get_text($qa['iq_email']); ?>" class="frm_input full_input" size="30" placeholder="이메일">
    <input type="hidden" name="iq_hp" id="iq_hp" value="<?php echo get_text($qa['iq_hp']); ?>" class="frm_input full_input" size="20" placeholder="휴대전화">

    <?php 
        if(MobileCheck() == "Mobile"){ ?>
            <div class="form_01 new_win_con on-small">
            <div class="line-clear"><p class="headText" style="font-size: 12px; color:#424242; font-weight: 500;">문의상품</p><p id="qna_it_name" style="font-size: 12px; color:#424242"><?php echo $it['it_name']; ?></p></div>
            <div class="line-clear">
                <div class="headText qna-type" style="font-size: 12px; color:#424242; font-weight: 500;" >문의유형</div>
                <div>
                    <select id="product-detail-qna-type" name = "iq_category">
                        <option value="상품">상품</option>
                        <option value="사이즈">사이즈</option>
                        <option value="배송">배송</option>
                        <option value="재입고">재입고</option>
                        <option value="기타">기타</option>
                    </select>
                </div>
            </div>
            <div class="line-clear qna_title">
                <p class="headText on-big" style="line-height:44px; font-size: 14px; color:#606060;">제목</p>
                <input type="text" style="outline:0;" name="iq_subject" value="<?php echo get_text($qa['iq_subject']); ?>" id="iq_subject" required class="required frm_input" maxlength="250" placeholder="제목">
            </div>
            <div class="line-clear">
                <p class="headText on-big" style="font-size: 14px; color:#606060;">문의사항</p>
                <textarea style="border: 1px solid #dedede;outline:0;" class="qatextArea" name = "iq_question" placeholder="  최소 10자 이상 작성해주세요.&#13;&#10;  결제 및 환불 관련 문의는&#13;&#10;  [마이페이지 > 고객센터 > 1:1 문의]를 &#13;&#10;  이용하시기 바랍니다."></textarea>
            </div>
            <br>
            <div class="win_btn">
                <input class="" data-dismiss="modal" value="취소">
                <input class="qa_submit_btn" type="submit" value="확인" class="btn_submit">
            </div>
            </div>
            
        <?php 
        } else { ?>
            <div class="form_01 new_win_con on-big" style="margin-left: 80px; margin-top: 10px;">
            <div class="line-clear"><p class="headText" style="font-size: 14px; color:#606060; font-weight: 500;">구매상품</p><p id="qna_it_name" style="font-size: 14px; color:#424242; margin-left: 123px; font-weight: normal"><?php echo $it['it_name']; ?></p></div>
            <div class="line-clear">
                <div class="headText qna-type" style="font-size: 14px; color:#606060; font-weight: 500;" >문의유형</div>
                <div style="margin-left: 123px;">
                    <select style="width: 360px;" id="product-detail-qna-type" name = "iq_category">
                        <option value="상품">상품</option>
                        <option value="사이즈">사이즈</option>
                        <option value="배송">배송</option>
                        <option value="재입고">재입고</option>
                        <option value="기타">기타</option>
                    </select>
                </div>
            </div>
            <div class="line-clear qna_title">
                <p class="headText on-big" style="line-height:44px; font-size: 14px; color:#606060; font-weight: 500;">제목</p>
                <input type="text" style="width: 580px; margin-left: 45px; outline:0;" name="iq_subject" value="<?php echo get_text($qa['iq_subject']); ?>" id="iq_subject" required class="required frm_input" maxlength="250" placeholder="제목">
            </div>
            <div class="line-clear">
                <p class="headText on-big" style="font-size: 14px; color:#606060; font-weight: 500;">문의사항</p>
                <textarea style="width: 580px; height: 200px; border: 1px solid #dedede; outline:0; margin-left: 45px;" class="qatextArea" name = "iq_question" placeholder="  최소 10자 이상 작성해주세요. 결제 및 환불 관련 문의는 &#13;&#10;  [마이페이지 > 고객센터 > 1:1 문의]를 이용하시기 바랍니다."></textarea>
            </div>

            <div class="win_btn" style="margin-top: 35px;">
                <input class="" data-dismiss="modal" value="취소">
                <input class="qa_submit_btn" type="submit" value="확인" class="btn_submit">
            </div>
            </div>
        <?php 
        }    
    ?>
    </form>
</div>





<script type="text/javascript">

function fitemqa_submit(f)
{
    $("#modal_product-detail-qna").modal('hide');
    var result = confirm('Q&A 작성을 완료하시겠습니까?'); 
    if(result) {
        <?php echo $editor_js; ?>
        return true;
    } else {
        return false;
    }


    
}
</script>
<!-- } 상품문의 쓰기 끝 -->
