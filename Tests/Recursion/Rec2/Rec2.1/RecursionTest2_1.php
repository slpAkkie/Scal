<?php

/*
|
|--------------------------------------------------
| RecursionTest2_1
|--------------------------------------------------
|
| Recursion in double depth
|
*/

namespace Recursion;

class RecursionTest2_1
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'Для namespace задан рекурсивный поиск. Второй уровень'
    );
  }
}
