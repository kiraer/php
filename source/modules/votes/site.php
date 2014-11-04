<?php
/**
By libi
 */
defined('IN_IA') or exit('Access Denied');

class VotesModuleSite extends WeModuleSite {
	public $module;

	// int: 预定义的数据, 本次请求的公众号编号
	public $weid;

	// bool: 预定义的数据, 是否存在
	public $inMobile;
 function __construct(){
	 global $_GPC;
  //$this->weid = $_GPC['weid'];
}
 public function doWebresult(){
 	global $_GPC;
    $rid = $_GPC['id']; 
	$list = pdo_fetchall("SELECT * FROM ".tablename('vote_option')." WHERE rid = :rid AND allowed='1' ORDER BY vote_num", array(':rid' => $rid), 'from_user');

	$fans = fans_search(array_keys($list), array('realname', 'mobile','qq'));

	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename=vote_data.xls");
	
	$tx=' ';   
	echo   $tx."\n\n";   
	//输出内容如下：   
	echo   iconv('utf-8','gbk',"姓名")."\t";   
	echo   iconv('utf-8','gbk',"手机")."\t";
	echo   "qq"."\t";    
	echo   iconv('utf-8','gbk',"宣言")."\t";    
	echo   iconv('utf-8','gbk',"票数")."\t";
	echo   iconv('utf-8','gbk',"排名")."\t";      
	echo   "\n";  
	$i = 0; 
	foreach($list as $v){
		$i++;
		echo   iconv('utf-8','gbk',$fans[$v['from_user']]['realname'])."\t";   
		echo   $fans[$v['from_user']]['mobile']."\t";
		echo   $fans[$v['from_user']]['qq']."\t";    
		echo   iconv('utf-8','gbk',$v['content'])."\t";    
		echo   $v['vote_num']."\t";
		echo   $i."\t";      
		echo   "\n";   
	}
  }
  public function doWeboption(){
    global $_GPC, $_W;
    $rid = $_GPC['id'];    
    global $_W, $_GPC;
		checklogin();
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$rid = intval($_GPC['id']);

		if ($operation == 'display') {
			if (!empty($_GPC['realname'])) {
				$openids = pdo_fetchall("SELECT from_user FROM ".tablename('fans')." WHERE realname = :realname", array(':realname' => $_GPC['realname']), 'from_user');
				if (!empty($openids)) {
					$condition = " AND openid IN ('".implode("','", array_keys($openids))."')";
				}
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 50;
			$list = pdo_fetchall("SELECT * FROM ".tablename('vote_option')." WHERE rid = :rid $condition ORDER BY vote_num DESC LIMIT ".($pindex - 1) * $psize.','.$psize, array(':rid' => $rid), 'from_user');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vote_option') . " WHERE rid = :rid $condition", array(':rid' => $rid));
			$pager = pagination($total, $pindex, $psize);
			$fans = fans_search(array_keys($list), array('realname', 'mobile','qq'));
			
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM ".tablename('vote_option')." WHERE id = '$id'");
			$item['profile'] = fans_search($item['from_user'], array('mobile', 'realname','qq'));
			if (checksubmit('submit')) {
				if(!empty($id)){
					pdo_update('vote_option', array(
						'content' => $_GPC['content'],
						'allowed' => $_GPC['allowed'],
					), array('id' => $id));
					$data = array(
						'realname'=>$_GPC['realname'],
						'mobile'=>$_GPC['mobile'],
						'qq'=>$_GPC['qq'],
						'avatar'=>$_GPC['avatar']
					);
					fans_update($item['from_user'],$data);
					message('更新信息成功！', $this->createWebUrl('option', array('id' => $item['rid'])), 'success');
				}else{
					//添加选手
					$a = pdo_fetchcolumn('SELECT from_user FROM '.tablename('vote_option')." WHERE from_user=:from_user",array(':from_user'=>$_GPC['from_user']));
					
					if(!empty($a)){
						message('该粉丝已经是选手了，请重新添加！', $this->createWebUrl('option', array('id' => $_GPC['rid'])), 'error');
					}
					pdo_insert('vote_option',array(
					'content' => $_GPC['content'],
					'allowed' => $_GPC['allowed'],
					'from_user'=>$_GPC['from_user'],
					'rid'=>$_GPC['rid']));
					//更新fans表
					$data = array(
						'realname'=>$_GPC['realname'],
						'mobile'=>$_GPC['mobile'],
						'qq'=>$_GPC['qq'],
						'avatar'=>$_GPC['avatar']
					);
					
					
					fans_update($_GPC['from_user'],$data);
					
					message('选手添加成功！', $this->createWebUrl('option', array('id' => $_GPC['rid'])), 'success');
				}
			}
			
			
		}

		

		$result = pdo_fetchall("SELECT * FROM ".tablename('vote_option')." WHERE rid = :rid ORDER by `id` ASC", array(':rid' => $rid));	
    
		 
  		include $this->template('display');
  }
  	public function dowebDelete() {
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$sql = "SELECT id,  rid, thumb FROM " . tablename('vote_option') . " WHERE `id`=:id";
		$row = pdo_fetch($sql, array(':id'=>$id));
		if (empty($row)) {
			message('抱歉，参赛者不存在或是已经被删除！', '', 'error');
		}
		if (pdo_delete('vote_option', array('id' => $id))) {
			file_delete($row['thumb']);
		}
		message('删除参赛者成功', '', 'success');
	}
	
	public function doMobilelist() {
		global $_GPC,$_W;
		
		//var_dump($_W['fans']);
		$from_user = $_W['fans']['from_user']; 
      //判断用户是否存在
       $rid=$_GPC['rid'];
	   
      $reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
      if($reply==false){
        die('活动已经取消了');
      }      
       $fans=pdo_fetch("SELECT * FROM " . tablename('vote_fans') . "where rid=".$rid." and from_user='".$from_user."' and  status=0");
      if($fans==false){
        //错误，
        //die('非法入口');
      }
      //判断有没有投票过
  		$zero_time = mktime(0,0,0);
       	$fans2=pdo_fetch("SELECT id FROM " . tablename('vote_fans') . "where rid=".$rid." and from_user='".$from_user."' and  status=0  and vote_time>".$zero_time."");	
      	$isvote=0;	
      	if($fans2==false){
           //今日没投票
           $isvote=1;
		   //pdo_update('vote_fans',array('vote_time'=>time(),'vote_num'=>($fans['vote_num']+1)),array('from_user'=> $from_user,'rid'=>$rid));
        }
           
        

		$pindex = max(1, intval($_GPC['page']));
		$psize = 500;
		$list = pdo_fetchall("SELECT * FROM ".tablename('vote_option')." WHERE rid = :rid AND allowed='1' ORDER BY vote_num DESC LIMIT ".($pindex - 1) * $psize.','.$psize, array(':rid' => $rid), 'from_user');
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vote_option') . " WHERE rid = :rid AND allowed='1'", array(':rid' => $rid));
      	
		$fans = fans_search(array_keys($list), array('realname', 'mobile','qq','avatar'));
      	include $this->template('list');
	}
   function doMobilesingle(){
	   //var_dump($this);
    	global $_GPC,$_W;
		$id = $_GPC['id']?$_GPC['id']:$_GPC['pop_id'];
		$rid = $_GPC['rid'];
		$reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
      	if($reply==false){
        	die('活动已经取消了');
      	}     
		$from_user = $_W['fans']['from_user'];
		if(!empty($id)){
			$single_info=pdo_fetch("SELECT * FROM ".tablename('vote_option')." WHERE id = :id AND allowed='1'", array(':id' => $id));
			
		}elseif(!empty($from_user)){
			$single_info=pdo_fetch("SELECT * FROM ".tablename('vote_option')." WHERE from_user = :from_user AND allowed='1'", array(':from_user' => $from_user));
			
		}else{
			checkauth();
		}	
		$fans = fans_search($single_info['from_user'], array('realname', 'mobile','qq','avatar'));
		$list = pdo_fetchall("SELECT * FROM ".tablename('vote_option')." WHERE rid = :rid AND allowed='1' ORDER BY vote_num DESC ", array(':rid' => $rid));
		$i = 0;
		foreach($list as $v){
			$i++;
			if($v['from_user']==$single_info['from_user']) $rank = $i;
		}
		
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vote_option') . " WHERE rid = :rid AND allowed='1'", array(':rid' => $rid));
		include $this->template('single');
   }
   function doMobilereg(){
	   global $_W,$_GPC;
	   $rid = $_GPC['rid'];
	  
	   $reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
      if($reply==false){
        die('活动已经取消了');
      }     
		$id = intval($_GPC['id']);
		checkauth();
		$item = pdo_fetch("SELECT * FROM ".tablename('vote_option')." WHERE from_user = :from_user",array('from_user'=>$_W['fans']['from_user']));
		if($item['allowed']!='1'&&!empty($item['id'])){
			message('您的申请信息正在审核中，请耐心等待',$this->createMobileUrl('list',array('rid'=>$rid)),'error');
		}
		$item['profile'] = fans_search($_W['fans']['from_user'], array('mobile', 'realname','qq','avatar'));
		if (checksubmit('submit')) {
			if(!empty($id)){
				pdo_update('vote_option', array(
					'content' => $_GPC['content'],
					'rid'=>$_GPC['rid']
				), array('id' => $id));
				$data = array(
					'avatar'=>$_GPC['avatar']
				);
				fans_update($item['from_user'],$data);
				message('更新信息成功！', $this->createMobileUrl('list',array('rid'=>$rid)), 'success');
			}else{
				//添加选手
				
				$is_allow  = pdo_fetchcolumn("SELECT vote_allow  FROM ".tablename('vote_reply')." WHERE rid=:rid",array(':rid'=>$rid));
				$allowed = $is_allow=='1'?'0':'1';
				pdo_insert('vote_option',array(
				'content' => $_GPC['content'],
				'from_user'=>$_W['fans']['from_user'],
				'rid'=>$_GPC['rid'],
				'allowed'=>$allowed
				));
				//更新fans表
				
				$data = array(
					'realname'=>$_GPC['realname'],
					'mobile'=>$_GPC['mobile'],
					'qq'=>$_GPC['qq'],
					'avatar'=>$_GPC['avatar']
				);
				
				
				fans_update($_W['fans']['from_user'],$data);
				
				message('申请成功,请等待审核！', $this->createMobileUrl('list',array('rid'=>$rid)), 'success');
			}
		

		}
	   include $this->template('reg');
   }
  function doMobiletp(){
	global $_GPC,$_W;
    //判断用户是否存在
	$rid=$_GPC['rid'];
	$id = $_GPC['id'];
	$reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
      if($reply==false){
        die('活动已经取消了');
      }     
  	//$from_user = authcode(base64_decode($_GPC['u']), 'DECODE'); 
	checkauth();
    $from_user = $_W['fans']['from_user'];
	
    if($rid==0 || $from_user==''){
      message('你无权进行投票',$this->createMobileUrl('list',array('rid'=>$rid)),'error');
    }else{
  	  	$fans=pdo_fetch("SELECT * FROM " . tablename('vote_fans') . "where rid=".$rid." and from_user='".$from_user."' and  status=0  ");
		
      	if($fans==false){
              message('您必须通过向公众号发送选手编号以获取选手页面进行投票',$this->createMobileUrl('list',array('rid'=>$rid)),'error');
			  
        }else{
   			 //判断用户今题是投票过来
   			 
			 $vote_limit = pdo_fetchcolumn("SELECT vote_limit FROM ".tablename('vote_reply')." WHERE rid=:rid",array(':rid'=>$rid));
			 //获取时间差单位为s  86400为一天
			 
   			 if($fans['vote_num']>=$vote_limit){
     		 //今天已经投票过了
                message('您已经达到投票总数限制了，无法再投票！',$this->createMobileUrl('single',array('rid'=>$rid,'id'=>$id)),'error');
  			  }else{
     			 
               //查找选手是否存在
				 $vote = pdo_fetch("SELECT * FROM ".tablename('vote_option')." WHERE rid = :rid and id=".$id." ORDER by `id` ASC", array(':rid' => $rid));	
              	 if($vote==false){
              	   //选手不存在
					 message('您所关注的选手不存在，请重新选择选手投票',$this->createMobileUrl('list',array('rid'=>$rid)),'error');
             	 }else{
      					//记录fans
      					pdo_update('vote_fans',array('vote_time'=>time(),'vote_num'=>($fans['vote_num']+1)),array('id'=>$fans['id']));
      					//增加一票
                   		pdo_update('vote_option', array('vote_num'=>($vote['vote_num']+1)), array('id' => $id));
     					message('投票成功，感谢您的参与！',$this->createMobileUrl('single',array('rid'=>$rid,'id'=>$id)),'success');               
               	 }                             
              }
         }               
    }
	
  }
  public function doMobilead(){
	  global $_W,$_GPC;
	  $rid=$_GPC['rid'];
	  $ad = pdo_fetchcolumn("SELECT ad FROM ".tablename('vote_reply')." WHERE rid=:rid",array(':rid'=>$rid));
	  include $this->template('ad');
  }
}