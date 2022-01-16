<?php
$sub_menu = '41';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '판매등록 리스트';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');



$sql_search = " where (1)";
if($sfl){
    if($stx){
        switch($sfl){
            case 'samjin_code':
                $sql_search .= " and samjin_code like '%{$stx}%' ";
                break;
            case 'mall_id':
                $sql_search .= " and mall_code like '%{$stx}%' ";
                break;
            case 'mall_order_no':
                preg_match_all("/[^() || \-\ \/\,]+/", $stx,$mall_order_no_list);
                $mall_order_no_in_list = empty($mall_order_no_list[0])?'NULL':"'".join("','", $mall_order_no_list[0])."'";
                $sql_search.= "and mall_order_no IN({$mall_order_no_in_list})";
                break;
            case 'sabang_ord_no':
                preg_match_all("/[^() ||  \/\,]+/", $stx,$sabang_ord_no_list);
                $sabang_ord_no_in_list = empty($sabang_ord_no_list[0])?'NULL':"'".join("','", $sabang_ord_no_list[0])."'";
                $sql_search .= " AND sabang_ord_no IN ({$sabang_ord_no_in_list})";
                break;
        }
    }else{

    }
    // if ($stx) {
    //     $sql_search .= " and ps_it_name like '%{$stx}%' ";
    // }
}


if ($sc_it_time != "") {
    $sc_it_times = explode("~", $sc_it_time);
    $fr_sc_it_time = trim($sc_it_times[0]);
    $to_sc_it_time = trim($sc_it_times[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_sc_it_time) ) $fr_sc_it_time = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_sc_it_time) ) $to_sc_it_time = '';

    $timestamp1 = strptime($fr_sc_it_time, '%Y-%m-%d');
    $timestamp2 = strptime($to_sc_it_time, '%Y-%m-%d');
    

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp2['tm_mon']+1, $timestamp2['tm_mday']+1, $timestamp2['tm_year']+1900);

    if($sc_it_time == " ") {
        $sql_search .= " and reg_date is not null ";
    }else{
        if ($fr_sc_it_time && $to_sc_it_time) {
            $sql_search .= " and reg_date between   FROM_UNIXTIME({$fr_sc_it_time}) and  FROM_UNIXTIME({$to_sc_it_time}) ";
        }
    }

}else{
    $toDate = date("Y-m-d");

    $timestamp1 = strptime($toDate, '%Y-%m-%d');

    $fr_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday'], $timestamp1['tm_year']+1900);
    $to_sc_it_time = mktime(0, 0, 0, $timestamp1['tm_mon']+1, $timestamp1['tm_mday']+1, $timestamp1['tm_year']+1900);

    $sql_search .= " and reg_date between   FROM_UNIXTIME({$fr_sc_it_time}) and  FROM_UNIXTIME({$to_sc_it_time}) ";
    $sc_it_time = $toDate.' ~ '.$toDate;
}


if ($mb_today_login != "") {
    $mb_today_logins = explode("~", $mb_today_login);
    $fr_mb_today_login = trim($mb_today_logins[0]);
    $to_mb_today_login = trim($mb_today_logins[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_today_login) ) $fr_mb_today_login = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_today_login) ) $to_mb_today_login = '';

    if ($fr_mb_today_login && $to_mb_today_login) {
        $sql_search .= " and reg_date between '$fr_mb_today_login' and '$to_mb_today_login' ";
    }
}

$sql_search .= "ORDER BY sabang_ord_no DESC ,order_price DESC, sno ASC ";

if($limit_list) $rows = $limit_list;
//else $rows = $config['cf_page_rows'];
 else $rows = 50;
// $rows=4;
// 테이블의 전체 레코드수만 얻음
$cnt_sql = "SELECT COUNT(*) AS cnt FROM  samjin_order_sale_registration {$sql_search}";
$cnt_row = sql_fetch($cnt_sql);
$total_count = $cnt_row['cnt'];

$allPut = false;
if ($limit_list == -1) { 
	$allPut = true;
    $from_record = 0;
    $rows = $total_count;
	// $outputCount = $total_count;
}

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = "SELECT * FROM samjin_order_sale_registration {$sql_search}  limit $from_record, $rows ";

