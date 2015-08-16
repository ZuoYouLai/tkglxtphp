<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//放止用户直接通过路径来访问控制器，如果这样的话会显示找不到（封装）

class Index extends CI_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	 public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$this->load->view('Index/index');
	}
	
}