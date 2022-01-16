<div class="modal fade" id="modal-join" tabindex="-1" role="dialog" aria-labelledby="modal-join" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-join-content" class="modal-content">
            <div style="align-content: center; text-align: center; padding: 37px 0 14px 0;">
                <img style="height: 24px" src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
            </div>
            <div style="padding-bottom: 14px; border-bottom: 1px solid var(--very-light-pink-two);">
                <a href="/auth/join.php">
                    <button type="submit" class="btn btn-join-email">회원가입</button>
                </a>
            </div>
            <div class="C1KOBLM" style="line-height: 24px; margin: 14px 0 14px 0; text-align: center;">SNS로 간편가입</div>
            <div style="text-align: center;">
                <? if (social_service_check('naver')) : ?>
                    <img class="btn-sns-join btn-sns-naver" src="/img/re/naver.png" data-sns="naver" srcset="/img/re/naver@2x.png 2x,/img/re/naver@3x.png 3x">
                <? endif ?>
                <? if (social_service_check('kakao')) : ?>
                    <img class="btn-sns-join btn-sns-kakao" src="/img/re/kakao.png" data-sns="kakao" srcset="/img/re/kakao@2x.png 2x,/img/re/kakao@3x.png 3x">
                <? endif ?>
                <? if (social_service_check('facebook')) : ?>
                    <img class="btn-sns-join btn-sns-fb" src="/img/re/facebook.png" data-sns="facebook" srcset="/img/re/facebook@2x.png 2x,/img/re/facebook@3x.png 3x">
                <? endif ?>
                <? if (social_service_check('twitter')) : ?>
                    <img class="btn-sns-join btn-sns-twitter" src="/img/re/twitter.png" data-sns="twitter" srcset="/img/re/twitter@2x.png 2x,/img/re/twitter@3x.png 3x">
                <? endif ?>
                <? if (social_service_check('google')) : ?>
                    <img class="btn-sns-join btn-sns-google" src="/img/re/google.png" data-sns="google" srcset="/img/re/google@2x.png 2x,/img/re/google@3x.png 3x">
                <? endif ?>
                <? if (social_service_check('payco')) : ?>
                    <img class="btn-sns-join btn-sns-payco" src="/img/re/payco.png" data-sns="payco" srcset="/img/re/payco@2x.png 2x,/img/re/payco@3x.png 3x">
                <? endif ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-join-agree" tabindex="-1" role="dialog" aria-labelledby="modal-join-agree" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-join-agree-content" class="modal-content">
            <div style="align-content: center; text-align: center; padding: 37px 0 4px 0;">
                <img style="height: 24px" src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
            </div>
            <div class="P3KOBLM" style="padding-bottom: 14px; border-bottom: 1px solid var(--very-light-pink-two); text-align: center; width: calc(100% - 40px); margin-left: 20px;">
                라이프라이크 / 리탠다드(주)
            </div>
            <div class="custom-checkbox C1KOGRL" style="line-height: 22px; margin: 7px 0 14px 0; text-align: left; padding: 0px 50px 8px 60px; border-bottom: 1px solid var(--very-light-pink-two); width: calc(100% - 40px); margin-left: 20px;">
                <input type="checkbox" class="custom-control-input cbg-sns-join-agree-all" id="sns-join-agree-all" data-checkall="cbg-sns-join-agree-all" data-checkgroup="cbg-sns-join-agree-all">
                <label class="custom-control-label" for="sns-join-agree-all">전체 동의하기</label>
            </div>
            <div class="custom-checkbox LAKOBLL" style="margin: 0 0 49px 0; text-align: left; padding-left: 80px; padding-right: 50px; height: 75px;">
                <input type="checkbox" class="custom-control-input cbg-sns-join-agree-all" id="sns-join-agree" data-checkgroup="cbg-sns-join-agree-all" required>
                <label class="custom-control-label" for="sns-join-agree">
                    라이프라이크 서비스 내 이용자 식별, 회원관리 및서비스 제공을 위해 개인 정보를 제공합니다. 정보는 서비스 탈퇴 시 지체없이 파기됩니다.
                    [필수] 필수 제공 항목
                    프로필 정보(이메일, 닉네임, 프로필 ….)
                </label>
            </div>
            <div>
                <button type="button" id="btn-sns-join-agree" class="btn btn-join-email">동의하고 계속하기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-join-coupon" tabindex="-1" role="dialog" aria-labelledby="modal-join-agree" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-join-coupon-content" class="modal-content" style="padding: 0 !important; height: 176px;">
            <div style="align-content: center; text-align: center; padding: 37px 0 4px 0;">
                <img style="height: 24px" src="/img/re/logo.png" srcset="/img/re/logo@2x.png 2x,/img/re/logo@3x.png 3x" class="logo">
            </div>
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: center;">
                [쿠폰 유형]이 발급되었습니다.<br />
                회원 가입 완료 후 마이페이지에서 확인해주세요.
            </div>
            <div>
                <button type="button" id="btn-join-coupon-agree" class="btn btn-join-email">계속하기</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade on-big" id="modal-popup-rule-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: unset;">
        <div id="modal-popup-rule-content-wrapper" class="modal-content" style="padding: 16px !important; height: auto; width: 100%; margin-left: unset;">
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: center;">
                <div id="modal-popup-rule-content" style="font-size: 16px;"></div>
            </div>
            <div id="modal-popup-rule-button" style="text-align: center;">
                <button type="button" class="btn btn-popup" style="width: 90px;" onclick="$('#modal-popup-rule-wrapper').modal('hide')">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade on-small" id="modal-popup-rule-wrapper-mo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: unset;">
        <div id="modal-popup-rule-content-wrapper" class="modal-content" style="padding: 16px !important; height: auto; width: 100%; margin-left: unset;">
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: center;">
                <div id="modal-popup-rule-content-mo" style="font-size: 16px; height : 450px; overflow-y:scroll; overflow-x:hidden;"></div>
            </div>
            <div id="modal-popup-rule-button" style="text-align: center;">
                <button type="button" class="btn btn-popup" style="width: 90px;" onclick="$('#modal-popup-rule-wrapper-mo').modal('hide')">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-popup-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-popup-content-wrapper" class="modal-content" style="padding: 0 !important; height: 176px;">
            <div class="P3KOBLM" style="padding-bottom: 14px; text-align: center;">
                <div id="modal-popup-content"></div>
            </div>
            <div id="modal-popup-button" style="text-align: center;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-alert-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-alert-content-wrapper" class="modal-content" style="padding: 0 !important; height: 176px;">
            <div class="P3KOBLM">
                <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close"><img src="/img/re/x.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x"></button>
                <div id="modal-alert-content"></div>
            </div>
            <div id="modal-alert-button" style="text-align: center; position: absolute;    bottom: 0;    width: 100%; margin-bottom:15px;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_preview_img" tabindex="-1" role="dialog" aria-labelledby="modal_preview_img">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">미리보기</h4>
            </div>
            <div class="modal-body">
                <div style="width: 100%; max-height: 80%;">
                    <img id="imgPath" src="#" style="width: 50%; max-height: 80%;">
                </div>
                <div id="imgStr">
                </div>
            </div>
            <div class="modal-footer">
                <br><br><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- koo -->
