<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가


$nwsql = " select * from {$g5['new_win_table']}
          where '" . G5_TIME_YMDHIS . "' between nw_begin_time and nw_end_time
            and nw_device IN ( 'both', 'pc' ) and nw_status = 'Y'
          order by nw_id asc ";
$nwresult = sql_query($nwsql, false);
?>

<!-- 팝업레이어 시작 { -->
<?php
for ($i = 0; $nw = sql_fetch_array($nwresult); $i++) {
    // 이미 체크 되었다면 Continue
    if ($_COOKIE["hd_pops_{$nw['nw_id']}"])
        continue;
?>
    <!-- popup -->
    <div id="hd_pops_background" style="top: 0; position: fixed; width: 100%; height: 100%; background-color: rgba(0,0,0,.5); z-index: 1000;"></div>
    <div class="popup" id="hd_pops_<?php echo $nw['nw_id'] ?>" style="top:<?php echo $nw['nw_top'] ?>px;left:<?php echo $nw['nw_left'] ?>px;width:<?php echo $nw['nw_width'] ?>px;height:<?php echo $nw['nw_height'] ?>px; z-index: 1010;">
        <div>
            <?php
            if ($nw['nw_link']) echo "<a href='" . $nw['nw_link'] . "'>";
            $img_url = G5_DATA_URL . '/popup/' . $nw['nw_imgfile'];
            echo '<img src="' . $img_url . '">';

            if ($nw['nw_link']) echo "</a>";
            ?>
        </div>

        <div class="popup_btn">
            <ul>
                <li><a href="#" data-popid="hd_pops_<?= $nw['nw_id']; ?>" data-popexp="<?= $nw['nw_disable_hours']; ?>" class="hd_pops_reject">오늘하루 열지않기</a></li>
                <li><a href="#" data-popid="hd_pops_<?= $nw['nw_id']; ?>" class="hd_pops_close">닫기</a></li>
            </ul>
        </div>
    </div>
    <!-- //popup -->
<?php }
?>

<script>
    $(function() {
        function popupClose(id) {
            $("#" + id).addClass("blind");
            if ($(".popup").length === $(".popup.blind").length) $("#hd_pops_background").hide();
        }
        $(".hd_pops_reject").click(function() {
            const ck_name = $(this).data("popid");
            const exp_time = parseInt($(this).data("popexp"));
            set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
            popupClose(ck_name);
        });
        $('.hd_pops_close').click(function() {
            const id = $(this).data("popid");
            popupClose(id);
        });
        $("#hd").css("z-index", 1000);
    });
</script>
<!-- } 팝업레이어 끝 -->