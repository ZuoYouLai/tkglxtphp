<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PostFocus extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('FocusModel');
		$this->load->library('Page');
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$index=$this->input->post('index');//图片次序
			$path=$this->input->post('path');//图片路径
			$url=$this->input->post('url');//图片路径
			$focus=$this->FocusModel->getList('position=1 LIMIT 5');	//首页焦点图
			$dataArray=array(
				'path'=>$path,
				'url'=>$url,
				'position'=>1
			);
			if (count($focus)<5){
				$flag=$this->FocusModel->add($dataArray);
				if ($flag){
					show_error('index.php/postFocus/index',500,'提示信息：焦点图添加成功！');
				}else{
					show_error('index.php/postFocus/index',500,'提示信息：焦点图添加失败！');
				}
			}else{
				$flag=$this->FocusModel->edit($dataArray,array('id'=>$focus[$index-1]['id']));
				if ($flag){
					show_error('index.php/postFocus/index',500,'提示信息：第'.$index.'张焦点图修改成功！');
				}else{
					show_error('index.php/postFocus/index',500,'提示信息：第'.$index.'张焦点图修改失败！');
				}
			}
		}else{
			$focus=$this->FocusModel->getList('position=1 LIMIT 5');	//首页焦点图
			$this->load->view('postFocus/list',array('focus'=>$focus));
		}
	}
}
