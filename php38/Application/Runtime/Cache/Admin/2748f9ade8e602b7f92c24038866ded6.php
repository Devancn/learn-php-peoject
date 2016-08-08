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


<form method="post" action="/index.php/Admin/Goods/goods_number/id/52/p/2.html" name="listForm">
	<div class="list-div" id="listDiv">
		<table cellpadding="3" cellspacing="1">
			<tr>
				<?php foreach ($gaData as $k => $v): ?>
				<th><?php echo $k; ?></th>
				<?php endforeach; ?>
				<th width="150">库存量</th>
				<th width="60">操作</th>
			</tr>
			<?php
 if($gnData): ?>
			<?php foreach ($gnData as $k0 => $v0): ?>
			<tr>
				<?php foreach ($gaData as $k => $v): ?>
				<td>
					<select name="goods_attr_id[]">
						<option value="">请选择</option>
						<?php foreach ($v as $k1 => $v1): if(strpos(','.$v0['attr_list'].',', ','.$v1['id'].',') !== FALSE) $select= 'selected="selected"'; else $select = ''; ?>
						<option <?php echo $select; ?> value="<?php echo $v1['id']; ?>"><?php echo $v1['attr_value']; ?>
						<?php endforeach; ?>
					</select>
				</td>
				<?php endforeach; ?>
				<td><input type="text" name="gn[]" value="<?php echo $v0['goods_number']; ?>" /></td>
				<td>
					<?php if($gaData): ?>
					<input onclick="addTr(this);" type="button" value="<?php echo $k0==0?'+':'-'; ?>" />
					<?php endif; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<?php foreach ($gaData as $k => $v): ?>
				<td>
					<select name="goods_attr_id[]">
						<option value="">请选择</option>
						<?php foreach ($v as $k1 => $v1): ?>
						<option value="<?php echo $v1['id']; ?>"><?php echo $v1['attr_value']; ?>
							<?php endforeach; ?>
					</select>
				</td>
				<?php endforeach; ?>
				<td><input type="text" name="gn[]" value="" /></td>
				<td>
					<?php if($gaData): ?>
					<input onclick="addTr(this);" type="button" value="+" />
					<?php endif; ?>
				</td>
			</tr>
			<?php endif; ?>
		</table>
		<table>
			<?php $attrCount = count($gaData); ?>
			<tr>
				<td align="center" colspan="<?php echo $attrCount+2; ?>"><input type="submit" value="保存" /></td>
			</tr>
		</table>
	</div>
</form>
<script>
	function addTr(btn)
	{
		var tr = $(btn).parent().parent();
		if($(btn).val() == '+')
		{
			var newTr = tr.clone();
			newTr.find(":button").val('-');
			$("table:first").append(newTr);
		}
		else
			tr.remove();
	}
</script>
<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>