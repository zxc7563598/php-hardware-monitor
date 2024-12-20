<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9db543b411ea55c62befbd2465f7827a
{
    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'Hejunjie\\HardwareMonitor\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Hejunjie\\HardwareMonitor\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9db543b411ea55c62befbd2465f7827a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9db543b411ea55c62befbd2465f7827a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9db543b411ea55c62befbd2465f7827a::$classMap;

        }, null, ClassLoader::class);
    }
}
