<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller
{
    //实现email验证
    public function email_chk(){
        $model = D ('Admin/Member');
        if($model->do_email_chk(I('get.code'))){
            $this->success('EMAIL验证成功,现在可以登录了!',U('login'),5);
            exit;
        }
        $this->error($model->getError(),U('regist'));
    }
    public function ajaxChkLogin()
    {
        if(session('member_id'))
        {
            $username = session('member_username');
            die(json_encode(array(
                'ok' => 1,
                'username' => empty($username) ? session('member_email') : $username,
            )));
        }
        else
        {
            die(json_encode(array(
                'ok' => 0,
            )));
        }
    }
    public function logout()
    {
        session(null);
        redirect('/');//重新跳到首页
    }
    public function login()
    {
        if(IS_POST)
        {
            $model = D('Admin/Member');
            // 接收表单并且根据规则验证表单
            if($model->validate($model->_login_validate)->create())
            {
                if($model->login())
                {
                    // 判断SESSION中是否设置了要返回的地址
                    $return = session('returnUrl');
                    if($return)
                    {
                        session('returnUrl', null);
                        $this->success('登录成功！', $return);
                        exit;
                    }
                    else
                    {
                        $this->success('登录成功！', U('Home/Index/index'));
                        exit;
                    }
                }
            }
            // 只要失败就获取失败原因
            $error = $model->getError();
            $this->error($error);
        }
        // 先取出失败的次数
        $leModel = D('login_error');
        $errorCount = $leModel->where(array(
            'ip' => array('eq', get_client_ip(1, TRUE)),
            'logtime' => array('egt', time()-C('login_error_chkcode_time')),
        ))->count();
        // 设置页面信息
        $this->assign(array(
            'errorCount' => $errorCount,
            '_page_title' => '登录',
            '_page_keywords' => '登录',
            '_page_description' => '登录',
        ));
        $this->display();
    }
    public function chkcode()
    {
        $Verify = new \Think\Verify(array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    2,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        ));
        $Verify->entry();
    }
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