<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends Controller{
	//删除
	public function delete(){
		//接收商品ID
		$id=I('get.id');
		$model=D('Goods');
		$model->delete($id);
		if(FALSE !== $model->delete($id)){
			$this->success('删除成功!');
			exit;
		}else{
			$this->error('删除失败!');
		}
	}
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
		//设置页面信息
		$this->assign(
			array(
				'_page_title' => '商品列表',
				'_page_btn_name' => '添加商品',
				'_page_btn_link' => U('add')
			)
		);

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
		//取出所有的分类制作下拉框
		$catModel=D('Category');
		$cateData=$catModel->getTree();

		//设置页面信息
		$this->assign(
			array(
				'cateData' => $cateData,
				'_page_title' => '添加商品',
				'_page_btn_name' => '商品列表',
				'_page_btn_link' => U('lst')
			)
		);
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
					$this->success('修改成功',U('lst'.'?p='.I('get.p')));
					//8.停止后面代码的执行
					exit;
				}
			}
			//4.获取失败的原因
			$error=$model->getError();
			//5.打印失败原因,并且3秒之后调回上一个页面
			$this->error($error);
		}
		//先取出要修改的商品的信息
		$id=I('get.id');//接收商品ID
		$model = M('Goods');
		$info=$model->find($id);//根据ID取出商品的信息
		$this->assign('info',$info);//分配到修改的表单
		//取出所有的分类制作下拉框
		$catModel=D('Category');
		$cateData=$catModel->getTree();

		$gcModel=M('goods_ext_cat');
		$gcData = $gcModel->field('cat_id')->where(array('goods_id' => array('eq',$id)))->select();
		//设置页面信息
		$this->assign(
			array(
				'gcData' => $gcData,
				'cateData'=> $cateData,
				'_page_title' => '添加商品',
				'_page_btn_name' => '商品列表',
				'_page_btn_link' => U('lst?p='.I('get.p'))
			)
		);
		$this->display();
	}
}