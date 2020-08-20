<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 自定义验证方法 支持验证场景
     * @param string $scene
     * @return bool
     * @throws \think\Exception
     */
    public function goCheck(string $scene = ''): bool
    {
        //获取所有请求参数
        $params = input();
        //是否需要验证场景
        $check = $scene ? $this->scene($scene)->check($params) : $this->check($params);
        if (!$check) {
            throw new \think\Exception($this->getError(), 10005);
        }
        return true;
    }
}
