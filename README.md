# Billplz-for-Minisite

Integrate Billplz with your Minisite written with HTML code. 

# Minimum Server Requirement

1. Linux Server Environment
2. PHP version 5.5 and above (Tested with PHP 7.0)

# Configuration

Download this Repository and upload the individual files to your minisite directory. (It can be same directory with your Minisite or it can be in subdirectory which is Recommended)

Open configuration.php file with your favourite text editor and edit the following information:
*Replace with your values in the single quotes*

  1. 'APIKEY' => 'Your API Key here'
  2. 'COLLECTION' => 'You Collection ID here'
  3. 'http://www.google.com/' => 'The full URL to your minisite or the full URL to this script subdirectory'
  4. 'http://www.google.com/success.html' => 'The full URL to redirect your payee after successful payment'
  5. 'Production' => 'Leave it as Production or change it to Staging if you are using API Key from billplz staging'

For integration with Affiliate Pro software: **(Optional)**

  1. Include the tracking code on your minisite or the form if you separate the form from the minisite homepage
  
  **include('affiliate-pro/controller/affiliate-tracking.php');**
  
  2. Include the tracking the tracking in file **verifytrans.php**
  
  **Line 31: include('affiliate-pro/controller/record-sale.php');**

# How to use

You need to have a form which collect and pass the input to the script.

- Information that you can collect from using the HTML Form

  1. Payer Name => **Mandatory**
  2. Payer Email => **Mandatory**
  3. Payer Mobile Phone Number => Optional
  4. Amount => **Mandatory**
  5. Notification => Optional
  6. Reference Label => Optional
  7. Reference Data => Optional
  8. Payment Description => **Mandatory**
  
- The HTML Form input name must be according to the name below:

  1. Payer Name => **nama**
  2. Payer Email => **email**
  3. Payer Mobile Phone Number => telefonbimbit 
  4. Amount => **amaun**
  5. Notification => deliver
  6. Reference Label => reference_label
  7. Reference Data => reference_1
  8. Payment Description => description
  
- Use input method="post" and action to file "billplzpost.php"

# Form Example:

- Please refer to index.php file

# Issues?

Check the following:
  1. API Key
  2. Collection ID
  3. Mode (IF YOU ARE USING PRODUCTION API KEY, PLEASE USE "Production". ELSE, PLEASE USE "Staging"
  4. You form method is set to POST and action is set to the correct file "billplzpost.php"
 
 Please email to me: wan@wanzul-hosting.com
 
 # Donation
 
 You can support this project by donation: www.wanzul.net/donate
