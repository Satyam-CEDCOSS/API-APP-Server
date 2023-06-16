<?php

require_once  __DIR__ . "/vendor/autoload.php";

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;

$loader = new Loader();
$loader->registerNamespaces(
    [
        'MyApp\Models' => __DIR__ . '/models/',
    ]
);



$loader->register();

$container = new FactoryDefault();

$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            "mongodb+srv://root:Password123@mycluster.qjf75n3.mongodb.net/?retryWrites=true&w=majority"
        );

        return $mongo->api_app_server;
    },
    true
);

$app = new Micro($container);

$app->post(
    '/signup',
    function () {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $appKey = substr(str_shuffle($chars), 0, 10);
        $secretKey = substr(str_shuffle($chars), 0, 10);
        $arr = [
            'name'=>$_POST["name"],
            'email'=>$_POST["email"],
            'password'=>$_POST["password"],
            'app_key'=>$appKey,
            'secret_key'=>$secretKey
        ];
        $success = $this->mongo->users->insertOne($arr);
        if ($success->getInsertedCount()) {
            print_r($success->getInsertedCount());
        }
    }
);

$app->post(
    '/login',
    function () {
        $check = $this->mongo->users->findOne(['$and' => [['email' => $_POST['email']],
         ['password' => $_POST['password']]]]);
        if ($check['_id']) {
            $token = $check['secret_key'] . "/" . $check['app_key'];
            $signer  = new Hmac();
            $builder = new Builder($signer);
            $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
            $builder
                ->setSubject($token)
                ->setPassphrase($passphrase);
            $accessToken = $builder->getToken();
            return $accessToken->getToken();
        }
    }
);

$app->get(
    '/product',
    function () {
        $header = apache_request_headers();
        $token = $header['Authorization'];
        $parser = new Parser();
        $tokenObject = $parser->parse($token);
        $role = $tokenObject->getclaims()->getpayload()['sub'];
        $data = explode('/', $role);
        $check = $this->mongo->users->findOne(['$and' => [['app_key' => $data['1']],
         ['secret_key' => $data['0']]]]);
        if ($check['name']) {
            return $check['name'];
        }
    }
);

$app->get(
    '/product/display',
    function () {
        $header = apache_request_headers();
        $token = $header['Authorization'];
        $parser = new Parser();
        $tokenObject = $parser->parse($token);
        $role = $tokenObject->getclaims()->getpayload()['sub'];
        $data = explode('/', $role);
        $check = $this->mongo->users->findOne(['$and' => [['app_key' => $data['1']],
         ['secret_key' => $data['0']]]]);
        if ($check['name']) {
            $page = $_GET['page'];
            if ($page < 1) {
                $page = 1;
            }
            $info = $this->mongo->products->find([], ['limit' => 10, 'skip' => (int)($page-1)*10]);
            $data = [];
            foreach ($info as $key => $value) {

                    $data[$key]['name'] = $value['name'];
                    $data[$key]['id'] = $value->_id;
            }
            return json_encode($data);
        }
    }
);

$app->handle(
    $_SERVER["REQUEST_URI"]
);
