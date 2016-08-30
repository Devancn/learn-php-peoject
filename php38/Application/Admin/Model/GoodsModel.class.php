<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model{
	// 设置添加时表单中允许接收的字段【安全】
	protected $insertFields = 'goods_name,market_price,shop_price,goods_desc,is_on_sale,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_hot,is_rec,is_new';
	// 设置修改的表单中允许出现的字段
	protected $updateFields = 'id,goods_name,market_price,shop_price,goods_desc,is_on_sale,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_hot,is_rec,is_new';
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
		if($data['promote_price']){
			$sd = I('post.promote_start_date');
			$ed = I('post.promote_end_date');
			$data['promote_start_date'] = strtotime("$sd 00:00:00");
			$data['promote_end_date'] = strtotime("$ed 23:59:59");
		}
		$data['admin_id']=session('id');
		//使用自己定义的函数过滤
		$data['goods_desc'] = removeXSS($_POST['goods_desc']);
		//向表单中补上addtime字段
		$data['addtime'] = time();
		/************** 上传图片 **************/
		//判断用户有没有选择图片
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			$ret=uploadOne('logo','Goods',array(
				array(650,650),
				array(130,130)
			));
			if($ret['ok'] == 1){
				$data['logo'] = $ret['images'][0];
				$data['mid_logo'] = $ret['images'][1];
				$data['sm_logo'] = $ret['images'][2];
			}else{
				$this->error=$ret['error'];
				return FALSE;
			}
		}
	}
