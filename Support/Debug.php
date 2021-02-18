<?php

/*
|
|--------------------------------------------------
| Debug functions
|--------------------------------------------------
|
| Use these functions when you need to display
| something on the screen
|
*/

/**
 * Display variable entry and stop the script
 *
 * @param mixed $var
 * @return void
 */
function svd( $var ): void
{
  print_r($var); exit;
}

/**
 * Display variable entry with its type
 * and stop the script
 *
 * @param mixed $var
 * @return void
 */
function svt( $var ): void
{
  var_dump($var); exit;
}
