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

<!-- 时间插件 -->
<link href="/Public/datepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="/Public/datepicker/jquery-1.7.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/datepicker-zh_cn.js"></script>
<!--搜索-->
<div class="form-div">
    <form method="GET" action="<?php echo U('lst'); ?>" name="searchForm">
	    <img src="/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        商品分类:
        <?php $catId = I('get.cat_id');?>
        <select name="cat_id" >
            <option value="">选择分类</option>
            <?php foreach ($cateData as $k => $v): if($catId == $v['id']){ $select = 'selected="selected"'; }else{ $select=""; } ?>
            <option <?php echo $select;?> value="<?php echo $v['id']?>"><?php echo str_repeat('-',$v['level']*8).$v['cat_name'];?></option>
            <?php endforeach;?>
        </select>
	    商品名称：
	    <input type="text" name="gn" size="15" value="<?php echo I('get.gn'); ?>" />
	    本店价格：
	    	从<input type="text" name="fp" size="15" value="<?php echo I('get.fp'); ?>" />
	    	到<input type="text" name="tp" size="15" value="<?php echo I('get.tp'); ?>" />
	    是否上架：
	    	<?php $ios = I('get.ios', '0'); ?>
	    	<input type="radio" name="ios" value="0" <?php if($ios=='0') echo 'checked="checked"'; ?> /> 全部
	    	<input type="radio" name="ios" value="是" <?php if($ios=='是') echo 'checked="checked"'; ?> /> 是
	    	<input type="radio" name="ios" value="否" <?php if($ios=='否') echo 'checked="checked"'; ?> /> 否
	    添加时间：
	    	从<input id="ft" type="text" name="ft" size="15" value="<?php echo I('get.ft'); ?>" />
	    	到<input id="et" type="text" name="et" size="15" value="<?php echo I('get.et'); ?>" />
	    <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>id</th>
                <th>主分类</th>
                <th>扩展分类</th>
                <th>logo</th>
                <th>商品名称</th>
                <th>市场价格</th>
                <th>本店价格</th>
                <th>是否上架</th>
                <th>添加时间</th>
                <th>库存量<th>
                <th>操作</th>
            </tr>
            <?php
 $priModel=D('Privilege'); foreach ($data as $k => $v): ?>
            <tr>
                <td><?php echo $v['id']; ?></td>
                <td><?php echo $v['cat_name'];?></td>
                <td><?php echo $v['ext_cat_name'];?></td>
                <td><img src="/Public/Uploads/<?php echo $v['sm_logo']; ?>" /></td>
                <td><?php echo $v['goods_name']; ?></td>
                <td><?php echo $v['market_price']; ?></td>
                <td><?php echo $v['shop_price']; ?></td>
                <td><?php echo $v['is_on_sale']; ?></td>
                <td><?php echo date('Y-m-d H:i:s', $v['addtime']); ?></td>
                <td><?php echo (int)$v['gn'];?></td>
                <td align="center">
                    <?php if($priModel->hasPriTOEditGoods($v['id'])):?>
	                <a href="<?php echo U('goods_number?id='.$v['id'].'&p='.I('get.p',1)); ?>" title="库存">库存</a> |
	                <a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p',1)); ?>" title="编辑">编辑</a> |
	                <a onclick="return confirm('确定要删除吗？');" href="<?php echo U('delete?id='.$v['id']); ?>" title="移除">移除</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td align="right" nowrap="true" colspan="8">
                    <div id="turn-page">
                        <?php echo $page; ?>
                    </div>
                </td>
            </tr>
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