<?php

namespace Hejunjie\HardwareMonitor\MacOS;

use Exception;
use Hejunjie\HardwareMonitor\Heart\BaseMonitor;

class Disk extends BaseMonitor
{
    /**
     * 获取 MacOS 系统硬盘信息
     * 
     * @return array 硬盘信息
     * @throws Exception 如果获取硬盘信息失败，将抛出异常
     */
    public static function getDiskInfo(): array
    {
        $diskInfo = [];
        try {
            // 获取磁盘信息（diskutil list）
            $diskutilOutput = [];
            exec("diskutil list", $diskutilOutput);
            if (empty($diskutilOutput)) {
                throw new Exception("在 macOS 上检索硬盘信息失败。");
            }
            $diskutilData = [];
            $model = '';
            $filesystem = '';
            $i = 0;
            foreach ($diskutilOutput as $line) {
                // 查找设备行，提取硬盘名称和类型（internal, external等）
                if (preg_match('/^\/dev\/([a-zA-Z0-9]+)\s\((.*)\):/', $line, $matches)) {
                    $device = $matches[1];
                    $filesystem = $matches[2];
                    // 获取硬盘型号
                    $mediaOutput = shell_exec("diskutil info $device");
                    if (preg_match('/Device \/ Media Name:\s+(.*)/', $mediaOutput, $media)) {
                        $model = $media[1];
                    }
                    $i = 0; // 设备信息开始，重置计数器
                } else {
                    if ($i > 0) {
                        $matches = preg_split('/\s+/', strrev($line));
                        if (count($matches) > 3) {
                            $diskutilData[] = [
                                'device'    => strrev($matches[0]), // 硬盘名称
                                'model'     => $model ?? '',        // 硬盘型号
                                'size'      => preg_replace('/[^\d.]/', '', strrev($matches[2])) . strrev($matches[1]), // 容量
                                'free'      => round(0, 2),         // 空闲空间（初始值为0）
                                'used'      => round(0, 2),         // 已用空间（初始值为0）
                                'filesystem' => $filesystem ?? '',   // 文件系统类型
                                'mountpoint' => '',                  // 挂载点（初始为空）
                            ];
                        }
                    }
                    $i++;
                }
            }
            // 获取分区使用情况（df -h）
            $dfOutput = [];
            exec("df -h", $dfOutput);
            if (empty($dfOutput)) {
                throw new Exception("在 macOS 上检索分区信息失败。");
            }
            $dfData = [];
            foreach ($dfOutput as $line) {
                if (empty($line) || strpos($line, 'Filesystem') !== false) {
                    continue;
                }
                $dfData[] = preg_split('/\s+/', $line);
            }
            // 合并 diskutil 和 df 的数据
            foreach ($diskutilData as &$disk) {
                foreach ($dfData as $df) {
                    if ($df[0] == '/dev/' . $disk['device']) {
                        $disk['size'] = self::convertToMB($df[1]);  // 总容量
                        $disk['free'] = self::convertToMB($df[3]);  // 可用空间
                        $disk['used'] = self::convertToMB($df[2]);  // 已用空间
                        $disk['capacity'] = $df[4];                 // 容量使用率
                        $disk['mountpoint'] = $df[8];               // 挂载点
                        $diskInfo[] = $disk;
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
        // 返回数据
        return $diskInfo;
    }
}
