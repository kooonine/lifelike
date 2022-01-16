<?php
//$sub_menu = "100610";
$sub_menu = "10";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$sql_common = " from lt_admin a left join {$g5['member_table']} b on (a.mb_id=b.mb_id) ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}


if ($sc_ad_type != "") {
    $sql_search .= " and ad_type = '{$sc_ad_type}' ";
}

if ($sc_ad_del != "") {
    $sql_search .= " and ad_del = '{$sc_ad_del}' ";
}

if ($sc_ad_reg_datetime != "") {
    $ad_reg_datetimes = explode("~", $sc_ad_reg_datetime);
    $sql_search .= " and ad_reg_datetime between '".trim($ad_reg_datetimes[0])."' and '".trim($ad_reg_datetimes[1])."' ";
}

if (!$sst) {
    $sst  = "a.ad_reg_datetime";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$g5['title'] = "관리자 계정관리";
include_once('./admin.head.php');

$colspan = 10;

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;sc_ad_del='.$sc_ad_del.'&amp;sc_ad_type='.$sc_ad_type.'&amp;sc_ad_reg_datetime='.$sc_ad_reg_datetime;
?>


<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
    <form name="flist" id="flistSearch" class="local_sch01 local_sch">
    
	<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>
    <tr>
        <th scope="row" style="width:15%;">상태</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="sc_ad_del" name="sc_ad_del" <?php echo ($sc_ad_del == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="sc_ad_del0" name="sc_ad_del" <?php echo ($sc_ad_del == '0')?'checked':''; ?>> 정상</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="sc_ad_del1" name="sc_ad_del" <?php echo ($sc_ad_del == '1')?'checked':''; ?>> 삭제</label>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">관리자구분</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="sc_ad_type" name="sc_ad_type" <?php echo ($sc_ad_type == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="super" id="sc_ad_type1" name="sc_ad_type" <?php echo ($sc_ad_type == 'super')?'checked':''; ?>> 슈퍼관리자</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="admin" id="sc_ad_type2" name="sc_ad_type" <?php echo ($sc_ad_type == 'admin')?'checked':''; ?>> 일반관리자</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="brand" id="sc_ad_type3" name="sc_ad_type" <?php echo ($sc_ad_type == 'brand')?'checked':''; ?>> 입점몰관리자</label>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">등록일</th>
		<td colspan="2">
            	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                	<input type='text' class="form-control" id="sc_ad_reg_datetime" name="sc_ad_reg_datetime" value=""/>
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
        <th scope="row">상태</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl">
                    <option value="mb_company" <?php echo get_selected($sfl, 'mb_company'); ?>>회사</option>
                    <option value="mb_dept" <?php echo get_selected($sfl, 'mb_dept'); ?>>부서</option>
                    <option value="mb_name" <?php echo get_selected($sfl, 'mb_name'); ?>>성명</option>
                    <option value="mb_title" <?php echo get_selected($sfl, 'mb_title'); ?>>직위</option>
                    <option value="a.mb_id" <?php echo get_selected($sfl, 'a.mb_id'); ?>>아이디</option>
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
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
	
	</form>
  	</div>
  	
  	<div class="x_panel">
      	
    <form name="fitemlistupdate" id="fitemlistupdate" method="post" action="./admin_list_delete.php" onsubmit="return fitemlist_submit(this);" autocomplete="off" >
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="sst" value="<?php echo $sst; ?>">
    <input type="hidden" name="sod" value="<?php echo $sod; ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    
    <input type="hidden" name="sc_ad_del" value="<?php echo $sc_ad_del; ?>">
    <input type="hidden" name="sc_ad_type" value="<?php echo $sc_ad_type; ?>">
    <input type="hidden" name="sc_ad_reg_datetime" value="<?php echo $sc_ad_reg_datetime; ?>">
    
	<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
      <th colspan="10">
        <div class="pull-right">
          <input type="submit" class="btn btn-danger" id="btn_del" value="삭제"></input>
        </div>
      </th>
    </tr>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col"><?php echo subject_sort_link('mb_company', 'sca='.$sca); ?>회사</a></th>
        <th scope="col"><?php echo subject_sort_link('mb_dept', 'sca='.$sca); ?>부서</a></th>
        <th scope="col"><?php echo subject_sort_link('mb_name', 'sca='.$sca); ?>성명</a></th>
        <th scope="col"><?php echo subject_sort_link('mb_title', 'sca='.$sca); ?>직위</a></th>
        <th scope="col"><?php echo subject_sort_link('mb_id', 'sca='.$sca); ?>아이디</a></th>
        <th scope="col"><?php echo subject_sort_link('ad_type', 'sca='.$sca); ?>관리자</a></th>
        <th scope="col"><?php echo subject_sort_link('ad_reg_datetime', 'sca='.$sca); ?>등록일</a></th>
        <th scope="col"><?php echo subject_sort_link('ad_del', 'sca='.$sca); ?>상태</a></th>
        <th scope="col">관리</th>
	</tr>
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
        </td>
        <td class="td_sort grid_2">
            <label for="mb_company_<?php echo $i; ?>" class="sound_only">회사</label>
            <?php echo $row['mb_company']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="mb_dept_<?php echo $i; ?>" class="sound_only">부서</label>
            <?php echo $row['mb_dept']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="mb_name_<?php echo $i; ?>" class="sound_only">성명</label>
            <?php echo $row['mb_name']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="mb_title_<?php echo $i; ?>" class="sound_only">직위</label>
            <?php echo $row['mb_title']; ?>
        </td>
        <td class="td_num grid_2" style="cursor: pointer;" onclick='location.href="./admin_form.php?w=u&amp;mb_id=<?php echo $row['mb_id']; ?>&amp;<?php echo $qstr; ?>"' >
            <label for="mb_id_<?php echo $i; ?>" class="sound_only">아이디</label>
            <input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
            <?php echo $row['mb_id']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="ad_type_<?php echo $i; ?>" class="sound_only">관리자</label>
            <?php 
                if($row['ad_type'] == "super") echo '슈퍼관리자';
                else if($row['ad_type'] == "admin") echo '일반관리자';
                else if($row['ad_type'] == "brand") echo '입점몰관리자';
            ?>
        </td>
        <td class="td_sort grid_2">
            <label for="ad_reg_datetime_<?php echo $i; ?>" class="sound_only">등록일</label>
            <?php echo $row['ad_reg_datetime']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="ad_reg_datetime_<?php echo $i; ?>" class="sound_only">상태</label>
            <?php echo ($row['ad_del'])?'삭제':'정상' ; ?>
        </td>
        <td class="td_mng td_mng_s">
            <a href="./admin_form.php?w=u&amp;mb_id=<?php echo $row['mb_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03"><span class="sound_only"><?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?> </span>수정</a>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="10" class="empty_table">자료가 한건도 없습니다.</td></tr>';
    ?>
    </tbody>
    <thead>
    <tr>
      <th colspan="10">
        <div class="pull-right">
          <input type="submit" class="btn btn-danger" id="btn_del" value="삭제"></input>
        </div>
      </th>
    </tr>
    </thead>
    </table>
	</div>
	</form>

	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
				<a href="./admin_form.php" class="btn btn_02" >관리자 등록</a>
			</div>
		  </div>
	  </div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

</div></div></div>

<script>


$(function() {

    
    $('#sc_ad_reg_datetime').daterangepicker({
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
    });
    $('#sc_ad_reg_datetime').val("<?php echo $sc_ad_reg_datetime ?>");

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		if(d == "all") {
			$('#sc_ad_reg_datetime').val("");
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
    
    		$('#sc_ad_reg_datetime').data('daterangepicker').setStartDate(startD);
    		$('#sc_ad_reg_datetime').data('daterangepicker').setEndDate(endD);
		}
	
	});

    window.addEventListener("keydown", (e) => {
        if (e.keyCode == 13) {
            document.getElementById('flistSearch').submit();
        }
    })
});

function fitemlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert("삭제 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
        return false;
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
