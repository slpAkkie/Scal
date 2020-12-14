<?php
/**
 * Файл содержит функции для отладки
 */

/**
 * Вывести содержимое переменной
 */
function vd( $var )
{

  echo '<pre style="font-size: 24px">';
  $var ? print_r( $var ) : var_dump( $var );
  echo '</pre>';

}

/**
 * Вывести содержимое переменной и завершить выполнение скрипта
 */
function vde( $var )
{

  vd( $var );
  exit;

}
