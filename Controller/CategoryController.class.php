<?php
// +----------------------------------------------------------------------
// | http://tiandaoedu.com/cgal/lx/
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://tiandaoedu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: run.yuan<run.yuan@tiandaoedu.com>
// +----------------------------------------------------------------------

namespace Cgal\Controller;

use Think\Controller;
use \Org\Page\RewritePage;
 
class CategoryController extends Controller	{

	private static $pre_cache; // 缓存前缀
	private static $type_arr;  
	private static $country_arr;
	private static $education_arr;
	private static $year_arr;
	private static $tag_arr;
	private static $systemId;  // 留学栏目id
	private static $hwsystemId; // 海外就业栏目 id
	private static $type_hw_arr; 
	
	public function __construct() {
		parent::__construct();
		self::$type_hw_arr = array('hwjxal' => 1085);
		self::$systemId = C('CGAL_LX_SYSTEMID');
		self::$hwsystemId = C('CGAL_HW_SYSTEMID');
		self::$type_arr = C('CGAL_TYPE_ARR');
		self::$country_arr = C('CGAL_COUNTRY_ARR');
		self::$education_arr = C('CGAL_EDUCATION_ARR');
		self::$year_arr = C('CGAL_YEAR_ARR');
		self::$tag_arr = C('CGAL_TAG_ARR');
		self::$pre_cache = C('CACHE_PREFIX.CGAL');
	}
	
	public function index()	{
	
		$tab = I('tab',''); // 一级分类
		$kind = I('kind','');// 二级分类
		$current_page = I('current_page',1); // 当前页码
		$year = I('year','');
		$country = I('country','');
		$education = I('education','');
		$tag = I('tag','');
        // 验证一级分类有效性
        $tab_arr = C('TAB_ARR');
        if(empty($tab) || !array_key_exists($tab, $tab_arr)) {
        	jump_404();
        }
        if($current_page <=0 )  {
        	$current_page = 1;
        }
        // 留学
		if($tab == 'lx') {
			$type_name = 'success';
			$typeid = self::$type_arr[$type_name];              
			// 获取成功案例(留学)tdk及数据
			$cgalData = $this ->getArticleData($year,$country,$education,$tag,$current_page,$type_name,$typeid,self::$systemId);
		}
		// 培训
		if($tab=='px') {
			$systemId = get_id($kind,'systemid');
			$categoryid = get_id($kind,'categoryid');
			$cgalData = $this->getPxData($systemId,$categoryid,$current_page);
		}
		// 海外就业
		if($tab == 'hwjy') {
			$type_name = "hwjxal";
			$typeid = self::$type_hw_arr[$type_name];
			$cgalData = $this ->getArticleData($year,$country,$education,$tag,$current_page,$type_name,$typeid,self::$hwsystemId);
			$year = '';
			$country = '';
			$education = '';
		}
		// 成功案例页留学搜索年份nav
		$year_nav = $this->get_href(self::$year_arr,$year,"0",$tab);
		
		// 成功案例页留学搜索国家nav	
		$country_nav = $this->get_href(self::$country_arr,$country,"1",$tab);
		
		// 成功案例页留学搜索学历nav
		$education_nav = $this->get_href(self::$education_arr,$education,"2",$tab);
		
		// 成功案例页留学搜索标签nav
		$tag_nav=$this->get_href(array_slice(self::$tag_arr,0,-2),$tag,"3",$tab);
		
		$total = $cgalData["count"];
		// 获取列表前10条信息
		$pageSize = 10;
		// 分页数据
		$listurl = get_ListUrl();
		$page = new RewritePage($total,$pageSize,$current_page,$listurl."list_{page}.html");//用于静态或者伪静态

		// 生成HTML
		$updateSortHtml = update_sort_html($cgalData,$tab);
		// 显示
		$page = $total>10 ? $page->myde_write() : '';
	
        // 获取轮播图
		$success = get_api_data('success');
		
		// 去掉头部导航条下广告位调用，如果将来要加上，放开这段代码，并放开相应模板注释的广告位代码就好
		// $flash_data=$this->getAddata("4718",self::$pre_cache); // 全站顶部广告位
		
		// $flash_url=$this->getAddata("4769",self::$pre_cache); // 全站顶部广告位flash
		
		$lbcp_data =  getAddata("4501",self::$pre_cache); // 右侧轮播(产品)
		
		$lbba_data =  getAddata("4502",self::$pre_cache); // 右侧轮播(banner)
	 
		$lbzt_data =  getAddata("4500",self::$pre_cache); // 右侧轮播(专题)
		$wzgd_data =  getAddata("4699",self::$pre_cache); // 文字滚动
		
		// 获取(培训|海外就业)选中的当前选项
		$cgal_url = explode('/',$_SERVER['REQUEST_URI']);
		$cgal_tag = $cgal_url[3];
		$cgalflag = $cgal_url[2];
		if(strpos($cgal_tag,'list_') !== false){
			$cgal_tag = '';
		}
		$this->assign('cgalflag',$cgalflag);
		$this->assign('cgal_tag',$cgal_tag);
		
		// 去掉头部导航条下广告位调用，如果将来要加上，放开这段代码，并放开相应模板注释的广告位代码就好
		// $this->assign('flash_url',$flash_url);
		// $this->assign('flash_data',$flash_data);
		$this->assign('lbcp_data',$lbcp_data);
		$this->assign('lbba_data',$lbba_data);
		$this->assign('lbzt_data',$lbzt_data);
		$this->assign('wzgd_data',$wzgd_data);
		$this->assign('success',$success);
		$this->assign('page',$page);
		$this->assign('year_nav',$year_nav);
		$this->assign('country_nav',$country_nav);
		$this->assign('education_nav',$education_nav);
		$this->assign('tag_nav',$tag_nav);
		$this->assign('cate_info',$cgalData['tdk']);
		$this->assign('content',$updateSortHtml);
		$this->display();
		
	}
	
    
	
