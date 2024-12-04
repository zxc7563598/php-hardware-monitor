<?php

namespace Hejunjie\HardwareMonitor\MacOS;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;

class Network extends BaseMonitor
{
    /**
     * 获取 MacOS 系统网络流量
     *
     * @return array
     * @throws Exception
     */
    public static function getTraffic(): array
    {
        $output = [];
        exec("netstat -ib", $output);
        if (empty($output) || count($output) <= 1) {
            throw new Exception("在 Mac 上检索网络信息失败。");
        }
        $trafficData = [];
        $seenNames = [];
        foreach (array_slice($output, 1) as $line) {
            $data = preg_split('/\s+/', trim($line));
            if (count($data) < 11 || in_array($data[0], $seenNames)) continue;
            $trafficData[] = [
                'name' => $data[0],
                'in' => self::convertToMB($data[6] . 'b'),
                'out' => self::convertToMB($data[9] . 'b'),
            ];
            $seenNames[] = $data[0];
        }
        return $trafficData;
    }
}
