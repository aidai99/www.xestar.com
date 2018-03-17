<?php
/**
 *     文章图片优化SEO
 *
 *   硬汉工作室 版权所有
 *   网址：www.nciaer.com
 *   QQ: 1069971363
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_nciaer_articlepicseo {

    public $config = array();

    public function __construct() {

        global $_G;

        $this->config = $_G['cache']['plugin']['nciaer_articlepicseo'];
    }

    public function view_nciaer_output() {

        global $_G, $article, $content, $cat;

        if(!$this->config['on']) return '';
        if($this->config['only_robot'] && !checkrobot()) return '';

        $title = $article['title']; // 文章名
        $summary = $article['summary']; // 文章简介
        $dateline = $article['dateline']; // 文章发布时间
        $author = $article['username']; // 文章作者
        $catname = $cat['catname']; // 文章分类
        $bbname = $_G['setting']['bbname']; // 站点名称

        $preg = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
        $title_seo = str_replace(
            array('{title}', '{summary}', '{dateline}', '{author}', '{catname}', '{bbname}'),
            array($title, $summary, $dateline, $author, $catname, $bbname),
            $this->config['title_seo']
        );
        $alt_seo = str_replace(
            array('{title}', '{summary}', '{dateline}', '{author}', '{catname}', '{bbname}'),
            array($title, $summary, $dateline, $author, $catname, $bbname),
            $this->config['alt_seo']
        );
        $replace = '<img src="$1" alt="' . $alt_seo . '" title="' . $title_seo . '" />';

        $text = preg_replace($preg, $replace, $content['content']);
        $content['content'] = $text;
    }
}

class plugin_nciaer_articlepicseo_portal extends plugin_nciaer_articlepicseo {
}

class mobileplugin_nciaer_articlepicseo_portal extends plugin_nciaer_articlepicseo {
}