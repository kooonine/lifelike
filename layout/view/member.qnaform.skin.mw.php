<?php
//   itemqaform.skin.php 모바일
ob_start();
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
$g5_title = "상품Q&A";
// include_once G5_LAYOUT_PATH . "/nav.member.php";
?>

<!-- 상품문의 쓰기 시작 { -->
<style>
    .qa_wapper{padding : 20px;}
    .mheadText {float: left; width : 19%; font-weight: bold;}
    .mqaContent {font-weight : bold;}
    .mline-clear{clear : both;}
    .mqatextArea{width :100%; height : 200px;}
    .mqa_submit_btn{float: right;    background-color: #000000;    font-size: 14px;    color: #ffffff;    width: 90px;    height: 50px;}
    #product-detail-qna-type{ width : 96px; height : 32px;}
</style>

<?php 

$iq_id = preg_replace('/[^0-9]/', '', trim($_REQUEST['iq_id']));

?>

<div class="qa_wapper">
    <div id="sit_qa_write" class="new_win">
        <!-- <h1 id="win_title">상품문의 쓰기</h1> -->
       
        <form name="fitemqa" method="post" action="<?=G5_SHOP_URL ?>/itemqaformupdate.php" onsubmit="return fitemqa_submit(this);" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w; ?>">
        <input type="hidden" name="it_id" value="<?php echo $it_id; ?>">
        <input type="hidden" name="iq_id" value="<?php echo $qa['iq_id']; ?>">
        <input type="hidden" name="iq_email" id="iq_email" value="<?php echo get_text($member['mb_email']); ?>" class="frm_input full_input" size="30" placeholder="이메일">
        <input type="hidden" name="iq_hp" id="iq_hp" value="<?php echo get_text($member['mb_hp']); ?>" class="frm_input full_input" size="20" placeholder="휴대전화">

        <div class="form_01 new_win_con">

            <div class="mline-clear"><p class="mheadText">아이디</p><p class="mqaContent"><?php echo $member['mb_id']; ?></p></div>
            <div class="mline-clear"><p class="mheadText">문의상품</p><p><?php echo $row['it_name'] ? $row['it_name']: $qa['it_name'] ; ?></p></div>
            <div class="mline-clear">
                <p class="mheadText"  style="line-height:32px;">문의유형</p>
                <select id="product-detail-qna-type" name = "iq_category">
                    <option value="상품">상품</option>
                    <option value="사이즈">사이즈</option>
                    <option value="배송">배송</option>
                    <option value="재입고">재입고</option>
                    <option value="기타">기타</option>
                </select>
            </div>
            <div class="mline-clear">
                <p class="mheadText" style="line-height:30px;">제목</p>
                <input type="text" name="iq_subject" value="<?php echo get_text($qa['iq_subject']); ?>" id="iq_subject" required class="required frm_input" maxlength="250" placeholder="제목" style="">
            </div>
            <div class="mline-clear">
                <textarea class="mqatextArea" name = "iq_question" value="<?php echo $qa['iq_question']; ?>" placeholder="최소 10자 이상 작성해주세요. 결제 및 환불 관련 문의는 마이페이지 > 고객센터 > 1:1 문의를 이용 바랍니다."><?php if($qa['iq_question'] != '') : ?><?= $qa['iq_question'] ?><? endif ?></textarea>
            </div>

            <div class="win_btn" style="height : 50px;">
                <input class="mqa_submit_btn" type="submit" value="작성완료" class="btn_submit">
            </div>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
// $(function(){
//     const asdf = $("textarea[name=iq_question]").val();
    
// });

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


<?php
// include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>