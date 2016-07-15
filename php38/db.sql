create database php38;
use php38;
set names utf8;
drop table if exists php38_goods;
create table php38_goods
(
  id mediumint unsigned not null auto_increment comment 'ID',
  cat_id mediumint unsigned not null  comment '主分类id',
  goods_name varchar(150) not null comment '商品名称',
  market_price decimal(10,2) not null comment '市场价格',
  shop_price decimal(10,2) not null comment '商城价格',
  logo varchar(150) not null default '' comment '图片',
  smg_logo varchar(150) not null default '' comment '小图片',
  mid_logo varchar(150) not null default '' comment '中图片',
  goods_desc longtext comment '商品描述',
  is_on_sale enum('是','否') not null default '是' comment '是否上架',
  addtime int unsigned not null comment '添加时间',
  admin_id mediumint unsigned not null  comment '添加这个商品的管理员id',
  primary key (id),
  key shop_price(shop_price),
  key addtime(addtime),
  key admin_id(admin_id),
  key cat_id(cat_id),
  key is_on_sale(is_on_sale)
)engine=myisam default charset=utf8;
-- 修改字段名
alter table php38_goods change column smg_logo sm_logo  varchar(150) not null default '' comment '小图片';
alter table php38_goods add index admin_id(admin_id);

drop table if exists php38_category;
create table php38_category
(
  id mediumint unsigned not null auto_increment comment 'ID',
  cat_name varchar(150) not null comment '分类名称',
  parent_id mediumint unsigned not null default '0' comment '上级分类ID,0:代表顶级分类',
  primary key (id)
)engine=myisam default charset=utf8;

--
-- 转存表中的数据 `php38_category`
--

INSERT INTO `php38_category` (`id`, `cat_name`, `parent_id`) VALUES
(1, '家用电器', 0),
(2, '手机、数码、京东通信', 0),
(3, '电脑、办公', 0),
(4, '家居、家具、家装、厨具', 0),
(5, '男装、女装、内衣、珠宝', 0),
(6, '个护化妆', 0),
(7, '鞋靴、箱包、钟表、奢侈品', 0),
(8, '运动户外', 0),
(9, '汽车、汽车用品', 0),
(10, '母婴、玩具乐器', 0),
(11, '食品、酒类、生鲜、特产', 0),
(12, '营养保健', 0),
(13, '图书、音像、电子书', 0),
(14, '彩票、旅行、充值、票务', 0),
(15, '理财、众筹、白条、保险', 0),
(16, '大家电', 1),
(17, '生活电器', 1),
(18, '厨房电器', 1),
(19, '个护健康', 1),
(20, '五金家装', 1);

ALTER TABLE php38_goods  ADD cat_id mediumint unsigned not null  comment '主分类id';

