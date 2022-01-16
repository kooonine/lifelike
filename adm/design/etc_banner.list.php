<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '기타 배너관리 / ' . $cp_category;
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from lt_banner_new ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common . "where cp_category =  '$cp_category' " ;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * ". $sql_common . "where cp_category =  '$cp_category'  order by cp_id asc ";

$result0 = sql_query($sql);
$result = sql_query($sql);
$result1 = sql_query($sql);
$result2 = sql_query($sql);
$result3 = sql_query($sql);
$result4 = sql_query($sql);
$result5 = sql_query($sql);
$result6 = sql_query($sql);
$result7 = sql_query($sql);
$result8 = sql_query($sql);



$sql_common_ca = " from lt_shop_category ";

$sql = "select * ". $sql_common_ca . " where main_yn ='Y' order by ca_id asc  ";
$categoryd = sql_query($sql);
$categorya = sql_fetch($sql);

?>



<!-- @START@ 내용부분 시작 -->


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 등록 된 배너<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>


            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                        <div class="local_ov01 local_ov"><span class="btn_ov01"><span class="ov_txt">전체 </span><span class="ov_num"> <?php echo $total_count; ?>건</span></span></div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a href="./main_banner.php" class="btn btn-success">목록</a>
                        <!-- <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>" class="btn btn-success">배너등록</a> -->
                    </div>
                </div>

                <div class="tbl_head01 tbl_wrap">
                    <div  class="banner_div">
                        <div class="aera-list">    
                            <div>상품상세-배너</div>
                            <ul  id="sortable0" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result0); $i++) { ?>
                                    <?php if($row['ca_name'] == '상품상세') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=상품상세&amp;ba_position=PRODUCT">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=상품상세&amp;ba_position=PRODUCT" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>    

                <!-- 이블 -->
                    <div  class="banner_div">
                        <div class="aera-list">    
                            <div>LIST상단-이불</div>
                            <ul  id="sortable0" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST상단-이불') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-이불&amp;ba_position=LIST_TOP">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-이불&amp;ba_position=LIST_TOP" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div  class="banner_div">
                        <div class="aera-list">    
                        <div>LIST좌측-이불</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result1); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST좌측-이불') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-이불&amp;ba_position=LIST_LEFT">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-이불&amp;ba_position=LIST_LEFT" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 베개/패드/토퍼 -->
                    <div  class="banner_div">
                        <div class="aera-list">
                            <div>LIST상단-베개/패드/토퍼</div>
                            <ul  id="sortable3" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result2); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST상단-베개/패드/토퍼') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-베개/패드/토퍼&amp;ba_position=LIST_TOP">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-베개/패드/토퍼&amp;ba_position=LIST_TOP" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div  class="banner_div">
                        <div class="aera-list">    
                        <div>LIST좌측-베개/패드/토퍼</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result3); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST좌측-베개/패드/토퍼') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-베개/패드/토퍼&amp;ba_position=LIST_LEFT">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-베개/패드/토퍼&amp;ba_position=LIST_LEFT" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 침대커버 -->
                    <div  class="banner_div">
                        <div class="aera-list">    
                            <div>LIST상단-침대커버</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result4); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST상단-침대커버') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-침대커버&amp;ba_position=LIST_TOP">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-침대커버&amp;ba_position=LIST_TOP" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div  class="banner_div">
                        <div class="aera-list">    
                        <div>LIST좌측-침대커버</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result5); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST좌측-침대커버') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-침대커버&amp;ba_position=LIST_LEFT">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-침대커버&amp;ba_position=LIST_LEFT" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 홈데코 -->
                    <div  class="banner_div">
                        <div class="aera-list">    
                            <div>LIST상단-홈데코</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result6); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST상단-홈데코') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-홈데코&amp;ba_position=LIST_TOP">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST상단-홈데코&amp;ba_position=LIST_TOP" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div  class="banner_div">
                        <div class="aera-list">    
                        <div>LIST좌측-홈데코</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result7); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST좌측-홈데코') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-홈데코&amp;ba_position=LIST_LEFT">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-홈데코&amp;ba_position=LIST_LEFT" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div  class="banner_div">
                        <div class="aera-list">    
                        <div>LIST좌측-브랜드</div>
                            <ul  id="sortable1" class= "banner_ul">
                                <?php for ($i = 0; $row = sql_fetch_array($result8); $i++) { ?>
                                    <?php if($row['ca_name'] == 'LIST좌측-브랜드') : ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-브랜드&amp;ba_position=LIST_LEFT">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <? else : ?>

                                    <? endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                                ?>
                            </ul>
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=LIST좌측-브랜드&amp;ba_position=LIST_LEFT" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal123 fade"  tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>


<script>

    $(function() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $("#sortable1").sortable();
        $("#sortable1").disableSelection();

    });

    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }

</script>


<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>