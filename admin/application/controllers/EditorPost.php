<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EditorPost extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('PostModel');
		$this->load->model('PostImageModel');
		$this->load->model('CircleModel');
		$this->load->model('MemberModel');
		$this->load->model('IconsModel');
		$this->load->model('RoleModel');//
		$this->load->model('TaskModel');
		$this->load->model('PointRecordsModel');
		$this->load->model('MessageModel');
		$this->load->library('Page');
		$this->load->library('ThumbHandler');
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
			//超级管理员可以查看所有系统会员创建的帖子
			if ($role['level']=='1'){
				$strWhere='a.create_id IN (SELECT id FROM jooli_member WHERE create_id<>0)';
			}else if($role['level']=='2'){//管理员可以查看所有系统会员创建的帖子[不包含超级管理员添加的会员]
				$strWhere='a.create_id IN (SELECT id FROM jooli_member WHERE create_id<>0 AND create_id<>1)';
			}else if ($role['level']=='3'){//3级管理员可以查看所有下级会员及自己创建的会员创建的帖子
				$strWhere='a.create_id IN (SELECT id FROM jooli_member WHERE create_id='.$user['id'].' OR create_id IN (SELECT id FROM jooli_user WHERE role_id IN (SELECT id FROM jooli_role WHERE pid='.$role['id'].')))';
			}else if ($role['level']=='4'){//4级管理员可以查看所有自己创建的会员创建的帖子
				$strWhere='a.create_id IN (SELECT id FROM jooli_member WHERE create_id='.$user['id'].')';
			}
			$totalCount=$this->PostModel->getListCountByWhere($strWhere);
			$post=$this->PostModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
			$page=new Page();
			$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
			$data=array(
				'page'=>$str_page,
				'post'=>$post
			);
			$this->load->view('EditorPost/list',$data);
		}else{
			show_error('index.php/Index/index',500,'提示信息：你所在的用户组状态异常！');
		}
	}
	/**
	 * 添加帖子
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit'){
			/*接收到的参数*/
			$circle_id=$this->input->post('circle_id');//圈子ID
			$create_id=$this->input->post('create_id');//作者ID
			$allow_reply=$this->input->post('allow_reply');//是否允许评论
			$content=$this->input->post('content');//帖子内容
			$province=$this->input->post('province');//定位省份
			$city=$this->input->post('city');//定位城市
			$district=$this->input->post('district');//定位区域
			$street='';//定位街道
			$picnum=$this->input->post('picnum');//上传的图片数量
			$circle=$this->CircleModel->getModel('id='.$circle_id);//查询去圈子信息
			$member=$this->MemberModel->getModel('id='.$create_id);
			//匹配内容中的话题
			$pattern='/#(.*)#/';
			preg_match($pattern,$content,$matches);
			if($matches){
				$topic=split(' ',$matches[0]);
				foreach ($topic as $item){//循环将话题替代成话题连接
					if (!empty($item)){
						$content=str_replace($item,'<a style="display:inline;" href="index.php?post&cmd=detail_topic&keyword='.urlencode(rtrim(substr($item,1),'#')).'">'.$item.'</a>',$content);
					}
				}
			}
			//匹配内容中的@好友
			$relation=array();
			$pattern='/@(.*) /';
			preg_match($pattern,$content,$matches);
			if ($matches){
				$sis=split(' ',$matches[0]);
				foreach ($sis as $item){//循环将话题替代成话题连接
					if (!empty($item)){
						//根据用户名查询用户信息
						$mem=$this->MemberModel->getModel("nick_name='".substr($item,1)."'");
						if ($mem){
							$content=str_replace($item,'<a style="display:inline;" href="index.php?member&cmd=zone&id='.$mem['id'].'">'.$item.'</a>',$content);
							$relation[]=$mem;
						}
					}
				}
			}
			//匹配内容中的[表情]
			$pattern='/\[(.*)\] /';
			preg_match($pattern,$content,$matches);
			if ($matches){
				$icon=split(' ',$matches[0]);
				foreach ($icon as $item){//循环将话题替代成话题连接
					if (!empty($item)){
						$ic=$this->IconsModel->getModel("code='".$item."'");
						if ($ic){
							$content=str_replace($item,'<img src="/uploadfile/images/faces/'.$ic['name'].'" style="width:25px;height:25px;display:inline;">',$content);
						}
					}
				}
			}
			//添加帖子
			$dataArray=array(
				'circle_id'=>$circle_id,
				'content'=>ltrim($content),
				'allow_reply'=>$allow_reply,
				'create_id'=>$member['id'],
				'contains_pic'=>$picnum?'1':'0',
				'province'=>$province,
				'city'=>$city,
				'district'=>$district,
				'street'=>$street,
				'create_time'=>now()
			);
			$flag=$this->PostModel->add($dataArray);
			if ($flag){
				if ($picnum){
					//上传图片
					for ($i=0;$i<$picnum;$i++){//循环上传图片
						//保存图片
						$img = str_replace ( 'data:image/png;base64,', '',$_POST['image_data'.$i]);
						$img = str_replace ( ' ', '+', $img );
						$data = base64_decode ( $img );
						$path = "../uploadfile/images/topic/";
						$fileName = uniqid () . '.png';
						$file = $path . $fileName;
						$success = file_put_contents ( $file, $data );
						if ($success) {
							/**原图上传完成，生成最大高度/宽度76的小图**/
							$t=new ThumbHandler();
							// 生成缩略图
							$t->setSrcImg($file);
							$t->setCutType(1);
							$t->setDstImg($path."thumb/".$fileName);
							// 指定固定宽高
							$t->createImg(152,152);
							$dataArray=array(
								'post_id'=>$flag,
								'name'=>'/uploadfile/images/topic/'.$fileName,
								'thumb'=>'/uploadfile/images/topic/thumb/'.$fileName,
								'create_time'=>now()
							);
							$this->PostImageModel->add($dataArray);
						}
					}
				}
				//查询用户是否完成新手任务
				$task=$this->TaskModel->getModel("name='新手任务' AND remark='首次发表一个话题' AND create_id=".$member['id']);
				if (!$task){
					$dataArray=array(
						'name'=>'新手任务',
						'remark'=>'首次发表一个话题',
						'create_id'=>$member['id'],
						'create_time'=>now()
					);
					$this->TaskModel->add($dataArray);//新增任务表信息
					$dataArray=array(
						'point_type'=>1,//1.积分 0.消费
						'points'=>20,
						'remark'=>"完成新手任务，首次发表一个话题",
						'ip'=>GetIP(),
						'create_id'=>$member['id'],
						'create_time'=>now()
					);
					$this->PointRecordsModel->add($dataArray);//新增积分记录表
					$this->MemberModel->edit(array('points'=>$member['points']+20), 'id='.$member['id']);//更新用户积分
				}else{
					//查询用户是否完成今日任务
					$task=$this->TaskModel->getModel("(name='每日奖励' AND remark='发表一个话题' AND FROM_UNIXTIME(create_time,'%Y-%m-%d') = '".date("Y-m-d",now())."' AND create_id=".$member['id'].") OR (name='新手任务' AND remark='首次发表一个话题' AND FROM_UNIXTIME(create_time,'%Y-%m-%d') = '".date("Y-m-d",now())."' AND create_id=".$member['id'].")");
					if (!$task){
						$dataArray=array(
							'name'=>'每日奖励',
							'remark'=>'发表一个话题',
							'create_id'=>$member['id'],
							'create_time'=>now()
						);
						$this->TaskModel->add($dataArray);//新增任务表信息
						$dataArray=array(
							'point_type'=>1,//1.积分 0.消费
							'points'=>10,
							'remark'=>"完成新手任务，首次发表一个话题",
							'ip'=>GetIP(),
							'create_id'=>$member['id'],
							'create_time'=>now()
						);
						$this->PointRecordsModel->add($dataArray);
						$this->MemberModel->edit(array('points'=>$member['points']+10), 'id='.$member['id']);
					}
				}
				if ($relation){
					foreach ($relation as $rela){
						//发送短消息
						$dataArray=array(
							'parent_id'=>0,
							'sub_id'=>$flag,
							'sub_type'=>1,//1.帖子 2.照片
							'message_type'=>1,//系统消息
							'member_id'=>$rela['id'],
							'content'=>'我在帖子中呼唤你，速速回应~',
							'is_read'=>0,
							'create_id'=>$member['id'],
							'create_time'=>now()
						);
						$re=$this->MessageModel->add($dataArray);
						//更新留言数据
						$this->MemberModel->edit(array('mess_num'=>(int)$rela['mess_num']+1), 'id='.$rela['id']);
					}
				}
				//更新圈子话题数量
				$this->CircleModel->edit(array('topic_num'=>(int)$circle['topic_num']+1), 'id='.$circle_id);
				//更新话题数量
				$this->MemberModel->edit(array('topic_num'=>(int)$member['topic_num']+1), 'id='.$member['id']);
				show_error('index.php/EditorPost/index',500,'提示信息：帖子发表成功！');
			}else{
				show_error('index.php/EditorPost/add',500,'提示信息：帖子发表失败！');
			}
		}else{
			$user=$this->user;//登录用户
			$role=$this->RoleModel->getRole('id='.$user['role_id']);//登录用户角色
			if ($role){
				$icons=$this->IconsModel->GetList('1=1');//表情数据
				$sis=$this->MemberModel->getList('');
				$circle=$this->CircleModel->getList('pid<>0');//所有圈子数据
				$strWhere='';
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
				$author=$this->MemberModel->getList($strWhere);
				$this->load->view('EditorPost/add',array('circle'=>$circle,'icons'=>$icons,'sis'=>$sis,'author'=>$author));
			}else{
				show_error('index.php/Index/index',500,'提示信息：你所在的用户组状态异常！');
			}
		}
	}
}
