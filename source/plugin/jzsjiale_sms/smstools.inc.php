<?php
if (! defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require_once  DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/SMS.php';


class SMSTools
{

    public $accesskeyid;
    
    public $accesskeysecret;
    
    public function __construct($accesskeyid = "",$accesskeysecret = ""){
        $this->accesskeyid = $accesskeyid;
        $this->accesskeysecret = $accesskeysecret ;
    }
    
    public function smssend($code,$expire,$type=0,$uid,$phoneNumbers = "",$signName = "",$templateCode = "",$templateParam = "")
    {
        if(empty($phoneNumbers) || empty($signName) || empty($templateCode) || empty($templateParam)){
            return;
        }
        loadcache('plugin');
        global $_G;
        $webbianma = $_G['charset'];
        
        $isok = false;

        $retdata = "error";
        
        
        //echo "==phoneNumbers:=".$phoneNumbers."---signName--".$signName.'==templateCode=='.$templateCode.'==templateParam=='.$templateParam;
        //exit;
      
            //sms send start
            $sms = new SMS();
            $sms->__construct($this->accesskeyid, $this->accesskeysecret);
            $ret = $sms->smssend($phoneNumbers,$signName,$templateCode,$templateParam);
            
            //sms send end
            
            
            //$ret = '{"Message":"\u6ca1\u6709\u8bbf\u95ee\u6743\u9650","RequestId":"43C8D5C3-0EC7-4390-AC42-D12316AFB630","Code":"isv.BUSINESS_LIMIT_CONTROL"}';
            //$ret = '{"Message":"OK","RequestId":"962AE9DB-EAF7-4098-A4FF-5F5F1B4F39B1","BizId":"108711042472^1111710451471","Code":"OK"}';
            //echo "====".$ret;EXIT;
            $retinfo = json_decode($ret);
            
            //echo "===".$retinfo->Code;
            if($retinfo != null && $retinfo->Code == 'OK'){
                $retdata = "success";
                $isok = true;
            }elseif($retinfo != null && $retinfo->Code != 'OK'){
                $retdata = $retinfo->Code;
                $isok = false;
            }else{
                $retdata = "error";
                $isok = false;
            }
            
            
        if($isok){
            $smscode = array('dateline'=>TIMESTAMP);
            $smscode['uid'] = $uid;
            $smscode['phone'] = $phoneNumbers;
            $smscode['seccode'] = $code;
            $smscode['expire'] = $expire;//guoqishijian
             
            if(C::t('#jzsjiale_sms#jzsjiale_sms_code')->insert($smscode,true)){
                $isok = true;
            }else{
                $isok = false;
            }
        }
        
        
        //if($isok){
            $smslist = array('dateline'=>TIMESTAMP);
            $smslist['uid'] = $uid;
            $smslist['phone'] = $phoneNumbers;
            $smslist['seccode'] = $code;
            $smslist['msg'] = $ret;
            $smslist['type'] = $type;//type:0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
            $smslist['status'] = ($retdata == "success")?1:0;
            
            if(C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->insert($smslist,true)){
                $isok = true;
            }else{
                $isok = false;
            }
        //}
      
        
        return $retdata;
    }
}