<?php
    class HoomiModel extends Model{
    /**
     * 链接厚米数据库
     */
     protected $connection = array(
		'db_type' => 'mysql',
		'db_user' => 'hmrds',
		'db_pwd' => '0BBD4l7w',
		'db_host' => 'hoomidata.mysql.rds.aliyuncs.com',
		'db_port' => '3306',
		'db_name' => 'hm_inner',
		'db_charset' => 'utf8',
	);
}

?>