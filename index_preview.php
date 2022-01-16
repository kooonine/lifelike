<?php
$sub_menu = '80';
include_once('./_common.php');
include_once(G5_ADMIN_PATH.'/admin.lib.php');
auth_check($auth[substr($sub_menu,0,2)], "w");

try{
    $main_id = strip_tags($_POST['main_id']);
    $main_name = strip_tags($_POST['main_name']);
    $main_type1 = strip_tags($_POST['main_type1']);
    $main_type2 = strip_tags($_POST['main_type2']);
    
    $sql = " select * from lt_design_main where main_id = '{$main_id}' ";
    $row = sql_fetch($sql);
    $main_view_data = json_decode(str_replace('\\','',$row['main_view_data']), true);
    
    $main_view_json = array();
    if (is_checked('title_name')) $main_view_json['title_name'] = $_POST['title_name'];
    
    $image_regex = "/(\.(gif|jpe?g|png))$/i";
    $movie_regex = "/(\.(mov|mp4|avi|mkv))$/i";
    
    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
    
    $design_dir = G5_DATA_PATH.'/design/'.$main_id;
    @mkdir($design_dir, G5_DIR_PERMISSION);
    @chmod($design_dir, G5_DIR_PERMISSION);
    
    //echo $_POST['main_type1']."<br/>";
    //movie
    if ($_POST['main_type1'] == "movie")
    {
        
        $main_view_json['imgLinkYN'] = $_POST['imgLinkYN0'];
        $main_view_json['linkURL'] = $_POST['linkURL'][0];
        
        //동영상 이미지 파일업로드
        if (isset($_FILES['movieimg']) && is_uploaded_file($_FILES['movieimg']['tmp_name']))
        {
            $tmp_file  = $_FILES['movieimg']['tmp_name'];
            $filesize  = $_FILES['movieimg']['size'];
            $filename  = $_FILES['movieimg']['name'];
            $filename  = get_safe_filename($filename);
            
            if (!preg_match($image_regex, $filename)) {
                alert($filename . '은(는) 이미지 파일이 아닙니다.');
                
            } else {
                
                // 프로그램 원래 파일명
                $main_view_json['movieimgsource'] = $filename;
                $main_view_json['movieimgfilesize'] = $filesize;
                
                shuffle($chars_array);
                $shuffle = implode('', $chars_array);
                
                // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
                $main_view_json['movieimg'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);
                
                $dest_file = $design_dir.'/'.$main_view_json['movieimg'];
                
                // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
                $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['movieimg']['error'][$i]);
                
                // 올라간 파일의 퍼미션을 변경합니다.
                chmod($dest_file, G5_FILE_PERMISSION);
                
                if (!get_magic_quotes_gpc()) {
                    $main_view_json['movieimg'] = addslashes($main_view_json['movieimg']);
                }
            }
        } else {
            $main_view_json['movieimg'] = $main_view_data['movieimg'];
        }
        
        //동영상 파일업로드
        if (isset($_FILES['moviefile']) && is_uploaded_file($_FILES['moviefile']['tmp_name']))
        {
            $tmp_file  = $_FILES['moviefile']['tmp_name'];
            $filesize  = $_FILES['moviefile']['size'];
            $filename  = $_FILES['moviefile']['name'];
            $filename  = get_safe_filename($filename);
            
            if (!preg_match($movie_regex, $filename)) {
                alert($filename . '은(는) 동영상 파일이 아닙니다.');
            } else {
                
                // 프로그램 원래 파일명
                $main_view_json['moviefilesource'] = $filename;
                $main_view_json['moviefilefilesize'] = $filesize;
                
                shuffle($chars_array);
                $shuffle = implode('', $chars_array);
                
                // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
                $main_view_json['moviefile'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);
                
                $dest_file = $design_dir.'/'.$main_view_json['moviefile'];
                
                // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
                $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['moviefile']['error'][$i]);
                
                // 올라간 파일의 퍼미션을 변경합니다.
                chmod($dest_file, G5_FILE_PERMISSION);
                
                if (!get_magic_quotes_gpc()) {
                    $main_view_json['moviefile'] = addslashes($main_view_json['moviefile']);
                }
            }
        } else if($_POST['orgmoviefile'] && $_POST['orgmoviefile'] != "") {
            
            $main_view_json['moviefile'] = $_POST['orgmoviefile'];
        } else {
            $main_view_json['moviefile'] = "";
        }
    }
    else if ($_POST['main_type1'] == "image" || $_POST['main_type1'] == "imagetext" || $_POST['main_type1'] == "rolling" || $_POST['main_type1'] == "motion" || $_POST['main_type1'] == "banner")
    {
        $count = count($_POST['imgOrder']);
        //echo $count.'<br/>';
        
        $imgfile = array();
        
        //print_r($_FILES);
        
        for ($i=0; $i<$count; $i++)
        {
            $imgfile[$i] = array();
            $imgfile[$i]['imgOrder'] = $_POST['imgOrder'][$i];
            
            $imgfile[$i]['imgLinkYN'] = $_POST['imgLinkYN'.($i+1)];
            $imgfile[$i]['linkURL'] = $_POST['linkURL'][$i];
            
            if($_POST['imgTextYN'.($i+1)]) $imgfile[$i]['imgTextYN'] = $_POST['imgTextYN'.($i+1)];
            if($_POST['mainText'][$i]) $imgfile[$i]['mainText'] = $_POST['mainText'][$i];
            if($_POST['subText'][$i]) $imgfile[$i]['subText'] = $_POST['subText'][$i];
            
            if (isset($_FILES['imgFile']) && is_uploaded_file($_FILES['imgFile']['tmp_name'][$i]))
            {
                
                $tmp_file  = $_FILES['imgFile']['tmp_name'][$i];
                $filesize  = $_FILES['imgFile']['size'][$i];
                $filename  = $_FILES['imgFile']['name'][$i];
                $filename  = get_safe_filename($filename);
                
                if (!preg_match($image_regex, $filename)) {
                    alert($filename . '은(는) 이미지 파일이 아닙니다.');
                    
                } else {
                    
                    // 프로그램 원래 파일명
                    $imgfile[$i]['source'] = $filename;
                    $imgfile[$i]['filesize'] = $filesize;
                    
                    shuffle($chars_array);
                    $shuffle = implode('', $chars_array);
                    
                    // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
                    $imgfile[$i]['imgFile'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);
                    
                    $dest_file = $design_dir.'/'.$imgfile[$i]['imgFile'];
                    
                    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
                    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['imgFile']['error'][$i]);
                    
                    // 올라간 파일의 퍼미션을 변경합니다.
                    chmod($dest_file, G5_FILE_PERMISSION);
                    
                    if (!get_magic_quotes_gpc()) {
                        $imgfile[$i]['imgFile'] = addslashes($imgfile[$i]['imgFile']);
                    }
                    
                    
                }
            } else if($_POST['orgimgFile'][$i] && $_POST['orgimgFile'][$i] != "") {
                
                $imgfile[$i]['imgFile'] = $_POST['orgimgFile'][$i];
                
            } else {
                
                unset($imgfile[$i]);
            }
        }
        //print_r($imgfile);
        //$imgOrder  = array_column($imgfile, 'imgOrder');
        //array_multisort($imgOrder, SORT_ASC, $imgfile);
        
        $main_view_json['imgFile'] = $imgfile;
        
    } else if ($_POST['main_type1'] == "sns") {
        
        if (is_checked('hashtag')) $main_view_json['hashtag'] = $_POST['hashtag'];
        if (is_checked('imgOrder')) $main_view_json['imgOrder'] = $_POST['imgOrder'];
        if (is_checked('widget')) $main_view_json['widget'] = $_POST['widget'];
        if (is_checked('imgsize')) $main_view_json['imgsize'] = $_POST['imgsize'];
        
        if (is_checked('imgCol')) $main_view_json['imgCol'] = $_POST['imgCol'];
        if (is_checked('imgRow')) $main_view_json['imgRow'] = $_POST['imgRow'];
        if (is_checked('imgBorder')) $main_view_json['imgBorder'] = $_POST['imgBorder'];
        
        if (is_checked('imgDistance')) $main_view_json['imgDistance'] = $_POST['imgDistance'];
        
    } else if ($_POST['main_type1'] == "product") {
        
        if (is_checked('it_id_list')) {
            $it_id_list = explode(",", $_POST['it_id_list']);
            $it_id = array();
            for ($i = 0; $i < count($it_id_list); $i++) {
                if($it_id_list[$i] != "") $it_id[] = $it_id_list[$i];
            }
            $main_view_json['it_id'] = $it_id;
        }
        
    } else if ($_POST['main_type1'] == "subproduct") {
        if (is_checked('view_count')) $main_view_json['view_count'] = $_POST['view_count'];
        
        if (is_checked('it_id_list')) {
            $it_id_list = explode(",", $_POST['it_id_list']);
            $it_id = array();
            for ($i = 0; $i < count($it_id_list); $i++) {
                if($it_id_list[$i] != "") $it_id[] = $it_id_list[$i];
            }
            $main_view_json['it_id'] = $it_id;
        }
    }
    
    if(count($main_view_json) > 0)
    {
        $main_view_data = $main_view_json;
    }
    
}catch(Exception $e){
    echo print_r2($e);
}
echo "<!--";
echo print_r2($main_view_data);
echo "-->";
//exit;
include_once(G5_PATH.'/head.php');
?>

