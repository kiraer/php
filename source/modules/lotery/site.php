<?php
/**
 * 大转盘抽奖模块
 *
 * 修正:夜未央
 * 
 * QQ357795894 
 */
defined('IN_IA') or exit('Access Denied');

class LoteryModuleSite extends WeModuleSite {
		public function getHomeTiles() {
		
	}

		public function getProfileTiles() {

	}

   //

	public function doMobileLottery($rid = 0) {
		global $_GPC ,$_W;

		$title = '大转盘抽奖';

		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		if (empty($fromuser)) {
			exit('非法参数！');
		}
                $id = intval($_GPC['id']);
		$pan = pdo_fetch("SELECT * FROM ".tablename('lotery_reply')." WHERE rid = '$id' LIMIT 1");
		$pan['zppic'] =$_W['attachurl'].$pan['zppic'];
		if (empty($pan)) {
			exit('非法参数！');
		}
              //select b.weid from ims_lotery_reply as a left join ims_rule  as b on a.rid = b.id where 1;
                $we = pdo_fetch("select b.weid from ims_lotery_reply as a left join ims_rule  as b on a.rid = b.id where 1");
	        $id = intval($_GPC['id']);
                $member = pdo_fetch("SELECT * FROM ".tablename('fans')." WHERE from_user = '{$fromuser}'");
		$lotery = pdo_fetch("SELECT * FROM ".tablename('lotery_reply')." WHERE rid = '$id' LIMIT 1");
		$JiangPin = pdo_fetchall("SELECT *FROM ".tablename('lotery_award')." WHERE rid = '{$id}'");
		if (empty($lotery)) {
			exit('非法参数！');
		}
		$data = pdo_fetchall("SELECT a.nickname, a.qq, a.from_user, a.realname, b.from_user, b.aid, b.`status`, b.award, c.id, c.`level`, c.rid, b.rid, a.mobile, a.gender, b.createtime FROM ims_fans AS a , ims_lotery_winner AS b , ims_lotery_award AS c WHERE a.from_user = b.from_user AND b.aid = c.id AND b.rid = c.rid AND b.rid = '$id' ORDER BY b.id DESC");
        $myaward = pdo_fetchall("SELECT award,status, description FROM ".tablename('lotery_winner')." WHERE from_user = '{$fromuser}' AND award <> '' AND rid = '{$id}' ORDER BY createtime DESC");
		

//$sql = "SELECT a.id, a.aid, a.award, a.description, a.status, a.createtime, b.realname, b.mobile, b.qq FROM ".tablename('lotery_winner')." AS a
	//			LEFT JOIN ".tablename('fans')." AS b ON a.from_user = b.from_user WHERE a.rid = '$id' AND a.award <> '' $where ORDER BY a.createtime DESC, a.status ASC LIMIT ".($pindex - 1) * $psize.",{$psize}";
		//强行会员登记

		if (empty($member['realname'])) {
	    $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');  
            $rule=pdo_fetch("select weid FROM ".tablename('rule')." where id='{$id}' limit 1");	
            $user = pdo_fetch("SELECT * FROM ".tablename('fans')." WHERE from_user = '".$from_user."' and weid=".$rule['weid']." limit 1" );
	    
		      if($_GPC['action']=='setinfo'){
		     		$insert = array(
					'nickname' => $_GPC['nickname'],
					'realname' => $_GPC['realname'],
					'mobile' => $_GPC['mobile'],
					'qq' => $_GPC['qq'],
		          	'gender' => $_GPC['gender'],        
					'from_user'=>$from_user,
		          	'weid'=>$rule['weid'],
		          	'createtime'=>time(),
		            'avatar'=>' ',
				);
		    
             if (empty($member)) {
				$insert['from_user'] = $from_user;
				pdo_insert('fans', $insert);
				die('<script>alert("登记成功！以后无需登记可直接参与抽奖！如果需要修改信息，请再次提交更改");location.href = "'.$this->createMobileUrl('lotery', array('name' => 'lottery', 'id' => intval($_GPC['id']), 'from_user' => $_GPC['from_user'])).'";</script>');

			} else {
				pdo_update('fans', $insert, array('from_user' => $fromuser));
				die('<script>alert("登记成功！感谢您的使用!");location.href = "'.$this->createMobileUrl('lottery', array('name' => 'lotery',  'id' => intval($_GPC['id']), 'from_user' => $_GPC['from_user'])).'";</script>');
				//die('<script>alert("登记成功！");location.href = "'.$this->createMobileUrl('lottery', array('id' => $_GPC['id'])).'";</script>');
} } 
		   	include $this->template('register');			
		} else {
			if ($lotery['status']) {
				include $this->template('lottery');
			} else {
            	include $this->template('lotteryend');
				//echo '<img src="/source/modules/lotery/template/images/activity-lottery-end2.jpg">';
			}
			
		}
		
	}



