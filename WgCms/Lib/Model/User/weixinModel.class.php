<?php

class weixinModel extends Model
{    
	/**
     * 链接微信数据库
     */
     protected $connection = array(
		'db_type' => 'mysql',
		'db_user' => 'root',
		'db_pwd' => 'root',
		'db_host' => 'localhost',
		'db_port' => '3306',
		'db_name' => 'weixin',
		'db_charset' => 'utf8',
	);
}