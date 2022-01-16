<?php
$sub_menu = "200140";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = 'SMS 자동발송 기간 설정';
include_once ('../admin.head.php');

$row['sf_user_timelimit_use'] = '1';
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="x_panel">
  	

  	  <div class="x_title">
  		<h4><span class="fa fa-check-square"></span> SMS 자동발송 기간 설정<small></small></h4>
  		<label class="nav navbar-right"></label>
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
          <tr>
            <th scope="row" rowspan="3">발송 제한 시간</th>
            <td colspan="3">
            	<label><input type="radio"  name="sf_user_timelimit_use[]" id="sf_user_timelimit_use_1" value="1" <?php echo get_checked($row['sf_user_timelimit_use'], '1');?> /> 전체 설정</label>&nbsp;&nbsp;&nbsp;&nbsp;
            	<label><input type="radio"  name="sf_user_timelimit_use[]" id="sf_user_timelimit_use_0" value="0" <?php echo get_checked($row['sf_user_timelimit_use'], '0');?> /> 개별 설정</label>
			</td>
          </tr>
          <tr>
            <td> 고객 : 
            	<?php   
        		        preg_match("/([0-9]{2}):([0-9]{2})/", $row['sf_user_starttime'], $m);
        		        $s = "<select name='sf_user_starttime_h[]' style='width:50px;'>";
                        for ($i=0; $i<=23; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['0']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select> : ";
                        
                        $s .= "<select name='sf_user_starttime_m[]' style='width:50px;'>";
                        for ($i=0; $i<=59; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['2']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select>";
                        
                        echo $s;
                ?> ~ <?php   
                        preg_match("/([0-9]{2}):([0-9]{2})/", $row['sf_user_endtime'], $m);
        		        $s = "<select name='sf_user_endtime_h[]' style='width:50px;'>";
                        for ($i=0; $i<=23; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['0']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select> : ";
                        
                        $s .= "<select name='sf_user_endtime_m[]' style='width:50px;'>";
                        for ($i=0; $i<=59; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['2']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select>";
                        
                        echo $s;
                ?> 까지 발송하지 않음
            </td>
          </tr>
          <tr>
            <td> 운영자 : 
            	<?php   
        		        preg_match("/([0-9]{2}):([0-9]{2})/", $row['sf_admin_starttime'], $m);
        		        $s = "<select name='sf_admin_starttime_h[]' style='width:50px;'>";
                        for ($i=0; $i<=23; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['0']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select> : ";
                        
                        $s .= "<select name='sf_admin_starttime_m[]' style='width:50px;'>";
                        for ($i=0; $i<=59; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['2']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select>";
                        
                        echo $s;
                ?> ~ <?php   
        		        preg_match("/([0-9]{2}):([0-9]{2})/", $row['sf_admin_endtime'], $m);
        		        $s = "<select name='sf_admin_endtime_h[]' style='width:50px;'>";
                        for ($i=0; $i<=23; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['0']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select> : ";
                        
                        $s .= "<select name='sf_admin_endtime_m[]' style='width:50px;'>";
                        for ($i=0; $i<=59; $i++) {
                            $s .= "<option value='$i'";
                            if ($i == $m['2']) {
                                $s .= " selected";
                            }
                            $s .= ">$i";
                        }
                        $s .= "</select>";
                        
                        echo $s;
                ?> 까지 발송하지 않음
            </td>
          </tr>
        </table>

          <div class="form-group">
            <div class="col-md-12 col-sm-12 col-xs-12 text-right"><br>
              <input type="submit" class="btn btn-primary" value="저장"></input>
            </div>
          </div>
  	  </div>




  	</form>

    </div>


  </div>


</div>

<script>
$(document).ready(function() {

});

$(function(){


});

function fconfigform_submit(f)
{
    f.action = "./configform_delivery_update.php";
    return true;
}
</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>
