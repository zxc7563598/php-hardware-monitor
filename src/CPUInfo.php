<?php

namespace Hejunjie\HardwareMonitor;

use Exception;
use Hejunjie\HardwareMonitor\Core\BaseMonitor;
use Hejunjie\HardwareMonitor\Linux;
use Hejunjie\HardwareMonitor\MacOS;

/**
 * CPU相关
 * @package Hejunjie\HardwareMonitor
 */
class CPUInfo extends BaseMonitor
{
    /**
     * 获取当前系统的 CPU 使用状态。
     * 
     * @return array 根据操作系统类型执行相应的命令，返回一个包含以下字段的关联数组：
     * - `user` => `float` 用户空间占用 CPU 时间的百分比
     * - `sys` => `float` 内核空间占用 CPU 时间的百分比
     * - `idle` => `float` CPU 空闲时间的百分比
     * - `wait` => `float` CPU 等待 I/O 操作的时间百分比
     * @throws Exception 如果在获取 CPU 使用信息时出现错误，将抛出异常。
     */
    public static function getCpuUsage()
    {
        $instance = self::getInstance();
        switch ($instance->osType) {
            case 'Linux':
                return Linux\CPU::getCpuUsage();
            case 'MacOS':
                return MacOs\CPU::getCpuUsage();
            default:
                throw new Exception("不支持的操作系统: {$instance->osType}");
        }
    }

    /**
     * 获取当前系统的 CPU 信息，包括型号、物理核心数、逻辑核心数以及每个插槽的核心数（Linux 特有）。
     *
     * @return array 根据操作系统类型执行相应的命令，返回一个包含以下字段的关联数组：
     * - `model` => `string` CPU 型号（例如 "Intel(R) Core(TM) i7-9700K CPU @ 3.60GHz"）
     * - `cores` => `int` 物理核心数（例如 4）
     * - `logical_cores` => `int` 逻辑核心数（例如 8）
     * - `cores_per_socket` => `int` 每个插槽的核心数，仅在 Linux 上有值（例如 4）
     * 
     * @throws Exception 如果在获取 CPU 信息时出现错误（例如命令执行失败），将抛出异常。
     */
    public static function getCpuInfo()
    {
        $instance = self::getInstance();
        switch ($instance->osType) {
            case 'Linux':
                return Linux\CPU::getCpuInfo();
            case 'MacOS':
                return MacOs\CPU::getCpuInfo();
            default:
                throw new Exception("不支持的操作系统: {$instance->osType}");
        }
    }
}
