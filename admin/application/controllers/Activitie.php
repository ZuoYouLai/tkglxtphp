<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Activitie extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ActivitieModel');
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
		$totalCount=$this->ActivitieModel->getListCountByWhere($strWhere);
		$activitie=$this->ActivitieModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'activitie'=>$activitie
		);
		$this->load->view('Activitie/list',$data);
	}
	/**
	 * 添加
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$dataArray=array(
				'title'=>$this->input->post('title'),
				'focus'=>$this->input->post('focus'),
				'video'=>$this->input->post('video'),
				'description'=>$this->input->post('description'),
				'article'=>$this->input->post('article'),
				'pic'=>$this->input->post('pic'),
				'status'=>$this->input->post('status'),
				'create_time'=>now(),
			);
			$result=$this->ActivitieModel->add($dataArray);
			if ($result){
				show_error('index.php/Activitie/index',500,'提示信息：活动专题添加成功！');
			}else{
				show_error('index.php/Activitie/add',500,'提示信息：活动专题添加失败！');
			}
		}else{
			$this->load->view('Activitie/add');
		}
	}
	/**
	 * 修改
	 * Enter description here ...
	 */
	public function edit(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$id=$this->input->post('id');
			$dataArray=array(
				'title'=>$this->input->post('title'),
				'focus'=>$this->input->post('focus'),
				'video'=>$this->input->post('video'),
				'description'=>$this->input->post('description'),
				'article'=>$this->input->post('article'),
				'pic'=>$this->input->post('pic'),
				'status'=>$this->input->post('status'),
				'create_time'=>now(),
			);
			$result=$this->ActivitieModel->edit($dataArray,'id='.$id);
			if ($result){
				show_error('index.php/Activitie/index',500,'提示信息：活动专题修改成功！');
			}else{
				show_error('index.php/Activitie/add',500,'提示信息：活动专题修改失败！');
			}
		}else{
			$id=$this->input->get('id');
			if ($id){
				$activitie=$this->ActivitieModel->getModel('id='.$id);
				if ($activitie){
					$this->load->view('Activitie/edit',array('activitie'=>$activitie));
				}else{
					show_error('index.php/Activitie/index',500,'提示信息：活动专题已被删除或不存在！');
				}
			}else{
				show_error('index.php/Activitie/index',500,'提示信息：参数错误！');
			}
		}
	}
}
