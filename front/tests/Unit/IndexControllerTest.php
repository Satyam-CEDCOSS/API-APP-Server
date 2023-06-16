<?php
declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Controllers\IndexController;

class IndexControllerTest extends AbstractUnitTest
{

    public static function addDataProvider(): array
    {
        return array(
            array(1, 2, 3),
            array(0, 0, 0),
            array(-1, -1, -2),
        );
    }

    /**
     * @dataProvider addDataProvider
     */
    public function testAdd($a, $b, $expected)
    {
        $indexController = new IndexController();
        $result = $indexController->addAction($a, $b);
        $this->assertEquals($expected, $result);
    }
}
