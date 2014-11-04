<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<div id="header">
	<div class="logo pull-left" style="background:#36393a url('<?php  if(empty($_W['setting']['copyright']['blogo'])) { ?>./resource/image/blogo.png<?php  } else { ?><?php  echo $_W['attachurl'];?><?php  echo $_W['setting']['copyright']['blogo'];?><?php  } ?>') no-repeat;"><?php  if(!empty($_W['setting']['copyright']['sitename'])) { ?><?php  echo $_W['setting']['copyright']['sitename'];?><?php  } ?></div>
	<!-- 导航 -->
	<div class="hnav clearfix">
		<div class="row-fluid">
			<ul class="hnav-main text-center unstyled pull-left" style="width:55%;">
				<li class="hnav-parent <?php  if($do == 'profile') { ?>active<?php  } ?>"><a href="./index.php?do=profile">功能设置</a></li>
				<li class="hnav-parent <?php  if($do == 'global') { ?>active<?php  } ?>"><a href="./index.php?do=global">系统设置</a></li>
				<li><a target="main" href="test.php">模拟测试</a></li>
				<li class="hnav-parent">
					<a href="">常用</a>
					<ul class="hnav-child unstyled text-left">
						
						<li><a target="main" href="test.php">调试工具</a></li>
						<li><a target="main" href="<?php  echo create_url('setting/updatecache')?>">更新缓存</a></li>
					</ul>
				</li>
				<?php  if(IMS_FAMILY == 'v' || $_W['isfounder']) { ?><li class="hnav-parent"><a href="http://bbs.b2ctui.com/" target="_blank">微动力论坛</a></li><?php  } ?>
				<li class="hnav-parent"><a href="https://mp.weixin.qq.com/" target="_blank">公众平台</a></li>
				<li class="hnav-parent"><a href="http://bbs.b2ctui.com/thread-2296-1-1.html" target="_blank">帮助教程</a></li>
			</ul>
			<!-- 右侧管理菜单 -->
			<ul class="hnav-manage text-center unstyled pull-right">
				<li class="hnav-parent" id="wechatpanel">
					<a href="javascript:;"><i class="icon-chevron-down icon-large"></i><span id="current-account"><?php  if($_W['account']) { ?><?php  echo $_W['account']['name'];?><?php  } else { ?>请切换公众号<?php  } ?></span></a>
					<ul class="hnav-child unstyled text-left">
						<?php  $i = 1;?>
						<?php  if(is_array($wechats)) { foreach($wechats as $account) { ?>
							<li><a href="<?php  echo create_url('account/switch', array('id' => $account['weid']))?>" onclick="return ajaxopen(this.href, function(s) {switchHandler(s)})"><?php  echo $account['name'];?></a></li>
							<?php  if($i >= 10) { ?>
								<li style="background:#4f525b;"><a href="<?php  echo create_url('account/display')?>" target="main">点击查看更多</a></li>
								<?php  break;?>
							<?php  } ?>
							<?php  $i++;?>
						<?php  } } ?>
					</ul>
				</li>
				<li class="hnav-parent"><a href=""><i class="icon-user icon-large"></i><?php  echo $_W['username'];?></a></li>
				<li class="hnav-parent"><a href="<?php  echo create_url('member/logout')?>"><i class="icon-signout icon-large"></i>退出</a></li>
			</ul>
			<!-- end -->
		</div>
	</div>
	<!-- end -->
