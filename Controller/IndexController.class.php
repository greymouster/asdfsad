<?php
// +----------------------------------------------------------------------
// | http://tiandaoedu.com/cgal/
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://tiandaoedu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: run.yuan<run.yuan@tiandaoedu.com>
// +----------------------------------------------------------------------

namespace Cgal\Controller;
use Think\Controller;

class IndexController extends Controller {
	//缓存前缀
	private $cache_pre = '';
	
	public function __construct() {
		parent::__construct();
		$this->cache_pre = C('CACHE_PREFIX.CGAL');
	}
	
	public function index() {		
		// 获取成功案例首页(推荐)
		$tui_data = $this->getCacheList(C('CACHE.CGAL_TUI'));
		$tui_list = $this->get_school($tui_data);
		$this->assign('tui_list', $tui_list);
		
		// 获取成功案例首页(最热)
		$hot_data = $this->getCacheList(C('CACHE.CGAL_HOT'));
		$hot_list = $this->get_school($hot_data);
		$this->assign('hot_list', $hot_list);
        
		// 获取成功案例首页(最新)
		$_data = $this->getCacheList(C('CACHE.CGAL_NEW'));
		$new_list = $this->get_school($_data);
		$this->assign('new_list', $new_list);
		
		// 获取成功案例首页(转/混申专业)
		$zhuan_data = $this->getCacheList(C('CACHE.CGAL_ZHUAN'));
		$zhuan_list = $this->get_school($zhuan_data);
		$this->assign('zhuan_list', $zhuan_list);
		
		// 获取成功案例首页(在职申请)
		$zai_data = $this->getCacheList(C('CACHE.CGAL_ZAIZHI'));
		$zai_list = $this->get_school($zai_data);
		$this->assign('zai_list', $zai_list);
		
		// 获取成功案例首页(低分反转)
		$di_data = $this->getCacheList(C('CACHE.CGAL_DIFEN'));
		$di_list = $this->get_school($di_data);
		$this->assign('di_list', $di_list);
		
		// 获取成功案例首页(小本逆袭)
		$xiao_data = $this->getCacheList(C('CACHE.CGAL_XIAOBEN'));
		$xiao_list = $this->get_school($xiao_data);
		$this->assign('xiao_list', $xiao_list);
		
		// 获取成功案例首页(常春藤)
		$chang_data = $this->getCacheList(C('CACHE.CGAL_CHANG'));
		$chang_list = $this->get_school($chang_data);
		$this->assign('chang_list', $chang_list);
		
		// 获取成功案例首页(DIY失利)
		$diy_data = $this->getCacheList(C('CACHE.CGAL_DIY'));
		$diy_list = $this->get_school($diy_data);
		$this->assign('diy_list', $diy_list);
		
		// 获取成功案例首页（海外申请）
		$hai_data = $this->getCacheList(C('CACHE.CGAL_HAIWAI'));
		$hai_list = $this->get_school($hai_data);
		$this->assign('hai_list', $hai_list);		
		
		// 获取成功案例首页（文理学院）
		$wen_data = $this->getCacheList(C('CACHE.CGAL_WENLI'));
		$wen_list = $this->get_school($wen_data);
		$this->assign('wen_list', $wen_list);
		
		// 获取成功案例首页(情侣党)
		$love_data = $this->getCacheList(C('CACHE.CGAL_LOVE'));
		$love_list = $this->get_school($love_data);
		$this->assign('love_list', $love_list);
		
		// 获取成功案例首页(艺术)
		$yi_data = $this->getCacheList(C('CACHE.CGAL_YISHU'));
		$yi_list = $this->get_school($yi_data);
		$this->assign('yi_list', $yi_list);

		$this->display();
	}
	
	/**
	 * 获取各个key在redis中缓存数据
	 * @param $name 缓存的 
	 * @return array|boolean
	 */
	private function getCacheList($name) {
		$key = format_key($name, '', $this->cache_pre);
		$obj = redis_cache($key);
		if ($obj) {
			$list = unserialize($obj);
			return $list;
		}
		
		return false;
	}
	
	/**
	 * 获取校徽图章 及 院校库链接
	 * @param array $data
	 * @return array
	 * @author liukw
	 */
	private function get_school($data) {
		if ($data) {			
			$school_ids = array();
			foreach ($data as $k => $v) {
				if (trim($v['SchoolId']) && $v['SchoolId'] != '暂无') {
					$school_ids[trim($v['SchoolId'])] = trim($v['SchoolId']);
				}
				//$data[$k] ['SchoolMicroURL'] = (strpos($v ['SchoolMicroURL'], "http://") !== false ? "" : "http://img2.tiandaoedu.com/www") . $v ['SchoolMicroURL'];
                //$data[$k] ['SchoolMicroURL'] = str_replace("/UploadFile/schoolMicro//UploadFile/schoolMicro/", "/UploadFile/schoolMicro/", $data[$k] ['SchoolMicroURL']);
			}
			if ($school_ids) {
				// 获取校徽图章 及 院校库链接
				$api_url = C('API_SOLR').'yuanxiaoku/select?q=id%3A'. implode('+OR+', $school_ids). '&wt=json&indent=true';
				$str = file_get_contents($api_url);				
				$yx_list = json_decode($str, true);				
				if ($yx_list) {
					$_data = array();
					foreach ($yx_list['response']['docs'] as $v) {
						$_data[$v['id']]['Schoolurl'] = C('YX_ROOT') . $v['SchoolUrl'] . "/";	
						$_data[$v['id']]['SchoolMicroURL'] = C('IMG2_URL') . $v['SchoolTokenSrc'];
					}
			
					foreach ($data as $k => $v) {
						if (isset($_data[$v['SchoolId']])) {
							$data[$k]['SchoolMicroURL'] = $_data[$v['SchoolId']]['SchoolMicroURL'];
							$data[$k]['Schoolurl'] = $_data[$v['SchoolId']]['Schoolurl'];
						}
					}
				}
			}
		}
		return $data;
	}	
	
}	