// 获取带翻页的商品数据
	public function search()
	{
		/*********** 搜索 ***********/
		$where = array();  // where条件的数组
		// 只查询出有权限管理的分类下的商品
		$id = session('id');
		// 如果是普通管理员
		if($id > 1)
		{
			// 先取出这个管理员有权限访问的分类ID
			$agcModel = M('admin_goods_cat');
			$hasPriCatIds =$agcModel->field('GROUP_CONCAT(cat_id) cids')->where(array(
				'admin_id' => array('eq', $id),
			))->find();
			//var_dump($hasPriCatIds);
			// 先取出这些扩展分类下的商品
			$gecModel = M('goods_ext_cat');
			$gids = $gecModel->field('GROUP_CONCAT(goods_id) gids')->where(array(
				'cat_id' => array('in', $hasPriCatIds['cids'])
			))->find();
			// 如果扩展分类中没有搜索出商品就不考虑扩展分类主考虑主分类
			if(empty($gids['gids']))
				$where['a.cat_id'] = array('in', $hasPriCatIds['cids']);
			else
				$where['a.cat_id'] = array('exp', "IN({$hasPriCatIds['cids']}) OR a.id IN({$gids['gids']})");
		}
		// 商品名称
		$gn = I('get.gn');
		if($gn)
			$where['a.goods_name'] = array('like', "%$gn%");   // goods_name LIKE '%$gn%'
		// 价格
		$fp = I('get.fp');
		$tp = I('get.tp');
		if($fp && $tp)
			$where['a.shop_price'] = array('between', array($fp, $tp));
		elseif ($fp)
			$where['a.shop_price'] = array('egt', $fp);  // shop_price >= $fp
		elseif ($tp)
			$where['a.shop_price'] = array('elt', $tp);  // shop_price <= $Tp
		// 添加时间
		$ft = I('get.ft');
		$et = I('get.et');
		if($ft && $et)
			$where['a.addtime'] = array('between', array(strtotime("$ft 00:00:00"), strtotime("$et 23:59:59")));
		elseif ($ft)
			$where['a.addtime'] = array('egt', strtotime("$ft 00:00:00"));  // shop_price >= $fp
		elseif ($et)
			$where['a.addtime'] = array('elt', strtotime("$et 23:59:59"));  // shop_price <= $Tp
		// 是否上架
		$ios = I('get.ios');
		if($ios == '是' || $ios == '否')
			$where['a.is_on_sale'] = array('eq', $ios);
		// 商品分类
		$catId = I('get.cat_id');
		if($catId)
		{
			// 先取出这个分类所有子分类的ID
			$catModel = D('Category');
			$children = $catModel->getChildren($catId);
			// 把父分类也放到这个数组中
			$children[] = $catId;
			// 如果是普通管理员再从上面的数组中去掉没有权限访问的分类
			if($id > 1)
			{
				// 有权限访问的分类ID
				$_arr = explode(',', $hasPriCatIds['cids']);
				$children = array_intersect($children, $_arr);
			}
			if($children)
			{
				$children = implode(',', $children);
				// 搜索这些分类下的商品【主分类】
				//$where['a.cat_id'] = array('in', $children);
				// 扩展分类
				// 先查询商品分类表，取出这些分类下扩展分类下所有商品的ID
				$gcModel = M('goods_ext_cat');
				$gids = $gcModel->field('GROUP_CONCAT(goods_id) gids')->where(array(
					'cat_id' => array('in', $children),
				))->find();
				// 根据商品ID取出这些商品
				//$where['a.id'] = array('in', $gids['gids']);
				// 主分类的条件和扩展分类的条件以OR的方式搜索
				if(empty($gids['gids']))
					$where['a.cat_id'] = array('IN', $children);
				else
					$where['a.cat_id'] = array('exp', "IN($children) OR a.id IN({$gids['gids']})");
			}
			else
				$where['a.id'] = array('eq', 0);
		}
		/************* 翻页 ***************/
		// 取出总的记录数
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, 15);
		// 设置上一页和下一页的字符串
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		// 生成翻页字符串，这个字符串要在页面中显示出来
		$pageString = $page->show();
		/************ 取某一页的数据 ********/
		/**
		 * SELECT a.*,b.cat_name,GROUP_CONCAT(d.cat_name) ext_cat_name
		 *  FROM php38_goods a
		 *   LEFT JOIN php38_category b ON a.cat_id=b.id
		 *   LEFT JOIN php38_goods_ext_cat c ON a.id=c.goods_id
		 *   LEFT JOIN php38_category d ON c.cat_id=d.id
		 *   GROUP BY a.id
		 */
		$data = $this->alias('a')
			->field('a.*,b.cat_name,GROUP_CONCAT(d.cat_name SEPARATOR "<br />") ext_cat_name,SUM(e.goods_number) gn')
			->join('LEFT JOIN php38_category b ON a.cat_id=b.id
		        LEFT JOIN php38_goods_ext_cat c ON a.id=c.goods_id
		        LEFT JOIN php38_category d ON c.cat_id=d.id
		        LEFT JOIN php38_goods_number e ON a.id=e.goods_id
		       ')
			->where($where)
			->limit($page->firstRow .','. $page->listRows)
			->group('a.id')
			->select();

		return array(
			'data' => $data,
			'page' => $pageString,
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
		/********** 处理相册图片 ***************/
		if(hasImage('pic')){
			//先整理二维数组
			$_goods_pics=array();
			foreach ($_FILES['pic']['name'] as $k=>$v){
				if(empty($v)){
					continue;
				}else{
					$_goods_pics[]=array(
						'name' => $v,
						'type' => $_FILES['pic']['type'][$k],
						'tmp_name' => $_FILES['pic']['tmp_name'][$k],
						'error' => $_FILES['pic']['error'][$k],
						'size' => $_FILES['pic']['size'][$k]
					);
				}
			}
			$gpModel=D('goods_pics');
			//用整理好的数组覆盖原图片数组,
			//因为uploadOne函数中会到$_FIELES里找图片
			$_FILES=$_goods_pics;
			//循环上传
			foreach ($_goods_pics as $k=>$v){
				if(empty($v)){
					continue;
				}else{
					$ret=uploadOne($k,'Goods',array(
						array(650.650),
						array(130.130)
					));
					if($ret['ok']==1){
						$gpModel->add(array(
							'goods_id' =>$data['id'],
							'pic'=>$ret['images'][0],
							'mid_pic'=>$ret['images'][1],
							'sm_pic'=>$ret['images'][2],
						));
					}
				}
			}
		}
		/********** 处理会员价格 ***************/
		$mpData=I('post.member_price');
		if($mpData){
			$mpModel=D('member_price');
			$levelId=I('post.level_id');
			foreach($mpData as $k => $v){
				$_price=(float)$v;
				if($_price == 0){
					continue;
				}
				$mpModel->add(array(
					'goods_id' => $data['id'],
					'price' => $v,
					'level_id'=>$levelId[$k]
				));
			}
		}
		/********** 处理商品属性 ***************/
		$attrId=I('post.attr_id');
		$attrValue=I('post.attr_value');
		//循环属性插入到商品属性表
		$gaMode=D('goods_attr');
		foreach($attrId as $k => $v){
			$gaMode->add(array(
				'goods_id' => $data['id'],
				'attr_id' =>$v,
				'attr_value'=>$attrValue[$k]
			));
		}
	}

	//执行修改方法之前调用这个方法
	protected function _before_update(&$data,$option){
		/********** 处理表单中扩展分类的代码 ***************/
		if($data['promote_price']){
			$sd = I('post.promote_start_date');
			$ed = I('post.promote_end_date');
			$data['promote_start_date'] = strtotime("$sd 00:00:00");
			$data['promote_end_date'] = strtotime("$ed 23:59:59");
		}
		$id=I('post.id');
		$priModel=D('Privilege');
		if(!$priModel->hasPriTOEditGoods($id)){
			$this->error='无权修改该商品';
			return false;
		}
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
		/********** 处理相册图片 ***************/
		if(hasImage('pic')){
			//先整理二维数组
			$_goods_pics=array();
			foreach ($_FILES['pic']['name'] as $k=>$v){
				if(empty($v)){
					continue;
				}else{
					$_goods_pics[]=array(
						'name' => $v,
						'type' => $_FILES['pic']['type'][$k],
						'tmp_name' => $_FILES['pic']['tmp_name'][$k],
						'error' => $_FILES['pic']['error'][$k],
						'size' => $_FILES['pic']['size'][$k]
					);
				}
			}
			$gpModel=D('goods_pics');
			//用整理好的数组覆盖原图片数组,
			//因为uploadOne函数中会到$_FIELES里找图片
			$_FILES=$_goods_pics;
			//循环上传
			foreach ($_goods_pics as $k=>$v){
				if(empty($v)){
					continue;
				}else{
					$ret=uploadOne($k,'Goods',array(
						array(650.650),
						array(130.130)
					));
					if($ret['ok']==1){
						$gpModel->add(array(
							'goods_id' =>$id,
							'pic'=>$ret['images'][0],
							'mid_pic'=>$ret['images'][1],
							'sm_pic'=>$ret['images'][2],
						));
					}
				}
			}
		}
		$mpData=I('post.member_price');
		$levelId=I('post.level_id');
		$mpModel=D('member_price');
		//删除原数据
		$mpModel->where(array(
			'goods_id' => $data['id'],
		))->delete();
		if($mpData){
			foreach($mpData as $k => $v){
				$_price=(float)$v;
				if($_price == 0){
					continue;
				}
				$mpModel->add(array(
					'goods_id' => $id,
					'price' => $v,
					'level_id'=>$levelId[$k]
				));
			}
		}
		/*********** 判断如果修改了类型，那么就删除之前所有的属性和库存量 *******************/
		$gaModel = D('goods_attr');
		$typeId = $this->field('type_id')->find($id);  // 先取出原类型id
		if($typeId['type_id'] != $data['type_id'])
		{
			// 删除所有属性
			$gaModel->where(array(
				'goods_id' => array('eq', $id),
			))->delete();
			// 删除所有库存量
			$gnModel = D('goods_number');
			$gnModel->where(array(
				'goods_id' => array('eq', $id),
			))->delete();
		}
		/*************** 修改商品属性 *******************/
		$oldga = I('post.old_attr_value');
		foreach ($oldga as $k => $v)
		{
			$gaModel->where(array(
				'id' => array('eq', $k),
			))->setField('attr_value', $v);
		}
		/**************** 添加新的商品属性 *****************/
		$attrId = I('post.attr_id');
		$attrValue = I('post.attr_value');
		foreach ($attrValue as $k => $v)
		{
			$gaModel->add(array(
				'goods_id' => $id,
				'attr_id' => $attrId[$k],
				'attr_value' => $v,
			));
		}
	}

	//执行修改方法之后调用这个方法
	protected function _after_update($data,$option){

	}

	//执行删除方法之前调用这个方法
	protected function _before_delete($option){
		//商品的ID
		$id=I('get.id');
		$priModel=D('Privilege');
		if(!$priModel->hasPriTOEditGoods($id)){
			$this->error='无权删除该商品';
			return false;
		}
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
		/********** 删除相册中对应的图片 *************/
		$gpModel=D('goods_pics');
		$pics=$gpModel->where(array(
			'goods_id'=>array('eq',$id),
		))->select();
		if($pics){
			//循环删除硬盘上的图片
			foreach($pics as $k=>$v){
				unlink('./Public/Uploads/'.$v['pic']);
				unlink('./Public/Uploads/'.$v['sm_pic']);
				unlink('./Public/Uploads/'.$v['mid_pic']);
			}
			//把相册表中的数据删除
			$gpModel->where(array(
				'goods_id'=>array('eq',$id),
			))->delete();
		}
		/********** 删除商品对应的会员价格数据 *************/
		$gpModel = M('member_price');
		$gpModel->where(array(
			'goods_id'=>array('eq',$id),
		))->delete();
	}
	//执行删除方法之后调用这个方法
	protected function _after_delete($option){

	}
}