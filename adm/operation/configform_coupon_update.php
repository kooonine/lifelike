<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>쿠폰내역조회 | LITANDARD</title>
<link rel="stylesheet" href="./css/admin.css">

<!--[if lte IE 8]>
<script src="../js/html5.js"></script>
<![endif]-->
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "http://litandard.letzdev.com";
var g5_bbs_url   = "../bbs";
var g5_is_member = "1";
var g5_is_admin  = "super";
var g5_is_mobile = "";
var g5_bo_table  = "";
var g5_sca       = "";
var g5_editor    = "";
var g5_cookie_domain = "";
var g5_admin_url = ".";
</script>

<link href="./vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- Bootstrap -->
<link href="./vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="./vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- NProgress -->
<link href="./vendors/nprogress/nprogress.css" rel="stylesheet">

<!-- Custom Theme Style -->
<link href="./css/custom.min.css" rel="stylesheet">
<!-- iCheck -->
<link href="./vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!-- bootstrap-progressbar -->
<link href="./vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="./vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="./vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="./vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="./vendors/nprogress/nprogress.js"></script>
<!-- bootstrap-progressbar -->
<script src="./vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="./vendors/iCheck/icheck.min.js"></script>
<script src="./vendors/moment/min/moment.min.js"></script>

<script src="../js/common.js?ver=171222"></script>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script src="./vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

</head>
<body class="nav-md" >

<script>
var tempX = 0;
var tempY = 0;

