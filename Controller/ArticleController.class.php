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

class ArticleController extends Controller {
	
	protected static  $systemId;
	protected static  $hwsystemId;
	protected static $pre_cache;
	
	public function __construct() {
		parent::__construct();
		self::$systemId = C('CGAL_LX_SYSTEMID');
		self::$hwsystemId = C('CGAL_HW_SYSTEMID');
		self::$pre_cache = C('CACHE_PREFIX.CGAL');
	}
	
	public function index() {
		
		$id = I('id',0);   // 文章id
		$tab = I('tab',''); // 一级分类
		$page = I('page',1);// 分页  
		// 文章数据有效性判断  begin-------------------
		// 非法文章跳入404页面
		if (empty($id)) {
			jump_404();
		}
		//验证一级分类有效性
		$tab_arr = C('TAB_ARR');
		if(empty($tab) || !array_key_exists($tab, $tab_arr)) {
			jump_404();
		}
		$act = I('act', ''); // 浏览类型 refresh（清理缓存+预览）
		$key = I('key', ''); // 预览校验字段
		$status = 4; // 文章状态 1、未审核2、审核通过3、审核失败4、已发布5、已下线
		// 开启文章预览功能，屏蔽页面静态化
		if ($act == 'refresh' && $key == C('CLEAR_KEY')) {
			//$status = 1;
			C("HTML_CACHE_ON", false);
		}
		
		switch ($tab) {
			case 'lx': // 留学分类
				$systemId = self::$systemId;
				$country = C('ID_COUNTRY_ARR');
				$education = C('CGAL_EDU_ARR');
				$other = $this->get_other($id);
				$back = $this->getBack($other,$country,$education);
				$tab_name = '留学案例';
				$this->assign('back',$back);
				$this->assign('other',$other);
				break;
			case 'px': // 培训分类
				$systemId = 10000;
				$tab_name = '培训案例';
				break;
			case 'hwjy': // 海外就业分类 
				$systemId = self::$hwsystemId;
				$tab_name = '海外就业案例'; 
				break;		
		}
		// 获取文章列表数据
		$article_data = $this->getArticleInfo($id,$status,$systemId);
		$article_row = false;
		if ($article_data && $article_data['code'] === 0) {
			if ($article_data['data']) {
				$article_row = $article_data['data']['result'][0];
			}
		}
		
		// 文章404报错
		if ($article_row == false) {
			jump_404();
		}
		
		$article_status = $article_row['status'];
		if ($article_status == 1 && $status == 4) {
			jump_404();
		}
		// 文章数据有效性判断  end-------------------
		
		// TDK
		$title = $article_row['subject'] . '_天道留学';
		$keyword = $article_row['keyword'] . ',' . $article_row['subject'];
		$description = $article_row['subject']. ',' .$article_row['summary'];
		$keyword_list = explode(",", $article_row['keyword']); // 关键词
		$this->assign('title', $title);
		$this->assign('keyword', $keyword);
		$this->assign('description', $description);
		$this->assign('tags',$keyword_list);
		// 面包屑
	    $position = "<p class='lf'><a href='http://tiandaoedu.com/'>天道首页</a> &gt; <a href='http://tiandaoedu.com/cgal/'>成功案例</a> &gt; <a href='http://tiandaoedu.com/cgal/$tab/'>$tab_name</a></p>";
		$this->assign('position',$position);
		
		//文章内容
		$arr['body'] = $article_row['content'];
		$arr['body'] = str_replace('/ueditor/net/upload/', "http://img2.tiandaoedu.com/www/ueditor/net/upload/", $arr['body']);
		$arr['body'] = str_replace('/ueditor/net/uploadFile/', "http://img2.tiandaoedu.com/www/ueditor/net/uploadFile/", $arr['body']);
		$arr['body'] = str_replace('/UploadFile/thumbnail/', "http://img2.tiandaoedu.com/www/thumbnail/", $arr['body']);
		$arr['body'] = str_replace('/Uploads/', "http://img.tiandao.tdedu.org/Uploads/", $arr['body']);
		$body=explode("#p#", $arr['body']);

		if(count($body)>1){
			foreach ($body as $k=>$v) {
		
				$tmpv = cn_substr($v,80);
				$pos = strpos($tmpv,'#e#');
				 
				if($pos>0) {
					$st =trim(substr($tmpv,0,$pos));
		
					if($st==""||$st=="副标题"||$st=="分页标题") {
						$body[$k] = preg_replace("/^(.*)#e#/is","",$v);
						continue;
					}else {
						$body[$k] = preg_replace("/^(.*)#e#/is","",$v);
						$Titles[$k] = $st;
					}
				}
			}
		
			$TotalPage = count($body);
			for($i=0;$i<count($body);$i++) {
				$main.=$body[$i];
			}
		    
			$con_main="<div class=\"btwz ofh\" id=\"getone\" style=\"display:none\"><div class=\"wzy_bot\">".$article_row['info'].$main."</div></div>";
			
			$body=$body[$page-1];
			$fenye = view_page($page,$TotalPage,$id,$Titles,$article_row['subject']);
		 
			$pageli=$fenye['pageli']."<br/>";
		
			$title_list=$fenye['title_list'];
		
		}else{
			$body=$article_row['info'].$arr['body'];
		}
		
		//相关度
		$str = '';
		$related_list = $article_data['data']['related'];
		if ($related_list && count($related_list) > 0) {
			foreach ($related_list as $k => $val) {
				if ($k >= 4) {
					break;
				}
				$str .= "<dd><a target='_blank' href='".C('CGAL_ROOT').$tab."/".$val['article_id'].".html'>".$val['subject']."</a></dd>";
			}
		} else {
			$str = '<dd><span style="color:#636363">暂时还没有相关内容</span></dd>';
		}
		
		$recommend_list = $article_data['data']['recommend'];
		
		//频道推荐文章
		$str_tj = '';
		if($recommend_list && count($recommend_list) != 0) {
			foreach ($recommend_list as $k => $val) {
				if ($k >= 4) {
					break;
				}
				$str_tj .= "<dd><a target='_blank' href='".C('CGAL_ROOT').$tab."/".$val['article_id'].".html'>".$val['subject']."</a></dd>";
			}
		}else {
			$str_tj ='<dd><span style="color:#636363">暂时还没有相关内容</span></dd>';
		}
		
		// 文章详情页尾部tab切换内容
		$con  = get_api_data('con');  
		$this->assign('con',$con);
		//  文章详情页头部右侧轮播图
		$advert = get_api_data('advert');
		$this->assign('right_lunbo',$advert);
		// 文章详情页右边tab切换内容
		$success = get_api_data('success');
		$this->assign('success',$success);
		// 分享
		$share = get_api_data('share'); 
		$this->assign('share',$share); 
		// 文章详情页头部左侧轮播图
		$cplb_data= getAddata("4493",self::$pre_cache); 
		$this->assign('cplb_data',$cplb_data);
		// 日历上部轮播
		$lbcp_data= getAddata("4501",self::$pre_cache); 
		$this->assign('lbcp_data',$lbcp_data);
		// 右侧轮播(banner)
		$lbba_data= getAddata("4502",self::$pre_cache); 
		$this->assign('lbba_data',$lbba_data);
		// 右侧轮播(专题)
		$lbzt_data= getAddata("4500",self::$pre_cache); 
		$this->assign('lbzt_data',$lbzt_data);
		// 文字滚动
		$wzgd_data= getAddata("4699",self::$pre_cache); 
		$this->assign('wzgd_data',$wzgd_data);
		//无缝滚动
		$wfgd_data= getAddata("4476",self::$pre_cache); 
		$this->assign('wfgd_data',$wfgd_data);
		// 标题下方广告位
		$btgg_data= getAddata("4700",self::$pre_cache); 
		$this->assign('btgg_data',$btgg_data);
		
		$this->assign('recommend',$str_tj); //频道推荐
		$this->assign('relevancy',$str); //相关度
		$this->assign('pageli', $pageli);
		$this->assign('body', $body);
		$this->assign('title_list', $title_list);
		$this->assign('con_main', $con_main);
		$this->assign('article', $article_row);
		$this->display();
	}
	
