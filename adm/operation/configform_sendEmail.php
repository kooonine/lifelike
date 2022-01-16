<?php
$sub_menu = "200110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = 'Email 발송';
include_once('../admin.head.php');


if (!isset($email_reciver_type)) $email_reciver_type = 'customer';
if (!isset($mb_mailling)) $mb_mailling = 1;


$sql  = " select  * from lt_mail where ma_name = '게시판-온라인집들이 게시판 통보' ";
$ma = sql_fetch($sql);
$content = $ma['ma_content'];

?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
                <input type="hidden" name="token" value="" id="token">
                <input type="hidden" name="testFlag" value="<?= (int) $test ?>" id="testFlag">
                <input type="hidden" name="receiverlist" value="" id="receiverlist">
                <input type="hidden" name="receiverlistcount" value="" id="receiverlistcount">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span> 메일발송<small></small></h4>
                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tr>
                            <th scope="row">발신인</th>
                            <td><input type="text" name="sender" value="<?php echo $config['cf_title'] ?>" id="sender" class="form-control"></td>
                            <th scope="row">발신인 이메일 주소</th>
                            <td><input type="text" name="email" value="<?php echo $member['mb_email'] ?>" id="email" class="form-control"></td>
                        </tr>
                        <tr>
                            <th class="text-center" colspan="4" style="text-align:center;">수신인 정보</th>
                        </tr>

                        <tr>
                            <th scope="row" rowspan="3">회원 ID</th>
                            <td>
                                <label><input type="radio" name="email_reciver_type" value="all" <?php echo get_checked($email_reciver_type, "all") ?>> 전체</label>&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" name="email_reciver_type" value="customer" <?php echo get_checked($email_reciver_type, "customer") ?> /> 고객</label>
                            </td>
                            <th scope="row"><label for="mb_mailling">메일링</label></th>
                            <td>
                                <select name="rejectType" id="rejectType">
                                    <option value="2">수신동의한 회원만
                                    <option value="3">전체
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">

                                <label for="sfl" class="sound_only">검색대상</label>
                                <select name="sfl" id="sfl">
                                    <option value="mb_id" <?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
                                    <option value="mb_nick" <?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
                                    <option value="mb_name" <?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
                                    <option value="mb_email" <?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
                                    <option value="mb_tel" <?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>연락처</option>
                                    <option value="mb_hp" <?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대전화번호</option>
                                </select>
                                <label for="stx" class="sound_only">검색어<strong class="sound_only"> 개별고객 대상시 필수</strong></label>
                                <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
                                <input type="button" class="btn btn-default" value="검색" id="btnSearch">

                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="tbl_frm01 tbl_wrap">
                                    <table>
                                        <tr>
                                            <th style="text-align:center;">검색대상</th>
                                            <th style="text-align:center;">발송대상</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="col-md-6 col-sm-12 text-left">
                                                    <h4>회원 목록</h4>
                                                </div>
                                                <div class="col-md-6 col-sm-12 text-right">
                                                    <button type="button" class="btn btn-default" onclick="hp_list_add()">선택추가</button>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <select name="mb_list" id="mb_list" class="select2_multiple form-control" multiple="multiple" size="5"></select>
                                                </div>
                                            </td>
                                            <td style="text-align:center;">

                                                <div class="col-md-6 col-sm-12 text-left">

                                                </div>
                                                <div class="col-md-6 col-sm-12 text-right">
                                                    <button type="button" class="btn btn-default" onclick="hp_list_del()">선택삭제</button>
                                                </div>

                                                <div class="col-md-12 col-sm-12">
                                                    <select name="hp_list" id="hp_list" class="select2_multiple form-control" multiple="multiple" size="5">
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">메일제목</th>
                            <td colspan="3">
                                <input type="text" name="subject" value="(광고) " id="subject" class="form-control">
                                <label class="red">* 모든 내용의 메일을 발송하실 때 제목 앞에 ‘(광고) ‘를 반드시 포함하셔야 합니다.</label>
                                <label class="red">* 메일 제목 앞에 ‘(광고)’를 포함하지 않을 경우 개정된 정보통신망 이용 촉진 및 정보보호 등에 관한 법률(’14년 5월 28일 일부개정, ’14년 11월 29일 시행, 법률 제12681호)에 의해 3천만원 이하의 과태료가 부과될 수 있습니다.</label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">발송시간</th>
                            <td colspan="3">
                                <input type="radio" name="sendType" value="0" id="sendType0" checked="checked"> <label for="sendType0">일반발송</label>
                                <input type="radio" name="sendType" value="1" id="sendType1"> <label for="sendType1">예약발송</label>

                                <div class='input-group date hidden' id='senddatepicker'>
                                    <input type='text' class="form-control" id="sendDate" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <?php echo editor_html('content', get_text($content, 0)); ?>
                            </td>
                        </tr>

                    </table>


                    <div class="ln_solid"></div>
                </div>


                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="button" class="btn btn-promary" id="cancel" value="취소"></input>
                            <input type="submit" class="btn btn-success" value="발송"></input>

                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(function() {

        $('#senddatepicker').datetimepicker({
            ignoreReadonly: true,
            allowInputToggle: true,
            format: 'YYYY-MM-DD HH:mm',
            locale: 'ko'
        });

        $('input[name="sendType"]').click(function() {

            if ($(this).val() == '1') {
                $('#senddatepicker').removeClass("hidden");
            } else {
                $('#senddatepicker').removeClass("hidden").addClass("hidden");
            }
        });
        $("#stx").keyup(function(e) {
            if (e.keyCode == 13) $("#btnSearch").click();
        });

        $("#btnSearch").click(function() {
            if ($("#stx").val() == "") {
                alert("검색어를 입력하세요.");
                $("#stx").focus();
                return;
            }

            $targetSel = $("#mb_list");

            $.post(
                "ajax.configform_sms_sender_search.php", {
                    stx: $("#stx").val(),
                    sfl: $("#sfl").val()
                },
                function(data) {
                    //alert(data);

                    var responseJSON = JSON.parse(data);
                    var count = responseJSON.length;
                    $targetSel.empty();

                    if (count == 0) {
                        $targetSel.append($('<option>', {
                            value: "",
                            text: "검색 결과가 없습니다."
                        }));
                        return;
                    }

                    for (i = 0; i < count; i++) {
                        $option = $('<option>', {
                            value: responseJSON[i]['mb_email'],
                            text: responseJSON[i]['mb_name'] + "(" + responseJSON[i]['mb_email'] + ")"
                        })

                        $option.attr("mb_name", responseJSON[i]['mb_name']);
                        $option.attr("data", responseJSON[i]['mb_id']);
                        $targetSel.append($option);
                    }


                }
            );
        });

    });


    function hp_list_add() {
        $mb_list = $('#mb_list :selected');

        if ($mb_list.length < 0) {
            alert('추가할 목록을 선택해주세요.');
            return;
        }
        $hp_list = $('#hp_list option');

        $add = true;
        $mb_list.each(function(i, sel) {
            //sms_obj.person_add($(sel).attr("data"), $(sel).attr("mb_name"), $(sel).val());
            //alert($(sel).attr("data")+","+$(sel).text()+","+$(sel).val());
            if ($add) {
                $hp_list.each(function(j, hpsel) {
                    if ($(sel).attr("data") == $(hpsel).attr("data")) {
                        alert('이미 같은 목록이 있습니다.');
                        $add = false;
                        return;
                    }
                });
                if ($add) {
                    $option = $('<option>', {
                        value: $(sel).val(),
                        text: $(sel).text()
                    })
                    $option.attr("mb_name", $(sel).attr("mb_name"));
                    $option.attr("data", $(sel).attr("data"));
                    $('#hp_list').append($option);
                }
            }
        });
    }

    function hp_list_add_data(mb_id, mb_name, mb_email) {
        $hp_list = $('#hp_list option');

        $add = true;
        $hp_list.each(function(j, hpsel) {
            if (mb_id == $(hpsel).attr("data")) {
                alert('이미 같은 목록이 있습니다.');
                $add = false;
                return;
            }
        });

        if ($add) {
            $option = $('<option>', {
                value: mb_email,
                text: mb_name + "(" + mb_email + ")"
            })
            $option.attr("mb_name", mb_name);
            $option.attr("data", mb_id);
            $('#hp_list').append($option);
        }
    }

    function hp_list_del() {
        $hp_list = $('#hp_list :selected');

        if ($hp_list.length < 0) {
            alert('삭제할 목록을 선택해주세요.');
            return;
        }

        $hp_list.remove();
    }

    function fconfigform_submit(f) {
        var receiverlist = "";
        var receiverlist2 = "";

        if ($("#email_reciver_type").val() != "all") {
            $hp_list = $('#hp_list option');

            if ($hp_list.length < 0) {
                alert('추가할 목록을 선택해주세요.');
                return;
            }
            var j = 0;
            $hp_list.each(function(i, sel) {
                if (receiverlist != "") {
                    receiverlist += "\n";
                    receiverlist2 += ",";
                }
                //이름,메일주소,핸드폰번호,기타1,기타2,,,,,기타10
                receiverlist += $(sel).attr("mb_name") + "," + $(sel).val();
                receiverlist2 += $(sel).val();
                j++;
            });

            if (j < 10) {
                $("#receiverlist").val(receiverlist2);
            } else {
                $("#receiverlist").val(receiverlist);
            }
            $("#receiverlistcount").val(j);
        }

        f.action = "./configform_sendEmail_send.php";
        return true;
    }



    <?php
    if ($_GET['act_button'] == "EMAIL" && isset($_GET['mb_id'])) {

        $mb = get_member($_GET['mb_id']);

        if ($mb['mb_id'] && $mb['mb_email']) {
            echo 'hp_list_add_data("' . $mb['mb_id'] . '", "' . $mb['mb_name'] . '", "' . $mb['mb_email'] . '");' . PHP_EOL;
        }
    } else if ($_POST['act_button'] == "EMAIL") {
        for ($i = 0; $i < count($_POST['chk']); $i++) {
            // 실제 번호를 넘김
            $k = $_POST['chk'][$i];
            $mb = get_member($_POST['mb_id'][$k]);
            if (!$mb['mb_id']) {
                continue;
            }

            if (($mb['mb_email'])) {
                echo 'hp_list_add_data("' . $mb['mb_id'] . '", "' . $mb['mb_name'] . '", "' . $mb['mb_email'] . '");' . PHP_EOL;
            }
        }
    }
    ?>
</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once('../admin.tail.php');
?>