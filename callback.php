<?php

require_once 'billplz.php';
require_once 'configuration.php';

/*
 * Get Data. Die if input is tempered or X Signature not enabled
 */
$data = billplz::getCallbackData($x_signature);
$tranID = $data['id'];

$billplz = new billplz;
$moreData = $billplz->check_bill($api_key, $tranID, $mode);

/*
 * Dalam variable $moreData ada maklumat berikut (array):
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
 * 
 * Contoh untuk akses data email: $moreData['email'];
 * 
 * Dalam variable $data ada maklumat berikut:
 * 1. x_signature
 * 2. id // bill_id
 * 3. paid
 * 4. paid_at
 * 5. amount
 * 6. collection_id
 * 7. due_at
 * 8. email
 * 9. mobile
 * 10. name
 * 11. paid_at
 * 12. state
 * 13. url
 * 
 * Contoh untuk ases data bill_id: $data['id']
 * 
 */

/*
 * Jika bayaran telah dibuat
 */
if ($data['paid']) {
    
}
/*
 * Jika bayaran tidak dibuat
 */ else {
    
}
