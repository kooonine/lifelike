<?php
$sub_menu = '92';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH.'/iteminfo.lib.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

if ($is_admin != 'super')
{
    $sql = "select * from lt_member_company where mb_id = '{$member['mb_id']}' ";
    $cp = sql_fetch($sql);
}

$html_title = "상품 ";

if ($w == "")
{
    $html_title .= "등록";

    // 옵션은 쿠키에 저장된 값을 보여줌. 다음 입력을 위한것임
    //$it[ca_id] = _COOKIE[ck_ca_id];
    $it['ca_id'] = "40"; //브랜드
    $it['ca_id3'] = $cp['company_code']; //브랜드
    
    //$it[it_maker]  = stripslashes($_COOKIE[ck_maker]);
    //$it[it_origin] = stripslashes($_COOKIE[ck_origin]);
    $it['it_maker']  = stripslashes(get_cookie("ck_maker"));
    $it['it_origin'] = stripslashes(get_cookie("ck_origin"));
    
    $it['it_use'] = 0;
    $it['it_item_type'] = 0; //0:제품,1:리스
    
    $it['its_discount_type'] = 0; //할인설정
    
    $it['its_free_laundry'] = 1; //
    $it['its_laundry_use'] = 0; //
    $it['its_laundrykeep_use'] = 0; //
    $it['its_repair_use'] = 0; //
    
    $it['it_sc_type'] = 0;
    $it['it_send_type'] = $cp['cp_send_type'];; 
    $it['it_send_term_start'] = $cp['cp_send_term_start'];
    $it['it_send_term_end'] = $cp['cp_send_term_end'];
    
    $it['it_sc_minimum'] = $cp['cp_send_cost_limit'];
    $it['it_sc_price'] = $cp['cp_send_cost_list'];
    
    $it['it_send_condition'] = $cp['cp_send_condition'];
    $it['it_sc_method'] = $cp['cp_send_prepayment'];
    
    $it['it_individual_costs_use'] = '0';
    
    $it['it_delivery_company'] = $cp['cp_delivery_company'];
    $it['it_return_costs'] = $cp['cp_return_costs'];
    $it['it_roundtrip_costs'] = $cp['cp_roundtrip_costs'];
    
    $it['it_return_zip'] = $cp['cp_return_zip'];
    $it['it_return_address1'] = $cp['cp_return_address1'];
    $it['it_return_address2'] = $cp['cp_return_address2'];
    
    
    $it['it_skin'] = 'basic';
    $it['it_mobile_skin'] = 'basic';
    
    $it['it_brand'] = '';
    $it['it_model'] = '';
    
    $it['it_type1'] = '';
    $it['it_type2'] = '';
    $it['it_type3'] = '';
    $it['it_type4'] = '';
    $it['it_type5'] = '';
    
    $it['it_level_sell'] = '0';
    $it['it_point_type'] = '9';
    $it['it_point_only'] = '0';
    $it['it_nocoupon'] = '1';
    $it['it_point_use'] = '1';
    $it['it_use_use'] = '1';
    $it['it_review_use'] = '1';
    
    $it['it_sc_method'] = '2';
    
    $it['it_mobile_explan_use'] = '0';
    
}
else if ($w == "u")
{
    $html_title .= "수정";
    
    $sql = " select * from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
    $it = sql_fetch($sql);
    
    if ($is_admin != 'super')
    {
        $sql = " select it_id from {$g5['g5_shop_item_table']} a
                  where a.it_id = '$it_id'
                    and a.ca_id3 = '".$cp['company_code']."' ";
        $row = sql_fetch($sql);
        if (!$row['it_id'])
            alert("\'{$member['mb_id']}\' 님께서 수정 할 권한이 없는 상품입니다.");
    } else {
        
        $sql = "select * from lt_member_company where company_code = '{$it['ca_id3']}' ";
        $cp = sql_fetch($sql);
    }

    if(!$it)
        alert('상품정보가 존재하지 않습니다.');
}
else
{
    alert();
}
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;

$g5['title'] = $html_title;
if ($is_admin != 'super'){
    include_once (G5_ADMIN_PATH.'/admin.head.php');
} else {
    include_once (G5_ADMIN_PATH.'/admin.head.sub.php');
}

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$token = get_admin_token(); 
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
      
<form name="fitemform" id="fitemform" action="./itemformupdate.php" method="post" onsubmit="return fitemformcheck(this)" enctype="multipart/form-data">

<input type="hidden" name="codedup" value="<?php echo $default['de_code_dup_use']; ?>">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod"  value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx"  value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<!-- 기본 설정 -->
<input type="hidden" name="it_skin" value="<?php echo $it['it_skin']; ?>">
<input type="hidden" name="it_mobile_skin" value="<?php echo $it['it_mobile_skin']; ?>">

<input type="hidden" name="it_maker" value="<?php echo $it['it_maker']; ?>">
<input type="hidden" name="it_origin" value="<?php echo $it['it_origin']; ?>">
<input type="hidden" name="it_brand" value="<?php echo $it['it_brand']; ?>">
<input type="hidden" name="it_model" value="<?php echo $it['it_model']; ?>">

<input type="hidden" name="it_type1" value="<?php echo $it['it_type1']; ?>">
<input type="hidden" name="it_type2" value="<?php echo $it['it_type2']; ?>">
<input type="hidden" name="it_type3" value="<?php echo $it['it_type3']; ?>">
<input type="hidden" name="it_type4" value="<?php echo $it['it_type4']; ?>">
<input type="hidden" name="it_type5" value="<?php echo $it['it_type5']; ?>">

<input type="hidden" name="ca_id2" value="<?php echo $it['ca_id2']; ?>">
<input type="hidden" name="ca_id3" value="<?php echo $it['ca_id3']; ?>">

