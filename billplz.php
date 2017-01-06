<?php

/*
 * How to use?
 *  require_once('billplz.php');
 *  $obj = new billplz;
 *  $obj->setCollection('collectionid');
 *  $obj->setName('name');
 *  $obj->setEmail('email');
 *  $obj->setMobile('mobile');
 *  $obj->setAmount('amount');
 *  $obj->setDeliver(false);
 *  $obj->setReference_1('reference 1');
 *  $obj->setDescription('description');
 *  $obj->setPassbackURL('redirect','callback');
 *  $obj->create_bill('apikey', 'Production');
 *  $obj->getURL();
 */

class billplz {

    var $array, $obj, $auto_submit, $url, $id;

    public function __construct() {
        $this->array = array();
        $this->obj = new curlaction;
    }

    //--------------------------------------------------------------------------
    // Direct Use
    public function check_apikey_collectionid($api_key, $collection_id, $mode) {
        $array = array(
            'collection_id' => $collection_id,
            'email' => 'aa@gmail.com',
            'description' => 'test',
            'mobile' => '60145356443',
            'name' => "Jone Doe",
            'amount' => 150, // RM20
            'callback_url' => "http://yourwebsite.com/return_url"
        );
        $this->obj->setAPI($api_key);
        $this->obj->setAction('CREATE');
        $this->obj->setURL($mode);
        $data = $this->obj->curl_action($array);
        if (isset($data['error']['type'])) {
            return false;
        } elseif (isset($data['url'])) {
            $this->obj->setAction('DELETE');
            $this->obj->setURL($mode, $data['id']);
            $this->obj->curl_action();
            return true;
        }
    }

    //------------------------------------------------------------------------//
    // Indirect Use
    public function checkMobileNumber($mobile) {
        $mobile = preg_replace("/[^0-9]/", "", $mobile);
        $custTel = $mobile;
        $custTel2 = substr($mobile, 0, 1);
        if ($custTel2 == '+') {
            $custTel3 = substr($mobile, 1, 1);
            if ($custTel3 != '6') {
                $custTel = "+6" . $mobile;
            }
        } else if ($custTel2 == '6') {
            
        } else {
            if ($custTel != '') {
                $custTel = "+6" . $mobile;
            }
        } return $custTel;
    }

    //------------------------------------------------------------------------//
    // Direct Use

    public function setCollection($collection_id) {
        $this->array['collection_id'] = $collection_id;
        return $this;
    }

    public function setName($name) {
        $this->array['name'] = $name;
        return $this;
    }

    public function setEmail($email) {
        $this->array['email'] = $email;
        return $this;
    }

    public function setMobile($mobile) {
        $this->array['mobile'] = $this->checkMobileNumber($mobile);
        return $this;
    }

    public function setAmount($amount) {
        $this->array['amount'] = $amount * 100;
        return $this;
    }

    public function setDeliver($deliver) {
        $this->array['deliver'] = $deliver;
        return $this;
    }

    public function setReference_1($reference_1) {
        $this->array['reference_1'] = $reference_1;
        return $this;
    }

    public function setDescription($description) {
        $this->array['description'] = substr($description, 0, 199);
        return $this;
    }

    public function setPassbackURL($redirect_url, $callback_url) {
        $this->array['redirect_url'] = $redirect_url;
        $this->array['callback_url'] = $callback_url;
        return $this;
    }
    
    public function setReference_1_Label($label){
        $this->array['reference_1_label'] = $label;
        return $this;
    }

    public function create_bill($api_key, $mode) {
        
        $this->obj->setAPI($api_key);
        $this->obj->setAction('CREATE');
        $this->obj->setURL($mode);
        $data = $this->obj->curl_action($this->array);
        
        if (isset($data['error'])) {
            unset($this->array['mobile']);
            $data = $this->obj->curl_action($this->array);
            $this->url = $data['url'];
        }
        $this->url = $data['url'];
        $this->id = $data['id'];
        return $this;
    }

    public function getURL() {
        return $this->url;
    }

    public function getID() {
        return $this->id;
    }

    //------------------------------------------------------------------------//
    // Direct Use
    public function check_bill($api_key, $bill_id, $mode) {
        $this->obj->setAPI($api_key);
        $this->obj->setAction('CHECK');
        $this->obj->setURL($mode, $bill_id);
        $data = $this->obj->curl_action();
        return $data;
    }

}

class curlaction {
    /*
     * How to use?
     *  Create Object: $obj = new curlaction;
     *  Set API Key:   $obj->setAPI('apikey');
     *  Set  Action:   $obj->setAction('CREATE');
     *  Set    Mode:   $obj->setURL('Production','');
     *  Create Bill:   $obj->curl_action(array());
     *  Remove Bill:   $obj->setAction('DELETE');
     *                 $obj->setURL('Production','billid');
     *                 $obj->curl_action('');
     *  Destruct:      unset($obj);
     */

    var $url, $action, $curldata, $api_key;
    public static $production = 'https://www.billplz.com/api/v3/';
    public static $staging = 'https://billplz-staging.herokuapp.com/api/v3/';

    public function setAPI($api_key) {
        $this->api_key = $api_key;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setURL($mode, $id = '') {
        if ($mode == 'Staging') {
            $this->url = self::$staging;
        } else {
            $this->url = self::$production;
        }
        if ($this->action == 'DELETE' || $this->action == 'CHECK') {
            $this->url.='bills/' . $id;
        } elseif ($this->action == 'CREATE') {
            $this->url.='bills/';
        } else { //COLLECTIONS
            $this->url.='collections/';
        }
        return $this;
    }

    public function curl_action($data = '') {
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $this->url);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERPWD, $this->api_key . ":");
        if ($this->action == 'DELETE') {
            curl_setopt($process, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        if ($this->action == 'CREATE' || $this->action == 'COLLECTIONS') {
            curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $return = curl_exec($process);
        curl_close($process);
        $this->curldata = json_decode($return, true);
        return $this->curldata;
    }

}

/*
 $obj = new billplz;
 $obj->setCollection('ugo_7dit')
 ->setName('Wan Zulkarnain')
 ->setEmail('wanzulkarnain69@gmail.com')
 ->setMobile('0145356443')
 ->setAmount('300')
 ->setDeliver(false)
 ->setReference_1('30')
 ->setDescription('Ntoh la nak')
 ->setPassbackURL('http://google.com/','http://google.com/')
 ->create_bill('ed586547-00b7-459a-a02e-7e876a744590', 'Staging');
 echo $obj->getURL();
*/
