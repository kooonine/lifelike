<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

if (!defined("_ORDERINQUIRY_")) exit; // 개별 페이지 접근 불가

// 테마에 orderinquiry.sub.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_inquiry_file = G5_THEME_MSHOP_PATH.'/orderinquiry.sub.php';
    if(is_file($theme_inquiry_file)) {
        include_once($theme_inquiry_file);
        return;
        unset($theme_inquiry_file);
    }
}
?>
<?php if($swiperslide) { ?>
<div class="swiper-container">
	<div class="swiper-wrapper">
<?php } ?>
        <?php
        $sql = " select a.*, b.ct_id, b.it_name, b.ct_option, b.it_id 
                    ,(a.od_cart_coupon + a.od_coupon + a.od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']} as a, {$g5['g5_shop_cart_table']} as b
                  where a.od_id = b.od_id 
                  and a.mb_id = '{$member['mb_id']}'
                ";
        
        $sql .= " and ((a.od_type = 'R' and a.od_status = '리스중') or a.od_type in ('L','K','S')) ";
        if(isset($od_type_care) && $od_type_care != "") $sql .= " and a.od_type = '{$od_type_care}' ";
        if(isset($od_time) && $od_time != "") $sql .= " and a.od_time >= '{$od_time}' ";
            
        $sql .=  "order by a.od_time desc
                  $limit ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $ct_name = get_text($row['it_name']).' ';
            
            $image_width = 80;
            $image_height = 80;
            $image = get_it_image($row['it_id'], 80, 80, '', '', $row['it_name']);
                
            switch($row['od_type']) {
               case 'R':
                    $od_type_name = '리스';
                    break;
                case 'L':
                    $od_type_name = '세탁';
                    break;
                case 'K':
                    $od_type_name = '세탁보관';
                    break;
                case 'S':
                    $od_type_name = '수선';
                    break;
            }
            
            $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
            
            /*
            //상태값 - 버튼
            --제품
            결제완료 : 주문취소/배송지변경
            상품준비중 : none
            배송중 : 배송조회
            배송완료 : 교환요청/반품요청/구매확정
            구매완료 : 리뷰작성/리뷰보기
            
            교환요청 : 교환철회,수거지변경
            반품요청 : 반품철회,수거지변경
            
            --리스
            계약등록 : 계약서작성,계약서수정,계약취소
            배송완료 : 교환요청/철회요청/리스시작하기
            리스중 : 계약서다운로드,리뷰작성,해지신청
            철회요청 : 철회취소,수거지변경
            해지요청 : 해지취소,수거지변경,위약금결제
            
            --세탁
            결제완료,세탁요청 : 세탁취소,배송지변경
            수거박스배송: 배송조회
            수거중: 배송조회
            세탁수거완료
            세탁중:
            배송중:
            세탁완료:구매확정
            --보관
            결제완료,보관요청:보관취소,배송지변경
            보관완료:
            --수선
            결제완료,수선요청:수선취소,배송지변경
            수선수거완료:
            수선중:
            수선완료:구매확정
            */
            $btn_act = '';
            $od_status = $row['od_status'];
            switch($row['od_status']) {
                case '주문':
                    $btn_act .= '';
                    break;
                case '결제완료':
                case '세탁요청':
                case '보관요청':
                case '수선요청':
                    $btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'" uid="'.$uid.'"><span>주문취소</span></button>';
                    $btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'"><span>배송지변경</span></button>';
                    break;
                case '상품준비중':
                    $btn_act .= '';
                    break;
                case '수거박스배송':
                case '수거중':
                    if ($row['od_pickup_invoice'] && $row['od_pickup_delivery_company'])
                    {
                        $dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
                        for($i=0; $i<count($dlcomp); $i++) {
                            if(strstr($dlcomp[$i], $row['od_pickup_delivery_company'])) {
                                list($com, $url, $tel) = explode("^", $dlcomp[$i]);
                                break;
                            }
                        }
                        
                        if($com && $url) {
                            $btn_act .= '<a href="'.$url.$row['od_pickup_invoice'].'" target="_blank"><button class="btn gray_line small"><span>배송조회</span></button></a>';
                        }
                    }
                    break;
                case '배송중':
                    if ($row['od_invoice'] && $row['od_delivery_company'])
                    {
                        $dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
                        for($i=0; $i<count($dlcomp); $i++) {
                            if(strstr($dlcomp[$i], $row['od_delivery_company'])) {
                                list($com, $url, $tel) = explode("^", $dlcomp[$i]);
                                break;
                            }
                        }
                        
                        if($com && $url) {
                            $btn_act .= '<a href="'.$url.$row['od_invoice'].'" target="_blank"><button class="btn gray_line small"><span>배송조회</span></button></a>';
                        }
                    }
                    break;
                case '배송완료':
                case '세탁완료':
                case '보관완료':
                case '수선완료':
                    $btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'" uid="'.$uid.'"><span>구매확정</span></button>';
                    break;
                case '구매완료':
                    $btn_act .= '<button class="btn gray_line small" it_id="'.$row['it_id'].'" ct_id="'.$row['ct_id'].'"><span>리뷰작성</span></button>';
                    $btn_act .= '<button class="btn gray_line small" it_id="'.$row['it_id'].'" ct_id="'.$row['ct_id'].'"><span>리뷰보기</span></button>';
                    break;
                case '계약등록':
                    //$btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'"><span>계약서작성</span></button>';
                    //$btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'" uid="'.$uid.'"><span>계약수정</span></button>';
                    $btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'" uid="'.$uid.'"><span>계약취소</span></button>';
                    break;
                case '리스중':
                    $btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'"><span>계약서다운로드</span></button>';
                    $btn_act .= '<button class="btn gray_line small" it_id="'.$row['it_id'].'" ct_id="'.$row['ct_id'].'"><span>리뷰작성</span></button>';
                    $btn_act .= '<button class="btn gray_line small" it_id="'.$row['it_id'].'" ct_id="'.$row['ct_id'].'"><span>리뷰보기</span></button>';
                    $btn_act .= '<button class="btn gray_line small" od_id="'.$row['od_id'].'" uid="'.$uid.'"><span>해지신청</span></button>';
                    break;
                default:
                    $btn_act .= '';
                    break;
            }

            $od_invoice = '';
            if($row['od_delivery_company'] && $row['od_invoice'])
                $od_invoice = ' '.get_text($row['od_delivery_company']).' / '.get_text($row['od_invoice']).'';

            
            /*
            제품 - 상태값
            결제완료/상품준비중/배송 중/배송완료/구매완료
            */
        ?>
		<?php if($swiperslide){?><div class="swiper-slide"><?php }?>
        <div class="order_cont">
            <div class="head">
                <span class="category round_green"><?php echo $od_type_name; ?></span>
                <span class="category round_none"><?php echo $od_status; ?></span>
                <!-- 
                <span class="category round_none">결제완료</span>
                <span class="category round_none">제품 준비 중</span>
                <span class="category round_none">배송 중</span>
                <span class="category round_none">배송완료</span>
                <span class="category round_none">구매완료</span>
                 -->
                <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="detail-view">상세보기</a>
            </div>
            <div class="body">
                <div class="order_num">
                    <span class="tit">주문번호 : <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?php echo $row['od_id']; ?></a></span>
                </div>
                <div class="cont">
                    <div class="photo"><?php echo $image; ?></div>
                    <div class="info">
                        <strong><?php echo $ct_name; ?></strong>
                        <p>옵션 : <?php echo get_text($row['ct_option']); ?></p>
                        <p>주문일 : <?php echo substr($row['od_time'],0,10); ?></p>
                        <?php if($od_invoice != '') echo '<p>배송정보 : '.$od_invoice.'</p>'; ?>
                        
                        <?php if($row['od_type'])?>
                        <p class="price"><span>결제 금액 : </span>  <?php echo display_price($row['od_receipt_price']); ?></p>
                        
                        
                    </div>
                </div>
                <div class="btn_comm count3" id="orderinquiry_btn">
                	<?php echo $btn_act; ?>
                </div>
            </div>
        </div>
        <?php if($swiperslide){?></div><?php }?>
        
        <?php
        }

        if ($i == 0)
            echo '<div class="none-item">주문 내역이 없습니다.</div>';
        ?>

