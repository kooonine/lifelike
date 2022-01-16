<?php
//$sub_menu = '300201';
$sub_menu = '30';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$g5['title'] = '베스트관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super' && $is_admin != 'admin')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;
    $nbsp = '';
    for ($i=0; $i<$len; $i++) {
        $nbsp .= '&nbsp;&nbsp;&nbsp;';
    }
    $ca_list .= '<option value="'.$row['ca_id'].'">'.$nbsp.$row['ca_name'].'</option>'.PHP_EOL;
}

$where = " and ";
$sql_search = "";
if ($stx != "") {
    
    if ($sfl == "its_sap_code") {
        $sql_search .= " $where it_id in (select it_id from lt_shop_item_sub where its_sap_code like '%$stx%' )";
        $where = " and ";
    }else if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if (!$best_ca || $best_ca == "") {
    $best_ca = '00';
}

if ($best_ca != "") {
    // $sql_search .= " $where (a.ca_id like '$best_ca%' or a.ca_id2 like '$best_ca%') ";
}

if ($sc_it_use != "") {
    $sql_search .= " and it_use = '{$sc_it_use}' ";
}

if ($sc_it_time != "") {
    $it_times = explode("~", $sc_it_time);
    $fr_date = trim($it_times[0]);
    $to_date = trim($it_times[1]);
    
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';
    
    $sql_search .= " and a.it_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($sfl == "")  $sfl = "it_name";

$sql_common = " from {$g5['g5_shop_item_table']} a ,
                     {$g5['g5_shop_category_table']} b,
                     {$g5['g5_shop_category_table']} c,
                     {$g5['g5_shop_category_table']} d,
                     lt_best_item e
               where (b.ca_id = left(a.ca_id,2)
                    and   c.ca_id = left(a.ca_id,4)
                    and   d.ca_id = left(a.ca_id,6)
                    and   e.it_id = a.it_id
                    and   e.bs_category = '{$best_ca}'
                    and   a.ca_id3 = ''
                    ";
                   
if ($is_admin != 'super' && $is_admin != 'admin')
    $sql_common .= " and b.ca_mb_id = '{$member['mb_id']}'";
$sql_common .= ") ";
$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;



$row = sql_fetch($sql);
$total_count = $row['cnt'];


if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
    $sst  = "it_time";
    $sod = "desc";
}
$sql_order = "order by sort ASC , $sst $sod";

$sql  = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*
				 , if(a.it_use = '1','진열','진열안함') as it_use_name
				 , if(a.it_soldout = '1','Y','N') as it_soldout_name
           $sql_common
           $sql_order
           limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$excel_sql = $sql;
if(substr_count($sql, "limit")){
    $sqls = explode('limit', $sql);
    $excel_sql = $sqls[0];
}
$headers = array('NO', '상품코드', '등록일','수정일', '분류', '카테고리', '상품명', '품목수량', '최종판매가','최종월리스료', '진열상태', '품절');
$bodys = array('NO', 'it_id', 'it_time','it_update_time',  'ca_name2', 'ca_name3', 'it_name', 'it_stock_qty', 'it_price', 'it_rental_price', 'it_use_name', 'it_soldout_name');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
   
$token = get_admin_token();
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        - 주간베스트페이지에 노출될 상품을 설정합니다.​<br>
        - 전체 포함 각 카테고리별 설정이 필요합니다.
  	
<form name="flist" id="flistSearch" class="local_sch01 local_sch">
<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
	<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>
    
    <tr>
        <th scope="row" style="width:15%;">카테고리</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<label for="best_ca" class="sound_only">상품카테고리 선택</label>
                <select name="best_ca" id="best_ca">
                    <option value="00" <?php echo get_selected($best_ca , '00') ?>>전체</option>
                    <option value="10" <?php echo get_selected($best_ca , '10') ?>>이불</option>
                    <option value="20" <?php echo get_selected($best_ca , '20') ?>>베개/패드/토퍼</option>
                    <option value="30" <?php echo get_selected($best_ca , '30') ?>>침구커버</option>
                    <option value="40" <?php echo get_selected($best_ca , '40') ?>>홈데코</option>
                </select>
                
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">전열상태</th>
		<td colspan="2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="it_use" name="sc_it_use" <?php echo ($sc_it_use == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="it_use1" name="sc_it_use" <?php echo ($sc_it_use == '1')?'checked':''; ?>> 진열함</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="it_use0" name="sc_it_use" <?php echo ($sc_it_use == '0')?'checked':''; ?>> 진열안함 </label>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">재고상태</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="it_use" name="sc_it_soldout" <?php echo ($sc_it_soldout == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="it_use1" name="sc_it_soldout" <?php echo ($sc_it_soldout == '0')?'checked':''; ?>> 재고있음</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="it_use0" name="sc_it_soldout" <?php echo ($sc_it_soldout == '1')?'checked':''; ?>> 재고없음(품절) </label>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </td>
    </tr>
    </table>
    </div>
        
    <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        	<button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
        	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
        </div>
    </div>
  	</div>

    <div class="x_panel">
      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 상품 목록<small></small></h4>
        <div class="clearfix"></div>
      </div>

      <div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        	<span class="btn_ov01"><span class="ov_txt">검색결과</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
        </div>
        <div class="pull-right">
		<input type="hidden" name="sst" id="sst" value="<?php echo $sst; ?>">
		<input type="hidden" name="sod" id="sod"  value="<?php echo $sod; ?>">

          <select id="sstsod" onchange="sstsod_change(this);">
            <option value="it_time,asc" <?php echo get_selected($sst.','.$sod, 'it_time,asc') ; ?>>등록일순</option>
            <option value="it_time,desc" <?php echo get_selected($sst.','.$sod, 'it_time,desc') ; ?>>최근등록일순</option>
            <option value="it_update_time,asc" <?php echo get_selected($sst.','.$sod, 'it_update_time,asc') ; ?>>수정일</option>
            <option value="it_update_time,desc" <?php echo get_selected($sst.','.$sod, 'it_update_time,desc') ; ?>>최근수정일</option>
          </select>
          <script>
          function sstsod_change(ctl)
          {
          	var sstsod = $("#"+ctl.id).val().split(',');
          	$("#sst").val(sstsod[0]);
          	$("#sod").val(sstsod[1]);

          	$('#flistSearch').submit();
              return true;
          }
          </script>
          <select name="page_rows" onchange="$('#flistSearch').submit();">
            <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
            <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
            <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
          </select>
          <br/><br/>
        </div>
      </div>
</form>

<form name="best_list_items" id="best_list_items" method="post" action="./best.listupdate.php" >

<input type="hidden" name="bs_category" id ="bs_category" value="<?=$best_ca?>">
<input type="hidden" name="sc_it_use" value="<?php echo $sc_it_use; ?>">
<input type="hidden" name="sc_it_soldout" value="<?php echo $sc_it_soldout; ?>">



<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
      <th colspan="12">
        
        <div class="pull-right">
          <a href="#"><input type="button" class="btn btn_03" value="선택삭제" id="btnProductDel2" /></input></a>
          

          <a href="#"><input type="button" class="btn btn_03" onclick="open_item_modal()" value="상품추가"></input></a>
          <!-- <input type="text" name="cp_item_set_subject[<?= $ii ?>]" id="cp_item_set_subject_<?= $ii ?>" value="<?= $cp_item['subject'] ?>"> -->
          <input type="hidden" name="cp_item_set_item0" id="cp_item_set_item_0" value="<?= $cp_item['item'] ?>">
          <input type="hidden" name="cp_item_set_category0" id="cp_item_set_category_0" value="<?= $cp_item['category'] ?>">

          <input type="hidden" name="cp_item_set_item_del" id="cp_item_set_item_del" value="">
        </div>
      </th>
    </tr>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">상품 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('it_time', 'sca='.$sca); ?>번호</a></th>
        <th scope="col"><?php echo subject_sort_link('it_item_type', 'sca='.$sca); ?>정렬</a></th>
        <th scope="col"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>상품코드</a></th>
        <th scope="col"><?php echo subject_sort_link('ca_name1', 'sca='.$sca); ?>카테고리</a></th>
        <th scope="col" colspan="2" id="th_pc_title"><?php echo subject_sort_link('it_name', 'sca='.$sca); ?>상품정보</a></th>
        <th scope="col" id="th_amt"><?php echo subject_sort_link('it_price', 'sca='.$sca); ?>최종판매가</a></th>
        <th scope="col" id="th_qty"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>전열<br/>상태</a></th>
        <th scope="col"><?php echo subject_sort_link('it_use', 'sca='.$sca, 1); ?>품절</a></th>
	</tr>
    </thead>
    <tbody id="bodylist">
        <?php
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $bg = 'bg' . ($i % 2);
            ?>
                <tr>
                    
                    <td class="td_chk bit_id">
                        <label for="bchk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>
                        <input type="checkbox" name="bchk<?php echo $chkname; ?>[]" value="<?php echo $i ?>" id="bchk<?php echo $chkname; ?>_<?php echo $i; ?>">

                        <input type="hidden" name="bit_id<?php echo $chkname; ?>[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
                    </td>
                    <td class="td_chk sort_td">
                        <input type="hidden" name ="sort[<?php echo $i; ?>]" value = "<?php echo $i + 1; ?>">
                        <?php echo ($i + 1); ?>
                    </td>
                    <td class="td_chk it_id_sort" data-value="<?php echo $row['it_id'] ?>">
                        <span class="glyphicon glyphicon-chevron-up" onclick="changeSort(this, 'up')"></span>
                        <span class="glyphicon glyphicon-chevron-down" onclick="changeSort(this, 'down')"></span>
                    </td>
                    <td class="td_num grid_2">
                        <?php echo $row['it_id']; ?>
                    </td>
                    <td class="td_sort grid_1">
                        <label for="it_item_type_<?php echo $i; ?>" class="sound_only">분류</label>
                        <?php echo ($row['it_item_type'] == '0' ? '제품' : '리스'); ?>
                    </td>
                    <td class="th_qty grid_6">
                        <?php echo $row['ca_name1'] ?>
                        <?php echo ($row['ca_name2'] ? ' > ' . $row['ca_name2'] : ''); ?>
                        <?php echo ($row['ca_name3'] ? ' > ' . $row['ca_name3'] : ''); ?>
                    </td>
                    <td headers="th_pc_title" class="td_input " style="text-align: left;cursor: pointer;">
                        <label for="name_<?php echo $i; ?>" class="sound_only">상품명</label>
                        <?php echo get_it_image($row['it_id'], 50, 50); ?>
                        [<?= $row['it_brand'] ?>] <?php echo htmlspecialchars2(cut_str($row['it_name'], 250, "")); ?>
                    </td>
                    <td headers="th_amt" class="td_numbig td_input grid_4">
                        <label for="price_<?php echo $i; ?>" class="sound_only">최종판매가</label>
                        <?php echo number_format($row['it_price']); ?>
                    </td>
                    <td class="td_input grid_1">
                        <label for="use_<?php echo $i; ?>" class="sound_only">진열상태</label>
                        <?php echo ($row['it_use'] ? '진열' : '진열안함'); ?>
                    </td>
                    <td class="td_input grid_1">
                        <label for="use_<?php echo $i; ?>" class="sound_only">품절</label>
                        <?php echo ($row['it_soldout'] ? 'Y' : 'N'); ?>
                    </td>
                </tr>
            <?php
            }
            
            if ($i == 0) {
            ?>
                <tr>
                    <td colspan="8">검색되는 상품이 없습니다.</td>
                </tr>
            <?php } ?>

    </tbody>
    </table>
</div>

<button type="submit" class="btn frm_input" >저장</button>

</form>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

</div></div></div>


<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품추가</h4>
            </div>

            <div class="modal-body">

                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_4">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="row"><label>제품분류</label></th>
                                <td>
                                <select id="ca_id">
                                    <option value="">상품카테고리 전체분류</option>
                                    <option value="1010">이불</option>
                                    <option value="1020">베개/패드/토퍼</option>
                                    <option value="1030">침구커버</option>
                                    <option value="1040">홈데코</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>전열상태</label></th>
                                <td>
                                    <label><input type="radio" value="" id="it_use" name="psc_it_use" <?php echo ($psc_it_use == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="1" id="it_use1" name="psc_it_use" <?php echo ($psc_it_use == '1')?'checked':''; ?>> 진열함</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="0" id="it_use0" name="psc_it_use" <?php echo ($psc_it_use == '0')?'checked':''; ?>> 진열안함 </label>&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>재고상태</label></th>
                                <td>
                                    <label><input type="radio" value="" id="it_use" name="psc_it_soldout" <?php echo ($psc_it_soldout == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="0" id="it_use1" name="psc_it_soldout" <?php echo ($psc_it_soldout == '0')?'checked':''; ?>> 재고있음</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" value="1" id="it_use0" name="psc_it_soldout" <?php echo ($psc_it_soldout == '1')?'checked':''; ?>> 재고없음(품절) </label>&nbsp;&nbsp;&nbsp;
                                </td>
                            </tr>

                            
                            <tr>
                                <th scope="row"><label>상품번호/상품명/sap코드</label></th>
                                <td>
                                    <input type="text" name="stx" id="stx" value="" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    <button type="button" class="btn btn-success" id="btnSearch">검색</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form name="procForm" id="procForm" method="post">
                    <div class="tbl_frm01 tbl_wrap" id="tblProduct" style="overflow-y: scroll;height: 500px;">
                        <? include_once(G5_ADMIN_URL . '/design/best_component_itemsearch.php'); ?>
                    </div>
                </form>

                <div style="text-align: right;">
                    <button type="button" class="btn btn-success" id="btnProductSubmit">추가</button>
                </div>

                <div class="x_title">
                    <h5><span class="fa fa-check-square"></span> 선택된 지정상품</h5>
                    <div style="text-align: right;">
                        <input type="button" class="btn btn-danger" value="삭제" id="btnProductDel" />
                    </div>
                </div>

                <form name="procForm1" id="procForm1" method="post">
                    <div class="tbl_frm01 tbl_wrap" id="tblProductForm">
                    
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <br><br><br>
                <button type="button" class="btn btn-default" data-dismiss="modal" id ="btnProductComform">적용</button>
            </div>
        </div>
    </div>
</div>
<script>
var CpItemIndex = 0;
function fitemlist_submit(f)
{
    return true;
}
function changeSort(elem, action) {
    const value = $(elem).parent().data("value");
    const $rows = $("#bodylist>tr>td.it_id_sort");
    const $sort_val =Number($(elem).closest('tr').children('.sort_td').children('input').val());
    let $current, targetIdx , changeIdx;
    
    $rows.each(function(idx, elem) {
        if ($(elem).data("value") == value) {
            targetIdx = action == 'up' ? idx - 1 : idx + 1;
            $current = $(elem).parent();
            //$(elem).parent().children('.sort_td').children('input').val(targetIdx);
        }
    });

    if (targetIdx >= 0 && targetIdx < $rows.length) {
        
        $rows.each(function(idx, elem) {
            if (idx == targetIdx) {
                if (action == 'up') {
                    changeIdx = $sort_val - 1;
                    $(elem).parent().before($current);
                    $(elem).closest('tr').children('.sort_td').children('input').val($sort_val);
                    $(elem).closest('tr').children('.sort_td').children('input').attr("name","sort["+($sort_val-1)+"]");
                    $(elem).closest('tr').children('.bit_id').children('input').attr("name","bit_id["+($sort_val-1)+"]");
                    $(elem).closest('tr').prev().children('.sort_td').children('input').val(changeIdx);
                    $(elem).closest('tr').prev().children('.sort_td').children('input').attr("name","sort["+(changeIdx-1)+"]");
                    $(elem).closest('tr').prev().children('.bit_id').children('input').attr("name","bit_id["+(changeIdx-1)+"]");
                } else {
                    changeIdx = $sort_val + 1;
                    $(elem).parent().after($current);
                    $(elem).closest('tr').children('.sort_td').children('input').val($sort_val);
                    $(elem).closest('tr').children('.sort_td').children('input').attr("name","sort["+($sort_val-1)+"]");
                    $(elem).closest('tr').children('.bit_id').children('input').attr("name","bit_id["+($sort_val-1)+"]");
                    $(elem).closest('tr').next().children('.sort_td').children('input').val(changeIdx);
                    $(elem).closest('tr').next().children('.sort_td').children('input').attr("name","sort["+(changeIdx-1)+"]");
                    $(elem).closest('tr').next().children('.bit_id').children('input').attr("name","bit_id["+(changeIdx-1)+"]");
                }
            }
        });
        


        let values = [];
        $("#bodylist>tr>td.it_id_sort").each(function(idx, elem) {
            values.push($(elem).data("value"));
        });
        $("#it_id_list").val(values.join(','));
    }
}



function LoadingWithMask() {
    //화면의 높이와 너비를 구합니다.
    var maskHeight = $(document).height();
    var maskWidth  = window.document.body.clientWidth;
     
    //화면에 출력할 마스크를 설정해줍니다.
    var mask       ="<div id='mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'></div>";
    var loadingImg ='';
      
    loadingImg +=" <img src='/img/re/Spinner.gif' style='position: relative; display: block; margin: 20% auto;'/>";
 
    //화면에 레이어 추가
    $('body')
        .append(mask)
 
    //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채웁니다.
    $('#mask').css({
            'width' : maskWidth,
            'height': maskHeight,
            'opacity' :'0.3'
    });
  
    //마스크 표시
    $('#mask').show();
  
    //로딩중 이미지 표시
    $('#mask').append(loadingImg);
    $('#loadingImg').show();



}

function closeLoadingWithMask() {
    $('#mask, #loadingImg').hide();
    $('#mask, #loadingImg').remove(); 
}



function tblProductFormBind() {
    var $table = $("#tblProductForm");
    $.post(
        "<?= G5_ADMIN_URL ?>/design/best_component_itemsearch.php", {
            w: "u",
            it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
        },
        function(data) {
            $table.empty().html(data);
            
        }
    );
};

function tblProductFormBind2() {
    // var $table = $("#bodylist").children('tr').children('#best_gubun_id').val();
    
    var $dataRow = $("#tblProductForm #tbodyProduct").children('tr').clone();
    
    $('#bodylist').append($dataRow);
    
};

function open_item_modal(){
    $('#coupon_product_modal').modal('show');
}

function openCpItemPopup(elem) {
    const id = $(elem).attr("target-data");
    CpItemIndex = $(elem).data("item-idx");

    tblProductFormBind();
    $('#' + id).modal('show');
}

$(function() {

    $('#best_ca').change(function (){
        
        $('#bs_category').val(this.value);
    });
    
    $("#btnSearch").click(function(event) {
        var $table = $("#tblProduct");
        LoadingWithMask();
        $.post(
            "<?= G5_ADMIN_URL ?>/design/best_component_itemsearch.php", {
                ca_id: $("#ca_id").val(),
                stx: $("#stx").val(),
                psc_it_use : $("input[name ='psc_it_use']:checked").val(),
                psc_it_soldout : $("input[name ='psc_it_soldout']:checked").val(),
                not_it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
            },
            function(data) {
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


    $("#btnProductDel2").click(function(event) {
        if (!is_checked("bchk[]")) {
            alert("삭제 하실 항목을 하나 이상 선택하세요.");
            return false;
        }
        LoadingWithMask();
        if (confirm("삭제하시겠습니까?")) {

            var $chk = $("input[name='bchk[]']");
            var $it_id = new Array();

            for (var i = 0; i < $chk.size(); i++) {
                if ($($chk[i]).is(':checked')) {
                    var k = $($chk[i]).val();
                    $it_id.push($("input[name='bit_id[" + k + "]']").val());
                }
            }

            $("#cp_item_set_item_del").val($it_id.join(","));

            $.post(
                "<?= G5_ADMIN_URL ?>/shop_admin/best.listupdate.php", {
                    w: "delete",
                    bs_category : $('#bs_category').val(),
                    it_id_list: $("#cp_item_set_item_del").val(),
                },
                function(data) {
                    $('#flistSearch').submit();
                    
                }
            );
            
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

    $("#btnProductComform").click(function(event) {
        //tblProductFormBind2();

        $.post(
        "<?= G5_ADMIN_URL ?>/shop_admin/best.listupdate.php", {
            w: "add",
            bs_category : $('#bs_category').val(),
            it_id_list: $("#cp_item_set_item_" + CpItemIndex).val(),
        },
        function(data) {
            // tblProductFormBind2();
            $('#flistSearch').submit();
            
        }
    );


    });
    
	$("#excel_download1, #excel_download2").click(function(){
		var $form = $('<form></form>');     
		$form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.php');
	    $form.attr('method', 'post');
	    $form.appendTo('body');
	     
	    var exceldata = $('<input type="hidden" value="<?=$excel_sql?>" name="exceldata">');
	    var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
	    var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
	    $form.append(exceldata).append(headerdata).append(bodydata);
	    $form.submit();
	});

	$("#btn_it_use0, #btn_it_use0_1").click(function(){
		if (!is_checked("chk[]")) {
	        alert("진열안함으로 수정 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }

		$("#act").val("it_use0");
		$("#fitemlistupdate").submit();
	});

	$("#btn_it_use1, #btn_it_use1_1").click(function(){
		if (!is_checked("chk[]")) {
	        alert("진열함으로 수정 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }

		$("#act").val("it_use1");
		$("#fitemlistupdate").submit();
	});
	
	
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
			$('#it_time').val("");
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

	
    $(".itemcopy").click(function() {
        var href = $(this).attr("href");
        window.open(href, "copywin", "left=100, top=100, width=300, height=200, scrollbars=0");
        return false;
    });
    window.addEventListener("keydown", (e) => {
        if (e.keyCode == 13) {
          	document.getElementById('flistSearch').submit();
        }
    })
});
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
