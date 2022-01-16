<?
include_once('./_common.php');

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
	$theme_mypage_file = G5_THEME_MSHOP_PATH.'/mypage.php';
	if(is_file($theme_mypage_file)) {
		include_once($theme_mypage_file);
		return;
		unset($theme_mypage_file);
	}
}

$g5['title'] = '마이페이지';
include_once(G5_MSHOP_PATH.'/_head.php');

// 쿠폰
$cp_count = 0;
$sql = " select cp_id
from {$g5['g5_shop_coupon_table']}
where mb_id IN ( '{$member['mb_id']}', '전체회원' )
and cp_start <= '".G5_TIME_YMD."'
and cp_end >= '".G5_TIME_YMD."' ";
$res = sql_query($sql);

for($k=0; $cp=sql_fetch_array($res); $k++) {
	if(!is_used_coupon($member['mb_id'], $cp['cp_id']))
		$cp_count++;
}

$push_cnt = 0;
$where = "";
$sql = "select token from lt_app_users where mb_id = '{$member['mb_id']}' ";
$res = sql_query($sql);
$tk_cnt = sql_num_rows($res);
if($tk_cnt) {
    $where = "and ( false";
    for($k=0; $tk=sql_fetch_array($res); $k++) {
        $where .= " or dest_phone like concat('%', '{$tk['token']}', '%') ";
    }
    $where .= ")";

    $sql = "select count(*) as cnt from lt_sms_sendhistory where sf_type = 'push' ".$where;

    $res = sql_fetch($sql);
    $push_cnt = $res['cnt'];
}

$sqlu = "select token from lt_app_users where mb_id = '".$member['mb_id']."' ";
$resu = sql_query($sqlu);
$tken=sql_fetch_array($resu);
$sqlct = "SELECT count(*) as pcount FROM lt_push_count_history WHERE token = '".$tken['token']."' AND nc = 'N' ";
$resultct = sql_query($sqlct);
$rowct=sql_fetch_array($resultct);
?>
<script>
	$('#header').html('');
</script>

