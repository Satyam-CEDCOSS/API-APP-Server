<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;


class SignupController extends Controller
{
    public function indexAction()
    {
        // Redirect to View
    }
    public function addAction()
    {
        $url = "http://172.23.0.2/signup";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if ($result) {
            $this->response->redirect('/');
        }
    }
}
