<?php
include_once('./_common.php');


include_once(G5_MSHOP_PATH.'/_head.php');


$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?';
if($ca_id) $sct_sort_href .= 'ca_id='.$ca_id;
if($skin) $sct_sort_href .= '&amp;skin='.$skin;
$sct_sort_href .= '&amp;sort=';


include_once(G5_SHOP_PATH.'/shop.head.php');


$sql_common = " from lt_shop_finditem ";
$sql_where = " where fi_status = 'Y' ";

if ($sort == "")
{
    $sort='it_sum_qty';
    $sortodr='desc';
}
?>

		<!-- container -->
		<div id="container">
			<div class="content shop">
				<!-- 컨텐츠 시작 -->
				<div class="grid">
					<div class="title_bar none alignC">
						<h2 class="g_title_02">당신의 라이프 스타일에 꼭 맞는 제품을 만나보세요. <br>선호하는 색상과 스타일, 가격까지<br><span class="point">다양한 제품 중 당신에게 꼭 맞는 제품을 찾아드립니다.</span></h2>
					</div>
				</div>

				<form class="form-horizontal form-label-left" name="frm" id="frm" method="post" onsubmit="return frm_submit(this);">
				<input type="hidden" name="sort" value="<?php echo $sort?>">
				<input type="hidden" name="sortodr" value="<?php echo $sortodr?>">
				
				<div class="grid bg_none">
					<div class="border_box curation_wrap" id="finditem_add_form">
					
                    <?php
                    $sql = " select * $sql_common $sql_where ";
                    $result = sql_query($sql);
                    $j = 0;
                    while ($row=sql_fetch_array($result)) {
                        $fi_contents = explode(",", $row['fi_contents']);
                    ?>
						<strong class="title"><?php echo $row['fi_subject']; ?></strong>
						<ul class="category_list"  id="findItem<?php echo $i; ?>">
							<?php for ($i = 0; $i < count($fi_contents); $i++) {
							    echo '<li><button type="button" class="category round_black" id="'.$j.'|'.$fi_contents[$i].'">'.$fi_contents[$i].'</button>
                                          <input type="checkbox" name="chk[]" value="'.$j.'" hidden>
                                          <input type="hidden" name="fi_contents['.$j.']" value="'.$fi_contents[$i].'">
                                          <input type="hidden" name="fi_id['.$j.']" value="'.$row['fi_id'].'">
                                      </li>';
							    $j++;
							}?>
						</ul>
					<?php } ?>
						<div class="btn_group"><button type="submit" class="btn big green"><span>확인</span></button></div>
					
					</div>
					
					
					<div class="grid bg_none">
					<div class="title_bar">
						<h3 class="g_title_01">나의 선택</h3>
						<button class="btn gray_line small round none" type="button" id="btnReset"><span>초기화</span></button>
					</div>
					<div class="border_box">
						<p class="sm">카테고리를 선택해 주세요.</p>
						<ul class="category_list mt10" id="selcted_list">
							<li></li>
							<?php 
                            for ($i=0; $i<count($_POST['chk']); $i++)
                            {
                                // 실제 번호를 넘김
                                $k     = $_POST['chk'][$i];
                                $fi_contents = $_POST['fi_contents'][$k];
                                $fi_id = $_POST['fi_id'][$k];
                                
                                echo '<li id="sel'.$k.'|'.$fi_contents.'"><button type="button" class="category round_black on">'.$fi_contents.'</button></li>';
                            }
							?>
							<!-- li><span>구매<a href="#" class="btn_del"><span class="blind">삭제</span></a></span></li -->
						</ul>
					</div>
					
					<script>
					$(function() {
						<?php 
                        for ($i=0; $i<count($_POST['chk']); $i++)
                        {
                            $k     = $_POST['chk'][$i];
                            $fi_contents = $_POST['fi_contents'][$k];
                            
                            echo '$("button[id=\''.$k.'|'.$fi_contents.'\']").addClass("on");';
                            echo '$("button[id=\''.$k.'|'.$fi_contents.'\']").closest("li").find("input[type=\'checkbox\']").prop("checked",true);';
                        }
                        ?>
					    $(document).on("click", "#finditem_add_form .category_list button", function() {
						    var id = $(this).attr("id");
						    var onoff = ($(this).attr("class") == "category round_black");
						    if(onoff)
						    {
							    //선택
						    	$(this).addClass("on");
						    	$("#selcted_list li:last").after("<li id='sel"+id+"'><button type=\"button\" class=\"category round_black on\">"+$(this).text()+"</button></li>");
						    	$(this).closest("li").find("input[type='checkbox']").prop("checked",true);
						    	
						    } else {
							    //해제
						    	$(this).removeClass("on");
						    	$("#selcted_list").find("li[id='sel"+id+"']").remove();
						    	$(this).closest("li").find("input[type='checkbox']").prop("checked",false);
						    }
					    });

					    $("#btnSearch").click(function() {


					    });

					    $("#btnReset").click(function() {
						    $(".category_list button").each(function(){
							    var id = $(this).attr("id");
						    	$(this).removeClass("on");
						    	$(this).closest("li").find("input[type='checkbox']").prop("checked",false);
						    });
						    
					    	$("#selcted_list").html("<li></li>");
					    	$("#btnSearch").click();
					    });

					    
					    
					});

					
					function frm_submit(f)
					{
					    f.action = "./finditem.php";
					    return true;
					}
					
					</script>
				</div>
				</form>

				<div class="grid">
				<?php 
			
			$count = count($_POST['chk']);
			if($count)
			{
				// 상품 출력순서가 있다면
				$order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
				$error = '<p class="sct_noitem">등록된 상품이 없습니다.</p>';
				
				$skin_dir = G5_MSHOP_SKIN_PATH;
				$skin_file = $skin_dir.'/list.10.skin.php';
				
				?>
					<div class="tab">
                    	<ul class="type2">
                    		<li <?php echo ($sort == 'it_sum_qty')?'class="on"':'' ?> style="cursor: pointer;" ><a onclick="sort('it_sum_qty','desc');"><span>인기순</span></a></li>
                    		<li <?php echo ($sort == 'it_update_time')?'class="on"':'' ?>  style="cursor: pointer;"><a onclick="sort('it_update_time','desc');"><span>최신순</span></a></li>
                    		<li <?php echo ($sort == 'it_price' && $sortodr=='asc')?'class="on"':'' ?>  style="cursor: pointer;"><a onclick="sort('it_price','asc');"><span>낮은가격순</span></a></li>
                    		<li <?php echo ($sort == 'it_price' && $sortodr=='desc')?'class="on"':'' ?>  style="cursor: pointer;"><a onclick="sort('it_price','desc');"><span>높은가격순</span></a></li>
                    	</ul>
                    </div>
					<div class="tab_cont">
						<!-- tab1 -->
						<div class="tab_inner">
						<?php
						$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '10' and ca_use = '1'  ";
						$ca = sql_fetch($sql);
						
						// 총몇개 = 한줄에 몇개 * 몇줄
						$items = $ca['ca_list_mod'] * $ca['ca_list_row'];
						// 페이지가 없으면 첫 페이지 (1 페이지)
						if ($page < 1) $page = 1;
						// 시작 레코드 구함
						$from_record = ($page - 1) * $items;
						
						$list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
						
						$findwhere = "";
					    $findwhere = "inner join (select it_id, count(*) cnt from lt_shop_item_finditem where (";
					    $comma = '';
					    for ($i=0; $i<$count; $i++)
					    {
					        // 실제 번호를 넘김
					        $k = $_POST['chk'][$i];
					        $findwhere .= $comma." (concat(fi_id,':',fi_contents) = '".$_POST['fi_id'][$k].':'.$_POST['fi_contents'][$k]."' )";
					        $comma = ' or ';
					    }
					    $findwhere .= ") group by it_id) as b on a.it_id = b.it_id";
					    
						$query = "select a.* from `{$g5['g5_shop_item_table']}` a ".$findwhere." where a.it_use = '1' ";
						$query .= " order by ".$order_by;
						$query .= " limit " . $from_record . " , " . ($ca['ca_list_mod'] * $ca['ca_list_row']);
						
						$list->set_query($query);
						
						//$list->set_category('', 1);
						$list->set_is_page(true);
						$list->set_order_by($order_by);
						$list->set_from_record($from_record);
						$list->set_view('it_img', true);
						$list->set_view('it_id', false);
						$list->set_view('it_name', true);
						$list->set_view('it_basic', true);
						$list->set_view('it_cust_price', true);
						$list->set_view('it_price', true);
						$list->set_view('it_icon', true);
						$list->set_view('sns', true);
						echo $list->run();
						
						// where 된 전체 상품수
						$total_count = $list->total_count;
						// 전체 페이지 계산
						$total_page  = ceil($total_count / $items);
						
						?>
                    
                        <?php
                        $qstr1 .= 'ca_id='.$ca_id;
                        $qstr1 .='&amp;sort='.$sort.'&amp;sortodr='.$sortodr;
                        echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&amp;page=');
			}
                        ?>
					</div>
					
				</div>
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
		<!-- //container -->

<script>
$(function() {

});

function sort(s, so){
	$("input[name='sort']").val(s);
	$("input[name='sortodr']").val(so);
	$("#frm").submit();
}


</script>
<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>