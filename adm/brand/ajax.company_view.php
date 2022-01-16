<?php
include_once('./_common.php');

$mb = get_member($mb_id);

$sql = "select * from lt_member_company where mb_id = '{$mb_id}' ";
$cp = sql_fetch($sql);

$token = get_admin_token(); 
?>

<div id="modal_company_view_detail" class="modal modal_company_view_detail" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">입점사 승인 상세보기 팝업</h4>
  </div>
  <div class="modal-body">
  
  <form name="fmember" id="fmember" action="./company_form_update.php" method="post"  onsubmit="return fmember_submit(this);">
  <input type="hidden" name="w" id="approve_w" value="">
  <input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>">
  <input type="hidden" name="token" value="<?php echo $token ?>">
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 수수료 및 정산일 정보 입력<small></small></h4>
        </div>
        <div class="pull-right"><label class="nav navbar-right">
        </label></div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
	  
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
            	<th colspan="2">기본수수료</th>
            	<td><input type="text" placeholder="" id="cp_commission" name ="cp_commission" class="frm_input" size="5" value="<?php echo $cp['cp_commission']?>">%
            	</td>
			</tr>
            <tr>
            	<th rowspan="2">정산일</th>
            	<th>매출기간</th>
            	<td>전월 <input type="text" placeholder="" id="cp_calculate_date1" name ="cp_calculate_date1" class="frm_input" size="5" value="<?php echo $cp['cp_calculate_date1']?>">
            	~ 당월 <input type="text" placeholder="" id="cp_calculate_date2" name ="cp_calculate_date2" class="frm_input" size="5" value="<?php echo $cp['cp_calculate_date2']?>">
            	</td>
			</tr>
            <tr>
            	<th>정산일</th>
            	<td>익월 <input type="text" placeholder="" id="cp_calculate_date" name ="cp_calculate_date" class="frm_input" size="5" value="<?php echo $cp['cp_calculate_date']?>">일
            	</td>
			</tr>
			</tbody>
			</table>
            
            
		</div>
	</div>
  
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 판매자 정보<small></small></h4>
        </div>
        <div class="pull-right"><label class="nav navbar-right">
        </label></div>
        <div class="clearfix"></div>
    </div>

	<div class="x_content">
	  
        <div class="tbl_frm01 tbl_wrap">
        <?php 
        if($cp['cp_status'] == "정보변경신청") {
            
            $sql = "select * from lt_member_company_approve where mb_id = '{$mb_id}' and cp_status = '정보변경신청' order by cp_no desc limit 1  ";
            $cp = sql_fetch($sql);
            echo '<input type="hidden" name="cp_no" value="'.$cp['cp_no'].'">';
        }
        ?>
            
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
            	<th>사업자구분</th>
            	<td><?php echo ($cp['company_type'])?"일반사용자":"법인사용자"; ?></td>
			</tr>
            <tr>
                <th>사업자등록번호</th>
                <td>
                    <?php echo $cp['company_no']?>
                </td>
            </tr>
            <tr>
                <th>상호명</th>
                <td>
                    <?php echo $cp['company_name']?>
                </td>
            </tr>
            <tr>
                <th>회사 주소</th>
                <td><?php echo $cp['company_zip1'].$cp['company_zip2']?><br/><?php echo $cp['company_addr1']?><br/><?php echo $cp['company_addr2']?></td>
            </tr>
            
            <tr>
                <th>업태/종목</th>
                <td>
                    <?php echo $cp['company_category']?>
                </td>
            </tr>
            <tr>
                <th>통신판매업신고번호</th>
                <td>
                    <?php echo $cp['cp_tongsin_no']?>
                </td>
            </tr>
            
            <tr>
                <th>대표자 성명</th>
                <td>
                    <?php echo $cp['company_leader']?>
                </td>
            </tr>
            <tr>
                <th>대표 전화번호<br>(회사 연락처)</th>
                <td>
                    <?php echo $cp['company_hp']?>
                </td>
            </tr>
            <tr>
                <th>팩스번호</th>
                <td><?php echo $cp['cp_fax']?></td>
            </tr>
			</tbody>
			</table>
            
		</div>
	</div>
	
    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 파트너 담당자 정보<small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
    </div>
    
    
	<div class="x_content">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th>담당자 성명</th>
                <td><?php echo $mb['mb_name'] ?></td>
            </tr>
            <tr>
                <th>로그인 ID</th>
                <td><?php echo $mb['mb_id'] ?></td>
            </tr>
            <tr>
                <th>이메일 주소</th>
                <td><?php echo $mb['mb_email'] ?></td>
            </tr>
            <tr>
                <th>휴대전화 번호</th>
                <td><?php echo $mb['mb_hp'] ?></td>
            </tr>
            <tr>
                <th>연락처</th>
                <td><?php echo $mb['mb_tel']?></td>
            </tr>
			</tbody>
			</table>
		</div>
	</div>
    
	
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 정산 담당자 정보<small></small></h4>
		</div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th>담당자 성명</th>
                <td><?php echo $cp['jeongsan_mb_name'] ?></td>
            </tr>
            <tr>
                <th>이메일 주소</th>
                <td><?php echo $cp['jeongsan_mb_email'] ?></td>
            </tr>
            <tr>
                <th>휴대전화 번호</th>
                <td><?php echo $cp['jeongsan_mb_tel'] ?></td>
            <tr>
                <th>연락처</th>
                <td><?php echo $cp['jeongsan_mb_hp'] ?></td>
			</tbody>
			</table>
		</div>
	</div>
    
	
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 배송 담당자 정보<small></small></h4>
		</div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
    
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th>담당자 성명</th>
                <td><?php echo $cp['delivery_mb_name'] ?></td>
            </tr>
            <tr>
                <th>이메일 주소</th>
                <td><?php echo $cp['delivery_mb_email'] ?></td>
            </tr>
            <tr>
                <th>휴대전화 번호</th>
                <td><?php echo $cp['delivery_mb_tel'] ?></td>
            </tr>
            <tr>
                <th>연락처</th>
                <td><?php echo $cp['delivery_mb_hp'] ?></td>
            </tr>
			</tbody>
			</table>
		</div>
	</div>
    
	
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> CS 담당자 정보<small></small></h4>
		</div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
    
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th>담당자 성명</th>
                <td><?php echo $cp['cs_mb_name'] ?></td>
            </tr>
            <tr>
                <th>이메일 주소</th>
                <td><?php echo $cp['cs_mb_email'] ?></td>
            </tr>
            <tr>
                <th>휴대전화 번호</th>
                <td><?php echo $cp['cs_mb_tel'] ?></td>
            </tr>
            <tr>
                <th>연락처</th>
                <td><?php echo $cp['cs_mb_hp'] ?></td>
            </tr>
			</tbody>
			</table>
		</div>
	</div>
    
    
	
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 정산 정보<small></small></h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
    
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th rowspan="3">정산 대금 입금 계좌</th>
                <td><?php echo $cp['cp_bank_name'] ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $cp['cp_bank_account'] ?> <?php echo $cp['cp_bank_account_no'] ?>
               	</td>
            </tr>
            <tr>
                <td>일반(간이)사업자의 예금주는 대표자명 또는 사업자등록증의 상호명과 동일해야하며, <br/>
                법인사업자의 예금주는 사업자등록증의 법인명의와 동일해야 합니다.<br/>
                임금 계좌 변경시, 심사가 최종 승인된 이후 구매확정 주문 건 부터 변경된 계좌로 정산 됩니다.</td>
            </tr>
			</tbody>
			</table>
		</div>
	</div>
    
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 배송 정보<small></small></h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
            	<td colspan="2">※ 출고지 주소와 반품/교환지 주소는 주소록에서 수정/변경이 가능합니다.</td>
            </tr>
            
            <tr>
                <th scope="row">출고지 주소</th>
                <td class="td_addr_line">
                    <label for="mb_zip" class="sound_only">우편번호</label>
                    <?php echo $cp['cp_out_zip']; ?><br/><?php echo $cp['cp_out_address1'] ?><br/><?php echo $cp['cp_out_address2'] ?>
                </td>
            </tr>
            
            <tr>
                <th scope="row">반품/교환지 주소</th>
                <td class="td_addr_line">
                    <label for="mb_zip" class="sound_only">우편번호</label>
                    <?php echo $cp['cp_return_zip']; ?><br/><?php echo $cp['cp_return_address1'] ?><br/><?php echo $cp['cp_return_address2'] ?>
                </td>
            </tr>
            </tbody>
            </table>
		</div>
	</div>
    
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 판매자 개인정보처리방침 관리<small></small></h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
            	<td colspan="2">※ 정보통신서비스 제공자로부터 개인정보를 제공받은 사업자는 정보통신망법을 준수해야 하며, 정보통신망법 제27조의2에 따라 개인정보 처리방침을 정하여 공개할 의무가 있습니다.<br/>
