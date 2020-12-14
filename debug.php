<?php
/**
 * Файл содержит функции для отладки
 */

/**
 * Вывести содержимое переменной
 *
 * @param mixed $var значение которое необходимо вывести на экран
 *
 * @return void
 */
function vd( $var ) : void
{

  echo '<pre style="font-size: 24px">';
  $var ? print_r( $var ) : var_dump( $var );
  echo '</pre>';

}

/**
 * Вывести содержимое переменной и завершить выполнение скрипта
 *
 * @param mixed $var значение которое необходимо вывести на экран
 *
 * @return void
 */
function vde( $var ) : void
{

  vd( $var );
  exit;

}
