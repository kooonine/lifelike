<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$onoff = $_GET['onoff'];
if($onoff == ""){
    $onoff = "on";
}
?>

<div class="content event">
	<!-- 컨텐츠 시작 -->
		<div class="grid ">
    	<div class="step-nav">
            <ul>
                <li name="onOffBar" data='on'><a href="#">진행 중</a></li>
                <li name="onOffBar" data='off'> <a href="#">종료</a></li>
            </ul>
        </div>
        <div class="type-wrap">
            <!-- 분류 선택시 f_200.30.001_m.html 팝업 -->
            <div class="type-list">
                <ul>
                	<?php for ($i=1; $i<10; $i++) {
                	    if($board['bo_'.$i.'_subj'] != ''){
                	?>
                    	<li><button name="<?php echo 'btn_bo_subj'?>" data-target="<?php echo $i?>" data="<?php echo $board['bo_'.$i]?>"><?php echo $board['bo_'.$i.'_subj'];?></button></li>
                    <?php }
                	}?>
                </ul>
            </div>
            <!-- 컨텐츠 분류 : 태그 -->
            <div class="type-tag">
                <ul>
                    <li id="li_bo_1" <?php if($bo_1 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_1 ?></span><a href="#" name="a_bo_type" data-target = "bo_1">닫기</a></li>
                    <li id="li_bo_2" <?php if($bo_2 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_2 ?></span><a href="#" name="a_bo_type" data-target = "bo_2">닫기</a></li>
                    <li id="li_bo_3" <?php if($bo_3 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_3 ?></span><a href="#" name="a_bo_type" data-target = "bo_3">닫기</a></li>
                    <li id="li_bo_4" <?php if($bo_4 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_4 ?></span><a href="#" name="a_bo_type" data-target = "bo_4">닫기</a></li>
                    <li id="li_bo_5" <?php if($bo_5 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_5 ?></span><a href="#" name="a_bo_type" data-target = "bo_5">닫기</a></li>
                    <li id="li_bo_6" <?php if($bo_6 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_6 ?></span><a href="#" name="a_bo_type" data-target = "bo_6">닫기</a></li>
                    <li id="li_bo_7" <?php if($bo_7 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_7 ?></span><a href="#" name="a_bo_type" data-target = "bo_7">닫기</a></li>
                    <li id="li_bo_8" <?php if($bo_8 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_8 ?></span><a href="#" name="a_bo_type" data-target = "bo_8">닫기</a></li>
                    <li id="li_bo_9" <?php if($bo_9 == ''){?>style="display:none"<?php }?>><span><?php echo $bo_9 ?></span><a href="#" name="a_bo_type" data-target = "bo_9">닫기</a></li>


                </ul>
            </div>
        </div>
        <form name="fboardlist" id="fboardlist" action="./board.php" method="post">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="sw" value="">
            <input type="hidden" name="bo_1" id="bo_1" value="<?php echo $bo_1 ?>">
            <input type="hidden" name="bo_2" id="bo_2" value="<?php echo $bo_2 ?>">
            <input type="hidden" name="bo_3" id="bo_3" value="<?php echo $bo_3 ?>">
            <input type="hidden" name="bo_4" id="bo_4" value="<?php echo $bo_4 ?>">
            <input type="hidden" name="bo_5" id="bo_5" value="<?php echo $bo_5 ?>">
            <input type="hidden" name="bo_6" id="bo_6" value="<?php echo $bo_6 ?>">
            <input type="hidden" name="bo_7" id="bo_7" value="<?php echo $bo_7 ?>">
            <input type="hidden" name="bo_8" id="bo_8" value="<?php echo $bo_8 ?>">
            <input type="hidden" name="bo_9" id="bo_9" value="<?php echo $bo_9 ?>">
                        

    	</form>

    	<div class="list webzine-list">
    		<ul class="type2">
    			<?php 
                for ($i=0; $i<count($list); $i++) {
                    $nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?
                    
                    $valDate = Trim($list[$i]['wr_7']); // 폼에서 POST로 넘어온 value 값('yyyy-mm-dd' 형식)
                    
                    $bo_new = $board['bo_new'];
                    
                    
                    
                    $leftDate = intval((strtotime($nDate)-strtotime($valDate))); // 나머지 날짜값이 나옵니다.
                    if($leftDate > 0){
                        $deadLine = "off";
                    }else {
                        $deadLine = "on";
                    }
                    
                    $leftDate = intval((strtotime($nDate)-strtotime($valDate)) / 86400); // 나머지 날짜값이 나옵니다.
                    if($leftDate == 0){
                        $leftDate = '마감 D-day';
                    }else {
                        if($valDate != ''){
                            $leftDate = '마감 D'.$leftDate;
                        }else {
                            $leftDate = '상시 모집';
                            $deadLine = "on";
                        }
                    }
                    
                    $postingDate = explode(',', $list[$i]['wr_1']);
                    $postYn = "N";
                    $bo_newYN = "N";
                    if(count($postingDate) == 2){
                        $postingStDt = Trim($postingDate[0]);
                        $postingEnDt = Trim($postingDate[1]);
                        $bo_newYN = intval(strtotime($postingStDt.' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';
                        if(intval(strtotime($nDate)-strtotime($postingStDt)) >= 0 && intval(strtotime($postingEnDt)-strtotime($nDate)) >= 0 ) {
                            $postYn = "Y";
                        }
                    }
                    if($postYn == "Y"){
                 ?>
                <li class="<?php echo $deadLine;?>">
                    <a href="<?php echo $list[$i]['href'] ?>"">
                    	<?php if($list[$i]['wr_file'] == 1){
    						    $wr_id = $list[$i]['wr_id'];
    						    $sql2 = " select bf_file from lt_board_file where bo_table='event' and wr_id= {list[$i]['wr_id']} ";
    						    $row2 = sql_fetch($sql2);
    						    $sum_img_url = G5_DATA_URL.'/file/event/'.$row2['bf_file'];
						?>
                        <div class="photo">
                            <img src="<?php echo $sum_img_url?>" alt="">
                        </div>
                        <?php }?>
                        <div class="cont">
                            <span class="category round"><?php echo $leftDate;?></span>                                        
                            <p class="title bold"><?php echo $list[$i]['wr_subject'];
                            
                            if($bo_newYN == 'Y'){
                            ?>
                            <span class="new">N</span></p>
                            <?php }?>
                            <p><?php echo $list[$i]['wr_3']?></p>
                            <span class="date"><?php echo $list[$i]['wr_datetime']?></span>
                        </div>
                    </a>
                </li>
                <?php 
                    } 
                }
                ?>
            </ul>
    		
    	</div>
    </div>

    <!-- 컨텐츠 종료 -->
</div>
     <script>
$(document).ready(function(){
	

	$('li[name="onOffBar"]').click(function(){
		$.changeList($(this).attr('data'));
	});

	$('button[name="btn_bo_subj"]').click(function(){
		var data = $(this).attr('data');
		var data_target = $(this).attr('data-target');

		$('#li_bo_'+data_target).css('display','');
		$('#li_bo_'+data_target+' span').html(data);
		$('#bo_'+data_target).val(data);
		$('#fboardlist').submit();
	});

	$('a[name="a_bo_type"]').click(function(){
		var data_target = $(this).attr('data-target');
		$('#li_'+data_target).css('display','none');
		$('#li_'+data_target+' span').html('');
		$('#'+data_target).val('');
		$('#fboardlist').submit();
	});
	
	$.changeList = function(type){
		if(type == 'on'){
			$('.type2 .on').css('display','block');
			$('.type2 .off').css('display','none');
			$('li[name="onOffBar"][data="off"]').removeClass('on');
			$('li[name="onOffBar"][data="on"]').removeClass('on').addClass('on');
		} else {
			$('.type2 .off').css('display','block');
			$('.type2 .on').css('display','none');
			$('li[name="onOffBar"][data="on"]').removeClass('on');
			$('li[name="onOffBar"][data="off"]').removeClass('on').addClass('on');
		}
		
	};
	$.changeList('<?php echo $onoff?>');
});


</script>