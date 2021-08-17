<?php

require 'lib/API.php';
require 'lib/Connect.php';
require 'configuration.php';

use Billplz\Minisite\API;
use Billplz\Minisite\Connect;

$data = Connect::getXSignature($x_signature, 'bill_redirect');
$connect = new Connect($api_key);
$connect->setStaging($is_sandbox);
$billplz = new API($connect);
list($rheader, $rbody) = $billplz->toArray($billplz->getBill($data['id']));

if ($rbody['paid']) {
    /***********************************************/
    // Include tracking code here
    // Do something here if payment has been made
    /***********************************************/

    if (!empty($successpath)) {
        header('Location: ' . $successpath);
    } else {
        header('Location: ' . $rbody['url']);
    }
} else {
    /*Do something here if payment has not been made*/
    header('Location: ' . $rbody['url']);
}
