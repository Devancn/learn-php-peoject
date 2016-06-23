create database php38;
use php38;
set names utf8;
drop table if exists php38_goods;
create table php38_goods
(
  id mediumint unsigned not null auto_increment comment 'ID',
  goods_name varchar(150) not null comment '商品名称',
  market_price decimal(10,2) not null comment '市场价格',
  shop_price decimal(10,2) not null comment '商城价格',
  logo varchar(150) not null default '' comment '图片',
  smg_logo varchar(150) not null default '' comment '小图片',
  mid_logo varchar(150) not null default '' comment '中图片',
  goods_desc longtext comment '商品描述',
  is_on_sale enum('是','否') not null default '是' comment '是否上架',
  addtime int unsigned not null comment '添加时间',
  primary key (id),
  key shop_price(shop_price),
  key addtime(addtime),
  key is_on_sale(is_on_sale)
)engine=myisam default charset=utf8;