<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if($w == '')
    $w = 'c';

$wr_is_comment = sql_fetch(" select count(wr_id) cnt from {$write_table} where   wr_parent='{$wr_id}' and wr_is_comment = '1' and     mb_id = '{$member['mb_id']}'");
if($wr_is_comment['cnt'] != 0){
    alert("이미 고객님께서는 신청한 이력이 있습니다. 감사합니다.");
}
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span><?php echo $board['bo_subject'] ?> 신청</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);

</script>
<div class="content community type1 sub">
	<!-- 컨텐츠 시작 -->
	<form name="fviewcomment" id="fviewcomment" action="<?php echo https_url(G5_BBS_DIR)."/write_comment_update.php"; ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="bo_vc_w">
	<div class="grid" id="gridContent">
		<div class="title_bar">
            <h3 class="g_title_01">기본정보</h3>
        </div>
		
            <input type="hidden" name="w" value="<?php echo $w ?>" id="w">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
            <input type="hidden" name="comment_id" value="<?php echo $c_id ?>" id="comment_id">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="is_good" value="">
			<input type="hidden" id="wr_content" name="wr_content"  value="">

<?php
    $sql_board = " select * from {$write_table} where wr_id='$wr_id'";
    $result = sql_query($sql_board);
    
    while ($row=sql_fetch_array($result)) {
        
        $experience_view_data = json_decode(str_replace('\\','',$row['wr_4']), true);
        
        
        if($experience_view_data['name'] == 1) {
?>
		<div class="inp_wrap">
            <div class="title count3"><label for="">신청자 명<span class="txt_essential"><em>필수입력</em></span></label></div>
            <div class="inp_ele count6">
                <div class="input"><input type="text" placeholder="이름 입력" id="mb_name" title="" value="<?php echo $member['mb_name']?>"></div>
            </div>
        </div>
<?php
        } 
        if($experience_view_data['email'] == 1) {
                
?>
		<div class="inp_wrap">
            <div class="title count3"><label for="">이메일<span class="txt_essential"><em>필수입력</em></span></label></div>
            <div class="inp_ele count6">
                <div class="input"><input type="email" placeholder="이메일주소 입력" id="mb_email" title="" value="<?php echo $member['mb_email']?>"></div>
            </div>
        </div>
<?php
        }
        if($experience_view_data['phone'] == 1) {
            
            ?>
		<div class="inp_wrap">
            <div class="title count3"><label for="">휴대전화 번호<span class="txt_essential"><em>필수입력</em></span></label></div>
            <div class="inp_ele count6">
                <div class="input"><input type="tel" placeholder="휴대전화 번호 입력" id="mb_hp" title="" value="<?php echo $member['mb_hp']?>"></div>
            </div>
        </div>
<?php
        }
        if($experience_view_data['age'] == 1){
                
?>
		<div class="inp_wrap">
			<div class="title count3"><label for="join2">생년월일<span class="txt_essential"><em>필수입력</em></span></label></div>
			<div class="inp_ele count2">
				<span class="sel_box">
					<select name="year" id= "year" title="목록" target1="month" target2="day">
						<option value="">선택</option>
						<?
                        $mb_birth_explode = explode('-',$member['mb_birth']);
                        //1960~현재년도까지
						
                        foreach(range(date('Y'), 1960) as $val){
                            if($mb_birth_explode[0] == $val) $selected = 'selected'; else $selected = '';
                            echo '<option value="'.$val.'" '.$selected.' >'.$val.'</option>';
                        }
                        
                        
                        ?>

					</select>
				</span>
			</div>
			<div class="inp_ele count2">
				<span class="sel_box">
					<select name="month"  id ="month" title="목록" target1="year" target2="day">
						<option value="">선택</option>
						<?

                        //1월부터 12월까지
						foreach(range(1, 12) as $val) {
						    if($mb_birth_explode[1] == $val) $selected = 'selected'; else $selected = '';
						    echo '<option value="'.sprintf('%0d' , $val).'" '.$selected.' >'.sprintf('%d월' , $val).'</option>';
						}
                        
                        ?>
					</select>
				</span>
			</div>
			<div class="inp_ele count2">
				<span class="sel_box">
					<select name="day"  id ="day"  title="목록">
						<option value="">선택</option>
						<?

                        //1월부터 12월까지
						foreach(range(1, 31) as $val){
						    if($mb_birth_explode[2] == $val) $selected = 'selected'; else $selected = '';
						    echo '<option value="'.sprintf('%0d' , $val).'" '.$selected.' >'.sprintf('%d일' , $val).'</option>';
						}
                        
                        ?>
					</select>
				</span>
			</div>
		</div>
		<script>
    		$(function(){
    			$('#year').change(function (){	
    	            var id = $(this).attr('id');
    	            var target1 = $(this).attr('target1');
    	            var target2 = $(this).attr('target2');
    	            var year = $('#'+id+' option:selected').val();
    	            var month = $('#'+target1+' option:selected').val();
    	            if(year != '' & month != '')
    	            //month 는 0 부터 시작해서..
    	            var day = 32 - new Date(year, month-1, 32).getDate();
    	            $.fn_append_day(day,target2);
    	        });
    
    	        $('#month').change(function ()
    	        {
    	        	var id = $(this).attr('id');
    	            var target1 = $(this).attr('target1');
    	            var target2 = $(this).attr('target2');
    	            var year = $('#'+target1+' option:selected').val();
    	            var month = $('#'+id+' option:selected').val();
    	            
    	            //month 는 0 부터 시작해서..
    	            var day = 32 - new Date(year, month-1, 32).getDate();
    	            $.fn_append_day(day,target2);
    	        });
    
    	        $.fn_append_day = function(day,target){
    	        	$('#'+target).html('');
    	        	var html = '';
    	            for(var i = 1 ; i < day+1 ; i++){
    	                html = '<option value="'+i+'">'+i+'일</option>';
    	            	$('#'+target).append(html);    
    	            }
    	            
    	        }
    		});
    		</script>
<?php
        }
        if($experience_view_data['sex'] == 1) {
            
            ?>
		<div class="inp_wrap">
            <div class="title count3"><label for="join3">성별</label></div>
            <div class="inp_ele count3">
                <span class="chk radio">
                
                    <input type="radio" id="mb_sex_1" name="mb_sex" value="M" <?php if($member['mb_sex'] == 'M') echo "checked='checked'";?>>
                    <label for="r_01_1">남성</label>
                </span>
            </div>
            <div class="inp_ele count3">
                <span class="chk radio">
                    <input type="radio" id="mb_sex_2" name="mb_sex" value="F" <?php if($member['mb_sex'] == 'F') echo "checked='checked'";?>>
                    <label for="r_01_2">여성</label>
                </span>
            </div>
        </div>
		
<?php
        } 
        if($experience_view_data['address'] == 1){
                
?>
        <div class="inp_wrap">
        	<div class="title count3">
                <label for="join7">주소<span class="txt_essential"><em>필수입력</em></span></label>
            </div>
            <div class="inp_ele count6 r_btn_100">
                <div class="input"><input type="text" placeholder="" id="mb_zip" name="mb_zip" title="우편번호" readonly value="<?php echo $member['mb_zip1'].$member['mb_zip2']?>"></div>
                <button type="button" class="btn small green" onclick="win_zip('fregisterform','mb_zip' , 'mb_addr1', 'mb_addr2', 'mb_addr3','mb_addr_jibeon');">우편번호</button>
            </div>
        </div>
        <div class="inp_wrap" id="daum_juso_pagemb_zip" style="display: none">
        	<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-5px;z-index:2" class="close_daum_juso" alt="접기 버튼">
        </div>
        <script>
            jQuery(function($){
                $("#daum_juso_pagemb_zip").off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            });
        </script>
        <div class="inp_wrap">
            <div class="inp_ele count6 col_r">
                <div class="input"><input type="text" placeholder="" id="mb_addr1"  name = "mb_addr1" readonly value="<?php echo $member['mb_addr1']?>"></div>
            </div>
        </div>
        <div class="inp_wrap">
            <div class="inp_ele count6 col_r">
                <div class="input"><input type="text" placeholder="" id="mb_addr2"  name = "mb_addr2" value="<?php echo $member['mb_addr2']?>"></div>
            </div>
        </div>
        <input type="hidden" id="mb_addr3" name = "mb_addr3" value="<?php echo $member['mb_addr3']?>"  >
		<input type="hidden" id = "mb_jibeon" name="mb_addr_jibeon"  value="<?php echo $member['mb_addr_jibeon']?>">
        
<?php
        }?>
        </div>
    
        <div class="grid">
       <?php if($experience_view_data['additem'] != "[]"){
           
?>

        <script>
            var wr_4 = '<?php echo $row["wr_4"]; ?>';
            if(wr_4 != "")
            {
            	var wr_4 = JSON.parse(wr_4.replace("&#034;","\""));
            	var addItemHead = '<div class="title_bar"><h3 class="g_title_01">추가정보</h3></div>';
            	for(var i=0;i<wr_4.addItem.length;i++)
            	{
            		addItem(wr_4.addItem[i]);
            	}
            }
            
            
            function addItem(addItem)
            {
                
                var addHtml = '<div class="inp_wrap">';
                addHtml += '	<div class="title count9"><label for="">'+addItem+'</label></div>';
                addHtml += '	<div class="inp_ele count9">';
                addHtml += '		<div class="input"><input type="text" placeholder="'+addItem+' 입력" id="'+addItem+'" name="addItem"></div>';
                addHtml += '	</div>';
                addHtml += '</div>';
                
                $("#itembox").append(addHtml);
            }
        
        </script>
<?php            
        }
    }