		public function doMobileGetaward() {
		global $_GPC, $_W;
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		if (empty($fromuser)) {
			exit('非法参数1！');
		}
		$id = intval($_GPC['id']);
		$egg = pdo_fetch("SELECT * FROM ".tablename('lotery_reply')." WHERE rid = '$id' LIMIT 1");
		if (empty($egg)) {
			exit('非法参数2！');
		}

		//$result = array('status' => -1, 'message' => '');
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('lotery_winner')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser' AND status <> 3");
		if (!empty($egg['maxlottery']) && $total >= $egg['maxlottery']) {
			$result['message'] = 405;
			message($result, '', 'ajax');
		}

		$gifts = pdo_fetchall("SELECT id, probalilty FROM ".tablename('lotery_award')." WHERE rid = '$id' ORDER BY probalilty ASC");
		//计算每个奖品的概率
		$probability = 0;
		$rate = 1;
		$award = array();
		foreach ($gifts as $name => $gift){
			if (empty($gift['probalilty'])) {
				continue;
			}
			if ($gift['probalilty'] < 1) {
				$temp = explode('.', $gift['probalilty']);
				$temp = pow(10, strlen($temp[1]));
				$rate = $temp < $rate ? $rate : $temp;
			}
			$probability = $probability + $gift['probalilty'] * $rate;
			$award[] = array('id' => $gift['id'], 'probalilty' => $probability);
		}
		$all = 100 * $rate;
		if($probability < $all){
			$award[] = array('title' => '','probalilty' => $all);
		}
		mt_srand((double) microtime()*1000000);
		$rand = mt_rand(1, $all);
		foreach ($award as $key => $gift){
			if(isset($award[$key - 1])){
				if($rand > $award[$key -1]['probalilty'] && $rand <= $gift['probalilty']){
					$awardid = $gift['id'];
					break;
				}
			}else{
				if($rand > 0 && $rand <= $gift['probalilty']){
					$awardid = $gift['id'];
					break;
				}
			}
		}
		$title = '';
		$result['message'] = empty($egg['default_tips']) ? '很遗憾,您没能中奖！' : $egg['default_tips'];
		$data = array(
			'rid' => $id,
			'from_user' => $fromuser,
			'createtime' => TIMESTAMP,
		);
		// $PanDuan = pdo_fetcha("SELECT ims_lotery_winner.aid, ims_lotery_winner.from_user FROM ims_lotery_winner WHERE ims_lotery_winner.aid < 5 AND ims_lotery_winner.from_user = '{$fromuser}'");
		// $awardid = ($PanDuan) ? 5 : $awardid ;



		if (!empty($awardid)) {
			$gift = pdo_fetch("SELECT * FROM ".tablename('lotery_award')." WHERE rid = '$id' AND id = '$awardid'");
			if ($gift['total'] > 0) {
				$data['award'] = $gift['description'];
				
				$data['description'] = $gift['description'];
				pdo_query("UPDATE ".tablename('lotery_award')." SET total = total - 1 WHERE rid = '$id' AND id = '$awardid'");
			
				$result['message'] = '恭喜您，得到“'.$data['award'].'”！' ;
                                        $result['award']=$data['award'];
				$result['deg']=$gift['deg'];
				$result['level'] = $gift['level'];
				$data['status'] = "1";
			} else {
                                        $result['message'] ='都啥时候了才来,奖品都被抢完了,下次早点哦!';
                                        $result['award']="都啥时候了才来,奖品都被抢完了,下次早点哦!";
                                        $result['deg']=$gift['deg'];
                                        //$result['level'] = $gift['level'];
                                        $result['level'] = "奖品都被抢完了!";
                                        $data['status'] = "2";
                                 } 
		}else{
				$result['level']  = "谢谢参与!";
				$result['status'] = "-1";
				$result['deg']=45;
		}
		$data['aid'] = $gift['id'];
		pdo_insert('lotery_winner', $data);
		$result['myaward'] = pdo_fetchall("SELECT award, description FROM ".tablename('lotery_winner')." WHERE from_user = '{$fromuser}' AND award <> '' AND rid = '$id' ORDER BY createtime DESC");
		message($result, '', 'ajax');
     
	}



