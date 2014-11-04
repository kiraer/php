<?php
/**
 * 3D展示模块微站定义
 *
 * @author hy
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class QuanjingModuleSite extends WeModuleSite {

	public function getHomeTiles() {
		//这个操作被定义用来呈现微站主页上的导航图标，返回值为数组结构, 每个元素将被呈现为一个链接. 元素结构为 array('title'=>'标题', 'url'=>'链接目标')
		global $_W, $_GPC;
		return array(
			array('title'=>'全景展示', 'url'=> create_url('index/module', array('do' => 'userlist', 'name' => 'quanjing', 'weid'=>$_W['weid']))),
		);
	}

	public function getProfileTiles() {
		//这个操作被定义用来呈现微站个人中心上的管理链接，返回值为数组结构, 每个元素将被呈现为一个链接. 元素结构为 array('title'=>'标题', url'=>'链接目标')
	}


}