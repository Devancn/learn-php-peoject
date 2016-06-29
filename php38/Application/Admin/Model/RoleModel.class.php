<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model 
{
	protected $insertFields = array('role_name');
	protected $updateFields = array('id','role_name');
	protected $_validate = array(
		array('role_name', 'require', '角色名称不能为空！', 1, 'regex', 3),
		array('role_name', '1,150', '角色名称的值最长不能超过 150 个字符！', 1, 'length', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($role_name = I('get.role_name'))
			$where['role_name'] = array('like', "%$role_name%");
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		/**
		 * SELECT a.*,GROUP_CONCAT(c.pri_name) pri_name FROM php38_role a LEFT JOIN php38_role_pri b ON a.id=b.role_id LEFT JOIN php38_privilege c ON b.pri_id=c.id GROUP BY a.id
		 */
		$data['data'] = $this->alias('a')
			->field('a.*,GROUP_CONCAT(c.pri_name) pri_name')
			->where($where)
			->join('LEFT JOIN php38_role_pri b ON a.id=b.role_id LEFT JOIN php38_privilege c ON b.pri_id=c.id')
			->group('a.id')
			->limit($page->firstRow.','.$page->listRows)
			->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	}
	// 添加完角色之后执行,其中$data['id']就表示刚刚添加的角色的ID
	protected function _after_insert($data, $option)
	{
		//*****************把这个角色拥有的权限保存到角色权限表
		$priId=I('post.pri_id');
		if($priId){
			$priModel=M('role_pri');
			foreach ($priId as $v){
				$priModel->add(array(
					'role_id' => $data['id'],
					'pri_id' => $v
				));
			}
		}
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
	}
	/************************************ 其他方法 ********************************************/
}