<div class="wrap_all">
	<p id="skipNavi"><a href="#container">본문 바로가기</a></p>
	<!-- header -->
	<div class="user_info">
		<h1 class="blind">사용자정보-마이페이지</h1>
		<div class="inner">
			<div class="profile_photo">
				<?
				$mb_dir = substr($member['mb_id'],0,2);
				$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$member['mb_id'].'.gif';
				if (file_exists($icon_file)) {
					$icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$member['mb_id'].'.gif';
					?>
					<p class="photo"><img src="<?=$icon_url;?>" alt=""/></p>
				<? }else {?>
					<p class="photo"><img src="/img/default.jpg" alt="" /></p>
				<? } ?>
				<a href="<?=G5_BBS_URL; ?>/member_confirm.php?url=register_form.php"><button type="button" class="register"><span class="blind">프로필 등록</span></button></a>
			</div>
			<div class="edit_cont">

				<!--p class="grade"><strong>PLATINUM</strong><a href="#" class="noti">?</a></p-->
				<a href="#" class="name"><strong><?=$member['mb_name']; ?></strong></a>
				<div class="block"><a href="<?=G5_MSHOP_URL ?>/mypage_sendsns.php"><button type="button" class="btn_invite">친구초대하기</button></a></div>
				<a href="<?=G5_BBS_URL; ?>/member_confirm.php?url=register_form.php"><button type="button" class="register"><span class="arrow_r">정보수정</span></button></a>
			</div>
		</div>
		<div class="tbl_list item_box">
			<ul class="count4">
				<li class="item_1">
					<a href="<?=G5_BBS_URL ?>/point.php" target="_blank">
						<span>적립금</span>
						<strong><?=number_format($member['mb_point']); ?></strong>
					</a>
				</li>
				<li class="item_2">
					<a href="<?=G5_SHOP_URL ?>/coupon.php">
						<span>쿠폰</span>
						<strong><?=number_format($cp_count); ?></strong>
					</a>
				</li>
				<li class="item_3">
					<a href="<?=G5_SHOP_URL ?>/cart.php">
						<span>장바구니</span>
						<strong><?=get_boxcart_datas_count(); ?></strong>
					</a>
				</li>
				<li class="item_4 noti">
					<a href="/mobile/shop/push_list.php">
						<span>알림내역</span>
						<strong><?=$rowct['pcount']?></strong>
						<!-- em class="on"><span class="blind">yes</span></em -->
					</a>
				</li>
			</ul>
		</div>
		<a href="<?=G5_URL ?>/index.php" class="btn_closed" ><span class="blind">닫기</span></a>
	</div>
	<!-- //header -->

	<!-- container -->
	<div id="container">
		<div class="content mypage">
			<!-- 컨텐츠 시작 -->
			<div class="grid">
				<div class=" tbl_list">
					<ul class="count2 my_favorite">
						<li class="ico_1">
							<a href="./wishlist.php">
								<span>관심제품</span>
								<strong><?=get_wishlist_datas_count();?></strong>
							</a>
						</li>
						<li class="ico_2">
							<a href="./viewlist.php">
								<span>최근 본 제품</span>
								<strong><?=(get_session("ss_tv_idx"))?get_session("ss_tv_idx"):'0';?></strong>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="grid">
				<div class="title_bar">
					<h3 class="g_title_01">주문내역</h3>
					<a href="./orderinquiry.php" class="more btn_full arrow_r" name="od"><span>전체보기</span></a>
				</div>

				<div class="tab_cont_wrap">
					<div class="tab">
						<ul class="type1" id="mypageorderbtn">
							<li od_type='' class='<?=($od_type=='')?"on":""?>'><a ><span>전체</span></a></li>
							<li od_type='R' class='<?=($od_type=='R')?"on":""?>'><a ><span>리스</span></a></li>
							<li od_type='O' class='<?=($od_type=='O')?"on":""?>'><a ><span>제품</span></a></li>
							<li od_type='L' class='<?=($od_type=='L')?"on":""?>'><a ><span>세탁</span></a></li>
							<li od_type='K' class='<?=($od_type=='K')?"on":""?>'><a ><span>세탁보관</span></a></li>
							<li od_type='S' class='<?=($od_type=='S')?"on":""?>'><a ><span>수선</span></a></li>
						</ul>
					</div>
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
						<div class="gray_box alignC">
							<ul class="count5">
								<li><a href="#"><span class="block sm_txt">리스</span><strong class="big_txt <?=($orderview['od_type_r']!=0)?"point":"" ?>"><?=$orderview['od_type_r'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">제품</span><strong class="big_txt <?=($orderview['od_type_o']!=0)?"point":"" ?>"><?=$orderview['od_type_o'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">세탁</span><strong class="big_txt <?=($orderview['od_type_l']!=0)?"point":"" ?>"><?=$orderview['od_type_l'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">세탁보관</span><strong class="big_txt <?=($orderview['od_type_k']!=0)?"point":"" ?>"><?=$orderview['od_type_k'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">수선</span><strong class="big_txt <?=($orderview['od_type_s']!=0)?"point":"" ?>"><?=$orderview['od_type_s'] ?></strong></a></li>
							</ul>
						</div>
						<p class="ico_import red point_red">최근 1개월 동안 구매한 제품이 조회됩니다.</p>

						<!-- tab 1 -->
						<div class="tab_inner" style="display: block;">
							<div class="rolling_wrap roll_01" id="mypageorderview">
								<?
									// 최근 주문내역
								define("_ORDERINQUIRY_", true);
								$swiperslide = true;

								$limit = " limit 0, 5 ";
								include G5_MSHOP_PATH.'/orderinquiry.sub.php';
								?>
							</div>
						</div>
					</div>
					<script>
						var swiperColumn_three = new Swiper('.roll_01 .swiper-container', {
							slidesPerView: 'auto',
							spaceBetween: 10,
						});
					</script>
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
						location.href='./mypage.php?od_type='+od_type+'#od';
					})
				});

			</script>
			<!-- } 최근 주문내역 끝 -->

			<?

			$sqlcancelview = "select count(*) cnt
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
				<div class="title_bar">
					<h3 class="g_title_01">취소/교환/반품/해지 내역</h3>
					<a href="./orderinquiryclaim.php" class="more btn_full arrow_r"><span>전체보기</span></a>
				</div>
				<div class="gray_box alignR mt20">
					<ul class="count2">
						<li><span class="left">전체</span><strong class="right"><?=number_format($cancelview['cnt']) ?></strong>건</li>
						<li><span class="left">리스</span><strong class="right"><?=number_format($cancelview['od_type_r']) ?></strong>건</li>
						<li><span class="left">제품</span><strong class="right"><?=number_format($cancelview['od_type_o']) ?></strong>건</li>
						<li><span class="left">세탁</span><strong class="right"><?=number_format($cancelview['od_type_l']) ?></strong>건</li>
						<li><span class="left">세탁보관</span><strong class="right"><?=number_format($cancelview['od_type_k']) ?></strong>건</li>
						<li><span class="left">수선</span><strong class="right"><?=number_format($cancelview['od_type_s']) ?></strong>건</li>
					</ul>
				</div>
			</div>
			<div class="grid">
				<div class="title_bar">
					<h3 class="g_title_01">리스/케어서비스 내역</h3>
					<a href="./orderinquirycare.php" class="more btn_full arrow_r" name="care"><span>전체보기</span></a>
				</div>

				<div class="tab_cont_wrap">
					<div class="tab">
						<ul class="type1" id="mypagecarebtn">
							<li od_type='' class='<?=($od_type_care=='')?"on":""?>'><a ><span>전체</span></a></li>
							<li od_type='R' class='<?=($od_type_care=='R')?"on":""?>'><a ><span>리스</span></a></li>
							<li od_type='L' class='<?=($od_type_care=='L')?"on":""?>'><a ><span>세탁</span></a></li>
							<li od_type='K' class='<?=($od_type_care=='K')?"on":""?>'><a ><span>세탁보관</span></a></li>
							<li od_type='S' class='<?=($od_type_care=='S')?"on":""?>'><a ><span>수선</span></a></li>
						</ul>
					</div>
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
						<div class="gray_box alignC">
							<ul class="count4">
								<li><a href="#"><span class="block sm_txt">리스</span><strong class="big_txt <?=($careview['od_type_r']!=0)?"point":"" ?>"><?=$careview['od_type_r'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">세탁</span><strong class="big_txt <?=($careview['od_type_l']!=0)?"point":"" ?>"><?=$careview['od_type_l'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">세탁보관</span><strong class="big_txt <?=($careview['od_type_k']!=0)?"point":"" ?>"><?=$careview['od_type_k'] ?></strong></a></li>
								<li><a href="#"><span class="block sm_txt">수선</span><strong class="big_txt <?=($careview['od_type_s']!=0)?"point":"" ?>"><?=$careview['od_type_s'] ?></strong></a></li>
							</ul>
						</div>
						<div class="info_box">
							<p class="ico_import red point_red">
								세탁과 보관, 수선 서비스를 이용하기 전, 잔여 무료 횟수와 케어 가능한 제품을 확인해 주세요.<br/>
							</p>
							<p style="float:right;"><a href="<?=G5_SHOP_URL?>/care.php" class="arrow_r_green">바로가기</a></p>
						</div>
						<div style="clear:both;"></div>

						<!-- tab 1 -->
						<div class="tab_inner" style="display: block;">
							<div class="rolling_wrap roll_01 roll_02" id="mypagecareview">
								<?
									// 최근 주문내역
								$swiperslide = true;

								$is_care = "1";
								$limit = " limit 0, 5 ";

								if(isset($od_type_care) && $od_type_care != "") $od_type = $od_type_care;
								else $od_type = "";

								include G5_MSHOP_PATH.'/orderinquiry.sub.php';
								?>
							</div>
						</div>
					</div>
					<script>
						var swiperColumn_three = new Swiper('.roll_02 .swiper-container', {
							slidesPerView: 'auto',
							spaceBetween: 10,
						});
					</script>
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
						location.href='./mypage.php?od_type_care='+od_type+'#care';
					})
				});
			</script>
			<!-- } 최근 주문내역 끝 -->
			<div class="grid">
				<div class="title_bar">
					<h3 class="g_title_01">나의 전체 활동</h3>
					<a href="<?=G5_BBS_URL?>/my_activity.php" class="more btn_full arrow_r"><span>전체보기</span></a>
				</div>
				<div class="list">
					<ul class="type1 arrow_r">
						<li><a href="<?=G5_BBS_URL?>/my_activity.php?type=item">제품평</a></li>
						<li><a href="<?=G5_BBS_URL?>/my_activity.php?type=review">체험단 리뷰</a></li>
						<li><a href="<?=G5_BBS_URL?>/my_activity.php?type=online">온라인집들이</a></li>
						<li><a href="<?=G5_BBS_URL?>/my_activity.php?type=event">이벤트</a></li>
					</ul>
				</div>
				<div class="title_bar">
					<h3 class="g_title_01">고객센터</h3>
					<a href="<?=G5_BBS_URL?>/faq.php" class="more btn_full arrow_r"><span>전체보기</span></a>
				</div>
				<div class="list">
					<ul class="type1 arrow_r">
						<li><a href="<?=G5_BBS_URL?>/faq.php">FAQ</a></li>
						<li><a href="<?=G5_BBS_URL?>/qalist.php">1:1 문의하기</a></li>
					</ul>
				</div>
			</div>
			<!-- 컨텐츠 종료 -->
		</div>
	</div>
	<!-- //container -->
</div>
<script>
	$(function() {
		$(".win_coupon").click(function() {
			var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
			new_win.focus();
			return false;
		});
	});
	function member_leave(){
		return confirm('정말 회원에서 탈퇴 하시겠습니까?')
	}
</script>

<? include_once(G5_MSHOP_PATH.'/_tail.php'); ?>
