<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

session_start();

class IndexController extends Controller
{
    public function indexAction()
    {
        // Redirect to View
    }
    public function checkAction()
    {
        $url = "http://172.23.0.2/login";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $_SESSION['token'] = curl_exec($ch);
        if ($_SESSION['token']) {
            $this->response->redirect('/product');
        }
    }
    public function logoutAction()
    {
        session_destroy();
        $this->response->redirect('/');
    }

    /**
     * @param int $a
     * @param int $b
     */
    public function addAction(int $a, int $b): int
    {
        return $a + $b;
    }
}
