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
</head>
<body>
    <div class="admin">
        <form method="post">
        <div class="panel admin-panel">
            <div class="panel-head">
                <strong>专题列表</strong></div>
            <div class="padding border-bottom">
                <input type="button" class="button button-small checkall" name="checkall" checkfor="id" value="全选" />
                <input type="button" class="button button-small border-green" value="添加产品" onclick="location.href='index.php/Product/add';" />
                <input type="button" class="button button-small border-yellow" value="批量删除" />
                <input type="button" class="button button-small border-blue" value="回收站" />
            </div>
            <table class="table table-hover">
                <tr>
                    <th width="45">
                        选择
                    </th>
                    <th width="120">
                        ID
                    </th>
                    <th width="200">
                       标题
                    </th>
                    <th width="100">
                        外链
                    </th>
                    <th width="100">
                        发帖人
                    </th>
                    <th width="100">
                        浏览量
                    </th>
                   	<th width="100">
                        回复量
                    </th>
                 	<th width="100">
                        点赞数
                    </th>
                   	<th width="100">
                        状态
                    </th>
                    <th width="100">
                        操作
                    </th>
                </tr>
                <?php 
                if ($famous){
                	foreach ($famous as $item){
                		?>
                		<tr>
		                    <td>
		                        <input type="checkbox" name="id" value="<?php echo $item['id']; ?>" />
		                    </td>
		                    <td>
		                        <?php echo $item['id']; ?>
		                    </td>
		                    <td>
		                        <?php echo $item['title']; ?>
		                    </td>
		                    <td>
		                        <a href="<?php echo 'http://jooli.uenet.cn/index.php?famous&cmd=index&id='.$item['id']; ?>" target="_blank"><?php echo 'http://jooli.uenet.cn/index.php?famous&cmd=index&id='.$item['id']; ?></a>
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
				                <?php echo $item['status']=='1'?'已发布':'草稿'; ?>
				            </td>
		                    <td>
		                        <a class="button border-blue button-little" href="index.php/Famous/edit?id=<?php echo $item['id']; ?>">修改</a> 
		                        <a class="button border-yellow button-little" href="#" onclick="{if(confirm('确认删除?')){return true;}return false;}">删除</a>
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