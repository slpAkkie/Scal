<?php

namespace Scal;

class Loader
{
    /**
     * Индикатор, что загрузчик уже запущен.
     *
     * @var bool
     */
    protected static $booted = false;

    /**
     * Файл конфигурации по умолчанию.
     *
     * @var string
     */
    protected const DEFAULT_CONFIGURATION_FILENAME = 'autoload.conf.json';

    /**
     * Разделитель пространства имен.
     *
     * @var string
     */
    protected const NAMESPACE_SEPARATOR = '\\';

    /**
     * Карты пространств имен, загруженная из конфигурации.
     *
     * @var array<string, string|array<string>>
     */
    protected static $namespaceMap = [];

    /**
     * Получить путь к файлу конфигурации.
     *
     * @return string|null
     */
    protected static function getConfigurationFilePath(): ?string
    {
        $filePath = Path::glue(APP_ROOT_PATH, self::DEFAULT_CONFIGURATION_FILENAME);

        if (!file_exists($filePath)) {
            $filePath = Path::glue(SCAL_ROOT_PATH, self::DEFAULT_CONFIGURATION_FILENAME);

            if (!file_exists($filePath)) {
                $filePath = null;
            }
        }

        return $filePath;
    }

    /**
     * Загрузить конфигурацию из файла.
     *
     * @param string $file Путь к файлу с конфигурацией.
     * @return array<string, string|array<string>>
     * @throws \Exception
     */
    protected static function loadConfiguration(string $file): array
    {
        $configuration = json_decode(file_get_contents($file), true);

        if (gettype($configuration) !== 'array') {
            throw new \Exception('Файл конфигурации имеет неверную структуру');
        }

        if (key_exists('mapping', $configuration)) {
            return $configuration['mapping'];
        }

        return [];
    }

    /**
     * Запустить загрузчик.
     *
     * @return void
     * @throws \Exception
     */
    protected static function boot(): void
    {
        // Получаем путь к конфигурационному файлу.
        $configurationFile = self::getConfigurationFilePath();
        $namespaceMap = [];

        // Загружаем конфигурацию.
        if ($configurationFile !== null) {
            $namespaceMap = self::loadConfiguration($configurationFile);
        }

        // Приводим пути к единому образу.
        self::$namespaceMap = self::unifyMapping($namespaceMap);

        // Отмечаем загрузчик как уже запущенный.
        self::$booted = true;
    }

    /**
     * Привести карту пространств имен к единому образу.
     *
     * @param array<string, string|array<string>> $namespaceMap
     * @return array<string, string|array<string>>
     */
    protected static function unifyMapping(array $namespaceMap): array
    {
        $unifiedMap = [];

        foreach ($namespaceMap as $namespace => $path) {
            $parsedPath = gettype($path) === 'array'
                ? Path::parse(
                    array_map(
                        fn ($p) => Path::glue(APP_ROOT_PATH, $p),
                        $path
                    )
                )
                : Path::parse(Path::glue(APP_ROOT_PATH, $path));

            if ($parsedPath) {
                $unifiedMap[$namespace] = $parsedPath;
            }
        }

        return $unifiedMap;
    }

    /**
     * Разобрать класс на пространство имен и название класса.
     *
     * @param string $class
     * @return array<string>
     */
    protected static function explodeClass(string $class): array
    {
        $exploded = explode(self::NAMESPACE_SEPARATOR, $class);

        if (count($exploded) === 1) {
            $namespace = '';
            $class = $exploded[0];
        } else {
            $explodedNamespace = array_slice($exploded, 0, -1);
            $namespace = implode(self::NAMESPACE_SEPARATOR, $explodedNamespace) . self::NAMESPACE_SEPARATOR;
            $class = end($exploded);
        }

        return [
            $namespace,
            $class,
        ];
    }

    /**
     * Получить путь к файлу с классом.
     *
     * @param string $namespace
     * @param string $class
     * @return string
     */
    protected static function getClassFile(string $namespace, string $class): string
    {
        $path = Path::unify(Path::glue(APP_ROOT_PATH, $namespace));
        $remainPath = '';

        foreach (self::$namespaceMap as $n => $v) {
            if (str_starts_with($namespace, $n)) {
                $path = $v;
                $remainPath = Path::unify(substr($namespace, strlen($n)));
            }
        }

        $path = self::findClassFile($path, $remainPath, $class . '.php');

        return $path;
    }

    /**
     * Найти файл по указанным путям.
     *
     * @param string|array<string> $path
     * @param string $remain
     * @param string $file
     * @return string|null
     *
     * @throws \Exception
     */
    protected static function findClassFile(string|array $path, string $remain, string $file): ?string
    {
        return match (gettype($path)) {
            // Если $path строка, то пытаемся найти файл по этому пути.
            'string' => (function () use ($path, $remain, $file) {
                if (file_exists($filePath = Path::glue($path, $remain, $file))) {
                    return $filePath;
                }

                // Если файл не найден вернем null.
                return null;
            })(),
            // Если это массив, то перебираем все пути и ищем файл в них.
            'array' => (function () use ($path, $remain, $file) {
                foreach ($path as $p) {
                    if (file_exists($filePath = Path::glue($p, $remain, $file))) {
                        return $filePath;
                    }
                }

                // Если файл не найден вернем null.
                return null;
            })(),
        };
    }

    /**
     * Подключить класс.
     *
     * @param string $class
     * @return void
     * @throws \Exception
     */
    public static function load(string $class): void
    {
        // Если загрузчик еще не запущен - запустим его.
        if (!self::$booted) {
            self::boot();
        }

        $file_path = '';

        try {
            $file_path = self::getClassFile(...self::explodeClass($class));
        } catch (\Throwable $e) {
            throw $e;
        }

        if (!file_exists($file_path)) {
            throw new \Exception('Класс [' . $class . '] не найден');
        }

        require_once($file_path);
    }
}
