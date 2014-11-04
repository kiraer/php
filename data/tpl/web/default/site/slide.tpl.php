<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<ul class="nav nav-tabs">
	<li <?php  if($do == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo create_url('site/slide/post');?>">添加</a></li>
	<li <?php  if($do == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo create_url('site/slide/display');?>">管理</a></li>
</ul>
<style>
.table td span{display:inline-block;margin-top:4px;}
.table td input{margin-bottom:0;}
</style>
<?php  if($do == 'display') { ?>
<div class="main">
	<div class="search">
		<form action="site.php" method="get">
		<input type="hidden" name="act" value="module" />
		<input type="hidden" name="do" value="slide" />
		<input type="hidden" name="name" value="site" />
		<table class="table table-bordered tb">
			<tbody>
				<tr>
					<th>关键字</th>
					<td>
						<input class="span6" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
					</td>
				</tr>
				<tr>
				 <tr class="search-submit">
					<td colspan="2"><button class="btn pull-right span2"><i class="icon-search icon-large"></i> 搜索</button></td>
				 </tr>
			</tbody>
		</table>
		</form>
	</div>
	<form class="form-horizontal" action="" method="post">
	<div style="padding:15px;">
		<table class="table table-hover table-striped">
			<thead class="navbar-inner">
				<tr>
					<th width="80px;">排序</th>
					<th>标题</th>
					<th style="width:85px; text-align:right;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td><input type="text" class="span1" name="displayorder[<?php  echo $item['id'];?>]" value="<?php  echo $item['displayorder'];?>" /></td>
					<td><?php  echo $item['title'];?></td>
					<td style="text-align:right;">
						<a href="<?php  echo create_url('site/slide/post', array('id' => $item['id']))?>" title="编辑" class="btn btn-mini"><i class="icon-edit"></i></a>
						<a onclick="return confirm('此操作不可恢复，确认吗？'); return false;" href="<?php  echo create_url('site/slide/delete', array('id' => $item['id']))?>" title="删除" class="btn btn-mini"><i class="icon-remove"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
			<tr>
				<td colspan="6">
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
					<input type="submit" class="btn btn-primary" name="submit" value="提交" />
				</td>
			</tr>
		</table>
		<?php  echo $pager;?>
	</div>
	</form>
</div>
<script type="text/javascript">
<!--
	var category = <?php  echo json_encode($children)?>;
//-->
</script>
<?php  } else if($do == 'post') { ?>
<div class="main">
	<form class="form-horizontal form" action="" method="post" enctype="multipart/form-data" onsubmit="return formcheck(this)">
		<input type="hidden" name="id" value="<?php  echo $item['id'];?>">
		<h4>幻灯片管理</h4>
		<table class="tb">
			<tr>
				<th><label for="">排序</label></th>
				<td>
					<input type="text" class="span1" placeholder="" name="displayorder" value="<?php  echo $item['displayorder'];?>">
				</td>
			</tr>
			<tr>
				<th><label for="">标题</label></th>
				<td>
					<input type="text" class="span6" placeholder="" name="title" value="<?php  echo $item['title'];?>">
				</td>
			</tr>
			<tr>
				<th><label for="">缩略图</label></th>
				<td>
					<?php  echo tpl_form_field_image('thumb', $item['thumb'])?>
					<span class="help-block"></span>
				</td>
			</tr>
			<tr>
				<th><label for="">链接</label></th>
				<td>
					<input type="text" class="span6" placeholder="" name="url" value="<?php  echo $item['url'];?>">
				</td>
			</tr>
		</table>

		<table class="tb">
			<tr>
				<th></th>
				<td>
					<button type="submit" class="btn btn-primary span3" name="submit" value="提交">提交</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
<!--
	var category = <?php  echo json_encode($children)?>;
	kindeditor($('.richtext-clone'));
//-->
</script>
<?php  } ?>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
