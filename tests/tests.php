<?php
/**
 * Файл содержит функции для проведения тестов
 */



/**
 * Функции тестирования
 */
function run_test( $class, $description )
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

function result_handler( $buffer )
{

  if ( !$buffer ) return '<span style="color: red">Тест не пройден</span>';
  else return $buffer;

}
