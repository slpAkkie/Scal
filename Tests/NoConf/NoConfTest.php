<?php

/*
|
|--------------------------------------------------
| NoConfTest
|--------------------------------------------------
|
| Class wasn't specified in configuration
|
*/

namespace NoConf;

class NoConfTest
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'Для класса задан namespace но он не указан в конфигурации'
    );
  }
}
