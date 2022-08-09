<?php
namespace ZhzyTo\GeneralOauth2;

/**
 * Class Application
 * @package ZhzyTo\GeneralOauth2
 */
class Application
{
    /**
     * 实例化对应类
     * @param $name
     * @param array $conf
     * @return mixed
     */
    public static function create($name, array $conf)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $name));
        $namespace = str_replace(' ', '', $value);

        // 所有动作地址
        $application = "\\ZhzyTo\\GeneralOauth2\\Providers\\{$namespace}";

        return new $application($conf);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::create($name, ...$arguments);
    }
}