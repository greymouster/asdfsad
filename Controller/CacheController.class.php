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

class CacheController extends Controller {
	//缓存前缀
	private $cache_pre = '';
	private $lxsystemId; //留学栏目id
	private $hwsystemId; //海外栏目id
	
	public function __construct() {
		parent::__construct();
		$this->cache_pre = C('CACHE_PREFIX.CGAL');
		$this->lxsystemId = C('CGAL_LX_SYSTEMID');
		$this->hwsystemId = C('CGAL_HW_SYSTEMID');
		
		// 权限校验
		$auth = I('auth', ''); // 一级分类
		if(empty($auth) || $auth != C('CLEAR_KEY')) {
			echo 'error';
			exit;
		}
	}
	
	/**
	 * 生成成功案例频道首页缓存,清理页面静态化文件
	 * @author liukw
	 */
	public function clear_home() {
		header('Content-Type: text/html; charset=UTF-8');
		$type = I('type', 0);
		
		if ($type == 0 || $type == 1) {
			//生成成功案例首页(转/混申专业)缓存
			$zhuan_param = array('', '', '', '', 10, '', 60); //获取成功案例记录参数
			$result_zhuan = $this->setCaglCache(C('CACHE.CGAL_ZHUAN'),$zhuan_param);
			echo '转/混专业 ------'.$result_zhuan.'<br/>';
			
			//生成成功案例首页(在职申请)缓存
			$zaizhi_param = array('','','', '', 10,'',61);//获取成功案例记录参数
			$result_zaizhi = $this->setCaglCache(C('CACHE.CGAL_ZAIZHI'),$zaizhi_param);
			echo '在职申请 -------'.$result_zaizhi.'<br/>';
			
			//生成成功案例首页(低分反转)缓存
			$difen_param = array('','','', '', 10,'',62);//获取成功案例记录参数
			$result_difen = $this->setCaglCache(C('CACHE.CGAL_DIFEN'), $difen_param);
			echo '低分反转 -------'.$result_difen.'<br/>';
			
			//生成成功案例首页(小本逆袭)缓存
			$xiaoben_param = array('','','', '', 10,'',63);
			$result_xiaoben = $this->setCaglCache(C('CACHE.CGAL_XIAOBEN'), $xiaoben_param);
			echo '小本逆袭 -------'.$result_xiaoben.'<br/>';
			
			//生成成功案例首页(常春藤)缓存
			$chang_param = array('', '', '', '', 10, '',64);
			$result_chang = $this->setCaglCache(C('CACHE.CGAL_CHANG'), $chang_param);
			echo '常春藤 ---------'.$result_chang.'<br/>';
		}
		
		if ($type == 0 || $type == 2) {
			//生成成功案例首页(DIY失利)缓存
			$diy_param = array('', '', '', '', 10, '', 65);
			$result_diy = $this->setCaglCache(C('CACHE.CGAL_DIY'), $diy_param);
			echo 'DIY失利 --------'.$result_diy.'<br/>';
			
			//生成成功案例首页(海外申请)缓存
			$haiwai_param = array('', '', '', '', 10, '', 66);
			$result_haiwai = $this->setCaglCache(C('CACHE.CGAL_HAIWAI'), $haiwai_param);
			echo '海外申请  -------'.$result_haiwai.'<br/>';
			
			//生成成功案例首页（文理学院）缓存
			$wenli_param = array('', '', '', '', 10, '', 67);
			$result_wenli = $this->setCaglCache(C('CACHE.CGAL_WENLI'), $wenli_param);
			echo '文理学院 -------'.$result_wenli.'<br/>';
			
			//生成成功案例首页(情侣党)缓存
			$love_param = array('', '', '', '', 10, '', 68);
			$result_love = $this->setCaglCache(C('CACHE.CGAL_LOVE'), $love_param);
			echo '情侣党 ---------'.$result_love.'<br/>';
			
			//生成成功案例首页(艺术)缓存
			$yishu_param = array('', '', '', '', 10, '', 69);
			$result_yishu = $this->setCaglCache(C('CACHE.CGAL_YISHU'), $yishu_param);
			echo '艺术 -----------'.$result_yishu.'<br/>';
		}
		
		// 底部三列 ---------------------------------------------------------		
		if ($type == 0 || $type == 3) {
			//生成成功案例首页(最新)缓存
			$new_param =array('','','', '',6,'',''); //获取成功案例记录的参数
			$result_new = $this->setCaglCache(C('CACHE.CGAL_NEW'),$new_param);
			echo '最新信息 -------'.$result_new.'<br/>';
			
			//生成成功案例首页(推荐)缓存
			$tui_param = array(2,'','', '',6,'','');//获取成功案例记录参数
			$result_tui = $this->setCaglCache(C('CACHE.CGAL_TUI'),$tui_param);
			echo '推荐信息 -------'.$result_tui.'<br/>';
			
			//生成成功案例首页(最热)缓存
			$hot_param = array('','view_num','', '',6,'','');//获取成功案例记录参数
			$result_hot = $this->setCaglCache(C('CACHE.CGAL_HOT'),$hot_param);
			echo '最热信息 -------'.$result_hot.'<br/>';
		}
		
		//生成广告位缓存
		$result_ad = $this->MakeAdCache();
		echo '广告位缓存 -------'.$result_ad.'<br/>';
		// 删除静态化页面
		$this->delHtml();
	} 
	
	/**
	 * 删除静态化页面
	 * @author liukw
	 */
	private function delHtml() {
		$file = "./Application/Html/cgal/home/index.html";
		if(file_exists($file)){
			unlink($file);
		}
	}
	
	/**
	 * 成功案例缓存生成
	 * @param  $cache_index  各个缓存的redis　key
	 * @param  $param  获取成功案例记录的参数 array
	 * @return boolean
	 */
	private function setCaglCache($cache_index,$param){
		$key = format_key($cache_index, '', $this->cache_pre);
		
		$data = get_success_list($param);

		if($data){
			redis_cache($key,serialize($data));
			return true;
		}
		return false;
	}
	
	
	/**
	 * 生成广告位缓存
	 */
	public function MakeAdCache() {		
		
		$ad_arr = C('CGAL_AD_ARR');
		foreach ($ad_arr as $ad_name=>$ad_id) {
			
			$key = format_key(C('CACHE.CGAL_AD'),$ad_id,$this->cache_pre);
			$info= $this->makeAddata($ad_id);
			//linux上需要转码
			if(is_linux()){
				$info = array_to_utf8($info);
			}
			$flag = redis_cache($key,serialize($info));
			return true;
		}
		return false;
	}
	
	
	
	/**
	 * 获取广告位数据
	 * @param 广告id $adid
	 * @return boolean|array
	 */
	private function makeAddata( $adid = '') {
		if (empty ( $adid ) ) {
			return '';
		}
		$query = "exec P_SearchAddata " . $adid;
		return M()->db ( 1, 'MS_SQL' )->query ( $query );
	}

	
}
	
	