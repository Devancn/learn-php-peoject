-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2016-10-09 07:05:01
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php38`
--

-- --------------------------------------------------------

--
-- 表的结构 `php38_admin`
--

CREATE TABLE `php38_admin` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `username` varchar(150) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `status` enum('正常','禁用') NOT NULL DEFAULT '正常' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员';

--
-- 转存表中的数据 `php38_admin`
--

INSERT INTO `php38_admin` (`id`, `username`, `password`, `status`) VALUES
(1, 'root', '99de37ebe3fc968924ff1d82dec33cd2', '正常');

-- --------------------------------------------------------

--
-- 表的结构 `php38_admin_goods_cat`
--

CREATE TABLE `php38_admin_goods_cat` (
  `admin_id` mediumint(8) UNSIGNED NOT NULL COMMENT '管理员id',
  `cat_id` mediumint(8) UNSIGNED NOT NULL COMMENT '分类id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员可以管理的分类表';

--
-- 转存表中的数据 `php38_admin_goods_cat`
--

INSERT INTO `php38_admin_goods_cat` (`admin_id`, `cat_id`) VALUES
(7, 14),
(7, 8),
(7, 6),
(7, 3),
(7, 19),
(7, 17),
(7, 16),
(7, 1),
(7, 14),
(7, 8),
(7, 6),
(7, 3),
(7, 19),
(7, 17),
(7, 16),
(7, 1);

-- --------------------------------------------------------

--
-- 表的结构 `php38_admin_role`
--

CREATE TABLE `php38_admin_role` (
  `admin_id` mediumint(8) UNSIGNED NOT NULL COMMENT '管理员id',
  `role_id` mediumint(8) UNSIGNED NOT NULL COMMENT '角色id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员所在角色';

--
-- 转存表中的数据 `php38_admin_role`
--

INSERT INTO `php38_admin_role` (`admin_id`, `role_id`) VALUES
(7, 1),
(7, 1);

-- --------------------------------------------------------

--
-- 表的结构 `php38_attribute`
--

CREATE TABLE `php38_attribute` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` enum('唯一','可选') NOT NULL COMMENT '属性类型',
  `attr_option_values` varchar(150) NOT NULL DEFAULT '' COMMENT '属性可选值',
  `type_id` tinyint(3) UNSIGNED NOT NULL COMMENT '类型id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性';

--
-- 转存表中的数据 `php38_attribute`
--

INSERT INTO `php38_attribute` (`id`, `attr_name`, `attr_type`, `attr_option_values`, `type_id`) VALUES
(1, '内存', '可选', '4g,8g,16G,32G', 2),
(2, '颜色', '可选', '白色,黑色,绿色,蓝色', 2),
(3, '作者', '唯一', '', 1),
(4, '出厂日期', '唯一', '', 2),
(5, 'CPU', '唯一', 'i5,i7', 2);

-- --------------------------------------------------------

--
-- 表的结构 `php38_category`
--

