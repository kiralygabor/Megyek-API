<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit9f4502a8b701ace57d1b65951339cbee
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit9f4502a8b701ace57d1b65951339cbee', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit9f4502a8b701ace57d1b65951339cbee', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit9f4502a8b701ace57d1b65951339cbee::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
