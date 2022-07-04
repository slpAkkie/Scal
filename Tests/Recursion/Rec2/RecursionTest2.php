<?php

/*
|
|--------------------------------------------------
| RecursionTest2
|--------------------------------------------------
|
| Recursion in single depth
|
*/

namespace Recursion;

class RecursionTest2
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'Для namespace задан рекурсивный поиск. Первый уровень'
    );
  }
}
