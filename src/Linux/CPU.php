<?php

namespace Hejunjie\HardwareMonitor\Linux;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;

class CPU extends BaseMonitor
{
    /**
     * 获取 Linux 系统 CPU 使用情况
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
        $output = shell_exec('top -bn1 | grep "Cpu(s)"');
        if (empty($output)) {
            throw new Exception("在 Linux 上检索 CPU 使用率失败。");
        }
        // 使用正则从输出中提取数值和对应的类别（如 us, sy, id）
        preg_match_all('/\s*(\d+\.\d+|\d+)\s+(\w+)/', $output, $matches);
        // 映射 CPU 使用情况
        $usageMapping = ['us' => 'user', 'sy' => 'sys', 'id' => 'idle', 'wa' => 'wait'];
        // 遍历匹配结果并赋值到相应字段
        foreach ($matches[2] as $index => $label) {
            if (isset($usageMapping[$label])) {
                $cpuUsage[$usageMapping[$label]] = (float)$matches[1][$index];
            }
        }
        return $cpuUsage;
    }

    /**
     * 获取 Linux 系统 CPU 信息
     * 
     * @return array CPU信息
     * @throws Exception 如果获取CPU信息失败，将抛出异常
     */
    public static function getCpuInfo(): array
    {
        $cpuInfo = [
            'model' => 'Unknown Model',          // CPU 型号
            'cores' => 0,                        // 总核心数
            'cores_per_socket' => 0,             // 每个插槽的核心数
            'logical_cores' => 0                 // 逻辑核心数
        ];
        // 执行 lscpu 命令并获取 CPU 信息
        $output = shell_exec('lscpu');
        if (empty($output)) {
            throw new Exception("在 Linux 上检索 CPU 信息失败。");
        }
        // 使用正则表达式提取 CPU 信息
        $modelMatches = [];
        $coreMatches = [];
        $corePerSocketMatches = [];
        $threadsPerCoreMatches = [];
        preg_match('/Model name:\s+(.*)/', $output, $modelMatches);
        preg_match('/^CPU\(s\):\s+(\d+)/m', $output, $coreMatches);
        preg_match('/Core\(s\) per socket:\s+(\d+)/', $output, $corePerSocketMatches);
        preg_match('/Thread\(s\) per core:\s+(\d+)/', $output, $threadsPerCoreMatches);
        // 格式化并填充 CPU 信息
        $cpuInfo['model'] = $modelMatches[1] ?? $cpuInfo['model'];
        $cpuInfo['cores'] = (int)($coreMatches[1] ?? 0);
        $cpuInfo['cores_per_socket'] = (int)($corePerSocketMatches[1] ?? 0);
        $cpuInfo['logical_cores'] = (int)($cpuInfo['cores'] * ($threadsPerCoreMatches[1] ?? 1));
        // 返回数据
        return $cpuInfo;
    }
}
