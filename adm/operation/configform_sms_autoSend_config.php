<?php
$sub_menu = "200130";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = 'SMS 자동발송 설정';
include_once ('../admin.head.php');

if($sf_type == "") $sf_type = "sms";
if($sf_cate == "") $sf_cate = "주문";

$token = get_admin_token();
?>


<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="x_panel">

    		<div class="x_content">

    			<div class="" role="tabpanel" data-example-id="togglable-tabs">
    			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
    			  	<li role="presentation" class="<?php echo ($sf_type=="sms"?"active":"") ?>"><a href="./configform_sms_autoSend_config.php?sf_type=sms" >SMS</a></li>
					<li role="presentation" class="<?php echo ($sf_type=="kakao"?"active":"") ?>"><a href="./configform_sms_autoSend_config.php?sf_type=kakao" >카카오톡</a></li>
    			  </ul>
    			  <div class="clearfix"></div>

    			  <div id="myTabContent" class="tab-content">
    				<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="sms-tab">

    					<div class="" role="tabpanel2" data-example-id="togglable-tabs">
                          <ul id="myTab1" class="nav nav-tabs bar_tabs" role="tablist">
                         <?php 
                        $catesql  = " select  sf_cate from lt_sms_form where   sf_type = '{$sf_type}' group by sf_cate";
                        $cateresult = sql_query($catesql);
                        for ($i=0; $row=sql_fetch_array($cateresult); $i++)
    					{
    					?>
    			  			<li role="presentation" class="<?php echo ($sf_cate==$row['sf_cate']?"active":"") ?>"><a href="./configform_sms_autoSend_config.php?sf_type=<?php echo $sf_type ?>&sf_cate=<?php echo $row['sf_cate'] ?>" ><?php echo $row['sf_cate'] ?></a></li>
						<?php } ?>
                          </ul>

				<div class="clearfix"></div>
    	
                <div id="myTabContent2" class="tab-content">

				<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
            	<input type="hidden" name="token" value="<?php echo $token ?>" id="token">
            	<input type="hidden" name="sf_type" value="<?php echo $sf_type ?>" id="sf_type">
            	<input type="hidden" name="sf_cate" value="<?php echo $sf_cate ?>" id="sf_cate">

                  <div role="tabpanel2" class="tab-pane fade active in" id="sms_order_info" aria-labelledby="sms_order_info-tab">
                  
					<div class="x_title">
						<h4><span class="fa fa-check-square"></span> <?php echo $sf_cate ?> <small></small></h4>
						<div class="clearfix"></div>
					</div>
					
                    <div class="tbl_head01 tbl_wrap">
                      <table>
                        <thead>
                        <tr>
                          <th class="col-md-2 col-sm-2 col-xs-12 text-center">발송케이스</th>
                          <th class="col-md-5 col-sm-5 col-xs-12 text-center">고객</th>
                          <th class="col-md-5 col-sm-5 col-xs-12 text-center">운영자</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $rowspan = "2";
                        if($sf_type=="kakao") $rowspan = "3";
    
                        $sql  = " select  * from lt_sms_form where   sf_type = '{$sf_type}' and sf_cate = '{$sf_cate}' ";
                        $result = sql_query($sql);
                        for ($i=0; $row=sql_fetch_array($result); $i++)
    					{
    					?>
                          <tr>
                            <td class="text-center" rowspan="<?php echo $rowspan?>">
                              <div class="container col-md-6 col-sm-6 col-xs-12">
                                <span name="sms_send_case" id="sf_user_msg_<?php echo $i ?>_title"><?php echo $row['sf_title'] ?></span><br>
                                <div class="text-left col-md-12 col-sm-12 col-xs-12" >
                                  <label><input type="checkbox" name="sf_user_use[<?php echo $row['sf_no'] ?>]" id="sf_user_use_<?php echo $i ?>" value="1" <?php echo get_checked($row['sf_user_use'], '1');?> /> 고객  </label><br>
                                  <label><input type="checkbox" name="sf_admin_use[<?php echo $row['sf_no'] ?>]" id="sf_admin_use_<?php echo $i ?>" value="1" <?php echo get_checked($row['sf_admin_use'], '1');?> /> 운영자  </label>

            						<input type="hidden" name="sf_no[]" value="<?php echo $row['sf_no'] ?>">
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="container col-md-6 col-sm-6 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                  <textarea class="resizable_textarea form-control" rows="5" name="sf_user_msg[]" id="sf_user_msg_<?php echo $i ?>" send_case="sf_user_msg_<?php echo $i ?>"><?php echo $row['sf_user_msg'] ?></textarea>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                                  <button type = 'button' class="btn btn-sm btn-default" name="user_priview" data="<?php echo $i ?>">미리보기</button>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                                  <span name="sf_user_msg_<?php echo $i ?>_counter" send_case="sf_user_msg_<?php echo $i ?>"><font color='red'>0</font></span>/300
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="container col-md-6 col-sm-6 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                  <textarea class="resizable_textarea form-control" rows="5" name="sf_admin_msg[]" id="sf_admin_msg_<?php echo $i ?>" send_case="sf_admin_msg_<?php echo $i ?>"><?php echo $row['sf_admin_msg'] ?></textarea>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                                  <button type = 'button' class="btn btn-sm btn-default" id="admin_priview" data="<?php echo $i ?>">미리보기</button>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                                  <span name="sf_admin_msg_<?php echo $i ?>_counter" send_case="sf_admin_msg_<?php echo $i ?>"><font color='red'>0</font></span>/300
                                </div>
                              </div>
                            </td>
                        </tr>
                        <?php if($sf_type=="kakao"){?>
                        <tr>
                        	<td>
                            	<div class="container col-lg-12">
                                	<div class="col-md-4 col-sm-4 col-xs-12 text-left">
                            			<label>고객 알림톡 템플릿 코드</label>
                            		</div>
                                	<div class="col-md-8 col-sm-8 col-xs-12 text-left">
                                		<input type="text" name="sf_user_template_code[]" value="<?php echo $row['sf_user_template_code'] ?>">
                                	</div>
								</div>
							</td>
                        	<td>
                            	<div class="container col-lg-12">
                                	<div class="col-md-4 col-sm-4 col-xs-12 text-left">
                            			<label>운영자 알림톡 템플릿 코드</label>
                            		</div>
                                	<div class="col-md-8 col-sm-8 col-xs-12 text-left">
                                		<input type="text" name="sf_admin_template_code[]" value="<?php echo $row['sf_admin_template_code'] ?>">
                                	</div>
								</div>
							</td>
						</tr>
                        <?php }?>
                        <tr>
                        	<td>
                            	<div class="container col-lg-12">
                                	<div class="col-md-4 col-sm-4 col-xs-12 text-left">
                            			<label><input type="checkbox"  name="sf_user_timelimit_use[<?php echo $row['sf_no'] ?>]" id="sf_user_timelimit_use_<?php echo $i ?>" value="1" 
                            			         <?php echo get_checked($row['sf_user_timelimit_use'], '1');?> /> 고객 발송 기간 제한</label>
                            		</div>
                                	<div class="col-md-8 col-sm-8 col-xs-12 text-left">
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
                                	</div>
                            	</div>
                            </td>
                        	<td>
                            	<div class="container col-lg-12">
                                	<div class="col-md-4 col-sm-4 col-xs-12 text-left">
                            			<label><input type="checkbox" name="sf_admin_timelimit_use[<?php echo $row['sf_no'] ?>]" id="sf_admin_timelimit_use_<?php echo $i ?>" value="1" <?php echo get_checked($row['sf_admin_timelimit_use'], '1');?> /> 운영자 발송 기간 제한</label>
                            		</div>
                                	<div class="col-md-8 col-sm-8 col-xs-12 text-left">
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
                                	</div>
                            	</div>
                        	</td>
                        </tr>
                        <?php 
    					}
    					
    					sql_free_result($result);
    					if ($i == 0)
    					    echo '<tr><td colspan="3" class="empty_table">자료가 없습니다.</td></tr>';
                        ?>
                        
                        </tbody>

                      </table>
                    </div>
                  </div>
                  


    	  <div class="x_content">
    		  <div class="form-group">
    			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
    			  <input type="submit" class="btn btn-success" value="메시지 저장"></input>
    			</div>
    		  </div>
    	  </div>
    	</form>

                </div>
              </div>
			</div>
			
    
		</div>

	</div>


  	</div>


  </div>


