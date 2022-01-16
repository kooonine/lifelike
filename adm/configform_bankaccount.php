<?php
//$sub_menu = "101420";
$sub_menu = "10";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '무통장입금계좌설정';
include_once ('./admin.head.php');

$bankArr = json_decode($default['de_bank_account'], true);

$token = get_admin_token();
?>

<form name="frm" id="frm" action="./configform_bankaccount_update.php" method="post">
<input type="hidden" name="token" value="<?php echo $token ?>" id="token">
<input type="hidden" name="de_bank_account" id="de_bank_account" value="<?php echo str_replace("\"", "&quot;", $default['de_bank_account']); ?>">

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                    	<input type="button" class="btn btn-danger" value="선택 삭제" onclick="frm_submit('del');"></input>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                    	<input type="button" class="btn btn-success" value="계좌 등록" onclick="frm_submit('new');"></input>
                    </div>
                </div>
              
                <div class="tbl_head01 tbl_wrap">
                    <table>
                    <caption><?php echo $g5['title']; ?> 목록</caption>
                    <thead>
                    <tr>
                        <th scope="col">
                            <label for="chkall" class="sound_only">전체</label>
                            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                        </th>
                        <th scope="col">은행명</th>
                        <th scope="col">계좌번호</th>
                        <th scope="col">예금주</th>
                        <th scope="col">사용여부</th>
                    </tr>
                    </thead>
                    <tbody> 
                    <?php 
                    $colspan = 5;
                    
                    for ($i=0; $i < count($bankArr); $i++) {
                        
                        $bg = 'bg'.($i%2);
                    ?>
                    <tr class="<?php echo $bg; ?>">
                        <td class="td_chk">
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td onclick="modify_show('<?php echo $i ?>','<?php echo $bankArr[$i]["name"] ?>','<?php echo $bankArr[$i]["number"] ?>','<?php echo $bankArr[$i]["holder"] ?>','<?php echo $bankArr[$i]["use"] ?>')">
                        	<a href="#"><?php echo $bankArr[$i]["name"] ?></a>
                        </td>
                        <td><?php echo $bankArr[$i]["number"] ?></td>
                        <td><?php echo $bankArr[$i]["holder"] ?></td>
                        <td><?php echo $bankArr[$i]["use"] ?></td>
                    </tr>
                     <?php
                    }
                    if ($i == 0)
                        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
                    ?>
                    </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                    	<input type="button" class="btn btn-danger" value="선택 삭제" onclick="frm_submit('del');"></input>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                    	<input type="button" class="btn btn-success" value="계좌 등록" onclick="frm_submit('new');"></input>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div>
</form>

<div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="bankModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
      <div class="modal-header">
    	<div class="row">
			<div class="col-md-10 col-sm-10 col-xs-10 text-left"><h5 class="modal-title" id="bankModalLabel">입금계좌 수정 팝업</h5></div>
      		<div class="col-md-2 col-sm-2 col-xs-2 text-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
    	</div>
      </div>
      
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="bankName" class="col-form-label">은행명</label>
            <select class="form-control" id="bankName">
            	<option>우리은행</option>
            	<option>국민은행</option>
            	<option>하나은행</option>
            	<option>농협</option>
            </select>
            <input type="hidden" name="idx" value="" id="idx">
          </div>
          
          <div class="form-group">
            <label for="bankNumber" class="col-form-label">계좌번호</label>
            <input type="text" class="form-control" id="bankNumber">
          </div>
          
          <div class="form-group">
            <label for=""bankHolder"" class="col-form-label">예금주</label>
            <input type="text" class="form-control" id="bankHolder">
          </div>
          
          <div class="form-group">
            <label for="message-text" class="col-form-label">사용여부</label>
            <div class="radio">
	            <label for="de_escrow_use2">
                <input type="radio" name="bankUse" value="Y" id="bankUseY">
                 사용함</label>
                 
				<label for="de_escrow_use1">
				<input type="radio" name="bankUse" value="N" id="bankUseN" >
                사용안함</label>
                
                
            </div>

          </div>
          
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="bank_submit()">저장</button>
      </div>
      
    </div>
  </div>
</div>

<script>
function frm_submit(pressed)
{
	if(pressed == "del") {
        if (!is_checked("chk[]")) {
            alert("삭제 하실 항목을 하나 이상 선택하세요.");
            return false;
        }
    
        if(confirm("선택한 무통장입금 계좌 정보를 삭제하시겠습니까?")) {

        	var	bankAccountArr = JSON.parse($("#de_bank_account").val());

//        	alert($('input:checkbox[name="chk"]'));
        	var chkedArr = $("input[name='chk[]']:checked");
        	
        	for(var i=chkedArr.length-1;i>= 0;i--){
        		//alert(chkedArr[i].value);
        		bankAccountArr.splice(chkedArr[i].value,1);
        	}
        	//alert(JSON.stringify(bankAccountArr));
        	
           	$("#de_bank_account").val(JSON.stringify(bankAccountArr));
        	$("#frm").submit();
            return false;
        }
	}

	if(pressed == "new") {
		
		$("#bankModalLabel").html("입금계좌 등록 팝업");
		$("#btnSave").html("저장");		
		
		$("#idx").val("");
		$("#bankName").val("");
		$("#bankNumber").val("");
		$("#bankHolder").val("");
		$('input:radio[name="bankUse"][value="Y"]').prop("checked", true);
		
		$('#bankModal').modal('show');
        return false;
	}

    return true;
}

function modify_show(i, name,number,holder,use)
{
	$("#bankModalLabel").html("입금계좌 수정 팝업");
	$("#btnSave").html("수정");	
	
	$("#idx").val(i);
	$("#bankName").val(name);
	$("#bankNumber").val(number);
	$("#bankHolder").val(holder);
	$('input:radio[name="bankUse"][value="' + use + '"]').prop("checked", true);
	
	$('#bankModal').modal('show');
}

function bank_submit()
{
//	alert($("#de_bank_account").val());
	var	bankAccountArr = JSON.parse($("#de_bank_account").val());
//	alert(bankAccountArr.length);

	var bankInfoJson = new Object();
	bankInfoJson.name = $("#bankName").val();
	bankInfoJson.number = $("#bankNumber").val();
	bankInfoJson.holder = $("#bankHolder").val();
	bankInfoJson.use = $('input:radio[name="bankUse"]:checked').val();

	if($("#idx").val() != "") {    
    	for(var i=0;i<bankAccountArr.length;i++){
    		if(i == $("#idx").val()){
    			bankAccountArr[i] = bankInfoJson;
    		}
    	}
	} else {
		bankAccountArr.push(bankInfoJson);
	}

	$("#de_bank_account").val(JSON.stringify(bankAccountArr));
	$("#frm").submit();
	
	//alert();
	
}

</script>


<?php
include_once ('./admin.tail.php');
?>
