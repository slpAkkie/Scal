<?php

namespace Uwi\Loader;

/**
 * ---------------------------------------------------------------------------
 * Реализация автозагрузки классов для платформы Uwi, соответствующая PSR-4
 * ---------------------------------------------------------------------------
 *
 * @author Alexandr Shamanin <@slpAkkie>
 * @package uwi-loader
 *
 */
class Loader
{
    /**
     * Имя функции, которая будет зарегистрирована, как функция автозагрузки классов
     *
     * @var string
     */
    private const SPL_AUTOLOAD_FUNCTION_NAME = 'loadClass';

    /**
     * Массив префиксов и путей, по которым искать файлы для них
     *
     * @var array
     */
    private static array $prefixes = array();

    /**
     * Зарегистрировать автозагрузчик классов
     *
     * @return void
     * @throws \Uwi\Exceptions\LoaderCannotBeRegisteredException
     */
    public static function register(): void
    {
        try {
            spl_autoload_register([self::class, self::SPL_AUTOLOAD_FUNCTION_NAME]);
        } catch (\Throwable $e) {
            require_once __DIR__ . '/Exceptions/LoaderCannotBeRegisteredException.php';
            throw new Exceptions\LoaderCannotBeRegisteredException($e);
        }
    }

    /**
     * Прочитать конфигурацию для автозагрузки из JSON файла
     * и добавить в автозагрузчик
     *
     * @return void
     * @throws \Uwi\Exceptions\ConfigurationCannotBeReadException
     * @throws \Uwi\Exceptions\JsonSchemaException
     */
    public static function fromJson(string $path): void
    {
        $path = realpath($path);

        $jsonRootPath = dirname($path);
        $jsonContent = @file_get_contents($path);
        if ($jsonContent === false) {
            require_once __DIR__ . '/Exceptions/ConfigurationCannotBeReadException.php';
            throw new Exceptions\ConfigurationCannotBeReadException(error_get_last()['message']);
        }

        $parsedArray = json_decode($jsonContent, true);
        if (is_null($parsedArray) || !is_array($parsedArray)) {
            require_once __DIR__ . '/Exceptions/JsonSchemaException.php';
            throw new Exceptions\JsonSchemaException('Json schema cannot be decoded');
        }

        if (!key_exists('psr-4', $parsedArray)) {
            require_once __DIR__ . '/Exceptions/JsonSchemaException.php';
            throw new Exceptions\JsonSchemaException('Key [psr-4] doesn\'t exists');
        }

        foreach ($parsedArray['psr-4'] as $prefix => $value) {
            if (key_exists($prefix, self::$prefixes)) {
                self::addPath($prefix, $value, $jsonRootPath);
            } else {
                self::addPrefix($prefix, $value, $jsonRootPath);
            }
        }
    }

    /**
     * Добавить путь для префикса
     *
     * @param string $prefix
     * @param string $path
     * @param string $rootPath
     * @return void
     */
    public static function addPath(string $prefix, string $path, string $rootPath = ''): void
    {
        if (in_array($path, self::$prefixes[$prefix])) {
            return;
        }

        self::$prefixes[$prefix][] = self::concatPath($rootPath, $path);
    }

    /**
     * Добавить пути для префикса
     *
     * @param string $prefix
     * @param array $path
     * @param string $rootPath
     * @return void
     */
    public static function addPaths(string $prefix, array $paths, string $rootPath = ''): void
    {
        $paths = array_filter($paths, function ($path) use ($prefix) {
            return !in_array($path, self::$prefixes[$prefix]);
        });

        $paths = array_map(fn ($path) => self::concatPath($rootPath, $path), $paths);

        self::$prefixes[$prefix] = array_merge(self::$prefixes[$prefix], $paths);
    }

    /**
     * Соединяет два пути
     *
     * @param string $str1
     * @param string $str2
     * @return string
     */
    private static function concatPath(string $str1, string $str2): string
    {
        return rtrim($str1, '\\/') . '/' . trim($str2, '\\/');
    }

    /**
     * Добавить префикс и пути к нему
     *
     * @param string $prefix
     * @param string $path
     * @param string $rootPath
     * @return void
     */
    public static function addPrefix(string $prefix, string|array $path, string $rootPath = ''): void
    {
        if (key_exists($prefix, self::$prefixes)) {
            return;
        }

        self::$prefixes[$prefix] = array();

        if (is_array($path)) {
            self::addPaths($prefix, $path, $rootPath);
        } else {
            self::addPath($prefix, $path, $rootPath);
        }
    }

    /**
     * Загрузить класс
     *
     * @param string $class
     * @return void
     */
    public static function loadClass(string $class): void
    {
        foreach (self::$prefixes as $prefix => $paths) {
            if (!str_starts_with($class, $prefix)) {
                continue;
            }

            foreach ($paths as $path) {
                $classPath = self::getPossibleClassPath($class, $prefix, $path);

                if (file_exists($classPath)) {
                    @require_once $classPath;
                }
            }
        }
    }

    /**
     * Собрать возможный путь до файла с классом
     *
     * @param string $class
     * @param string $prefix
     * @param string $path
     * @return string
     */
    private static function getPossibleClassPath(string $class, string $prefix, string $path): string
    {
        $classPath = str_replace(
            array($prefix, '\\',),
            array($path . '/', '/'),
            $class
        );

        $classPath .= '.php';

        return $classPath;
    }
}
