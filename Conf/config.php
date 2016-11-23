<?php
/**
 *项目公共配置
 *@package PiGCms
 *@author PiGCms
 **/
return array(
	'LOAD_EXT_CONFIG' 		=> 'db,info,email,safe,upfile,cache,route,app,alipay,sms',		
	'APP_AUTOLOAD_PATH'     =>'@.ORG',
	'OUTPUT_ENCODE'         =>  true, 			//页面压缩输出
	'PAGE_NUM'				=> 15,
	/*Cookie配置*/
	'COOKIE_PATH'           => '/',     		// Cookie路径
    'COOKIE_PREFIX'         => '',      		// Cookie前缀 避免冲突
	'COOKIE_EXPIRE' => 3600,
	'COOKIE_DOMAIN' => '.chiduobao.cn',
	/*定义模版标签*/
	'TMPL_L_DELIM'   		=>'{wghd:',			//模板引擎普通标签开始标记
	'TMPL_R_DELIM'			=>'}',				//模板引擎普通标签结束标记
	'HOOMI_KEY'			    =>'d3ea1174edc5025852764e901fbb3c97', 			 //厚米加密key
	'SESSION_OPTIONS'         =>  array(
        'name'                =>  'BJYSESSION',                    //设置session名
        'expire'              =>  100,                      //SESSION保存15天
        'use_trans_sid'       =>  1,                               //跨页传递
        'use_only_cookies'    =>  0,                               //是否只开启基于cookies的session的会话方式
    ),
);
?>