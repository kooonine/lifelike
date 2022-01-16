<?php 
//  dd ($db_point);

?>

<? for ($oi = 0; $point = sql_fetch_array($db_point); $oi++) : ?>

    <?
    switch ($point['po_rel_table']) {
        case "@member":
            $subject = "회원가입 적립";
            break;
        case "@order":
            $subject = $point['po_point'] > 0 ? "구매적립" : "구매사용";
            break;
        case "item_use":
            $subject = $point['po_point'] > 0 ? "리뷰 작성 적립" : "리뷰 삭제";
            break;
        default:
            $subject = $point['po_content'];
            break;
    }
    ?>
    <tr class="on-big">
        <td style="font-size: 14px;font-weight: normal;color: #565656;"><?= date("Y.m.d", strtotime($point['po_datetime'])) ?></td>
        <td style="font-size: 18px;font-weight: normal;color: #4c4c4c;text-align: left;"><?= $subject ?></td>
        <td style="font-size: 14px;font-weight: 500;color: #424242;">
            <a href="/member/order.php?od_id=<?= $point['po_rel_id'] ?>" style="text-decoration: underline !important;">
                <?= $point['po_rel_table'] == "@order" ? $point['po_rel_id'] : "" ?>
            </a>
        </td>
        <td style="font-size: 20px;font-weight: 500;color: #f14e00;"><?= $point['po_point'] > 0 ? "+" : "" ?><?= number_format($point['po_point']) ?>P</td>
        </td>
    </tr>
    <tr class="on-small">
        <td style="border-bottom: 10px solid #f2f2f2; text-align: left; padding: 24px 14px;">
            <div style="font-size: 14px; font-weight: 500; color: #333333;"><?= $subject ?></div>
            <div style="font-size: 12px; font-weight: 500; color: #777777;">
                <a href="/member/order.php?od_id=<?= $point['po_rel_id'] ?>" style="text-decoration: underline !important;">
                    <?= $point['po_rel_table'] == "@order" ? $point['po_rel_id'] : "" ?>
                </a>
            </div>
            <div style="font-size: 12px; color: #333333;">
                <?= date("Y.m.d", strtotime($point['po_datetime'])) ?> ~ <?= date("Y.m.d", strtotime($point['po_expire_date'])) ?>
            </div>
        </td>
        <td style="border-bottom: 10px solid #f2f2f2; text-align: right; font-size: 28px; font-weight: 500; padding-right: 14px; color: <?= $point['po_point'] < 0 ? "#e96900" : "#00bbb4" ?>;"><?= number_format($point['po_point']) ?>P</td>
    </tr>
<? endfor ?>