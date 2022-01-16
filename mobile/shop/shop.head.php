<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
</head>
<body>
<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
    

    

		
<div id="wrap_all">
	
	<p class="skipNavi"></p>
        <div id="header" class="common">
			<!-- top_group -->
			<div class="top_group">
				<div class="title" id="gnbTitle">
					
				</div>
				<a href="#" class="top_close"><span class="blind">닫기</span></a>
			</div>
			<!-- logo_group -->
			<div class="logo_group">
				<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
				<h1 class="logo"><a href="#"><span class="blind">LIFELIKE</span></a></h1>
				<button type="button" class="btn_search"><span class="blind">검색</span></button>
				<button type="button" class="btn_cart"><span class="blind">장바구니</span></button>
			</div>
			<!-- nav_group -->
			<div class="nav_group">
				<h2 class="blind">메뉴</h2>
				<div class="menu">
					<ul>
						<?php
                        $sql = " select *
                                    from {$g5['menu_table']}
                                    where me_mobile_use = '1'
                                      and length(me_code) = '2'
                                    order by me_order, me_id ";
                        $result = sql_query($sql, false);
            
                        for($i=0; $row=sql_fetch_array($result); $i++) {
                        ?>
						<li>
							<a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>"><?php echo $row['me_name'] ?></a>
							<?php
                            $sql2 = " select *
                                        from {$g5['menu_table']}
                                        where me_mobile_use = '1'
                                          and length(me_code) = '4'
                                          and substring(me_code, 1, 2) = '{$row['me_code']}'
                                        order by me_order, me_id ";
                            $result2 = sql_query($sql2);
                            $result2Cnt = -1;
                            for ($k=0; $row2=sql_fetch_array($result2); $k++) {
                                $result2Cnt = $k;
                                if($k == 0){
                            ?>
							<div class="dep2">
								<ul>
							<?php } ?>
									<li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a>
									<?php
                                    $sql3 = " select *
                                                from {$g5['menu_table']}
                                                where me_mobile_use = '1'
                                                  and length(me_code) = '6'
                                                  and substring(me_code, 1, 4) = '{$row2['me_code']}'
                                                order by me_order, me_id ";
                                    $result3 = sql_query($sql3);
                                    $result3Cnt = -1;
                                    for ($l=0; $row3=sql_fetch_array($result3); $l++) {
                                        $result3Cnt = $l;
                                        if($l == 0){
                                    ?>
										<div class="dep3">
											<ul>
										<?php } ?>
												<li><a href="<?php echo $row3['me_link']; ?>" target="_<?php echo $row3['me_target']; ?>"><?php echo $row3['me_name'] ?></a></li>
									<?php }
									if($result3Cnt > -1) {
									?>
											</ul>
										</div>
									<?php }?>
									</li>
							<?php } 
							if($result2Cnt > -1) {
							?>
    							</ul>
    						</div>
    						<?php }?>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			
		</div>

        <nav id="aside" class="">
			<div class="inner">
				<div class="user_area">
					<!-- 로그인 후 -->
					<div class="user_photo">
						<span class="photo"><img src="<?php echo G5_MOBILE_URL; ?>/img/mb/sample/profile_photo.png" alt="" /></span>
						<button type="button" class="modify">p</button>
					</div>
					<div class="user_text">
						<span class="txt1">안녕하세요</span>
						<p class="txt2"><a href="#"><strong>김태평</strong>님</a></p>
					</div>
					<!-- //로그인 후 -->
					<!-- 로그인 전 -->
					<p class="logout_text"><a href="#"><span>로그인</span>해주세요</a></p>
					<!-- //로그인 전 -->
					<a href="#" class="btn_closed"><span class="blind">닫기</span></a>
				</div>
				<div class="comm_area">
					<ul class="count2">
						<li class="home"><a href="#"><span>HOME</span></a></li>
						<li class="set"><a href="#"><span>설정</span></a></li>
					</ul>
				</div>
				<div class="menu_area">
					<ul class="count3">
						<li><a href="#">제품</a></li>
						<li><a href="#">리스</a></li>
						<li><a href="#">케어</a></li>
						<li><a href="#">커뮤니티</a></li>
						<li><a href="#">매거진</a></li>
						<li><a href="#">큐레이션</a></li>
					</ul>
				</div>
				<div class="link_area">
					<ul class="count2">
						<li><a href="#">회사소개</a></li>
						<li><a href="#">이벤트</a></li>
						<li><a href="#">공지사항</a></li>
						<li><a href="#">고객센터</a></li>
					</ul>
				</div>
				<div class="banner_area">
					<a href="#"><img src="<?php echo G5_MOBILE_URL; ?>/img/mb/common/aside_banner.jpg" alt="" /></a>
				</div>
				<p class="foot_text">라이프라이크 Copyright © LIFELIKE All right reserved</p>
			</div>
		</nav>
        
   
	
    <div id="container">
    
