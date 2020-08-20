<?php
declare (strict_types=1);

namespace app\controller\api;

use app\BaseController;
use app\model\Translates AS TranslatesModel;
use app\validate\TranslateValidate;

class Translate extends BaseController
{
    /**
     * 获取Json数据
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function json()
    {
        (new TranslateValidate())->goCheck();
        $result = TranslatesModel::search();
        return $this->showResCode('获取成功',$result);
    }

    /**
     * 获取String数据
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function string()
    {
        (new TranslateValidate())->goCheck();
        $result = TranslatesModel::search();
        echo $result[0];
    }
}
