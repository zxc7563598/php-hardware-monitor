<?php

namespace Hejunjie\HardwareMonitor;

use Exception;
use Hejunjie\HardwareMonitor\Heart\BaseMonitor;
use Hejunjie\HardwareMonitor\Linux;
use Hejunjie\HardwareMonitor\MacOS;

/**
 * 内存相关
 * @package Hejunjie\HardwareMonitor
 */
class MemoryInfo extends BaseMonitor
{

    /**
     * 获取当前系统内存使用状态
     * 
     * @return array 包含内存信息的数组，字段包括：
     * - 'total' => int 总内存（MB）
     * - 'used' => int 已用内存（MB）
     * - 'free' => int 空闲内存（MB）
     * - 'cached' => int 缓存内存（MB）
     * - 'buffers' => int 缓冲区内存（MB）
     * @throws Exception 如果获取内存信息失败，将抛出异常。
     */
    public static function getMemoryUsage(): array
    {
        $instance = self::getInstance();
        switch ($instance->osType) {
            case 'Linux':
                return Linux\Memory::getMemoryUsage();
            case 'MacOS':
                return MacOs\Memory::getMemoryUsage();
            default:
                throw new Exception("不支持的操作系统: {$instance->osType}");
        }
    }
}
