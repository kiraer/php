<?php defined('IN_IA') or exit('Access Denied');?>	<div id="footer">
		<span class="pull-left">
			<p><?php  if(empty($_W['setting']['copyright']['footerleft'])) { ?>Powered by <a href="http://www.b2ctui.com"><b>微动力</b></a> v<?php echo IMS_VERSION;?> &copy; 2014 <a href="http://www.b2ctui.com">www.b2ctui.com</a><?php  } else { ?><?php  echo $_W['setting']['copyright']['footerleft'];?><?php  } ?></p>
		</span>
		<span class="pull-right">
			<p><?php  if(empty($_W['setting']['copyright']['footerright'])) { ?><a href="http://www.b2ctui.com">关于微动力</a>&nbsp;&nbsp;<a href="http://bbs.b2ctui.com">微动力帮助</a><?php  } else { ?><?php  echo $_W['setting']['copyright']['footerright'];?><?php  } ?>&nbsp;&nbsp;<?php  echo $_W['setting']['copyright']['statcode'];?></p>
		</span>
	</div>
	<div class="emotions" style="display:none;"></div>
</body>
</html>