<!-- 개인정보처리방칰 처리방침 ㅋㅋ -->
<div class="modal fade on-big" id="modal_stipulation_on-big" tabindex="-1" role="dialog" aria-labelledby="modal_stipulation">
    <div class="modal-dialog" id="modal-stipulation-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">라이프라이크 서비스 이용약관(구매회원)</h4>
            </div>
            <div class="modal-body" style="font-size: 14px; color: #565656;">
                <?= $config['cf_stipulation'] ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade on-big" id="modal_privacy_on-big" tabindex="-1" role="dialog" aria-labelledby="modal_privacy">
    <div class="modal-dialog" id="modal-privacy-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">개인정보 처리방침</h4>
            </div>

            <div class="modal-body" style="font-size: 14px; color: #565656;">
                <?= $config['cf_privacy'] ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade on-small" id="modal_stipulation_on-small" tabindex="-1" role="dialog" aria-labelledby="modal_stipulation">
    <div class="modal-dialog" id="modal-privacy-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">라이프라이크 서비스 이용약관(구매회원)</h4>
            </div>

            <div class="modal-body" style="font-size: 14px; color: #565656;">
                <?= $config['cf_stipulation'] ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade on-small" id="modal_privacy_on-small" tabindex="-1" role="dialog" aria-labelledby="modal_privacy">
    <div class="modal-dialog" id="modal-privacy-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">개인정보 처리방침</h4>
            </div>

            <div class="modal-body" style="font-size: 14px; color: #565656;">
                <?= $config['cf_privacy'] ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_product-detail-qna" tabindex="-1" role="dialog" aria-labelledby="modal_product-detail-qna">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header on-big">
                <div style="font-size: 26px; color: #070707; font-weight: bold; margin-left: 370px;">Q&A 작성</div>
                <!-- <h4 class="modal-title" style="font-size: 26px; color: #070707; font-weight: bold;  margin-left: 370px;">Q&A 작성</h4> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="margin-top: 20px;">&times;</span></button>
            </div>
            <div class="modal_header on-small">Q&A 작성
                <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>
            <div class="modal-body">
                <? include_once(G5_SHOP_PATH . '/itemqaform.php'); ?>
            </div>
        </div>
    </div>
</div>

<? 
    function MobileCheckPopup() {
        global $HTTP_USER_AGENT;
        $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

        $checkCount = 0;
        for($i=0; $i<sizeof($MobileArray); $i++){
            if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
        }
        return ($checkCount >= 1) ? "Mobile" : "Computer";
    }

    $nwsql = " select * from {$g5['new_win_table']}
    where '" . G5_TIME_YMDHIS . "' between nw_begin_time and nw_end_time
    and nw_device IN ( 'both', 'pc' ) and nw_status = 'Y'
    order by nw_id desc limit 1";
    if(MobileCheckPopup() == "Mobile") {
        $nwsql = " select * from {$g5['new_win_table']}
        where '" . G5_TIME_YMDHIS . "' between nw_begin_time and nw_end_time
        and nw_device IN ( 'both', 'mobile' ) and nw_status = 'Y'
        order by nw_id desc limit 1";
    }
    $nwresult = sql_query($nwsql, false);
