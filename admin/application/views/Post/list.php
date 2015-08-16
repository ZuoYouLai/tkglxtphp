<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit" />
    <title>居俪网后台管理-后台管理</title>
    <base href="<?php echo base_url(); ?>" />
    <link type="text/css" rel="stylesheet" href="statics/css/pintuer.css" />
    <link type="text/css" rel="stylesheet" href="statics/css/admin.css" />
    <script type="text/javascript" src="statics/js/jquery.js"></script>
    <script type="text/javascript" src="statics/js/pintuer.js"></script>
    <script type="text/javascript" src="statics/js/respond.js"></script>
  	<script type="text/javascript" src="statics/plugin/layer-v1.8.2/layer.min.js"></script>
    <script type="text/javascript">
       	//单个推荐
       	function rec(id){
            layer.confirm('推荐后帖子将出现在推荐列表，确定要这么做吗？', function(){ 
            	location.href="index.php/Post/operate?cmd=rec&ids="+id;
    		},'提示信息',function(index){
    			layer.close(index); 
        	});
       	}
       	//单个回收
       	function rubbish(id){
            layer.confirm('删除后帖子将出现在回收站，确定要这么做吗？', function(){ 
    			location.href="index.php/Post/operate?cmd=rubbish&ids="+id;
    		},'提示信息',function(index){
    			layer.close(index); 
        	});
       	}
    	//批量屏蔽
    	function pb_all(){
			var ids="";
			$('input[name="id"]:checked').each(function(){
        		if(ids!=''){
        			ids+=",";
                }	
        		ids+=$(this).val();
			});
            if(ids==''){
            	layer.msg('请选择屏蔽项！');
            	return false;
           	}
            layer.confirm('屏蔽后帖子将不会在前台显示，确定要这么做吗？', function(){ 
            	location.href="index.php/Post/operate?cmd=pingbi&ids="+ids;
    		},'提示信息',function(index){
    			layer.close(index); 
        	});
       	}       	
       	//批量推荐
       	function rec_all(){
       		var ids="";
			$('input[name="id"]:checked').each(function(){
        		if(ids!=''){
        			ids+=",";
                }	
        		ids+=$(this).val();
			});
            if(ids==''){
            	layer.msg('请选择屏蔽项！');
            	return false;
           	}
            layer.confirm('推荐后帖子将出现在推荐列表，确定要这么做吗？', function(){ 
            	location.href="index.php/Post/operate?cmd=rec&ids="+ids;
    		},'提示信息',function(index){
    			layer.close(index); 
        	});
       	}       	
       	//批量回收
       	function rubbish_all(){
       		var ids="";
			$('input[name="id"]:checked').each(function(){
        		if(ids!=''){
        			ids+=",";
                }	
        		ids+=$(this).val();
			});
            if(ids==''){
            	layer.msg('请选择删除项！');
            	return false;
           	}
            layer.confirm('删除后帖子将出现在回收站，确定要这么做吗？', function(){ 
    			location.href="index.php/Post/operate?cmd=rubbish&ids="+ids;
    		},'提示信息',function(index){
    			layer.close(index); 
        	});
       	}       	
    </script>
</head>
<body>
    <div class="admin">
        <form method="post">
        <div class="panel admin-panel">
            <div class="panel-head">
                <strong>帖子列表</strong></div>
            <div class="padding border-bottom">
                <input type="button" class="button button-small checkall" name="checkall" checkfor="id" value="全选" />
                <input type="button" class="button button-small border-green" onclick="pb_all();" value="批量屏蔽" />
                <input type="button" class="button button-small border-yellow" onclick="rec_all();" value="批量推荐" />
                <input type="button" class="button button-small border-red" onclick="rubbish_all();" value="批量删除" />
                <input type="button" class="button button-small border-blue" onclick="location.href='index.php/Post/rubbish';" value="回收站" />
            </div>
            <table class="table table-hover">
                <tr>
                    <th width="45">选择</th>
                    <th width="45">ID</th>
                    <th>内容</th>
                    <th width="100">发帖人</th>
                    <th width="100">访问数</th>
                    <th width="100">回复数</th>
                    <th width="100">收藏数</th>
                    <th width="100">允许回复</th>
                    <th width="100">是否推荐</th>
                    <th width="100">状态</th>
                    <th width="150">操作</th>
                </tr>
                <?php 
                if ($post){
                	foreach ($post as $item){
                		?>
                		<tr>
		                    <td>
		                        <input type="checkbox" name="id" value="<?php echo $item['id']; ?>" />
		                    </td>
		                    <td>
		                        <?php echo $item['id']; ?>
		                    </td>
		                    <td>
		                        <?php echo CutString(strip_tags($item['content']), 90); ?>...
		                    </td>
		                    <td>
		                        <?php echo $item['nick_name']; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['visit_num']; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['reply_num']; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['coll_num']; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['allow_reply']=='1'?'允许':'不允许'; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['is_elite']=='1'?'已推荐':'未推荐'; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['status']=='1'?'正常':'屏蔽'; ?>
		                    </td>
		                    <td>
		                    	<a class="button border-green button-little" href="index.php/Comment/add?action=Post&id=<?php echo $item['id']; ?>">回帖</a>
		                        <a class="button border-blue button-little" href="javascript:void(0);" onclick="rec(<?php echo $item['id']; ?>);">推荐</a> 
		                        <a class="button border-yellow button-little" href="javascript:void(0);" onclick="rubbish(<?php echo $item['id']; ?>);">删除</a>
		                    </td>
		                </tr>
                		<?php 
                	}
                }
                ?>
            </table>
            <div class="panel-foot text-center">
                <?php echo $page; ?>
            </div>
        </div>
        </form>
        <br />
        <p class="text-right text-gray">基于<a class="text-gray" target="_blank" href="#">居俪网</a>构建</p>
    </div>
</body>
</html>