회사 홈페이지에 별도로 ‘개인정보 처리방침’을 공개하지 않는 스마트스토어 판매자 회원의 경우 스마트스토어에서 제공하는 ‘개인정보 처리방침’ 공개 기능을 이용해 구매자에게 해당 내용을 고지 할 수 있습니다.</td>
            </tr>
            <tr>
                <th>개인정보처리방침공개 여부</th>
                <td><?php echo ($cp['cp_ps_open_use'])?"공개":"비공개" ?></td>
            </tr>
            <tr>
                <th>기타업무 위탁 업체 입력</th>
                <td><?php echo ($cp['cp_ps_mb_use'])?"있음":"없음" ?></td>
            </tr>
            <tr>
                <th rowspan="2">개인정보 보호 책임자</th>
                <td><?php echo $cp['cp_ps_mb_name'] ?></td>
            </tr>
            <tr>
                <td><?php echo $cp['cp_ps_mb_email'] ?></td>
            </tr>
            
            </tbody>
            </table>
		</div>
	</div>
    
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 입점몰 이미지 등록<small></small></h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
    	  
	<div class="x_content">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="mb_img">입점몰 이미지</label></th>
                <td>
                    <?php
                    $mb_dir = $mb['mb_id'];
                    $icon_file = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$mb['mb_id'].'_company_img.gif';
                    if (file_exists($icon_file)) {
                        $icon_url = G5_DATA_URL.'/company/'.$mb_dir.'/'.$mb['mb_id'].'_company_img.gif';
                        echo '<img src="'.$icon_url.'" alt="">';
                    }
                    ?>
        		</td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
    
    
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 서류제출<small></small></h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
    	  
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label>사업자 등록증</label></th>
            <td>
                <?php
                $company_file_name = $cp['company_file'];
                
                $company_file = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$company_file_name;
                if (file_exists($company_file)) {
                    $company_url = G5_DATA_URL.'/company/'.$mb_dir.'/'.$company_file_name;
                    echo '<a href="'.$company_url.'" download>'.$company_file_name.'</a>';
                }
                ?>
            </td>
        </tr>
        <?php  for ($i = 1; $i <= 4; $i++) {
            if($cp['company_file'.$i]){?>
        <tr>
            <th scope="row"><label>추가 서류제출 <?php echo $i?></label></th>
            <td>
                <?php
                    $company_file_name = $cp['company_file'.$i];
                    
                    $company_file = G5_DATA_PATH.'/company/'.$mb_dir.'/'.$company_file_name;
                    if (file_exists($company_file)) {
                        $company_url = G5_DATA_URL.'/company/'.$mb_dir.'/'.$company_file_name;
                        echo '<a href="'.$company_url.'" download>'.$company_file_name.'</a>';
                    }
                ?>
            </td>
        </tr>
        <?php } } ?>
        </tbody>
        </table>
    </div>
    </div>
    
    <div class="pull-right">
    	<?php if($cp['cp_status'] == "승인요청") { ?>
    	<input type="button" value="승인" class="btn_submit btn" onclick="approve('approve');">
    	<?php } ?>
    	
    	<?php if($cp['cp_status'] == "정보변경신청") { ?>
    	<input type="button" value="승인" class="btn_submit btn" onclick="approve('modifyapprove');">
    	<input type="button" value="반려" class="btn_submit btn" onclick="approve('modifyreturn');">
    	<?php } ?>
    	
    	<?php if($cp['cp_status'] == "탈퇴신청") { ?>
    	<input type="button" value="탈퇴완료" class="btn_submit btn" onclick="approve('leave');">
    	<?php } ?>
    	
    	<?php if($cp['cp_status'] != "승인반려") { ?>
    	<input type="button" value="승인반려" class="btn_submit btn" onclick="approve('return');">
    	<?php } ?>
    	
    </div>
    <div class="clearfix"></div>
    
    <div id="approve" hidden>
    <div class="x_title">
    	<div class="pull-left">
        	<h4 id="approve_title"><span class="fa fa-check-square"></span>승인</h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
    
	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label>담당자</label></th>
            <td><?php echo $member['mb_name'].'('.$member['mb_id'].')'?></td>
        </tr>
        <tr id="tr_cp_reason">
            <th scope="row"><label>사유</label></th>
            <td><input type="text" name="cp_reason" value="" id="cp_reason" maxlength="200" class="frm_input" size="80"></td>
        </tr>
        </tbody>
        </table>
     </div>
     </div>
    
    <div class="pull-right">
    	<input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
    </div>
    </div>
    
    <div class="x_title">
    	<div class="pull-left">
        	<h4>&nbsp;<small></small></h4>
		</div>
        <div class="pull-right"><label class="nav navbar-right"></div>
        <div class="clearfix"></div>
    </div>
