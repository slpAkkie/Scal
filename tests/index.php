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
  <h1>Page for testing Scal</h1>
  <hr>

  <!-- Logs
  ==================== -->
  <pre><?php

      // Enable display of all errors
      error_reporting(E_ALL);

      // Scal inclusion
      require_once '../Scal.php';

      Scal\Loader::$configuration_path = 'ScalTest.json';
      Scal\Loader::init();

      new DirectTest();                // Пространства имен нет, должен искать относительно пути где подключен Scal
      new Direct\SecondDirectTest();   // Пространства имен нет, должен искать относительно пути где подключен Scal
      new Core\CoreTest();             // Пространство имен Core указано в конфигурации без рекурсии
      new Core\Inner\CoreInnerTest();  // Пространство имен Core указано в конфигурации без рекурсии, а Inner нет
      new Illuminate\IllumTest();      // Пространство имен Illuminate указано в конфигурации без рекурсии
      new Support\SupD();              // Пространство имен Support указано в конфигурации с рекурсией
      new Support\SupFir();            // Пространство имен Support указано в конфигурации с рекурсией
      new Support\SupSec();            // Пространство имен Support указано в конфигурации с рекурсией
      new Exceptions\CoreException();  // Пространство имен Exceptions указано в конфигурации как массив путей
      new Exceptions\AppException();   // Пространство имен Exceptions указано в конфигурации как массив путей
      new App\Controllers\ContrTest(); // Пространство имен App\Controllers указано в конфигурации, как сложный путь

  ?></pre>

</body>
</html>
