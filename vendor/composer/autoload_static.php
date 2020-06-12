<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdafae07238ceb7355c3064c730a32529
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SamiXSous\\Printful\\' => 19,
        ),
        'P' => 
        array (
            'Printful\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SamiXSous\\Printful\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Printful\\' => 
        array (
            0 => __DIR__ . '/..' . '/printful/php-api-sdk/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdafae07238ceb7355c3064c730a32529::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdafae07238ceb7355c3064c730a32529::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
