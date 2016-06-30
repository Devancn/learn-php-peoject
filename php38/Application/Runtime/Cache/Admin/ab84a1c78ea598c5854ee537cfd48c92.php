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


<div class="main-div">
    <form name="main_form" method="POST" action="/index.php/Admin/Role/add.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">角色名称：</td>
                <td>
                    <input  type="text" name="role_name" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">权限列表：</td>
                <ul>
                    <?php foreach ($priData as $k => $v): ?>
                    <li>
                        <?php echo str_repeat('-', $v['level']*8); ?><input level="<?php echo $v['level']; ?>" value="<?php echo $v['id']; ?>" type="checkbox" name="pri_id[]" /> <a level="<?php echo $v['level']; ?>" href="javascript:void(0);">[-]</a> <?php echo $v['pri_name']; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $(":checkbox").click(function(){
        // 所在的li
        var li = $(this).parent();
        // 先取当前的level
        var level = $(this).attr("level");
        // 判断是选中还是取消
        if($(this).attr("checked"))
        {
            // 循环后面的每一个
            li.nextAll("li").each(function(k,v){
                // 判断如果level大就说明是子权限
                if($(v).find(":checkbox").attr("level") > level)
                    $(v).find(":checkbox").attr("checked", "checked");
                else
                    return false;  // 退出循环，后面的不用再判断了
            });
            var tmp_level = level;  // 复制一个level值
            // 循环前面的每一个
            li.prevAll("li").each(function(k,v){
                // 判断如果level大就说明是子权限
                if($(v).find(":checkbox").attr("level") < tmp_level)
                {
                    $(v).find(":checkbox").attr("checked", "checked");
                    tmp_level--;   // 每找到一个上级就再向前提一级
                }
            });
        }
        else
        {
            li.nextAll("li").each(function(k,v){
                // 判断如果level大就说明是子权限
                if($(v).find(":checkbox").attr("level") > level)
                    $(v).find(":checkbox").removeAttr("checked");
                else
                    return false;  // 退出循环，后面的不用再判断了
            });
        }
    });

    $("a").click(function(){
        var li = $(this).parent();
        // 获取是第几级的
        var level = $(this).attr("level");
        if($(this).text() == '[+]')
        {
            $(this).text('[-]');
            li.nextAll("li").each(function(k,v){
                // 判断如果level大就说明是子权限
                if($(v).find(":checkbox").attr("level") > level)
                {
                    $(v).find("a").text('[-]');
                    $(v).show();
                }
                else
                    return false;  // 退出循环，后面的不用再判断了
            });
        }
        else
        {
            $(this).text('[+]');
            // 把所有的子权限折叠
            li.nextAll("li").each(function(k,v){
                // 判断如果level大就说明是子权限
                if($(v).find(":checkbox").attr("level") > level)
                    $(v).hide();
                else
                    return false;  // 退出循环，后面的不用再判断了
            });
        }
    });
</script>

<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>