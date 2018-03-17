<?php
/**
 * Created by www.zheyitianshi.com.
 * User: www.zheyitianshi.com
 * Date: 2016/7/18
 * Time: 10:43
 */
class LoginResponse
{

    public function subscribe($param)
    {
        list($data) = $param;
        $openid = $data['from'];
        if($openid){

            global $_G;
            include_once DISCUZ_ROOT. 'source/plugin/wechat/wechat.lib.class.php';
            include_once libfile('function/cache');
            $wechat_client = new WeChatClient($_G['wechat']['setting']['wechat_appId'], $_G['wechat']['setting']['wechat_appsecret']);
            $userinfo = $wechat_client->getUserInfoById($openid);
            unset($userinfo['unionid']);
            foreach ($userinfo as $index => $item) {
                $userinfo[$index] = diconv($userinfo[$index], 'utf-8');
            }
            if($userinfo){
                C::t('#xigua_vote#xigua_user')->insert($userinfo);
            }

            C::t('#xigua_vote#xigua_user')->subscribe($openid);
        }
        include_once DISCUZ_ROOT .'source/plugin/wechat/response.class.php';
        self::_custom('subscribe');

        /* if no guanzhu text */
        if($openid){
            global $_G;
            $list = array();
            $vdata = C::t('#xigua_vote#xigua_vote')->fetch_default_vid();
            $exopenid = urlencode($openid);
            $list[0] = array(
                'title' => $vdata['title'],
                'desc'  => '',
                'pic'   => $vdata['cover'][0],
                'url'   => $_G['siteurl'].'plugin.php?id=xigua_vote:index&vid='.$vdata['vid'].'&openid='.$exopenid,
            );
            echo WeChatServer::getXml4RichMsgByArray($list);
        }
    }

    public function redirect(){

        if(empty($_G['cache']['plugin'])){
            loadcache('plugin');
        }

        $config = $_G['cache']['plugin']['xigua_login'];
        if($config['apptype']=='dingyue'){
            $postdata = file_get_contents("php://input");
            $data = dfsockopen($url, 0, $postdata, '', FALSE, '', 50, TRUE, 'POST');
            echo $data;
            exit;
        }
    }
}