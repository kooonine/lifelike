<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가


?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>체험단 상세</span></h1>';
header += '<a href="#" onlick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>
<?php 


$nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?

$valDate = Trim($view['wr_7']); // 폼에서 POST로 넘어온 value 값('yyyy-mm-dd' 형식)

$leftDate = intval((strtotime($nDate)-strtotime($valDate)) / 86400); // 나머지 날짜값이 나옵니다.
if($leftDate == 0){
    $leftDate = '마감 D-day';
}else {
    if($valDate != ''){
        $leftDate = '마감 D'.$leftDate;
    }else {
        $leftDate = '상시 모집';
    }
}
?>

<div class="content sub community type4">
	<!-- 컨텐츠 시작 -->
	<div class="grid head new-grid">
        <div class="title_bar none">
            <span class="category round"><?php echo $leftDate;?></span>                
            <div class="btn_comm big">
							<?php 
							$sql = " select bg_flag from {$g5['board_good_table']}
                                    where bo_table = '{$bo_table}'
                                    and wr_id = '{$wr_id}'
                                    and mb_id = '{$member['mb_id']}'
                                    and bg_flag in ('good', 'nogood') ";
							$pickYN = sql_fetch($sql);
							?>
                            <button type="button" class="pick ico <?php if ($pickYN['bg_flag']) echo 'on';?>" id="btn_pick" href="<?php echo $good_href.'&amp;'.$qstr ?>"><span class="blind">찜</span><?php echo $view['wr_good']?></button>
                            <button type="button" class="shared" ><span class="blind">공유</span></button>
                        </div>
            <h2 class="g_title_01"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h2>
            <p class="g_title_02"><?php echo $list[$i]['wr_3']?></p>
            <p class="g_title_02"><?php echo $view['wr_datetime']?></p>
        </div>

        <!-- 와이드 타입 -->
        <div class="type-box wide">
            <ul>
                <li>
                    <span class="type-box-label">마감일</span>
                    <span class="type-box-value"><?php echo $view['wr_7'];?></span>
                </li>
            </ul>
        </div>

        <div class="detail_wrap">
            <!-- ?php
                        // // 파일 출력
                        // $v_img_count = count($view['file']);
                        // if($v_img_count) {
                        //     echo "<div class=\"photo\">\n";
                
                        //     for ($i=0; $i<=count($view['file']); $i++) {
                        //         if ($view['file'][$i]['view']) {
                        //             //echo $view['file'][$i]['view'];
                        //             echo get_view_thumbnail($view['file'][$i]['view']);
                        //         }
                        //     }
                
                        //     echo "</div>\n";
                        // }
                         ? -->
    		
    		<?php  if($view['wr_10'] == '1') {echo $view['wr_content_mobile'];} else {echo $view['wr_content'];} ?>
            <div class="detail_tag">
                <?php echo $view['wr_2']?>
            </div>
            <div class="btn_group">
				<a href="<?php echo $write_href ?>&wr_id=<?php echo $wr_id;?>"><button type="button" class="btn big green"><span>신청하기</span></button></a>
			</div>
        </div>
    </div>
	
</div>


<script>

$(document).ready(function(){
	
	$(".shared").click(function() {
		var imgUrl= $('.view_image img').attr('src');
		var wr_2 = '<?php echo $view['wr_2']?>'.replace('#','-')
		window.open('<?php echo $share_href;?>'+imgUrl+'&wr_2='+wr_2);
	 });	
	
	$("#btn_pick").click(function() {
		var href = $(this).attr('href');
		$.post(
				href,
		        { js: "on" },
		        function(data) {
		            if(data.error) {
		                alert(data.error);
		                return false;
		            }
					if(data.flag) {
						if(data.flag == 'ON'){
							$("#btn_pick").removeClass('on').addClass('on');
						}else {
							$("#btn_pick").removeClass('on');
						}
					}
		            if(data.count) {
		            	$("#btn_pick").text('');
		            	$("#btn_pick").append('<span class="blind">찜</span> '+data.count);
		            }
		        }, "json"
		    );
	 });
});

</script>