</div>
<!-- 头部 end -->
<div class="content-main">
	<table width="100%" height="100%" cellspacing="0" cellpadding="0" id="frametable">
		<tbody>
			<tr>
				<td valign="top" height="100%" class="content-left" style="">
					<div class="sidebar-nav" style="">
						<div id="snav-big">
						<?php  if(is_array($mset)) { foreach($mset as $k => $g) { ?>
						<?php  if($g['menus']) { ?>
						<?php 
						switch ($k) {
							case 'basic':
							  $snav_icon = 'icon-star';
							  break;
							case 'business':
							  $snav_icon = 'icon-briefcase';
							  break;
							case 'customer':
							  $snav_icon = 'icon-sitemap';
							  break;
							case 'activity':
							  $snav_icon = 'icon-gift';
							  break;
							case 'services':
							  $snav_icon = 'icon-cog';
							  break;
							default:
							  $snav_icon = 'icon-folder-open';
						}
						?>
						<span class="snav-big" type="<?php  echo $k;?>"><i class="<?php  echo $snav_icon;?>"></i><span><?php  echo $g['title'];?></span></span>
						<?php  } ?>
						<?php  } } ?>
						</div>
						<div id="snav">
						<?php  if(is_array($mset)) { foreach($mset as $k => $g) { ?>
						<?php  if($g['menus']) { ?>
						<?php  if(is_array($g['menus'])) { foreach($g['menus'] as $menu) { ?>
						<ul class="snav unstyled hide" id="<?php  echo $k;?>">
							<?php  if(is_array($menu['title'])) { ?>
							<li class="snav-header-list"><a href="<?php  echo $menu['title']['1'];?>" target="main"><?php  echo $menu['title']['0'];?></a></li>
							<?php  } else { ?>
							<li class="snav-header"><a href=""><?php  echo $menu['title'];?><i class="arrow"></i></a></li>
							<?php  } ?>
							<?php  if(!empty($menu['items'])) { ?>
							<?php  if(is_array($menu['items'])) { foreach($menu['items'] as $item) { ?>
							<li class="snav-list hide"><a href="<?php  echo $item['1'];?>" target="main"><?php  echo $item['0'];?></a><?php  if(!empty($item['childItems'])) { ?><a href="<?php  echo $item['childItems']['1'];?>" target="main" class="snav-small"><?php  echo $item['childItems']['0'];?></a><?php  } ?></li>
							<?php  } } ?>
							<?php  } ?>
						</ul>
						<?php  } } ?>
						<?php  } ?>
						<?php  } } ?>
						</div>
					</div>
				</td>
				<td valign="top" height="100%" style=""><iframe width="100%" scrolling="yes" height="100%" frameborder="0" style="min-width:800px; overflow:visible; height:100%;" name="main" id="main" src="<?php  echo $iframe;?>"></iframe></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
function max(a) {
	var b = a[0];
	for(var i=1;i<a.length;i++){ if(b<a[i])b=a[i]; }
	return b;
}
function currentMenuItem(a) {
	window.frames['main'].location.href= a;
}
function switchHandler(s) {
	var mainurl = window.frames['main'].location;
	if (window.frames['main'].location.href.indexOf('global') > -1) {
		window.top.location.href = '?do=profile';
	}
	window.top.location.href = '?do=profile';
	$('#current-account').html(s);
}
function strlen(str) {
		var n = 0;
		for(i=0;i<str.length;i++){
			var leg=str.charCodeAt(i);
			n+=1;
		}
		return n;
}
function leftScrollbar() {
	$('.content-left').mCustomScrollbar("destroy");
	$('.content-left').mCustomScrollbar({
		scrollButtons:{
			enable:true
		},
		theme:"dark-thin",
		autoHideScrollbar:false
	});
}

