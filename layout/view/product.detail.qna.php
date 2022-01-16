<div class="product-detail-subtitle-wrapper on-big" style="padding-bottom: 20px;">
    <span class="product-detail-subtitle">
        Q&A(<?= number_format($total_count) ?>)
    </span>
    <span class="product-detail-subtitle-action" style="float: right;">
        <select id="product-detail-qna-type" class="product-detail-qna-type-pc">
            <option>전체보기</option>
            <option value="상품">상품</option>
            <option value="사이즈">사이즈</option>
            <option value="배송">배송</option>
            <option value="재입고">재입고</option>
            <option value="기타">기타</option>
        </select>
        <button id="btn-product-detail-qna" type="button" class="btn btn-black on-big" onclick = "openmodal('<?=$member['mb_id']?>' , '&w=')">Q&A 작성</button>
    </span>
</div>

<div class="product-detail-subtitle-wrapper on-small" style="padding-bottom: 20px;">
    <div class="product-detail-subtitle product-detail-qna-title">
        Q&A(<?= number_format($total_count) ?>)
    </div>
    
    <div class="product-detail-subtitle-action" style="float: right;">
        <select id="product-detail-qna-type" class="product-detail-qna-type-mo" style="border: none; line-height:20px;">
            <option>전체보기</option>
            <option value="상품">상품</option>
            <option value="사이즈">사이즈</option>
            <option value="배송">배송</option>
            <option value="재입고">재입고</option>
            <option value="기타">기타</option>
        </select>    
    </div>
    <div>
        <button id="btn-product-detail-qna" type="button" class="btn on-small" onclick = "openmodal('<?=$member['mb_id']?>' , '&w=')">Q&A 작성</button>
        <!-- <button id="btn-product-detail-qna" type="button" class="btn on-small" onclick = "openmodal_moblie('<?=$member['mb_id']?>', '&w=')">Q&A문의</button>         -->
    </div>
</div>

<div class="product-detail-qna">
    <table id="qna_list_table">
        <tr class = "on-big">
            <th class="lt-col-1" style="text-align: center;font-weight: bold;">문의종류</td>
            <td class="lt-col-2 on-big" style="text-align: center;font-weight: bold;">내용</td>
            <td class="lt-col-2 on-small" style="text-align: center;font-weight: bold; text-align:left;">내용</td>
            <td class="lt-col-3" style="text-align: center;font-weight: bold;">이름</td>
            <td class="lt-col-4" style="text-align: center;font-weight: bold;">문의일</td>
            <!-- <td class="lt-col-5" style="text-align: center;font-weight: bold;">답변여부</td> -->
        </tr>
        <?if (!empty($list)) :?>
        <?php foreach ($list as $q) : ?>
            <tr class="product-detail-qna-subject on-big" onclick="openAnswer(this)">
                <th class="lt-col-1" style="text-align: center; font-size: 18px;  font-weight: normal;  line-height: normal; color: #f93f00;"><?= $q['category'] ?></td>
                <td class="lt-col-2" style="font-size: 18px;  font-weight: normal;  line-height: normal; color: #333333; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;width:300px; display:block; cursor: pointer;"><?= $q['subject'] ?></td>
                <td class="lt-col-3" style="text-align: center; font-size: 16px;  font-weight: normal;  line-height: normal; color: #f93f00;"><?= get_star_string($q['name']) ?></td>
                <td class="lt-col-4" style="text-align: center; font-size: 12px; font-weight: 500; color: #565656;"><?= $q['date'] ?></td>
                <!-- <td class="lt-col-5" style="text-align: center; font-size: 18px; color: #f93f00;"><?= $q['answer'] ? "답변완료" : "답변대기중" ?></td> -->
            </tr>
            <tr class="product-detail-qna-content on-big">
                <td></td>
                <td colspan=4>
                    <div style="font-size: 16px;  font-weight: normal;  color: #333333;">Q. <?= trim($q['question']) ?></div>
                    <div style="font-size: 16px;  font-weight: normal;  color: #f93f00;">A. <?= trim($q['answer']) ?></div>
                </td>
            </tr>
            <tr class="product-detail-qna-subject on-small" onclick="mopenAnswer(this)">
                <td>
                    <div class="lt-col-1" style="text-align: left; font-size: 16px;  font-weight: 500;  line-height: normal; color: #333333;"><?= $q['category'] ?></div>
                    <div class="lt-col-2" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width:100px; display:block;font-size: 12px;  font-weight: normal;   color: #3a3a3a;"><?= $q['subject'] ?></div>
                    <div class="lt-col-3" style="text-align: left;font-size: 12px;  font-weight: normal;  color: #959595"><?= get_star_string($q['name']) ?> | <span class="lt-col-4" ><?= $q['date'] ?></span></div>
                    <!-- <div class="lt-col-5" style="text-align: left; font-size: 12px;  font-weight: normal;  color: #f54600;"><?= $q['answer'] ? "답변완료" : "답변대기" ?></div> -->
                </td>
            </tr>
            <tr class="product-detail-qna-content on-small">
                <td colspan=5>
                    <div style="font-size: 12px;  font-weight: normal;  color: #3a3a3a;">문의: <?= trim($q['question']) ?></div>
                    <div style="font-size: 12px;  font-weight: normal;  color: #3a3a3a;">답변: <?= trim($q['answer']) ?></div>
                </td>
            </tr>
            
        <?php endforeach ?>
        <?endif?>
    </table>
    <br>
    <?php if ($total_count > 5) : ?>
        <div class="on-small add_item_btn"><a onclick="addList(<?=$total_page?> , <?=$q['it_id'] ?> )">더보기</a></div> 
    <?endif?>
    <? if ($paging) : ?>
        <div class="page-margin-bottom on-big"><?= $paging ?></div>
    <? endif ?>
