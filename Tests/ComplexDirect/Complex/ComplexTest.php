<?php

/*
|
|--------------------------------------------------
| ComplexTest
|--------------------------------------------------
|
| Two parts in namespace
|
*/

namespace Complex\Direct;

class ComplexTest
{
  public function __construct()
  {
    SCAL_DEV_MODE && \Scal\Support\Test::testCompleted(
      'namespace состоит из несколькиз частей, как и путь'
    );
  }
}
