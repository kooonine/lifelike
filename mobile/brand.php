<?php
include_once('./_common.php');

include_once(G5_MSHOP_PATH.'/_head.php');


$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?';
$sct_sort_href .= '&amp;sort=';

// 출력순서가 없다면
if ($sort == "")
{
    $sort='it_sum_qty';
    $sortodr='desc';
}

$sql_common = " from lt_member_company as a";
$sql_search = " where cp_status in ('승인완료','정보변경신청','정보변경반려','정보변경승인') ";

$sql_order = " order by {$sort} {$sortodr} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*
                ,(select sum(it_sum_qty) from lt_shop_item where ca_id3 = a.company_code ) as it_sum_qty
{$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);
?>

<!-- container -->
<div id="container">
	<div class="content magazine">
		<!-- 컨텐츠 시작 -->
		<div class="grid ">
			<h2 class="blind">브랜드</h2>
			<div class="tab_cont_wrap">
				<div class="tab">
					<ul class="type2">
						<li <?php echo ($sort == 'it_sum_qty')?'class="on"':'' ?> ><a href="<?php echo $sct_sort_href; ?>it_sum_qty&amp;sortodr=desc"><span>인기순</span></a></li>
						<li <?php echo ($sort == 'company_name')?'class="on"':'' ?> ><a href="<?php echo $sct_sort_href; ?>company_name&amp;sortodr=asc"><span>이름순</span></a></li>
					</ul>
				</div>
				<div class="tab_cont">
					<!-- 모집마감순 -->
					<div class="tab_inner">
						<div class="item_row_list">
							<ul class="count2">
                            <?php
                            for ($i=0; $row=sql_fetch_array($result); $i++) {
                                
                                $icon_file = G5_DATA_PATH.'/company/'.$row['mb_id'].'/'.$row['mb_id'].'_company_img.gif';
                            ?>
								<li>
									<a href="<?php echo G5_SHOP_URL ?>/list.php?ca_id=40&company_code=<?php echo $row['company_code']?>">
										<div class="photo"><?php 
										if (file_exists($icon_file)) {
										    $icon_url = G5_DATA_URL.'/company/'.$row['mb_id'].'/'.$row['mb_id'].'_company_img.gif';
										    echo '<img src="'.$icon_url.'" alt="">';
										} else {
										    echo '<img src="'.G5_SHOP_URL.'/img/no_image.gif" alt="">';
										}
										?></div>
										<div class="cont">
											<strong class="title bold ellipsis"><?php echo $row['company_name']?></strong>
										</div>
									</a>
								</li>
							<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 컨텐츠 종료 -->
	</div>
</div>
<!-- //container -->

<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>