<?php

/*
|
|--------------------------------------------------
| DirectTest
|--------------------------------------------------
|
| One namespace, one folder in configuration
|
*/

namespace Direct;

class DirectTest
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'namespace указан в конфигурации'
    );
  }
}
