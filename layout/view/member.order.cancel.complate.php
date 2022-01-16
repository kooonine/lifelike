<?php
ob_start();
$g5_title = $page_title;
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<link rel="stylesheet" href="/re/css/shop.css">
<style>
    @media (max-width: 1366px) {
        .member-order-sub-info-wapper {
            margin-top: 20px;
        }

        .member-content-title {
            font-size: 16px !important;
            border-bottom: 1px solid #f2f2f2 !important;
        }

        .member-order-detail-list>tbody>tr:last-child>td {
            border-bottom: unset !important;
        }

        .member-order-sub-info {
            margin-bottom: 10px;
        }

        .member-order-sub-info table tr>td {
            font-size: 12px;
            line-height: 32px;
        }

        .member-order-sub-info table tr:last-child>td {
            border-bottom: unset;
        }

        .order-description {
            font-size: 12px;
            font-weight: normal;
            color: #7f7f7f;
            background-color: #f2f2f2;
            padding: 8px 20px;
        }

        div.member-content-section {
            margin-bottom: 32px;
        }

        div#member-content-wrapper {
            padding: unset;
        }

        .mo_hard_line {
            display: none;
        }
    }
</style>
<div id="member-content-wrapper">
    <div class="on-big" style="font-size: 26px; font-weight: bold; text-align: center; color: #0f0f0f;">
        <?= $page_prefix ?> 완료되었습니다.
    </div>
    <div class="on-big" style="font-size: 14px; line-height: 1.71; text-align: center; color: #565656; margin: 26px 0 60px 0;">
        <? if ($action == "cancel") : ?>
            카드사, 은행사에 따라 환불시점은 상이할 수 있습니다.<br>
            문의사항은 해당 카드사, 은행사로 연락주시기 바랍니다.
        <? else : ?>
            CS확인 후 택배기사님이 방문하여 상품을 수거해 갈 예정입니다.
        <? endif ?>
    </div>
    <div class="on-small" style="font-size: 18px; font-weight: bold; text-align: center; color: #3a3a3a; padding-top: 80px; padding-bottom: 20px;">
        <?= $page_prefix ?> 완료되었습니다.
    </div>
    <div class="on-small" style="font-size: 12px; font-weight: normal; line-height: 1.67; text-align: center; color: #3a3a3a; padding-bottom: 80px;">
        <? if ($action == "cancel") : ?>
            카드사, 은행사에 따라 환불시점은 상이할 수 있습니다.<br>
            문의사항은 해당 카드사, 은행사로 연락주시기 바랍니다.
        <? else : ?>
            CS확인 후 택배기사님이 방문하여 상품을 수거해 갈 예정입니다.
        <? endif ?>
    </div>

    <div class="on-big" style="font-weight: 500; padding: 28px; border: solid 1px #f2f2f2; background-color: #f2f2f2; display: flex; justify-content: space-between;">
        <span style="font-size: 18px;">
            <span>주문번호</span>
            <span style="margin-left: 20px;"><?= $od['od_id'] ?></span>
        </span>
        <span style="font-size: 16px; display: none;">
            <span>주문일자</span>
            <span style="margin-left: 20px;"><?= date("Y.m.d", strtotime($od['od_time'])) ?></span>
        </span>
    </div>
    <!-- 타이틀 모바일 -->

    <div class="on-small" style="font-size: 18px; font-weight: 500; padding: 20px 14px; background-color: #f2f2f2; display: flex; justify-content: space-between;">
        <?= $od['od_id'] ?>
    </div>

    <!-- 상품정보 -->
    <div class="member-content-section" style="margin-bottom: 20px;">
        <div class="on-big" style="margin-top: 30px; border-top: 3px solid #333333;"></div>
        <table>
            <tr class="on-big">
                <th colspan=2>상품정보</th>
                <th>결제금액(수량)</th>
            </tr>
            <? include_once "member.order.complate.item.php" ?>
        </table>
    </div>

    <div style="text-align: center; margin-bottom: 60px;">
        <button type="button" class="on-big btn-member btn-lg btn-white" onclick="location.href='/'">계속 쇼핑</button>
        <!-- <button type="button" class="on-big btn-member btn-lg" onclick="location.href='/member/order.cancel.php'" style="margin-left: 20px;">취소/반품/교환 조회</button> -->
        <button type="button" class="on-big btn-member btn-lg" onclick="location.href='/member/order.cancel.php'" style="margin-left: 20px;">취소/반품조회</button>
        <button type="button" class="on-small btn-member btn-lg btn-white" style="vertical-align: top; font-size: 14px; width: calc((100% - 42px) / 2);" onclick="location.href='/'">계속 쇼핑</button>
        <!-- <button type="button" class="on-small btn-member btn-lg btn-white" style="vertical-align: top; font-size: 14px; width: calc((100% - 42px) / 2); margin-left: 14px;" onclick="location.href='/member/order.cancel.php'">취소/반품/교환 조회</button> -->
        <button type="button" class="on-small btn-member btn-lg btn-white" style="vertical-align: top; font-size: 14px; width: calc((100% - 42px) / 2); margin-left: 14px;" onclick="location.href='/member/order.cancel.php'">취소/반품조회</button>
    </div>
</div>

<script type="text/javascript">
</script>
<?
include_once G5_LAYOUT_PATH . "/modal.review.php";
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>