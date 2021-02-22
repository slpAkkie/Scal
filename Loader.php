<?php

/*
|
|--------------------------------------------------
| Scal
|--------------------------------------------------
|
| General class of Scal.
| Here is all the logic for loading the classes.
|
| Author: Alexandr Shamanin (@slpAkkie)
| Version: 2.0.0
|
*/

namespace Scal;

use Scal\Exceptions\ClassNotFoundException;
use Scal\Exceptions\ConfigurationCannotBeReadException;
use Scal\Exceptions\ConfigurationNotFoundException;
use Scal\Exceptions\FileNotFoundException;
use Scal\Exceptions\WrongConfigurationException;

class Loader
{
  /**
   * Is Loader has been initialized
   *
   * @var bool
   */
  private static $initialized = false;

  /**
   * Default configuration file name
   *
   * @var string
   */
  private static $cfg_name = 'Scal.json';

  /**
   * Custom configuration file name specified manually
   * or null if it's not.
   * Contains absolute file path from project's root directory
   *
   * @var null|string
   */
  public static $cfg_path = null;

  /**
   * Scal configuration
   *
   * @var array
   */
  private static $configuration = array(
    "nsp"     => array(),
    "options" => array(
      "suffixes"  => array()
    )
  );

  /**
   * Initialize Scal
   *
   * @return void
   *
   * @throws Scal\Exceptions\ConfigurationNotFoundException
   * @throws Scal\Exceptions\ConfigurationCannotBeReadException
   */
  public static function init(): void
  {
    // Define data about configuration file by default
    $cfg_path = SCAL_REAL_PATH;
    $cfg_name = self::$cfg_name;
    $cfg_full_path = $cfg_path . $cfg_name;

    // Check if configuration path was set manually
    if (self::$cfg_path !== null) {
      // Normilize the path
      self::$cfg_path = self::fixDirSeparator(self::$cfg_path);

      // Extract configuration name from its path
      $cfg_path = dirname(self::$cfg_path);
      $cfg_name = basename(self::$cfg_path);

      // Build path
      if ($cfg_path === '.') $cfg_full_path = $cfg_name;
      else $cfg_full_path = SCAL_EXECUTED_IN . $cfg_path . DIRECTORY_SEPARATOR . $cfg_name;
    }



    // Check if configuration file doesn't exists
    if (!file_exists($cfg_full_path)) throw new ConfigurationNotFoundException();

    // Else try to read it
    $configuration = json_decode(file_get_contents($cfg_full_path), true);
    if (gettype($configuration) !== 'array') throw new ConfigurationCannotBeReadException();


    // Normilize namespaces and paths
    $configuration['nsp'] = self::normalizeConf($configuration['nsp']);
    self::$configuration = $configuration;



    // Set initialized to true
    self::$initialized = true;
  }

  /**
   * Normilize path and make it correct
   *
   * @param string $path
   * @return string
   */
  public static function fixDirSeparator(string $path): string
  {
    return preg_replace('/[\/\\\]/', DIRECTORY_SEPARATOR, trim($path, '/\\'));
  }

  /**
   * Normilize paths and make it correct
   *
   * @param array $path
   * @return array
   */
  public static function fixDirSeparatorInArray(array $paths): array
  {
    for ($i = 0; $i < count($paths); $i++)
      $paths[$i] = self::fixDirSeparator($paths[$i]);

    return $paths;
  }

  /**
   * Glue parts of path into ine
   *
   * @param array $parts
   * @return string
   */
  public static function gluePaths(string ...$parts): string
  {
    return join(DIRECTORY_SEPARATOR, self::fixDirSeparatorInArray($parts));
  }

  /**
   * Normalize namespace with ending \
   *
   * @param string $namespace
   * @return string
   */
  public static function normalizeNamespace($namespace): string
  {
    return trim($namespace, '\\') . '\\';
  }

