<?php
$sub_menu = "200210";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

if(!$cm_no)
    alert('잘못된 접근입니다.');

$sql = "select * from lt_shop_coupon_mng where cm_no={$cm_no} ";
$row = sql_fetch($sql);
        
$g5['title'] = '쿠폰확인하기';
include_once ('../admin.head.php');

//$token = get_admin_token();
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div>
  	<div class="x_panel">

  	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
  	<input type="hidden" name="token" value="" id="token">
  	<input type="hidden" name="w" value="u" id="w">
  	<input type="hidden" name="cm_no" value="<?php echo $cm_no?>" id="cm_no">
  	
  	  <div class="x_title">
  		<h4><span class="fa fa-check-square"></span> 기본정보<small></small></h4>
  		<label class="nav navbar-right"></label>
  		<div class="clearfix"></div>
  	  </div>

  	  <div class="tbl_frm01 tbl_wrap">
        <table>
          <tr scope = 'row'>
            <th>쿠폰명</th>
            <td colspan="3">
              <input type="text" class="form-control" id="cm_subject" name="cm_subject" required="required" maxlength="255" value="<?php echo $row['cm_subject']?>" />
            </td>
            </tr>
            
          <tr scope = 'row'>
            <th >쿠폰설명</th>
            <td colspan="3">
              <input type="text" class="form-control" id="cm_summary" name="cm_summary" maxlength="255" value="<?php echo $row['cm_summary']?>" />
            </td>
          </tr>
          
          <tr scope = 'row'>
            <th>혜택구분</th>
            <td>
            <label ><?php 
                switch($row['cm_type']) {
                    case '0':
                        echo '할인금액 : '.number_format($row['cm_price']).'원';
                        break;
                    case '1':
                        echo '할인율 : '.$row['cm_price'].'%';
                        echo ', 절사단위 : '.number_format($row['cm_trunc']).'원단위';
                        echo ', 최대금액 : '.number_format($row['cm_maximum']).'원';
                        break;
                }
                ?></label>
            </td>
            <th>발급구분</th>
            <td>
            <label ><?php echo ($row['cm_target_type'] == '0')?"대상자지정":"조건부 자동" ?>
              <?php echo ($row['cm_target_type2'] != '')?"(".$row['cm_target_type2'].")":"" ?></label>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>발급시점</th>
            <td>
            <label>
            <?php echo ($row['cm_create_time'] == '0')?"즉시발급":"지정한 시점에 발급" ?>
            <?php echo ($row['cm_start'] != '0000-00-00 00:00:00')?"(".$row['cm_start']." 부터)":"" ?>
            </label>
            </td>
            <th>발급상태</th>
            <td>
            <label><?php echo $row['cm_status']?></label>
            </td>
          </tr>

          <tr scope = 'row'>
            <th>쿠폰생성일자</th>
            <td>
            <label id="coupon_create_date"><?php echo $row['cm_datetime']?></label>
            </td>
            <th></th>
            <td></td>
          </tr>

        </table>
  	  </div>
  	  
  	  
      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 상세정보<small></small></h4>

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
          <tr scope = 'row'>
            <th>사용기간</th>
            <td>
            <label><?php 
                    if ($row['cm_end_time'] == 0) echo "기간 제한 없음";
                    else {
                        echo "발급일로부터 ".$row['cm_end_time']."일 이내";
                        
                        $cp_start=date_create(G5_TIME_YMDHIS);
                        $cp_end = date_create(G5_TIME_YMDHIS);
                        date_add($cp_end, date_interval_create_from_date_string($row['cm_end_time'].' days'));
                        
                        echo " ( ".date_format($cp_start,"Y-m-d H:i")." ~ ";
                        echo date_format($cp_end,"Y-m-d H:i")." )";
                        
                    }
                ?></label>
            </td>
            <th>적용범위</th>
            <td>
            	<label><?php echo ($row['cm_method'] =='0')?"상품쿠폰":"주문서쿠폰"; ?></label>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>사용범위</th>
            <td>
            <label > <?php echo $row['cm_use_device']?>  </label>
            </td>
            <th>사용가능 기준금액</th>
            <td>
            <label ><?php 
                switch($row['cm_use_type']) {
                    case '0': echo '제한없음'; break;
                    case '1': echo '모든 상품의 주문금액'; break;
                    case '2': echo '쿠폰적용 상품의 주문금액'; break;
                    case '3': echo '상품금액기준'; break;
                }
                ?></label>
            </td>
          </tr>
          <tr scope = 'row'>
            <th >쿠폰적용 상품선택</th>
            <td colspan="3">
                <select name="cm_item_type" id="cm_item_type" >
                    <option value="0" <?php echo get_selected($row['cm_item_type'],'0') ?>>모두 적용</option>
                    <option value="1" <?php echo get_selected($row['cm_item_type'],'1') ?>>선택한 상품 적용</option>
                    <option value="2" <?php echo get_selected($row['cm_item_type'],'2') ?>>선택한 상품 제외하고 적용</option>
                </select>
                <input type="hidden" value="<?php echo $row['cm_item_it_id_list'] ?>" name="cm_item_it_id_list" id="cm_item_it_id_list" />
                <button type="button" class="btn btn-default frm_input <?php if($row['cm_item_type'] == '0') echo 'hidden'; ?>" id="coupon_btn_product_type" target-data="coupon_product_modal">상품선택</button>
            </td>
	      </tr>
	      
	      <tr scope = 'row'>            
            <th>쿠폰적용 분류선택</th>
            <td colspan="3">
                <select name="cm_category_type" id="cm_category_type" >
                    <option value="0" <?php echo get_selected($row['cm_category_type'],'0') ?>>모두 적용</option>
                    <option value="1" <?php echo get_selected($row['cm_category_type'],'1') ?>>선택한 분류 적용</option>
                    <option value="2" <?php echo get_selected($row['cm_category_type'],'2') ?>>선택한 분류 제외하고 적용</option>
                </select>
            	<input type="hidden" value="<?php echo $row['cm_item_ca_id_list'] ?>" name="cm_item_ca_id_list" id="cm_item_ca_id_list" />
            	<button type="button" class="btn btn-default frm_input <?php if($row['cm_category_type'] == '0') echo 'hidden'; ?>" id="coupon_btn_category_type" target-data="coupon_category_modal">분류선택</button>              
            </td>
          </tr>
          <tr scope = 'row'>
            <th>적용계산 기준</th>
            <td>
            <label ><?php echo ($row['cm_use_price_type'] =='0')?"할인(쿠폰제외) 적용 전 결제금액)":"할인(쿠폰제외) 적용 후 결제금액)"; ?></label>
            </td>
            <th>동일쿠폰사용 설정</th>
            <td>
            <label >주문서당 <?php echo $row['cm_duple_item_use']?>개까지 사용가능</label>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>로그인시 쿠폰발급 알림 설정</th>
            <td>
            <label ><?php echo ($row['cm_login_send'] =='0')?"사용안함":"사용함"; ?></label>
            </td>
            <th>쿠폰발급 SMS 발송</th>
            <td>
            <label ><?php echo ($row['cm_sms_send'] =='0')?"사용안함":"사용함"; ?></label>
            </td>
          </tr>

          <tr>
            <td class="col-md-12 col-sm-12 col-xs-12 text-right" colspan="4" style="text-align:right;">
              <input type="submit" class="btn btn-default" id="coupon_btn_update" value="저장"></input>
              <a href="./configform_coupon_list.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
            </td>
          </tr>
        </table>
  	  </div>

  	</form>

    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $("#coupon_btn_product_type, #coupon_btn_category_type").click(function(){
      var id = $(this).attr("target-data");
      $('#'+id).modal('show');
    });

    $('#coupon_btn_category_add').click(function(){

    	var ca_id = $('#coupon_sel_product_main').val();
    	if(ca_id != "")
    	{
        	var ca_name = $('#coupon_sel_product_main :selected').text();
        
        	var stop = false;
        	$('#coupon_ul_category li').each(function() {
        	     if($(this).attr("data") == ca_id) {
        		     alert("등록된 상품분류입니다.");
        		     stop = true;
        		     return;
        	     }
        	  });
        	if(stop) return;
        	
            var li_script = '<li data="'+ca_id+'">' + ca_name
            + '<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>'
            + '</li>'
            ;
        
            $('#coupon_ul_category').append(li_script);
            $("button[name='coupon_btn_category_delete']").parent().css("height","22px");
            $("button[name='coupon_btn_category_delete']").css("height","100%");
    	}
    });

    $("button[name='coupon_btn_category_delete']").parent().css("height","22px");
    $("button[name='coupon_btn_category_delete']").css("height","100%");
});