<!-- container -->
<div id="container">
<?
switch( $main_id )
{
    case 1 : {
        ?>
<script>
	$('#gnbTitle').html('<?=$main_view_data['title_name']?>');
</script>
<?
} break;
case 2 : {
	?>
<!-- visual -->
<div class="main_visual">
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<? for($i=0; $i<$main_type2; $i++) {
				$img_data = $main_view_data['imgFile'][$i];
				$link_url = $img_data['linkURL'];
				$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
				?>
				<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=$img_url?>" alt="" /></a></div>
			<? } ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
</div>
<script>
	var swiperMain_visual = new Swiper('.main_visual .swiper-container', {
		slidesPerView: 'auto',
		spaceBetween: 0,
		loop: true,
		autoplay: {
			delay: 4000,
			disableOnInteraction: false,
		},
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
	});
</script>
<?
} break;
case 3 : {
	?>
<div class="main_content">
	<?
	$movieimg_url = G5_DATA_URL.'/design/'.$main_id.'/'.$main_view_data['movieimg'];
	$moviefile_url = G5_DATA_URL.'/design/'.$main_id.'/'.$main_view_data['moviefile'];
	
	$link_url = $main_view_data['linkURL'];
	?>
	<!-- column_group -->
	<div class="column_group">
		<h3 class="blind"><?=$main_view_data['title_name']?></h3>
		<div class="column_one">
			<ul>
				<li>
					<?php if($main_view_data['moviefile'] != "") { ?>
					<video controls poster="<?=$movieimg_url?>" width="711" height="748" >
						<source src="<?=$moviefile_url?>" type="video/mp4" width="711" height="748" >
							Your browser does not support the video tag.
					</video>
					<?php } else { ?>
					<a href="<?=$link_url?>"><div class="photo"><img src="<?=$movieimg_url?>" alt="" width="711" height="748" /></div></a>
					<?php } ?>
				</li>
			</ul>
		</div>
	</div>
</div>
	<?
		} break;
		case 4 : {
			?>
<div class="main_content">
	<div class="column_group">
		<div class="column_one">
			<ul>
				<li>
					<div class="column_swiper">
						<div class="swiper-wrapper">
							<? for($i=0; $i<$main_type2; $i++) {
								$img_data = $main_view_data['imgFile'][$i];
								$link_url = $img_data['linkURL'];
								$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
								?>
								<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=$img_url?>" alt="" width="711" /></a></div>
							<? } ?>
						</div>
						<div class="swiper-pagination"></div>
					</div>
					<script>

						var swiperMain_visual = new Swiper('.column_swiper', {
							slidesPerView: 'auto',
							spaceBetween: 0,
							loop: true,
							autoplay: {
								delay: 4000,
								disableOnInteraction: false,
							},
							pagination: {
								el: '.swiper-pagination',
								clickable: true,
							},
						});
					</script>
				</li>
			</ul>
		</div>
	</div>
</div>

			<?
		} break;
		case 5 : {
			?>
<div class="main_content">
	<div class="column_group">
			<div class="column_two">
				<h3 class="blind"><?=$main_view_data['title_name']?></h3>
				<ul class="thumb_list col2">
					<?
					for($i=0; $i<count($main_view_data['it_id']); $i++) {
						$sql2 = "select it_id, it_name,it_basic,it_price, it_img1, it_id, it_rental_price, it_item_type from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1";
						$row2 = sql_fetch($sql2);
						if($row2){
							$img_data = $row2['it_img1'];
							$img_file = G5_DATA_PATH.'/item/'.$img_data;
							$link_url = G5_URL.'/shop/item.php?it_id='.$row2['it_id'];
							$img_url = G5_DATA_URL.'/item/'.$img_data;

							$sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '".$member['mb_id']."' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='".$row2['it_id']."' ";
							$rowwish = sql_fetch($sqlwish);
							?>
							<li>
								<a href="<?=$link_url ?>">
									<div class="photo"><img src="<?=$img_url ?>" alt="" /></div>
									<div class="cont ">
										<span class="title ellipsis"><?=$row2['it_name'] ?></span>
										<span class="txt ellipsis"><?=$row2['it_basic'] ?></span>
										<span class="price"><?=($row2['it_item_type'])?number_format($row2['it_rental_price']):number_format($row2['it_price']) ?>원</span>
									</div>
									<?
									echo "<div class=\"btn_comm big bottom\"><!-- 찜 눌르면 class=\"on\" 추가 --> ";

									echo "<button type=\"button\" onclick=\"javascript:item_wish(document.fitem, '".$row2['it_id']."');\" class=\"pick ico ".(($rowwish['wishis'] != '0')?'on':'')."\" it_id=\"".$row2['it_id']."\"><span class=\"blind\">찜</span>".$rowwish['cnt']."</button>";
									echo "</div>";
									?>
								</a>
							</li>
						<? } } ?>
					</ul>
					<!-- <a href="<?=G5_URL.'/shop/' ?>" class="btn_more"><span class="blind">더보기</span></a> -->
					<form name="fitem"><input type="hidden" name="it_id"><input type="hidden" name="url"></form>
					<script>
						function item_wish(f, it_id){
							f.it_id.value = it_id;
							f.url.value = "<?=G5_URL ?>/shop/wishupdate.php?it_id="+it_id;
							f.action = "<?=G5_URL ?>/shop/wishupdate.php";
							f.target = "_self";
							f.submit();
						}
					</script>
				</div>

	</div>
</div>
				<?
			} break;
			case 6 : {
				?>
<div class="main_content">
	<div class="column_group">
				<div class="column_three">
					<h3 class="blind">리케리페 서비스</h3>
					<ul>
						<? for($i=0; $i<$main_type2; $i++) {
							$img_data = $main_view_data['imgFile'][$i];
							$link_url = $img_data['linkURL'];
							$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
							?>
							<li><a href="<?=$link_url?>"><img src="<?=$img_url?>" alt="" /></a></li>
						<? } ?>
					</ul>
					<!-- <a href="#" class="btn_more"><span class="blind">더보기</span></a> -->
				</div>
			</div>
	</div>
</div>
			<?
		} break;
	}

	if($main_id > 6) {
	    
    switch( $main_type1 )
	{
		case "rolling" : {
			?>
			<!-- 브랜드 -->
			<div class="section_content brand">
				<h3 class="main_title"><?=$main_view_data['title_name']?></h3>
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<? for($i=0; $i<$main_type2; $i++) {
							$img_data = $main_view_data['imgFile'][$i];
							$link_url = $img_data['linkURL'];
							$img_file = G5_DATA_PATH.'/design/'.$main_id.'/'.$img_data['imgFile'];
							if ($img_data['imgFile'] && file_exists($img_file)) {
								$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
								?>
								<div class="swiper-slide"><a href="<?=$link_url?>"><div class="photo"><img src="<?=$img_url?>" alt="" /></div></a></div>
							<? } else { ?>
								<div class="swiper-slide"><a href="<?=$link_url?>"><div class="photo"><img src="<?=G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></div></a></div>
								<? }
						}?>

					</div>

					<div class="swiper-pagination"></div>

					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>
				<script>
					var swiper = new Swiper('.section_content.brand .swiper-container', {
						slidesPerView: 2,
						loop: true,
						centeredSlides: true,
						spaceBetween: 30,
						navigation: {
							nextEl: '.section_content.brand .swiper-button-next',
							prevEl: '.section_content.brand .swiper-button-prev',
							clickable: true,
						},
						breakpoints: {
							1024: {
								slidesPerView: 1,
								spaceBetween: 0,
							},
						},
					});
				</script>
			</div>

			<?
		} break;
		case "image" : {
			?>
			<div class="section_content event">
				<div class="fix_wrap">
					<h3 class="main_title"><?=$main_view_data['title_name']?></h3>
					<ul class="thumb_list col<?=$main_type2?>">
						<? for($i=0; $i<$main_type2; $i++) {
							$img_data = $main_view_data['imgFile'][$i];
							$link_url = $img_data['linkURL'];
							$img_file = G5_DATA_PATH.'/design/'.$main_id.'/'.$img_data['imgFile'];
							if ($img_data['imgFile'] && file_exists($img_file)) {
								$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
								?>
								<li>
									<a href="<?=$link_url?>"><div class="photo"><img src="<?=$img_url?>" alt="" /></div></a>

									<? if($img_data['mainText'] != null && $img_data['mainText'] != "") { ?>
										<div class="cont">
											<span class="title ellipsis"><?=$img_data['mainText'] ?></span>
											<span class="txt"><?=$img_data['subText']?></span>
										</div>
									<? } ?>
								</li>
							<? } else { ?>
								<li><a href="<?=$link_url?>"><div class="photo"><img src="<?=G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></div></a></li>
								<? }
						}?>
					</ul>
				</div>
			</div>
			<?
		} break;
		case "imagetext" : {
			?>
			<div class="section_content magazine">
				<div class="inner">
					<h3 class="main_title"><?=$main_view_data['title_name'] ?></h3>
					<div class="swiper-container">
						<ul class="swiper-wrapper">
							<? for($i=0; $i<$main_type2; $i++) {
								$img_data = $main_view_data['imgFile'][$i];
								$link_url = $img_data['linkURL'];
								$img_file = G5_DATA_PATH.'/design/'.$main_id.'/'.$img_data['imgFile'];
								$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
								?>
								<li class="swiper-slide">
									<div class="photo"><img src="<?=$img_url?>" alt="" /></div>
									<div class="cont">
										<span class="title"><?=$img_data['mainText'] ?></span>
										<span class="txt"><?=$img_data['subText']?></span>
										<a href="<?=$link_url?>" class="btn_seeMore">See More</a>
									</div>
								</li>
							<? } ?>
						</ul>
					</div>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>
				<script>
					var swiper = new Swiper('.section_content.magazine .swiper-container', {
						slidesPerView: 2,
						spaceBetween: 15,
						loop: true,
						navigation: {
							nextEl: '.section_content.magazine .swiper-button-next',
							prevEl: '.section_content.magazine .swiper-button-prev',
						},
					});
				</script>
			</div>
			<?
		} break;
		case "banner" : {
			?>
			<div class="banner_bar">
				<div class="rolling_wrap swiper-container">
					<div class="swiper-wrapper">
						<? for($i=0; $i<$main_type2; $i++) {
							$img_data = $main_view_data['imgFile'][$i];
							$link_url = $img_data['linkURL'];
							$img_file = G5_DATA_PATH.'/design/'.$main_id.'/'.$img_data['imgFile'];
							if ($img_data['imgFile'] && file_exists($img_file)) {
								$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
								?>
								<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=$img_url?>" alt="" /></a></div>
							<? } else { ?>
								<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=G5_MOBILE_URL; ?>/img/theme_img.jpg"  alt="" /></a></div>
								<? }
						}?>
					</div>
				</div>
				<script>
					var swiper = new Swiper('.banner_bar .swiper-container', {
						autoplay: {
							delay: 4000,
						},
						loop: true,
					});
				</script>
			</div>
			<?
		} break;
		case "motion" : {
			?>
			<div class="product_all">
				<div class="banner fix_wrap">
					<h3 class="blind"><?=$main_view_data['title_name']?></h3>

					<div class="banner_bar_swiper">
						<div class="swiper-wrapper">
							<? for($i=0; $i<$main_type2; $i++) {
								$img_data = $main_view_data['imgFile'][$i];
								$link_url = $img_data['linkURL'];
								$img_file = G5_DATA_PATH.'/design/'.$main_id.'/'.$img_data['imgFile'];
								if ($img_data['imgFile'] && file_exists($img_file)) {
									$img_url = G5_DATA_URL.'/design/'.$main_id.'/'.$img_data['imgFile'];
									?>
									<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=$img_url?>" alt="" /></a></div>
								<? } else { ?>
									<div class="swiper-slide"><a href="<?=$link_url?>"><img src="<?=G5_MOBILE_URL; ?>/img/theme_img.jpg"  alt="" /></a></div>
									<? }
							}?>
						</div>
						<div class="swiper-pagination"></div>
					</div>

					<script>
						var swiperMain_visual = new Swiper('.banner_bar_swiper', {
							slidesPerView: 'auto',
							spaceBetween: 0,
							loop: true,
							autoplay: {
								delay: 4000,
								disableOnInteraction: false,
							},
							pagination: {
								el: '.swiper-pagination',
								clickable: true,
							},
						});
					</script>
				</div>
			</div>

			<?
		} break;
		case "subproduct" : {
			?>
			<!-- 상품 -->
			<div class="product_all">
				<!-- 신상품 -->
				<div class="section_content">
					<div class="fix_wrap">
						<h3 class="main_title"><?=$main_view_data['title_name']?></h3>
						<ul class="thumb_list col<?=($main_type2=="4")?$main_type2:$main_type2."add"?>">
							<?
							for($i=0; $i<count($main_view_data['it_id']); $i++) {
								$sql2 = "select it_name, it_price, it_img1, it_id, it_basic, it_rental_price, it_item_type from lt_shop_item where it_id = '{$main_view_data['it_id'][$i]}' and it_use = 1";
								$row2 = sql_fetch($sql2);

								if($row2) {
									$link_url = G5_URL.'/shop/item.php?it_id='.$row2['it_id'];
									$img_data = $row2['it_img1'];
									$img_url = G5_DATA_URL.'/item/'.$img_data;
									?>
									<li>
										<a href="<?=$link_url?>">
											<div class="photo"><img src="<?=$img_url?>" alt="" /></div>
											<div class="cont">
												<strong class="title bold ellipsis"><?=$row2['it_name']?></strong>
												<span class="text ellipsis"><?=$row2['it_basic']?></span>
												<span class="price"><?=($row2['it_item_type'])?number_format($row2['it_rental_price']):number_format($row2['it_price']) ?>원</span>
											</div>
										</a>
									</li>
								<? } } ?>
							</ul>
							<!-- a href="#" class="btn_more"><span class="blind">더보기</span></a -->
						</div>
					</div>
				</div>
				<?
			} break;
			case "movie" : {

				$movieimg_url = G5_DATA_URL.'/design/'.$main_id.'/'.$main_view_data['movieimg'];
				$moviefile_url = G5_DATA_URL.'/design/'.$main_id.'/'.$main_view_data['moviefile'];
				
				$link_url = $main_view_data['linkURL'];
								
				?>
				<div class="section_content make">
					<div class="fix_wrap">
						<h3 class="main_title"><?=$main_view_data['title_name']?></h3>
						<div class="video_container">
						
							<?php if($main_view_data['moviefile'] != "") { ?>
							<video controls poster="<?=$movieimg_url?>" width="1000">
								<source src="<?=$moviefile_url?>" type="video/mp4" width="1000">
									Your browser does not support the video tag.
							</video>
							<?php } else { ?>
							<a href="<?=$link_url?>"><div class="photo"><img src="<?=$movieimg_url?>" alt="" /></div></a>
							<?php } ?>
						</div>
					</div>
				</div>
					<?
				} break;
				case "sns" : {
					?>
					<div class="section_content instagram">
						<div class="fix_wrap">
							<h3 class="main_title"><?=$main_view_data['title_name']?></h3>
							<ul class="thumb_list">
								<li><a href="#"><img src="img/pc/main/sample_instagram_1.jpg" alt="instagram photo" /></a>
								</li>
								<li><a href="#"><img src="img/pc/main/sample_instagram_2.jpg" alt="instagram photo" /></a>
								</li>
								<li><a href="#"><img src="img/pc/main/sample_instagram_3.jpg" alt="instagram photo" /></a>
								</li>
								<li><a href="#"><img src="img/pc/main/sample_instagram_4.jpg" alt="instagram photo" /></a>
								</li>
								<li><a href="#"><img src="img/pc/main/sample_instagram_5.jpg" alt="instagram photo" /></a>
								</li>
								<li><a href="#"><img src="img/pc/main/sample_instagram_6.jpg" alt="instagram photo" /></a>
								</li>
							</ul>
						</div>
					</div>
					<?
				} break;
				default : {
					?>



					<?
				} break;
			}


		}

		?>

	</div>
</div>
<!-- //container -->

<?
include_once(G5_PATH.'/tail.php');

if($preview_main_id){
	echo "<script>$('#header').html('');$('#footer').html('');</script>";
}
?>