	public function doMobileRegister() {
		global $_GPC;
		$title = '大转盘抽奖登记个人信息';
                $id=intval($_GPC['id']);
                $rule=pdo_fetch("select weid FROM ".tablename('rule')." where id='{$id}' limit 1");
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		$member = pdo_fetch("SELECT * FROM ".tablename('fans')." WHERE from_user = '{$fromuser}' LIMIT 1");
		if($_GPC['action']=='setinfo'){
			$data = array(
				'nickname' => $_GPC['nickname'],
					'realname' => $_GPC['realname'],
					'mobile' => $_GPC['mobile'],
					'qq' => $_GPC['qq'],
		          	'gender' => $_GPC['gender'],        
		          	'weid'=>$rule['weid'],
			);
		
			if (empty($data['realname'])) {
				die('<script>alert("请填写您的真实姓名！");location.reload();</script>');
			}
			if (empty($data['mobile'])) {
				die('<script>alert("请填写您的手机号码！");location.reload();</script>');
			}
			if (empty($member)) {
				$data['from_user'] = $fromuser;
				pdo_insert('fans', $data);
				die('<script>alert("登记成功！以后无需登记可直接参与抽奖！如果需要修改信息，请再次提交更改");location.href = "'.$this->createMobileUrl('lottery', array('name' => 'lotery',  'id' => intval($_GPC['id']), 'from_user' => $_GPC['from_user'])).'";</script>');
			} else {
				pdo_update('fans', $data, array('from_user' => $fromuser));
				die('<script>alert("更新成功！感谢您的使用!");location.href = "'.$this->createMobileUrl('lottery', array('name' => 'lotery',  'id' => intval($_GPC['id']), 'from_user' => $_GPC['from_user'])).'";</script>');
			}

		}
		include $this->template('register1');
	}


		public function doWebDelete() {
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$rid = intval($_GPC['rid']);
		//print_r($_GPC);
		$sql = "SELECT id FROM " . tablename('lotery_award') . "";
		$row = pdo_fetch($sql, array(':id'=>$id));
		if (empty($row)) {
			message('抱歉，奖品不存在或是已经被删除！', '', 'error');
		}
		if (!pdo_delete('lotery_award', array('id' => $id))) {
		$url = create_url('rule/post', array('id' => $rid));
			message('删除奖品成功', $url, 'success'); 
		}
	}



	public function doWebAwardlist() {
		global $_GPC, $_W;
		checklogin();
		$id = intval($_GPC['id']);
		if (checksubmit('delete')) {
			pdo_delete('lotery_winner', " id  IN  ('".implode("','", $_GPC['select'])."')");
			message('删除成功！', create_url('site/module/awardlist', array( 'name' => 'lotery', 'id' => $id, 'page' => $_GPC['page'])));
		}
		if (!empty($_GPC['wid'])) {
			$wid = intval($_GPC['wid']);
			pdo_update('lotery_winner', array('status' => intval($_GPC['status'])), array('id' => $wid));
			message('标识领奖成功！', create_url('site/module/awardlist', array('name' => 'lotery', 'id' => $id, 'page' => $_GPC['page'])));
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$where = '';
		$starttime = !empty($_GPC['starttime']) ? strtotime($_GPC['starttime']) : 0;
		$endtime = !empty($_GPC['starttime']) ? strtotime($_GPC['endtime']) : 0;
		if (!empty($starttime) && $starttime == $endtime) {
			$endtime = $endtime + 86400 - 1;
		}
		$condition = array(
			'isregister' => array(
				'',
				" AND b.realname <> ''",
				" AND b.realname = ''",
			),

			'aid' => "AND a.aid != '5'",
			'status' => "AND a.status = '{$_GPC['status']}'",
			'qq' => " AND b.qq LIKE '%{$_GPC['profilevalue']}%'",
			'mobile' => " AND b.mobile LIKE '%{$_GPC['profilevalue']}%'",
			'realname' => " AND b.realname LIKE '%{$_GPC['profilevalue']}%'",
			'title' => " AND a.award = '{$_GPC['awardvalue']}'",
			'description' => " AND a.description LIKE '%{$_GPC['awardvalue']}%'",
			'starttime' => " AND a.createtime >= '$starttime'",
			'endtime' => " AND a.createtime <= '$endtime'",
		);
		if (!isset($_GPC['isregister'])) {
			$_GPC['isregister'] = 1;
		}
		$where .= $condition['isregister'][$_GPC['isregister']];
		if (empty($_GPC['status'])) {
			$where .= '';
		} elseif ($_GPC['status'] =='1') {
			$where .= $condition['aid'];
			$where .= $condition['status'];
		} else{
			$where .= $condition['status'];
		}
		if (!empty($_GPC['profile'])) {
			$where .= $condition[$_GPC['profile']];
		}
		if (!empty($_GPC['award'])) {
			$where .= $condition[$_GPC['award']];
		}
		if (!empty($starttime)) {
			$where .= $condition['starttime'];
		}
		if (!empty($endtime)) {
			$where .= $condition['endtime'];
		}
		$sql = "SELECT a.id, a.aid, a.award, a.description, a.status, a.createtime, b.realname, b.mobile, b.qq FROM ".tablename('lotery_winner')." AS a
				LEFT JOIN ".tablename('fans')." AS b ON a.from_user = b.from_user WHERE a.rid = '$id' AND a.award <> '' $where ORDER BY a.createtime DESC, a.status ASC LIMIT ".($pindex - 1) * $psize.",{$psize}";
		$list = pdo_fetchall($sql);

                     $con =  count($list);
		if (!empty($list)) {
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('lotery_winner')." AS a
				LEFT JOIN ".tablename('fans')." AS b ON a.from_user = b.from_user WHERE a.rid = '$id' $where");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('awardlist');
	}




	
	}