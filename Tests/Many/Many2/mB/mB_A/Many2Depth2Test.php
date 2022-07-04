<?php

/*
|
|--------------------------------------------------
| Many2Depth2Test
|--------------------------------------------------
|
| One of array path
|
*/

namespace Many;

class Many2Depth2Test
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted('Для namespace задан массив путей, один из которых с рекурсивным поиском. Этот класс находится в рекурсивном поиске');
  }
}
