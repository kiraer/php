<?php
/**
 * 大转盘抽奖模块看版
 *
 * 修正:夜未央
 * 
 * QQ357795894 
 */
defined('IN_IA') or exit('Access Denied');

class LoteryModule extends WeModule {
	public $name = 'LoteryModule';
	public $title = '大转盘抽奖';
	public $ability = '';
	public $tablename = 'lotery_reply';
	public $table = 'lotery_winner';

	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			$award = pdo_fetchall("SELECT * FROM ".tablename('lotery_award')." WHERE rid = :rid ORDER BY `id` ASC", array(':rid' => $rid));
			if (!empty($award)) {
				foreach ($award as &$pointer) {
					if (!empty($pointer['activation_code'])) {
						$pointer['activation_code'] = implode("\n", iunserializer($pointer['activation_code']));
					}
				}
			}
		} else {
			$reply = array(
				'maxlottery' => 1,
			);
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
			'picture' => $_GPC['picture'],
			'zppic'=> $_GPC['picture2'],
			'description' => $_GPC['description'],
			'maxlottery' => intval($_GPC['maxlottery']),
			'rule' => $_GPC['rule'],
		);
		if (empty($id)) {
			pdo_insert($this->tablename, $insert);
		} else {
			if (!empty($_GPC['picture'])) {
				file_delete($_GPC['picture-old']);
				
			} else {
				unset($insert['picture']);
				
			}
			if (!empty($_GPC['picture2'])) {
				
				file_delete($_GPC['zppic-old']);
			} else {
				
				unset($insert['zppic']);
			}
			
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
		if (!empty($_GPC['award-level'])) {
			foreach ($_GPC['award-level'] as $index => $title) {
				if (empty($title)) {
					continue;
				}
				$update = array(
				
					'level' => $_GPC['award-level'][$index],
					'total' => intval($_GPC['award-total'][$index]),
					'probalilty' => $_GPC['award-probalilty'][$index],
					'deg' => $_GPC['award-deg'][$index],
					'description' => $_GPC['award-description'][$index],
				);
				if (empty($update['inkind']) && !empty($_GPC['award-activation-code'][$index])) {
					$activationcode = explode("\n", $_GPC['award-activation-code'][$index]);
					$update['activation_code'] = iserializer($activationcode);
					$update['total'] = count($activationcode);
					$update['activation_url'] = $_GPC['award-activation-url'][$index];
				}
				pdo_update('lotery_award', $update, array('id' => $index));
			}
		}
		//处理添加
		//print_r($_GPC);
		if (!empty($_GPC['award-level-new'])) {
			foreach ($_GPC['award-level-new'] as $index => $title) {
				if (empty($title)) {
					continue;
				}
				$insert = array(
					'rid' => $rid,
					'level' => $_GPC['award-level-new'][$index],
					'total' => intval($_GPC['award-total-new'][$index]),
					'probalilty' => $_GPC['award-probalilty-new'][$index],
					'deg' => $_GPC['award-deg-new'][$index],
					'description' => $_GPC['award-description-new'][$index],
				);
				pdo_insert('lotery_award', $insert);
			}
		}
	}

	public function ruleDeleted($rid = 0) {
		global $_W;
		$replies = pdo_fetchall("SELECT id, picture FROM ".tablename($this->tablename)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				file_delete($row['picture']);
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->tablename, "id IN ('".implode("','", $deleteid)."')");
		return true;
	}

	public function doFormDisplay() {
		global $_W, $_GPC;
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$result['content']['id'] = $GLOBALS['id'] = 'add-row-news-'.$_W['timestamp'];
		$result['content']['html'] = $this->template('item', TEMPLATE_FETCH);
		exit(json_encode($result));
	}



	public function doManage() {
		global $_GPC, $_W;
		include model('rule');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;		
		$params = array();
		$params[':weid'] = $_W['weid'];
		$params[':module'] = 'lotery';
		$list = rule_search('weid = :weid AND `module` = :module', $params, $pindex, $psize, $total);
		if (!empty($list)) {
			foreach($list as &$item) {
				$condition = '`rid`=:rid';
				$params = array();
				$params[':rid'] = $item['id'];
				$item['keywords'] = rule_keywords_search($condition, $params);
				$item['options'] = array();
				foreach($bindings as $opt) {
					if($opt['module'] == $item['module']) {
						if(!empty($opt['call'])) {
							$site = WeUtility::createModuleSite($item['module']);
							if(method_exists($site, $opt['call'])) {
								$ret = $site->$opt['call']();
								if(is_array($ret)) {
									foreach($ret as $et) {
										$et['url'] .= strexists($et['url'], '?') ? "&id={$item['id']}" : "?id={$item['id']}";
										$item['options'][] = array('title' => $et['title'], 'link' => $et['url']);
									}
								}
							}
						} else {
							$vars = array();
							$vars['eid'] = $opt['eid'];
							$vars['id'] = $item['id'];
							$link = create_url('site/entry', $vars);
							$item['options'][] = array('title' => $opt['title'], 'link' => $link);
						}
					}
				}
			}
		}
		include $this->template('manage');
	}


	
	public function dostatus( $rid = 0) {
		global $_GPC;
		$rid = $_GPC['rid'];
		//echo $rid;
		$insert = array(
			'status' => $_GPC['status']
		);
		
		pdo_update($this->tablename,$insert,array('rid' => $rid));
		message('模块操作成功！', referer(), 'success');
	}	
	


}