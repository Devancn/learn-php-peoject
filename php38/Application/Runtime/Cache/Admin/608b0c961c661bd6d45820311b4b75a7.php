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


<!-- 在线编辑器  um -->
<link href="/Public/umeditor1_2_2-utf8-php/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/lang/zh-cn/zh-cn.js"></script>

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">基本信息</span>
            <!--<span class="tab-back">会员价格</span>-->
            <!--<span class="tab-back">商品属性</span>-->
            <span class="tab-back">商品相册</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/index.php/Admin/Goods/edit/id/40/p/1.html" method="post">
            <input type="hidden" name="id" value="<?php echo I('get.id'); ?>" />
            <!-- 基本信息 -->
            <table width="90%" class="table_form" id="general-table" align="center">
                <tr>
                    <td class="label">主分类：</td>
                    <td>
                        <select name="cat_id">
                            <option value="">选择分类</option>
                            <?php foreach ($catData as $k => $v): if($info['cat_id'] == $v['id']) $select = 'selected="selected"'; else $select = ''; ?>
                            <option <?php echo $select; ?> value="<?php echo $v['id']; ?>"><?php echo str_repeat('-', $v['level']*8) . $v['cat_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">扩展分类：</td>
                    <td>
                        <ul>
                            <li style="margin:5px;">
                                <input onclick="add_li(this);" type="button" value="+" />
                                <select name="ext_cat_id[]">
                                    <option value="">选择分类</option>
                                    <?php foreach ($catData as $k => $v): ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo str_repeat('-', $v['level']*8) . $v['cat_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </li>
                            <!-- 循环输出原扩展分类 -->
                            <?php foreach ($gcData as $k1 => $v1): ?>
                            <li style="margin:5px;">
                                <input onclick="add_li(this);" type="button" value="-" />
                                <select name="ext_cat_id[]">
                                    <option value="">选择分类</option>
                                    <?php foreach ($catData as $k => $v): if($v1['cat_id'] == $v['id']) $select = 'selected="selected"'; else $select = ''; ?>
                                    <option <?php echo $select; ?> value="<?php echo $v['id']; ?>"><?php echo str_repeat('-', $v['level']*8) . $v['cat_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value="<?php echo $info['goods_name']; ?>" size="30" />
                        <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo $info['market_price']; ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo $info['shop_price']; ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="是" <?php if($info['is_on_sale'] == '是') echo 'checked="checked"'; ?> /> 是
                        <input type="radio" name="is_on_sale" value="否" <?php if($info['is_on_sale'] == '否') echo 'checked="checked"'; ?> /> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">商品图片：</td>
                    <td>
                        <input type="file" name="logo" /><br />
                        <img src="/Public/Uploads/<?php echo $info['sm_logo']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="label">商品描述：</td>
                    <td>
                        <textarea id="goods_desc" name="goods_desc" cols="40" rows="3"><?php echo $info['goods_desc']; ?></textarea>
                    </td>
                </tr>
            </table>

            <!-- 相册 -->
            <table style="display:none;" width="90%" class="table_form" align="center">
                <tr>
                    <td class="label"><input onclick="$('#pic_ul').append( $('#pic_ul').find('li:eq(0)').clone()  );" type="button" value="添加一张图片" /></td>
                    <td>
                        <ul id="pic_ul">
                            <li><input type="file" name="pic[]" /></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <style>
                            #old_pic_ul li{float:left;margin:5px;list-style-type:none;}
                        </style>
                        <ul id="old_pic_ul">
                            <?php foreach ($gpData as $k => $v): ?>
                            <li>
                                <img src="/Public/Uploads/<?php echo $v['sm_pic']?>" />
                                <br /><input pic_id="<?php echo $v['id']; ?>" type="button" value="删除" />
                            </li>
                            <?php endforeach; ?>
                        </ul>

                    </td>
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
    UM.getEditor('goods_desc', {
        initialFrameWidth : '100%',
        initialFrameHeight : 500
    });

    function add_li(btn)
    {
        if($(btn).val() == '+')
        {
            // 先获取所在的UL标签
            var ul = $(btn).parent().parent();
            // 把按钮所在的LI克隆一份放到UL中
            var newLi = $(btn).parent().clone();
            // 把+变-
            newLi.find(":button").val('-');
            ul.append(newLi);
        }
        else
            $(btn).parent().remove();
    }

    $("#tabbar-div p span").click(function(){
        // 点击的是第几个按钮
        var i = $(this).index();
        // 显示第i个table
        $(".table_form").hide().eq(i).show();
        $(".tab-front").removeClass("tab-front").addClass("tab-back");
        $(this).addClass("tab-front").removeClass("tab-back");
    });

    // 删除图片
    $("#old_pic_ul li :button").click(function(){
        if(confirm('确定要删除吗？'))
        {
            var btn = $(this);
            // 获取点击的图片的ID
            var pic_id = btn.attr("pic_id");
            // 发送AJAX请求到服务器上去删除服务器上的图片
            $.ajax({
                type : "GET",
                url : "<?php echo U('Goods/ajaxDelPic', '', FALSE); ?>/pic_id/"+pic_id,
                success : function(data)
                {
                    // 把图片从页面中删除掉
                    //$(this).parent().remove();	  // 这里的$(this)代表的是这个ajax对象并不是按钮
                    btn.parent().remove();
                    console.log(data);
                }
            });
        }
    });

    // 当选择一个类型时，取出这个类型下的属性
    $("select[name=type_id]").change(function(){
        // 获取当前类型的id
        var type_id = $(this).val();
        // 根据type_id取出属性
        $.ajax({
            type : "GET",
            url : "<?php echo U('Goods/ajaxGetAttr', '', FALSE); ?>/type_id/"+type_id,
            dataType : "json",
            success : function(data)
            {
                // 把数据显示在页面中
                var html = "";
                // 循环服务器返回的属性数据拼成LI标签
                $(data).each(function(k,v){
                    html += "<li>";
                    html += '<input type="hidden" name="attr_id[]" value="'+v.id+'" />';
                    if(v.attr_type == "可选")
                        html += '<a onclick="add_li(this);" href="javascript:void(0);">[+]</a> ';
                    html += v.attr_name+" : ";
                    // 如果属性没有可选值就制作文本框否则制作下拉框
                    if(v.attr_option_values == "")
                        html += '<input name="attr_value[]" type="text" />';
                    else
                    {
                        html += '<select name="attr_value[]"><option value="">请选择</option>';
                        // 把可选值转化成数组
                        var _arr = v.attr_option_values.split(",");
                        $(_arr).each(function(k1,v1){
                            html += '<option value="'+v1+'">'+v1+'</option>';
                        });
                        html += '</select>';
                    }
                    html += "</li>";
                });
                // 把LI放到页面中
                $("#attr_list").html(html);
            }
        });
    });
    // 属性前面的+号
    function add_li(a)
    {
        var li = $(a).parent();
        if($(a).text() == "[+]")
        {
            var newLi = li.clone();
            // 设置新添加的属性的名称
            newLi.find("select").attr('name', 'attr_value[]');
            newLi.find(":text").attr('name', 'attr_value[]');
            newLi.find(".attr_id").attr('name', 'attr_id[]');
            // 把新克隆出来的a标签上的goods_attr_id删除
            newLi.find("a").removeAttr("goods_attr_id");
            newLi.find("a").text('[-]');
            li.after(newLi);
        }
        else
        {
            // 从a标签上获取商品属性id
            var gaid = $(a).attr("goods_attr_id");
            // 是否新添加的直接删除
            if(gaid == undefined)
            {
                li.remove();
                return ; // 退出函数不再向后执行AJAX 了
            }
            if(confirm('确定要删除吗？'))
            {
                $.ajax({
                    type : "GET",
                    url : "<?php echo U('ajaxDelGoodsAttr', '', FALSE); ?>/goods_attr_id/"+gaid,
                    success : function(data)
                    {
                        if(data == 1)
                            alert("该属性已经设置了库存量数据不能删除！");
                        else
                            li.remove();
                    }
                });
            }
        }
    }
</script>

















<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>