<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRYCARE_")) exit; // 개별 페이지 접근 불가

?>
        <?php
        $sql = " select a.*, b.ct_id, b.it_name, b.ct_option, b.it_id 
                    ,(a.od_cart_coupon + a.od_coupon + a.od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']} as a, {$g5['g5_shop_cart_table']} as b
                  where a.od_id = b.od_id 
                  and a.mb_id = '{$member['mb_id']}'
                ";
        
        $sql .= " and (a.od_type in ('L','K','S') or (a.od_type = 'R' and a.od_status = '리스중'))";
        if(isset($od_type_care) && $od_type_care != "") $sql .= " and a.od_type = '{$od_type_care}' ";
        if(isset($od_time) && $od_time != "") $sql .= " and a.od_time >= '{$od_time}' ";
        
        $sql .=  "order by od_time desc
                  $limit ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $ct_name = get_text($row['it_name']).' ';
            
            $image_width = 80;
            $image_height = 80;
            $image = get_it_image($row['it_id'], 80, 80, '', '', $row['it_name']);
                
            $od_status_step = '';
            
            switch($row['od_type']) {
                case 'R':
                    $od_type_name = '리스';
                    
                    $od_status_step .= '<li '.(($row['od_status'] == "계약등록")?'class="on"':'').'><span>계약등록</span></li>';
                    $od_status_step .= '<li '.(($row['od_status'] == "상품준비중")?'class="on"':'').'><span>상품준비중</span></li>';
                    $od_status_step .= '<li '.(($row['od_status'] == "배송중")?'class="on"':'').'><span>배송중</span></li>';
                    $od_status_step .= '<li '.(($row['od_status'] == "배송완료")?'class="on"':'').'><span>배송완료</span></li>';
                    $od_status_step .= '<li '.(($row['od_status'] == "리스중")?'class="on"':'').'><span>리스중</span></li>';
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
                default:
                    $od_type_name = '제품';
                    break;
            }
            
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

            $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
            
            /*
            제품 - 상태값
            결제완료/상품준비중/배송 중/배송완료/구매완료
            */
        ?>
        <div class="order_cont">
			<div class="head">
				<span class="category round_green"><?php echo $od_type_name; ?></span>
				<span class="order_number">주문번호 : <strong><a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?php echo $row['od_id']; ?></a></strong></span>
				<a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="arrow_r_gray floatR">상세보기</a>
			</div>
            <div class="body">
				<ul class="order_step">
					<?php echo $od_status_step ?>
				</ul>
                <div class="cont right_cont">
                    <div class="photo"><?php echo $image; ?></div>
                    <div class="info">
                        <strong><?php echo $ct_name; ?></strong>
                        <p>옵션 : <?php echo get_text($ct['ct_option']); ?></p>
                        <p>주문일 : <?php echo substr($row['od_time'],0,10); ?></p>
                        <?php if($od_invoice != '') echo '<p>배송정보 : '.$od_invoice.'</p>'; ?>
                    </div>
					<div class="pay_item">
						결제 금액<span class="amount"><strong><?php echo display_price($row['od_receipt_price']); ?></strong> 원</span>
					</div>
                    <!-- 
                        //상태값 - 버튼 
                        결제완료 : 주문취소/배송지변경
                        제품 준비중 : none
                        배송중 : 배송조회
                        배송완료 : 교환요청/반품요청/구매확정
                        구매완료 : 리뷰작성/리뷰보기
                    -->
                    <div class="button_item" id="orderinquiry_btn">
                    	<?php echo $btn_act; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        }

        if ($i == 0)
            echo '<div class="none-item order_cont">내역이 없습니다.</div>';
        ?>
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