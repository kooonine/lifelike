<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$g5['title'] = '회원등급설정';
include_once('./admin.head.php');

if ($deleteCheck) {
    $deleteSql = "DELETE FROM lt_member_rating WHERE mr_id = '$deleteCheck'";
    sql_query($deleteSql);
    alert('삭제되었습니다.');
    // header('Location: ./member_list5.php');
}

if ($page == 99) {
    $image_regex = "/(\.(gif|jpe?g|png))$/i";
    $chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $cp_image;
    if (isset($_FILES['iconImg']) ) {
        // 기존 동영상 이미지가 있는 경우 삭제
        // if ($_POST['org_cp_image_' . $fi])
        //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);
        if (preg_match($image_regex, $_FILES['iconImg']['name'])) {
            $design_dir = G5_DATA_PATH . '/rating/';
            @mkdir($design_dir, G5_DIR_PERMISSION);
            @chmod($design_dir, G5_DIR_PERMISSION);
            shuffle($chars_array);
            $shuffle = implode('', $chars_array);
            $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['iconImg']['name']);
            $dest_path = $design_dir . '/' . $dest_file;
            move_uploaded_file($_FILES['iconImg']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            $iconImgRes = $dest_file;
        }
    }
    if ($mrId) {
        $addQuery = '';
        if($imgProcess==1) {
            $addQuery = ", mr_icon = '$iconImgRes'";
        }
        $updateSql = "UPDATE lt_member_rating SET mr_couponProductName = '$couponProductName', mr_couponPlusName = '$couponPlusName', mr_couponCartName = '$couponCartName', mr_rating = '$ratingName', mr_start_count = '$ratingStartCount', mr_end_count = '$ratingEndCount', mr_start_amount = '$ratingStartAmount', mr_end_amount = '$ratingEndAmount', mr_point_percent = '$ratingPer'$addQuery WHERE mr_id = $mrId";
        sql_query($updateSql);
        alert('수정되었습니다.');
    } else {
        $insertSql = "INSERT INTO lt_member_rating (mr_couponProductName,mr_couponPlusName,mr_couponCartName,mr_rating,mr_start_count,mr_end_count,mr_start_amount,mr_end_amount,mr_point_percent,mr_icon) VALUES ('$couponProductName','$couponPlusName','$couponCartName','$ratingName','$ratingStartCount','$ratingEndCount','$ratingStartAmount','$ratingEndAmount','$ratingPer','$iconImgRes')";
        sql_query($insertSql);
        alert('등록되었습니다.');
    }
}
$sql = "SELECT * FROM lt_member_rating ORDER BY mr_start_amount asc";
$result = sql_query($sql); 

