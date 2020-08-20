<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Translates extends Model
{
    /**
     * 搜索翻译数据
     * @return mixed|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function search()
    {
        $content = preg_replace('# #','',input('content'));
        $row  = self::where('content',$content)
            ->where('tolang',input('to','en'))
            ->find();
        $result = $row ? $row['result'] : (new static())->requestAndSaveData();
        return unserialize($result);
    }

    /**
     * 发起请求
     * @param array $param
     * @return mixed
     */
    public function request($param = [])
    {
        $param = empty($param) ? input() : $param;
        $obj = registry('translate');
        $arrs = $obj->content($param['content'])
            ->to($param['to'] ?? 'en')
            ->from($param['from'] ?? 'auto')
            ->toArray();
        return $arrs['translation'];
    }

    /**
     * 发起请求并保存数据
     * @param array $data
     * @return bool
     */
    public function requestAndSaveData($data = [])
    {
        $data = input();
        $this->content = preg_replace('# #','',$data['content']);
        $this->tolang = $data['to'] ?? 'en';
        $this->from = $data['from'] ?? 'auto';
        $this->result = serialize($this->request());
        $this->dataType = $data['dataType'] ?? 'json';
        $this->ip = request()->ip();
        $this->city = getIpCity();
        $this->save();
        return $this->result;
    }
}
