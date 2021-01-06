<?php

/**
 * ACL - Автолоадер классов
 *
 * Для использование подключите файл до того, как будут использованы namespace
 *
 * ACL должна быть запущена в корне проекта, откуда в последствии будут искаться классы
 * Вы также можете задать корневой каталог проекта в ручную установив константу MITO_RD до подключения
 *
 *
 *
 * @author Alexandr Shamanin (@slpAkkie)
 * @version 1.3.5
 */





/**
 * Автозагрузка классов
 *
 * @param string $class Входная строка (namespace\class)
 *
 * @return void
 */
function ACL( string $class ) : void
{

  /**
   * Загрузка конфигурации
   * Файл ACL.conf.json конфигурации должен лежать рядом с ACL.php
   *
   * @var bool|array|null Конфигурация
   */
  static $__conf = false;

  /** __conf еще не была подгружена */
  $__conf === false && $__conf = json_decode( @file_get_contents( __CONF_FILE ), true );
  /** Файл конфигурации отсутствует */
  $__conf === null && $__conf = array();



  /** Разбиение входной строки по __NS (Разделитель namespace`ов) */
  $class = explode( __NS, $class );

  /**
   * @var string namespace подключаемого класса
   */
  $namespace = join( __DS, array_slice( $class, 0, -1, true ) );

  /**
   * @var string Дочернии каталоги для каталога namespace`а
   */
  $inner_path = '';

  /**
   * @var string Имя подключаемого класса
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
           * @var int Количество звездочек (Указатель на рекурсивный просмотр дочерних каталогов) в пути
           */
          $star_count = 0;


          /** Нормализация пути */
          $path = __DS . trim( preg_replace( '/\\//', __DS, preg_replace( '/\*/', '', $path, -1, $star_count ) ), __DS ) . __DS;

          /** Если в пути указана больше одной звездочки, то путь указан неверно */
          if ( $star_count > 1 ) continue;


          /**
           * @var bool Нужно ли искать во всех дочерних папках
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



/** Регистрация функции ACL как автолоадер */
spl_autoload_register( 'ACL' );



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
 * @var string Разделитель каталогов
 */
define( '__DS', DIRECTORY_SEPARATOR );

/**
 * @var string Разделитель namespace`ов
 */
define( '__NS', '\\' );

/**
 * @var string Корневая директория проекта
 */
!defined( '__ROOT_DIR' ) && define( '__ROOT_DIR', __DIR__ );

/**
 * @var string Путь к файлу конфигурации
 */
!defined( '__CONF_FILE' ) && define( '__CONF_FILE', __DIR__ . __DS . 'ACL.conf.json' );
