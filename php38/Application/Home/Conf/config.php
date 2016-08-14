<?php
return array(
	//'配置项'=>'配置值'
	'HTML_CACHE_ON'     =>    true, // 开启静态缓存
	'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
	'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
	//以下可以配置哪些页面生成缓存
	'HTML_CACHE_RULES'  =>     array(
		'Index:index' => array('index',3600),  //首页生成1小时的缓存 文件，文件名叫index
		'Index:goods'=>array('goods-(id)',4800),//表示根据不同的ID生成不同的缓存文件
	)
);