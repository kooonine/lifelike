<?php 
include_once('../../common.php');
include_once(G5_PATH.'/_head.php');
$g5['title'] = '비회원 주문/배송조회';


$sql = " select *,
                    (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']}
                  where od_id = '$od_id'
                  order by od_id desc
                  ";
$result = sql_query($sql);
$od_status = "";
for($i=0; $row=sql_fetch_array($result); $i++) {
    $od_status = $row['od_status'];
    $receipt_price = $row['od_receipt_price']+ $row['od_receipt_point'];
}
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>비회원 주문/배송조회</span></h1>';
header += '<a href="<?php echo G5_MOBILE_URL ?>/common/nonmemberorder.php" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<div class="content comm sub">
	<!-- 컨텐츠 시작 -->
	<div class="grid cont">
		<div class="title_bar none">
            <h2 class="g_title_01">비회원 고객님의 주문 내역입니다.</h2>
            <p class="g_title_02">주문 및 배송조회가 가능합니다.</p>
		</div>
	</div>
	<div class="grid">
		<div class="title_bar">
			<h3 class="g_title_01">주문내역</h3>
		</div>
		<div class="order_cont">
		
			<div class="head">
				<span class="category round_green">제품</span>
				<span class="category round_none"><?php echo $od_status;?></span>
			</div>
			<div class="body">
				<div class="order_num">
					<span class="tit">주문번호 : <?php echo $od_id;?></span>
					<span class="amount">결제 금액 : <?php echo $receipt_price?> 원</span>
				</div>
				<?php
                $sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type
                            from {$g5['g5_shop_cart_table']}
                            where od_id = '$od_id'
                            group by it_id
                            order by ct_id ";
                $result = sql_query($sql);
                for($i=0; $row=sql_fetch_array($result); $i++) {
                    $image_width = 80;
                    $image_height = 80;
                    $image = get_it_image($row['it_id'], 80, 80, '', '', $row['it_name']);
        
                    // 옵션항목
                    $sql = " select ct_id, it_name, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_time
                                from {$g5['g5_shop_cart_table']}
                                where od_id = '$od_id'
                                  and it_id = '{$row['it_id']}'
                                order by io_type asc, ct_id asc ";
                    $res = sql_query($sql);
                    for($k=0; $opt=sql_fetch_array($res); $k++) {
                ?>	
				<div class="cont">
					<div class="photo">
						<a href="./item.php?it_id=<?php echo $row['it_id']; ?>" class="total_img"><?php echo $image; ?></a>
					</div>
					<div class="info">
						<a href="./item.php?it_id=<?php echo $row['it_id']; ?>" class="total_img"><strong><?php echo $row['it_name']; ?></strong></a>
						<p>옵션 : <?php echo $opt['ct_option'];?></p>
						<p>주문일 : <?php echo $od['od_receipt_time'];?></p>
					</div>
				</div>
				<?php }}?>
				<div class="btn_comm count3">
					<button class="btn gray_line small"><span>주문취소</span></button>
					<button class="btn gray_line small"><span>배송조회</span></button>
				</div>
				
			</div>
			
		</div>
		<p class="info_cmt">최근 1개월 동안 구매한 제품이 조회됩니다.</p>
		<div class="title_bar">
			<h3 class="g_title_01">고객센터</h3>
		</div>
		<div class="list">
			<ul class="type1">
                <li><a href="#">FAQ</a></li>
                <li><a href="#">1:1문의하기</a></li>
			</ul>
		</div>

	</div>
	
	<!-- 컨텐츠 종료 -->
</div>
<?php include_once(G5_PATH.'/_tail.php');?>
		
		<!-- footer -->
	
		<!-- //footer -->
	</div>
</body>
</html>