<?php if($swiperslide) { ?>
    </div>
</div>
<?php } ?>
<div id="dvOrderinquiryPopup"></div>

<section class="popup_container layer" id="od_review_select" hidden it_id="" ct_id="">
<div class="inner_layer">
<!-- lnb -->
<div id="lnb" class="header_bar">
	<h1 class="title"><span>리뷰 작성 유형 선택</span></h1>
</div>
<!-- //lnb -->
<div class="content sub">
	<div class="grid cont">
		<div class="list">
			<ul class="type1 pad">	
				<li><a href="#" onclick="location.href='<?php echo G5_SHOP_URL?>/itemuseform.php?mode=txt&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">일반 리뷰</span></a></li>
				<li><a href="#" onclick="location.href='<?php echo G5_SHOP_URL?>/itemuseform.php?mode=img&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">프리미엄 리뷰</span></a></li>
			</ul>
		</div>
	</div>
</div>
<a href="#" class="btn_closed btn_close" onclick="$('#od_review_select').prop('hidden', true);"><span class="blind">닫기</span></a>
</div>
</section>

<form method="post" action="./orderinquirychange.php" id="orderinquirychange_form" name="orderinquirychange_form">
    <input type="hidden" name="act" value="">
    <input type="hidden" name="od_id"  value="">
    <input type="hidden" name="token"  value="">
    <input type="hidden" name="uid"  value="">
