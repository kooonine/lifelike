<?php
include_once('./_common.php');


$_SESSION['st_mb_name'];

if($_SESSION['st_mb_name']){
    goto_url("./st_item_order.php");
}else {
    if($_COOKIE['save_me'] == 'me_on' && $_COOKIE['save_name'] && $_COOKIE['save_code'] ){
        set_session('st_mb_name', $_COOKIE['save_name']);
        set_session('st_mb_code', $_COOKIE['save_code']);
        goto_url("./st_item_order.php");
    }
}


?>

<div class="content">

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
    <div class="join-b">
        <p>로그인</p>
        <p class="login">매장명 : <input name="Id" id="Id" type="text" value="<?=$_COOKIE['save_name'] ? $_COOKIE['save_name'] : '' ?>"> </p>
        <p class="login">비밀번호 : <input name="password" id = "Pass" type = "password" onkeydown = "loginEnter(event)"> </p>
        <div class="join_p">
            <p>
                <input id="save_id" name="save_id" type = "checkbox" value="id_on" <?php if(substr_count($_COOKIE['save_id'], 'id_on') >= 1 ) echo "checked"; ?> >
                <lable>아이디 저장</lable>  <button type="button" onclick="location.href='./st_join.php'">회원가입</button>
            </p>
            <p>
                <input id="save_me" name="save_me" type = "checkbox" value="me_on" <?php if(substr_count($_COOKIE['save_me'], 'me_on') >= 1 ) echo "checked"; ?>>
                <lable>자동 로그인</lable>  <button type="button" onclick="location.href='./st_passFind.php'" >비밀번호찾기</button>
            </p>
        </div>
        
        <div class="btn-group0">
            <button class="big-btn-success" type="button" onclick="login();">로그인</button>
        </div>
    </div>
    <div>
        <p>- 리탠다드 본사 승인 완료 후 로그인 가능합니다.</p>
        <p>- 빠른 승인을 원할 경우 010-9031-7650으로 문자 주세요.</p>
    </div>
</div>

<script>

    function login(){
        var Id = $("#Id").val();
        var Pass = $("#Pass").val();
        var save_id = $("#save_id").val();
        var save_me = $("#save_me").val();

        if(!Id){
            alert("매장명을 입력해주세요.");
            $("#Id").focus();
            return;
        }
        if(!Pass){
            alert("비밀번호을 입력해주세요.");
            $("#Pass").focus();
            return;
        }

        var formData = new FormData();
                
        formData.append("Id", Id);
        formData.append("Pass", Pass);
        formData.append("type", "login");
        if ($("input:checkbox[name='save_id']").is(":checked") == true) {
            
            formData.append("save_id", save_id);
        }
        if ($("input:checkbox[name='save_me']").is(":checked") == true) {
            
            formData.append("save_me", save_me);
        }
        $.ajax({
            url:'./b2b_login_check.php',
            type:'post',
            processData: false,
            contentType:false,
            async: false,
            data: formData,
            
            success:function(data){
                // console.log(data);
                if (data.indexOf('300') !== -1) {
                    alert("가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\n비밀번호는 대소문자를 구분합니다.");
                    
                }else if(data.indexOf('301') !== -1) {
                    alert("승인이 완료되지 않은 매장입니다.\n리탠다드 본사 승인 완료 후 로그인 가능합니다.\n빠른 승일을 원할 경우 010-9031-7650으로 문자 주세요.");
                    
                }else if(data.indexOf('200') !== -1) {
                    location.href="./st_item_order.php";
                    
                }   
            }
            
        }); 
    }

    function loginEnter (e){
        if (e.keyCode == 13) {
            login();
        }
    }
  

</script>
