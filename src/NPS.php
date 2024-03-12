<?php

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;
use NPServices\NpsSoapPackage\AvailabilityResponse;
use NPServices\NpsSoapPackage\OrderResponse;
use NPServices\NpsSoapPackage\ListOfMakerCodes;
use InvalidArgumentException;

class NPS extends \SoapClient
{

    public function __construct(string $wsdl, string $id, string $password) {

        // The Id need to be alphanumeric
        if (!ctype_alnum($id)) {
            throw new InvalidArgumentException("The ID must be alphanumeric");
        } else {
            $this->id = $id;
            $this->password = $password;
            $this->error = '';
        }

        // Default options WSDL
        $options = [
            'cache_wsdl' => 0,
            'trace' => 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ])
        ];
        parent::__construct($wsdl, $options);
    }

    // Webservice Method to question NPS stocks 
    public function getAvailability(string $refRequest, array $itemsList)
    {
        if ($this->verifyItemsList($itemsList)) {
            usort($itemsList, function($a, $b) {
                return $a['positionNumber'] - $b['positionNumber'];
            });
            $body = [
                'customerId' => $this->id,
                'password' => $this->password,
                'reference' => $refRequest,
                'items' => $itemsList
            ];
            $response = $this->__soapCall('getAvailability', $body);
            return new AvailabilityResponse($response);
        } else {
            return ['ERROR' => $this->error];
        }
    }

    // Webservice Method to create an order 
    public function createOrder(
        string $customerId,
        string $contact,
        string $phone,
        string $email,
        string $reference,
        array $entries,
        array $billingAddress,
        array $shippingAddress,
        string $deliveryId,
        bool $express
    ){
        if (
            $this->verifyItemsList($entries) &&
            $this->checkCustomerId($customerId) &&
            $this->checkDeliveryId($deliveryId) &&
            $this->checkCustomerAddress($shippingAddress) &&
            $this->checkCustomerAddress($billingAddress)
        ) {
            $body = [
                'customerId' => $this->id,
                'password' => $this->password,
                'order' => [
                    'id' => '',
                    'customerId' => $customerId,
                    'contact' => $contact,
                    'phone' => $phone,
                    'email' => $email,
                    'reference' => $reference,
                    'entries' => $entries,
                    'billingAddress' => $billingAddress,
                    'shippingAddress' => $shippingAddress,
                    'deliveryId' => $deliveryId,
                    'express' => $express,
                ],
            ];
            $response = $this->__soapCall('createOrder', $body);
            return new OrderResponse($response);
        } else {
            return ['ERROR' => $this->error];
        }
    }

    public function getMakerCodes()
    {
        return new ListOfMakerCodes($this->__soapCall('getMakerCode', []));
    }

    // Params verification on items list
    protected function verifyItemsList($itemsList)
    {
        // the items list can't be empty
        if ($itemsList) {
            foreach ($itemsList as $item) {

                // Verify array with item informations
                $clesAttendues = array_flip(['reference', 'makerCode', 'positionNumber', 'requestedQuantity']);
                $difference = array_diff_key($clesAttendues, $item);

                if(empty($difference)) {
                    // Need to have a reference and reference need to be alphanumeric
                    if(!$item['reference']){ 
                        $this->error = 'The reference is invalid';
                    }
                    // Need to have a makerCode and makerCode need to exists
                    if(!$item['makerCode'] || !$this->isMakerCodeExist($item['makerCode']) ) {
                        $this->error = "The 'makerCode' field is invalid";
                    }
                    // Need to have a positionNumber and positionNumber need to be an integer
                    if(!$item['positionNumber'] || !is_int($item['positionNumber'])) {
                        $this->error = "The 'positionNumber' field is invalid";
                    }
                    // requestedQuantity need to be an integer
                    if(!is_int($item['requestedQuantity'])) {
                        $this->error = "The 'requestedQuantity' field is invalid";
                    }

                    // requestedQuantity can't be equal to 0
                    if($item['requestedQuantity'] == 0) {
                        $this->error = "The 'requestedQuantity' field only accepts numbers greater than 0";
                    }
                } else {
                    $missingFieldsImplode = implode("', '", array_keys($difference));
                    $this->error = "'{$missingFieldsImplode}' field(s) are missing from the items list";
                }
            }
        } else {
            $this->error = "The items list can not be empty";
        }

        if ($this->error) {
            return false;
        } else {
            return true;
        }
    }

    // Verify if makerCode exist
    protected function isMakerCodeExist(string $makerCode) {
        $makerCodes = $this->getMakerCodes();
        if($makerCode == '*') return true;
        foreach ($makerCodes->getMakerCodes() as $element) {
            if($element->getMakerCode() == $makerCode) {
                return true;
            }
        }

        return false;
    }

    // CustomerId verification to pass an order
    protected function checkCustomerId($testId)
    {
        $pattern = '/^C(01|ND)[0-9A-Z]{5}$/';
        if(!preg_match($pattern, $testId)) {
            $this->error = "The 'customerId' field is invalid";
        }
        
        if ($this->error) {
            return false;
        } else {
            return true;
        }
    }

    // DeliveryId verification to pass an order
    protected function checkDeliveryId($testId)
    {
        $pattern = '/^(C\d{2}|LIV[A-Z]{2}|L\d{4})$/';
        if(!preg_match($pattern, $testId)) {
            $this->error = "The 'deliveryId' field is invalid";
        }
        
        if ($this->error) {
            return false;
        } else {
            return true;
        }
    }

    // Verify array with address informations
    protected function checkCustomerAddress(array $addressInfos)
    {
        $clesAttendues = array_flip(['societe', 'name1', 'name2', 'street1', 'street2', 'postalCode', 'city', 'countryIsoCode', 'countryName']);

        $difference = array_diff_key($clesAttendues, $addressInfos);

        if(!empty($difference)) {
            $missingFieldsImplode = implode("', '", array_keys($difference));
            $this->error = "'{$missingFieldsImplode}' field(s) are missing from one of your addresses";
        }

        if ($this->error) {
            return false;
        } else {
            return true;
        }
    }
    
}