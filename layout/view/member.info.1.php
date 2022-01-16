<?php
$g5_title = "회원 정보 변경";
ob_start();
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .row-password {
        display: none;
    }

    .row-password.active {
        display: table-row;
    }

    .member-content-sub-title {
        line-height: 54px;
        font-size: 16px;
        font-weight: bold;
        color: #000000;
        border-top: 1px solid #000000;
        border-bottom: 1px solid #e0e0e0;
        margin-top: 64px;
    }

    .member-content-sub-title.first {
        margin-top: unset;
    }

    .member-content-desc table>tbody>tr>td {
        font-size: 14px;
        height: 44px;
    }

    .member-content-desc table>tbody>tr:first-child>td {
        padding-top: 14px;
    }

    .btn.btn-cart {
        margin-top: unset;
    }

    .member-content-desc label {
        font-size: 14px;
        color: #7f7f7f;
    }

    .member-content-desc:last-child {
        padding-bottom: 60px;
    }

    #member-info-wrapper {
        width: 700px;
        padding: 40px 60px;
        border: 1px solid #e0e0e0;
        border-radius: 2px;
    }

    #member-info-wrapper th {
        font-size: 14px;
        font-weight: 500;
        color: #333333;
    }

    #member-info-wrapper td {
        padding: 8px 0;
        font-size: 14px;
        color: #333333;
    }

    #member-info-wrapper input[type='text'],
    #member-info-wrapper input[type='password'] {
        width: 340px;
    }

    @media (max-width: 1366px) {

        .mo_hard_line,
        .nav-samll-member-top {
            display: none;
        }

        .member-content-desc {
            border-top: 8px solid #f2f2f2;
            padding: 20px;
            padding-top: unset;
        }

        .member-content-sub-title {
            border-top: unset;
            margin-top: unset;
        }

        #member-info-wrapper {
            width: 100%;
            padding: 24px 14px;
            border: unset;
            border-bottom: 10px solid #e0e0e0;
        }

        #member-info-wrapper th {
            display: none;
        }

        #member-info-wrapper td {
            font-size: 12px;
            font-weight: 500;
            font-size: 12px;
            font-weight: bold;
            padding: unset;
            padding-top: 4px;
        }

        #member-info-wrapper button {
            margin-left: 10px;
        }

        #member-info-wrapper input[type='text'],
        #member-info-wrapper input[type='password'] {
            width: 100%;
        }

        #member-info-maketing {
            display: flex;
            justify-content: space-between;
        }

        #member-info-maketing>div {
            width: 50%;
            font-weight: normal;
        }

        .btn-member {
            font-size: 14px;
        }
    }
