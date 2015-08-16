<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ProductModel');
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
		$strWhere='';
		$orderBy='';
		$totalCount=$this->ProductModel->getListCountByWhere($strWhere);
		$product=$this->ProductModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'product'=>$product
		);
		$this->load->view('Product/list',$data);
	}
	/**
	 * 添加用户
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$dataArray=array(
				'title'=>$this->input->post('title',true),
				'head_pic'=>$this->input->post('head_pic',true),
				'number'=>$this->input->post('number',true),
				'points'=>$this->input->post('points',true),
				'stoSum'=>$this->input->post('stoSum',true),
				'balSum'=>$this->input->post('stoSum',true),
				'focus'=>$this->input->post('focus',true),
				'content'=>html_escape($_POST['content']),
				'status'=>$this->input->post('status',true),
				'intime'=>now()
			);
			$result=$this->ProductModel->add($dataArray);
			if ($result){
				show_error('index.php/Product/index',500,'提示信息：商品信息添加成功！');
			}else{
				show_error('index.php/Product/add',500,'提示信息：商品信息添加失败！');
			}
		}else{
			$this->load->view('Product/add');
		}
	}
}