<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../../common.php');
$share_url = urldecode($_GET['share_url']);
$title = urldecode($_GET['title']);
$imgUrl = urldecode($_GET['imgUrl']);
?>
<html lang="ko">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>

</head>
<body>
	<section class="popup_container layer">
    	<div class="inner_layer">
    		<div id="lnb" class="header_bar">
    			<h1 class="title"><span>공유</span></h1>
    			<a href="#" class="btn_closed" ><span class="blind">닫기</span></a>
    		</div>
    
    		<div class="grid">
    
    			<div class="border_box alignC none">
    				<p class="sm tb_cell">공유할 채널을 선택해 주세요.</p>
    			</div>
    			<ul class="sns_link">						
    				<li><a href="#" class="sns naver" onclick="sendSns('naver','<?php echo $share_url;?>','<?php echo $title?>')"><span>네이버로 공유하기</span></a></li>
                    <li><a href="#" class="sns talk" onclick="sendSns('kakao','<?php echo $share_url;?>','<?php echo $title?>')"><span>카카오톡으로 공유하기</span></a></li>
                    <li><a href="#" class="sns facebook" onclick="sendSns('facebook','<?php echo $share_url;?>','<?php echo $title?>')"> <span>페이스북으로 공유하기</span></a></li>
    			</ul>
    			<a href="#" class="btn_closed" name="btn_closed"><span class="blind">닫기</span></a>
    		</div>
    	</div>
    </section>
		<!-- //popup -->
		<script>
		Kakao.init('7bc3289136239ac358ca94d102d590c7');
		
		$(document).ready(function(){
			$('a[name="btn_closed"]').click(function() {
				self.close();
				window.close();
			});
		});



		function sendSns(sns, url, txt)
		{
		    var o;
		    var _url = encodeURIComponent(url);
		    var _txt = encodeURIComponent(txt);
		    var _br  = encodeURIComponent('\r\n');
		 
		    switch(sns)
		    {
		        case 'facebook':
		            o = {
		                method:'popup',
		                url:'http://www.facebook.com/sharer/sharer.php?u=' + _url
		            };
		            break;
		 
		        case 'twitter':
		            o = {
		                method:'popup',
		                url:'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
		            };
		            break;
		 
		        case 'me2day':
		            o = {
		                method:'popup',
		                url:'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
		            };
		            break;
		        case 'naver':
		            o = {
		                method:'popup',
		                url:'https://share.naver.com/web/shareView.nhn?url=' +_url + '&title=' + _txt
		            };
		            break;
		        case 'kakao':
		        	Kakao.Link.sendDefault({
				        objectType: 'feed',
				        content: {
				          title: '게시글 공유하기',
				          description: txt,
				          imageUrl: '<?php echo $imgUrl;?>',
				          link: {
				            mobileWebUrl: url,
				            webUrl: url
				          }
				        },
				        social: {
				          likeCount: 0,
				          commentCount: 0,
				          sharedCount: 0
				        },
				        buttons: [
				          {
				            title: '웹으로 보기',
				            link: {
				              mobileWebUrl: url,
				              webUrl: url
				            }
				          },
				          {
				            title: '앱으로 보기',
				            link: {
				              mobileWebUrl: url,
				              webUrl: url
				            }
				          }
				        ]
				      });
			        
		            break;
		        case 'kakaotalk':
		            o = {
		                method:'web2app',
		                param:'sendurl?msg=' + _txt + '&url=' + _url + '&type=link&apiver=2.0.1&appver=2.0&appid=&appname=' + encodeURIComponent(''),
		                a_store:'itms-apps://itunes.apple.com/app/id362057947?mt=8',
		                g_store:'market://details?id=com.kakao.talk',
		                a_proto:'kakaolink://',
		                g_proto:'scheme=kakaolink;package=com.kakao.talk'
		            };
		            break;
		 
		        case 'kakaostory':
		            o = {
		                method:'web2app',
		                param:'posting?post=' + _txt + _br + _url + '&apiver=1.0&appver=2.0&appid=&appname=' + encodeURIComponent(''),
		                a_store:'itms-apps://itunes.apple.com/app/id486244601?mt=8',
		                g_store:'market://details?id=com.kakao.story',
		                a_proto:'storylink://',
		                g_proto:'scheme=kakaolink;package=com.kakao.story'
		            };
		            break;
		 
		        case 'band':
		            o = {
		                method:'web2app',
		                param:'create/post?text=' + _txt + _br + _url,
		                a_store:'itms-apps://itunes.apple.com/app/id542613198?mt=8',
		                g_store:'market://details?id=com.nhn.android.band',
		                a_proto:'bandapp://',
		                g_proto:'scheme=bandapp;package=com.nhn.android.band'
		            };
		            break;
		        
		 
		        default:
		            alert('지원하지 않는 SNS입니다.');
		            return false;
		    }
		 
		    switch(o.method)
		    {
		        case 'popup':
		            window.open(o.url);
		            break;
		 
		        case 'web2app':
		            if(navigator.userAgent.match(/android/i))
		            {
		                // Android
		                setTimeout(function(){ location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end'}, 100);
		            }
		            else if(navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i))
		            {
		                // Apple
		                setTimeout(function(){ location.href = o.a_store; }, 200);          
		                setTimeout(function(){ location.href = o.a_proto + o.param }, 100);
		            }
		            else
		            {
		                alert('이 기능은 모바일에서만 사용할 수 있습니다.');
		            }
		            break;
		    }
		}
		</script>
</body>
</html>
