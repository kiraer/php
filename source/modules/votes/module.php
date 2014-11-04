<?php
/**
 * 微投票模块定义
 *
 * @author Libi
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class VotesModule extends WeModule {
	public $name = 'votes';
	public $title = '投票系统';
	public $ability = '';
	public $tablename = 'vote_reply';

	public function fieldsFormDisplay($rid = 0) {
		global $_W;
      	if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));		
 		} 
		include $this->template('form');
	}
  	public function fieldsFormValidate($rid = 0) {
		
        return true;
	}
  
  	public function fieldsFormSubmit($rid = 0) {
		global $_GPC, $_W;
 
        $id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'thumb' => $_GPC['picture'],
          	'vote_title'=> $_GPC['vote_title'],
          	'vote_limit'=> $_GPC['vote_limit'],
			'ad'=> $_GPC['ad'],
			'vote_allow'=> $_GPC['vote_allow'],
		);
      //处理图片
      	if (!empty($_GPC['picture'])) {
			file_delete($_GPC['picture-old']);
		} else {
			unset($insert['thumb']);
		}
		if (empty($id)) {
			$id=pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
      	return true;
	}
   	public function ruleDeleted($rid = 0) {
		global $_W;
		pdo_delete('vote_reply', array('rid' => $rid));
		$fans = pdo_fetchall("SELECT from_user FROM ".tablename('vote_option'). "WHERE rid=:rid",array(':rid'=>$rid));
		foreach($fans as $v){
			pdo_delete('fans', array('from_user' => $v['from_user']));
		};
		pdo_delete('vote_option', array('rid' => $rid));
		pdo_delete('vote_fans', array('rid' => $rid));
		return true;
	}
 
   
	/*public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}*/

	public function settingsDisplay($settings) {
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$this->saveSettings($dat);
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}