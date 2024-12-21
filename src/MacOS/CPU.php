<?php

namespace Hejunjie\HardwareMonitor\MacOS;

use Exception;
use Hejunjie\HardwareMonitor\Heart\BaseMonitor;

class CPU extends BaseMonitor
{
    /**
     * 获取 MacOS 系统 CPU 使用情况
     * 
     * @return array CPU使用状态
     * @throws Exception 如果获取CPU使用情况失败，将抛出异常
     */
    public static function getCpuUsage(): array
    {
        $cpuUsage = [
            'user' => 0.0,  // 用户空间占用
            'sys' => 0.0,   // 内核空间占用
            'idle' => 0.0,  // 空闲时间
            'wait' => 0.0   // 等待时间
        ];
        // 执行 top 命令并获取 CPU 占用信息
        $output = shell_exec('top -l 1 -s 0 | grep "CPU usage"');
        if (empty($output)) {
            throw new Exception("在 macOS 上检索 CPU 使用率失败。");
        }
        // 使用正则从输出中提取各个 CPU 占用值
        preg_match_all('/(\d+\.\d+)% (\w+)/', $output, $matches);
        // 映射 CPU 使用情况
        $usageMapping = ['user' => 0, 'sys' => 1, 'idle' => 2, 'wait' => 3];
        // 遍历匹配结果并赋值到相应字段
        foreach ($usageMapping as $key => $index) {
            $cpuUsage[$key] = isset($matches[1][$index]) ? (float)$matches[1][$index] : 0.0;
        }
        return $cpuUsage;
    }

    /**
     * 获取 MacOS 系统 CPU 信息
     * 
     * @return array CPU信息
     * @throws Exception 如果获取CPU信息失败，将抛出异常
     */
    public static function getCpuInfo(): array
    {
        $cpuInfo = [
            'model' => 'Unknown Model',         // CPU 型号
            'cores' => 0,                       // 物理核心数
            'logical_cores' => 0,               // 逻辑核心数
            'cores_per_socket' => 0,            // 每个插槽的核心数
        ];
        try {
            // 获取 CPU 型号
            $output = shell_exec('sysctl -n machdep.cpu.brand_string');
            if (empty($output)) {
                throw new Exception("在 macOS 上检索 CPU 型号失败。");
            }
            $cpuInfo['model'] = trim($output);
            // 获取物理核心数
            $output = shell_exec('sysctl -n hw.physicalcpu');
            if (empty($output)) {
                throw new Exception("在 macOS 上检索物理核心数失败。");
            }
            $cpuInfo['cores'] = (int)trim($output);
            // 获取逻辑核心数
            $output = shell_exec('sysctl -n hw.logicalcpu');
            if (empty($output)) {
                throw new Exception("在 macOS 上检索逻辑核心数失败。");
            }
            $cpuInfo['logical_cores'] = (int)trim($output);
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
        // 返回数据
        return $cpuInfo;
    }

    /**
     * 获取 MacOS 系统 CPU 负载
     * 
     * @return array CPU负载信息
     */
    public static function getCpuLoad(): array
    {
        $load = sys_getloadavg();
        $cpuLoad = [
            '1min' => 0,
            '5min' => 0,
            '15min' => 0
        ];
        if ($load) {
            $cpuLoad = [
                '1min' => round($load[0], 2),
                '5min' => round($load[1], 2),
                '15min' => round($load[2], 2)
            ];
        }
        return $cpuLoad;
    }
}
