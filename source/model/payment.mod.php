<?php
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');

define('ALIPAY_GATEWAY', 'http://wappaygw.alipay.com/service/rest.htm');

/**
 * $params['tid']
 * $params['title']
 * $params['fee']
 * $params['user']
 *
 * $alipay['account']
 * $alipay['partner']
 * $alipay['secret']
 *
 * $ret['url']
 */
function alipay_build($params, $alipay = array()) {
	global $_W;
	$tid = $_W['weid'] . ':' . $params['tid'];
	$set = array();
	$set['service'] = 'alipay.wap.trade.create.direct';
	$set['format'] = 'xml';
	$set['v'] = '2.0';
	$set['partner'] = $alipay['partner'];
	$set['req_id'] = $tid;
	$set['sec_id'] = 'MD5';
	$callback = $_W['siteroot'] . 'payment/alipay/return.php';
	$notify = $_W['siteroot'] . 'payment/alipay/notify.php';
	$merchant = $_W['siteroot'] . 'payment/alipay/merchant.php';
	$expire = 10;
	$set['req_data'] = "<direct_trade_create_req><subject>{$params['title']}</subject><out_trade_no>{$tid}</out_trade_no><total_fee>{$params['fee']}</total_fee><seller_account_name>{$alipay['account']}</seller_account_name><call_back_url>{$callback}</call_back_url><notify_url>{$notify}</notify_url><out_user>{$params['user']}</out_user><merchant_url>{$merchant}</merchant_url><pay_expire>{$expire}</pay_expire></direct_trade_create_req>";
	$prepares = array();
	foreach($set as $key => $value) {
		if($key != 'sign') {
			$prepares[] = "{$key}={$value}";
		}
	}
	sort($prepares);
	$string = implode($prepares, '&');
	$string .= $alipay['secret'];
	$set['sign'] = md5($string);
	$response = ihttp_get(ALIPAY_GATEWAY . '?' . http_build_query($set));
	$ret = array();
	@parse_str($response['content'], $ret);
	foreach($ret as &$v) {
		$v = str_replace('\"', '"', $v);
	}
	if(is_array($ret)) {
		if($ret['res_error']) {
			$error = simplexml_load_string($ret['res_error'], 'SimpleXMLElement', LIBXML_NOCDATA);
			if($error instanceof SimpleXMLElement && $error->detail) {
				message("发生错误, 无法继续支付. 详细错误为: " . strval($error->detail));
			}
		}

		if($ret['partner'] == $set['partner'] && $ret['req_id'] == $set['req_id'] && $ret['sec_id'] == $set['sec_id'] && $ret['service'] == $set['service'] && $ret['v'] == $set['v']) {
			$prepares = array();
			foreach($ret as $key => $value) {
				if($key != 'sign') {
					$prepares[] = "{$key}={$value}";
				}
			}
			sort($prepares);
			$string = implode($prepares, '&');
			$string .= $alipay['secret'];
			if(md5($string) == $ret['sign']) {
				$obj = simplexml_load_string($ret['res_data'], 'SimpleXMLElement', LIBXML_NOCDATA);
				if($obj instanceof SimpleXMLElement && $obj->request_token) {
					$token = strval($obj->request_token);
					$set = array();
					$set['service'] = 'alipay.wap.auth.authAndExecute';
					$set['format'] = 'xml';
					$set['v'] = '2.0';
					$set['partner'] = $alipay['partner'];
					$set['sec_id'] = 'MD5';
					$set['req_data'] = "<auth_and_execute_req><request_token>{$token}</request_token></auth_and_execute_req>";
					$prepares = array();
					foreach($set as $key => $value) {
						if($key != 'sign') {
							$prepares[] = "{$key}={$value}";
						}
					}
					sort($prepares);
					$string = implode($prepares, '&');
					$string .= $alipay['secret'];
					$set['sign'] = md5($string);
					$url = ALIPAY_GATEWAY . '?' . http_build_query($set);
					return array('url' => $url);
				}
			}
		}
	}
	message('非法访问.');
}

/**
 * $params['tid']
 * $params['title']
 * $params['fee']
 * $params['user']
 *
 * $alipay['account']
 * $alipay['partner']
 * $alipay['secret']
 *
 * @return js payment object
 */
function wechat_build($params, $wechat) {
	global $_W;
	$wOpt['appId'] = $_W['account']['key'];
	$wOpt['timeStamp'] = TIMESTAMP;
	$wOpt['nonceStr'] = random(8);
	$package = array();
	$package['bank_type'] = 'WX';
	$package['body'] = $params['title'];
	$package['attach'] = $_W['weid'];
	$package['partner'] = $wechat['partner'];
	$package['out_trade_no'] = $params['tid'];
	$package['total_fee'] = $params['fee'] * 100;
	$package['fee_type'] = '1';
	$package['notify_url'] = $_W['siteroot'] . 'notify.php'; #todo: 这里调用$_W['siteroot']是在子目录下. 获取的是当前二级目录
	$package['spbill_create_ip'] = CLIENT_IP;
	$package['time_start'] = date('YmdHis', TIMESTAMP);
	$package['time_expire'] = date('YmdHis', TIMESTAMP + 600);
	$package['input_charset'] = 'UTF-8';
	ksort($package);
	$string1 = '';
	foreach($package as $key => $v) {
		$string1 .= "{$key}={$v}&";
	}
	$string1 .= "key={$wechat['key']}";
	$sign = strtoupper(md5($string1));

	$string2 = '';
	foreach($package as $key => $v) {
		$v = urlencode($v);
		$string2 .= "{$key}={$v}&";
	}
	$string2 .= "sign={$sign}";
	$wOpt['package'] = $string2;

	$string = '';
	$keys = array('appId', 'timeStamp', 'nonceStr', 'package', 'appKey');
	sort($keys);
	foreach($keys as $key) {
		$v = $wOpt[$key];
		if($key == 'appKey') {
			$v = $wechat['signkey'];
		}
		$key = strtolower($key);
		$string .= "{$key}={$v}&";
	}
	$string = rtrim($string, '&');

	$wOpt['signType'] = 'SHA1';
	$wOpt['paySign'] = sha1($string);
	return $wOpt;
}
