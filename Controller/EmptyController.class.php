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

class EmptyController extends Controller {
	
	//空操作_empty()方法
    function _empty(){
		header("HTTP/1.0 404 Not Found");
		include ("./404.htm");
		exit;
    }
    
    function index(){
        header("HTTP/1.0 404 Not Found");
		include ("./404.htm");
		exit;
    }
}