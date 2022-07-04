<?php

/*
|
|--------------------------------------------------
| Many2Depth1Test
|--------------------------------------------------
|
| One of array path with recursion. Single depth
|
*/

namespace Many;

class Many2Depth1Test
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted('Для namespace задан массив путей, один из которых с рекурсивным поиском. Этот класс находится в рекурсивном поиске');
  }
}
