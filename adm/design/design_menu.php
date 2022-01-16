<?php
$sub_menu = "800110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');
    
$g5['title'] = '메뉴관리';
include_once ('../admin.head.php');

$token = get_admin_token();
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

<table class="table table-bordered" style="height: 100%">
<thead>
<tr>
    <th scope="col" class="text-center active" width="10%">카레고리</th>
    <th scope="col" class="text-center active" width="30%">1 Depth</th>
    <th scope="col" class="text-center active" width="30%">2 Depth</th>
    <th scope="col" class="text-center active" width="30%">3 Depth</th>
</tr>
</thead>
<tbody>
    <tr>
        <th scope="col" class="text-center active" style="vertical-align: middle;" rowspan="2">수정 및 관리</th>
        <th scope="col" class="text-center active"><input type="button" class="btn btn-secondary" id="btnMgr1" value="관리"></input></th>
        <th scope="col" class="text-center active"><input type="button" class="btn btn-secondary" id="btnMgr2" value="관리"></input></th>
        <th scope="col" class="text-center active"><input type="button" class="btn btn-secondary" id="btnMgr3" value="관리"></input></th>
    </tr>
    
    <tr>
		<td scope="col" class="text-center" style="vertical-align: top;">
		<table class="table table-bordered tblGroup1" style="margin-bottom:0;">
		<?php 
		$sql = " select * from {$g5['menu_table']} where length(me_code) = 2 order by me_depth, me_order, me_code ";
		$result = sql_query($sql);
		
		$selected_menu_class = '';
		$selected_me_code = '';
		$selected_me_code1 = '';
		
		for ($i=0; $row=sql_fetch_array($result); $i++)
		{
		    if($i== 0) {
		        $selected_menu_class = 'bg-primary';
		        $selected_me_code = $row['me_code'];
		    }
		    else $selected_menu_class = '';
		?>    		
    		<tr><td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['me_code'] ?>" onclick="viewMenu('<?php echo $row['me_code'] ?>');" style="cursor: pointer;" id="td<?php echo $row['me_code'] ?>"><?php echo $row['me_name'] ?></td></tr>    		
    	<?php } ?>
    	</table>
		</td>
		<td scope="col" class="text-center" style="vertical-align: top;">
		<?php 
		$sql = " select * from {$g5['menu_table']} where length(me_code) = 4 order by substr(me_code,1,2), me_order, me_code ";
		$result = sql_query($sql);
		
		$menu_code1 = '';
		$j = 0;
		
		for ($i=0; $row=sql_fetch_array($result); $i++)
		{
		    $selected_menu_class = '';
		    $hidden_menu_class = 'hidden';
		    $cur_menu_code1 = substr($row['me_code'],0,2);
		    
		    if($menu_code1 != $cur_menu_code1){
		        $j = 0;
		        
		        if($selected_me_code == substr($row['me_code'],0,2)) {
		            $hidden_menu_class = '';
		            $selected_menu_class = 'bg-primary';
		            $selected_me_code1 = $row['me_code'];
		        }
		        
		        if($i != 0) echo '</table>';
		        echo '<table class="table table-bordered tblGroup2 '.$hidden_menu_class.'" style="margin-bottom:0;" id="table'.$cur_menu_code1.'">';
		        
		    }
		?>    		
    		<tr><td class="<?php echo $selected_menu_class ?>" data="<?php echo $row['me_code'] ?>" onclick="viewMenu1('<?php echo $cur_menu_code1 ?>','<?php echo $row['me_code'] ?>');" style="cursor: pointer;" id="td<?php echo $row['me_code'] ?>"><?php echo $row['me_name'] ?></td></tr>    		
    	<?php
        	if($menu_code1 != $cur_menu_code1){
        	    $menu_code1 = $cur_menu_code1;
        	}
        	$j++;
		}
		echo '</table>';
		?>
		
		</td>
		<td scope="col" class="text-center" style="vertical-align: top;">
		
    	<?php 
		$sql = " select * from {$g5['menu_table']} where length(me_code) = 6 order by substr(me_code,1,4), me_order, me_code ";
		$result = sql_query($sql);
		
		$menu_code2 = '';
		$j = 0;
		
		for ($i=0; $row=sql_fetch_array($result); $i++)
		{
		    $hidden_menu_class = 'hidden';
		    
		    $cur_menu_code2 = substr($row['me_code'],0,4);
		    
		    if($menu_code2 != $cur_menu_code2){
		        $j = 0;
		        
		        if($selected_me_code1 == substr($row['me_code'],0,4)) {
		            $hidden_menu_class = '';
		        }
		        
		        if($i != 0) echo '</table>';
		        echo '<table class="table table-bordered tblGroup3 '.$hidden_menu_class.'" style="margin-bottom:0;" id="table'.$cur_menu_code2.'">';
		    }
		    
		?>    		
    		<tr><td data="<?php echo $row['me_code'] ?>" id="td<?php echo $row['me_code'] ?>"><?php echo $row['me_name'] ?></td></tr>    		
    	<?php
        	if($menu_code2 != $cur_menu_code2){
        	    $menu_code2 = $cur_menu_code2;
        	}
        	$j++;
		}
		echo '</table>';
		?>
		
		</td>
	</tr>
        
