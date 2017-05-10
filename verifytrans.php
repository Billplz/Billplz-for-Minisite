<?php

require_once 'billplz.php';
require_once 'configuration.php';

class verifytrans {

    var $billplz;
    var $data;
    var $moreData;

    function __construct() {
        /*
         * Get Data. Die if input is tempered or X Signature not enabled
         */
        global $x_signature;
        $this->data = Billplz::getRedirectData($x_signature);
        $this->billplz = new Billplz;
    }

    function checkStatus() {
        global $api_key;
        $bill_id = $this->data['id'];
        $this->moreData = $this->billplz->check_bill($api_key, $bill_id);
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
            if (!empty($_GET['successpath'])) {
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
                header('Location: ' . $this->moreData['url']);
            } else {
                echo "If you are not redirected, please click <a href=" . '"' . $this->data['url'] . '"' . " target='_self'>Here</a><br />"
                . "<script>location.href = '" . $this->moreData['url'] . "'</script>";
            }
        }
    }

}

$verifytrans = new verifytrans();
$verifytrans->checkStatus()->process();
//$verifytrans->process();