function imageview(id, w, h)
{

    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - ( w + 11 );
    submenu.top  = tempY - ( h / 2 );

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}
</script>

	<div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
				<a href="." class="site_title">LITANDARD 관리자</a>
            </div>

			<div class="clearfix"></div>
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">


              <div class="menu_section">
                <ul class="nav side-menu">

                  <li><a> 환경설정 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a href="./config_form.php" >기본환경설정</a></li><li><a href="./auth_list.php" >관리권한설정</a></li><li><a href="./theme.php" >테마설정</a></li><li><a href="./menu_list.php" >메뉴설정</a></li><li><a href="./sendmail_test.php" >메일 테스트</a></li><li><a href="./newwinlist.php" >팝업레이어관리</a></li><li><a href="./session_file_delete.php" >세션파일 일괄삭제</a></li><li><a href="./cache_file_delete.php" >캐시파일 일괄삭제</a></li><li><a href="./captcha_file_delete.php" >캡챠파일 일괄삭제</a></li><li><a href="./thumbnail_file_delete.php" >썸네일파일 일괄삭제</a></li><li><a href="./phpinfo.php" >phpinfo()</a></li><li><a href="./browscap.php" >Browscap 업데이트</a></li><li><a href="./browscap_convert.php" >접속로그 변환</a></li><li><a href="./dbupgrade.php" >DB업그레이드</a></li><li><a href="./service.php" >부가서비스</a></li></ul>				  </li>

                  <li><a> 환경설정관리 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a>기본환경설정 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./configform_biz.php">사업자정보</a></li><li><a href="./configform_pwd.php">비밀번호변경</a></li><li><a href="./configform_stipulation.php">이용약관설정</a></li><li><a href="./configform_etc.php">기타이용안내설정</a></li><li><a href="./configform_privacy.php">개인정보제공설정</a></li></ul></li><li><a>사이트환경관리 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./configform_ip.php">IP접속제한관리</a></li><li><a href="./configformip_visit.php">방문자분석</a></li><li><a href="./configform_search.php">검색엔진 최적화</a></li><li><a href="./configform_mail.php">메일발송환경설정</a></li></ul></li><li><a>회원등급관리 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./configform_usergrade.php">회원등급설정</a></li></ul></li><li><a>결제정보관리 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./configform_payment.php">결제방식설정</a></li><li><a href="./configform_cash.php">현금영수증서비스</a></li><li><a href="./configform_tax.php">세금계산서서비스</a></li></ul></li><li><a>배송관리 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./configform_delivery.php">배송/반품설정</a></li><li><a href="./configform_deliverycompany.php">배송업체관리</a></li></ul></li><li><a>관리자 권한설정 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./configform_admin.php">관리자 리스트</a></li></ul></li></ul>				  </li>

                  <li><a> 회원관리 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a href="./member_list.php" >회원관리</a></li><li><a href="./mail_list.php" >회원메일발송</a></li><li><a href="./visit_list.php" >접속자집계</a></li><li><a href="./visit_search.php" >접속자검색</a></li><li><a href="./visit_delete.php" >접속자로그삭제</a></li><li><a href="./point_list.php" >포인트관리</a></li><li><a href="./poll_list.php" >투표관리</a></li></ul>				  </li>

                  <li><a> 회원관리 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a>회원설정 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./member_config.php">회원가입항목설정</a></li></ul></li><li><a>회원정보 <span class="fa fa-chevron-down"></span></a><ul class="nav child_menu"><li><a href="./member_list.php">회원정보조회</a></li></ul></li></ul>				  </li>

                  <li><a> 게시판관리 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a href="./board_list.php" >게시판관리</a></li><li><a href="./boardgroup_list.php" >게시판그룹관리</a></li><li><a href="./popular_list.php" >인기검색어관리</a></li><li><a href="./popular_rank.php" >인기검색어순위</a></li><li><a href="./qa_config.php" >1:1문의설정</a></li><li><a href="./contentlist.php" >내용관리</a></li><li><a href="./faqmasterlist.php" >FAQ관리</a></li><li><a href="./write_count.php" >글,댓글 현황</a></li></ul>				  </li>

                  <li><a> 쇼핑몰관리 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a href="./shop_admin/configform.php" >쇼핑몰설정</a></li><li><a href="./shop_admin/orderlist.php" >주문내역</a></li><li><a href="./shop_admin/personalpaylist.php" >개인결제관리</a></li><li><a href="./shop_admin/categorylist.php" >분류관리</a></li><li><a href="./shop_admin/itemlist.php" >상품관리</a></li><li><a href="./shop_admin/itemqalist.php" >상품문의</a></li><li><a href="./shop_admin/itemuselist.php" >사용후기</a></li><li><a href="./shop_admin/itemstocklist.php" >상품재고관리</a></li><li><a href="./shop_admin/itemtypelist.php" >상품유형관리</a></li><li><a href="./shop_admin/optionstocklist.php" >상품옵션재고관리</a></li><li><a href="./shop_admin/couponlist.php" >쿠폰관리</a></li><li><a href="./shop_admin/couponzonelist.php" >쿠폰존관리</a></li><li><a href="./shop_admin/sendcostlist.php" >추가배송비관리</a></li><li><a href="./shop_admin/inorderlist.php" >미완료주문</a></li></ul>				  </li>

                  <li><a> 쇼핑몰현황/기타 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a href="./shop_admin/sale1.php" >매출현황</a></li><li><a href="./shop_admin/itemsellrank.php" >상품판매순위</a></li><li><a href="./shop_admin/orderprint.php" >주문내역출력</a></li><li><a href="./shop_admin/itemstocksms.php" >재입고SMS알림</a></li><li><a href="./shop_admin/itemevent.php" >이벤트관리</a></li><li><a href="./shop_admin/itemeventlist.php" >이벤트일괄처리</a></li><li><a href="./shop_admin/bannerlist.php" >배너관리</a></li><li><a href="./shop_admin/wishlist.php" >보관함현황</a></li><li><a href="./shop_admin/price.php" >가격비교사이트</a></li></ul>				  </li>

                  <li><a> SMS 관리 <span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu"><li><a href="./sms_admin/config.php" >SMS 기본설정</a></li><li><a href="./sms_admin/member_update.php" >회원정보업데이트</a></li><li><a href="./sms_admin/sms_write.php" >문자 보내기</a></li><li><a href="./sms_admin/history_list.php" >전송내역-건별</a></li><li><a href="./sms_admin/history_num.php" >전송내역-번호별</a></li><li><a href="./sms_admin/form_group.php" >이모티콘 그룹</a></li><li><a href="./sms_admin/form_list.php" >이모티콘 관리</a></li><li><a href="./sms_admin/num_group.php" >휴대전화번호 그룹</a></li><li><a href="./sms_admin/num_book.php" >휴대전화번호 관리</a></li><li><a href="./sms_admin/num_book_file.php" >휴대전화번호 파일</a></li></ul>				  </li>
				                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
			 -->
            <!-- /menu footer buttons -->
          </div>
        </div>

		<!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">

                <li class="" id="tnb_logout"><a href="../bbs/logout.php">로그아웃</a></li>
				<li class=""><a href="../" class="tnb_shop" target="_blank" title="쇼핑몰 바로가기">쇼핑몰 이동</a></li>
				<li class=""><a href="./member_form.php?w=u&amp;mb_id=admin">관리자정보</a></li>
				<li class=""><a href="javascript:;">최고관리자 님</a></li>

              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

