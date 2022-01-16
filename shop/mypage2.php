<?
include_once('./_common.php');
if (!$is_member){
	goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage2.php"));
}

if (G5_IS_MOBILE) {
	include_once(G5_MSHOP_PATH.'/mypage2.php');
	return;
}

$g5['title'] = '마이페이지';
$title = '마이페이지';
include_once('./_head.php');

// 쿠폰
$cp_count = 0;
$sql = " select cp_id from {$g5['g5_shop_coupon_table']} where mb_id IN ( '{$member['mb_id']}', '전체회원' ) and cp_start <= '".G5_TIME_YMD."' and cp_end >= '".G5_TIME_YMD."' ";
$res = sql_query($sql);

for($k=0; $cp=sql_fetch_array($res); $k++) {
	if(!is_used_coupon($member['mb_id'], $cp['cp_id']))
		$cp_count++;
}
?>

<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>

<!-- header -->
<div class="user_info">
	<h1 class="blind">사용자정보 마이페이지</h1>
	<div class="inner">
		<div class="profile_photo">
			<?
			$mb_dir = substr($member['mb_id'],0,2);
			$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$member['mb_id'].'.gif';
			if (file_exists($icon_file)) {
				$icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$member['mb_id'].'.gif';
				?>
				<p class="photo"><img src="<?=$icon_url;?>" alt=""/></p>
			<? } else {?>
				<p class="photo"><img src="/img/default.jpg" alt="" /></p>
			<? } ?>
			<p class="name"><strong><?=$member['mb_name']; ?></strong>님</p>
			<div class="block"><button type="button" class="btn_invite" id="btn_invite" onclick="$('#sendsns_popup').css('display','');">친구 초대하기</button></div>
			<a href="<?=G5_BBS_URL; ?>/member_confirm.php?url=register_form.php" class="btn01"><button type="button" class="register"><span class="arrow_r_gray">정보수정</span></button></a>
		</div>
		<div class="edit_cont">
			<div class="tbl_list item_box">
				<ul class="count3">
					<li class="item_1">
						<a href="<?=G5_BBS_URL ?>/point.php" >
							<span class="ico">적립금</span>
							<strong><em><?=number_format($member['mb_point']); ?></em> 원</strong>
						</a>
					</li>
					<li class="item_2">
						<a href="<?=G5_SHOP_URL ?>/coupon.php" >
							<span class="ico">쿠폰</span>
							<strong><em><?=number_format($cp_count); ?></em>장</strong>
						</a>
					</li>
					<li class="item_3">
						<a href="<?=G5_SHOP_URL ?>/cart.php" >
							<span class="ico">장바구니</span>
							<strong><em><?=get_boxcart_datas_count(); ?></em></strong>
						</a>
					</li>
				</ul>
			</div>
			<div class=" tbl_list my_favorite">
				<ul class="count2">
					<li class="ico_1">
						<a href="<?=G5_SHOP_URL ?>/wishlist.php">
							<span>관심제품</span>
							<strong><?=get_wishlist_datas_count();?></strong>
						</a>
					</li>
					<li class="ico_2">
						<a href="<?=G5_SHOP_URL ?>/viewlist.php">
							<span>최근 본 제품</span>
							<strong><?=(get_session("ss_tv_idx"))?get_session("ss_tv_idx"):'0';?></strong>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- //header -->

