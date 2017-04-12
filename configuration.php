<?php
/**
 * Instruction: 
 *  
 * 1. Replace the APIKEY with your API Key.
 * 2. Replace the COLLECTION with your CollectionID.
 * 3. Replace the X_SIGNATURE with your X Signature Key
 * 4. No need to change $mode (Default: 'Production'). Change to 'Staging' only if you want to test with
 *      https://billplz-staging.herokuapp.com
 * 5. Replace the http://www.google.com/ with your FULL PATH TO YOUR WEBSITE. It must be end with trailing slash "/".
 * 6. Replace the http://www.google.com/success.html with your FULL PATH TO YOUR SUCCESS PAGE. *The URL can be overriden later
 * 7. OPTIONAL: Set $amount value.
 * 8. OPTIONAL: Set $fallbackurl if the user are failed to be redirected to the Billplz Payment Page.
 * 
 */
$api_key = 'APIKEY';
$collection_id = 'COLLECTION';
$x_signature = 'X_SIGNATURE';
$mode = 'Production';

$websiteurl = 'http://www.google.com/';
$successpath = 'http://www.google.com/success.html';
$amount = ''; //Example (RM13.50): $amount = '13.50';
$fallbackurl = ''; //Example: $fallbackurl = 'http://www.google.com/pay.php';