$(document).on("click","button[name='coupon_btn_category_delete']",function(){
  $(this).parent().parent().remove();
});

function fconfigform_submit(f)
{
	if($("#cm_item_type").val() != "0" && $("#cm_item_it_id_list").val() == "")
	{
		alert("쿠폰적용 상품을 선택해주십시오.");
		return false;
	}
	if($("#cm_category_type").val() != "0")
	{
    	var ca_id_list = new Array();
    	$('#coupon_ul_category li').each(function() {
    		ca_id_list.push($(this).attr("data"));
    	});	
    	$('#cm_item_ca_id_list').val(ca_id_list.join(","));

    	if($("#cm_category_type").val() != "0" && $("#cm_item_ca_id_list").val() == "")
    	{
    		alert("쿠폰적용 분류를 선택해주십시오.");
    		return false;
    	}
	}
	
    var token = get_ajax_token();

    if(!token) {
        alert("토큰 정보가 올바르지 않습니다.");
        return false;
    }

    var $f = $(f);

    if(typeof f.token === "undefined")
        $f.prepend('<input type="hidden" name="token" value="">');

    $f.find("input[name=token]").val(token);
    
    f.action = "./configform_coupon_create_update.php";
    return true;
}
</script>


<!-- @END@ 내용부분 끝 -->

