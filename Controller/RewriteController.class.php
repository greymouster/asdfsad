<?php
// +----------------------------------------------------------------------
// | http://tiandaoedu.com/zyjx/
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://tiandaoedu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: liukw<kaiwei.liu@tiandaoedu.com>
// +----------------------------------------------------------------------

namespace Cgal\Controller;
use Think\Controller;

class RewriteController extends Controller {
	
	/**
	 * 首页
	 */
	public function index() {
		$url="http://".$_SERVER['HTTP_HOST']."/cgal/".$_GET['category']."/";
		Header( "HTTP/1.1 301 Moved Permanently" );
		Header( "Location: ".$url."" );		
	}	
}