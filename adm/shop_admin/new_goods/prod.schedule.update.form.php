<?

$sub_menu = "930200";
include_once('./_common.php');
include_once('../../admin.head.php');
include_once(G5_LAYOUT_PATH . "/modal.php");


auth_check($auth[substr($sub_menu,0,2)], 'w');
$g5['title'] = '리오더 등록';
$ps_id = $_GET['ps_id'];

if (!($w == '' || $w == 'u' || $w == 'r')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == '') {
    $title_msg = '작성';
    if ($cp_id) {
        alert('글쓰기에는 \$cp_id 값을 사용하지 않습니다.');
    }
} else if ($w == 'u') {
    $title_msg = '수정';
    $sql = " select * from lt_prod_schedule where ps_id = '$ps_id' ";
    $ps = sql_fetch($sql);
    if (!$ps['ps_id']) alert("등록된 자료가 없습니다.");

}

// 그룹접근 가능
if (!empty($group['gr_use_access'])) {
    if ($is_guest) {
        alert("접근 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.", 'login.php?' . $qstr . '&amp;url=' . urlencode($_SERVER['SCRIPT_NAME'] . '?bo_table=' . $bo_table));
    }

    if ($is_admin == 'super' || $group['gr_admin'] === $member['mb_id'] || $board['bo_admin'] === $member['mb_id']) {; // 통과
    } else {
        // 그룹접근
        $sql = " select gr_id from {$g5['group_member_table']} where gr_id = '{$board['gr_id']}' and mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['gr_id'])
            alert('접근 권한이 없으므로 글쓰기가 불가합니다.\\n\\n궁금하신 사항은 관리자에게 문의 바랍니다.');
    }
}

$ps_sizes = array();
if (!empty($ps['ps_size'])) {
    $ps_sizes = json_decode($ps['ps_size'], true);
}


$g5['title'] = $title_msg;

$is_html = true;
$is_category = false;
$is_link = true;
$is_file = true;
$is_file_content = true;

$html_checked   = "";
$html_value     = "";
$subject = "";

if (isset($cp['cp_subject'])) {
    $subject = str_replace("\"", "&#034;", get_text(cut_str($cp['cp_subject'], 255), 0));
}


$g5['title'] = "생산일정 " . $title_msg;


$action_url = https_url('adm') . "/shop_admin/new_goods/prod.schedule.update.php";
$brands = array(
    '소프라움' => '소프라움',
    '쉐르단' => '쉐르단',
    '랄프로렌홈' => '랄프로렌홈',
    '베온트레' => '베온트레',
    '링스티드던' => '링스티드던',
    '로자리아' => '로자리아',
    '그라치아노' => '그라치아노',
    '시뇨리아' => '시뇨리아',
    '플랫폼일반' => '플랫폼일반',
    '플랫폼렌탈' => '플랫폼렌탈',
    '온라인' => '온라인',
    '템퍼' => '템퍼'
);
$prod_gubun = array(
    '' => '선택',
    'MW' => 'MW',
    'MA' => 'MA',
    'MD' => 'MD',
    'MS' => 'MS',
    'MX' => 'MX'
);
$nabgis = array(
    '' => '선택',
    '1월' => '1월',
    '2월' => '2월',
    '3월' => '3월',
    '4월' => '4월',
    '5월' => '5월',
    '6월' => '6월',
    '7월' => '7월',
    '8월' => '8월',
    '9월' => '9월',
    '10월' => '10월',
    '11월' => '11월',
    '12월' => '12월'
    
);
$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');

$img_size_pc = array(
    'GNB_TOP' => '1920*90px',
    'GNB_IN' => '',
    'MAIN' => '1920*580px',
    'BADING' => '1420*200px',
    'GOOS' => '460*460px',
    'THEME' => 'THEME',
    'MD' => '460*460px',
    'BRAND' => '1420*450px',
    'BEST' => 'BEST',
    'NEW' => 'NEW',
    'HOT' => 'HOT',
    'SEASON' => 'SEASON',
    'EVENT' => 'EVENT',
);