	/**
	 * ajax 获取点击量及频道推荐
	 * @param 当前页号 $current_page
	 * @param 排序 $sortOrder
	 * @param 年份 $year
	 * @param 学历 $education
	 * @param 国家 $country
	 * @param 标签 $tag
	 * @return string
	 */
	public function jsonDate($current_page='',$sortOrder ='',$year ='',$education ='',$country ='',$tag ='',$kind,$tab) {
		
		if(empty($sortOrder)) {
			return json_encode(false);
		}
		
		$current_page = empty($current_page)? 1 : intval($current_page);
		
		if($sortOrder =='click') {
			$ArchiveOrderbys = 'view_num';
		}elseif($sortOrder =='recommend'){
			$ArchiveOrderbys = 'recommend';
		}
	    
		// 获取留学案例
		if($tab == 'lx') {
			$type_name = 'success';
			$typeid = self::$type_arr[$type_name];
			
			if ($year == "all" && $country == "all" && $education == "all" && $tag == "all") {
			
				// 获取按更新时间排序的数据
				$data = $this->getUpdateSortDate($type_name,$typeid,$current_page,$ArchiveOrderbys,self::$systemId,10);
					
			} else{
			
				$data = $this->cgalInfo($year,$country,$education,$tag,$current_page,$ArchiveOrderbys,self::$systemId);
			}
		}
		// 获取培训案例
		if($tab == 'px') {
			$systemId = get_id($kind,'systemid');
			$categoryid = get_id($kind,'categoryid');
			$data = $this->getPxData($systemId,$categoryid,$current_page,$ArchiveOrderbys);
		}
		// 海外就业
		if($tab == 'hwjy') {
			$type_name = "hwjxal";
			$typeid = self::$type_hw_arr[$type_name];
			if ($year == "all" && $country == "all" && $education == "all" && $tag == "all") {
					
				// 获取按更新时间排序的数据
				$data = $this->getUpdateSortDate($type_name,$typeid,$current_page,$ArchiveOrderbys,self::$hwsystemId);
			}else{
				$data = $this->getHwjyInfo($tag,$current_page,$ArchiveOrderbys);
			}
			
			$year = '';
			$country = '';
			$education = '';
		}
		$content = update_sort_html($data,$tab);
		$total = $data["count"];
		
		$pageSize =10;
		$page = new RewritePage($total,$pageSize,$current_page,"",1,$sortOrder);// 用于静态或者伪静态
		$page = $total>10 ? $page->myde_write() : '';// 显示
		echo $content .= $page;
		exit;
	}
	
