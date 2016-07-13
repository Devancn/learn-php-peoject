<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','cpassword','status','chkcode');
	protected $updateFields = array('id','username','password','cpassword','status');
	//添加和修改管理员时使用的规则
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,150', '用户名的值最长不能超过 150 个字符！', 1, 'length', 3),
		//添加时生效,修改时不生效
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('password', 'cpassword', '两次密码不一致！', 1, 'confirm', 3),
		array('status', '正常,禁用', "状态的值只能是在 '正常,禁用' 中的一个值！", 2, 'in',1),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
	);

	//登录的方法
	public function login(){
		//从模型中获取用记名和密码
		$username=I('post.username');
		$password=I('post.password');
		//先判断有没有这个账号
		$urser=$this->where(array(
			'username' => array('eq',$username)
		))->find();
		if($urser){
			//判断禁用

			if($urser['status'] == '正常'){
				if($urser['password'] == md5($password.C('MD5_KEY'))){
					//登录成功后把 id和 username存到session中
					session('id',$urser['id']);
					session('username',$urser['username']);
					return TRUE;
				}else{
					$this->error="密码错误!";
					return FALSE;
				}
			}else{
				$this->error="账号被禁用,不能登录!";
				return FALSE;
			}
		}else{
			$this->error="账号不存在!";
			return FALSE;
		}
	}
	//定义登录使用的表单验证规则
	public $_login_validate=array(
		array('chkcode', 'require', '验证码不能为空！', 1, 'regex', 3),
		array('chkcode', 'chk_code', '验证码不正确！', 1, 'callback',3),
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
	);
	//验证码是否正确
	protected function chk_code($code){
		$verify = new \Think\Verify();
		return $verify->check($code);
	}

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
		$data['password'] = md5($data['password'] . C('MD5_KEY'));
	}
	// 添加前
	protected function _after_insert($data, $option)
	{

	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
		$id = I('post.id');  // 管理员ID
		if($id == 1)
			unset($data['status']);
		// 判断密码是否为空
		if(empty($data['password']))
			unset($data['password']); // 为空就不修改这个字段
		else
			$data['password'] = md5($data['password'] . C('MD5_KEY'));
		/********** 处理表单中的角色 ***********/
		// 先删除原数据
		$arModel = D('admin_role');
		$arModel->where(array(
			'admin_id' => array('eq', $id),
		))->delete();
		// 再接收表单中的数据重新添加一遍
		$roleId = I('post.role_id');
		if($roleId)
		{
			foreach ($roleId as $v)
			{
				$arModel->add(array(
					'admin_id' => $id, // 如果是添加的钩子ID是保存在$data['id']，但 现在是修改的钩子函数所以ID不在$data里
					'role_id' => $v,
				));
			}
		}
		/********** 处理表单中的商品分类 ***********/
		// 先删除原数据
		$agcModel = D('admin_goods_cat');
		$agcModel->where(array(
			'admin_id' => array('eq', $id),
		))->delete();
		// 重新添加
		$catId = I('post.cat_id');
		if($catId)
		{
			foreach ($catId as $v)
			{
				$agcModel->add(array(
					'admin_id' => $id,
					'cat_id' => $v,
				));
			}
		}
	}
	// 删除前
	protected function _before_delete($option)
	{
		$id = I('get.id');  // 接收管理员ID
		if($id == 1)
		{
			$this->error = '超级管理员不允许删除！';
			return FALSE;
		}
		$arModel = M('admin_role');
		$arModel->where(array(
			'admin_id' => array('eq', $id),
		))->delete();
		// 管理员对应的商品分类
		$agcModel = M('admin_goods_cat');
		$agcModel->where(array(
			'admin_id' => array('eq', $id),
		))->delete();
	}
	/************************************ 其他方法 ********************************************/
}