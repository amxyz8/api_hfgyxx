<?php


namespace app\common\lib;

class ClassArr
{
    public static function smsClassStat()
    {
        return [
            'ali' => "app\common\lib\sms\AliSms",
            'Other' => "app\common\lib\sms\OtherSms",
        ];
    }
    
    public static function uploadClassStat()
    {
        return [
            'text' => 'xxx',
            'image' => 'xxx',
        ];
    }
    public static function initClass($type, $class, $params = [], $needInstance = false)
    {
        //如果是静态方法, 直接返回类库
        //非静态, 返回对象
        if (!array_key_exists($type, $class)) {
            return false;
        }
        $className = $class[$type];
        
        // new ReflectionClass('A') => 建立A反射类
        // ->newInstanceArgs($args) => 相当于实例化A对象
        return $needInstance == true ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }
}
