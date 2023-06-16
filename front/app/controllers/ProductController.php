<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

session_start();

class ProductController extends Controller
{
    public function indexAction()
    {
        $url = "http://172.23.0.2/product";
        $token = $_SESSION['token'];
        $header = [
            "Authorization: $token"
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        print_r($data);
        if ($data) {
            $this->view->name = $data;
        } else {
            print_r("Access Denied");
            die;
        }
    }
    public function displayAction()
    {
        $url = "http://172.23.0.2/product/display?page=$_POST[page]";
        $token = $_SESSION['token'];
        $header = [
            "Authorization: $token"
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        if (!isset($_SESSION['time'])) {
            $flag = 1;
        }
        if ($data && ($flag==1 || time()-$_SESSION['time']>30)) {
            $data = json_decode($data, true);
            $_SESSION['time'] = time();
            foreach ($data as $value) {
                $this->view->txt .= "<tr>
                <td>".$value['id']['$oid']."</td>
                <td>".$value['name']."</td>
                </tr>";
            }
        } else {
            print_r("Warning: You Should Refresh After 30 seconds");
            die;
        }
    }
}