</div>
<input type="hidden" name="qna_it_id" id="qna_it_id_hi" value = "<?=$it['it_id']?>" >
<?php if ($total_count <= 0) : ?>
<div class="product-detail-qna no-content on-big">
    Q&A가 없습니다.
</div>

<div class="product-detail-qna no-content on-small" style="font-size: 12px;  font-weight: normal;  line-height: normal;  text-align: center;  color: #424242;">
    등록된 Q&A가 없습니다.
</div>
<?php endif ?>





<script>
    function openAnswer(elem) {
        if ($(elem).next(".product-detail-qna-content.on-big").hasClass("active") === true) {
            $(elem).next(".product-detail-qna-content.on-big").removeClass("active");
        } else {
            $(".product-detail-qna-content.on-big").removeClass("active");
            $(elem).next(".product-detail-qna-content.on-big").addClass("active");
        }
    }
    function mopenAnswer(elem) {
        if ($(elem).next(".product-detail-qna-content.on-small").hasClass("active") === true) {
            $(elem).next(".product-detail-qna-content.on-small").removeClass("active");
        } else {
            $(".product-detail-qna-content.on-small").removeClass("active");
            $(elem).next(".product-detail-qna-content.on-small").addClass("active");
        }
    }

    var add_qna_page = 2;
    function addList(totalPage){
        var type = $('.product-detail-qna-type-mo').val();
        var it_id = $('#qna_it_id_hi').val();
        if(type == '전체보기'){
            type = "";
        }
        $.ajax({
            url:'/ajax_front/ajax.qna.php',
            type:'post',
            data:{page : add_qna_page , it_id : it_id , type : type},
            
            success:function(response){
                $('#qna_list_table tbody').append(response);
                add_qna_page++;
            }
        });

        if(add_qna_page>= totalPage){
            $('.add_item_btn').css('display','none');
        }
    }


    $(".product-detail-qna-type-mo").on("change", function() {
        var select_qna_page = 1;
        var it_id = $('#qna_it_id_hi').val();
        var type = $('.product-detail-qna-type-mo').val();
        if(type == '전체보기'){
            type = "";
        }

        $.ajax({
            url:'/ajax_front/ajax.qna.php',
            type:'post',
            data:{page : select_qna_page , it_id : it_id , type : type },
            
            success:function(response){
                $('#qna_list_table tbody').empty().html(response);
                select_qna_page++;
            }
        });

        if(select_qna_page>= totalPage){
            $('.add_item_btn').css('display','none');
        }
    });

    $(".product-detail-qna-type-pc").on("change", function() {
        var select_qna_page_pc = 1;
        var it_id = $('#qna_it_id_hi').val();
        var type = $('.product-detail-qna-type-pc').val();
        if(type == '전체보기'){
            type = "";
        }

        $.ajax({
            url:'/ajax_front/ajax.qna_pc.php',
            type:'post',
            data:{page : select_qna_page_pc , it_id : it_id , type : type  },
            
            success:function(response){                
                $('#qna_list_table tbody').empty().html(response);
            }
        });

        // if(add_qna_page>= totalPage){
        //     $('.add_item_btn').css('display','none');
        // }
    });




   

</script>