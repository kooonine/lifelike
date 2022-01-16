<?
include_once('./_common.php');
include_once(G5_SHOP_PATH . '/settle_naverpay.inc.php');

// 보관기간이 지난 상품 삭제
cart_item_clean();

// cart id 설정
set_cart_id($sw_direct);
set_session("ss_direct", $sw_direct);

$s_cart_id = get_session('ss_cart_id');
// 선택필드 초기화
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
sql_query($sql);

$cart_action_url = G5_SHOP_URL . '/cartupdate.php';

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/cart.php');
    return;
}

// 테마에 cart.php 있으면 include
if (defined('G5_THEME_SHOP_PATH')) {
    $theme_cart_file = G5_THEME_SHOP_PATH . '/cart.php';
    if (is_file($theme_cart_file)) {
        include_once($theme_cart_file);
        return;
        unset($theme_cart_file);
    }
}

$g5['title'] = '장바구니';
include_once('./_head.php');


if (!$od_type) $od_type = "O";

// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = " select a.ct_id,
a.it_id,
a.it_name,
a.ct_price,
a.ct_point,
a.ct_qty,
a.ct_status,
a.ct_send_cost,
a.it_sc_type,
a.ct_rental_price,
a.ct_item_rental_month,
b.ca_id,
b.it_item_type,
a.ct_option,
a.io_price,
b.it_stock_qty
from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
where a.od_id = '$s_cart_id' and a.od_type = '$od_type' ";

//$sql .= " group by a.it_id ";
$sql .= " order by a.it_sc_type, a.it_id ";
$result = sql_query($sql);
$cart_count = sql_num_rows($result);
$title = "장바구니";
?>