/******************************** RBAC  *******************************/
-- 权限表
DROP TABLE IF EXISTS php38_privilege;
CREATE TABLE php38_privilege
(
	id mediumint unsigned not null auto_increment comment 'Id',
	pri_name varchar(150) not null comment '权限名称',
	module_name varchar(30) not null default '' comment '模块名称',
	controller_name varchar(30) not null default '' comment '控制器名称',
	action_name varchar(30) not null default '' comment '方法名称',
	parent_id mediumint unsigned not null default '0' comment '上级权限ID，0：代表顶级分类',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '权限';

-- 角色拥有的权限
DROP TABLE IF EXISTS php38_role_pri;
CREATE TABLE php38_role_pri
(
	role_id mediumint unsigned not null comment '角色id',
	pri_id mediumint unsigned not null comment '权限id',
	key role_id(role_id),
	key pri_id(pri_id)
)engine=MyISAM default charset=utf8 comment '角色拥有的权限';

-- 角色表
DROP TABLE IF EXISTS php38_role;
CREATE TABLE php38_role
(
	id mediumint unsigned not null auto_increment comment 'Id',
	role_name varchar(150) not null comment '角色名称',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '角色';

-- 管理员所在角色
DROP TABLE IF EXISTS php38_admin_role;
CREATE TABLE php38_admin_role
(
	admin_id mediumint unsigned not null comment '管理员id',
	role_id mediumint unsigned not null comment '角色id',
	key role_id(role_id),
	key admin_id(admin_id)
)engine=MyISAM default charset=utf8 comment '管理员所在角色';

-- 管理员表
DROP TABLE IF EXISTS php38_admin;
CREATE TABLE php38_admin
(
	id mediumint unsigned not null auto_increment comment 'Id',
	username varchar(150) not null comment '用户名',
	password char(32) not null comment '密码',
	status enum('正常','禁用') not null default '正常' comment '状态',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '管理员';
INSERT INTO php38_admin VALUES(1,'root','99de37ebe3fc968924ff1d82dec33cd2','正常');

-- 管理员可以管理的分类
DROP TABLE IF EXISTS php38_admin_goods_cat;
CREATE TABLE php38_admin_goods_cat
(
	admin_id mediumint unsigned not null comment '管理员id',
	cat_id mediumint unsigned not null comment '分类id',
	key cat_id(cat_id),
	key admin_id(admin_id)
)engine=MyISAM default charset=utf8 comment '管理员可以管理的分类表';

/*********************** 权限表数据 ************************/
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

-- 商品相册
DROP TABLE IF EXISTS php38_goods_pics;
CREATE TABLE php38_goods_pics
(
	id mediumint unsigned not null auto_increment comment 'Id',
	goods_id mediumint unsigned not null  comment 'Id',
	pic varchar(150) not null  comment '原图路径',
	sm_pic varchar(150) not null  comment '小图路径',
	mid_pic varchar(150) not null  comment '中图路径',
	primary key (id),
	key (goods_id)
)engine=MyISAM default charset=utf8 comment '商品相册';

-- 会员级别
DROP TABLE IF EXISTS php38_member_level;
CREATE TABLE php38_member_level
(
	id tinyint unsigned not null auto_increment comment 'Id',
	level_name varchar(30) not null comment '级别名称',
	level_rate tinyint unsigned not null default '100' comment '折扣率，100=10折 98=9.8折 90=9折，用时除100',
	jifen_bottom mediumint unsigned not null comment '积分下限',
	jifen_top mediumint unsigned not null comment '积分上限',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '会员级别';

-- 会员价格
DROP TABLE IF EXISTS php38_member_price;
CREATE TABLE php38_member_price
(
	goods_id mediumint unsigned not null comment '商品id',
	level_id tinyint unsigned not null comment '级别id',
	price decimal(10,2) not null comment '价格',
	key goods_id(goods_id),
	key level_id(level_id)
)engine=MyISAM default charset=utf8 comment '会员价格';

-- 类型
DROP TABLE IF EXISTS php38_type;
CREATE TABLE php38_type
(
	id tinyint unsigned not null auto_increment comment 'Id',
	type_name varchar(30) not null comment '类型名称',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '类型';

-- 属性
DROP TABLE IF EXISTS php38_attribute;
CREATE TABLE php38_attribute
(
	id mediumint unsigned not null auto_increment comment 'Id',
	attr_name varchar(30) not null comment '属性名称',
	attr_type enum('唯一','可选') not null comment '属性类型',
	attr_option_values varchar(150) not null default '' comment '属性可选值',
	type_id tinyint unsigned not null comment '类型id',
	primary key (id),
	key type_id(type_id)
)engine=MyISAM default charset=utf8 comment '属性';

-- 商品属性
DROP TABLE IF EXISTS php38_goods_attr;
CREATE TABLE php38_goods_attr
(
	id mediumint unsigned not null auto_increment comment 'Id',
	goods_id mediumint unsigned not null comment '商品id',
	attr_id mediumint unsigned not null comment '属性id',
	attr_value varchar(150) not null default '' comment '属性值',
	primary key (id),
	key goods_id(goods_id),
	key attr_id(attr_id)
)engine=MyISAM default charset=utf8 comment '商品属性';

-- 库存量
DROP TABLE IF EXISTS php38_goods_number;
CREATE TABLE php38_goods_number
(
	goods_id mediumint unsigned not null comment '商品id',
	attr_list varchar(150) not null default '' comment '商品属性id，规则 1：如果一件商品有多个属性用，隔开 规则2：如果一件商品有多个属性ID就升降拼字符串，所以如果有两个属性ID5,6,那么不能拼成6,5，我们定义了这个规则之后，前台要取库存量也按这个规则就不会出错',
	goods_number mediumint unsigned not null comment '库存量',
	key goods_id(goods_id)
)engine=MyISAM default charset=utf8 comment '库存量';

-- 根据角色名查询该角色所拥有的权限
select a.*,GROUP_CONCAT(c.pri_name) from php38_role a LEFT JOIN php38_role_pri b ON a.id=b.role_id
LEFT JOIN php38_privilege c ON b.pri_id=c.id;

-- 根据管理员id查找相应的权限
select COUNT(*) from php38_admin_role a
left join php38_role_pri b on a.role_id=b.role_id
left join php38_privilege c on b.pri_id =c.id
where a.admin_id =8 and module_name='Admin' and c.controller_name='Privilege' and action_name='lst' \G;

SELECT count(*) has FROM php38_admin_role a LEFT JOIN php38_role_pri b ON a.role_id = b.role_id LEFT JOIN php38_privilege c ON b.pri_id=c.id WHERE a.admin_id=8 AND c.module_name="MODEULE_NAME" AND c.controller_name="Role" AND c.action_name="lst"

-- 修改商品相册表结构
ALTER TABLE `php38_goods_pics` CHANGE `prc` `pic` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '原图路径';
