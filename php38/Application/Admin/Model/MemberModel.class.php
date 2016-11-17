<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model 
{
	protected $insertFields = array('email','name','password','cpassword','gender','must_click');
	protected $updateFields = array('id','email','name','password','cpassword','gender');
	protected $_validate = array(
		array('must_click', 'require', '必须同意注册协议！', 1, 'regex', 3),
		array('email', 'require', 'Email不能为空！', 1, 'regex', 3),
		array('email', 'email', 'Email格式不正确！', 1, 'regex', 3),
		array('email', '1,150', 'Email的值最长不能超过 150 个字符！', 1, 'length', 3),
		array('name', '1,30', '昵称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 3),
		array('password', '6,20', '密码必须是6-20位字符！', 1, 'length', 3),
		array('cpassword', 'password', '两次密码不一致！', 1, 'confirm', 3),
		array('gender', '男,女,保密', "性别的值只能是在 '男,女,保密' 中的一个值！", 2, 'in', 3),
        array('email', '', '你的邮箱已经注册过了！', 1, 'unique', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($email = I('get.email'))
			$where['email'] = array('like', "%$email%");
		if($name = I('get.name'))
			$where['name'] = array('like', "%$name%");
		$st = I('get.st');
		$et = I('get.et');
		if($st && $et)
			$where['regtime'] = array('between', array(strtotime("$st 00:00:00"), strtotime("$et 23:59:59")));
		elseif($st)
			$where['regtime'] = array('egt', strtotime("$st 00:00:00"));
		elseif($et)
			$where['regtime'] = array('elt', strtotime("$et 23:59:59"));
		if($regip = I('get.regip'))
			$where['regip'] = array('eq', $regip);
		$gender = I('get.gender');
		if($gender != '' && $gender != '-1')
			$where['gender'] = array('eq', $gender);
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
	    $data['regtime'] = time();
        $data['regip'] = get_client_ip(1,TRUE);
		if(isset($_FILES['face']) && $_FILES['face']['error'] == 0)
		{
			$ret = uploadOne('face', 'Admin', array(
				array(150, 150, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['face'] = $ret['images'][0];
				$data['face'] = $ret['images'][1];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
	}
	protected function _after_insert(&$data, $option){
        //生成一个email验证码[唯一的]
        $code=md5(uniqid()).C('MD5_KEY');
        $model = D('email_chk_code');
        $model->add(array(
            'member_id' => $data['id'],
            'chk_email_code' => $code,
            'chk_email_code_time' => $data['regtime'],
        ));
        // 把验证码发到会员 的email中
        $content =C('reg_email_content');
        $content = str_replace('#code#',$code,$content);
        sendMail($data['email'],C('reg_email_title'),$content);
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
		if(isset($_FILES['face']) && $_FILES['face']['error'] == 0)
		{
			$ret = uploadOne('face', 'Admin', array(
				array(150, 150, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['face'] = $ret['images'][0];
				$data['face'] = $ret['images'][1];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			deleteImage(array(
				I('post.old_face'),
				I('post.old_face'),
	
			));
		}
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
		$images = $this->field('face,face')->find($option['where']['id']);
		deleteImage($images);
	}
	/************************************ 其他方法 ********************************************/
}