</tbody>
</table> 


	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
			  	<a href='/index.php?device=mobile' target="_blank"><input type="button" class="btn btn-secondary" value="Mobile 미리보기"></input></a>
				<a href='/index.php?device=pc' target="_blank"><input type="button" class="btn btn-secondary" value="PC 미리보기"></input></a>
			</div>
		  </div>
	  </div>

	</div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    
      <div class="modal-header">
    	<div class="row">
			<div class="col-md-10 col-sm-10 col-xs-10 text-left"><h5 class="modal-title" id="bankModalLabel">◆ 카테고리 수정 및 추가</h5></div>
      		<div class="col-md-2 col-sm-2 col-xs-2 text-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
    	</div>
      </div>
      
	<form name="frmNew" id="frmNew" method="post" onsubmit="return frmNew_submit(this);">
	<input type="hidden" name="token" value="<?php echo $token ?>" >
	
	<input type="hidden" id="me_code1" name="me_code1" value="10" >
	<input type="hidden" id="me_code2" name="me_code2" value="1010" >
	<input type="hidden" id="me_code3" name="me_code3" value="" >
	
      <div class="modal-body">
      	<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-8 text-left"><h4><span class="fa fa-check-square"></span> <span id="modalTitle">1 Depth 카테고리</span></h4></div>
      		<div class="col-md-4 col-sm-4 col-xs-4 text-right"><button type="button" id="btnAdd" class="btn btn-secondary">카테고리 추가</button></div>
    	</div>
      	
        <table class="table table-bordered" style="height: 100%">
            <tbody id="tblCategory">
            
            <tr>
                <th scope="col" class="text-center active" style="vertical-align: middle;" width="20%">카레고리</th>
                <td><input type="text" name="me_name"  value="" id="me_name" class="form-control"></td>
                <th scope="col" class="text-center active" style="vertical-align: middle;" width="20%">사용여부</th>
                <td>
    				<div class="radio">
    					<label id="me_use_1"><input type="radio" name="me_use" value="1" checked="checked" >Y</label>
    					<label id="me_use_0"><input type="radio" name="me_use" value="0" >N</label>
    				</div>
                </td>
            </tr>
            <tr>
                <th scope="col" class="text-center active" style="vertical-align: middle;">URL</th>
                <td colspan="3"><input type="text" name="me_link"  value="" id="me_link" class="form-control"></td>
            </tr>
            
        </table>
        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
        <button type="submit" class="btn btn-success" id="btnConfirm">적용</button>
      </div>
      </form>
      
    </div>
  </div>
</div>





<script>
$(function(){

	$("#btnMgr1").click(function(event) {

		var depth = 1;
		var code = '';
		newModal(code, depth);
	    return false;
	});
	   

	$("#btnMgr2").click(function(event) {

		var depth = 2;
		var code = $("#me_code1").val();
		newModal(code, depth);
	    return false;
	});


	$("#btnMgr3").click(function(event) {

		var depth = 3;
		var code = $("#me_code2").val();
		newModal(code, depth);
	    return false;
	});
});


function viewMenu(depth1)
{
	$("td.bg-primary").removeClass('bg-primary');
	$("table.tblGroup2").removeClass('hidden').addClass('hidden');
	$("table.tblGroup3").removeClass('hidden').addClass('hidden');
	
	$("#td"+depth1).addClass('bg-primary');
	$("#table"+depth1).removeClass('hidden');

	var firstTd = $("#table"+depth1).find("td:first");

	$("#me_code1").val(depth1);

	if(firstTd.attr("data") != undefined)
	{
		firstTd.addClass('bg-primary');
		viewMenu1(depth1, firstTd.attr("data"));
		//alert(depth1);
	}
}

function viewMenu1(depth1, depth2)
{
	$("#me_code1").val(depth1);
	$("#me_code2").val(depth2);
	
	$("#table"+depth1).find("td.bg-primary").removeClass('bg-primary');
	$("table.tblGroup3").removeClass('hidden').addClass('hidden');
	
	$("#td"+depth2).addClass('bg-primary');
	$("#table"+depth2).removeClass('hidden');
	//alert(depth2);
}


function newModal(code, depth)
{
	var url = "./design_menu_form.php?code="+code+"&depth="+depth;
    window.open(url, "add_menu", "left=230,top=100,width=800,height=700,scrollbars=yes,resizable=yes");
    return false;
}

function frm_submit(f)
{
	if(onofflist.length == 0)
	{
		alert("변경된 내역이 없습니다.");
		return false;
	}

	if(confirm("적용하시겠습니까?"))
	{
    	$("#onofflist").val(JSON.stringify(onofflist));
        f.action = "./design_layout_web_update.php";
	}
    return false;
    
}
</script>

<?php
include_once ('../admin.tail.php');
?>
