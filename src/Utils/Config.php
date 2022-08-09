<?php
namespace ZhzyTo\GeneralOauth2\Utils;

/**
 * Class Config
 * @package ZhzyTo\GeneralOauth2\Utils
 */
class Config
{
    protected array $conf;

    public function __construct(array $conf)
    {
        $this->conf = $conf;
    }

    /**
     * 配置项是否存在
     * @param string $key
     * @return bool
     */
    public function has(string $key) :bool {
        return (bool) $this->get($key);
    }

    /**
     * 获取配置项的值
     * @param string $key
     * @return array|false|mixed
     */
    public function get(string $key) {
        // 键名全部转为小写
        $conf = array_change_key_case($this->conf,CASE_LOWER);

        $key = strtolower($key);

        return $conf[$key] ?? false;
    }
}