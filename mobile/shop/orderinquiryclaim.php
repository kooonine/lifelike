<?php
include_once('./_common.php');


define("_ORDERINQUIRY_", true);

$od_pwd = get_encrypt_string($od_pwd);

// 회원인 경우
if ($is_member)
{
    $sql_common = " from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";
    
    $od_stime=date_create(G5_TIME_YMD);
    date_add($od_stime, date_interval_create_from_date_string('-1 months'));
    $od_stime = date_format($od_stime,"Y-m-d");
    
    $od_etime = G5_TIME_YMD;
}
else // 그렇지 않다면 로그인으로 가기
{
    goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_SHOP_URL.'/orderinquiryclaim.php'));
}


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$sql .= " and od_status_claim in ('주문취소','교환','반품','철회','해지') ";

if(isset($od_status_claim) && $od_status_claim != "") $sql .= " and od_status_claim = '{$od_status_claim}' ";
if(isset($od_stime) && $od_stime != "") $sql .= " and od_time >= '{$od_stime}' ";
if(isset($od_etime) && $od_etime != "") $sql .= " and od_time <= '{$od_etime} 23:59:59' ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면
if ($total_count == 0)
{
    if (!$is_member) // 회원일 경우는 메인으로 이동
        alert('주문이 존재하지 않습니다.');
}

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


// 비회원 주문확인의 경우 바로 주문서 상세조회로 이동
$g5['title'] = '전체 취소/교환/반품/해지 내역';
include_once(G5_MSHOP_PATH.'/_head.php');
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>전체 취소/교환/반품/해지 내역</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<!-- //lnb -->
<div class="content mypage sub">
        
		<div class="grid">
			<div class="tab_cont_wrap">
				<div class="tab">
                <ul class="type3 onoff">
                    	<li <?php echo ($od_status_claim=="")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiryclaim.php?od_status_claim=';"><a href="#"><span>전체</span></a></li>
                    	<li <?php echo ($od_status_claim=="주문취소")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiryclaim.php?od_status_claim=주문취소';"><a href="#"><span>취소</span></a></li>
                    	<li <?php echo ($od_status_claim=="교환")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiryclaim.php?od_status_claim=교환';"><a href="#"><span>교환</span></a></li>
                    	<li <?php echo ($od_status_claim=="반품")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiryclaim.php?od_status_claim=반품';"><a href="#"><span>반품</span></a></li>
                    	<li <?php echo ($od_status_claim=="철회")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiryclaim.php?od_status_claim=철회';"><a href="#"><span>철회</span></a></li>
                    	<li <?php echo ($od_status_claim=="해지")?'class="on"':'' ?> onclick="location.href='<?php echo G5_SHOP_URL ?>/orderinquiryclaim.php?od_status_claim=해지';"><a href="#"><span>해지</span></a></li>
					</ul>
				</div>
				
				<div class="tab_cont">
					<!-- tab 1 -->
					<div class="tab_inner">
						<p class="ico_import">최근 1개월간 내역입니다.</p>
						<p class="txt_total">총<strong><?php echo $total_count; ?></strong>건</p>
						<div class="orderwrap">
                            
    <?php
    
    $is_claim = "1";
    $limit = " limit $from_record, $rows ";
    include G5_MSHOP_PATH.'/orderinquiry.sub.php';
    ?>

    <?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
    <!-- } 주문 내역 끝 -->
    					</div>
					</div>
				</div>
			</div>
		</div>
</div>
<script>
$(function(){

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		$('#dateBtn').val(d);
		
		var startD = new Date();
		var endD = new Date();
        
		if(d == "3d") {
			startD.setDate(startD.getDate()-3);
		} else if(d == "1w") {
			startD.setDate(startD.getDate()-7);
		} else if(d == "1m") {
			startD.setMonth(startD.getMonth() - 1);
		} else if(d == "3m") {
			startD.setMonth(startD.getMonth() - 3);
		} else if(d == "6m") {
			startD.setMonth(startD.getMonth() - 6);
		}  else if(d == "1y") {
			startD.setMonth(startD.getMonth() - 12);
		}

		$("input[name=od_stime]").val(date_format(startD, "yyyy-MM-dd"));
		$("input[name=od_etime]").val(date_format(endD, "yyyy-MM-dd"));
		//$('#sc_od_time').data('daterangepicker').setStartDate(startD);
		//$('#sc_od_time').data('daterangepicker').setEndDate(endD);
	
	});
});
</script>
<?php
include_once('./_tail.php');
?>

