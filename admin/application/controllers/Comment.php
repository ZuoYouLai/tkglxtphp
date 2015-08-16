<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comment extends My_Controller {
	/**
	 * 构造函数
	 * Enter description here ...
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('PostModel');
		$this->load->model('PostImageModel');
		$this->load->model('CommentModel');
		$this->load->model('MemberModel');
		$this->load->model('IconsModel');
		$this->load->model('TaskModel');
		$this->load->library('Page');
		$this->load->library('ThumbHandler');
		$this->load->model('PointRecordsModel');
		$this->load->model('MessageModel');
		$this->load->helper('string');
	}
	/**
	 * 默认函数
	 * Enter description here ...
	 */
	public function index()
	{
		$pageSize=12;
		$pageIndex=$this->input->get('page')?$this->input->get('page'):1;
		$strWhere='a.is_del<>1';
		$orderBy='';
		$totalCount=$this->CommentModel->getListCountByWhere($strWhere);
		$comment=$this->CommentModel->getListByPage($pageSize,$pageIndex,$strWhere,$orderBy);
		$page=new Page();
		$str_page=$page->create($pageIndex, $pageSize, $totalCount, array(), array());
		$data=array(
			'page'=>$str_page,
			'comment'=>$comment
		);
		$this->load->view('Comment/list',$data);
	}
	/**
	 * 添加评论/回复
	 * Enter description here ...
	 */
	public function add(){
		$cmd=$this->input->post('cmd');
		if ($cmd&&$cmd=='submit') {
			/*接收到的参数*/
			$parent_id=$this->input->post('pid');//上级ID，例如：回复的上级ID为回复对象的ID
			$sub_id=$this->input->post('sub_id');//评论/回复对象ID
			$create_id=$this->input->post('create_id');//评论人
			$content=$_POST['content'];//评论/回复内容
			$image=$_POST['image_data'];//评论内容的图片数据
			$action=$this->input->post('action');
			$post=$this->PostModel->getModel('id='.$sub_id);
			$creater=$this->MemberModel->getModel('id='.$create_id);
			if ($post&&$content&&$creater){
				if ($image){
					//保存图片
					$img = str_replace ( 'data:image/png;base64,', '', $image );
					$img = str_replace ( ' ', '+', $img );
					$data = base64_decode ( $img );
					$path = "../uploadfile/images/comment/";
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
						$image=$fileName;
					}
				}
				//匹配内容中的话题
				$pattern='/#(.*)#/';
				preg_match($pattern,$content,$matches);
				if ($matches){
					$topic=split(' ',$matches[0]);
					foreach ($topic as $item){//循环将话题替代成话题连接
						if (!empty($item)){
							$content=str_replace($item,'<a style="display:inline;" href="index.php?post&cmd=detail_topic&keyword='.rtrim(substr($item,1),'#').'">'.$item.'</a>',$content);
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
				if($matches){
					$icon=split(' ',$matches[0]);
					foreach ($icon as $item){//循环将话题替代成话题连接
						if (!empty($item)){
							$ic=$this->IconsModel->getModel("code='".$item."'");
							if ($ic){
								$content=str_replace($item,'<img src="'.$ic['name'].'" style="width:25px;height:25px;display:inline;">',$content);
							}
						}
					}
				}

				$dataArray=array(
					'parent_id'=>$parent_id,
					'sub_id'=>$post['id'],
					'sub_type'=>1,
					'content'=>$content,
					'content_pic'=>$image?"/uploadfile/images/comment/".$image:"",
					'thumb'=>$image?"/uploadfile/images/comment/thumb/".$image:"",
					'create_id'=>$creater['id'],
					'create_time'=>now()
				);
				$flag=$this->CommentModel->add($dataArray);
				if ($flag){
					if ($parent_id=='0'){
						$this->PostModel->edit(array('reply_num'=>(int)$post['reply_num']+1), 'id='.$post['id']);
					}
					//查询用户是否完成新手任务
					$task=$this->TaskModel->getModel("name='新手任务' AND remark='首次回复一个话题' AND create_id=".$creater['id']);
					if (!$task){
						$dataArray=array(
							'name'=>'新手任务',
							'remark'=>'首次回复一个话题',
							'create_id'=>$creater['id'],
							'create_time'=>now()
						);
						$flag=$this->TaskModel->Add($dataArray);
						if ($flag){
							$dataArray=array(
								'point_type'=>1,//1.积分 0.消费
								'points'=>20,
								'remark'=>"完成新手任务，首次回复一个话题",
								'ip'=>GetIP(),
								'create_id'=>$creater['id'],
								'create_time'=>now()
							);
							$r=$this->PointRecordsModel->add($dataArray);
							if ($r){
								$this->MemberModel->edit(array('points'=>$creater['points']+20), 'id='.$creater['id']);
							}
						}
					}else{
						//查询用户是否完成今日任务
						$task=$this->TaskModel->getModel("(name='每日奖励' AND remark='回复一个话题' AND FROM_UNIXTIME(create_time,'%Y-%m-%d') = '".date("Y-m-d",now())."' AND create_id=".$creater['id'].") OR (name='新手任务' AND remark='首次回复一个话题' AND FROM_UNIXTIME(create_time,'%Y-%m-%d') = '".date("Y-m-d",now())."' AND create_id=".$creater['id'].")");
						if (!$task){
							$dataArray=array(
								'name'=>'每日奖励',
								'remark'=>'回复一个话题',
								'create_id'=>$creater['id'],
								'create_time'=>now()
							);
							$flag=$this->TaskModel->add($dataArray);
							if ($flag){
								$dataArray=array(
									'point_type'=>1,//1.积分 0.消费
									'points'=>5,
									'remark'=>"完成新手任务，首次发表一个话题",
									'ip'=>GetIP(),
									'create_id'=>$creater['id'],
									'create_time'=>now()
								);
								$r=$this->PointRecordsModel->add($dataArray);
								if ($r){
									$this->MemberModel->edit(array('points'=>$creater['points']+5), 'id='.$creater['id']);
								}
							}
						}
					}

					if ($relation){
						foreach ($relation as $rela){
							//发送短消息
							$dataArray=array(
								'parent_id'=>$parent_id,
								'sub_id'=>$post['id'],
								'sub_type'=>1,//1.帖子 2.照片 3.名媛专题
								'message_type'=>1,//系统消息
								'member_id'=>$rela['id'],
								'content'=>$parent_id=='0'?'我在评论中呼唤你，速速回应~':'我在回复中呼唤你，速速回应~',
								'is_read'=>0,
								'create_id'=>$creater['id'],
								'create_time'=>now()
							);
							$re=$this->MessageModel->add($dataArray);
						}
					}
					show_error('index.php/Comment/add?id='.$post['id'],500,'提示信息：评论/回复发表成功！');
				}else{
					show_error('index.php/Comment/add?id='.$post['id'],500,'提示信息：评论/回复发表失败！');
				}
			}else{
				show_error('index.php/'.$action.'/index',500,'提示信息：帖子/回复内容/回复人不存在！');
			}
		}else{
			$action='EditorPost';
			if ($this->input->get('action')){
				$action=$this->input->get('action');
			}
			$id=$this->input->get('id');
			if($id){
				$post=$this->PostModel->getModel('id='.$id);
				if ($post){
					$creater=$this->MemberModel->getModel('id='.$post['create_id']);
					$comments=$this->CommentModel->getList('sub_id='.$post['id'].' AND sub_type=1 AND status=1 AND is_del=0 ORDER BY id DESC');//帖子的所有评论及回复
					$author=$this->MemberModel->getList('create_id<>0');//所有系统会员作为评论人
					$icons=$this->IconsModel->GetList('1=1');//表情数据
					$sis=$this->MemberModel->getList('');//	可以@整站会员
					$result=array();//评论/回复集合
					if ($comments){
						$i=1;
						foreach ($comments as $comment){
							if ($comment['parent_id']=='0'){
								$comment['reply']=null;
								$comment['creater']=null;
								$comment['member']=$this->MemberModel->getModel('id='.$comment['create_id']);
								$comment['sort']=$i;
								$result[]=$comment;
							}else{
								//查询该条该回复的上一条评论
								$comms=$this->CommentModel->GetList('id='.$comment['parent_id'].' AND status=1 AND is_del=0 AND sub_type=1 AND sub_id='.$post['id'].' ORDER BY id DESC');
								if ($comms){//有回复的评论
									foreach ($comms as $comm){
										$comm['reply']=$comment;
										$comm['creater']=$this->MemberModel->getModel('id='.$comment['create_id']);//回复人
										$comm['member']=$this->MemberModel->getModel('id='.$comm['create_id']);//回复对象
										$comm['sort']=$i;
										$result[]=$comm;
									}
								}
							}
							$i++;
						}
					}
					$post_image=array();
					if ($post['contains_pic']){
						$post_image=$this->PostImageModel->getList('post_id='.$post['id'].' LIMIT 9');
					}
					$this->load->view('Comment/add',array('author'=>$author,'action'=>$action,'post'=>$post,'sis'=>$sis,'icons'=>$icons,'creater'=>$creater,'comments'=>$result,'post_image'=>$post_image));
				}else{
					show_error('index.php/'.$action.'/index',500,'提示信息：您操作的帖子不存在或已被删除！');
				}
			}else{
				show_error('index.php/'.$action.'/index',500,'提示信息：参数错误！');
			}
		}
	}
	/**
	 * 操作函数
	 * Enter description here ...
	 */
	public function operate(){
		$cmd=$this->input->get('cmd');
		$ids=$this->input->get('ids');
		switch ($cmd){
			case "pingbi":
				$flag=$this->CommentModel->edit(array('status'=>0),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Comment/index',500,'提示信息：帖子屏蔽成功！');
				}else{
					show_error('index.php/Comment/index',500,'提示信息：帖子屏蔽失败！');
				}
				break;
			case "rec":
				$flag=$this->CommentModel->edit(array('is_elite'=>1),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Comment/index',500,'提示信息：帖子推荐成功！');
				}else{
					show_error('index.php/Comment/index',500,'提示信息：帖子推荐失败！');
				}
				break;
			case "del":
				$flag=$this->CommentModel->del('id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Comment/index',500,'提示信息：帖子删除成功！');
				}else{
					show_error('index.php/Comment/index',500,'提示信息：帖子删除失败！');
				}
				break;
			case "rubbish":
				$flag=$this->CommentModel->edit(array('is_del'=>1),'id IN ('.$ids.')');
				if ($flag){
					show_error('index.php/Comment/index',500,'提示信息：帖子删除成功！');
				}else{
					show_error('index.php/Comment/index',500,'提示信息：帖子删除失败！');
				}
				break;
		}
	}
}