</div>

</div>


<div class="modal fade" id="priview_modal" tabindex="-1" role="dialog" aria-labelledby="priview_modal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="priview_Label"></h4>
      </div>
      <div class="modal-body">
        <div id="scf_sms">
            <section class="scf_sms_box">
                <div class="scf_sms_img">
                    <textarea id="priview_textarea" name="priview_textarea" ></textarea>
                </div>
                <span id="priview_counter" class="scf_sms_cnt">0 / 300</span>
            </section>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
  $('textarea[name="sf_user_msg[]"], textarea[name="sf_admin_msg[]"]').keyup(function (e){

      var content = $(this).val();
      var counter_id = $(this).attr('id')+'_counter';
      var send_case = $(this).attr('send_case');


    //  $(this).height(((content.split('\n').length + 1) * 1.5) + 'em');
      $('span[name='+counter_id+'][send_case='+send_case+']').html('<font color="red">'+content.length + '</font>');
  });

  $('textarea[name="sf_user_msg[]"], textarea[name="sf_admin_msg[]"]').each(function (index, item) {
	  
      var content = $(item).val();
      var counter_id = $(item).attr('id')+'_counter';
      var send_case = $(item).attr('send_case');
    //  $(this).height(((content.split('\n').length + 1) * 1.5) + 'em');
      $('span[name='+counter_id+'][send_case='+send_case+']').html('<font color="red">'+content.length + '</font>');
		  
  });

  
});



$('button[name="user_priview"]').click(function(){
  var data_id = $(this).attr('data');
  
  $('#priview_Label').html($('#sf_user_msg_'+data_id+'_title').text());
  $('#priview_textarea').val($('#sf_user_msg_'+data_id).val());
  $('#priview_counter').html($('#sf_user_msg_'+data_id+'_counter').text());
  $('#priview_modal').modal('show');
});

$('button[name="admin_priview"]').click(function(){
  var data_id = $(this).attr('data');
  
  $('#priview_Label').html($('#sf_user_msg_'+data_id+'_title').text());
  $('#priview_textarea').val($('#sf_admin_msg_'+data_id).val());
  $('#priview_counter').html($('#sf_admin_msg_'+data_id+'_counter').text());
  $('#priview_modal').modal('show');
});

$(function(){


});

function fconfigform_submit(f)
{
    f.action = "./configform_sms_autoSend_config_update.php";
    return true;
}
</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>