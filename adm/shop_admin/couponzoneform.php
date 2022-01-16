<?php
$sub_menu = '400810';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$g5['title'] = '다운로드 쿠폰관리';

if ($w == 'u') {
    $html_title = '쿠폰 수정';

    $sql = "SELECT * FROM {$g5['g5_shop_coupon_zone_table']} WHERE cz_id = '$cz_id' ";
    $cp = sql_fetch($sql);
    if (!$cp['cz_id']) alert('등록된 자료가 없습니다.');
} else {
    $html_title = '쿠폰 입력';
    $cp['cz_start'] = G5_TIME_YMD;
    $cp['cz_end'] = date('Y-m-d', (G5_SERVER_TIME + 86400 * 15));
    $cp['cz_period'] = 15;
}

if ($cp['cp_method'] == 1) {
    $cp_target_label = '적용분류';
    $cp_target_btn = '분류검색';
} else {
    $cp_target_label = '적용상품';
    $cp_target_btn = '상품검색';
}

$cp_weekday = explode(',', $cp['cz_weekday']);
$cp_week = explode(',', $cp['cz_week']);

$sql_brands = "SELECT * FROM lt_brand WHERE br_use=1 ORDER BY br_id";
$db_brands = sql_query($sql_brands);

include_once(G5_ADMIN_PATH . '/admin.head.php');
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');
?>

