<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/11/29 13:35
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
// ������ֳ�ֵϵͳ

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}






$vars_7ree = $_G['cache']['plugin']['x7ree_recharge'];


//�Ƿ��ѵ�¼�������
if(in_array($code_7ree,array('1','2')) && !$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}

//submit���
if (in_array($code_7ree,array('1')) && !submitcheck('submit_7ree')) showmessage('Access Denied @ 7ree');

$code_7ree = intval($_GET['code_7ree']);
$id_7ree = intval($_GET['id_7ree']);

$navtitle = "���ֳ�ֵ";
$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];

//����Ա�������
if(in_array($code_7ree,array('4','5')) && ($_G['adminid']<>1)) showmessage('Access Denied @ 7ree');

//id��ʧ���
if(in_array($code_7ree,array('5')) && !$id_7ree) showmessage("ERROR@7REE, MISS ID.");

if(in_array($code_7ree,array('2','5')) && $_GET['formhash'] <> FORMHASH) showmessage("Access Deined @ 7ree");


$pagenum_7ree = 20;




if(!$code_7ree){//��ֵ��ҳ
	$ext_7ree=intval($_GET['ext_7ree']);
	//shwomessage($ext_7ree);
	//$ext_7ree=intval($_COOKIE['ext_7ree']);
	$cost_7ree = round($ext_7ree*$vars_7ree['rate_7ree']/100,2);//֧����������
	$cost2_7ree=$cost_7ree*100;//΢�Ÿ�����
	//$cost1_7ree=0.01;
	//$cost2_7ree=1;
	if($ext_7ree){
			if(!$_G['uid']){
					showmessage('not_loggedin', NULL, array(), array('login' => 1));
			}
			$type_7ree=intval($_GET['type_7ree']);

			if($type_7ree==3){//����ת��֧��
							$oid_7ree='yhz'.date("YmdHis").'-uid'.$_G['uid'].'-ext'.$ext_7ree;
							$paycode_7ree = substr(date("YmdHis"), -4);

			}else{
				
					if(defined('IN_MOBILE')){///////////////////�ֻ�΢��֧������///////////////////
							$oid_7ree='wxp'.date("YmdHis").'-uid'.$_G['uid'].'-ext'.$ext_7ree;
							
							if($_GET['code']){
									$type_7ree=1;
									$insertvalue_7ree=array(
														'uid_7ree'=>$_G['uid'],
														'user_7ree'=>$_G['username'],
														'time_7ree'=>$_G['timestamp'],
														'ip_7ree'=>$_G['clientip'],
														'ext_7ree'=>$ext_7ree,
														'cost_7ree'=>$cost_7ree,
														'type_7ree'=>$type_7ree,
														'orderid_7ree'=>$oid_7ree,
									);

									DB::insert('x7ree_recharge_log', $insertvalue_7ree);
									$id_7ree = DB::insert_id();
									
									//$jsurl_7ree = urlencode("http://wenledao.com/plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=2&oid_7ree=".$oid_7ree);
									$jsurl_7ree = "http://wenledao.com/plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=2&oid_7ree=".$oid_7ree."&formhash=".FORMHASH;
							}
						
						
							ini_set('date.timezone','Asia/Shanghai');
							//error_reporting(E_ERROR);
							require_once "./source/plugin/x7ree_recharge/lib/WxPay.Api.php";
							require_once "./source/plugin/x7ree_recharge/WxPay.JsApiPay.php";
							require_once './source/plugin/x7ree_recharge/log.php';
							//�١���ȡ�û�openid
							$tools = new JsApiPay();
							$openId = $tools->GetOpenid();
							//�ڡ�ͳһ�µ�
							$input = new WxPayUnifiedOrder();

							$body_7ree=iconv("GB2312", "UTF-8//IGNORE", "��ֵ����".$ext_7ree.$exttitle_7ree);
							$input->SetBody($body_7ree);
							$input->SetAttach("extcreidt");
							$input->SetOut_trade_no($oid_7ree);
							$input->SetTotal_fee($cost2_7ree);
							$input->SetTime_start(date("YmdHis"));
							$input->SetTime_expire(date("YmdHis", time() + 600));
							$input->SetGoods_tag("wendaole");
							//$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
							$input->SetNotify_url("http://wenledao.com/plugin.php?id=x7ree_recharge:notify_7ree");
							$input->SetTrade_type("JSAPI");
							$input->SetOpenid($openId);
							$order = WxPayApi::unifiedOrder($input);
							
							/*
							echo '<font color="#f00"><b>ͳһ�µ�֧������Ϣ</b></font><br/>';
							print_r($order);
							*/

							$jsApiParameters = $tools->GetJsApiParameters($order);

							//��ȡ�����ջ���ַjs��������
							//$editAddress = $tools->GetEditAddressParameters();

							//�ۡ���֧�ֳɹ��ص�֪ͨ�д���ɹ�֮������ˣ��� notify.php
							/**
							 * ע�⣺
							 * 1������Ļص���ַ���ɷ��ʵ�ʱ�򣬻ص�֪ͨ��ʧ�ܣ�����ͨ����ѯ������ȷ��֧���Ƿ�ɹ�
							 * 2��jsapi֧��ʱ��Ҫ�����û�openid��WxPay.JsApiPay.php���л�ȡopenid���� ���ĵ����Բο�΢�Ź���ƽ̨����ҳ��Ȩ�ӿڡ���
							 * �ο�http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html��
							 */
					}else{///////////////////pcɨ��֧������///////////////////
					
							///����΢�ź�֧������֧�����Ͳ�ͬ

							if(!$type_7ree){
								showmessage("����ѡ�񸶿ʽ���ύ�����ڷ��ء�");
							}elseif($type_7ree==1){//΢��PC֧��
									$oid_7ree='smp'.date("YmdHis").'-uid'.$_G['uid'].'-ext'.$ext_7ree;
									$jsurl_7ree = "http://wenledao.com/plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=2&oid_7ree=".$oid_7ree."&formhash=".FORMHASH;
											$type_7ree=1;
											$insertvalue_7ree=array(
																'uid_7ree'=>$_G['uid'],
																'user_7ree'=>$_G['username'],
																'time_7ree'=>$_G['timestamp'],
																'ip_7ree'=>$_G['clientip'],
																'ext_7ree'=>$ext_7ree,
																'cost_7ree'=>$cost_7ree,
																'type_7ree'=>$type_7ree,
																'orderid_7ree'=>$oid_7ree,
											);

											DB::insert('x7ree_recharge_log', $insertvalue_7ree);
											$id_7ree = DB::insert_id();
											
											$jsurl_7ree = "http://wenledao.com/plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=2&oid_7ree=".$oid_7ree."&formhash=".FORMHASH;
									ini_set('date.timezone','Asia/Shanghai');
									//error_reporting(E_ERROR);
									require_once "./source/plugin/x7ree_recharge/lib/WxPay.Api.php";
									require_once "./source/plugin/x7ree_recharge/WxPay.NativePay.php";
									$notify = new NativePay();
									$input = new WxPayUnifiedOrder();

									$body_7ree=iconv("GB2312", "UTF-8//IGNORE", "��ֵ����".$ext_7ree.$exttitle_7ree);
									$input->SetBody($body_7ree);

									$input->SetAttach("extcreidt");
									$input->SetOut_trade_no($oid_7ree);
									$input->SetTotal_fee($cost2_7ree);
									$input->SetTime_start(date("YmdHis"));
									$input->SetTime_expire(date("YmdHis", time() + 600));
									$input->SetGoods_tag("wendaole");
									$input->SetNotify_url("http://wenledao.com/plugin.php?id=x7ree_recharge:notify_7ree");
									$input->SetTrade_type("NATIVE");
									$input->SetProduct_id($id_7ree);

									$result = $notify->GetPayUrl($input);
									$url2_7ree = $result["code_url"];
									$url3_7ree = urlencode($url2_7ree);
									
							}elseif($type_7ree==2){//֧����֧��
									$oid_7ree='ali'.date("YmdHis").'-uid'.$_G['uid'].'-ext'.$ext_7ree;
									
									$insertvalue_7ree=array(
																'uid_7ree'=>$_G['uid'],
																'user_7ree'=>$_G['username'],
																'time_7ree'=>$_G['timestamp'],
																'ip_7ree'=>$_G['clientip'],
																'ext_7ree'=>$ext_7ree,
																'cost_7ree'=>$cost_7ree,
																'type_7ree'=>$type_7ree,
																'orderid_7ree'=>$oid_7ree,
															);

									DB::insert('x7ree_recharge_log', $insertvalue_7ree);
									$id_7ree = DB::insert_id();
									
									require_once("./source/plugin/x7ree_recharge/lib/alipay.config.php");
									require_once("./source/plugin/x7ree_recharge/lib/alipay_submit.class.php");
									$subject = "��ֵ����".$ext_7ree.$exttitle_7ree;
									$parameter = array(
											"service"       => $alipay_config['service'],
											"partner"       => $alipay_config['partner'],
											"seller_id"  => $alipay_config['seller_id'],
											"payment_type"	=> $alipay_config['payment_type'],
											"notify_url"	=> $alipay_config['notify_url'],
											"return_url"	=> $alipay_config['return_url'],
											
											"anti_phishing_key"=>$alipay_config['anti_phishing_key'],
											"exter_invoke_ip"=>$alipay_config['exter_invoke_ip'],
											"out_trade_no"	=> $oid_7ree,
											"subject"	=> $subject,
											"total_fee"	=> $cost1_7ree,
											"body"	=> $body,
											"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
									);

									//��������
									$alipaySubmit = new AlipaySubmit($alipay_config);
									$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "ȷ��");
									echo $html_text;

							}
					}
			
			}
		

	}else{

					$default_7ree=explode(',', $vars_7ree['default_7ree']);
					$membercount_7ree = getuserprofile('extcredits'.$vars_7ree['ext_7ree']);
					$rate_7ree = $vars_7ree['rate_7ree']/100;
	}








}elseif($code_7ree==1){//�ҵĳ�ֵ��¼
	$page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
	$querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('x7ree_recharge_log')." WHERE uid_7ree = '{$_G[uid]}'");
	$query = DB::query("SELECT * FROM ".DB::table('x7ree_recharge_log')." WHERE uid_7ree = '{$_G[uid]}' ORDER BY id_7ree DESC LIMIT {$startpage}, {$pagenum_7ree}");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$result['paycode_7ree'] = substr($result['orderid_7ree'],13,4);
		$list_7ree[] = $result;
	}
	$multipage = multi($querynum, $pagenum_7ree, $page, "plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=1" );

}elseif($code_7ree==2){//���ز��������ύ��ֵ����
/*
		$ext_7ree = intval($_GET['ext_7ree']);
		$type_7ree = intval($_GET['type_7ree']);
		if(!$ext_7ree) showmessage("��Ǹ������������Ҫ��ֵ�Ļ���֮�����ύ��");
		$cost_7ree =  round($ext_7ree*$vars_7ree['rate_7ree']/100,2);
		//db��־��¼
		$insertvalue_7ree=array(
							'uid_7ree'=>$_G['uid'],
							'user_7ree'=>$_G['username'],
							'time_7ree'=>$_G['timestamp'],
							'ip_7ree'=>$_G['clientip'],
							'ext_7ree'=>$ext_7ree,
							'cost_7ree'=>$cost_7ree,
							'type_7ree'=>$type_7ree,
		);
		DB::insert('x7ree_recharge_log', $insertvalue_7ree);
		$id_7ree = DB::insert_id();
*/
		//����΢�ŷ���״̬���̻������Ÿ���log��ֵ״̬
		$orderid_7ree = dhtmlspecialchars(trim($_GET['oid_7ree']));
		$thisorder_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_recharge_log')." WHERE orderid_7ree='".$orderid_7ree."' AND uid_7ree= $_G[uid] AND status_7ree=0");
		if($thisorder_7ree['orderid_7ree'] && $_G['uid']){
			$checkorderurl_7ree='http://wenledao.com/plugin.php?id=x7ree_recharge:checkorder_7ree&out_trade_no='.$thisorder_7ree['orderid_7ree'];
			$result = https_request($checkorderurl_7ree);
			if($result<>'SUCCESS') showmessage("ERROR##7REE@WX PAY FAIL.");
			$updatevalue_7ree=array('status_7ree'=>1,);
			$wherevalue_7ree=array('orderid_7ree'=>$thisorder_7ree['orderid_7ree'],'uid_7ree'=>$_G['uid']);
			DB::update('x7ree_recharge_log', $updatevalue_7ree, $wherevalue_7ree);
			//���ӻ�Ա����
			updatemembercount($_G['uid'], array($vars_7ree['ext_7ree'] => $thisorder_7ree['ext_7ree']));
			showmessage("��ϲ���ɹ���ֵ����".$thisorder_7ree['ext_7ree'].$exttitle_7ree."�����ڷ��ء�",'plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=1');
		}else{
			showmessage("ERROR##7REE@MISS OR BAD OID.");
		}

}elseif($code_7ree==3){//����ת����Ϣ���ύ��д��
		$oid_7ree=$_GET['oid_7ree'];
		$type_7ree=intval($_GET['type_7ree']);
		$ext_7ree=intval($_GET['ext_7ree']);
		$cost_7ree = round($ext_7ree*$vars_7ree['rate_7ree']/100,2);//֧����������
		$bank_7ree=$_GET['bank_7ree'];
		$account_7ree=$_GET['account_7ree'];
		$cardnum_7ree=$_GET['cardnum_7ree'];
		if(!$bank_7ree || !$account_7ree || !$cardnum_7ree) showmessage('��Ǹ�����С��˺źͻ�����Ϣ��������д���뷵���޸ġ�');
		
		$insertvalue_7ree=array(
										'uid_7ree'=>$_G['uid'],
										'user_7ree'=>$_G['username'],
										'time_7ree'=>$_G['timestamp'],
										'ip_7ree'=>$_G['clientip'],
										'ext_7ree'=>$ext_7ree,
										'cost_7ree'=>$cost_7ree,
										'type_7ree'=>$type_7ree,
										'bank_7ree'=>$bank_7ree,
										'account_7ree'=>$account_7ree,
										'cardnum_7ree'=>$cardnum_7ree,
										'orderid_7ree'=>$oid_7ree,
		);

		DB::insert('x7ree_recharge_log', $insertvalue_7ree);
							$id_7ree = DB::insert_id();
		
		
		
		showmessage('��ϲ������ת����Ϣ�ѳɹ��ύ����ȴ���ˡ�','plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=1');


}elseif($code_7ree==4){//�����ֵ�б�
	$page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
	$querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('x7ree_recharge_log'));
	$query = DB::query("SELECT * FROM ".DB::table('x7ree_recharge_log')." ORDER BY id_7ree DESC LIMIT {$startpage}, {$pagenum_7ree}");
	while($result = DB::fetch($query)) {
		$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$result['paycode_7ree'] = substr($result['orderid_7ree'],13,4);
		$list_7ree[] = $result;
	}
	$multipage = multi($querynum, $pagenum_7ree, $page, "plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=4" );

}elseif($code_7ree==5){//����Ա���ͨ����������
		$thislog_7ree= DB::fetch_first("SELECT * FROM ".DB::table('x7ree_recharge_log')." WHERE id_7ree = '{$id_7ree}'");
		if($thislog_7ree['uid_7ree'] && $thislog_7ree['ext_7ree'] && $thislog_7ree['cost_7ree'] && !$thislog_7ree['status_7ree']){
			$valuearray_7ree=array('status_7ree'=>1);
			$wherearray_7ree=array('id_7ree'=>$id_7ree);
			DB::update('x7ree_recharge_log', $valuearray_7ree, $wherearray_7ree);
		}
			//���ӻ�Ա����
			updatemembercount($thislog_7ree['uid_7ree'], array($vars_7ree['ext_7ree'] => $thislog_7ree['ext_7ree']));


		showmessage('��ϲ�����ͨ������Ա���������ˡ�','plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=4');
}else{
		showmessage("Undefined Operation @ 7ree.com");
}

		include template('x7ree_recharge:x7ree_recharge');
		

 
function https_request($url, $data = null){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}

?>