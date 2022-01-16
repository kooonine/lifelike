<?php
include_once('./_common.php');

// 오류시 공히 Error 라고 처리하는 것은 회원정보가 있는지? 비밀번호가 틀린지? 를 알아보려는 해킹에 대비한것
//$is_id = $_POST['is_id'];

// 회원아이디가 아닌 회원고유번호로 회원정보를 구한다.
$sql = "select * from `{$g5['g5_shop_item_use_table']}` where is_id = '{$is_id}' and is_confirm = '1' and is_best = '1'  ";
$row  = sql_fetch($sql);


if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/itemuse_best_view.php');
    return;
}

if($row){
    
    $thumbnail_width = 500;
    
    $is_star    = get_star($row['is_score']);
    $is_name    = get_text($row['is_name']);
    $is_subject = conv_subject($row['is_subject'],50,"…");
    //$is_content = ($row['wr_content']);
    $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);
    
    $hash = md5($row['is_id'].$row['is_time'].$row['is_ip']);
    $file_count = 0;
    $movie_count = 0;
    if($row['is_file']){
        $file['count'] = 0;
        $movie['count'] = 0;
        $fi_sql = " select * from lt_shop_item_use_file where is_id = '".$row['is_id']."' order by bf_no ";
        $fi_result = sql_query($fi_sql);
        while ($fi_row = sql_fetch_array($fi_result))
        {
            $filepath = G5_DATA_PATH.'/file/itemuse';
            $no = $fi_row['bf_no'];
            
            if($fi_row['bf_type'] == '0'){
                //movie
                $movie[$no]['path'] = G5_DATA_URL.'/file/itemuse';
                $movie[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                $movie[$no]['datetime'] = $fi_row['bf_datetime'];
                $movie[$no]['source'] = addslashes($fi_row['bf_source']);
                $movie[$no]['file'] = $fi_row['bf_file'];
                $movie['count']++;
            } else {
                $file[$no]['path'] = G5_DATA_URL.'/file/itemuse';
                $file[$no]['size'] = get_filesize($fi_row['bf_filesize']);
                $file[$no]['datetime'] = $fi_row['bf_datetime'];
                $file[$no]['source'] = addslashes($fi_row['bf_source']);
                $file[$no]['file'] = $fi_row['bf_file'];
                $file[$no]['image_width'] = $fi_row['bf_width'] ? $fi_row['bf_width'] : 640;
                $file[$no]['image_height'] = $fi_row['bf_height'] ? $fi_row['bf_height'] : 480;
                $file[$no]['image_type'] = $fi_row['bf_type'];
                
                $thumb = thumbnail($file[$no]['file'], $filepath, $filepath, 228, 228, false, false, 'center', false, $um_value='80/0.5/3');
                $file[$no]['thumb'] = $thumb;
                $file['count']++;
            }
        }
        $file_count = $file['count'];
        $movie_count = $movie['count'];
    }
    
    
    
?>
	<section class="popup_container layer" id="best_review_popup">
        <div class="inner_layer" style="top:50%; margin-top:-350px;">
            <div class="grid">
                <div class="title_bar">
                    <h2 class="g_title_01">베스트 리뷰</h2>
                </div>
                <div class="order_cont">
                    <div class="head">
                        <div class="user_bar">
                        	<?php 
                        	$mb_dir = substr($row['mb_id'],0,2);
                        	$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$row['mb_id'].'.gif';
            				if (file_exists($icon_file)) {
            				    $icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$row['mb_id'].'.gif';
            				?>
            				<span class="photo"><img src="<?php echo $icon_url;?>" alt=""/></span>
            				<?php }else {?>
            				<span class="photo"><img src="/img/default.jpg" alt="" /></span>
            				<?php } ?>
                            <span class="name"><?php echo $row['mb_id']; ?></span>
                        </div>
                    </div>
                    <div class="body">
                        <div class="cont">
                            <div class="info">
                                <span class="category black">Best</span>
                    <p><?php echo $is_subject; ?></p>
                                <div class="review_star">
                                    <div class="star small">
                                        <!-- width = 평점 2배 -->
                        <span class="star_num">별점 : <?php echo $is_star; ?></span>
                        <div class="star_bar"><span class="bar" style="width:<?php echo $is_star*20; ?>%;"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text">
                			<?php echo $is_content; // 사용후기 내용 ?>
                        </div>
                        
                        <?php if($file_count) { ?>
                        <div class="swiper-container best-review">
                            <div class="swiper-wrapper">
                                    <?php for ($j = 0; $j < $file_count; $j++) {
                			         $src = G5_DATA_URL.'/file/itemuse/'.$file[$j]['thumb'];
                			         echo '<div class="swiper-slide"><img src="'.$src.'" ></div>';
                                    }?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <?php } ?>
                        
                        <?php if($movie_count) { ?>
                        <div class="swiper-container best-review">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="view ico_video">
                        <?php for ($j = 0; $j < $movie_count; $j++) {
                            $src = G5_DATA_URL.'/file/itemuse/'.$movie[$no]['file'];
                            echo '<video controls width="350px"><source src="'.$src.'" type="video/mp4" width="350px">Your browser does not support the video tag.</video>';
                        }?>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <?php } ?> 
                        <script>
                            var swiperMain_visual = new Swiper('.best-review', {
                                slidesPerView: 'auto',
                                spaceBetween: 0,
                                loop: true,
                                pagination: {
                                    el: '.swiper-pagination',
                                    clickable: true,
                                },
                            });
                        </script>
                        <div class="btn_comm">
                        		<?
                				$sql = " select  count(*) cnt
                                                ,ifnull(sum(if(mb_id = '{$member['mb_id']}',1,0)),0) mb_cnt
                                        from    lt_shop_item_use_good
                                        where   is_id = '{$row['is_id']}' ";
                				$pickYN = sql_fetch($sql);
                
                				$good_href = './itemgood.php?is_id='.$row['is_id'].'&amp;good=good';
                				?>
                            <!-- 찜 눌르면 class="on" 추가 -->
                            <button type="button" class="pick ico itemusegood <? if ($pickYN['mb_cnt']) echo 'on';?>" href="<?=$good_href.'&amp;'.$qstr ?>"><span class="blind">찜</span><?=$pickYN['cnt']?></button>
                            <span class="date line floatR"><?php echo $row['is_time'] ?></span>
                        </div>
                    </div>
                </div>
                <a class="btn_closed" onclick="$('#best_review').empty();"><span class="blind">닫기</span></a>
            </div>
        </div>
    </section>
    
    
<?php     
} else {
    alert("잘못된 접근입니다.");
}
?>
