<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends Controller{
	//列表页
	public function lst(){
		$model = D ('Goods');
		//获取数据以及翻页字符串
		$data = $model -> search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page']
		));

		//$this->assign('data',$data['data']);
		//$this->assign('data',$data['page']);

		$this->display();
	}

	// 添加
	public function add(){
		//IF里处理表单
		if(IS_POST){
			//2.生成模型
			$model = D ('Goods');
			//3.接收表单,根据模型中定义的规则验证表单\
			//第二个参数:1.添加 2.修改
			if($model->create(I('post.'),1)){
				//6.表单中的数据插入到数据库中
				if($model->add()){
					//7.提示成功信息，并且在1秒之后跳转到商品列表页面
					$this->success('添加成功',U('lst'));
					//8.停止后面代码的执行
					exit;
				}
			}
			//4.获取失败的原因
			$error=$model->getError();
			//5.打印失败原因,并且3秒之后调回上一个页面
			$this->error($error);
		}
		//1.显示添加商品的表单
		$this->display();
	}
	// 修改
	public function edit(){
		//IF里处理表单
		if(IS_POST){
			//2.生成模型
			$model = D ('Goods');
			//3.接收表单,根据模型中定义的规则验证表单\
			//第二个参数:1.添加 2.修改
			if($model->create(I('post.'),2)){
				//6.修改数据
				//返回值:save返回值是mysql_affected_rows:影响的条件数,如果一伯商品原名叫abc，修改之后还叫abc,返回0
				if(FALSE !== $model->save()){
					//7.提示成功信息，并且在1秒之后跳转到商品列表页面
					$this->success('修改成功',U('lst'));
					//8.停止后面代码的执行
					exit;
				}
			}
			//4.获取失败的原因
			$error=$model->getError();
			//5.打印失败原因,并且3秒之后调回上一个页面
			$this->error($error);
		}
		//1.显示添加商品的表单
		$this->display();
	}
}