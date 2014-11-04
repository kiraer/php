<?php
/**
 * 转发有礼模块处理程序
 *
 * @author 小南
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class ForwardModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('forward_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if (empty($row['id'])) {
			return array();
		}
		$title = pdo_fetchcolumn("SELECT name FROM ".tablename('rule')." WHERE id = :rid LIMIT 1", array(':rid' => $rid));
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'news';
		$response['ArticleCount'] = 1;
		$response['Articles'] = array();
		$response['Articles'][] = array(
			'Title' => $title,
			'Description' => $row['description'],
			'PicUrl' => $_W['attachurl'] . $row['picture'],
			'Url' => $_W['siteroot'] . create_url('mobile/module', array('do' => 'show', 'name' => 'forward', 'id' => $rid, 'weid' => $_W['weid'], 'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))),
			'TagName' => 'item',
		);
		return $response;
	}
}