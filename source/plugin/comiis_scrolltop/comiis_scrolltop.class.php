<?php
/**
 * 
 * ���׳�Ʒ ������Ʒ
 * ������ƹ����� ��Ȩ���� http://www.zheyitianshi.com
 * רҵ��̳��ҳ���������, ҳ���������, ���ݰ��/����, ������ο���, ��վЧ��ͼ���, ҳ���׼DIV+CSS����, �������С����ҵ��վ���...
 * ����������Ϊ��ҵ�ṩ������վ���衢��վ�ƹ㡢��վ�Ż������򿪷�������ע�ᡢ���������ȷ���
 * һ����ƺͽ������Ϊ��ҵ��������ʺ��Լ��������վ��Ӫƽ̨������޶ȵ�ʹ��ҵ����Ϣʱ�����������̻���
 *
 *   �绰: 0668-8810200
 *   �ֻ�: 13450110120  15813025137
 *    Q Q: 21400445  8821775  11012081  327460889
 * E-mail: ceo@www.zheyitianshi.com
 *
 * ����ʱ��: ��һ����������09:00-11:00, ����03:00-05:00, ����08:30-10:30(����������Ϣ)
 * ��������û�����Ⱥ: ��Ⱥ83667771 ��Ⱥ83667772 ��Ⱥ83667773 ��Ⱥ110900020 ��Ⱥ110900021 ��Ⱥ70068388 ��Ⱥ110899987
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