<?php
namespace ZhzyTo\GeneralOauth2\Providers;

use ZhzyTo\GeneralOauth2\Utils\Config;

class BaseProvider
{
    protected Config $config_class;

    /**
     * 设置config对象
     * BaseProvider constructor.
     * @param array $conf
     */
    public function __construct(array $conf) {
        $this->config_class = new Config($conf);
    }
}