<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2cb3625a5b461a61200bc1a00e78da7a
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Automattic\\WooCommerce\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Automattic\\WooCommerce\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/woocommerce/src/WooCommerce',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2cb3625a5b461a61200bc1a00e78da7a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2cb3625a5b461a61200bc1a00e78da7a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2cb3625a5b461a61200bc1a00e78da7a::$classMap;

        }, null, ClassLoader::class);
    }
}