</style>
<div id="member-content-wrapper">
    <form action="/bbs/register_form_update.php" method="POST" id="form-account" name="form_account">
        <input type="hidden" name="w" value="u">

        <input type="hidden" id="form-mb-dupinfo" name="mb_dupinfo" value="">
        <input type="hidden" id="form-mb-name" name="mb_name" value="<?= $member['mb_name'] ?>">
        <input type="hidden" id="form-mb-hp" name="mb_hp" value="<?= $member['mb_hp'] ?>">
        <input type="hidden" id="form-mb-birth" name="mb_birth" value="<?= $member['mb_birth'] ?>">
        <input type="hidden" id="form-mb-sex" name="mb_sex" value="<?= $member['mb_sex'] ?>">
        <input type="hidden" id="mb_addr_jibeon" name="mb_addr_jibeon" value="">
        <input type="hidden" id="mb_addr3" name="mb_addr3" value="">
        <div class="member-content-title on-big">
            회원 정보 수정
        </div>
        <div id="member-info-wrapper">
            <table style="width: 100%;">
                <tr>
                    <td class="on-small" style="padding-top: 16px;">아이디</td>
                </tr>
                <tr>
                    <th style="width: 120px;">아이디</th>
                    <td>
                        <? if ($is_social_login) : ?>
                            <img style="width: 24px; height: 24px;" src="/img/re/<?= $member_social['provider'] ?>@3x.png">
                            <input type="hidden" id="mb_email" name="mb_email" value="<?= $member['mb_email'] ?>">
                            <input type="hidden" name="mb_id" value="<?= $member['mb_id'] ?>">
                        <? else : ?>
                            <input type="text" name="mb_id" value="<?= $member['mb_id'] ?>" readonly>
                        <? endif ?>
                    </td>
                </tr>
                <? if ($is_social_login == false) : ?>
                    <tr class="row-password row-password-0 active">
                        <td class="on-small" style="padding-top: 16px;">비밀번호</td>
                    </tr>
                    <tr class="row-password row-password-0 active">
                        <th style="width: 120px;">비밀번호</th>
                        <td style="display: flex; justify-content: space-between;">
                            <input type="password" class="form-control form-input" value="PASSWORD" readonly>
                            <button type="button" class="btn-member btn-toggle-password">변경</button>
                        </td>
                    </tr>
                    <tr class="row-password row-password-1">
                        <td class="on-small" style="padding-top: 16px;">현재 비밀번호<span class="point-require">*</span></td>
                    </tr>
                    <tr class="row-password row-password-1">
                        <th style="width: 120px;">현재 비밀번호</th>
                        <td>
                            <input type="password" class="form-control form-input" placeholder="현재 비밀번호" name="mb_password_org">
                        </td>
                    </tr>
                    <tr class="row-password row-password-1">
                        <td class="on-small" style="padding-top: 16px;">신규 비밀번호<span class="point-require">*</span></td>
                    </tr>
                    <tr class="row-password row-password-1">
                        <th style="width: 120px;">신규 비밀번호</th>
                        <td>
                            <input type="password" class="form-control form-input" placeholder="신규 비밀번호" name="mb_password">
                        </td>
                    </tr>
                    <tr class="row-password row-password-1">
                        <td class="on-small" style="padding-top: 16px;">비밀번호 확인<span class="point-require">*</span></td>
                    </tr>
                    <tr class="row-password row-password-1">
                        <th style="width: 120px;">비밀번호 확인</th>
                        <td>
                            <input type="password" class="form-control form-input" placeholder="신규 비밀번호 확인" name="mb_password_re">
                        </td>
                    </tr>
                    <tr class="row-password row-password-1 on-big">
                        <th style="width: 120px;"></th>
                        <td>
                            <span style="display: inline-block; width: 340px; text-align: right;">
                                <button type="button" class="btn-member btn-white btn-toggle-password">취소</button>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="on-small" style="padding-top: 16px;">이메일</td>
                    </tr>
                    <tr>
                        <th style="width: 120px;">이메일</th>
                        <td>
                            <input type="text" class="form-control form-input" name="mb_email" aria-describedby="btn-mb-email" value="<?= $member['mb_email'] ?>">
                        </td>
                    </tr>
                <? else : ?>
                    <tr style="display: none;">
                        <th style="width: 120px;">이메일</th>
                        <td>
                            <input type="hidden" class="form-control form-input" name="mb_email" aria-describedby="btn-mb-email" value="<?= $member['mb_email'] ?>">
                        </td>
                    </tr>
                <? endif ?>
                <tr>
                    <td class="on-small" style="padding-top: 16px;">이름</td>
                </tr>
                <tr>
                    <th style="width: 120px;">이름</th>
                    <td>
                        <input type="text" id="pass-name" class="form-control form-input" value="<?= $member['mb_name'] ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td class="on-small" style="padding-top: 16px;">휴대폰번호</td>
                </tr>
                <tr>
                    <th style="width: 120px;">휴대폰번호</th>
                    <td style="display: flex; justify-content: space-between;">
                        <input type="text" id="pass-hp" class="form-control form-input" value="<?= hyphen_hp_number($member['mb_hp']) ?>" readonly>
                        <button type="button" class="btn-member" onclick="passDoAuth()">변경</button>
                    </td>
                </tr>
                <tr>
                    <td class="on-small" style="padding-top: 16px;">생년월일</td>
                </tr>
                <tr>
                    <th style="width: 120px;">생년월일</th>
                    <td>
                        <input type="text" id="pass-birth" class="form-control form-input" value="<?= date("Y.m.d", strtotime($member['mb_birth'])) ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td class="on-small" style="padding-top: 16px;">성별</td>
                </tr>
                <tr>
                    <th style="width: 120px;">성별</th>
                    <td>
                        <input type="text" id="pass-sex" class="form-control form-input" value="<?= $member['mb_sex'] == "M" ? "남자" : "여자" ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <td class="on-small" style="padding-top: 16px;">광고성정보수신동의</td>
                </tr>
                <tr>
                    <th style="width: 120px;">광고성정보수신동의</th>
                    <td id="member-info-maketing">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="agree-mkt-1" name="mb_mailling" value="1" <?= get_checked($member['mb_mailling'], 1) ?>>
                            <label class="custom-control-label" for="agree-mkt-1">문자메시지</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="agree-mkt-2" name="mb_sms" value="1" <?= get_checked($member['mb_sms'], 1) ?>>
                            <label class="custom-control-label" for="agree-mkt-2">이메일</label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="on-big">
            <span style="display: inline-block; width: 700px; text-align: center; margin-top: 40px;">
                <button type="button" class="btn-member btn-white" onclick="location.reload()">취소</button>
                <button type="submit" class="btn-member" style="margin-left: 10px;">확인</button>
            </span>
        </div>
        <div class="on-small" style="text-align: right; padding: 0 14px; margin-top: 8px;">
            <a href="/member/withdraw.php">
                <button type="button" class="btn-member btn-white btn-xs">회원 탈퇴</button>
            </a>
        </div>
        <div class="on-small" style="padding: 0 14px; margin-top: 40px;">
            <button type="button" style="width: calc(50% - 7px);" class="btn-member btn-white" onclick="location.reload()">취소</button>
            <button type="submit" style="width: calc(50% - 7px); margin-left: 14px;" class="btn-member" style="margin-left: 14px;">확인</button>
        </div>
    </form>
    <div class="on-big" style="margin-bottom: 80px;"></div>
