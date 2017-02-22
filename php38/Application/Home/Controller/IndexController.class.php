<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller
{
	// 首页
    public function index()
    {
    	$goodsModel = D('Admin/Goods');
    	$catModel = D('Admin/Category');
    	// 获取疯狂抢购
    	$goods1 = $goodsModel->getPromoteGoods();
    	$goods2 = $goodsModel->getRecGoods('hot');
    	$goods3 = $goodsModel->getRecGoods('rec');
    	$goods4 = $goodsModel->getRecGoods('new');
    	// 取出中间推荐楼层
    	$floorData = $catModel->getFloorCatData();
    	
    	// 设置页面信息
    	$this->assign(array(
    		'_page_title' => '首页',
    		'_page_keywords' => '首页',
    		'_page_description' => '首页',
    		'_page_hide_nav' => 0,  // 展开
            'goods1' => $goods1,
            'goods2' => $goods2,
            'goods3' => $goods3,
            'goods4' => $goods4,
            'floorData' => $floorData,
    	));
		$this->display();
    }
    // 商品详情页
    public function goods()
    {
    	// 设置页面信息
    	$this->assign(array(
    		'_page_title' => '商品详情页',
    		'_page_keywords' => '商品详情页',
    		'_page_description' => '商品详情页',
    		'_page_hide_nav' => 1,  // 隐藏导航分类
    	));
		$this->display();
    }
}