$result = sql_query($sql);


$qstr= "sfl=".$sfl."&amp;stx=".$stx."&amp;sc_it_time=".$sc_it_time."&amp;limit_list=".$limit_list."&amp;page=".$page;
if ($allPut) $limit_list = '-1';
$oform_headers = array('구분','거래처코드','거래처명','삼진코드','삼진품목명','색상','사이즈','주문수량','할인','마진','단가','분할가','세트예상분할가','쇼핑몰주문번호','사방넷주문번호','송장등록일시');
$oform_bodys = array('order_gb','mall_code','mall_name','samjin_code','samjin_name','samjin_color','samjin_size','order_cnt','order_sale','order_majin','order_price','order_division_price','round_division_price','mall_order_no','sabang_ord_no','reg_date');

$enc = new str_encrypt();

$oform_headers = $enc->encrypt(json_encode_raw($oform_headers));
$oform_bodys = $enc->encrypt(json_encode_raw($oform_bodys));

?>

<script src="./jquery.table2excel.js"></script>
<body id="total_order_body">
<div class="x_panel">
<!-- <div style="background-color : #fff;"> -->
    <form id="new_goods_form" name="new_goods_form" class="local_sch01 local_sch" onsubmit="" method="get">
        
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
                        <!-- <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option> -->
                        <option value="samjin_code" <?php echo get_selected($sfl, 'samjin_code'); ?>>삼진코드</option>
                        <option value="mall_id" <?php echo get_selected($sfl, 'mall_id'); ?>>거래처코드</option>
                        <option value="mall_order_no" <?php echo get_selected($sfl, 'mall_order_no'); ?>>쇼핑몰주문번호</option>
                        <option value="sabang_ord_no" <?php echo get_selected($sfl, 'sabang_ord_no'); ?>>사방넷주문번호</option>
                    </select>
                    <label for="stx" class="sound_only">검색어</label>
                    <input type="text" style="width : 90%;" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
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
                        <option value="-1" <?php if(substr_count($limit_list, '-1') >= 1) echo "selected"; ?>>전체 보기</option>
                        <!-- <option value="-1" <?= get_selected($outputCount, '-1'); ?> >전체 보기</option> -->
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
<!-- </div> -->

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
	<span class="btn_ov01">[ 검색결과 <?= number_format($total_count); ?>건 ]</span>
</div>
<form>
    <input type ="hidden" name ="excel_sno" id="excel_sno" >
<div class="local_cmd01 local_cmd" style="margin-top : 20px;">
    <div class="btn btn_02" style="height: 30px;" onclick ="down_excel()">엑셀다운로드</div>
    <input type='file' name ="upload_excel" id='upload_excel' />
    <div class="btn btn_02" style="height: 30px;" id="upload_excel_btn">엑셀업로드
    </div>
</div>

