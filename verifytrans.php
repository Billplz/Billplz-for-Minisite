<?php

require_once 'billplz.php';
require_once 'configuration.php';

class verifytrans {

    //var $billplz;
    var $data;
    //var $moreData;

    function __construct() {
        /*
         * Get Data. Die if input is tempered or X Signature not enabled
         */

        $this->data = billplz::getRedirectData($x_signature);
        //$this->billplz = new billplz;
    }

    function checkStatus() {
        //global $api_key, $mode;
        //$bill_id = $this->data['id'];
        //$this->moreData = $this->billplz->check_bill($api_key, $bill_id, $mode);
        return $this;
    }

    /*
     * Dalam variable $this->data ada maklumat berikut:
     * 1. id //bill_id
     * 2. paid_at
     * 3. paid
     * 4. x_signature
     */

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
//$verifytrans->checkStatus()->process();
$verifytrans->process();
