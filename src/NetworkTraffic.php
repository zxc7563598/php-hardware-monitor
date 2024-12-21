<?php

namespace Hejunjie\HardwareMonitor;

use Exception;
use Hejunjie\HardwareMonitor\Heart\BaseMonitor;
use Hejunjie\HardwareMonitor\Linux;
use Hejunjie\HardwareMonitor\MacOS;

/**
 * 网络相关
 * @package Hejunjie\HardwareMonitor
 */
class NetworkTraffic extends BaseMonitor
{
    /**
     * 获取当前系统网络流量（流入和流出字节数）
     * 
     * @return array 该方法根据操作系统类型执行相应的命令，返回一个包含以下字段的数组，可通过多次调用来计算网络调用，字段包括：
     * - `name`：`string` 网卡名称
     * - `in`：`float` 网络流入数（MB）
     * - `out`：`float` 网络流出数（MB）
     * @throws Exception 如果获取网络信息失败，将抛出异常。
     */
    public static function getNetworkTraffic(): array
    {
        $instance = self::getInstance();
        switch ($instance->osType) {
            case 'Linux':
                return Linux\Network::getTraffic();
            case 'MacOS':
                return MacOs\Network::getTraffic();
            default:
                throw new Exception("不支持的操作系统: {$instance->osType}");
        }
    }
}
