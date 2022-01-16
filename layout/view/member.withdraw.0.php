<?php
$g5_title = "회원정보수정";
ob_start();
include_once G5_LAYOUT_PATH . "/nav.member.php";
?>
<style>
    .member-content-desc {
        padding-top: 90px;
    }

    #input-member-info-password {
        width: 340px;
    }

    #btn-member-info-password {
        margin: 0;
        margin-left: 20px;
    }

    @media (max-width: 1366px) {
        .member-content-desc {
            padding-top: 20px;
        }

        .mo_hard_line,
        .nav-samll-member-top {
            display: none;
        }

        #member-info-password {
            padding: 20px 14px;
        }

        #input-member-info-password {
            display: block;
            width: 100%;
        }

        #btn-member-info-password {
            width: 100%;
            margin-left: 0;
            margin-top: 30px;
            display: block;
        }
    }
</style>
<div id="member-content-wrapper">
    <form action="" method="POST" id="form-account" name="form_account">
        <input type="hidden" name="step" value=1>
        <div class="member-content-title on-big">
            회원 탈퇴
        </div>
        <div id="member-info-password">
            <div style="text-align: left;">
                <div style="font-size: 16px; font-weight: 500; color: #333333;">비밀번호 확인</div>
                <div style="font-size: 14px; font-weight: normal; color: #565656; padding: 10px 0 18px 0;">안전한 개인정보 변경을 위해 비밀번호를 확인합니다.</div>
                <div class="on-small" style="font-size: 12px; font-weight: 500; color: #565656;">
                    비밀번호<span class="point-require">*</span>
                </div>
                <div>
                    <input type="password" id="input-member-info-password" class="form-input" name="mb_password" placeholder="비밀번호(영문+숫자+특수문자 조합 8자리 이상)"> <button type="submit" id="btn-member-info-password" class="btn-member">확인</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
$contents = ob_get_contents();
ob_end_clean();

return $contents;
?>;