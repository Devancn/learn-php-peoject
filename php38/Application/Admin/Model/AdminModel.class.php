<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','status');
	protected $updateFields = array('id','username','password','status');
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,150', '用户名的值最长不能超过 150 个字符！', 1, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 3),
		array('password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 3),
		array('status', '正常,禁用', "状态的值只能是在 '正常,禁用' 中的一个值！", 2, 'in', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($username = I('get.username'))
			$where['username'] = array('like', "%$username%");
		$status = I('get.status');
		if($status != '' && $status != '-1')
			$where['status'] = array('eq', $status);
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	}
	// 添加前
	protected function _after_insert($data, $option)
	{
		/******************** 处理表单中的角色 *************************/
		$roleId=I('post.role_id');
		if($roleId){
			$arModel=D('admin_role');
			foreach($roleId as $v){
				$arModel->add(array(
					'admin_id' => $data['id'],
					'role_id'  =>$v,
				));
			}
		}

		/******************** 处理表单中的商品分类 *************************/
		$catId=I('post.cat_id');
		if($catId){
			$agcModel=D('admin_goods_cat');
			foreach($catId as $v){
				$agcModel->add(array(
					'admin_id' => $data['id'],
					'cat_id'  =>$v,
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