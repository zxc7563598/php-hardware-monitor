<?php

namespace Hejunjie\HardwareMonitor\MacOS;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;

class Memory extends BaseMonitor
{
    /**
     * 获取 MacOS 系统内存使用情况
     * 
     * @return array 内存使用状态
     * @throws Exception 如果获取内存信息失败，将抛出异常
     */
    public static function getMemoryUsage(): array
    {
        $memoryInfo = [
            'total' => 0,
            'used' => 0,
            'free' => 0,
            'cached' => 0,
            'buffers' => 0
        ];
        try {
            $output = shell_exec('sysctl -n hw.memsize');
            $memoryInfo['total'] = self::convertToMB(trim($output) . 'b');  // 总内存（MB）
            // 获取空闲内存
            $output = shell_exec('vm_stat');
            preg_match('/Pages free:\s+(\d+)/', $output, $matches);
            $memoryInfo['free'] = self::convertToMB(($matches[1] * 4096) . 'b'); // 空闲内存（MB）
            // 计算已用内存
            $memoryInfo['used'] = $memoryInfo['total'] - $memoryInfo['free']; // 已用内存（MB）
            // macOS 不直接提供缓存和缓冲区的命令
            $memoryInfo['cached'] = 0;
            $memoryInfo['buffers'] = 0;
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
        return $memoryInfo;
    }
}