$img_size_mo = array(
    'GNB_TOP' => '900*270px',
    'GNB_IN' => 'GNB 내부',
    'MAIN' => '1080*1200px',
    'BADING' => '1080*1080px',
    'GOOS' => '1080*1080px',
    'THEME' => 'THEME',
    'MD' => '1080*1080px',
    'BRAND' => '1080*840px',
    'BEST' => 'BEST',
    'NEW' => 'NEW',
    'HOT' => 'HOT',
    'SEASON' => 'SEASON',
    'EVENT' => 'EVENT',
);


?>

<!-- @START@ 내용부분 시작 -->

<style>
    button.btn-add {
        visibility: hidden;
    }

    button.btn-add.first {
        visibility: visible;
    }
    #ps_size_table_style td {border-top: 0px;    border-bottom: 0px;    padding: 10px 0;}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="uid" value="<?= get_uniqid(); ?>">
                <input type="hidden" name="w" value="<?= $w ?>">
                <input type="hidden" name="ps_id" value="<?= $ps['ps_id'] ?>">
                <input type="hidden" name="token" value="<?= get_admin_token() ?>">

                <div class="x_title">
                    <h4><span class="fa fa-check-square"></span> 생산일정 등록<small></small></h4>
                        

                    <label class="nav navbar-right"></label>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table id="compaign-content-wrapper" class="ng_table">
                            <caption>기본정보
                            </caption>
                            <colgroup>
                                <col width="10%" class="">
                                <col width="40%">
                                <col width="10%" class="">
                                <col width="40%" >
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>구분</th>
                                    <td>
                                        <input name="ps_gubun" id ="ps_gubun" value="<?=$ps['ps_gubun']?>">
                                    </td>
                                    <th>목표납기(제품기획)*</th>
                                    <td>
                                        <select name="ps_limit_date" required id ="ps_limit_date">
                                            <? foreach ($nabgis as $ng => $nabgi) : ?>
                                                <option value="<?= $ng ?>" <?= $ps['ps_limit_date'] == $ng ? "selected" : "" ?>><?= $nabgi ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>SO (OK)</th>
                                    <td>
                                       <input name="ps_os" id ="ps_os" value="<?=$ps['ps_os']?>">
                                    </td>
                                    <th>브랜드</th>
                                    <td>
                                        <select name="ps_brand" id ="ps_brand">
                                            <? foreach ($brands as $ck => $brand) : ?>
                                                <option value="<?= $ck ?>" <?= $ps['ps_brand'] == $ck ? "selected" : "" ?>><?= $brand ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>생산구분*</th>
                                    <td>
                                        <select name="ps_prod_gubun" required id ="ps_prod_gubun">
                                            <? foreach ($prod_gubun as $gb => $prodgubun) : ?>
                                                <option value="<?= $gb ?>" <?= $ps['ps_prod_gubun'] == $gb ? "selected" : "" ?>><?= $prodgubun ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <th>상품명</th>
                                    <td>
                                        <input name="ps_it_name" id ="ps_it_name" value="<?=$ps['ps_it_name']?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>품목(아이템)*</th>
                                    <td>
                                        <input name="ps_prod_name" id ="ps_prod_name" required value="<?=$ps['ps_prod_name']?>">
                                    </td>
                                    <th>사이즈</th>
                                    <td>
                                        <table id="ps_size_table_style">
                                        <?
                                        $jo_sql ="select * from lt_job_order where ps_id ={$ps['ps_id']} ORDER BY jo_size_code ASC ";

                                        $jo_result= sql_query($jo_sql);
                                        $jo_data = sql_fetch($jo_sql);
                                        
                                        for ($jo = 0; $jo_row = sql_fetch_array($jo_result); $jo++) {
                                        $ps_size_qty = '';
                                        ?>
                                            <tr>
                                                <td><input name="ps_size[<?=$jo?>]" id ="ps_size_<?=$jo?>" value="<?=$jo_row['jo_size_code']?>"></td>
                                                <?if (!empty($ps_sizes)) :?>
                                                <?php foreach ($ps_sizes as $si => $ps_size) : ?>
                                                    <? if($jo_row['jo_size_code'] == $ps_size['size']){
                                                        $ps_size_qty = $ps_size['qty'];
                                                    }   
                                                    ?>
                                                <?php endforeach ?>
                                                
                                                <?endif?>
                                                <td><input name="ps_size_qty[<?=$jo?>]" id ="ps_size_qty_<?=$jo?>" value="<?=$ps_size_qty ?  $ps_size_qty : ''?>" placeholder="수량"></td>
                                            </tr>
                                        <?}?>

                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>코드</th>
                                    <td>
                                        <input name="ps_code" id ="ps_code" value="<?=$ps['ps_code']?>">
                                    </td>
                                    <th>업체명</th>
                                    <td>
                                        <?
                                        $ps_company_name = array();
                                        if (!empty($ps['ps_company_name'])) {
                                            $ps_company_name = json_decode($ps['ps_company_name'], true);
                                        }
                                        ?>
                                        <div>
                                            <input type="hidden" name = "ps_company_name_no[1]" value = '1'>
                                            <select class="" name="ps_company_name_bongje[1]">
                                                <option value = "" <?= $ps_company_name[1]['bongje'] == '' ? "selected" : "" ?>>선택</option>
                                                <option value = "가공비_봉제" <?= $ps_company_name[1]['bongje'] == '가공비_봉제' ? "selected" : "" ?>>가공비_봉제</option>
                                                <option value = "가공비_퀼팅" <?= $ps_company_name[1]['bongje'] == '가공비_퀼팅' ? "selected" : "" ?>>가공비_퀼팅</option>
                                                <option value = "가공비_원헤드" <?= $ps_company_name[1]['bongje'] == '가공비_원헤드' ? "selected" : "" ?>>가공비_원헤드</option>
                                            </select>    
                                            <input name="ps_company_name_nm[1]" id ="ps_company_name_nm_1" value="<?=$ps_company_name[1]['name']?>">
                                        </div>
                                        <div>
                                            <input type="hidden" name = "ps_company_name_no[2]" value = '2'>
                                            <select class="" name="ps_company_name_bongje[2]">
                                                <option value = "" <?= $ps_company_name[2]['bongje'] == '' ? "selected" : "" ?>>선택</option>
                                                <option value = "가공비_봉제" <?= $ps_company_name[2]['bongje'] == '가공비_봉제' ? "selected" : "" ?>>가공비_봉제</option>
                                                <option value = "가공비_퀼팅" <?= $ps_company_name[2]['bongje'] == '가공비_퀼팅' ? "selected" : "" ?>>가공비_퀼팅</option>
                                                <option value = "가공비_원헤드" <?= $ps_company_name[2]['bongje'] == '가공비_원헤드' ? "selected" : "" ?>>가공비_원헤드</option>
                                            </select>    
                                            <input name="ps_company_name_nm[2]" id ="ps_company_name_nm_2" value="<?=$ps_company_name[2]['name']?>">
                                        </div>
                                        <div>
                                            <input type="hidden" name = "ps_company_name_no[3]" value = '3'>
                                            <select class="" name="ps_company_name_bongje[3]">
                                                <option value = "" <?= $ps_company_name[3]['bongje'] == '' ? "selected" : "" ?>>선택</option>
                                                <option value = "가공비_봉제" <?= $ps_company_name[3]['bongje'] == '가공비_봉제' ? "selected" : "" ?>>가공비_봉제</option>
                                                <option value = "가공비_퀼팅" <?= $ps_company_name[3]['bongje'] == '가공비_퀼팅' ? "selected" : "" ?>>가공비_퀼팅</option>
                                                <option value = "가공비_원헤드" <?= $ps_company_name[3]['bongje'] == '가공비_원헤드' ? "selected" : "" ?>>가공비_원헤드</option>
                                            </select>    
                                            <input name="ps_company_name_nm[3]" id ="ps_company_name_nm_3" value="<?=$ps_company_name[3]['name']?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>결재승인일자*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_approval_date" value="<?php echo $ps['ps_approval_date']; ?>"  id="ps_approval_date" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar" onclick="ps_approval_date_DatePicker()" style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>원단생산업체</th>
                                    <td>
                                        <input name="ps_prod_company" id ="ps_prod_company" value="<?=$ps['ps_prod_company']?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>원단발주</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_balju" value="<?php echo $ps['ps_balju']; ?>" id="ps_balju"  class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>원단납기예정</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_expected_limit_date" value="<?php echo $ps['ps_expected_limit_date']; ?>"  id="ps_expected_limit_date"  class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>원단검품 (시험성적)</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_gumpum" value="<?php echo $ps['ps_gumpum']; ?>"  id="ps_gumpum"  class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>생산발주*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_prod_balju" value="<?php echo $ps['ps_prod_balju']; ?>"  id="ps_prod_balju" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>샘플예정일*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_sample_date" value="<?php echo $ps['ps_sample_date']; ?>"  id="ps_sample_date" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>출시예정일*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_ipgo_date" value="<?php echo $ps['ps_ipgo_date']; ?>"  id="ps_ipgo_date" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>제품기획승인일자*</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_prod_proprosal_date" value="<?php echo $ps['ps_prod_proprosal_date']; ?>"  id="ps_prod_proprosal_date" required class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                    <th>출시확정일</th>
                                    <td>
                                        <span style="position: relative;">
                                            <input type="text" name="ps_real_ipgo_date" value="<?php echo $ps['ps_real_ipgo_date']; ?>"  id="ps_real_ipgo_date" class="frm_input required" size="21" maxlength="19">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"  style="position: absolute;bottom: 2px;right: 10px;top: auto;cursor: pointer;"></i>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button class="btn btn_02" type="button" id="btn_cancel">취소</button>
                            <button class="btn btn_02" type="button" id="btn_submit">임시저장</button>
                            <button type="submit" class="btn btn-success" onclick="formsubmit()"  value="저장">저장</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../vendors/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.js"></script>
