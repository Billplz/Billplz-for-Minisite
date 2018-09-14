<?php
/**
 * Instruction:
 *
 * 1. Replace the APIKEY with your API Key.
 * 2. OPTIONAL: Replace the COLLECTION with your Collection ID.
 * 3. Replace the X_SIGNATURE with your X Signature Key
 * 4. Replace the http://www.google.com/ with your FULL PATH TO YOUR WEBSITE. It must be end with trailing slash "/".
 * 5. Replace the http://www.google.com/success.html with your FULL PATH TO YOUR SUCCESS PAGE. *The URL can be overridden later
 * 6. OPTIONAL: Set $amount value.
 * 7. OPTIONAL: Set $fallbackurl if the user are failed to be redirected to the Billplz Payment Page.
 *
 */
$api_key = 'APIKEY';
$collection_id = 'COLLECTION';
$x_signature = 'X_SIGNATURE';

$websiteurl = 'http://www.google.com';
$successpath = 'http://www.google.com/success.html';
$amount = ''; //Example (RM13.50): $amount = '1350';
$fallbackurl = ''; //Example: $fallbackurl = 'http://www.google.com/pay.php';
$description = 'PAYMENT DESCRIPTION';
$reference_1_label = '';
$reference_2_label = '';
