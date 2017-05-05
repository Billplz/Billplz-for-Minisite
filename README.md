# Billplz for Minisite

Integrate Billplz with your Minisite written with HTML code. 

# Minimum Server Requirement

1. Linux Server Environment
2. PHP version 5.5 and above (Tested with PHP 7.0)

# Configuration

Download this Repository and upload the individual files to your minisite directory. (It can be same directory with your Minisite or it can be in subdirectory which is **Recommended**)

Open configuration.php file with your favourite text editor and edit the following information:

*Replace with your values in the single quotes*

  * 'APIKEY' => 'Your API Key here'
  * **Optional** 'COLLECTION' => 'Your Collection ID here'
  * 'X_SIGNATURE' => 'Your X Signature Key here'
  * 'http://www.google.com/' => 'The full URL to your minisite or the full URL to this script subdirectory.
  * 'http://www.google.com/success.html' => 'The full URL to redirect your payee after successful payment'. *It can be overriden later*
  *  **Optional**: Set $fallbackurl value  in the event of failure to redirect user to payment page
  *  **Optional**: Set $amount value for fixed payment value. Useful to avoid user setting their own price

---

For integration with Affiliate Pro software: **(Optional)**

  1. Include the tracking code in file **billplzpost.php** after // Include tracking code here
  
  **Line 226:** 
  <pre>
  include('affiliate-pro/controller/affiliate-tracking.php');
  </pre>
  
  2. Include the tracking code in file **verifytrans.php** after // Include tracking code here
  
  **Line 40:**
  <pre>
  $sale_amount = number_format((float)($this->data['amount']/100), 2, '.', '');
  $product = $this->data['description'];**
  global $commission;**
  include('affiliate-pro/controller/record-sale.php');**
  </pre>
  
  3. Insert the code below in **configuration.php** after $fallbackurl = ''; and replace '30' with your own value
  
  **Line 22:**
  <pre>
  $commission = '30';
  </pre>
  
---

# How to use

You need to have a form which collect and pass the input to the script.

- Information that you can collect from using the HTML Form

  1. Payer Name => **Mandatory**
  2. Payer Email => **Mandatory**
  3. Payer Mobile Phone Number => Optional
  4. Amount => **Mandatory** (NOT REQUIRED: If $amount value has been set at Configuration stage)
  5. Notification => Optional
  6. Reference 1 Label => Optional
  7. Reference 1 Data => Optional
  8. Reference 2 Label => Optional
  9. Reference 2 Data => Optional
  10. Payment Description => **Mandatory**
  11. Success URL => Optional
  12. Collection ID => Optional
  
- The HTML Form input name must be according to the name below:

  1. Payer Name => **nama**
  2. Payer Email => **email**
  3. Payer Mobile Phone Number => telefonbimbit 
  4. Amount => **amaun**
  5. Notification => deliver
  6. Reference 1 Label => reference_label_1
  7. Reference 1 Data => reference_1
  8. Reference 2 Label => reference_label_2
  9. Reference 2 Data => reference_2
  10. Payment Description => **description**
  11. Success URL => successpath
  12. Collection ID => collection_id
  
- Use input method="post" and action to file "billplzpost.php"

# Form Example:

- Please refer to index.php file

# Issues?

- Check the following
  1. API Key
 
- Please email to me: wan@wanzul-hosting.com 

# Donation
 
 You can support this project by donation: www.wanzul.net/donate
