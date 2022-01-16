<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<script src="<?=G5_JS_URL; ?>/viewimageresize.js"></script>
<?
$nDate = date("Y-m-d",time()); // 오늘 날짜를 출력하겠지요?
$leftDate = "";
if($view['wr_7'] != ""){
	$valDate = Trim($view['wr_7']); // 폼에서 POST로 넘어온 value 값('yyyy-mm-dd' 형식)
	$leftDate = intval((strtotime($nDate)-strtotime($valDate)) / 86400); // 나머지 날짜값이 나옵니다.
	if($leftDate == 0){
		$leftDate = '마감 D-day';
	} else {
		if($valDate != ''){
			$leftDate = '마감 D'.$leftDate;
		} else {
			$leftDate = '상시 모집';
		}
	}
}
?>
<!-- container -->
<div id="container" class="no_title">
	<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
	<div class="content sub community type4">
		<!-- 컨텐츠 시작 -->
		<div class="grid head new-grid">
			<div class="title_bar none">
				<ul>
					<li>
						<span class="subject"><?=$view['wr_subject'] ?></span>
						<? if($leftDate != ""){?>
							&nbsp;<span class="category round"><?=$leftDate;?></span>
						<? } ?>

						<!-- 찜 클릭시 class="on" -->
						<?
						$sql = " select bg_flag from {$g5['board_good_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and mb_id = '{$member['mb_id']}' and bg_flag in ('good', 'nogood') ";
						$pickYN = sql_fetch($sql);

						if($_SESSION['ss_mb_id']){
							$likebtn = "location.href='".$good_href.'&'.$qstr."'";
						} else {
							$likebtn = "func_confirm('회원만 이용가능합니다. 로그인 하시겠습니까?','/bbs/login.php')";
						}
						?>
						<div class="btn_like_share">
						<? if($board['bo_use_good']) {?>
							<button type="button" id="btn_pick" onclick="<?=$likebtn?>"><i class="axi <?=$pickYN['bg_flag']?'axi-thumbs-up fmint':'axi-thumbs-o-up';?> " > <span style="color:#000;"><?=$view['wr_good']?></span></i></button>
						<?} ?>
							<button type="button" onclick="$('#sendsns_popup').css('display','');"><i class="axi axi-share"></i></button>
						</div>
						<div class="clear"></div>
					</li>
					<li>
						<span class="subsubject"><?php echo ($board['bo_use_view'] == '1' || $board['bo_use_view_summary'] == '1')?$view['wr_3']:'' ?></span>
						<span class="datetime"><?php echo ($board['bo_use_view'] == '1' || $board['bo_use_view_datetime'] == '1')?$view['wr_datetime']:'' ?></span>
						<div class="clear"></div>
					</li>
				</ul>
				<!--
				<div class="user-info">
					<span class="user-photo"><?=get_member_profile_img($view['mb_id']); ?></span>
					<span class="user-name"><?=$view['mb_id'] ?></span>
				</div>
				-->
			</div>

			<!-- 와이드 타입 -->
			<? if($view['wr_7'] != ""){?>
			<div class="type-box wide">
				<ul>
					<li>
						<span class="type-box-label">마감일</span>
						<span class="type-box-value"><?=$view['wr_7'];?></span>
					</li>
				</ul>
			</div>
			<? }?>

			<? if($view['wr_6'] != ""){?>
			<div class="type-box">
				<ul>
					<? for ($i=1; $i<10; $i++) {
						if($board['bo_'.$i.'_subj'] != ''){
							$detail_category = explode(',', $view['wr_6']);
						?>
						<li>
							<span class="type-box-label"><?=$board['bo_'.$i.'_subj']?></span>
							<span class="type-box-value"><? if($detail_category[$i-1] != ''){ echo $detail_category[$i-1];}else{echo '선택안함';}?></span>
						</li>
						<?
						}
					}?>
				</ul>
			</div>
			<? } ?>

			<? if($view['wr_8'] != ""){?>
			<div class="star-point">
				<span class="num"><? if($view['wr_8'] != ''){echo $view['wr_8']/2; } else {echo '0';}?></span>
				<div class="star">
					<!-- width = 평점 2배 -->
					<div class="star-bar"><span class="bar" style="width:<? if($view['wr_8'] != ''){echo $view['wr_8']*10; } else {echo '0';}?>%;"></span></div>
				</div>
			</div>
			<? } ?>

			<div class="detail_wrap">
				<?
				// 파일 출력
				/*$v_img_count = count($view['file']);
				if($v_img_count) {
					echo "<div class=\"photo\">\n";

					for ($i=0; $i<=count($view['file']); $i++) {
						if ($view['file'][$i]['view']) {
							//echo $view['file'][$i]['view'];
							echo get_view_thumbnail($view['file'][$i]['view']);
						}
					}

					echo "</div>\n";
				}*/
				 ?>

				<?=$view['wr_content']?>
			</div>

			<? if($view['wr_2'] != ""){?>
			<div class="detail_tag">
				<?=$view['wr_2']?>
			</div>
			<? } ?>

			<div class="detail_list">
				<ul>
				<?if (isset($prev['wr_id']) && $prev['wr_id']) { ?>
					<li class="detail_list_prev">
						<a href="<?=$prev_href ?>"><?=$prev_wr_subject ?></a>
						<span class="detail_list_date"><?=substr($prev_wr_date,0,10) ?></span>
					</li>
				<?} ?>
				<?if (isset($next['wr_id']) && $next['wr_id']) { ?>
					<li class="detail_list_next">
						<a href="<?=$next_href ?>"><?=$next_wr_subject ?></a>
						<span class="detail_list_date"><?=substr($next_wr_date,0,10) ?></span>
					</li>
				<?} ?>
				</ul>
			</div>

			<div class="btn_group">
			<? if($board['bo_use_userform'] == "1") {?>
				<?if($is_member) {?>
				<a href="/bbs/write.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id;?>"><button type="button" class="btn big green"><span>신청하기</span></button></a>
				<?} else {?>
				<button type="button" class="btn big green" onclick="alert('로그인이 필요한 서비스입니다.');location.href='./login.php?url='+encodeURIComponent('/bbs/write.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id;?>');"><span>신청하기</span></button>
				<?}?>
			<? } else { ?>
				<a href="<?=$list_href ?>"><button type="button" class="btn big border"><span>목록</span></button></a>
				<? if($view['mb_id'] == $member['mb_id']){?>
				<button type="button" class="btn big border" onclick="del('<?=$delete_href ?>');";><span>삭제</span></button>
				<button type="button" class="btn big green" onclick="location.href='<?=$update_href ?>'";><span>수정</span></button>
				<? }?>
			<? } ?>
			</div>
		</div>

		<? if($view['wr_5'] != ''){?>
		<div class="grid">
			<div class="title_bar">
				<h3 class="g_title_01">관련제품</h3>
			</div>
			<div class="pdt_rolling pdt1">
				<div class="item_row_list swiper-container">
					<ul class="swiper-wrapper">
						<?

						   $itemList = explode(',', $view['wr_5']);

						   for($i=0; $i<count($itemList); $i++){
							$sql2 = " select * from lt_shop_item where it_id = '{$itemList[$i]}' and it_use = 1";
							$row2 = sql_fetch($sql2);
							if(!$row2) continue;
							$link_url = G5_URL.'/shop/item.php?it_id='.$row2['it_id'];
							?>
							<li class="swiper-slide">
								<a href="<?=$link_url?>">
									<div class="photo">
									<?
										$img_data = $row2['it_img1'];
										$img_file = G5_DATA_PATH.'/item/'.$img_data;

										if ($img_data && file_exists($img_file)) {
											$img_url = G5_DATA_URL.'/item/'.$img_data;

									?>
											<img src="<?=$img_url?>" alt="" />
									<? } else { ?>
											<img src="<?=G5_MOBILE_URL; ?>/img/theme_img.jpg"  alt="" />
									<? }	?>
									</div>
									<div class="cont">
										<div class="inner">
											<strong class="title bold line2"><?=$row2['it_name']?></strong>
											<span class="price"><?=($row2['it_item_type'])?number_format($row2['it_rental_price']):number_format($row2['it_price']) ?> 원</span>
										</div>
									</div>
								</a>
							</li>
						<? }?>

					</ul>
				</div>
				<script>
					var swiperColumn_three = new Swiper('.pdt1 .swiper-container', {
						slidesPerView: 'auto',
						spaceBetween: 10,
						//loop: true,
					});
				</script>
			</div>
		</div>
		<? }?>

		<? if($board['bo_use_userform'] != "1" && $board['bo_use_comment']) {
			include_once(G5_BBS_PATH.'/view_comment.php');
		}?>
	</div>
