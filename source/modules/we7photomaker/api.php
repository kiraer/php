<?php

/**

 * 微擎微拍模块微站定义

 *

 * @author 微擎团队

 * @url 

 */

define('IN_SYS', true);

require '../../bootstrap.inc.php';



$actions = array('getsetting', 'main', 'getdata', 'finished', 'failed', 'batchfinish');

if (in_array($_GPC['act'], $actions)) {

	$action = $_GPC['act'];

} else {

	exit('Access Denied');

}

$_W['attachurl'] = str_replace('source/modules/we7photomaker/', '', $_W['attachurl']);

$site = WeUtility::createModuleSite('we7photomaker');

$site->inMobile = false;

$site->module['name'] = 'we7photomaker';

if (method_exists($site, 'doWeb'.$action)) {

	call_user_func(array($site, 'doWeb'.$action));

	exit;

} else {

	exit('Access Denied');

}



