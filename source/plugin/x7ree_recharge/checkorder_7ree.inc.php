<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/4/20 17:12
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
// 微信订单查询

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_recharge'];

ini_set('date.timezone','Asia/Shanghai');
require_once "./source/plugin/x7ree_recharge/lib/WxPay.Api.php";
$tradeId = trim($_GET["out_trade_no"]);

if(isset($tradeId) && $tradeId != ""){

    $input = new WxPayOrderQuery();
    $input->SetOut_trade_no($tradeId); // 设置好要查询的订单
    $order = WxPayApi::orderQuery($input); // 进行查询
    //var_dump($order); // 打印出订单信息

}


if($order['err_code_des'] =="order not exist"){
    echo('FAIL01');
}else{
    $money = $order['total_fee']; //所付款数,单位分 
    if($order['trade_state'] =="SUCCESS"){
    	echo('SUCCESS');
        //支付成功
/*
    }else if($order['trade_state'] =="REFUND"){
        //已退款
    }else if($order['trade_state'] =="NOTPAY"){
        //用户还没支付
    }else if($order['trade_state'] =="CLOSED"){
        //订单关闭
    }else if($order['trade_state'] =="REVOKED"){
        //已撤销（刷卡支付）
    }else if($order['trade_state'] =="USERPAYING"){
        //用户支付中
    }else if($order['trade_state'] =="PAYERROR"){
        //支付失败(其他原因，例如银行返回失败)
*/
    }else{
    	echo('FAIL');
    }
}


?>