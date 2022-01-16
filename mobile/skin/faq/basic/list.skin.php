<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$faq_skin_url.'/style.css">', 0);
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
					<li class="on"><a href="<?php echo G5_BBS_URL?>/faq.php"><span>FAQ</span></a></li>
					<li><a href="<?php echo G5_BBS_URL?>/qalist.php"><span>1:1 문의하기</span></a></li>
				</ul>
			</div>

            <div class="tab_cont_wrap">
            	<div class="tab button_group">
            		<ul class="type3 count4 tab_btn">
            			<li class="<?php echo ($fa_category1 == "")?"on":"" ?>"><a href="<?php echo $category_href?>"><span>전체</span></a></li>
                        <?php for ($i = 0; $i < count($fa_category1_arr); $i++) {
                            echo '<li onclick="location.href=\''.$category_href.'?fa_category1='.$fa_category1_arr[$i].'\'" class="'.(($fa_category1_arr[$i]==$fa_category1)?'on':'').'">';
                            echo '<a href="#">'.$fa_category1_arr[$i].'</a></li>';
                        }?>
                	</ul>
            	</div>
            </div>
      

<!-- //title_bar  -->
<div class="tab_cont">
    <?php // FAQ 내용
    if( count($faq_list) ){
    ?>
    <!-- tab1 -->
    <div class="tab_inner">
    	<div class="toggle line_top">
            <?php
            foreach($faq_list as $key=>$v){
                if(empty($v))
                    continue;
            ?>
    		<div class="toggle_group">
    			<div class="title">
    				<span class="category round_green"><?php echo $v['fa_category2'] ?></span>
    				<a href="#" class="toggle_anchor"><h3 class="tit"><?php echo conv_content($v['fa_subject'], 1); ?></h3></a>
    			</div>
    			<div class="cont">
    				<?php echo conv_content($v['fa_content'], 1); ?>
    			</div>
    		</div>
            <?php
            }
            ?>
    	</div>
    </div>
    <!-- //tab1 -->
    <?php
    } else {
    ?>
    <!-- tab1 -->
    <div class="tab_inner">
    	<div class="toggle line_top">
    		<div class="toggle_group">
    			<div class="title">
    <?php
        if($stx){
            echo '<p class="empty_list">검색된 게시물이 없습니다.</p>';
        } else {
            echo '<div class="empty_table">등록된 FAQ가 없습니다.';
            if($is_admin)
                echo '<br><a href="'.G5_ADMIN_URL.'/faqmasterlist.php">FAQ를 새로 등록하시려면 FAQ관리</a> 메뉴를 이용하십시오.';
            echo '</div>';
        }
    ?>
    </div>
    </div>
    </div>
    </div>

    <?php } ?>
</div>
    </div>
<?php echo $list_pages ?>
<?php
// 하단 HTML
echo '<div id="faq_thtml">'.conv_content($fm['fm_mobile_tail_html'], 1).'</div>';
?>


<!-- } FAQ 끝 -->

<script>
$(function() {
    $(".closer_btn").on("click", function() {
        $(this).closest(".con_inner").slideToggle();
    });
});

function faq_open(el)
{
    var $con = $(el).closest("li").find(".con_inner");

    if($con.is(":visible")) {
        $con.slideUp();
    } else {
        $("#faq_con .con_inner:visible").css("display", "none");

        $con.slideDown(
            function() {
                // 이미지 리사이즈
                $con.viewimageresize2();
            }
        );
    }

    return false;
}
</script>
