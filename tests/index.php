<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scal testing</title>
</head>

<body>

    <h1>Scal testing</h1>
    <hr>

    <?php

    // Enable display of all errors
    error_reporting(E_ALL);

    // Include Scal
    define('SCAL_TESTS_ENABLED', true);
    require_once '../Scal.php';

    // Tests
    if (SCAL_TESTS_ENABLED) {
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
    } else {
        echo 'Чтобы провести тесты, задайте константу SCAL_TESTS_ENABLED в значение true';
    }

    ?>

</body>

</html>