?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
            <div class="tbl_head01 tbl_wrap" id ="ratingView">
                <table>
                    <colgroup>
                        <col width="6%"/>
                        <col width="17%"/>
                        <col width="17%"/>
                        <col width="40%"/>
                        <col width="20%"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">
                                <!-- <input type="radio" name="radioRating" onclick="return(false);"> -->
                            </th>
                            <th scope="col">등급명</th>
                            <th scope="col">등급아이콘</th>
                            <th scope="col">승급조건</th>
                            <th scope="col">적립률</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i=0; $row=sql_fetch_array($result); $i++) {
                        ?>
                        <tr>
                            
                            <th scope="col">
                                <input type="radio" name="radioRating" id = <?=$row['mr_id'] ?> value=<?=$row['mr_id'] ?> mrProduct='<?=$row['mr_couponProductName'] ?>' mrPlus='<?=$row['mr_couponPlusName'] ?>' mrCart='<?=$row['mr_couponCartName'] ?>' mrPer = '<?=$row['mr_point_percent'] ?>' mrName='<?=$row['mr_rating'] ?>' startCount='<?=$row['mr_start_count'] ?>' endCount='<?=$row['mr_end_count'] ?>' startAmount='<?=$row['mr_start_amount'] ?>' endAmount='<?=$row['mr_end_amount'] ?>' imgName='<?=$row['mr_icon'] ?>'>
                            </th>
                            <th scope="col"><?=$row['mr_rating'] ?></th>
                            <th scope="col">
                                <!-- <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/rating/' . $row['mr_icon'] ?>') no-repeat center"> -->
                                <? if($row['mr_icon'] && $row['mr_icon'] !='') { ?>
                                    <img style='width: 40px; height: 40px;' src ='<?= G5_DATA_URL . '/rating/' . $row['mr_icon'] ?>'>
                                <!-- </div> -->
                                <? } ?>
                            </th>
                            <th scope="col">
                                <? if ($row['mr_end_count'] == 0 || $row['mr_end_count'] == '' || !$row['mr_end_count']) { ?>
                                    <div>
                                        주문건수 : <?=$row['mr_start_count'] ?>회이상
                                    </div>
                                <? } else { ?>
                                    <div>
                                        주문건수 : <?=$row['mr_start_count'] ?>회 ~ <?=$row['mr_end_count'] ?>회
                                    </div>
                                <? } ?>
                                <? if ($row['mr_end_amount'] == 0 || $row['mr_end_amount'] == '' || !$row['mr_end_amount']) { ?>
                                    <div>
                                        결제금액 : <?=$row['mr_start_amount'] ?>원이상
                                    </div>
                                <? } else { ?>
                                    <div>
                                        결제금액 : <?=$row['mr_start_amount'] ?>원 ~ <?=$row['mr_end_amount'] ?>원
                                    </div>
                                <? } ?>

                            </th>
                            <th scope="col"><?=$row['mr_point_percent'] ?></th>
                        </tr>
 
                    </tbody>
                    <?php
                        }
                        if ($i == 0)
                        echo "<tr><td colspan=\"5\" class=\"empty_table\">등록된 등급이 없습니다.</td></tr>";
                    ?>
                </table>
                <br>
                <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button class="btn btn_02" type="reset" id="btn_clear" onclick="deleteRating()">삭제</button>
                            <button type="button" class="btn btn-success" onclick="modifyRating()">수정</button>
                            <button type="button" class="btn btn-success" onclick="submitRating()">등록</button>

                        </div>
                </div>

            </div>
            <div id ="ratingForm" style="display: none;">

                <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="post" action="./member_list5.php" onsubmit="return fsearch_submit(this);" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
                    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
                    <input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
                    <input type="hidden" name="mb_10"  id="mb_10" value="<?php echo $mb_10; ?>"> -->
                    
                    <input type="hidden" name="page"  id="page" value=99>
                    <input type="hidden" name="mrId"  id="mrId" value="">
                    <input type="hidden" name="deleteCheck" id="deleteCheck" value="">



                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                    	<colgroup>
                        <col class="grid_4">
                        <col>
                        <col class="grid_3">
                        </colgroup>

                        <tr>
                            <th scope="row">등급명</th>
                    		<td colspan="2">
                    		<input type="text" name="ratingName" value=""  id="ratingName"  class=" frm_input" required>
                    		</td>
                        </tr>
                        <tr>
                            <th scope="row">포인트</th>
                    		<td colspan="2">
                            <input type="text" name="ratingPer" value="" id="ratingPer"  class=" frm_input" required> %
                    		</td>
                        </tr>
                        <tr>
                            <th scope="row">아이콘</th>
                    		<td colspan="2">
                            <input type="hidden" name="imgProcess" id="imgProcess" value="">
                            <!-- <input type="file" id="imgFile" name="it_img" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:200px" accept=".jpg, .png"> -->
							<!-- <input type="hidden" id="orgimgFile" name="orgit_img" value="test!!!"> -->
                            
                                <!-- <input type="file" name="cp_image_<?= $mc ?>" id="cp_image[<?= $mc ?>]" title="<?= $banner ?> 이미지" class="frm_file " accept="image/*"> -->
                                <input type="file" name="iconImg" id="iconImg" title="" class="frm_file " accept="image/*" style="display: none;">
                                <!-- <span>test!!</span> -->
                                
                                <input name="fileTitle" id="fileTitle" class="upload_name" value="" disabled="disabled">
                                <!-- <span style="cursor:pointer">X</span> -->
			                    <label for="iconImg"><span style="cursor:pointer; border: 1px solid gray">파일 선택</span></label>
			                    <input type="file" name="iconImg2" id="iconImg2" title="" class="frm_file"  accept="image/*" style="display: none;"> 
                    		</td>
                        </tr>
                        <!-- 쿠폰추가 -->
                        <tr>
                            <th scope="row">제품쿠폰명</th>
                    		<td colspan="2">
                    		<input type="text" name="couponProductName" value=""  id="couponProductName"  class=" frm_input">
                    		</td>
                        </tr>
                        <tr>
                            <th scope="row">플러스쿠폰명</th>
                    		<td colspan="2">
                    		<input type="text" name="couponPlusName" value=""  id="couponPlusName"  class=" frm_input">
                    		</td>
                        </tr>
                        <tr>
                            <th scope="row">장바구니쿠폰명</th>
                    		<td colspan="2">
                    		<input type="text" name="couponCartName" value=""  id="couponCartName"  class=" frm_input">
                    		</td>
                        </tr>
                        <!-- 쿠폰끝 -->
                        <tr>
                            <th scope="row" rowspan="2">승급조건</th>
                    		<td colspan="2">
                                주문건수 :
                            <input type="text" name="ratingStartCount" value="" id="ratingStartCount"  class=" frm_input" style="width: 80px;" required>&nbsp;건 
                            &nbsp;~ &nbsp;
                            <input type="text" name="ratingEndCount" value="" id="ratingEndCount"  class=" frm_input" style="width: 80px;">&nbsp;건
                    		</td>
                        </tr>
                        <tr>
                    		<td colspan="2">
                                결제금액 :
                            <input type="text" name="ratingStartAmount" value="" id="ratingStartAmount"  class=" frm_input" style="width: 130px;" required>&nbsp;원
                            &nbsp;~ &nbsp;
                            <input type="text" name="ratingEndAmount" value="" id="ratingEndAmount"  class=" frm_input" style="width: 130px;">&nbsp;원
                    		</td>
                    	</tr>
                    	</table>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
    	                    <button class="btn btn_02" type="reset" id="btn_clear" onclick="viewGO()">취소</button>
                            <button type="submit" class="btn btn-success" >확인</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

