<?php
//$sub_menu = '300300';
$sub_menu = '30';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$g5['title'] = '상품관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

// 분류
$ca_list  = '<option value="">선택</option>'.PHP_EOL;
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
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
        $sql_search .= " $where a.it_id in (select it_id from lt_shop_item_sub where its_sap_code like '%$stx%' )";
        $where = " and ";
    }else if ($sfl != "") {
        $sql_search .= " $where a.$sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($sca != "") {
    $sql_search .= " $where (a.ca_id like '$sca%' or a.ca_id2 like '$sca%') ";
}

if ($sc_it_use != "") {
    $sql_search .= " and it_use = '{$sc_it_use}' ";
    if ($sc_it_use == 1) $qstr  = "sc_it_use=1&" .$qstr;
    else $qstr  = "sc_it_use=0&" .$qstr;
}

if ($sc_it_soldout == 1) {
    $sql_search .= " and (a.it_soldout = 0 and e.io_stock_qty > 0 ) ";
    $qstr  = "sc_it_soldout=1&" .$qstr;
} else if ($sc_it_soldout == 0 && $sc_it_soldout != NULL) {
    $sql_search .= " and (a.it_soldout = 1 or e.io_stock_qty < 1 ) ";
    $qstr  = "sc_it_soldout=0&" .$qstr;
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

$sql_common = " from {$g5['g5_shop_item_table']} a 
                    LEFT JOIN {$g5['g5_shop_item_option_table']} AS e ON a.it_id = e.it_id,
                     {$g5['g5_shop_category_table']} b,
                     {$g5['g5_shop_category_table']} c,
                     {$g5['g5_shop_category_table']} d
               where (b.ca_id = left(a.ca_id,2)
                    and   c.ca_id = left(a.ca_id,4)
                    and   d.ca_id = left(a.ca_id,6)
                    and   a.ca_id3 = ''
                    ";
                   
// if ($is_admin != 'super')
// $sql_common .= " and b.ca_mb_id = '{$member['mb_id']}'";
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
$sst2  = "it_order, it_id";
$sod2 = "desc";
$sql_order = "order by $sst $sod, $sst2 $sod2";
$sql_common = " from {$g5['g5_shop_item_table']} a 
                    LEFT JOIN {$g5['g5_shop_item_option_table']} AS e ON a.it_id = e.it_id,
                     {$g5['g5_shop_category_table']} b,
                     {$g5['g5_shop_category_table']} c,
                     {$g5['g5_shop_category_table']} d
               where (b.ca_id = left(a.ca_id,2)
                    and   c.ca_id = left(a.ca_id,4)
                    and   d.ca_id = left(a.ca_id,6)
                    and   a.ca_id3 = ''
                    ";
$sql_common .= ") ";
$sql_common .= $sql_search;
$sql_group = "group by a.it_id";        
$sql  = " select b.ca_name as ca_name1, c.ca_name as ca_name2, d.ca_name as ca_name3, a.*,e.io_order_no AS sap_code,e.io_sapcode_color_gz AS io_sapcode_color_gz,e.io_stock_qty AS io_stock_qty,e.io_hoching AS io_hoching
				 , if(a.it_use = '1','진열','진열안함') as it_use_name
				 , if(a.it_soldout = '1','Y','N') as it_soldout_name
           $sql_common
           $sql_group
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

// 일단 이거 2개 수정 해야함
$headers = array('NO','it_id','category','사이즈','시즌','충전재','스타일','패브릭','it_name','model','brand','code','search','제조사','제조국','sell_price','tag_price','size','it_img1','it_img2','it_img3','it_img4','it_img5','it_explan','it_explain2','제품소재 및 충전재','색상','사이즈','제조사','제조국','세탁방법 및 주의사항','제품구성','품질보증기준','A/S 책임자와 전화번호');
$headers2 = array('NO','it_id','자사몰 카테고리 구분','필터값설정 (사이즈)','필터값설정 (시즌)','필터값설정 (충전재)','필터값설정 (스타일)','필터값설정 (패브릭)','상품명','모델명','브랜드명','자체상품코드','사이트검색어','제조사','제조국','판매가','TAG가','옵션상세명칭(2)','대표이미지','종합몰(JPG)이미지','부가이미지2','부가이미지3','부가이미지4','상품상세설명','추가 상품상세설명_1','속성값3','속성값4','속성값5','속성값6','속성값7','속성값8','속성값9','속성값10','속성값11');
$bodys = array('NO','it_id','ca_id','f_size','f_season','f_filling','f_style','f_fabric','it_name','sap_code','it_brand','io_sapcode_color_gz','it_search_word','it_maker','it_origin','its_final_price','its_price','io_hoching','it_img1','it_img2','it_img3','it_img4','it_img5','it_explan','it_explan2','value1','value2','value3','value4','value5','value6','value7','value8','value9');


// $headers = array('NO', '상품코드','sap코드', '등록일','수정일', '분류', '카테고리', '상품명', '품목수량', '최종판매가','최종월리스료', '진열상태', '품절');
// $headers2 = array('it_id', '카테고리','필터값설정(사이즈)', '11','t', '', '', '상품명', 'aat', '','최종월리스료', '', '품절');
// $headers3 = array('test', '','', '11','t', '', '', '상품명', 'aat', '','최종월리스료', '', '품절');

// $bodys = array('it_id', 'it_id','sap_code', 'it_time','it_update_time',  'ca_name2', 'ca_name3', 'it_name', 'it_stock_qty', 'it_price', 'it_rental_price', 'it_use_name', 'it_soldout_name');

// -- 일단 이거 2개

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$headers2 = $enc->encrypt(json_encode_raw($headers2));
$bodys = $enc->encrypt(json_encode_raw($bodys));
   
$token = get_admin_token();
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
  	
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
        <th scope="row" style="width:15%;">검색분류</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<label for="sca" class="sound_only">상품카테고리 선택</label>
                <select name="sca" id="sca">
                    <option value="">상품카테고리 전체분류</option>
                    <?php
                    $sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
                    $result1 = sql_query($sql1);
                    for ($i=0; $row1=sql_fetch_array($result1); $i++) {
                        $len = strlen($row1['ca_id']) / 2 - 1;
                        $nbsp = '';
                        for ($i=0; $i<$len; $i++) $nbsp .= '&nbsp;&nbsp;&nbsp;';
                        echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
                    }
                    ?>
                </select>
                
            	<label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl">
                    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
                    <option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>상품코드</option>
                    <option value="its_sap_code" <?php echo get_selected($sfl, 'its_sap_code'); ?>>SAP코드</option>
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">상품등록일</th>
		<td colspan="2">
            	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                	<input type='text' class="form-control" id="it_time" name="sc_it_time" value=""/>
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
        <th scope="row">진열상태</th>
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
        <th scope="row">품절상태</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="it_use" name="sc_it_soldout" <?php echo ($sc_it_soldout == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="it_use1" name="sc_it_soldout" <?php echo ($sc_it_soldout == '1')?'checked':''; ?>> 재고있음</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="it_use0" name="sc_it_soldout" <?php echo ($sc_it_soldout == '0')?'checked':''; ?>> 품절(재고없음)</label>&nbsp;&nbsp;&nbsp;
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

<form name="fitemlistupdate" id="fitemlistupdate" method="post" action="./itemlistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off" >
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<input type="hidden" name="sc_it_use" value="<?php echo $sc_it_use; ?>">
<input type="hidden" name="sc_it_time" value="<?php echo $sc_it_time; ?>">

<!-- 이게 재고 같은거 !!!! -->
<input type="hidden" name="sc_it_soldout" value="<?php echo $sc_it_soldout; ?>">


<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
<input type="hidden" name="act" id="act" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
      <th colspan="12">
        <div class="pull-left">
          <input type="button" class="btn btn_02" id="btn_it_use1" value="진열함"></input>
          <input type="button" class="btn btn_02" id="btn_it_use0" value="진열안함"></input>
          <input type="button" class="btn btn_02" id="btn_it_use2" value="품절"></input>
          <!-- 품절은 개수로 또는 설정?? -->
        </div>
        <div class="pull-right">
          <input type="button" class="btn btn_02" id="excel_download1" value="엑셀다운로드"></input>
          <a href="./itemform0.php"><input type="button" class="btn btn_03" value="제품등록"></input></a>
          <a href="./itemform1.php"><input type="button" class="btn btn_03" value="리스등록"></input></a>
        </div>
      </th>
    </tr>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">상품 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>상품코드</a></th>
        <th scope="col"><?php echo subject_sort_link('it_time', 'sca='.$sca); ?>등록일</a></th>
        <th scope="col"><?php echo subject_sort_link('it_item_type', 'sca='.$sca); ?>분류</a></th>
        <th scope="col"><?php echo subject_sort_link('ca_name1', 'sca='.$sca); ?>카테고리</a></th>
        <th scope="col" colspan="2" id="th_pc_title"><?php echo subject_sort_link('it_name', 'sca='.$sca); ?>상품명</a></th>
        <th scope="col" id="th_qty"><?php echo subject_sort_link('it_stock_qty', 'sca='.$sca); ?>품목<br/>수량</a></th>
        <th scope="col" id="th_amt"><?php echo subject_sort_link('it_price', 'sca='.$sca); ?>최종판매가<br/>(최종월리스료)</a></th>
        <th scope="col"><?php echo subject_sort_link('it_use', 'sca='.$sca, 1); ?>진열<br/>상태</a></th>
        <th scope="col"><?php echo subject_sort_link('it_soldout', 'sca='.$sca, 1); ?>품절</a></th>
        <th scope="col">관리</th>
	</tr>
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['it_name']); ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
        </td>
        <td class="td_num grid_2">
            <input type="hidden" id ="it_id[<?php echo $i; ?>]" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
            <?php echo $row['it_id']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="it_time_<?php echo $i; ?>" class="sound_only">등록일</label>
            <?php echo $row['it_time']; ?>
            <?php echo "<br>".$row['it_update_time']; ?>
        </td>
        <td  class="td_sort grid_1">
            <label for="it_item_type_<?php echo $i; ?>" class="sound_only">분류</label>
            <?php echo ($row['it_item_type'] == '0' ? '제품' : '리스'); ?>
        </td>
        <td  class="th_qty grid_6">
            <?php echo $row['ca_name1'] ?>
            <?php echo ($row['ca_name2'] ? ' > '.$row['ca_name2'] : ''); ?>
            <?php echo ($row['ca_name3'] ? ' > '.$row['ca_name3'] : ''); ?>
        </td>
        <td class="td_img grid_2"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?></a></td>
        <td headers="th_pc_title" class="td_input " style="text-align: left;cursor: pointer;" 
        	onclick="location.href='./itemform<?php echo $row['it_item_type']?>.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>';">
            <label for="name_<?php echo $i; ?>" class="sound_only">상품명</label>
            <?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?>
        </td>
        <td headers="th_qty" class="td_numbig td_input grid_4">
            <label for="stock_qty_<?php echo $i; ?>" class="sound_only">품목수량</label>
            <!-- <?php echo number_format($row['it_stock_qty']); ?> -->
            <?php echo number_format($row['io_stock_qty']); ?>
        </td>
        <td headers="th_amt" class="td_numbig td_input grid_4">
            <label for="price_<?php echo $i; ?>" class="sound_only">최종판매가</label>
            <?php echo number_format($row['it_price']); ?>
            <?php if($row['it_item_type'] == '1') echo '('.number_format($row['it_rental_price']).')'; ?>
        </td>
        <td class="td_input grid_1">
            <label for="use_<?php echo $i; ?>" class="sound_only">진열상태</label>
            <?php echo ($row['it_use'] ? '진열' : '진열안함'); ?>
        </td>
        <td class="td_input grid_1">
            <label for="soldout_<?php echo $i; ?>" class="sound_only">품절</label>
            <!-- <?php echo $row['it_soldout'] ?> -->
            <?php echo ($row['it_soldout'] || $row['io_stock_qty'] ==0  ? 'Y' : 'N'); ?>
        </td>
        <td class="td_mng td_mng_s">
            <a href="./itemform<?php echo $row['it_item_type']?>.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;ca_id=<?php echo $row['ca_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>수정</a>
            <a href="<?php echo $href; ?>" class="btn btn_02" target="_blank"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>보기</a>
            <? if ($is_admin == 'super' || $member['mb_id'] == 'jeongwseong') { ?>
                <a onclick="itDel('<?php echo $row['it_id']; ?>')" class="btn btn_03" value =<?php echo $row['it_id']; ?>><span class="sound_only"></span>삭제</a>
            <?}?>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="12" class="empty_table">자료가 한건도 없습니다.</td></tr>';
    ?>
    </tbody>
    <thead>
    <tr>
      <th colspan="12">
        <div class="pull-left">
          <input type="button" class="btn btn_02" id="btn_it_use1_1" value="진열함"></input>
          <input type="button" class="btn btn_02" id="btn_it_use0_1" value="진열안함"></input>
          <input type="button" class="btn btn_02" id="btn_it_use2_1" value="품절"></input>
        </div>
        <div class="pull-right">
          <input type="button" class="btn btn_02" id="excel_download2" value="엑셀다운로드"></input>
          <a href="./itemform0.php"><input type="button" class="btn btn_03" value="제품등록"></input></a>
          <a href="./itemform1.php"><input type="button" class="btn btn_03" value="리스등록"></input></a>
        </div>
      </th>
    </tr>
    </thead>
    </table>

<!-- <div class="btn_confirm01 btn_confirm">
    <input type="submit" value="일괄수정" class="btn_submit" accesskey="s">
</div> -->
</form>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

</div></div></div>

<script>

function fitemlist_submit(f)
{
    return true;
}
function refreshMemList(){
	location.reload();
}
function itDel(e) {
    if (confirm("삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            url: "ajax.del_it.php",
            data: { it_id: e },
            cache: false,
            async: false,
            dataType: "json",
            success: function(data) {
                alert("삭제 성공"); 
                refreshMemList();
            },  
            complete : function() {
                alert("삭제 완료");    
                refreshMemList();
            }
        })
    }    
}
$(function() {
	$("#excel_download1, #excel_download2").click(function(){
        var $chk = $("input[name='chk[]']:checked");
        var $searchIt='';
        for (var i = 0; i < $chk.length; i++) {
            var getId = $($chk[i]).val();
            let itId = document.getElementById(`it_id[${getId}]`).value;
			if (i==0) $searchIt = $searchIt.concat(itId);
			else $searchIt = $searchIt.concat(`,${itId}`);
        }
		var $form = $('<form></form>');     
		$form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.php');
	    $form.attr('method', 'post');
	    $form.appendTo('body');
	    var exceldata = $('<input type="hidden" value="<?=$excel_sql?>" name="exceldata">');
        var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
        var headerdata2 = $('<input type="hidden" value="<?=$headers2?>" name="headerdata2">');
        var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
        var itemlistCheck = $('<input type="hidden" value=1 name="itemlistCheck">');
        var selectCheckIt = $(`<input type="hidden" value="${$searchIt}" name="selectCheckIt">`);

	    $form.append(exceldata).append(headerdata).append(bodydata).append(headerdata2).append(itemlistCheck).append(selectCheckIt);
	    $form.submit();
    });
    $("#btn_it_use2, #btn_it_use2_1").click(function(){
		if (!is_checked("chk[]")) {
	        alert("품절 처리 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }

		$("#act").val("it_use2");
		$("#fitemlistupdate").submit();
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
