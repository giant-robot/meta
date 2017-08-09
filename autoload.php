<?php
/**
 * Register a dead-simple PSR-4 autoloader to handles classes in the
 * GiantRobot\Meta namespace.
 */
spl_autoload_register(function ($class) {

    $ns = "GiantRobot\\Meta";
    $src = __DIR__ . '/src/';

    if (0 !== strpos($class, $ns))
    {
        return null;
    }

    $file = $src . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (! file_exists($file) || ! is_readable($file))
    {
        return null;
    }

    require $file;

    return true;
});

/**
 * Include rogue files.
 */
require __DIR__.'/src/GiantRobot/Meta/hooks.php';
