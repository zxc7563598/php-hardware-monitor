<?php

namespace Hejunjie\HardwareMonitor;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;
use Hejunjie\HardwareMonitor\Linux;
use Hejunjie\HardwareMonitor\MacOS;

class NetworkTraffic extends BaseMonitor
{
    /**
     * 获取当前系统网络流量（流入和流出字节数）
     * 
     * @return array
     * @throws Exception
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