	/**
	 * 获取成功案例(留学)
	 * @param 年份 $year
	 * @param 国家 $country
	 * @param 学历 $education
	 * @param 标签 $tag
	 * @param 当前页码 $current_page
	 */
	public function getArticleData($year,$country,$education,$tag,$current_page,$type_name,$typeid,$systemId) {
		
		if (! empty($year) || ! empty($country) || ! empty($education)) {
			$yearn = $year=="all" ? "" : $year;
	
			// 全部是all获取数据
			if ($year == "all" && $country == "all" && $education == "all" && $tag == "all") {
				 
				// 获取按更新时间排序的数据
				$arr = $this->getUpdateSortDate($type_name,$typeid,$current_page,$sort=2,$systemId);
				$arr['tdk'] = get_tdk($systemId,$typeid);
				 
			}else{
				
				$arr=$this->cgalInfo($year,$country,$education,$tag,$current_page,$sort=2,$systemId);
				// 如果是留学案例则需要添加标题后缀
				$title = $systemId == C('CGAL_LX_SYSTEMID') ?  "成功案例|天道教育_留学成功案例" : "成功案例|天道教育_海外就业成功案例";
				// 获取tdk
				$cate_info['title'] = "天道" . $yearn . "" . $this->gettdk(self::$country_arr, $country) . "" .
						$this->gettdk(self::$education_arr, $education) . "" .
						$this->gettdk(self::$tag_arr, $tag) . $title;
				$cate_info['description'] = $cate_info['keywords'] = "天道" .  $yearn . "" . $this->gettdk(self::$country_arr, $country) .
				"" . $this->gettdk(self::$education_arr, $education) . "" .
				$this->gettdk(self::$tag_arr, $tag) . "成功案例";
	
				$arr['tdk']['title']=   str_replace("全部","",$cate_info['title']);
				$arr['tdk']['keywords'] = str_replace("全部","",$cate_info['keywords']);
				$arr['tdk']['description'] = $cate_info['description'];
	
			}
		}else{
			 
			// 获取按更新时间排序的数据
			$arr = $this->getUpdateSortDate($type_name,$typeid,$current_page,$sort=2,$systemId);
				
			// 获取文章的tdk
			$arr['tdk']= get_tdk($systemId,$typeid);
	        
		}
		 
		return $arr;
	}
	
	/**
	 * 获取按更新时间排序的数据
	 * @param $type_name
	 * @param $type_id
	 * @param 当前页码 $current_page
	 * @param  $ArchiveOrderbys
	 * @param  条数$pageSize
	 * @return array
	 */
	public function getUpdateSortDate($type_name,$type_id,$current_page,$ArchiveOrderbys='2',$SystemId,$pageSize) {
	
		$pageSize = empty($pageSize) ? 10 : $pageSize;
		
		if($ArchiveOrderbys == 'view_num') {
			$ArchiveOrderbys = 4;
		}
	    
		if($ArchiveOrderbys == 'recommend') {
			$ArchiveOrderbys = 1;
			$flag_id = 2;
		}
		
		$key = format_key(C('CACHE.CGAL_CATEGORY'),"{$SystemId}_{$type_name}_{$type_id}_{$ArchiveOrderbys}_{$current_page}",self::$pre_cache);
		// 获取文章缓存
		$obj = redis_cache($key);
		if($obj){
			$info = unserialize($obj);
		}else{
			// 获取默认的缓存
			$flag = 1 ;
			if($type_id==202){
				$type_id ="202,204,205,206,305";
			}
			// 获取文章数据
			$info = get_cgal_article_info($SystemId ,$pageSize ,4,$ArchiveOrderbys,$current_page,$flag_id,$type_id,$flag);
			if(!empty($info['data'])) {
				redis_cache($key,serialize($info),C('CACHE_TIMER'));
			}
	
		}
		return $info;
	}
	
