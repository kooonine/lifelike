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


$si_sql = "select * from b2b_sale_item_list where si_no in   ({$_POST['select_sis']}) AND display_yn = 'Y' ";
$si_res_sql = sql_query($si_sql);



$cp_sql = "select * from b2b_store_list where st_name = '{$_SESSION['st_mb_name']}' and st_code = '{$_SESSION['st_mb_code']}' limit 1 ";
$cp = sql_fetch($cp_sql);

?>


<div>
    <h2><img class="b2b_logo" src = "https://lifelikecdn.co.kr/common/sofraum_logo.png"> <button class="logout_btn" onclick="logout('<?=$_SESSION['st_mb_name']?>');">로그아웃</button></h2>
    <h4>주문</h4>
    
</div>
<div class="front_content">

    <form id="order_form" name="order_form"  method="post">

        <table id = "sit_tlb" class="spo_tbl">
            <colgroup>
                <col width = "65%">
                <col width = "15%">
                <col width = "20%">
            </colgroup>
            <thead>
                <tr>
                    <th>상품정보</th>
                    <th>수량</th>
                    <th>주문금액</th>
                </tr>

            </thead>
            <tbody>
                <? for($sil = 0 ; $sil_row = sql_fetch_array($si_res_sql); $sil++ ) {?>
                    <tr>
                        <input type="hidden" name = "cp_name" value="<?=$cp['cp_name']?>">
                        <input type="hidden" name = "cp_code" value="<?=$cp['cp_code']?>">
                        <input type="hidden" name = "st_name" value="<?=$cp['st_name']?>">
                        <input type="hidden" name = "st_tel" value="<?=$cp['st_tel']?>">
                        <input type="hidden" name = "it_name[]" value="<?=$sil_row['it_name']?>">
                        <input type="hidden" name = "samjin_it_name[]" value="<?=$sil_row['samjin_it_name']?>">
                        <input type="hidden" name = "supply_price[]" class="supply_price_<?=$sil?>" value="<?=$sil_row['supply_price']?>">
                        <input type="hidden" name = "size[]" value="<?=$sil_row['size']?>">
                        <input type="hidden" name = "color[]" value="<?=$sil_row['color']?>">
                        <input type="hidden" name = "sap_code[]" value="<?=$sil_row['sap_code']?>">
                        <input type="hidden" name = "samjin_code[]" value="<?=$sil_row['samjin_code']?>">
                        <td>
                            <div class="b2b_sit_info_wrap">
                                <div class="b2b_sit_img">
                                <?
                                $THUM1 = '';
                                if(file('https://lifelikecdn.co.kr/sabang/sofraum/'.$sil_row['sap_code'].'_THUM_1.jpg')){
                                    $THUM1 = "https://lifelikecdn.co.kr/sabang/sofraum/".$sil_row['sap_code']."_THUM_1.jpg";
                                }
                                
                                ?>
                                <img src= "<?=$THUM1?>">
                                </div>
                                <div class="b2b_sit_info">
                                    <p>소프라움 <?=$sil_row['size']?></p>
                                    <p><?=$sil_row['it_name']?></p>
                                    <p><?=number_format($sil_row['supply_price'])?>원</p>
                                    <input type = "hidden" class = "normal_price_<?=$sil?>" name="normal_price[]" value = "<?=$sil_row['normal_price']?>">

                                </div>
                            </div>
                        </td>
                        <td>
                            <input class="input_w_n qty_input order_qty_<?=$sil?>" data-idx= "<?=$sil?>" it_n = "<?=$sil_row['it_name']?>" stock = "<?=$sil_row['stock']?>" type="number" name="order_qty[]" oninput="order_qty(this)" onblur="chk_stock(this)">
                            <p>최소주문수량 : <?=$sil_row['minium_order']?>개</p>
                            <input type = "hidden" class = "minium_order_<?=$sil?>" value = "<?=$sil_row['minium_order']?>">
                        </td>
                        <td>
                            <input type="hidden" class="Oprice Oprice_<?=$sil?>" name = "order_price[]" value="">
                            <span class="order_price order_price_<?=$sil?>"></span>
                            <span>원</span>
                        </td>
                    </tr>
                <?}?>
            </tbody>
        </table>

        <p>- 주문 후 010-9031-7650으로 문자 주시면 빠른처리가 가능합니다.</p>

        <div class="sit_total_price">
            총 상품 금액 : <span class="t_price"></span> 원
            <input type="hidden" class="total_order_price" name = "total_price[]" value="">
        </div>

        <h4>배송</h4>

    

        <table class="spo_tbl">
            <tr>
                <th>주소</th>
                <td>
                    <div><input type="text"  class="input_w_n st_zip_addr"  name ="receive_zip" value="<?=$cp['st_zip']?>" readonly required> <button id="btn_zip_addr" class="btn btn-cart-action btn-black-2" type="button" style="margin-top: 0; vertical-align: top; margin-left: 14px;" onclick="win_zip('order_form','receive_zip' , 'receive_addr1', 'receive_addr2', 'receive_addr3','receive_addr_jibun');">우편번호</button></div>
                    <div>
                        <input type="text" class="st_zip_addr" name ="receive_addr1" id ="receive_addr1" size = 40 value="<?=$cp['st_addr1']?>" readonly required> <input type="text" name ="receive_addr2" id ="receive_addr2"  size=35 value="<?=$cp['st_addr2']?>" required>
                        <input type="hidden" name ="receive_addr3">
                        <input type="hidden" name ="receive_addr_jibun">
                    </div>
                </td>
            </tr>
            <tr>
                <th>수취인명</th>
                <td>
                    <input type="text" name ="receive_name" id ="receive_name" value="<?=$cp['st_owner']?>" required>
                </td>
            </tr>
            <tr>
                <th>연락처</th>
                <td>
                    <?
                        
                        $tel_arr = explode( '-',$cp['st_tel']);
                    ?>
                    <input class="input_w_n" type="number" maxlength='4' oninput="maxLengthCheck(this)"  id = "receive_tel1" name = "receive_tel1"  value="<?=$tel_arr[0]?>" required> - <input class="input_w_n" type="number" maxlength='4' oninput="maxLengthCheck(this)" id = "receive_tel2" name = "receive_tel2"  value="<?=$tel_arr[1]?>" required> - <input class="input_w_n" type="number" maxlength='4' oninput="maxLengthCheck(this)" id = "receive_tel3" name = "receive_tel3"  value="<?=$tel_arr[2]?>" required>
                        <input type="hidden" name = "st_tel" id = "st_tel">
                </td>
            </tr>
        </table>

        <p>- 고객배송시 주소와 수취인명, 연락처를 변경해주세요.</p>

        <div class="btn-group">
            <button class="btn btn-success" type="button" onclick="location.href='./st_item_order.php'" >취소</button>
            <button class="btn btn-success" type="button" onclick="order()">주문</button>
        </div>

    </form>

    

