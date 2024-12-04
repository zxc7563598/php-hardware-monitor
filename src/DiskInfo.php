<?php

namespace Hejunjie\HardwareMonitor;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;
use Hejunjie\HardwareMonitor\Linux;
use Hejunjie\HardwareMonitor\MacOS;

/**
 * 硬盘相关
 * @package Hejunjie\HardwareMonitor
 */
class DiskInfo extends BaseMonitor
{
    /**
     * 获取当前系统硬盘相关信息。
     * 
     * @return array 根据操作系统类型执行相应的命令，返回包含硬盘信息的数组，字段包括：
     * - 'device' => string 硬盘设备名称（例如 /dev/sda，C:）
     * - 'model' => string 硬盘型号
     * - 'size' => float 硬盘总容量（MB）
     * - 'used' => float 已用容量（MB）
     * - 'free' => float 剩余容量（MB）
     * - 'capacity' => string 占用百分比
     * - 'filesystem' => string 文件系统类型
     * - 'mountpoint' => string 挂载点
     * 
     * @throws Exception 如果获取硬盘信息失败，将抛出异常。
     */
    public static function getDiskInfo()
    {
        $instance = self::getInstance();
        switch ($instance->osType) {
            case 'Linux':
                return Linux\Disk::getDiskInfo();
            case 'MacOS':
                return MacOs\Disk::getDiskInfo();
            default:
                throw new Exception("不支持的操作系统: {$instance->osType}");
        }
    }
}
