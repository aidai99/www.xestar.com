<?php
if (! defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class Utils
{
    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match("/^1[34578]{1}\d{9}$/",$mobile) ? true : false;
    }
    
    public function getbianma($data,$webbianma = "gbk"){
        if ($webbianma == "gbk") {
            $data = diconv($data,'GB2312', 'UTF-8');
             
        }
        $data = isset($data) ? trim(htmlspecialchars($data, ENT_QUOTES)) : '';
        return $data;
    }
    
    function generate_code($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }
    
    //type 0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
    function gettype($type = 0) {
        $typestr = "";
        switch ($type){
            case 0:
                $typestr = plang('typeceshi');
                break;
            case 1:
                $typestr = plang('typezhuce');
                break;
            case 2:
                $typestr = plang('typeshenfenyanzheng');
                break;
            case 3:
                $typestr = plang('typedenglu');
                break;
            case 4:
                $typestr = plang('typexiugaimima');
                break;
            default:
                $typestr = plang('typeceshi');
                break;
        }
        return $typestr;
    }
    
    function getstatus($status = 0) {
        $statusstr = "";
        switch ($status){
            case 0:
                $statusstr = plang('statuserror');
                break;
            case 1:
                $statusstr = plang('statussuccess');
                break;
            default:
                $typestr = plang('statuserror');
                break;
        }
        return $statusstr;
    }
    
    
    
    function plang($str) {
        return lang('plugin/jzsjiale_sms', $str);
    }
}
?>