	/**
	 * 获取文章标题下方的同学成功录取信息
	 * @param 文章id $id
	 * @return array
	 */
	protected function get_other($id) {
	
		$api_url = C('API_ROOT')."/Api/Article/get_cgal/";
		$data_arr = array('article_id'=>"$id");
		$str = http_curl($api_url, $data_arr);
		$result = json_decode($str, true);
		
		if(!empty($result['data']['school'])) {
				
			$OschoolId = array();
				
			foreach($result['data']['school'] as $k=>$v) {

				if(!empty($v['school_id']) && $v['school_id']!="暂无" ){
						
					$OschoolId[]= $v['school_id'];
						
				}else{
						
					if($v['school_name']!=$result[0]['school_name']){
						$school.=$v['school_name'];
					}
						
						
				}
				 
					
				$article['School'].=$result['data']['school'][$k]['school_name']."  ";
			}
				

			if(strlen($OschoolId[0])>5) {

				$Oyuanxiao=$this->getyuanxiao($OschoolId);
			}


		}

		$schoolId = array();
		
		foreach($result['data']['main'] as $key=>$value) {

			$article['SchoolMicroURL']=str_replace("/UploadFile/","http://img2.tiandaoedu.com/www/UploadFile/",$value['school_img']);
				
			$article['StudentName']= $value['studentname'];
			$article['OfferIndex'] = $value['school_rank'];
			$article['OfferSpecially'] = $value['offer_special'];
				
			$article['ApplyBack'] = !empty( $value['fuzzybackground']) && strlen($value['fuzzybackground'])>2?$value['fuzzybackground']:'';

			$article['Remark'] = $value['mark'];
			$article['Country'] = $value['country'];
			$article['Education'] = $value['education'];
			$article['ScholarshipMoney'] = $value['money'];
			$article['MainSchool']=$value['school_name'];
			$article['ScholarshipState']=$value['school_rank'];
			if(strlen($value['school_id']) > 5) {
				$schoolId[] = $value['school_id'];
			}
		}

		if(!empty($schoolId)) {

			$data2=$this->getyuanxiao($schoolId);
			$schoolImgArr = array();
			foreach($data2['response']['docs'] as $v) {
				$article['SchoolMicroURL'] = "http://img2.tiandaoedu.com/www".$v['SchoolTokenSrc'];
				$article['Schoolurl'] = "http://yuanxiao.tiandaoedu.com/".$v['SchoolUrl']."/";
			}

		}
		
		return $article;

	}
	
