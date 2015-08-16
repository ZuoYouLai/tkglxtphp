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
		$this->load->library('XML');

	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
        $xml = new XML();
        $file = fopen("Area.xml","r") or die("Unable to open file!");//读取XML内容
		$filetxt ="";	
		$filetxt = fread($file,filesize("../uploadfile/Area/Area.xml"));//将文件中的序列化字符串读出
        $unTreeresult =$xml->xml_unserialize($filetxt);//反序成内容
        fclose($file);//关闭文件
        $tree=new Tree();//新建一个树
		$totalCount = count($unTreeresult["root"]["item"]);//获得数据总条目
		$pageSize=100;				
		$first =($pageIndex-1)*$pageSize; //得到获得数组循环的首下表
		$end = $pageIndex*$pageSize;//尾下标-1
		$end <= $totalCount?"":$end=$totalCount;
		$result = array();//取得小树
		for ($first;$first<$end;$first++)
			{ 
				$result[] = $unTreeresult["root"]["item"][$first];
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
				$totalResult = $this->AreaModel->getListByWhere('');//更新XML文件开始
				$tree=new Tree();
		        $treeResult =$tree->create($totalResult,1);//大树
				$file =fopen("../uploadfile/Area/Area.xml", "w");
				$xml= new XML();
				$res=$xml->xml_serialize($treeResult,0,null);
				fwrite($file, $res);//将序列化的字符写进文件
                fclose($file);
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
				$totalResult = $this->AreaModel->getListByWhere('');//更新XML文件开始
				$tree=new Tree();
		        $treeResult =$tree->create($totalResult,1);//大树
				$file =fopen("../uploadfile/Area/Area.xml", "w");
				$xml= new XML();
				$res=$xml->xml_serialize($treeResult,0,null);
				fwrite($file, $res);//将序列化的字符写进文件
       		    //print_r($res);
                fclose($file);
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
