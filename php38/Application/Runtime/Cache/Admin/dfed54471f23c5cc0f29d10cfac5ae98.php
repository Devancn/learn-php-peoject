<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - <?php echo $_page_title;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
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


<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/index.php/Admin/Category/edit/id/1/p/1.html" method="post">
            <input type="hidden" name="id" value="<?php echo I('get.id');?>">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">上级分类：</td>
                    <td>
                        <select name="parent_id" >
                            <option value="0">顶级分类</option>
                            <?php foreach ($catData as $k => $v): if($info['id'] == $v['id'] || in_array($v['id'],$children)){ continue; } if($info['parent_id'] == $v['id']){ $select = 'selected="selected"'; }else{ $select = ''; } ?>
                                <option <?php echo $select;?> value="<?php echo $v['id']?>"><?php echo str_repeat('-',$v['level']*8).$v['cat_name'];?></option>
                            <?php endforeach;?>
                        </select>
                        <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">分类名称：</td>
                    <td><input type="text" name="cat_name" value="<?php echo $info['cat_name'];?>"size="30" />
                        <span class="require-field">*</span></td>
                </tr>
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>
<script>
    UM.getEditor('goods_desc',{
        initialFrameWidth:'50%',
        initialFrameHeight:200
    });
</script>
<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>