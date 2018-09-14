<?php

namespace Billplz\Minisite;

class API
{
    private $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function setConnect($connect)
    {
        $this->connect = $connect;
    }

    /**
     * This method is to change the URL to staging if failed to authenticate with production.
     * It will recall the method that has executed previously to perform it in staging.
     */
    private function detectMode($method_name, $response, $parameter = '', $optional = '', $extra = '')
    {
        if ($response[0] === 401 && $this->connect->detect_mode) {
            $this->connect->detect_mode = false;
            $this->connect->setMode(false);
            if (!empty($extra)) {
                return $this->{$method_name}($parameter, $optional, $extra);
            } elseif (!empty($optional)) {
                return $this->{$method_name}($parameter, $optional);
            } elseif (!empty($parameter)) {
                return $this->{$method_name}($parameter);
            } else {
                return $this->{$method_name}();
            }
        }
        return false;
    }

    public function getCollectionIndex($parameter = array())
    {
        $response = $this->connect->getCollectionIndex($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function createCollection($parameter, $optional = array())
    {
        $response = $this->connect->createCollection($parameter, $optional);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter, $optional)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getCollection($parameter)
    {
        $response = $this->connect->getCollection($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function createOpenCollection($parameter, $optional = array())
    {
        $parameter['title'] = substr($parameter['title'], 0, 49);
        $parameter['description'] = substr($parameter['description'], 0, 199);

        if (intval($parameter['amount']) > 999999999) {
            throw new \Exception("Amount Invalid. Too big");
        }

        $response = $this->connect->createOpenCollection($parameter, $optional);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter, $optional)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getOpenCollection($parameter)
    {
        $response = $this->connect->getOpenCollection($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getOpenCollectionIndex($parameter = array())
    {
        $response = $this->connect->getOpenCollectionIndex($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function createMPICollection($parameter)
    {
        $response = $this->connect->createMPICollection($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getMPICollection($parameter)
    {
        $response = $this->connect->getMPICollection($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function createMPI($parameter, $optional = array())
    {
        $response = $this->connect->createMPI($parameter, $optional);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter, $optional)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getMPI($parameter)
    {
        $response = $this->connect->getMPI($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function deactivateCollection($parameter)
    {
        $response = $this->connect->deactivateCollection($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function activateCollection($parameter)
    {
        $response = $this->connect->deactivateCollection($parameter, 'activate');
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function createBill($parameter, $optional = array(), $sendCopy = '')
    {
        /* Email or Mobile must be set */
        if (empty($parameter['email']) && empty($parameter['mobile'])) {
            throw new \Exception("Email or Mobile must be set!");
        }

        /* Manipulate Deliver features to allow Email/SMS Only copy */
        if ($sendCopy === '0') {
            $optioonal['deliver'] = 'false';
        } elseif ($sendCopy === '1' && !empty($parameter['email'])) {
            $optional['deliver'] = 'true';
            unset($parameter['mobile']);
        } elseif ($sendCopy === '2' && !empty($parameter['mobile'])) {
            $optional['deliver'] = 'true';
            unset($parameter['email']);
        } elseif ($sendCopy === '3') {
            $optional['deliver'] = 'true';
        }

        /* Validate Mobile Number first */
        if (!empty($parameter['mobile'])) {
            /* Strip all unwanted character */
            $parameter['mobile'] = preg_replace('/[^0-9]/', '', $parameter['mobile']);

            /* Add '6' if applicable */
            $parameter['mobile'] = $parameter['mobile'][0] === '0' ? '6'.$parameter['mobile'] : $parameter['mobile'];

            /* If the number doesn't have valid formatting, reject it */
            /* The ONLY valid format '<1 Number>' + <10 Numbers> or '<1 Number>' + <11 Numbers> */
            /* Example: '60141234567' or '601412345678' */
            if (!preg_match('/^[0-9]{11,12}$/', $parameter['mobile'], $m)) {
                $parameter['mobile'] = '';
            }
        }

        /* Create Bills */
        $bill = $this->connect->createBill($parameter, $optional);
        if ($bill[0] === 200) {
            return $bill;
        }

        /* Determine if the API Key is belong to Staging */
        if ($detect_mode = $this->detectMode(__FUNCTION__, $bill, $parameter, $optional, $sendCopy)) {
            return $detect_mode;
        }

        /* Check if Failed caused by wrong Collection ID */
        $collection = $this->toArray($this->getCollection($parameter['collection_id']));

        /* If doesn't exists or belong to another merchant */
        /* + In-case the collection id is an empty string */
        if ($collection[0] === 404 || $collection[0] === 401 || empty($parameter['collection_id'])) {
            /* Get All Active & Inactive Collection List */
            $collectionIndexActive = $this->toArray($this->getCollectionIndex(array('page'=>'1', 'status'=>'active')));
            $collectionIndexInactive = $this->toArray($this->getCollectionIndex(array('page'=>'1', 'status'=>'inactive')));

            /* If Active Collection not available but Inactive Collection is available */
            if (empty($collectionIndexActive[1]['collections']) && !empty($collectionIndexInactive[1]['collections'])) {
                /* Use inactive collection */
                $parameter['collection_id'] = $collectionIndexInactive[1]['collections'][0]['id'];
            }

            /* If there is Active Collection */
            elseif (!empty($collectionIndexActive[1]['collections'])) {
                $parameter['collection_id'] = $collectionIndexActive[1]['collections'][0]['id'];
            }

            /* If there is no Active and Inactive Collection */
            else {
                $collection = $this->toArray($this->createCollection('Payment for Purchase'));
                $parameter['collection_id'] = $collection[1]['id'];
            }
        } else {
            return $bill;
        }

        /* Create Bills */
        return $this->connect->createBill($parameter, $optional);
    }

    public function deleteBill($parameter)
    {
        $response = $this->connect->deleteBill($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getBill($parameter)
    {
        $response = $this->connect->getBill($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function bankAccountCheck($parameter)
    {
        $response = $this->connect->bankAccountCheck($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getTransactionIndex($id, $parameter = array('page'=>'1'))
    {
        $response = $this->connect->getTransactionIndex($id, $parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $id, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getPaymentMethodIndex($parameter)
    {
        $response = $this->connect->getPaymentMethodIndex($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function updatePaymentMethod($parameter)
    {
        $response = $this->connect->updatePaymentMethod($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getBankAccountIndex($parameter = array('account_numbers'=>['0','1']))
    {
        $response = $this->connect->getBankAccountIndex($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function getBankAccount($parameter)
    {
        $response = $this->connect->getBankAccount($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function createBankAccount($parameter)
    {
        $response = $this->connect->createBankAccount($parameter);
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response, $parameter)) {
            return $detect_mode;
        }
        return $response;
    }

    public function bypassBillplzPage($bill)
    {
        $bills = \json_decode($bill, true);
        if ($bills['reference_1_label']!=='Bank Code') {
            return \json_encode($bill);
        }

        $fpxBanks = $this->toArray($this->getFpxBanks());
        if ($fpxBanks[0] !== 200) {
            return \json_encode($bill);
        }

        $found = false;
        foreach ($fpxBanks[1]['banks'] as $bank) {
            if ($bank['name'] === $bills['reference_1']) {
                if ($bank['active']) {
                    $found = true;
                    break;
                }
                return \json_encode($bill);
            }
        }

        if ($found) {
            $bills['url'].='?auto_submit=true';
        }

        return json_encode($bills);
    }

    public function getFpxBanks()
    {
        $response = $this->connect->getFpxBanks();
        if ($detect_mode = $this->detectMode(__FUNCTION__, $response)) {
            return $detect_mode;
        }
        return $response;
    }

    public function toArray($json)
    {
        return $this->connect->toArray($json);
    }
}
