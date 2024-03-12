<?php 

/*
 * Copyright (C) 2024 Nippon pieces services
*/

namespace NPServices\NpsSoapPackage;
use NPServices\NpsSoapPackage\ItemResponse;
use NPServices\NpsSoapPackage\CustomerAddressResponse;

class OrderResponse {
    private $id;
    private $customerId;
    private $contact;
    private $phone;
    private $email;
    private $createdAt;
    private $reference;
    private $entries = [];
    private $billingAddress;
    private $shippingAddress;
    private $deliveryId;
    private $express;

    public function __construct($data) {
        $this->id = $data->id;
        $this->customerId = $data->customerId;
        $this->contact = $data->contact;
        $this->phone = $data->phone;
        $this->email = $data->email;
        $this->createdAt = $data->createdAt;
        $this->reference = $data->reference;
        foreach ($data->entries as $entry) {
            $this->entries[] = new ItemResponse($entry);
        }
        $this->billingAddress = new CustomerAddressResponse($data->billingAddress);
        $this->shippingAddress = new CustomerAddressResponse($data->shippingAddress);
        $this->deliveryId = $data->deliveryId;
        $this->express = $data->express;
    }

    public function getId() {
        return $this->id;
    }
    public function getCustomerId() {
        return $this->customerId;
    }
    public function getContact() {
        return $this->contact;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getCreatedAt() {
        return $this->createdAt;
    }
    public function getReference() {
        return $this->reference;
    }
    public function getEntries() {
        return $this->entries;
    }
    public function getBillingAddress() {
        return $this->billingAddress;
    }
    public function getShippingAddress() {
        return $this->shippingAddress;
    }
    public function getDeliveryId() {
        return $this->deliveryId;
    }
    public function getExpress() {
        return $this->express;
    }
}