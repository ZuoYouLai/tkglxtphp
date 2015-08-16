<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EditorMember extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('MemberModel');
		$this->load->model('RoleModel');
		$this->load->library('Page');
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$user=$this->user;
		/**分页参数**/
		$pageSize=12;
		$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
		$strWhere='create_id='.$user['id'];
		$orderBy='';
		$role=$this->RoleModel->getRole('id='.$user['role_id']);
		if ($role){
			//超级管理员可以查看所有系统会员
			if ($role['level']=='1'){
				$strWhere='create_id<>0';
			}else if($role['level']=='2'){//管理员可以查看所有系统会员[不包含超级管理员添加的会员]
				$strWhere='create_id<>0 AND create_id<>1';
			}else if ($role['level']=='3'){//3级管理员可以查看所有下级会员及自己创建的会员
				$strWhere='create_id='.$user['id'].' OR create_id IN (SELECT id FROM jooli_user WHERE role_id IN (SELECT id FROM jooli_role WHERE pid='.$role['id'].'))';
			}else if ($role['level']=='4'){//4级管理员可以查看所有自己创建的会员
				$strWhere='create_id='.$user['id'];
			}
			$totalCount=$this->MemberModel->getListCountByWhere($strWhere);
			$member=$this->MemberModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
			$page=new Page();
			$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
			$data=array(
				'page'=>$str_page,
				'member'=>$member
			);
			$this->load->view('EditorMember/list',$data);
		}else{
			show_error('index.php/Index/index',500,'提示信息：你所在的用户组状态异常！');
		}
	}
	/**
	 * 添加用户
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$user=$this->user;
			$tel=$this->input->post('tel');
			$password=$this->input->post('password');
			$member=$this->MemberModel->getModel("tel='".$tel."'");
			if (!$member){
				$dataArray=array(
					'nick_name'=>$this->input->post('nick_name'),
					'tel'=>$tel,
					'password'=>md5($password),
					'token'=>md5(create_random(32)),
					'create_id'=>$user['id'],
					'create_time'=>now()
				);
				$result=$this->MemberModel->add($dataArray);
				if ($result){
					show_error('index.php/EditorMember/index',500,'提示信息：会员添加成功！');
				}else{
					show_error('index.php/EditorMember/add',500,'提示信息：会员添加失败！');
				}
			}else{
				show_error('index.php/EditorMember/add',500,'提示信息：手机号码已被占用！');
			}
		}else{
			$this->load->view('EditorMember/add');
		}
	}
	/**
	 * 修改系统会员
	 * Enter description here ...
	 */
	public function edit(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			$id=$this->input->post('id');
			$user=$this->user;
			$tel=$this->input->post('tel');
			$password=$this->input->post('password');
			$member=$this->MemberModel->getModel("id='".$id."'");
			if ($member){
				$dataArray=array();
				if ($password==$member['password']){
					$dataArray=array(
						'nick_name'=>$this->input->post('nick_name'),
					);
				}else{
					$dataArray=array(
						'nick_name'=>$this->input->post('nick_name'),
						'password'=>md5($password)
					);
				}
				$result=$this->MemberModel->edit($dataArray,'id='.$id);
				if ($result){
					show_error('index.php/EditorMember/index',500,'提示信息：会员修改成功！');
				}else{
					show_error('index.php/EditorMember/edit',500,'提示信息：会员修改失败！');
				}
			}else{
				show_error('index.php/EditorMember/edit',500,'提示信息：你修改的会员不存在或者已被删除！');
			}
		}else{
			$id=$this->input->get('id');
			if ($id){
				$member=$this->MemberModel->getModel('id='.$id);
				if ($member){
					$this->load->view('EditorMember/edit',array('member'=>$member));
				}else{
					show_error('index.php/EditorMember/edit',500,'提示信息：你修改的会员不存在或者已被删除！');
				}
			}else{
				show_error('index.php/EditorMember/edit',500,'提示信息：参数错误！');
			}
		}
	}
}
