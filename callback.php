<?php

require 'lib/API.php';
require 'lib/Connect.php';
require 'configuration.php';

use Billplz\Minisite\API;
use Billplz\Minisite\Connect;

$data = Connect::getXSignature($x_signature, 'bill_callback');
$connect = new Connect($api_key);
$connect->setStaging($is_sandbox);
$billplz = new API($connect);
list($rheader, $rbody) = $billplz->toArray($billplz->getBill($data['id']));

if ($rbody['paid']) {
    /*Do something here if payment has been made*/
} else {
    /*Do something here if payment has not been made*/
}

echo 'Callback is done';

/*
 * In variable (array) $moreData you may get this information:
 * 1. reference_1
 * 2. reference_1_label
 * 3. reference_2
 * 4. reference_2_label
 * 5. amount
 * 6. description
 * 7. id // bill_id
 * 8. name
 * 9. email
 * 10. paid
 * 11. collection_id
 * 12. due_at
 * 13. mobile
 * 14. url
 * 15. callback_url
 * 16. redirect_url
 */