<!-- 장바구니 시작 { -->
<script src="<?= G5_JS_URL; ?>/shop.js"></script>
<script src="<?= G5_JS_URL; ?>/shop.override.js"></script>

<? require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/navigation.php" ?>
<!-- container -->
<div id="container">
    <div class="content mypage sub">
        <!-- 컨텐츠 시작 -->
        <div class="grid" id="sit_sel_option">
            <!--
			<div class="tab fix">
				<ul class="type4 black center onoff">
					<li <?= ($od_type == "O") ? 'class="on"' : '' ?> onclick="location.href='<?= G5_SHOP_URL ?>/cart.php?od_type=O';"><a href="#"><span>제품</span></a></li>
					<li <?= ($od_type == "R") ? 'class="on"' : '' ?> onclick="location.href='<?= G5_SHOP_URL ?>/cart.php?od_type=R';"><a href="#"><span>리스</span></a></li>
				</ul>
			</div>
			-->

            <form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform" method="post" action="<?= $cart_action_url; ?>">
                <input type="hidden" name="od_type" value="<?= $od_type ?>" />

                <? if ($cart_count) { ?>
                    <div class="title_bar none" id="sod_chk">
                        <span class="chk check">
                            <input type="checkbox" name="ct_all" value="1" id="ct_all" checked>
                            <label for="ct_all">전체 선택</label>
                        </span>
                        <span class="floatR">총 <?= $cart_count ?>건</span>
                    </div>
                <? } ?>

                <?
                $tot_point = 0;
                $tot_sell_price = 0;
                $tot_sell_rental_price = 0;
                $tot_sell_rental_price_all = 0;
                $it_send_cost = 0;

                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    // 합계금액 계산
                    $sql = " select SUM((a.ct_price + a.io_price) * a.ct_qty) as price,
					SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
					SUM((a.ct_rental_price + a.io_price) * a.ct_qty) as rental_price,
					SUM(a.ct_point * a.ct_qty) as point,
					SUM(a.ct_qty) as qty
					from   lt_shop_cart as a
					inner join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
					where  a.ct_id = '{$row['ct_id']}'
					and    a.od_id = '$s_cart_id' ";
                    $sum = sql_fetch($sql);
                    if ($i == 0) {
                        // 계속쇼핑
                        $continue_ca_id = $row['ca_id'];
                    }
                    $image_width = 150;
                    $image_height = 150;
                    $image = get_it_image($row['it_id'], $image_width, $image_height);

                    $it_name = '<a href="./item.php?it_id=' . $row['it_id'] . '"><strong>' . stripslashes($row['it_name']) . '</strong></a>';
                    $it_options = print_item_options($row['it_id'], $s_cart_id);

                    $price_plus = '';
                    if ($row['io_price'] >= 0) {
                        $price_plus = '+';
                    }
                    $it_options = get_text($row['ct_option']) . ' / ' . $row['ct_qty'] . '개 (' . $price_plus . display_price($row['io_price']) . ')' . PHP_EOL;

                    // 배송비
                    /*
					switch($row['ct_send_cost']){
						case 1:
						$ct_send_cost = '착불';
						break;
						case 2:
						$ct_send_cost = '무료';
						break;
						default:
						$sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id, $sum['before_price']);

						if($sendcost == 0){
							$ct_send_cost = '무료';
						} else {
							$ct_send_cost = number_format($sendcost)."원";
						}

						break;
					}
					*/
                    $point      = $sum['point'];
                    $sell_price = $sum['price'];
                    $sell_rental_price = $sum['rental_price'];

                    $it_price = $row['ct_price'];
                    if ($row['it_item_type'] == "1") $it_price = $row['ct_rental_price'];
                ?>
                    <div class="order_cont chk_order">
                        <input type="hidden" name="ct_id[<?= $i; ?>]" value="<?= $row['ct_id']; ?>">
                        <input type="hidden" name="it_id[<?= $i; ?>]" value="<?= $row['it_id']; ?>">
                        <input type="hidden" name="it_name[<?= $i; ?>]" value="<?= get_text($row['it_name']); ?>">

                        <div class="body">
                            <div class="cont right_cont">
                                <span class="chk check">
                                    <input type="checkbox" class="ct_chk" name="ct_chk[<?= $i; ?>]" value="1" id="ct_chk_<?= $i; ?>" checked>
                                </span>
                                <div class="photo">
                                    <?= $image; ?>
                                </div>
                                <div class="info">
                                    <strong><?= $it_name; ?></strong>
                                    <li>
                                        <p><span class="txt">옵션 </span> <span class="point_black"><?= $it_options; ?> </span></p>
                                        <? if ($config['cf_use_point'] && $sum['point']) { ?>
                                            <p>
                                                <span class="txt">적립금</span>
                                                <span class="point_black"><?= number_format($sum['point']); ?>
                                            </p>
                                        <? } ?>

                                        <p><span class="txt">수량</span>
                                            <span class="point_black">
                                                <span class="count_control">
                                                    <em class="num"><input type="text" name="ct_qty[<?= $row['ct_id'] ?>]" value="<?= $row['ct_qty'] ?>" size="5" style="height:26px; text-align:center;"></em>
                                                    <button type="button" class="count_minus"><span class="blind">감소</span></button>
                                                    <button type="button" class="count_plus"><span class="blind">증가</span></button>
                                                </span>
                                            </span>
                                        </p>
                                    </li>
                                </div>
                                <div class="pay_item">
                                    <? if ($od_type == "O") { ?>
                                        <p>주문 금액<span class="amount"> <strong><?= number_format($sell_price); ?></strong> 원</span></p>
                                    <? } else if ($od_type == "R") { ?>
                                        <p> 월리스료 <span class="amount"> <strong><?= number_format($sell_rental_price); ?></strong> 원</span></p>
                                        <p>(<?= $row['ct_item_rental_month'] ?>개월) 총 완납금액 <span class="amount"> <strong><?= number_format($sell_rental_price * (int) $row['ct_item_rental_month']); ?></strong> 원</span></p>
                                    <? } ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="btn_comm">
                                <button type="button" class="btn border small floatR count_mod" ct_id="<?= $row['ct_id'] ?>"><span>적용</span></button>
                                <div class="btn_cart floatR">
                                    <button type="button" class="ico_01 count_delete" ct_id="<?= $row['ct_id'] ?>"><span class="blind">상품삭제</span></button>
                                    <?
                                    $sqlwish = " select count(*) as cnt, ifnull(sum(case when mb_id = '" . $member['mb_id'] . "' then 1 else 0 end),0) as wishis from lt_shop_wish where it_id ='" . $row['it_id'] . "' ";
                                    $rowwish = sql_fetch($sqlwish);
                                    echo "<a href=\"javascript:item_wish(document.fitem, '" . $row['it_id'] . "');\" >";
                                    echo "<button type=\"button\" class=\"pick ico_02 " . (($rowwish['wishis'] != '0') ? 'on' : '') . "\" it_id=\"" . $row['it_id'] . "\"><span class=\"blind\">좋아요</span></button>";
                                    echo "</a>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?
                    $tot_point      += $point;
                    $tot_sell_price += $sell_price;
                    $tot_sell_rental_price += $sell_rental_price;
                    $tot_sell_rental_price_all += $sell_rental_price * (int) $row['ct_item_rental_month'];
                } // for 끝

                if ($i == 0) {
                    echo '<div class="order_cont chk_order"><div class="body"><div class="cont"><div class="info"><strong>장바구니에 담긴 상품이 없습니다.</strong></div></div></div></div>';
                } else {
                    // 배송비 계산
                    $send_cost = get_sendcost($s_cart_id, 0);
                }
                //echo $s_cart_id;
                ?>

                <div class="order_cont chk_order order-total">
                    <div class="body">
                        <div class="cont right_cont">
                            <? if ($i != 0) { ?>
                                <?
                                if ($od_type == "O") {
                                    $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비
                                    if ($tot_price > 0 || $send_cost > 0) {
                                ?>
                                        <? if ($tot_point > 0 && $config['cf_use_point']) { ?>
                                            <div class="info" style="min-height:30px;">
                                                <p><span class="txt">적립 예정 금액</span><span class="point_black"><strong><?= number_format($tot_point); ?></strong> 원</span></p>
                                            </div>
                                        <? } ?>

                                        <? if ($send_cost > 0) { // 배송비가 0 보다 크다면 (있다면) 
                                        ?>
                                            <div class="info" style="min-height:30px;">
                                                <p><span class="txt">배송비</span><span class="point_black"><strong><?= number_format($send_cost); ?></strong> 원</span></p>
                                            </div>
                                        <? } ?>

                                        <? if ($tot_price > 0) { ?>
                                            <div class="pay_item">
                                                <span class="txt">총 주문 금액</span>
                                                <span class="amount"><strong><?= number_format($tot_price); ?></strong> 원</span>
                                            </div>
                                        <? } ?>
                                    <? } ?>
                                    <?
                                } else if ($od_type == "R") {
                                    $tot_price = $tot_sell_rental_price; // 총계 = 주문상품금액합계 + 배송비
                                    if ($tot_price > 0) {
                                    ?>

                                        <? if ($tot_point > 0) { ?>
                                            <div class="info">
                                                <p><span class="txt">적립 예정 금액</span><span class="point_black"><strong><?= number_format($tot_point); ?></strong> 원</span></p>
                                            </div>
                                        <? } ?>

                                        <? if ($send_cost > 0) { // 배송비가 0 보다 크다면 (있다면) 
                                        ?>
                                            <div class="info">
                                                <p><span class="txt">배송비</span><span class="point_black"><strong><?= number_format($send_cost); ?></strong> 원</span></p>
                                            </div>
                                        <? } ?>

                                        <div class="pay_item">
                                            <? if ($tot_sell_rental_price > 0) { ?>
                                                총 월리스료
                                                <span class="amount"><strong><?= number_format($tot_sell_rental_price); ?></strong> 원</span>
                                            <? } ?>
                                            <? if ($tot_price > 0) { ?>
                                                <span class="amount">총 완납 금액</span>
                                                <span class="amount"><strong><?= number_format($tot_sell_rental_price_all + $send_cost); ?></strong> 원</span>
                                            <? } ?>
                                        </div>
                            <?
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <? if ($i == 0) { ?>
                    <div class="btn_group count3">
                        <a href="<?= G5_SHOP_URL; ?>/" class="btn big border">쇼핑 계속하기</a>
                    </div>
                <? } else { ?>
                    <div class="btn_group count3">
                        <button type="button" onclick="return form_check('alldelete');" class="btn big border"><span>전체 제품 삭제</span></button>
                        <button type="button" onclick="return form_check('seldelete');" class="btn big border"><span>선택 제품 삭제</span></button>

                        <input type="hidden" name="url" value="<?= G5_SHOP_URL; ?>/orderform.php">
                        <input type="hidden" name="act" value="">
                        <input type="hidden" name="records" value="<?= $i; ?>">
                        <button type="button" onclick="return form_check('buy');" class="btn big green">바로 주문</button>
                    </div>
                <? } ?>

                <? if ($naverpay_button_js) { ?>
                    <div class="naverpay-cart"><?= $naverpay_request_js . $naverpay_button_js; ?></div>
                <? } ?>
            </form>
        </div>
    </div>
</div>

<form name="fitem" method="post">
    <input type="hidden" name="it_id" value="">
    <input type="hidden" name="od_type" value="<? $od_type ?>">
    <input type="hidden" name="sw_direct">
    <input type="hidden" name="url">
</form>

<!-- //container -->
<script>
    $(function() {
        var close_btn_idx;

        $(".ct_chk").click(function() {
            $(this).attr("checked", $(this).is(":checked"));

            if (!$(this).is(":checked")) $("input[name=ct_all]").attr("checked", false);
        });

        // 선택사항수정
        $(".mod_options").click(function() {
            var it_id = $(this).attr("id").replace("mod_opt_", "");
            var $this = $(this);
            close_btn_idx = $(".mod_options").index($(this));

            $.post(
                "./cartoption.php", {
                    it_id: it_id
                },
                function(data) {
                    $("#mod_option_frm").remove();
                    $this.after("<div id=\"mod_option_frm\"></div>");
                    $("#mod_option_frm").html(data);
                    price_calculate();
                }
            );
        });

        // 모두선택
        $("input[name=ct_all]").click(function() {
            if ($(this).is(":checked"))
                $("input[name^=ct_chk]").attr("checked", true);
            else
                $("input[name^=ct_chk]").attr("checked", false);
        });

        $(".count_delete").click(function() {
            var $this = $(this);
            var ct_id = $this.attr("ct_id");

            if (confirm("선택하신 옵션항목을 삭제하시겠습니까?")) {

                $this.addClass("disabled").attr("disabled", true);
                $.ajax({
                    url: g5_url + "/shop/ajax.cartupdate.php",
                    type: "POST",
                    data: {
                        "act": "del",
                        "ct_id": ct_id
                    },
                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function(data) {
                        if (data.error != "") {
                            $this.removeClass("disabled").attr("disabled", false);
                            alert(data.error);
                            return false;
                        }

                        $this.attr("disabled", false);
                        //alert("삭제되었습니다.");
                        location.href = "<?= G5_SHOP_URL . '/cart.php?od_type=' . $od_type ?>";
                        /*
                        var $el = $this.closest("li");
                        $el.closest("li").remove();
                        price_calculate();
                        */
                    }
                });

            }
        });

        $(".count_mod").click(function() {
            var $this = $(this);
            var ct_id = $this.attr("ct_id");
            var ct_qty = $("input[name='ct_qty[" + ct_id + "]']").val();

            if (confirm("선택하신 옵션항목 수량을 적용하시겠습니까?")) {

                $this.addClass("disabled").attr("disabled", true);
                $.ajax({
                    url: g5_url + "/shop/ajax.cartupdate.php",
                    type: "POST",
                    data: {
                        "act": "mod",
                        "ct_id": ct_id,
                        "ct_qty": ct_qty
                    },
                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function(data) {
                        if (data.error != "") {
                            $this.removeClass("disabled").attr("disabled", false);
                            alert(data.error);
                            return false;
                        }

                        $this.attr("disabled", false);
                        location.href = "<?= G5_SHOP_URL . '/cart.php?od_type=' . $od_type ?>";

                        //alert("수정되었습니다.");
                        //price_calculate();
                    }
                });

            }
        });

        // 옵션수정 닫기
        $(document).on("click", "#mod_option_close", function() {
            $("#mod_option_frm").remove();
            $("#win_mask, .window").hide();
            $(".mod_options").eq(close_btn_idx).focus();
        });
        $("#win_mask").click(function() {
            $("#mod_option_frm").remove();
            $("#win_mask").hide();
            $(".mod_options").eq(close_btn_idx).focus();
        });

        $(document).on("change", "select.cart_it_option", function() {
            var val = $(this).val();

            var info = val.split(",");
            // 재고체크
            if (parseInt(info[2]) < 1) {
                alert("선택하신 선택옵션상품은 재고가 부족하여 구매할 수 없습니다.");
                $(this).val("");
                return false;
            }

            var its_no = $(this).attr("its_no");
            add_sel_option_mobile_chk(its_no);
        });

        $(document).on("change", "select.cart_it_supply", function() {
            var its_no = $(this).attr("its_no");
            add_sel_option_mobile_chk(its_no);
        });
    });

    function fsubmit_check(f) {
        if ($("input[name^=ct_chk]:checked").length < 1) {
            alert("구매하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        return true;
    }

    function form_check(act) {
        var f = document.frmcartlist;
        var cnt = f.records.value;

        if (act == "buy") {
            f.act.value = act;
            f.submit();
        } else if (act == "alldelete") {
            if (confirm("선택하신 제품을 장바구니에서 삭제하시겠습니다?")) {
                f.act.value = act;
                f.submit();
            }
        } else if (act == "seldelete") {
            if ($("input[name^=ct_chk]:checked").length < 1) {
                alert("삭제하실 상품을 하나이상 선택해 주십시오.");
                return false;
            }

            if (confirm("선택하신 제품을 장바구니에서 삭제하시겠습니다?")) {
                f.act.value = act;
                f.submit();
            }
        }

        return true;
    }

    function add_sel_option_mobile_chk(its_no) {
        var add_exec = true;

        var $sel_it_option = $("select[name='sel_it_option[]'][its_no='" + its_no + "']");
        if ($sel_it_option.val() == "") add_exec = false;

        var $sel_it_supply = $("select[name='sel_it_supply[]'][its_no='" + its_no + "']");
        if ($sel_it_supply.size() > 0) {
            $sel_it_supply.each(function() {
                if ($(this).val() == "") add_exec = false;
            });
        }

        //add_option
        if (add_exec) {
            var id = "";
            var value, info, sel_opt, item, price, stock, run_error = false;
            var option = sep = "";

            var it_price = parseInt($("input[name='its_final_price[]'][its_no='" + its_no + "']").val());
            //var it_price = parseInt($("input#it_price").val());
            var item = $sel_it_option.closest("li").find("span[id^=spn_it_option]").text();

            value = $sel_it_option.val();
            info = value.split(",");
            sel_opt = info[0];
            id = sel_opt;
            option += sep + item + ":" + sel_opt;

            price = info[1];
            stock = info[2];

            $sel_it_supply.each(function() {
                //if($(this).val() == "") add_exec = false;
                value = $(this).val();
                info = value.split(",");
                sel_opt = info[0].split(chr(30))[1];

                //id += chr(30)+sel_opt;
                sep = " , ";
                option += sep + sel_opt;
                price = parseInt(price) + parseInt(info[1]);
            });

            //alert(option);

            if (same_option_check(option))
                return;

            add_sel_option_mobile(0, id, option, price, stock, it_price);
        }
    }

    function add_sel_option_mobile(type, id, option, price, stock, it_price) {
        var item_code = $("input[name='it_id[]']").val();
        var opt = "";
        var li_class = "sit_opt_list";
        if (type)
            li_class = "sit_spl_list";

        var opt_prc;
        if (parseInt(price) >= 0)
            opt_prc = number_format(it_price) + "원 (+" + number_format(String(price)) + "원)";
        else
            opt_prc = number_format(it_price) + "원 (" + number_format(String(price)) + "원)";

        opt += "<li class=\"" + li_class + "\">";
        opt += "<input type=\"hidden\" name=\"io_type[" + item_code + "][]\" value=\"" + type + "\">";
        opt += "<input type=\"hidden\" name=\"io_id[" + item_code + "][]\" value=\"" + id + "\">";
        opt += "<input type=\"hidden\" name=\"io_value[" + item_code + "][]\" value=\"" + option + "\">";
        opt += "<input type=\"hidden\" class=\"it_price\" value=\"" + it_price + "\">";
        opt += "<input type=\"hidden\" class=\"io_price\" value=\"" + price + "\">";
        opt += "<input type=\"hidden\" class=\"io_stock\" value=\"" + stock + "\">";
        opt += "<div class=\"cont\"><p class=\"txt\"><span>" + option + "</span></p>";
        opt += "<span style=\"\">" + opt_prc + "</span></div>";

        opt += "<div class=\"cont alignR\"><div class=\"count_control\">";
        opt += "<em class=\"num\"><input type=\"text\" name=\"ct_qty[" + item_code + "][]\" value=\"1\" class=\"frm_input\" size=\"5\" style=\"height:18px;\"></em>";

        opt += "<button type=\"button\" class=\"count_minus\"><span class=\"blind\">감소</span></button>";
        opt += "<button type=\"button\" class=\"count_plus\"><span class=\"blind\">증가</span></button>";

        opt += "</div>";
        opt += "<button type=\"button\" class=\"count_del\"><span class=\"blind\">삭제</span></button>";

        opt += "</div></li>";

        if ($("#sit_sel_option > ul").size() < 1) {
            $("#sit_sel_option").html("<ul id=\"sit_opt_added\"></ul>");
            $("#sit_sel_option > ul").html(opt);
        } else {
            if (type) {
                if ($("#sit_sel_option .sit_spl_list").size() > 0) {
                    $("#sit_sel_option .sit_spl_list:last").after(opt);
                } else {
                    if ($("#sit_sel_option .sit_opt_list").size() > 0) {
                        $("#sit_sel_option .sit_opt_list:last").after(opt);
                    } else {
                        $("#sit_sel_option > ul").html(opt);
                    }
                }
            } else {
                if ($("#sit_sel_option .sit_opt_list").size() > 0) {
                    $("#sit_sel_option .sit_opt_list:last").after(opt);
                } else {
                    if ($("#sit_sel_option .sit_spl_list").size() > 0) {
                        $("#sit_sel_option .sit_spl_list:first").before(opt);
                    } else {
                        $("#sit_sel_option > ul").html(opt);
                    }
                }
            }
        }

        price_calculate();
    }

    function item_wish(f, it_id) {
        if ($(".pick[it_id='" + it_id + "']").attr("class").indexOf("on") < 0) {
            $.post(
                "<?= G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {

                        if (confirm("관심상품에 저장되었습니다. 보러가시겠습니까?")) location.href = '<?= G5_SHOP_URL; ?>/wishlist.php';

                        $(".pick[it_id='" + it_id + "']").addClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        } else {
            $.post(
                "<?= G5_SHOP_URL; ?>/wishupdate2.php", {
                    it_id: it_id,
                    w: 'r'
                },
                function(data) {
                    var responseJSON = JSON.parse(data);
                    if (responseJSON.result == "S") {
                        $(".pick[it_id='" + it_id + "']").removeClass("on");
                    } else {
                        alert(responseJSON.alert);
                        return false;
                    }
                }
            );
        }
    }
</script>


<?
include_once('./_tail.php');
?>