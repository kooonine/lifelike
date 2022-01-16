<!-- popup -->		
<section class="popup_container layer" id="sendsns_popup" style="display: none;">
	<div class="inner_layer">
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>공유</span></h1>
			<a class="btn_closed" onclick="$('#sendsns_popup').css('display','none');"><span class="blind">닫기</span></a>
		</div>

		<div class="grid">

			<div class="border_box alignC none">
				<p class="sm tb_cell">공유할 채널을 선택해 주세요.</p>
			</div>
			<ul class="sns_link">
				<li><a href="#" class="sns naver" onclick="sendSns('naver','<?=G5_SHOP_URL.'/item.php?it_id='.$it_id ?>','<?=stripslashes($it['it_name'])?>')"><span>네이버로 공유하기</span></a></li>
				<li><a href="#" class="sns talk" onclick="sendSns('kakao','<?=G5_SHOP_URL.'/item.php?it_id='.$it_id ?>','<?=stripslashes($it['it_name'])?>')"><span>카카오로 공유하기</span></a></li>
				<li><a href="#" class="sns facebook" onclick="sendSns('facebook','<?=G5_SHOP_URL.'/item.php?it_id='.$it_id ?>','<?=stripslashes($it['it_name'])?>')"><span>페이스북으로 공유하기</span></a></li>
			</ul>
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
		          imageUrl: '<?php echo $mainimg;?>',
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
		            title: '자세히 보기',
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