<?php
declare (strict_types=1);

namespace app\controller\common\translate;

class Translate
{
    /**
     * @var array config
     */
    public $config = [
        'apiUrl' => '',
        'appKey' => '',
        'secKey' => '',
        'signType' => 'v3'
    ];

    /**
     * @var string source language
     */
    public $from;

    /**
     * @var string target language
     */
    public $to;

    /**
     * @var string Content of translation
     */
    public $content;

    /**
     * @var mixed The result of translation
     */
    public $result;

    /**
     * Translate.class constructor.
     * @param mixed $config
     */
    public function __construct($config = null)
    {
        $this->setConfig($config ?? config('common')['translate']);
    }

    /**
     * Set source language
     * @param string $from
     * @return Translate
     */
    public function from(string $from = 'auto'): Translate
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Set target language
     * @param string $to
     * @return Translate
     */
    public function to(string $to): Translate
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Set up translation
     * @param string $content
     * @return Translate
     */
    public function content(string $content): Translate
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set the result of translate
     * @param $result
     * @return Translate
     */
    public function setResult($result): Translate
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Return original data
     * @return mixed
     */
    public function getResult()
    {
        if (!$this->result) $this->doRequest();
        return $this->result;
    }

    /**
     * getConfig
     * @param string $name
     * @return array
     */
    public function getConfig($name = '')
    {
        return empty($name) ? $this->config : $this->config[$name];
    }

    /**
     * setConfig
     * @param array $config
     * @return Translate
     */
    public function setConfig(array $config): Translate
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * doRequest
     * @return bool|string
     */
    private function doRequest()
    {
        $ret_url = $this->getConfig('apiUrl') . '?' . http_build_query($this->parseRequestArgs());
        $this->setResult(file_get_contents($ret_url));
        return $this->result;
    }

    /**
     * Return result of Array
     * @return array
     */
    public function toArray(): Array
    {
        return json_decode($this->getResult(), true);
    }

    /**
     * Parse request args
     * @return array
     */
    private function parseRequestArgs(): array
    {
        $salt = create_guid();
        $args = [
            'q' => $this->content,
            'appKey' => $this->getConfig('appKey'),
            'salt' => $salt,
        ];
        $args['from'] = $this->from;
        $args['to'] = $this->to;
        $args['signType'] = $this->getConfig('signType');
        $curTime = strtotime("now");
        $args['curtime'] = $curTime;
        $signStr = $this->getConfig('appKey') . truncate($this->content) . $salt . $curTime . $this->getConfig('secKey');
        $args['sign'] = hash("sha256", $signStr);
        return $args;
    }
}
