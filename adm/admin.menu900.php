<?php
$menu['menu900'] = array (
    array('900000', '커뮤니티관리', G5_ADMIN_URL.'/', 'community')
    
    //,array('900100', '게시판관리', G5_ADMIN_URL.'/', '', array (
        ,array('900110', '게시판통합관리', G5_ADMIN_URL.'/community/board_management.php', 'board_management')
        //,array('900120', '게시판 생성/수정', G5_ADMIN_URL.'/community/board_create.php', 'board_create')
    ,array('900130', '게시물 관리', G5_ADMIN_URL.'/community/post_management.php', 'post_management')
    ,array('900140', 'FAQ 관리', G5_ADMIN_URL.'/community/faqlist.php', 'faqmasterlist')
        
        
        //,array('900191', '게시판관리', ''.G5_ADMIN_URL.'/board_list.php', 'bbs_board')
        //,array('900192', '글,댓글 현황', G5_ADMIN_URL.'/write_count.php', 'scf_write_count')
    //))
    
    //,array('900200', '문의/리뷰관리', G5_ADMIN_URL.'/', '', array (
        ,array('900210', '고객문의관리', G5_ADMIN_URL.'/community/help_management.php', 'help_detail')
        ,array('900220', '상품문의관리', G5_ADMIN_URL.'/shop_admin/itemqalist.php', 'scf_item_qna')    
    
        //,array('900220', '제휴관리', G5_ADMIN_URL.'/community/partnership_management.php', 'partnership_management')
        
        ,array('900230', '리뷰관리', G5_ADMIN_URL.'/community/review_management.php', 'review_management')
        //,array('900230', '리뷰관리', G5_ADMIN_URL.'/shop_admin/itemuselist.php', 'scf_ps')
    //))
    
    /*
    ,array('700100', '회원설정', G5_ADMIN_URL.'/', '', array (
        array('700110', '회원가입항목설정', G5_ADMIN_URL.'/member_config.php', 'member_config')
    ))
    
    ,array('700200', '회원정보', G5_ADMIN_URL.'/', '', array (
        array('700210', '회원정보조회', G5_ADMIN_URL.'/member_list.php', 'mb_list')
    ))
    */
);
?>