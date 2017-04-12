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
                if (!headers_sent()) {
                    header('Location: ' . base64_decode($_GET['successpath']));
                } else {
                    echo "If you are not redirected, please click <a href=" . '"' . base64_decode($_GET['successpath']) . '"' . " target='_self'>Here</a><br />"
                    . "<script>location.href = '" . base64_decode($_GET['successpath']) . "'</script>";
                }
            } else {
                if (!headers_sent()) {
                    header('Location: ' . $successpath);
                } else {
                    echo "If you are not redirected, please click <a href=" . '"' . $successpath . '"' . " target='_self'>Here</a><br />"
                    . "<script>location.href = '" . $successpath . "'</script>";
                }
            }
        } else {
            if (!headers_sent()) {
                header('Location: ' . $this->data['url']);
            } else {
                echo "If you are not redirected, please click <a href=" . '"' . $this->data['url'] . '"' . " target='_self'>Here</a><br />"
                . "<script>location.href = '" . $this->data['url'] . "'</script>";
            }
        }
    }

}

$verifytrans = new verifytrans();
//$verifytrans->checkStatus()->process();
$verifytrans->process();