</div>


<script>

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
                    
                    
                }else if(data.indexOf('201') !== -1) {
                    location.href="./main.php";
                    
                }   
            }
            
        }); 
    }

    $(".st_zip_addr").click(function() {
        $("#btn_zip_addr").click();
    });

    function maxLengthCheck(object){
        if (object.value.length > object.maxLength){
        object.value = object.value.slice(0, object.maxLength);
        }    
    }

    function order_qty(qty){
        let id = $(qty).data("idx");

        let supplyPrice = $(".supply_price_"+id).val();
        
        let orderPrice = (qty.value*1) * (supplyPrice*1) ;
        
        $(".order_price_"+id).empty().html(comma(orderPrice+""));

        $(".Oprice_"+id).val(orderPrice);

        var fileValue = $(".Oprice").length;
        var fileData = new Array(fileValue);
        let total_order_price = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".Oprice")[i].value.replace(/,/gi,'');
            total_order_price += (fileData[i]*1);
        }
        $(".t_price").empty().html(comma(total_order_price+""));

        $(".total_order_price").val(total_order_price);
    }

    function chk_stock(elem){
        let i_stock = $(elem).attr('stock');
        let i_n = $(elem).attr('it_n');
        let ord_qty = elem.value;
        
        if((ord_qty*1)> (i_stock*1)){
            alert(i_n+" 상품의 현재고는 "+i_stock+"개입니다. " + i_stock + "개 이하로 주문해주세요.");
            elem.value = '';
        }
    }

    function comma(obj){
        
        var regx = new RegExp(/(-?\d+)(\d{3})/);
        var bExists = obj.indexOf(".", 0);//0번째부터 .을 찾는다.
        var strArr = obj.split('.');
        while (regx.test(strArr[0])) {//문자열에 정규식 특수문자가 포함되어 있는지 체크
            //정수 부분에만 콤마 달기 
            strArr[0] = strArr[0].replace(regx, "$1,$2");//콤마추가하기
        }
        if (bExists > -1) {
            //. 소수점 문자열이 발견되지 않을 경우 -1 반환
            obj = strArr[0] + "." + strArr[1];
        } else { //정수만 있을경우 //소수점 문자열 존재하면 양수 반환 
            obj = strArr[0];
        }
        return obj;//문자열 반환     
    }

    function order(){
        var chk_order = 0;
        var fileValue = $(".qty_input").length;
        var fileData = new Array(fileValue);
      
        for(var j=0; j<fileValue; j++){     
            let id = $(".order_qty_"+j).data("idx");
            let mini_qty = $(".minium_order_"+id).val();
            
            if(($(".order_qty_"+j).val()*1) < (mini_qty * 1) ){
                chk_order = 1;
            }
        }


        if(chk_order > 0){
            alert("최소주문수량을 확인 후 주문해주세요.");
            return;
        }else{
            // order_form.submit();

            var formData = $("#order_form").serialize();
	
			$.ajax({
				cache : false,
				url : "./ajax_st_item_order.php", // 요기에
				type : 'POST', 
				data : formData, 
				success : function(data) {
					// console.log(data);
					alert("주문 완료 되었습니다.");
					// location.reload();
                    location.href="./st_item_order.php";
				}, // success 
	
				error : function(xhr, status) {
					alert(xhr + " : " + status);
				}
			}); 
        }

    }


</script>
