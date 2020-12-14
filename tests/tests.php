<?php
/**
 * Файл содержит функции для проведения тестов
 */



/**
 * Функции тестирования
 *
 * @param string $class Класс для тестирования
 * @param string $description Описание теста
 *
 * @return void
 */
function run_test( string $class, string $description ) : void
{

  static $test_number = 1;

  echo '<h2>Тест ' . $test_number++ . '</h2>';
  echo '<p>' . $description . '</p>';
  echo '<pre>>>> ';

  ob_start( 'result_handler' );
  new $class();
  echo ob_get_clean();

  echo '</pre>';
  echo '<hr />';

}

/**
 * Обработка фатальных ошибок
 *
 * @param string $buffer Буфер вывода
 *
 * @return string
 */
function result_handler( string $buffer ) : string
{

  if ( !$buffer ) return '<span style="color: red">Тест не пройден</span>';
  else return $buffer;

}