	/**
	 * 获取成功案例的数据
	 * @param 年份 $year
	 * @param 国家 $country
	 * @param 学历 $education
	 * @param 标签 $tag
	 * @param 页面 $current_page
	 * @param 排序 $ArchiveOrderbys
	 * @return array
	 */
	public function cgalInfo($year="all", $country="all", $education="all", $tag="all", $current_page="1", $ArchiveOrderbys, $systemId) {
		$request=array();
		$pageSize=10;
		$request['num'] = $pageSize;
		$request['page'] = $current_page;
	
		if($ArchiveOrderbys=='recommend') {
			$request['flag'] = 2;
		}else if($ArchiveOrderbys=='view_num') {
			$request['sort'] = 'view_num';
		}
	
		$year_arr = C('CGAL_YEAR_NUM_ARR');
		if($year_arr[$year]){
			$request['year'] = $year_arr[$year];
		}
			
		$country_arr = C('CGAL_COUNTRY_NUM_ARR');
		if($country_arr[$country]) {
			$request['country_id'] = $country_arr[$country];
		}
			
		$education_arr = C('CGAL_ENUCATION_NUM_ARR');
		if($education_arr[$education]) {
			$request['edu_id'] = $education_arr[$education];
		}
			
		$tag_arr = C('CGAL_TAG_NUM_ARR');
		if($tag_arr[$tag]) {
			$request['label_id'] = $tag_arr[$tag];
		}
		$key = format_key(C('CACHE.CGAL_CATEGORY'),"{$tag}_{$country}_{$year}_{$education}_{$ArchiveOrderbys}_{$current_page}",self::$pre_cache);
		// 读取缓存
		$obj = redis_cache($key);
		if($obj) {
			$info = unserialize($obj);
		}else{
			// 留学案例  
			if($systemId == 36) {
				$info=$this->getApiArticleInfo($request);
			}else{ 
				// 海外就业案例
				$info = $this->getHwjyArticleInfo($request);
			}
			
			// 存储redis中
			if(!empty($info['data'])) {
				redis_cache($key,serialize($info),C('CACHE_TIMER'));
			}
		}
	
		return $info;
	}
	
	/**
	 * 获取成功案例的培训案例
	 * @param 系统id $systemId
	 * @param 栏目id $categoryid
	 * @param 当前页码 $current_page
	 * @return array
	 */
	public function getPxData($systemId = '',$categoryid ='',$current_page='1',$orderby='2',$pageSize) {
		
		$pageSize = empty($pageSize)? 10 :$pageSize;
		if($orderby == 'view_num'){
			$orderby = 4;
		}elseif($orderby == 'recommend') {
			$orderby = 1;
			$flag_id = 2;
		}
		$key = format_key(C('CACHE.CGAL_CATEGORY'),"{$systemId}_{$orderby}_{$categoryid}_{$current_page}",self::$pre_cache);
		$obj = redis_cache($key);
		if( $obj ) {
			$data = unserialize($obj);
		}else{
			if( empty($systemId) ) {
				$systemId  = "3,4,27,28,29";
				$category_id ="283,284,307,308,309";
				$flag = '1';
				$data = get_px_article_info($systemId, $category_id, $pageSize, $current_page, $flag, $orderby, $flag_id);
			}else{
				$data = get_cgal_article_info($systemId ,$pageSize ,4,$orderby,$current_page,$flag_id,$categoryid);
					
			}
			// 缓存到redis中	
			if( !empty($data['data']) ) {
				redis_cache($key,serialize($data),C('CACHE_TIMER'));
			}
			
		}
		$peixun_tdk = C('CGAL_PEIXUN_TDK');
		$systemId = empty($systemId) ? 0 :$systemId;
		$data['tdk']['title'] = $peixun_tdk[$systemId] ['title'];
		$data['tdk']['keywords'] = $peixun_tdk[$systemId] ['keyword'];
		$data['tdk']['description'] = $peixun_tdk[$systemId] ['desc'];
		return $data;
	}
	
	/**
	 * 获取TDK信息
	 * @param  分类对应的信息$arr
	 * @param  接收到的参数信息 $data
	 * @return string
	 */
	private function gettdk ($arr, $data) {
		foreach ($arr as $key => $val) {
			if ($key == $data) {
				$data = $val;
			}
		}
		if ($data != "all" && $data != "全部") {
			return $data;
		}
	}
	
	

