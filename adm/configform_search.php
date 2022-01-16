<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '검색엔진 최적화(SEO)';
include_once ('./admin.head.php');


get_admin_token();
?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="" id="token">

	  <div class="x_content">

			<div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
				<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">SEO태그 설정</a>
				</li>
				<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">고급설정</a>
				</li>
			  </ul>
			  <div class="clearfix"></div>

			  <div id="myTabContent" class="tab-content">
				<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">	
					<div class="x_title">
						<h4><span class="fa fa-check-square"></span> SEO 태그 사용 설정 <small></small></h4>
						<div class="clearfix"></div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_seo_use">SEO 태그 사용 설정</span>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
						 <div class="radio">
							<label><input type="radio" class="flat" name="cf_add_seo_use" id="cf_add_seo_use1" value="1" <? if($config['cf_add_seo_use'] == "1") { ?>checked="" <? } ?> required /> 사용함</label>
	                        <label><input type="radio" class="flat" name="cf_add_seo_use" id="cf_add_seo_use0" value="0" <? if($config['cf_add_seo_use'] == "0") { ?>checked="" <? } ?> /> 사용안함</label>
						</div>
						</div>
					</div>
					
					<div class="clearfix"></div>
					<div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab1" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#cf_add_meta_common" id="cf_add_meta_common-tab" role="tab" data-toggle="tab" aria-expanded="true">공통정보 설정</a>
						</li>
                        <li role="presentation" class="hidden"><a href="#de_order_info" role="tab" id="de_order_info-tab" data-toggle="tab" aria-expanded="false">카테고리 설정</a>
						</li>
                        <li role="presentation" class="hidden"><a href="#de_pay_info" role="tab" id="de_pay_info-tab" data-toggle="tab" aria-expanded="false">상세페이지 설정</a>
						</li>
                        <li role="presentation" class=""><a href="#de_shipping_info" role="tab" id="de_shipping_info-tab" data-toggle="tab" aria-expanded="false">게시판 설정</a>
						</li>


                      </ul>
                      <div id="myTabContent2" class="tab-content">
                      	
					  <div class="clearfix"></div>

                        <div role="tabpanel" class="tab-pane fade active in" id="cf_add_meta_common" aria-labelledby="cf_add_meta_common-tab">
							<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> 공통정보 설정 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
        					
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">타이틀 : Title</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_common_title"  value="<?php echo $config['cf_add_meta_common_title']; ?>" id="cf_add_meta_common_title" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		
                            <div class="ln_solid"></div>
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_meta_common_author">메타태그 : Author</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_common_author"  value="<?php echo $config['cf_add_meta_common_author']; ?>" id="cf_add_meta_common_author" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_meta_common_description">메타태그 : Description</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_common_description" rows="5"><?=$config['cf_add_meta_common_description']?></textarea>
        						</div>
        					</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_meta_common_keywords">메타태그 : Keywords</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_common_keywords" rows="5"><?=$config['cf_add_meta_common_keywords']?></textarea>
        						</div>
        					</div>
        					
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="de_order_info" aria-labelledby="de_order_info-tab">
							<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> 카테고리 설정 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
							                  	
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">타이틀 : Title</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_category_title"  value="<?php echo $config['cf_add_meta_category_title']; ?>" id="cf_add_meta_category_title" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		
                            <div class="ln_solid"></div>
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Author</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_category_author"  value="<?php echo $config['cf_add_meta_category_author']; ?>" id="cf_add_meta_category_author" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Description</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_category_description" rows="5"><?=$config['cf_add_meta_category_description']?></textarea>
        						</div>
        					</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Keywords</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_category_keywords" rows="5"><?=$config['cf_add_meta_category_keywords']?></textarea>
        						</div>
        					</div>
        					                  
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="de_pay_info" aria-labelledby="de_pay_info-tab">
							<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> 상세페이지 설정 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
							                  	
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">타이틀 : Title</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_detail_title"  value="<?php echo $config['cf_add_meta_detail_title']; ?>" id="cf_add_meta_detail_title" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		
                            <div class="ln_solid"></div>
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Author</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_detail_author"  value="<?php echo $config['cf_add_meta_detail_author']; ?>" id="cf_add_meta_detail_author" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Description</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_detail_description" rows="5"><?=$config['cf_add_meta_detail_description']?></textarea>
        						</div>
        					</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Keywords</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_detail_keywords" rows="5"><?=$config['cf_add_meta_detail_keywords']?></textarea>
        						</div>
        					</div>
        					
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="de_shipping_info" aria-labelledby="de_shipping_info-tab">
							<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> 게시판 설정 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
							                  	
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">타이틀 : Title</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_bbs_title"  value="<?php echo $config['cf_add_meta_bbs_title']; ?>" id="cf_add_meta_bbs_title" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		
                            <div class="ln_solid"></div>
                        	<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Author</span>
                    			</label>
                    			<div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="cf_add_meta_bbs_author"  value="<?php echo $config['cf_add_meta_bbs_author']; ?>" id="cf_add_meta_bbs_author" class="form-control col-md-7 col-xs-12" size="30">
                    			</div>
                    		</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Description</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_bbs_description" rows="5"><?=$config['cf_add_meta_bbs_description']?></textarea>
        						</div>
        					</div>
                    		  
                            <div class="ln_solid"></div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="de_admin_company_saupja_no">메타태그 : Keywords</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_meta_bbs_keywords" rows="5"><?=$config['cf_add_meta_bbs_keywords']?></textarea>
        						</div>
        					</div>
        					
                        </div>

                      </div>
                    </div>


				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
					<div class="" role="tabpanel" data-example-id="togglable-tabs">
					
                      <ul id="myTab2" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#cf_add_html_pc-dv" id="cf_add_html_head)pc-tab" role="tab" data-toggle="tab" aria-expanded="true">PC</a>
						</li>
                        <li role="presentation" class=""><a href="#cf_add_html_mobile-dv" role="tab" id="cf_add_html_mobile-tab" data-toggle="tab" aria-expanded="false">Mobile</a>
						</li>

                      </ul>
                      <div id="myTabContent2" class="tab-content">
                      
                      	<div role="tabpanel" class="tab-pane fade active in" id="cf_add_html_pc-dv" aria-labelledby="cf_add_html_pc-tab">
                      	
        					<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> HTML 태그 직접입력 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
    					
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_html_head_pc">Head 태그 소스</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_html_head_pc" rows="5"><?=$config['cf_add_html_head_pc']?></textarea>
        						</div>
        					</div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_html_body_pc">Body 태그 소스</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_html_body_pc" rows="5"><?=$config['cf_add_html_body_pc']?></textarea>
        						</div>
        					</div>
        					<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> 검색로봇 접근제어 설정 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
    						<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_robots_pc">Robots.txt 내용</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_robots_pc" rows="5"><?=$config['cf_add_robots_pc']?></textarea>
        						</div>
        					</div>
                        </div>
                        
                      	<div role="tabpanel" class="tab-pane fade" id="cf_add_html_mobile-dv" aria-labelledby="cf_add_html_mobile-tab">
        					
        					<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> HTML 태그 직접입력 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_html_head_mobile">Head 태그 소스</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_html_head_mobile" rows="5"><?=$config['cf_add_html_head_mobile']?></textarea>
        						</div>
        					</div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_html_body_mobile">Body 태그 소스</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_html_body_mobile" rows="5"><?=$config['cf_add_html_body_mobile']?></textarea>
        						</div>
        					</div>
        					<div class="x_title">
        						<h4><span class="fa fa-check-square"></span> 검색로봇 접근제어 설정 <small></small></h4>
        						<div class="clearfix"></div>
        					</div>
        					<div class="form-group">
        						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cf_add_robots_mobile">Robots.txt 내용</span>
        						</label>
        						<div class="col-md-9 col-sm-9 col-xs-12">
        							<textarea class="resizable_textarea form-control" name="cf_add_robots_mobile" rows="5"><?=$config['cf_add_robots_mobile']?></textarea>
        						</div>
        					</div>
        					
                        </div>
                      </div>
                      
                      
                   </div>
                      
				</div>
			  </div>
			</div>

		</div>


	  <div class="x_content">
		  <div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
			  <input type="submit" class="btn btn-success" value="저장"></input>

			</div>
		  </div>
	  </div>

	</form>

	</div>
  </div>
</div>

<script>
$(function(){
    

});

function fconfigform_submit(f)
{
    f.action = "./configform_search_update.php";
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