<script>
    <? if ($write_min || $write_max) { ?>
        // 글자수 제한
        var char_min = parseInt(<?= $write_min; ?>); // 최소
        var char_max = parseInt(<?= $write_max; ?>); // 최대
        check_byte("cp_content", "char_count");

        $(function() {
            $("#cp_content").on("keyup", function() {
                check_byte("cp_content", "char_count");
            });
        });

    <? } ?>

    function html_auto_br(obj) {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        } else
            obj.value = "";
    }

    function ps_approval_date_DatePicker(){
        $('#ps_approval_date').datetimepicker({
            //ignoreReadonly: true,
            allowInputToggle: true,
            format: 'YYYY-MM-DD',
            locale: 'ko'
        });
    }
    //결재승인일자
    $('#ps_approval_date').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //원단발주
    $('#ps_balju').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: false,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //원단납기예정
    $('#ps_expected_limit_date').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: false,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //원단검품
    $('#ps_gumpum').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: false,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //생산발주
    $('#ps_prod_balju').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //샘플예정일
    $('#ps_sample_date').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //출시예정일
    $('#ps_ipgo_date').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //기획승인일자
    $('#ps_prod_proprosal_date').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });
    //출시확정일
    $('#ps_real_ipgo_date').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD',
        locale: 'ko'
    });






    $('#enddatepicker').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'YYYY-MM-DD HH:mm',
        locale: 'ko'
    });

    $("#startdatepicker").on("dp.change", function(e) {
        $('#enddatepicker').data("DateTimePicker").minDate(e.date);
    });

    $("#enddatepicker").on("dp.change", function(e) {
        $('#startdatepicker').data("DateTimePicker").maxDate(e.date);
    });

    $("#btn_cancel").click(function() {
        if (confirm("목록으로 이동 시 입력된 값은 삭제됩니다. 이동하시겠습니까?")) {
            window.history.back();
        }
    });

    $("#btn_submit").click(function() {
        fwrite_submit($("#fwrite"));
    });
    function formsubmit(){
        
        var f = document.fwrite;
        var $f = jQuery(f);
        var $b = jQuery(this);
        var $t, t;
        var result = true;
        if (confirm("저장하시겠습니까?")) {
            $f.find("input, select, textarea").each(function(i) {
                $t = jQuery(this);

                if($t.prop("required")) {
                    if(!jQuery.trim($t.val())) {
                        //t = jQuery("label[for='"+$t.attr("id")+"']").text();
                        result = false;
                        $t.focus();
                        //alert(t+" 필수 입력입니다.");
                        return false;
                    }
                }
            });
            if(!result){
                return false;
            }
            f.submit();
        }
    }

    var addItemCnt = 0;

    function fwrite_submit(f) {
        // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
        

        //기간 설정
        if ($('#startdate').val() == "") {
            alert('게시일을 입력하세요.');
            return false;
        }

        if ($('#enddate').val() == "") {
            alert('종료일을 입력하세요.');
            return false;
        }

        if (confirm("저장하시겠습니까?")) {
            document.getElementById("btn_submit").disabled = "disabled";
            f.submit();
        } else {
            return false;
        }
    }
