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

use Scal\Exceptions\ConfigurationCannotBeReadException;
use Scal\Exceptions\ConfigurationNotFoundException;

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
  private static $configuration_name = 'Scal.json';

  /**
   * Custom configuration file name specified manually
   * or null if it's not.
   * Contains absolute file path from project's root directory
   *
   * @var null|string
   */
  public static $configuration_path = null;

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
    $configuration_path = SCAL_REAL_PATH;
    $configuration_name = self::$configuration_name;
    $configuration_full_path = $configuration_path . $configuration_name;

    // Check if configuration path was set manually
    if (self::$configuration_path !== null) {
      // Normilize the path
      self::$configuration_path = self::path_normilize(self::$configuration_path);

      // Extract configuration name from its path
      $configuration_path = dirname(self::$configuration_path);
      $configuration_name = basename(self::$configuration_path);

      // Build path
      if ($configuration_path === '.') $configuration_full_path = $configuration_name;
      else $configuration_full_path = SCAL_EXECUTED_IN . $configuration_path . DIRECTORY_SEPARATOR . $configuration_name;
    }



    // Check if configuration file doesn't exists
    if (!file_exists($configuration_full_path)) throw new ConfigurationNotFoundException();

    // Else try to read it
    self::$configuration = json_decode(file_get_contents($configuration_full_path), true);
    if (gettype(self::$configuration) !== 'array') throw new ConfigurationCannotBeReadException();



    // Set initialized to true
    self::$initialized = true;
  }

  /**
   * Normilize path and make it correct
   *
   * @param string $path
   * @return string
   */
  public static function path_normilize(string $path): string
  {
    return preg_replace('/[\/\\\]/', DIRECTORY_SEPARATOR, trim($path, '/\\'));
  }

  /**
   * Class autoloader
   *
   * @param string $class Class to load
   * @return void
   */
  public static function load(string $class): void
  {
    // Code here
  }
}
