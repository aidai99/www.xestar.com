<?php
/**
 * 
 * 克米出品 必属精品
 * 克米设计工作室 版权所有 http://www.zheyitianshi.com
 * 专业论坛首页及风格制作, 页面设计美化, 数据搬家/升级, 程序二次开发, 网站效果图设计, 页面标准DIV+CSS生成, 各类大中小型企业网站设计...
 * 我们致力于为企业提供优质网站建设、网站推广、网站优化、程序开发、域名注册、虚拟主机等服务，
 * 一流设计和解决方案为企业量身打造适合自己需求的网站运营平台，最大限度地使企业在信息时代稳握无限商机。
 *
 *   电话: 0668-8810200
 *   手机: 13450110120  15813025137
 *    Q Q: 21400445  8821775  11012081  327460889
 * E-mail: ceo@www.zheyitianshi.com
 *
 * 工作时间: 周一到周五早上09:00-11:00, 下午03:00-05:00, 晚上08:30-10:30(周六、日休息)
 * 克米设计用户交流群: ①群83667771 ②群83667772 ③群83667773 ④群110900020 ⑤群110900021 ⑥群70068388 ⑦群110899987
 * 
 */
if(!defined('IN_DISCUZ')) {exit('Access Denied');}
$finish = TRUE;
class plugin_comiis_scrolltop{
	function global_footer(){
		global $_G,$page;
		$comiis_scrolltop_n = $comiis_scrolltop_t = $comiis_scrolltop_index = $comiis_scrolltop_list = $comiis_scrolltop_view = 0;
		$comiis_scrolltop = $_G['cache']['plugin']['comiis_scrolltop'];
		$comiis_scrolltop_ver = "jbmk";
		if($_G['basescript'] == 'forum'){
			$comiis_scrolltop_index = CURMODULE == 'index' ? 1 : 0;
			$comiis_scrolltop_list = CURMODULE == 'forumdisplay' ? 1 : 0;
			$comiis_scrolltop_view = CURMODULE == 'viewthread' ? 1 : 0;
		}
		$comiis_scrolltop_data = array(
			'comiis_t_kf' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_kf_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_kf_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_kf_name'], 8, ''),
			),
			'comiis_t_mob' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_mob_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_mob_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_mob_name'], 8, ''),
			),
			'comiis_t_t' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_t_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_t_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_t_name'], 8, ''),
			),
			'comiis_t_ft' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_ft_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_ft_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_ft_name'], 8, ''),
			),
			'comiis_t_l' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_l_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_l_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_l_name'], 8, ''),
			),
			'comiis_t_r' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_r_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_r_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_r_name'], 8, ''),
			),
			'comiis_t_hf' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_hf_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_hf_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_hf_name'], 8, ''),
			),
			'comiis_t_ht' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_ht_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_ht_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_ht_name'], 8, ''),
			),
			'comiis_t_hn' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_hn_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_hn_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_hn_name'], 8, ''),
			),
			'comiis_t_lb' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_lb_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_lb_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_lb_name'], 8, ''),
			),
			'comiis_t_rebbs' => array(
				'class' => (dstrlen($comiis_scrolltop['comiis_t_rebbs_name']) <= 6) ? 1 : 0,
				'all_name' => $comiis_scrolltop['comiis_t_rebbs_name'],
				'name' => cutstr($comiis_scrolltop['comiis_t_rebbs_name'], 8, ''),
			),
		);
		$comiis_scrolltop_t = $page - 1;
		$comiis_scrolltop_n = $page + 1;
		include_once template('comiis_scrolltop:comiis_html');
		return $return;
	}
}
?>