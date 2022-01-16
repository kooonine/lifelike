<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$onoff = $_GET['onoff'];
if($onoff == ""){
    $onoff = "on";
}
?>
<!-- container -->
<div id="container">
		
<div class="content magazine">
				<!-- 컨텐츠 시작 -->
<?php
$bannersql = " select * from lt_board where bo_use = 1 and bo_table='".$board['bo_table']."'";
$result = sql_query($bannersql);
    
    while ($row=sql_fetch_array($result)) {

       
        $main_view_data = json_decode(str_replace('\\','',$row['bo_banner']), true);
?>
    <div class="visual_area">
    	<div class="swiper-container">
    		<div class="swiper-wrapper">
    			<?php for($i=0; $i<$main_view_data['bannerCount']; $i++) {
    			    
				    $img_data = $main_view_data['imgFile'][$i];
				    $link_url = $img_data['linkURL'];
				    $img_file = G5_DATA_PATH.'/banner/experience/'.$img_data['imgFile'];
				    
				    if ($img_data['imgFile'] && file_exists($img_file)) {
				        $img_url = G5_DATA_URL.'/banner/experience/'.$img_data['imgFile'];
			    ?>
			    		<div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo $img_url?>" alt="" width="1000px" /></a></div>
				<?php } else { ?>
				        <div class="swiper-slide"><a href="<?php echo $link_url?>"><img src="<?php echo G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a></div>
				<?php }	
				}?>
    		</div>
    		<div class="swiper-pagination"></div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
    	</div>
    	<script>
		var swiperMain_visual = new Swiper('.visual_area .swiper-container', {
			 autoplay: {
				delay: 4000,
			  },
			  loop: true,
			  pagination: {
				el: '.swiper-pagination',
				clickable: true,
			  },
			  navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			  },
			});
      </script>
    </div>
   <?php }?>
    <div class="grid ">
<!-- 		<div class="tab_cont_wrap"> -->
<!-- 			<div class="tab"> -->
<!-- 				<ul class="type2 onoff tab_btn black"> -->
<!-- 					<li name="onOffBar" class="on"><a href="#"><span>모집마감순</span></a></li> -->
<!-- 					<li name="onOffBar" class=""><a href="#"><span>인기순</span></a></li> -->
<!-- 				</ul> -->
<!-- 			</div> -->
			
	        <div class="step-nav">
                <ul class="type2 onoff tab_btn">
