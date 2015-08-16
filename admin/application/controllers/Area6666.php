<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreaModel');
		$this->load->library('Tree');
		$this->load->library('Page');
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		//$pageSize=3500;
		//$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
		//$strWhere='';
		//$orderBy='';
		//$totalCount=$this->AreaModel->getCountByWhere($strWhere);
		//$result=$this->AreaModel->getByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		//$page=new Page();
		//$tree=new Tree();
		//$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		//$data['page']=$str_page;
		//$data['result']=$tree->create($result,1);
		//$this->load->view('Area/list',$data);

		$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
		$totalResult = $this->AreaModel->getListByWhere('');
		$tree=new Tree();
		$treeResult =$tree->create($totalResult,1);//大树
		$totalCount = count($treeResult);
		$pageSize=100;				
		$strWhere='';
		echo $totalCount;
		$first =($pageIndex-1)*$pageSize; //得到获得数组循环的首下表
		$end = $pageIndex*$pageSize;//尾下标-1
		$end <= $totalCount?"":$end=$totalCount;
		$result = array();//取得小树
		for ($first;$first<$end;$first++)
			{ 
				$result[] = $treeResult[$first];
			}			
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data['page']=$str_page;
		$data['result']=$result;
		$this->load->view('Area/list',$data);
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
				'level'=>$this->input->post('level'),
				'pid'=>$this->input->post('pid')
			);
			$result=$this->AreaModel->add($dataArray);
			if ($result){
				show_error('index.php/Area/index',500,'提示信息：地区添加成功！');
			}else{
				show_error('index.php/Area/add',500,'提示信息：地区添加失败！');
			}
		}else{
			$parent=$this->AreaModel->getListByWhere('');
			$tree=new Tree();
			$this->load->view('Area/add',array('parent'=>$tree->create($parent,1)));
		}
	}
	/**
	 * 编辑
	 * Enter description here ...
	 */
	public function edit(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$Area_id=$this->input->post('Area_id');
			$dataArray=array(
				'title'=>$this->input->post('title'),
				'level'=>$this->input->post('level'),
				'pid'=>$this->input->post('pid')
			);
			$result=$this->AreaModel->edit($dataArray,'id='.$Area_id);
			if ($result){
				show_error('index.php/Area/index',500,'提示信息：地区修改成功！');
			}else{
				show_error('index.php/Area/index',500,'提示信息：地区修改失败！');
			}
		}else{
			$Area_id=$this->input->get('id');
			if ($Area_id){
				$Area=$this->AreaModel->get('id='.$Area_id);
					$parent=$this->AreaModel->getListByWhere('');
					$tree=new Tree();
					$this->load->view('Area/edit',array('Area'=>$Area,'parent'=>$tree->create($parent)));
				}else{
					show_error('index.php/Area/index',500,'提示信息：参数错误！');
				}
			}
		}
	/**
	 * 删除
	 * Enter description here ...
	 */
	public function del(){
		$Area_id=$this->input->get('id');
		if ($Area_id){
			$result=$this->AreaModel->del('id='.$Area_id);
				if ($result){
					show_error('index.php/Area/index',500,'提示信息：地区删除成功！');
				}else{
					show_error('index.php/Area/index',500,'提示信息：地区删除失败！');
				}
			}else{
				show_error('index.php/Area/index',500,'提示信息：参数错误！');
			}
		}
	}
