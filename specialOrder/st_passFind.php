<?php

include_once('./_common.php');


$cp_code = "ACE";
$cp_name = "에이스침대";


?>

<div>
    <h2><img class="b2b_logo" src = "https://lifelikecdn.co.kr/common/sofraum_logo.png"></h2>
    <h4>비밀번호찾기</h4>
    
</div>

<input type="hidden" id = "po_cp_code" value= "<?=$cp_code?>" >
<input type="hidden" id = "po_st_name" value= "" >


<div class="front_content" id="pass_find_con">
    <form class="clasic" method = "post">
        <table class="spo_tbl">
            <tr>
                <th>매장명</th>
                <td>
                    <input type="text" name = "st_name" autocomplete="off" size = 35>
                </td>
            </tr>
            <tr>
                <th>사업자번호</th>
                <td>
                    <input class="input_w_n" name="st_num1" type="number" maxlength='3' autocomplete="off" oninput="maxLengthCheck(this)" > - <input class="input_w_s" name="st_num2" type="number" maxlength='2' autocomplete="off" oninput="maxLengthCheck(this)" > - <input class="input_w_n" name="st_num3" type="number" maxlength='5' autocomplete="off" oninput="maxLengthCheck(this)" >
                    <input type="hidden" name ="st_number" value="">
                </td>
            </tr>
            <tr>
                <th>점주명</th>
                <td>
                    <input type="text" name ="st_owner" autocomplete="off" value="">
                </td>
            </tr>            
            <tr>
                <th>연락처</th>
                <td>

                    <input class="input_w_n" name="st_tel1" type="number" maxlength='4' autocomplete="off" oninput="maxLengthCheck(this)"> - <input class="input_w_n" name="st_tel2" type="number" maxlength='4' autocomplete="off" oninput="maxLengthCheck(this)"> - <input class="input_w_n" name="st_tel3" type="number" maxlength='4' oninput="maxLengthCheck(this)">
                    <input type="hidden" name = "st_tel">
                </td>
            </tr>
        </table>
        <div class="btn-group">
            <button class="btn btn-success" type="button" onclick="location.href='./main.php'">취소</button>
            <button class="btn btn-success" type="button" onclick="Find_pass()">인증</button>
        </div>
    </form>
</div>


<script>
    function Find_pass(){
        var st_name = $("input[name = 'st_name']").val();
        var st_number = $("input[name = 'st_num1']").val()  +'-'+ $("input[name = 'st_num2']").val() +'-'+ $("input[name = 'st_num3']").val();
        var st_owner = $("input[name = 'st_owner']").val();
        var st_tel = $("input[name = 'st_tel1']").val() +'-'+ $("input[name = 'st_tel2']").val() +'-'+ $("input[name = 'st_tel3']").val();
        
        if(st_name && st_number && st_owner && st_tel  ){
            var formData = new FormData();
                
            formData.append("st_name", st_name);
            formData.append("st_number", st_number);
            formData.append("st_owner", st_owner);
            formData.append("st_tel", st_tel);
            
            formData.append("type", "PassFind");
            $.ajax({
                url:'./b2b_login_check.php',
                type:'post',
                processData: false,
                contentType:false,
                async: false,
                data: formData,
                
                success:function(data){
                    console.log(data);
                    if (data.indexOf('300') !== -1) {
                        alert("가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\n비밀번호는 대소문자를 구분합니다.");
                    }else if(data.indexOf('301') !== -1) {
                        alert("승인이 완료되지 않은 매장입니다.\n리탠다드 본사 승인 완료 후 로그인 가능합니다.\n빠른 승일을 원할 경우 010-9031-7650으로 문자 주세요.");
                    }
                    else if(data.indexOf('200') !== -1) {

                        $("#po_st_name").val(st_name);


                        var $content_area = $("#pass_find_con");
                        $.post(
                            "./st_newPassword.php", {
                                st_name: st_name,
                                st_number : st_number,
                                st_owner : st_owner,
                                st_tel : st_tel
                                
                            },
                            function(res) {
                                $content_area.empty().html(res);
                            }
                        );
                    }   
                }
            });
        }else{
            alert("모든 정보를 정확하게 입력해주세요.");
        }


    }

    function new_password(){
        var st_name = $("#po_st_name").val();
        var cp_code = $("#po_cp_code").val();
        var new_pass = $("input[name = 'password']").val();
        var new_pass_re = $("input[name = 'password_re']").val();

        if(new_pass == new_pass_re){
        }else{
            alert("비밀번호를 확인해주세요.");
        }

        var formData = new FormData();

        formData.append("cp_code", cp_code);        
        formData.append("st_name", st_name);
        formData.append("st_password", new_pass);
        
        
        formData.append("type", "newPass");
        $.ajax({
            url:'./b2b_login_check.php',
            type:'post',
            processData: false,
            contentType:false,
            async: false,
            data: formData,
            
            success:function(data){
                
                if(data.indexOf('200') !== -1) {
                    alert("비밀번호가 변경되었습니다.");
                    location.href="./main.php";
                }   
            }
        });



    }
    function maxLengthCheck(object){
        if (object.value.length > object.maxLength){
        object.value = object.value.slice(0, object.maxLength);
        }    
    }


</script>
