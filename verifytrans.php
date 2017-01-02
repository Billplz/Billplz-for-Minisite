<?php

require_once 'billplz.php';
require_once 'configuration.php';

class verifytrans {

    var $billplz;
    var $data;

    function __construct() {
        if (isset($_GET['billplz']['id'])) {
            $this->billplz = new billplz;
        } else {
            exit('Fake Request!');
        }
    }

    function checkStatus() {
        global $api_key, $mode;
        $bill_id = filter_var($_GET['billplz']['id'], FILTER_SANITIZE_STRING);
        $this->data = $this->billplz->check_bill($api_key, $bill_id, $mode);
        return $this;
    }

    function process() {
        global $successpath;
        if ($this->data['paid']) {
            //////////////////////////////////////////////////
            // Include tracking code here
            
            //////////////////////////////////////////////////
            if (isset($_GET['successpath'])) {
                header('Location: ' . base64_decode($_GET['successpath']));
            } else {
                header('Location: ' . $successpath);
            }
        } else {
            header('Location: ' . $this->data['url']);
        }
    }

}

$verifytrans = new verifytrans();
$verifytrans->checkStatus()->process();
