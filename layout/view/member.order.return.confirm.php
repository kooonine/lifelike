<?php
ob_start();
$g5_title = "반품요청";
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    #table-cancel-amount {
        font-size: 16px;
        font-weight: 600;
        color: #7f7f7f;
        width: 100%;
    }

    #table-cancel-amount>tbody>tr:first-child>th,
    #table-cancel-amount>tbody>tr:first-child>td {
        font-size: 16px;
        font-weight: bold;
        color: #000000;
        line-height: 40px;
        border-bottom: 1px solid #e0e0e0;
    }

    #table-cancel-amount>tbody>tr:first-child>td {
        font-size: 20px;
    }

    #table-cancel-amount>tbody>tr>td {
        height: 40px;
        vertical-align: top;
    }

    #table-cancel-address>tbody>tr>td {
        font-size: 14px;
        font-weight: normal;
        color: #000000;
        line-height: 40px;
    }

    #table-cancel-address {
        width: 100%;
    }

    .row-address {
        display: none;
    }

    .row-address.active {
        display: table-row;
    }

    .custom-control.custom-radio>label::before {
        margin-top: 6px;
    }

    .custom-control.custom-radio>label::after {
        margin-top: 6px;
    }

    .order-detail-circle.step2 {
        border: solid 2px #00bbb4;

    }

    .order-detail-circle.step2 .order-detail-circle-label {
        color: #00bbb4;
    }

    #content_text {
        margin-bottom: 14px;
        font-size: 16px;
        border-bottom: 1px solid #e0e0e0;
        padding: 14px 0;
    }

    @media (max-width : 1366px) {
        #member-content-wrapper {
            padding: 0 20px;
        }

        .step2 {
            /* border: solid 2px #00bbb4; */
        }

        .order-detail-text-label {
            color: #7f7f7f;
        }

        .step2 .order-detail-circle-label,
        .step2 .order-detail-text-label {
            color: #00bbb4;
        }

        .textfild12 {
            font-size: 12px;
        }

        .textfild14 {
            font-size: 14px;
        }

        .textfild16 {
            font-size: 16px;
            margin: 18px 0;
        }

        .Tleft {
            font-size: 12px;
            float: left;
        }

        .Tright {
            font-size: 12px;
            text-align: right;
            font-weight: 600;
        }

        #content_text {
            font-size: 12px;
        }

        textarea::placeholder {
            color: red;
        }

        .return_content::placeholder {
            word-break: break-all;
            white-space: pre;
            text-align: left;
        }

        .row-address.active {
            display: block;
        }
    }
</style>