?>
<?php
for ($i = 0; $nw = sql_fetch_array($nwresult); $i++) {
    // 이미 체크 되었다면 Continue
    if ($_COOKIE["hd_pops_{$nw['nw_id']}"])
        continue;
?> <div class="modal fade on-big" id="modal_notice_popup" tabindex="-1" role="dialog" aria-labelledby="modal_notice_popup">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div class="popup" id="hd_pops_<?php echo $nw['nw_id'] ?>" style="text-align: center;">
                    <div style="margin-left: -36px; margin-top: -16px;">
                    <div style="position: absolute; left: 880px; height: 50px; width: 17px;" data-dismiss="modal"> </div>
                    <?php
                    if ($nw['nw_link']) echo "<a href='" . $nw['nw_link'] . "'>";
                    $img_url = G5_DATA_URL . '/popup/' . $nw['nw_imgfile'];
                    echo '<img  style= z-index: 1010;" src="' . $img_url . '">';

                    if ($nw['nw_link']) echo "</a>";
                    ?>
                    </div>
                    <div class="win_btn" style="margin: auto; margin-bottom: 10px; margin-top: 20px;">
                        <input class="btn_submit" data-dismiss="modal" value="오늘은 그만보기" data-popid="hd_pops_<?= $nw['nw_id']; ?>" data-popexp="<?= $nw['nw_disable_hours']; ?>" onclick="daily24('hd_pops_<?= $nw['nw_id']; ?>')" style="font-size: 18px; font-weight: 500; cursor:pointer">
                        <input class="btn_submit" data-dismiss="modal" style="background-color: #333333; color: #ffffff; font-size: 18px; font-weight: 500; cursor:pointer" value="닫기">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="on-small">
    <div class="modal fade" id="modal-alert-wrapper-mobile" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="modal-alert-content-wrapper" class="modal-content" style="padding: 0 !important; height: 577px;">
                
            <div style="margin-left: 0px">
                    <?php
                    if ($nw['nw_link']) echo "<a href='" . $nw['nw_link'] . "'>";
                    $img_url = G5_DATA_URL . '/popup/' . $nw['nw_imgfile'];
                    echo '<img  style= "z-index: 1010; width:100%;" src="' . $img_url . '">';

                    if ($nw['nw_link']) echo "</a>";
                    ?>
                    <div class="btn-order-mobile-group on-small" id="btn-order-mobile"  style="bottom: 0; width: 100%">
                        <button class="btn btn-order-mobile-popup" data-dismiss="modal"  onclick="daily24('hd_pops_<?= $nw['nw_id']; ?>')" style="float: left;">오늘은 그만보기</button>
                        <button class="btn btn-order-mobile-popup baro" data-dismiss="modal" style="float: left;">닫기</button>
                    </div>
                </div>
            
                <div class="P3KOBLM">
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close"><img src="/img/re/x.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x"></button>
                    <div id="modal-alert-content"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
?>



<div class="modal fade" id="modal-instock-noti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width: 500px; width: 500px;">
        <div class="modal-content">
            <div style="background-color: #ffffff; text-align: center; height: 73px; border-bottom: 1px solid #e0e0e0; font-size: 26px; font-weight: bold; color: #0d0d0d; line-height: 73px;">
                재입고 알림신청
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 24px; height: 24px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: cover; position: absolute; right: 17px; top: 25px; border: unset;"></button>
            </div>
            <div id="modal-instock-noti-content">
                <table>
                    <tr>
                        <th>상품명</th>
                        <td>
                            <div class="instock-noti-brand"></div>
                            <div class="instock-noti-item"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>휴대폰번호</th>
                        <td>
                            <div class="instock-noti-phone"><?= $member['mb_hp'] ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="modal-instock-noti-button">
                <button type="button" onclick="$('#modal-instock-noti').modal('hide')">취소</button>
                <button type="button" onclick="notiInStock()" style="background-color: #333333; color: #ffffff; margin-left: 20px; font-weight: normal;">확인</button>
            </div>
            <div id="modal-instock-noti-desc">
                <div><span></span>상품이 재입고되는 즉시 등록하신 휴대폰 번호로 알림이 발송됩니다.</div>
                <div><span></span>알림 신청 후 판매종료된 상품은 알림이 발송되지 않습니다.</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bottom" id="modal-instock-noti-mobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div style="text-align: center; height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 18px; font-weight: 500; color: #090909; line-height: 50px;">
                재입고 알림신청
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: cover; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div id="modal-instock-noti-content">
                <table>
                    <tr>
                        <th>상품명</th>
                    </tr>
                    <tr>
                        <td>
                            [<span class="instock-noti-brand"></span>]
                            <span class="instock-noti-item"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>휴대폰번호</th>
                    </tr>
                    <tr>
                        <td>
                            <span class="instock-noti-phone"><?= $member['mb_hp'] ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="modal-instock-noti-button">
                <button type="button" onclick="$('#modal-instock-noti-mobile').modal('hide')">취소</button>
                <button type="button" onclick="notiInStock()" style="background-color: #333333; color: #ffffff; margin-left: 14px; font-weight: normal;">확인</button>
            </div>
            <div id="modal-instock-noti-desc">
                <div><span></span>상품이 재입고되는 즉시 등록하신 휴대폰 번호로 알림이 발송됩니다.</div>
                <div><span></span>알림 신청 후 판매종료된 상품은 알림이 발송되지 않습니다.</div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-loading" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 100px; height: 100px; margin: 0 auto;">
        <div class="modal-content" style="width: inherit; height: inherit; background: unset; box-shadow: unset; margin: unset; padding: unset !important; margin-top: calc(50vh - 50px); background-color: #ffffff; border-radius: 50px;">
            <span class="img-loading state-0"></span>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-sns-share-wrapper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="modal-sns-share-content-wrapper" class="modal-content" style="padding: 0 !important; height : 176px; height: calc(176px + env(safe-area-inset-bottom));">
            <div class="modal_header">공유하기
                <img src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>
            <input id='clip_tmp' type='text' value="<?= $sns_url ?>" style='position:absolute;top:-2000px;' />
            <div id="sns_share_data_area"></div>
            <div class="modal_body">
                <?php

                echo  get_sns_share_link_new('facebook', $sns_url, $sns_title, $sns_image, G5_URL . '/img/re/icon-facebook.png');
                echo  get_sns_share_link_new('twitter', $sns_url, $sns_title, $sns_image, G5_URL . '/img/re/icon-twitter.png');
                echo  get_sns_share_link_new('kakaotalk', $sns_url, $sns_title, $sns_image, G5_URL . '/img/re/icon-kakao.png');
                ?>
                <a onclick="copyurl('<?= $sns_url ?>')"><img src="/img/re/icon-url.png" srcset="/img/re/icon-url.png 1x, /img/re/icon-url@2x.png 2.5x, /img/re/icon-url@3x.png 4x"></a>

            </div>

            <!-- <div class="P3KOBLM">
                <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close"><img src="/img/re/x.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x"></button>
                <div id="modal-sns-share-content"></div>
            </div> -->
            <!-- <div id="modal-sns-share-button" style="text-align: center; position: absolute;    bottom: 0;    width: 100%;"></div> -->
        </div>
    </div>
</div>


<!-- 무이자혜택 -->
<div class="modal" id="modal-order-benefit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 940px;">
        <div class="modal-content" style="width: 940px; height: auto; margin-left: 0;">
            <div style="height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 16px; font-weight: 500; color: #333333; line-height: 50px;">
                무이자 할부 혜택 안내
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div id="modal-order-benefit-content" style="width: 100%; height: 700px; overflow-y: scroll; text-align: center; border: 1px solid #e0e0e0;">
                <!-- <img src="/data/card/<?= date("Y") ?>/<?= date("m") ?>.png" style="width: 779px;"> -->
                <img src="/data/card/2021/04.png">
            </div>
            <div id="modal-order-benefit-button" style="text-align: right; padding: 16px 0 32px 0;">
                <button type="button" class="btn btn-cart-action only-result" data-dismiss="modal" aria-label="close">확인</button>
            </div>
        </div>
    </div>
</div>

<!-- 무이자혜택 모바일 -->
<div class="modal" id="modal-order-benefit-mobile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 100%; height: unset; margin-left: 0; padding: 0 8px !important;">
            <div style="text-align: center; height: 50px; border-bottom: 1px solid #e0e0e0; font-size: 12px; font-weight: 500; color: #333333; line-height: 50px;">
                무이자 할부 혜택 안내
                <button type="button" data-dismiss="modal" aria-label="close" style="width: 18px; height: 18px; background: url(/img/re/cancle@3x.png) center center no-repeat; background-size: 18px; position: absolute; right: 12px; top: 15px; border: unset;"></button>
            </div>
            <div id="modal-order-benefit-content" style="width: 100%; height: 400px; overflow-y: scroll; text-align: center; border: 1px solid #e0e0e0;">
                <!-- <img src="/data/card/<?= date("Y") ?>/<?= date("m") ?>_m.png" style="width: 324px;"> -->
                <img src="/data/card/2021/04_m.png" style="width: 324px;">
            </div>
            <div id="modal-order-benefit-button" style="text-align: right; padding: 16px 0;">
                <button type="button" class="btn btn-cart-action only-result" data-dismiss="modal" aria-label="close">확인</button>
            </div>
        </div>
    </div>
</div>





<style>
    .btn-order-mobile-popup {
        width: 50%;
        height: 50px;
        color: #ffffff;
        border: solid 1px #333333;
        background-color: #000000;
        font-size: 16px;
        font-weight: 500;
    }
    .btn-order-mobile-popup:first-child {
        color: #333333;
        border: solid 1px #333333;
        background-color: #ffffff;
    }
    .btn.baro:hover {
        color: #ffffff;
    }

    .img-loading {
        width: 100px;
        height: 100px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: 100px;
    }

    .img-loading.state-0 {
        background-image: url(/img/re/loading/1@3x.png);
    }

    .img-loading.state-1 {
        background-image: url(/img/re/loading/2@3x.png);
    }

    .img-loading.state-2 {
        background-image: url(/img/re/loading/3@3x.png);
    }

    .img-loading.active {
        width: 100px;
        height: 100px;
    }

    .modal.fade:not(.show).bottom .modal-dialog {
        -webkit-transform: translate3d(0, 10%, 0);
        transform: translate3d(0, 10%, 0);
    }

    #modal_stipulation_on-big #modal-stipulation-dialog {
        margin: auto;
        max-width: 940px;
    }

    #modal_stipulation_on-big .modal-content {
        background-color: #fff;
        width: 940px;
        height: 480px;
        margin: auto;
        margin-top: 20%;
        box-shadow: var(--shadow-1);
    }

    #modal_stipulation_on-big .modal-header {
        display: block;
    }

    #modal_stipulation_on-big .modal-header .modal-title {
        font-size: 16px;
    }

    #modal_stipulation_on-big .modal-body {
        overflow-y: scroll;
        overflow-x: hidden;
        font-size: 14px;
    }

    #modal_stipulation_on-big .modal-footer button {
        width: 90px;
        height: 50px;
        margin: 0;
        padding: 0;
        color: #fff;
        border: none;
        background-color: black;
    }

    #modal_stipulation_on-small #modal-stipulation-dialog {
        margin: auto;
        max-width: 340px;
    }

    #modal_stipulation_on-small .modal-content {
        background-color: #fff;
        width: 340px;
        height: 500px;
        margin: auto;
        margin-top: 20%;
        padding: 0 !important;
        box-shadow: var(--shadow-1);
    }

    #modal_stipulation_on-small .modal-header {
        display: block;
    }

    #modal_stipulation_on-small .modal-header .modal-title {
        font-size: 12px;
    }

    #modal_stipulation_on-small .modal-body {
        overflow-y: scroll;
        overflow-x: hidden;
        font-size: 14px;
    }

    #modal_stipulation_on-small .modal-footer {
        width: 340px;
        height: 50px;
        background-color: black;
    }

    #modal_stipulation_on-small .modal-footer button {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        color: #fff;
        border: none;
    }

    #modal_privacy_on-big #modal-privacy-dialog {
        margin: auto;
        max-width: 940px;
    }

    #modal_privacy_on-big .modal-content {
        background-color: #fff;
        width: 940px;
        height: 480px;
        margin: auto;
        margin-top: 20%;
        box-shadow: var(--shadow-1);
    }

    #modal_privacy_on-big .modal-header {
        display: block;
    }

    #modal_privacy_on-big .modal-header .modal-title {
        font-size: 16px;
    }

    #modal_privacy_on-big .modal-body {
        overflow-y: scroll;
        overflow-x: hidden;
        font-size: 14px;
    }

    #modal_privacy_on-big .modal-footer button {
        width: 90px;
        height: 50px;
        margin: 0;
        padding: 0;
        color: #fff;
        border: none;
        background-color: black;
    }

    #modal_privacy_on-small #modal-privacy-dialog {
        margin: auto;
        max-width: 340px;
    }

    #modal_privacy_on-small .modal-content {
        background-color: #fff;
        width: 340px;
        height: 500px;
        margin: auto;
        margin-top: 20%;
        padding: 0 !important;
        box-shadow: var(--shadow-1);
    }

    #modal_privacy_on-small .modal-header {
        display: block;
    }

    #modal_privacy_on-small .modal-header .modal-title {
        font-size: 12px;
    }

    #modal_privacy_on-small .modal-body {
        overflow-y: scroll;
        overflow-x: hidden;
        font-size: 14px;
    }

    #modal_privacy_on-small .modal-footer {
        width: 340px;
        height: 50px;
        background-color: black;
    }

    #modal_privacy_on-small .modal-footer button {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        color: #fff;
        border: none;
    }

    #modal_product-detail-qna .modal-content {
        width: 940px;
        height: 583px;
        margin: 0;
        padding: 0;
    }

    #modal_product-detail-qna .modal-dialog {
        padding-left: 100px;
    }

    #modal_notice_popup .modal-content {
        width: 940px;
        height: auto;
        margin: 0;
        padding: 0;
    }

    #modal_notice_popup .modal-dialog {
        padding-left: 100px;
    }
    .instock-noti-brand {
        font-size: 16px;
        color: #4c4c4c;
    }

    .instock-noti-item {
        font-size: 18px;
        color: #3a3a3a;
    }

    .instock-noti-phone {
        font-size: 16px;
        color: #333333;
    }

    #modal-instock-noti>.modal-dialog,
    #modal-instock-noti-mobile>.modal-dialog {
        max-width: unset;
        background-color: #ffffff;
    }

    #modal-instock-noti>.modal-dialog>.modal-content,
    #modal-instock-noti-mobile>.modal-dialog>.modal-content {
        background-color: unset;
        width: unset;
        height: unset;
        margin-left: unset;
        padding: unset !important;
        box-shadow: unset;
    }

    #modal-instock-noti-mobile>.modal-dialog {
        width: 100%;
        margin: unset;
        bottom: 48px;
        position: absolute;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }


    #modal-instock-noti-content {
        padding: 20px;
        padding-bottom: 10px;
    }

    #modal-instock-noti-content>table th {
        vertical-align: top;
        font-size: 14px;
        font-weight: bold;
        color: #4c4c4c;
        width: 120px;
    }

    #modal-instock-noti-content>table td {
        padding-bottom: 30px;
    }

    #modal-instock-noti-button {
        text-align: center;
        font-size: 0;
        padding-bottom: 40px;
    }

    #modal-instock-noti-button>button {
        width: 110px;
        height: 44px;
        border-radius: 2px;
        border: solid 1px #333333;
        background-color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        color: #3a3a3a;
    }

    #modal-instock-noti-desc {
        border-top: 1px solid #e0e0e0;
        padding: 20px;
        padding-top: 16px;
        font-size: 12px;
        font-weight: normal;
        line-height: 1.5;
        color: #b5b5b5;
    }

    #modal-instock-noti-desc>div>span {
        display: inline-block;
        width: 3px;
        height: 3px;
        border-radius: 3px;
        border: 1px solid #acacac;
        margin-right: 4px;
        vertical-align: middle;
    }

    #modal_product-detail-qna .headText.qna-type {
        line-height: 44px;
    }

    #modal_product-detail-qna .qna_title {
        margin-top: 10px;
    }

    #modal_product-detail-qna .win_btn {
        width: 89%;
        /* margin: 20px auto; */
    }

    /* #modal_product-detail-qna .win_btn input {width : calc(50% - 7px); height : 44px; text-align : center;  font-size: 14px;  font-weight: 500;} */

    #modal_product-detail-qna .win_btn input {
        height: 52px;
        width: 340px;
        text-align: center;
        font-size: 14px;
        font-weight: 500;
    }

    #modal_notice_popup .headText.qna-type {
        line-height: 44px;
    }

    #modal_notice_popup .qna_title {
        margin-top: 10px;
    }

    #modal_notice_popup .win_btn {
        width: 89%;
        /* margin: 20px auto; */
    }

    /* #modal_notice_popup .win_btn input {width : calc(50% - 7px); height : 44px; text-align : center;  font-size: 14px;  font-weight: 500;} */

    #modal_notice_popup .win_btn input {
        height: 52px;
        width: 340px;
        text-align: center;
        font-size: 14px;
        font-weight: 500;
    }


    #modal-sns-share-wrapper .modal-dialog {
        margin-top: 340px;
    }

    #modal-sns-share-wrapper .modal_header {
        height: 50px;
        line-height: 50px;
        text-align: center;
        font-size: 18px;
        font-weight: 500;
        color: #090909;
        position: relative;
        border-bottom: 1px solid #e0e0e0;
    }

    #modal-sns-share-wrapper .modal_header img {
        position: absolute;
        top: 50%;
        right: 7px;
        transform: translate(-50%, -50%);
    }

    #modal-sns-share-wrapper .modal_body {
        display: flex;
        justify-content: space-around;
        margin: auto 0;

    }
    .btn.btn-cart-action {
        width: 110px;
        height: 44px;
        border: solid 1px #333333;
        border-radius: 2px;
        background-color: #333333;
        font-size: 16px;
        font-weight: 400;
        text-align: center;
        color: #ffffff;
    }
   





    @media (max-width: 1366px) {
        #modal-instock-noti-content>table th {
            vertical-align: top;
            font-size: 16px;
            color: #333333;
            width: auto;
            font-weight: 500;
        }

        #modal-instock-noti-content>table td {
            padding-bottom: 20px;
            font-size: 14px;
            color: #424242;
        }

        #modal-instock-noti-content {
            padding: 14px;
            padding-bottom: 20px;
        }

        .instock-noti-brand {
            font-size: inherit;
            color: inherit;
        }

        .instock-noti-item {
            font-size: inherit;
            color: inherit;
        }

        .instock-noti-phone {
            font-size: inherit;
            color: inherit;
        }

        #modal-instock-noti-button {
            padding-bottom: 0;
        }

        #modal-instock-noti-button>button {
            width: calc(50% - 23px);
            font-size: 14px;
        }

        #modal-instock-noti-desc {
            border-top: unset;
            padding: 16px;
            padding-bottom: 20px;
            font-size: 10px;
            font-weight: 300;
            line-height: 1.8;
            color: #b5b5b5;
        }

        #modal-instock-noti-desc>div>span {
            display: inline-block;
            width: 3px;
            height: 3px;
            border-radius: 3px;
            border: 1px solid #acacac;
            margin-right: 4px;
            vertical-align: middle;
        }

        #modal_product-detail-qna .modal-content {
            width: 100%;
            height: 530px;
            margin: 0;
            padding: 0;
        }

        #modal_product-detail-qna .modal-dialog {
            padding-left: 0px;
            position: fixed;
            bottom: 0;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        #modal_product-detail-qna .modal-content {
            border-radius: 24px 24px 0 0;
        }

        #modal_product-detail-qna .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        #modal_product-detail-qna .modal_header img {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translate(-50%, -50%);
        }

        #modal_product-detail-qna .modal-header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        #modal_notice_popup .modal-content {
            width: 100%;
            height: 530px;
            margin: 0;
            padding: 0;
        }

        #modal_notice_popup .modal-dialog {
            padding-left: 0px;
            position: fixed;
            bottom: 0;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        #modal_notice_popup .modal-content {
            border-radius: 24px 24px 0 0;
        }

        #modal_notice_popup .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        #modal_notice_popup .modal_header img {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translate(-50%, -50%);
        }

        #modal_notice_popup .modal-header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        #modal-sns-share-wrapper .modal_header {
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #090909;
            position: relative;
            border-bottom: 1px solid #e0e0e0;
        }

        #modal-sns-share-wrapper .modal_header img {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translate(-50%, -50%);
        }

        #modal-sns-share-wrapper .modal_body {
            margin-top: 20px;
            padding: 0 36px;
        }

        #modal-sns-share-wrapper {
            height: 100vh;
        }

        #modal-sns-share-wrapper .modal-dialog {
            width: 100%;
            margin: 0 !important;
            padding: 0;
        }

        #modal-sns-share-content-wrapper {
            width: 100%;
            border-radius: 20px 20px 0 0;
            margin: 0;
            padding: 0 !important;
            position: fixed;
            bottom: 0px;
        }

        #modal_product-detail-qna .modal-body {
            max-height: calc(100vh -150px);
            overflow-x: scroll;
        }

        #modal_product-detail-qna .qatextArea {
            width: 100%;
        }

        #modal_product-detail-qna .headText {
            width: 45px;
            height: 18px;
            font-size: 12px;
            font-weight: 500;
            line-height: normal;
            color: #424242;
        }

        #modal_product-detail-qna .required.frm_input {
            width: 100%;
            height: 44px;
            margin: 16px 0 8px 0;
        }

        #modal_product-detail-qna #qna_it_name {
            font-size: 12px;
            font-weight: normal;
            line-height: normal;
            color: #3a3a3a;
            padding-left: 59px;
        }

        #modal_product-detail-qna .qna-type {
            line-height: 18px;
            width: 100%;
        }

        #modal_product-detail-qna #product-detail-qna-type {
            width: 100%;
            height: 44px;
            margin: 8px 0 0 0;
        }

        #modal_product-detail-qna .headText.qna-type {
            line-height: 18px;
        }

        #modal_product-detail-qna .qna_title {
            margin-top: 0px;
        }

        #modal_product-detail-qna .win_btn {
            width: 100%;
            margin: 0;
        }

        #modal_product-detail-qna .win_btn input {
            width: calc(50% - 7px);
            height: 44px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }

        #modal_notice_popup .modal-body {
            max-height: calc(100vh -150px);
            overflow-x: scroll;
        }

        #modal_notice_popup .qatextArea {
            width: 100%;
        }

        #modal_notice_popup .headText {
            width: 45px;
            height: 18px;
            font-size: 12px;
            font-weight: 500;
            line-height: normal;
            color: #424242;
        }

        #modal_notice_popup .required.frm_input {
            width: 100%;
            height: 44px;
            margin: 16px 0 8px 0;
        }

        #modal_notice_popup #qna_it_name {
            font-size: 12px;
            font-weight: normal;
            line-height: normal;
            color: #3a3a3a;
            padding-left: 59px;
        }

        #modal_notice_popup .qna-type {
            line-height: 18px;
            width: 100%;
        }

        #modal_notice_popup #product-detail-qna-type {
            width: 100%;
            height: 44px;
            margin: 8px 0 0 0;
        }

        #modal_notice_popup .headText.qna-type {
            line-height: 18px;
        }

        #modal_notice_popup .qna_title {
            margin-top: 0px;
        }

        #modal_notice_popup .win_btn {
            width: 100%;
            margin: 0;
        }

        #modal_notice_popup .win_btn input {
            width: calc(50% - 7px);
            height: 44px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }

        .btn.btn-cart-action {
            width: 100% !important;
            height: 44px !important;
            border-radius: 2px;
            border: solid 1px #333333;
            background-color: #333333;
            font-size: 14px !important;
            font-weight: 500;
            text-align: center;
         
            color: #ffffff;
        }



    }
