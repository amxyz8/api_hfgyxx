<?php
namespace app\controller;

class Error
{
    public function __call($name, $arguments)
    {
        return show(config('status.method_not_found'), "找不到{$name}控制器", null, 404);
    }
}
