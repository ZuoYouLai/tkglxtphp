<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Famous extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('FamousModel');
		$this->load->model('MemberModel');
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
		$totalCount=$this->FamousModel->getListCountByWhere($strWhere);
		$famous=$this->FamousModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'famous'=>$famous
		);
		$this->load->view('Famous/list',$data);
	}
	/**
	 * 添加用户
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$dataArray=array(
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'content'=>html_escape($_POST['content']),
				'province'=>$this->input->post('province'),
				'city'=>$this->input->post('city'),
				'district'=>$this->input->post('district'),
				'street'=>'',
				'status'=>$this->input->post('status'),
				'create_id'=>$this->input->post('create_id'),
				'create_time'=>now(),
			);//print_r($dataArray);exit;
			$result=$this->FamousModel->add($dataArray);
			if ($result){
				show_error('index.php/Famous/index',500,'提示信息：名媛专题添加成功！');
			}else{
				show_error('index.php/Famous/add',500,'提示信息：名媛专题添加失败！');
			}
		}else{
			$lady=$this->MemberModel->getList('is_lady=1');
			if ($lady){
				$this->load->view('Famous/add',array('lady'=>$lady));
			}else{
				show_error('index.php/Index/index',500,'提示信息：请先添加名媛数据，在新增名媛专题！');
			}
		}
	}
	/**
	 * 修改专题
	 * Enter description here ...
	 */
	public function edit(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$id=$this->input->post('id');
			$dataArray=array(
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'content'=>html_escape($_POST['content']),
				'province'=>$this->input->post('province'),
				'city'=>$this->input->post('city'),
				'district'=>$this->input->post('district'),
				'street'=>'',
				'status'=>$this->input->post('status'),
				'create_id'=>$this->input->post('create_id'),
				'create_time'=>now(),
			);//print_r($dataArray);exit;
			$result=$this->FamousModel->edit($dataArray,'id='.$id);
			if ($result){
				show_error('index.php/Famous/index',500,'提示信息：名媛专题修改成功！');
			}else{
				show_error('index.php/Famous/edit',500,'提示信息：名媛专题修改失败！');
			}
		}else{
			$id=$this->input->get('id');
			if ($id){
				$famous=$this->FamousModel->getModel('id='.$id);
				if ($famous){
					$lady=$this->MemberModel->getList('is_lady=1');
					if ($lady){
						$this->load->view('Famous/edit',array('lady'=>$lady,'famous'=>$famous));
					}else{
						show_error('index.php/Index/index',500,'提示信息：请先添加名媛数据，在新增名媛专题！');
					}
				}else{
					show_error('index.php/Famous/index',500,'提示信息：专题不存在或已被删除！');
				}
			}else{
				show_error('index.php/Famous/index',500,'提示信息：参数错误！');
			}
		}
	}
}
