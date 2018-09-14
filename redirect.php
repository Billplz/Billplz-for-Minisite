<?php

require 'lib/API.php';
require 'lib/Connect.php';
require 'configuration.php';

use Billplz\Minisite\API;
use Billplz\Minisite\Connect;

$data = Connect::getXSignature($x_signature);
$connnect = (new Connect($api_key))->detectMode();
$billplz = new API($connnect);
list($rheader, $rbody) = $billplz->toArray($billplz->getBill($data['id']));

if ($rbody['paid']) {
    /***********************************************/
    // Include tracking code here
    /***********************************************/
    if (!empty($successpath)) {
        header('Location: ' . $successpath);
    } else {
        header('Location: ' . $rbody['url']);
    }
} else {
    header('Location: ' . $rbody['url']);
}
