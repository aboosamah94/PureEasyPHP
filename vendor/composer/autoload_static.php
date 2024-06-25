<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5f5751cd9395a16ba53a8bbc17166da7
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pureeasyphp\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pureeasyphp\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit5f5751cd9395a16ba53a8bbc17166da7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5f5751cd9395a16ba53a8bbc17166da7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5f5751cd9395a16ba53a8bbc17166da7::$classMap;

        }, null, ClassLoader::class);
    }
}