<input type="hidden" name="it_status" value="승인대기">




	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 기본 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row"><label for="it_use">진열상태</label></th>
            <td colspan="3">
            	<?php echo ($it['it_use'])?"진열함":"진열안함"?>
				<input type="hidden" name="it_use" value="0">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ca_id">카테고리 선택</label></th>
            <td colspan="3">
            
                <select name="ca_id" id="ca_id" required="required">
                    <option value="">상품카테고리 선택</option>
                    <?php
                    $sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '40%' and length(ca_id) > 2 order by ca_order, ca_id ";
                    $result1 = sql_query($sql1);
                    for ($i=0; $row1=sql_fetch_array($result1); $i++) {
                        $len = strlen($row1['ca_id']) / 2 - 1;
                        $nbsp = '';
                        for ($i=0; $i<$len; $i++) $nbsp .= '&nbsp;&nbsp;&nbsp;';
                        echo '<option value="'.$row1['ca_id'].'" '.get_selected($it['ca_id'], $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
                    }
                    ?>
                </select>
            </td>
		</tr>            
        <tr>
            <th scope="row"><label for="it_skin">상품코드</label></th>
            <td colspan="3">
                <?php if ($w == '') { // 추가 ?>
                    <!-- 최근에 입력한 코드(자동 생성시)가 목록의 상단에 출력되게 하려면 아래의 코드로 대체하십시오. -->
                    <!-- <input type=text class=required name=it_id value="<?php echo 10000000000-time()?>" size=12 maxlength=10 required> <a href='javascript:;' onclick="codedupcheck(document.all.it_id.value)"><img src='./img/btn_code.gif' border=0 align=absmiddle></a> -->
                    <input type="text" name="it_id" value="" id="it_id" required class="frm_input required" size="20" maxlength="20" readonly="readonly">
                    <?php if ($default['de_code_dup_use'] && false) { ?><button type="button" class="btn_frmline" onclick="codedupcheck(document.all.it_id.value)">중복검사</button><?php } ?>
        			<script>
        	        $.post(
        	            "./codedupcheck.php",
        	            { company_code: '<?php echo $cp['company_code']?>' },
        	            function(data) {
        	                //alert(data.name);
        	                if(data.name) {
        	                	var it_id = data.name;
        	                	$("#it_id").val(it_id);
        	                } else {
        	                	var it_id = "<?php echo substr($cp['company_code'], 1)?>0000000001";
        	                	$("#it_id").val(it_id);
        	                }
        	            }, "json"
        	        );
        	        
        			</script>
                <?php } else { ?>
                    <input type="hidden" name="it_id" value="<?php echo $it['it_id']; ?>">
                    <span class="frm_ca_id"><?php echo $it['it_id']; ?></span>
                    <a href="<?php echo G5_SHOP_URL; ?>/item.php?it_id=<?php echo $it_id; ?>" class="btn_frmline" target="_blank">상품확인</a>
                    <!-- <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemuselist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">사용후기</a>
                    <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemqalist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">상품문의</a> -->
                <?php } ?>                
                <?php echo '<input type="hidden" name="it_item_type" value="'.$it['it_item_type'].'">'; ?>
                
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_skin">상품코드</label></th>
            <td colspan="3">
            	기본 수수료
            	 <input type="text" name="cp_commission" value="<?php echo $cp['cp_commission']; ?>" id="cp_commission" class="readonly text-right" readonly size="3">%
            	/ 변경 수수료
            	<input type="text" name="it_commission" value="<?php echo ($it['it_commission'] != "")?$it['it_commission']:"-"; ?>" id="it_commission" class="readonly text-right" readonly size="3">%
            	
            	수수료 수정은 리탠다드에서만 가능합니다.
            </td>
        <tr>
            <th scope="row"><label for="it_name">상품명</label></th>
            <td colspan="3">
                <input type="text" name="it_name" value="<?php echo get_text(cut_str($it['it_name'], 80, "")); ?>" id="it_name" required maxlength="80"
                		class="frm_input required col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_name').text($(this).val().length);" onblur="$('#len_it_name').text($(this).val().length);">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_name"><?php echo strlen(get_text($it['it_name'])); ?></span> / 80</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">한줄 상품 설명</label></th>
            <td colspan="3">
                <input type="text" name="it_basic" value="<?php echo get_text($it['it_basic']); ?>" id="it_basic" maxlength="200"
                		class="frm_input col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_basic').text($(this).val().length);" onblur="$('#len_it_basic').text($(this).val().length);">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_basic"><?php echo strlen(get_text($it['it_basic'])); ?></span> / 200</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">검색어 설정</label></th>
            <td colspan="3">
                <input type="text" name="it_search_word" value="<?php echo get_text($it['it_search_word']); ?>" id="it_search_word" maxlength="200"
                		class="frm_input col-lg-10 col-md-10 col-sm-10 col-xs-12" onkeyup="$('#len_it_search_word').text($(this).val().length);" onblur="$('#len_it_search_word').text($(this).val().length);">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><span id="len_it_search_word"><?php echo strlen(get_text($it['it_search_word'])); ?></span> / 200</label>
        		<div class="clearfix"></div>                
                <?php echo help("- 검색어는 \",\" (콤마)로 구분해주세요 "); ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_order">출력순서</label></th>
            <td colspan="3">
                <?php echo help("숫자가 작을 수록 상위에 출력됩니다. 음수 입력도 가능하며 입력 가능 범위는 -2147483648 부터 2147483647 까지입니다.\n<b>입력하지 않으면 자동으로 출력됩니다.</b>"); ?>
                <input type="text" name="it_order" value="<?php echo $it['it_order']; ?>" id="it_order" class="frm_input" size="12">
            </td>
        </tr>
        
        </tbody>
        </table>
        
    </div>
	</div>
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 이미지 정보 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row"><label for="it_basic">대표이미지</label></th>
            <td>
            <div style="width:180px;position:relative;float:left;">
            <?php
            $i=1;
            
            $it_img = G5_DATA_PATH.'/item/'.$it['it_img'.$i];
            if(is_file($it_img) && $it['it_img'.$i]) {
                $size = @getimagesize($it_img);
                $thumb = get_it_thumbnail($it['it_img'.$i], 150, 150, 'imgimgFile'.$i);
        	?>
        	<span class="sit_wimg_limg<?php echo $i; ?>"><?php echo $thumb; ?></span>
            <?php 
            } else {
                echo '<img src="'.G5_ADMIN_URL.'/img/theme_img.jpg" class="img-thumbnail" id="imgimgFile'.$i.'" style="width: 150px; height: 150px;">';
            }
            ?>
            </div>
            
            <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="input-group" id="imgInputGroup<?php echo $i ?>">
    		        <span class="">
    		        	<div class="btn btn-info">
		        			<span><?php if(is_file($it_img) && $it['it_img'.$i]) echo '이미지 수정'; else echo '이미지 등록'; ?></span>
    		        		<input type="file" id="imgFile<?php echo $i ?>" name="it_img<?php echo $i ?>" class="hiddenFile" delBtnID="btnDelimgFile<?php echo $i ?>" imgID="imgimgFile<?php echo $i ?>" style="width:100px" accept=".jpg, .png" >
    		        	</div>
					</span>
					<input type="hidden" id="orgimgFile<?php echo $i ?>" name="orgit_img<?php echo $i ?>" value="<?php echo $it['it_img'.$i]; ?>" >
    		    </div>
	    
    		    <div class="col-md-12 col-lg-12 col-sm-12">
					<span class="red">* 업로드 이미지 사이즈 (480px * 480px) <br />
					* 최대 15MB / 확장자 jpg, png만 가능</span>
                </div>
    		</div>
            
            
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">추가이미지(<span id="">0</span>/4)</label></th>
            <td>
            <table>
            <tr>
        	<?php for($i=2; $i<=5; $i++) { ?>
        	<td style="width: 25%">
            <?php
            
            $it_img = G5_DATA_PATH.'/item/'.$it['it_img'.$i];
            if(is_file($it_img) && $it['it_img'.$i]) {
                $size = @getimagesize($it_img);
                $thumb = get_it_thumbnail($it['it_img'.$i], 150, 150, 'imgimgFile'.$i);
        	?>
        	<span class="sit_wimg_limg<?php echo $i; ?>"><?php echo $thumb; ?></span>
            <?php 
            } else {
                echo '<img src="'.G5_ADMIN_URL.'/img/theme_img.jpg" class="img-thumbnail" id="imgimgFile'.$i.'" style="width: 150px; height: 150px;">';
            }
            ?>
            </td>
            <?php } ?>
            </tr>
            
            <tr>
            <?php for($i=2; $i<=5; $i++) { ?>
            <td>
                <div class="input-group" id="imgInputGroup<?php echo $i ?>">
    		        <span class="">
    		        	<div class="btn btn-info">
		        			<span><?php if(is_file($it_img) && $it['it_img'.$i]) echo '이미지 수정'; else echo '이미지 등록'; ?></span>
    		        		<input type="file" id="imgFile<?php echo $i ?>" name="it_img<?php echo $i ?>" class="hiddenFile" delBtnID="btnDelimgFile<?php echo $i ?>" imgID="imgimgFile<?php echo $i ?>" style="width:100px" accept=".jpg, .png" >
    		        	</div>
					</span>
					<button class="btn btn-danger <?php if(!$it['it_img'.$i]) echo 'hidden'; ?>" type="button" id="btnDelimgFile<?php echo $i ?>" fileBtnID="imgFile<?php echo $i ?>"  >삭제</button>
					<input type="hidden" id="orgimgFile<?php echo $i ?>" name="orgit_img<?php echo $i ?>" value="<?php echo $it['it_img'.$i]; ?>" >
    		    </div>
    		</td>
        	<?php } ?>
        	</tr>
        	</table>
        	
		    <div class="col-md-12 col-lg-12 col-sm-12">
				<span class="red">* 업로드 이미지 사이즈 (480px * 480px) <br />
					* 최대 15MB / 확장자 jpg, png만 가능</span>
            </div>
            
            </td>
        </tr>
        </tbody>
        </table>
	</div>
	</div>

    <div class="x_title">
        <div style="float: left;"><h4><span class="fa fa-check-square"></span> 상품상세 정보 설정 <small></small></h4></div>
        <div style="float: right;">
        </div>
        <div class="clearfix"></div>
	</div> 
	
	<div class="x_content" id="lt_shop_item_sub">
		<?php
        if(!$w && $w != "u")
        {
            $its['its_discount_type'] = '0';
            $its['its_free_laundry'] = '1';
            $its['its_laundry_use'] = '0';
            $its['its_laundrykeep_use'] = '0';
            $its['its_repair_use'] = '0';
            
            $sql = " select 0 as its_discount_type, 1 as its_free_laundry, 0 as its_laundry_use, 0 as its_laundrykeep_use, 0 as its_repair_use";
            $result = sql_query($sql); 
        }
        else if ($w == "u")
        {
            $it['it_id'] = $it_id;
            
            $sql = " select * from lt_shop_item_sub where it_id = '$it_id' ";
            $its = sql_fetch($sql);
        } 
        if(!$s) $s = 0;
        
        ?>
        <div class="tbl_frm01 tbl_wrap" id="tbl_shop_item_sub<?php echo $s?>">
        	<input type="hidden" name="its_no[]" value="<?php echo $its['its_no']; ?>">
        	<input type="hidden" name="itscnt[]" id="itscnt<?php echo $s; ?>" value="<?php echo $s; ?>">
        	<input type="hidden" name="its_sap_code[]" value="<?php echo $its['its_sap_code']; ?>">
        	<input type="hidden" name="its_order_no[]" value="<?php echo $its['its_order_no']; ?>">
        	<input type="hidden" name="its_item[]" value="<?php echo $its['its_item']; ?>">
        	<input type="hidden" name="its_zbox[]" value="<?php echo $its['its_zbox_name'].','.$its['its_zbox_price']; ?>">
        	<input type="hidden" name="its_zbox_price[]" value="<?php echo $its['its_zbox_price']; ?>">
    		<table>
            <tbody>
            <tr>
                <th scope="row"><label for="it_skin">할인설정</label></th>
                <td colspan="5">                
                    <label><input type="radio" value="0" id="its_discount_type0<?php echo $s?>" name="its_discount_type<?php echo $s?>" <?php echo ($its['its_discount_type'] == '0')?'checked':''; ?>> 사용안함 </label>&nbsp;&nbsp;&nbsp;
                    
                    <label><input type="radio" value="1" id="its_discount_type1<?php echo $s?>" name="its_discount_type<?php echo $s?>" <?php echo ($its['its_discount_type'] == '1')?'checked':''; ?>> 할인율(%)</label>&nbsp;&nbsp;&nbsp;
                	<input type="text" name="its_discount1[]" value="<?php echo ($its['its_discount_type'] == '1')?$its['its_discount']:''; ?>" id="its_discount1<?php echo $s?>" class="frm_input text-right <?php echo ($its['its_discount_type'] == '1')?'':'readonly'; ?>" size="10" <?php echo ($its['its_discount_type'] == '1')?'':'readonly'; ?>>&nbsp;&nbsp;&nbsp;
                	
                	<label><input type="radio" value="2" id="its_discount_type2<?php echo $s?>" name="its_discount_type<?php echo $s?>" <?php echo ($its['its_discount_type'] == '2')?'checked':''; ?>> 할인가(원)</label>&nbsp;&nbsp;&nbsp;
                	<input type="text" name="its_discount2[]" value="<?php echo ($its['its_discount_type'] == '2')?$its['its_discount']:''; ?>" id="its_discount2<?php echo $s?>" class="frm_input text-right <?php echo ($its['its_discount_type'] == '2')?'':'readonly'; ?>" size="10" <?php echo ($its['its_discount_type'] == '2')?'':'readonly'; ?> >                
                </td>
    		</tr>
            <tr>
                <th scope="row"><label for="it_skin">판매가</label></th>
                <td>
                	<input type="text" name="its_price[]" value="<?php echo $its['its_price']; ?>" id="its_price<?php echo $s?>" required class="frm_input full_input text-right">
                </td>
                <th scope="row"><label for="it_skin">최종판매가</label></th>
                <td colspan="3">
                	<label></label>
                	<input type="text" name="its_final_price[]" value="<?php echo $its['its_final_price']; ?>" id="its_final_price<?php echo $s?>" required class="frm_input readonly text-right" readonly="readonly">
                </td>
    		</tr>
    		</tbody>
    		</table>
    		
    		<script>
    		 jQuery(function($) {
    			 var s = '<?php echo $s?>';
    			 
    			 $("#its_discount1"+s).autoNumeric('init', {mDec: '0', vMax:100});
    			 $("#its_discount2"+s).autoNumeric('init', {mDec: '0'});
    			 $("#its_price"+s).autoNumeric('init', {mDec: '0'});    
    			 $("#its_final_price"+s).autoNumeric('init', {mDec: '0'});    
    			 
    			 $("#its_zbox_price"+s).autoNumeric('init', {mDec: '0'});
    			 $("#its_zbox"+s).change(function(){
    				 var zbox = $(this).val();
    				 $("#its_zbox_price"+s).autoNumeric('set', zbox.split(",")[1]);
    			 });
    
    			$("#its_discount_type0"+s).click(function(){
    				$("#its_discount1"+s).val("");
    				$("#its_discount2"+s).val("");
    				
    				$("#its_discount1"+s).prop("readonly",true);
    				$("#its_discount1"+s).removeClass("readonly").addClass("readonly");
    				$("#its_discount2"+s).prop("readonly",true);
    				$("#its_discount2"+s).removeClass("readonly").addClass("readonly");
    				$.final_price_set<?php echo $s?>();				
    			 });
    			 $("#its_discount_type1"+s).click(function(){
    				$("#its_discount1"+s).val("");
    				$("#its_discount2"+s).val("");
    				
    				$("#its_discount1"+s).prop("readonly",false);
    				$("#its_discount1"+s).removeClass("readonly");
    				$("#its_discount2"+s).prop("readonly",true);
    				$("#its_discount2"+s).removeClass("readonly").addClass("readonly");
    				$.final_price_set<?php echo $s?>();
    
    				$("#its_discount1"+s).focus();
    			 });
    			$("#its_discount_type2"+s).click(function(){
    				$("#its_discount1"+s).val("");
    				$("#its_discount2"+s).val("");
    				
    				$("#its_discount1"+s).prop("readonly",true);
    				$("#its_discount1"+s).removeClass("readonly").addClass("readonly");
    				$("#its_discount2"+s).prop("readonly",false);
    				$("#its_discount2"+s).removeClass("readonly");
    				$.final_price_set<?php echo $s?>();
    
    				$("#its_discount2"+s).focus();
    			 });
    
    			 $.final_price_set<?php echo $s?> = function(){
    
    				if($("#its_discount1"+s).val() != "")
    				{
        				var final_price = $("#its_price"+s).autoNumeric('get') - ($("#its_price"+s).autoNumeric('get') / 100 * $("#its_discount1"+s).autoNumeric('get'));
        				$("#its_final_price"+s).autoNumeric('set', final_price);
    				} 
    				else if($("#its_discount2"+s).val() != "")
    				{
    					$("#its_discount2"+s).autoNumeric('update', {vMax: $("#its_price"+s).autoNumeric('get')});
    					
        				var final_price = $("#its_price"+s).autoNumeric('get') - $("#its_discount2"+s).autoNumeric('get');
        				$("#its_final_price"+s).autoNumeric('set', final_price);
    				} else {
        				$("#its_final_price"+s).val($("#its_price"+s).val());
        				//$("#it_price").val($("#its_final_price"+s).val());
    				}
    
    				var its_org_price = 0;
    				var its_final_price = 0;
    
    				$("input[name='its_price[]']").each(function() {
    					its_org_price += parseInt($(this).autoNumeric('get'));
                    });
                    
    				$("input[name='its_final_price[]']").each(function() {
    					its_final_price += parseInt($(this).autoNumeric('get'));
                    });
    				$("#it_price").autoNumeric('set', its_final_price);
    				$("#it_discount_price").autoNumeric('set', its_org_price-its_final_price);
    			 }
    
    			$("#its_discount1"+s+",#its_discount2"+s+",#its_price"+s).keyup(function(){
    				$.final_price_set<?php echo $s?>();
    			});
    			
    		  });
    		</script>
    		
        	<table>
            <tbody>
            <tr>
                <th scope="row">상품고정옵션</th>
                <td colspan="2">
                    <div class="sit_option tbl_frm01">
                        <?php echo help('상품고정옵션은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 옷을 예로 들어 [고정옵션명 : 사이즈 , 옵션 항목 : XXL,XL,L,M,S]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
                        <table>
                        <caption>상품고정옵션 입력</caption>
                        <colgroup>
                            <col class="grid_6">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row">
                                <label for="opt1_subject">고정옵션명</label>
                                <input type="text" name="it_option_subject[]" value="<?php echo $its['its_option_subject']; ?>" id="opt1_subject" class="frm_input" size="15">
                            </th>
                            <td>
                                <label for="opt1"><b>옵션 항목</b></label>
                                <input type="text" name="opt1" value="" id="opt1" class="frm_input" size="50">
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    
                    <div class="btn_confirm02 btn_confirm">
                        <button type="button" id="option_table_create">고정옵션목록생성</button>
                    </div>
                </div>
                <div id="sit_option_frm"><?php
                include_once(G5_ADMIN_PATH.'/shop_admin/itemoption.php'); 
                ?></div>
                        
                    <script>
                    $(function() {
                        <?php if($it['it_id'] && $po_run) { ?>
                        //옵션항목설정
                        var arr_opt1 = new Array();
                        var arr_opt2 = new Array();
                        var arr_opt3 = new Array();
                        var opt1 = opt2 = opt3 = '';
                        var opt_val;
    
                        $(".opt-cell").each(function() {
                            opt_val = $(this).text().split(" > ");
                            opt1 = opt_val[0];
                            opt2 = opt_val[1];
                            opt3 = opt_val[2];
    
                            if(opt1 && $.inArray(opt1, arr_opt1) == -1)
                                arr_opt1.push(opt1);
    
                            if(opt2 && $.inArray(opt2, arr_opt2) == -1)
                                arr_opt2.push(opt2);
    
                            if(opt3 && $.inArray(opt3, arr_opt3) == -1)
                                arr_opt3.push(opt3);
                        });
    
    
                        $("input[name=opt1]").val(arr_opt1.join());
                        $("input[name=opt2]").val(arr_opt2.join());
                        $("input[name=opt3]").val(arr_opt3.join());
                        <?php } ?>
                        // 옵션목록생성
                        $("#option_table_create").click(function() {
                            var it_id = $.trim($("input[name=it_id]").val());
                            var opt1_subject = $.trim($("#opt1_subject").val());
                            var opt2_subject = $.trim($("#opt2_subject").val());
                            var opt3_subject = $.trim($("#opt3_subject").val());
                            var opt1 = $.trim($("#opt1").val());
                            var opt2 = $.trim($("#opt2").val());
                            var opt3 = $.trim($("#opt3").val());
                            var $option_table = $("#sit_option_frm");
    
                            if(!opt1_subject || !opt1) {
                                alert("옵션명과 옵션항목을 입력해 주십시오.");
                                return false;
                            }
    
                            $.post(
                                "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemoption.php",
                                { it_id: it_id, w: "<?php echo $w; ?>", opt1_subject: opt1_subject, opt2_subject: opt2_subject, opt3_subject: opt3_subject, opt1: opt1, opt2: opt2, opt3: opt3 },
                                function(data) {
                                    $option_table.empty().html(data);
                                }
                            );
                        });
    
                        // 모두선택
                        $(document).on("click", "input[name=opt_chk_all]", function() {
                            if($(this).is(":checked")) {
                                $("input[name='opt_chk[]']").attr("checked", true);
                            } else {
                                $("input[name='opt_chk[]']").attr("checked", false);
                            }
                        });
    
                        // 선택삭제
                        $(document).on("click", "#sel_option_delete", function() {
                            var $el = $("input[name='opt_chk[]']:checked");
                            if($el.size() < 1) {
                                alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                                return false;
                            }
    
                            $el.closest("tr").remove();
                        });
    
                        // 일괄적용
                        $(document).on("click", "#opt_value_apply", function() {
                            if($(".opt_com_chk:checked").size() < 1) {
                                alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                                return false;
                            }
    
                            var opt_price = $.trim($("#opt_com_price").val());
                            var opt_stock = $.trim($("#opt_com_stock").val());
                            var opt_noti = $.trim($("#opt_com_noti").val());
                            var opt_use = $("#opt_com_use").val();
                            var $el = $("input[name='opt_chk[]']:checked");
    
                            // 체크된 옵션이 있으면 체크된 것만 적용
                            if($el.size() > 0) {
                                var $tr;
                                $el.each(function() {
                                    $tr = $(this).closest("tr");
    
                                    if($("#opt_com_price_chk").is(":checked"))
                                        $tr.find("input[name='opt_price[]']").val(opt_price);
    
                                    if($("#opt_com_stock_chk").is(":checked"))
                                        $tr.find("input[name='opt_stock_qty[]']").val(opt_stock);
    
                                    if($("#opt_com_noti_chk").is(":checked"))
                                        $tr.find("input[name='opt_noti_qty[]']").val(opt_noti);
    
                                    if($("#opt_com_use_chk").is(":checked"))
                                        $tr.find("select[name='opt_use[]']").val(opt_use);
                                });
                            } else {
                                if($("#opt_com_price_chk").is(":checked"))
                                    $("input[name='opt_price[]']").val(opt_price);
    
                                if($("#opt_com_stock_chk").is(":checked"))
                                    $("input[name='opt_stock_qty[]']").val(opt_stock);
    
                                if($("#opt_com_noti_chk").is(":checked"))
                                    $("input[name='opt_noti_qty[]']").val(opt_noti);
    
                                if($("#opt_com_use_chk").is(":checked"))
                                    $("select[name='opt_use[]']").val(opt_use);
                            }
                        });
                    });
                    </script>
                </td>
            </tr>
            
            
            <?php
            $spl_subject = explode(',', $its['its_supply_subject']);
            $spl_count = count($spl_subject);
            ?>
            <tr>
                <th scope="row">상품추가옵션</th>
                <td colspan="2">
                    <div id="sit_supply_frm<?php echo $s?>" class="sit_option tbl_frm01">
                        <?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 스마트폰을 예로 들어 [추가1 : 추가구성상품 , 추가1 항목 : 액정보호필름,케이스,충전기]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
                        <table>
                        <caption>상품추가옵션 입력</caption>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                        <?php
                        $i = 0;
                        do {
                            $seq = $i + 1;
                        ?>
                        <tr>
                            <th scope="row">
                                <label for="spl_subject_<?php echo $seq; ?><?php echo $s?>">추가<?php echo $seq; ?></label>
                                <input type="text" name="spl_subject<?php echo $s?>[]" id="spl_subject_<?php echo $seq; ?><?php echo $s?>" value="<?php echo $spl_subject[$i]; ?>" class="frm_input" size="15">
                            </th>
                            <td>
                                <label for="spl_item_<?php echo $seq; ?><?php echo $s?>"><b>추가<?php echo $seq; ?> 항목</b></label>
                                <input type="text" name="spl<?php echo $s?>[]" id="spl_item_<?php echo $seq; ?><?php echo $s?>" value="" class="frm_input" size="40">
                                <?php
                                if($i > 0)
                                    echo '<button type="button" id="del_supply_row" class="btn_frmline">삭제</button>';
                                ?>
                            </td>
                        </tr>
                        <?php
                            $i++;
                        } while($i < $spl_count);
                        ?>
                        </tbody>
                        </table>
                        <div id="sit_option_addfrm_btn"><button type="button" id="add_supply_row<?php echo $s?>" class="btn_frmline">옵션추가</button></div>
                        <div class="btn_confirm02 btn_confirm">
                            <button type="button" id="supply_table_create<?php echo $s?>">옵션목록생성</button>
                        </div>
                        
                        <script>
                        $(function() {
               			 	var s = '<?php echo $s?>';
               			 
                            <?php if($it['it_id']) { ?>
                            // 추가옵션의 항목 설정
                            var arr_subj = new Array();
                            var subj, spl;
        
                            $("input[name='spl_subject"+s+"[]']").each(function() {
                                subj = $.trim($(this).val());
                                if(subj && $.inArray(subj, arr_subj) == -1)
                                    arr_subj.push(subj);
                            });
        
                            for(i=0; i<arr_subj.length; i++) {
                                var arr_spl = new Array();
                                $(".spl-subject-cell").each(function(index) {
                                    subj = $(this).text();
                                    if(subj == arr_subj[i]) {
                                        spl = $(".spl-cell:eq("+index+")").text();
                                        arr_spl.push(spl);
                                    }
                                });
        
                                $("input[name='spl"+s+"[]']:eq("+i+")").val(arr_spl.join());
                            }
                            <?php } ?>
                            // 입력필드추가
                            $("#add_supply_row"+s).click(function() {
                                var $el = $("#sit_supply_frm"+s+" tr:last");
                                var fld = "<tr>\n";
                                fld += "<th scope=\"row\">\n";
                                fld += "<label for=\"\">추가</label>\n";
                                fld += "<input type=\"text\" name=\"spl_subject"+s+"[]\" value=\"\" class=\"frm_input\" size=\"15\">\n";
                                fld += "</th>\n";
                                fld += "<td>\n";
                                fld += "<label for=\"\"><b>추가 항목</b></label>\n";
                                fld += "<input type=\"text\" name=\"spl"+s+"[]\" value=\"\" class=\"frm_input\" size=\"40\">\n";
                                fld += "<button type=\"button\" id=\"del_supply_row\" class=\"btn_frmline\">삭제</button>\n";
                                fld += "</td>\n";
                                fld += "</tr>";
        
                                $el.after(fld);
        
                                supply_sequence(s);
                            });
        
                            // 입력필드삭제
                            $(document).on("click", "#del_supply_row", function() {
                                $(this).closest("tr").remove();
        
                                supply_sequence(s);
                            });
        
                            // 옵션목록생성
                            $("#supply_table_create"+s).click(function() {
                                var it_id = $.trim($("input[name=it_id]").val());
                                var subject = new Array();
                                var supply = new Array();
                                var subj, spl;
                                var count = 0;
                                var $el_subj = $("input[name='spl_subject"+s+"[]']");
                                var $el_spl = $("input[name='spl"+s+"[]']");
                                var $supply_table = $("#sit_option_addfrm"+s);
        
                                $el_subj.each(function(index) {
                                    subj = $.trim($(this).val());
                                    spl = $.trim($el_spl.eq(index).val());
        
                                    if(subj && spl) {
                                        subject.push(subj);
                                        supply.push(spl);
                                        count++;
                                    }
                                });
        
                                if(!count) {
                                    alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
                                    return false;
                                }
        
                                $.post(
                                    "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsupply.php",
                                    { w: "<?php echo $w; ?>", 'subject[]': subject, 'supply[]': supply, subID: '<?php echo $s?>' },
                                    function(data) {
                                        $supply_table.empty().html(data);
                                    }
                                );
                            });
        
                            // 모두선택
                            $(document).on("click", "input[name=spl_chk_all]", function() {
                                if($(this).is(":checked")) {
                                    $("input[name='spl_chk[]']").attr("checked", true);
                                } else {
                                    $("input[name='spl_chk[]']").attr("checked", false);
                                }
                            });
        
                            // 선택삭제
                            $(document).on("click", "#sel_supply_delete", function() {
                                var $el = $("input[name='spl_chk[]']:checked");
                                if($el.size() < 1) {
                                    alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                                    return false;
                                }
        
                                $el.closest("tr").remove();
                            });
        
                            function supply_sequence(subid)
                            {
                                var $tr = $("#sit_supply_frm"+subid+" tr");
                                var seq;
                                var th_label, td_label;
        
                                $tr.each(function(index) {
                                    seq = index + 1;
                                    $(this).find("th label").attr("for", "spl_subject_"+seq+""+subid).text("추가"+seq);
                                    $(this).find("th input").attr("id", "spl_subject_"+seq+""+subid);
                                    $(this).find("td label").attr("for", "spl_item_"+seq+""+subid);
                                    $(this).find("td label b").text("추가"+seq+" 항목");
                                    $(this).find("td input").attr("id", "spl_item_"+seq+""+subid);
                                });
                            }
        
                        });
                        </script>
                    </div>
    
                    <div id="sit_option_addfrm<?php echo $s?>"></div>
                	<script>
                	$.post(
                            "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsupply.php",
                            { w: "<?php echo $w; ?>", it_id: '<?php echo $its['it_id']; ?>', its_no: '<?php echo $its['its_no']; ?>', subID: '<?php echo $s?>'},
                            function(data) {
                            	$("#sit_option_addfrm<?php echo $s?>").empty().html(data);
                            }
                        );
                	</script>
    
                </td>
            </tr>
            </tbody>
            </table>
    		
		</div>
    </div>
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 혜택 / 기능 / 표시 정보 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row"><label for="it_basic">총합계 :<br/>최종 판매가</label></th>
            <td>
            	<div style="float: left"><input type="text" name="it_price" value="<?php echo $it['it_price']; ?>" id="it_price" class="frm_input text-right readonly" readonly="readonly"></div>
                <div style="float: left;padding-left: 20px; padding-top: 4px;"><?php echo help("※ 1개등록시 최종판매가 노출, 세트구성시 상품별 최종판매가의 합계금액이 노출됩니다. "); ?></div>
            </td>
        </tr>
        <tr <?php if($it['it_item_type'] == '0') echo "hidden"; ?>>
            <th scope="row"><label for="it_basic">총합계 :<br/>최종월리스료</label></th>
            <td>
            	<div style="float: left"><input type="text" name="it_rental_price" value="<?php echo $it['it_rental_price']; ?>" id="it_rental_price" class="frm_input text-right readonly" readonly="readonly"></div>
                <div style="float: left;padding-left: 20px; padding-top: 4px;">총 납부금: <strong id="it_final_rental_price">0</strong>원</div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">총할인금액</label></th>
            <td>
            	<div style="float: left"><input type="text" name="it_discount_price" value="<?php echo $it['it_discount_price']; ?>" id="it_discount_price" class="frm_input text-right readonly" readonly="readonly"></div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">판매기간</label></th>
            <td>
				<div style="float: left">
					<input type='text' class="frm_input" id="it_period" name="it_period" <?php echo ($it['it_period'] == '')?'disabled':''; ?> value="<?php echo $it['it_period'] ?>" />
				</div>
				<div style="float: left;padding-left: 20px;padding-top: 4px;">
					<label><input type="checkbox" name="it_period_chk" value="1" id="it_period_chk" <?php echo ($it['it_period'] == '')?'checked':''; ?>>미설정</label>&nbsp;&nbsp;
				</div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">구매회원구분</label></th>
            <td>
                <label><input type="radio" value="0" id="it_level_sell0" name="it_level_sell" <?php echo ($it['it_level_sell'] == '0')?'checked':''; ?>> 전체</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="2" id="it_level_sell2" name="it_level_sell" <?php echo ($it['it_level_sell'] == '2')?'checked':''; ?>> 회원</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="1" id="it_level_sell1" name="it_level_sell" <?php echo ($it['it_level_sell'] == '1')?'checked':''; ?>> 비회원</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">주문수량제한</label></th>
            <td>
            	<div style="float: left; padding-top: 4px;">
                    <label><input type="radio" value="0" id="it_buy_max_qty0" name="rdo_it_buy_max_qty" <?php echo ($it['it_buy_max_qty'] == '0')?'checked':''; ?>> 제한없음</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="it_buy_max_qty1" name="rdo_it_buy_max_qty" <?php echo ($it['it_buy_max_qty'] != '0')?'checked':''; ?>> 갯수설정</label>&nbsp;&nbsp;&nbsp;
                </div>
            	<div style="float: left">
            		<input type="text" name="it_buy_max_qty" value="<?php echo $it['it_buy_max_qty']; ?>" id="it_buy_max_qty" class="frm_input">
            		<label>개 ( 설정값 이하 구매 가능 )</label>
            	</div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">구매시 적립금 설정</label></th>
            <td>
            	<div style="float: left; padding-top: 4px;">
            		<label><input type="radio" value="9" id="it_buy_max_qty9" name="it_point_type" <?php echo ($it['it_point_type'] == '9')?'checked':''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="3" id="it_buy_max_qty3" name="it_point_type" <?php echo ($it['it_point_type'] == '3')?'checked':''; ?>> 기본사용 (구매액의 <?php echo $default['de_point_percent'] ?>%)</label>&nbsp;&nbsp;&nbsp;                    
                    <!-- 
                    <label><input type="radio" value="2" id="it_buy_max_qty2" name="it_point_type" <?php echo ($it['it_point_type'] == '2')?'checked':''; ?>> 적립율(%)</label>&nbsp;&nbsp;&nbsp; -->
                </div>
                <!-- <div style="float: left"><input type="text" name="it_point2" value="<?php echo $it['it_point']; ?>" id="it_point2" class="frm_input">&nbsp;&nbsp;&nbsp;</div>
            	<div style="float: left; padding-top: 4px;">
                	<label><input type="radio" value="0" id="it_buy_max_qty0" name="it_point_type" <?php echo ($it['it_point_type'] == '0')?'checked':''; ?>> 적립금액 (원)</label>&nbsp;&nbsp;&nbsp;
                </div>
                <div style="float: left"><input type="text" name="it_point0" value="<?php echo $it['it_point']; ?>" id="it_point0" class="frm_input"></div> -->
            </td>
        </tr>
        <tr hidden>
            <th scope="row"><label for="it_basic">적립금 전용결제</label></th>
            <td>
                <label><input type="radio" value="0" id="it_point_only0" name="it_point_only" <?php echo ($it['it_point_only'] == '0')?'checked':''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="1" id="it_point_only1" name="it_point_only" <?php echo ($it['it_point_only'] == '1')?'checked':''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr hidden>
            <th scope="row"><label for="it_basic">쿠폰 사용</label></th>
            <td>
                <label><input type="radio" value="0" id="it_nocoupon0" name="it_nocoupon" <?php echo ($it['it_nocoupon'] == '0')?'checked':''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="1" id="it_nocoupon1" name="it_nocoupon" <?php echo ($it['it_nocoupon'] == '1')?'checked':''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr hidden>
            <th scope="row"><label for="it_basic">적립금 사용</label></th>
            <td>
                <label><input type="radio" value="0" id="it_point_use0" name="it_point_use" <?php echo ($it['it_point_use'] == '0')?'checked':''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="1" id="it_point_use1" name="it_point_use" <?php echo ($it['it_point_use'] == '1')?'checked':''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr hidden>
            <th scope="row"><label for="it_basic">후기 쓰기</label></th>
            <td>
                <label><input type="radio" value="0" id="it_use_use0" name="it_use_use" <?php echo ($it['it_use_use'] == '0')?'checked':''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="1" id="it_use_use1" name="it_use_use" <?php echo ($it['it_use_use'] == '1')?'checked':''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr hidden>
            <th scope="row"><label for="it_basic">제품 문의</label></th>
            <td>
                <label><input type="radio" value="0" id="it_review_use0" name="it_review_use" <?php echo ($it['it_review_use'] == '0')?'checked':''; ?>> 사용안함</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" value="1" id="it_review_use1" name="it_review_use" <?php echo ($it['it_review_use'] == '1')?'checked':''; ?>> 사용함</label>&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
				<tr>
					<th scope="row" rowspan="2"><label for="it_basic">리스트 표시 설정</label></th>
					<td>
						<div style="float: left; padding-top: 4px;">
							<label><input type="radio" value="0" id="it_view_list_items0" name="rdo_it_view_list_items" <?php echo ($it['it_view_list_items'] == '')?'checked':''; ?>> 전체설정</label>&nbsp;&nbsp;&nbsp;
							<label><input type="radio" value="1" id="it_view_list_items1" name="rdo_it_view_list_items" <?php echo ($it['it_view_list_items'] != '')?'checked':''; ?>> 선택설정</label>&nbsp;&nbsp;&nbsp;
						</div>
						<div style="float: left;padding-left: 20px; padding-top: 4px;"><?php echo help("※ 선택 설정시 표시 할 항목설정이 가능합니다."); ?></div>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" value="상품명" checked="checked" disabled> 제품명</label>&nbsp;&nbsp;
					<label><input type="checkbox" value="최종 판매가" checked="checked" disabled> 최종 판매가</label>&nbsp;&nbsp;
						<?php
						//$it_view_list_items_arr = explode(",", "할인블릿,이벤트블릿,인기블릿,신상품,쿠폰,좋아요,공유하기,리뷰수,상품명,한줄설명,할인전금액,할인가/할인율,판매가,최종 판매가");
						$it_view_list_items_arr = explode(",", "신상품,한줄설명,좋아요");

						for ($li = 0; $li < count($it_view_list_items_arr); $li++) {
							?>

							<label><input type="checkbox" name="it_view_list_items[]" value="<?php echo $it_view_list_items_arr[$li]?>" id="it_view_list_items<?php echo $li?>" <?php echo option_array_checked($it_view_list_items_arr[$li] , $it['it_view_list_items']); ?> <?php echo ($it['it_view_list_items'] != '')?'':'disabled'; ?>> <?php echo $it_view_list_items_arr[$li] ?></label>&nbsp;&nbsp;

						<?php } ?>
						<input type="hidden" name="it_view_list_items[]" value="상품명" >
						<input type="hidden" name="it_view_list_items[]" value="최종 판매가" >
					</td>
				</tr>

				<tr>
					<th scope="row" rowspan="2"><label for="it_basic">상세 표시 설정</label></th>
					<td>
						<div style="float: left; padding-top: 4px;">
							<label><input type="radio" value="0" id="it_view_detail_items0" name="rdo_it_view_detail_items" <?php echo ($it['it_view_detail_items'] == '')?'checked':''; ?>> 전체설정</label>&nbsp;&nbsp;&nbsp;
							<label><input type="radio" value="1" id="it_view_detail_items1" name="rdo_it_view_detail_items" <?php echo ($it['it_view_detail_items'] != '')?'checked':''; ?>> 선택설정</label>&nbsp;&nbsp;&nbsp;
						</div>
						<div style="float: left;padding-left: 20px; padding-top: 4px;"><?php echo help("※ 선택 설정시 표시 할 항목설정이 가능합니다."); ?></div>
					</td>
				</tr>
				<tr>
					<td>
					<label><input type="checkbox" value="상품명" checked="checked" disabled> 제품명</label>&nbsp;&nbsp;
					<label><input type="checkbox" value="최종 판매가" checked="checked" disabled> 최종 판매가</label>&nbsp;&nbsp;
					
						<?php
						$it_view_list_items_arr = explode(",", "할인블릿,이벤트블릿,인기블릿,좋아요,공유하기,한줄설명,할인전금액");
						
						for ($li = 0; $li < count($it_view_list_items_arr); $li++) {
							if($li == 3) echo "<br/>";
							?>

							<label><input type="checkbox" name="it_view_detail_items[]" value="<?php echo $it_view_list_items_arr[$li]?>" id="it_view_detail_items<?php echo $li?>" <?php echo option_array_checked($it_view_list_items_arr[$li] , $it['it_view_detail_items']); ?> <?php echo ($it['it_view_detail_items'] != '')?'':'disabled'; ?>> <?php echo $it_view_list_items_arr[$li] ?></label>&nbsp;&nbsp;

						<?php } ?>
						<input type="hidden" name="it_view_detail_items[]" value="상품명" >
						<input type="hidden" name="it_view_detail_items[]" value="최종 판매가" >
					</td>
				</tr>

			</tbody>
		</table>
		<script>
			$(function() {
				$("input[name='rdo_it_view_list_items']").click(function(){

					$("input[name='it_view_list_items[]']").prop("checked", false);
					
					var chk = ($(this).val() == "0");
					$("input[name='it_view_list_items[]']").prop("disabled", chk);
				});

				$("input[name='rdo_it_view_detail_items']").click(function(){

					$("input[name='it_view_detail_items[]']").prop("checked", false);
					
					var chk = ($(this).val() == "0");
					$("input[name='it_view_detail_items[]']").prop("disabled", chk);

				});
			});
		</script>

        
    </div>
	</div>
	
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 배송 정보 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
            <tr>
                <th scope="row"><label for="it_sc_type">배송정보</label></th>
                <td>
                	<div class="radio">
					<?php
					$it_sc_type_readonly = "";
					if($it['it_sc_type'] == '0') $it_sc_type_readonly = "disabled";
					?>
					<label><input type="radio" value="0" id="it_sc_type0" name="it_sc_type" <?php echo ($it['it_sc_type'] == '0')?'checked':''; ?>> 기본설정 사용</label>&nbsp;&nbsp;&nbsp;
					<label><input type="radio" value="2" id="it_sc_type2" name="it_sc_type" <?php echo ($it['it_sc_type'] == '2')?'checked':''; ?>> 선택설정</label>&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
            
    		<tr>
                <th scope="row"><label>배송방법</label></th>
				<td>
        			<div class="col-md-12 col-sm-12 col-xs-12">
        				<select name="it_send_type" id="it_send_type" <?php echo $it_sc_type_readonly ?>>				
                            <option value="택배" <?php echo get_selected($it['it_send_type'], '택배') ; ?>>택배</option>
                            <option value="빠른등기" <?php echo get_selected($it['it_send_type'], '빠른등기') ; ?>>빠른등기</option>
                            <option value="기타" <?php echo get_selected($it['it_send_type'], '기타') ; ?>>기타</option>
                        </select>
                    </div>
           		</td>
            </tr>
	  
		  <tr>
                <th scope="row"><label>배송기간</label></th>
				<td>
        			<div class="col-md-1 col-sm-1 col-xs-1">
                        <input type="text" name="it_send_term_start"  value="<?php echo $it['it_send_term_start']; ?>" id="it_send_term_start" class="form-control" <?php echo $it_sc_type_readonly ?>>
                    </div>
                    <label class="col-md-1 col-sm-1 col-xs-1" style="padding-top:8px;">일 ~</label>
        			<div class="col-md-1 col-sm-1 col-xs-1">
                        <input type="text" name="it_send_term_end"  value="<?php echo $it['it_send_term_end']; ?>" id="it_send_term_end" class="form-control" <?php echo $it_sc_type_readonly ?>>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9" style="padding-top:8px;">
                    	<label class="control-label">일 정도 소요됩니다.</label>
                    </div>
           		</td>
            </tr>
		  
		  <tr id="dvCostCase1">
                <th scope="row"><label>기본 배송비 설정</label></th>
				<td>
        			<div class="col-md-1 col-sm-1 col-xs-1">
                        <input type="text" name="it_sc_minimum"  value="<?php echo $it['it_sc_minimum']; ?>" id="it_sc_minimum" class="form-control" <?php echo $it_sc_type_readonly ?>>
                    </div>
                    <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원 미만일 때 배송비</label>
        			<div class="col-md-1 col-sm-1 col-xs-1">
                        <input type="text" name="it_sc_price"  value="<?php echo $it['it_sc_price']; ?>" id="it_sc_price" class="form-control" <?php echo $it_sc_type_readonly ?>>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-top:8px;">
                    	<label class="control-label">원을 부과합니다.</label>
                    </div>
           		</td>
            </tr>
	  
		  <tr>
                <th scope="row"><label>배송료 청구기준<br/>주문금액 조건설정</label></th>
				<td>
    				<div class="radio">
    					<label ><input type="radio" name="it_send_condition" id="it_send_condition0" value="판매"  <?php echo option_array_checked('판매', $it['it_send_condition']); ?> <?php echo $it_sc_type_readonly ?> /> 할인전, 정상판매가격 기준(권장)</label>&nbsp;&nbsp;&nbsp;
                        <label ><input type="radio" name="it_send_condition" id="it_send_condition1" value="최종" <?php echo option_array_checked('최종', $it['it_send_condition']); ?> <?php echo $it_sc_type_readonly ?> /> 최종 주문(결제)금액 기준</label>
    				</div>
           		</td>
            </tr>
	  
		  <tr>
                <th scope="row"><label>배송비 선결제 설정</label></th>
				<td>
    				<div class="radio">
							<label><input type="radio" name="it_sc_method" id="it_sc_method0" value="0" checked="checked" /> 선결제</label>&nbsp;&nbsp;&nbsp;
						<!-- label><input type="radio" name="it_sc_method" id="it_sc_method1" value="1"  /> 착불</label>&nbsp;&nbsp;&nbsp;
							<label><input type="radio" name="it_sc_method" id="it_sc_method2" value="2" /> 착불/선결제</label> -->
    				</div>
           		</td>
            </tr>

		  <!-- tr>
				<th scope="row"><label>상품별 개별배송비 설정</label></th>
				<td>
					<div class="radio">
						<label><input type="radio" name="it_individual_costs_use" id="it_individual_costs_use0" value="0"  <?php echo option_array_checked('0', $it['it_individual_costs_use']); ?> /> 사용안함</label>&nbsp;&nbsp;&nbsp;
						<label><input type="radio" name="it_individual_costs_use" id="it_individual_costs_use1" value="1" <?php echo option_array_checked('1', $it['it_individual_costs_use']); ?>/> 사용함</label>
					</div>
				</td>
			</tr -->
            
            <tr>
                <th scope="row"><label>반품/교환 택배사</label></th>
				<td>
    			<div class="col-md-12 col-sm-12 col-xs-12">
                    <select name="it_delivery_company" id="it_delivery_company" <?php echo $it_sc_type_readonly ?>>
                        <?php echo get_delivery_company($it['it_delivery_company']); ?>
                    </select>
               	</div>
           		</td>
            </tr>
	  
		  <tr>
                <th scope="row"><label>반품배송비(편도)</label></th>
				<td>
        			<div class="col-md-2 col-sm-2 col-xs-10">
                <input type="text" name="it_return_costs"  value="<?php echo $it['it_return_costs']; ?>" id="it_return_costs" class="form-control" <?php echo $it_sc_type_readonly ?>>
                    </div>
                    <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
           		</td>
            </tr>
	  
		  <!-- <tr>
                <th scope="row"><label>교환배송비(왕복)</label></th>
				<td>
			<div class="col-md-2 col-sm-2 col-xs-10">
                <input type="text" name="it_roundtrip_costs"  value="<?php echo $it['it_roundtrip_costs']; ?>" id="it_roundtrip_costs" class="form-control" >
            </div>
            <label class="col-md-2 col-sm-2 col-xs-2" style="padding-top:8px;">원</label>
           		</td>
            </tr> -->
		  
		  <tr>
                <th scope="row"><label>반품 주소 설정</label></th>
				<td>
        			<div class="col-md-9 col-sm-9 col-xs-12">
        				<div class="input-group col-sm-4 col-sm-4">
        					<input type="text" name="it_return_zip" value="<?php echo $it['it_return_zip']; ?>" id="it_return_zip" class="form-control col-md-6 col-xs-6"  size="5" maxlength="6" <?php echo $it_sc_type_readonly ?>>
        					
							<span class="input-group-btn <?php if($it_sc_type_readonly != "") echo "hidden"; ?>" id="btnZip" >
        						&nbsp;<button type="button" class="btn btn-primary" onclick="win_zip('fitemform', 'it_return_zip', 'it_return_address1', 'it_return_address2', 'it_return_address3', 'it_return_address_jibeon');">주소검색</button>
        					</span>
        				</div>
        
        				<div class="input-group col-sm-9 col-sm-9">
        				<input type="text" name="it_return_address1" value="<?php echo $it['it_return_address1']; ?>" id="it_return_address1" class="form-control col-md-12 col-xs-12" size="30" <?php echo $it_sc_type_readonly ?>>
        				</div>
        				
        				<div class="input-group col-sm-9 col-sm-9">
        				<input type="text" name="it_return_address2" value="<?php echo $it['it_return_address2']; ?>" id="it_return_address2" class="form-control col-md-12 col-xs-12" size="30" <?php echo $it_sc_type_readonly ?>>
        				</div>
        				<input type="hidden" name="it_return_address3" value="" id="it_return_address3">
        				<input type="hidden" name="it_return_address_jibeon" value="" id="it_return_address_jibeon">
        
        			</div>
           		</td>
            </tr>
    
        </tbody>
        </table>
    </div>
    </div>

<script>
	$(function() {
		$("#it_sc_type0,#it_sc_type2").change(function() {
			var type = $(this).val();

			switch(type) {
				case "2":
				$("#it_send_type").prop("disabled",false);
				$("#it_send_term_start").prop("disabled",false);
				$("#it_send_term_end").prop("disabled",false);
				$("#it_sc_minimum").prop("disabled",false);
				$("#it_sc_price").prop("disabled",false);
				$("#it_send_condition0").prop("disabled",false);
				$("#it_send_condition1").prop("disabled",false);
				$("#it_sc_method0").prop("disabled",false);
				$("#it_delivery_company").prop("disabled",false);
				
				$("#it_return_costs").prop("disabled",false);
				$("#it_roundtrip_costs").prop("disabled",false);
				$("#it_return_zip").prop("disabled",false);
				$("#it_return_address1").prop("disabled",false);
				$("#it_return_address2").prop("disabled",false);


				$("#btnZip").removeClass("hidden");

				break;
				default:
				$("#it_send_type").val("<?php echo $cp['cp_send_type'] ?>");
				$("#it_send_term_start").val("<?php echo $cp['cp_send_term_start'] ?>");
				$("#it_send_term_end").val("<?php echo $cp['cp_send_term_end'] ?>");
				$("#it_sc_minimum").val("<?php echo $cp['cp_send_cost_limit'] ?>");
				$("#it_sc_price").val("<?php echo $cp['cp_send_cost_list'] ?>");

				$("input:radio[name='it_send_condition']:input[value='<?php echo $cp['cp_send_condition'] ?>']").click();

				$("#it_delivery_company").val("<?php echo $cp['cp_delivery_company'] ?>");
				$("#it_return_costs").val("<?php echo $cp['cp_return_costs'] ?>");
				$("#it_roundtrip_costs").val("<?php echo $cp['cp_roundtrip_costs'] ?>");
				$("#it_return_zip").val("<?php echo $cp['cp_return_zip'] ?>");
				$("#it_return_address1").val("<?php echo $cp['cp_return_address1'] ?>");
				$("#it_return_address2").val("<?php echo $cp['cp_return_address2'] ?>");
				$("#it_return_address3").val("");
				$("#it_return_address_jibeon").val("");


				$("#it_send_type").prop("disabled",true);
				$("#it_send_term_start").prop("disabled",true);
				$("#it_send_term_end").prop("disabled",true);
				$("#it_sc_minimum").prop("disabled",true);
				$("#it_sc_price").prop("disabled",true);
				$("#it_send_condition0").prop("disabled",true);
				$("#it_send_condition1").prop("disabled",true);
				$("#it_sc_method0").prop("disabled",true);
				$("#it_delivery_company").prop("disabled",true);

				$("#it_return_costs").prop("disabled",true);
				$("#it_roundtrip_costs").prop("disabled",true);
				$("#it_return_zip").prop("disabled",true);
				$("#it_return_address1").prop("disabled",true);
				$("#it_return_address2").prop("disabled",true);
				$("#btnZip").removeClass('hidden').addClass('hidden');
				break;
			}
		});
	});
</script>
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 내게맞는 상품 찾기 정보 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <?php 
        $sql = " select * from lt_shop_finditem where fi_status = 'Y' ";
        $result = sql_query($sql);
        
        while ($row=sql_fetch_array($result)) {
        ?>
            <tr>
                <th scope="row">
                	<?php echo $row['fi_subject'] ?>
                	<input type="hidden" name="fi_subject[]" value="<?php echo $row['fi_subject'] ?>" >
                	<input type="hidden" name="fi_id[]" value="<?php echo $row['fi_id'] ?>" >
                </th>
                <td colspan="2"><?php 
                $fi_contents = explode(",",$row['fi_contents']);
                $l=0;
                foreach($fi_contents as $key => $val) {
                    echo '<label><input type="checkbox" name="fi_contents_'.$row['fi_id'].'[]" value="'.$val.'" id="fi_contents_'.$row['fi_id'].'_'.$l.'">'.$val.'</label>&nbsp;&nbsp;&nbsp;';
                    $l++;
                }
                
                ?></td>
            </tr>
        <?php } ?>
        </tbody>
       	</table>
       	<script>
        $(function() {
       	<?php
       	if($it['it_id'] && $it['it_info_finditem'] != '')
       	{
       	    $it_info_finditem = json_decode($it['it_info_finditem'], true);
       	    foreach ($it_info_finditem as $key => $value) {
       	        
       	        if(isset($value['fi_contents']) && $value['fi_contents'] != "" && !is_array($value['fi_contents'])) {
       	            $fi_contents = explode(",",$value['fi_contents']);
       	        
           	        foreach ($fi_contents as $key2 => $value2) {       	        
           	            echo "$(\"input[name='fi_contents_".$value['fi_id']."[]'][value='".$value2."']\").prop(\"checked\",true);";
           	        }
       	        }
       	    }
       	}
       	?>
        });
       	</script>
	</div>
	</div>
	
	
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 제품 설명<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row">제품설명<br/>(PC)</th>
                <td colspan="2"> <?php echo editor_html('it_explan', get_text($it['it_explan'], 0)); ?>
                    <span class="red">※ 업로드 이미지 권장 사이즈 (960px * @) <br>※ 최대 15MB / 확장자 jpg, png만 가능</span>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="2">제품설명<br/>(모바일)</th>
                <td colspan="2">
    				<div class="radio">
    					<label><input type="radio" name="it_mobile_explan_use" id="it_mobile_explan_use0" value="0"  <?php echo option_array_checked('0', $it['it_mobile_explan_use']); ?> /> 사용안함</label>&nbsp;&nbsp;&nbsp;
                        <label><input type="radio" name="it_mobile_explan_use" id="it_mobile_explan_use1" value="1" <?php echo option_array_checked('1', $it['it_mobile_explan_use']); ?>/> 사용함</label>
    				</div>
    			</td>
            </tr>
            <tr>
                <td colspan="2"> <?php echo editor_html('it_mobile_explan', get_text($it['it_mobile_explan'], 0)); ?>
                    <span class="red">※ 업로드 이미지 권장 사이즈 (300px * @) <br>※ 최대 15MB / 확장자 jpg, png만 가능</span>
                </td>
            </tr>
        </tbody>
		</table>
	</div>
	</div>
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 제품 상세 정보 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row">제품분류</th>
    			<td colspan="3">
    			
                <?php 
                $sel_ca_name = "";
                $sel_ca_name1 = "";
                $sel_ca_name2 = "";
                $sel_ca_name3 = "";
                $sel_ca_name4 = "";
                
                
                if($it['it_id'] && $it['it_info_gubun']) {
                    $sql = "select * from lt_shop_info where if_id = '".$it['it_info_gubun']."'";
                    $row = sql_fetch($sql);
                    $sel_ca_name1 = $row['ca_name1'];
                    $sel_ca_name2 = $row['ca_name2'];
                    $sel_ca_name3 = $row['ca_name3'];
                    $sel_ca_name4 = $row['ca_name4'];
                    
                    $sel_ca_name = $row['ca_name1'];
                    if($row['ca_name2']) $sel_ca_name .= ' > '.$row['ca_name2'];
                    if($row['ca_name3']) $sel_ca_name .= ' > '.$row['ca_name3'];
                    if($row['ca_name4']) $sel_ca_name .= ' > '.$row['ca_name4'];
                }
                ?>
                	<select name="sel_ca_name1" id="sel_ca_name1" class="" target="ca_name1" next="sel_ca_name2">
    				<option value="">----선택-----</option>
                    	<?php 
            			$sql = "select ca_name1 from lt_shop_info group by ca_name1";
            			$result = sql_query($sql);
            			for ($i=0; $row=sql_fetch_array($result); $i++)
            			{
            			    echo '<option value="'.$row['ca_name1'].'" '.get_selected($row['ca_name1'], $sel_ca_name1).'>'.$row['ca_name1'].'</option>';
            			}
                    	?>
                    </select>
    				<select name="sel_ca_name2" id="sel_ca_name2" class="" target="ca_name2" next="sel_ca_name3">
                    	<option value="">----선택-----</option>
                    	<?php 
                    	if($it['it_id'] && $it['it_info_gubun']) {
                    	    $sql = "select ca_name2 as ca_name from lt_shop_info where if_id = '".$it['it_info_gubun']."' and ca_name1 = '".$sel_ca_name1."' group by ca_name2";
                			$result = sql_query($sql);
                			while ($row=sql_fetch_array($result))
                			{
                			    if($row['ca_name'] != "") echo '<option value="'.$row['ca_name'].'" '.get_selected($row['ca_name'], $sel_ca_name2).'>'.$row['ca_name'].'</option>';
                			}
                    	}
                    	?>
                    </select>
                    <select name="sel_ca_name3" id="sel_ca_name3" class="" target="ca_name3" next="sel_ca_name4">
                    	<option value="">----선택-----</option>
                    	<?php 
                    	if($it['it_id'] && $it['it_info_gubun']) {
                    	    $sql = "select ca_name3 as ca_name from lt_shop_info where if_id = '".$it['it_info_gubun']."' and ca_name2 = '".$sel_ca_name2."' group by ca_name3";
                			$result = sql_query($sql);
                			while ($row=sql_fetch_array($result))
                			{
                			    if($row['ca_name'] != "") echo '<option value="'.$row['ca_name'].'" '.get_selected($row['ca_name'], $sel_ca_name3).'>'.$row['ca_name'].'</option>';
                			}
                    	}
                    	?>
                    </select>
            		<select name="sel_ca_name4" id="sel_ca_name4" class="" target="ca_name4">
                        <option value="">----선택-----</option>
                    	<?php 
                    	if($it['it_id'] && $it['it_info_gubun']) {
                    	    $sql = "select ca_name4 as ca_name from lt_shop_info where if_id = '".$it['it_info_gubun']."' and ca_name3 = '".$sel_ca_name3."' group by ca_name4";
                			$result = sql_query($sql);
                			while ($row=sql_fetch_array($result))
                			{
                			    if($row['ca_name'] != "") echo '<option value="'.$row['ca_name'].'" '.get_selected($row['ca_name'], $sel_ca_name4).'>'.$row['ca_name'].'</option>';
                			}
                    	}
                    	?>
                    </select>
            		<button type="button" class="btn btn-success" id="btnItemInfoUpdate">수정</button>
    			</td>
    		</tr>
    		
            <tr>
             	<th scope="row">선택된 제품분류</th>
            	<td colspan="5">
            		<label id="spnItemInfoUpdate"><?php echo $sel_ca_name ?></label>
            		<input type="hidden" name="it_info_gubun" id="it_info_gubun" value="<?php echo $it['it_info_gubun'] ?>">
            	</td>
            </tr>
        </tbody>
		</table>
		
        <table id="tblItemInfo">
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
        	<tbody id="tbodyItemInfo">
    		<?php
    		if($it['it_id'] && $it['it_info_value'] != '')
            {
                $article = json_decode($it['it_info_value'], true);
                foreach ($article as $key => $value) {
                    $list = '<tr>';
                    $list .= '    <th scope="row">'.$value['name'].'</th>';
                    $list .= '    <td>';
                    $list .= '    	<input type="hidden" name="ii_article[]" value="'.$value['name'].'" >';
                    $list .= '    	<input type="text" name="ii_value[]" value="'.$value['value'].'" class="form-control">';
                    $list .= '    </td>';
                    $list .= '</tr>';
                    
                    echo $list;
                }
            }
    ?>
        	</tbody>
        </table>
		
	</div>
	</div>

    <script>
    $(function(){
        var sel_if_id = '';
        
		$.get_ca_name = function(ca_name1,ca_name2,ca_name3, targetuid, targetSel){
			sel_if_id = '';
			
			$targetSel = $("#"+targetSel);
			$.post(
	                "<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php",
	                { ca_name1: ca_name1, ca_name2: ca_name2, ca_name3: ca_name3 },
	                function(data) {
	                	var responseJSON = JSON.parse(data);
	                	var count = responseJSON.length;
    	   				for(i=0; i<count; i++) {
    	   					 //alert(data[i]['me_name']);
    	   					 if(responseJSON[i][targetuid] != "") {
    	   						$targetSel.append($('<option>', {value:responseJSON[i][targetuid], text: responseJSON[i][targetuid], data: responseJSON[i]['if_id']}));
    	   					 } else if(responseJSON[i]['cnt'] == "1") {
     		   					//$.get_if_info(responseJSON[i]['if_id']);
    	   						sel_if_id = responseJSON[i]['if_id'];
     		   				}
    	   				}
	                }
	            );
		};
		
		var rowCnt = 0;
		$.get_if_info = function(if_id){
			
			$.post(
	                "<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php",
	                { if_id: if_id },
	                function(data) {
	                	var responseJSON = JSON.parse(data)[0];

	                	var ca_name = responseJSON['ca_name1'];
	                	if(responseJSON['ca_name2'] != "") ca_name += " > "+responseJSON['ca_name2'];
	                	if(responseJSON['ca_name3'] != "") ca_name += " > "+responseJSON['ca_name3'];
	                	if(responseJSON['ca_name4'] != "") ca_name += " > "+responseJSON['ca_name4'];

	                	$("#spnItemInfoUpdate").text(ca_name);

	                	var article = JSON.parse(responseJSON['article']);
	                	var count = article.length;
	                	var $tblItemInfo = $("#tblItemInfo");
	        			$("#tbodyItemInfo").empty();
	        			rowCnt = 0;
	        			
    	   				for(i=0; i<count; i++) {

    	   					var list = "<tr id='tr"+rowCnt+"'>";
    	   					list += "    <th scope=\"row\">"+article[i]['name']+"</th>";
    	   					list += "    <td>";
    	   					list += "    	<input type=\"hidden\" name=\"ii_article[]\" value=\""+article[i]['name']+"\" >";
    	   					list += "    	<input type=\"text\" name=\"ii_value[]\" value=\""+article[i]['value']+"\" class=\"form-control\">";
    	   				    list += "    </td>";
    	   					list += "</tr>";
    	   					
    	   				    var $menu_last = null;
    	   			        $menu_last = $tblItemInfo.find("tbody").find("tr:last");
    	   			        if($menu_last.size() > 0) {
    	   			            $menu_last.after(list);
    	   			        } else {
    	   			            $tblItemInfo.find("tbody").append(list);
    	   			        }
    	   			        
    	   			     	rowCnt++;
    	   				}

	                	$("#it_info_gubun").val(responseJSON['if_id']);
	                }
	            );
		};

		$("#btnItemInfoUpdate").click(function(e){
			var change = false;
			if(sel_if_id != "") {
    			if($("#it_info_gubun").val() == "") {
    				change = true;
    			} else {
    				change = confirm("제품 정보 고시 항목이 변경되어 작성된 내용이 모두 삭제될 수 있습니다. 수정하시겠습니까?")
    			}
			} else {
				alert("하위 분류를 선택해주세요.");
			}

			if(change)
			{
				$.get_if_info(sel_if_id);
			} 
				
		});


		$("#sel_ca_name1").change(function(e){

			$("#sel_ca_name2").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
			if($(this).val() != "")
			{
				$.get_ca_name($(this).val(), '', '', 'ca_name2', 'sel_ca_name2');
			}
		});

		$("#sel_ca_name2").change(function(){

			$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
			if($(this).val() != "")
			{
				$.get_ca_name($("#sel_ca_name1").val(), $(this).val(), '', 'ca_name3', 'sel_ca_name3');
			}
		});

		$("#sel_ca_name3").change(function(){
			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
			if($(this).val() != "")
			{
				$.get_ca_name($("#sel_ca_name1").val(), $("#sel_ca_name2").val(), $(this).val(),  'ca_name4', 'sel_ca_name4');
			}
		});
    });
    </script>
    
    
	
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 관련제품 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
    <div class="local_desc02 local_desc">
        <p>
            상품리스트에서 관련 상품으로 추가하시면 선택된 관련상품 목록에 <strong>함께</strong> 추가됩니다.<br>
            예를 들어, A 상품에 B 상품을 관련상품으로 등록하면 B 상품에도 A 상품이 관련상품으로 자동 추가됩니다.</strong>
        </p>
    </div>
    
	<div class="x_content">
    	<div class="tbl_frm01 tbl_wrap">
            <h4><span class="fa fa-check-square"></span> 등록된 전체상품 목록</h4>
            <label for="sch_relation" class="sound_only">상품분류</label>
            <span class="srel_pad">
                <select id="sch_relation" hidden>
                    <option value=''>분류별 상품</option>
                    <?php
                        $sql = " select * from {$g5['g5_shop_category_table']} ";
                        $sql .= " order by ca_order, ca_id ";
                        $result = sql_query($sql);
                        for ($i=0; $row=sql_fetch_array($result); $i++)
                        {
                            $len = strlen($row['ca_id']) / 2 - 1;
    
                            $nbsp = "";
                            for ($i=0; $i<$len; $i++)
                                $nbsp .= "&nbsp;&nbsp;&nbsp;";
    
                            echo "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
                        }
                    ?>
                </select>
                <label for="sch_name" class="sound_only">상품명</label>
                <input type="text" name="sch_name" id="sch_name" class="frm_input" size="15">
                <button type="button" id="btn_search_item" class="btn_frmline">검색</button>
            </span>
            <div id="relation" class="srel_list">
                <div class="tbl_head01 tbl_wrap">
                <table>
                	<thead>
                		<tr>
                            <th scope="col">상품코드</a></th>
                            <th scope="col">분류</a></th>
                            <th scope="col">카테고리</a></th>
                            <th scope="col">상품명</a></th>
                            <th scope="col">최종판매가<br/>(최종월리스료)</a></th>
                            <th scope="col">진열<br/>상태</a></th>
                            <th scope="col">추가</th>
                		</tr>
                	</thead>
                	<tbody>
                	</tbody>
            	</table>
            </div>
		</div>
        <script>
        $(function() {
            $("#btn_search_item").click(function() {
                var ca_id = $("#sch_relation").val();
                var it_name = $.trim($("#sch_name").val());
                var $relation = $("#relation");

                if(ca_id == "" && it_name == "") {
                    $relation.html("<p>상품명을 입력하신 후 검색하여 주십시오.</p>");
                    return false;
                }

                $("#relation").load(
                    "./itemformrelation.php",
                    { it_id: "<?php echo $it_id; ?>", ca_id: ca_id, it_name: it_name }
                );
            });

            $(document).on("click", "#relation .add_item", function() {
                // 이미 등록된 상품인지 체크
                var $li = $(this).closest("tr");
                var it_id = $li.find("input:hidden").val();
                var it_id2;
                var dup = false;
                $("#reg_relation input[name='re_it_id[]']").each(function() {
                    it_id2 = $(this).val();
                    if(it_id == it_id2) {
                        dup = true;
                        return false;
                    }
                });

                if(dup) {
                    alert("이미 선택된 상품입니다.");
                    return false;
                }

                var cont = "<tr>"+$li.html().replace("add_item", "del_item").replace("추가", "삭제")+"</tr>";
                var count = $("#reg_relation tr").size();

                if(count > 0) {
                    $("#reg_relation tr:last").after(cont);
                } else {
                    $("#reg_relation").html("<tr>"+cont+"</tr>");
                }

                $li.remove();
            });

            $(document).on("click", "#reg_relation .del_item", function() {
                if(!confirm("상품을 삭제하시겠습니까?"))
                    return false;

                $(this).closest("tr").remove();

                var count = $("#reg_relation tr").size();
                if(count < 1)
                    $("#reg_relation").html("<p>선택된 상품이 없습니다.</p>");
            });
        });
        </script>
    </div>
        
	<br/><br/>
    <section class="">
        <h4>선택된 관련상품 목록</h4>
        <span class="srel_pad"></span>
        <div class="tbl_head01 tbl_wrap">
            <table>
            	<thead>
            		<tr>
                        <th scope="col">상품코드</a></th>
                        <th scope="col">분류</a></th>
                        <th scope="col">카테고리</a></th>
                        <th scope="col">상품명</a></th>
                        <th scope="col">최종판매가<br/>(최종월리스료)</a></th>
                        <th scope="col">진열<br/>상태</a></th>
                        <th scope="col">추가</th>
            		</tr>
            	</thead>
            	<tbody id="reg_relation">
            <?php
            $str = array();
            $sql = " select b.ca_id, b.it_id, b.it_name, b.it_price,b.it_use,ca1.ca_name as ca_name1, ca2.ca_name as ca_name2, ca3.ca_name as ca_name3, it_item_type
                       from {$g5['g5_shop_item_relation_table']} a
                       left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id)
                       left join {$g5['g5_shop_category_table']} ca1 on ca1.ca_id = left(b.ca_id,2)
                       left join {$g5['g5_shop_category_table']} ca2 on ca2.ca_id = left(b.ca_id,4)
                       left join {$g5['g5_shop_category_table']} ca3 on ca3.ca_id = left(b.ca_id,6)
                      where a.it_id = '$it_id'
                      order by ir_no asc ";
            $result = sql_query($sql);
            for($g=0; $row=sql_fetch_array($result); $g++)
            {
                $it_name = get_it_image($row['it_id'], 50, 50).' '.$row['it_name'];
                
                $list = '';
                $list .= '<tr>';
                $list .= '<td>'.$row['it_id'].'</td>';
                $list .= '<td>'.($row['it_item_type'] == '0' ? '제품' : '리스').'</td>';
                $list .= '<td>'.$row['ca_name1'].($row['ca_name2'] ? ' > '.$row['ca_name2'] : '').($row['ca_name3'] ? ' > '.$row['ca_name3'] : '').'</td>';
                $list .= '<td style="text-align:left;">'.$it_name;
                $list .= '<td>'.number_format($row['it_price']).'</td>';
                $list .= '<input type="hidden" name="re_it_id[]" value="'.$row['it_id'].'">'.'</td>';
                $list .= '<td>'.($row['it_use'] ? '진열' : '진열안함').'</td>';
                $list .= '<td><button type="button" class="del_item btn_frmline">삭제</button></td>';
                $list .= '</tr>'.PHP_EOL;
                
                echo $list;
                
                $str[] = $row['it_id'];
            }
            $str = implode(",", $str);

            if($g <= 0)
                echo '<p>선택된 상품이 없습니다.</p>';
            ?>
            	</tbody>
        	</table>
        </div>
        </div>
        <input type="hidden" name="it_list" value="<?php echo $str; ?>">
    </section>
    
	<div class="x_title">
        <h4><span class="fa fa-check-square"></span> 기타 설정<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
	</div>
	
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="it_shop_memo">상점메모</label></th>
            <td>
            	<textarea name="it_shop_memo" id="it_shop_memo" class="resizable_textarea form-control" rows="4" ><?php echo $it['it_shop_memo']; ?></textarea>
            </td>
        </tr>
        </tbody>
        </table>
	</div>
	</div>
	
<?php 
if($it['it_status'] == "승인" || $it['it_status'] == "반려") {
?>
<div class="clearfix"></div>

<div class="x_title">
	<div class="pull-left">
    	<h4><span class="fa fa-check-square"></span> <?=$it['it_status']?> 처리 내역 </h4>
	</div>
    <div class="pull-right"><label class="nav navbar-right"></div>
    <div class="clearfix"></div>
</div>
<div class="x_content">
<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label>담당자</label></th>
        <td><?=$it['it_approve_mb_name'].'('.$it['it_approve_mb_id'].')'?></td>
        <th scope="row"><label><?=$it['it_status']?>일자</label></th>
        <td><?=$it['it_approve_date']?></td>
    </tr>
    <?php if($it['it_status'] == "반려") {?>
    <tr id="tr_cp_reason">
        <th scope="row"><label>사유</label></th>
        <td colspan="3"><?=$it['it_reason']?></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
 </div>
</div>
<?php } ?>
    

<?php if ($is_admin != 'brand'){ ?>
</form>

    <div class="text-center">
        	<?php if($it['it_status'] == "승인대기") { ?>
        	<input type="button" value="승인" class="btn_submit btn btn_approve" onclick="approve('approve');">
        	<input type="button" value="반려" class="btn_submit btn btn_approve" onclick="approve('return');">
        	<?php } ?>
    </div>
    <div class="clearfix"></div>
    
	<div id="approve" hidden>
	<form name="fmember" id="fmember" action="./itemform.brand.approve.update.php" method="post" >
  	<input type="hidden" name="w" id="approve_w" value="">
  	<input type="hidden" name="it_id" id="approve_it_id" value="<?php echo $it['it_id']; ?>">
  
        <div class="x_title">
        	<div class="pull-left">
            	<h4 id="approve_title"><span class="fa fa-check-square"></span>승인 </h4>
    		</div>
            <div class="pull-right"><label class="nav navbar-right"></div>
            <div class="clearfix"></div>
        </div>
    	
    	<div class="x_content">
            <div class="tbl_frm01 tbl_wrap">
                <table>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label>담당자</label></th>
                    <td><?php echo $member['mb_name'].'('.$member['mb_id'].')'?></td>
                </tr>
                <tr id="tr_cp_reason">
                    <th scope="row"><label>사유</label></th>
                    <td><input type="text" name="it_reason" value="" id="it_reason" maxlength="200" class="frm_input" size="80"></td>
                </tr>
                </tbody>
                </table>
             </div>
         </div>
        
        <div class="pull-right">
        	<input type="submit" value="확인" class="btn_submit btn btn_approve" accesskey='s'>
        </div>
		</form>
    </div>
    <script>
    function approve(act)
    {
    	if(act == "approve"){
    		$("#approve_title").html('<span class="fa fa-check-square"></span> 승인사유');
    		$("#approve").prop("hidden",false);
    		$("#tr_cp_reason").prop("hidden",true);
    
    	} else if(act == "return"){
    		$("#approve_title").html('<span class="fa fa-check-square"></span> 반려사유');
    		$("#approve").prop("hidden",false);
    		$("#tr_cp_reason").prop("hidden",false);
    	}

    	$("#it_reason").prop('disabled', false);
    	$("#approve_it_id").prop("disabled",false);
    	$("#approve_w").prop("disabled",false);
    	
    	$("#approve_w").val(act);
    	
    }
    </script>

<?php } else { ?>    

<div class="text-center">
	<?php if($w == "") {?><input type="submit" value="저장" class="btn_submit btn" accesskey="s"><?php } ?>
	
    <?php if ($w == "u") { 
        if($it['it_status'] == "승인" || $it['it_status'] == "반려") {
        ?>
        <input type="submit" value="상품수정" class="btn_submit btn" accesskey="s" onclick="return confirm('상품을 수정하는 경우 프론트 노출이 중지되고 수정승인이 요청됩니다. 변경된 상품 정보로 수정하시겠습니까?');">
     <?php } ?>
     
    <a href="<?php echo G5_SHOP_URL ;?>/item.php?it_id=<?php echo $it_id ;?>" class="btn_02  btn" target="_blank">미리보기</a> 
    <?php } ?>
    <a href="./itemlist.brand.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
</div>
</form>
<?php } ?>


</div></div></div>




<script>
var f = document.fitemform;
var subID = "";

$(function(){
	$(document).ready(function($) {
		
		$("#it_price").autoNumeric('init', {mDec: '0'});
		$("#it_rental_price").autoNumeric('init', {mDec: '0'});
		$("#it_discount_price").autoNumeric('init', {mDec: '0'});
		
		var its_org_price = 0;
		var its_org_rental_price = 0;
		var its_final_rental_price = 0;
		var its_final_price = 0;

		$("input[name='its_price[]']").each(function() {
			its_org_price += parseInt($(this).autoNumeric('get'));
        });

		$("input[name='its_rental_price[]']").each(function() {
			its_org_rental_price += parseInt($(this).autoNumeric('get'));
        });
        
		$("input[name='its_final_rental_price[]']").each(function() {
			its_final_rental_price += parseInt($(this).autoNumeric('get'));
        });
        
		$("input[name='its_final_price[]']").each(function() {
			its_final_price += parseInt($(this).autoNumeric('get'));
        });
		$("#it_price").autoNumeric('set', its_final_price);
		$("#it_rental_price").autoNumeric('set', its_final_rental_price);
		if(its_org_rental_price == 0) {
			$("#it_discount_price").autoNumeric('set', its_org_price-its_final_price);
		} else {
			var it_item_rental_month = parseInt($("#it_item_rental_month").val());
			$("#it_final_rental_price").text(number_format(its_final_rental_price * it_item_rental_month));
			$("#it_discount_price").autoNumeric('set', (its_org_rental_price-its_final_rental_price) * it_item_rental_month );
		}
		 
		$.delBtnFileUpload = function(event) {
			var fileBt = $("#"+$(this).attr("fileBtnID"));
			
			var fileBtnID = fileBt.attr("id");
			var labalID = fileBt.attr("labalID");
			var delBtnID = fileBt.attr("delBtnID");
			var imgID = fileBt.attr("imgID");
			
			$("#"+fileBtnID).val("");
			$("#org"+fileBtnID).val("");
			if(labalID != "") $("#"+labalID).val("");
			if(imgID != "")
			{
				$("#"+imgID).attr("src", "../img/theme_img.jpg");
				//$("#"+imgID).removeClass('hidden').addClass('hidden');
			}
			
			$(this).removeClass('hidden').addClass('hidden');
		}

		$.imgFileUploadChange = function(event) {
			
			var fileBtnID = $(this).attr("id");
			var labalID = $(this).attr("labalID");
			var delBtnID = $(this).attr("delBtnID");
			var imgID = $(this).attr("imgID");
			
			var fileName = "";
			if(window.FileReader){
				fileName = $(this)[0].files[0].name;
			} else {
				fileName = $(this)[0].val().split('/').pop().split('\\').pop();
			}
			
			if(fileName != "" && imgID != "") {
				var reader = new FileReader();
				reader.onload = function (e) {
					$("#"+imgID).attr("src", e.target.result);
				}
				reader.readAsDataURL($(this)[0].files[0]);
				
				$("#"+imgID).removeClass('hidden');
			}
			
			//$("#btnDelMainImgFile").removeClass('d-none').addClass('d-none');
			$("#"+delBtnID).removeClass('hidden');
			if(labalID != "") $("#"+labalID).val(fileName);
		}

		$.setImgFileUpload = function(fileInputId) {

			$("#"+fileInputId).on('change', $.imgFileUploadChange);
			var delBtnID = $("#"+fileInputId).attr("delBtnID");
			$("#"+delBtnID).click($.delBtnFileUpload);
		}
		
		$.setImgFileUpload('imgFile1');
		$.setImgFileUpload('imgFile2');
		$.setImgFileUpload('imgFile3');
		$.setImgFileUpload('imgFile4');
		$.setImgFileUpload('imgFile5');

		$('#it_period').daterangepicker({
			"autoApply": true,
			"opens": "right",
			locale: {
		        "format": "YYYY-MM-DD",
		        "separator": " - ",
		        "applyLabel": "선택",
		        "cancelLabel": "취소",
		        "fromLabel": "시작일자",
		        "toLabel": "종료일자",
		        "customRangeLabel": "직접선택",
		        "weekLabel": "W",
		        "daysOfWeek": ["일","월","화","수","목","금","토"],
		        "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
		        "firstDay": 1
		    }
	    	/*,ranges: {
		           '오늘': [moment(), moment()],
		           '3일': [moment().subtract(2, 'days'), moment()],
		           '1주': [moment().subtract(6, 'days'), moment()],
		           '1개월': [moment().subtract(1, 'month'), moment()],
		           '3개월': [moment().subtract(3, 'month'), moment()],
		           '이번달': [moment().startOf('month'), moment().endOf('month')],
		           '마지막달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		        }*/
		});

		$('#it_period').val("<?php echo $it['it_period'] ?>");
		
		$("#it_period_chk").click(function(){
			 var chk = $(this).is(":checked");
			 
			 $('#it_period').val("");
			 if(chk) {
				 $('#it_period').prop('disabled', true);
			 } else  { 
				 $('#it_period').prop('disabled', false);
			 }
		});
		
	});
	
	<?php if ($is_admin != 'brand'){ ?>
	$("input").prop('disabled', true);
	$("select").prop('disabled', true);
	$("button").prop('disabled', true);
	$("textarea").prop('disabled', true);

	$(".btn_approve").prop('disabled', false);
	<?php } ?>
});

function sapconfirm(orderno, sapcode, item, price, s)
{
	$("#its_order_no"+s).val(orderno);
	$("#its_sap_code"+s).val(sapcode);
	$("#its_item"+s).val(item);
	
	//$("#its_price"+s).val(price);
	//$("#its_final_price"+s).val(price);
	
	$("#its_price"+s).autoNumeric('set', price);
	$("#its_final_price"+s).autoNumeric('set', price);
	$("#its_price"+s).trigger("keyup");
	
	makeOption(price, s);

	$("#modal_sapsearch").modal('hide');
}

function makeOption(price, s)
{
    //var it_id = $.trim($("input[name=it_id]").val());
    var sap_code = $("#its_sap_code"+s).val();
    var $option_table = $("#sit_option_frm"+s);
    
	$.post(
            "./itemsapoption.php",
            { w: "<?php echo $w; ?>", sap_code: sap_code, min_price: price, subID: subID, subID:s},
            function(data) {
                $option_table.empty().html(data);
            }
        );
}

function codedupcheck(id)
{
    if (!id) {
        alert('상품코드를 입력하십시오.');
        f.it_id.focus();
        return;
    }

    var it_id = id.replace(/[A-Za-z0-9\-_]/g, "");
    if(it_id.length > 0) {
        alert("상품코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
        return false;
    }

    $.post(
        "./codedupcheck.php",
        { it_id: id },
        function(data) {
            if(data.name) {
                alert("코드 '"+data.code+"' 는 '"+data.name+"' (으)로 이미 등록되어 있으므로\n사용하실 수 없습니다.");
                return false;
            } else {
                alert("'"+data.code+"' 은(는) 등록된 코드가 없으므로 사용하실 수 있습니다.");
                document.fitemform.codedup.value = '';
            }
        }, "json"
    );
}

function fitemformcheck(f)
{
    if (!f.ca_id.value) {
        alert("기본분류를 선택하십시오.");
        f.ca_id.focus();
        return false;
    }

    if (f.w.value == "") {
        var error = "";
        $.ajax({
            url: "./ajax.it_id.php",
            type: "POST",
            data: {
                "it_id": f.it_id.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                error = data.error;
            }
        });

        if (error) {
            alert(error);
            return false;
        }
    }

	var maxSendCost = parseInt(f.it_sc_price.value) + parseInt(f.it_return_costs.value);

	if(parseInt($("input[name='its_final_price[]']").autoNumeric('get')) < maxSendCost){
		alert("판매가는 왕복배송비("+maxSendCost+"원)보다 낮은금액으로 등록할 수 없습니다.");
		$("input[name='its_final_price[]']").focus();
		return false;
	}
	
	if(f.it_point_type.value == "2") {
		var point = parseInt(f.it_point2.value);
		if(point > 99) {
			alert("적립금 비율을 0과 99 사이의 값으로 입력해 주십시오.");
			return false;
		}
	}

    if(parseInt(f.it_sc_type.value) > 1) {
        if(!f.it_sc_price.value || f.it_sc_price.value == "0") {
            alert("기본배송비를 입력해 주십시오.");
            return false;
        }

        if(f.it_sc_type.value == "2" && (!f.it_sc_minimum.value || f.it_sc_minimum.value == "0")) {
            alert("배송비 상세조건의 주문금액을 입력해 주십시오.");
            return false;
        }

        if(f.it_sc_type.value == "4" && (!f.it_sc_qty.value || f.it_sc_qty.value == "0")) {
            alert("배송비 상세조건의 주문수량을 입력해 주십시오.");
            return false;
        }
    }
    var chk = false;
    
    $("input[name='it_option_subject[]']").each(function() {
		if(trim($(this).val()) == ""){
			chk = true;	
    		$(this).focus();
		}
    });
    if(chk){
    	alert("옵션명은 필수입력 사항입니다.");
        return false;
    }
    
    // 관련상품처리
    var item = new Array();
    var re_item = it_id = "";

    $("#reg_relation input[name='re_it_id[]']").each(function() {
        it_id = $(this).val();
        if(it_id == "")
            return true;

        item.push(it_id);
    });

    if(item.length > 0)
        re_item = item.join();

    $("input[name=it_list]").val(re_item);


    <?php echo get_editor_js('it_explan'); ?>
    <?php echo get_editor_js('it_mobile_explan'); ?>


    return true;
}
</script>

<?php
if ($is_admin != 'super'){
    include_once (G5_ADMIN_PATH.'/admin.tail.php');
} else {
    include_once (G5_ADMIN_PATH.'/admin.tail.sub.php');
}
?>