<script>
jQuery(function($){

    var menu_cookie_key = 'g5_admin_btn_gnb';

    $(".tnb_mb_btn").click(function(){
        $(".tnb_mb_area").toggle();
    });

    $("#btn_gnb").click(function(){

        var $this = $(this);

        try {
            if( ! $this.hasClass("btn_gnb_open") ){
                set_cookie(menu_cookie_key, 1, 60*60*24*365);
            } else {
                delete_cookie(menu_cookie_key);
            }
        }
        catch(err) {
        }

        $("#container").toggleClass("container-small");
        $("#gnb").toggleClass("gnb_small");
        $this.toggleClass("btn_gnb_open");

    });

    $(".gnb_ul li .btn_op" ).click(function() {
        $(this).parent().addClass("on").siblings().removeClass("on");
    });

});
</script>

		<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>회원혜택관리</h3>
              </div>

			  <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 pull-right">
	                <h5> 홈 > 운영관리 > 회원혜택관리 > 쿠폰관리 > 쿠폰내역조회 </h5>
                </div>
              </div>


            </div>
            <div class="clearfix"></div>





<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="x_panel">

  	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
  	<input type="hidden" name="token" value="" id="token">
      <h2><span class="fa fa-square"></span> 쿠폰수정하기<small></small></h2>
  	  <div class="x_title">
  		<h2><span class="fa fa-check-square"></span> 기본정보<small></small></h2>
  		<label class="nav navbar-right"></label>
  		<div class="clearfix"></div>
  	  </div>

  	  <div class="tbl_frm01 tbl_wrap">
        <table>

          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>쿠폰번호</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label id="coupon_create_no">0000000</label>
            </td>
            <th>쿠폰생성일자</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </labelid="coupon_create_date">2019-01-18 15:08</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>쿠폰명</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            <input id="coupon_name" class="frm_input" value="TEXT"></input>
            </td>
            <th >쿠폰설명</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
              </label >TEXT</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>혜택구분</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >0000원 할인</label>
            </td>
            <th>발급구분</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >대상자 지정발급>회원대상</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>발급시점</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >2019-01-18 15:08 부터</label>
            </td>
            <th>발급상태</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >발급중</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>동일쿠폰사용 설정</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >주문서당 1개까지 사용가능</label>
            </td>
            <th>발급대상자</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >모든회원</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>발급수 제한</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label>제한없음</label>
            </td>
            <th>1회 발급수량</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label>1장씩 발급</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>동일인 재발급 여부</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label>불가능</label>
            </td>
            <th ></th>
            <td class="col-md-5 col-sm-5 col-xs-5">
              </label></label>
            </td>
          </tr>

        </table>
  	  </div>




  	</form>

  	</div>

    <div class="x_panel">
      <div class="x_title">
        <h2><span class="fa fa-check-square"></span> 상세정보<small></small></h2>

        <div class="clearfix"></div>
      </div>

      <div class="tbl_frm01 tbl_wrap">
        <table>
          <colgroup>
          <col class="grid_4">
          <col>
          <col>
          <col>
          </colgroup>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>사용기간</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label>2019-01-18 15:08 ~ 2019-01-18 15:08</label>
            </td>
            <th>적용범위</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label>주문서 쿠폰</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>사용범위</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >PC 쇼핑몰, 모바일 쇼핑몰</label>
            </td>
            <th >쿠폰적용 상품선택</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
              <select name="coupon_sel_product" id="coupon_sel_product" >
                  <option value="모두적용" >모두적용</option>
                  <option value="선택한 상품 적용" >선택한 상품 적용</option>
                  <option value="선택한 상품 제외하고 적용" >선택한 상품 제외하고 적용</option>
              </select>
            <button type="button" class="btn btn-sm btn-default hidden" id="coupon_btn_product">상품확인</button>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>쿠폰적용 분류선택</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
              <select name="coupon_sel_category" id="coupon_sel_category" >
                  <option value="모두적용" >모두적용</option>
                  <option value="선택한 상품 적용" >선택한 상품 적용</option>
                  <option value="선택한 상품 제외하고 적용" >선택한 상품 제외하고 적용</option>
              </select>
              <button type="button" class="btn btn-sm btn-default hidden"  id="coupon_btn_category">상품확인</button>
            </td>
            <th>사용가능 기준금액</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >제한없음</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>적용계산 기준</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >할인(쿠폰제외) 적용 전 결제금액)</label>
            </td>
            <th>사용가능 결제수단</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >결제수단1, 결제수단2, 결제수단3,</label>
            </td>
          </tr>
          <tr scope = 'row' class="col-md-12 col-sm-12 col-xs-12">
            <th>로그인시 쿠폰발급 알림 설정</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >사용안함</label>
            </td>
            <th>쿠폰발급 SMS 발송</th>
            <td class="col-md-5 col-sm-5 col-xs-5">
            </label >사용안함</label>
            </td>
          </tr>

          <tr>
            <td class="col-md-12 col-sm-12 col-xs-12 text-right" colspan="4" style="text-align:right;">
              <input type="submit" class="btn btn-default" id="coupon_btn_save" value="저장"></input>
            </td>
          </tr>
        </table>
  	  </div>

    </div>


  </div>


