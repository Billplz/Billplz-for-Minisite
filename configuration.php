<?php
/**
 * Instruction: 
 *  
 * 1. Replace the APIKEY with your API Key
 * 2. Replace the COLLECTION with your CollectionID
 * 3. Replace the http://www.google.com/ with your FULL PATH TO YOUR WEBSITE. It must be end with trailing slash "/"
 * 4. Replace the http://www.google.com/success.html with your FULL PATH TO YOUR SUCCESS PAGE. 
 * 5. No need to change $mode (Default: 'Production'). Change to 'Staging' only if you want to test with
 *      https://billplz-staging.herokuapp.com
 * 6. OPTIONAL: Set $fallbackurl if the user are failed to be redirected to the Billplz Payment Page
 * 
 */
$api_key = 'APIKEY';
$collection_id = 'COLLECTION';
$websiteurl = 'http://www.google.com/';

$successpath = 'http://www.google.com/success.html';
$mode = 'Production';

$fallbackurl = '';
