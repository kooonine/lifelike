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
					<li class="on"><a href="<?php echo G5_SHOP_URL?>/itemqalist.php"><span>1:1 문의하기</span></a></li>
				</ul>
			</div>
			<div class="tab_cont_wrap">
				<div class="tab button_group">
					<ul class="type3 count4 onoff tab_btn">
                        <li class="on"><a href="#"><span>전체</span></a></li>
                        <li><a href="#"><span>리스</span></a></li>
						<li><a href="#"><span>제품</span></a></li>									
						<li><a href="#"><span>세탁</span></a></li>
                        <li><a href="#"><span>세탁보관</span></a></li>
                        <li><a href="#"><span>수선</span></a></li>
                        <li><a href="#"><span>기타</span></a></li>
                    </ul>
				</div>
				<div class="btn_group"><button type="button" class="btn big green"><span>문의하기</span></button></div>
			</div>	
			
    <div class="tab_cont">
    <!-- tab1 -->
    <div class="tab_inner">
    	<div class="list">
    		<ul class="type1">
    <?php
    $thumbnail_width = 500;
    $num = $total_count - ($page - 1) * $rows;

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $iq_subject = conv_subject($row['iq_subject'],50,"…");

        $is_secret = false;
        if($row['iq_secret']) {
            $iq_subject .= ' <i class="fa fa-lock" aria-hidden="true"></i>';

            if($is_admin || $member['mb_id' ] == $row['mb_id']) {
                $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
            } else {
                $iq_question = '비밀글로 보호된 문의입니다.';
                $is_secret = true;
            }
        } else {
            $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
        }

        $it_href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];

        if ($row['iq_answer'])
        {
            $iq_answer = get_view_thumbnail(conv_content($row['iq_answer'], 1), $thumbnail_width);
            $iq_stats = '답변완료';
            $iq_style = 'sit_qaa_done';
            $is_answer = true;
        } else {
            $iq_stats = '답변대기';
            $iq_style = 'sit_qaa_yet';
            $iq_answer = '답변이 등록되지 않았습니다.';
            $is_answer = false;
        }

        if ($i == 0) echo '<ol>';
    ?>
    <li>
    	<a href="#">
    	<strong class="title ellipsis"><?php echo $iq_question; // 상품 문의 내용 ?></strong>
    	<div class="foot">
        	<span class="date"><?php echo $row['iq_time']; ?></span>
        	<span class="state off"><?php echo $iq_stats; ?></span>
        </div>
        <!-- <?php echo $iq_answer; ?>  -->
        </a>
	</li>
    <?php
        $num--;
    }

    if ($i > 0) echo '</ol>';
    if ($i == 0) echo '<p id="sps_empty">자료가 없습니다.</p>';
    ?>
    		</ul>
    	</div>
    </div>

<?php echo get_paging($config['cf_mobile_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
		
	</div>
</div>

<script>
$(function(){
    // 상품문의 더보기
    $(".sqa_con_btn button").click(function(){
        var $con = $(this).parent().prev();
        if($con.is(":visible")) {
            $con.slideUp();
            $(this).html("내용보기 <i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i>");
        } else {
            $(".sps_con_btn button").html("내용보기 <i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i>");
            $("div[id^=sps_con]:visible").hide();
            $con.slideDown(
                function() {
                    // 이미지 리사이즈
                    $con.viewimageresize2();
                }
            );
            $(this).html("내용닫기 <i class=\"fa fa-caret-up\" aria-hidden=\"true\"></i>");
        }
    });
});
</script>
<!-- } 전체 상품 사용후기 목록 끝 -->