<form name="fcouponform" action="./couponzoneformupdate.php" method="post" enctype="multipart/form-data" onsubmit="return form_check(this);">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="cz_id" value="<?php echo $cz_id; ?>">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="cz_type" value="0">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?></caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <!-- <tr>
        <th scope="row"><label for="cz_type">발행쿠폰타입</label></th>
        <td>
           <?php echo help("발행 쿠폰의 타입을 설정합니다.<br>포인트쿠폰은 회원의 포인트를 쿠폰으로 교환하는 쿠폰이며 다운로드 쿠폰은 회원이 다운로드하여 사용할 수 있는 쿠폰입니다."); ?>
           <select name="cz_type" id="cz_type">
                <option value="0"<?php echo get_selected('0', $cp['cz_type']); ?>>다운로드쿠폰</option>
                <option value="1"<?php echo get_selected('1', $cp['cz_type']); ?>>포인트쿠폰</option>
           </select>
        </td>
    </tr> -->
                <tr>
                    <th scope="row"><label for="cz_subject">프로모션 쿠폰</label></th>
                    <td>
                        <label><input type="checkbox" name="cp_promotion_check" id="cp_promotion_check" value=1 <?php echo get_checked($cp['cp_promotion_check'], 1) ?>> </label>
                        <label for="cp_promotion_check">(프로모션 쿠폰 체크)</label>&nbsp;&nbsp;
                        <label><input type="checkbox" name="cp_point_coupon_check" id="cp_point_coupon_check" value=1 <?php echo get_checked($cp['cp_point_coupon'], 1) ?>> </label>
                        <label for="cp_point_coupon_check">(포인트 쿠폰 체크)</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cz_subject">쿠폰이름</label></th>
                    <td>
                        <input type="text" name="cz_subject" value="<?php echo get_text($cp['cz_subject']); ?>" id="cz_subject" required class="required frm_input" size="50">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cz_desc">사용제한 설명</label></th>
                    <td>
                        <input type="text" name="cz_desc" value="<?php echo get_text($cp['cz_desc']); ?>" id="cz_desc" required class="required frm_input" size="100">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cz_start">사용시작일</label></th>
                    <td>
                        <?php echo help('입력 예: ' . date('Y-m-d')); ?>
                        <input type="text" name="cz_start" value="<?php echo stripslashes($cp['cz_start']); ?>" id="cz_start" required class="frm_input required">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="cz_end">사용종료일</label></th>
                    <td>
                        <?php echo help('입력 예: ' . date('Y-m-d')); ?>
                        <input type="text" name="cz_end" value="<?php echo stripslashes($cp['cz_end']); ?>" id="cz_end" required class="frm_input required">
                    </td>
                </tr>
                <tr id="tr_cp_point_coupon_amount" style="display: none;">
                    <th scope="row"><label for="cp_point_coupon_amount">지급 포인트</label></th>
                    <td>
                        <input type="text" name="cp_point_coupon_amount" value="<?php echo stripslashes($cp['cp_point_coupon_amount']); ?>" id="cp_point_coupon_amount" class="frm_input required"> 
                    </td>
                </tr>
                <tr id="tr_cz_point">
                    <th scope="row"><label for="cz_point">쿠폰교환 포인트</label></th>
                    <td>
                        <?php echo help("쿠폰으로 교환할 회원의 포인트를 지정합니다. 쿠폰 다운로드 때 설정한 값만큼 회원의 포인트를 차감합니다."); ?>
                        <input type="text" name="cz_point" value="<?php echo get_text($cp['cz_point']); ?>" id="cz_point" class="frm_input">
                    </td>
                </tr>
                <tr id="tr_cz_period">
                    <th scope="row" rowspan=3><label for="cz_period">쿠폰사용기한</label></th>
                    <td>
                        <?php echo help("쿠폰 다운로드 후 사용할 수 있는 기간을 설정합니다."); ?>
                        <input type="text" name="cz_period" value="<?php echo stripslashes($cp['cz_period']); ?>" id="cz_period" required class="frm_input required" size="5"> 일
                    </td>
                </tr>
                <tr id="tr_cz_weekday">
                    <td>
                        <?php echo help("쿠폰 다운로드 후 사용할 수 있는 요일을 설정합니다."); ?>
                        <div>
                            <!-- <input type="checkbox" name="cz_weekday[]" id="cz_weekday_all"><label for="cz_weekday_all">전체</label> -->
                            <input type="checkbox" name="cz_weekday[]" value=1 id="cz_weekday_1" <?= in_array(1, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_1">월</label>
                            <input type="checkbox" name="cz_weekday[]" value=2 id="cz_weekday_2" <?= in_array(2, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_2">화</label>
                            <input type="checkbox" name="cz_weekday[]" value=3 id="cz_weekday_3" <?= in_array(3, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_3">수</label>
                            <input type="checkbox" name="cz_weekday[]" value=4 id="cz_weekday_4" <?= in_array(4, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_4">목</label>
                            <input type="checkbox" name="cz_weekday[]" value=5 id="cz_weekday_5" <?= in_array(5, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_5">금</label>
                            <input type="checkbox" name="cz_weekday[]" value=6 id="cz_weekday_6" <?= in_array(6, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_6">토</label>
                            <input type="checkbox" name="cz_weekday[]" value=7 id="cz_weekday_7" <?= in_array(7, $cp_weekday) ? "checked" : "" ?>><label for="cz_weekday_7">일</label>
                        </div>
                    </td>
                </tr>
                <tr id="tr_cz_week">
                    <td>
                        <?php echo help("쿠폰 다운로드 후 사용할 수 있는 주차를 설정합니다."); ?>
                        <div>
                            <!-- <input type="checkbox" name="cz_week[]" id="cz_week_all"><label for="cz_week_all">전체</label> -->
                            <input type="checkbox" name="cz_week[]" value="1" id="cz_week_1" <?= in_array(1, $cp_week) ? "checked" : "" ?>><label for="cz_week_1">첫째주</label>
                            <input type="checkbox" name="cz_week[]" value="2" id="cz_week_2" <?= in_array(2, $cp_week) ? "checked" : "" ?>><label for="cz_week_2">둘째주</label>
                            <input type="checkbox" name="cz_week[]" value="3" id="cz_week_3" <?= in_array(3, $cp_week) ? "checked" : "" ?>><label for="cz_week_3">셋째주</label>
                            <input type="checkbox" name="cz_week[]" value="4" id="cz_week_4" <?= in_array(4, $cp_week) ? "checked" : "" ?>><label for="cz_week_4">넷째주</label>
                        </div>
                    </td>
                </tr>
                <!-- <tr>
                    <th scope="row">쿠폰이미지</th>
                    <td>
                        <input type="file" name="cp_img">
                        <?php
                        $cpimg_str = '';
                        $cpimg = G5_DATA_PATH . "/coupon/{$cp['cz_file']}";
                        if (is_file($cpimg) && $cp['cz_id']) {
                            $size = @getimagesize($cpimg);
                            if ($size[0] && $size[0] > 750)
                                $width = 750;
                            else
                                $width = $size[0];

                            echo '<input type="checkbox" name="cp_img_del" value="1" id="cp_img_del"> <label for="cp_img_del">삭제</label>';
                            $cpimg_str = '<img src="' . G5_DATA_URL . '/coupon/' . $cp['cz_file'] . '" width="' . $width . '">';
                        }
                        if ($cpimg_str) {
                            echo '<div class="coupon_img">';
                            echo $cpimg_str;
                            echo '</div>';
                        }
                        ?>
                    </td>
                </tr> -->
                <tr id="tr_cp_method">
                    <th scope="row"><label for="cp_method">발급쿠폰종류</label></th>
                    <td>
                        <select name="cp_method" id="cp_method">
                            <option value="0" <?php echo get_selected('0', $cp['cp_method']); ?>>제품쿠폰</option>
                            <option value="11" <?php echo get_selected('11', $cp['cp_method']); ?>>플러스쿠폰</option>
                            <option value="4" <?php echo get_selected('4', $cp['cp_method']); ?>>브랜드쿠폰</option>
                            <option value="2" <?php echo get_selected('2', $cp['cp_method']); ?>>장바구니쿠폰</option>
                            <option value="3" <?php echo get_selected('3', $cp['cp_method']); ?>>배송비쿠폰</option>
                            <!-- <option value="1" <?php echo get_selected('1', $cp['cp_method']); ?>>카테고리할인</option> -->
                        </select>
                    </td>
                </tr>
                <tr id="tr_cp_target">
                    <th scope="row"><label for="cp_target"><?php echo $cp_target_label; ?></label></th>
                    <td>
                        <select name="cp_brand" id="cp_brand" style="<?= $cp['cp_method'] != '4' ? 'display: none;' : '' ?>">
                            <option value="">선택안함</option>
                            <? while (false != ($brand = sql_fetch_array($db_brands))) : ?>
                                <option value="<?= $brand["br_name_en"] ?>" <?php echo get_selected($brand["br_name_en"], $cp['cp_brand']); ?>><?= $brand['br_name'] ?></option>
                            <? endwhile ?>
                        </select>
                        <input type="text" name="cp_target" value="<?php echo stripslashes($cp['cp_target']); ?>" id="cp_target" required class="required frm_input">
                        <button type="button" class="btn frm_input" target-data="coupon_product_modal" data-item-idx=1 onclick=openCpItemPopup(this)>상품선택</button>
                    </td>
                </tr>
                <tr id="tr_cp_type">
                    <th scope="row"><label for="cp_type">할인금액타입</label></th>
                    <td>
                        <select name="cp_type" id="cp_type">
                            <option value="0" <?php echo get_selected('0', $cp['cp_type']); ?>>정액할인(원)</option>
                            <option value="1" <?php echo get_selected('1', $cp['cp_type']); ?>>정률할인(%)</option>
                        </select>
                    </td>
                </tr>
                <tr id="tr_cp_price">
                    <th scope="row"><label for="cp_price"><?php echo $cp['cp_type'] ? '할인비율' : '할인금액'; ?></label></th>
                    <td>
                        <input type="text" name="cp_price" value="<?php echo stripslashes($cp['cp_price']); ?>" id="cp_price" required class="frm_input required"> <span id="cp_price_unit"><?php echo $cp['cp_type'] ? '%' : '원'; ?></span>
                    </td>
                </tr>
                <tr id="tr_cp_trunc">
                    <th scope="row"><label for="cp_trunc">절사금액</label></th>
                    <td>
                        <select name="cp_trunc" id="cp_trunc">
                            <option value="1" <?php echo get_selected('1', $cp['cp_trunc']); ?>>1원단위</option>
                            <option value="10" <?php echo get_selected('10', $cp['cp_trunc']); ?>>10원단위</option>
                            <option value="100" <?php echo get_selected('100', $cp['cp_trunc']); ?>>100원단위</option>
                            <option value="1000" <?php echo get_selected('1000', $cp['cp_trunc']); ?>>1,000원단위</option>
                        </select>
                    </td>
                </tr>
                <tr id="tr_cp_minimum">
                    <th scope="row"><label for="cp_minimum">최소주문금액</label></th>
                    <td>
                        <input type="text" name="cp_minimum" value="<?php echo stripslashes($cp['cp_minimum']); ?>" id="cp_minimum" class="frm_input"> 원
                    </td>
                </tr>
                <tr id="tr_cp_maximum">
                    <th scope="row"><label for="cp_maximum">최대할인금액</label></th>
                    <td>
                        <input type="text" name="cp_maximum" value="<?php echo stripslashes($cp['cp_maximum']); ?>" id="cp_maximum" class="frm_input"> 원
                    </td>
                </tr>
                <tr id="tr_cz_download_user_limit">
                    <th scope="row"><label for="cz_download_user_limit">회원별 다운로드 제한수량</label></th>
                    <td>
                        <input type="text" name="cz_download_user_limit" value="<?php echo stripslashes($cp['cz_download_user_limit']); ?>" id="cz_download_user_limit" class="frm_input"> 개
                    </td>
                </tr>
                <tr id="tr_cz_download_limit">
                    <th scope="row"><label for="cz_download_limit">발급제한수량</label></th>
                    <td>
                        <input type="text" name="cz_download_limit" value="<?php echo stripslashes($cp['cz_download_limit']); ?>" id="cz_download_limit" class="frm_input"> 개
                    </td>
                </tr>
                <? if ($w == 'u') : ?>
                    <tr id="tr_cz_make_coupon">
                        <th scope="row"><label for="cz_make_coupon">난수 쿠폰발행</label></th>
                        <td>
                            <input type="text" value="0" id="cz_make_coupon" class="frm_input"> 개
                            <button type="button" onclick="makeCouponForce()">발행</button>
                            <button type="button" onclick="couponList()">발행된쿠폰조회</button>
                        </td>
                    </tr>
                <? endif ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <a href="./couponzonelist.php?<?php echo $qstr; ?>" class="btn_02 btn">목록</a>
        <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
    </div>

</form>

<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품선택</h4>
            </div>
            <div class="modal-body">
                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr id="tr_cp_brand_name">
                                <th>선택된 브랜드</th>
                                <td id="cp_brand_name"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label>제품분류</label></th>
                                <td>
                                    <select id="ca_id">
                                        <option value=''>분류별 상품</option>
                                        <?
                                        $sql = " select * from {$g5['g5_shop_category_table']} ";
                                        if ($is_admin != 'super')
                                            $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
                                        $sql .= " order by ca_order, ca_id ";
                                        $result = sql_query($sql);
                                        for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                            $len = strlen($row['ca_id']) / 2 - 1;

                                            $nbsp = "";
                                            for ($i = 0; $i < $len; $i++)
                                                $nbsp .= "&nbsp;&nbsp;&nbsp;";

                                            echo "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>상품번호/상품명</label></th>
                                <td>
                                    <input type="text" name="stx" id="stx" value="" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    <button type="button" class="btn btn-success" id="btnSearch">검색</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form name="procForm" id="procForm" method="post">
                    <div class="tbl_frm01 tbl_wrap" id="tblProduct">
                        <? include_once(G5_ADMIN_URL . '/design/design_component_itemsearch.php'); ?>
                    </div>
                </form>

                <div style="text-align: right;">
                    <button type="button" class="btn btn-success" id="btnProductSubmit">추가</button>
                </div>

                <div class="x_title">
                    <h5><span class="fa fa-check-square"></span> 선택된 지정상품</h5>
                    <div style="text-align: right;">
                        <input type="button" class="btn btn-danger" value="삭제" id="btnProductDel" />
                    </div>
                </div>

                <form name="procForm1" id="procForm1" method="post">
                    <div class="tbl_frm01 tbl_wrap" id="tblProductForm">

                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <br><br><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        <?php if (!$cp['cz_type']) { ?>
            $("#tr_cz_point").hide();
        <?php } ?>
        <?php if ($cp['cp_method'] == 2 || $cp['cp_method'] == 3) { ?>
            $("#tr_cp_target").hide();
            $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
        <?php } ?>
        <?php if ($cp['cp_type'] != 1) { ?>
            $("#tr_cp_maximum").hide();
            $("#tr_cp_trunc").hide();
        <?php } ?>
        <?php if ($cp['cp_point_coupon_check'] == 1) { ?>
            $("input:checkbox[id='cp_point_coupon_check']").attr("checked", true);
            $("#tr_cp_method").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_method").hide();
            $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_target").hide();
            $("#tr_cp_type").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_type").hide();
            $("#tr_cp_price").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_price").hide();
            $("#tr_cp_trunc").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_trunc").hide();
            $("#tr_cp_minimum").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_minimum").hide();
            $("#tr_cp_maximum").find("input").attr("required", false).removeClass("required");
            $("#tr_cp_maximum").hide();
            $("#tr_cz_download_user_limit").find("input").attr("required", false).removeClass("required");
            $("#tr_cz_download_user_limit").hide();
            $("#tr_cz_period").find("input").attr("required", false).removeClass("required");
            $("#tr_cz_period").hide();
            $("#tr_cz_weekday").find("input").attr("required", false).removeClass("required");
            $("#tr_cz_weekday").hide();
            $("#tr_cz_week").find("input").attr("required", false).removeClass("required");
            $("#tr_cz_week").hide();
            $("#tr_cp_point_coupon_amount").find("input").attr("required", true).addClass("required");
            $("#tr_cp_point_coupon_amount").show();
        <?php } ?>

        $("#cz_type").change(function() {
            if ($(this).val() == "1") {
                $("#tr_cz_point").find("input").attr("required", true).addClass("required");
                $("#tr_cz_point").show();
            } else {
                $("#tr_cz_point").find("input").attr("required", false).removeClass("required");
                $("#tr_cz_point").hide();
            }
        });
        $("#cp_method").change(function() {
            var cp_method = $(this).val();
            change_method(cp_method);
        });

        $("#cp_type").change(function() {
            var cp_type = $(this).val();
            change_type(cp_type);
        });

        $("#cp_brand").on("change", function() {
            const brandName = $("#cp_brand > option:selected").text();

            $("#cp_brand_name").text(brandName);
        });

        $("#sch_target").click(function() {
            var cp_method = $("#cp_method").val();
            var opt = "left=50,top=50,width=520,height=600,scrollbars=1";
            var url = "./coupontarget.php?sch_target=";

            if (cp_method == "0") {
                window.open(url + "0", "win_target", opt);
            } if (cp_method == "11") {
                window.open(url + "0", "win_target", opt);
            } else if (cp_method == "1") {
                window.open(url + "1", "win_target", opt);
            } else {
                return false;
            }
        });

        $("#cz_start, #cz_end").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "c-99:c+99"
        });
        $("#cp_promotion_check").change(function(){
            if($("#cp_promotion_check").is(":checked")){
                $("input:checkbox[id='cp_point_coupon_check']").attr("checked", false);
                $("#tr_cp_method").find("input").attr("required", true).addClass("required");
                $("#tr_cp_method").show();
                $("#tr_cp_target").find("input").attr("required", true).addClass("required");
                $("#tr_cp_target").show();
                $("#tr_cp_type").find("input").attr("required", true).addClass("required");
                $("#tr_cp_type").show();
                $("#tr_cp_price").find("input").attr("required", true).addClass("required");
                $("#tr_cp_price").show();
                $("#tr_cp_trunc").find("input").attr("required", true).addClass("required");
                $("#tr_cp_trunc").show();
                $("#tr_cp_minimum").find("input").attr("required", true).addClass("required");
                $("#tr_cp_minimum").show();
                $("#tr_cp_maximum").find("input").attr("required", true).addClass("required");
                $("#tr_cp_maximum").show();
                $("#tr_cz_download_user_limit").find("input").attr("required", true).addClass("required");
                $("#tr_cz_download_user_limit").show();
                $("#tr_cz_period").find("input").attr("required", true).addClass("required");
                $("#tr_cz_period").show();
                $("#tr_cz_weekday").find("input").attr("required", true).addClass("required");
                $("#tr_cz_weekday").show();
                $("#tr_cz_week").find("input").attr("required", true).addClass("required");
                $("#tr_cz_week").show();
                $("#tr_cp_point_coupon_amount").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_point_coupon_amount").hide();
            }else{
    
            }
        });
        $("#cp_point_coupon_check").change(function(){
            if($("#cp_point_coupon_check").is(":checked")){
                $("input:checkbox[id='cp_promotion_check']").attr("checked", false);
                $("#tr_cp_method").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_method").hide();
                $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_target").hide();
                $("#tr_cp_type").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_type").hide();
                $("#tr_cp_price").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_price").hide();
                $("#tr_cp_trunc").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_trunc").hide();
                $("#tr_cp_minimum").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_minimum").hide();
                $("#tr_cp_maximum").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_maximum").hide();
                $("#tr_cz_download_user_limit").find("input").attr("required", false).removeClass("required");
                $("#tr_cz_download_user_limit").hide();
                $("#tr_cz_period").find("input").attr("required", false).removeClass("required");
                $("#tr_cz_period").hide();
                $("#tr_cz_weekday").find("input").attr("required", false).removeClass("required");
                $("#tr_cz_weekday").hide();
                $("#tr_cz_week").find("input").attr("required", false).removeClass("required");
                $("#tr_cz_week").hide();
                $("#tr_cp_point_coupon_amount").find("input").attr("required", true).addClass("required");
                $("#tr_cp_point_coupon_amount").show();
            }else{
                $("#tr_cp_method").find("input").attr("required", true).addClass("required");
                $("#tr_cp_method").show();
                $("#tr_cp_target").find("input").attr("required", true).addClass("required");
                $("#tr_cp_target").show();
                $("#tr_cp_type").find("input").attr("required", true).addClass("required");
                $("#tr_cp_type").show();
                $("#tr_cp_price").find("input").attr("required", true).addClass("required");
                $("#tr_cp_price").show();
                $("#tr_cp_trunc").find("input").attr("required", true).addClass("required");
                $("#tr_cp_trunc").show();
                $("#tr_cp_minimum").find("input").attr("required", true).addClass("required");
                $("#tr_cp_minimum").show();
                $("#tr_cp_maximum").find("input").attr("required", true).addClass("required");
                $("#tr_cp_maximum").show();
                $("#tr_cz_download_user_limit").find("input").attr("required", true).addClass("required");
                $("#tr_cz_download_user_limit").show();
                $("#tr_cz_period").find("input").attr("required", true).addClass("required");
                $("#tr_cz_period").show();
                $("#tr_cz_weekday").find("input").attr("required", true).addClass("required");
                $("#tr_cz_weekday").show();
                $("#tr_cz_week").find("input").attr("required", true).addClass("required");
                $("#tr_cz_week").show();
                $("#tr_cp_point_coupon_amount").find("input").attr("required", false).removeClass("required");
                $("#tr_cp_point_coupon_amount").hide();
            }
        });
    });

    function tblProductFormBind() {

        var $table = $("#tblProductForm");
        $.post(
            "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                w: "u",
                it_id_list: $("#cp_target").val()
            },
            function(data) {
                $table.empty().html(data);
            }
        );
    };

    function openCpItemPopup(elem) {
        const id = $(elem).attr("target-data");
        tblProductFormBind();
        $('#coupon_ul_category').html("");
        $('#' + id).modal('show');
    }

    $("#btnSearch").click(function(event) {
        var $table = $("#tblProduct");
        $.post(
            "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                it_brand: $("#cp_brand").val(),
                ca_id: $("#ca_id").val(),
                stx: $("#stx").val(),
                not_it_id_list: $("#cp_target").val()
            },
            function(data) {
                $table.empty().html(data);
            }
        );
    });

    $("#btnProductDel").click(function(event) {
        if (!is_checked("chk2[]")) {
            alert("삭제 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if (confirm("삭제하시겠습니까?")) {

            var $chk = $("input[name='chk2[]']");
            var $it_id = new Array();

            for (var i = 0; i < $chk.size(); i++) {
                if (!$($chk[i]).is(':checked')) {
                    var k = $($chk[i]).val();
                    $it_id.push($("input[name='it_id2[" + k + "]']").val());
                }
            }

            $("#cp_target").val($it_id.join(","));
            tblProductFormBind();
        }
    });


    $("#btnProductSubmit").click(function(event) {
        if (!is_checked("chk[]")) {
            alert("등록 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        var $chk = $("input[name='chk[]']:checked");
        var $it_id = new Array();

        for (var i = 0; i < $chk.size(); i++) {
            var k = $($chk[i]).val();
            $it_id.push($("input[name='it_id[" + k + "]']").val());
        }

        var it_ids = $it_id.join(",");
        if ($("#cp_target").val() != "") it_ids += "," + $("#cp_target").val();
        $("#cp_target").val(it_ids);

        tblProductFormBind();
        $("#btnSearch").click();
    });

    $("#btnProductSearch").click(function(event) {
        $("#stx").val("");
        var $table = $("#tblProduct");
        $table.empty();
        $("#modal_product").modal('show');
    });

    function change_method(cp_method) {
        if (cp_method == "0" || cp_method == "4" || cp_method == "11") {
            $("#tr_cp_target").find("input").attr("required", true).addClass("required");
            $("#tr_cp_target").show();
            if (cp_method == "4") {
                $("#cp_brand").show();
                $("#tr_cp_brand_name").show();
            } else {
                $("#cp_brand > option").eq(0).prop("selected", true);
                $("#cp_brand").hide();
                $("#tr_cp_brand_name").hide();
            }
        } else {
            $("#tr_cp_target").hide();
            $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
        }
        // if (cp_method == "0") {
        //     $("#sch_target").text("상품검색");
        //     $("#tr_cp_target").find("label").text("적용상품");
        //     $("#tr_cp_target").find("input").attr("required", true).addClass("required");
        //     $("#tr_cp_target").show();
        // } else if (cp_method == "1") {
        //     $("#sch_target").text("분류검색");
        //     $("#tr_cp_target").find("label").text("적용분류");
        //     $("#tr_cp_target").find("input").attr("required", true).addClass("required");
        //     $("#tr_cp_target").show();
        // } else {
        //     $("#tr_cp_target").hide();
        //     $("#tr_cp_target").find("input").attr("required", false).removeClass("required");
        // }
    }

    function change_type(cp_type) {
        if (cp_type == "0") {
            $("#cp_price_unit").text("원");
            $("#cp_price_unit").closest("tr").find("label").text("할인금액");
            $("#tr_cp_maximum").hide();
            $("#tr_cp_trunc").hide();
        } else {
            $("#cp_price_unit").text("%");
            $("#cp_price_unit").closest("tr").find("label").text("할인비율");
            $("#tr_cp_maximum").show();
            $("#tr_cp_trunc").show();
        }
    }

    function form_check(f) {
        var sel_type = f.cp_type;
        var cp_type = sel_type.options[sel_type.selectedIndex].value;
        var cp_price = f.cp_price.value;

        <?php if (!$cpimg_str) { ?>
            if (f.cp_img.value == "") {
                alert("쿠폰이미지를 업로드해 주십시오.");
                return false;
            }
        <?php } ?>

        if (isNaN(cp_price)) {
            if (cp_type == "1")
                alert("할인비율을 숫자로 입력해 주십시오.");
            else
                alert("할인금액을 숫자로 입력해 주십시오.");

            return false;
        }

        cp_price = parseInt(cp_price);

        if (cp_type == "1" && (cp_price < 1 || cp_price > 99)) {
            alert("할인비율을 1과 99 사이의 숫자로 입력해 주십시오.");
            return false;
        }

        return true;
    }

    function makeCouponForce() {
        const cz_id = fcouponform.cz_id.value;
        const make_count = $("#cz_make_coupon").val();

        const tmp_coupons = [{
            id: cz_id,
            count: make_count,
            force: true
        }];

        const coupons = encodeURIComponent(JSON.stringify(tmp_coupons));

        $.ajax({
            type: 'GET',
            data: {
                coupons
            },
            url: '/shop/ajax.coupondownload.php',
            cache: false,
            async: true,
            dataType: 'json',
            success(data) {
                if (data.error != '') {
                    console.log(data.error);
                    alert(data.error);
                    return false;
                }

                alert('쿠폰이 발행됐습니다.');
            },
        });
    }

    function couponList() {
        const cz_id = fcouponform.cz_id.value;
        window.open('/adm/shop_admin/coupon.list.php?cz_id=' + cz_id);
    }
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>