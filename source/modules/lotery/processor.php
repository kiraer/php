<?php
/**
 * 语音回复处理类
 * 
 * [WeEngine System] 更多模块请浏览：BBS.b2ctui.com
 */
defined('IN_IA') or exit('Access Denied');

class LoteryModuleProcessor extends WeModuleProcessor {
	
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('lotery_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if($row==false){
			return ;
		}
return $this->respNews(array(
			'Title' => $row['title'],
			'Description' => $row['description'],
			'PicUrl' => $_W['attachurl'] . $row['picture'],
			'Url' => $this->createMobileUrl('lottery', array('id' => $rid, 'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))),
			'TagName' => 'item',
		));

	}
}
