<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Meta tags
  ==================== -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Title
  ==================== -->
  <title>Scal testing</title>

</head>
<body>

  <h1>Page for testing Scal</h1>
  <hr>

  <section>
    <?php

      // Enable display of all errors
      error_reporting(E_ALL);

      // Scal inclusion
      // TODO: Specify the configuration file before Scal including
      require_once '../Scal.php';

    ?>
  </section>

</body>
</html>
