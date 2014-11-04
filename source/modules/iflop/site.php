<?php
/**
 * 翻牌抽奖
 *
 * 作者:迷失卍国度
 *
 * qq:15595755
 */
defined('IN_IA') or exit('Access Denied');

class IflopModuleSite extends WeModuleSite {

    public function doMobileIndex(){
        global $_W,$_GPC;
        include $this->template('wap_index');
    }

    //抽奖
    public function doMobileLottery(){
        global $_W,$_GPC;
        $action = $_GPC['action'];
        $rid = intval($_GPC['rid']);

        $prize_arr = array(
            '0' => array('id'=>1,'prize'=>'佳能5d3','v'=>1),
            '1' => array('id'=>2,'prize'=>'美里达挑战者700','v'=>5),
            '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
            '3' => array('id'=>4,'prize'=>'单反杯','v'=>12),
            '4' => array('id'=>5,'prize'=>'10Q币','v'=>22),
            '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50)
        );
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this -> get_rand($arr); //根据概率获取奖项id
        $res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项
        unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项
        shuffle($prize_arr); //打乱数组顺序
        for($i=0;$i<count($prize_arr);$i++){
            $pr[] = $prize_arr[$i]['prize'];
        }
        $res['no'] = $pr;
        echo json_encode($res);
    }

    //取得随机产品
    function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}