$(document).ready(function() {
	//顶部子导航
	$(".hnav").delegate(".hnav-parent", "mouseover", function(){
		var $this = this;
		if ($(this).attr('id') == 'wechatpanel') {
			if ($(this).attr('loading') == '1'){
				return false;
			}
			position();
			if (cookie.get('wechatloaded') == '1') {
				return true;
			}
			$($this).find(".hnav-child").html('<li><a>加载中</a></li>');
			$(this).attr('loading', '1');
			ajaxopen('<?php  echo create_url('member/wechat')?>', function(s){
				var obj = $($this).find(".hnav-child");
				var html = '';
				for (i in s) {
					html += '<li><a href="account.php?act=switch&id='+s[i]['weid']+'" onclick="return ajaxopen(this.href, function(s) {switchHandler(s)})">'+s[i]['name']+'</a></li>';
				}
				obj.html(html);
				$('#wechatpanel').attr('loading', '0');
			});
		} else {
			position();
		}
		function position() {
			var tmp = new Array();
			$($this).find(".hnav-child").show();
			$($this).find(".hnav-child li").each(function(i) {
				tmp[i] = strlen($(this).find("a").html());
			});
			$($this).find(".hnav-child li a").css("width", max(tmp)*18);
			$($this).find(".hnav-child").css("left", $($this).offset().left);
		}
		return false;
	});
	$(".hnav").delegate(".hnav-parent", "mouseout", function(){
		$(".hnav-child").hide();
	});
	//左侧导航
	var snav_type = $(".sidebar-nav #snav-big .snav-big:eq(0)").attr("type");
	$(".sidebar-nav #snav-big .snav-big:eq(0)").addClass("on");
	$(".sidebar-nav .snav[id="+snav_type+"]").removeClass("hide");
	//初始化第一条显示
	$(".sidebar-nav #snav-big .snav-big").each(function() {
		var a = $(this).attr("type");
		$(".sidebar-nav .snav[id="+a+"]").eq(0).find(".snav-header").addClass("open");
		$(".sidebar-nav .snav[id="+a+"]").eq(0).find(".snav-list").addClass("open");
	});
	$(".sidebar-nav #snav-big").delegate(".snav-big", "click", function(){
		var a = $(this).attr("type");
		$(".sidebar-nav #snav-big .snav-big").removeClass("on");
		$(this).addClass("on");
		$(".sidebar-nav .snav").addClass("hide");
		$(".sidebar-nav .snav[id="+a+"]").removeClass("hide");
		//右侧菜单滚动控制
		leftScrollbar();
	});
	$(".sidebar-nav").delegate(".snav-header", "click", function(){
		var a = $(this).hasClass("open");
		var b = $(this).parent().attr("id");
		$(".sidebar-nav .snav[id="+b+"] .snav-header").removeClass("open");
		$(".sidebar-nav .snav[id="+b+"] .snav-list").addClass("hide");
		if(a) {
			$(this).removeClass("open");
			$(this).parent().find(".snav-list").each(function(i) {
				$(this).addClass("hide");
			});
		} else {
			$(this).addClass("open");
			$(this).parent().find(".snav-list").each(function(i) {
				$(this).removeClass("hide");
			});
		}
		//右侧菜单滚动控制
		leftScrollbar();
		return false;
	});
	$(".sidebar-nav .snav").each(function() {
		if($(this).find(".snav-header").hasClass("open")) {
			$(this).find(".snav-list").each(function() {
				$(this).toggleClass("hide");
			});
		}
		$(this).find(".snav-list").each(function() {
			if($(this).hasClass("current")) {
				$(this).parent().find(".snav-header").toggleClass("open");
				$(this).parent().find(".snav-list").each(function() {
					$(this).toggleClass("hide");
				});
			}
		});
		$(this).find(".snav-list a,.snav-header-list a").click(function() {
			$(".snav-list,.snav-header-list").removeClass("current");
			$(this).parent().addClass("current");
			currentMenuItem($(this).attr("href"));
			return false;
		});
	});
});
$(function() {
	//调整框架宽高 兼容ie8
	$(".content-main, .content-main table td, .sidebar-nav #snav-big").height($(window).height()-40);
	$("#main").width($(window).width()-200);
	//右侧菜单滚动控制
	leftScrollbar();
});
$(window).resize(function(){
	//调整框架宽高 兼容ie8
	$(".content-main, .content-main table td, .sidebar-nav #snav-big").height($(window).height()-40);
	$("#main").width($(window).width()-200);
	//右侧菜单滚动控制
	leftScrollbar();
});
</script>
