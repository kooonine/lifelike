<?php
include_once "./_common.php";

$sql_cz = "SELECT * FROM lt_shop_coupon_zone ORDER BY cz_id DESC";
$db_cz = sql_query($sql_cz);
$cz_set = array();

while (false != ($cz = sql_fetch_array($db_cz))) $cz_set[] = $cz;

if ($cz_id) {
    $code_set = array();
    $coupon_count = 0;

    $tmp_coupon_set = array();
    foreach ($cz_id as $ci => $id) {
        $tmp_coupon = array();
        $tmp_coupon['id'] = $id;
        $tmp_coupon['count'] = $count[$ci];
        $coupon_count += $count[$ci];

        $tmp_coupon_set[] = $tmp_coupon;
    }

    $code_set['coupon'] = $tmp_coupon_set;
    $code_set['color'] = $color ? $color : "mint";
    if ($title) $code_set['title'] = $title;
    if ($subject) $code_set['subject'] = $subject;
    if ($description) $code_set['description'] = $description;

    $code = urlencode(json_encode_raw($code_set));
}

if ($subject) {
    $lastChar = mb_substr($subject, mb_strlen($subject) - 1, 1);
    if ($lastChar == '%' || $lastChar == '원') {
        $subject = mb_substr($subject, 0, mb_strlen($subject) - 1) . "<small>" . $lastChar . "</small>";
    }
}


include_once(G5_ADMIN_PATH . '/admin.head.sub.php');
?>
<style>
    @import "/re/css/fonts.css";
    @import "/re/css/coupon.css";

    body {
        background-color: #ffffff;
        color: #333333;
    }

    table td {
        padding: 10px 0;
    }

    #maker-wrapper {
        padding: 20px;
    }

    .btn-coupon {
        width: 28px;
        height: 28px;
        font-size: 14px;
        vertical-align: text-top;
        line-height: 14px;
        margin: 0;
        padding: 0;
    }

    .coupon-set {
        width: auto;
        line-height: normal !important;
    }

    .coupon-set-pc {
        width: 420px;
    }

    .coupon-set-m {
        width: 250px;
    }

    .coupon-set-inner-left {
        width: calc(100% - 76px);
    }

    .coupon-set-m .coupon-set-inner-left {
        width: calc(100% - 44px);
    }

    .coupon-set-m .coupon-set-inner {
        height: 160px;
    }

    .coupon-set-m .coupon-set-inner-left {
        font-size: 20px;
        padding: 17px 0 0 22px;
    }

    .coupon-set-m .coupon-set-inner-right {
        width: 44px;
        background-size: 18px;
        right: 0px;
    }

    .coupon-set-m .coupon-set-subject {
        font-size: 70px;
    }

    .coupon-set-m .coupon-set-subject>small {
        font-size: 40px;
    }

    .coupon-set-m .coupon-set-desc {
        padding: 15px 22px;
        font-size: 12px;
        margin: 6px 0;
    }

    .coupon-set-m .btn-download-coupon {
        height: 40px;
        font-size: 16px;
    }