<script>
$(function(){
     
	var fileTarget = $('#iconImg');
	fileTarget.on('click', function(){ 
        var filename = document.getElementById('iconImg').files[0];
        if (!filename) {
            $('#imgProcess').val(1);
            $('#fileTitle').val('');
        }
    })
	fileTarget.on('change', function(){ 
        $('#imgProcess').val(1);
        var filename = document.getElementById('iconImg').files[0];
        if (filename) {
            $('#fileTitle').val(filename.name);
        } else {
            $('#fileTitle').val('');
        }
    })
});
function fsearch_submit(f) {
    f.action = './member_list5.php';
    return true;
}
function viewGO() { 
    $('#ratingForm').hide();
    $('#ratingView').show();
}
function submitRating() { 
    $('#mrId').val('');
    $('#ratingName').val('');
    $('#couponProductName').val('');
    $('#couponPlusName').val('');
    $('#couponCartName').val('');
    $('#ratingPer').val('');
    $('#ratingStartCount').val('');
    $('#ratingEndCount').val('');
    $('#ratingStartAmount').val('');
    $('#ratingEndAmount').val('');
    $('#fileTitle').val('');
    $('#imgProcess').val('');
    $('#ratingForm').show();
    $('#ratingView').hide();
    
}
function deleteRating() { 
    var check_count = document.getElementsByName("radioRating").length;
    var mrId;
    for (var i=0; i<check_count; i++) {
        if (document.getElementsByName("radioRating")[i].checked == true) {
            mrId = document.getElementsByName("radioRating")[i].value;
        }
    }
    if (!mrId) alert ('삭제할 등급을 선택해주세요.');
    else {
        var f = document.fsearch;
        $('#deleteCheck').val(mrId);
        f.submit()
    }

}

// /생각일해보자 delete

function modifyRating() {
    var check_count = document.getElementsByName("radioRating").length;
    var mrId;
    var ratingName;
    var ratingPer;
    var ratingStartCount;
    var ratingEndCount;
    var ratingStartAmount;
    var ratingEndAmount;
    var fileTitle;
    var couponProductName;
    var couponPlusName;
    var couponCartName;

    for (var i=0; i<check_count; i++) {
        if (document.getElementsByName("radioRating")[i].checked == true) {
            mrId = document.getElementsByName("radioRating")[i].value;
            ratingName = $( `#${mrId}` ).attr("mrName");

            couponProductName = $( `#${mrId}` ).attr("mrProduct");
            console.log('mr_couponProductName : ',couponProductName);
            couponPlusName = $( `#${mrId}` ).attr("mrPlus");
            couponCartName = $( `#${mrId}` ).attr("mrCart");

            ratingPer = $( `#${mrId}` ).attr("mrPer");
            ratingStartCount = $( `#${mrId}` ).attr("startCount");
            ratingEndCount = $( `#${mrId}` ).attr("endCount");
            ratingStartAmount = $( `#${mrId}` ).attr("startAmount");
            ratingEndAmount = $( `#${mrId}` ).attr("endAmount");
            fileTitle = $( `#${mrId}` ).attr("imgName");
        }
    }

    if (!mrId) alert ('수정할 등급을 선택해주세요.');
    else {
        $('#mrId').val(mrId);
        $('#ratingName').val(ratingName);
        $('#couponProductName').val(couponProductName);
        $('#couponPlusName').val(couponPlusName);
        $('#couponCartName').val(couponCartName);
        $('#ratingPer').val(ratingPer);
        $('#ratingStartCount').val(ratingStartCount);
        $('#ratingEndCount').val(ratingEndCount);
        $('#ratingStartAmount').val(ratingStartAmount);
        $('#ratingEndAmount').val(ratingEndAmount);
        $('#fileTitle').val(fileTitle);
        $('#imgProcess').val('');
        $('#ratingForm').show();
        $('#ratingView').hide();
    }
}
// function fmemberlist_submit(f)
// {

    // if(document.pressed == "불량회원설정") {
    	
	// 	if($("input[name='mb_intercept']:checked").val() == "1" && $("#mb_7").val() == ""){
	// 		alert("설정 사유를 입력해 주세요");
	// 		$("#mb_7").focus();
	// 		return false;
	// 	}

    // } else {
    // 	return false;
    // }

    // return true;
// }


</script>

<?php
include_once ('./admin.tail.php');
?>