</form>

    </div>
</div>
</div>
</div>
<script>
function approve(act)
{
	if(act == "approve" || act == "modifyapprove"){
		$("#approve_title").html('<span class="fa fa-check-square"></span> 승인');
		$("#approve").prop("hidden",false);
		$("#tr_cp_reason").prop("hidden",true);

	} else if(act == "return" || act == "modifyreturn"){
		$("#approve_title").html('<span class="fa fa-check-square"></span> 반려사유');
		$("#approve").prop("hidden",false);
		$("#tr_cp_reason").prop("hidden",false);
	} else if(act == "leave") {
		$("#approve_w").val(act);
		$("#fmember").submit();
	}
	
	$("#approve_w").val(act);
}

function fmember_submit(f)
{
	var w = $("#approve_w").val();
	if(act == "approve" || act == "modifyapprove"){
		if($("#cp_commission").val() == ""){
			alert("기본수수료를 입력해 주세요");
			$("#cp_commission").focus();
			return false;
		}
		if($("#cp_calculate_date1").val() == ""){
			alert("매출기간을 입력해 주세요");
			$("#cp_calculate_date1").focus();
			return false;
		}
		if($("#cp_calculate_date2").val() == ""){
			alert("매출기간을 입력해 주세요");
			$("#cp_calculate_date2").focus();
			return false;
		}
		if($("#cp_calculate_date").val() == ""){
			alert("정산일을 입력해 주세요");
			$("#cp_calculate_date").focus();
			return false;
		}

	} else if(act == "return" || act == "modifyreturn"){
		
	}
	return true;
}
</script>