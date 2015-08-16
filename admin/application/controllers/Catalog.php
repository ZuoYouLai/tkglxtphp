<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Catalog extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CircleModel');
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
		$strWhere='pid=0';
		$orderBy='';
		$totalCount=$this->CircleModel->getListCountByWhere($strWhere);
		$catalog=$this->CircleModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'catalog'=>$catalog
		);
		$this->load->view('Catalog/list',$data);
	}
	/**
	 * 添加分类
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$dataArray=array(
				'pid'=>0,
				'name'=>$this->input->post('name'),
				'logo'=>$this->input->post('logo'),
				'thumb'=>$this->input->post('logo'),
				'intro'=>$this->input->post('intro'),
				'create_time'=>now()
			);//print_r($dataArray);exit;
			$result=$this->CircleModel->add($dataArray);
			if ($result){
				show_error('index.php/Catalog/index',500,'提示信息：分类添加成功！');
			}else{
				show_error('index.php/Catalog/add',500,'提示信息：分类添加失败！');
			}
		}else{
			$this->load->view('Catalog/add');
		}
	}
	/**
	 * 编辑分类
	 * Enter description here ...
	 */
	public function edit(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$id=$this->input->post('id');
			$dataArray=array(
				'pid'=>0,
				'name'=>$this->input->post('name'),
				'logo'=>$this->input->post('logo'),
				'thumb'=>$this->input->post('logo'),
				'intro'=>$this->input->post('intro'),
				'create_time'=>now()
			);//print_r($dataArray);exit;
			$result=$this->CircleModel->edit($dataArray,'id='.$id);
			if ($result){
				show_error('index.php/Catalog/index',500,'提示信息：分类修改成功！');
			}else{
				show_error('index.php/Catalog/edit',500,'提示信息：分类修改失败！');
			}
		}else{
			$id=$this->input->get('id');
			if ($id){
				$catalog=$this->CircleModel->getModel('id='.$id);
				if ($catalog){
					$this->load->view('Catalog/edit',array('catalog'=>$catalog));
				}else{
					show_error('index.php/Catalog/index',500,'提示信息：你要修改的分类不存在或者已被删除！');
				}
			}else{
				show_error('index.php/Catalog/index',500,'提示信息：参数错误！');
			}
		}
	}
}
