<?php

namespace Hejunjie\HardwareMonitor\Linux;

use Exception;
use Hejunjie\HardwareMonitor\Heart\BaseMonitor;

class Memory extends BaseMonitor
{
    /**
     * 获取 Linux 系统内存使用情况
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
            // 获取内存信息
            $output = shell_exec('free -m');
            if (empty($output)) {
                throw new Exception("在 Linux 上检索内存信息失败。");
            }
            $lines = explode("\n", trim($output));
            foreach ($lines as $line) {
                $columns = preg_split('/\s+/', $line);
                if (strpos($columns[0], 'Mem') !== false) {
                    $memoryInfo['total'] = self::convertToMB($columns[1] . 'mb');  // 总内存（MB）
                    $memoryInfo['used'] = self::convertToMB($columns[2] . 'mb');   // 已使用内存（MB）
                    $memoryInfo['free'] = self::convertToMB($columns[3] . 'mb');   // 空闲内存（MB）
                    $memoryInfo['cached'] = self::convertToMB($columns[6] . 'mb'); // 文件缓存（MB）
                    $memoryInfo['buffers'] = self::convertToMB($columns[5] . 'mb'); // 缓存内存（MB）
                }
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
        return $memoryInfo;
    }
}
