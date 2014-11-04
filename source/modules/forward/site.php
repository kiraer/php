<?php
/**
 * 转发有礼模块微站定义
 *
 * @author 小南
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class ForwardModuleSite extends WeModuleSite {

	public function doWebAwardlist() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$weid=$_W["weid"];
		$rid = $_GPC['id'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$where ="";
		$mobile = $_GPC['mobile'];
		if(!empty($mobile)){
			$where .= " AND a.mobile = '$mobile'";
		}
		$list = pdo_fetchall("select realname,mobile,tongji from ".tablename('fans')." a, ".tablename('forward_winner')." b where a.weid=b.weid and a.from_user=b.from_user and b.weid=".$weid." and b.rid='{$rid}' $where  ORDER BY tongji DESC LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("select COUNT(*) from ".tablename('fans')." a, ".tablename('forward_winner')." b where a.weid=b.weid and a.from_user=b.from_user and b.rid='{$rid}' $where");
		$pager = pagination($total, $pindex, $psize);
		
		include $this->template('userlist');
	}
	
	//前台展示
	public function doMobileshow(){
		global $_GPC, $_W;
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if(!strpos($userAgent,'MicroMessenger')){
			include $this->template('s404');
			exit();
		}
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		if (empty($fromuser)) {
			exit('非法参数！');
		}
		$id = intval($_GPC['id']);
		$pan = pdo_fetch("SELECT * FROM ".tablename('forward_reply')." WHERE rid = '$id'  LIMIT 1");
		if (empty($pan)) {
			exit('非法参数！');
		}
		$weid= $_GPC['weid'];
		$rid = $_GPC['id'];
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('forward_winner')." WHERE  rid = '{$id}' and weid='".$weid."'");
		$member = pdo_fetch("SELECT * FROM ".tablename('forward_winner')." WHERE from_user = '{$fromuser}' and rid='{$id}' and weid='{$weid}' LIMIT 1");
		$list =  pdo_fetchall("select realname,mobile,tongji from ".tablename('fans')." a, ".tablename('forward_winner')." b where a.weid=b.weid and a.from_user=b.from_user and b.weid=".$weid." and b.rid='{$rid}' and a.realname<>'' and a.mobile<>'' ORDER BY tongji DESC LIMIT 100");
		$orderNum =  pdo_fetchcolumn("select COUNT(*) from ".tablename('forward_winner')." where rid = '{$id}' and weid='".$weid."' and  tongji>(SELECT tongji from ".tablename('forward_winner')." where from_user='".$fromuser."' and weid=".$weid.") order by tongji desc");
		
		$orderNum = $orderNum + 1;
		if(!empty($member)){
			$state = 1;	
		}else{
			$state = 0;		
		}
		include $this->template('show');
	}
	//提交用户姓名和手机号码
	public function doMobileajaxGetUser(){
		global $_GPC, $_W;
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		
		$data = array(
					'realname' => $_GPC['username'],
					'mobile' => $_GPC['mobile'],
		);
		if (empty($data['realname'])) {
				die('<script>alert("请填写您的真实姓名！");history.go(-1);</script>');		
			}
		if (empty($data['mobile'])) {
				die('<script>alert("请填写您的手机号码！");history.go(-1);</script>');	
		}
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		// 判断fans表里面有没有记录	
		pdo_update('fans', $data, array('from_user' => $fromuser));
		
		$insert = array(
				'weid' => $_GPC['weid'],
				'rid' => $_GPC['id'],
				'from_user' => $fromuser,
				'IPaddress' => $_W['clientip'],
				'createtime' => TIMESTAMP
		);
		$pan = pdo_fetch("SELECT * FROM ".tablename('forward_winner')." WHERE rid = '$id' and from_user='$fromuser'  LIMIT 1");
		if(empty($pan)){
			pdo_insert('forward_winner', $insert);
			message('提交成功！', referer(), 'success');
		}else{
			message('已经存在此用户', referer(), 'success');
		}	
	}
	//屏蔽电话号码中间的四位数字
	public function hidtel($phone)
	{
		 $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
		 if($IsWhat == 1)
		 {
		  return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
		
		 }
		 else
		 {
		  return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
		 }
	}
	// 点击量统计
	public function doMobileTongji(){
		global $_GPC, $_W;
		$IPaddress = CLIENT_IP;
		$URL = urldecode($_GPC['url']);
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		if (empty($fromuser)) {
			exit('非法参数！');
		}
		$member = pdo_fetch("SELECT IPaddress,tongji FROM ".tablename('forward_winner')." WHERE from_user = '{$fromuser}' and rid='".$_GPC['id']."' LIMIT 1");
		if($IPaddress != $member['IPaddress']){
			if(!isset($_COOKIE["tangel"])){ 
				//cookies不存在
				setcookie('tangel','flag',time()+86400);
				$data = array(
					'IPaddress' => $IPaddress,
					'tongji' => $member['tongji'] +1
				);
				pdo_update('forward_winner', $data,array('from_user' => $fromuser));	
			}
		}
		//echo $IPaddress; exit();
		header("Location: ".$URL ); //跳转
	}	

}