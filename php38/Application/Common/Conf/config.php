<?php
return array(
	'SHOW_PAGE_TRACE'           =>  true,
	'DEFAULT_FILTER' =>  'trim,htmlspecialchars', // 默认参数过滤方法 用于I函数...
	'DB_TYPE' => 'mysql',//mysql,mysqli,pdo
	'DB_HOST '=> '127.0.0.1',
	'DB_NAME' => 'php38',
	'DB_USER' => 'root',
	'DB_PWD' => '',
	'DB_PREFIX' => 'php38_',
	'DB_CHARSET' => 'utf8',
	/********************* 图片相关配置 ***********************/
	'IMAGE_PREFIX' => '/Puclic/Uploads/', //显示图片时的前缀
	'IMAGE_SAVE_PATH' => './Public/Uploads/',
	'IMG_maxSize' => 2 ,//单位 M
	'IMG_exts' =>array('jpg','gif','png','jpeg','pjpeg'),
	/********************* md5密钥 ***********************/
	'MD5_KEY' => '!@#$%^&*asd#$#$#$#asgsdf434234',
);