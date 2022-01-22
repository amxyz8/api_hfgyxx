<?php


namespace app\common\lib;

use think\facade\Request;

class Str
{
    /**
     * 生成登录所需的token
     * @param $string
     * @return string
     */
    public static function getLoginToken($string)
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $token = sha1($str.$string);
        return $token;
    }
    
    /**
     * @return string
     */
    public static function generateToken()
    {
        //3组字符串md5加密
        //生成32位随机字符串
        $randChars = self::getRandChar(32);
        $timestamp = Request::server('REQUEST_TIME_FLOAT');
        $salt = config('wx.token_salt');
        
        return md5($randChars.$timestamp.$salt);
    }
    
    /**
     * 生成随机字符串
     * @param $length
     * @return string|null
     */
    public static function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        
        return $str;
    }
    
    /**
     * 解决网页乱码
     * @param $html
     * @return false|string
     */
    public static function solverGarbled($html)
    {
        $content = '';
        $text = file_get_contents($html);
        define('UTF32_BIG_ENDIAN_BOM', chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
        define('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
        define('UTF16_BIG_ENDIAN_BOM', chr(0xFE) . chr(0xFF));
        define('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
        define('UTF8_BOM', chr(0xEF) . chr(0xBB) . chr(0xBF));
        $first2 = substr($text, 0, 2);
        $first3 = substr($text, 0, 3);
        $first4 = substr($text, 0, 3);
        $encodType = "";
        if (UTF8_BOM == $first3) {
            $encodType = 'UTF-8 BOM';
        } elseif (UTF32_BIG_ENDIAN_BOM == $first4) {
            $encodType = 'UTF-32BE';
        } elseif (UTF32_LITTLE_ENDIAN_BOM == $first4) {
            $encodType = 'UTF-32LE';
        } elseif (UTF16_BIG_ENDIAN_BOM == $first2) {
            $encodType = 'UTF-16BE';
        } elseif (UTF16_LITTLE_ENDIAN_BOM == $first2) {
            $encodType = 'UTF-16LE';
        }

        //下面的判断主要还是判断ANSI编码的·
        if ('' == $encodType) {
            //即默认创建的txt文本-ANSI编码的
            $content = iconv("GBK", "UTF-8", $text);
        } elseif ('UTF-8 BOM' == $encodType) {
            //本来就是UTF-8不用转换
            $content = $text;
        } else {
            //其他的格式都转化为UTF-8就可以了
            $content = iconv($encodType, "UTF-8", $text);
        }
        
        return $content;
    }
}
