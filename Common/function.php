<?php 

/**
 * 获取对应的id
 * @param 二级分类 $cat
 * @param 对应的key $key
 * @return int
 */
function get_id($cat,$key){
	if(empty($cat) || empty($key)){
		$id = 0;
	}
	$arr = C('CGAL_CATEGORY_ARR');
	$id = $arr[$cat][$key];
	return $id;
}

/**
 * 成功案例记录数
 * @param 案例录取年份 $year
 * @param 案例录取国家 $country_id
 * @param 案例教育背景 $edu_id
 * @param 案例所属标签 $label_id
 * 参数顺序对应 [0]flag [1]sort [2]year [3]country_id [4]num [5]edu_id [6]label_id
 */
function get_success_list($param_arr) {
	$api_url = C('API_ROOT').'/Api/Article/get_success_list/';
	$param = array(
		'flag'=> $param_arr[0],
		'sort'=> $param_arr[1],
		'year'=> $param_arr[2], 
		'country_id'=> $param_arr[3],
        'num'=> $param_arr[4],
		'edu_id'=> $param_arr[5], 
		'label_id'=> $param_arr[6],
	);
	$str = http_curl($api_url, $param);
	
	if ($str) {
		$data = json_decode($str, true);
		$datacount =  $data['count'];
		$data = $data['data'] ;
		if ($data) {
			foreach ($data as $k => $v) {
				$article['a_Id'] = $v['article_id'];
				$article['a_Subject']= $v['subject'];
				$article['SchoolName'] = $v['school_name'];//大学
				$article['Country'] = $v['country'];
				$article['Education'] = $v['education'];
				$article['SchoolId'] = $v['school_id'];
				$article['a_Summary'] = $v['summary'];
				$article['ScholarshipMoney'] = isset($v['money']) ? $v['money'] : '';
				$article['SchoolMicroURL'] = (strpos($v['school_img'], "http://") !== false ? "" : "http://img2.tiandaoedu.com/www") . $v['school_img'];
				$article['SchoolMicroURL'] = str_replace("/UploadFile/schoolMicro//UploadFile/schoolMicro/", "/UploadFile/schoolMicro/", $article['SchoolMicroURL']);
				$results[] = $article;
			}
		}
		$data = array ();
		$data = $results;
		return $data;
	} 
	
	return false;
}


/**
 * 获取文章页数据
 * @param 系统分类id $systemId
 * @param 页数            $pageSize
 * @param string $Status
 * @param 排序        $ArchiveOrderbys
 * @param 页号       $currentPage
 * @param string $CustomFlagId
 * @param 栏目id $categoryId
 * @param string $ArticleTopOptionId
 * @param string $ApplicationStage
 * @param string $ApplicationAspects
 * @param string $flag
 * @return array|string
 */
function get_cgal_article_info($systemId = '', $pageSize = '', $Status = '', $ArchiveOrderbys = '', $currentPage = '', $CustomFlagId = '', $categoryId = '', $flag = '') {
	if (empty ( $systemId ) || empty ( $pageSize ) || empty ( $Status ) || empty ( $ArchiveOrderbys )) {
		return '';
	}

	$result = array ();
	$api_url = C('API_ROOT')."/Api/Article/get_list/";
	$data_arr = array(
			'system_id'=>"$systemId", //系统分类id
			'category_id'=>"$categoryId",//栏目id
			'page'=>"$currentPage", //页号
			'num'=>"$pageSize", //页数
			'orderby'=>"$ArchiveOrderbys", 
			'flag'=>"$flag", 
			'flag_id'=>"$CustomFlagId"
	);
	$str = http_curl($api_url, $data_arr);
	$data = json_decode($str, true);
	return $data;
}

/**
 * 获取培训数据
 * @param 栏目id $systemId
 * @param 分类id $category_id
 * @param 总页码    $pageSize
 * @param 当前页    $current_page
 * @param unknown  $flag
 * @param 排序规则  $orderby
 * @param 推荐        $flag_id
 * @return string|mixed
 */
function get_px_article_info($systemId,$category_id,$pageSize,$current_page,$flag,$orderby,$flag_id){
	if(empty($systemId) || empty($category_id)){
		return '';
	}
	$api_url = C('API_ROOT')."/Api/Article/get_pxal/";
	$data_arr = array(
		'system_id' => $systemId,
		'num' => $pageSize,
		'page' => $current_page,
		'flag' => $flag,
		'category_id'=> $category_id,
		'orderby'=>$orderby,
		'flag_id'=>$flag_id
	);
	$str = http_curl($api_url,$data_arr);
	$data = json_decode($str,true);
	return $data;
}


// 获取广告位数据
function getAddata( $adid = '',$pre_cache) {
	if (empty ( $adid ) ) {
		return '';
	}
	$key = format_key(C('CACHE.CGAL_AD'),$adid,$pre_cache);
	$obj = redis_cache($key);
    if($obj){
    	$info = unserialize($obj);
    }
	return $info;
}


