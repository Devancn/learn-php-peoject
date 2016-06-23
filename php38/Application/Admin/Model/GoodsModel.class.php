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

	//TP在执行add方法时会先调用这个方法[在记录插入到数据库之前，给我们一个机会修改表单中的数据]
	//$datra :代表表单中的数据
	protected function _before_insert(&$data,$option){
		//向表单中补上addtime字段
		$data['addtime'] = time();
		/************** 上传图片 **************/
		//判断用户有没有选择图片
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			//上传图片
			$upload = new \Think\Upload();// 实例化上传类,如果提示上传目录不存在就在Upload(array('maxSize'=>  2 * 1024 * 1024 .....))里面传递一个数组
			$upload->maxSize   =     2 * 1024 * 1024 ;// 设置附件上传大小(2M)一定小于php.ini中的设置
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     'Goods/'; // 设置附件上传（子）目录
			// 上传文件
			$info  =  $upload->upload();
			if(!$info) {
				// 获取失败的原因,并传到模型中
				$this->error = $upload ->getError();
				return FALSE; //返回到了控制器,在控制器到会调用$model->getEror()获取到信息并显示
			}else{
				/************** 为新上传的图片生成两张缩略图 **************/
				//先拼出图片的名字
				$logo=$info['logo']['savepath'].$info['logo']['savename'];
				$smlogo=$info['logo']['savepath'].'sm_'.$info['logo']['savename'];
				$midlogo=$info['logo']['savepath'].'mid_'.$info['logo']['savename'];
				//开始生成缩略图
				$image = new \Think\Image();
				//打开源图
				$image->open('./Public/Uploads/'.$logo);
				//生成缩略图：从在大->小生成(如果生成多张缩略图的话)
				//第三个参数:1-6,6种方法,默认是1（按比例缩放）
				$image->thumb(650, 650,1)->save('./Public/Uploads/'.$midlogo);
				$image->thumb(130, 130,1)->save('./Public/Uploads/'.$smlogo);
				//把三个图片的路径保存到商品表中
				$data['logo']=$logo;
				$data['sm_logo']=$smlogo;
				$data['mid_logo']=$midlogo;
			}
		}
	}

	//执行添加方法之后调用这个方法
	//$data：添加之后的数据,$data['id']：新添加记录的ID
	protected function _after_inert($data,$option){

	}

	//执行修改方法之前调用这个方法
	protected function _before_update(&$data,$option){

	}

	//执行修改方法之后调用这个方法
	protected function _after_update($data,$option){

	}

	//执行删除方法之前调用这个方法
	protected function _before_delete($option){

	}

	//执行删除方法之后调用这个方法
	protected function _after_delete($option){

	}
}