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
    	//屏蔽话题
    	function pb(){
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
            layer.confirm('屏蔽后评论将不会在前台显示，确定要这么做吗？', function(){ 
            	location.href="index.php/Comment/operate?cmd=pingbi&ids="+ids;
    		},'提示信息',function(index){
    			layer.close(index); 
        	});
       	}
       	//回收站
       	function rubbish(id){
            layer.confirm('删除后评论将出现在回收站，确定要这么做吗？', function(){ 
    			location.href="index.php/Comment/operate?cmd=rubbish&ids="+id;
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
                <strong>评论列表</strong></div>
            <div class="padding border-bottom">
                <input type="button" class="button button-small checkall" name="checkall" checkfor="id" value="全选" />
                <input type="button" class="button button-small border-green" onclick="pb();" value="批量屏蔽" />
                <input type="button" class="button button-small border-yellow" value="批量删除" />
                <input type="button" class="button button-small border-blue" value="回收站" />
            </div>
            <table class="table table-hover">
                <tr>
                    <th width="45">选择</th>
                    <th width="45">ID</th>
                    <th>内容</th>
                    <th>评论人</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php 
                if ($comment){
                	foreach ($comment as $item){
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
		                        <?php echo $item['status']=='1'?'正常':'屏蔽'; ?>
		                    </td>
		                    <td>
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