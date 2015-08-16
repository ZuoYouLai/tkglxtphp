<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//放止用户直接通过路径来访问控制器，如果这样的话会显示找不到（封装）

class Login extends CI_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	 public function __construct()
	{
		parent::__construct();
		$this->load->model('LoginModel');//引入LoginModel在后面的时候不用再一一引入
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$head='头部';
		$title='主题';
		echo"hello";
		
	}
	
}
