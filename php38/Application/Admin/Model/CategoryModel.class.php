<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model{
	//设置添加时表单中允许接收的字段【安全】
	protected $insertFields='cat_name,parent_id';
	//设置修改时表单中允许接收的字段【安全】
	protected $updateFields='id,cat_name,parent_id';
	//定义表单验证规则
	protected $_validate=array(
		array('cat_name','require','分类名称不为能为空',1)
	);

	/****************打印树形结构**************************/
	public function getTree(){
		//取出所有的分类
		$data=$this->select();
		//递归重新排序数据
		return $this->_getTree($data);
	}

	/**
	 * @param $data : 要排序的数据
	 * @param int $parent_id : 从第几级开始排序，默认从顶级
	 * @param int $level :标记每个分类是第几级的 0 :顶级
	 */
	protected function _getTree($data,$parent_id=0,$level=0){
		static $_ret = array();// 保存排序好的结果的数组
		foreach ($data as $k => $v){
			if($v['parent_id'] == $parent_id){
				//为这个分类添加一个level字段,标记这个分类是第几级的
				$v['level']=$level;
				$_ret[]=$v;
				//找这个$v的子分类
				$this->_getTree($data,$v['id'],$level+1);
			}
		}
		return $_ret;
	}


	/****************找子分类的方法**************************/
	/**
	 * @param $catId :父分类的ID
	 */
	public function getChildren($catId){
		//取出所有的分类
		$data = $this -> select();
		//先清空再递归找子分类
		return $this->_getChildren($data,$catId,TRUE);
	}

	protected function _getChildren($data,$parent_id,$isClear=FALSE){
		static $_ret=array();// 保存找到的子分类的ID
		//如果是新的查询就清空,递归过程中不清空
		if($isClear){
			$_ret =array();//清空这个数组
		}
		foreach ($data as $k => $v){
			if($v['parent_id'] == $parent_id){
				//把这个子分类的ID放到数组中
				 $_ret[]=$v['id'];
				//再找这个分类的子分类
				$this->_getChildren($data,$v['id']);
				return $_ret;
			}
		}
	}

	//在调用delete之前先自动执行
	protected function _before_delete($option){
		/****************如果一个分类有子分类就不允许删除**************************/
		$children = $this->getChildren(I('get.id'));
		if($children){
			$this->error='有子分类无法删除!';
			return FALSE;//阻止删除
		}

		/*** 删除一个分类 同时删除子分类
		$children = $this->getChildren(I('get.id'));
		if($children){
			$children = implode(',',$children);//转化成字符串
			//$this->delete($children)---->注意:这里如果delete就死循环了!!所以不能delete只能执行sql语句删除(这个函数和_before_delete这个函数一直死循环了)
			$this->execute("DELETE FROM php38_category WHERE id IN($children)");
		}
		 * ****/
	}
}