//中文截取2，单字节截取模式
function cn_substr($str, $length, $start=0) {
	if(strlen($str) < $start+1) {
		return '';
	}
	preg_match_all("/./su", $str, $ar);
	$str = '';
	$tstr = '';

	//为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
	for($i=0; isset($ar[0][$i]); $i++) {
		if(strlen($tstr) < $start) {
			$tstr .= $ar[0][$i];
		} else {
			if(strlen($str) < $length + strlen($ar[0][$i])) {
				$str .= $ar[0][$i];
			} else {
				break;
			}
		}
	}

	return $str;
}

/**
 * 获取分类及tdk
 * @param 系统id $systemId
 * @param 栏目id $categoryId
 * @return array
 */
function get_tdk($systemId = '', $categoryId = '') {
	if (empty ( $systemId )) {
		return '';
	}

	$api_url = C('API_ROOT')."/Api/Article/get_tdk/";
	$data_arr = array('system_id'=>"$systemId", 'category_id'=>"$categoryId");
	$str = http_curl($api_url, $data_arr);
	$data = json_decode($str, true);

	$result = $data['data'][0];

	$tdk ['title'] = $result ['title'];
	$tdk ['keywords'] = $result ['keyword'];
	$tdk ['description'] = $result ['description'];
	$tdk ['category_id'] = $result ['category_id'];

	return $tdk;
}

/**
 * 获取轮播图接口
 * @param 请求接口标识符 $type
 * @return boolean|string
 */
function get_api_data($type=''){
	if(empty($type)){
		return false;
	}

	switch($type){
		case 'calendar':
			$api_url = C('O_API_ROOT')."newCan/calendar.class.php";
			return file_get_contents($api_url);
			break;
		case 'success':
			$api_url = C('O_API_ROOT')."html/mod/api.right.php?type=lxal";
			return file_get_contents($api_url);
			break;
		case 'advert':
			$api_url= C('O_API_ROOT')."advert.php";
			return unserialize(file_get_contents($api_url));
		case 'con':
			$api_url = C('O_API_ROOT')."get_con.php?select=archive";
			return file_get_contents($api_url);
			break;
		case 'share':
			$api_url = C('O_API_ROOT')."html/mod/api.publichtml.php?m=share";
			return file_get_contents($api_url);
			break;
	}
}


/**
 * 格式化文章列表页
 * @return string
 */
 function update_sort_html($updateSortDate,$pre){
	$TotalPageCount=$updateSortDate["count"];
	if(empty($TotalPageCount)){
		return "<div id=\"content_list\" align=\"center\" style=\"color:#666666\">没有更多文章了！</div>";
	}
	foreach ($updateSortDate['data']  as $row) {
		if (! empty($row['keyword'])) {
			$key = "";
			$key .= "<p class=\"gjc\">关键词：";
			$rown = explode(",", $row['keyword']);
			foreach ($rown as $k => $v) {
				$key .= "<span>{$v}</span>&nbsp;&nbsp;";
			}
			$key .= "</p>\r\n";
		}
		//文章url
		$href = "http://tiandaoedu.com/cgal/".$pre."/".$row["article_id"].".html";
			
		$src='src="'.format_thumb_url($row['thumb_img']).'"';


		if (strpos($row['flag'], "3") !== false) {
			$content .= "<div class=\"sty_one ofh\">\r\n
			    <div class=\"lf\"><a href=\"".$href."\"><img alt=\"".$row['subject']."\" ".$src." width=\"210px\" height=\"140px\"/></a></div>\r\n
			    <div class=\"sty_one_main rf\">\r\n
			    <p class=\"ptit\"><a href=\"$href\">" .$row['subject']. "</a></p>\r\n
				   <p>" . cn_substr($row['summary'], 340, 0) . "...</p>\r\n ". $key ."
				   <p class=\"a_zx\"><a href=\"http://tb.53kf.com/webCompany.php?arg=10136855&style=1\" class=\"btn-w btn-online-chat\" rel=\"nofollow\">在线咨询 &gt;</a><a href=\"http://tiandaoedu.com/lxcp/lxfa/\">留学方案 &gt;</a></p>\r\n
				</div>\r\n";
		} else {
			$content .= "<div class=\"sty_two ofh\">\r\n
			   <p class=\"ptit\"><a href=\"".$href."\">" .$row['subject']. "</a></p>\r\n
			   <p>" . cn_substr($row['summary'], 340, 0) . "...</p>\r\n ". $key ."
			   <p class=\"a_zx\"><a href=\"http://tb.53kf.com/webCompany.php?arg=10136855&style=1\" class=\"btn-w btn-online-chat\" rel=\"nofollow\">在线咨询 &gt;</a><a href=\"http://tiandaoedu.com/lxcp/lxfa/\">留学方案 &gt;</a></p>\r\n";
		}
		$content .= "</div>\r\n\r\n";
	}
	return $content;
}

