<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller
{

    // 注册
    public function regist()
    {
        if(IS_POST){
            $model = D('Admin/Member');
            if($model->create(I('post.',1))){//验证表单
                if($model -> add()){
                    $this->success('注册成功，请登录您的邮箱完成验证之后才可以登录',U('login'),5);
                    exit;
                }
            }
            $this->error($model->getError());
        }
    	// 设置页面信息
    	$this->assign(array(
    		'_page_title' => '注册',
    		'_page_keywords' => '注册',
    		'_page_description' => '注册',
    	));
		$this->display();
    }
}