	/**
	 * 获取同学的录取院校及成绩
	 * @return string
	 */
	public function getBack($other,$country,$education) {
		if(!empty($other)) {
			$school=str_replace("{$other['MainSchool']}","","{$other['School']}");
			$mainschool=$other['MainSchool'];
			//全奖
			if($other['ScholarshipState']==3) {
				$School_state='全奖';
			}else{
				$School_state=$other['ScholarshipMoney'];
			}
		
		
			if(!empty($other['OSchoolurl'])) {
					
				$oschool="<p class=\"xza\">其他录取学校：".$other['OSchoolurl']."</p>";
			}
		
			if( strlen($school)>2 && empty($other['OSchoolurl'])){
					
				$oschool="<p class=\"xza\">其他录取学校：".$school."</p>";
					
			}
		
		
			if(!empty($other['Schoolurl'])) {
					
				$mainschool="<a href=\"".$other['Schoolurl']."\">".$mainschool."</a>";
					
			}
		
			if($other['ApplyBack'] && $other['ApplyBack']!==" ") {
				$back_ApplyBack = "<p>申请背景：".$other['ApplyBack']."</p>";// 申请背景存在显示模糊背景
			}else{
				$back_ApplyBack = "";// 申请备件不存在不显示该参数
			}
		
			$back="<dl class=\"xzmk_an1_dl\">
			<dt><img src=\"".$other['SchoolMicroURL']."\" width=\"100\" height=\"100\" /></dt>
			<dd>
				<p>".$other['StudentName']."</p>
                <p>主录学校：<b class=\"xz_xx\">".$mainschool."</b>  ".$School_state." ".$education[$other['Education']]." ".$country[$other['Country']]."</p>
				<p>院校排名：".$other['OfferIndex']."</p>
				<p>录取专业：".$other['OfferSpecially']."</p>".$back_ApplyBack."
				<p>基本成绩：".$other['Remark']."</p>
                ".$oschool."
			</dd>
		</dl>";
	    
		}
		return $back;
	}
	
	/**
	 * 获取校徽图章 及大学接口
	 * @param unknown $schoolIdAry
	 * @return string
	 */
	protected function getyuanxiao($schoolIdAry) {
	
		$api_url = C('API_SOLR').'yuanxiaoku/select?q=id%3A' . implode("+OR+", $schoolIdAry). "&wt=json&indent=true";
	
		return (array)file_get_contents($api_url);
	}
	
	
	/**
	 * 获取文章详情
	 * @param int $article_id
	 * @param string $type_name
	 * @param string $status 1、未审核2、审核通过3、审核失败4、已发布5、已下线
	 * @return array
	 * @author liukw
	 */
	private function getArticleInfo($id,$status=4,$systemId) {
		if(empty($id)){
			return false;
		}
	
		$key = format_key(C('CACHE.CGAL_ARTICLELIST'), "{$id}_{$systemId}", self::$pre_cache);
		$obj = redis_cache($key);
		if($obj) {
			return $data = unserialize($obj);
		}else{
			$api_url = C('API_ROOT').'/Api/Article/get_article/';
			$param = array('article_id' => $id, 'system_id' => $systemId, 'article_status' => $status);
			$str = http_curl($api_url, $param);
			$data = json_decode($str, true);
			if($data && $data['code'] === 0) {
				if($data['data']){
					// 屏蔽文章详情缓存功能
					// redis_cache($key, serialize($data), C('CACHE_TIMER'));
				}
			}
				
			return $data;
		}
	}
	
	/**
	 * 页面清理——删除静态页面，跳入详情页
	 * @author liukw
	 */
	public function refresh() {
		$article_id = I('id', 0); // 文章id article_id
		$page = I('page','');
		$flag = I('flag',0);
		//获取一级分类
		$url = $_SERVER['REQUEST_URI'];
		$params = explode('/', $url);
		$tab = $params[2];
		$file = "./../TiandaoRoot/Application/Html/cgal/article/{$article_id}_{$page}.html";
		if(file_exists($file)){
			unlink($file);
		}
		if($flag == 1){
			$url = C('CGAL_ROOT') . "{$tab}/{$article_id}.html?act=refresh&key=" . C('CLEAR_KEY');
			header("location: " . $url);
		}
	}
	
}