?>
		<hr class="full_line">       
        <div class="inp_wrap alignR mb20">
                <p class="floatL bold">이용자 동의</p>
        </div>    
        <div class="inp_wrap">
            <span class="chk check">
                <input type="checkbox" id="chk_01">
                <label for="chk_01">개인정보 수집·이용 동의<span>(필수)</span></label>
            </span>
            <a href="<?php echo G5_MOBILE_URL?>/common/terms_agreement.php?id=chk_01&type=user_privacy&title=개인정보 수집·이용 동의" class="btn floatR arrow_r" target="_blank">전문보기</a>
        </div>
        <div class="info_box line full">
            <p class="ico_import red point_red">주의사항</p>
            <div class="list">
                <ul class="hyphen">
                    <li>체험단 신청 본인이 아닌 경우 신청이 불가합니다.</li>
                    <li>개인정보 수집 · 이용동의에 동의하지 않을 경우 당첨문자 안내 및 상품 배송이 불가하여 이벤트 참여가 불가합니다.</li>                   
                </ul>
            </div> 
        </div>
		
		<div class="btn_group"><button type="submit" id="btn_submit" class="btn big green"><span><?php echo $board['bo_subject'] ?> 신청</span></button></div>
		
	</div>
	</form>
	<!-- 컨텐츠 종료 -->
