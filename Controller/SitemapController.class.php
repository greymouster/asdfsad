<?php
// +----------------------------------------------------------------------
// | http://tiandaoedu.com/cgal/sitemap/sitemap_cgal.xml
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://tiandaoedu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: run.yuan<run.yuan@tiandaoedu.com>
// +----------------------------------------------------------------------

namespace  Cgal\Controller;

use Think\Controller;
class SitemapController extends Controller{
	
	private static $pre_cache;
	public function __construct() {
		parent::__construct();
		self::$pre_cache = C('CACHE_PREFIX.CGAL');
	}
	public function index(){
		// 动态配置输出类型
		$content = '<?xml version="1.0" encoding="UTF-8"?>
					<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
						<sitemap>
							<loc>http://tiandaoedu.com/cgal/sitemap/lx.xml</loc>
						</sitemap>
						<sitemap>
							<loc>http://tiandaoedu.com/cgal/sitemap/px.xml</loc>
						</sitemap>
				        <sitemap>
							<loc>http://tiandaoedu.com/cgal/sitemap/hwjy.xml</loc>
						</sitemap>
					</sitemapindex>';
		
		$this->show($content, 'utf-8', 'text/xml');
	}
	
	public function category(){
		$tab = I('tab','');
		if($tab == 'lx'){
			$updateSortDate = $this->getUpdateSortDate("success","202",1,2,36,1000);
		}
		if($tab == 'px'){
			$updateSortDate = $this->getPxData('','',1,2,1000);
		}
		if($tab == 'hwjy'){
			$updateSortDate = $this->getUpdateSortDate('hwjxal',1085,1,2,42,1000);
		}
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
					<url>
						<loc>' . C('CGAL_ROOT') . $tab .'/</loc>
						<changefreq>weekly</changefreq>
						<priority>0.6</priority>
				    </url>';
		if($updateSortDate && $updateSortDate['data']){
			foreach ( $updateSortDate['data'] as $row ) {
			
				$date = date('Ymd', $row['publish_time']);
				$xml .= '<url>
							<loc>' . C('CGAL_ROOT') . $tab. '/' . $row['article_id'] . '.html</loc>
							<lastmod>'.$date.'</lastmod>
							<changefreq>weekly</changefreq>
							<priority>0.1</priority>
						 </url>';
			
			}
			$xml .= "</urlset>\n";
		}
		
		
		$this->show($xml, "utf-8", "text/xml");
	    
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
	public function getUpdateSortDate($type_name,$type_id,$current_page,$ArchiveOrderbys='2',$SystemId,$pageSize){
	    // 获取文章缓存
		$key = format_key(C('CACHE.CGAL_CATEGORY'),"{$SystemId}_{$pageSize}_{$type_name}_{$type_id}_$ArchiveOrderbys",self::$pre_cache);
		$obj = redis_cache($key);
		if($obj){
			$info = unserialize($obj);
		}else{
			
			$flag ='';
			if($type_id==202){
				$type_id ="202,204,205,206,305";
				$flag = 1;
			}
			//获取文章数据
			$info = get_cgal_article_info($SystemId ,$pageSize ,4,$ArchiveOrderbys,$current_page,'',$type_id,$flag);
			if(!empty($info['data'])){
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
	public function getPxData($systemId = '',$categoryid ='',$current_page='1',$orderby='2',$pageSize){
	
		$key = format_key(C('CACHE.CGAL_CATEGORY'),"{$systemId}_{$orderby}_{$categoryid}_{$pageSize}_{$current_page}",self::$pre_cache);
		$obj = redis_cache($key);
		if( $obj ){
			$data = unserialize($obj);
		}else{
			if(empty($systemId)){
				$systemId  = "3,4,27,28,29";
				$category_id ="283,284,307,308,309";
				$flag = '1';
				$data = get_px_article_info($systemId, $category_id, $pageSize, $current_page, $flag, $orderby, $flag_id);
			}
			//缓存到redis中
			if(!empty($data['data'])){
				redis_cache($key,serialize($data),C('CACHE_TIMER'));
			}
				
		}
		return $data;
	}
	
}
