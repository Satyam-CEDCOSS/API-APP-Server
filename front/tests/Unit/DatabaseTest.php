<?php

declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Controllers\SignupController;
use MyApp\Models\Users;

class DatabaseTest extends AbstractUnitTest
{
    public function testAddUser()
    {
        $arr = [
            'name' => 'Ayush',
            'email' => 'a@y.com',
            'password' => '123'
        ];
        $test = new SignupController();
        $result = $test->addAction($arr);
        $this->assertEquals($result, true);
    }
}
