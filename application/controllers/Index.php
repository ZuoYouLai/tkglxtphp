<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//��ֹ�û�ֱ��ͨ��·�������ʿ���������������Ļ�����ʾ�Ҳ�������װ��

class Index extends CI_Controller {
	/**
	 * ���캯��
	 * Enter description here ...
	 */
	 public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Ĭ�Ϻ���
	 * Enter description here ...
	 */
	public function index()
	{
		$this->load->view('Index/index');
	}
	
}