</div>

<script>
$(document).ready(function() {

      $("#coupon_sel_product").change(function(){
        if($(this).val() == '모두적용'){
          $("#coupon_btn_product").removeClass('hidden').addClass('hidden');
        }else {
          $("#coupon_btn_product").removeClass('hidden');
        }

      });
      $("#coupon_sel_category").change(function(){
        if($(this).val() == '모두적용'){
          $("#coupon_btn_category").removeClass('hidden').addClass('hidden');
        }else {
          $("#coupon_btn_category").removeClass('hidden');
        }

      });

      $('#coupun_btn_product_add').click(function(){

        $('#coupon_checkbox_all').prop('checked',false);
        $("input[name=coupon_checkbox]").prop('checked',false);
      });


    $('#product_table_selected').on("click",'#coupon_btn_product_delete',function(){
      var check_cnt = 0;
      $('input[name=coupon_checkbox_selected]:checked').each(function() {
          check_cnt +=1;
      });

      if(check_cnt > 0){
        var result = confirm('선택한 상품을 삭제하시겠습니까?');
        if(result) {
        }
      }else {
        alert('선택된 상품이 없습니다.');
      }
    });



  var sort_selected = 'coupon_product_reg_date';
  $("#product_table_length").removeClass("dataTables_length");
  $("#product_table_length > label > select[name='product_table_length']").change(function(){
    $("#coupon_product_sort").val(sort_selected);

  });



  //전체선택
  $('#coupon_checkbox_all').click(function(){
    var check = $(this).is(":checked");

    if(check){
      $("input[name=coupon_checkbox]").prop('checked',true);
    }else {
      $("input[name=coupon_checkbox]").prop('checked',false);
    }
  });

  //전체선택
  $('#coupon_checkbox_all_selected').click(function(){
    var check = $(this).is(":checked");

    if(check){
      $("input[name=coupon_checkbox_selected]").prop('checked',true);
    }else {
      $("input[name=coupon_checkbox_selected]").prop('checked',false);
    }
  });

  //목록
  $('input[name="coupon_btn_list"]').click(function(){

  });
  //분류선택
  $('#coupon_btn_category').click(function(){
    $('#coupon_category_modal').modal('toggle');
    $("#category_table").css("width","100%");
  });
  //제품선택
  $('#coupon_btn_product').click(function(){
    $('#coupon_product_modal').modal('toggle');
    $("#product_table").css("width","100%");
    $("#product_table_selected").css("width","100%");
  });


  $('#coupon_btn_category_add').click(function(){

      var li_script = '<li>'
      + $('#coupon_sel_product_main').val()
      + ' > '
      + $('#coupon_sel_product_sub').val()
      + ' > '
      + $('#coupon_sel_product_section').val()
      + '<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>'
      + '</li>'
      ;

      $('#coupon_ul_category').append(li_script);
      $("button[name='coupon_btn_category_delete']").parent().css("height","22px");
      $("button[name='coupon_btn_category_delete']").css("height","100%");
  });

  $("button[name='coupon_btn_category_delete']").parent().css("height","22px");
  $("button[name='coupon_btn_category_delete']").css("height","100%");

});
$(document).on("click","button[name='coupon_btn_category_delete']",function(){
  $(this).parent().parent().remove();
});


