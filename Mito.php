<?php

/**
 * Mito - Автолоадер классов
 *
 * Для использование подключите файл до того, как будут использованы namespace
 *
 * Mito должна быть запущена в корне проекта, откуда в последствии будут искаться классы
 * Вы также можете задать корневой каталог проекта в ручную установив константу MITO_RD до подключения
 *
 *
 *
 * @author Alexandr Shamanin (@slpAkkie)
 * @version 1.2.4
 */





/**
 * Автозагрузка классов
 *
 * @param string $class Входная строка (namespace\class)
 *
 * @return void
 */
function Mito( string $class ) : void
{

  /**
   * Загрузка конфигурации
   * Файл Mito.conf.json конфигурации должен лежать рядом с Mito.php
   *
   * @var bool|array|null
   */
  static $__conf = false;

  /** __conf еще не была подгружена */
  $__conf === false && $__conf = json_decode( @file_get_contents( __CONF_FILE ), true );
  /** Файл конфигурации отсутствует */
  $__conf === null && $__conf = array();



  /** Разбиение входной строки по __NS (Разделитель namespace`ов) */
  $class = explode( __NS, $class );

  /**
   * namespace подключаемого класса
   *
   * @var string
   */
  $namespace = join( __DS, array_slice( $class, 0, -1, true ) );

  /**
   * Дочернии каталоги для каталога namespace`а
   *
   * @var string
   */
  $inner_path = '';

  /**
   * Имя подключаемого класса
   *
   * @var string
   */
  $class_name = join( null, array_slice( $class, -1, 1, true ) );





  /** Если namespace пустой, тогда вести поиск в корне проекта */

  if ( $namespace === '' ) {
    /** Формирование пути к файлу */
    $inc_path = __ROOT_DIR . __DS . $class_name . '.php';

    /** Если файл есть */
    if ( file_exists( $inc_path ) ) require_once( $inc_path );

    return;
  }





  /** Попытки найти подключаемый класс до тех пор, пока можно делить namespace */

  do {
    /** Проверка namespace`а подключаемого класса на соответствие namespace`ам в конфигурации */
    foreach ( $__conf as $ns => $ns_pathways ) {
      if ( $namespace === trim( $ns, '\\' ) ) {



        if ( gettype( $ns_pathways ) !== 'array' ) $ns_pathways = [ $ns_pathways ];

        foreach ( $ns_pathways as $__i => $path ) {
          /**
           * Количество звездочек (Указатель на рекурсивный просмотр дочерних каталогов) в пути
           *
           * @var int
           */
          $star_count = 0;


          /** Нормализация пути */
          $path = __DS . trim( preg_replace( '/\\//', __DS, preg_replace( '/\*/', '', $path, -1, $star_count ) ), __DS ) . __DS;

          /** Если в пути указана больше одной звездочки, то путь указан неверно */
          if ( $star_count > 1 ) continue;


          /**
           * Нужно ли искать во всех дочернихпапках
           *
           * @var bool
           */
          $child_recursievly = (bool)($star_count === 1);

          /**
           *
           */



          /** Формирование пути к файлу */

          $file_path = __ROOT_DIR . $path . $inner_path;
          $file_name = $class_name . '.php';

          if ( $child_recursievly && $inner_path === '' ) $inc_path = find_file( $file_path, $file_name );
          else $inc_path = $file_path . $file_name;

          if ( $inc_path === null ) return;

          /** Если файл есть */
          if ( file_exists( $inc_path ) ) {
            require_once( $inc_path );
            return;
          }
        }



      }
    }
  } while ( preg_match( '/\\\/', $namespace )
    && ($inner_path = join( null, array_slice( explode( '\\', $namespace ), -1, null, true ) ) . __DS)
    && ($namespace = join( __DS, array_slice( explode( '\\', $namespace ), 0, -1, true )) )
  );

}



/** Регистрация функции Mito как автолоадер */
spl_autoload_register( 'Mito' );



/**
 * Найти файл в каталоге, включая дочернии (Рекурсивно)
 *
 * @param string $dir Директория для поиска
 * @param string $file_name Имя искомого файла (с расширением)
 *
 * @return string|null Путь к искомому файлу относителньо $dir или null если файл не найден
 */
function find_file( string $dir, string $file_name ) : ?string
{

  $dir_entry = array_splice( scandir( $dir ), 2 );

  foreach ( $dir_entry as $__i => $item ) {

    if ( file_exists( $item ) ) {
      if ( $item === $file_name )
        return $dir . $file_name;
    } else return find_file( $dir . $item . __DS, $file_name );

  }

  return null;

}





/** Необходимые константы */

/**
 * Разделитель каталогов
 *
 * @var string
 */
define( '__DS', DIRECTORY_SEPARATOR );
/**
 * Разделитель namespace`ов
 *
 * @var string
 */
define( '__NS', '\\' );

/**
 * Корневая директория проекта
 *
 * @var string
 */
!defined( '__ROOT_DIR' ) && define( '__ROOT_DIR', __DIR__ );

/**
 * Путь к файлу конфигурации
 *
 * @var string
 */
!defined( '__CONF_FILE' ) && define( '__CONF_FILE', __DIR__ . __DS . 'Mito.conf.json' );
