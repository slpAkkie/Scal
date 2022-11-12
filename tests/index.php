<?php

define('APP_ROOT_PATH', __DIR__);

require_once APP_ROOT_PATH . '/TestModule/options.php';
require_once APP_ROOT_PATH . '/../Initializer.php';

use TestModule\Test;



Test::run(
    desc: 'Class without namespace and not in configuration',
    test: function () {
        Test::assertNonException(function () {
            new NoConfTest();
        });
    }
);
Test::run(
    desc: 'Namespace specified, but it\'s not in the configuration',
    test: function () {
        Test::assertNonException(function () {
            new NoConf\NoConfTest();
        });
    }
);
Test::run(
    desc: 'Namespace specified in the configuration',
    test: function () {
        Test::assertNonException(function () {
            new Direct\DirectTest();
        });
    }
);
Test::run(
    desc: 'Namespace contains several parts like a path',
    test: function () {
        Test::assertNonException(function () {
            new Complex\Direct\ComplexTest();
        });
    }
);
Test::run(
    desc: 'Namespace specified for recursive search. Class in the root directory',
    test: function () {
        Test::assertNonException(function () {
            new Recursion\RecursionTest0();
        });
    }
);
Test::run(
    desc: 'Namespace specified for recursive search. Class in the first depth',
    test: function () {
        Test::assertNonException(function () {
            new Recursion\RecursionTest1();
        });
    }
);
Test::run(
    desc: 'Namespace specified for recursive search. Class in the first depth',
    test: function () {
        Test::assertNonException(function () {
            new Recursion\RecursionTest2();
        });
    }
);
Test::run(
    desc: 'Namespace specified for recursive search. Class in the second depth',
    test: function () {
        Test::assertNonException(function () {
            new Recursion\RecursionTest2_1();
        });
    }
);
Test::run(
    desc: 'Namespace specified as array. One of path specefied as recursive search. This class not in recursove search',
    test: function () {
        Test::assertNonException(function () {
            new Many\Many1Test();
        });
    }
);
Test::run(
    desc: 'Namespace specified as array. One of path specefied as recursive search. This test for class should found as recursive',
    test: function () {
        Test::assertNonException(function () {
            new Many\Many2Test();
        });
    }
);
Test::run(
    desc: 'Namespace specified as array. One of path specefied as recursive search. This test for class should found as recursive',
    test: function () {
        Test::assertNonException(function () {
            new Many\Many2Depth1Test();
        });
    }
);
Test::run(
    desc: 'Namespace specified as array. One of path specefied as recursive search. This test for class should found as recursive',
    test: function () {
        Test::assertNonException(function () {
            new Many\Many2Depth1_2Test();
        });
    }
);
Test::run(
    desc: 'Namespace specified as array. One of path specefied as recursive search. This test for class should found as recursive',
    test: function () {
        Test::assertNonException(function () {
            new Many\Many2Depth2Test();
        });
    }
);