<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 상품선택</h4>
      </div>
      
    	<div class="modal-body">
    	
    		<div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label>제품분류</label></th>
                    <td>
                    	<select id="ca_id">
                        <option value=''>분류별 상품</option>
                        <?php
                            $sql = " select * from {$g5['g5_shop_category_table']} ";
                            if ($is_admin != 'super')
                                $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
                            $sql .= " order by ca_order, ca_id ";
                            $result = sql_query($sql);
                            for ($i=0; $ca_row=sql_fetch_array($result); $i++)
                            {
                                $len = strlen($ca_row['ca_id']) / 2 - 1;
    
                                $nbsp = "";
                                for ($i=0; $i<$len; $i++)
                                    $nbsp .= "&nbsp;&nbsp;&nbsp;";
    
                                echo "<option value=\"{$ca_row['ca_id']}\">$nbsp{$ca_row['ca_name']}</option>\n";
                            }
                        ?>
                	</select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>상품번호/상품명</label></th>
                    <td>
                    	<input type="text" name="stx" id="stx" value="" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;">
          				<button type="button" class="btn btn-success" id="btnSearch">검색</button>
                    </td>
                </tr>
			</tbody>
			</table>
			</div>
			
			<form name="procForm" id="procForm" method="post" >
			<div class="tbl_frm01 tbl_wrap" id="tblProduct">
			<?php include_once(G5_ADMIN_URL.'/design/design_component_itemsearch.php'); ?>
			</div>
			</form>
			
			<div style="text-align: right;">
          		<button type="button" class="btn btn-success" id="btnProductSubmit">추가</button>
			</div>
			
			<div class="x_title">
				<h5><span class="fa fa-check-square"></span> 선택된 지정상품</h5>
				<div style="text-align: right;">
              		<input type="button" class="btn btn-danger" value="삭제" id="btnProductDel" />
    			</div>
			</div>
			
			<form name="procForm1" id="procForm1" method="post" >
			<div class="tbl_frm01 tbl_wrap" id="tblProductForm">

			</div>
			</form>
			
    	</div>

      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>
