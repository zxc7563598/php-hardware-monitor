<?php

namespace Hejunjie\HardwareMonitor\Linux;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;

class Network extends BaseMonitor
{
    /**
     * 获取 Linux 系统网络流量
     *
     * @return array
     * @throws Exception
     */
    public static function getTraffic(): array
    {
        $output = [];
        exec('cat /proc/net/dev', $output);
        if (empty($output) || count($output) <= 2) {
            throw new Exception("在 Linux 上检索网络信息失败。");
        }
        $trafficData = [];
        foreach (array_slice($output, 2) as $line) {
            $data = preg_split('/\s+/', trim($line));
            $trafficData[] = [
                'name' => rtrim($data[0], ':'),
                'in' => self::convertToMB($data[1] . 'b'),
                'out' => self::convertToMB($data[9] . 'b'),
            ];
        }
        return $trafficData;
    }
}
