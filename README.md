# 硬件信息 Composer 包

这个 Composer 包提供了一种简单而高效的方式来获取硬件信息，如 CPU 详情、内存使用情况、磁盘空间和网络流量，支持 Linux 和 macOS 系统。它封装了系统命令来获取这些数据，并以结构化的格式返回。

> Windows 系统测试中，近期更新

## 功能

- 获取 CPU 模型、核心数和逻辑核心数。
- 获取系统内存使用情况（总内存、已用内存、空闲内存、缓存内存、缓冲区内存）。
- 获取详细的磁盘信息，包括容量、已用空间和挂载点。
- 获取实时的网络流量统计。
- 支持 Linux 和 macOS 操作系统。

## 系统要求

- PHP 8.0 或更高版本
- 支持 Linux 或 macOS 操作系统
- 需要使用 Composer 进行依赖管理

## 安装

你可以通过 Composer 安装这个包。在项目目录下运行以下命令：

```bash
composer require hejunjie/hardware-monitor
```

## 使用方法

### 获取 CPU 信息

要获取 CPU 信息（如模型、核心数、逻辑核心数）：

```php
use Hejunjie\HardwareMonitor\CPUInfo;

$cpuInfo = CPUInfo::getCpuInfo();
echo 'CPU 模型: ' . $cpuInfo['model'];
echo '物理核心数: ' . $cpuInfo['cores'];
echo '逻辑核心数: ' . $cpuInfo['logical_cores'];
echo '每个插槽的核心数: ' . $cpuInfo['cores_per_socket'];
```

### 获取 CPU 使用情况

获取 CPU 使用情况：

```php
use Hejunjie\HardwareMonitor\CPUInfo;

$cpuUsage = CPUInfo::getCpuUsage();
echo '用户空间占用 CPU 时间的百分比: ' . $cpuUsage['user'];
echo '内核空间占用 CPU 时间的百分比: ' . $cpuUsage['sys'];
echo '空闲时间的百分比: ' . $cpuUsage['idle'];
echo '等待 I/O 操作的时间百分比: ' . $cpuUsage['wait'];
```

### 获取内存使用情况

要获取内存使用情况：

```php
use Hejunjie\HardwareMonitor\MemoryInfo;

$memoryInfo = MemoryInfo::getMemoryUsage();
echo '总内存: ' . $memoryInfo['total'] . ' MB';
echo '已用内存: ' . $memoryInfo['used'] . ' MB';
echo '空闲内存: ' . $memoryInfo['free'] . ' MB';
echo '缓存内存: ' . $memoryInfo['cached'] . ' MB';
echo '缓冲区内存: ' . $memoryInfo['buffers'] . ' MB';
```

### 获取磁盘信息

要获取磁盘信息（如设备名称、大小、空闲空间）：

```php
use Hejunjie\HardwareMonitor\DiskInfo;

$diskInfo = DiskInfo::getDiskInfo();
foreach ($diskInfo as $disk) {
    echo '硬盘设备名称: ' . $disk['device'];
    echo '硬盘型号: ' . $disk['model'];
    echo '大小: ' . $disk['size'] . ' MB';
    echo '已用: ' . $disk['used'] . ' MB';
    echo '空闲: ' . $disk['free'] . ' MB';
    echo '占用百分比: ' . $disk['capacity'];
    echo '文件系统类型: ' . $disk['filesystem'];
    echo '挂载点: ' . $disk['mountpoint'];
}
```

### 获取网络流量

要获取网络流量统计（如流入和流出数据）：

```php
use Hejunjie\HardwareMonitor\NetworkTraffic;

$networkTraffic = NetworkTraffic::getNetworkTraffic();
foreach ($networkTraffic as $network) {
    echo '网络接口: ' . $network['name'];
    echo '流入流量: ' . $network['in'] . ' MB';
    echo '流出流量: ' . $network['out'] . ' MB';
}
```