<script>
$(function(){
	function check_all(f)
	{
	    var chk = document.getElementsByName("chk[]");

	    for (i=0; i<chk.length; i++)
	        chk[i].checked = f.chkall.checked;
	}
	
	function check_all2(f)
	{
	    var chk = document.getElementsByName("chk2[]");

	    for (i=0; i<chk.length; i++)
	        chk[i].checked = f.chkall.checked;
	}
	
	function tblProductFormBind() {

        var $table = $("#tblProductForm");
        $.post(
        		"<?php echo G5_ADMIN_URL?>/design/design_component_itemsearch.php",
                { w:"u", it_id_list: $("#cm_item_it_id_list").val() },
                function(data) {
                	$table.empty().html(data);
                }
            );
	};

	tblProductFormBind();

	$("#cm_category_type").change(function(){
	  var value = $(this).val();
	  if(value != '0'){
	    $('#coupon_btn_category_type').removeClass('hidden');
	  }else {
	    $('#coupon_btn_category_type').removeClass('hidden').addClass('hidden');
	  }
	});
	$("#cm_item_type").change(function(){
	  var value = $(this).val();
	  if(value != '0'){
	    $('#coupon_btn_product_type').removeClass('hidden');
	  }else {
	    $('#coupon_btn_product_type').removeClass('hidden').addClass('hidden');
	  }
	});
	
    $("#btnSearch").click(function(event) {
        var $table = $("#tblProduct");
    	$.post(
                "<?php echo G5_ADMIN_URL?>/design/design_component_itemsearch.php",
                { ca_id: $("#ca_id").val(), stx: $("#stx").val(), not_it_id_list: $("#cm_item_it_id_list").val() },
                function(data) {
                	$table.empty().html(data);
                }
            );
    });

    $("#btnProductDel").click(function(event) {
		if (!is_checked("chk2[]")) {
	        alert("삭제 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }
	    
		if(confirm("삭제하시겠습니까?"))
		{
		    
			var $chk = $("input[name='chk2[]']");
			var $it_id = new Array();
			
			for (var i=0; i<$chk.size(); i++)
			{
				if(!$($chk[i]).is(':checked'))
				{
        		 	var k = $($chk[i]).val();
        		 	$it_id.push($("input[name='it_id2["+k+"]']").val());
				}
			}
			
			$("#cm_item_it_id_list").val($it_id.join(","));
			tblProductFormBind();
		}
	});
	
	
	$("#btnProductSubmit").click(function(event) {

		if (!is_checked("chk[]")) {
	        alert("등록 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }
	    
		var $chk = $("input[name='chk[]']:checked");
		var $it_id = new Array();
		
		for (var i=0; i<$chk.size(); i++)
		{
    		 var k = $($chk[i]).val();
    		 $it_id.push($("input[name='it_id["+k+"]']").val());
		}
		var cm_item_it_id_list = $it_id.join(",");

		if($("#cm_item_it_id_list").val() != "") cm_item_it_id_list += ","+$("#cm_item_it_id_list").val();
		
		$("#cm_item_it_id_list").val(cm_item_it_id_list);

		tblProductFormBind();
		$("#btnSearch").click();
		
		//$("#modal_product").modal('hide');
	});

	
	$("#btnProductSearch").click(function(event) {
		$("#stx").val("");
        var $table = $("#tblProduct");
		$table.empty();
		$("#modal_product").modal('show');
	});
});
</script>
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
                  <label>상품분류 선택</label>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th rowspan="2">상품분류</th>
                <td>
                  <select name="coupon_sel_product_main" id="coupon_sel_product_main" >
                    <option value=''>분류별 상품</option>
                    <?php
                        $sql = " select  a.ca_id, a.ca_name
                                        ,b.ca_id as ca_id1, b.ca_name as ca_name1
                                        ,c.ca_id as ca_id2, c.ca_name as ca_name2
                                from    {$g5['g5_shop_category_table']} as a
                                        left outer join {$g5['g5_shop_category_table']} as b
                                          on left(a.ca_id,2) = b.ca_id
                                        left outer join {$g5['g5_shop_category_table']} as c
                                          on left(a.ca_id,4) = c.ca_id
                                order by a.ca_order, a.ca_id; ";
                        
                        $result = sql_query($sql);
                        for ($i=0; $ca_row=sql_fetch_array($result); $i++)
                        {
                            $ca_name = $ca_row['ca_name'];
                            if($ca_row['ca_name'] != $ca_row['ca_name2'])
                            {
                                $ca_name = $ca_row['ca_name2'].'>'.$ca_name;
                            }
                            if($ca_row['ca_name'] != $ca_row['ca_name1'])
                            {
                                $ca_name = $ca_row['ca_name1'].'>'.$ca_name;
                            }
                            
                            /*
                            $len = strlen($row['ca_id']) / 2 - 1;

                            $nbsp = "";
                            for ($i=0; $i<$len; $i++)
                                $nbsp .= "&nbsp;&nbsp;&nbsp;";
                            */

                            echo "<option value=\"{$ca_row['ca_id']}\">$nbsp{$ca_name}</option>\n";
                        }
                    ?>
                  </select>
                  <button type="button" class="btn btn-default" id="coupon_btn_category_add">추가</button>
                </td>
              </tr>
              <tr>
                <td>
                  <ul data-role="listview" id="coupon_ul_category">
                  <?php
                  if($row['cm_item_ca_id_list'] != '')
                  {
                      $cm_item_ca_id_list = implode("','", explode(',', $row['cm_item_ca_id_list']));
                      
                      $sql = " select  a.ca_id, a.ca_name
                                            ,b.ca_id as ca_id1, b.ca_name as ca_name1
                                            ,c.ca_id as ca_id2, c.ca_name as ca_name2
                                    from    {$g5['g5_shop_category_table']} as a
                                            left outer join {$g5['g5_shop_category_table']} as b
                                              on left(a.ca_id,2) = b.ca_id
                                            left outer join {$g5['g5_shop_category_table']} as c
                                              on left(a.ca_id,4) = c.ca_id
                                    where   a.ca_id in ('{$cm_item_ca_id_list}')
                                    order by a.ca_order, a.ca_id; ";
                      
                      $result = sql_query($sql);
                      for ($i=0; $ca_row=sql_fetch_array($result); $i++)
                      {
                          $ca_name = $ca_row['ca_name'];
                          if($ca_row['ca_name'] != $ca_row['ca_name2'])
                          {
                              $ca_name = $ca_row['ca_name2'].'>'.$ca_name;
                          }
                          if($ca_row['ca_name'] != $ca_row['ca_name1'])
                          {
                              $ca_name = $ca_row['ca_name1'].'>'.$ca_name;
                          }
               ?>
               	<li data="<?php echo $ca_row['ca_id'] ?>"><?php echo $ca_name ?>
            		<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>
            	</li>
            	<?php 
                      }
                  }
                  ?>
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


<?php
include_once ('../admin.tail.php');
?>
