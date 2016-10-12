<?php
function isAndroid(){
	if(strstr($_SERVER['HTTP_USER_AGENT'],'Android')) {
		return 1;
	}
	return 0;
}
/*
 * ║ё├╫╝╙├▄
*/
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : 'hoomi');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
  if(function_exists("mb_substr")) {
   if($suffix)
  {
     if($str==mb_substr($str, $start, $length, $charset)) 
     {
      return mb_substr($str, $start, $length, $charset); 
     }
     else
     {
      return mb_substr($str, $start, $length, $charset)."..."; 
     } 
 }
    else
 {
   return mb_substr($str, $start, $length, $charset);
  }
  }
  elseif(function_exists('iconv_substr')) {
   if($suffix)
  {
     if($str==iconv_substr($str,$start,$length,$charset)) 
     {
      return iconv_substr($str,$start,$length,$charset); 
     }
     else
     {
      return iconv_substr($str,$start,$length,$charset)."..."; 
     } 
   }
    else
 {
   return iconv_substr($str,$start,$length,$charset);
  }
  }
  $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
  $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
  $re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
  $re['big5']  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
  preg_match_all($re[$charset], $str, $match);
  $slice = join("",array_slice($match[0], $start, $length));
  if($suffix) return $slice."бн";
  return $slice;
}
?>