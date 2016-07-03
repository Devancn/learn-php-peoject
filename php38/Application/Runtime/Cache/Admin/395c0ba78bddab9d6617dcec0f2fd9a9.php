<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - <?php echo $_page_title;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
    <script src="/Public/Admin/Js/jquery-1.7.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $_page_btn_link;?>"><?php echo $_page_btn_name;?></a>
    </span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title;?> </span>
    <div style="clear:both"></div>
</h1>
<!--内容放的位置-->
<!--//导入layout.html-->


<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>id</th>
                <th>分类名称</th>
                <th>操作</th>
            </tr>
            <?php foreach ($data as $k => $v): ?>
            <tr>
                <td><?php echo $v['id']; ?></td>
                <td><?php echo str_repeat('-',$v['level']*8).$v['cat_name']; ?></td>
                <td align="center">
	                <a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p',1)); ?>" title="编辑">编辑</a> |
	                <a onclick="return confirm('确定要删除吗？');" href="<?php echo U('delete?id='.$v['id']); ?>" title="移除">移除</a> 
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</form>

<script>
$("#ft").datepicker({ dateFormat: "yy-mm-dd" });
$("#et").datepicker({ dateFormat: "yy-mm-dd" });
</script>
<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>