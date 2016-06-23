<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model{
	//设置添加时表单中允许接收的字段【安全】
	protected $insertFields='goods_name,market_price,shop_price,goods_desc,is_on_sale';
	//设置修改时表单中允许接收的字段【安全】
	protected $updateFields='id,goods_name,market_price,shop_price,goods_desc,is_on_sale';
	//定义表单验证规则
	protected $_validate=array(
		array('goods_name','require','商品名称不能为空',1),
		array('market_price','currency','市场价格必须是货币类型!',1),
		array('shop_price','currency','本店价格必须是货币类型',1),
	);
}