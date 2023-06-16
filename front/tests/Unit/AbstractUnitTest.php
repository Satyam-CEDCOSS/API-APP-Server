<?php

declare(strict_types=1);

namespace Tests\Unit;

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Incubator\Test\PHPUnit\UnitTestCase;
use PHPUnit\Framework\IncompleteTestError;

abstract class AbstractUnitTest extends UnitTestCase
{
    private bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $di = new FactoryDefault();

        $di->set(
            'mongo',
            function () {
                $mongo = new MongoDB\Client(
                    "mongodb+srv://root:Password123@mycluster.qjf75n3.mongodb.net/?retryWrites=true&w=majority"
                );
                return $mongo->api_app_server;
            },
            true
        );
        Di::reset();
        Di::setDefault($di);

        $this->loaded = true;
    }

    public function __destruct()
    {
        if (!$this->loaded) {
            throw new IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
    }
}