function fconfigform_submit(f)
{
    f.action = "./configform_coupon_update.html";
    return true;
}
</script>




<!-- @END@ 내용부분 끝 -->


			</div>
        </div>
        <!-- /page content -->

		<!-- footer content -->
        <footer>
          <div class="pull-right">
            Copyright &copy; litandard.letzdev.com. All rights reserved.
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>


<script>
$(".scroll_top").click(function(){
     $("body,html").animate({scrollTop:0},400);
})
</script>

<!-- <p>실행시간 : 0 -->

<script src="./admin.js?ver=171222"></script>
<script src="../js/jquery.anchorScroll.js?ver=171222"></script>

<!-- Custom Theme Scripts -->
<script src="./js/custom.min.js"></script>

<script>


$(function(){

    var admin_head_height = $("#hd_top").height() + $("#container_title").height() + 5;

    $("a[href^='#']").anchorScroll({
        scrollSpeed: 0, // scroll speed
        offsetTop: admin_head_height, // offset for fixed top bars (defaults to 0)
        onScroll: function () {
          // callback on scroll start
        },
        scrollEnd: function () {
          // callback on scroll end
        }
    });

    var hide_menu = false;
    var mouse_event = false;
    var oldX = oldY = 0;

    $(document).mousemove(function(e) {
        if(oldX == 0) {
            oldX = e.pageX;
            oldY = e.pageY;
        }

        if(oldX != e.pageX || oldY != e.pageY) {
            mouse_event = true;
        }
    });


});

</script>

