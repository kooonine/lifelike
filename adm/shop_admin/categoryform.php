<?php
$sub_menu = '400200';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], "w");

$sql_common = " from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql_common .= " where ca_mb_id = '{$member['mb_id']}' ";

if ($w == "") {
    if ($is_admin != 'super' && !$ca_id)
        alert("최고관리자만 1단계 분류를 추가할 수 있습니다.");

    $len = strlen($ca_id);
    if ($len == 10)
        alert("분류를 더 이상 추가할 수 없습니다.\\n\\n5단계 분류까지만 가능합니다.");

    $len2 = $len + 1;

    $sql = " select MAX(SUBSTRING(ca_id,$len2,2)) as max_subid from {$g5['g5_shop_category_table']}
              where SUBSTRING(ca_id,1,$len) = '$ca_id' ";
    $row = sql_fetch($sql);

    $subid = base_convert($row['max_subid'], 36, 10);
    $subid += 36;
    if ($subid >= 36 * 36) {
        //alert("분류를 더 이상 추가할 수 없습니다.");
        // 빈상태로
        $subid = "  ";
    }
    $subid = base_convert($subid, 10, 36);
    $subid = substr("00" . $subid, -2);
    $subid = $ca_id . $subid;

    $sublen = strlen($subid);

    if ($ca_id) // 2단계이상 분류
    {
        $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
        $ca = sql_fetch($sql);
        $html_title = $ca['ca_name'] . " 하위분류추가";
        $ca['ca_name'] = "";
    } else // 1단계 분류
    {
        $html_title = "1단계분류추가";
        $ca['ca_use'] = 1;
        $ca['ca_explan_html'] = 1;
        $ca['ca_img_width']  = $default['de_simg_width'];
        $ca['ca_img_height'] = $default['de_simg_height'];
        $ca['ca_mobile_img_width']  = $default['de_simg_width'];
        $ca['ca_mobile_img_height'] = $default['de_simg_height'];
        $ca['ca_list_mod'] = 3;
        $ca['ca_list_row'] = 5;
        $ca['ca_mobile_list_mod'] = 3;
        $ca['ca_mobile_list_row'] = 5;
        $ca['ca_stock_qty'] = 99999;
    }
    $ca['ca_skin'] = "list.10.skin.php";
    $ca['ca_mobile_skin'] = "list.10.skin.php";
} else if ($w == "u") {
    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);
    if (!$ca['ca_id'])
        alert("자료가 없습니다.");

    $html_title = $ca['ca_name'] . " 수정";
    $ca['ca_name'] = get_text($ca['ca_name']);
}

$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH . '/admin.head.php');

$pg_anchor = '<ul class="anchor">
<li><a href="#anc_scatefrm_basic">필수입력</a></li>
<li><a href="#anc_scatefrm_optional">선택입력</a></li>
<li><a href="#anc_scatefrm_extra">여분필드</a></li>';
if ($w == 'u') $pg_anchor .= '<li><a href="#frm_etc">기타설정</a></li>';
$pg_anchor .= '</ul>';