<!-- 최근 주문내역 시작 { -->

<!-- container -->
<div id="container">
	<div class="content mypage">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="title_bar none">
				<h2 class="g_title_01">주문내역</h2>
				<a href="./orderinquiry.php"  class="title-more" name="od"><span>전체보기</span></a>
			</div>

			<div class="tab_cont_wrap">
				<?
				$sqlorderview = "select  count(*) cnt
				,sum(IF(od_type='O', 1,0)) od_type_o
				,sum(IF(od_type='R', 1,0)) od_type_r
				,sum(IF(od_type='L', 1,0)) od_type_l
				,sum(IF(od_type='K', 1,0)) od_type_k
				,sum(IF(od_type='S', 1,0)) od_type_s
				from {$g5['g5_shop_order_table']}
				where mb_id = '{$member['mb_id']}'
				";
				$orderview = sql_fetch($sqlorderview);
				?>
				<div class="tab_cont">
					<div class="tab_inner">
						<div class="home_order">
							<ul class="count5" id="mypageorderbtn">
								<li od_type='' class='cursor <?=($od_type=='')?"active":""?>'><span class="block sm_txt">전체</span><strong class="big_txt <?=($orderview['od_type_r']!=0)?"point":"" ?>"><?=number_format($orderview['cnt']) ?></strong></li>
								<li od_type='R' class='cursor <?=($od_type=='R')?"active":""?>'><span class="block sm_txt">리스</span><strong class="big_txt <?=($orderview['od_type_r']!=0)?"point":"" ?>"><?=number_format($orderview['od_type_r']) ?></strong></li>
								<li od_type='O' class='cursor <?=($od_type=='O')?"active":""?>'><span class="block sm_txt">제품</span><strong class="big_txt <?=($orderview['od_type_o']!=0)?"point":"" ?>"><?=number_format($orderview['od_type_o']) ?></strong></li>
								<li od_type='L' class='cursor <?=($od_type=='L')?"active":""?>'><span class="block sm_txt">세탁</span><strong class="big_txt <?=($orderview['od_type_l']!=0)?"point":"" ?>"><?=number_format($orderview['od_type_l']) ?></strong></li>
								<li od_type='K' class='cursor <?=($od_type=='K')?"active":""?>'><span class="block sm_txt">세탁ㆍ보관</span><strong class="big_txt <?=($orderview['od_type_k']!=0)?"point":"" ?>"><?=number_format($orderview['od_type_k']) ?></strong></li>
								<li od_type='S' class='cursor <?=($od_type=='S')?"active":""?>'><span class="block sm_txt">수선</span><strong class="big_txt <?=($orderview['od_type_s']!=0)?"point":"" ?>"><?=number_format($orderview['od_type_s']) ?></strong></li>
							</ul>
						</div>
						<div class="fred">
							<i class="axi axi-info-outline" style="font-size:14px; letter-spacing:-0.5px; "> 최근 1개월 동안 구매한 제품이 조회됩니다.</i>
						</div>
						<br/>
						<div class="orderwrap">
							<?
							// 최근 주문내역
							define("_ORDERINQUIRY_", true);

							$limit = " limit 0, 5 ";
							include G5_SHOP_PATH.'/orderinquiry.sub2.php';
							?>
						</div>
					</div>
				</div>
				<script>
					$(function() {
						$(document).on("click", "#mypageorderbtn li", function() {
							$("#mypageorderbtn li").each(function() {
								$(this).removeClass("on");
							});
							$(this).removeClass("on").addClass("on");

							var od_type = $(this).attr("od_type");
							location.href='./mypage2.php?od_type='+od_type+'#od';
						})
					});

				</script>
			</div>
		</div>
		<!-- } 최근 주문내역 끝 -->

		<?

		$sqlcancelview = "select  count(*) cnt
		,sum(IF(od_type='O', 1,0)) od_type_o
		,sum(IF(od_type='R', 1,0)) od_type_r
		,sum(IF(od_type='L', 1,0)) od_type_l
		,sum(IF(od_type='K', 1,0)) od_type_k
		,sum(IF(od_type='S', 1,0)) od_type_s
		from {$g5['g5_shop_order_table']}
		where mb_id = '{$member['mb_id']}'
		and od_status_claim in ('주문취소','교환','반품','철회','해지')
		";
		$cancelview = sql_fetch($sqlcancelview);
		?>
		<div class="grid">
			<div class="title_bar none">
				<h2 class="g_title_01">취소/교환/환불/해지 내역</h2>
				<a href="./orderinquiryclaim.php" class="title-more"><span>전체보기</span></a>
			</div>
			<div class="home_order">
				<ul class="count6" id="mypagecarebtn">
					<li><span class="block sm_txt">전체</span><strong class="big_txt"><?=number_format($cancelview['cnt']) ?></strong></li>
					<li><span class="block sm_txt">리스</span><strong class="big_txt"><?=number_format($cancelview['od_type_r']) ?></strong></li>
					<li><span class="block sm_txt">제품</span><strong class="big_txt"><?=number_format($cancelview['od_type_o']) ?></strong></li>
					<li><span class="block sm_txt">세탁</span><strong class="big_txt"><?=number_format($cancelview['od_type_l']) ?></strong></li>
					<li><span class="block sm_txt">세탁ㆍ보관</span><strong class="big_txt"><?=number_format($cancelview['od_type_k']) ?></strong></li>
					<li><span class="block sm_txt">수선</span><strong class="big_txt"><?=number_format($cancelview['od_type_s']) ?></strong></li>
				</ul>
			</div>
		</div>

		<div class="grid">
			<div class="title_bar none">
				<h3 class="g_title_01">리스/케어서비스 내역</h3>
				<a href="./orderinquirycare.php" class="title-more" name="care"><span>전체보기</span></a>
			</div>

			<div class="tab_cont_wrap">
				<?
				$sqlcareview = "select  count(*) cnt
				,sum(IF(od_type='R', 1,0)) od_type_r
				,sum(IF(od_type='L', 1,0)) od_type_l
				,sum(IF(od_type='K', 1,0)) od_type_k
				,sum(IF(od_type='S', 1,0)) od_type_s
				from {$g5['g5_shop_order_table']}
				where mb_id = '{$member['mb_id']}' and ((od_type = 'R' and od_status = '리스중') or od_type in ('L','K','S'))
				";
				$careview = sql_fetch($sqlcareview);
				?>
				<div class="tab_cont">
					<div class="home_order">
						<ul class="count5" id="mypagecarebtn">
							<li od_type='' class='cursor <?=($od_type_care=='')?"active":""?>'><span class="block sm_txt">전체</span><strong class="big_txt <?=($careview['od_type_r']!=0)?"point":"" ?>"><?=number_format($careview['cnt']) ?></strong></li>
							<li od_type='R' class='cursor <?=($od_type_care=='R')?"active":""?>'><span class="block sm_txt">리스</span><strong class="big_txt <?=($careview['od_type_r']!=0)?"point":"" ?>"><?=number_format($careview['od_type_r']) ?></strong></li>
							<li od_type='L' class='cursor <?=($od_type_care=='L')?"active":""?>'><span class="block sm_txt">세탁</span><strong class="big_txt <?=($careview['od_type_l']!=0)?"point":"" ?>"><?=number_format($careview['od_type_l']) ?></strong></li>
							<li od_type='K' class='cursor <?=($od_type_care=='K')?"active":""?>'><span class="block sm_txt">세탁ㆍ보관</span><strong class="big_txt <?=($careview['od_type_k']!=0)?"point":"" ?>"><?=number_format($careview['od_type_k']) ?></strong></li>
							<li od_type='S' class='cursor <?=($od_type_care=='S')?"active":""?>'><span class="block sm_txt">수선</span><strong class="big_txt <?=($careview['od_type_s']!=0)?"point":"" ?>"><?=number_format($careview['od_type_s']) ?></strong></li>
						</ul>
					</div>
					<div class="info_box fred" style="font-weight:600; font-size:13px; letter-spacing:-0.5px; ">
						※ 세탁과 보관, 수선 서비스를 이용하기 전, 잔여 무료 횟수와 케어 가능한 제품을 확인해 주세요.
						<a href="<?=G5_SHOP_URL?>/care.php" class="btn floatR arrow_r_green bold">바로가기</a>
					</div>
					<div class="orderwrap">
						<?
						// 최근 주문내역
						define("_ORDERINQUIRY_", true);
						$is_care = "1";
						$limit = " limit 0, 5 ";

						if(isset($od_type_care) && $od_type_care != "") $od_type = $od_type_care;
						else $od_type = "";

						include G5_SHOP_PATH.'/orderinquiry.sub.php';
						?>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(function() {
				$(document).on("click", "#mypagecarebtn li", function() {
					$("#mypagecarebtn li").each(function() {
						$(this).removeClass("on");
					});
					$(this).removeClass("on").addClass("on");

					var od_type = $(this).attr("od_type");
					location.href='./mypage2.php?od_type_care='+od_type+'#care';
				})
			});
		</script>

		<div class="grid">
			<div class="divide_two box">
				<div class="box">
					<div class="title_bar none">
						<h2 class="g_title_01">나의 활동</h2>
						<a href="<?=G5_BBS_URL?>/my_activity.php" class="title-more"><span>전체보기</span></a>
					</div>
					<div class="list notiList_my">
						<ul class="type4 arrow_r">
							<li class="ico_1"><a href="<?=G5_BBS_URL?>/my_activity.php?type=item">제품평</a></li>
							<li class="ico_2"><a href="<?=G5_BBS_URL?>/my_activity.php?type=review">체험단 리뷰</a></li>
							<li class="ico_3"><a href="<?=G5_BBS_URL?>/my_activity.php?type=online">온라인집들이</a></li>
							<li class="ico_4"><a href="<?=G5_BBS_URL?>/my_activity.php?type=event">이벤트</a></li>
						</ul>
					</div>
				</div>
				<div class="box">
					<div class="title_bar none">
						<h2 class="g_title_01">고객센터</h2>
						<a href="<?=G5_BBS_URL?>/faq.php" class="title-more"><span>전체보기</span></a>
					</div>
					<div class="list notiList_customer">
						<ul class="type4 arrow_r">
							<li class="ico_2"><a href="<?=G5_BBS_URL?>/faq.php">FAQ</a></li>
							<li class="ico_1"><a href="<?=G5_BBS_URL?>/qalist.php">1:1 문의하기</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br/>
