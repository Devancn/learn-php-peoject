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

<!-- 时间插件 -->
<link href="/Public/datepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datepicker/datepicker-zh_cn.js"></script>


<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">基本信息</span>
            <span class="tab-back">商品描述</span>
            <span class="tab-back">会员价格</span>
            <span class="tab-back">商品属性</span>
            <span class="tab-back">商品相册</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/index.php/Admin/Goods/add.html" method="post">
            <!-- 基本信息 -->
            <table width="90%" class="table_form" align="center">
                <tr>
                    <td class="label">主分类：</td>
                    <td>
                        <select name="cat_id">
                            <option value="">选择分类</option>
                            <?php foreach ($catData as $k => $v): ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo str_repeat('-', $v['level']*8) . $v['cat_name']; ?></option>
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
                        </ul>

                    </td>
                </tr>
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value=""size="30" />
                        <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="0" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="0" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="是" checked="checked" /> 是
                        <input type="radio" name="is_on_sale" value="否" /> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">促销价格：</td>
                    <td>
                        开始时间：<input type="text" id="promote_start_date" name="promote_start_date" />
                        结束时间：<input type="text" id="promote_end_date" name="promote_end_date" />
                        促销价格：￥ <input type="text" name="promote_price" /> 元
                    </td>
                </tr>
                <tr>
                    <td class="label">是否推荐：</td>
                    <td>
                        新品 <input type="checkbox" name="is_new" value="是" />
                        热销 <input type="checkbox" name="is_hot" value="是" />
                        推荐 <input type="checkbox" name="is_rec" value="是" />
                        楼层 <input type="checkbox" name="is_floor" value="是" />
                    </td>
                </tr>
                <tr>
                    <td class="label">排序：</td>
                    <td>
                        <input type="text" name="sort_number" value="100" /> 越小越靠前
                    </td>
                </tr>
                <tr>
                    <td class="label">商品图片：</td>
                    <td>
                        <input type="file" name="logo" />
                    </td>
                </tr>
            </table>
            <!-- 商品描述 -->
            <table style="display:none;" width="100%" class="table_form" align="center">
                <tr>
                    <td>
                        <textarea id="goods_desc" name="goods_desc" cols="40" rows="3"></textarea>
                    </td>
                </tr>
            </table>
            <!-- 会员价格 -->
            <table style="display:none;" width="90%" class="table_form" align="center">
                <tr><td colspan="2" style="color:#F00;font-size:20px;font-weight:bold;padding:10px;">说明：如果指定了价格就使用这个价格，如果没有指定价格就按这个级别的折扣率来计算</td></tr>
                <?php foreach ($mlData as $k => $v): ?>
                <tr>
                    <td width="150"><?php echo $v['level_name']; ?>【<?php echo $v['level_rate']/10; ?>折】 ：</td>
                    <td>
                        ￥<input type="text" name="member_price[]" />元
                        <input type="hidden" name="level_id[]" value="<?php echo $v['id']; ?>" />
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <!-- 商品属性 -->
            <table style="display:none;" width="90%" class="table_form" align="center">
                <tr>
                    <td>商品类型：<?php buildSelect('type', 'type_id', 'type_name'); ?></td>
                </tr>
                <tr><td><ul id="attr_list"></ul></td></tr>
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
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<script>
    // 时间插件
    $("#promote_start_date").datepicker({ dateFormat: "yy-mm-dd" });
    $("#promote_end_date").datepicker({ dateFormat: "yy-mm-dd" });

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
            newLi.find("a").text('[-]');
            li.after(newLi);
        }
        else
            li.remove();
    }
</script>




















<div id="footer">
    共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>