// 쿠폰 적용 불가 설정 필드 추가
if (!sql_query(" select ca_nocoupon from {$g5['g5_shop_category_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_category_table']}`
                    ADD `ca_nocoupon` tinyint(4) NOT NULL DEFAULT '0' AFTER `ca_adult_use` ", true);
}

// 스킨 디렉토리 필드 추가
if (!sql_query(" select ca_skin_dir from {$g5['g5_shop_category_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_category_table']}`
                    ADD `ca_skin_dir` varchar(255) NOT NULL DEFAULT '' AFTER `ca_name`,
                    ADD `ca_mobile_skin_dir` varchar(255) NOT NULL DEFAULT '' AFTER `ca_skin_dir` ", true);
}

// 분류 출력순서 필드 추가
if (!sql_query(" select ca_order from {$g5['g5_shop_category_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_category_table']}`
                    ADD `ca_order` int(11) NOT NULL DEFAULT '0' AFTER `ca_name` ", true);
    sql_query(" ALTER TABLE `{$g5['g5_shop_category_table']}` ADD INDEX(`ca_order`) ", true);
}

// 모바일 상품 출력줄수 필드 추가
if (!sql_query(" select ca_mobile_list_row from {$g5['g5_shop_category_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_category_table']}`
                    ADD `ca_mobile_list_row` int(11) NOT NULL DEFAULT '0' AFTER `ca_mobile_list_mod` ", true);
}

// 스킨 Path
if (!$ca['ca_skin_dir'])
    $g5_shop_skin_path = G5_SHOP_SKIN_PATH;
else {
    if (preg_match('#^theme/(.+)$#', $ca['ca_skin_dir'], $match))
        $g5_shop_skin_path = G5_THEME_PATH . '/' . G5_SKIN_DIR . '/shop/' . $match[1];
    else
        $g5_shop_skin_path  = G5_PATH . '/' . G5_SKIN_DIR . '/shop/' . $ca['ca_skin_dir'];
}

if (!$ca['ca_mobile_skin_dir'])
    $g5_mshop_skin_path = G5_MSHOP_SKIN_PATH;
else {
    if (preg_match('#^theme/(.+)$#', $ca['ca_mobile_skin_dir'], $match))
        $g5_mshop_skin_path = G5_THEME_MOBILE_PATH . '/' . G5_SKIN_DIR . '/shop/' . $match[1];
    else
        $g5_mshop_skin_path = G5_MOBILE_PATH . '/' . G5_SKIN_DIR . '/shop/' . $ca['ca_mobile_skin_dir'];
}
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fcategoryform" action="./categoryformupdate.php" onsubmit="return fcategoryformcheck(this);" method="post" enctype="multipart/form-data">

                <input type="hidden" name="codedup" value="<?php echo $default['de_code_dup_use']; ?>">
                <input type="hidden" name="w" value="<?php echo $w; ?>">
                <input type="hidden" name="sst" value="<?php echo $sst; ?>">
                <input type="hidden" name="sod" value="<?php echo $sod; ?>">
                <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
                <input type="hidden" name="stx" value="<?php echo $stx; ?>">
                <input type="hidden" name="page" value="<?php echo $page; ?>">
                <input type="hidden" name="ca_explan_html" value="<?php echo $ca['ca_explan_html']; ?>">
                <input type="hidden" name="ca_mb_id" value="<?php echo $ca['ca_mb_id']; ?>">

                <input type="hidden" name="ca_cert_use" value="<?php echo $ca['ca_cert_use']; ?>">
                <input type="hidden" name="ca_adult_use" value="<?php echo $ca['ca_adult_use']; ?>">

                <input type="hidden" name="ca_list_mod" value="<?php echo $ca['ca_list_mod']; ?>">
                <input type="hidden" name="ca_list_row" value="<?php echo $ca['ca_list_row']; ?>">
                <input type="hidden" name="ca_mobile_list_mod" value="<?php echo $ca['ca_mobile_list_mod']; ?>">
                <input type="hidden" name="ca_mobile_list_row" value="<?php echo $ca['ca_mobile_list_row']; ?>">
                <input type="hidden" name="ca_stock_qty" value="<?php echo $ca['ca_stock_qty']; ?>">
                <input type="hidden" name="ca_sell_email" value="<?php echo $ca['ca_sell_email']; ?>">
                <!-- <input type="hidden" name="ca_use" value="<?php echo $ca['ca_use']; ?>"> -->
                <input type="hidden" name="ca_nocoupon" value="<?php echo $ca['ca_nocoupon']; ?>">

                <input type="hidden" id="ca_skin_dir" name="ca_skin_dir" value="<?php echo $ca['ca_skin_dir'] ?>">
                <input type="hidden" id="ca_mobile_skin_dir" name="ca_mobile_skin_dir" value="<?php echo $ca['ca_mobile_skin_dir'] ?>">
                <section id="anc_scatefrm_basic">
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                            <caption>분류 추가 필수입력</caption>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="ca_id">분류코드</label></th>
                                    <td>
                                        <?php if ($w == "") { ?>
                                            <?php echo help("자동으로 보여지는 분류코드를 사용하시길 권해드리지만 직접 입력한 값으로도 사용할 수 있습니다.\n분류코드는 나중에 수정이 되지 않으므로 신중하게 결정하여 사용하십시오.\n\n분류코드는 2자리씩 10자리를 사용하여 5단계를 표현할 수 있습니다.\n0~z까지 입력이 가능하며 한 분류당 최대 1296가지를 표현할 수 있습니다.\n그러므로 총 3656158440062976가지의 분류를 사용할 수 있습니다."); ?>
                                            <input type="text" name="ca_id" value="<?php echo $subid; ?>" id="ca_id" required class="required frm_input" size="<?php echo $sublen; ?>" maxlength="<?php echo $sublen; ?>">
                                            <!-- <?php if ($default['de_code_dup_use']) { ?><a href="javascript:;" onclick="codedupcheck(document.getElementById('ca_id').value)">코드 중복검사</a><?php } ?> -->
                                        <?php } else { ?>
                                            <input type="hidden" name="ca_id" value="<?php echo $ca['ca_id']; ?>">
                                            <span class="frm_ca_id"><?php echo $ca['ca_id']; ?></span>
                                            <a href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=<?php echo $ca_id; ?>" class="btn_frmline">미리보기</a>
                                            <a href="./categoryform.php?ca_id=<?php echo $ca_id; ?>&amp;<?php echo $qstr; ?>" class="btn_frmline">하위분류 추가</a>
                                            <a href="./itemlist.php?sca=<?php echo $ca['ca_id']; ?>" class="btn_frmline">상품리스트</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_name">분류명</label></th>
                                    <td><input type="text" name="ca_name" value="<?php echo $ca['ca_name']; ?>" id="ca_name" size="38" required class="required frm_input"></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_use">사용여부</label></th>
                                    <td>
                                        <?php echo help("카테고리의 사용여부를 결정합니다. URL로 직접 접근은 허용됩니다."); ?>
                                        <input type="radio" name="ca_use" id="ca_use_1" class="frm_input" value="1" style="vertical-align: text-bottom; margin-right: 4px;" <?php echo $ca['ca_use'] != "0" ? "checked" : ""; ?>>
                                        <label for="ca_use_1" style="vertical-align: text-bottom; margin-right: 8px;">사용함</label>
                                        <input type="radio" name="ca_use" id="ca_use_2" class="frm_input" value="0" style="vertical-align: text-bottom; margin-left: 8px; margin-right: 4px;" <?php echo $ca['ca_use'] == "0" ? "checked" : ""; ?>>
                                        <label for="ca_use_2" style="vertical-align: text-bottom; margin-right: 8px;">사용안함</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_order">출력순서</label></th>
                                    <td>
                                        <?php echo help("숫자가 작을 수록 상위에 출력됩니다. 음수 입력도 가능하며 입력 가능 범위는 -2147483648 부터 2147483647 까지입니다.\n<b>입력하지 않으면 자동으로 출력됩니다.</b>"); ?>
                                        <input type="text" name="ca_order" value="<?php echo $ca['ca_order']; ?>" id="ca_order" class="frm_input" size="12">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_skin">출력스킨</label></th>
                                    <td>
                                        <?php echo help('기본으로 제공하는 스킨은 ' . str_replace(G5_PATH . '/', '', $g5_shop_skin_path) . '/list.*.skin.php 입니다.'); ?>
                                        <select id="ca_skin" name="ca_skin" required class="required">
                                            <?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", $g5_shop_skin_path, $ca['ca_skin']); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_img_width">썸네일이미지 폭</label></th>
                                    <td>
                                        <?php echo help("쇼핑몰환경설정 &gt; 이미지(소) 넓이가 기본값으로 설정됩니다.\n" . G5_SHOP_URL . "/list.php에서 출력되는 이미지의 폭입니다."); ?>
                                        <input type="text" name="ca_img_width" value="<?php echo $ca['ca_img_width']; ?>" id="ca_img_width" required class="required frm_input" size="5"> 픽셀
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_img_height">썸네일이미지 높이</label></th>
                                    <td>
                                        <?php echo help("쇼핑몰환경설정 &gt; 이미지(소) 높이가 기본값으로 설정됩니다.\n" . G5_SHOP_URL . "/list.php에서 출력되는 이미지의 높이입니다."); ?>
                                        <input type="text" name="ca_img_height" value="<?php echo $ca['ca_img_height']; ?>" id="ca_img_height" required class="required frm_input" size="5"> 픽셀
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_mobile_skin">모바일 출력스킨</label></th>
                                    <td>
                                        <?php echo help('기본으로 제공하는 스킨은 ' . str_replace(G5_PATH . '/', '', $g5_mshop_skin_path) . '/list.*.skin.php 입니다.'); ?>
                                        <select id="ca_mobile_skin" name="ca_mobile_skin" required class="required">
                                            <?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", $g5_mshop_skin_path, $ca['ca_mobile_skin']); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_mobile_img_width">모바일 썸네일이미지 폭</label></th>
                                    <td>
                                        <?php echo help("쇼핑몰환경설정 &gt; 이미지(소) 넓이가 기본값으로 설정됩니다.\n" . G5_SHOP_URL . "/list.php에서 출력되는 이미지의 폭입니다."); ?>
                                        <input type="text" name="ca_mobile_img_width" value="<?php echo $ca['ca_mobile_img_width']; ?>" id="ca_mobile_img_width" required class="required frm_input" size="5"> 픽셀
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_mobile_img_height">모바일 썸네일이미지 높이</label></th>
                                    <td>
                                        <?php echo help("쇼핑몰환경설정 &gt; 이미지(소) 높이가 기본값으로 설정됩니다.\n" . G5_SHOP_URL . "/list.php에서 출력되는 이미지의 높이입니다."); ?>
                                        <input type="text" name="ca_mobile_img_height" value="<?php echo $ca['ca_mobile_img_height']; ?>" id="ca_mobile_img_height" required class="required frm_input" size="5"> 픽셀
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_1">상단 배너</label></th>
                                    <td>
                                        <?php echo help("카테고리 최상단에 노출되는 배너입니다. 기존 등록 이미지 포함 최대 5개까지 설정 가능하며. 그 이상의 등록할 경우 기존 이미지부터 삭제합니다."); ?>
                                        링크 <input type="text" name="ca_banner_url" class="frn_input" />
                                        설명 <input type="text" name="ca_banner_alt" class="frn_input" />
                                        <input type="file" name="ca_banner_file" id="ca_banner_file" class="frm_input" size="5" style="display: inline-block; border: none;" />
                                        <?php
                                        $db_banners = sql_query("SELECT bn_id,bn_alt,bn_url,bn_file FROM lt_shop_category_banner WHERE bn_device='PC' AND ca_id='" . $ca_id . "'");
                                        if ($db_banners->num_rows > 0) : ?>
                                            <? for ($bi = 0; $banner = sql_fetch_array($db_banners); $bi++) : ?>
                                                <div>
                                                    <a href="<?= $banner['bn_url'] ?>"><img src="/data/banner/category/<?= $banner['bn_file'] ?>" style="height: 100px;" alt="<?= $banner['bn_alt'] ?>">
                                                    </a>
                                                    <span>
                                                        <input type="checkbox" name="bn_delete[]" value="<?= $banner['bn_id'] ?>" , id="bn_delete_<?= $banner['bn_id'] ?>" class="frm_input" style="vertical-align: middle;" />
                                                        <label for="bn_delete_<?= $banner['bn_id'] ?>" style="vertical-align: text-top;">삭제</label>
                                                    </span>
                                                </div>
                                            <? endfor ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="ca_2">모바일 상단 배너</label></th>
                                    <td>
                                        <?php echo help("모바일 카테고리 최상단에 노출되는 배너입니다."); ?>
                                        링크 <input type="text" name="ca_banner_m_url" class="frn_input" />
                                        설명 <input type="text" name="ca_banner_m_alt" class="frn_input" />
                                        <input type="file" name="ca_banner_m_file" id="ca_banner_m_file" class="frm_input" size="5" style="display: inline-block; border: none;" />
                                        <?php
                                        $db_banners = sql_query("SELECT bn_id,bn_alt,bn_url,bn_file FROM lt_shop_category_banner WHERE bn_device='M' AND ca_id='" . $ca_id . "'");
                                        if ($db_banners->num_rows > 0) : ?>
                                            <? for ($bi = 0; $banner = sql_fetch_array($db_banners); $bi++) : ?>
                                                <div>
                                                    <a href="<?= $banner['bn_url'] ?>"><img src="/data/banner/category/<?= $banner['bn_file'] ?>" style="height: 100px;" alt="<?= $banner['bn_alt'] ?>">
                                                    </a>
                                                    <span>
                                                        <input type="checkbox" name="bn_delete[]" value="<?= $banner['bn_id'] ?>" , id="bn_delete_<?= $banner['bn_id'] ?>" class="frm_input" style="vertical-align: middle;" />
                                                        <label for="bn_delete_<?= $banner['bn_id'] ?>" style="vertical-align: text-top;">삭제</label>
                                                    </span>
                                                </div>
                                            <? endfor ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <?php if ($w == "u") { ?>
                                    <tr>
                                        <th scope="row">하위분류</th>
                                        <td>
                                            <?php echo help("이 분류의 코드가 10 이라면 10 으로 시작하는 하위분류의 설정값을 이 분류와 동일하게 설정합니다.\n<strong>이 작업은 실행 후 복구할 수 없습니다.</strong>"); ?>
                                            <label for="sub_category">이 분류의 하위분류 설정을, 이 분류와 동일하게 일괄수정</label>
                                            <input type="checkbox" name="sub_category" value="1" id="sub_category" onclick="if (this.checked) if (confirm('이 분류에 속한 하위 분류의 속성을 똑같이 변경합니다.\n\n이 작업은 되돌릴 방법이 없습니다.\n\n그래도 변경하시겠습니까?')) return ; this.checked = false;">
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>


                <div class="">
                    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
                    <a href="./categorylist.php?'.$qstr.'" class="btn_02 btn">목록</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    <?php if ($w == 'u') { ?>
        $(".banner_or_img").addClass("sit_wimg");
        $(function() {
            $(".sit_wimg_view").bind("click", function() {
                var sit_wimg_id = $(this).attr("id").split("_");
                var $img_display = $("#" + sit_wimg_id[1]);

                $img_display.toggle();

                if ($img_display.is(":visible")) {
                    $(this).text($(this).text().replace("확인", "닫기"));
                } else {
                    $(this).text($(this).text().replace("닫기", "확인"));
                }

                var $img = $("#" + sit_wimg_id[1]).children("img");
                var width = $img.width();
                var height = $img.height();
                if (width > 700) {
                    var img_width = 700;
                    var img_height = Math.round((img_width * height) / width);

                    $img.width(img_width).height(img_height);
                }
            });
            $(".sit_wimg_close").bind("click", function() {
                var $img_display = $(this).parents(".banner_or_img");
                var id = $img_display.attr("id");
                $img_display.toggle();
                var $button = $("#ca_" + id + "_view");
                $button.text($button.text().replace("닫기", "확인"));
            });
        });
    <?php } ?>

    function fcategoryformcheck(f) {
        if (f.w.value == "") {
            var error = "";
            $.ajax({
                url: "./ajax.ca_id.php",
                type: "POST",
                data: {
                    "ca_id": f.ca_id.value
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(data, textStatus) {
                    error = data.error;
                }
            });

            if (error) {
                alert(error);
                return false;
            }
        }

        <?php echo get_editor_js('ca_head_html'); ?>
        <?php echo get_editor_js('ca_tail_html'); ?>
        <?php echo get_editor_js('ca_mobile_head_html'); ?>
        <?php echo get_editor_js('ca_mobile_tail_html'); ?>

        return true;
    }

    var captcha_chk = false;

    function use_captcha_check() {
        $.ajax({
            type: "POST",
            url: g5_admin_url + "/ajax.use_captcha.php",
            data: {
                admin_use_captcha: "1"
            },
            cache: false,
            async: false,
            dataType: "json",
            success: function(data) {}
        });
    }

    function frm_check_file() {
        var ca_include_head = "<?php echo $ca['ca_include_head']; ?>";
        var ca_include_tail = "<?php echo $ca['ca_include_tail']; ?>";
        var head = jQuery.trim(jQuery("#ca_include_head").val());
        var tail = jQuery.trim(jQuery("#ca_include_tail").val());

        if (ca_include_head !== head || ca_include_tail !== tail) {
            // 캡챠를 사용합니다.
            jQuery("#admin_captcha_box").show();
            captcha_chk = true;

            use_captcha_check();

            return false;
        } else {
            jQuery("#admin_captcha_box").hide();
        }

        return true;
    }

    jQuery(function($) {
        if (window.self !== window.top) { // frame 또는 iframe을 사용할 경우 체크
            $("#ca_include_head, #ca_include_tail").on("change paste keyup", function(e) {
                frm_check_file();
            });

            use_captcha_check();
        }

        $(".shop_category").on("click", function() {
            if (!confirm("현재 테마의 스킨, 이미지 사이즈 등의 설정을 적용하시겠습니까?"))
                return false;

            $.ajax({
                type: "POST",
                url: "../theme_config_load.php",
                cache: false,
                async: false,
                data: {
                    type: 'shop_category'
                },
                dataType: "json",
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return false;
                    }

                    $.each(data, function(key, val) {
                        if (key == "error")
                            return true;

                        $("#" + key).val(val);
                    });
                }
            });
        });
    });

    /*document.fcategoryform.ca_name.focus(); 포커스 해제*/
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>