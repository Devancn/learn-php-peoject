<?php
return array(
	'SHOW_PAGE_TRACE' => FALSE,
	'URL_PATHINFO_DEPR' => '/',
	'DEFAULT_FILTER' => 'trim,htmlspecialchars',
	'DB_TYPE' => 'mysql', // mysql,mysqli,pdo
	'DB_HOST' => '127.0.0.1',
	'DB_NAME' => 'php38',
	'DB_USER' => 'root',
	'DB_PWD' => '',
	'DB_PREFIX' => 'php38_',
	'DB_CHARSET' => 'utf8',
	/*************** 图片相关配置 *********************/
	'IMAGE_PREFIX' => '/Public/Uploads/',  // 显示图片时的前缀
	'IMAGE_SAVE_PATH' => './Public/Uploads/',
	'IMG_maxSize' => 2, // 单位M
	'IMG_exts' => array('jpg', 'gif', 'png', 'jpeg', 'pjpeg'),
	/*************** md5密钥 ********************/
	'MD5_KEY' => 'fdj;sa3@329e#-1`#@2309d;lfda;peq2',
    /************** 发邮件的配置 ***************/
//    'MAIL_ADDRESS' => 'postmaster@iblogs.cc‍',   // 发送人的email
//    'MAIL_FROM' => 'postmaster',      // 发送人姓名
//    'MAIL_SMTP' => 'smtpdm.aliyun.com',      // 邮件服务器的地址
//    'MAIL_LOGINNAME' => 'postmaster@iblogs.cc',
//    'MAIL_PASSWORD' => 'Dxh82051',
    'MAIL_ADDRESS' => 'xldingxiaohuan@sina.com',   // 发货人的email
    'MAIL_FROM' => '丁晓欢',      // 发送人姓名
    'MAIL_SMTP' => 'smtp.sina.com',      // 邮件服务器的地址
    'MAIL_LOGINNAME' => 'xldingxiaohuan',
    'MAIL_PASSWORD' => 'xiaohuan34644826',
);