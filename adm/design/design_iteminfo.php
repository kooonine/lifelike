<?php
$sub_menu = "800700";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'r');

$sql_common = " from lt_shop_info";
$sql_search = " where (1) ";

if (!$sst) {
    $sst  = "ca_name1, ca_name2, ca_name3, ca_name4";
    $sod = "asc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$g5['title'] = '제품정보고시 관리';
include_once ('../admin.head.php');

$colspan = 7;

/*
 * 
INSERT INTO lt_shop_info
(ca_name1, ca_name2, ca_name3, ca_name4, title, article) 
VALUES ('의류', '의류', '', '', '', '[{"name":"제품소재", "example":"섬유의 조성 또는 혼용률을 백분율로 표시, 기능성인 경우 성적서 또는 허가서","order":1},{"name":"색상", "example":"","order":2},{"name":"치수", "example":"","order":3},{"name":"제조자", "example":"수입품의 경우 수입자를 함께 표기 (병행수입의 경우 병행수입 여부로 대체 가능)","order":4},{"name":"세탁방법 및 취급시 주의사항", "example":"","order":5},{"name":"제조연월", "example":"","order":6},{"name":"품질보증기준", "example":"","order":7},{"name":"A/S 책임자와 전화번호", "example":"","order":8}]');


 */
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 제품정보고시 템플릿 목록<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
	  
	<form name="flist" id="flist" action="design_iteminfo_list_update.php" onsubmit="return list_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

      <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
        <table>
        <thead>
        <tr>
          <th colspan="<?php echo $colspan ?>" style="text-align: right;">
          	<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn-danger">
            <button class="btn btn_03" type="button" id="btnNew">템플릿 생성</button>
          </th>
        </tr>
        <tr>
            <th scope="col" rowspan="2">
                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
            </th>
          	<th scope="col" rowspan="2">템플릿명</th>
          	<th scope="col" colspan="4">카테고리</th>
          	<th scope="col" rowspan="2">관리</th>
        </tr>
        <tr>
          	<th scope="col">1Depth</th>
          	<th scope="col">2Depth</th>
          	<th scope="col">3Depth</th>
          	<th scope="col">4Depth</th>
        </tr>
        </thead>

        <tbody>
        
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        
        $bg = 'bg'.($i%2);
    ?>
    
		<tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            <input type="hidden" name="if_id[<?php echo $i ?>]" value="<?php echo $row['if_id'] ?>">
        </td>
          <td class="td_left" style="min-width:100px;"><?php echo $row['title'] ?></td>
          <td class="td_category2"><?php echo $row['ca_name1'] ?></td>
          <td class="td_category1"><?php echo $row['ca_name2'] ?></td>
          <td class="td_category1"><?php echo $row['ca_name3'] ?></td>
          <td class="td_category1"><?php echo $row['ca_name4'] ?></td>
          <td class="td_odrnum">
            <button class="btn btn_02" type="button" onclick="openForm('<?php echo $row['if_id']; ?>');" >수정</button>
          </td>
        </tr>
        <?php
        }
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
        ?>
        </tbody>
		
		<thead>
        <tr>
          <th colspan="<?php echo $colspan ?>" style="text-align: right;">
          	<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn-danger">
            <button class="btn btn_03" type="button" id="btnNew2">템플릿 생성</button>
          </th>
        </tr>
        </thead>
        
        </table>
      </div>
      </form>
      
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>
      
    </div>
</div>
</div>
</div>


