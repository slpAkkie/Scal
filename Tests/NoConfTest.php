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

class NoConfTest
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'Этот класс не содержит указания namespace, следовательно не записан в конфигурации'
    );
  }
}
