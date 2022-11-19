<?php

namespace Scal;

class Path
{
    /**
     * Директории, которые будут проигнорированы.
     *
     * @var array<string>
     */
    private const IGNORE_DIRECTORIES = [
        '.', '..', '.git', '.vscode', '.idea',
    ];

    /**
     * Получить папки для рекурсивных путей.
     *
     * @param string $path
     * @return array<string>
     */
    public static function getDirsForRecursivePath(string $path): array
    {
        $subdirectories = [
            $path,
        ];

        foreach (scandir($path) as $entry) {
            if (in_array($entry, self::IGNORE_DIRECTORIES)) {
                continue;
            }

            $entry = self::glue($path, $entry);

            if (is_dir($entry)) {
                $subdirectories = array_merge(
                    self::getDirsForRecursivePath(Path::glue($entry)),
                    $subdirectories
                );
            }
        }

        return $subdirectories;
    }

    /**
     * Привести путь к единому образу для загрузчика.
     *
     * @param string $path
     * @return string|array<string>
     */
    public static function unify(string $path): string|array
    {
        $path = preg_replace('/\\\/', DIRECTORY_SEPARATOR, $path);

        if (str_ends_with($path, '*')) {
            $path = self::getDirsForRecursivePath(
                // Отрезать последние два символа указывающих на рекурсивный путь.
                substr($path, 0, -2)
            );
        }

        return $path;
    }

    /**
     * Привести массив путей к единому образу для загрузчика.
     *
     * @param array<string> $paths
     * @return array<string>
     */
    public static function parsePaths(array $paths): array
    {
        foreach ($paths as $k => $v) {
            $parsed = self::parse($v);

            if (is_array($parsed)) {
                $paths = array_merge($paths, $parsed);
            } else {
                $paths[$k] = $parsed;
            }
        }

        return $paths;
    }

    /**
     * Привести пути или массив путей к единому образу для загрузчика.
     *
     * @param string|array<string> $arg
     * @return string|array<string>|null
     */
    public static function parse(string|array $arg): string|array|null
    {
        return match (gettype($arg)) {
            'string' => self::unify($arg),
            'array' => self::parsePaths($arg),
            default => null,
        };
    }

    /**
     * Склеить строки в строку пути.
     *
     * @param array<string> $args
     * @return string
     */
    public static function glue(...$args): string
    {
        return implode(DIRECTORY_SEPARATOR, array_filter($args));
    }
}
