<?php
/* * 
 * ���ܣ�֧����ҳ����תͬ��֪ͨҳ��
 * �汾��3.3
 * ���ڣ�2012-07-23
 * ˵����
 * ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 * �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

 *************************ҳ�湦��˵��*************************
 * ��ҳ����ڱ������Բ���
 * �ɷ���HTML������ҳ��Ĵ��롢�̻�ҵ���߼��������
 * ��ҳ�����ʹ��PHP�������ߵ��ԣ�Ҳ����ʹ��д�ı�����logResult���ú����ѱ�Ĭ�Ϲرգ���alipay_notify_class.php�еĺ���verifyReturn
 */

require_once("./lib/alipay.config.php");
require_once("./lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<?php
	

//����ó�֪ͨ��֤���
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();


if($verify_result) {//��֤�ɹ�
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//������������̻���ҵ���߼��������
	
	//�������������ҵ���߼�����д�������´�������ο�������
    //��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�

	//�̻�������
	$out_trade_no = $_GET['out_trade_no'];

	//֧�������׺�
	$trade_no = $_GET['trade_no'];

	//����״̬
	$trade_status = $_GET['trade_status'];



    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//�жϸñʶ����Ƿ����̻���վ���Ѿ���������
			//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
			//���������������ִ���̻���ҵ�����
			
			//����discuz����
			require_once '../../class/class_core.php';
			require_once '../../function/function_core.php';
			$discuz = C::app();
			$cachelist = array('plugin');
			$discuz->cachelist = $cachelist;
			$discuz->init();
			
			$vars_7ree = $_G['cache']['plugin']['x7ree_recharge'];
			
			
			
			
			$orderid_7ree = dhtmlspecialchars(trim($_GET['out_trade_no']));
			//showmessage($orderid_7ree);
			$thisorder_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_recharge_log')." WHERE orderid_7ree='".$orderid_7ree."' AND uid_7ree= $_G[uid] AND status_7ree=0");
			if($thisorder_7ree['orderid_7ree'] && $_G['uid']){
				$updatevalue_7ree=array('status_7ree'=>1,);
				$wherevalue_7ree=array('orderid_7ree'=>$orderid_7ree,'uid_7ree'=>$_G['uid']);
				DB::update('x7ree_recharge_log', $updatevalue_7ree, $wherevalue_7ree);
				//���ӻ�Ա���֣���ϵͳ��־��¼
				updatemembercount($_G['uid'], array($vars_7ree['ext_7ree'] => $thisorder_7ree['ext_7ree']), false, 'REJ',7);
				echo "<script>window.location.href='http://wenledao.com/plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=1';</script>";
				//showmessage("��ϲ���ɹ���ֵ����".$thisorder_7ree['ext_7ree'].$exttitle_7ree."�����ڷ��ء�",'http://wenledao.com/plugin.php?id=x7ree_recharge:x7ree_recharge&code_7ree=1');
			}else{
				showmessage("ERROR##7REE@MISS OR BAD OID.");
			}
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
		

	//�������������ҵ���߼�����д�������ϴ�������ο�������
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //��֤ʧ��
    //��Ҫ���ԣ��뿴alipay_notify.phpҳ���verifyReturn����
    echo "��֤ʧ��";
}
?>
        <title>֧������ʱ���˽��׽ӿ�</title>
	</head>
    <body>
    </body>
</html>