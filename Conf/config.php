<?php 
return array(
		
	//成功案例TDK
	'TITLE'	=> '天道成功案例_天道教育',
	'KEYWORDS' => '天道留学成功案例大全,天道培训成功案例大全',
	'DESCRIPTION' => '天道成功案例频道为您提供天道学子包括美国、英国、加拿大、香港、澳大利亚、新西兰、德国、法国、荷兰等国家的最新天道留学成功案例，及天道SAT、托福、雅思、GRE、GMAT等天道培训成功案例,让您及时获取最新资讯。',	
 
    // 成功案例留学systemId
    'CGAL_LX_SYSTEMID' => 36, 
    
    // 成功案例海外就业systemId
    'CGAL_HW_SYSTEMID' => 42,
    // 缓存时间
	'CACHE_TIMER' => 1800,
		
	// 静态缓存
	'HTML_CACHE_ON' => false,
	'HTML_CACHE_RULES' => array(
		'Index:index' => array('cgal/home/index', 3600),
		'Category:index'=>array('cgal/category/{tab}_{kind}_{current_page}_{year}_{country}_{education}_{tag}', 3600),
		'Article:index'  =>array('cgal/article/{id}_{page}', 0),
	),
		
	'CACHE' => array(
		'CGAL_NEW'   => '01', //首页最新
		'CGAL_TUI'   => '02', //首页推荐
		'CGAL_HOT'   => '03', //首页最热	
		'CGAL_ZHUAN' => '04', //转/混申专业
		'CGAL_ZAIZHI' => '05', //在职申请
		'CGAL_DIFEN' => '06', //低分反转
		'CGAL_XIAOBEN' => '07', //小本逆袭  
		'CGAL_CHANG'  => '08', //常春藤
		'CGAL_DIY' => '09', //DIY失利
		'CGAL_HAIWAI' => '10', //海外申请
		'CGAL_WENLI' => '11', //文理学院
		'CGAL_LOVE' => '12', //情侣党
		'CGAL_YISHU' => '13', //艺术	
		'CGAL_CATEGORY' =>'14', //文章列表缓存字段
		'CGAL_AD' => '15', // 广告缓存
		'CGAL_ARTICLELIST' => '16', //文章详情页
	),
	
	//国家名称
    'ID_COUNTRY_ARR' => array("1" => "美国", "2" => "英国", "4" => "加拿大","8" => "新加坡", "16" => "澳洲", "32" => "香港",
    		 "33" => "荷兰", "34" => "欧洲","40" => "日本","64" => "其它"),
	//学历名称
	'CGAL_EDU_ARR' => array("全部", "本科", "硕士", "博士", "本科转学", "高中"),

	'CGAL_TYPE_ARR' => array('success' => 202,'undergrad' => 204,'mba' => 205,'graduate' => 206,'highschool' => 305),	
		
	//国家搜索对应的别名
	'CGAL_COUNTRY_ARR' => array("all" => "全部", "us" => "美国", "uk" => "英国", "ca" => "加拿大", "sg" => "新加坡", "aus" => "澳洲", 
			"hk" => "香港", "eu" => "欧洲","jp" => "日本",	"qt" => "其它"),

	//学历搜索对应的别名
	'CGAL_EDUCATION_ARR' => array("all" => "全部", "bs" => "博士", "ss" => "硕士", "bk" => "本科", 
			"bkzx" => "本科转学", "gz" => "高中", "gzxb" => "高中续本","gzjh" => "高中交换"),

	//年份搜索对应的别名
	'CGAL_YEAR_ARR' => array("all" => "全部","2017"=>"2017",	"2016"=>"2016","2015"=>"2015","2014"=>"2014","2013"=>"2013",
			"2012"=>"2012","2011"=>"2011","2010"=>"2010","2009"=>"2009","2008"=>"更早"),	
	//tag标签对应的别名
	'CGAL_TAG_ARR' => array("all" => "全部", "hszy"=>"转/混申专业","zzsq"=>"在职申请","dffz"=>"低分反转","xbnx"=>"小本逆袭",
			"cct"=>"常春藤","diysl"=>"DIY失利","hwsq"=>"海外申请","wlxy"=>"文理学院","qld"=>"情侣党","ys"=>"艺术","wbq"=>"五百强","h1b"=>"H-1B"),	

	//年份对应的数字
	'CGAL_YEAR_NUM_ARR' => array("all" => "0","2017"=>"2017","2016"=>"2016","2015"=>"2015","2014"=>"2014","2013"=>"2013","2012"=>"2012","2011"=>"2011","2010"=>"2010","2009"=>"2009","2008"=>"2008"),
	
	//国家简称对应的数字
    'CGAL_COUNTRY_NUM_ARR' => array("all" => "0","us" => "1","uk" => "2","ca" => "4","sg" => "8","aus" => "16","hk" => "32","eu" => "34","jp" => "40","qt" => "64"),

    //学历简称对应的数字
    'CGAL_ENUCATION_NUM_ARR' => array("all" => "0","bs" => "3","ss" => "2","bk" => "1","bkzx" => "4","gz" => "5","gzxb" => "14","gzjh" => "15"),
    
	//tag标签简称对应的数字
	'CGAL_TAG_NUM_ARR' => array("all" => "0","hszy"=>"60","zzsq"=>"61","dffz"=>"62","xbnx"=>"63","cct"=>"64","diysl"=>"65","hwsq"=>"66","wlxy"=>"67","qld"=>"68","ys"=>"69","wbq"=>"214","h1b"=>"215"),	
    
	//广告位id
	'CGAL_AD_ARR' => array("flash_data"=>4718,"flash_url"=>4769,"lbcp_data"=>4501,"lbba_data"=>4502,"lbzt_data"=>4500,"wzgd_data"=>4699,
					       "wz_cplb_data"=>4493,"wz_wfgd_data"=>4476,"btgg_data"=>4700 ),	
		
		
	//培训TDK
	'CGAL_PEIXUN_TDK' => array (
			   "0" => array (
						"name" => "全部",
						"url" => "",
						"s" => 0,
						"c" => 0,
						"title" => "天道培训成功案例,天道留学考试成功案例,出国留学考试成功案例|天道教育_培训成功案例",
						"keyword" => "天道培训成功案例,天道留学考试成功案例,出国留学考试成功案例",
						"desc" => "天道培训成功案例频道为您呈现天道留学考试成功案例,出国留学考试成功案例涵盖sat考试成功案例,托福考试成功案例,雅思考试成功案例,gmat考试成功案例,gre考试成功案例,ssat考试成功案例,ap考试成功案例,act考试成功案例,ib考试成功案例,小托福考试成功案例。"
				),
				"29" => array (
						"name" => "SAT",
						"url" => "sat/",
						"s" => 29,
						"c" => 309,
						"title" => "sat考试成功案例,sat培训案例,sat培训班|天道教育_培训成功案例",
						"keyword" => "sat考试成功案例,sat培训案例,sat培训班",
						"desc" => "天道培训sat考试成功案例栏目汇总天道sat培训班高分学员真实案例,让您通过sat培训案例了解天道名师教学方式,体验天道培训服务体系,让您放心选择天道sat培训班。"
				),
				"4" => array (
						"name" => "托福",
						"url" => "toefl/",
						"s" => 4,
						"c" => 284,
						"title" => "托福考试成功案例,托福培训案例,托福培训班|天道教育_培训成功案例",
						"keyword" => "托福考试成功案例,托福培训案例,托福培训班",
						"desc" => "天道培训托福考试成功案例栏目为您提供天道学员参加托福培训班获得优秀成绩的过程回顾,让您通过托福培训案例了解天道托福培训服务体系与天道名师教学方法,让您更加信赖天道托福培训班。"
				),
				"3" => array (
						"name" => "雅思",
						"url" => "ielts/",
						"s" => 3,
						"c" => 283,
						"title" => "雅思考试成功案例,雅思培训案例,雅思培训班|天道教育_培训成功案例",
						"keyword" => "雅思考试成功案例,雅思培训案例,雅思培训班",
						"desc" => "天道培训雅思考试成功案例栏目汇总天道雅思培训班高分学员真实案例,让您通过雅思培训案例了解天道名师教学方式,体验天道培训服务体系,让您放心选择天道雅思培训班。"
				),
				"28" => array (
						"name" => "GRE",
						"url" => "gre/",
						"s" => 28,
						"c" => 308,
						"title" => "gre考试成功案例,gre培训案例,gre培训班|天道教育_培训成功案例",
						"keyword" => "gre考试成功案例,gre培训案例,gre培训班",
						"desc" => "天道培训gre培训案例栏目汇总天道gre培训班高分学员真实案例,让您通过gre培训案例了解天道名师教学方式,体验天道培训服务体系,让您放心选择天道gre培训班。"
				),
				"27" => array (
						"name" => "GMAT",
						"url" => "gmat/",
						"s" => 27,
						"c" => 307,
						"title" => "gmat考试成功案例,gmat培训案例,gmat培训班|天道教育_培训成功案例",
						"keyword" => "gmat考试成功案例,gmat培训案例,gmat培训班",
						"desc" => "天道培训gmat考试成功案例栏目汇总天道gmat培训班高分学员真实案例,让您通过gmat培训案例了解天道名师教学方式,体验天道培训服务体系,让您放心选择天道gmat培训班。"
				),
				"30" => array (
						"name" => "SSAT",
						"url" => "ssat/",
						"s" => 30,
						"c" => 310,
						"title" => "ssat考试成功案例,ssat培训案例,ssat培训班|天道教育_培训成功案例",
						"keyword" => "ssat考试成功案例,ssat培训案例,ssat培训班",
						"desc" => "天道培训ssat考试成功案例栏目汇总天道ssat培训班高分学员真实案例,让您通过ssat培训案例了解天道名师教学方式,体验天道培训服务体系,让您放心选择天道ssat培训班。"
				),
				"41" => array (
						"name" => "日语",
						"url" => "ry/",
						"s" => 41,
						"c" => 1058,
						"title" => "日语考试成功案例,日语培训案例,日语培训班|天道教育_培训成功案例",
						"keyword" => "日语考试成功案例,日语培训案例,日语培训班",
						"desc" => "天道培训日语考试成功案例栏目汇总天道日语培训班高分学员真实案例,让您通过日语培训案例了解天道名师教学方式,体验天道培训服务体系,让您放心选择天道日语培训班。"
				)
		),
		
		//海外就业TDK
		'CGAL_HWJY_TDK' => array (
				"all" => array (
						"name" => "全部",
						"title" => "天道海外就业成功案例_天道教育",
						"keyword" => "天道海外就业成功案例,海外就业案例,天道名企就业案例",
						"desc" => "天道海外就业成功案例频道为您提供天道学子最新的海外就业案例,包括天道名企就业案例、国内外五百强企业就业案例、H-1B就业案例等,让您及时获得天道学子最新海外就业成功案例信息以及天道资深顾问对天道海外就业成功案例的精彩点评。"
				),
				"wbq" => array (
						"name" => "五百强",
						"title" => "天道五百强就业案例|天道教育_海外就业成功案例",
						"keyword" => "天道五百强就业案例,天道海外就业案例",
						"desc" => "天道五百强就业案例频道为您提供天道学子最新的五百强海外就业案例,包括天道名企就业案例、国内外五百强企业就业案例,让您及时获得天道学子最新海外就业成功案例信息以及天道资深顾问对天道海外就业成功案例的精彩点评。"
				),
				"h1b" => array (
						"name" => "H-1B",
						"title" => "天道H-1B海外就业案例|天道教育_海外就业成功案例",
						"keyword" => "天道H-1B就业案例,天道海外就业案例",
						"desc" => "天道H-1B就业案例频道为您提供天道学子最新的H-1B海外就业案例,包括天道名企就业案例、国内外H-1B企业就业案例,让您及时获得天道学子最新海外就业成功案例信息以及天道资深顾问对天道海外就业成功案例的精彩点评。"
				),
		),		
		
		// 培训对应的systemid和categoryid
		'CGAL_CATEGORY_ARR' => array(
			'sat'   => array('systemid'=>29,'categoryid'=>309),
			'toefl' => array('systemid'=> 4,'categoryid'=>284),
			'ielts' => array('systemid'=> 3,'categoryid'=>283),
			'gre'   => array('systemid'=>28,'categoryid'=>308),
			'gmat'  => array('systemid'=>27,'categoryid'=>307),
			'ssat'  => array('systemid'=>30,'categoryid'=>310),
			'ry'    => array('systemid'=>41,'categoryid'=>1058),
	    ),
		
		// 一级分类对应的名称
		'TAB_ARR' => array('lx'=>'留学','px'=>'培训','hwjy'=>'海外就业'),
);