<!--                     <li name="onOffBar" class="on"><a href="#">모집마감순</a></li> -->
<!--                     <li name="onOffBar"><a href="#">인기순</a></li> -->
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
                    		<li><button type="button" class="bo_option" name="<?php echo 'btn_bo_subj'?>" targetID="bo_option_<?php echo $i?>" ><?php echo $board['bo_'.$i.'_subj'];?></button></li>
                    <?php 
                	    
                            if($board['bo_'.$i] != '') {
                                $bo_option_list = explode(',', $board['bo_'.$i]);
                                $option_count = count($bo_option_list);
                                $bo_select = '<select id="bo_option_'.$i.'" name="sel_bo_option[]" hidden >'.PHP_EOL;
                                $bo_select .= '<option value="">선택</option>'.PHP_EOL;
                                for($j=0; $j<$option_count; $j++) {
                                    $bo_select .= '<option value="'.$bo_option_list[$j].'">'.$bo_option_list[$j].'</option>'.PHP_EOL;
                                }
                                $bo_select .= '</select>'.PHP_EOL;
                                
                                echo $bo_select.PHP_EOL;
                            }
                	    
                	    }
                	}?>
                </ul>
            </div>
            
            <!-- The Modal -->
            <div class="popup_container layer" id="optionModal"  style="display: none;">
                <div class="inner_layer" style="top: 100px;">
                    <div class="content " style="padding-top: 0px;">
                        <div class="title_bar">
                            <h2 class="g_title_01" id="optionModalTitle"></h2>
                        </div>
                        <div class="grid cont" style="margin: 0;">
                    		<div class="list">
                    			<ul class="type1 pad"  id="optionModalList">
                    			</ul>
                    		</div>
                        </div>
                    </div>
            
                    <a href="#" class="btn_closed" onclick="$('#optionModal').css('display','none');"><span class="blind">닫기</span></a>
                </div>
            </div>
            <!--End Modal-->
            
            <!-- 컨텐츠 분류 : 태그 -->
            <div class="type-tag">
                <ul id="bo_selected_option">
		
                </ul>
            </div>
        </div>
			
        <form name="fboardlist" id="fboardlist" action="./board.php" method="get">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="sw" value="">
            <input type="hidden" name="wr_6" id="wr_6" value="<?php echo $wr_6 ?>">
            
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

		<div class="tab">
            <ul class="type2 tab_btn">
                <li><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=<?php echo $board['bo_table'] ?>&sst=wr_datetime"><span>등록순</span></a></li>
                <li><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=<?php echo $board['bo_table'] ?>&sst=wr_good desc"><span>추천순</span></a></li>
            </ul>
        </div>
        <!-- 컨텐츠 게시판 : 카드형 세로타입 -->
        <div class="card-list">
            <ul class="letz1">
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
                    
                    $postYn = "N";
                    $bo_newYN = "N";
                    if($list[$i]['wr_1'] == '0') $postYn = "N";
                    else if($list[$i]['wr_1'] == '1') $postYn = "Y";
                    else {
                        $postingDate = explode(',', $list[$i]['wr_1']);                    
                        if(count($postingDate) == 2){
                            $postingStDt = Trim($postingDate[0]);
                            $postingEnDt = Trim($postingDate[1]);
                            $bo_newYN = intval(strtotime($postingStDt.' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';
                            if(intval(strtotime($nDate)-strtotime($postingStDt)) >= 0 && intval(strtotime($postingEnDt)-strtotime($nDate)) >= 0 ) {
                                $postYn = "Y";
                            }
                        } else {
                            $postingStDt = Trim($postingDate[0]);
                            $bo_newYN = intval(strtotime($postingStDt.' +'.$bo_new.' hours')-strtotime($nDate)) > 0 ? 'Y' : 'N';
                            if(intval(strtotime($nDate)-strtotime($postingStDt)) >= 0) {
                                $postYn = "Y";
                            }
                        }
                    }
                    if($postYn == "Y"){
                 ?>
                <li class="<?php echo $deadLine;?>">
                    <a href="<?php echo $list[$i]['href'] ?>"">
                    	<?php if($list[$i]['wr_file'] == 1){
    						    $wr_id = $list[$i]['wr_id'];
    						    $sql2 = " select bf_file from lt_board_file where bo_table='experience' and wr_id= {$list[$i]['wr_id']} ";
    						    $row2 = sql_fetch($sql2);
    						    $sum_img_url = G5_DATA_URL.'/file/experience/'.$row2['bf_file'];
						?>

                        <div class="cont">
                            <div class="photo">
                                <img src="<?php echo $sum_img_url?>" alt="">
                            </div>
                            <?php }?>
<!--                         	<div class="inner"> -->
                                <span class="category round"><?php echo $leftDate;?></span>                                        
                                <strong class="title bold ellipsis"><?php echo $list[$i]['wr_subject'];
                                if($bo_newYN == 'Y'){
                                ?>
                                <span class="new">N</span>
                                <?php }?>
                                </strong>
                                
                                <span class="text"><?php echo $list[$i]['wr_3']?></span>
                                <span class="date"><?php echo $list[$i]['wr_datetime']?></span>
<!--                             </div> -->
                        </div>
                    </a>
                </li>
                <?php 
                    } 
                }
                ?>
            </ul>
            <?php if($i == 0) echo "<p class=\"sct_noitem\">등록된 글이 없습니다.</p>\n"; ?>
			</div>
    		
<!--     	</div> -->
<!--     </div> -->

    <!-- 컨텐츠 종료 -->
<!-- </div> -->

		</div>
	</div>
</div>

<script>

     function bo_option_select(optionval){
 		
 		var wr_6 = $('#wr_6').val();
 		var new_wr_6 = '';
 		var wr_6Split = wr_6.split(',');
			if(wr_6 != ''){
 	      	for ( var i in wr_6Split ) {
 	        	 if(wr_6Split[i] != optionval){
 		        	 if(new_wr_6 == '') {
 			       		new_wr_6 = wr_6Split[i];
 		        	 }else {
 		        		 new_wr_6 += ','+wr_6Split[i];
 			       	 }
 	        	 }
 	      	}
			}
			if(new_wr_6 != ''){
				
		      	new_wr_6 += ','+optionval;
			}else {
				new_wr_6 = optionval;
			}
 		$('#wr_6').val(new_wr_6);
 		
 		$(".modal").css("display","none");
 		
 		$('#fboardlist').submit();
 	};


 	function bo_selected_option_delete(optionval){
 		var wr_6 = $('#wr_6').val();
 		var new_wr_6 = '';
			if(wr_6 != ''){
				var wr_6Split = wr_6.split(',');
				
		      	for ( var i in wr_6Split ) {
		        	 if(wr_6Split[i] != optionval){
			        	 if(new_wr_6 == '') {
				       		new_wr_6 = wr_6Split[i];
			        	 }else {
			        		 new_wr_6 += ','+wr_6Split[i];
				       	 }
		        	 }
		      	}
			}
 		$('#wr_6').val(new_wr_6);
 		$('#fboardlist').submit();
 	};

     
$(document).ready(function(){
	$(".bo_option").click(function() {
		try{
    	var target_id = $(this).attr("targetID");
    	
    	var optionName = $(this).text();
    	$('#optionModalTitle').html(optionName);

    	var optionList = "";
    	var $option = $('#'+target_id+' option');

    	$option.each(function() {
    		bo_value = $(this).val();
    		

        	if(bo_value != ""){
        		optionList += "<li>";
        		optionList += "<a onclick='bo_option_select(\""+bo_value+"\");' >";
        		optionList += "<span class=\"bold\">"+bo_value+"</span>";
    			optionList += "</a></li>";
            	
        	}
        });
    	
    	$('#optionModalList').html(optionList);

    	$("#optionModal").css("display","block");
		}catch(e){
			alert(e.message);
		}
    });

	$('li[name="onOffBar"]').click(function(){
		$.changeList($(this).attr('data'));
	});

	
// 	$.changeList = function(type){
// 		if(type == 'on'){
// 			$('li[name="onOffBar"][data="off"]').removeClass('on');
// 			$('li[name="onOffBar"][data="on"]').removeClass('on').addClass('on');
// 		} else {
// 			$('li[name="onOffBar"][data="on"]').removeClass('on');
// 			$('li[name="onOffBar"][data="off"]').removeClass('on').addClass('on');
// 		}
// 	};


	$.changeList = function(type){
		if(type == 'on'){
			$('.letz1 .on').css('display','');
			$('.letz1 .off').css('display','none');
			$('li[name="onOffBar"][data="off"]').removeClass('on');
			$('li[name="onOffBar"][data="on"]').removeClass('on').addClass('on');
		} else {
			$('.letz1 .off').css('display','');
			$('.letz1 .on').css('display','none');
			$('li[name="onOffBar"][data="on"]').removeClass('on');
			$('li[name="onOffBar"][data="off"]').removeClass('on').addClass('on');
		}
	};
	
	$.changeList('<?php echo $onoff?>');

	$(".close").click(function() {
    	$(".modal").css("display","none");
    });;

	$.bo_selected_option_create = function(){
		$('#bo_selected_option').html('');
		var wr_6 = $('#wr_6').val();
		var html = '';
		
		if(wr_6 != ''){
			var wr_6Split = wr_6.split(',');
		      for ( var i in wr_6Split ) {
		        html += '<li><span>'+wr_6Split[i]+'</span><a onclick="bo_selected_option_delete(\''+wr_6Split[i]+'\');" >닫기</a></li>'; 
		      }
			$('#bo_selected_option').html(html);
		}
	};

	$.bo_selected_option_create();
});


</script>