<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 상품선택</h4>

      </div>
      <div class="modal-body" >
          <div class="row">
            <div class="tbl_frm01 tbl_wrap">

              <table>
                <thead>
                  <tr >
                    <th colspan="4" style="text-align:center;">
                      <label>지정상품 선택</label>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>카테고리</th>
                    <td>
                      <select name="coupon_sel_product_main" id="coupon_sel_product_main" >
                        <option value="대분류" >대분류</option>
                      </select>
                      <select name="coupon_sel_product_sub" id="coupon_sel_product_sub" >
                        <option value="중분류" >중분류</option>
                      </select>
                      <select name="coupon_sel_product_section" id="coupon_sel_product_section" >
                        <option value="소분류" >소분류</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>상품검색</th>
                    <td colspan="3">
                      <select name="coupon_sel_product_type" id="coupon_sel_product_type" >
                          <option value="상품명" >상품명</option>
                          <option value="상품코드" >상품코드</option>
                      </select>
                      <input type="text" value="" class="frm_input" >
                    </td>
                  </tr>
                  <tr>
                    <td  colspan="4">
                      <div class="pull-right">
                        <button type="button" class="btn btn-primary" id="coupun_btn_product_search">검색</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="tbl_head01 tbl_wrap">
              <div class="pull-right">
                <select name="order" id="order" >
                  <option value="최근등록순" >최근등록순</option>
                  <option value="주문량순">주문량순</option>
                  <option value="상품명순">상품명순</option>
                </select>
                <select >
                  <option value="10개씩 보기" >10개씩 보기</option>
                  <option value="20개씩 보기">20개씩 보기</option>
                  <option value="30개씩 보기">30개씩 보기</option>
                </select>
              </div>
                <table id="product_table" style>
                <thead>
                  <tr>
                    <th scope="col"><input type="checkbox" id="coupon_checkbox_all" /></input></th>
                    <th scope="col">상품명</th>
                    <th scope="col">판매가</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td scope="col"><input type="checkbox" name="coupon_checkbox" /></input></td>
                    <td scope="col"><img src="./img/test_image.png"/> TEXT TEXT</td>
                    <td scope="col">00,000</td>
                  </tr>
                  <tr>
                    <td scope="col"><input type="checkbox" name="coupon_checkbox" /></input></td>
                    <td scope="col"><img src="./img/test_image.png"/> TEXT TEXT</td>
                    <td scope="col">00,000</td>
                  </tr>
                  <tr>
                    <td scope="col"><input type="checkbox" name="coupon_checkbox" /></input></td>
                    <td scope="col"><img src="./img/test_image.png"/> TEXT TEXT</td>
                    <td scope="col">00,000</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td  colspan="4">
                      <div class="pull-right">
                        <button type="button" class="btn btn-primary" id="coupun_btn_product_add">추가</button>
                      </div>
                    </td>
                  </tr>
                </tfoot>
                </table>
            </div>
          </div>
          <div class="row">
            <div class="tbl_head01 tbl_wrap col-md-12 col-sm-12 col-xs-12">

                <table id="product_table_selected" style>
                <thead>
                <tr >
                  <th colspan="4" style="text-align:center;">
                    선택한 지정상품
                    <div class="pull-right"><button type="button" class="btn btn-sm btn-default" id="coupon_btn_product_delete">삭제</button></div>
                  </th>
                </tr>
                <tr>
                  <th scope="col" class="col-md-1 col-sm-1 col-xs-1"><input type="checkbox" id="coupon_checkbox_all_selected" /></input></th>
                  <th scope="col" class="col-md-6 col-sm-6 col-xs-6">상품명</th>
                  <th scope="col" class="col-md-3 col-sm-3 col-xs-3">판매가</th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                    <td scope="col"><input type="checkbox" name="coupon_checkbox_selected" /></input></td>
                    <td scope="col"><img src="./img/test_image.png"/> TEXT TEXT</td>
                    <td scope="col">00,000</td>
                  </tr>
                  <tr>
                    <td scope="col"><input type="checkbox" name="coupon_checkbox_selected" /></input></td>
                    <td scope="col"><img src="./img/test_image.png"/> TEXT TEXT</td>
                    <td scope="col">00,000</td>
                  </tr>
                  <tr>
                    <td scope="col"><input type="checkbox" name="coupon_checkbox_selected" /></input></td>
                    <td scope="col"><img src="./img/test_image.png"/> TEXT TEXT</td>
                    <td scope="col">00,000</td>
                  </tr>
                </tbody>
                </table>
            </div>


          </div>
      </div>

      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="coupon_category_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_category_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 카테고리 선택</h4>

      </div>
      <div class="modal-body" >
        <div class="row">
        <div class="tbl_frm01 tbl_wrap">
          <table>
            <thead>
              <tr >
                <th colspan="4" style="text-align:center;">
                  <label>상품 카테고리 선택</label>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th rowspan="2">카테고리</th>
                <td>
                  <select name="coupon_sel_product_main" id="coupon_sel_product_main" >
                    <option value="대분류" >대분류</option>
                  </select>
                  <select name="coupon_sel_product_sub" id="coupon_sel_product_sub" >
                    <option value="중분류" >중분류</option>
                  </select>
                  <select name="coupon_sel_product_section" id="coupon_sel_product_section" >
                    <option value="소분류" >소분류</option>
                  </select>
                  <button type="button" class="btn btn-default" id="coupon_btn_category_add">추가</button>
                </td>
              </tr>
              <tr>
                <td>
                  <ul data-role="listview" id="coupon_ul_category">

              				<li>
                          학생
                          <div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>
                      </li>
              				<li>
                        컴퓨터/인터넷
                        <div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>
                      </li>
              				<li>
                        언론
                        <div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>
                      </li>

            			</ul>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">저장</button>
      </div>
    </div>
  </div>
</div>


<!-- <div style='float:left; text-align:center;'>RUN TIME : 0<br></div> -->
<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position", "relative");
        count = count - 1;
    });
});
</script>
<![endif]-->

</body>
</html>