</form>

</div>
            

<script>

$(function() {
    $(document).on("click", "#orderinquiry_btn button", function() {
        var mode = $(this).text();
    	var od_id = $(this).attr("od_id");
    	
        switch(mode) {
            case "주문취소":
            	 var uid=$(this).attr("uid");
            	 location.href="<?php echo G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&uid="+uid;
            	break;
            case "배송지변경":
                $.post(
                    "./orderinquiry.deliverychangeform.php",
                    { od_id: od_id },
                    function(data) {
                    	$("#dvOrderinquiryPopup").html(data);
                    }
                );
                break;
            case "교환요청":
                if(confirm("교환을 요청 하시겠습니까? 교환 시 사유에 따라 배송료가 발생 될 수 있습니다."))
                {
	           	 	var uid=$(this).attr("uid");
    	       	 	location.href="<?php echo G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&act=change&uid="+uid;
                }
           	break;
            case "반품요청":
                if(confirm("반품을 요청 하시겠습니까? 반품 시 사유에 따라 배송료가 발생 될 수 있습니다."))
                {
	           	 	var uid=$(this).attr("uid");
    	       	 	location.href="<?php echo G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&act=return&uid="+uid;
                }
        	   	break;
            case "구매확정":
                if(confirm("구매확정 시 반품 및 교환이 불가합니다. 확정 하시겠습니까?"))
                {
	           	 	var uid=$(this).attr("uid");
	           	 	
                	$("form[name=orderinquirychange_form] input[name=act]").val("구매확정");
                	$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
                	$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
                	$("form[name=orderinquirychange_form]").submit();
                }
        	   	break;
            case "리뷰작성":
            	var it_id = $(this).attr("it_id");
            	var ct_id = $(this).attr("ct_id");
            	$('#od_review_select').attr("it_id",it_id);
            	$('#od_review_select').attr("ct_id",ct_id);
            	$('#od_review_select').prop('hidden', false);
            	
                //location.href="<?php echo G5_SHOP_URL;?>/itemuseform.php?it_id="+it_id;
        	   	break;
            case "리뷰보기":
                var it_id = $(this).attr("it_id");
                location.href="<?php echo G5_SHOP_URL;?>/item.php?it_id="+it_id+"#review";
        	   	break;
            case "계약취소":
           	 	var uid=$(this).attr("uid");
           	 	location.href="<?php echo G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&uid="+uid;
           		break;
        }
    });
});
</script>