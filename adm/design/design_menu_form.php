<?php
$sub_menu = "100290";
include_once('./_common.php');

if ($is_admin != 'super')
    alert_close('최고관리자만 접근 가능합니다.');

$g5['title'] = '메뉴 관리';
include_once ('../admin.head.sub.php');

$token = get_admin_token();
?>


<div class="container body">
<div class="main_container">
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	<div id="menu_frm" class="new_win">
        <h3><?php echo $g5['title']; ?></h3>
    </div>
    
	<form name="frmNew" id="frmNew" method="post" action="./design_menu_update.php" onsubmit="return frm_submit(this);">
    <input type="hidden" name="token" value="<?php echo $token ?>" >
    <input type="hidden" name="p_code" value="<?php echo $code ?>" >
    <input type="hidden" name="depth" value="<?php echo $depth ?>" >

  	<div class="row">
		<div class="col-md-8 col-sm-8 col-xs-8 text-left"><h4><span class="fa fa-check-square"></span> <span id="modalTitle"><?php echo $depth; ?> Depth 카테고리</span></h4></div>
  		<div class="col-md-4 col-sm-4 col-xs-4 text-right"><button type="button" id="btnAdd" class="btn btn-secondary">카테고리 추가</button></div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12 text-left"><span class="help red">※ 프론트 노출에 따라 텍스트는 00자로 제한됩니다.</span></div>
	</div>
	
	<table class="table table-bordered" style="height: 100%" id="menulist">
    <tbody id="tblCategory">
    <?php 
    
    $sql = "select * from {$g5['menu_table']}
        where   me_code like '".$code."%'
        and     me_depth = '".$depth."'
        ";
    $result = sql_query($sql);
    
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $search  = array('"', "'");
        $replace = array('&#034;', '&#039;');
        $me_name = str_replace($search, $replace, $row['me_name']);
        
        //echo "{ \"me_code\":\"".$row['me_code']."\", \"me_name\":\"".$row['me_name']."\", \"me_link\":\"".$row['me_link']."\" }";
    ?>
    <tr>
        <th scope="col" class="text-center active" style="vertical-align: middle;" width="20%">카레고리</th>
        <td scope="col" class="text-center" style="vertical-align: middle;">
        	<input type="hidden" name="me_id[]" value="<?php echo $row['me_id'] ?>">
        	<input type="hidden" name="code[]" value="<?php echo $row['me_code'] ?>">
        	<input type="hidden" name="me_order[]" value="<?php echo $row['me_order'] ?>">
        	<input type="hidden" name="me_target[]" value="<?php echo $row['me_target'] ?>">
        	<input type="text" name="me_name[]" value="<?php echo $me_name; ?>" id="me_name_<?php echo $i; ?>" required class="form-control">
        </td>
        <th scope="col" class="text-center active" style="vertical-align: middle;" width="20%">사용여부</th>
        <td>
			<div class="radio">
				<select name="me_use[]" id="me_use_<?php echo $i; ?>">
                    <option value="1"<?php echo get_selected($row['me_use'], '1', true); ?>>사용함</option>
                    <option value="0"<?php echo get_selected($row['me_use'], '0', true); ?>>사용안함</option>
                </select>
			</div>
        </td>
    </tr>
    <tr>
        <th scope="col" class="text-center active" style="vertical-align: middle;">URL</th>
        <td colspan="3">
        	<input type="text" name="me_link[]" value="<?php echo $row['me_link'] ?>" id="me_link_<?php echo $i; ?>" required class="form-control">
        </td>
    </tr>
    <?php 
    
    
    }
    ?>
    </tbody>
	</table>
	
	<div class="x_content">
		<div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
        		<button type="button" class="btn btn-secondary" onclick="window.close();">취소</button>
        		<button type="submit" class="btn btn-success" id="btnConfirm">일괄 적용</button>
        	</div>
        </div>
    </div>
    </form>
	
	</div>
  </div>
</div>
</div>
</div>
     
<script>
$(function() {
	
	$("#btnAdd").click(function(event) {

	    var $menulist = $("#menulist");
	    var ms = new Date().getTime();

	    var list = "<tr>";
	    list += "    <th scope=\"col\" class=\"text-center active\" style=\"vertical-align: middle;\" width=\"20%\">카레고리</th>";
	    list += "    <td scope=\"col\" class=\"text-center\" style=\"vertical-align: middle;\">";
	    list += "    	<input type=\"hidden\" name=\"me_id[]\" value=\"\">";
	    list += "    	<input type=\"hidden\" name=\"code[]\" value=\"\">";
	    list += "    	<input type=\"hidden\" name=\"me_order[]\" value=\"0\">";
	    list += "    	<input type=\"hidden\" name=\"me_target[]\" value=\"self\">";
	    list += "    	<input type=\"text\" name=\"me_name[]\" value=\"\" id=\"me_name_"+ms+"\" required class=\"form-control\">";
	    list += "    </td>";
	    list += "    <th scope=\"col\" class=\"text-center active\" style=\"vertical-align: middle;\" width=\"20%\">사용여부</th>";
	    list += "    <td>";
	    list += " 		<div class=\"radio\">";
	    list += " 			<select name=\"me_use[]\" id=\"me_use_"+ms+"\">";
	    list += "                 <option value=\"1\">사용함</option>";
	    list += "                 <option value=\"0\" selected>사용안함</option>";
	    list += "             </select>";
	    list += "		</div>";
	    list += "    </td>";
	    list += "</tr>";
	    list += "<tr>";
	    list += "    <th scope=\"col\" class=\"text-center active\" style=\"vertical-align: middle;\">URL</th>";
	    list += "    <td colspan=\"3\">";
	    list += "    	<input type=\"text\" name=\"me_link[]\" value=\"\" id=\"me_link_"+ms+"\" required class=\"form-control\">";
	    list += "    </td>";
	    list += "</tr>";
	    var $menu_last = null;
        $menu_last = $menulist.find("tr:last");

        if($menu_last.size() > 0) {
            $menu_last.after(list);
        } else {
            $menulist.find("tbody").append(list);
        }

        $("#me_name_"+ms).focus();
            
	});
	
});

function frm_submit(f)
{
	if(confirm("변경사항을 적용하시겠습니까?"))
	{
        var me_links = document.getElementsByName('me_link[]');
        var reg = /^javascript/; 
    
    	for (i=0; i<me_links.length; i++){
            
    	    if( reg.test(me_links[i].value) ){ 
            
                alert('링크에 자바스크립트문을 입력할수 없습니다.');
                me_links[i].focus();
                return false;
            }
        }
        return true;
	}
    return false;

}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>