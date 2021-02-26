<?php

/*
|
|--------------------------------------------------
| RecursionTest0
|--------------------------------------------------
|
| Recursion in root
|
*/

namespace Recursion;

class RecursionTest0
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'Для namespace указан рекурсивный поиск. Класс находится в корне каталога'
    );
  }
}