<div id="member-content-wrapper">
    <div class="member-content-title">
        반품 신청
    </div>

    <div class="on-big" style="margin-top: 14px; margin-bottom: 80px;" id="order-detail-return-wrapper">
        <span class="order-detail-circle step1">
            <span class="order-detail-circle-label" style="line-height: 69px;">반품 신청</span>
        </span>
        <span class="order-detail-circle-next"></span>
        <span class="order-detail-circle step2">
            <span class="order-detail-circle-label" style="line-height: 69px;">반품 접수</span>
        </span>
        <span class="order-detail-circle-next"></span>
        <span class="order-detail-circle step3">
            <span class="order-detail-circle-label" style="line-height: 69px;">반품 완료</span>
        </span>
        <span class="order-detail-circle-next"></span>
    </div>

    <div class="on-small" style="margin: 14px; text-align : center;" id="order-detail-return-wrapper">
        <span class="order-detail-text step1">
            <span class="order-detail-text-label">반품</br>신청</span>
        </span>
        <span class="order-detail-text-next"></span>
        <span class="order-detail-text step2">
            <span class="order-detail-text-label">반품</br>접수</span>
        </span>
        <span class="order-detail-text-next"></span>
        <span class="order-detail-text step3">
            <span class="order-detail-text-label">반품</br>완료</span>
        </span>
    </div>

    <div id="member-content-line" class="member-content-line on-small"></div>
    <form method="post" name="forderform" id="forderform" action="<?= G5_SHOP_URL; ?>/orderinquiryreturn.php" autocomplete="off">
        <div class="member-content-title">
            환불 예정 금액
        </div>
        <table id="table-cancel-amount" class="on-big">
            <colgroup>
                <col style="width: 140px">
                <col style="width: 160px">
                <col style="width: 180px">
                <col style="width: 140px">
                <col style="width: 160px">
                <col style="width: 160px">
                <col style="width: 160px">
                <col style="width: 160px">
            </colgroup>
            <tr>
                <th style="text-align: left;">결제 금액</th>
                <td style="text-align: right;"><?= number_format($od_last_price) ?>원</td>
                <td style="text-align: center;">-</td>
                <th style="text-align: left;">차감 금액</th>
                <td style="text-align: right;"><?= number_format($od_last_price - $tot_sell_price) ?>원</td>
                <td style="text-align: center;">=</td>
                <th style="text-align: left; color: #f04e00;">취소 예정 금액</th>
                <td style="text-align: right; color: #f04e00;"><?= number_format($tot_sell_price) ?>원</td>
            </tr>
            <tr>
                <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">상품 금액</th>
                <td style="text-align: right;"><?= number_format($tot_sell_price) ?>원</td>
                <td colspan=6></td>
            </tr>
            <tr>
                <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">배송비</th>
                <td style="text-align: right;"><?= number_format($tot_send_cost) ?>원</td>
                <td colspan=6></td>
            </tr>

            <? if ($od['od_cart_coupon'] > 0) : ?>
                <tr>
                    <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">쿠폰할인(상품)</th>
                    <td style="text-align: right;">(-)<?= number_format($tot_send_cost) ?>원</td>
                    <td colspan=6></td>
                </tr>
            <? endif ?>
            <? if ($od['od_send_coupon'] > 0) : ?>
                <tr>
                    <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">쿠폰할인(배송비)</th>
                    <td style="text-align: right;">(-)<?= number_format($od['od_send_coupon']) ?>원</td>
                    <td colspan=6></td>
                </tr>
            <? endif ?>
            <? if ($od['od_receipt_point'] > 0) : ?>
                <tr>
                    <th style="font-size: 14px; font-weight: normal; color: #7f7f7f">적립금</th>
                    <td style="text-align: right;">(-)<?= number_format($od['od_receipt_point']) ?>원</td>
                    <td colspan=6></td>
                </tr>
            <? endif ?>
            <tr class="on-big">
                <td colspan=8 style="border-top: 1px solid #000000; font-size: 14px; font-weight: normal; color: #8a8a8a; padding: 8px 0;">
                    실제 환불 금액은 반품 상품 수거 완료 이후 확정됩니다.<br>
                    한 주문에서 여러건의 취소/반품을 신청한 경우 처리 순으로 환불이 진행되므로, 접수 시 환불 예정 금액과 실제 환불 금액은 달라질 수 있습니다.
                </td>
            </tr>
        </table>

        <div class="on-small">
            <div style="margin-top : 1rem;">
                <div class="textfild12">
                    <p class="Tleft" style="font-weight: 500;">결제 금액</p>
                    <p class="Tright"><?= number_format($od_last_price) ?>원</p>
                </div>
                <div class="textfild12">
                    <p class="Tleft">상품 금액</p>
                    <p class="Tright"><?= number_format($tot_sell_price) ?>원</p>
                </div>
                <div class="textfild12">
                    <p class="Tleft">배송비</p>
                    <p class="Tright"><?= number_format($tot_send_cost) ?>원</p>
                </div>
                <div class="textfild12">
                    <p class="Tleft">쿠폰 할인(상품)</p>
                    <p class="Tright">(-)<?= number_format($tot_send_cost) ?>원</p>
                </div>
            </div>
            <div style="padding-top : 1rem; border-top : 1px solid #f7f7f7;border-bottom : 1px solid #f7f7f7">
                <div class="textfild12">
                    <p class="Tleft" style="font-weight: 500;">차감 금액</p>
                    <p class="Tright"><?= number_format($od_last_price - $tot_sell_price) ?>원</p>
                </div>
                <div class="textfild12">
                    <p class="Tleft">반품배송비</p>
                    <p class="Tright"><?= number_format($tot_send_cost) ?>원</p>
                </div>
            </div>

            <div class="textfild16">
                <p style="font-weight: bold; color: #000000; float:left">환물 예정 금액</p>
                <p style=" font-weight: 600;text-align: right;color: #e65026;"><?= number_format($tot_sell_price) ?>원</p>
            </div>
        </div>

        <div class="on-small" style="margin: 18px -20px;background-color:#f2f2f2;">
            <div style="padding : 8px 20px;">
                <span style="font-size: 12px;font-weight: 500;color: #7f7f7f;">
                    - 실제 환불 금액은 반품 상품 수거 완료 이후 확정됩니다.<br>
                    - 한 주문에서 여러건의 취소/반품을 신청한 경우 처리 순으로 환불이 진행되므로, 접수 시 환불 예정 금액과 실제 환불 금액은 달라질 수 있습니다.
                </span>
            </div>
        </div>


        <div class="member-content-title on-big" style="margin-top: 80px;">반품 사유 입력</div>
        <div class="member-content-title on-small" style="margin-top: 16px;">반품 사유 입력</div>

        <table id="table-cancel-address" class="on-big">
            <tr>
                <td style="width: 100px;">반품 사유 선택</td>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-0" name="cancel_select" value="단순변심" required>
                        <label class="custom-control-label" for="cancel-select-0">단순변심</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-1" name="cancel_select" value="서비스불만족" required>
                        <label class="custom-control-label" for="cancel-select-1">서비스 불만족</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-2" name="cancel_select" value="상품파손" required>
                        <label class="custom-control-label" for="cancel-select-2">상품파손</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-3" name="cancel_select" value="상품정보상이" required>
                        <label class="custom-control-label" for="cancel-select-3">상품정보 상이</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-4" name="cancel_select" value="오배송" required>
                        <label class="custom-control-label" for="cancel-select-4">오배송</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-5" name="cancel_select" value="색상및사이즈변경" required>
                        <label class="custom-control-label" for="cancel-select-5">색상 및 사이즈 변경</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input cancel-select" id="cancel-select-6" name="cancel_select" value="다른상품잘못주문" required>
                        <label class="custom-control-label" for="cancel-select-6">다른 상품 잘못 주문</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>반품 상세 사유</td>
                <td>
                    <div class="input-group" style="width: 360px;">
                        <input type="textarea" class="form-control form-input" name="cancel_memo" placeholder="상세 사유를 최대 50자 이내로 입력 해 주세요" maxlength="50">
                    </div>
                </td>
            </tr>
        </table>

        <div class="on-small">
            <div class="textfild14" style="height : 32px; padding :16px 0 ;">
                <p style="float : left; line-height : 32px; padding-right : 30px;">반품 사유 선택</p>
                <select class="cancel-selectbar" name="cancel_select" style="float : left; line-height : 32px; height: 32px; width:96px;">
                    <option id="cancel-select-0" value="단순변심">단순변심</option>
                    <option id="cancel-select-1" value="오배송">오배송</option>
                    <option id="cancel-select-2" value="상품파손">상품파손</option>
                </select>
            </div>
            <div class="textfild14" style="height : 32px;clear: both;">
                <p style=" line-height : 32px; text-align : left;">반품 상세 사유</p>
            </div>
            <div class="input-group wrap" style="width: 100%; height : 200px; position:relative;">
                <textarea type="textarea" id="content" style="height : 200px;" class="form-control form-input return_content" name="cancel_memo" placeholder="(필수) 내용을 입력하세요. &#13;&#10; 한번 등록된 문의 내용은 수정할 수 없습니다." maxlength="100"></textarea>
                <span id="counter" style="position:absolute;position: absolute;right: 5px;bottom: 5px;font-size: 10px;z-index: 3;">###</span>
            </div>
        </div>

        <div class="member-content-title on-big" style="margin-top: 80px;">반품 수거지 확인</div>
        <div class="member-content-title on-small" style="margin-top: 16px;">반품 수거지 확인
            <button type="button" class="btn btn-cart" style="margin-top: unset; margin-left: 8px; float: right;" onclick="toggleAddressRow(1)">배송지 변경</button>
        </div>
        <div id="content_text">
            반품 신청이 완료되면 입력된 배송지로 1-3일 이내 반품 수거가 진행됩니다. 반품하실 상품을 미리 준비하시기 바랍니다.<br>
            구성품이 누락된 경우 반품이 취소될 수 있습니다. 꼼꼼한 재포장 부탁드립니다.
        </div>

        <div style="font-size : 12px; width:100%;" class="on-small">
            <div>
                <p style="width : 31% ; float : left;">수령인</p>
                <p><?= $member['mb_name'] ?> 님</p>
            </div>
            <div>
                <p style="width : 31% ; float : left;">연락처</p>
                <p><?= hyphen_hp_number($member['mb_hp']) ?></p>
            </div>

            <div class="row-address row-address-0 active">
                <p style="width : 31% ; float : left;">주소</p>
                <p>
                    <span id="od_b_addr">
                        (<?= $member['mb_zip1'] ?><?= $member['mb_zip2'] ?>) <?= $member['mb_addr1'] ?> <?= $member['mb_addr2'] ?>
                    </span>
                </p>
            </div>

            <div class="row-address row-address-1">
                <p>주소</p>
                <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?= $member['mb_addr3'] ?>">
                <input type="hidden" name="od_b_addr_jibeon" value="<?= $member['mb_addr_jibeon'] ?>">
                <div class="input-group" style="">
                    <input type="text" class="form-control form-input" id="od_b_zip" name="od_b_zip" title="우편번호" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $member['mb_zip1'] ?><?= $member['mb_zip2'] ?>">
                    <div class="input-group-append" id="btn-mb-zip">
                        <button class="btn btn-outline-secondary btn-black-2" type="button" style="color :#000000;background-color : #e0e0e0; margin-top: 0;width:90px; height : 50px;" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">우편번호 검색</button>
                    </div>
                </div>
            </div>

            <div class="row-address row-address-1">
                <div class="input-group" style="">
                    <input type="text" class="form-control form-input" id="od_b_addr1" name="od_b_addr1" value="<?= $member['mb_addr1'] ?>">
                </div>
            </div>
            <div class="row-address row-address-1">
                <div class="input-group" style="">
                    <input type="text" class="form-control form-input" id="od_b_addr2" name="od_b_addr2" value="<?= $member['mb_addr2'] ?>">
                    <div class="input-group-append" id="btn-mb-addr2">
                        <button class="btn btn-outline-secondary btn-black-2" type="button" style="color :#000000;background-color : #e0e0e0;margin-top: 0; width:90px; height : 50px;" onclick="toggleAddressRow(0)">변경</button>
                    </div>
                </div>

            </div>


        </div>

        <table id="table-cancel-address" class="on-big">
            <tr>
                <td style="width: 100px;">이름</td>
                <td>
                    <?= $member['mb_name'] ?>
                </td>
            </tr>
            <tr>
                <td>연락처</td>
                <td>
                    <?= hyphen_hp_number($member['mb_hp']) ?>
                </td>
            </tr>
            <tr class="row-address row-address-0 active">
                <td>주소</td>
                <td>
                    <span id="od_b_addr">
                        (<?= $member['mb_zip1'] ?><?= $member['mb_zip2'] ?>) <?= $member['mb_addr1'] ?> <?= $member['mb_addr2'] ?>
                    </span>
                    <button type="button" class="btn btn-cart on-big" style="margin-top: unset; margin-left: 8px;" onclick="toggleAddressRow(1)">배송지 변경</button>
                </td>
            </tr>
            <tr class="row-address row-address-1">
                <td rowspan=3>주소</td>
                <td>
                    <!-- <div class="input" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');"> -->
                    <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?= $member['mb_addr3'] ?>">
                    <input type="hidden" name="od_b_addr_jibeon" value="<?= $member['mb_addr_jibeon'] ?>">
                    <div class="input-group" style="width: 360px;">
                        <input type="text" class="form-control form-input" id="od_b_zip" name="od_b_zip" title="우편번호" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');" readonly aria-describedby="btn-mb-zip" value="<?= $member['mb_zip1'] ?><?= $member['mb_zip2'] ?>">
                        <div class="input-group-append" id="btn-mb-zip">
                            <button class="btn btn-outline-secondary btn-black-2" type="button" style="margin-top: 0" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">우편번호 검색</button>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="row-address row-address-1">
                <td>
                    <div class="input-group" style="width: 360px;">
                        <input type="text" class="form-control form-input" id="od_b_addr1" name="od_b_addr1" value="<?= $member['mb_addr1'] ?>">
                    </div>
                </td>
            </tr>
            <tr class="row-address row-address-1">
                <td>
                    <div class="input-group" style="width: 360px;">
                        <input type="text" class="form-control form-input" id="od_b_addr2" name="od_b_addr2" value="<?= $member['mb_addr2'] ?>">
                        <div class="input-group-append" id="btn-mb-addr2">
                            <button class="btn btn-outline-secondary btn-black-2" type="button" style="margin-top: 0" onclick="toggleAddressRow(0)">변경</button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="on-small" style="width : 100%; height : 66px; border-top : 1px solid #f2f2f2">
            <button type="button" class="btn btn-black" style="width: 100%; height: 50px; float:right; margin-top: 16px; font-size: 18px; font-weight: 500;" onclick="confirm_cancel()">반품 신청 완료</button>
        </div>

        <div class="on-big">
            <div class="member-content-title" style="margin-top: 80px;">주문 결제 정보</div>
            <div class="member-order-detail-summary">
                <span>결제 방법</span><span style="margin-left: 8px; margin-right: 160px; font-weight: 600; color: #00bbb4"><?= $od['od_settle_case'] ?></span>
                <span>결제 완료 일시</span><span style="margin-left: 8px; font-weight: 600;"><?= date("Y-m-d H:i", strtotime($od['od_receipt_time'])) ?></span>
            </div>

            <input type="hidden" name="od_id" value="<?= $od['od_id']; ?>">
            <input type="hidden" name="ct_id_arr" value="<?= implode(',', $ct_id) ?>">
            <input type="hidden" name="token" value="<?= $token; ?>">
            <input type="hidden" name="act" value="return">
            <input type="hidden" name="od_send_cost" value="<?= $od['od_send_cost']; ?>">
            <input type="hidden" name="od_send_cost2" value="<?php echo $default['de_return_costs']; ?>">
            <? foreach ($target as $ti => $t) : ?>
                <input type="hidden" name="chk[]" value="<?= $ti ?>">
                <? foreach ($t as $tname => $tvalue) : ?>
                    <input type="hidden" name="<?= $tname ?>[<?= $ti ?>]" value="<?= $tvalue ?>">
                <? endforeach ?>
            <? endforeach ?>
            <div style="margin-top: 16px; padding-left: 24px;">
                <span style="height: 40px; font-size: 14px; line-height: 22px; color: #7f7f7f; display: inline-block;">
                    <span class="custom-checkbox"><input type="checkbox" id="confirm-cancel" class="custom-control-input">
                        <label for="confirm-cancel" class="custom-control-label">(필수) 주문 및 취소 상품 정보(상품명, 가격, 배송정보, 할인내역)을 확인하였으며 이에 동의합니다.</label>
                    </span>
                </span>
                <button type="button" class="btn btn-black" style="width: 100px; height: 50px; float:right; margin-top: 0; font-size: 12px; font-weight: 500;" onclick="confirm_cancel()">반품 신청 완료</button>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleAddressRow(rowIdx) {
        if (rowIdx == 0) {
            const od_b_addr = "(" + $("#od_b_zip").val() + ") " + $("#od_b_addr1").val() + " " + $("#od_b_addr2").val();
            $("#od_b_addr").text(od_b_addr);
        }

        $(".row-address").removeClass("active");
        $(".row-address-" + rowIdx).addClass("active");
    }

    function confirm_cancel() {
        var deviceChk = false;
        var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
        for (var word in mobileKeyWords) {
            if (navigator.userAgent.match(mobileKeyWords[word]) != null) {
                deviceChk = true;
                break;
            } else {
                if ($(window).width() < 700) {
                    deviceChk = true;
                }
            }
        }

        const form = $("#forderform");
        if (deviceChk == false) {
            if ($("input.cancel-select:checked").length <= 0) {
                alert("반품 사유를 선택해주세요.");
                return false;
            }
            if ($("#confirm-cancel").prop("checked") == false) {
                alert("주문 취소 및 상품정보 확인에 동의해주세요.");
                return false;
            }
        }
        return form.submit();
    }

    $(function() {
        $('#content').keyup(function(e) {
            var content = $(this).val();
            //$(this).height(((content.split('\n').length + 1) * 1.5) + 'em');
            $('#counter').html(content.length + '/100');
        });
        $('#content').keyup();



    });
</script>

<?
$tmp_ods = array();
$contents = ob_get_contents();
ob_end_clean();
return $contents;
?>