<?php
$sub_menu = "900110";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');


if($wr_type == "") $wr_type = "1";
?>
<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
  			<div class="x_content">
  				<div class="" role="tabpanel" data-example-id="togglable-tabs">
            	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            		<li role="presentation" class="<?php echo ($wr_type=="1"?"active":"") ?>"><a href="./member_form.php?w=&mb_id=<?php echo $mb_id?>&mode=6&wr_type=1" >적립금</a></li>
            		<li role="presentation" class="<?php echo ($wr_type=="2"?"active":"") ?>"><a href="./member_form.php?w=&mb_id=<?php echo $mb_id?>&mode=6&wr_type=2" >쿠폰</a></li>
		          </ul>
  			  	  <div class="clearfix"></div>
				</div>
			</div>
			<?php 
			if($wr_type=="1"){
			    
			    $sql_search = " where (1) ";
			    $sql_search .= " and (a.mb_id = '{$mb_id}') ";
			    $sst  = "po_id";
			    $sod = "desc";
			    $sql_order = " order by {$sst} {$sod} ";
			    
			    $sql = " select a.*, b.mb_name, c.mb_name as po_request_name, d.mb_name as po_approve_name
                         from {$g5['point_table']} a
                            inner join {$g5['member_table']} as b  on a.mb_id = b.mb_id
                            left outer join {$g5['member_table']} as c  on a.po_request_id = c.mb_id
                            left outer join {$g5['member_table']} as d  on a.po_approve_id = d.mb_id
                {$sql_search}
                {$sql_order} ";
                $result = sql_query($sql);
			?>    
			
		<div class="tbl_head01 tbl_wrap">
            <table>
        	<caption><?php echo $g5['title']; ?> 목록</caption>
              <thead>
                <tr>
                  <th colspan="9">
                    <div class="pull-right">
                      <a href="<?php echo G5_ADMIN_URL?>/operation/configform_saveMoney_management.php?sfl=mb_id&stx=<?php echo $mb_id?>" class="btn btn_02" target="_blank">자세히보기</a>
                    </div>
                  </th>
                </tr>
              <tr>
                <th rowspan="2">일자</th>
                <th colspan="3">적립금 내역</th>
                <th colspan="2">지급 처리자</th>
                <th rowspan="2">내용</th>
              </tr>
              <tr>
                <th >중가</th>
                <th >차감</th>
                <th >잔액</th>
                <th >요청자</th>
                <th >처리자</th>
              </tr>
              </thead>
              <tbody>
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++) {
                    if ($i==0 || ($row2['mb_id'] != $row['mb_id'])) {
                        $sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                        $row2 = sql_fetch($sql2);
                    }
            
                    $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);
            
                    $link1 = $link2 = '';
                    if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table']) {
                        $link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
                        $link2 = '</a>';
                    } elseif($row['po_rel_table'] == "@order") {
                        $link1 = '<a href="'.G5_ADMIN_URL.'/shop_admin/orderform.php?od_id='.$row['po_rel_id'].'" target="_blank">';
                        $link2 = '</a>';
                    }
            
                    $expr = '';
                    if($row['po_expired'] == 1)
                        $expr = ' txt_expired';
            
                    $bg = 'bg'.($i%2);
                ?>
                <tr>
            	  <td class="td_datetime"><?php echo $row['po_datetime'] ?></td>
            	  <td class="td_num td_pt"><?php echo ($row['po_point'] >= 0)?number_format($row['po_point']):"" ?></td>           
            	  <td class="td_num td_pt"><?php echo ($row['po_point'] < 0)?number_format($row['po_point']):"" ?></td>
                  <td class="td_num td_pt"><?php echo number_format($row['po_mb_point']) ?></td>
                  <td><?php echo ($row['po_request_id'] != '')?$row['po_request_name'].'('.$row['po_request_id'].')':'' ?></td>
                  <td><?php echo ($row['po_approve_id'] != '')?$row['po_approve_name'].'('.$row['po_approve_id'].')':'' ?></td>
            	  <td class="td_left"><?php echo $link1 ?><?php echo $row['po_content'] ?><?php echo $link2 ?></td>
                </tr>
                <?php
                }
            
                if ($i == 0)
                    echo '<tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
                ?>
              </tbody>
              </table>
          </div>
			<?php    
			} elseif($wr_type=="2"){
			    
			    
			    
			    $sql_common = " from lt_shop_coupon as a
                                 inner join lt_shop_coupon_mng as b
                                  on a.cm_no = b.cm_no
                              left outer join lt_shop_coupon_log as c
                                on a.cp_id = c.cp_id and c.mb_id = '{$mb_id}'
                            where a.mb_id IN ( '{$mb_id}', '전체회원' )
                            order by a.cp_no ";
			    
			    $sql =  "select  count(a.cp_no) cnt
                			    ,sum(if(a.cp_end is null or a.cp_end = '0000-00-00' or now() between a.cp_start and a.cp_end and c.cl_id is null,1,0)) use_cnt
                			    ,sum(if(c.cl_id is null,1,0)) nouse_cnt
                			    ,sum(if(a.cp_end = '0000-00-00' or now() between a.cp_start and a.cp_end,0,1)) close_cnt
                        {$sql_common} ";
			    $total = sql_fetch($sql);
			    
			    $sql = "select  a.*, b.*, c.cl_id, c.od_id, c.cl_datetime {$sql_common} ";
                $result = sql_query($sql);
            ?>
            <div class="tbl_head01 tbl_wrap">
                <table>
                <thead>
                <tr>
                    <th scope="col">발급된 쿠폰</th>
                    <th scope="col"><?php echo $total['cnt']?></th>
                    <th scope="col">사용가능 쿠폰</th>
                    <th scope="col"><?php echo $total['use_cnt']?></th>
                </tr>
                <tr>
                    <th scope="col">미사용 쿠폰</th>
                    <th scope="col"><?php echo $total['nouse_cnt']?></th>
                    <th scope="col">만료된 쿠폰</th>
                    <th scope="col"><?php echo $total['close_cnt']?></th>
                </tr>
                </thead>
                </table>
			</div>               
                
            <div class="tbl_head01 tbl_wrap">
                <table>
                <thead>
                <tr>
                    <th scope="col">쿠폰명</th>
                    <th scope="col">할인액(률)</th>
                    <th scope="col">발급일자</th>
                    <th scope="col">사용가능기간</th>            
                    <th scope="col">사용일자</th>
                    <th scope="col">주문번호</th>
                    <th scope="col">사용여부</th>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++) {
            
                    $link1 = '<a href="'.G5_ADMIN_URL.'/shop_admin/orderform.php?od_id='.$row['od_id'].'" target="_blank">';
                    $link2 = '</a>';
            
                    $bg = 'bg'.($i%2);
                ?>
            
                <tr class="<?php echo $bg; ?>">
                    <td class="td_name sv_use"><div><a href="<?php echo G5_ADMIN_URL?>/operation/configform_coupon_issuance_history.php?cm_no=<?php echo $row['cm_no']?>&sfl=mb_id&stx=<?php echo $mb_id?>" target="_blank"><?php echo $row['cm_subject']; ?></a></div></td>
                    <td class="td_name sv_use"><div><?php 
                            switch($row['cm_type']) {
                                    case '0':
                                        echo '할인금액 : '.number_format($row['cm_price']).'원';
                                        break;
                                    case '1':
                                        echo '할인율 : '.$row['cm_price'].'%';
                                        break;
                                }
                                ?></div></td>
                    <td class="td_name sv_use"><div><?php echo $row['cp_datetime']; ?></div></td>
                    <td class="td_name sv_use"><div><?php echo $row['cp_start'].'~'.$row['cp_end']; ?></div></td>
                    <td class="td_name sv_use"><div><?php echo $row['cl_datetime']; ?></div></td>
                    <td class="td_name sv_use"><div><?php echo ($row['od_id'])?$link1.$row['od_id'].$link2:''; ?></div></td>
                    <td class="td_cntsmall"><?php echo ($row['cl_id'])?'사용':'미사용'; ?></td>
                </tr>
            
                <?php
                }
            
                if ($i == 0)
                    echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
                ?>
                </tbody>
                </table>
            </div>
            
			    
			    
			<?php } ?>


			
		</div>
	</div>
</div>
<!-- @END@ 내용부분 끝 -->
