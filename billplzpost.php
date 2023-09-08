<?php

require 'lib/API.php';
require 'lib/Connect.php';
require 'configuration.php';

use Billplz\Minisite\API;
use Billplz\Minisite\Connect;

$parameter = array(
    'collection_id' => !empty($collection_id) ? $collection_id : $_REQUEST['collection_id'],
    'email'=> isset($_REQUEST['email']) ? $_REQUEST['email'] : '',
    'mobile'=> isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '',
    'name'=> isset($_REQUEST['name']) ? $_REQUEST['name'] : 'No Name',
    'amount'=> !empty($amount) ? $amount : $_REQUEST['amount'],
    'callback_url'=> $websiteurl . '/callback.php',
    'description'=> !empty($description) ? $description : $_REQUEST['description']
);

$optional = array(
    'redirect_url' => $websiteurl . '/redirect.php',
    'reference_1_label' => !empty($reference_1_label) ? $reference_1_label : $_REQUEST['reference_1_label'],
    'reference_1' => isset($_REQUEST['reference_1']) ? $_REQUEST['reference_1'] : '',
    'reference_2_label' => !empty($reference_2_label) ? $reference_2_label : $_REQUEST['reference_2_label'],
    'reference_2' => isset($_REQUEST['reference_2']) ? $_REQUEST['reference_2'] : '',
    'deliver' => 'false'
);

if (empty($parameter['mobile']) && empty($parameter['email'])) {
    $parameter['email'] = 'noreply@billplz.com';
}

if (!filter_var($parameter['email'], FILTER_VALIDATE_EMAIL)) {
    $parameter['email'] = 'noreply@billplz.com';
}

$connect = new Connect($api_key);
$connect->setStaging($is_sandbox);
$billplz = new API($connect);
list ($rheader, $rbody) = $billplz->toArray($billplz->createBill($parameter, $optional));
/***********************************************/
// Include tracking code here
/***********************************************/

$is_debug = defined('DEBUG') || (bool) $debug;

if ($rheader !== 200) {
    if ($is_debug) {
        echo '<pre>'.print_r($rbody, true).'</pre>';
    } elseif (!empty($fallbackurl)) {
        header('Location: ' . $fallbackurl);
        exit;
    }
}

if (isset($rbody['url'])) {
    header('Location: ' . $rbody['url']);
    exit;
}