<div id="modal_form" class="modal fade" role="dialog">
<form name="finfo" id="finfo" action="design_iteminfo_update.php" method="post" onsubmit="return finfo_submit(this);" >
<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal">&times;</button>
    		<h4 class="modal-title">배너관리 팝업</h4>
    	</div>
    	<div class="modal-body">
    		<h4><span class="fa fa-check-square"></span> 템플릿 기본정보</h4>
    		<div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label>템플릿명</label></th>
                    <td>
                    	<input type="text" name="title" id="title" value="" class="form-control" required="required" >
                    	<input type="hidden" name="if_id" id="if_id">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>템플릿명</label></th>
                    <td>
                        <table>
                        <thead>
                        	<tr>
                        		<th class="text-center">1Depth</th>
                        		<th class="text-center">2Depth</th>
                        		<th class="text-center">3Depth</th>
                        		<th class="text-center">4Depth</th>
                        	</tr>
                        </thead>
                        <tbody>
                        	<tr>
                        		<td>
                        			<input type="text" name="ca_name1" id="ca_name1" value="" class="form-control" required="required" >
                                    <select name="sel_ca_name1" id="sel_ca_name1" class="form-control" target="ca_name1" next="sel_ca_name2">
                                    	<option value="">----선택-----</option>
                                    	<?php 
                            			$sql = "select ca_name1 from lt_shop_info group by ca_name1";
                            			$result = sql_query($sql);
                            			for ($i=0; $row=sql_fetch_array($result); $i++)
                            			{
                            			    echo '<option value="'.$row['ca_name1'].'">'.$row['ca_name1'].'</option>';
                            			}
                                    	?>
                                    </select>
                        		</td>
                        		<td><input type="text" name="ca_name2" id="ca_name2" value="" class="form-control" >
                                    <select name="sel_ca_name2" id="sel_ca_name2" class="form-control" target="ca_name2" next="sel_ca_name3">
                                    	<option value="">----선택-----</option>
                                    </select>
                        		</td>
                        		<td><input type="text" name="ca_name3" id="ca_name3" value="" class="form-control" >
                                    <select name="sel_ca_name3" id="sel_ca_name3" class="form-control" target="ca_name3" next="sel_ca_name4">
                                    	<option value="">----선택-----</option>
                                    </select>
                        		</td>
                        		<td><input type="text" name="ca_name4" id="ca_name4" value="" class="form-control" >
                                    <select name="sel_ca_name4" id="sel_ca_name4" class="form-control" target="ca_name4">
                                    	<option value="">----선택-----</option>
                                    </select>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        		<span class="red">
                        		* 각 Depth 하단의 셀렉트박스에서 항목을 변경시 입력필드에 항목이 기입됩니다.<br />
                      			* 해당 카테고리가 없을 경우 입력필드에 직접 기입하면 신규로 등록됩니다. 
                      			</span>
                        		</td>
                        	</tr>
                        </tbody>
                        </table>
                    </td>
                </tr>
            
            </table>
            </div>
            <br/>
            
    		<div style="float: left;"><h4><span class="fa fa-check-square"></span> 템플릿 상세정보</h4></div>
    		<div style="float: right;"><button type="button" class="btn btn-info" id="btnAddItem">항목추가</button></div>
    		<div class="tbl_frm01 tbl_wrap">
            <table id="tblItemInfo">
                <colgroup>
                    <col>
                    <col>
                    <col style="width:50px;">
                </colgroup>
            	<thead>
                	<tr>
                		<th class="text-center">항목명</th>
                		<th class="text-center">내용</th>
                		<th class="text-center" style="width:50px;">관리</th>
                	</tr>
            	</thead>
            	<tbody id="tbodyItemInfo">
            	</tbody>
            </table>
            </div>
    	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
          <button type="button" class="btn btn-success" id="btnSubmit">저장</button>
        </div>
	</div>
</div>
</form>
</div>



<script>

var rowCnt = 0;