</div>

<!-- popup -->
<section class="popup_container layer" id="sendsns_popup" style="display: none;">
	<div class="inner_layer" style="top:100px; width:450px; margin-left:-225px;">
		<div class="grid">
			<div class="title_bar">
				<h2 class="g_title_01">공유</h2>
			</div>
			<div class="border_box alignC none">
				<p class="sm tb_cell">공유 할 채널을 선택 해 주세요.</p>
			</div>
			<ul class="sns_link">
				<li><a href="#" class="sns naver" onclick="sendSns('naver','<?=$_SERVER['HTTP_HOST'].$G5_BBS_URL.$_SERVER['REQUEST_URI'];?>','<?=$view['wr_subject']?>')"><span>네이버로 공유하기</span></a></li>
				<li><a href="#" class="sns talk" onclick="sendSns('kakao','<?=$_SERVER['HTTP_HOST'].$G5_BBS_URL.$_SERVER['REQUEST_URI'];?>','<?=$view['wr_subject']?>')"><span>카카오로 공유하기</span></a></li>
				<li><a href="#" class="sns facebook" onclick="sendSns('facebook','<?=$_SERVER['HTTP_HOST'].$G5_BBS_URL.$_SERVER['REQUEST_URI'];?>','<?=$view['wr_subject']?>')"><span>페이스북으로 공유하기</span></a></li>
			</ul>
			<a href="#" class="btn_closed" onclick="$('#sendsns_popup').css('display','none');"><span class="blind">닫기</span></a>
		</div>
	</div>
