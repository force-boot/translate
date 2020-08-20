<?php
// 应用公共文件

use app\controller\common\Registry;

/**
 * 辅助方法 注册类实例
 * @param $alias
 * @param string $args
 * @return mixed|null
 */
function registry($alias, $args = null)
{
    if (!$obj = Registry::get($alias)) {
        $className = $class = "app\\controller\\common\\" . strtolower($alias) . "\\" . ucwords($alias);
        $obj = new $className($args);
        Registry::set($alias, $obj);
    }
    return $obj;
}

/**
 * 获取IP所在地 省-市
 * @param string $ip ip地址 为空获取当前请求IP
 * @return string
 */
function getIpCity(string $ip = '')
{
    if (empty($ip)) $ip = request()->ip();
    $url = 'http://whois.pconline.com.cn/ipJson.jsp?json=true&ip=';
    $city = file_get_contents($url . $ip);
    $city = mb_convert_encoding($city, "UTF-8", "GB2312");
    $city = json_decode($city, true);
    return $city['pro'] . '-' . $city['city'];
}

/**
 * 生成唯一key
 * @param string $param 附加参数
 * @return string
 */
function createUniqueKey(string $param = 'token'): string
{
    $md5 = md5(uniqid(md5(microtime(true)), true));
    return sha1($md5 . md5($param));
}

/**
 * 字符串转义
 * @param $string
 * @param int $force
 * @param bool $strip
 * @return array|string
 */
function daddslashes($string, $force = 0, $strip = FALSE)
{
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if (!MAGIC_QUOTES_GPC || $force) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

function convert(&$args)
{
    $data = '';
    if (is_array($args)) {
        foreach ($args as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $data .= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                }
            } else {
                $data .= "$key=" . rawurlencode($val) . "&";
            }
        }
        return trim($data, "&");
    }
    return $args;
}

// uuid generator
function create_guid()
{
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);
    $dec_hex = dechex($a_dec * 1000000);
    $sec_hex = dechex($a_sec);
    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);
    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);
    return $guid;
}

function create_guid_section($characters)
{
    $return = "";
    for ($i = 0; $i < $characters; $i++) {
        $return .= dechex(mt_rand(0, 15));
    }
    return $return;
}

function truncate($q)
{
    $len = abslength($q);
    return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
}

function abslength($str)
{
    if (empty($str)) {
        return 0;
    }
    if (function_exists('mb_strlen')) {
        return mb_strlen($str, 'utf-8');
    } else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

function ensure_length(&$string, $length)
{
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string, $length, "0");
    } else if ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}