$(function(){
	$(document).ready(function($) {
		$("#btnNew, #btnNew2").click(function(){

			$("#ca_name1").val("");
			$("#ca_name2").val("");
			$("#ca_name3").val("");
			$("#ca_name4").val("");
			
			$("#sel_ca_name1").val("");
			$("#sel_ca_name2").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));

			$("#title").val("");
			$("#if_id").val("");
			$("#tbodyItemInfo").empty();
			$("#modal_form").modal('show');
		});

		$.get_ca_name = function(ca_name1,ca_name2,ca_name3, targetuid, targetSel){

			$targetSel = $("#"+targetSel);
			$.post(
	                "<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php",
	                { ca_name1: ca_name1, ca_name2: ca_name2, ca_name3: ca_name3 },
	                function(data) {
	                	var responseJSON = JSON.parse(data);
	                	var count = responseJSON.length;
    	   				for(i=0; i<count; i++) {
    	   					 //alert(data[i]['me_name']);
    	   					 if(responseJSON[i][targetuid] != "") {
    	   						$targetSel.append($('<option>', {value:responseJSON[i][targetuid], text: responseJSON[i][targetuid]}));
    	   					 } else if(responseJSON[i]['cnt'] == "1") {
    		   					$.get_if_info(responseJSON[i]['if_id']);
    		   				}
    	   				}
	                    
	                }
	            );
		};

		$.get_if_info = function(if_id){
			
			$.post(
	                "<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php",
	                { if_id: if_id },
	                function(data) {
	                	var responseJSON = JSON.parse(data)[0];

	                	$("#title").val(responseJSON['title']);
	                	
	                	$("#ca_name1").val(responseJSON['ca_name1']);
	                	$("#ca_name2").val(responseJSON['ca_name2']);
	                	$("#ca_name3").val(responseJSON['ca_name3']);
	                	$("#ca_name4").val(responseJSON['ca_name4']);

	                	var article = JSON.parse(responseJSON['article']);
	                	var count = article.length;
	                	var $tblItemInfo = $("#tblItemInfo");
	        			$("#tbodyItemInfo").empty();
	        			rowCnt = 0;
	        			
    	   				for(i=0; i<count; i++) {

    	   					var list = "<tr id='tr"+rowCnt+"'>";
    	   					list += "    <td>";
    	   					list += "    	<input type=\"text\" name=\"name[]\" value=\""+article[i]['name']+"\" class=\"form-control\" required=\"required\" >";
    	   				    list += "    </td>";
    	   					list += "    <td>";
    	   					list += "    	<input type=\"text\" name=\"value[]\" value=\""+article[i]['value']+"\" class=\"form-control\">";
    	   				    list += "    </td>";
    	   					list += "    <td>";
    	   					list += "    	<button type=\"button\" class=\"btn btn-danger\" onclick=\"delRow('"+rowCnt+"');\">삭제</button>";
    	   				    list += "    </td>";
    	   					list += "</tr>";
    	   					
    	   				    var $menu_last = null;
    	   			        $menu_last = $tblItemInfo.find("tbody").find("tr:last");
    	   			        if($menu_last.size() > 0) {
    	   			            $menu_last.after(list);
    	   			        } else {
    	   			            $tblItemInfo.find("tbody").append(list);
    	   			        }
    	   			        
    	   			     	rowCnt++;
    	   				}
	                }
	            );
		};
		$("#btnAddItem").click(function(){

			var list = "<tr id='tr"+rowCnt+"'>";
			list += "    <td>";
			list += "    	<input type=\"text\" name=\"name[]\" value=\"\" class=\"form-control\">";
		    list += "    </td>";
			list += "    <td>";
			list += "    	<input type=\"text\" name=\"value[]\" value=\"\" class=\"form-control\">";
		    list += "    </td>";
			list += "    <td>";
			list += "    	<button type=\"button\" class=\"btn btn-danger\" onclick=\"delRow('"+rowCnt+"');\">삭제</button>";
		    list += "    </td>";
			list += "</tr>";

			var $tblItemInfo = $("#tblItemInfo");
		    var $menu_last = null;
	        $menu_last = $tblItemInfo.find("tbody").find("tr:last");
	        if($menu_last.size() > 0) {
	            $menu_last.after(list);
	        } else {
	            $tblItemInfo.find("tbody").append(list);
	        }
	        
	     	rowCnt++;
	     	
		});


		$("#sel_ca_name1").change(function(e){

			$("#ca_name1").val($(this).val());
			$("#ca_name2").val("");
			$("#ca_name3").val("");
			$("#ca_name4").val("");

			$("#sel_ca_name2").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
			if($(this).val() != "")
			{
				$.get_ca_name($(this).val(), '', '', 'ca_name2', 'sel_ca_name2');
			} else {
				
			}
		});

		$("#sel_ca_name2").change(function(){
			$("#ca_name2").val($(this).val());
			$("#ca_name3").val("");
			$("#ca_name4").val("");

			$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
			if($(this).val() != "")
			{
				$.get_ca_name($("#sel_ca_name1").val(), $(this).val(), '', 'ca_name3', 'sel_ca_name3');
			}
		});

		$("#sel_ca_name3").change(function(){
			$("#ca_name3").val($(this).val());
			$("#ca_name4").val("");

			$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
			if($(this).val() != "")
			{
				$.get_ca_name($("#sel_ca_name1").val(), $("#sel_ca_name2").val(), $(this).val(),  'ca_name4', 'sel_ca_name4');
			}
		});

		$("#sel_ca_name4").change(function(){
			$("#ca_name4").val($(this).val());
		});


		$("#btnSubmit").click(function(){
			$("#finfo").submit();
		});

		
		
	});
});

function openForm(if_id)
{
	$("#ca_name1").val("");
	$("#ca_name2").val("");
	$("#ca_name3").val("");
	$("#ca_name4").val("");
	
	$("#sel_ca_name1").val("");
	$("#sel_ca_name2").empty().append($('<option>', {value:'', text: '----선택-----'}));
	$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
	$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
	
	$("#tbodyItemInfo").empty();

	$.get_if_info(if_id);

	$("#if_id").val(if_id);
	$("#modal_form").modal('show');
}
		
function delRow(seq)
{
	$("#tr"+seq).remove();
}

function finfo_submit(f)
{
	if($('#title').val() == "" )
	{
		alert('템플릿명을 입력하세요.');
		$('#title').focus();
		return false;
	}

    var $tblItemInfo = $("#tblItemInfo");    
    $itemInfos = $tblItemInfo.find("tbody").find("tr");
    if($itemInfos.size() <= 0) {
        alert("템플릿 상세정보를 하나 이상 입력하세요.");
        return false;
    }

    var namesChk = false;
    var names = $("input[name='name[]']");
    names.each(function(){
    	if($(this).val() == "") {
        	namesChk = true;
        	$(this).focus();
    	}
    });

	if(namesChk) {
        alert("항목명을 입력하세요.");
        return false;
    }

	if(!confirm("변경사항을 적용하시겠습니까?"))
	{
	    return false;
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

    return true;

}

function list_submit(f)
{
    if (!is_checked("chk[]")) {
        alert("삭제 하실 항목을 하나 이상 선택하세요.");
        return false;
	}
    
    if ( !confirm("해당 템플릿을 삭제하시겠습니까?\n모든 데이타가 삭제되며 복원되지 않습니다.") ) {
    	//선택한 게시판 삭제
      return false;
    }

    return true;
}

</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>