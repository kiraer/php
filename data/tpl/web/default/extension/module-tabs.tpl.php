<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
	<li<?php  if($do == 'installed') { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('extension/module/installed');?>">已安装的模块</a></li>
	<li<?php  if($do == 'prepared') { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('extension/module/prepared');?>">安装模块</a></li>
	<li<?php  if($do == 'designer') { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('extension/module/designer');?>">设计新模块</a></li>
	<li><a href="http://bbs.b2ctui.com" target="_blank">查找更多模块</a></li>
	<?php  if($do == 'permission') { ?><li class="active"><a href="<?php  echo create_url('extension/module/permission', array('id' => $id))?>">当前模块</a></li><?php  } ?>
</ul>
