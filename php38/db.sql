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
  admin_id mediumint unsigned not null  comment '添加这伯商品的管理员id',
  primary key (id),
  key shop_price(shop_price),
  key addtime(addtime),
  key cat_id(cat_id),
  key is_on_sale(is_on_sale)
)engine=myisam default charset=utf8;
-- 修改字段名
alter table php38_goods change column smg_logo sm_logo  varchar(150) not null default '' comment '小图片';


drop table if exists php38_category;
create table php38_category
(
  id mediumint unsigned not null auto_increment comment 'ID',
  cat_name varchar(150) not null comment '分类名称',
  parent_id mediumint unsigned not null default '0' comment '上级分类ID,0:代表顶级分类',
  primary key (id)
)engine=myisam default charset=utf8;

INSERT INTO php38_category
VALUES
(1,'笔记本',0),
(2,'本',0),
(3,'手机',0),
(4,'Thinkpad',1),
(5,'ThinkpadT500',4),
(6,'ThinkpadT501',5),
(7,'超极本',1);


ALTER TABLE php38_goods  ADD cat_id mediumint unsigned not null  comment '主分类id';

/******************************** RBAC  *******************************/
-- 权限表
drop table if exists php38_privilege;
create table php38_privilege
(
  id mediumint unsigned not null auto_increment comment 'ID',
  pri_name varchar(150) not null comment '权限名称',
  module_name varchar(30) not null default '' comment '模块名称',
  controller_name varchar(30) not default '' null comment '控制器名称',
  action_name varchar(30) not null default '' comment '方法名称',
  parent_id mediumint unsigned not null default '0' comment '上级权限ID,0:代表顶级分类',
  primary key (id)
)engine=myisam default charset=utf8 comment '权限';

-- 角色拥有的权限
drop table if exists php38_role_pri;
create table php38_role_pri
(
 role_id mediumint unsigned not null  comment '角色ID',
 pri_id mediumint unsigned not null  comment '权限ID',
 key role_id(role_id),
 key pri_id(pri_id)
)engine=myisam default charset=utf8 comment '角色拥有的权限';

-- 角色表
drop table if exists php38_role;
create table php38_role
(
  id mediumint unsigned not null auto_increment comment 'ID',
  role_name varchar(150) not null comment '角色名称',
  primary key (id)
)engine=myisam default charset=utf8 comment '角色';

-- 管理员所在角色
drop table if exists php38_admin_role;
create table php38_admin_role
(
 admin_id mediumint unsigned not null  comment '管理员ID',
 role_id mediumint unsigned not null  comment '角色ID',
 key role_id(role_id),
 key admin_id(admin_id)
)engine=myisam default charset=utf8 comment '管理员所在角色';

-- 管理员表
drop table if exists php38_admin;
create table php38_admin
(
  id mediumint unsigned not null auto_increment comment 'ID',
  username varchar(150) not null comment '用户名',
  password char(32) not null comment '密码',
  status enum('正常','禁用') not null default '正常' comment '状态',
  primary key (id)
)engine=myisam default charset=utf8 comment '管理员';
INSERT INTO php38_admin VALUES(1,'root','21232f297a57a5a743894a0e4a801fc3','正常');

-- 管理员可以管理的分类
drop table if exists php38_admin_goods_cat;
create table php38_admin_goods_cat
(
 admin_id mediumint unsigned not null  comment '管理员ID',
 cat_id mediumint unsigned not null  comment '分类ID',
 key cat_id(cat_id),
 key admin_id(admin_id)
)engine=myisam default charset=utf8 comment '管理员可以管理的分类';
