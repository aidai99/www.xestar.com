<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/4/20 17:12
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
// ΢�Ŷ�����ѯ

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_recharge'];

ini_set('date.timezone','Asia/Shanghai');
require_once "./source/plugin/x7ree_recharge/lib/WxPay.Api.php";
$tradeId = trim($_GET["out_trade_no"]);

if(isset($tradeId) && $tradeId != ""){

    $input = new WxPayOrderQuery();
    $input->SetOut_trade_no($tradeId); // ���ú�Ҫ��ѯ�Ķ���
    $order = WxPayApi::orderQuery($input); // ���в�ѯ
    //var_dump($order); // ��ӡ��������Ϣ

}


if($order['err_code_des'] =="order not exist"){
    echo('FAIL01');
}else{
    $money = $order['total_fee']; //��������,��λ�� 
    if($order['trade_state'] =="SUCCESS"){
    	echo('SUCCESS');
        //֧���ɹ�
/*
    }else if($order['trade_state'] =="REFUND"){
        //���˿�
    }else if($order['trade_state'] =="NOTPAY"){
        //�û���û֧��
    }else if($order['trade_state'] =="CLOSED"){
        //�����ر�
    }else if($order['trade_state'] =="REVOKED"){
        //�ѳ�����ˢ��֧����
    }else if($order['trade_state'] =="USERPAYING"){
        //�û�֧����
    }else if($order['trade_state'] =="PAYERROR"){
        //֧��ʧ��(����ԭ���������з���ʧ��)
*/
    }else{
    	echo('FAIL');
    }
}


?>