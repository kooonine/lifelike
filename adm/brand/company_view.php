<?php

$required_mb_id = 'readonly';

$mb['mb_name'] = get_text($mb['mb_name']);
$mb['mb_nick'] = get_text($mb['mb_nick']);
$mb['mb_email'] = get_text($mb['mb_email']);
$mb['mb_homepage'] = get_text($mb['mb_homepage']);
$mb['mb_birth'] = get_text($mb['mb_birth']);
$mb['mb_tel'] = get_text($mb['mb_tel']);
$mb['mb_hp'] = get_text($mb['mb_hp']);
$mb['mb_addr1'] = get_text($mb['mb_addr1']);
$mb['mb_addr2'] = get_text($mb['mb_addr2']);
$mb['mb_addr3'] = get_text($mb['mb_addr3']);
$mb['mb_signature'] = get_text($mb['mb_signature']);
$mb['mb_recommend'] = get_text($mb['mb_recommend']);
$mb['mb_profile'] = get_text($mb['mb_profile']);
$mb['mb_1'] = get_text($mb['mb_1']);
$mb['mb_2'] = get_text($mb['mb_2']);
$mb['mb_3'] = get_text($mb['mb_3']);
$mb['mb_4'] = get_text($mb['mb_4']);
$mb['mb_5'] = get_text($mb['mb_5']);
$mb['mb_6'] = get_text($mb['mb_6']);
$mb['mb_7'] = get_text($mb['mb_7']);
$mb['mb_8'] = get_text($mb['mb_8']);
$mb['mb_9'] = get_text($mb['mb_9']);
$mb['mb_10'] = get_text($mb['mb_10']);

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>
    <div class="x_title">
    	<div class="pull-left">
        	<h4><span class="fa fa-check-square"></span> 판매자 정보<small></small></h4>
        </div>
        <div class="pull-right"><label class="nav navbar-right">
        
    	<?php if($cp['cp_status'] == "승인반려") { ?>
        <a href="<?php echo G5_ADMIN_URL?>/brand/company.php?w=c"><input type="button" value="재심사요청" class="btn_submit btn" ></a>
    	<?php } else if($cp['cp_status'] != "승인요청" && $cp['cp_status'] != "정보변경신청") { ?>
    	<a href="<?php echo G5_ADMIN_URL?>/brand/company.php?w=u"><input type="button" value="정보변경" class="btn_submit btn" ></a>
    	<?php } ?>
        </label></div>
        <div class="clearfix"></div>
    </div>

	<div class="x_content">
	  
        <div class="tbl_frm01 tbl_wrap">
            
            <form name="faddr1" id="faddr1" action="./company_form_update.php" method="post" >
            <input type="hidden" name="w" value="addr1">
            <input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
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
                <th rowspan="3">회사 주소</th>
                <td>
                    <input type="text" placeholder="" id="company_zip" name="company_zip" title="우편번호" readonly required class="frm_input readonly" value="<?php echo $cp['company_zip1'].$cp['company_zip2']?>">
                    <input type="button" class="btn_02 btn" onclick="win_zip('faddr1', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3','company_addr_jibeon');" value="주소 검색">
                    
                    <input type="submit" value="주소 변경" class="btn_01 btn" accesskey='s' onclick="return confirm('회사 주소를 변경하시겠습니까?');">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" title="상세주소" placeholder="" id="company_addr1"  name = "company_addr1" readonly="readonly" required="required" class="frm_input col-sm-6 readonly" value="<?php echo $cp['company_addr1']?>">
                </td>
            
            <tr>
                <td>
                    <input type="text" title="상세주소" placeholder="" id="company_addr2" name = "company_addr2"  class="frm_input col-sm-6" value="<?php echo $cp['company_addr2']?>">
                    <input type="hidden" id="company_addr3" name = "company_addr3"  value="<?php echo $cp['company_addr3']?>">
            		<input type="hidden" id = "company_jibeon" name="company_addr_jibeon"  value="<?php echo $cp['company_addr_jibeon']?>">
                </td>
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
            </form>
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
                <form name="faddr2" id="faddr2" action="./company_form_update.php" method="post" >
                <input type="hidden" name="w" value="addr2">
                <input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                
                    <label for="mb_zip" class="sound_only">우편번호</label>
                    <input type="text" name="cp_out_zip" value="<?php echo $cp['cp_out_zip']; ?>" id="cp_out_zip" class="frm_input readonly" size="5" maxlength="6">
                    <input type="button" class="btn_02 btn" onclick="win_zip('faddr2', 'cp_out_zip', 'cp_out_address1', 'cp_out_address2', 'cp_out_address3', 'cp_out_address_jibeon');" value="주소 검색">
                    <input type="submit" value="주소 변경" class="btn_01 btn" accesskey='s' onclick="return confirm('출고지 주소를 변경하시겠습니까?');">
                    <br>
                    
                    <input type="text" name="cp_out_address1" value="<?php echo $cp['cp_out_address1'] ?>" id="cp_out_address1" class="frm_input readonly" size="60">
                    <br>
                    
                    <input type="text" name="cp_out_address2" value="<?php echo $cp['cp_out_address2'] ?>" id="cp_out_address2" class="frm_input" size="60">
                    <input type="hidden" name="cp_out_address3" value="">            
                    <input type="hidden" name="cp_out_address_jibeon" value="">
                </form>
                </td>
            </tr>
            
            <tr>
                <th scope="row">반품/교환지 주소</th>
                <td class="td_addr_line">
                <form name="faddr3" id="faddr3" action="./company_form_update.php" method="post" >
                <input type="hidden" name="w" value="addr3">
                <input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                
                    <label for="mb_zip" class="sound_only">우편번호</label>
                    <input type="text" name="cp_return_zip" value="<?php echo $cp['cp_return_zip']; ?>" id="cp_return_zip" class="frm_input readonly" size="5" maxlength="6">
                    <input type="button" class="btn_02 btn" onclick="win_zip('faddr3', 'cp_return_zip', 'cp_return_address1', 'cp_return_address2', 'cp_return_address3', 'cp_return_address_jibeon');" value="주소 검색">
                    <input type="submit" value="주소 변경" class="btn_01 btn" accesskey='s' onclick="return confirm('반품/교환지 주소를 변경하시겠습니까?');">
                    <br>
                    
                    <input type="text" name="cp_return_address1" value="<?php echo $cp['cp_return_address1'] ?>" id="cp_return_address1" class="frm_input readonly" size="60">
                    <br>
                    
                    <input type="text" name="cp_return_address2" value="<?php echo $cp['cp_return_address2'] ?>" id="cp_return_address2" class="frm_input" size="60">
                    <input type="hidden" name="cp_return_address3" value="" >            
                    <input type="hidden" name="cp_return_address_jibeon" value="">
                </form>
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
    <!-- 
    <div class="pull-right">
        <input type="submit" value="정보변경신청" class="btn_submit btn" accesskey='s'>
    </div>
     -->
</form>


<script>
$(function(){

});

</script>
