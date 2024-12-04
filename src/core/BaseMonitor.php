<?php

namespace HardwareMonitor\Core;

abstract class BaseMonitor
{
    private static $instances = []; // 存储单例实例
    protected $osType;             // 系统类型

    // 私有构造函数，防止直接实例化
    private function __construct()
    {
        $osType = PHP_OS_FAMILY;
        if ($osType === 'Windows') {
            $this->osType = 'WIN';
        } elseif ($osType === 'Unix') {
            $this->osType = 'Linux';
        } elseif ($osType === 'Darwin') {
            $this->osType = 'MacOS';
        } else {
            $system_info = php_uname('s');
            if (strpos($system_info, 'Windows') !== false) {
                $this->osType = 'WIN';
            } elseif (strpos($system_info, 'Linux') !== false) {
                $this->osType = 'Linux';
            } elseif (strpos($system_info, 'Darwin') !== false) {
                $this->osType = 'MacOS';
            } else {
                $this->osType = 'Unknown';
            }
        }
    }

    // 禁止克隆
    private function __clone() {}

    // 单例模式：获取实例
    public static function getInstance(): self
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}
