<?php

namespace Hejunjie\HardwareMonitor\Heart;

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

    /**
     * 转换大小单位为MB
     * 
     * @param mixed $size 大小单位
     * @return float|string 
     */
    protected static function convertToMB($size): mixed
    {
        // 去掉两端空格并转换为小写
        $size = trim(strtolower($size));
        // 匹配数字和单位（如M, Mi, G, Gi, T, Ti）
        if (preg_match('/^(\d+(\.\d+)?)\s*(b|bi?|k|kb?|ki?|m|mi?|mb?|g|gi?|gb?|t|ti?|tb?)$/', $size, $matches)) {
            $number = floatval($matches[1]);
            $unit = $matches[3];
            // 根据单位进行相应的转换
            switch ($unit) {
                case 'b':    // 字节
                case 'bi':   // 二进制字节
                    return round(($number / (1024 * 1024)), 2);  // 转换为MB
                case 'k':    // 千字节 (kB)
                case 'kb':   // 千字节
                    return round(($number / 1024), 2);  // 转换为MB
                case 'ki':   // 千二进制字节 (KiB)
                    return round(($number / 1024 / 1024), 2);  // 转换为MB
                case 'm':    // 兆字节 (MB)
                case 'mb':   // 兆字节
                    return round(($number), 2);  // 已经是MB
                case 'mi':   // 兆二进制字节 (MiB)
                    return round(($number * 1.048576), 2);  // 转换为MB
                case 'g':    // 千兆字节 (GB)
                case 'gb':   // 千兆字节
                    return round(($number * 1024), 2);  // 转换为MB
                case 'gi':   // 千兆二进制字节 (GiB)
                    return round(($number * 1024 * 1.048576), 2);  // 转换为MB
                case 't':    // 太字节 (TB)
                case 'tb':   // 太字节
                    return round(($number * 1024 * 1024), 2);  // 转换为MB
                case 'ti':   // 太二进制字节 (TiB)
                    return round(($number * 1024 * 1024 * 1.048576), 2);  // 转换为MB
                default:
                    return $number; // 默认返回原数值（防止单位错误的情况）
            }
        } else {
            // 如果匹配不到合法的格式，远样返回
            return $size;
        }
    }
}
