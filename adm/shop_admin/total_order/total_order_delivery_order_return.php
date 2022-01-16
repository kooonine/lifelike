<?php
$sub_menu = '41';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '반품지시 리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


$sql_search = " where (1)";
if($sfl){
    if($stx){
        switch($sfl){
            case 'samjin_name':
                $sql_search .= " and snor_samjin_name like '%{$stx}%' ";
                break;
            case 'samjin_code':
                $sql_search .= " and snor_samjin_code like '%{$stx}%' ";
                break;
            // case 'mall_code':
            //     if (in_array("NT", $os)) {
            //         echo "NT가 존재합니다.";
            //     }
            //     $sql_search .= " and m{$stx} is not null ";
            //     break;
            
        }
    }else{

    }
    // if ($stx) {
    //     $sql_search .= " and ps_it_name like '%{$stx}%' ";
    // }
}
if ($sc_it_time != "") {
    if($sc_it_time =="   ") {

    } else {
        $sc_it_times = explode("~", $sc_it_time);
        $fr_sc_it_time = trim($sc_it_times[0]);
        $to_sc_it_time = trim($sc_it_times[1]);
    
        if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_sc_it_time) ) $fr_sc_it_time = '';
        if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_sc_it_time) ) $to_sc_it_time = '';
    
        $timestamp1 = strptime($fr_sc_it_time, '%Y-%m-%d');
        $timestamp2 = strptime($to_sc_it_time, '%Y-%m-%d');
        
    
        $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
        $to_sc_it_time = mktime(0, 0, 0, $timestamp2['tm_mon']+1, $timestamp2['tm_mday']+1, $timestamp2['tm_year']+1900);
    
        if ($fr_sc_it_time && $to_sc_it_time) {
            $sql_search .= " and reg_dt between   FROM_UNIXTIME({$fr_sc_it_time}) and  FROM_UNIXTIME({$to_sc_it_time}) ";
        }
    }
    
}else{
    $toDate = date("Y-m-d");

    $timestamp1 = strptime($toDate, '%Y-%m-%d');

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']+1, $timestamp1['tm_year']+1900);

    $sql_search .= " and reg_dt between   FROM_UNIXTIME({$fr_sc_it_time}) and  FROM_UNIXTIME({$to_sc_it_time}) ";
    $sc_it_time = $toDate.' ~ '.$toDate;
}

if ($mb_today_login != "") {
    $mb_today_logins = explode("~", $mb_today_login);
    $fr_mb_today_login = trim($mb_today_logins[0]);
    $to_mb_today_login = trim($mb_today_logins[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_today_login) ) $fr_mb_today_login = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_today_login) ) $to_mb_today_login = '';

    if ($fr_mb_today_login && $to_mb_today_login) {
        $sql_search .= " and reg_dt between '$fr_mb_today_login' and '$to_mb_today_login' ";
    }
}




if (!$dpartner_ids) {
    //$sql_search .= " and dparner_id in ('경민실업','어시스트','본사') ";
}else{
    if ($dpartner_ids == '100') {
        $sql_search .= " and dpartner_id = 100 ";
    }else{
        // $sql_search .= " and dpartner_id in ('{$dpartner_ids}') ";
        $dpartner_ids_item = explode(',', $dpartner_ids);
        // $sql_search .= " dpartner_name in ('{$dparter}%') ";
        $sql_search .= " and ( ";
        foreach($dpartner_ids_item as $dii => $dparter){
            if($dii == 0 ){
                $sql_search .= " dpartner_id = {$dparter} ";
            }else{
                $sql_search .= " or dpartner_id = {$dparter} ";
            }
        }
        $sql_search .= " ) ";
    }
}

if($limit_list) $rows = $limit_list;
//else $rows = $config['cf_page_rows'];
else $rows = 50;
// $rows=4;

// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt 
FROM samjin_order_delivery_order_return {$sql_search} 
GROUP BY snor_mall_code, snor_samjin_code, snor_samjin_color, snor_samjin_size";
$cnt_row = sql_fetch($cnt_sql);