  /**
   * Normilize namespaces and paths
   *
   * @param array $cfg Configuration to normilize
   * @return array
   */
  public static function normalizeConf($cfg): array
  {
    $out_cfg = array();

    foreach ($cfg as $namespace => $path) {
      $namespace = self::normalizeNamespace($namespace);
      switch (gettype($path)) {
        case 'array': $path = self::fixDirSeparatorInArray($path); break;
        case 'string':
          if (substr($path, -1) === '*') {
            $path = self::getSubdirectories($path) ?? $path;
          }
          else
            $path = self::fixDirSeparator($path);
          break;
        default: throw new WrongConfigurationException();
      }

      $out_cfg[$namespace] = $path;
    }

    return $out_cfg;
  }

  /**
   * Get all subdirectories of given recursively
   *
   * @param string $root
   * @return mixed
   */
  public static function getSubdirectories($root)
  {
    $subdirectories = glob($root, GLOB_ONLYDIR);

    if (count($subdirectories) === 0) return null;

    foreach ($subdirectories as $subd) {
      $subdsub = self::getSubdirectories($subd . DIRECTORY_SEPARATOR . '*');
      if (count($subdsub) > 1) array_push(
        $subdirectories,
        ...array_splice($subdsub, 1)
      );
    }

    return [
      substr($root, 0, strlen($root) - 1),
      ...$subdirectories
    ];
  }

  /**
   * Get path to file of class
   *
   * @param string $namespace Namespace to make path
   * @return string
   */
  public static function pathFromNamespace(string $namespace): string
  {
    return self::gluePaths(SCAL_EXECUTED_IN, $namespace);
  }

  /**
   * Get path to file of class
   *
   * @param string $namespace Namespace to make path
   * @return string
   */
  public static function pathFromCfg(string $namespace): string
  {
    return self::gluePaths(SCAL_EXECUTED_IN, self::$configuration['nsp'][$namespace]);
  }

  /**
   * Extract class namespace and class name
   *
   * @param string $class
   * @return array
   */
  public static function extractClassPieces(string $class): array
  {
    $class_pieces = explode('\\', $class);

    if (count($class_pieces) === 1) {
      $class_namespace = '';
      $class_name = $class_pieces[0];
    } else {
      $class_namespace = self::normalizeNamespace(join('\\', array_slice($class_pieces, 0, count($class_pieces) - 1)));
      $class_name = array_slice($class_pieces, -1)[0];
    }

    return [$class_namespace, $class_name];
  }

  /**
   * Get class file path by namespace and class name
   *
   * @param string $namespace
   * @param string $class
   * @return string
   */
  public static function getClassFilePath(string $namespace, string $class): string
  {
    // Default
    $path = self::pathFromNamespace($namespace);

    // Try to find in configuration
    foreach (self::$configuration['nsp'] as $key => $value)
      if (substr($namespace, 0, strlen($key)) === $key) {
        $path = $value;
        $remain_path = substr($namespace, strlen($key));
      }

    $path = self::checkPath($path, $remain_path, $class . '.php');

    return $path;
  }

  /**
   * Check if file exists and return it
   *
   * @param array|string $path
   * @param string $remain_path
   * @param string $file_name
   * @return string
   */
  public static function checkPath($path, $remain_path, $file_name): string
  {
    switch (gettype($path)) {
      case 'string':
        return $remain_path
          ? self::gluePaths($path, $remain_path, $file_name)
          : self::gluePaths($path, $file_name); break;
      case 'array':
        foreach ($path as $value) {
          $file_path = self::checkPath($value, $remain_path, $file_name);
          if (file_exists($file_path)) return $file_path;
        }
        throw new FileNotFoundException();
        break;
    }
  }

  /**
   * Class autoloader
   *
   * @param string $class Class to load
   * @return void
   */
  public static function load(string $class): void
  {
    // Check if not initialized
    if (!self::$initialized) self::init();

    // Explode input $class
    [$namespace, $class_name] = self::extractClassPieces($class);

    $file_path = self::getClassFilePath($namespace, $class_name);

    if (!file_exists($file_path)) throw new ClassNotFoundException();

    require_once $file_path;

    if (!class_exists($class, false)) throw new ClassNotFoundException();
  }
}
