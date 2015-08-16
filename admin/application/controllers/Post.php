<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Post extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('PostModel');
		$this->load->library('Page');
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$pageSize=12;
		$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
		$strWhere='a.is_del<>1';
		$orderBy='';
		$totalCount=$this->PostModel->getListCountByWhere($strWhere);
		$post=$this->PostModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'post'=>$post
		);
		$this->load->view('Post/list',$data);
	}
	/**
	 * 回收站函数
	 * Enter description here ...
	 */
	public function rubbish()
	{
		$pageSize=12;
		$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
		$strWhere='a.is_del =1';
		$orderBy='';
		$totalCount=$this->PostModel->getListCountByWhere($strWhere);
		$post=$this->PostModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'post'=>$post
		);
		$this->load->view('Post/rubbish_list',$data);
	}
	/**
	 * 操作函数
	 * Enter description here ...
	 */
	public function operate(){
		$cmd=$this->input->get('cmd');
		$ids=$this->input->get('ids');
		switch ($cmd){
			case "pingbi":
				$flag=$this->PostModel->edit(array('status'=>0),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Post/index',500,'提示信息：帖子屏蔽成功！');
				}else{
					show_error('index.php/Post/index',500,'提示信息：帖子屏蔽失败！');
				}
				break;
			case "rec":
				$flag=$this->PostModel->edit(array('is_elite'=>1),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Post/index',500,'提示信息：帖子推荐成功！');
				}else{
					show_error('index.php/Post/index',500,'提示信息：帖子推荐失败！');
				}
				break;
			case "del":
				$flag=$this->PostModel->del('id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Post/index',500,'提示信息：帖子彻底删除成功！');
				}else{
					show_error('index.php/Post/index',500,'提示信息：帖子彻底删除失败！');
				}
				break;
			case "rubbish":
				$flag=$this->PostModel->edit(array('is_del'=>1),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Post/index',500,'提示信息：帖子删除成功！');
				}else{
					show_error('index.php/Post/index',500,'提示信息：帖子删除失败！');
				}
				break;
			case "ret":
				$flag=$this->PostModel->edit(array('is_del'=>0,'status'=>1),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Post/index',500,'提示信息：帖子还原成功！');
				}else{
					show_error('index.php/Post/index',500,'提示信息：帖子还原失败！');
				}
				break;
		}
	}
}