</section>
<!-- //popup -->

<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script>

$(document).ready(function(){

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
						} else {
							$("#btn_pick").removeClass('on');
						}
					}
					if(data.count) {
						$("#btn_pick").text('');
						$("#btn_pick").append('<span class="blind">찜</span>'+data.count);
					}
				}, "json"
			);
	 });
});


Kakao.init('7bc3289136239ac358ca94d102d590c7');
function sendSns(sns, url, txt)
{
	var o;
	var _url = encodeURIComponent(url);
	var _txt = encodeURIComponent(txt);
	var _br  = encodeURIComponent('\r\n');

	switch(sns)
	{
		case 'facebook':
			o = {
				method:'popup',
				url:'http://www.facebook.com/sharer/sharer.php?u=' + _url
			};
			break;

		case 'twitter':
			o = {
				method:'popup',
				url:'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
			};
			break;

		case 'me2day':
			o = {
				method:'popup',
				url:'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
			};
			break;
		case 'naver':
			o = {
				method:'popup',
				url:'https://share.naver.com/web/shareView.nhn?url=' +_url + '&title=' + _txt
			};
			break;
		case 'kakao':
			Kakao.Link.sendDefault({
				objectType: 'feed',
				content: {
				  title: '게시글 공유하기',
				  description: txt,
				  imageUrl: '<?=$imgUrl;?>',
				  link: {
					mobileWebUrl: url,
					webUrl: url
				  }
				},
				social: {
				  likeCount: 0,
				  commentCount: 0,
				  sharedCount: 0
				},
				buttons: [
				  {
					title: '웹으로 보기',
					link: {
					  mobileWebUrl: url,
					  webUrl: url
					}
				  },
				  {
					title: '앱으로 보기',
					link: {
					  mobileWebUrl: url,
					  webUrl: url
					}
				  }
				]
			  });
			return false;
			break;
		case 'kakaotalk':
			o = {
				method:'web2app',
				param:'sendurl?msg=' + _txt + '&url=' + _url + '&type=link&apiver=2.0.1&appver=2.0&appid=&appname=' + encodeURIComponent(''),
				a_store:'itms-apps://itunes.apple.com/app/id362057947?mt=8',
				g_store:'market://details?id=com.kakao.talk',
				a_proto:'kakaolink://',
				g_proto:'scheme=kakaolink;package=com.kakao.talk'
			};
			break;

		case 'kakaostory':
			o = {
				method:'web2app',
				param:'posting?post=' + _txt + _br + _url + '&apiver=1.0&appver=2.0&appid=&appname=' + encodeURIComponent(''),
				a_store:'itms-apps://itunes.apple.com/app/id486244601?mt=8',
				g_store:'market://details?id=com.kakao.story',
				a_proto:'storylink://',
				g_proto:'scheme=kakaolink;package=com.kakao.story'
			};
			break;

		case 'band':
			o = {
				method:'web2app',
				param:'create/post?text=' + _txt + _br + _url,
				a_store:'itms-apps://itunes.apple.com/app/id542613198?mt=8',
				g_store:'market://details?id=com.nhn.android.band',
				a_proto:'bandapp://',
				g_proto:'scheme=bandapp;package=com.nhn.android.band'
			};
			break;


		default:
			alert('지원하지 않는 SNS입니다.');
			return false;
	}

	switch(o.method)
	{
		case 'popup':
			window.open(o.url);
			break;

		case 'web2app':
			if(navigator.userAgent.match(/android/i))
			{
				// Android
				setTimeout(function(){ location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end'}, 100);
			}
			else if(navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i))
			{
				// Apple
				setTimeout(function(){ location.href = o.a_store; }, 200);
				setTimeout(function(){ location.href = o.a_proto + o.param }, 100);
			}
			else
			{
				alert('이 기능은 모바일에서만 사용할 수 있습니다.');
			}
			break;
	}
}

</script>
