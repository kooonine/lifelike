<?php

include_once('./_common.php');

if(empty($_SESSION['st_mb_name'])){
    if($_COOKIE['save_me'] == 'me_on' && $_COOKIE['save_name'] && $_COOKIE['save_code'] ){
        set_session('st_mb_name', $_COOKIE['save_name']);
        set_session('st_mb_code', $_COOKIE['save_code']);
    }else{
        alert("로그인이 필요합니다.");
        goto_url("./main.php");
    }
}


$cp_sql = "select cp_code from b2b_store_list where st_name = '{$_SESSION['st_mb_name']}' and  st_code = '{$_SESSION['st_mb_code']}' limit 1 ";
$cp = sql_fetch($cp_sql);


$sql = "select * from b2b_sale_item_list where cp_code = '{$cp['cp_code']}' and display_yn = 'Y' ";
$sql_res = sql_query($sql);


?>


<div>
    <h2><img class="b2b_logo" src = "https://lifelikecdn.co.kr/common/sofraum_logo.png">  <button class="logout_btn" onclick="logout('<?=$_SESSION['st_mb_name']?>');">로그아웃</button></h2>
    <h4>상품선택</h4>

    
</div>
<div class="">
    <span class="b2b-sio-grid">
    <ul class="b2b_sio_ul">
        <?for($sio = 0 ; $si_row = sql_fetch_array($sql_res); $sio++ ):?>
        <li>
            <div>
                <div class="b2b-sio-input-a">
                    <input type="checkbox" class="b2b-sio-input-chk" name="chk[]" value="<?= $si_row['si_no'] ?>" id="chk_<?= $si_row['si_no'] ?>" boId= '<?= $si_row['si_no'] ?>' <?=$si_row['stock'] < 1 ? 'disabled' : ''  ?>  >
                </div>
                <div class="imgA">
                    <div class="dim" style="display : <?=$si_row['stock'] < 1 ? 'block' : 'none'  ?>">
                        일시품절
                    </div>
                    <?
                    if(file('https://lifelikecdn.co.kr/sabang/sofraum/'.$si_row['sap_code'].'_THUM_1.jpg')){
                        $THUM1 = "https://lifelikecdn.co.kr/sabang/sofraum/".$si_row['sap_code']."_THUM_1.jpg";
                    }
                    
                    ?>
                    <img style="opacity : <?=$si_row['stock'] < 1 ? '0.3' : ''  ?>" src= "<?=$THUM1?>">
                </div>
                <div>소프라움 <?=$si_row['size']?></div>
                <div><?=$si_row['it_name']?></div>
                <div><?=number_format($si_row['supply_price'])?>원</div>
                <div>최소구매수량 : <?=$si_row['minium_order']?></div>

            </div>
            
        </li>
        
        
        <?endfor?>        
    </ul>
    </span>

    <div class="biger-btn-a">
        <button class="on-big biger-btn-success" onclick ="order_item();">주문</button>
        <button class="on-small biger-btn-success" onclick ="order_item();">주문</button>
        <form id="si_order_detail" method="post" action="./st_item_order_detail.php">
            <input type="hidden" name = "select_sis" id = "select_sis" value="">
        </form>
    </div>
</div>

<script>

    $(document).ready(function () {

        let div = document.querySelector('.on-big.biger-btn-success');
        let result = document.querySelector('.b2b-sio-grid');

        $(".on-big.biger-btn-success").css("margin-left",((result.offsetWidth/2)-100));

    });
        
    

    




    function logout(st_name){
        var formData = new FormData();
                
        formData.append("Id", st_name);
        formData.append("type", "logout");
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
                    // alert("가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\n비밀번호는 대소문자를 구분합니다.");
                    
                }else if(data.indexOf('201') !== -1) {
                    location.href="./main.php";
                    
                }   
            }
            
        }); 
    }

    function order_item(){
        if ($("input:checkbox[name='chk[]']").is(":checked") == false) {
            alert("상품을 선택해 주세요.");
            return false;
        }
        $("#select_sis").val("");

        var select = new Array();
    
        $("input[name='chk[]']:checked").each(function() {
            var si_id = this.value;
            select.push(si_id);
        });

        var selects = select.join(",");
        
        $("#select_sis").val(selects);

        var form = $("#si_order_detail");
        
        form.submit();


        
    }

</script>
