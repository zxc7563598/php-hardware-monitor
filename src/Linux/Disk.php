<?php

namespace Hejunjie\HardwareMonitor\Linux;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;

class Disk extends BaseMonitor
{
    /**
     * 获取 Linux 系统硬盘信息
     * 
     * @return array 硬盘信息
     * @throws Exception 如果获取硬盘信息失败，将抛出异常
     */
    public static function getDiskInfo(): array
    {
        $diskInfo = [];
        try {
            // 获取硬盘设备信息（lsblk）
            $lsblkOutput = shell_exec('lsblk -o NAME,SIZE,FSTYPE,MOUNTPOINT');
            if (empty($lsblkOutput)) {
                throw new Exception("在 Linux 上检索硬盘设备信息失败。");
            }
            // 解析 lsblk 输出
            $lsblk = [];
            $lines = explode("\n", $lsblkOutput);
            foreach ($lines as $line) {
                if (empty($line) || strpos($line, 'NAME') !== false) {
                    continue;
                }
                $parts = preg_split('/\s+/', $line);
                if (count($parts) >= 4) {
                    $lsblk[] = [
                        'device'    => $parts[0],          // 硬盘设备名
                        'size'      => $parts[1],          // 硬盘总容量
                        'fstype'    => $parts[2],          // 文件系统类型
                        'mountpoint' => $parts[3] ?? 'N/A' // 挂载点
                    ];
                }
            }
            // 获取分区使用情况（df）
            $dfOutput = shell_exec('df -h');
            if (empty($dfOutput)) {
                throw new Exception("在 Linux 上检索分区信息失败。");
            }
            // 解析 df 输出
            $dfInfo = [];
            $lines = explode("\n", $dfOutput);
            foreach ($lines as $line) {
                if (empty($line) || strpos($line, 'Filesystem') !== false) {
                    continue;
                }
                $parts = preg_split('/\s+/', $line);
                if (count($parts) >= 6) {
                    $dfInfo[] = [
                        'device'    => $parts[0],   // 硬盘设备名
                        'size'      => $parts[1],   // 硬盘总容量
                        'used'      => $parts[2],   // 已用容量
                        'available' => $parts[3],   // 剩余容量
                        'mounted'   => $parts[5],   // 挂载点
                    ];
                }
            }
            // 结合 lsblk 和 df 的数据
            foreach ($lsblk as &$_lsblk) {
                foreach ($dfInfo as $_dfInfo) {
                    if ($_lsblk['mountpoint'] == $_dfInfo['mounted']) {
                        $_lsblk['df'] = $_dfInfo;
                    }
                }
                // 整理数据
                $size = self::convertToMB($_lsblk['df']['size']);
                $free = self::convertToMB($_lsblk['df']['available']);
                $used = self::convertToMB($_lsblk['df']['used']);
                $diskInfo[] = [
                    'device' => $_lsblk['df']['device'],
                    'model' => '', // 可以根据实际情况进一步补充型号
                    'size' => $size,
                    'free' => $free,
                    'used' => $used,
                    'capacity'  => round((($used / $size) * 100), 2) . '%',
                    'filesystem' => $_lsblk['fstype'],
                    'mountpoint' => $_lsblk['df']['mounted']
                ];
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
        // 返回数据
        return $diskInfo;
    }
}
