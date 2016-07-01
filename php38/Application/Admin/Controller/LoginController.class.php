<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller
{
	public function chkcode(){
		$Verify = new \Think\Verify(
			$config =    array(
				'fontSize'    =>    30,    // 验证码字体大小
				'length'      =>    4,     // 验证码位数
				'useNoise'    =>    true, // 关闭验证码杂点
			)
		);
		$Verify->entry();
	}
	public function Login()
	{
		if(IS_POST){
			$model=D('Admin');
			//接收表单并且根据规则难表单
			if($model->validate($model->_login_validate)->create()){
				if($model->login()){
					$this->success('登录成功!',U('Admin/Index/index'));
					exit;
				}
			}
			//只要失败就获取失败原因
			$error = $model->getError();
			$this->error($error);
		}
		$this->display();
	}
}