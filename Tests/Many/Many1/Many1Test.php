<?php

/*
|
|--------------------------------------------------
| Many1Test
|--------------------------------------------------
|
| One of array path
|
*/

namespace Many;

class Many1Test
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted('Для namespace задан массив путей, один из которых с рекурсивным поиском');
  }
}
