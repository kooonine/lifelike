<?php

include_once('./_common.php');



$cp_code = $_GET['cp_code'];
$cp_name = $_GET['cp_name'];

if(empty($cp_code)){
    
    $cp_code = "ACE";
    $cp_name = "에이스침대";
}




?>


<div>
    <h2><img class="b2b_logo" src = "https://lifelikecdn.co.kr/common/sofraum_logo.png"></h2>
    <h4>소프라움 특판 사이트에 오신것을 환영합니다.</h4>
    <p>
    소프라움(SOFRAUM)은 부드러운 공간(Soft + Raum)이라는 의미의 구스 침구 전문 브랜드입니다. <br>
    우모와 화학섬유 충전재, 봉제기술 등의 깊은 이해와 노하우를 바탕으로 높은 품질의 침구류를 생산하고 있습니다.<br>
    국내 최고 다운 가공 업체인 태평양물산㈜의 프리미엄 다운 전문 브랜드 ‘프라우덴’에서 생산하는 최고 품질의 구스다운만을 사용하여 <br>
    구스 상품 제조부터 유통, 판매, 세탁 관리, 폐기에 이르기까지 전과정을 직접 책임지는 전세계 유일의 침구 브랜드입니다. 
    </p>
</div>
<div class="front_content">
    <form id = "st_join_form" name = "st_join_form" action="./ajax_st_join.php" method="post">
        <input type="hidden" name = "cp_name" id="cp_name" value = "<?=$cp_name?>">
        <input type="hidden" name = "cp_code" id="cp_code" value = "<?=$cp_code?>">
        <table class="spo_tbl">
            <tr>
                <th>매장명</th>
                <td>
                    <input type="text" name = "st_name" id = "st_name" size = 35 required>
                </td>
            </tr>
            <tr>
                <th>비밀번호</th>
                <td>
                    <div>
                        <div>
                            <span class="passTxt">비밀번호</span>
                            <input type="password" name = "password" id = "password" size = 20 required>
                        </div>
                        <div>
                            <span class="passTxt">비밀번호확인</span>
                            <input type="password" name = "password_re" id = "password_re" size = 20 required>
                        </div>
                        
                    </div>
                </td>
            </tr>
            <tr>
                <th>사업자번호</th>
                <td>
                    <input class="input_w_n"  type="number" maxlength='3' oninput="maxLengthCheck(this)" name ="st_num1" id ="st_num1" required> - <input class="input_w_s" type="number" maxlength='2' oninput="maxLengthCheck(this)" name ="st_num2" id ="st_num2" required> - <input class="input_w_n" type="number" maxlength='5' oninput="maxLengthCheck(this)" name ="st_num3" id ="st_num3" required>
                    <input type="hidden" name ="st_number" id ="st_number" value="">
                </td>
            </tr>
            <tr>
                <th>주소</th>
                <td>
                    <div><input type="text"  class="input_w_n st_zip_addr" name ="st_zip" readonly required> <button id="btn_zip_addr" class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 14px;" onclick="win_zip('st_join_form','st_zip' , 'st_addr1', 'st_addr2', 'st_addr3','st_addr_jibun');">우편번호</button></div>
                    <div>
                        <input type="text" class="st_zip_addr" name ="st_addr1" id ="st_addr1" size = 40 readonly required> <input type="text" name ="st_addr2" id ="st_addr2"  size=35 required>
                        <input type="hidden" name ="st_addr3">
                        <input type="hidden" name ="st_addr_jibun">
                    </div>
                    
                </td>
            </tr>
            <tr>
                <th>점주명</th>
                <td>
                    <input type="text" name ="st_owner" id ="st_owner" value="" required>
                </td>
            </tr>
            <tr>
                <th>연락처</th>
                <td>
                
                    <input class="input_w_n" type="number" maxlength='4' oninput="maxLengthCheck(this)"  id = "st_tel1" name = "st_tel1" required> - <input class="input_w_n" type="number" maxlength='4' oninput="maxLengthCheck(this)" name = "st_tel2" required> - <input class="input_w_n" type="number" maxlength='4' oninput="maxLengthCheck(this)" name = "st_tel3" required>
                    <input type="hidden" name = "st_tel" id = "st_tel">
                
                </td>
            </tr>
        </table>
        <div class="btn-group">
            <button class="btn btn-success" type="button" onclick="location.href='./main.php'" >취소</button>
            <button class="btn btn-success"  onclick="st_join()">가입</button>
        </div>
    </form>
    <div>
        <p>- 리탠다드 본사 승인 완료 후 로그인 가능합니다.</p>
        <p>- 빠른 승인을 원할 경우 010-9031-7650으로 문자 주세요.</p>
    </div>
</div>


<script>
    function st_join(){
        var f = document.st_join_form;
        var $f = jQuery(f);
        var $b = jQuery(this);
        var $t, t;
        var result = true;
        if (confirm("저장하시겠습니까?")) {
            $f.find("input, select, textarea").each(function(i) {
                $t = jQuery(this);

                if($t.prop("required")) {
                    if(!jQuery.trim($t.val())) {
                        t = jQuery("label[for='"+$t.attr("id")+"']").text();
                        result = false;
                        $t.focus();
                        alert(t+" 필수 입력입니다.");
                        return false;
                    }
                }
            });
            if(!result){
                return false;
            }else {
                f.submit();
            }
        }
    }

    function maxLengthCheck(object){
        if (object.value.length > object.maxLength){
        object.value = object.value.slice(0, object.maxLength);
        }    
    }

    $(".st_zip_addr").click(function() {
        $("#btn_zip_addr").click();
    });

    



</script>
