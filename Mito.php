<?php

/**
 * Mito - Автолоадер классов
 *
 * Для использование подключите файл до того, как будут использованы namespace
 *
 * Данная версия Mito должна обязательно лежать в корне проекта
 *
 *
 *
 * @version 1.0.0
 * @author Alexandr Shamanin (@slpAkkie)
 */





/**
 * Автозагрузка классов
 *
 * @param string $input Входная строка (namespace\class)
 *
 * @return void
 */
function Mito( string $input ) : void
{

  /**
   * Загрузка конфигурации
   * Файл Mito.conf.json конфигурации должен лежать рядом с Mito.php
   *
   * @var bool|array|null
   */
  static $Mito_conf = false;

  /** Mito_conf еще не была подгружена */
  $Mito_conf === false && $Mito_conf = json_decode( @file_get_contents( __DIR__ . _DS . 'Mito.conf.json' ), true );
  /** Файл конфигурации отсутствует */
  $Mito_conf === null && $Mito_conf = array();





  /** Разделение входной строки на namespace и класс */

  /** Разбиение входной строки по _NS (Разделитель namespace`ов) */
  $input = explode( _NS, $input );

  /**
   * Namespace подключаемого класса
   *
   * @var string
   */
  $namespace = join( _DS, array_slice( $input, 0, -1, true ) );

  /**
   * Имя подключаемого класса
   *
   * @var string
   */
  $class = join( null, array_slice( $input, -1, 1, true ) );



  /** Если namespace пустой, тогда вести поиск в корне проекта */

  if ( $namespace === '' ) {
    /** Нормализация пути */
    $path = _DS;
    /** Формирование пути к файлу */
    $include_path = __DIR__ . $path . $class . _FEXT;

    /** Если файл есть */
    if ( file_exists( $include_path ) ) {
      require_once( $include_path );
      return;
    }
  }




  /** Проверка namespace`а подключаемого класса на соответствие namespace`ам в конфигурации */
  foreach ( $Mito_conf as $ns => $paths ) {

    if ( $namespace === trim( $ns, '\\' ) ) {

      if ( gettype( $paths ) !== 'array' ) $paths = [ $paths ];

      foreach ( $paths as $i => $path ) {
        /** Нормализация пути */
        $path = _DS . trim( preg_replace( '/\\//', _DS, $path ), '\\/' ) . _DS;
        /** Формирование пути к файлу */
        $include_path = __DIR__ . $path . $class . _FEXT;

        /** Если файл есть */
        if ( file_exists( $include_path ) ) {
          require_once( $include_path );
          break;
        }
      }

    }

  }
}



/**
 * Регистрация функции Mito как автолоадер
 */
spl_autoload_register( 'Mito' );





/**
 *
 * ====================
 * Необходимые константы
 * ====================
 *
 */

/** Разделитель каталогов */
define( '_DS', DIRECTORY_SEPARATOR );
/** Разделитель namespace`ов */
define( '_NS', '\\' );
/** Расширение подключаемых файлов php */
define( '_FEXT', '.php' );





/**
 *
 * ====================
 * Функции для отладки
 * ====================
 *
 */

/**
 * Вывести информацию о переменной
 */
function vd( $var )
{

  echo '<pre style="font-size: 24px">';
  var_dump( $var );
  echo '</pre>';

}

/**
 * Вывести информацию о переменной и завершить выполнение скрипта
 */
function vde( $var )
{

  vd( $var );
  exit;

}
