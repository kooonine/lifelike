<?php
$sub_menu = "100220";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = 'IP접속제한관리';
include_once ('./admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

$sql_common = " from    lt_ipblock a
                        left outer join lt_member b
                        on a.ib_mb_id = b.mb_id
                        inner join lt_member c
                        on a.ib_admin_id = c.mb_id
                    ";

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
    $sst  = "ib_datetime";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";

$sql  = " select a.*, b.mb_name, c.mb_name as admin_name
           $sql_common
           $sql_order
           limit $from_record, $rows ";
$result = sql_query($sql);

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;


$token = get_admin_token();
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> IP접속 제한 리스트<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	<form name="flist" id="flistSearch" class="local_sch01 local_sch">
	<div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        
        </div>
        <div class="pull-right">
			<button class="btn btn_02" type="button" id="btnNew">IP차단 등록</button>
            <select name="page_rows" onchange="$('#flistSearch').submit();">
                <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
                <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
                <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
            </select>
        </div>
	</div>
	</form>

	<div class="tbl_frm01 tbl_wrap">
	<table>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('ib_no', 'sca='.$sca); ?>번호</a></th>
        <th scope="col"><?php echo subject_sort_link('ib_mb_id', 'sca='.$sca); ?>차단 대상 ID</a></th>
        <th scope="col"><?php echo subject_sort_link('b.mb_name', 'sca='.$sca); ?>차단 대상 성명</a></th>
        <th scope="col"><?php echo subject_sort_link('ib_intercept_ip', 'sca='.$sca); ?>차단 IP</a></th>
        <th scope="col"><?php echo subject_sort_link('c.mb_name', 'sca='.$sca); ?>관리자 성명</a></th>
        <th scope="col"><?php echo subject_sort_link('ib_datetime', 'sca='.$sca); ?>접속 제한 일시</a></th>
        <th scope="col">비고</th>
	</tr>
    </thead>
    
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $bg = 'bg'.($i%2);

        $it_point = $row['it_point'];
        if($row['it_point_type'])
            $it_point .= '%';
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_num grid_2">
            <?php echo $i+1+$from_record; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="ib_mb_id<?php echo $i; ?>" class="sound_only">ID</label>
            <?php echo $row['ib_mb_id']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="mb_name<?php echo $i; ?>" class="sound_only">성명</label>
            <?php echo $row['mb_name']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="ib_intercept_ip<?php echo $i; ?>" class="sound_only">IP</label>
            <?php echo $row['ib_intercept_ip']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="admin_name<?php echo $i; ?>" class="sound_only">관리자 성명</label>
            <?php echo $row['admin_name']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="ib_datetime<?php echo $i; ?>" class="sound_only">접속 제한 일시</label>
            <?php echo $row['ib_datetime']; ?>
        </td>
        <td class="td_mng td_mng_s">
            <a href="./configform_ip_update.php?w=d&amp;ib_no=<?php echo $row['ib_no']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_03">접속제한해제</a>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    
    </table>
	</div>
	
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
	
	
	</div>
  </div>
</div>


<div id="modal_form" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
<form name="fupdate" id="fupdate" method="post" action="./configform_ip_update.php" onsubmit="return fupdate_submit(this);" autocomplete="off" >
<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
<input type="hidden" name="ib_intercept_ip" value="" id="ib_intercept_ip">

	<div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title" id="modalLabel">접속 차단 IP</h4>
        	</div>
        	<div class="modal-body">
				<div class="tbl_frm01 tbl_wrap">
					<table>
                    <colgroup>
                    	<col width="20%">
                    	<col width="80%">
                    </colgroup>
                    <tbody>
                      <tr>
                      	<th rowspan="2">설정</th>
                      	<td>
                      		<input type="number" min='0' max='255' id="ib_intercept_ip1" minlength="1" maxlength="3" size="3" class="frm_input" required="required">&nbsp;.&nbsp;
                      		<input type="number" min='0' max='255' id="ib_intercept_ip2" minlength="1" maxlength="3" size="3" class="frm_input">&nbsp;.&nbsp;
                      		<input type="number" min='0' max='255' id="ib_intercept_ip3" minlength="1" maxlength="3" size="3" class="frm_input">&nbsp;.&nbsp;
                      		<input type="number" min='0' max='255' id="ib_intercept_ip4" minlength="1" maxlength="3" size="3" class="frm_input">
                      	</td>
                      </tr>
                      <tr>
                      	<td>
                      		※ 대역차단 예시 <br/>
                      		111. 111. 111.[공란] 입력 시 111. 111. 111. 0~255 대역이 모두 차단됨
                      	
                      	</td>
                      </tr>
					</tbody>
					</table>
				</div>
			</div>
            <div class="modal-footer">
            	<button type="submit" class="btn btn-primary" id="btnSave">저장</button>
              	<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
		</div>
	</div>
</form>
</div>





<script>
$(function(){

	$("#ib_intercept_ip1").keyup(function(){
		if($("#ib_intercept_ip1").val().length >= 3) $("#ib_intercept_ip2").focus();
	});

	$("#ib_intercept_ip2").keyup(function(){
		if($("#ib_intercept_ip2").val().length >= 3) $("#ib_intercept_ip3").focus();
	});

	$("#ib_intercept_ip3").keyup(function(){
		if($("#ib_intercept_ip3").val().length >= 3) $("#ib_intercept_ip4").focus();
	});

	$("#btnNew").click(function(){
		$("#ib_intercept_ip1").val("");
		$("#ib_intercept_ip2").val("");
		$("#ib_intercept_ip3").val("");
		$("#ib_intercept_ip4").val("");
		$("#modal_form").modal('show');
	});

	$('#modal_form').on('shown.bs.modal', function () {
		
	    $('#ib_intercept_ip1').focus();
	})
});

function fupdate_submit(f)
{
	var ib_intercept_ip = "";

	var blank = false;

	if($('#ib_intercept_ip4').val() == "") {
		ib_intercept_ip = ".+";
		blank = true;
	} else {
		ib_intercept_ip = "."+$('#ib_intercept_ip4').val();
	}

	if($('#ib_intercept_ip3').val() == "") {
		ib_intercept_ip = ".+"+ib_intercept_ip;
		blank = true;		
	} else if($('#ib_intercept_ip3').val() != "") {
		ib_intercept_ip = "."+$('#ib_intercept_ip3').val()+ib_intercept_ip;
	}
	
	if($('#ib_intercept_ip2').val() == "") {
		ib_intercept_ip = ".+"+ib_intercept_ip;
		blank = true;		
	} else if($('#ib_intercept_ip2').val() != "") {
		ib_intercept_ip = "."+$('#ib_intercept_ip2').val()+ib_intercept_ip;
	}
	
	console.log(ib_intercept_ip + ":"+ blank);
	
	ib_intercept_ip = $('#ib_intercept_ip1').val()+ib_intercept_ip;
	
	$('#ib_intercept_ip').val(ib_intercept_ip);
	
	
    return true;
}

</script>

<?php
include_once ('./admin.tail.php');
?>