</script>



<script>
    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }

</script>

<script>
    $(function() {
        $('#coupon_btn_category_add').click(function() {

            var ca_id = $('#coupon_sel_product_main').val();
            if (ca_id != "") {
                let ca_name = $('#coupon_sel_product_main :selected').text();
                let ca_ids = [];

                let stop = false;
                $('#coupon_ul_category li').each(function() {
                    if ($(this).attr("data") == ca_id) {
                        alert("등록된 상품분류입니다.");
                        stop = true;
                        return;
                    }
                    ca_ids.push($(this).attr("data"));
                });
                if (stop) return;

                let li_script = '<li data="' + ca_id + '">' + ca_name +
                    '<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>' +
                    '</li>';

                $('#coupon_ul_category').append(li_script);
                $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
                $("button[name='coupon_btn_category_delete']").css("height", "100%");

                ca_ids.push(ca_id);
                $("#cp_item_set_category_" + CpItemIndex).val(ca_ids.join(','))
            }
        });

        $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
        $("button[name='coupon_btn_category_delete']").css("height", "100%");


        $("#btnSearch").click(function(event) {
            var $table = $("#tblProduct");
            $.post(
                "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                    ca_id: $("#ca_id").val(),
                    stx: $("#stx").val(),
                    not_it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
                },
                function(data) {
                    if(!data){
                        alert("해당 상품이 없거나, 상품을 검색 할 수 없습니다.");
                    }
                    $table.empty().html(data);
                }
            );
        });

        $("#btnProductDel").click(function(event) {
            if (!is_checked("chk2[]")) {
                alert("삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            if (confirm("삭제하시겠습니까?")) {

                var $chk = $("input[name='chk2[]']");
                var $it_id = new Array();

                for (var i = 0; i < $chk.size(); i++) {
                    if (!$($chk[i]).is(':checked')) {
                        var k = $($chk[i]).val();
                        $it_id.push($("input[name='it_id2[" + k + "]']").val());
                    }
                }

                $("#cp_item_set_item_" + CpItemIndex).val($it_id.join(","));
                tblProductFormBind();
            }
        });


        $("#btnProductSubmit").click(function(event) {
            if (!is_checked("chk[]")) {
                alert("등록 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            var $chk = $("input[name='chk[]']:checked");
            var $it_id = new Array();

            for (var i = 0; i < $chk.size(); i++) {
                var k = $($chk[i]).val();
                $it_id.push($("input[name='it_id[" + k + "]']").val());
            }

            var it_ids = $it_id.join(",");
            if ($("#cp_item_set_item_" + CpItemIndex).val() != "") it_ids += "," + $("#cp_item_set_item_" + CpItemIndex).val();
            $("#cp_item_set_item_" + CpItemIndex).val(it_ids);

            tblProductFormBind();
            $("#btnSearch").click();
        });

        $("#btnProductSearch").click(function(event) {
            $("#stx").val("");
            var $table = $("#tblProduct");
            $table.empty();
            $("#modal_product").modal('show');
        });
    });
</script>

<?
include_once('../../admin.tail.php');
?>