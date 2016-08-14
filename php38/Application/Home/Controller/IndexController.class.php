<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页
    public function index(){
        //设置页面信息
        $this->assign(array(
            '_page_title' => '首页',
            '_page_keywords' => '首页',
            '_page_description' => '首页',
            '_page_hide_nav' => 0, //代表展开
        ));
        $this->display();
    }

    //商品详情页
    public function goods(){
        //设置页面信息
        $this->assign(array(
            '_page_title' => '商品详情页',
            '_page_keywords' => '商品详情页',
            '_page_description' => '商品详情页',
            '_page_hide_nav' => 1,
        ));
        $this->display();
    }
}