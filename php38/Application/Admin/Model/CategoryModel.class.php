<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model{
	//设置添加时表单中允许接收的字段【安全】
	protected $insertFields='cat_name';
	//设置修改时表单中允许接收的字段【安全】
	protected $updateFields='id,cat_name';
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
}