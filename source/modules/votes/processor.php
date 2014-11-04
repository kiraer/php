<?php
/**
 * 微投票模块处理程序
 *
 * @author Libi
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class VotesModuleProcessor extends WeModuleProcessor {
	public function respond() {
		//$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微动力文档来编写你的代码
			  global $_W;	
			   
      $rid = $this->rule;
      $reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
      if($reply==false){
        $message='活动已经取消了';
 		return $this->respText($message);        
      }
	  
        //文字图片投票 
        //记录用户
         $fans=pdo_fetch("SELECT * FROM " . tablename('vote_fans') . "where rid=".$rid." and from_user='".$this->message['from']."' and  status=0  ");	
         if($fans==false){
           	//新用户，插入
			$data=array(
          		'from_user'=> $this->message['from'],
               	'rid'=>$rid,
                'oid'=>0,
                'vote_num'=>0,
              	'vote_time'=>0,
        	);
        	pdo_insert('vote_fans',$data,true);
          }
		  	$content = $this->message['content'];
			
        	$response['FromUserName'] = $this->message['to'];
			$response['ToUserName'] = $this->message['from'];
			$response['MsgType'] = 'news';
			$response['ArticleCount'] = 1;
			$response['Articles'] = array();
			if(preg_match('/^TMH([0-9]{1,9})$/',$content,$r)){
				$single_info=pdo_fetch("SELECT * FROM ".tablename('vote_option')." WHERE id = :id AND allowed='1'", array(':id' => $r[1]));
				if(empty($single_info)) {return $this->respText('该选手不存在或正在审核！');   }
				$fans = fans_search($single_info['from_user'],array('realname'));
				$response['Articles'][] = array(
				'Title' => $fans['realname'],
				'Description' => $single_info['content'],
				'PicUrl' =>empty($reply['thumb'])?'':$_W['attachurl'] . $reply['thumb'],
				'Url' => $this->buildSiteUrl($this->createmobileurl('single', array('rid' => $rid,'id'=>$r[1]))),
				'TagName' => 'item',
				);

			}else{
				$response['Articles'][] = array(
				'Title' => $reply['title'],
				'Description' => $reply['description'],
				'PicUrl' =>empty($reply['thumb'])?'':$_W['attachurl'] . $reply['thumb'],
				'Url' => $this->buildSiteUrl($this->createmobileurl('list', array('rid' => $rid))),
				'TagName' => 'item',
				);
			}

		return $response;
	}
}