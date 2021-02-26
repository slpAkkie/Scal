<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Title
  ==================== -->
  <title>Scal testing</title>

</head>
<body>

  <!-- Heading
  ==================== -->
  <h1>Scal testing</h1>
  <hr>

  <!-- Logs
  ==================== -->
  <?php

      // Enable display of all errors
      error_reporting(E_ALL);

      // Include Scal
      define('SCAL_DEV_MODE', true);
      define('SCAL_EXCEPTION_MODE', true);
      require_once '../Scal.php';

      // Exception mode
      if (!SCAL_EXCEPTION_MODE) { echo 'Режим оповещения об ошибках отключен (SCAL_EXCEPTION_MODE)'; }

      // Tests
      if (SCAL_DEV_MODE) {
        Scal\Support\Test::tryLoad('NoConfTest');
        Scal\Support\Test::tryLoad('NoConf\NoConfTest');
        Scal\Support\Test::tryLoad('Direct\DirectTest');
        Scal\Support\Test::tryLoad('Complex\Direct\ComplexTest');
        Scal\Support\Test::tryLoad('Recursion\RecursionTest0');
        Scal\Support\Test::tryLoad('Recursion\RecursionTest1');
        Scal\Support\Test::tryLoad('Recursion\RecursionTest2');
        Scal\Support\Test::tryLoad('Recursion\RecursionTest2_1');
        Scal\Support\Test::tryLoad('Many\Many1Test');
        Scal\Support\Test::tryLoad('Many\Many2Test');
        Scal\Support\Test::tryLoad('Many\Many2Depth1Test');
        Scal\Support\Test::tryLoad('Many\Many2Depth1_2Test');
        Scal\Support\Test::tryLoad('Many\Many2Depth2Test');
      } else { echo 'Чтобы провести тесты, задайте константу SCAL_DEV_MODE в значение true'; }

  ?>

</body>
</html>
