<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member extends My_Controller {
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
		$strWhere='';
		$orderBy='';
		$totalCount=$this->MemberModel->getListCountByWhere($strWhere);
		$member=$this->MemberModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'member'=>$member
		);
		$this->load->view('Member/list',$data);
	}
}