</style>

<script>
    function copyurl(sns_url) {

        $('#clip_tmp').val(sns_url);
        $('#clip_tmp').select();
        var successful = document.execCommand('copy');
        if (successful) {
            alert('url 주소가 복사되었습니다.');
        } else {
            alert('url 주소가 복사되지 않았습니다.');
        }
    }

    $("#btn-sns-join-agree").on("click", function() {
        let agree = true;
        $("#modal-join-agree input[type=checkbox]").each(function(ai, ae) {
            if ($(ae).prop("required") && $(ae).prop("checked") === false) agree = false;
        });

        if (agree) {
            const sns = $("#modal-join-agree").data("sns");
            const url = "<?= $sns_login_uri ?>?provider=" + sns + "&amp;url=<?= $_SERVER['PHP_SELF'] ?>"
            const winSnsLogin = window.open(url, '_blank', 'width=800px,height:800px');
        } else {
            alert("가입을 위해 개인정보 제공 동의가 필요합니다.");
        }
    });

    $(".btn-sns-join").on("click", function() {
        let sns = $(this).data('sns');

        $("#modal-join").modal("hide");
        $("#modal-join").on("hidden.bs.modal", function(e) {
            if (typeof sns !== 'undefined') {
                $("#modal-join-agree").data("sns", sns).modal("show");
                sns = undefined;
            }
        });
    });

    $("#btn-join-coupon-agree").on("click", function() {
        $("#modal-join-coupon").modal("hide");
    });

    function openRule(type, device) {
        if (device == 'PC' || device == '') {
            $.get("/auth/ajax.rule.php?type=" + type, function(response) {
                $("#modal-popup-rule-content").html(response);
            });
            $("#modal-popup-rule-wrapper").modal("show");
        } else {
            $.get("/auth/ajax.rule.php?type=" + type, function(response) {
                $("#modal-popup-rule-content-mo").html(response);
            });
            $("#modal-popup-rule-wrapper-mo").modal("show");
        }
    }
    function daily24(e) {
        set_cookie(e, 1, 24, g5_cookie_domain);
    }
    function openNoticePopup() {
        if (document.body.clientWidth < 800) {
            $("#modal-alert-wrapper-mobile").modal("show");
        } else {
            $("#modal_notice_popup").modal('show');
        }
    }

    function openPopup(data, type) {
        if (!type) type = "alert";
        const popupContent = data.content;
        let closeText = "";
        let closeAction = "";
        let confirmText = "";
        let confirmAction = "";
        let popupButton = "";
        switch (type) {
            case "confirm":
                closeText = data.close && data.close.text ? data.close.text : "취소";
                closeAction = data.close && data.close.action ? data.close.action : "closePopup()";
                confirmText = data.confirm && data.confirm.text ? data.confirm.text : "확인";
                confirmAction = data.confirm && data.confirm.action ? data.confirm.action : "closePopup()";
                popupButton += "<button type='button' class='btn btn-half' onclick=" + closeAction + ">" + closeText + "</button>";
                popupButton += "<button type='button' class='btn btn-half btn-confirm' onclick=" + confirmAction + ">" + confirmText + "</button>";
                break;
            default:
                closeText = data.close && data.close.text ? data.close.text : "취소";
                closeAction = data.close && data.close.action ? data.close.action : "closePopup()";
                popupButton += "<button type='button' class='btn' onclick=" + closeAction + ">" + closeText + "</button>";
                break;
        }

        $("#modal-alert-content").html(popupContent);
        $("#modal-alert-button").html(popupButton);

        return $("#modal-alert-wrapper").modal("show");
    }

    function closePopup() {
        return $("#modal-alert-wrapper").modal("hide");
    }

    function openSnsPopup(type) {
        $('#mobile-options-wrapper').css('display', 'none');
        $('.btn-order-mobile-group').css('display', 'none');
        return $("#modal-sns-share-wrapper").modal("show");
    }
    $("#modal-sns-share-wrapper").on('hide.bs.modal', function(e) {
        $('#mobile-options-wrapper').css('display', 'block');
        $('.btn-order-mobile-group').css('display', 'block');
    });

    function closeSnsPopup() {
        return $("#modal-sns-share-wrapper").modal("hide");
    }

    function openmodal(mb_id, type) {
        $('#btn-order-mobile').css('display', 'none');
        //현재 주소를 가져온다.
        var renewURL = location.href;
        //현재 주소 중 page 부분이 있다면 날려버린다.
        renewURL = renewURL.replace(/\?w=.*/ig, '');
        renewURL = renewURL.replace(/\&w=/ig, '');
        renewURL = renewURL.replace(/\&iq_id=/ig, '');
        //새로 부여될 페이지 번호를 할당한다.
        // ajax에서 넘기는  변수로 할당해주거나 할당된 변수로 변경
        renewURL += type;
        //페이지 갱신 실행!
        history.pushState(null, null, renewURL);
        var isEmpty = mb_id;
        if (isEmpty) {
            $("#modal_product-detail-qna").modal('show');
        } else {
            alert("상품문의는 회원만 작성 가능합니다.");
            window.location = '/auth/login.php';

        }
    }
    $("#modal_product-detail-qna").on('hide.bs.modal', function(e) {
        $('#btn-order-mobile').css('display', 'block');
    });

    function qaUpdate(iq_id) {

        var error = "";
        $.get('ajax.member.qna.list.php?w=u&iq_id=' + iq_id, function(data) {
            const $data = JSON.parse(data);
            const $modal = $("#modal_product-detail-qna");

            if ($data.error) {
                alert("수정이 불가합니다. 고객센터의 문의하세요.");
                return false;
            }

            $($modal).find("input[name=w]").val('u');
            $($modal).find("input[name=it_id]").val($data.it_id);
            $($modal).find("input[name=iq_id]").val($data.iq_id);
            $($modal).find("input[name=iq_subject]").val($data.iq_subject);
            $($modal).find("#qna_it_name").html($data.it_name);
            $($modal).find("select[name=iq_category]").val($data.iq_category);
            $($modal).find("textarea[name=iq_question]").val($data.iq_question);

            $("#modal_product-detail-qna").modal('show');
        });
    }

    function qaDelete(iq_id) {

        var result = confirm('상품문의를 삭제 하시겠습니까?');
        if (result) {
            $.get('ajax.member.qna.list.php?w=d&iq_id=' + iq_id, function(data) {
                const $data = JSON.parse(data);
                if ($data) {
                    alert("상품문의가 삭제되었습니다.");
                    location.reload();
                }
            });
        } else {

        }
    }

    function openmodal_moblie(mb_id, type) {
        //현재 주소를 가져온다.
        var renewURL = location.href;
        //현재 주소 중 page 부분이 있다면 날려버린다.
        renewURL = renewURL.replace(/\?w=.*/ig, '');
        renewURL = renewURL.replace(/\&w=/ig, '');
        renewURL = renewURL.replace(/\&iq_id=/ig, '');
        //새로 부여될 페이지 번호를 할당한다.
        // ajax에서 넘기는  변수로 할당해주거나 할당된 변수로 변경
        renewURL += type;
        //페이지 갱신 실행!
        history.pushState(null, null, renewURL);

        var isEmpty = mb_id;
        if (isEmpty) {
            $('#modal_product-detail-qna_mo').modal('show');
            //location.href = "<?= G5_MSHOP_URL; ?>/itemqaform.php" + type;
        } else {
            alert("상품문의는 회원만 작성 가능합니다.");
        }
    }

    function filter_modal() {
        $('#nav-bottom-small').css('display', 'none');
        $("#modal-filter").modal('show');
    }

    function notiInStock() {
        const itemId = $(".instock-noti-item").first().data("item");

        if (itemId) {
            const data = {
                item: itemId,
                phone: $(".instock-noti-phone").first().text()
            };
            $.post("/shop/ajax.itemstocksmsupdate.php", data, function(response) {

                if (response.result == true) {
                    return alert("재입고 알림이 등록되었습니다.");
                } else {
                    let msg = "ERROR";
                    switch (response.error) {
                        case "EMPTY_PARAMS":
                            msg = "잘못된 요청입니다.";
                            break;
                        case "NOT_FOUND_ITEM":
                            msg = "상품정보를 찾을 수 없습니다.";
                            break;
                        case "IMPOSSIBLE_ITEM":
                            msg = "재입고 알림 설정이 불가능한 상품입니다.";
                            break;
                        case "ALREADY_EXISTS":
                            msg = "이미 재입고 알림이 설정된 상품입니다. ";
                            break;
                    }

                    return alert(msg);
                }
            }, "JSON");

            return false;
        }

        alert("잘못된 요청입니다.");
        return false;
    }

    let intervalLoading;
    let currentImage = 0;

    function toggleLoading() {
        const delay = 500;
        const isShown = $("#modal-loading").hasClass("show");
        if (isShown) {
            $("#modal-loading").modal("hide");
            clearInterval(intervalLoading);
        } else {
            $("#modal-loading").modal({
                backdrop: 'static'
            }).modal("show");
            intervalLoading = setInterval(function() {
                $(".img-loading").fadeOut(delay, function() {
                    $(".img-loading").removeClass("state-" + currentImage);
                    currentImage++;
                    if (currentImage > 2) currentImage = 0;
                    $(".img-loading").addClass("state-" + currentImage).show();
                });
            }, delay);
        }
    }

    $("#modal-loading").on("hide.bs.modal", function() {
        return clearInterval(intervalLoading);
    });

    $("#modal-filter").on('click', function(e) {
        if ($("#modal-filter").css("display") == "none") {
            $('#nav-bottom-small').css('display', 'flex');
        }
    });

    $(".btn-toggle-instock").on("click", function() {
        const mb_id = $(this).data("mb_id");
        if (mb_id == '' || mb_id == null) {
            alert("로그인 후 이용 가능합니다.");
            window.location = "/auth/login.php?r=1";
            return;
        }
        const it_id = $(this).data("item");
        const it_brand = $(this).data("brand");
        const it_name = $(this).data("name");

        $(".instock-noti-brand").text(it_brand);
        $(".instock-noti-item").data("item", it_id).text(it_name);

        if ($(this).data("screen") == "mobile") {
            $("#modal-instock-noti-mobile").modal("show");
        } else {
            $("#modal-instock-noti").modal("show");
        }
        return false
    });

    $("#btn-toggle-benefit").on("click", function() {
        $("#modal-order-benefit").modal("show");
    });

    $("#btn-toggle-benefit-mobile").on("click", function() {
        $("#modal-order-benefit-mobile").modal("show");
    });

</script>