	//获取成功案例文章信息
	public function getApiArticleInfo($request=array()) {
	
		$api_url = C('API_ROOT')."/Api/Article/get_success_list";
		$str = http_curl($api_url, $request);
		$data = json_decode($str, true);
		if($data['result']=='ok'){
			return $data;
		}else{
			return array();
		}
	
	}
	
	/**
	 * 拼接新的url链接
	 * @param 搜索条件对应的数组 $arr
	 * @param 搜索条件字符串$str
	 * @param 标记类型 $type
	 * @return string
	 */
	protected function get_href ($arr, $str, $type,$tab) {
		
		foreach ($arr as $key => $val) {
			if ($str == $key && $tab !='hwjy') {
				$href .= "<li><a target=\"_self\" class=\"selected\" href=\"" .
						$this->get_url($key, $type) . "\">" . $val . "</a></li>\r\n";
			} else {
				$href .= "<li><a target=\"_self\" href=\"" . $this->get_url($key, $type) . "\">" .
						$val . "</a></li>\r\n";
			}
		}
		return $href;
	}
	
	/**
	 * 拼凑url
	 * @param unknown $val
	 * @param unknown $type
	 * @return string
	 */
	protected function get_url($val, $type) {
		
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$arr = explode("/", $url);
		if (empty($arr[5]) || strpos($arr[5], "html") || ! strpos($arr[5], "-")) {
			$arr[5] = "all-all-all-all";
		}
		$arrn = explode("-", $arr[5]);
		$arrn[$type] = $val;
		// 因为seo给的链接的原因 所以要判断海外就业的tag标签要是存在全部替换为all
		$tag_arr = array('wbq','h1b');
		if(in_array($arrn[3], $tag_arr)) {
			$arrn[3] = 'all';
		}
		$url = "http://tiandaoedu.com/cgal/lx/";
		foreach ($arrn as $v) {
			$url .= $v . "-";
		}
		$url = substr($url, 0, strlen($url) - 1) . "/";
		return $url;
	}

	/**
	 * 获取海外就业的数据
	 * @param 标签 $tag
	 * @param 页面 $current_page
	 * @param 排序 $ArchiveOrderbys
	 * @return array
	 */
	public function getHwjyInfo($tag="all",$current_page="1",$ArchiveOrderbys) {
		$request=array();
		$pageSize=10;
		// $tag_hw_arr = array("all" => "全部","wbq"=>"五百强","h1b"=>"H-1B");
		$request['num'] = $pageSize;
		$request['page'] = $current_page;
	
		if($ArchiveOrderbys == 'recommend') {
			$request['flag'] = 2;
		}else if($ArchiveOrderbys == 'view_num') {
			$request['sort'] = 'view_num';
		}
	
		$tag_arr = array("all" => "0","wbq"=>"214","h1b"=>"215");
		if($tag_arr[$tag]) {
			$request['label_id'] = $tag_arr[$tag];
		}
		
		// 读取redis缓存
		$key = format_key(C('CACHE.CGAL_CATEGORY'),"{$tag}_{$current_page}_{$ArchiveOrderbys}_{$request['label_id']}",self::$pre_cache);
		$obj = redis_cache($key);
		if($obj) {
			$info = unserialize($obj);
		}else{
			$info = $this->getHwjyArticleInfo($request);
			if(!empty($info['data'])){
				redis_cache($key,serialize($info),C('CACHE_TIMER'));
			}
		}
		return $info;
	}
	
	// 获取海外就业文章信息
	public function getHwjyArticleInfo($request=array()) {
	
		$api_url = C('API_ROOT')."/Api/Article/get_hwjysuccess_list";
		$str = http_curl($api_url, $request);
		$data = json_decode($str, true);
		if($data['result']=='ok'){
			return $data;
		}
	
	}
	
	/**
	 * 删除redis列表缓存
	 */
	public function delCache() {
		// 权限校验
		$auth = I('auth', ''); //
		if(empty($auth) || $auth != C('CLEAR_KEY')) {
		      echo 'error';exit;
		}
		$flag = I('flag', '');
		if($flag!=''){
		   redis_delkeys($flag);
		}

	}
	
}