<table id="reportTb" class="display" style="width:100%">
        <thead>
            <tr>
                <th><label for="chkall" class="sound_only">선택 전체</label>
                    <input type="checkbox" name="chkall" value="1" id="chkall" class="chk_all" onclick="all_chk(this.form)">
                </th>
                <th>구분</th>
                <th>거래처코드</th>
                <th>거래처명</th>
                <th>삼진품목명</th>
                <th>삼진코드</th>
                <th>색상</th>
                <th>사이즈</th>
                <th>주문수량</th>
                <th>할인</th>
                <th>마진</th>
                <th>단가</th>
                <th>분할가</th>
                <th>세트예상<br>분할가</th>
                <th>쇼핑몰주문번호</th>
                <th>사방넷주문번호</th>
                
            </tr>
        </thead>
        <tbody id="revenue-status">
            <?if(!empty($result)):?>
            <?for($ofi = 0 ; $row_ord = sql_fetch_array($result); $ofi++) {?>
            <?
                if($idx_sabang_no == $row_ord['sabang_ord_no']){

                }else{
                    $idx_sabang_no = $row_ord['sabang_ord_no'];
                    $set_price_sum  = $row_ord['order_price'];
                }
            ?>
            <tr style="<?= $row_ord['copy_idx'] ? 'background-color : yellow;':'' ?> <?if($row_ord['order_gb'] =='002' && $row_ord['set_code'] && ($set_price_sum != $row_ord['set_division_sum']) ){ echo 'background-color : red;';}?> ">
                <td>
                    <input type="checkbox" name="chk[]" value="<?=$ofi?>">
                    <input type="hidden" name="sno[<?=$ofi?>]" value="<?=$row_ord['sno']?>" id="sno_<?=$ofi?>">
                </td>
                <td><?=$row_ord['order_gb'] =='002' ? "세트" : "단품"?></td>
                <td><?=$row_ord['mall_code']?></td>
                <td><?=$row_ord['mall_name']?></td>
                <td><?=$row_ord['samjin_name']?></td>
                <td><?=$row_ord['samjin_code']?></td>
                <td><?=$row_ord['samjin_color']?></td>
                <td><?=$row_ord['samjin_size']?></td>
                <td><?=$row_ord['order_cnt']?></td>
                <td>-</td>
                <td>-</td>
                <td><?=$row_ord['order_price']?></td>
                <td>
                    <?if(($set_price_sum == $row_ord['set_division_sum']) && $row_ord['order_gb'] =='002') :?>
                        <?=$row_ord['set_division_price']?>
                    <?else :?>
                        <?=$row_ord['order_division_price']?>
                    <?endif?>
                </td>
                <td><?=$row_ord['round_division_price']?></td>
                <td><?=$row_ord['copy_idx'] ? 'ⓒ':''?> <?=$row_ord['set_code'] ? 'ⓢ':''?>  <?=$row_ord['mall_order_no']?></td>
                <td><?=$row_ord['sabang_ord_no']?></td>
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
                $('#it_time').val(" ");
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
        $(document).ready(function () {
            var table = $('#reportTb').DataTable({
                scrollY: "650px",
                scrollX: true,
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
                    $('td', row).css('text-align', 'center');
                    // $('td:eq(1)', row).css('text-align', 'center');

                },
                fixedColumns: {
                    leftColumns: 5
                }

            });

            $('#upload_excel').hide();
            $('#upload_excel_btn').on('click', function () {$('#upload_excel').click();});

            $('#upload_excel').change(function () {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onloadend = function () {
                //    $('#main_pf_foto_img').attr('src', reader.result);
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                }
                upload_excel();
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
            $("#excel_sno").val('');

            $("input[name='chk[]']:checked").each(function() {
                var sno = $(".DTFC_LeftBodyLiner input[name='sno["+this.value+"]']").val();
                
                if(sno != undefined || sno != ''){
                    $select.push(sno);
                }

            });

            var selects = $select.join(",");
            // if ($("#excel_sno").val() != "") selects += "," + $("#excel_sno").val();
            $("#excel_sno").val(selects);

            
            excel_sql = "select * from samjin_order_sale_registration where sno in ( "+selects +" ) ORDER BY sabang_ord_no DESC , sno ASC ";
            headerdata = $('<input type="hidden" value="<?=$oform_headers?>" name="headerdata">');
            bodydata = $('<input type="hidden" value="<?=$oform_bodys?>" name="bodydata">');
            excel_type = '판매등록';

            var $form = $('<form></form>');     
            $form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.total_order_sale_registration.php');
            $form.attr('method', 'post');
            $form.appendTo('body');
            
            var exceldata = $('<input type="hidden" value="'+excel_sql+'" name="exceldata">');
            
            var excelnamedata = $('<input type="hidden" value="'+excel_type+'" name="excelnamedata">');
            $form.append(exceldata).append(headerdata).append(bodydata).append(excelnamedata);
            $form.submit();

        }

        function upload_excel(){
            var $excelfile = $("#upload_excel");
            
            var $form = $('<form></form>');     
            $form.attr('action', './upload_samjin_sale_registration.php');
            $form.attr('method', 'post');
            $form.attr('enctype', 'multipart/form-data');
            $form.appendTo('body');
            $form.append($excelfile);

        
            $form.submit();
        }

    </script>
<?php 
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
