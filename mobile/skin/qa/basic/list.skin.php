<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>고객센터</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>
	
<!-- //lnb -->
<div class="content mypage sub">
	<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="tab">
				<ul class="type1">
					<li><a href="<?php echo G5_BBS_URL?>/faq.php"><span>FAQ</span></a></li>
					<li class="on"><a href="<?php echo G5_BBS_URL?>/qalist.php"><span>1:1 문의하기</span></a></li>
				</ul>
			</div>
			<div class="tab_cont_wrap">
				<div class="tab button_group">
					<ul class="type3 count4 tab_btn">
                        <?php echo $category_option ?>
                    </ul>
				</div>
				<div class="btn_group"><button type="button" class="btn big green" onclick="location.href='<?php echo $write_href ?>';"><span>문의하기</span></button></div>
			</div>	
			
    <div class="tab_cont">
    <!-- tab1 -->
    <div class="tab_inner">
    	<div class="list">
    		<ul class="type1">
                <?php
                for ($i=0; $i<count($list); $i++) {
                ?>
                <li>
                	<a href="<?php echo $list[$i]['view_href']; ?>">
                	<strong class="title ellipsis">[<?php echo $list[$i]['qa_category'];?>]<?php echo $list[$i]['subject']; ?></strong>
                	<div class="foot">
                    	<span class="date"><?php echo $list[$i]['date']; ?></span>
                    	<?php echo ($list[$i]['qa_status'] ? '<span class="state on">답변완료</span>' : '<span class="state off">답변대기</span>'); ?>
                    </div>
                    </a>
            	</li>
                <?php
                }
                if ($i == 0) echo '<p id="sps_empty">자료가 없습니다.</p>';
                ?>
    		</ul>
    	</div>
    </div>

<?php echo get_paging($config['cf_mobile_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
		
	</div>
</div>
