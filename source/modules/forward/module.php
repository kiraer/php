<?php
/**
 * 转发有礼模块定义
 *
 * @author 小南
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class ForwardModule extends WeModule {
	public $name = 'Forward';
	public $title = '转发有礼';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if($rid==0){
			$reply = array(
				'end' =>strtotime(date('Y-m-d')),				
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename('forward_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return true;
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'weid' => $_W['weid'],
			'title' => $_GPC['title'],
			'picture' => $_GPC['picture'],
			'description' => $_GPC['description'],
			'rule' => $_GPC['rule'],
			'end' =>strtotime($_GPC['end']),
			'url' =>  $_GPC['url']
		);
		if (empty($id)) {
			pdo_insert('forward_reply', $insert);
		}else{
			if (!empty($_GPC['picture'])) {
				file_delete($_GPC['picture-old']);
				
			} else {
				unset($insert['picture']);
				
			}
			pdo_update('forward_reply', $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		$replies = pdo_fetchall("SELECT id,rid FROM ".tablename('forward_reply')." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
				$ridid[] =$row['rid'];
			}
		}
		pdo_delete('forward_reply', "id IN ('".implode("','", $deleteid)."')");
		pdo_delete('forward_winner', "rid IN ('".implode("','", $ridid)."')");
		return true;
	}


}