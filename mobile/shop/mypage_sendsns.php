<?php
include_once('./_common.php');
?>
<!DOCTYPE html>
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
				<h1 class="title"><span>친구 초대하기</span></h1>
				<a class="btn_closed" name="btn_closed"><span class="blind">닫기</span></a>
			</div>
    	<div class="grid">
    
    		<div class="gray_box cont">
    			<h2 class="g_title_05">주소를 복사해서 친구, 지인들을 라이프라이크로 초대해보세요.</h2>
    			<div class="inp_wrap">
    				<div class="inp_ele r_btn">
    				<div class="input"><input type="text" placeholder="" value="<?php echo G5_URL?>" id="clipboardURL"></div>
    				<button type="button" class="btn green" onclick="copy_to_clipboard()">복사</button>
    				</div>
    			</div>
    		</div>
    		
    		<div class="info_box">
    			<p class="ico_import">안내사항</p>
    			<ul class="hyphen">
    				<li><em>회원가입 시 추천인 아이디를 입력하시면 추천인과 본인에게 적립금 1,000원씩 드립니다.</em></li>
    				<li><em>추천인의 아이디를 정확히 입력해 주세요.</em></li>
    			</ul>
    		</div>
    
    		<div class="title_bar none alignC">
    			<h2 class="g_title_05">채널을 선택해 주세요.</h2>
    		</div>
    		<ul class="sns_link">					
    			<li><a class="sns naver" onclick="sendSns('naver','<?php echo G5_URL;?>','<?php echo $config['cf_title']?>')"><span>네이버로 초대하기</span></a></li>
                <li><a class="sns talk" onclick="sendSns('kakao','<?php echo G5_URL;?>','<?php echo $config['cf_title']?>')"><span>카카오톡으로 초대하기</span></a></li>
                <li><a class="sns facebook" onclick="sendSns('facebook','<?php echo G5_URL;?>','<?php echo $config['cf_title']?>')"><span>페이스북으로 초대하기</span></a></li>
    		</ul>
    	</div>
    </div>
</section>
	
<script>
Kakao.init('7bc3289136239ac358ca94d102d590c7');

$(document).ready(function(){
	$('a[name="btn_closed"]').click(function() {
		history.back();
	});
});

$(function() {
	
});


function copy_to_clipboard() {
  var copyText = document.getElementById("clipboardURL");
  copyText.select();
  document.execCommand("Copy");
  alert("주소가 복사되었습니다. \n원하는 곳에 붙여넣기(Ctrl+V)해주세요.");
}

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

            location.href=o.url;
            return false;
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
		          title: txt,
		          //description: "<?php echo get_text($config['cf_add_meta_common_description']) ?>",
		          //imageUrl: '<?php echo G5_IMG_URL."/lifelike.png";?>',
				  imageUrl: '',
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
        	return false;
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