</style>
<div id="maker-wrapper">
    <form method="POST">
        <table id="coupon-set">
            <tr>
                <th>쿠폰선택</th>
                <th>쿠폰추가</th>
                <th><button type="button" id="btn-add-coupon" class="btn-coupon">+</button></th>
            </tr>
            <? if (!empty($cz_id)) : ?>
                <? foreach ($cz_id as $ci => $id) : ?>
                    <tr class="coupon-row">
                        <td>
                            <select name="cz_id[]">
                                <? foreach ($cz_set as $cz) : ?>
                                    <option value="<?= $cz['cz_id'] ?>" <?= get_selected($cz['cz_id'], $id) ?>><?= $cz['cz_subject'] ?></option>
                                <? endforeach ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="count[]" value=<?= $count[$ci] ?>>
                        </td>
                        <td><button type="button" id="btn-remove-coupon" class="btn-coupon" onclick=removeCouponRow(this)>-</button></td>
                    </tr>
                <? endforeach ?>
            <? else : ?>
                <tr class="coupon-row">
                    <td>
                        <select name="cz_id[]">
                            <? foreach ($cz_set as $cz) : ?>
                                <option value="<?= $cz['cz_id'] ?>"><?= $cz['cz_subject'] ?></option>
                            <? endforeach ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="count[]" value=1>
                    </td>
                    <td><button type="button" id="btn-remove-coupon" class="btn-coupon" onclick=removeCouponRow(this)>-</button></td>
                </tr>
            <? endif ?>
        </table>
        <table style="width: 100%;">
            <tr>
                <th>세트 컬러</th>
            </tr>
            <tr>
                <td>
                    <select name="color">
                        <option <?= get_selected($color, "mint") ?>value="mint">민트</option>
                        <option <?= get_selected($color, "orange") ?>value="orange">오렌지</option>
                        <option <?= get_selected($color, "gray") ?>value="gray">그레이</option>
                        <option <?= get_selected($color, "darkgray") ?>value="darkgray">다크크레이</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>세트 이름</th>
            </tr>
            <tr>
                <td>
                    <input type="text" name="title" value="<?= strip_tags($title) ?>">
                </td>
            </tr>
            <tr>
                <th>세트 할인 문구(예: 20%, 5,000원)</th>
            </tr>
            <tr>
                <td>
                    <input type="text" name="subject" value="<?= strip_tags($subject) ?>">
                </td>
            </tr>
            <tr>
                <th>세트 설명</th>
            </tr>
            <tr>
                <td>
                    <textarea style="width: 100%;" name="description" cols="30" rows="10"><?= $description ?></textarea>
                </td>
            </tr>
            <tr>
                <button type="submit">생성</button>
            </tr>
            <? if (!empty($code)) : ?>
                <tr>
                    <th>삽입코드</th>
                </tr>
                <tr>
                    <td>
                        <textarea style="width: 100%;" rows="10">##<?= $code ?>##</textarea>
                    </td>
                </tr>
                <tr>
                    <th>미리보기(PC)</th>
                </tr>
                <tr>
                    <td style="background-color: #ffffff; padding: 20px;">
                        <div class="coupon-set coupon-set-color-<?= $color ?> coupon-set-pc">
                            <div class="coupon-set-inner">
                                <div class="coupon-set-inner-left">
                                    <div><?= $title ?>
                                        <? if ($coupon_count > 1) : ?>
                                            <span class="coupon-set-count"><?= $coupon_count ?>장</span>
                                        <? endif ?>
                                    </div>
                                    <div class="coupon-set-subject">
                                        <?= $subject ?>
                                    </div>
                                </div>
                                <div class="coupon-set-inner-right"></div>
                            </div>
                            <div class="coupon-set-desc"><?= nl2br($description) ?></div>
                            <div><button type="button" class="btn btn-download-coupon">쿠폰 다운</button></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>미리보기(MOBILE)</th>
                </tr>
                <tr>
                    <td style="background-color: #ffffff; padding: 20px;">
                        <div class="coupon-set coupon-set-color-<?= $color ?> coupon-set-m">
                            <div class="coupon-set-inner">
                                <div class="coupon-set-inner-left">
                                    <div><?= $title ?>
                                        <? if ($coupon_count > 1) : ?>
                                            <span class="coupon-set-count"><?= $coupon_count ?>장</span>
                                        <? endif ?>
                                    </div>
                                    <div class="coupon-set-subject">
                                        <?= $subject ?>
                                    </div>
                                </div>
                                <div class="coupon-set-inner-right"></div>
                            </div>
                            <div class="coupon-set-desc"><?= nl2br($description) ?></div>
                            <div><button type="button" class="btn btn-download-coupon">쿠폰 다운</button></div>
                        </div>
                    </td>
                </tr>
            <? endif ?>
        </table>
    </form>
</div>
<script type="text/javascript">
    function removeCouponRow(elem) {
        return $(elem).parent().parent().remove();
    }

    $("#btn-add-coupon").on("click", function() {
        const couponRow = $(".coupon-row").last().html();
        return $("#coupon-set").append("<tr>" + couponRow + "</tr>");
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>