/**
 * 文章的分页方法
 * @param unknown $page
 * @param unknown $total
 * @param unknown $aid
 * @param unknown $titles
 * @param unknown $arc_title
 * @return string
 */
function view_page($page,$total,$aid,$titles,$arc_title){
	//保证页码的合法性
	if($page<1) $page = 1;

	$next=$page+1; //下一页
	$pagenext=$aid."_".$next.".html";


	$pre=$page-1;  //上一页
	$pagepre=$aid."_".$pre.".html";
	if($pre<1) {
		$pageli.= "<div class=\"dy ofh\"><div id=\"er_ym\" class=\"yema ofh\"><a class=\"syy\">上</a>";
	}
	else{
		$pageli.= "<div class=\"dy ofh\"><div id=\"er_ym\" class=\"yema ofh\"><a class=\"syy\" href=\"".$pagepre."\">上</a>";
	}


	$title_list.="";

	for($i=1;$i<=$total;$i++){

		if(count($titles)==0)
		{
			$page_title=$arc_title;
		}else{

			if ($i<2){
				$page_title=$arc_title;
			}else{
				$lnpage=$i-1;
				$page_title=$titles[$lnpage];
			}
			if (empty($page_title)){
				$page_title=$arc_title;
			}
		}

		if($i>1)
		{

			$newurl=$aid."_".$i.".html";
		}
		else
		{
			$newurl=$aid.".html";
		}
			
		if($i!=$page){

			$pageli.= "<a href=\"".$newurl."\">$i</a>";

		}else{

			$pageli.= "<a class='hover' \">$i</a>";

		}

		$title_list.="<a href=".$newurl.">".$page_title."</a>";
	}

	if($next>$total) {
	 $pageli.= "<a class=\"xyy\">下</a><p><span>共</span><span id=\"totalpage\">".$total."</span><span>页</span></p><div id=\"info_btn_1\" onclick=\"ShowHide('getone','all_con')\">阅读全文</div></div></div>";
	}else{
	 $pageli.= "<a class=\"xyy\" href=\"".$pagenext."\">下</a><p><span>共</span><span id=\"totalpage\">".$total."</span><span>页</span></p><div id=\"info_btn_1\" onclick=\"ShowHide('getone','all_con')\">阅读全文</div></div></div>";
	}

	$fy['title_list']=$title_list;
	$fy['pageli']=$pageli;
	return $fy;
}

/**
 * 格式化缩略图url
 * @param unknown $thmb_url
 * @return string|Ambigous <string, mixed>
 */
function format_thumb_url($thmb_url) {
	if(empty($thmb_url)){
		return '';
	}

	if(strpos($thmb_url, "UploadFile") !== false){
		$thmb_url=str_replace("/UploadFile/",C('IMG2_URL')."/UploadFile/",$thmb_url);
	}elseif(strpos($thmb_url, "ueditor/net/upload")!==false){
		$thmb_url=str_replace('/ueditor/net/upload/',C('IMG2_URL')."/ueditor/net/upload/",$thmb_url);
	}elseif(strpos($thmb_url, "ueditor/net/uploadFile")!==false){
		$thmb_url=str_replace('/ueditor/net/uploadFile/',C('IMG2_URL')."/ueditor/net/uploadFile/",$thmb_url);
	}elseif(strpos($thmb_url, "/Uploads/")!==false){
		$thmb_url="http://img.tiandao.tdedu.org".$thmb_url;
	}else{
		if(strpos($thmb_url, "http://") !== false){
			$thmb_url="http://tiandaoedu.com".$thmb_url;
		}
	}

	return $thmb_url;
}


function SpHtml2Text($str)
{
	$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
	$alltext = "";
	$start = 1;
	for($i=0;$i<strlen($str);$i++)
	{
		if($start==0 && $str[$i]==">")
		{
			$start = 1;
		}
		else if($start==1)
		{
			if($str[$i]=="<")
			{
				$start = 0;
				$alltext .= " ";
			}
			else if(ord($str[$i])>0x80)
			{
				$alltext .= $str[$i];
				$alltext .= $str[$i+1];
				$i++;
			}
			else
			{
				$alltext .= $str[$i];
			}
		}
	}
	$alltext = str_replace("\"","'",$alltext);
	$alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
	$alltext = preg_replace("/[ ]+/s"," ",$alltext);
	return $alltext;
}


/**
 * 获取国家名称
 * @param string $country_id
 * @return string
 * @author liukw
 */
function get_coutry($country_id) {
	$country_arr = C('ID_COUNTRY_ARR');
	if ($country_arr && $country_id) {
		if (array_key_exists($country_id, $country_arr)) {
			return $country_arr[$country_id];
		}
	}
	
	return '';
}

/**
 * 获取国家名称
 * @param string $education_id
 * @return string
 * @author liukw
 */
function get_education($education_id) {
	$education_arr = C('CGAL_EDU_ARR');
	if ($education_arr && $education_id) {
		if (array_key_exists($education_id, $education_arr)) {
			return $education_arr[$education_id];
		}
	}

	return '';
}