CREATE TABLE `php38_category` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `cat_name` varchar(150) NOT NULL COMMENT '分类名称',
  `parent_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID，0：代表顶级分类',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐到楼层'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类';

--
-- 转存表中的数据 `php38_category`
--

INSERT INTO `php38_category` (`id`, `cat_name`, `parent_id`, `is_floor`) VALUES
(1, '家用电器', 0, '是'),
(2, '手机、数码、京东通信', 0, '是'),
(3, '电脑、办公', 0, '否'),
(4, '家居、家具、家装、厨具', 0, '否'),
(5, '男装、女装、内衣、珠宝', 0, '否'),
(6, '个护化妆', 0, '否'),
(21, 'iphone', 2, '否'),
(8, '运动户外', 0, '否'),
(9, '汽车、汽车用品', 0, '否'),
(10, '母婴、玩具乐器', 0, '否'),
(11, '食品、酒类、生鲜、特产', 0, '否'),
(12, '营养保健', 0, '否'),
(13, '图书、音像、电子书', 0, '否'),
(14, '彩票、旅行、充值、票务', 0, '否'),
(15, '理财、众筹、白条、保险', 0, '否'),
(16, '大家电', 1, '否'),
(17, '生活电器', 1, '否'),
(18, '厨房电器', 1, '是'),
(19, '个护健康', 1, '否'),
(20, '五金家装', 1, '是'),
(22, '冰箱', 16, '否');

-- --------------------------------------------------------

--
-- 表的结构 `php38_goods`
--

CREATE TABLE `php38_goods` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `cat_id` mediumint(8) UNSIGNED NOT NULL COMMENT '主分类id',
  `goods_name` varchar(150) NOT NULL COMMENT '商品名称',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价格',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '图片',
  `sm_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '小图片',
  `mid_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '中图片',
  `goods_desc` longtext COMMENT '商品描述',
  `is_on_sale` enum('是','否') NOT NULL DEFAULT '是' COMMENT '是否上架',
  `addtime` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `admin_id` mediumint(8) UNSIGNED NOT NULL COMMENT '添加这件商品的管理员id',
  `type_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型id',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `promote_start_date` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '促销开始时间',
  `promote_end_date` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '促销结束时间',
  `is_hot` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否热销',
  `is_rec` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐',
  `is_new` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否新品',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐到楼层',
  `sort_number` tinyint(3) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序的数字'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `php38_goods`
--

INSERT INTO `php38_goods` (`id`, `cat_id`, `goods_name`, `market_price`, `shop_price`, `logo`, `sm_logo`, `mid_logo`, `goods_desc`, `is_on_sale`, `addtime`, `admin_id`, `type_id`, `promote_price`, `promote_start_date`, `promote_end_date`, `is_hot`, `is_rec`, `is_new`, `is_floor`, `sort_number`) VALUES
(16, 18, '新的联想商品321', '12.00', '333.00', 'Goods/2015-09-06/55eb9e60d5ffe.jpg', 'Goods/2015-09-06/sm_55eb9e60d5ffe.jpg', 'Goods/2015-09-06/mid_55eb9e60d5ffe.jpg', '', '是', 1441504302, 0, 2, '300.00', 1441814400, 1442678399, '否', '否', '否', '是', 100),
(18, 2, '2321321', '222.00', '222.00', 'Goods/2015-09-08/55ee8ddeaa2a9.gif', 'Goods/2015-09-08/sm_55ee8ddeaa2a9.gif', 'Goods/2015-09-08/mid_55ee8ddeaa2a9.gif', '', '是', 1441526225, 0, 3, '111.00', 1442073600, 1442678399, '否', '否', '否', '是', 50),
(19, 19, 'aaaaaaaaaaaaaaaa', '0.00', '0.00', 'Goods/2015-09-08/55ee97bf920c8.gif', 'Goods/2015-09-08/sm_55ee97bf920c8.gif', 'Goods/2015-09-08/mid_55ee97bf920c8.gif', '&lt;p&gt;&lt;script&gt;alert(123);&lt;/script&gt;&lt;/p&gt;', '是', 1441531783, 0, 0, '0.00', 0, 57599, '否', '否', '否', '是', 100),
(20, 14, '213', '0.00', '0.00', 'Goods/2015-09-09/55efd19c8c482.jpg', 'Goods/2015-09-09/sm_55efd19c8c482.jpg', 'Goods/2015-09-09/mid_55efd19c8c482.jpg', '&lt;p&gt;&lt;img src=&quot;http://www.38s.com/Public/umeditor1_2_2-utf8-php/php/upload/20150908/14416998286796.gif&quot; _src=&quot;http://www.38s.com/Public/umeditor1_2_2-utf8-php/php/upload/20150908/14416998286796.gif&quot;/&gt;&lt;/p&gt;', '是', 1441678232, 0, 0, '0.00', 0, 0, '否', '否', '否', '否', 100),
(21, 4, 'fdsafdsfds', '0.00', '0.00', 'Goods/2015-09-09/55eff5c4bc3d0.jpg', 'Goods/2015-09-09/thumb_1_55eff5c4bc3d0.jpg', 'Goods/2015-09-09/thumb_0_55eff5c4bc3d0.jpg', '', '是', 1441706572, 0, 0, '0.00', 0, 0, '否', '否', '否', '否', 100),
(22, 18, 'images```', '0.00', '0.00', 'Goods/2015-09-09/55eff2bfc447d.jpg', 'Goods/2015-09-09/thumb_1_55eff2bfc447d.jpg', 'Goods/2015-09-09/thumb_0_55eff2bfc447d.jpg', '', '是', 1441788607, 1, 0, '0.00', 0, 57599, '是', '否', '否', '否', 100),
(28, 16, 'aa', '0.00', '0.00', '', '', '', '', '是', 1475996131, 1, 0, '0.00', 0, 0, '否', '否', '否', '否', 100),
(26, 18, '商品属性测试', '123.00', '111.00', 'Goods/2015-09-12/55f3990367298.gif', 'Goods/2015-09-12/thumb_1_55f3990367298.gif', 'Goods/2015-09-12/thumb_0_55f3990367298.gif', '', '是', 1442020927, 1, 2, '0.00', 0, 57599, '否', '是', '是', '否', 100),
(27, 17, 'hhahah', '100.00', '100.00', 'Goods/2016-10-09/57f9e9821c3dd.jpg', 'Goods/2016-10-09/thumb_1_57f9e9821c3dd.jpg', 'Goods/2016-10-09/thumb_0_57f9e9821c3dd.jpg', '&lt;p&gt;hahahahhahhah&lt;/p&gt;', '是', 1442201085, 1, 2, '80.00', 1441036800, 1443628799, '否', '是', '是', '是', 75);