$cnt_row2 = sql_query($cnt_sql);
$total_count2 = 0;
for($cri = 0 ; $cr= sql_fetch_array($cnt_row2); $cri++){
    $total_count2 += $cr['cnt'];
}
$total_count = $cnt_row['cnt'];
$total_page  = ceil($total_count2 / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = "SELECT sum(snor_cnt) AS sumcnt , snor_id,snor_mall_code, snor_mall_name, snor_samjin_code, snor_samjin_name,snor_samjin_color,snor_samjin_size, snor_price, snor_division_price, snor_mall_order_no, snor_sabang_ord_no 
FROM samjin_order_delivery_order_return {$sql_search} 
GROUP BY snor_mall_code, snor_samjin_code, snor_samjin_color, snor_samjin_size ORDER BY snor_mall_code ASC limit $from_record, $rows";

$result = sql_query($sql);


$qstr= "dpartner_ids=".$dpartner_ids."&amp;sfl=".$sfl."&amp;stx=".$stx."&amp;sc_it_time=".$sc_it_time."&amp;limit_list=".$limit_list."&amp;page=".$page;


$oform_headers = array('거래처코드','거래처명','삼진코드','삼진품목명','색상','사이즈','주문수량','할인','마진','단가');
$oform_bodys = array('snor_mall_code','snor_mall_name','snor_samjin_code','snor_samjin_name','snor_samjin_color','snor_samjin_size','sumcnt','-','-','snor_price');

$enc = new str_encrypt();

$oform_headers = $enc->encrypt(json_encode_raw($oform_headers));
$oform_bodys = $enc->encrypt(json_encode_raw($oform_bodys));



?>
<script src="./jquery.table2excel.js"></script>
<body id ="total_order_body">
<!-- <div style="background-color : #fff;"> -->
<div class="x_panel">
    <form id="new_goods_form" name="new_goods_form" class="local_sch01 local_sch" onsubmit="" method="get">
        <input type="hidden" name = "dpartner_ids" value='<?=$dpartner_ids?>' id="dpartner_ids">
        
        <div class="tbl_frm01 tbl_wrap">
            <table class="new_goods_list">
            <colgroup>
            <!-- <col class="grid_4">
            <col>
            <col class="grid_3"> -->
            </colgroup>
            
            <tr>
                <th scope="row">검색분류</th>
                <td colspan="2">
                    <label for="sfl" class="sound_only">검색대상</label>
                    <select name="sfl" id="sfl">
                        <option value="samjin_name" <?php echo get_selected($sfl, 'samjin_name'); ?>>상품명</option>
                        <option value="samjin_code" <?php echo get_selected($sfl, 'samjin_code'); ?>>삼진코드</option>
                        <option value="mall_code" <?php echo get_selected($sfl, 'mall_code'); ?>>거래처코드</option>
                    </select>
                    <label for="stx" class="sound_only">검색어</label>
                    <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
            </td>
            </tr>
            <tr>
                <th scope="row">물류</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <label><input type="checkbox" value=""  id="dpartner_id_0"  <?php if(!$dpartner_ids) echo "checked"; ?>>전체</label>&nbsp;&nbsp;
                    <label><input type="checkbox" value="100" id="dpartner_id_1" class="dpartner_id" <?php if(!$dpartner_ids || (substr_count($dpartner_ids, '100') >= 1) ) echo "checked"; ?> >경민실업</label>&nbsp;&nbsp;
                    <label><input type="checkbox" value="200" id="dpartner_id_2" class="dpartner_id" <?php if(!$dpartner_ids || (substr_count($dpartner_ids, '200') >= 1) ) echo "checked"; ?> >어시스트</label>&nbsp;&nbsp;
                    <label><input type="checkbox" value="300" id="dpartner_id_3" class="dpartner_id" <?php if(!$dpartner_ids || (substr_count($dpartner_ids, '300') >= 1) ) echo "checked"; ?> >본사</label>&nbsp;&nbsp;
                </div>
                </td>
            </tr>
            <tr>
                <th scope="row">일자</th>
                <td colspan="2">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <input type='text' class="form-control" id="it_time" name="sc_it_time" value="" autocomplete="off"/>
                        <i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                        <div class="btn-group" >
                            <button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                            <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                        </div>
                    </div>
                </td>
            </tr>
            
           
            <tr>
                <th scope="row">보기</th>
                <td colspan="2">
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                    <select name="limit_list" id="limit_list">
                        <option value="50" <?php if(!$limit_list ||(substr_count($limit_list, '50') >= 1)) echo "selected"; ?>>50개</option>
                        <option value="100" <?php if(substr_count($limit_list, '100') >= 1) echo "selected"; ?>>100개</option>
                        <option value="200" <?php if(substr_count($limit_list, '200') >= 1) echo "selected"; ?>>200개</option>
                        <option value="300" <?php if(substr_count($limit_list, '300') >= 1) echo "selected"; ?>>300개</option>
                        <option value="400" <?php if(substr_count($limit_list, '400') >= 1) echo "selected"; ?>>400개</option>
                        <option value="500" <?php if(substr_count($limit_list, '500') >= 1) echo "selected"; ?>>500개</option>
                    </select>
                </div>
                </td>
            </tr>
            </table>
        </div>
        <div class="form-group">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <button class="btn btn_02 search-reset" type="button" id="btn_clear">초기화</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
            </div>
        </div>
    </form>


<style>
        th, td {
            white-space: nowrap;
        }

        div.dataTables_wrapper {
            width: 100%;
            margin: 0 auto;
        }
        table.dataTable tr, td{
            border: 1px solid lightgray;
        }
        table.dataTable tr > td{
            border: 1px solid lightgray;
        }
            table.dataTable tr > th {
                border: 1px solid lightgray;
                background:#eeeeee;
            }
        /*table.dataTable thead > tr > td {
            width: 80px;
        }*/
        #total_order_body{font-size : 13px;}
    </style>
<link rel="stylesheet" href="./fixed_table.css">
<div class="local_ov01 local_ov">
	<span class="btn_ov01">[ 검색결과 <?= number_format($total_count2); ?>건 ]</span>
</div>
<form>
    <input type ="hidden" name ="excel_sno" id="excel_sno" >
<div class="local_cmd01 local_cmd" style="margin-top : 20px;">
    <div class="btn btn_02" style="height: 30px;" onclick ="down_excel()">엑셀다운로드</div>
</div>

<table id="reportTb" class="display" style="width:100%">
        <thead>
            <tr>
                <th>
                    <label for="chkall" class="sound_only">선택 전체</label>
                    <input style="margin-left: -8px;" type="checkbox" name="chkall" value="1" id="chkall" class="chk_all" onclick="allCheck2(this.form)">
                </th>
                <th>거래처코드</th>
                <th>거래처명</th>
                <th>삼진코드</th>
                <th>삼진품목명</th>
                <th>색상</th>
                <th>사이즈</th>
                <th>주문수량</th>
                <th>할인</th>
                <th>마진</th>
                <th>단가</th>
            </tr>
        </thead>
        <tbody id="revenue-status">
            <?if(!empty($result)):?>
            <?for($ofi = 0 ; $row_ord = sql_fetch_array($result); $ofi++) {?>
            <tr>
                <td>
                    <input type="checkbox" name="chk[]" value="<?=$ofi?>">
                    <input type="hidden" name="sno[<?=$ofi?>]" value="<?=$row_ord['snor_samjin_name']?>"snor_mall_code ="<?=$row_ord['snor_mall_code']?>", snor_samjin_color ="<?=$row_ord['snor_samjin_color']?>", snor_samjin_size ="<?=$row_ord['snor_samjin_size']?>", snor_samjin_code ="<?=$row_ord['snor_samjin_code']?>"  id="sno_<?=$ofi?>">
                </td>
                <td><?=$row_ord['snor_mall_code']?></td>
                <td><?=$row_ord['snor_mall_name']?></td>
                <td><?=$row_ord['snor_samjin_code']?></td>
                <td><?=$row_ord['snor_samjin_name']?></td>
                <td><?=$row_ord['snor_samjin_color']?></td>
                <td><?=$row_ord['snor_samjin_size']?></td>
                <td><?=$row_ord['sumcnt']?></td>
                <td>-</td>
                <td>-</td>
                <td><?=$row_ord['snor_price']?></td>
            </tr>
            <?}?>
            <?endif?>
        </tbody>
    </table>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div> 
</body>
    <script src="./fixed_table.js"></script>

    <script>
        // 일자
        $('#it_time').daterangepicker({
            "autoApply": true,
            "opens": "right",
            locale: {
                "format": "YYYY-MM-DD",
                "separator": " ~ ",
                "applyLabel": "선택",
                "cancelLabel": "취소",
                "fromLabel": "시작일자",
                "toLabel": "종료일자",
                "customRangeLabel": "직접선택",
                "weekLabel": "W",
                "daysOfWeek": ["일","월","화","수","목","금","토"],
                "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
                "firstDay": 1
            }
            /*,ranges: {
                '오늘': [moment(), moment()],
                '3일': [moment().subtract(2, 'days'), moment()],
                '1주': [moment().subtract(6, 'days'), moment()],
                '1개월': [moment().subtract(1, 'month'), moment()],
                '3개월': [moment().subtract(3, 'month'), moment()],
                '이번달': [moment().startOf('month'), moment().endOf('month')],
                '마지막달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }*/
        });
        //alert($("button[name='dateBtn'].btn_03").attr("data"));
        $('#it_time').val("<?php echo $sc_it_time ?>");
        //날짜 버튼
        $("button[name='dateBtn']").click(function(){
            
            var d = $(this).attr("data");
            if(d == "all") {
                $('#it_time').val("   ");
            } else {
                var startD = moment();
                var endD = moment();
                
                if(d == "3d") {
                    startD = moment().subtract(2, 'days');
                    endD = moment();
                    
                } else if(d == "1w") {
                    startD = moment().subtract(6, 'days');
                    endD = moment();
                    
                } else if(d == "1m") {
                    startD = moment().subtract(1, 'month');
                    endD = moment();
                    
                } else if(d == "3m") {
                    startD = moment().subtract(3, 'month');
                    endD = moment();
                }
        
                $('#it_time').data('daterangepicker').setStartDate(startD);
                $('#it_time').data('daterangepicker').setEndDate(endD);
            }
        
        });

        $(".dpartner_id").change(function(){
            var dpartnerId = "";
            $("input.dpartner_id:checked").each(function(){
                //alert($(this).val());
                if(dpartnerId != "") dpartnerId += ",";
                dpartnerId += $(this).val();

            });
            $("#dpartner_ids").val(dpartnerId);
        });
        $("#dpartner_id_0").change(function(){
            if($("#dpartner_id_0").is(":checked")){
                $(".dpartner_id").prop('checked',true);
                $("#dpartner_ids").val('');
            }else{
                $(".dpartner_id").prop('checked',false);
            }
        });
        
        $(document).ready(function () {
            var table = $('#reportTb').DataTable({
                // scrollY: "650px",
                // scrollX: true,
                scrollCollapse: true,
                ordering: false,
                info: false,
                paging: false,
                searching: false,
                createdRow: function (row, data, dataIndex) {

                    //ROWSPAN
                    if (dataIndex == 0) {
                        //$('td:eq(0)', row).attr('rowspan', 9);
                    }
                    else {
                        if (data[1] === '대한항공') {
                            //$('td:eq(0)', row).attr('rowspan', 9);
                        }
                        else {
                            //$('td:eq(0)', row).css('display', 'none');
                        }
                    }
                    //COLSPAN
                    if (data[1] === '합계') {
                        $('td:eq(1)', row).attr('colspan', 2);
                    }

                    //CSS셋팅
                    // $('td:not(:eq(0))', row).css('text-align', 'right');
                    // $('td:eq(0)', row).css('text-align', 'center');
                    // $('td:eq(1)', row).css('text-align', 'center');

                },
                // fixedColumns: {
                //     leftColumns: 0
                // }

            });
        });

        function all_chk(){
            if($(".chk_all").hasClass("allchks")){
                $(".chk_all").removeClass("allchks");
                $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , false);
            }else{
                $(".chk_all").addClass("allchks");
                $(".DTFC_LeftBodyLiner input[name='chk[]']").prop("checked" , true);
            }
        }
        function allCheck2(e=false) { 
            if ($("input:checkbox[id='chkall']").is(':checked')) {
                $("input[name='chk[]']").prop("checked" , true);
            } else {
                $("input[name='chk[]']").prop("checked" , false);
            }
        }

        function down_excel(){
            // $("#reportTb").table2excel({
            //     name: "Excel table",
            //     filename: "excel table",
            //     fileext: ".xls",
            //     exclude_img: true,
            //     exclude_links: true,
            //     exclude_inputs: true
            // });

            if (!is_checked("chk[]")) {
                alert("엑셀 다운로드 할 상품을 선택해주세요.");
                return false;
            }
            
            var $select = new Array();
            var $selectMallCode = new Array();
            var $snorSamjinColor = new Array();
            var $snorSamjinSize = new Array();
            var $snorSamjinCode = new Array();
            
            $("#excel_sno").val('');

            $("input[name='chk[]']:checked").each(function() {
                var sno = $("input[name='sno["+this.value+"]']").val();
                let snor_mall_code = $("input[name='sno["+this.value+"]']").attr("snor_mall_code");
                let snor_samjin_color = $("input[name='sno["+this.value+"]']").attr("snor_samjin_color");
                let snor_samjin_size = $("input[name='sno["+this.value+"]']").attr("snor_samjin_size");
                let snor_samjin_code = $("input[name='sno["+this.value+"]']").attr("snor_samjin_code");

                if(sno != undefined || sno != ''){
                    $select.push("'"+sno+"'");

                    $selectMallCode.push(snor_mall_code);
                    $snorSamjinColor.push(snor_samjin_color);
                    $snorSamjinSize.push(snor_samjin_size);
                    $snorSamjinCode.push(snor_samjin_code);
                }
            });
            var selects = $select.join(',');

            var selectMallCodes = $selectMallCode.join(',');
            var snorSamjinColors = $snorSamjinColor.join(',');
            var snorSamjinSizes = $snorSamjinSize.join(',');
            var snorSamjinCods = $snorSamjinCode.join(',');
            // if ($("#excel_sno").val() != "") selects += "," + $("#excel_sno").val();
            $("#excel_sno").val(selects);
            excel_sql = $select;

            headerdata = $('<input type="hidden" value="<?=$oform_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$oform_bodys?>" name="bodydata">');
            excel_type = '반품지시';

            var $form = $('<form></form>');     
            $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.total_order_delivery_order_return.php');
            $form.attr('method', 'post');
            $form.appendTo('body');
            
            var sec = $('<input type="hidden" value="<?=$sql_search?>" name="sec">');

            var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
            var exceldata_1 = $('<input type="hidden" value="'+selectMallCodes+'" name="exceldata_1">');
            var exceldata_2 = $('<input type="hidden" value="'+snorSamjinColors+'" name="exceldata_2">');
            var exceldata_3 = $('<input type="hidden" value="'+snorSamjinSizes+'" name="exceldata_3">');
            var exceldata_4 = $('<input type="hidden" value="'+snorSamjinCods+'" name="exceldata_4">');
            
            var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
            $form.append(sec).append(exceldata).append(headerdata).append(bodydata).append(excelnamedata).append(exceldata_1).append(exceldata_2).append(exceldata_3).append(exceldata_4);
            $form.submit();

        }
    </script>
<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
