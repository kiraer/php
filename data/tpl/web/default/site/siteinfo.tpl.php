<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
	<div class="main">
		<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
			<h4>站点信息</h4>
			<table class="tb">
				<tr>
					<th><label for="">标题附加内容</label></th>
					<td>
						<input type="text" name="sitename" class="span6" value="<?php  echo $item['sitename'];?>" />
					</td>
				</tr>
				<tr>
					<th><label for="">keywords</label></th>
					<td>
						<input type="text" name="keywords" class="span6" value="<?php  echo $item['keywords'];?>" />
					</td>
				</tr>
				<tr>
					<th><label for="">description</label></th>
					<td>
						<input type="text" name="description" class="span6" value="<?php  echo $item['description'];?>" />
					</td>
				</tr>
				<tr>
					<th>底部自定义</th>
					<td>
						<textarea style="height:150px;" class="span6" cols="70" name="footer" autocomplete="off"><?php  echo $item['footer'];?></textarea>
						<span class="help-block">自定义底部信息，支持HTML</span>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input name="submit" type="submit" value="提交" class="btn btn-primary span3" />
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
					</td>
				</tr>
			</table>
		</form>
	</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