</div>

<style>
    .btn-bank-tab {
        line-height: 50px;
        width: 50%;
        display: inline-block;
        text-align: center;
        background-color: #f2f2f2;
        color: #7f7f7f;
        font-size: 14px;
    }

    .btn-bank-tab.active {
        background-color: #ffffff;
        color: #000000;
    }

    div.bank-tab-bank {
        display: none;
        padding: 20px;
        padding-top: 12px;
        font-size: 0;
    }

    div.bank-tab-bank.active {
        display: block;
    }

    div.bank-tab-bank>button {
        cursor: pointer;
        font-size: 12px;
        font-weight: normal;
        text-align: center;
        color: #7f7f7f;
        width: calc(100% / 3 + 1px);
        height: 32px;
        border: solid 1px #cecece;
        background-color: #ffffff;
        margin-right: -1px;
        margin-top: 8px;
    }
</style>
<!--
<div class="modal fade" id="modal-bank-account" tabindex="-1" role="dialog" aria-labelledby="btn-select-bank" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 100%; max-width: 360px; height: 600px;">
        <div id="modal-bank-account-content" class="modal-content" style="height: 100%; width: 100%; margin: unset; padding: unset !important; background-clip: border-box;">
            <div style="font-size: 0;">
                <span id="btn-bank-tab-bank" class="btn-bank-tab bank-tab-bank active" data-type="bank">은행</span>
                <span id="btn-bank-tab-cma" class="btn-bank-tab bank-tab-bank" data-type="cma">증권</span>
            </div>
            <div id="bank-tab-bank" class="bank-tab-bank active">
                <? for ($i = 0; $i < count($bank_set['bank']); $i++) : ?>
                    <button type="button" onclick="selectBank('<?= $bank_set['bank'][$i] ?>')"><?= $bank_set['bank'][$i] ?></button>
                <? endfor ?>
            </div>
            <div id="bank-tab-cma" class="bank-tab-bank">
                <? for ($i = 0; $i < count($bank_set['cma']); $i++) : ?>
                    <button type="button" onclick="selectBank('<?= $bank_set['cma'][$i] ?>')"><?= $bank_set['cma'][$i] ?></button>
                <? endfor ?>
            </div>
        </div>
    </div>
</div>
                -->

<!-- PASS 본인인증 FORM -->
<form id="form-pass" method="post">
    <input type="hidden" name="m" value="checkplusService">
    <input type="hidden" name="EncodeData" value="<?php echo $enc_data ?>">
</form>

<script>
    function passDoAuth() {
        const urlPass = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
        const winPass = window.open('', 'popupPass', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');

        $("#form-pass").prop("action", urlPass).prop("target", "popupPass").submit();
    }

    function passResponse(response) {
        console.log(response);
        if (response.error.length > 0) {
            alert(response.error);
        } else {
            let birthdate = response.data.BIRTHDATE.substr(0, 4) + "." + response.data.BIRTHDATE.substr(4, 2) + "." + response.data.BIRTHDATE.substr(6, 2);
            let hp = response.data.MOBILE_NO.substr(0, 3) + "-" + response.data.MOBILE_NO.substr(3, 4) + "-" + response.data.MOBILE_NO.substr(7, 4);
            if (response.data.MOBILE_NO.length < 11) {
                hp = response.data.MOBILE_NO.substr(0, 3) + "-" + response.data.MOBILE_NO.substr(3, 3) + "-" + response.data.MOBILE_NO.substr(6, 4);
            }

            $("#form-mb-name").val(response.data.UTF8_NAME);
            $("#form-mb-hp").val(response.data.MOBILE_NO);
            $("#form-mb-birth").val(response.data.BIRTHDATE);
            $("#form-mb-sex").val(response.data.GENDER_PLAIN);
            $("#form-mb-dupinfo").val(response.data.CI);

            $("#pass-name").val(response.data.UTF8_NAME);
            $("#pass-hp").val(hp);
            $("#pass-birth").val(birthdate);
            $("#pass-sex").val(response.data.GENDER);
        }
    }

    $(".btn-toggle-password").on("click", function() {
        const $rowPassword = $(".row-password.active");
        $rowPassword.removeClass("active");

        if ($rowPassword.length > 2) {
            $(".row-password-0").addClass("active");
            $(".row-password-1 input[type='text']").val("");
        } else {
            $(".row-password-1").addClass("active");
        }

    });
</script>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;