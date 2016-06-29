<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model{
	//设置添加时表单中允许接收的字段【安全】
	protected $insertFields='goods_name,market_price,shop_price,goods_desc,is_on_sale,cat_id';
	//设置修改时表单中允许接收的字段【安全】
	protected $updateFields='id,goods_name,market_price,shop_price,goods_desc,is_on_sale,cat_id';
	//定义表单验证规则
	protected $_validate=array(
		array('cat_id','require','必须要选择一个主分类',1),
		array('goods_name','require','商品名称不能为空',1),
		array('market_price','currency','市场价格必须是货币类型!',1),
		array('shop_price','currency','本店价格必须是货币类型',1),
	);

	//TP在执行add方法时会先调用这个方法[在记录插入到数据库之前，给我们一个机会修改表单中的数据]
	//$datra :代表表单中的数据
	protected function _before_insert(&$data,$option){
		//使用自己定义的函数过滤
		$data['goods_desc'] = removeXSS($_POST['goods_desc']);
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
	//获取袋翻页的商品数据
	public function search(){
		/************** 搜索 **************/
		$where=array(); //where条件的数组
		//商品名称
		$gn=I('get.gn');
		if($gn){
			$where['a.goods_name'] = array('like',"%$gn%"); //goods_name LIKE '%$gn%'
		}
		//价格
		$fp=I('get.fp');
		$tp=I('get.tp');
		if($fp && $tp){
			$where['a.shop_price']=array('between',array($fp,$tp));
		}else if($fp){
			$where['a.shop_price']=array('egt',$fp); //大于等于 shop_price>=$fp
		}else if($tp){
			$where['a.shop_price']=array('elt',$tp); //小于等于 shop_price<=$fp
		}
		//添加时间
		$ft=I('get.ft');
		$et=I('get.et');
		if($ft && $et){
			$where['a.addtime']=array('between',array(strtotime("$ft 00:00:00"),strtotime("$et 23:59:59")));
		}else if($ft){
			$where['a.addtime']=array('egt',strtotime("$ft 00:00:00")); //大于等于 shop_price>=$fp
		}else if($et){
			$where['a.addtime']=array('elt',strtotime("$et 23:59:59")); //小于等于 shop_price<=$fp
		}
		//是否上架
		$ios=I('get.ios');
		if($ios == '是' || $ios == '否'){
			$where['is_on_sale'] = array('eq',$ios); //goods_name LIKE '%$gn%'
		}
		//商品分类
			$catId=I('get.cat_id');
		if($catId){
			//先取出这个分类所有子分类的ID
			$catModel=D('Category');
			$children=$catModel->getChildren($catId);
			//所父分类也放到这个数组中
			$children[]=$catId;
			$children=implode(',',$children);
			//搜索这些分类下的商品[主分类和扩展分类]
			//$where['a.cat_id'] = array('in',$children);
			//扩展分类
			//先查询商品分类表,根据分类的ID取出这个如那分类下所有商品的ID
			$gcModel = M('goods_ext_cat');
			$gids=$gcModel->field('GROUP_CONCAT(goods_id) gids')->where(array(
				'cat_id' => array('eq',$catId),
			))->find();
			//根据商品ID取出商品
			//$where['a.id'] = array('in',$gids['gids']);
			//主分类的条件的扩展分类的条件以OR的方式搜索
			$where['a.cat_id'] = array('exp',"IN($children) OR a.id IN({$gids['gids']})"); //exp 表示写一个表达式
		}
		/************** 翻页 **************/
		//取出总得记录数
		$count=$this->alias('a')->where($where)->count();
		$Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//设置上一页和下一页的字符串
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		//生成翻页字符串,这个字符串要在页面中显示出来
		$pageString= $Page->show();// 分页显示输出

		/************** 取某一页的数据 **************/
		/**
		 * SELECT a.*,b.cat_name,GROUP_CONCAT(d.cat_name) ext_cat_name
		 * FROM php38_goods a
		 * LEFT JOIN php38_category b ON a.cat_id=b.id
		 * LEFT JOIN php38_goods_ext_cat c ON a.id=c.goods_id
		 * LEFT JOIN php38_category d ON c.cat_id=d.id
		 * GROUP BY a.id
		 */
		$data=$this->where($where)->alias('a')
			->field('a.*,b.cat_name,GROUP_CONCAT(d.cat_name SEPARATOR "<br>") ext_cat_name')
			->join('LEFT JOIN php38_category b ON a.cat_id=b.id
					LEFT JOIN php38_goods_ext_cat c ON a.id=c.goods_id
					LEFT JOIN php38_category d ON c.cat_id=d.id
			')
			->limit($Page->firstRow.','.$Page->listRows)
			->group('a.id')
			->select();

		return array(
			'data' => $data,
			'page' => $pageString
		);
	}

	//执行添加方法之后调用这个方法
	//$data：添加之后的数据,$data['id']：新添加记录的ID
	protected function _after_insert($data,$option){
		/********** 处理表单中扩展分类的代码 ***************/
		$ecid=I('post.ext_cat_id');
		if($ecid){
			// 生成中间表的模型
			$gcModel = M('goods_ext_cat');
			foreach ($ecid as $k => $v){
				//如果没有选择分类就跳过
				if(empty($v))
					continue;
				$gcModel->add(array(
					'goods_id' => $data['id'],
					'cat_id'  => $v,
				));
			}
		}
	}

	//执行修改方法之前调用这个方法
	protected function _before_update(&$data,$option){
		/********** 处理表单中扩展分类的代码 ***************/
		$id=I('post.id');
		$ecid=I('post.ext_cat_id');
		// 生成中间表的模型
		$gcModel = M('goods_ext_cat');
		//先清空原扩展分类数据
		$gcModel->where(array('goods_id' => array('eq',$id)))->delete();
		if($ecid){
			foreach ($ecid as $k => $v){
				//如果没有选择分类就跳过
				if(empty($v))
					continue;
				$gcModel->add(array(
					'goods_id' => $id,
					'cat_id'  => $v,
				));
			}
		}
		//上传图片
		//判断用户有没有选择图片
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){
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
				/************** 删除商品的原图片 **************/
				//商品的ID
				$id=I('get.id');
				//先从数据库中取出这伯商品的图片路径
				$logo=$this->field('logo,sm_logo,mid_logo')->find($id);
				//如果有就删除
				if($logo){
					unlink('./Public/Uploads/'.$logo['logo']);
					unlink('./Public/Uploads/'.$logo['sm_logo']);
					unlink('./Public/Uploads/'.$logo['mid_logo']);
				}
			}
		}
	}

	//执行修改方法之后调用这个方法
	protected function _after_update($data,$option){

	}

	//执行删除方法之前调用这个方法
	protected function _before_delete($option){
		//商品的ID
		$id=I('get.id');
		/************** 先删除商品的图片 **************/
		//先从数据库中取出这伯商品的图片路径
		$logo=$this->field('logo,sm_logo,mid_logo')->find($id);
		//如果有就删除
		if($logo){
			unlink('./Public/Uploads/'.$logo['logo']);
			unlink('./Public/Uploads/'.$logo['sm_logo']);
			unlink('./Public/Uploads/'.$logo['mid_logo']);
		}
		/********** 删除扩展分类中对应的数据 *************/
		$gcModel = M('goods_ext_cat');
		//因为这是调用的另一个模型的delete方法,那么在删除之前就先调用另一个的_before_delete
		$gcModel->where(array('goods_id'=> array('eq',$id)))->delete(); //eq 等于的意思
	}

	//执行删除方法之后调用这个方法
	protected function _after_delete($option){

	}
}