<br/>

<section class="popup_container layer" id="sendsns_popup" style="display: none;">
	<div class="inner_layer" style="top:100px;">
		<div class="grid">
			<div class="title_bar">
				<h1 class="g_title_01">친구 초대하기</h1>
			</div>

			<div class="grid gray_box cont">
				<h2 class="g_title_05">주소를 복사해서 친구, 지인들을 라이프라이크로 초대해보세요.</h2>
				<div class="inp_wrap">
					<div class="inp_ele r_btn">
						<div class="input"><input type="text" placeholder="" value="<?=G5_URL?>" id="clipboardURL"></div>
						<button type="button" class="btn green" onclick="copy_to_clipboard()">복사</button>
					</div>
				</div>
			</div>

			<div class="info_box">
				<p class="ico_import">안내사항</p>
				<ul class="hyphen">
					<li><em>회원가입 시 추천인 아이디를 입력하시면 추천인과 본인에게 적립금 1,000원씩 드립니다.</em></li>
					<li><em>추천인의 아이디를 정확히 입력해 주세요.</em></li>
				</ul>
			</div>

			<div class="title_bar none alignC">
				<h2 class="g_title_05">채널을 선택해 주세요.</h2>
			</div>
			<ul class="sns_link">
				<li><a href="#" class="sns naver" onclick="sendSns('naver','<?=G5_URL;?>','<?=$config['cf_title']?>')"><span>네이버로 초대하기</span></a></li>
				<li><a href="#" class="sns talk" onclick="sendSns('kakao','<?=G5_URL;?>','<?=$config['cf_title']?>')"><span>카카오톡으로 초대하기</span></a></li>
				<li><a href="#" class="sns facebook" onclick="sendSns('facebook','<?=G5_URL;?>','<?=$config['cf_title']?>')"><span>페이스북으로 초대하기</span></a></li>
			</ul>


		</div>
		<a href="#" class="btn_closed" onclick="$('#sendsns_popup').css('display','none');"><span class="blind">닫기</span></a>
	</div>
</section>

<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script>
	$(function() {
		$(".win_coupon").click(function() {
			var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
			new_win.focus();
			return false;
		});
	});

	function copy_to_clipboard() {
		var copyText = document.getElementById("clipboardURL");
		copyText.select();
		document.execCommand("Copy");
		alert("주소가 복사되었습니다. \n원하는 곳에 붙여넣기(Ctrl+V)해주세요.");
	}

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

	function member_leave()
	{
		return confirm('정말 회원에서 탈퇴 하시겠습니까?')
	}
</script>
<!-- } 마이페이지 끝 -->

<?
include_once("./_tail.php");
?>