</div>
<script>
$(document).ready(function(){

});
function fviewcomment_submit(f)
{

    if($('#mb_name').val() == ''){
        alert('이름을 입력 해 주세요');
        return false;
    } else if($('#mb_email').val() == ''){
    	alert('이메일을 입력 해 주세요');
    	return false;
    } else if($('#mb_hp').val() == ''){
    	alert('휴대전화번호를 입력 해 주세요');
    	return false;
    } else if($('#mb_zip').val() == ''){
    	alert('주소를 입력 해 주세요');
    	return false;
    } else if($('#mb_age').val() == ''){
    	alert('생년월일을 입력 해 주세요');
    	return false;
    } else if($('#mb_sex').val() == ''){
    	alert('성별을 선텍 해 주세요');
    	return false;
    }
    
	if($('input[type="checkbox"]:checked').length == 0){
		alert('개인정보 수집 · 이용 동의를 해주세요');
        return false;
	}
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자

    f.is_good.value = 0;

    var subject = "";
    var content = "";

	var contentJson = new Object();
	contentJson.id = "<?php echo $member['mb_id'] ?>";
	
	if($('#mb_name').val() != '') contentJson.name = $('#mb_name').val();
	if($('#mb_email').val() != '') contentJson.email = $('#mb_email').val();
	if($('#mb_hp').val() != '') contentJson.phone = $('#mb_hp').val();
	if($('#year').val() != '') contentJson.age = $('#year').val()+'-'+$('#month').val()+'-'+$('#day').val();
	if($('#mb_sex').val() != '') contentJson.sex = $('#mb_sex').val();

	if($('#mb_zip').val() != ''){
    	var addressJson = new Object();
    	addressJson.zip= $('#mb_zip').val();
    	addressJson.adrr1= $('#mb_addr1').val();
    	addressJson.adrr2= $('#mb_addr2').val();
    	addressJson.addr3= $('#mb_addr3').val();
    	addressJson.addr_jibeon = $('#mb_addr_jibeon').val();
    	contentJson.address = addressJson;
	}

	if($('input[name="addItem"]').length > 0) {
        var addItemJson = {};
        var keyname = '';
    
    	$('input[name="addItem"]').each(function (i){
    		 var addItemValue = $("input[name='addItem']").eq(i).attr("value");
    		 var addItemId = $("input[name='addItem']").eq(i).attr("id");
    		 addItemJson[keyname + addItemId] = addItemValue;
    	});
    
    	contentJson.addItem = addItemJson;
	}
        
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": "",
            "content": contentJson
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });
	

    // 양쪽 공백 없애기
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
    document.getElementById('wr_content').value = JSON.stringify(contentJson);
     



    set_comment_token(f);

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

</script>