-- --------------------------------------------------------

--
-- 表的结构 `php38_goods_attr`
--

CREATE TABLE `php38_goods_attr` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品id',
  `attr_id` mediumint(8) UNSIGNED NOT NULL COMMENT '属性id',
  `attr_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性值'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性';

--
-- 转存表中的数据 `php38_goods_attr`
--

INSERT INTO `php38_goods_attr` (`id`, `goods_id`, `attr_id`, `attr_value`) VALUES
(1, 26, 1, '32G'),
(9, 26, 1, '16G'),
(8, 26, 1, '8g'),
(4, 26, 2, '白色'),
(5, 26, 2, '绿色'),
(6, 26, 4, '2015年8月9日'),
(7, 26, 5, 'i7'),
(10, 26, 2, '蓝色'),
(11, 16, 1, '4g'),
(12, 16, 2, '白色'),
(13, 16, 4, '2015年8月9日'),
(14, 16, 5, 'i7'),
(15, 16, 1, ''),
(16, 16, 2, ''),
(17, 16, 4, ''),
(18, 16, 5, ''),
(19, 27, 1, '4g'),
(20, 27, 2, '白色'),
(21, 27, 4, '2016年10月9日'),
(22, 27, 5, 'i5');

-- --------------------------------------------------------

--
-- 表的结构 `php38_goods_ext_cat`
--

CREATE TABLE `php38_goods_ext_cat` (
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `cat_id` mediumint(8) UNSIGNED NOT NULL COMMENT '分类Id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品的扩展分类';

--
-- 转存表中的数据 `php38_goods_ext_cat`
--

INSERT INTO `php38_goods_ext_cat` (`goods_id`, `cat_id`) VALUES
(16, 12),
(16, 4),
(18, 1),
(16, 2),
(16, 18),
(16, 16),
(16, 1),
(20, 13),
(21, 18),
(19, 20),
(27, 2),
(28, 22);

-- --------------------------------------------------------

--
-- 表的结构 `php38_goods_number`
--

CREATE TABLE `php38_goods_number` (
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品id',
  `attr_list` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性id，规则 1：如果一件商品有多个属性用，隔开 规则2：如果一件商品有多个属性ID就升降拼字符串，所以如果有两个属性ID5,6,那么不能拼成6,5，我们定义了这个规则之后，前台要取库存量也按这个规则就不会出错',
  `goods_number` mediumint(8) UNSIGNED NOT NULL COMMENT '库存量'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='库存量';

--
-- 转存表中的数据 `php38_goods_number`
--

INSERT INTO `php38_goods_number` (`goods_id`, `attr_list`, `goods_number`) VALUES
(26, '1,14,2,5', 321),
(26, '14,2,5', 22),
(16, '', 123);

-- --------------------------------------------------------

--
-- 表的结构 `php38_goods_pics`
--

CREATE TABLE `php38_goods_pics` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品id',
  `pic` varchar(150) NOT NULL COMMENT '原图路径',
  `sm_pic` varchar(150) NOT NULL COMMENT '小图路径',
  `mid_pic` varchar(150) NOT NULL COMMENT '中图路径'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='相册';

--
-- 转存表中的数据 `php38_goods_pics`
--

INSERT INTO `php38_goods_pics` (`id`, `goods_id`, `pic`, `sm_pic`, `mid_pic`) VALUES
(2, 22, 'Goods/2015-09-09/55eff2c43349d.jpg', 'Goods/2015-09-09/thumb_1_55eff2c43349d.jpg', 'Goods/2015-09-09/thumb_0_55eff2c43349d.jpg'),
(7, 24, 'Goods/2015-09-11/55f23137706d7.gif', 'Goods/2015-09-11/thumb_1_55f23137706d7.gif', 'Goods/2015-09-11/thumb_0_55f23137706d7.gif'),
(11, 24, 'Goods/2015-09-11/55f233994a66e.jpg', 'Goods/2015-09-11/thumb_1_55f233994a66e.jpg', 'Goods/2015-09-11/thumb_0_55f233994a66e.jpg'),
(13, 24, 'Goods/2015-09-11/55f233ba84189.jpg', 'Goods/2015-09-11/thumb_1_55f233ba84189.jpg', 'Goods/2015-09-11/thumb_0_55f233ba84189.jpg'),
(14, 24, 'Goods/2015-09-11/55f233bbe5808.jpg', 'Goods/2015-09-11/thumb_1_55f233bbe5808.jpg', 'Goods/2015-09-11/thumb_0_55f233bbe5808.jpg'),
(15, 24, 'Goods/2015-09-11/55f233bd775fa.jpg', 'Goods/2015-09-11/thumb_1_55f233bd775fa.jpg', 'Goods/2015-09-11/thumb_0_55f233bd775fa.jpg'),
(16, 27, 'Goods/2016-10-09/57f9e982322e6.png', 'Goods/2016-10-09/thumb_1_57f9e982322e6.png', 'Goods/2016-10-09/thumb_0_57f9e982322e6.png');

-- --------------------------------------------------------

--
-- 表的结构 `php38_member_level`
--

CREATE TABLE `php38_member_level` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'Id',
  `level_name` varchar(30) NOT NULL COMMENT '级别名称',
  `level_rate` tinyint(3) UNSIGNED NOT NULL DEFAULT '100' COMMENT '折扣率，100=10折 98=9.8折 90=9折，用时除100',
  `jifen_bottom` mediumint(8) UNSIGNED NOT NULL COMMENT '积分下限',
  `jifen_top` mediumint(8) UNSIGNED NOT NULL COMMENT '积分上限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员级别';

--
-- 转存表中的数据 `php38_member_level`
--

INSERT INTO `php38_member_level` (`id`, `level_name`, `level_rate`, `jifen_bottom`, `jifen_top`) VALUES
(1, '初级会员', 100, 0, 10000),
(2, '中级会员', 95, 10001, 50000),
(3, '高级会员', 90, 50001, 150000),
(4, 'VIP', 85, 150001, 500000),
(5, 'VIP中P', 80, 500001, 16777215);

-- --------------------------------------------------------

--
-- 表的结构 `php38_member_price`
--

CREATE TABLE `php38_member_price` (
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品id',
  `level_id` tinyint(3) UNSIGNED NOT NULL COMMENT '级别id',
  `price` decimal(10,2) NOT NULL COMMENT '价格'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员价格';

--
-- 转存表中的数据 `php38_member_price`
--

INSERT INTO `php38_member_price` (`goods_id`, `level_id`, `price`) VALUES
(27, 5, '80.00'),
(27, 4, '85.00'),
(27, 3, '90.00'),
(27, 2, '95.00'),
(27, 1, '100.00');

-- --------------------------------------------------------

--
-- 表的结构 `php38_privilege`
--

CREATE TABLE `php38_privilege` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `pri_name` varchar(150) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller_name` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `parent_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级权限ID，0：代表顶级分类'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限';

--
-- 转存表中的数据 `php38_privilege`
--

INSERT INTO `php38_privilege` (`id`, `pri_name`, `module_name`, `controller_name`, `action_name`, `parent_id`) VALUES
(1, '商品模块', 'null', 'null', 'null', 0),
(2, '添加商品', 'Admin', 'Goods', 'add', 3),
(3, '商品列表', 'Admin', 'Goods', 'lst', 1),
(4, '修改商品', 'Admin', 'Goods', 'edit', 3),
(5, 'RBAC', 'null', 'null', 'null', 0),
(6, '权限列表', 'Admin', 'Privilege', 'lst', 5),
(7, '添加权限', 'Admin', 'Privilege', 'add', 6),
(8, '角色列表', 'Admin', 'Role', 'lst', 5),
(9, '修改权限', 'Admin', 'Privilege', 'edit', 6),
(10, '删除商品', 'Admin', 'Goods', 'delete', 3),
(11, '删除权限', 'Admin', 'Privilege', 'delete', 6),
(12, '添加角色', 'Admin', 'Role', 'add', 8),
(13, '修改角色', 'Admin', 'Role', 'edit', 8),
(14, '删除角色', 'Admin', 'Role', 'delete', 8),
(15, '管理员列表', 'Admin', 'Admin', 'lst', 5),
(16, '添加管理员', 'Admin', 'Admin', 'add', 15),
(17, '修改管理员', 'Admin', 'Admin', 'edit', 15),
(18, '删除管理员', 'Admin', 'Admin', 'delete', 15),
(19, '商品分类列表', 'Admin', 'Category', 'lst', 1),
(20, '添加分类', 'Admin', 'Category', 'add', 19),
(21, '修改分类', 'Admin', 'Category', 'edit', 19),
(22, '删除分类', 'Admin', 'Category', 'delete', 19),
(23, '会员模块', 'null', 'null', 'null', 0),
(24, '会员级别列表', 'Admin', 'MemberLevel', 'lst', 23),
(25, '添加级别', 'Admin', 'MemberLevel', 'add', 24),
(26, '修改级别', 'Admin', 'MemberLevel', 'edit', 24),
(27, '删除级别', 'Admin', 'MemberLevel', 'delete', 24),
(28, '类型列表', 'Admin', 'Type', 'lst', 1),
(29, '添加类型', 'Admin', 'Type', 'add', 28),
(30, '修改类型', 'Admin', 'Type', 'edit', 28),
(31, '删除类型', 'Admin', 'Type', 'delete', 28),
(32, '属性列表', 'Admin', 'Attribute', 'lst', 28),
(33, '添加属性', 'Admin', 'Attribute', 'add', 32),
(34, '修改属性', 'Admin', 'Attribute', 'edit', 32),
(35, '删除属性', 'Admin', 'Attribute', 'delete', 32),
(36, 'ajax获取商品属性', 'Admin', 'Goods', 'ajax_get_attr', 3),
(37, 'ajax删除商品属性', 'Admin', 'Goods', 'ajaxDelGoodsAttr', 4),
(38, 'ajax删除商品相册图片', 'admin', 'Goods', 'ajax_delete_image', 4),
(39, '清空缓存', 'Admin', 'Goods', 'deleteTempImages', 3),
(40, 'ajax上传商品相册图片', 'Admin', 'Goods', 'ajax_upload_pic', 3),
(41, '库存量管理', 'Admin', 'Goods', 'gn', 3);

-- --------------------------------------------------------

--
-- 表的结构 `php38_role`
--

CREATE TABLE `php38_role` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `role_name` varchar(150) NOT NULL COMMENT '角色名称'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色';

-- --------------------------------------------------------

--
-- 表的结构 `php38_role_pri`
--

CREATE TABLE `php38_role_pri` (
  `role_id` mediumint(8) UNSIGNED NOT NULL COMMENT '角色id',
  `pri_id` mediumint(8) UNSIGNED NOT NULL COMMENT '权限id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色拥有的权限';

--
-- 转存表中的数据 `php38_role_pri`
--

INSERT INTO `php38_role_pri` (`role_id`, `pri_id`) VALUES
(1, 11),
(1, 9),
(1, 7),
(1, 6),
(1, 5),
(1, 35),
(1, 34),
(1, 33),
(1, 32),
(1, 31),
(1, 30),
(1, 29),
(1, 28),
(1, 22),
(1, 21),
(1, 20),
(1, 19),
(1, 41),
(1, 40),
(1, 39),
(1, 36),
(1, 10),
(1, 38),
(1, 37),
(1, 4),
(1, 2),
(1, 3),
(1, 1),
(1, 8),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18);

-- --------------------------------------------------------

--
-- 表的结构 `php38_type`
--

CREATE TABLE `php38_type` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT 'Id',
  `type_name` varchar(30) NOT NULL COMMENT '类型名称'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='类型';

--
-- 转存表中的数据 `php38_type`
--

INSERT INTO `php38_type` (`id`, `type_name`) VALUES
(1, '书'),
(2, '笔记本'),
(3, '手机'),
(4, '服装');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `php38_admin`
--
ALTER TABLE `php38_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `php38_admin_goods_cat`
--
ALTER TABLE `php38_admin_goods_cat`
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `php38_admin_role`
--
ALTER TABLE `php38_admin_role`
  ADD KEY `role_id` (`role_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `php38_attribute`
--
ALTER TABLE `php38_attribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `php38_category`
--
ALTER TABLE `php38_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_floor` (`is_floor`);

--
-- Indexes for table `php38_goods`
--
ALTER TABLE `php38_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_price` (`shop_price`),
  ADD KEY `addtime` (`addtime`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `promote_start_date` (`promote_start_date`),
  ADD KEY `promote_end_date` (`promote_end_date`),
  ADD KEY `is_hot` (`is_hot`),
  ADD KEY `is_rec` (`is_rec`),
  ADD KEY `is_new` (`is_new`),
  ADD KEY `is_floor` (`is_floor`),
  ADD KEY `is_on_sale` (`is_on_sale`);

--
-- Indexes for table `php38_goods_attr`
--
ALTER TABLE `php38_goods_attr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `attr_id` (`attr_id`);

--
-- Indexes for table `php38_goods_ext_cat`
--
ALTER TABLE `php38_goods_ext_cat`
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `php38_goods_number`
--
ALTER TABLE `php38_goods_number`
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `php38_goods_pics`
--
ALTER TABLE `php38_goods_pics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Indexes for table `php38_member_level`
--
ALTER TABLE `php38_member_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `php38_member_price`
--
ALTER TABLE `php38_member_price`
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `level_id` (`level_id`);

--
-- Indexes for table `php38_privilege`
--
ALTER TABLE `php38_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `php38_role`
--
ALTER TABLE `php38_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `php38_role_pri`
--
ALTER TABLE `php38_role_pri`
  ADD KEY `role_id` (`role_id`),
  ADD KEY `pri_id` (`pri_id`);

--
-- Indexes for table `php38_type`
--
ALTER TABLE `php38_type`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `php38_admin`
--
ALTER TABLE `php38_admin`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `php38_attribute`
--
ALTER TABLE `php38_attribute`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `php38_category`
--
ALTER TABLE `php38_category`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `php38_goods`
--
ALTER TABLE `php38_goods`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=32;
--
-- 使用表AUTO_INCREMENT `php38_goods_attr`
--
ALTER TABLE `php38_goods_attr`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `php38_goods_pics`
--
ALTER TABLE `php38_goods_pics`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `php38_member_level`
--
ALTER TABLE `php38_member_level`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `php38_privilege`
--
ALTER TABLE `php38_privilege`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=42;
--
-- 使用表AUTO_INCREMENT `php38_role`
--
ALTER TABLE `php38_role`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id';
--
-- 使用表AUTO_INCREMENT `php38_type`
--
ALTER TABLE `php38_type`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id', AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
